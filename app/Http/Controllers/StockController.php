<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use App\Models\StockTransaction;
use Exception;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        try {
            $stocks = Stock::with([
                'product' => function($query) {
                    $query->withSum(['returnItems as damaged_quantity' => function($q) {
                        $q->where('condition', 'damaged');
                    }], 'quantity');
                },
                'branch', 'brand', 'category', 'subcategory'
            ])->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Stocks retrieved successfully',
                'data' => $stocks
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve stocks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            if(auth()->user()->role === 'admin' || auth()->user()->role === 'employee') {

                
                $data = $request->validate([
                    'product_id' => 'required|exists:products,product_id',
                    'branch_id' => 'required|exists:branches,branch_id',
                    'brand_id' => 'required|exists:brands,brand_id',
                    'category_id' => 'required|exists:categories,category_id',
                    'subcategory_id' => 'required|exists:sub_categories,subcategory_id',
                    'product_name' => 'required|string|max:255',
                    'quantity' => 'required|integer|min:0',
                    'minimum_stock_level' => 'nullable|integer|min:0',
                    'maximum_stock_level' => 'nullable|integer|min:0',
                    'last_restocked_date' => 'nullable|date',
                    'location_in_warehouse' => 'nullable|string|max:255',
                ]);

                // Check if stock already exists with same brand, subcategory, category, and product name
                $existingStock = Stock::where('brand_id', $data['brand_id'])
                                     ->where('subcategory_id', $data['subcategory_id'])
                                     ->where('category_id', $data['category_id'])
                                     ->where('product_name', $data['product_name'])
                                     ->where('branch_id', $data['branch_id'])
                                     ->first();

                if ($existingStock) {
                    // Auto increment quantity if same item exists
                    DB::beginTransaction();
                    
                    $quantityBefore = $existingStock->quantity;
                    $existingStock->quantity += $data['quantity'];
                    $existingStock->last_restocked_date = now();
                    $existingStock->save();

                    // Record transaction
                    StockTransaction::create([
                        'stock_id' => $existingStock->stock_id,
                        'product_id' => $existingStock->product_id,
                        'branch_id' => $existingStock->branch_id,
                        'brand_id' => $existingStock->brand_id,
                        'category_id' => $existingStock->category_id,
                        'subcategory_id' => $existingStock->subcategory_id,
                        'product_name' => $existingStock->product_name,
                        'transaction_type' => 'in',
                        'quantity_before' => $quantityBefore,
                        'quantity_changed' => $data['quantity'],
                        'quantity_after' => $existingStock->quantity,
                        'notes' => 'Auto increment on duplicate stock creation',
                        'created_by' => auth()->id(),
                    ]);
                    
                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => 'Stock quantity increased for existing item',
                        'data' => $existingStock,
                        'action' => 'incremented'
                    ], 200);
                }

                // Create new stock if doesn't exist
                $data['last_restocked_date'] = now();
                $stock = Stock::create($data);

                // Record initial stock transaction
                StockTransaction::create([
                    'stock_id' => $stock->stock_id,
                    'product_id' => $stock->product_id,
                    'branch_id' => $stock->branch_id,
                    'brand_id' => $stock->brand_id,
                    'category_id' => $stock->category_id,
                    'subcategory_id' => $stock->subcategory_id,
                    'product_name' => $stock->product_name,
                    'transaction_type' => 'initial',
                    'quantity_before' => 0,
                    'quantity_changed' => $data['quantity'],
                    'quantity_after' => $data['quantity'],
                    'notes' => 'Initial stock creation',
                    'created_by' => auth()->id(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Stock created successfully',
                    'data' => $stock,
                    'action' => 'created'
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null,
                ], 403);
            }
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add items to stock (increment quantity)
     */
    public function addStock(Request $request, $id)
    {
        try {
            if(auth()->user()->role === 'admin' || auth()->user()->role === 'employee') {
                
                $data = $request->validate([
                    'quantity' => 'required|integer|min:1',
                ]);

                $stock = Stock::find($id);
                
                if (!$stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock not found',
                        'data' => null
                    ], 404);
                }

                DB::beginTransaction();

                $quantityBefore = $stock->quantity;
                $stock->quantity += $data['quantity'];
                $stock->last_restocked_date = now();
                $stock->save();

                // Record transaction
                StockTransaction::create([
                    'stock_id' => $stock->stock_id,
                    'product_id' => $stock->product_id,
                    'branch_id' => $stock->branch_id,
                    'brand_id' => $stock->brand_id,
                    'category_id' => $stock->category_id,
                    'subcategory_id' => $stock->subcategory_id,
                    'product_name' => $stock->product_name,
                    'transaction_type' => 'in',
                    'quantity_before' => $quantityBefore,
                    'quantity_changed' => $data['quantity'],
                    'quantity_after' => $stock->quantity,
                    'notes' => 'Stock added via addStock method',
                    'created_by' => auth()->id(),
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Stock quantity increased successfully',
                    'data' => $stock,
                    'previous_quantity' => $quantityBefore,
                    'added_quantity' => $data['quantity'],
                    'new_quantity' => $stock->quantity
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null,
                ], 403);
            }
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove items from stock using product_id (decrement quantity)
     */
    public function removeStock(Request $request, $id)
    {
        try {
            

            if(auth()->user()->role === 'admin' || auth()->user()->role === 'employee') {
                
                $data = $request->validate([
                    'quantity' => 'required|integer|min:1',
                ]);

                // Find stock by product_id
                $stock = Stock::where('stock_id', $id)->first();

                
                if (!$stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock not found for this product',
                        'data' => null
                    ], 404);
                }

                if ($stock->quantity < $data['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock quantity',
                        'data' => [
                            'available_quantity' => $stock->quantity,
                            'requested_quantity' => $data['quantity'],
                            'shortage' => $data['quantity'] - $stock->quantity
                        ]
                    ], 400);
                }

                DB::beginTransaction();

                $previousQuantity = $stock->quantity;
                $stock->quantity -= $data['quantity'];
                $stock->save();

                // Record transaction
                StockTransaction::create([
                    'stock_id' => $stock->stock_id,
                    'product_id' => $stock->product_id,
                    'branch_id' => $stock->branch_id,
                    'brand_id' => $stock->brand_id,
                    'category_id' => $stock->category_id,
                    'subcategory_id' => $stock->subcategory_id,
                    'product_name' => $stock->product_name,
                    'transaction_type' => 'out',
                    'quantity_before' => $previousQuantity,
                    'quantity_changed' => -$data['quantity'],
                    'quantity_after' => $stock->quantity,
                    'notes' => 'Stock removed via removeStock method',
                    'created_by' => auth()->id(),
                ]);

                DB::commit();

                // Check if stock is now low
                $isLowStock = $stock->isLowStock();

                return response()->json([
                    'success' => true,
                    'message' => 'Stock quantity decreased successfully',
                    'data' => $stock,
                    'previous_quantity' => $previousQuantity,
                    'removed_quantity' => $data['quantity'],
                    'new_quantity' => $stock->quantity,
                    'low_stock_alert' => $isLowStock
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null,
                ], 403);
            }
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if(auth()->user()->role === 'admin' || auth()->user()->role === 'employee') {
                $stock = Stock::find($id);
                
                if (!$stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock not found',
                        'data' => null
                    ], 404);
                }

                $data = $request->validate([
                    'product_id' => 'nullable|exists:products,product_id',
                    'branch_id' => 'nullable|exists:branches,branch_id',
                    'brand_id' => 'nullable|exists:brands,brand_id',
                    'category_id' => 'nullable|exists:categories,category_id',
                    'subcategory_id' => 'nullable|exists:subcategories,subcategory_id',
                    'product_name' => 'nullable|string|max:255',
                    'quantity' => 'nullable|integer|min:0',
                    'minimum_stock_level' => 'nullable|integer|min:0',
                    'maximum_stock_level' => 'nullable|integer|min:0',
                    'last_restocked_date' => 'nullable|date',
                    'location_in_warehouse' => 'nullable|string|max:255',
                ]);

                $stock->update($data);

                return response()->json([
                    'success' => true,
                    'message' => 'Stock updated successfully',
                    'data' => $stock
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null,
                ], 403);
            }
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            if(auth()->user()->role === 'admin' || auth()->user()->role === 'employee') {
                $stock = Stock::find($id);
                
                if (!$stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock not found',
                        'data' => null
                    ], 404);
                }

                $stock->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Stock deleted successfully',
                    'data' => null
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null,
                ], 403);
            }
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get low stock items
     */
    public function getLowStock()
    {
        try {
            $stocks = Stock::with([
                'product' => function($query) {
                    $query->withSum(['returnItems as damaged_quantity' => function($q) {
                        $q->where('condition', 'damaged');
                    }], 'quantity');
                },
                'branch', 'brand', 'category', 'subcategory'
            ])
            ->whereColumn('quantity', '<=', 'minimum_stock_level')
            ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Low stock items retrieved successfully',
                'count' => $stocks->count(),
                'data' => $stocks
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve low stock items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get stock by branch
     */
    public function getStockByBranch($branch_id)
    {
        try {
            $stocks = Stock::with([
                'product' => function($query) {
                    $query->withSum(['returnItems as damaged_quantity' => function($q) {
                        $q->where('condition', 'damaged');
                    }], 'quantity');
                },
                'branch', 'brand', 'category', 'subcategory'
            ])
            ->where('branch_id', $branch_id)
            ->get();
            $totalQuantity = $stocks->sum('quantity');
            
            return response()->json([
                'success' => true,
                'message' => 'Branch stocks retrieved successfully',
                'total_quantity' => $totalQuantity,
                'data' => $stocks
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve branch stocks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get stock by product
     */
    public function getStockByProduct($product_id)
{
    try {
        $stocks = Stock::with([
            'product' => function($query) {
                $query->withSum(['returnItems as damaged_quantity' => function($q) {
                    $q->where('condition', 'damaged');
                }], 'quantity');
            },
            'branch', 'brand', 'category', 'subcategory'
        ])
        ->where('product_id', $product_id)
        ->get();

        // Calculate total quantity
        $totalQuantity = $stocks->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Product stocks retrieved successfully',
            'total_quantity' => $totalQuantity,
            'data' => $stocks
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve product stocks',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Get Bin Card data (transactions filtered by branch, product, and date range)
     */
    public function getBinCard(Request $request)
    {
        try {
            $branchId = $request->query('branch_id');
            $productId = $request->query('product_id');
            $fromDate = $request->query('from_date');
            $toDate = $request->query('to_date');
            $typesString = $request->query('types', 'ALL');
            $types = explode(',', $typesString);

            $query = StockTransaction::with(['stock', 'product', 'branch', 'brand', 'category', 'subcategory', 'creator'])
                                     ->orderBy('created_at', 'desc'); // Newest first

            if ($branchId) {
                $query->where('branch_id', $branchId);
            }

            if ($productId) {
                $query->where('product_id', $productId);
            }

            if ($fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            }

            if ($toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            }

            // Apply Transaction Type Filter (Multi-select)
            if (!in_array('ALL', $types)) {
                $query->where(function($q) use ($types) {
                    foreach ($types as $type) {
                        $q->orWhere(function($sub) use ($type) {
                            if ($type === 'GRN') {
                                $sub->where('notes', 'like', '%GRN%')
                                    ->orWhere('notes', 'like', '%Purchase%')
                                    ->orWhere('transaction_type', 'purchase')
                                    ->orWhere('transaction_type', 'PURCHASE');
                            } elseif ($type === 'TRANSFER') {
                                $sub->where('notes', 'like', '%TRANSFER%');
                            } elseif ($type === 'ADJUSTMENT') {
                                $sub->where(function($inner) {
                                    $inner->where(function($notesSub) {
                                        $notesSub->where('notes', 'not like', '%TRANSFER%')
                                                 ->where('notes', 'not like', '%GRN%')
                                                 ->where('notes', 'not like', '%Purchase%')
                                                 ->orWhereNull('notes');
                                    })
                                    ->whereNotIn('transaction_type', ['purchase', 'PURCHASE']);
                                });
                            }
                        });
                    }
                });
            }

            $transactions = $query->paginate($request->query('per_page', 10));

            // Pre-resolve Supplier Names for GRNs if possible to avoid N+1 queries
            $purchaseIds = collect($transactions->items())
                ->filter(function($t) {
                    $notes = strtoupper($t->notes);
                    return (str_contains($notes, 'GRN') || str_contains($notes, 'PURCHASE')) && !str_contains($notes, 'SUPPLIER');
                })
                ->map(function($t) {
                    if ($t->reference_number) return $t->reference_number;
                    preg_match('/GRN:\s*([A-Za-z0-9-]+)/i', $t->notes, $matches);
                    return $matches[1] ?? null;
                })
                ->filter()
                ->unique()
                ->toArray();

            $purchaseMap = [];
            if (!empty($purchaseIds)) {
                $purchaseMap = \App\Models\Purchase::with('supplier')
                    ->whereIn('purchase_id', $purchaseIds)
                    ->get()
                    ->keyBy('purchase_id');
            }

            // Transform data to match UI expectations while preserving pagination
            $formattedData = collect($transactions->items())->map(function ($t) use ($purchaseMap) {
                $type = strtoupper($t->transaction_type);
                $notes = strtoupper($t->notes);
                $finalNotes = $t->notes;
                
                // Detect Special Types from notes
                if (str_contains($notes, 'TRANSFER')) {
                    $type = (strtoupper($t->transaction_type) === 'IN') ? 'TRANSFER_IN' : 'TRANSFER_OUT';
                } elseif (str_contains($notes, 'GRN') || str_contains($notes, 'PURCHASE')) {
                    $type = 'PURCHASE'; // Will show as GRN in UI
                    
                    // Inject supplier name if missing
                    if (!str_contains($notes, 'SUPPLIER')) {
                        preg_match('/GRN:\s*([A-Za-z0-9-]+)/i', $t->notes, $matches);
                        $pid = $t->reference_number ?? ($matches[1] ?? null);
                        if ($pid && isset($purchaseMap[$pid]) && $purchaseMap[$pid]->supplier) {
                            $finalNotes .= " from Supplier [{$purchaseMap[$pid]->supplier->supplier_name}]";
                        }
                    }
                }

                return [
                    'id' => $t->id,
                    'created_at' => $t->created_at,
                    'type' => $type,
                    'quantity' => abs($t->quantity_changed),
                    'reference_no' => $t->reference_number ?? ($matches[1] ?? null),
                    'notes' => $finalNotes
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Bin card data retrieved successfully',
                'data' => $formattedData,
                'meta' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total()
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve bin card data',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Transfer stock between branches
     */
    public function transferStock(Request $request)
    {
        try {
            $data = $request->validate([
                'product_id' => 'required|exists:products,product_id',
                'from_branch_id' => 'required|exists:branches,branch_id',
                'to_branch_id' => 'required|exists:branches,branch_id|different:from_branch_id',
                'quantity' => 'required|integer|min:1',
                'notes' => 'nullable|string',
                'reference_number' => 'nullable|string'
            ]);

            $fromBranch = Branch::find($data['from_branch_id']);
            $toBranch = Branch::find($data['to_branch_id']);

            DB::beginTransaction();

            // 1. Get Source Stock
            $sourceStock = Stock::where('product_id', $data['product_id'])
                               ->where('branch_id', $data['from_branch_id'])
                               ->first();

            if (!$sourceStock || $sourceStock->quantity < $data['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock in source branch.'
                ], 400);
            }

            // 2. Get or Create Destination Stock
            $destStock = Stock::where('product_id', $data['product_id'])
                             ->where('branch_id', $data['to_branch_id'])
                             ->first();

            if (!$destStock) {
                // Create stock record for destination branch if it doesn't exist
                $destStock = Stock::create([
                    'product_id' => $sourceStock->product_id,
                    'branch_id' => $data['to_branch_id'],
                    'brand_id' => $sourceStock->brand_id,
                    'category_id' => $sourceStock->category_id,
                    'subcategory_id' => $sourceStock->subcategory_id,
                    'product_name' => $sourceStock->product_name,
                    'quantity' => 0,
                    'minimum_stock_level' => $sourceStock->minimum_stock_level ?? 10,
                ]);
            }

            // 3. Perform the movement
            $sourceQtyBefore = $sourceStock->quantity;
            $sourceStock->quantity -= $data['quantity'];
            $sourceStock->save();

            $destQtyBefore = $destStock->quantity;
            $destStock->quantity += $data['quantity'];
            $destStock->last_restocked_date = now();
            $destStock->save();

            // 4. Record Transactions (Simple way using existing types)
            // Source (OUT)
            StockTransaction::create([
                'stock_id' => $sourceStock->stock_id,
                'product_id' => $sourceStock->product_id,
                'branch_id' => $sourceStock->branch_id,
                'brand_id' => $sourceStock->brand_id,
                'category_id' => $sourceStock->category_id,
                'subcategory_id' => $sourceStock->subcategory_id,
                'product_name' => $sourceStock->product_name,
                'transaction_type' => 'out',
                'quantity_before' => $sourceQtyBefore,
                'quantity_changed' => -$data['quantity'],
                'quantity_after' => $sourceStock->quantity,
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => "STOCK TRANSFER: Sent to Branch [{$toBranch->branch_name}]. " . ($data['notes'] ?? ''),
                'created_by' => auth()->id(),
            ]);

            // Destination (IN)
            StockTransaction::create([
                'stock_id' => $destStock->stock_id,
                'product_id' => $destStock->product_id,
                'branch_id' => $destStock->branch_id,
                'brand_id' => $destStock->brand_id,
                'category_id' => $destStock->category_id,
                'subcategory_id' => $destStock->subcategory_id,
                'product_name' => $destStock->product_name,
                'transaction_type' => 'in',
                'quantity_before' => $destQtyBefore,
                'quantity_changed' => $data['quantity'],
                'quantity_after' => $destStock->quantity,
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => "STOCK TRANSFER: Received from Branch [{$fromBranch->branch_name}]. " . ($data['notes'] ?? ''),
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock transferred successfully.',
                'data' => [
                    'source_stock' => $sourceStock,
                    'destination_stock' => $destStock
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Transfer failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== STOCK TRANSACTION HISTORY METHODS ==========

    /**
     * Get stock transaction history
     */
    public function getTransactionHistory($stock_id = null)
    {
        try {
            $query = StockTransaction::with(['stock', 'product', 'branch', 'brand', 'category', 'subcategory', 'creator'])
                                     ->orderBy('created_at', 'desc');
            
            if ($stock_id) {
                $query->where('stock_id', $stock_id);
            }
            
            $transactions = $query->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaction history retrieved successfully',
                'count' => $transactions->count(),
                'data' => $transactions
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve transaction history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction history by product
     */
    public function getTransactionsByProduct($product_id)
    {
        try {
            $transactions = StockTransaction::with(['stock', 'product', 'branch', 'brand', 'category', 'subcategory', 'creator'])
                                           ->where('product_id', $product_id)
                                           ->orderBy('created_at', 'desc')
                                           ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Product transaction history retrieved successfully',
                'count' => $transactions->count(),
                'data' => $transactions
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product transaction history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction history by branch
     */
    public function getTransactionsByBranch($branch_id)
    {
        try {
            $transactions = StockTransaction::with(['stock', 'product', 'branch', 'brand', 'category', 'subcategory', 'creator'])
                                           ->where('branch_id', $branch_id)
                                           ->orderBy('created_at', 'desc')
                                           ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Branch transaction history retrieved successfully',
                'count' => $transactions->count(),
                'data' => $transactions
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve branch transaction history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction history by date range
     */
    public function getTransactionsByDateRange(Request $request)
    {
        try {
            $data = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'transaction_type' => 'nullable|in:in,out,adjustment,initial',
            ]);

            $query = StockTransaction::with(['stock', 'product', 'branch', 'brand', 'category', 'subcategory', 'creator'])
                                     ->whereBetween('created_at', [$data['start_date'], $data['end_date']]);
            
            if (isset($data['transaction_type'])) {
                $query->where('transaction_type', $data['transaction_type']);
            }

            $transactions = $query->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaction history retrieved successfully',
                'count' => $transactions->count(),
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'data' => $transactions
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve transaction history',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}