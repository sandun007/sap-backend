<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\ProfitReportController;
use App\Http\Controllers\ProductPriceHistoryController;






/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('dashboard/stats', [DashboardController::class, 'getStats']);
    Route::apiResource('customers', CustomerController::class);
    Route::get('products/dropdowns', [ProductController::class, 'getDropdownData']);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::post('/categories/import', [CategoryController::class, 'import']);
    Route::apiResource('suppliers', SupplierController::class);
    Route::post('/suppliers/import', [SupplierController::class, 'import']);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('brands', BrandController::class);
    Route::post('/brands/import', [BrandController::class, 'import']);
    Route::apiResource('branches', BranchController::class);
    Route::post('/branches/import', [BranchController::class, 'import']);
    Route::apiResource('employees', EmployeeController::class);
    Route::get('stocks/batches', [StockController::class, 'getProductBatches']);
    Route::post('stocks/adjust-batches', [StockController::class, 'adjustBatches']);
    Route::post('stocks/adjust', [StockController::class, 'adjustStock']);
    Route::get('stocks/product/{product_id}', [StockController::class, 'getStockByProduct']);
    Route::get('stocks/history', [StockController::class, 'getBinCard']);
    Route::get('stocks/batches', [StockController::class, 'getProductBatches']);
    Route::post('stocks/adjust-batches', [StockController::class, 'adjustBatches']);
    Route::get('stocks/alerts/low-stock', [StockController::class, 'getLowStock']);
    Route::get('stocks/adjustment-report', [StockController::class, 'getAdjustmentReport']);
    Route::get('stocks/branch/{branch_id}', [StockController::class, 'getStockByBranch']);
    Route::get('stocks/product/{product_id}', [StockController::class, 'getStockByProduct']);
    Route::post('stocks/transfer', [StockController::class, 'transferStock']);
    Route::post('stocks/adjust', [StockController::class, 'adjustStock']);
    Route::post('stocks/{id}/add', [StockController::class, 'addStock']);
    Route::post('stocks/{id}/remove', [StockController::class, 'removeStock']);
    Route::apiResource('stocks', StockController::class);
    Route::apiResource('subcategories', SubCategoryController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('stores', StoreController::class);
    Route::apiResource('banks', BankController::class);
    Route::get('purchases/next-id', [PurchaseController::class, 'getNextPurchaseId']);
    Route::get('purchases/product/{product_id}', [PurchaseController::class, 'getProductHistory']);
    Route::apiResource('purchases', PurchaseController::class);
    Route::get('purchase-returns/supplier/{supplier_id}/products', [PurchaseReturnController::class, 'getSupplierProducts']);
    Route::apiResource('purchase-returns', PurchaseReturnController::class);
    Route::get('invoices/search', [InvoiceController::class, 'search']);
    Route::post('invoices/return', [InvoiceController::class, 'processReturn']);
    Route::apiResource('invoices', InvoiceController::class);
    Route::get('sales-returns/verify-credit', [SalesReturnController::class, 'verifyCreditNote']);
    Route::apiResource('sales-returns', SalesReturnController::class)->only(['index', 'show']);
    Route::get('reports/sales', [SalesReportController::class, 'getSalesReport']);
    Route::get('reports/profit', [ProfitReportController::class, 'getProfitReport']);

    // Price Control Routes
    Route::post('price-control/update', [ProductPriceHistoryController::class, 'updatePrice']);
    Route::get('price-control/history', [ProductPriceHistoryController::class, 'getHistory']);

        // Settings Routes
        Route::get('/settings', [SettingController::class, 'index']);
        Route::post('/settings', [SettingController::class, 'update']);




    // Stock Transaction History
    Route::get('stock-transactions', [StockController::class, 'getTransactionHistory']); // All transactions
    Route::get('stock-transactions/stock/{stock_id}', [StockController::class, 'getTransactionHistory']); // By stock
    Route::get('stock-transactions/product/{product_id}', [StockController::class, 'getTransactionsByProduct']); // By product
    Route::get('stock-transactions/branch/{branch_id}', [StockController::class, 'getTransactionsByBranch']); // By branch
    Route::post('stock-transactions/date-range', [StockController::class, 'getTransactionsByDateRange']); // By date range

    // Optional: Additional stock-related routes
    Route::get('stocks/branch/{branch_id}', [StockController::class, 'getStockByBranch']);
    // Dashboard Analytics
    Route::get('dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('dashboard/sales', [DashboardController::class, 'getSalesData']);
    Route::get('dashboard/stock-status', [DashboardController::class, 'getStockStatus']);
    Route::get('dashboard/recent-activity', [DashboardController::class, 'getRecentActivity']);

    Route::post('stocks/transfer', [StockController::class, 'transferStock']);
});

Route::post('/customer/create', [CustomerController::class, 'store']);
Route::post('/admin/create', [AdminController::class, 'createAdmin']);


