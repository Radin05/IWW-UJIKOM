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
                                <span class="text-primary">Klik button Lihat!</span>
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
                                <div class="card-people mt-auto">
                                    <img src="{{ asset('assets/images/dashboard/people.svg') }}" alt="people">
                                    <div class="weather-info">
                                        <div class="d-flex">
                                            <div>
                                                <h2 class="mb-0 font-weight-normal"><i
                                                        class="icon-sun mr-2"></i>32<sup>C</sup>
                                                </h2>
                                            </div>
                                            <div class="ml-2">
                                                <h4 class="location font-weight-normal">Suhu</h4>
                                                <h6 class="font-weight-normal">Indonesia</h6>
                                            </div>
                                        </div>
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
                            <div class="card position-relative">
                                <div class="card-body">
                                    <h2 class="text-center mb-3">Kegiatan Mendatang</h2>
                                    @php
                                        $carbon = \Carbon\Carbon::now('Asia/Jakarta');
                                        $upcomingKegiatan = $kegiatan->filter(function ($item) use ($carbon) {
                                            return \Carbon\Carbon::parse($item->tanggal_kegiatan)->between(
                                                $carbon,
                                                $carbon->copy()->addDays(7),
                                            );
                                        });
                                    @endphp

                                    @if ($upcomingKegiatan->isEmpty())
                                        <p class="text-center text-danger">Tidak ada kegiatan dalam 7 hari ke depan.</p>
                                    @else
                                        @foreach ($upcomingKegiatan as $data)
                                            @php
                                                $status = trim($data->status);
                                                $bgColor = 'bg-light'; // Default warna

                                                if ($status == 'Rapat') {
                                                    $bgColor = 'bg-darkyellow'; // Kuning tua
                                                } elseif ($status == 'Kerja bakti') {
                                                    $bgColor = 'bg-darkgreen'; // Hijau tua
                                                } elseif ($status == 'Kegiatan') {
                                                    $bgColor = 'bg-darkblue'; // Biru tua
                                                }
                                            @endphp

                                            <div class="p-3 mb-2 rounded {{ $bgColor }} text-white">
                                                <h5 class="mb-1">{{ $data->nama_kegiatan }}</h5>
                                                <p class="mb-1">{!! $data->deskripsi !!}</p>
                                                <p class="mb-1"><strong>Tanggal:</strong> {{ $data->tanggal_kegiatan }} |
                                                    <strong>Jam:</strong> {{ $data->jam_kegiatan }}
                                                </p>
                                                <h4 class="mb-0 text-warning"><strong>Status:</strong> {{ $data->status }}
                                                </h4>
                                            </div>
                                        @endforeach
                                    @endif
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


    <!-- Variabel global untuk data chart -->
    <script>
        window.chartLabels = @json($labels);
        window.chartData = @json($data);
    </script>

@endsection
