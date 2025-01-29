<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Skydash Admin</title>

    <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/select.dataTables.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

    @yield('css')


    @php
        use Carbon\Carbon;
    @endphp

    <style>
        .container-scroller {
            background: ;
        }

        .ahay a.uhuy {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: #000000;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .ahay a.uhuy:hover {
            background-color: #4B49AC;
            color: #000000;
            text-decoration: none;
        }

        .ahay.active a.uhuy {
            color: #fff;
            font-weight: bold;
        }

        .ahay.active a.uhuy:hover {
            color: #fff;
        }
    </style>

</head>

<body>

    <div class="container-scroller">

        @extends('bar.navbar')

        <div class="container-fluid page-body-wrapper">

            <nav class="sidebar sidebar-offcanvas mt-3" id="sidebar">
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
                                            <a class="nav-link uhuy"
                                                href="{{ route('superadmin.manajemen-admin.index') }}">
                                                Admin RT
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item ahay {{ request()->routeIs('superadmin.aktivitas') ? 'active' : '' }}">
                                <a class="nav-link uhuy" href="{{ route('superadmin.aktivitas') }}">
                                    <i class="icon-paper menu-icon"></i>
                                    <span class="menu-title">Aktivasi</span>
                                </a>
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
                                <a class="nav-link uhuy"
                                    href="{{ route('admin.warga.index', ['nama_RT' => $nama_RT]) }}">
                                    <i class="icon-grid menu-icon"></i>
                                    <span class="menu-title">Data Warga</span>
                                </a>
                            </li>
                            <li class="nav-item ahay {{ request()->routeIs('admin.pembayaran.index') ? 'active' : '' }}">
                                <a class="nav-link uhuy"
                                    href="{{ route('admin.pembayaran.index', ['nama_RT' => $nama_RT, 'year' => Carbon::now('Asia/Jakarta')->year, 'month' => Carbon::now('Asia/Jakarta')->month]) }}">
                                    <i class="icon-grid menu-icon"></i>
                                    <span class="menu-title">Pembayaran Warga</span>
                                </a>
                            </li>
                            <li class="nav-item ahay {{ request()->routeIs('admin.kas.index') ? 'active' : '' }}">
                                <a class="nav-link uhuy"
                                    href="{{ route('admin.kas.index', ['nama_RT' => $nama_RT]) }}">
                                    <i class="icon-paper menu-icon"></i>
                                    <span class="menu-title">Kas {{ Auth::user()->rt->nama_RT }}</span>
                                </a>
                            </li>
                            <li class="nav-item ahay {{ request()->routeIs('admin.aktivitas') ? 'active' : '' }}">
                                <a class="nav-link uhuy" href="{{ route('admin.aktivitas', ['nama_RT' => $nama_RT]) }}">
                                    <i class="icon-paper menu-icon"></i>
                                    <span class="menu-title">Aktivasi</span>
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

    <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>

    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>

    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/Chart.roundedBarCharts.js') }}"></script>

    @stack('scripts')
</body>

</html>
