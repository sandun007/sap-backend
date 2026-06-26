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
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use App\Models\Stock;
use App\Models\StockTransaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Carbon\Carbon;

class ProfitReportTest extends TestCase
{
    use DatabaseTransactions;

    private $user;
    private $branch;
    private $brand;
    private $category;
    private $subcategory;
    private $product;
    private $supplier;
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

        $this->supplier = Supplier::create([
            'supplier_id' => 'SUP_TEST_' . uniqid(),
            'supplier_name' => 'Test Supplier',
            'email' => 'supplier@example.com',
            'phone' => '1234567890',
            'status' => 'active',
        ]);

        $this->testDate = Carbon::parse('2030-06-15 12:00:00');
    }

    public function test_profit_report_summary_stats_and_group_by_date(): void
    {
        Sanctum::actingAs($this->user);

        // 1. Create Invoice (Sales = 200.00, COGS = 140.00, Gross Profit = 60.00)
        $invoice = new Invoice([
            'invoice_number' => 'INV_' . uniqid(),
            'branch_id' => $this->branch->branch_id,
            'net_total' => 200.00,
            'payment_method' => 'cash',
            'status' => 'paid',
        ]);
        $invoice->created_at = $this->testDate;
        $invoice->save();

        $item = new InvoiceItem([
            'invoice_id' => $invoice->invoice_id,
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'product_code' => $this->product->product_code,
            'price' => 100.00,
            'qty' => 2,
            'total' => 200.00,
        ]);
        $item->created_at = $this->testDate;
        $item->save();

        // 2. Create Sales Return (Refund = 50.00)
        $salesReturn = new SalesReturn([
            'return_number' => 'RET_' . uniqid(),
            'invoice_id' => $invoice->invoice_id,
            'invoice_number' => $invoice->invoice_number,
            'branch_id' => $this->branch->branch_id,
            'refund_amount' => 50.00,
            'refund_method' => 'cash',
            'reason' => 'Test refund',
        ]);
        $salesReturn->created_at = $this->testDate;
        $salesReturn->save();

        // Sales Return Item - Damaged (Loss = 1 * 70.00 = 70.00)
        $returnItem = new SalesReturnItem([
            'return_id' => $salesReturn->return_id,
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'product_code' => $this->product->product_code,
            'quantity' => 1,
            'price' => 100.00,
            'total' => 100.00,
            'condition' => 'damaged',
        ]);
        $returnItem->created_at = $this->testDate;
        $returnItem->save();

        // 3. Create Stock Adjustment Out (Loss = 2 * 70.00 = 140.00)
        $stock = Stock::create([
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'brand_id' => $this->brand->brand_id,
            'category_id' => $this->category->category_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'product_name' => $this->product->product_name,
            'quantity' => 10,
        ]);

        $adjTxn = new StockTransaction([
            'stock_id' => $stock->stock_id,
            'product_id' => $this->product->product_id,
            'branch_id' => $this->branch->branch_id,
            'brand_id' => $this->brand->brand_id,
            'category_id' => $this->category->category_id,
            'subcategory_id' => $this->subcategory->subcategory_id,
            'product_name' => $this->product->product_name,
            'transaction_type' => 'adjustment',
            'quantity_before' => 10,
            'quantity_changed' => -2,
            'quantity_after' => 8,
            'notes' => 'Damaged discard',
        ]);
        $adjTxn->created_at = $this->testDate;
        $adjTxn->save();

        // 4. Create Purchase Return (Supplier Recovery = 30.00)
        $purchaseReturn = new PurchaseReturn([
            'purchase_return_id' => 'PR_' . uniqid(),
            'supplier_id' => $this->supplier->supplier_id,
            'branch_id' => $this->branch->branch_id,
            'return_date' => $this->testDate->toDateString(),
            'total_amount' => 30.00,
            'notes' => 'Supplier return',
        ]);
        $purchaseReturn->created_at = $this->testDate;
        $purchaseReturn->save();

        $startDate = '2030-06-01';
        $endDate = '2030-06-30';

        $response = $this->getJson("/api/reports/profit?start_date={$startDate}&end_date={$endDate}&group_by=date");

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $this->assertEquals(200, $response->json('summary.total_sales'));
        $this->assertEquals(140, $response->json('summary.total_cogs'));
        $this->assertEquals(60, $response->json('summary.gross_profit'));
        $this->assertEquals(50, $response->json('summary.total_refunds'));
        $this->assertEquals(70, $response->json('summary.damaged_returns_loss'));
        $this->assertEquals(140, $response->json('summary.adjustment_loss'));
        $this->assertEquals(260, $response->json('summary.total_losses'));
        $this->assertEquals(30, $response->json('summary.supplier_claims'));
        $this->assertEquals(-170, $response->json('summary.net_profit'));

        // Group by Date checks
        $response->assertJsonCount(1, 'data');
        $this->assertEquals('2030-06-15', $response->json('data.0.date'));
        $this->assertEquals(200, $response->json('data.0.sales'));
        $this->assertEquals(140, $response->json('data.0.cogs'));
        $this->assertEquals(50, $response->json('data.0.refunds'));
        $this->assertEquals(210, $response->json('data.0.write_offs'));
        $this->assertEquals(30, $response->json('data.0.supplier_claims'));
        $this->assertEquals(-170, $response->json('data.0.net_profit'));
    }

    public function test_profit_report_group_by_product(): void
    {
        Sanctum::actingAs($this->user);

        // Create Invoice (Sales = 200.00, COGS = 140.00, Gross Profit = 60.00)
        $invoice = new Invoice([
            'invoice_number' => 'INV_' . uniqid(),
            'branch_id' => $this->branch->branch_id,
            'net_total' => 200.00,
            'payment_method' => 'cash',
            'status' => 'paid',
        ]);
        $invoice->created_at = $this->testDate;
        $invoice->save();

        $item = new InvoiceItem([
            'invoice_id' => $invoice->invoice_id,
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'product_code' => $this->product->product_code,
            'price' => 100.00,
            'qty' => 2,
            'total' => 200.00,
        ]);
        $item->created_at = $this->testDate;
        $item->save();

        // Create Sales Return Item - Damaged (Loss = 1 * 70.00 = 70.00)
        $salesReturn = new SalesReturn([
            'return_number' => 'RET_' . uniqid(),
            'invoice_id' => $invoice->invoice_id,
            'invoice_number' => $invoice->invoice_number,
            'branch_id' => $this->branch->branch_id,
            'refund_amount' => 50.00,
            'refund_method' => 'cash',
            'reason' => 'Test refund',
        ]);
        $salesReturn->created_at = $this->testDate;
        $salesReturn->save();

        $returnItem = new SalesReturnItem([
            'return_id' => $salesReturn->return_id,
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'product_code' => $this->product->product_code,
            'quantity' => 1,
            'price' => 100.00,
            'total' => 100.00,
            'condition' => 'damaged',
        ]);
        $returnItem->created_at = $this->testDate;
        $returnItem->save();

        $startDate = '2030-06-01';
        $endDate = '2030-06-30';

        $response = $this->getJson("/api/reports/profit?start_date={$startDate}&end_date={$endDate}&group_by=product");

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        // Group by Product checks
        $response->assertJsonCount(1, 'data');
        $this->assertEquals($this->product->product_code, $response->json('data.0.product_code'));
        $this->assertEquals($this->product->product_name, $response->json('data.0.product_name'));
        $this->assertEquals(2, $response->json('data.0.qty_sold'));
        $this->assertEquals(200, $response->json('data.0.sales'));
        $this->assertEquals(140, $response->json('data.0.cogs'));
        $this->assertEquals(70, $response->json('data.0.write_offs'));
        $this->assertEquals(-10, $response->json('data.0.net_profit')); // gross (60) - losses (70) = -10
    }
}
