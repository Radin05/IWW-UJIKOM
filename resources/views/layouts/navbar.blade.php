<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Skydash Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/select.dataTables.min.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

    <style>
        .ahay a.uhuy {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: #000000;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        /* Default Hover Effect */
        .ahay a.uhuy:hover {
            background-color: #4B49AC;
            /* Warna latar saat hover */
            color: #000000;
            /* Warna teks saat hover */
            text-decoration: none;
            /* Pastikan underline hilang */
        }

        /* Active Link */
        .ahay.active a.uhuy {
            /* Warna latar saat active */
            color: #fff;
            /* Warna teks saat active */
            font-weight: bold;
            /* Teks lebih tebal */
        }

        /* Hover untuk Active Link (opsional, agar lebih interaktif) */
        .ahay.active a.uhuy:hover {
            /* Warna yang lebih gelap saat active + hover */
            color: #fff;
            /* Pastikan tetap kontras */
        }
    </style>
</head>

<body>

    <div class="container-scroller">

        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="mr-5" href="index.html"><img src="{{ asset('assets/images/RR2.png') }}" width="75"
                        height="55" class="responsive-logo mr-2" alt="logo') }}" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
                <ul class="navbar-nav mr-lg-2">
                    <li class="nav-item nav-search d-none d-lg-block">
                        <div class="input-group">
                            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                <span class="input-group-text" id="search">
                                    <i class="icon-search"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now"
                                aria-label="search" aria-describedby="search">
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                            data-toggle="dropdown">
                            <i class="icon-bell mx-0"></i>
                            <span class="count"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                            aria-labelledby="notificationDropdown">
                            <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="ti-info-alt mx-0"></i>
                                    </div>
                                </div>

                            </a>
                        </div>
                    </li>
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            <h5>{{ Auth::user()->name }}</h5>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="profileDropdown">
                            <a class="dropdown-item">
                                <i class="ti-settings text-primary"></i>
                                Settings
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                <i class="ti-power-off text-primary"></i>
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                    <li class="nav-item nav-settings d-none d-lg-flex">
                        <a class="nav-link" href="#">
                            <i class="icon-ellipsis"></i>
                        </a>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>

        <div class="container-fluid page-body-wrapper">
            <div class="theme-setting-wrapper">
                <div id="settings-trigger"><i class="ti-settings"></i></div>
                <div id="theme-settings" class="settings-panel">
                    <i class="settings-close ti-close"></i>
                    <p class="settings-heading">SIDEBAR SKINS</p>
                    <div class="sidebar-bg-options selected" id="sidebar-light-theme">
                        <div class="img-ss rounded-circle bg-light border mr-3"></div>Light
                    </div>
                    <div class="sidebar-bg-options" id="sidebar-dark-theme">
                        <div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark
                    </div>
                    <p class="settings-heading mt-2">HEADER SKINS</p>
                    <div class="color-tiles mx-0 px-4">
                        <div class="tiles success"></div>
                        <div class="tiles warning"></div>
                        <div class="tiles danger"></div>
                        <div class="tiles info"></div>
                        <div class="tiles dark"></div>
                        <div class="tiles default"></div>
                    </div>
                </div>
            </div>

            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                @auth
                    @if (Auth::user()->role === 'superadmin')
                        <ul class="nav">
                            <li class="nav-item ahay {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                                <a class="nav-link uhuy" href="{{ route('superadmin.dashboard') }}">
                                    <i class="icon-grid menu-icon"></i>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            </li>

                            <li class="nav-item ahay {{ request()->routeIs('superadmin.rt.index') ? 'active' : '' }}">
                                <a class="nav-link uhuy" href="{{ route('superadmin.rt.index') }}">
                                    <i class="icon-paper menu-icon"></i>
                                    <span class="menu-title">RT</span>
                                </a>
                            </li>
                            
                            <li class="nav-item ahay {{ request()->routeIs('superadmin.aktivitas') ? 'active' : '' }}">
                                <a class="nav-link uhuy" href="{{ route('superadmin.aktivitas') }}">
                                    <i class="icon-paper menu-icon"></i>
                                    <span class="menu-title">Aktivasi</span>
                                </a>
                            </li>

                            <li
                                class="nav-item ahay {{ request()->routeIs('superadmin.manajemen-superadmin.index') || request()->routeIs('superadmin.manajemen-admin.index') ? 'active menu-open' : '' }}">
                                <a class="nav-link uhuy" data-toggle="collapse" href="#ui-basic"
                                    aria-expanded="{{ request()->routeIs('superadmin.manajemen-superadmin.index') || request()->routeIs('superadmin.manajemen-admin.index') ? 'true' : 'false' }}"
                                    aria-controls="ui-basic">
                                    <i class="icon-layout menu-icon"></i>
                                    <span class="menu-title">Manajemen</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="collapse {{ request()->routeIs('superadmin.manajemen-superadmin.index') || request()->routeIs('superadmin.manajemen-admin.index') ? 'show' : '' }}"
                                    id="ui-basic">
                                    <ul class="nav flex-column sub-menu">
                                        <li
                                            class="nav-item ahay {{ request()->routeIs('superadmin.manajemen-superadmin.index') ? 'active' : '' }}">
                                            <a class="nav-link uhuy"
                                                href="{{ route('superadmin.manajemen-superadmin.index') }}">
                                                Admin RW
                                            </a>
                                        </li>
                                        <li
                                            class="nav-item ahay {{ request()->routeIs('superadmin.manajemen-admin.index') ? 'active' : '' }}">
                                            <a class="nav-link uhuy" href="{{ route('superadmin.manajemen-admin.index') }}">
                                                Admin RT
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    @elseif (Auth::user()->role === 'admin')
                        <ul class="nav">
                            <li class="nav-item ahay {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                                <a class="nav-link uhuy" href="{{ route('admin.index', ['nama_RT' => $nama_RT]) }}">
                                    <i class="icon-grid menu-icon"></i>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item ahay {{ request()->routeIs('admin.warga.index') ? 'active' : '' }}">
                                <a class="nav-link uhuy" href="{{ route('admin.warga.index', ['nama_RT' => $nama_RT]) }}">
                                    <i class="icon-grid menu-icon"></i>
                                    <span class="menu-title">Data Warga</span>
                                </a>
                            </li>
                        </ul>
                    @endif
                @endauth
            </nav>


            @yield('content')


        </div>
    </div>


    <!-- plugins:js -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/Chart.roundedBarCharts.js') }}"></script>
    <!-- End custom js for this page-->
</body>

</html>
