@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    @php
        use Carbon\Carbon;
    @endphp

    <style>
        .bg-darkyellow {
            background-color: #7a4f00 !important;
            color: white;
        }

        .bg-darkgreen {
            background-color: #006400 !important;
            color: white;
        }

        .bg-darkblue {
            background-color: #00008B !important;
            color: white;
        }

        #rt-chart {
            width: 100% !important;
            height: 300px !important;
        }
    </style>

    <div class="main-panel mt-4">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 col-xl-8 mb-2 mb-xl-0">
                            <h3 class="font-weight-bold">SELAMAT DATANG DIHALAMAN ADMIN RT</h3>
                            <h6 class="font-weight-normal mb-5">Hallo {{ Auth::user()->name }}
                                <p>Disini Kamu dapat atur data Warga, Kas RT dan data Kegiatan RT</p>
                                <span class="text-primary">Hati-hati dalam mengatur data!</span>
                            </h6>
                        </div>
                        <div class="col-12 col-xl-4 mt-3">
                            <div class="justify-content-end d-flex">
                                <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                    <i class="mdi mdi-calendar"></i>Today <small> {{ $carbon->format('d-m-Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card tale-bg">
                                <div class="card-people mt-auto text-center">
                                    <img src="{{ asset('assets/images/dashboard/people.svg') }}" alt="people"
                                        class="mb-3">
                                    <h4 class="font-weight-bold">Peraturan Pengurus {{ Auth::user()->rt->nama_RT }}</h4>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="button" class="btn btn-warning mt-2" data-bs-toggle="modal"
                                        data-bs-target="#aturanPengurusRtModal">
                                        Lihat Aturan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Aturan Pengurus RT -->
                        <div class="modal fade" id="aturanPengurusRtModal" tabindex="-1"
                            aria-labelledby="aturanPengurusRtLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning text-white">
                                        <h5 class="modal-title" id="aturanPengurusRTLabel">Peraturan Pengurus
                                            {{ Auth::user()->rt->nama_RT }}</h5>
                                    </div>
                                    <div class="modal-body text-left">
                                        <ol>
                                            <li>Setiap posisi Pengurus RT di RT-nya masing-msaing hanya memiliki
                                                <strong>satu akun resmi</strong> yang digunakan dalam pengelolaan RT.</li>
                                            <li>Pengurus RT memiliki wewenang untuk <strong>mengatur kas RT</strong>,
                                                termasuk:
                                                <ul>
                                                    <li>Menambahkan data keluarga baru dan membuatkan akun bagi warga</li>
                                                    <li>Menerima dan mencatat iuran kas dari warga</li>
                                                    <li>Menambahkan dana tambahan dari sumber eksternal</li>
                                                    <li>Mencatat dan mengelola pengeluaran kas RT</li>
                                                </ul>
                                            </li>
                                            <li><strong>Dilarang menambahkan atau mengubah data tanpa alasan yang
                                                    sah</strong> atau tanpa persetujuan bersama.</li>
                                            <li>Setiap perubahan data kas harus dilakukan secara <strong>terbuka dan
                                                    transparan</strong> serta dapat dipertanggungjawabkan.</li>
                                        </ol>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 grid-margin transparent">
                            <div class="row">
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card card-tale">
                                        <div class="card-body">
                                            <h5 class="mb-4">Jumlah Warga {{ Auth::user()->rt->nama_RT }}</h5>
                                            <p class="fs-15 mb-2">
                                                {{ $jumlahKeluarga }}
                                            </p>
                                            <p>
                                                <a href="{{ route('admin.warga.index', ['nama_RT' => $nama_RT]) }}">
                                                    <button type="button" class="btn-light mt-2">Lihat</button>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card card-dark-blue">
                                        <div class="card-body">
                                            <h4 class="mb-4">Jumlah Pembayaran {{ Auth::user()->rt->nama_RT }}</h4>
                                            @if (!empty($pembayaran) && $pembayaran > 0)
                                                <p class="fs-15 mb-2">
                                                    Rp {{ number_format($pembayaran, 0, ',', '.') }}
                                                </p>
                                            @else
                                                Rp. 0
                                            @endif
                                            <p>
                                                <a
                                                    href="{{ route('admin.pembayaran.index', ['nama_RT' => $nama_RT, 'year' => Carbon::now('Asia/Jakarta')->year, 'month' => Carbon::now('Asia/Jakarta')->month]) }}">
                                                    <button type="button" class="btn-light mt-2">Lihat</button>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                                    <div class="card card-light-blue">
                                        <div class="card-body">
                                            <h4 class="mb-4">Total Kas {{ Auth::user()->rt->nama_RT }}</h4>
                                            @if (!empty($kasRt) && !empty($kasRt->jumlah_kas_rt))
                                                <p class="fs-15 mb-2">
                                                    Rp
                                                    {{ number_format($kasRt->jumlah_kas_rt, 0, ',', '.') }}
                                                </p>
                                            @else
                                                Rp. 0
                                            @endif
                                            <p>
                                                <a href="{{ route('admin.kas.index', ['nama_RT' => $nama_RT]) }}">
                                                    <button type="button" class="btn-light mt-2">Lihat</button>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 stretch-card transparent">
                                    <div class="card card-light-blue">
                                        <div class="card-body">
                                            <h4 class="mb-4">Total Pengeluaran</h4>
                                            <p class="fs-15 mb-2">Rp
                                                {{ number_format($pengeluaran, 0, ',', '.') }}</p>
                                            <a href="{{ route('admin.kas.index', ['nama_RT' => $nama_RT]) }}">
                                                <button type="button" class="btn-light mt-2">Lihat</button>
                                            </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="card-title">Laporan Pengeluaran 5 Bulan Terakhir</p>
                                    </div>
                                    <p class="font-weight-500">Grafik pengeluaran selama 5 bulan sebelum bulan ini.</p>
                                    <div id="sales-epik" class="chartjs-legend mt-4 mb-2"></div>
                                    <canvas id="rt-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        console.log("Labels: ", window.chartLabelsRT);
        console.log("Kas Data: ", window.chartDataKasRT);
        console.log("Pengeluaran Data: ", window.chartDataPengeluaranRT);
    </script>

    <script>
        window.chartLabelsRT = @json($labels);
        window.chartDataKasRT = @json($chartKasRT);
        window.chartDataPengeluaranRT = @json($dataPengeluaran);
    </script>


@endsection
