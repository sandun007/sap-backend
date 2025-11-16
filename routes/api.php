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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Customer CRUD routes
Route::apiResource('customers', CustomerController::class);
//product CRUD
Route::apiResource('products', ProductController::class);
// Category CRUD
Route::apiResource('categories', CategoryController::class);
//Supplier CRUD
Route::apiResource('suppliers', SupplierController::class);
// Department CRUD
Route::apiResource('departments', DepartmentController::class);
// Brand CRUD
Route::apiResource('brands', BrandController::class);
// Branch CRUD
Route::apiResource('branches', BranchController::class);

//Employee CRUD
Route::apiResource('employees', EmployeeController::class);

// Stock CRUD
Route::apiResource('stocks', StockController::class);

// SubCategory CRUD
Route::apiResource('subcategories', SubCategoryController::class);

//Order CRUD
Route::apiResource('orders', OrderController::class);
