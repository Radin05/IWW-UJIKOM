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


@section('navbar')
    <div class="branding d-flex align-items-center">

        <div class="container position-relative d-flex align-items-center justify-content-end">
            <a href="" class="logo d-flex align-items-center me-auto">
                <img src="{{ asset('asset/img/RR2.png') }}" alt="">
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#beranda" class="active">Beranda</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                    <li><a href="#layanan">Layanan</a></li>
                    <li><a href="#pengurus">Pengurus RW</a></li>
                    <li><a href="#kegiatan">Kegiatan</a></li>
                    <li><a href="#lokasi">Lokasi</a></li>
                    <li><a href="{{ route('warga.iuran') }}">Iuran</a></li>
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
@endsection

<main class="main">

    <!-- Hero Section -->
    <section id="beranda" class="hero section">

        <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

            <div class="carousel-item active">
                <img src="{{ asset('asset/img/hero-carousel/gerbang1.jpg') }}" alt="">
                <div class="container">
                    <h2>Selamat Datang Di Website RR2</h2>
                    <h5>Selamat datang di website resmi Komplek <strong>RR2!</strong> Temukan informasi terbaru,
                        kegiatan warga, pengelolaan iuran dan berbagai layanan lainnya di sini.</h5>
                </div>
            </div><!-- End Carousel Item -->

            <div class="carousel-item">
                <img src="{{ asset('asset/img/hero-carousel/kegiatan.jpg') }}" alt="">
                <div class="container">
                    <h2>Jadwal Kegiatan Komplek</h2>
                    <p>Jangan lewatkan berbagai acara seru dan kegiatan warga di Komplek RR2! Dari pertemuan rutin
                        hingga acara spesial,
                        pastikan Anda tetap terinformasi.</p>
                    <a href="#kegiatan" class="btn-get-started">Lihat Detail</a>
                </div>
            </div><!-- End Carousel Item -->

            <div class="carousel-item">
                <img src="{{ asset('asset/img/hero-carousel/uang.jpg') }}" alt="">
                <div class="container">
                    <h2>Pengelolaan Iuran Bulanan Komplek RR2</h2>
                    <p>Pengelolaan iuran bulanan di Komplek RR2 memastikan transparansi dan keberlanjutan fasilitas dan
                        kegiatan warga.
                        Semua pembayaran iuran akan dikelola dengan baik untuk mendukung kesejahteraan Warga dan
                        perkembangan bersama.</p>

                    <a href="{{ route('warga.iuran') }}" class="btn-get-started">Lihat Detail</a>
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
                        <img src="{{ asset('asset/img/icons/people.png') }}" class="mx-3" height="50px"
                            width="50px" alt="">
                        <div>
                            <span>{{ $jumlahwarga }}</span>
                            <p>Warga RR2</p>
                        </div>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item d-flex align-items-center w-100 h-100">
                        <img src="{{ asset('asset/img/icons/group.png') }}" class="mx-3" height="50px"
                            width="50px" alt="">
                        <div>
                            <span>{{ $jumlahWargaRt }}</span>
                            <p>Warga </p>
                        </div>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item d-flex align-items-center w-100 h-100">
                        <img src="{{ asset('asset/img/icons/meeting (1).png') }}" class="mx-3" height="50px"
                            width="50px" alt="">
                        <div>
                            <span>{{ $jumlahrt }}</span>
                            <p>Jumlah RT</p>
                        </div>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item d-flex align-items-center w-100 h-100">
                        <img src="{{ asset('asset/img/icons/money-bag.png') }}" class="mx-3" height="50px"
                            width="50px" alt="">
                        <div>
                            @if (!empty($kasRw) && !empty($kasRw->jumlah_kas_rw))
                                <h5>
                                    Rp {{ number_format($kasRw->jumlah_kas_rw, 0, ',', '.') }}
                                </h5>
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
                        <img src="{{ asset('asset/img/icons/coins.png') }}" class="mx-3" height="50px"
                            width="50px" alt="">
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
                            <p>Jumlah Kas RT</p>
                        </div>
                    </div>
                </div><!-- End Stats Item -->


            </div>

        </div>

    </section><!-- /Stats Section -->

    <!-- About Section -->
    <section id="tentang" class="about section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Tentang RW20 <br></h2>
            <p>RW 20 Rancamanyar Regency 2 adalah lingkungan yang nyaman dan harmonis, di mana kebersamaan serta gotong
                royong menjadi dasar dalam membangun komunitas yang rukun dan sejahtera.</p>
        </div><!-- End Section Title -->

        <div class="container">

            <div class="row gy-4">
                <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="100">
                    <img src="{{ asset('asset/img/about2.jpg') }}" class="img-fluid" alt="Tentang RW 20">
                    <a href="https://www.youtube.com/watch?v=j86iMfJp27w" class="glightbox pulsating-play-btn"></a>
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

    <section class="features section">
        <div class="container">

            <div class="row justify-content-around gy-4">
                <div class="features-image col-lg-6" data-aos="fade-up" data-aos-delay="100"><img
                        src="{{ asset('asset/img/about1.jpg') }}" alt=""></div>

                <div class="col-lg-5 d-flex flex-column justify-content-center" data-aos="fade-up"
                    data-aos-delay="100">
                    <h3>Membangun Lingkungan yang Nyaman dan Harmonis</h3>
                    <p>RW 20 Rancamanyar Regency 2 hadir sebagai komunitas yang menjunjung tinggi nilai kebersamaan,
                        gotong royong, dan kepedulian sosial. Kami berkomitmen untuk menciptakan lingkungan yang aman,
                        nyaman, dan penuh kekeluargaan bagi seluruh warga.</p>

                    <div class="icon-box d-flex position-relative" data-aos="fade-up" data-aos-delay="100">
                        <img src="{{ asset('asset/img/icons/collaboration.png') }}" class="mx-3" height="50px"
                            width="50px" alt="">
                        <div class="mx-2">
                            <h4><a href="" class="stretched-link">Kebersamaan Warga</a></h4>
                            <p>Kami mempererat hubungan antarwarga dengan berbagai kegiatan sosial yang membangun
                                solidaritas dan keharmonisan.</p>
                        </div>
                    </div>

                    <div class="icon-box d-flex position-relative" data-aos="fade-up" data-aos-delay="100">
                        <img src="{{ asset('asset/img/icons/clean-environment.png') }}" class="mx-3"
                            height="50px" width="50px" alt="">
                        <div class="mx-2">
                            <h4><a href="" class="stretched-link">Lingkungan Bersih</a></h4>
                            <p>Komitmen menjaga kebersihan dan kelestarian lingkungan agar RW 20 tetap hijau, sehat, dan
                                nyaman untuk semua.</p>
                        </div>
                    </div>

                    <div class="icon-box d-flex position-relative" data-aos="fade-up" data-aos-delay="100">
                        <img src="{{ asset('asset/img/icons/shield.png') }}" class="mx-3" height="50px"
                            width="50px" alt="">
                        <div class="mx-2">
                            <h4><a href="" class="stretched-link">Keamanan Terjaga</a></h4>
                            <p>Dengan sistem keamanan yang baik dan kepedulian antarwarga, kami menciptakan lingkungan
                                yang aman dan tenteram.</p>
                        </div>
                    </div>

                    <div class="icon-box d-flex position-relative" data-aos="fade-up" data-aos-delay="100">
                        <img src="{{ asset('asset/img/icons/togetherness.png') }}" class="mx-3" height="50px"
                            width="50px" alt="">
                        <div class="mx-2">
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

    <!-- Gallery Section -->
    <section id="galeri" class="gallery section">

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
                            href="{{ asset('asset/img/gallery/g1.jpg') }}"><img
                                src="{{ asset('asset/img/gallery/g1.jpg') }}" class="img-fluid" alt=""></a>
                    </div>
                    <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery"
                            href="{{ asset('asset/img/gallery/g2.jpg') }}"><img
                                src="{{ asset('asset/img/gallery/g2.jpg') }}" class="img-fluid" alt=""></a>
                    </div>
                    <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery"
                            href="{{ asset('asset/img/gallery/g3.jpg') }}"><img
                                src="{{ asset('asset/img/gallery/g3.jpg') }}" class="img-fluid" alt=""></a>
                    </div>
                    <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery"
                            href="{{ asset('asset/img/gallery/g5.jpg') }}"><img
                                src="{{ asset('asset/img/gallery/g5.jpg') }}" class="img-fluid" alt=""></a>
                    </div>
                    <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery"
                            href="{{ asset('asset/img/gallery/g4.jpg') }}"><img
                                src="{{ asset('asset/img/gallery/g4.jpg') }}" class="img-fluid" alt=""></a>
                    </div>
                    <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery"
                            href="{{ asset('asset/img/gallery/g6.jpg') }}"><img
                                src="{{ asset('asset/img/gallery/g6.jpg') }}" class="img-fluid" alt=""></a>
                    </div>
                    <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery"
                            href="{{ asset('asset/img/gallery/g7.jpg') }}"><img
                                src="{{ asset('asset/img/gallery/g7.jpg') }}" class="img-fluid" alt=""></a>
                    </div>
                    {{-- <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery"
                            href="{{ asset('asset/img/gallery/g8.jpg') }}"><img
                                src="{{ asset('asset/img/gallery/g8.jpg') }}" class="img-fluid"
                                alt=""></a></div> --}}
                </div>
                <div class="swiper-pagination"></div>
            </div>

        </div>

    </section><!-- /Gallery Section -->

    <!-- Call To Action Section -->
    <section id="layanan" class="call-to-action section accent-background">

        <div class="container">
            <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="50">
                <div class="col-xl-10">
                    <div class="text-center">
                        <h3>Ada yang ingin kamu tanyakan?</h3>
                        <p>Kami siap membantu anda dengan sebaik-baiknya menggunakan layanan kami melewati nomor
                            whatsapp yang dapat dihubungi. kamu dapat menanyakan
                            apa saja tentang RW 20 Rancamanyar Regency 2 dengan <strong>klik link dibawah!!</strong></p>
                        <a href="https://wa.me/6282115452003" class="cta-btn" target="_blank"
                            rel="noopener noreferrer">Kirim Pesan</a>
                    </div>
                </div>
            </div>
        </div>

    </section><!-- /Call To Action Section -->

    <!-- Pengurus Section -->
    <section id="pengurus" class="doctors section light-background">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>PENGURUS RW</h2>
            <p>Berikut daftar pengurus RW 20 Rancamanyar Regency 2 tahun {{ $year }}</p>
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
                                $carbon->copy()->addDays(30),
                            ]);
                        @endphp

                        @if ($kegiatanList->isEmpty())
                            <p class="text-center text-danger">Tidak ada kegiatan RW mendatang.</p>
                        @else
                            @foreach ($kegiatanList as $data)
                                <div class="faq-item bg-dark text-light">
                                    <h4 class=" text-light">{{ $data->nama_kegiatan }}</h4>
                                    <p>
                                        <small><strong>Tanggal:</strong> {{ $data->tanggal_kegiatan }} |
                                            <strong>Jam:</strong> {{ $data->jam_kegiatan }}</small>
                                    </p>
                                    <div class="faq-content">
                                        <h5 class="text-light">Deskripsi</h5>
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
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.870679351469!2d107.59003497409572!3d-6.906203867620048!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e93b15cc5f63%3A0x52d7666b0ec05f67!2sKomplek%20Rancamanyar%20Regency%202!5e0!3m2!1sid!2sid!4v1712477777777!5m2!1sid!2sid"
                width="100%" height="370" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

    </section><!-- /location Section -->

</main>

@section('footer')
    <div class="col-lg-3 col-md-3 footer-links">
        <ul>
            <li class="mt-4"><a href="#tentang">Tentang RR2</a></li>
            <li class="mt-4"><a href="#layanan">Layanan</a></li>
            <li class="mt-4"><a href="#kegiatan">Kegiatan Terdekat</a></li>
        </ul>
    </div>

    <div class="col-lg-3 col-md-3 footer-links">
        <ul>
            <li class="mt-4"><a href="{{ route('warga.iuran') }}#rw">Pengelolaan Kas RW</a></li>
            <li class="mt-4"><a href="{{ route('warga.iuran') }}#rt">Pengelolaan Kas Tiap RT</a></li>
            <li class="mt-4"><a href="#pengurus">Pengurus RW</a></li>
        </ul>
    </div>

    <div class="col-lg-2 col-md-3 footer-links">
        <ul>
            <li class="mt-4"><a href="{{ route('warga.profil') }}">Profil Saya</a></li>
            <li class="mt-4"><a href="#lokasi">Lokasi</a></li>
        </ul>
    </div>
@endsection

@endsection
