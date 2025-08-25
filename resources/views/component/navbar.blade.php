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
<nav class="navbar navbar-expand-lg" style="height: 40px !important;">
    <div class="container-fluid">
{{--        <a class="navbar-brand fw-bold" href="#">PosXpress</a>--}}
        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">

                <!-- Master Files -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Master Files</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Products</a></li>
                        <li><a class="dropdown-item" href="#">Customers</a></li>
                        <li><a class="dropdown-item" href="#">Suppliers</a></li>
                    </ul>
                </li>

                <!-- Data Capture -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Data Capture</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Sales</a></li>
                        <li><a class="dropdown-item" href="#">Purchases</a></li>
                        <li><a class="dropdown-item" href="#">Returns</a></li>
                    </ul>
                </li>

                <!-- Reports -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Reports</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Sales Report</a></li>
                        <li><a class="dropdown-item" href="#">Stock Report</a></li>
                        <li><a class="dropdown-item" href="#">Profit Report</a></li>
                    </ul>
                </li>

                <!-- Accounts -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Accounts</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Ledger</a></li>
                        <li><a class="dropdown-item" href="#">Payments</a></li>
                        <li><a class="dropdown-item" href="#">Expenses</a></li>
                    </ul>
                </li>

                <!-- Administration -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Administration</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Users</a></li>
                        <li><a class="dropdown-item" href="#">Roles</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                    </ul>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <a class="nav-link" href="#">Logout</a>
                </li>

            </ul>
        </div>
    </div>
</nav>
