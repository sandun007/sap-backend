<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Carbon\Carbon;

class SalesReportTest extends TestCase
{
    use DatabaseTransactions;

    private $user;
    private $branch;
    private $brand;
    private $category;
    private $subcategory;
    private $product;
    private $testDate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Operator User',
            'email' => 'operator_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'operator',
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

        $this->branch = Branch::create([
            'branch_name' => 'Test Branch',
            'branch_code' => 'TBRNCH_' . uniqid(),
            'address' => '123 Test St',
            'is_active' => true,
        ]);

        // Use a future date to isolate test records from seeded database rows
        $this->testDate = Carbon::parse('2030-06-15 12:00:00');
    }

    public function test_sales_report_summary_stats_and_group_by_date(): void
    {
        Sanctum::actingAs($this->user);

        // Create invoice on future testDate
        $invoice1 = new Invoice([
            'invoice_number' => 'INV_' . uniqid(),
            'branch_id' => $this->branch->branch_id,
            'net_total' => 200.00,
            'payment_method' => 'cash',
            'status' => 'paid',
        ]);
        $invoice1->created_at = $this->testDate;
        $invoice1->save();

        $item1 = new InvoiceItem([
            'invoice_id' => $invoice1->invoice_id,
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'product_code' => $this->product->product_code,
            'price' => 100.00,
            'qty' => 2,
            'total' => 200.00,
        ]);
        $item1->created_at = $this->testDate;
        $item1->save();

        $startDate = '2030-06-01';
        $endDate = '2030-06-30';

        $response = $this->getJson("/api/reports/sales?start_date={$startDate}&end_date={$endDate}&group_by=date");

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        
        // Assert Summary values (loose type check or cast equivalents)
        $this->assertEquals(200, $response->json('summary.total_sales'));
        $this->assertEquals(1, $response->json('summary.total_invoices'));
        // Profit calculation: 2 * (100 - 70) = 60
        $this->assertEquals(60, $response->json('summary.total_profit'));

        // Assert Data row
        $response->assertJsonCount(1, 'data');
        $this->assertEquals(200, $response->json('data.0.sales'));
        $this->assertEquals(60, $response->json('data.0.profit'));
        $this->assertEquals(1, $response->json('data.0.invoices_count'));
    }

    public function test_sales_report_group_by_invoice(): void
    {
        Sanctum::actingAs($this->user);

        $invoice1 = new Invoice([
            'invoice_number' => 'INV_' . uniqid(),
            'branch_id' => $this->branch->branch_id,
            'net_total' => 100.00,
            'payment_method' => 'cash',
            'status' => 'paid',
        ]);
        $invoice1->created_at = $this->testDate;
        $invoice1->save();

        $item1 = new InvoiceItem([
            'invoice_id' => $invoice1->invoice_id,
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'product_code' => $this->product->product_code,
            'price' => 100.00,
            'qty' => 1,
            'total' => 100.00,
        ]);
        $item1->created_at = $this->testDate;
        $item1->save();

        $startDate = '2030-06-01';
        $endDate = '2030-06-30';

        $response = $this->getJson("/api/reports/sales?start_date={$startDate}&end_date={$endDate}&group_by=invoice");

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $this->assertEquals($invoice1->invoice_number, $response->json('data.0.invoice_number'));
        $this->assertEquals(100, $response->json('data.0.net_total'));
        $this->assertEquals(30, $response->json('data.0.profit'));
    }

    public function test_sales_report_group_by_item(): void
    {
        Sanctum::actingAs($this->user);

        $invoice1 = new Invoice([
            'invoice_number' => 'INV_' . uniqid(),
            'branch_id' => $this->branch->branch_id,
            'net_total' => 300.00,
            'payment_method' => 'cash',
            'status' => 'paid',
        ]);
        $invoice1->created_at = $this->testDate;
        $invoice1->save();

        $item1 = new InvoiceItem([
            'invoice_id' => $invoice1->invoice_id,
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'product_code' => $this->product->product_code,
            'price' => 100.00,
            'qty' => 3,
            'total' => 300.00,
        ]);
        $item1->created_at = $this->testDate;
        $item1->save();

        $startDate = '2030-06-01';
        $endDate = '2030-06-30';

        $response = $this->getJson("/api/reports/sales?start_date={$startDate}&end_date={$endDate}&group_by=item&product_id={$this->product->product_id}");

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $this->assertEquals($invoice1->invoice_number, $response->json('data.0.invoice_number'));
        $this->assertEquals(3, $response->json('data.0.qty'));
        $this->assertEquals(300, $response->json('data.0.total'));
        $this->assertEquals(90, $response->json('data.0.profit'));
    }
}
