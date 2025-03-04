<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>RR2 - Warga </title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link rel="stylesheet" href="{{ asset('asset/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/vendor/aos/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/vendor/glightbox/css/glightbox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/vendor/swiper/swiper-bundle.min.css') }}">

    <!-- Main CSS File -->
    <link rel="stylesheet" href="{{ asset('asset/css/main.css') }}">

    <style>
        .logout-link {
            display: flex;
            align-items: center;
            gap: 6px;
            /* Mengatur jarak antara ikon dan teks */
            text-decoration: none;
            color: inherit;
            /* Pastikan warna mengikuti default */
        }

        .logout-link svg {
            width: 16px;
            height: 16px;
        }
    </style>
</head>

<body class="index-page">

    @yield('content')

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('asset/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('asset/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('asset/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('asset/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('asset/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('asset/vendor/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('asset/js/main.js') }}"></script>

</body>

</html>
