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
use App\Models\Invoice;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class InvoiceCheckoutTest extends TestCase
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

        // Create test user
        $this->user = User::create([
            'name' => 'Operator User',
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
            'product_code' => 'TPROD_' . uniqid(),
            'brand_id' => $this->brand->brand_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'price' => 100.00,
            'cost_price' => 70.00,
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
     * Test checkout successfully deducts stock and logs outflow transaction
     */
    public function test_checkout_deducts_stock_and_logs_transaction(): void
    {
        Sanctum::actingAs($this->user);

        // Seed stock at this branch
        $stock = Stock::create([
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'brand_id' => $this->brand->brand_id,
            'category_id' => $this->category->category_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'product_name' => $this->product->product_name,
            'quantity' => 10,
            'minimum_stock_level' => 2,
            'maximum_stock_level' => 100,
        ]);

        $payload = [
            'branch_id' => $this->branch->branch_id,
            'net_total' => 200.00,
            'payment_method' => 'cash',
            'cash_given' => 200.00,
            'balance_returned' => 0.00,
            'items' => [
                [
                    'product_id' => $this->product->product_id,
                    'product_name' => $this->product->product_name,
                    'product_code' => $this->product->product_code,
                    'price' => 100.00,
                    'qty' => 2,
                ]
            ]
        ];

        $response = $this->postJson('/api/invoices', $payload);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);

        // Check stock is updated
        $stock->refresh();
        $this->assertEquals(8, $stock->quantity);

        // Check stock transaction log
        $invoice = Invoice::where('branch_id', $this->branch->branch_id)->first();
        $this->assertNotNull($invoice);

        $this->assertDatabaseHas('stock_transactions', [
            'stock_id' => $stock->stock_id,
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'transaction_type' => 'out',
            'quantity_before' => 10,
            'quantity_changed' => -2,
            'quantity_after' => 8,
            'reference_number' => $invoice->invoice_number,
        ]);
    }

    /**
     * Test checkout fails with 400 if there is insufficient stock
     */
    public function test_checkout_fails_if_insufficient_stock(): void
    {
        Sanctum::actingAs($this->user);

        // Seed stock at this branch with small quantity
        $stock = Stock::create([
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'brand_id' => $this->brand->brand_id,
            'category_id' => $this->category->category_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'product_name' => $this->product->product_name,
            'quantity' => 1,
            'minimum_stock_level' => 0,
            'maximum_stock_level' => 100,
        ]);

        $payload = [
            'branch_id' => $this->branch->branch_id,
            'net_total' => 200.00,
            'payment_method' => 'cash',
            'cash_given' => 200.00,
            'balance_returned' => 0.00,
            'items' => [
                [
                    'product_id' => $this->product->product_id,
                    'product_name' => $this->product->product_name,
                    'product_code' => $this->product->product_code,
                    'price' => 100.00,
                    'qty' => 2, // exceeds available stock
                ]
            ]
        ];

        $response = $this->postJson('/api/invoices', $payload);

        $response->assertStatus(400);
        $response->assertJsonPath('success', false);
        $response->assertJsonMissing(['data']);

        // Check stock quantity was not deducted
        $stock->refresh();
        $this->assertEquals(1, $stock->quantity);
    }
}
