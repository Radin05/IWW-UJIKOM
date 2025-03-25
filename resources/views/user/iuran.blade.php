@extends('layouts.app2')

@section('title', 'Halaman User')

@section('content')

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
                        <li><a href="{{ route('warga.index') }}#hero">Home</a></li>
                        <li><a href="{{ route('warga.index') }}#about">Tentang</a></li>
                        <li><a href="{{ route('warga.index') }}#pengurus">Pengurus RW</a></li>
                        <li><a href="{{ route('warga.iuran') }}" class="active">Iuran</a></li>
                        <li><a href="{{ route('warga.index') }}#kegiatan">Kegiatan</a></li>
                        <li><a href="{{ route('warga.index') }}#lokasi">Lokasi</a></li>
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

        <section class="call-to-action section accent-background">

            <div class="container">
                <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="100">
                    <div class="col-xl-10">
                        <div class="text-center text-dark">
                            <h3>Iuran Warga</h3>
                            <p class="mt-5">berfokus pada sistem kontribusi keuangan yang dilakukan oleh setiap warga
                                dalam suatu
                                lingkungan,
                                seperti RT atau RW, untuk mendukung berbagai kebutuhan bersama.
                                Iuran ini biasanya digunakan untuk keperluan operasional dan pengembangan fasilitas umum,
                                seperti keamanan,
                                kebersihan, perbaikan infrastruktur, serta kegiatan sosial dan kemasyarakatan.
                            </p>
                            <p>

                                Sistem iuran warga dapat diatur secara bulanan atau tahunan, dengan besaran yang telah
                                disepakati bersama oleh warga dan pengurus.
                                Transparansi dalam pengelolaan iuran menjadi faktor penting agar seluruh warga dapat
                                mengetahui pemasukan
                                dan pengeluaran dana dengan jelas.</p>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <section class="gallery section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Pemakaian Iuran</h2>
                <p>Ini merupakan contoh pemakaian iuran</p>
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
                                href="{{ asset('asset/img/gallery/gallery-2.jpg') }}"><img
                                    src="{{ asset('asset/img/gallery/gallery-2.jpg') }}" class="img-fluid"
                                    alt=""></a></div>
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

        </section>

        <!-- Services Section -->
        <section class="services section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Sistem Iuran</h2>
                <p>Bagaimana cara iuran disimpan sampai dipakai?</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item position-relative">
                            <div class="icon">
                                <img src="{{ asset('asset/img/icons/give-money (1).png') }}" height="50px"
                                    width="50px" alt="Ikon Pembayaran">
                            </div>
                            <a href="#" class="stretched-link">
                                <h3>1. Proses Pembayaran Iuran kepada Bendahara RT</h3>
                            </a>
                            <p>Setiap warga di lingkungan RT diwajibkan untuk membayar iuran kepada Bendahara RT
                                masing-masing.
                                Pembayaran ini dapat dilakukan secara tunai maupun melalui metode lain yang telah
                                disepakati.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-item position-relative">
                            <div class="icon">
                                <img src="{{ asset('asset/img/icons/salary.png') }}" height="50px" width="50px"
                                    alt="Ikon Pembayaran">
                            </div>
                            <a href="#" class="stretched-link">
                                <h3>2. Penyimpanan Dana oleh Bendahara RT</h3>
                            </a>
                            <p>Setelah menerima iuran dari warga, Bendahara RT menyimpan uang tersebut sementara sebelum
                                disalurkan.
                                Selain itu bendahara wajib mendata terlebih dulu uang yang telah di setorkan.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-item position-relative">
                            <div class="icon">
                                <img src="{{ asset('asset/img/icons/commission.png') }}" height="50px" width="50px"
                                    alt="Ikon Pembayaran">
                            </div>
                            <a href="#" class="stretched-link">
                                <h3>3. Distribusi Dana ke Kas RT dan Kas RW</h3>
                            </a>
                            <p>Dalam periode tertentu (misalnya setiap bulan), uang yang terkumpul akan dibagi sesuai dengan
                                ketentuan yang telah ditetapkan.
                                Sebagian uang akan disimpan dalam kas RT untuk kebutuhan di tingkat RT,
                                sementara sebagian lainnya akan disetorkan ke kas RW sebagai kontribusi dari seluruh RT
                                dalam satu wilayah RW.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="service-item position-relative">
                            <div class="icon">
                                <img src="{{ asset('asset/img/icons/notes.png') }}" height="50px" width="50px"
                                    alt="Ikon Pembayaran">
                            </div>
                            <a href="#" class="stretched-link">
                                <h3>4. Pencatatan dan Pendataan Keuangan</h3>
                            </a>
                            <p>Setiap transaksi yang dilakukan, baik penerimaan iuran maupun distribusi ke kas RW, akan
                                dicatat dalam sistem atau buku kas.
                                Pencatatan ini bertujuan untuk memastikan transparansi dan akuntabilitas dalam pengelolaan
                                keuangan,
                                sehingga setiap pihak yang berkepentingan dapat mengetahui kondisi keuangan dengan jelas.
                            </p>
                            <a href="#" class="stretched-link"></a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                        <div class="service-item position-relative">
                            <div class="icon">
                                <img src="{{ asset('asset/img/icons/finance.png') }}" height="50px" width="50px"
                                    alt="Ikon Pembayaran">
                            </div>
                            <a href="#" class="stretched-link">
                                <h3>5. Pemanfaatan Dana untuk Keperluan Masyarakat</h3>
                            </a>
                            <p>Dana yang sudah terkumpul dalam kas RT dan kas RW dapat digunakan untuk berbagai kebutuhan,
                                seperti kegiatan sosial, perbaikan fasilitas umum,
                                atau program kemasyarakatan lainnya yang telah disepakati bersama.
                                Penggunaan dana ini harus sesuai dengan aturan yang berlaku dan disetujui dalam rapat warga
                                atau pengurus RT/RW.</p>
                            <a href="#" class="stretched-link"></a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                        <div class="service-item position-relative">
                            <div class="icon">
                                <img src="{{ asset('asset/img/icons/file.png') }}" height="50px" width="50px"
                                    alt="Ikon Pembayaran">
                            </div>
                            <a href="#" class="stretched-link">
                                <h3>6. Pencatatan dan Pelaporan Setiap Pengeluaran</h3>
                            </a>
                            <p>Setiap penggunaan dana dari kas RT maupun kas RW wajib dicatat secara rinci, termasuk jumlah
                                yang dikeluarkan,
                                tujuan penggunaan, dan pihak yang bertanggung jawab.
                                Pencatatan ini penting untuk menjaga transparansi dan memastikan bahwa dana digunakan dengan
                                benar sesuai
                                dengan kebutuhan warga dan aturan yang telah disepakati.</p>
                            <a href="#" class="stretched-link"></a>
                        </div>
                    </div>

                </div>

            </div>

        </section><!-- /Services Section -->

        <section class="tabs section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Kas RW</h2>
                <p>Ini merupakan pengelolaan iuran yang sudah didistribusikan ke kas RW</p>
            </div><!-- End Section Title -->

            <div class="container mt-4" data-aos="fade-up">
                <div class="row">
                    <!-- Filter Bulan & Tahun -->
                    <div class="col-lg-4">
                        <form method="GET" action="{{ route('warga.iuran') }}">
                            <div class="form-group">
                                <label for="month">Pilih Bulan</label>
                                <select name="month" id="month" class="form-control">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for="year">Pilih Tahun</label>
                                <select name="year" id="year" class="form-control">
                                    @for ($y = now()->year - 5; $y <= now()->year + 3; $y++)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                            {{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Tampilkan</button>
                        </form>
                    </div>

                    <!-- Informasi Kas RW -->
                    <div class="col-lg-6">
                        <div class="info-box bg-warning text-white p-3 rounded shadow-sm">
                            <h4>Kas RW Saat Ini</h4>
                            <p><strong>Jumlah Kas RW:</strong> Rp
                                {{ number_format($kasRw->jumlah_kas_rw ?? 0, 2, ',', '.') }}</p>
                        </div>

                        <div class="info-box bg-info text-white p-3 rounded shadow-sm mt-3">
                            <h4>Kas RW Kumulatif Hingga
                                {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}
                            </h4>
                            <p><strong>Jumlah Kas RW:</strong> Rp {{ number_format($jumlah_kas_rw, 2, ',', '.') }}</p>
                            <p><strong>Tanggal Pembaruan Terakhir:</strong> {{ $tgl_pembaruan }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Pengeluaran Kas RW -->
            <div class="container mt-4">
                <h4>Pengeluaran Kas RW ({{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                    {{ $year }})</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                                <th>Kegiatan</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pengeluaranRw as $index => $pengeluaran)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pengeluaran->tgl_pengeluaran)->format('d-m-Y') }}</td>
                                    <td>Rp {{ number_format($pengeluaran->nominal, 2, ',', '.') }}</td>
                                    <td>{{ $pengeluaran->kegiatan->nama_kegiatan ?? '-' }}</td>
                                    <td>{!! $pengeluaran->keterangan !!}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada pengeluaran pada bulan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>


        <!-- Tabs Section -->
        <section id="tabs" class="tabs section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Kas RT</h2>
                <p>Data kas dari masing-masing RT</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row">
                    <div class="col-lg-3">
                        <ul class="nav nav-tabs flex-column">
                            @foreach ($rts as $index => $rt)
                                <li class="nav-item">
                                    <a class="nav-link {{ $index == 0 ? 'active show' : '' }}" data-bs-toggle="tab"
                                        href="#rt-{{ $rt->id }}">
                                        {{ $rt->nama_RT }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="col-lg-9 mt-4 mt-lg-0">
                        <div class="tab-content">
                            @foreach ($rts as $index => $rt)
                                <div class="tab-pane {{ $index == 0 ? 'active show' : '' }}"
                                    id="rt-{{ $rt->id }}">
                                    <div class="row">
                                        <div class="col-lg-8 details order-2 order-lg-1">
                                            <h3>Kas {{ $rt->nama_RT }}</h3>
                                            <p><strong>Total Kas:</strong>
                                                Rp
                                                {{ number_format(optional($kasRts->where('rt_id', $rt->id)->first())->jumlah_kas_rt, 2, ',', '.') ?? '0' }}
                                            </p>

                                            <!-- Tabel Pengeluaran -->
                                            <h4 class="mt-4">Riwayat Pengeluaran Bulan Ini</h4>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nominal</th>
                                                            <th>Kegiatan</th>
                                                            <th>Keterangan</th>
                                                            <th>Tanggal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = 1;

                                                            $pengeluaranRT = $pengeluarans->where('rt_id', $rt->id);
                                                        @endphp

                                                        @forelse($pengeluaranRT as $data)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                <td>Rp {{ number_format($data->nominal, 2, ',', '.') }}
                                                                </td>
                                                                <td>{{ $data->kegiatan ? $data->kegiatan->nama_kegiatan : '-- Untuk hal lain --' }}
                                                                </td>
                                                                <td>{!! $data->keterangan !!}</td>
                                                                <td>{{ \Carbon\Carbon::parse($data->tgl_pengeluaran)->format('d M Y') }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="5" class="text-center">Tidak ada
                                                                    pengeluaran bulan ini</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- End Tabel Pengeluaran -->

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>

        </section><!-- /Tabs Section -->

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
