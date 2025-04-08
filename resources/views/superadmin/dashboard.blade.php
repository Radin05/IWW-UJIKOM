@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

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

        #sales-chart-custom {
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
                            <h3 class="font-weight-bold">SELAMAT DATANG DIHALAMAN ADMIN RW</h3>
                            <h6 class="font-weight-normal mb-5">Hallo {{ Auth::user()->name }}
                                <p>Disini Kamu dapat atur data Kas RW dan data Kegiatan RW</p>
                                <span class="text-primary">Hati-hati dalam mengatur data!</span>
                            </h6>
                        </div>
                        <div class="col-12 col-xl-4 mt-3">
                            <div class="justify-content-end d-flex">
                                <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                    <div class="card">
                                        <div class="card-body bg-light">
                                            <i class="mdi mdi-calendar"></i>Hari ini :<small> {{ $carbon->format('d-m-Y') }}
                                            </small>
                                        </div>
                                    </div>
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
                                    <h4 class="font-weight-bold">Peraturan Pengurus RW</h4>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="button" class="btn btn-warning mt-2" data-bs-toggle="modal"
                                        data-bs-target="#aturanPengurusRwModal">
                                        Lihat Aturan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Aturan Pengurus RW -->
                        <div class="modal fade" id="aturanPengurusRwModal" tabindex="-1"
                            aria-labelledby="aturanPengurusRwLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning text-white">
                                        <h5 class="modal-title" id="aturanPengurusRwLabel">Peraturan Pengurus RW</h5>
                                    </div>
                                    <div class="modal-body text-left">
                                        <ol>
                                            <li>Setiap posisi Pengurus RW hanya memiliki <strong>satu akun resmi</strong>
                                                yang digunakan.</li>
                                            <li>Pengurus RW memiliki wewenang untuk <strong>mengatur kas RW</strong>,
                                                termasuk:
                                                <ul>
                                                    <li>Menambahkan dana tambahan dari sumber eksternal</li>
                                                    <li>Mencatat semua pengeluaran kas RW</li>
                                                </ul>
                                            </li>
                                            <li><strong>Dilarang menambahkan data secara sembarangan</strong> tanpa dasar
                                                yang jelas atau tanpa persetujuan bersama.</li>
                                            <li>Setiap perubahan data kas harus dilakukan secara <strong>transparan</strong>
                                                dan dapat dipertanggungjawabkan.</li>
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
                                <div class="col-md-6 transparent">
                                    <div class="card card-light-blue">
                                        <div class="card-body">
                                            <h5 class="mb-4">Total Kas RW saat ini</h5>
                                            @if (!empty($kasRw) && !empty($kasRw->jumlah_kas_rw))
                                                <p class="fs-15 mb-2">
                                                    Rp
                                                    {{ number_format($kasRw->jumlah_kas_rw, 0, ',', '.') }}
                                                </p>
                                            @else
                                                Rp. 0
                                            @endif
                                            <p>
                                                <a href="{{ route('superadmin.kas.index') }}">
                                                    <button type="button" class="btn-light mt-2">Lihat</button>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 transparent">
                                    <div class="card card-tale">
                                        <div class="card-body">
                                            <h4 class="mb-4">Total Pengeluaran</h4>
                                            <p class="fs-15 mb-2">
                                                Rp
                                                {{ number_format($pengeluaran, 0, ',', '.') }}</p>
                                            <p>
                                                <a href="{{ route('superadmin.kas.index') }}">
                                                    <button type="button" class="btn-light mt-2">Lihat</button>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-2 mt-3 mb-lg-0 stretch-card transparent">
                                    <div class="card card-dark-blue">
                                        <div class="card-body">
                                            <h2 class="mb-4">Kegiatan / Rapat</h2>
                                            <p>
                                                <a href="{{ route('superadmin.kegiatan-rw.index') }}">
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
                                    <div id="sales-legend" class="chartjs-legend mt-4 mb-2"></div>
                                    <canvas id="sales-chart-custom"></canvas>
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

    <!-- Variabel global untuk data chart -->
    <script>
        window.chartLabels = @json($labels);
        window.chartDataKasRW = @json($chartKasRW);
        window.chartDataPengeluaranRW = @json($dataPengeluaran);
    </script>

@endsection
