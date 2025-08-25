<nav class="navbar navbar-light" style="height: 50px !important; padding: 0 15px; position: relative; background-color: #025a9b">
    <!-- Company Name centered -->
    <span class="fw-bold h3 text-white">
       PosXpress
    </span>
    <span class="fw-bold h3 text-white position-absolute start-50 translate-middle-x text-uppercase">
       Isuru Super
    </span>

    <!-- User Info on the right -->
    <div class="ms-auto d-flex align-items-center">
        <span class="me-2 text-white">Admin</span>
        <img src="{{asset('assets/img/user_icon.jpg')}}" alt="User" class="rounded-circle" style="width:40px; height:40px; object-fit:cover;">
    </div>
</nav>


<!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm" style="height: 40px !important; background-color: #0a3d62">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-primary" href="#">
            @stack('path')
        </a>
        <button class="navbar-toggler text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">

                <!-- Master Files -->
                <li class="nav-item dropdown">
                    <a href="{{route('dashboard')}}" class="nav-link">Dashboard</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#">
                        <i class="bi bi-folder2-open me-2"></i>Master File
                    </a>
                    <ul class="dropdown-menu">
                        <!-- Products -->
                        <li class="dropdown-submenu">
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-box-seam me-2"></i>Products
                            </a>
                            <ul class="dropdown-menu dropdown-menu-start">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-plus-circle me-2"></i>New Product</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-list-ul me-2"></i>Product List</a></li>
                            </ul>
                        </li>

                        <!-- Customer -->
                        <li class="dropdown-submenu">
                            <a class="dropdown-item" href="#"><i class="bi bi-people me-2"></i>Customer</a>
                            <ul class="dropdown-menu dropdown-menu-start">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person-plus me-2"></i>New Customer</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person-lines-fill me-2"></i>Customer List</a></li>
                            </ul>
                        </li>

                        <!-- Supplier -->
                        <li class="dropdown-submenu">
                            <a class="dropdown-item" href="#"><i class="bi bi-truck me-2"></i>Supplier</a>
                            <ul class="dropdown-menu dropdown-menu-start">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-plus-circle me-2"></i>New Supplier</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-list-ul me-2"></i>Supplier List</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>



                <!-- Data Capture -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#">Data Capture</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Sales</a></li>
                        <li><a class="dropdown-item" href="#">Purchases</a></li>
                        <li><a class="dropdown-item" href="#">Returns</a></li>
                    </ul>
                </li>

                <!-- Reports -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#">Reports</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Sales Report</a></li>
                        <li><a class="dropdown-item" href="#">Stock Report</a></li>
                        <li><a class="dropdown-item" href="#">Profit Report</a></li>
                    </ul>
                </li>

                <!-- Accounts -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#">Accounts</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Ledger</a></li>
                        <li><a class="dropdown-item" href="#">Payments</a></li>
                        <li><a class="dropdown-item" href="#">Expenses</a></li>
                    </ul>
                </li>

                <!-- Administration -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#">Administration</a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-submenu">
                            <a class="dropdown-item" href="#">Users</a>
                            <ul class="dropdown-menu dropdown-menu-start">
                                <li><a class="dropdown-item" href="#">Add User</a></li>
                                <li><a class="dropdown-item" href="#">Manage Users</a></li>
                            </ul>
                        </li>
                        <li><a class="dropdown-item" href="#">Roles</a></li>
                        <li class="dropdown-submenu">
                            <a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a>
                            <ul class="dropdown-menu dropdown-menu-start">
                                <li><a class="dropdown-item" href="{{route('setting.general')}}"><i class="bi bi-sliders me-2"></i>General</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-shield-check me-2"></i>Security</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-danger fw-bold" href="#">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
