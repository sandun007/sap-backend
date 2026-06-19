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
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class PurchaseReturnTest extends TestCase
{
    use DatabaseTransactions;

    private $user;
    private $branch;
    private $brand;
    private $category;
    private $subcategory;
    private $product;
    private $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::create([
            'name' => 'Operator User',
            'email' => 'operator_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'operator',
        ]);

        // Create supplier
        $this->supplier = Supplier::create([
            'supplier_id' => 'SUP_' . uniqid(),
            'supplier_name' => 'Nexus Supplier',
            'contact_person' => 'John Doe',
            'phone' => '1234567890',
            'email' => 'supplier@example.com',
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

        // Create product linked to supplier
        $this->product = Product::create([
            'product_name' => 'Test Product',
            'product_code' => 'TPROD_' . uniqid(),
            'brand_id' => $this->brand->brand_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'price' => 100.00,
            'cost_price' => 70.00,
            'unit' => 'pcs',
            'status' => 'active',
            'supplier_id' => $this->supplier->supplier_id,
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
     * Test guests cannot access purchase returns
     */
    public function test_guest_cannot_access_purchase_returns()
    {
        $response = $this->getJson("/api/purchase-returns/supplier/{$this->supplier->supplier_id}/products?branch_id={$this->branch->branch_id}");
        $response->assertStatus(401);

        $response2 = $this->postJson('/api/purchase-returns', []);
        $response2->assertStatus(401);
    }

    /**
     * Test retrieving supplier products (both direct and historical purchase items)
     */
    public function test_retrieving_supplier_products()
    {
        Sanctum::actingAs($this->user);

        // Pre-create stock
        Stock::create([
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'brand_id' => $this->brand->brand_id,
            'category_id' => $this->category->category_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'product_name' => $this->product->product_name,
            'quantity' => 15,
            'minimum_stock_level' => 5,
        ]);

        // Query supplier products
        $response = $this->getJson("/api/purchase-returns/supplier/{$this->supplier->supplier_id}/products?branch_id={$this->branch->branch_id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);

        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals($this->product->product_id, $data[0]['product_id']);
        $this->assertEquals(15, $data[0]['stock_qty']);
        $this->assertEquals(0, $data[0]['damaged_qty']);
    }

    /**
     * Test expired purchase returns deduct stock and log transactions
     */
    public function test_expired_purchase_return_deducts_stock()
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

        $response = $this->postJson('/api/purchase-returns', [
            'supplier_id' => $this->supplier->supplier_id,
            'branch_id' => $this->branch->branch_id,
            'return_date' => '2026-06-19',
            'notes' => 'Returning expired products',
            'items' => [
                [
                    'product_id' => $this->product->product_id,
                    'quantity' => 10,
                    'price' => 70.00,
                    'return_type' => 'expired'
                ]
            ]
        ]);

        $response->dump();
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Purchase return submitted successfully.'
                 ]);

        // Verify stock deducted
        $stock->refresh();
        $this->assertEquals(40, $stock->quantity);

        // Verify transaction logged
        $transaction = StockTransaction::where('reference_number', $response->json('data.purchase_return_id'))->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('out', $transaction->transaction_type);
        $this->assertEquals(-10, $transaction->quantity_changed);
        $this->assertEquals(40, $transaction->quantity_after);
        $this->assertStringContainsString('Expired', $transaction->notes);
    }

    /**
     * Test expired purchase return fails if insufficient stock
     */
    public function test_expired_return_fails_if_insufficient_stock()
    {
        Sanctum::actingAs($this->user);

        // Pre-create stock with 5 units
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

        $response = $this->postJson('/api/purchase-returns', [
            'supplier_id' => $this->supplier->supplier_id,
            'branch_id' => $this->branch->branch_id,
            'return_date' => '2026-06-19',
            'notes' => 'Returning too many expired products',
            'items' => [
                [
                    'product_id' => $this->product->product_id,
                    'quantity' => 10, // exceeds available stock 5
                    'price' => 70.00,
                    'return_type' => 'expired'
                ]
            ]
        ]);

        $response->assertStatus(500);
        $this->assertStringContainsString('Insufficient standard stock', $response->json('message'));
    }

    /**
     * Test damaged purchase return validates against sales return damaged stock and updates net counts
     */
    public function test_damaged_purchase_return_validates_damaged_counts()
    {
        Sanctum::actingAs($this->user);

        // Create a mock invoice for returns
        $invoice = \App\Models\Invoice::create([
            'invoice_number' => 'INV-TEST-01',
            'net_total' => 500,
            'payment_method' => 'cash',
        ]);

        // Customer returns 5 damaged units
        $salesReturn = SalesReturn::create([
            'return_number' => 'RTN-TEST-01',
            'invoice_id' => $invoice->invoice_id,
            'refund_amount' => 500,
        ]);

        SalesReturnItem::create([
            'return_id' => $salesReturn->return_id,
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'product_code' => $this->product->product_code,
            'quantity' => 5,
            'price' => 100,
            'total' => 500,
            'condition' => 'damaged'
        ]);

        // Verify product damaged quantity count before supplier return is 5
        $prodResBefore = $this->getJson('/api/products');
        $prods = $prodResBefore->json('data');
        $testProduct = collect($prods)->firstWhere('product_id', $this->product->product_id);
        $this->assertEquals(5, $testProduct['damaged_quantity']);

        // Return 3 damaged units to the supplier
        $response = $this->postJson('/api/purchase-returns', [
            'supplier_id' => $this->supplier->supplier_id,
            'branch_id' => $this->branch->branch_id,
            'return_date' => '2026-06-19',
            'notes' => 'Returning damaged items',
            'items' => [
                [
                    'product_id' => $this->product->product_id,
                    'quantity' => 3,
                    'price' => 70.00,
                    'return_type' => 'damaged'
                ]
            ]
        ]);

        $response->assertStatus(200);

        // Verify product net damaged quantity is now 2 (5 - 3)
        $prodResAfter = $this->getJson('/api/products');
        $prodsAfter = $prodResAfter->json('data');
        $testProductAfter = collect($prodsAfter)->firstWhere('product_id', $this->product->product_id);
        $this->assertEquals(2, $testProductAfter['damaged_quantity']);

        // Return 3 damaged units again (fails because only 2 left)
        $responseFail = $this->postJson('/api/purchase-returns', [
            'supplier_id' => $this->supplier->supplier_id,
            'branch_id' => $this->branch->branch_id,
            'return_date' => '2026-06-19',
            'notes' => 'Returning too many damaged items',
            'items' => [
                [
                    'product_id' => $this->product->product_id,
                    'quantity' => 3,
                    'price' => 70.00,
                    'return_type' => 'damaged'
                ]
            ]
        ]);

        $responseFail->assertStatus(500);
        $this->assertStringContainsString('Insufficient damaged stock', $responseFail->json('message'));
    }
}
