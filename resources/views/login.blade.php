<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">
    <title>{{$inv_data->company_name}} - Login</title>
    <style>
        body {
            background: linear-gradient(to right, #004e92, #000428);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            width: 380px;
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 8px 18px rgba(0,0,0,0.2);
        }
        .company-logo {
            width: 150px;
            margin-bottom: 10px;
        }
        .form-control {
            border-radius: 6px;
            padding: 10px;
            font-size: 14px;
        }
        .btn-login {
            background-color: #0070f2;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-login:hover {
            background-color: #0056b3;
        }
        .sap-title {
            font-size: 20px;
            font-weight: bold;
            color: #004e92;
        }
    </style>
</head>
<body>
<div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center">
    <div class="card login-card p-4">
        <div class="text-center">
            <!-- Company Logo -->
            <img src="{{asset('assets/img/logo.png')}}" alt="Company Logo" class="company-logo">

            <!-- Company Name -->
            <h4 class="sap-title">{{$inv_data->company_name}} - Login</h4>
        </div>

        <!-- Login Form -->
        <form class="mt-3">
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" class="form-control" placeholder="Enter username">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" class="form-control" placeholder="Enter password">
            </div>

            <a href="{{route('dashboard')}}" class="btn btn-login w-100 text-white">Login</a>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">© 2025 Isuru Super | Powered by CodeXpress</small>
        </div>
    </div>
</div>
</body>
</html>
