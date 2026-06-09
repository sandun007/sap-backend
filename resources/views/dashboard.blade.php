@extends('app')
@push('title')
    Dashboard
@endpush
@push('css')
    <style>
        .quick-access-item {
            transition: all 0.2s ease;
            text-decoration: none;
            color: inherit;
        }

        .quick-access-item:hover {
            background-color: rgba(10, 61, 98, 0.1);
            transform: translateY(-3px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            color: #0a3d62;
        }

        .quick-access-item i {
            transition: transform 0.2s ease;
        }

        .quick-access-item:hover i {
            transform: scale(1.2);
        }

        .quick-access-item p {
            font-size: 0.85rem;
            font-weight: 500;
        }
    </style>
@endpush
@section('content')
    <div class="row g-4">
        <!-- KPI Cards -->
        <div class="col-md-3">
            <div class="card kpi-card p-3">
                <h5>Total Sales</h5>
                <h4>Rs.45,230</h4>
                <p>↑ 12% from last month</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card kpi-card p-3" style="background: linear-gradient(135deg,#28a745,#20c997);">
                <h5>Profit</h5>
                <h2>Rs.9,520</h2>
                <p>↑ 8% growth</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card kpi-card p-3" style="background: linear-gradient(135deg,#ffc107,#ff9800);">
                <h5>Pending Orders</h5>
                <h2>128</h2>
                <p>↓ 5% this week</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card kpi-card p-3" style="background: linear-gradient(135deg,#dc3545,#e83e8c);">
                <h5>Low Stock</h5>
                <h2>23</h2>
                <p>Critical Items</p>
            </div>
        </div>
    </div>


{{--    =============== Quick Access File =====================--}}
    <div class="row g-4 mt-3">
        <div class="col-12">
            <div class="card p-3">
                <div class="row">
                    <div class="col-12 mb-2">
                        <p class="h6">Quick Access File</p>
                    </div>

                    <!-- Sales Invoice -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-receipt text-primary" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Sales Invoice</p>
                        </a>
                    </div>

                    <!-- Purchases -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-cart-check text-success" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Purchases</p>
                        </a>
                    </div>

                    <!-- Live Stock -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-box-seam text-warning" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Live Stock</p>
                        </a>
                    </div>

                    <!-- Sales Report -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-graph-up text-info" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Sales Report</p>
                        </a>
                    </div>

                    <!-- Stock Report -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-clipboard-data text-secondary" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Stock Report</p>
                        </a>
                    </div>

                    <!-- Profit Report -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-cash-coin text-success" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Profit Report</p>
                        </a>
                    </div>

                    <!-- Return -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-arrow-counterclockwise text-danger" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Return</p>
                        </a>
                    </div>

                    <!-- Product -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-tags text-dark" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Product</p>
                        </a>
                    </div>

                    <!-- Category -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-grid text-primary" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Category</p>
                        </a>
                    </div>

                    <!-- Brand -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-award text-success" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Brand</p>
                        </a>
                    </div>

                    <!-- Customer -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Customer</p>
                        </a>
                    </div>

                    <!-- Supplier -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-truck text-warning" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Supplier</p>
                        </a>
                    </div>

                    <!-- Employee -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-person-badge text-success" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Employee</p>
                        </a>
                    </div>

                    <!-- Expenses -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-wallet2 text-danger" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Expenses</p>
                        </a>
                    </div>

                    <!-- Branch -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-building text-primary" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Branch</p>
                        </a>
                    </div>

                    <!-- Department -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-diagram-3 text-success" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Department</p>
                        </a>
                    </div>

                    <!-- Bank -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-bank text-warning" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Bank</p>
                        </a>
                    </div>

                    <!-- Cheque -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-credit-card-2-front text-danger" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Cheque</p>
                        </a>
                    </div>

                    <!-- Stores -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-shop text-primary" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Stores</p>
                        </a>
                    </div>

                    <!-- Settings -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-gear text-warning" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Setting</p>
                        </a>
                    </div>

                    <!-- Shortcut -->
                    <div class="col-4 col-sm-3 col-md-1 text-center mb-3">
                        <a href="" class="quick-access-item d-block p-2 rounded">
                            <i class="bi bi-lightning-charge-fill text-warning" style="font-size: 2rem;"></i>
                            <p class="mt-2 m-0">Shortcut</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Sales & Stock Section -->
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Sales Overview</h5>
                <canvas id="salesChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3" style="width:300px !important;">
                <h5>Stock Status</h5>
                <canvas id="stockChart" ></canvas>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row g-4 mt-2">
        <div class="col-md-12">
            <div class="card p-3">
                <h5>Recent Transactions</h5>
                <table class="table table-hover mt-3">
                    <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>#INV-1001</td>
                        <td>ABC Traders</td>
                        <td>2025-08-01</td>
                        <td>$1,250</td>
                        <td><span class="badge bg-success">Paid</span></td>
                    </tr>
                    <tr>
                        <td>#INV-1002</td>
                        <td>XYZ Corp</td>
                        <td>2025-08-03</td>
                        <td>$980</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                    </tr>
                    <tr>
                        <td>#INV-1003</td>
                        <td>Global Stores</td>
                        <td>2025-08-05</td>
                        <td>$2,300</td>
                        <td><span class="badge bg-danger">Overdue</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
