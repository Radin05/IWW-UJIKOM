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

    <header id="header" class="header sticky-top">

        <div class="topbar d-flex align-items-center">
            <div class="container d-flex justify-content-center justify-content-md-between">
                <div class="d-none d-md-flex align-items-center">
                    <i class="bi bi-clock me-1"></i>
                    <span id="real-time-clock"></span>
                </div>

                <div class="d-flex align-items-center">
                    <i class="bi bi-phone me-1"></i> +62 821 1545 2003
                </div>
            </div>
        </div><!-- End Top Bar -->

        @yield('navbar')

    </header>

    @yield('content')


    <footer id="footer" class="footer light-background">

        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="" class="logo d-flex align-items-center">
                        <span class="sitename">RR2</span>
                    </a>
                    <div class="footer-contact pt-1">
                        <span>Rancamanyar Regency 2</span>
                        <p>
                            Kel. Rancamanyar, Kec. Baleendah, Kab. Bandung, Indonesia RW/20 40375
                        </p>
                        <p class="mt-3"><strong>No Telepon:</strong> <span>+62 821 1545 2003</span></p>
                        <p><strong>Email:</strong> <span>testingwebaja2107@google.com</span></p>
                    </div>
                    <div class="social-links d-flex mt-4">
                        <a href=""><i class="bi bi-twitter-x"></i></a>
                        <a href=""><i class="bi bi-facebook"></i></a>
                        <a href=""><i class="bi bi-instagram"></i></a>
                        <a href=""><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                @yield('footer')

            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">2025</strong> <span>Created : </span>By Radin AL
            </p>
        </div>

    </footer>

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

    <script>
        function updateClock() {
            const now = new Date();
            const options = {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };
            const dateStr = now.toLocaleDateString('id-ID', options);
            const timeStr = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            document.getElementById('real-time-clock').innerHTML = `${dateStr}, ${timeStr} WIB`;
        }

        // Perbarui setiap detik
        setInterval(updateClock, 1000);

        // Jalankan sekali saat pertama kali dimuat
        updateClock();
    </script>

</body>

</html>
