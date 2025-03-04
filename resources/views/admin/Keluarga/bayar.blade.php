@extends('layouts.app')

@section('title', 'Admin Data Keluarga dan Pembayaran')

@section('content')

    <?php
    use Carbon\Carbon;
    ?>

    <style>
        .btn-outline-info.dropdown-toggle::after {
            display: none;
        }

        .btn-outline-info {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            border: none;
        }

        .icon-ellipsis {
            font-size: 1.5rem;
        }

        .form-sample {
            margin: 0 auto;
            max-width: 600px;
        }

        .form-group h4 {
            color: black;
            margin-bottom: 10px;
        }

        .form-select {
            width: 100%;
        }

        .warna {
            background-color: red !important;
            color: white !important;
            font-size: 1.1rem;
            /* Perbesar ukuran font */
            padding: 10px 15px;
            /* Tambahkan padding agar lebih besar */
            border-radius: 5px;
            /* Tambahkan sudut melengkung */
            display: block;
            /* Agar dropdown-item terlihat lebih besar */
            text-align: center;
        }

        .warna:hover {
            background-color: darkred !important;
            color: white !important;
        }
    </style>

    <div class="main-panel mt-4">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card card-tale">
                        <div class="card-body">

                            <div class="row d-flex justify-content-center align-items-start gap-3">
                                <!-- Card Filter -->
                                <div class="col-md-3 mb-3">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <form action="{{ route('admin.pembayaran.index', ['nama_RT' => $nama_RT]) }}"
                                                method="GET" class="form-sample">
                                                <div class="form-group mb-3">
                                                    <h4>Tahun</h4>
                                                    <select name="year" id="year" class="form-select">
                                                        @for ($i = 2020; $i <= Carbon::now('Asia/Jakarta')->year + 3; $i++)
                                                            <option
                                                                {{ request('year', Carbon::now('Asia/Jakarta')->year) == $i ? 'selected' : '' }}>
                                                                {{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <h4>Bulan</h4>
                                                    <select name="month" id="month" class="form-select">
                                                        @foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $key => $month)
                                                            <option value="{{ $key }}"
                                                                {{ request('month', Carbon::now('Asia/Jakarta')->month) == $key ? 'selected' : '' }}>
                                                                {{ $month }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-info w-100">Filter</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Total Pembayaran -->
                                <div class="col-md-6">
                                    <div class="card shadow-lg text-center">
                                        <div class="card-body">
                                            <h1 class="card-title">Total Pembayaran Bulan Ini</h1>
                                            <h3 class="fs-4 fw-bold text-dark">
                                                Rp. {{ number_format($totalPembayaranPerbulan, 0, ',', '.') }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Button & Modal Pembayaran --}}
                            <button type="submit" class="add btn btn-primary todo-list-add-btn mt-3" data-bs-toggle="modal"
                                data-bs-target="#createPembayaranModalLabel" id="add-task">Tambah Pembayaran
                            </button>

                            <div class="modal fade" id="createPembayaranModalLabel" tabindex="-1"
                                aria-labelledby="createPembayaranModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content bg-dark">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="createPembayaranModalLabel">Tambah Pembayaran</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('admin.pembayaran.store', ['nama_RT' => $nama_RT]) }}"
                                                method="POST">
                                                @csrf

                                                <!-- Field No KK Keluarga (Select Option) -->
                                                <div class="mb-3">
                                                    <label for="no_kk_keluarga" class="form-label">Pilih Keluarga</label>
                                                    <select
                                                        class="form-control @error('no_kk_keluarga') is-invalid @enderror"
                                                        id="no_kk_keluarga" name="no_kk_keluarga" required>
                                                        <option value="" disabled selected>Pilih keluarga...</option>
                                                        @foreach ($keluargas as $keluarga)
                                                            <option value="{{ $keluarga->no_kk }}"
                                                                {{ old('no_kk_keluarga') == $keluarga->no_kk ? 'selected' : '' }}>
                                                                {{ $keluarga->nama_keluarga }} - {{ $keluarga->no_kk }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('no_kk_keluarga')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Field Tanggal Pembayaran -->
                                                <div class="mb-3">
                                                    <label for="tgl_pembayaran" class="form-label">Tanggal
                                                        Pembayaran</label>
                                                    <input type="date"
                                                        class="form-control @error('tgl_pembayaran') is-invalid @enderror"
                                                        id="tgl_pembayaran" name="tgl_pembayaran"
                                                        value="{{ old('tgl_pembayaran') }}" required>
                                                    @error('tgl_pembayaran')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Pilih Bulan -->
                                                <div class="mb-3">
                                                    <label for="month&year" class="form-label">Pilih Bulan dan Tahun IWW
                                                        nya</label>

                                                    <select class="form-control @error('month') is-invalid @enderror"
                                                        id="month" name="month" required>
                                                        @for ($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ old('month') == $i ? 'selected' : '' }}>
                                                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    @error('month')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror

                                                    <br>

                                                    <select class="form-control @error('year') is-invalid @enderror"
                                                        id="year" name="year" required>
                                                        @for ($i = now()->year + 3; $i >= 2020; $i--)
                                                            <option value="{{ $i }}"
                                                                {{ old('year') == $i ? 'selected' : '' }}>
                                                                {{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    @error('year')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Jumlah Pembayaran -->
                                                <div class="mb-3">
                                                    <label for="sejumlah" class="form-label">Jumlah Pembayaran</label>
                                                    <input type="number" class="form-control" id="sejumlah"
                                                        name="sejumlah" value="{{ old('sejumlah') }}" required>
                                                </div>

                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- End Button & Modal Pembayaran --}}

                            <div class="table-responsive pt-3">
                                <table class="table table-dark">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Keluarga</th>
                                            <th>IWW</th>
                                            <th>Tanggal Bayar</th> <!-- New header for payment date -->
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($keluargas as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->nama_keluarga }}</td>
                                                <td>
                                                    <br>
                                                    @if ($pembayarans->isNotEmpty())
                                                        @foreach ($pembayarans->where('no_kk_keluarga', $data->no_kk) as $pembayaran)
                                                            Rp. {{ number_format($pembayaran->sejumlah, 0, ',', '.') }}
                                                            <br><br>
                                                        @endforeach
                                                    @else
                                                        Rp. 0
                                                    @endif
                                                </td>
                                                <td>
                                                    <br>
                                                    @if ($pembayarans->where('no_kk_keluarga', $data->no_kk)->isNotEmpty())
                                                        @foreach ($pembayarans->where('no_kk_keluarga', $data->no_kk) as $pembayaran)
                                                            {{ $pembayaran->tgl_pembayaran ? \Carbon\Carbon::parse($pembayaran->tgl_pembayaran)->format('d-m-Y') : 'Belum ada pembayaran' }}
                                                            <br>
                                                            <br>
                                                        @endforeach
                                                    @else
                                                        Belum ada pembayaran
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-outline-info"
                                                            id="dropdownMenuIconButton3" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            <i class="icon-ellipsis"></i>
                                                        </button>
                                                        <div class="dropdown-menu bg-secondary"
                                                            aria-labelledby="dropdownMenuIconButton3">
                                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#editPembayaranModal-{{ $data->no_kk }}">Edit</button>
                                                            <div class="dropdown-divider"></div>
                                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#deletePembayaranModal-{{ $data->no_kk }}">Hapus</button>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">Tidak ada data pembayaran.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                @foreach ($keluargas as $data)
                                    <div class="modal fade" id="editPembayaranModal-{{ $data->no_kk }}" tabindex="-1"
                                        aria-labelledby="editPembayaranLabel-{{ $data->no_kk }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Pembayaran</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @foreach ($pembayarans->where('no_kk_keluarga', $data->no_kk) as $pembayaran)
                                                        <form
                                                            action="{{ route('admin.pembayaran.update', ['nama_RT' => $nama_RT, 'pembayaran' => $pembayaran->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="form-group">
                                                                <label>Jumlah Pembayaran</label>
                                                                <input type="number" class="form-control"
                                                                    name="sejumlah" value="{{ $pembayaran->sejumlah }}"
                                                                    required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Tanggal Pembayaran</label>
                                                                <input type="date" class="form-control"
                                                                    name="tgl_pembayaran"
                                                                    value="{{ $pembayaran->tgl_pembayaran->format('Y-m-d') }}"
                                                                    required>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary mt-2">Simpan
                                                                Perubahan</button>
                                                            <hr>
                                                        </form>
                                                    @endforeach
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="deletePembayaranModal-{{ $data->no_kk }}"
                                        tabindex="-1" aria-labelledby="deletePembayaranModalLabel-{{ $data->no_kk }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus Pembayaran</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Pilih pembayaran yang ingin dihapus:</p>
                                                    @foreach ($pembayarans->where('no_kk_keluarga', $data->no_kk) as $pembayaran)
                                                        {{-- <form
                                                            action="{{ route('admin.pembayaran.destroy', ['nama_RT' => $nama_RT, 'pembayaran' => $pembayaran->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-block mb-2">
                                                                Hapus Rp
                                                                {{ number_format($pembayaran->sejumlah, 0, ',', '.') }}
                                                                pada
                                                                {{ $pembayaran->tgl_pembayaran->format('d M Y') }}
                                                            </button>
                                                        </form> --}}
                                                        <a href="{{ route('admin.pembayaran.destroy', ['nama_RT' => $nama_RT, 'pembayaran' => $pembayaran->id]) }}"
                                                            class="dropdown-item warna mb-2" data-confirm-delete="true">
                                                            Hapus Rp
                                                            {{ number_format($pembayaran->sejumlah, 0, ',', '.') }}
                                                            pada
                                                            {{ $pembayaran->tgl_pembayaran->format('d M Y') }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach




                                {{-- @foreach ($pembayarans as $data)
                                    @php
                                        $createLog = $data->activityLog->where('activity', 'create')->first();
                                        // $updateLog = $data->activityLog->where('activity', 'update')->last();
                                    @endphp
                                    <div class="modal fade" id="activityLog-{{ $data->id }}" tabindex="-1" aria-labelledby="activityLogLabel-{{ $data->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="activityLogLabel-{{ $data->id }}">
                                                        Aktifitas CRUD</h5>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Dibuat/Diubah oleh :
                                                            <b>{{ $createLog?->activity ?? 'Tidak Diketahui' }}</b>
                                                            By
                                                            <b>{{ $createLog?->user?->name ?? 'Tidak Diketahui' }}</b>
                                                        </label>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Waktu Aktifitas :
                                                            <small>
                                                                {{ $createLog?->performed_at
                                                                    ? \Carbon\Carbon::parse($createLog->performed_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s')
                                                                    : '-' }}
                                                            </small>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Diubah oleh :
                                                            <b>{{ $updateLog?->activity ?? 'Belum diedit' }}</b>
                                                            By
                                                            <b>{{ $updateLog?->user?->name ?? 'Belum diedit' }}</b>
                                                        </label>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Waktu Aktifitas :
                                                            <small>
                                                                {{ $updateLog?->performed_at
                                                                    ? \Carbon\Carbon::parse($updateLog->performed_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s')
                                                                    : '-' }}
                                                            </small>
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var createPembayaranModalLabel = new bootstrap.Modal(document.getElementById(
                    'createPembayaranModalLabel'));
                createPembayaranModalLabel.show();
            });
        </script>
    @endif


    @include('sweetalert::alert')

@endsection
