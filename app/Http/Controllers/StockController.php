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
    private function calculateNetDamaged($stocks)
    {
        return $stocks->map(function ($s) {
            if ($s->product) {
                $salesDamaged = (int)$s->product->sales_damaged_qty;
                $supplierDamaged = (int)$s->product->supplier_damaged_qty;
                $s->product->damaged_quantity = max(0, $salesDamaged - $supplierDamaged);
            }
            return $s;
        });
    }

    public function index()
    {
        try {
            $stocks = Stock::with([
                'product' => function($query) {
                    $query->withSum(['returnItems as sales_damaged_qty' => function($q) {
                        $q->where('condition', 'damaged');
                    }], 'quantity')
                    ->withSum(['purchaseReturnItems as supplier_damaged_qty' => function($q) {
                        $q->where('return_type', 'damaged');
                    }], 'quantity');
                },
                'branch', 'brand', 'category', 'subcategory'
            ])->get();
            $stocks = $this->calculateNetDamaged($stocks);
            
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
                    $query->withSum(['returnItems as sales_damaged_qty' => function($q) {
                        $q->where('condition', 'damaged');
                    }], 'quantity')
                    ->withSum(['purchaseReturnItems as supplier_damaged_qty' => function($q) {
                        $q->where('return_type', 'damaged');
                    }], 'quantity');
                },
                'branch', 'brand', 'category', 'subcategory'
            ])
            ->whereColumn('quantity', '<=', 'minimum_stock_level')
            ->get();
            $stocks = $this->calculateNetDamaged($stocks);
            
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
                    $query->withSum(['returnItems as sales_damaged_qty' => function($q) {
                        $q->where('condition', 'damaged');
                    }], 'quantity')
                    ->withSum(['purchaseReturnItems as supplier_damaged_qty' => function($q) {
                        $q->where('return_type', 'damaged');
                    }], 'quantity');
                },
                'branch', 'brand', 'category', 'subcategory'
            ])
            ->where('branch_id', $branch_id)
            ->get();
            $stocks = $this->calculateNetDamaged($stocks);
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
                $query->withSum(['returnItems as sales_damaged_qty' => function($q) {
                    $q->where('condition', 'damaged');
                }], 'quantity')
                ->withSum(['purchaseReturnItems as supplier_damaged_qty' => function($q) {
                    $q->where('return_type', 'damaged');
                }], 'quantity');
            },
            'branch', 'brand', 'category', 'subcategory'
        ])
        ->where('product_id', $product_id)
        ->get();
        $stocks = $this->calculateNetDamaged($stocks);

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
                                $sub->where(function($inner) {
                                    $inner->where('notes', 'like', '%GRN%')
                                          ->orWhere('notes', 'like', '%Purchase%')
                                          ->orWhere('transaction_type', 'purchase')
                                          ->orWhere('transaction_type', 'PURCHASE');
                                })->where('transaction_type', '!=', 'adjustment')
                                  ->where('notes', 'not like', '%Return%');
                            } elseif ($type === 'TRANSFER') {
                                $sub->where('notes', 'like', '%TRANSFER%');
                            } elseif ($type === 'ADJUSTMENT') {
                                $sub->where('transaction_type', 'adjustment');
                            } elseif ($type === 'MANUAL') {
                                $sub->whereIn('transaction_type', ['in', 'out'])
                                    ->where(function($inner) {
                                        $inner->where('notes', 'not like', '%TRANSFER%')
                                              ->where('notes', 'not like', '%GRN%')
                                              ->where('notes', 'not like', '%Purchase%')
                                              ->orWhereNull('notes');
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
                $refNo = $t->reference_number;
                
                // Detect Special Types from notes/transaction_type
                if (strtolower($t->transaction_type) === 'adjustment') {
                    $type = ($t->quantity_changed > 0) ? 'ADJUSTMENT_IN' : 'ADJUSTMENT_OUT';
                    // Extract the GRN reference embedded in batch-adjustment notes, e.g.
                    // "Batch Adj [GRN: GRN-460758-SEVX]: reason"  →  refNo = GRN-460758-SEVX
                    if (!$refNo) {
                        preg_match('/\[GRN:\s*([A-Za-z0-9-]+)\]/i', $t->notes, $adjMatches);
                        if (!empty($adjMatches[1])) {
                            $refNo = $adjMatches[1];
                        }
                    }
                } elseif (str_contains($notes, 'TRANSFER')) {
                    $type = (strtoupper($t->transaction_type) === 'IN') ? 'TRANSFER_IN' : 'TRANSFER_OUT';
                } elseif (str_contains($notes, 'RETURN') && str_contains($notes, 'PURCHASE')) {
                    $type = 'PURCHASE_RETURN';
                } elseif (str_contains($notes, 'GRN') || str_contains($notes, 'PURCHASE')) {
                    $type = 'PURCHASE'; // Will show as GRN in UI
                    
                    // Inject supplier name if missing
                    if (!str_contains($notes, 'SUPPLIER')) {
                        preg_match('/GRN:\s*([A-Za-z0-9-]+)/i', $t->notes, $matches);
                        $pid = $t->reference_number ?? ($matches[1] ?? null);
                        if ($pid) {
                            $refNo = $pid;
                            if (isset($purchaseMap[$pid]) && $purchaseMap[$pid]->supplier) {
                                $finalNotes .= " from Supplier [{$purchaseMap[$pid]->supplier->supplier_name}]";
                            }
                        }
                    }
                } elseif ($type === 'ADJUSTMENT') {
                    $type = ($t->quantity_changed > 0) ? 'ADJUSTMENT_ADD' : 'ADJUSTMENT_SUB';
                }

                $isDamaged = str_contains(strtoupper($t->notes), 'DAMAGED');

                return [
                    'id' => $t->transaction_id,
                    'created_at' => $t->created_at,
                    'type' => $type,
                    'quantity' => abs($t->quantity_changed),
                    'reference_no' => $refNo ?? ($matches[1] ?? 'N/A'),
                    'notes' => $finalNotes,
                    'is_damaged' => $isDamaged,
                    'user' => $t->creator ? $t->creator->name : 'System'
                ];
            });

            // Calculate current stock level for the selected product and branch
            $currentStock = 0;
            if ($branchId && $productId) {
                $stockRecord = Stock::where('branch_id', $branchId)
                                    ->where('product_id', $productId)
                                    ->first();
                if ($stockRecord) {
                    $currentStock = $stockRecord->quantity;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Bin card data retrieved successfully',
                'data' => $formattedData,
                'current_stock' => $currentStock,
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

    /**
     * Adjust stock directly (structural stock adjustment)
     */
    public function adjustStock(Request $request)
    {
        try {
            $data = $request->validate([
                'product_id' => 'required|exists:products,product_id',
                'branch_id' => 'required|exists:branches,branch_id',
                'adjustment_type' => 'required|in:in,out',
                'quantity' => 'required|integer|min:1',
                'notes' => 'required|string|max:1000'
            ]);

            DB::beginTransaction();

            // Find stock
            $stock = Stock::where('product_id', $data['product_id'])
                          ->where('branch_id', $data['branch_id'])
                          ->first();

            $product = Product::with('subcategory')->find($data['product_id']);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.'
                ], 404);
            }

            if (!$stock) {
                if ($data['adjustment_type'] === 'out') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot deduct stock. No stock record exists for this product at the selected branch.'
                    ], 400);
                }

                // Create stock record
                $stock = Stock::create([
                    'product_id' => $product->product_id,
                    'branch_id' => $data['branch_id'],
                    'brand_id' => $product->brand_id,
                    'category_id' => $product->subcategory->category_id ?? null,
                    'subcategory_id' => $product->subcategory_id,
                    'product_name' => $product->product_name,
                    'quantity' => 0,
                    'minimum_stock_level' => 10,
                    'last_restocked_date' => now(),
                ]);
            }

            $quantityBefore = $stock->quantity;
            $quantityChanged = $data['quantity'];

            if ($data['adjustment_type'] === 'out') {
                if ($stock->quantity < $quantityChanged) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock. Available: {$stock->quantity}, trying to deduct: {$quantityChanged}."
                    ], 400);
                }
                $stock->quantity -= $quantityChanged;
                $quantityChanged = -$quantityChanged; // negative for out
            } else {
                $stock->quantity += $quantityChanged;
            }

            $stock->last_restocked_date = now();
            $stock->save();

            // Record transaction
            $transaction = StockTransaction::create([
                'stock_id' => $stock->stock_id,
                'product_id' => $stock->product_id,
                'branch_id' => $stock->branch_id,
                'brand_id' => $stock->brand_id,
                'category_id' => $stock->category_id,
                'subcategory_id' => $stock->subcategory_id,
                'product_name' => $stock->product_name,
                'transaction_type' => 'adjustment',
                'quantity_before' => $quantityBefore,
                'quantity_changed' => $quantityChanged,
                'quantity_after' => $stock->quantity,
                'notes' => $data['notes'],
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock adjusted successfully.',
                'data' => [
                    'stock' => $stock,
                    'transaction' => $transaction
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Adjustment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProductBatches(Request $request)
    {
        try {
            $productId = $request->query('product_id');
            $branchId = $request->query('branch_id');

            if (!$productId || !$branchId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product ID and Branch ID are required.'
                ], 400);
            }

            $query = \App\Models\PurchaseItem::with('purchase')
                ->where('product_id', $productId)
                ->whereHas('purchase', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });

            $batches = $query->orderBy('created_at', 'desc')->get();

            // Only load GRNs where current system stock is > 0 (or recent history)
            $activeBatches = $batches->filter(function ($b) {
                return ($b->current_stock_qty ?? $b->quantity) > 0;
            });

            if ($activeBatches->isEmpty()) {
                $activeBatches = $batches->take(10); // recent history
            } else {
                // Merge with recent history to satisfy both conditions in the rule
                $activeBatches = $activeBatches->merge($batches->take(5))->unique('id');
            }

            $activeBatches = $activeBatches->sortByDesc('created_at');

            $formattedBatches = $activeBatches->map(function ($b) {
                return [
                    'id' => $b->id,
                    'purchase_id' => $b->purchase_id,
                    'purchase_date' => $b->purchase->purchase_date ?? $b->created_at->format('Y-m-d'),
                    'current_stock_qty' => $b->current_stock_qty ?? $b->quantity,
                    'original_qty' => $b->quantity,
                    'stock_price' => (float)$b->stock_price,
                    'selling_price' => (float)$b->selling_price,
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => $formattedBatches
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch batches: ' . $e->getMessage()
            ], 500);
        }
    }

    public function adjustBatches(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,product_id',
                'branch_id' => 'required|exists:branches,branch_id',
                'reason' => 'nullable|string|max:1000',
                'adjustments' => 'required|array|min:1',
                'adjustments.*.purchase_item_id' => 'required|exists:purchase_items,id',
                'adjustments.*.new_quantity' => 'required|integer|min:0',
                'adjustments.*.new_cost_price' => 'nullable|numeric|min:0',
                'adjustments.*.new_retail_price' => 'nullable|numeric|min:0',
                'adjustments.*.reason' => 'nullable|string|max:1000',
            ]);

            $productId = $request->input('product_id');
            $branchId = $request->input('branch_id');
            $reason = $request->input('reason');
            $adjustments = $request->input('adjustments');

            DB::beginTransaction();

            $product = Product::with('subcategory')->find($productId);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.'
                ], 404);
            }

            $stock = Stock::where('product_id', $productId)
                          ->where('branch_id', $branchId)
                          ->first();

            if (!$stock) {
                // If stock record doesn't exist, create it with 0 quantity
                $stock = Stock::create([
                    'product_id' => $productId,
                    'branch_id' => $branchId,
                    'brand_id' => $product->brand_id,
                    'category_id' => $product->subcategory->category_id ?? null,
                    'subcategory_id' => $product->subcategory_id,
                    'product_name' => $product->product_name,
                    'quantity' => 0,
                    'minimum_stock_level' => 10,
                    'last_restocked_date' => now(),
                ]);
            }

            $transactions = [];

            foreach ($adjustments as $adj) {
                $purchaseItem = \App\Models\PurchaseItem::find($adj['purchase_item_id']);
                
                // 1. Calculate Qty discrepancy
                $oldQty = $purchaseItem->current_stock_qty ?? $purchaseItem->quantity;
                $newQty = $adj['new_quantity'];
                $discrepancy = $newQty - $oldQty;

                // 2. Perform price updates if provided
                $priceChanged = false;
                if (isset($adj['new_cost_price']) && (float)$adj['new_cost_price'] != (float)$purchaseItem->stock_price) {
                    $purchaseItem->stock_price = $adj['new_cost_price'];
                    $product->cost_price = $adj['new_cost_price'];
                    $priceChanged = true;
                }
                if (isset($adj['new_retail_price']) && (float)$adj['new_retail_price'] != (float)$purchaseItem->selling_price) {
                    $purchaseItem->selling_price = $adj['new_retail_price'];
                    $product->price = $adj['new_retail_price'];
                    $priceChanged = true;
                }

                if ($priceChanged) {
                    $product->save();
                }

                if ($discrepancy == 0) {
                    if ($priceChanged) {
                        $purchaseItem->save();
                    }
                    continue; // No stock change
                }

                // Check negative stock for the branch-product stock overall
                $quantityBefore = $stock->quantity;
                $quantityAfter = $quantityBefore + $discrepancy;

                if ($quantityAfter < 0) {
                    throw new \Exception("Cannot adjust stock. Total stock at branch would become negative ({$quantityAfter}) due to batch adjustment on GRN [{$purchaseItem->purchase_id}].");
                }

                // Update purchase item's batch stock
                $purchaseItem->current_stock_qty = $newQty;
                $purchaseItem->save();

                // Update stock record
                $stock->quantity = $quantityAfter;
                $stock->last_restocked_date = now();
                $stock->save();

                $adjReason = !empty($adj['reason']) ? $adj['reason'] : $reason;

                // Create stock transaction record
                $transaction = StockTransaction::create([
                    'stock_id' => $stock->stock_id,
                    'product_id' => $productId,
                    'branch_id' => $branchId,
                    'brand_id' => $stock->brand_id,
                    'category_id' => $stock->category_id,
                    'subcategory_id' => $stock->subcategory_id,
                    'product_name' => $stock->product_name,
                    'transaction_type' => 'adjustment',
                    'quantity_before' => $quantityBefore,
                    'quantity_changed' => $discrepancy,
                    'quantity_after' => $quantityAfter,
                    'notes' => "Batch Adj [GRN: {$purchaseItem->purchase_id}]: {$adjReason}",
                    'created_by' => auth()->id(),
                ]);

                $transactions[] = $transaction;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Batch stock adjusted successfully.',
                'data' => [
                    'stock_quantity' => $stock->quantity,
                    'transactions_count' => count($transactions)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Batch adjustment failed: ' . $e->getMessage()
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

    public function getAdjustmentReport(Request $request)
    {
        try {
            $fromDate = $request->query('from_date');
            $toDate = $request->query('to_date');

            $query = StockTransaction::with(['product', 'branch'])
                ->where('transaction_type', 'adjustment');

            if ($fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            }

            if ($toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            }

            $transactions = $query->orderBy('created_at', 'desc')->get();

            $reportData = $transactions->map(function ($t) {
                $grnNo = 'N/A';
                if ($t->reference_number) {
                    $grnNo = $t->reference_number;
                } else {
                    preg_match('/\[GRN:\s*([A-Za-z0-9-]+)\]/i', $t->notes, $matches);
                    if (!empty($matches[1])) {
                        $grnNo = $matches[1];
                    } else {
                        preg_match('/GRN:\s*([A-Za-z0-9-]+)/i', $t->notes, $matches2);
                        if (!empty($matches2[1])) {
                            $grnNo = $matches2[1];
                        }
                    }
                }

                return [
                    'transaction_id' => $t->transaction_id,
                    'created_at' => $t->created_at->toIso8601String(),
                    'grn_no' => $grnNo,
                    'product_name' => $t->product_name ?? ($t->product->product_name ?? 'N/A'),
                    'cost_price' => $t->product->cost_price ?? 0.00,
                    'retail_price' => $t->product->price ?? 0.00,
                    'previous_quantity' => $t->quantity_before,
                    'discrepancy' => $t->quantity_changed,
                    'new_quantity' => $t->quantity_after,
                    'unit' => $t->product->unit ?? 'pcs',
                    'notes' => $t->notes,
                    'branch_name' => $t->branch->branch_name ?? 'N/A',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $reportData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate stock adjustment report: ' . $e->getMessage()
            ], 500);
        }
    }
}