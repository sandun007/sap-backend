<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @stack('title')
    </title>
    <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @stack('css')
    <style>

        .navbar {
            background-color: #0a3d62;
        }
        .navbar-brand, .nav-link, .dropdown-item {
            color: #fff !important;
        }
        .dropdown-menu {
            background-color: #1e3799;
        }
        .dropdown-item:hover {
            background-color: #4a69bd;
        }
        body {
            background-color: #f4f6f9;
            font-family: "Segoe UI", Arial, sans-serif;
        }
        .dashboard-cards .card {
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            border: none;
        }
        .card h5 {
            font-weight: 600;
            color: #2c3e50;
        }
        .kpi-card {
            background: linear-gradient(135deg, #0070f3, #00bcd4);
            color: #fff;
            height: 120px !important;
        }

        .kpi-card h5{
            color: white;
        }
        .table thead {
            background-color: #0070f3;
            color: #fff;
        }
        /* Submenu positioning */
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu > .dropdown-menu {
            top: 0;
            right: 100%;   /* popup LEFT of parent */
            left: auto;    /* disable default right-side */
            margin-top: -1px;
            display: none;
            position: absolute;
        }

        /* Show submenu on hover */
        .dropdown-submenu:hover > .dropdown-menu {
            display: block;
        }
        .nav-item.dropdown:hover > .dropdown-menu {
            display: block;
            margin-top: 0; /* Fix jump effect */
        }

        /* Submenu position */
        .dropdown-submenu {
            position: relative;
        }
        .navbar .dropdown-toggle::after {
            display: none;
        }

        /*.dropdown-submenu > .dropdown-menu {*/
        /*    top: 0;*/
        /*    left: 100%;*/
        /*    margin-top: -1px;*/
        /*}*/


    </style>

</head>
