<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\ProductPriceHistory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class PriceControlTest extends TestCase
{
    use DatabaseTransactions;

    private $user;
    private $brand;
    private $category;
    private $subcategory;
    private $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Manager User',
            'email' => 'manager_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        $this->brand = Brand::create([
            'brand_name' => 'Test Brand',
            'brand_code' => 'TBRND_' . uniqid(),
            'status' => 'active',
        ]);

        $this->category = Category::create([
            'category_name' => 'Test Category',
            'category_code' => 'TCAT_' . uniqid(),
            'status' => 'active',
        ]);

        $this->subcategory = SubCategory::create([
            'subcategory_name' => 'Test Subcategory',
            'subcategory_code' => 'TSUB_' . uniqid(),
            'category_id' => $this->category->category_id,
            'status' => 'active',
        ]);

        // Create initial product
        $this->product = Product::create([
            'product_name' => 'Test Product',
            'product_code' => 'TPROD_' . uniqid(),
            'brand_id' => $this->brand->brand_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'price' => 100.00,
            'cost_price' => 80.00,
            'unit' => 'pcs',
            'status' => 'active',
        ]);
    }

    public function test_product_creation_logs_initial_price(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/products', [
            'product_name' => 'New Product',
            'product_code' => 'NPROD_' . uniqid(),
            'brand_id' => $this->brand->brand_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'price' => 150.00,
            'cost_price' => 110.00,
            'unit' => 'pcs',
            'status' => 'active',
        ]);

        $response->assertStatus(201);

        $productId = $response->json('data.product_id');

        $this->assertDatabaseHas('product_price_histories', [
            'product_id' => $productId,
            'old_cost_price' => 0.00,
            'new_cost_price' => 110.00,
            'old_selling_price' => 0.00,
            'new_selling_price' => 150.00,
            'user_id' => $this->user->id
        ]);
    }

    public function test_price_history_logged_on_update(): void
    {
        Sanctum::actingAs($this->user);

        // Update price using the price-control endpoint
        $response = $this->postJson('/api/price-control/update', [
            'product_id' => $this->product->product_id,
            'cost_price' => 90.00,
            'selling_price' => 120.00
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        // Verify product was updated
        $this->product->refresh();
        $this->assertEquals(90.00, $this->product->cost_price);
        $this->assertEquals(120.00, $this->product->price);

        // Verify database log entry exists
        $this->assertDatabaseHas('product_price_histories', [
            'product_id' => $this->product->product_id,
            'old_cost_price' => 80.00,
            'new_cost_price' => 90.00,
            'old_selling_price' => 100.00,
            'new_selling_price' => 120.00,
            'user_id' => $this->user->id
        ]);
    }

    public function test_retrieve_price_history(): void
    {
        Sanctum::actingAs($this->user);

        // Manually create two history records for this product
        ProductPriceHistory::create([
            'product_id' => $this->product->product_id,
            'old_cost_price' => 0.00,
            'new_cost_price' => 80.00,
            'old_selling_price' => 0.00,
            'new_selling_price' => 100.00,
            'user_id' => $this->user->id
        ]);

        ProductPriceHistory::create([
            'product_id' => $this->product->product_id,
            'old_cost_price' => 80.00,
            'new_cost_price' => 90.00,
            'old_selling_price' => 100.00,
            'new_selling_price' => 120.00,
            'user_id' => $this->user->id
        ]);

        // Request history filtered by product_id
        $response = $this->getJson('/api/price-control/history?product_id=' . $this->product->product_id);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertCount(2, $response->json('data'));
    }
}
