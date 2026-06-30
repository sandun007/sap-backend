<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTransaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class StockAdjustmentTest extends TestCase
{
    use DatabaseTransactions;

    private $user;
    private $branch;
    private $brand;
    private $category;
    private $subcategory;
    private $product;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::create([
            'name' => 'Test Operator',
            'email' => 'operator_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'operator',
        ]);

        // Create brand
        $this->brand = Brand::create([
            'brand_name' => 'Test Brand',
            'brand_code' => 'TBRND_' . uniqid(),
            'status' => 'active',
        ]);

        // Create category
        $this->category = Category::create([
            'category_name' => 'Test Category',
            'category_code' => 'TCAT_' . uniqid(),
            'status' => 'active',
        ]);

        // Create subcategory
        $this->subcategory = SubCategory::create([
            'subcategory_name' => 'Test Subcategory',
            'subcategory_code' => 'TSUB_' . uniqid(),
            'category_id' => $this->category->category_id,
            'status' => 'active',
        ]);

        // Create product
        $this->product = Product::create([
            'product_name' => 'Test Product',
            'brand_id' => $this->brand->brand_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'price' => 100.00,
            'unit' => 'pcs',
            'status' => 'active',
        ]);

        // Create branch
        $this->branch = Branch::create([
            'branch_name' => 'Test Branch',
            'branch_code' => 'TBRNCH_' . uniqid(),
            'address' => '123 Test St',
            'is_active' => true,
        ]);
    }

    /**
     * Test guest cannot adjust stock.
     */
    public function test_guest_cannot_adjust_stock()
    {
        $response = $this->postJson('/api/stocks/adjust', [
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'adjustment_type' => 'in',
            'quantity' => 10,
            'notes' => 'Unauthorized attempt',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test stock adjustment IN creates/updates stock and logs transaction.
     */
    public function test_authenticated_user_can_adjust_stock_in()
    {
        Sanctum::actingAs($this->user);

        // Verify stock doesn't exist yet
        $this->assertDatabaseMissing('stocks', [
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
        ]);

        $response = $this->postJson('/api/stocks/adjust', [
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'adjustment_type' => 'in',
            'quantity' => 25,
            'notes' => 'Initial inventory adjustment',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Stock adjusted successfully.',
                 ]);

        // Verify stock created
        $stock = Stock::where('product_id', $this->product->product_id)
                      ->where('branch_id', $this->branch->branch_id)
                      ->first();

        $this->assertNotNull($stock);
        $this->assertEquals(25, $stock->quantity);

        // Verify transaction logged
        $transaction = StockTransaction::where('stock_id', $stock->stock_id)->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('adjustment', $transaction->transaction_type);
        $this->assertEquals(0, $transaction->quantity_before);
        $this->assertEquals(25, $transaction->quantity_changed);
        $this->assertEquals(25, $transaction->quantity_after);
        $this->assertEquals('Initial inventory adjustment', $transaction->notes);
        $this->assertEquals($this->user->id, $transaction->created_by);
    }

    /**
     * Test stock adjustment OUT deducts stock and logs transaction.
     */
    public function test_authenticated_user_can_adjust_stock_out()
    {
        Sanctum::actingAs($this->user);

        // Pre-create stock with 50 units
        $stock = Stock::create([
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'brand_id' => $this->brand->brand_id,
            'category_id' => $this->category->category_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'product_name' => $this->product->product_name,
            'quantity' => 50,
            'minimum_stock_level' => 5,
        ]);

        $response = $this->postJson('/api/stocks/adjust', [
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'adjustment_type' => 'out',
            'quantity' => 15,
            'notes' => 'Damaged items removal',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Stock adjusted successfully.',
                 ]);

        $stock->refresh();
        $this->assertEquals(35, $stock->quantity);

        // Verify transaction logged
        $transaction = StockTransaction::where('stock_id', $stock->stock_id)
                                       ->where('quantity_changed', -15)
                                       ->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('adjustment', $transaction->transaction_type);
        $this->assertEquals(50, $transaction->quantity_before);
        $this->assertEquals(-15, $transaction->quantity_changed);
        $this->assertEquals(35, $transaction->quantity_after);
        $this->assertEquals('Damaged items removal', $transaction->notes);
    }

    /**
     * Test stock adjustment OUT fails if insufficient stock.
     */
    public function test_stock_adjustment_out_fails_if_insufficient_stock()
    {
        Sanctum::actingAs($this->user);

        // Pre-create stock with 10 units
        Stock::create([
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'brand_id' => $this->brand->brand_id,
            'category_id' => $this->category->category_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'product_name' => $this->product->product_name,
            'quantity' => 10,
            'minimum_stock_level' => 5,
        ]);

        $response = $this->postJson('/api/stocks/adjust', [
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'adjustment_type' => 'out',
            'quantity' => 15,
            'notes' => 'Deducting too much',
        ]);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Insufficient stock. Available: 10, trying to deduct: 15.',
                 ]);
    }

    /**
     * Test getBinCard filters and mappings.
     */
    public function test_get_bin_card_filters_and_mappings()
    {
        Sanctum::actingAs($this->user);

        // Pre-create stock
        $stock = Stock::create([
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'brand_id' => $this->brand->brand_id,
            'category_id' => $this->category->category_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'product_name' => $this->product->product_name,
            'quantity' => 100,
            'minimum_stock_level' => 5,
        ]);

        // 1. Create a Purchase Transaction
        StockTransaction::create([
            'stock_id' => $stock->stock_id,
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'brand_id' => $this->brand->brand_id,
            'category_id' => $this->category->category_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'product_name' => $this->product->product_name,
            'transaction_type' => 'in',
            'quantity_before' => 0,
            'quantity_changed' => 50,
            'quantity_after' => 50,
            'notes' => 'GRN-12345 Purchase from Vendor',
            'created_by' => $this->user->id,
        ]);

        // 2. Create a Transfer Transaction
        StockTransaction::create([
            'stock_id' => $stock->stock_id,
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'brand_id' => $this->brand->brand_id,
            'category_id' => $this->category->category_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'product_name' => $this->product->product_name,
            'transaction_type' => 'out',
            'quantity_before' => 50,
            'quantity_changed' => -10,
            'quantity_after' => 40,
            'notes' => 'TRANSFER TO BRANCH [Sub Branch]',
            'created_by' => $this->user->id,
        ]);

        // 3. Create an Adjustment Transaction
        StockTransaction::create([
            'stock_id' => $stock->stock_id,
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'brand_id' => $this->brand->brand_id,
            'category_id' => $this->category->category_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'product_name' => $this->product->product_name,
            'transaction_type' => 'adjustment',
            'quantity_before' => 40,
            'quantity_changed' => -5,
            'quantity_after' => 35,
            'notes' => 'Stock Audit Correction',
            'created_by' => $this->user->id,
        ]);

        // 4. Create a Manual Transaction
        StockTransaction::create([
            'stock_id' => $stock->stock_id,
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'brand_id' => $this->brand->brand_id,
            'category_id' => $this->category->category_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'product_name' => $this->product->product_name,
            'transaction_type' => 'in',
            'quantity_before' => 35,
            'quantity_changed' => 15,
            'quantity_after' => 50,
            'notes' => 'Manual entry by supervisor',
            'created_by' => $this->user->id,
        ]);

        // 5. Create an Adjustment Transaction with "GRN" in notes
        StockTransaction::create([
            'stock_id' => $stock->stock_id,
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'brand_id' => $this->brand->brand_id,
            'category_id' => $this->category->category_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'product_name' => $this->product->product_name,
            'transaction_type' => 'adjustment',
            'quantity_before' => 50,
            'quantity_changed' => 10,
            'quantity_after' => 60,
            'notes' => 'Batch Adj [GRN: GRN-460758-SEVX]: abc from Supplier [Dinu (pvt) LTS]',
            'created_by' => $this->user->id,
        ]);

        // Test filter ALL returns all 5
        $response = $this->getJson("/api/stocks/history?product_id={$this->product->product_id}&branch_id={$this->branch->branch_id}&types=ALL");
        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data');

        // Verify transformed output fields on the adjustment item
        $data = $response->json('data');
        $adjustmentItem = collect($data)->first(function($item) {
            return str_contains($item['notes'], 'abc');
        });
        $this->assertNotNull($adjustmentItem);
        $this->assertEquals(10, $adjustmentItem['quantity']);
        $this->assertStringContainsString('abc', $adjustmentItem['notes']);
        $this->assertEquals($this->user->name, $adjustmentItem['user']);

        // Verify transformed output fields on the transfer item
        $transferItem = collect($data)->firstWhere('type', 'TRANSFER_OUT');
        $this->assertNotNull($transferItem);
        $this->assertEquals(10, $transferItem['quantity']);
        $this->assertEquals('TRANSFER TO BRANCH [Sub Branch]', $transferItem['notes']);

        // Test filter ADJUSTMENT returns 2 (both adjustments)
        $response = $this->getJson("/api/stocks/history?product_id={$this->product->product_id}&branch_id={$this->branch->branch_id}&types=ADJUSTMENT");
        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');

        // Test filter TRANSFER returns 1
        $response = $this->getJson("/api/stocks/history?product_id={$this->product->product_id}&branch_id={$this->branch->branch_id}&types=TRANSFER");
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.type', 'TRANSFER_OUT');

        // Test filter GRN returns 1 (excludes the adjustment containing GRN in notes)
        $response = $this->getJson("/api/stocks/history?product_id={$this->product->product_id}&branch_id={$this->branch->branch_id}&types=GRN");
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.type', 'PURCHASE');

        // Test filter MANUAL returns 1
        $response = $this->getJson("/api/stocks/history?product_id={$this->product->product_id}&branch_id={$this->branch->branch_id}&types=MANUAL");
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.type', 'IN');
    }

    /**
     * Test getProductBatches returns active batches.
     */
    public function test_get_product_batches_returns_active_batches()
    {
        Sanctum::actingAs($this->user);

        $supplier = \App\Models\Supplier::create([
            'supplier_id' => 'SUP_' . uniqid(),
            'supplier_name' => 'Test Supplier',
        ]);

        $purchase1 = \App\Models\Purchase::create([
            'purchase_id' => 'GRN-001',
            'supplier_id' => $supplier->supplier_id,
            'branch_id' => $this->branch->branch_id,
            'purchase_date' => '2026-06-01',
            'grand_total' => 1000,
        ]);

        $item1 = \App\Models\PurchaseItem::create([
            'purchase_record_id' => $purchase1->id,
            'purchase_id' => 'GRN-001',
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'product_code' => 'TPROD_CODE',
            'quantity' => 10,
            'current_stock_qty' => 5,
            'stock_price' => 100,
            'selling_price' => 150,
            'subtotal' => 1000,
        ]);
        \DB::table('purchase_items')->where('id', $item1->id)->update(['created_at' => now()->subMinutes(10)]);

        $purchase2 = \App\Models\Purchase::create([
            'purchase_id' => 'GRN-002',
            'supplier_id' => $supplier->supplier_id,
            'branch_id' => $this->branch->branch_id,
            'purchase_date' => '2026-06-02',
            'grand_total' => 2000,
        ]);

        $item2 = \App\Models\PurchaseItem::create([
            'purchase_record_id' => $purchase2->id,
            'purchase_id' => 'GRN-002',
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'product_code' => 'TPROD_CODE',
            'quantity' => 20,
            'current_stock_qty' => 0, // Out of stock batch
            'stock_price' => 110,
            'selling_price' => 160,
            'subtotal' => 2200,
        ]);
        \DB::table('purchase_items')->where('id', $item2->id)->update(['created_at' => now()]);

        // Query the batches API
        $response = $this->getJson("/api/stocks/batches?product_id={$this->product->product_id}&branch_id={$this->branch->branch_id}");

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data'); // Since it falls back/merges with recent history if empty or includes history

        $data = $response->json('data');
        
        // Order by created_at desc
        $this->assertEquals('GRN-002', $data[0]['purchase_id']);
        $this->assertEquals(0.0, $data[0]['current_stock_qty']);
        $this->assertEquals('GRN-001', $data[1]['purchase_id']);
        $this->assertEquals(5.0, $data[1]['current_stock_qty']);
    }

    /**
     * Test adjustBatches updates stock, updates prices, and logs transactions.
     */
    public function test_adjust_batches_performs_updates_and_logs_transactions()
    {
        Sanctum::actingAs($this->user);

        $supplier = \App\Models\Supplier::create([
            'supplier_id' => 'SUP_' . uniqid(),
            'supplier_name' => 'Test Supplier',
        ]);

        $purchase = \App\Models\Purchase::create([
            'purchase_id' => 'GRN-003',
            'supplier_id' => $supplier->supplier_id,
            'branch_id' => $this->branch->branch_id,
            'purchase_date' => '2026-06-03',
            'grand_total' => 1000,
        ]);

        $item = \App\Models\PurchaseItem::create([
            'purchase_record_id' => $purchase->id,
            'purchase_id' => 'GRN-003',
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'product_code' => 'TPROD_CODE',
            'quantity' => 10,
            'current_stock_qty' => 8,
            'stock_price' => 100,
            'selling_price' => 150,
            'subtotal' => 1000,
        ]);

        // Pre-create overall stock with 8 units
        $stock = Stock::create([
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'brand_id' => $this->brand->brand_id,
            'category_id' => $this->category->category_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'product_name' => $this->product->product_name,
            'quantity' => 8,
            'minimum_stock_level' => 5,
        ]);

        $response = $this->postJson('/api/stocks/adjust-batches', [
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'reason' => 'Audit correction',
            'adjustments' => [
                [
                    'purchase_item_id' => $item->id,
                    'new_quantity' => 12, // adjustment of +4
                    'new_cost_price' => 105,
                    'new_retail_price' => 155,
                ]
            ]
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Batch stock adjusted successfully.',
                     'data' => [
                         'stock_quantity' => 12,
                         'transactions_count' => 1
                     ]
                 ]);

        // Verify purchase item updated
        $item->refresh();
        $this->assertEquals(12, $item->current_stock_qty);
        $this->assertEquals(105, $item->stock_price);
        $this->assertEquals(155, $item->selling_price);

        // Verify product updated
        $this->product->refresh();
        $this->assertEquals(105, $this->product->cost_price);
        $this->assertEquals(155, $this->product->price);

        // Verify overall stock updated
        $stock->refresh();
        $this->assertEquals(12, $stock->quantity);

        // Verify transaction logged
        $transaction = StockTransaction::where('stock_id', $stock->stock_id)
                                       ->where('quantity_changed', 4)
                                       ->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('adjustment', $transaction->transaction_type);
        $this->assertEquals(8, $transaction->quantity_before);
        $this->assertEquals(4, $transaction->quantity_changed);
        $this->assertEquals(12, $transaction->quantity_after);
        $this->assertStringContainsString('Audit correction', $transaction->notes);
    }

    /**
     * Test adjustBatches fails if stock would become negative.
     */
    public function test_adjust_batches_fails_if_stock_becomes_negative()
    {
        Sanctum::actingAs($this->user);

        $supplier = \App\Models\Supplier::create([
            'supplier_id' => 'SUP_' . uniqid(),
            'supplier_name' => 'Test Supplier',
        ]);

        $purchase = \App\Models\Purchase::create([
            'purchase_id' => 'GRN-004',
            'supplier_id' => $supplier->supplier_id,
            'branch_id' => $this->branch->branch_id,
            'purchase_date' => '2026-06-04',
            'grand_total' => 1000,
        ]);

        $item = \App\Models\PurchaseItem::create([
            'purchase_record_id' => $purchase->id,
            'purchase_id' => 'GRN-004',
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'product_code' => 'TPROD_CODE',
            'quantity' => 10,
            'current_stock_qty' => 5,
            'stock_price' => 100,
            'selling_price' => 150,
            'subtotal' => 1000,
        ]);

        // Pre-create overall stock with 5 units
        Stock::create([
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'brand_id' => $this->brand->brand_id,
            'category_id' => $this->category->category_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'product_name' => $this->product->product_name,
            'quantity' => 5,
            'minimum_stock_level' => 5,
        ]);

        $response = $this->postJson('/api/stocks/adjust-batches', [
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'reason' => 'Audit deduction',
            'adjustments' => [
                [
                    'purchase_item_id' => $item->id,
                    'new_quantity' => 0, // adjustment of -5. This is allowed, stock is 0.
                ]
            ]
        ]);

        $response->assertStatus(200);

        // Try adjusting below 0 (new_quantity is validated min:0, but what if discrepancy pushes overall stock negative?)
        // E.g., if we decrease batch by more than what overall stock holds?
        // Let's reset the stock record to 3, but batch has 5. We try to reduce batch to 0 (discrepancy -5).
        // Since overall stock is 3, 3 + (-5) = -2, which is negative!
        
        $stock = Stock::where('product_id', $this->product->product_id)
                      ->where('branch_id', $this->branch->branch_id)
                      ->first();
        $stock->quantity = 3;
        $stock->save();

        // Let's reload $item first, set it, and save it
        $item = \App\Models\PurchaseItem::find($item->id);
        $item->current_stock_qty = 5;
        $item->save();

        $response = $this->postJson('/api/stocks/adjust-batches', [
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'reason' => 'Invalid deduction',
            'adjustments' => [
                [
                    'purchase_item_id' => $item->id,
                    'new_quantity' => 0, // discrepancy -5, overall stock 3 - 5 = -2 (negative!)
                ]
            ]
        ]);

        $response->assertStatus(500)
                 ->assertJson([
                     'success' => false,
                 ]);
        
        $this->assertStringContainsString('would become negative', $response->json('message'));
    }
}

