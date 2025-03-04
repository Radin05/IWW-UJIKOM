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
                                <span class="text-primary">Klik button Lihat!</span>
                            </h6>
                        </div>
                        <div class="col-12 col-xl-4">
                            <div class="justify-content-end d-flex">
                                <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                    <form id="yearForm" method="GET" action="">
                                        <select name="year" id="year" class="form-select"
                                            onchange="document.getElementById('yearForm').submit();">
                                            @for ($i = 2020; $i <= Carbon::now('Asia/Jakarta')->year + 3; $i++)
                                                <option value="{{ $i }}"
                                                    {{ request('year', Carbon::now('Asia/Jakarta')->year) == $i ? 'selected' : '' }}>
                                                    Tahun {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </form>
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
                                                        class="icon-sun mr-2"></i>31<sup>C</sup></h2>
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
                                                <h4 class="mb-0"><strong>Status:</strong> {{ $data->status }}</h4>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card position-relative">
                                <div class="card-body">
                                    <div id="detailedReports"
                                        class="carousel slide detailed-report-carousel position-static pt-2"
                                        data-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div class="row">
                                                    <div
                                                        class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                                                        <div class="ml-xl-4 mt-3">
                                                            <p class="card-title">Detailed Reports</p>
                                                            <h1 class="text-primary">$34040</h1>
                                                            <h3 class="font-weight-500 mb-xl-4 text-primary">North America
                                                            </h3>
                                                            <p class="mb-2 mb-xl-0">The total number of sessions within the
                                                                date range. It is the period time a user is actively engaged
                                                                with your website, page or app, etc</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-xl-9">
                                                        <div class="row">
                                                            <div class="col-md-6 border-right">
                                                                <div class="table-responsive mb-3 mb-md-0 mt-3">
                                                                    <table class="table table-borderless report-table">
                                                                        <tr>
                                                                            <td class="text-muted">Illinois</td>
                                                                            <td class="w-100 px-0">
                                                                                <div class="progress progress-md mx-4">
                                                                                    <div class="progress-bar bg-primary"
                                                                                        role="progressbar"
                                                                                        style="width: 70%"
                                                                                        aria-valuenow="70"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"></div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <h5 class="font-weight-bold mb-0">713</h5>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-muted">Washington</td>
                                                                            <td class="w-100 px-0">
                                                                                <div class="progress progress-md mx-4">
                                                                                    <div class="progress-bar bg-warning"
                                                                                        role="progressbar"
                                                                                        style="width: 30%"
                                                                                        aria-valuenow="30"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"></div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <h5 class="font-weight-bold mb-0">583</h5>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-muted">Mississippi</td>
                                                                            <td class="w-100 px-0">
                                                                                <div class="progress progress-md mx-4">
                                                                                    <div class="progress-bar bg-danger"
                                                                                        role="progressbar"
                                                                                        style="width: 95%"
                                                                                        aria-valuenow="95"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"></div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <h5 class="font-weight-bold mb-0">924</h5>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-muted">California</td>
                                                                            <td class="w-100 px-0">
                                                                                <div class="progress progress-md mx-4">
                                                                                    <div class="progress-bar bg-info"
                                                                                        role="progressbar"
                                                                                        style="width: 60%"
                                                                                        aria-valuenow="60"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"></div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <h5 class="font-weight-bold mb-0">664</h5>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-muted">Maryland</td>
                                                                            <td class="w-100 px-0">
                                                                                <div class="progress progress-md mx-4">
                                                                                    <div class="progress-bar bg-primary"
                                                                                        role="progressbar"
                                                                                        style="width: 40%"
                                                                                        aria-valuenow="40"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"></div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <h5 class="font-weight-bold mb-0">560</h5>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-muted">Alaska</td>
                                                                            <td class="w-100 px-0">
                                                                                <div class="progress progress-md mx-4">
                                                                                    <div class="progress-bar bg-danger"
                                                                                        role="progressbar"
                                                                                        style="width: 75%"
                                                                                        aria-valuenow="75"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"></div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <h5 class="font-weight-bold mb-0">793</h5>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 mt-3">
                                                                <canvas id="north-america-chart"></canvas>
                                                                <div id="north-america-legend"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="carousel-item">
                                                <div class="row">
                                                    <div
                                                        class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                                                        <div class="ml-xl-4 mt-3">
                                                            <p class="card-title">Detailed Reports</p>
                                                            <h1 class="text-primary">$34040</h1>
                                                            <h3 class="font-weight-500 mb-xl-4 text-primary">North America
                                                            </h3>
                                                            <p class="mb-2 mb-xl-0">The total number of sessions within the
                                                                date range. It is the period time a user is actively engaged
                                                                with your website, page or app, etc</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-xl-9">
                                                        <div class="row">
                                                            <div class="col-md-6 border-right">
                                                                <div class="table-responsive mb-3 mb-md-0 mt-3">
                                                                    <table class="table table-borderless report-table">
                                                                        <tr>
                                                                            <td class="text-muted">Illinois</td>
                                                                            <td class="w-100 px-0">
                                                                                <div class="progress progress-md mx-4">
                                                                                    <div class="progress-bar bg-primary"
                                                                                        role="progressbar"
                                                                                        style="width: 70%"
                                                                                        aria-valuenow="70"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"></div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <h5 class="font-weight-bold mb-0">713</h5>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-muted">Washington</td>
                                                                            <td class="w-100 px-0">
                                                                                <div class="progress progress-md mx-4">
                                                                                    <div class="progress-bar bg-warning"
                                                                                        role="progressbar"
                                                                                        style="width: 30%"
                                                                                        aria-valuenow="30"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"></div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <h5 class="font-weight-bold mb-0">583</h5>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-muted">Mississippi</td>
                                                                            <td class="w-100 px-0">
                                                                                <div class="progress progress-md mx-4">
                                                                                    <div class="progress-bar bg-danger"
                                                                                        role="progressbar"
                                                                                        style="width: 95%"
                                                                                        aria-valuenow="95"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"></div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <h5 class="font-weight-bold mb-0">924</h5>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-muted">California</td>
                                                                            <td class="w-100 px-0">
                                                                                <div class="progress progress-md mx-4">
                                                                                    <div class="progress-bar bg-info"
                                                                                        role="progressbar"
                                                                                        style="width: 60%"
                                                                                        aria-valuenow="60"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"></div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <h5 class="font-weight-bold mb-0">664</h5>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-muted">Maryland</td>
                                                                            <td class="w-100 px-0">
                                                                                <div class="progress progress-md mx-4">
                                                                                    <div class="progress-bar bg-primary"
                                                                                        role="progressbar"
                                                                                        style="width: 40%"
                                                                                        aria-valuenow="40"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"></div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <h5 class="font-weight-bold mb-0">560</h5>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-muted">Alaska</td>
                                                                            <td class="w-100 px-0">
                                                                                <div class="progress progress-md mx-4">
                                                                                    <div class="progress-bar bg-danger"
                                                                                        role="progressbar"
                                                                                        style="width: 75%"
                                                                                        aria-valuenow="75"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100"></div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <h5 class="font-weight-bold mb-0">793</h5>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 mt-3">
                                                                <canvas id="south-america-chart"></canvas>
                                                                <div id="south-america-legend"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="carousel-control-prev" href="#detailedReports" role="button"
                                            data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#detailedReports" role="button"
                                            data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
