@extends('layouts.app2')

@section('title', 'Halaman User')

@section('content')

    <style>
        .team-member {
            width: 100%;
            text-align: center;
        }

        .team-member .member-img {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .team-member .member-img img {
            width: 100%;
            max-width: 200px;
            /* Maksimum lebar */
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
        }

        /* Responsif untuk Tablet */
        @media (max-width: 768px) {
            .team-member .member-img img {
                max-width: 180px;
                height: 220px;
            }
        }

        /* Responsif untuk Smartphone */
        @media (max-width: 576px) {
            .team-member .member-img img {
                max-width: 160px;
                height: 200px;
            }
        }
    </style>

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

        <div class="branding d-flex align-items-center">

            <div class="container position-relative d-flex align-items-center justify-content-end">
                <a href="" class="logo d-flex align-items-center me-auto">
                    <img src="{{ asset('asset/img/RR2.png') }}" alt="">
                </a>

                <nav id="navmenu" class="navmenu">
                    <ul>
                        <li><a href="#hero" class="active">Home</a></li>
                        <li><a href="#about">Tentang</a></li>
                        <li><a href="#pengurus">Pengurus RW</a></li>
                        <li><a href="{{ route('warga.iuran') }}">Iuran</a></li>
                        <li><a href="#kegiatan">Kegiatan</a></li>
                        <li><a href="#lokasi">Lokasi</a></li>
                        <li class="dropdown"><a href="{{ route('warga.profil') }}"><span>PROFIL</span> <i
                                    class="bi bi-chevron-down toggle-dropdown"></i></a>
                            <ul>
                                <li><a href="{{ route('warga.profil') }}">Profil</a></li>
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <span>Logout</span>

                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-power" viewBox="0 0 16 16">
                                            <path d="M7.5 1v7h1V1z" />
                                            <path
                                                d="M3 8.812a5 5 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812" />
                                        </svg>
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
                </nav>

            </div>

        </div>

    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section">

            <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

                <div class="carousel-item active">
                    <img src="{{ asset('asset/img/hero-carousel/gerbang1.jpg') }}" alt="">
                    <div class="container">
                        <h2>Selamat Datang Di Website RR2</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua.</p>
                        <a href="#about" class="btn-get-started">Read More</a>
                    </div>
                </div><!-- End Carousel Item -->

                <div class="carousel-item">
                    <img src="{{ asset('asset/img/hero-carousel/kegiatan.jpg') }}" alt="">
                    <div class="container">
                        <h2>Lihat Kegiatan Mendatang</h2>
                        <p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod
                            maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus.
                            Temporibus autem quibusdam et aut officiis debitis aut.</p>
                        <a href="#kegiatan" class="btn-get-started">Read More</a>
                    </div>
                </div><!-- End Carousel Item -->

                <div class="carousel-item">
                    <img src="{{ asset('asset/img/hero-carousel/uang.jpg') }}" alt="">
                    <div class="container">
                        <h2>Pengelolaan Iuran Bulanan</h2>
                        <p>Beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut
                            odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                        </p>
                        <a href="#about" class="btn-get-started">Read More</a>
                    </div>
                </div><!-- End Carousel Item -->

                <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
                </a>

                <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
                </a>

                <ol class="carousel-indicators"></ol>

            </div>

        </section><!-- /Hero Section -->

        <!-- Stats Section -->
        <section id="stats" class="stats section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100">
                            <i class="fas fa-user-md flex-shrink-0"></i>
                            <div>
                                <span>{{ $jumlahwarga }}</span>
                                <p>Warga RR2</p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100">
                            <i class="fas fa-user-md flex-shrink-0"></i>
                            <div>
                                <span>{{ $jumlahWargaRt }}</span>
                                <p>Warga </p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100">
                            <i class="far fa-hospital flex-shrink-0"></i>
                            <div>
                                <span>{{ $jumlahrt }}</span>
                                <p>RT</p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100">
                            <i class="fas fa-flask flex-shrink-0"></i>
                            <div>
                                @if (!empty($kasRw) && !empty($kasRw->jumlah_kas_rw))
                                    <h4>
                                        Rp {{ number_format($kasRw->jumlah_kas_rw, 0, ',', '.') }}
                                    </h4>
                                @else
                                    <h4>
                                        Rp. 0
                                    </h4>
                                @endif
                                <p>Jumlah Kas RW saat ini</p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100">
                            <i class="fas fa-wallet flex-shrink-0"></i>
                            <div>
                                @if (!empty($kasRt) && !empty($kasRt->jumlah_kas_rt))
                                    <h4>
                                        Rp
                                        {{ number_format($kasRt->jumlah_kas_rt, 0, ',', '.') }}
                                    </h4>
                                @else
                                    <h4>
                                        Rp. 0
                                    </h4>
                                @endif
                                <p>Kas RT</p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->


                </div>

            </div>

        </section><!-- /Stats Section -->

        <!-- Gallery Section -->
        <section id="gallery" class="gallery section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Galeri</h2>
                <p>"Foto ini bukan sekadar gambar, tapi cerminan cinta dan kebersamaan warga RW 20."</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="swiper init-swiper">
                    <script type="application/json" class="swiper-config">
              {
                "loop": true,
                "speed": 600,
                "autoplay": {
                  "delay": 5000
                },
                "slidesPerView": "auto",
                "centeredSlides": true,
                "pagination": {
                  "el": ".swiper-pagination",
                  "type": "bullets",
                  "clickable": true
                },
                "breakpoints": {
                  "320": {
                    "slidesPerView": 1,
                    "spaceBetween": 0
                  },
                  "768": {
                    "slidesPerView": 3,
                    "spaceBetween": 20
                  },
                  "1200": {
                    "slidesPerView": 5,
                    "spaceBetween": 20
                  }
                }
              }
            </script>
                    <div class="swiper-wrapper align-items-center">
                        <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery"
                                href="{{ asset('asset/img/gallery/gallery-1.jpg') }}"><img
                                    src="{{ asset('asset/img/gallery/gallery-1.jpg') }}" class="img-fluid"
                                    alt=""></a></div>
                        <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery"
                                href="{{ asset('asset/img/gallery/g2.jpg') }}"><img
                                    src="{{ asset('asset/img/gallery/g2.jpg') }}" class="img-fluid" alt=""></a>
                        </div>
                        <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery"
                                href="{{ asset('asset/img/gallery/gallery-3.jpg') }}"><img
                                    src="{{ asset('asset/img/gallery/gallery-3.jpg') }}" class="img-fluid"
                                    alt=""></a></div>
                        <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery"
                                href="{{ asset('asset/img/gallery/gallery-4.jpg') }}"><img
                                    src="{{ asset('asset/img/gallery/gallery-4.jpg') }}" class="img-fluid"
                                    alt=""></a></div>
                        <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery"
                                href="{{ asset('asset/img/gallery/gallery-5.jpg') }}"><img
                                    src="{{ asset('asset/img/gallery/gallery-5.jpg') }}" class="img-fluid"
                                    alt=""></a></div>
                        <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery"
                                href="{{ asset('asset/img/gallery/gallery-6.jpg') }}"><img
                                    src="{{ asset('asset/img/gallery/gallery-6.jpg') }}" class="img-fluid"
                                    alt=""></a></div>
                        <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery"
                                href="{{ asset('asset/img/gallery/gallery-7.jpg') }}"><img
                                    src="{{ asset('asset/img/gallery/gallery-7.jpg') }}" class="img-fluid"
                                    alt=""></a></div>
                        <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery"
                                href="{{ asset('asset/img/gallery/gallery-8.jpg') }}"><img
                                    src="{{ asset('asset/img/gallery/gallery-8.jpg') }}" class="img-fluid"
                                    alt=""></a></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>

        </section><!-- /Gallery Section -->

        <!-- Call To Action Section -->
        <section id="call-to-action" class="call-to-action section accent-background">

            <div class="container">
                <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="50">
                    <div class="col-xl-10">
                        <div class="text-center">
                            <h3>In an emergency? Need help now?</h3>
                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                                pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt
                                mollit anim id est laborum.</p>
                            <a class="cta-btn" href="#appointment">Make an Appointment</a>
                        </div>
                    </div>
                </div>
            </div>

        </section><!-- /Call To Action Section -->

        <!-- About Section -->
        <section id="about" class="about section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>About Us<br></h2>
                <p>RW 20 Rancamanyar Regency 2 adalah lingkungan yang nyaman dan harmonis, di mana kebersamaan serta gotong
                    royong menjadi dasar dalam membangun komunitas yang rukun dan sejahtera.</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-4">
                    <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="100">
                        <img src="{{ asset('asset/img/about.jpg') }}" class="img-fluid" alt="Tentang RW 20">
                        <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox pulsating-play-btn"></a>
                    </div>
                    <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="200">
                        <h3>Membangun Kebersamaan dan Keharmonisan Warga</h3>
                        <p class="fst-italic">
                            Sebagai warga RW 20, kami menjunjung tinggi nilai kebersamaan dan kepedulian antar tetangga.
                            Dengan saling menghormati, menjaga kebersihan lingkungan, serta aktif dalam kegiatan sosial,
                            kami menciptakan suasana yang aman dan nyaman bagi semua.
                        </p>
                        <ul>
                            <li><i class="bi bi-check2-all"></i> <span>Saling menghormati dalam perbedaan dan
                                    pendapat.</span></li>
                            <li><i class="bi bi-check2-all"></i> <span>Menjaga kebersihan demi lingkungan yang sehat dan
                                    nyaman.</span></li>
                            <li><i class="bi bi-check2-all"></i> <span>Membangun komunikasi yang baik untuk mempererat
                                    hubungan antar warga.</span></li>
                            <li><i class="bi bi-check2-all"></i> <span>Membantu sesama dalam semangat gotong royong.</span>
                            </li>
                            <li><i class="bi bi-check2-all"></i> <span>Menjaga keamanan dengan peduli terhadap lingkungan
                                    sekitar.</span></li>
                        </ul>
                        <p>
                            RW 20 Rancamanyar Regency 2 – <strong>Bersama Kita Harmonis, Bersatu Kita Kuat!</strong>
                        </p>
                    </div>
                </div>

            </div>


        </section>

        <section id="features" class="features section">

            <div class="container">

                <div class="row justify-content-around gy-4">
                    <div class="features-image col-lg-6" data-aos="fade-up" data-aos-delay="100"><img
                            src="{{ asset('asset/img/features.jpg') }}" alt=""></div>

                    <div class="col-lg-5 d-flex flex-column justify-content-center" data-aos="fade-up"
                        data-aos-delay="100">
                        <h3>Membangun Lingkungan yang Nyaman dan Harmonis</h3>
                        <p>RW 20 Rancamanyar Regency 2 hadir sebagai komunitas yang menjunjung tinggi nilai kebersamaan,
                            gotong royong, dan kepedulian sosial. Kami berkomitmen untuk menciptakan lingkungan yang aman,
                            nyaman, dan penuh kekeluargaan bagi seluruh warga.</p>

                        <div class="icon-box d-flex position-relative" data-aos="fade-up" data-aos-delay="100">
                            <i class="fa-solid fa-hand-holding-medical flex-shrink-0"></i>
                            <div>
                                <h4><a href="" class="stretched-link">Kebersamaan Warga</a></h4>
                                <p>Kami mempererat hubungan antarwarga dengan berbagai kegiatan sosial yang membangun
                                    solidaritas dan keharmonisan.</p>
                            </div>
                        </div>

                        <div class="icon-box d-flex position-relative" data-aos="fade-up" data-aos-delay="100">
                            <i class="fa-solid fa-suitcase-medical flex-shrink-0"></i>
                            <div>
                                <h4><a href="" class="stretched-link">Lingkungan Bersih</a></h4>
                                <p>Komitmen menjaga kebersihan dan kelestarian lingkungan agar RW 20 tetap hijau, sehat, dan
                                    nyaman untuk semua.</p>
                            </div>
                        </div>

                        <div class="icon-box d-flex position-relative" data-aos="fade-up" data-aos-delay="100">
                            <i class="fa-solid fa-staff-snake flex-shrink-0"></i>
                            <div>
                                <h4><a href="" class="stretched-link">Keamanan Terjaga</a></h4>
                                <p>Dengan sistem keamanan yang baik dan kepedulian antarwarga, kami menciptakan lingkungan
                                    yang aman dan tenteram.</p>
                            </div>
                        </div>

                        <div class="icon-box d-flex position-relative" data-aos="fade-up" data-aos-delay="100">
                            <i class="fa-solid fa-lungs flex-shrink-0"></i>
                            <div>
                                <h4><a href="" class="stretched-link">Partisipasi Aktif</a></h4>
                                <p>Setiap warga memiliki peran penting dalam memajukan RW 20 melalui berbagai kegiatan
                                    komunitas dan gotong royong.</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </section>
        <!-- End About Section -->

        <!-- Pengurus Section -->
        <section id="pengurus" class="doctors section light-background">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>PENGURUS RW</h2>
                <p>Pengurus RW 20 Rancamanyar Regency 2 tahun {{ $year }}</p>
            </div><!-- End Section Title -->

            <div class="container">
                <div class="row gy-4">

                    @foreach ($superadmins as $admin)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 d-flex align-items-stretch justify-content-center"
                            data-aos="fade-up" data-aos-delay="100">
                            <div class="team-member">
                                <div class="member-img">
                                    <img src="{{ asset('storage/' . $admin->foto) }}" class="img-fluid"
                                        alt="{{ $admin->name }}">
                                    <div class="social">
                                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                                        <a href="#"><i class="bi bi-facebook"></i></a>
                                        <a href="#"><i class="bi bi-instagram"></i></a>
                                        <a href="#"><i class="bi bi-linkedin"></i></a>
                                    </div>
                                </div>
                                <div class="member-info">
                                    <h4>{{ $admin->name }}</h4>
                                    <span>{{ $admin->kedudukan }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

        </section><!-- Pengurus Section -->

        <!-- Kegiatan Mendatang Section -->
        <section id="kegiatan" class="faq section light-background">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Kegiatan Mendatang</h2>
                <p>Berikut adalah daftar kegiatan RW yang telah dijadwalkan.</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row justify-content-center">

                    <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">

                        <div class="faq-container">

                            @php
                                $carbon = \Carbon\Carbon::now('Asia/Jakarta');
                                $kegiatanList = $kegiatanRw->whereBetween('tanggal_kegiatan', [
                                    $carbon,
                                    $carbon->copy()->addDays(7),
                                ]);
                            @endphp

                            @if ($kegiatanList->isEmpty())
                                <p class="text-center text-danger">Tidak ada kegiatan RW mendatang.</p>
                            @else
                                @foreach ($kegiatanList as $data)
                                    <div class="faq-item">
                                        <h4>{{ $data->nama_kegiatan }}</h4>
                                        <p>
                                            <small><strong>Tanggal:</strong> {{ $data->tanggal_kegiatan }} |
                                                <strong>Jam:</strong> {{ $data->jam_kegiatan }}</small>
                                        </p>
                                        <div class="faq-content">
                                            <p>{!! $data->deskripsi !!}</p>
                                        </div>
                                        <i class="faq-toggle bi bi-chevron-right"></i>
                                    </div><!-- End Faq item -->
                                @endforeach
                            @endif

                        </div>

                    </div><!-- End FAQ Column -->

                </div>

            </div>

        </section><!-- /Kegiatan Mendatang Section -->

        <!-- location Section -->
        <section id="lokasi" class="contact section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Lokasi</h2>
                <p>Rancamanyar Regency II, Desa Rancamanyar, Kec.Baleendah, Kab.Bandung, Prov.Jawa Barat</p>
            </div><!-- End Section Title -->

            <div class="mb-5" data-aos="fade-up" data-aos-delay="200">
                <iframe style="border:0; width: 100%; height: 370px;"
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48389.78314118045!2d-74.006138!3d40.710059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a22a3bda30d%3A0xb89d1fe6bc499443!2sDowntown%20Conference%20Center!5e0!3m2!1sen!2sus!4v1676961268712!5m2!1sen!2sus"
                    frameborder="0" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div><!-- End Google Maps -->

        </section><!-- /location Section -->

    </main>

    <footer id="footer" class="footer light-background">

        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="index.html" class="logo d-flex align-items-center">
                        <span class="sitename">Medicio</span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p>A108 Adam Street</p>
                        <p>New York, NY 535022</p>
                        <p class="mt-3"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
                        <p><strong>Email:</strong> <span>info@example.com</span></p>
                    </div>
                    <div class="social-links d-flex mt-4">
                        <a href=""><i class="bi bi-twitter-x"></i></a>
                        <a href=""><i class="bi bi-facebook"></i></a>
                        <a href=""><i class="bi bi-instagram"></i></a>
                        <a href=""><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Terms of service</a></li>
                        <li><a href="#">Privacy policy</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <li><a href="#">Web Design</a></li>
                        <li><a href="#">Web Development</a></li>
                        <li><a href="#">Product Management</a></li>
                        <li><a href="#">Marketing</a></li>
                        <li><a href="#">Graphic Design</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Hic solutasetp</h4>
                    <ul>
                        <li><a href="#">Molestiae accusamus iure</a></li>
                        <li><a href="#">Excepturi dignissimos</a></li>
                        <li><a href="#">Suscipit distinctio</a></li>
                        <li><a href="#">Dilecta</a></li>
                        <li><a href="#">Sit quas consectetur</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Nobis illum</h4>
                    <ul>
                        <li><a href="#">Ipsam</a></li>
                        <li><a href="#">Laudantium dolorum</a></li>
                        <li><a href="#">Dinera</a></li>
                        <li><a href="#">Trodelas</a></li>
                        <li><a href="#">Flexo</a></li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">Medicio</strong> <span>All Rights Reserved</span></p>
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you've purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> Distributed by <a
                    href=“https://themewagon.com>ThemeWagon
            </div>
        </div>

    </footer>

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

@endsection
