@extends('layouts.app')

@section('title', 'Admin Data Uang Kas RW')

@section('content')

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
    </style>

    <div class="main-panel mt-4">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card card-tale">
                        <div class="card-body">

                            <div class="row justify-content-center align-items-start mb-4">
                                <!-- Cash Balance Card -->
                                <div class="col-md-4">
                                    <div class="card text-center shadow-sm">
                                        <div class="card-body p-4">
                                            <h3 class="text-dark mb-3">Jumlah Kas RW</h3>

                                            @if (!$kasRw)
                                                <p class="card-text text-danger">Kas belum tersedia.</p>
                                            @else
                                                <h2 class="card-text fw-bold text-success">Rp
                                                    {{ number_format($kasRw->jumlah_kas_rw, 0, ',', '.') }}</h2>
                                            @endif

                                            <form action="{{ route('superadmin.kas.update') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary mt-5">Perbarui Kas</button>
                                            </form>
                                            <button type="button" class="btn btn-secondary mt-3" data-bs-toggle="modal"
                                                data-bs-target="#uangEksternalModal">
                                                Uang Eksternal
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Transaction History Card -->
                                <div class="col-md-8">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h4 class="text-center text-dark fw-bold mb-3">Riwayat Kas RW</h4>
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <thead class="table-dark text-center">
                                                        <tr>
                                                            <th width="10%">No</th>
                                                            <th>Nominal</th>
                                                            <th>Tanggal Pembaruan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($riwayatKas as $index => $item)
                                                            <tr>
                                                                <td class="text-center">
                                                                    {{ $index + $riwayatKas->firstItem() }}</td>
                                                                <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                                                <td class="text-center">
                                                                    {{ \Carbon\Carbon::parse($item->tgl_pembaruan_kas)->format('d M Y') }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center">Belum ada histori kas
                                                                    RT.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="d-flex justify-content-center mt-3">
                                                {{ $riwayatKas->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Uang Eksternal -->
                            <div class="modal fade" id="uangEksternalModal" tabindex="-1"
                                aria-labelledby="uangEksternalModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content bg-dark">
                                        <form action="{{ route('superadmin.uang-tambahan-kas.store') }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="uangEksternalModalLabel">Tambah Uang Eksternal
                                                </h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="nominal" class="form-label">Nominal Uang Eksternal</label>
                                                    <input type="number" class="form-control" id="nominal" name="nominal"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="keterangan" class="form-label">Keterangan</label>
                                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                                                </div>
                                                <!-- Jika dibutuhkan untuk mengaitkan transaksi ke kas_rw, kamu bisa menyisipkan hidden input -->
                                                <input type="hidden" name="kas_rw_id"
                                                    value="{{ $kasRw ? $kasRw->id : '' }}">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Tambah</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">

                                <h4 class="text-white">Uang Tambahan Kas</h4>
                                <table class="table table-dark table-striped table-hover" id="example">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nominal</th>
                                            <th>Keterangan</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($uangTambahans as $key => $data)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>Rp {{ number_format($data->nominal ?? 0, 2, ',', '.') }}</td>
                                                <td>{!! $data->keterangan ?: '-' !!}</td>
                                                <td>{{ $data->created_at ? $data->created_at->format('d F Y') : '-' }}
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-outline-info"
                                                            id="dropdownMenuIconButton-{{ $data->id }}"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="icon-ellipsis"></i>
                                                        </button>
                                                        <div class="dropdown-menu bg-info"
                                                            aria-labelledby="dropdownMenuIconButton-{{ $data->id }}">
                                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#editUangTambahanModal-{{ $data->id }}">Edit</button>

                                                            <div class="dropdown-divider"></div>

                                                            <form
                                                                action="{{ route('superadmin.uang-tambahan-kas.destroy', $data->id) }}"
                                                                method="POST" class="d-inline delete-form"
                                                                data-type="uang-tambahan" data-title="Hapus Uang Tambahan?"
                                                                data-text="Yakin ingin menghapus uang tambahan ini?">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="dropdown-item text-danger">Hapus</button>
                                                            </form>

                                                            <div class="dropdown-divider"></div>

                                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#activityLog-{{ $data->id }}">Aktifitas</button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {{ $uangTambahans->links() }}
                                </div>

                                @foreach ($uangTambahans as $data)
                                    <div class="modal fade" id="editUangTambahanModal-{{ $data->id }}"
                                        tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Uang Tambahan</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        action="{{ route('superadmin.uang-tambahan-kas.update', $data->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-3">
                                                            <label for="nominal" class="form-label">Nominal</label>
                                                            <input type="number" class="form-control" name="nominal"
                                                                value="{{ $data->nominal }}">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="keterangan" class="form-label">Keterangan</label>
                                                            <input type="text" class="form-control" name="keterangan"
                                                                value="{{ $data->keterangan }}">
                                                        </div>

                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $createLog = $data->activityLog->where('activity', 'create')->first();
                                        $updateLog = $data->activityLog->where('activity', 'update')->last();
                                    @endphp

                                    <div class="modal fade" id="activityLog-{{ $data->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Aktifitas CRUD</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label>Dibuat oleh:</label>
                                                        <b>{{ $createLog?->user?->name ?? 'Tidak Diketahui' }}</b>
                                                        <br>
                                                        <label>Aktivitas:</label>
                                                        <b>{{ $createLog?->activity ?? 'Tidak Diketahui' }}</b>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label>Waktu Aktivitas:</label>
                                                        @if ($createLog && $createLog->performed_at)
                                                            <small>{{ \Carbon\Carbon::parse($createLog->performed_at)->translatedFormat('d F Y H:i:s') }}</small>
                                                        @else
                                                            <small>-</small>
                                                        @endif
                                                    </div>

                                                    <hr>

                                                    <div class="mb-3">
                                                        <label>Diubah oleh:</label>
                                                        <b>{{ $updateLog?->user?->name ?? 'Belum diedit' }}</b>
                                                        <br>
                                                        <label>Deskripsi Perubahan:</label>
                                                        <small>{!! nl2br(e($updateLog?->description ?? 'Belum ada perubahan')) !!}</small>
                                                    </div>

                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Kembali</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                            <hr class="my-4 text-white">

                            <div class="card bg-dark text-white p-4">
                                <h5 class="mb-3">Tambah Pengeluaran Kas RW</h5>

                                <form action="{{ route('superadmin.pengeluaran-kas-rw.store') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="nominal" class="form-label">Nominal</label>
                                        <input type="number" class="form-control @error('nominal') is-invalid @enderror"
                                            id="nominal" name="nominal" min="0" step="0.01" required>
                                        @error('nominal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="kegiatan_id" class="form-label">Kegiatan</label>
                                        <select class="form-control @error('kegiatan_id') is-invalid @enderror"
                                            name="kegiatan_id">
                                            <option value="">-- Untuk hal lain --</option>
                                            @foreach ($kegiatans as $kegiatan)
                                                <option value="{{ $kegiatan->id }}">
                                                    {{ $kegiatan->nama_kegiatan }}
                                                    (<small>{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('d M Y') }}</small>)
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kegiatan_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="exampleFormControlTextarea1"
                                            name="keterangan" rows="3"></textarea>
                                        @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="tgl_pengeluaran" class="form-label">Tanggal
                                            Pengeluaran Kas</label>
                                        <input type="date"
                                            class="form-control @error('tgl_pengeluaran') is-invalid @enderror"
                                            id="tgl_pengeluaran" name="tgl_pengeluaran"
                                            value="{{ old('tgl_pengeluaran') }}" required>
                                        @error('tgl_pengeluaran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                            </div>

                            <hr class="my-4 text-white">

                            <div class="table-responsive mt-5">
                                <h4 class="mb-4 fw-bold text-white">Daftar Pengeluaran Kas RW</h4>

                                <!-- Total Pengeluaran Card -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="card border-success shadow-sm">
                                            <div class="card-body d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0 text-dark fw-bold me-2">Total Pengeluaran Kas RW:&nbsp;
                                                </h5>
                                                <h5 class="mb-0 text-success fw-bold">
                                                    Rp {{ number_format($totalPengeluaranRw, 2, ',', '.') }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-dark table-striped table-hover" id="example">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nominal</th>
                                            <th>Kegiatan</th>
                                            <th>Keterangan</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pengeluarans as $key => $data)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>Rp {{ number_format($data->nominal, 2, ',', '.') }}</td>
                                                <td>
                                                    {{ $data->kegiatan ? $data->kegiatan->nama_kegiatan : '-- Untuk hal lain --' }}
                                                </td>
                                                <td>{!! $data->keterangan !!}</td>
                                                <td>{{ $data->tgl_pengeluaran }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-outline-info"
                                                            id="dropdownMenuIconButton3" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            <i class="icon-ellipsis"></i>
                                                        </button>
                                                        <div class="dropdown-menu bg-info"
                                                            aria-labelledby="dropdownMenuIconButton3">
                                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#editPengeluaranModal-{{ $data->id }}">Edit</button>

                                                            <div class="dropdown-divider"></div>

                                                            <form
                                                                action="{{ route('superadmin.pengeluaran-kas-rw.destroy', $data->id) }}"
                                                                method="POST" class="d-inline delete-form"
                                                                data-type="pengeluaran" data-title="Hapus Pengeluaran?"
                                                                data-text="Yakin ingin menghapus pengeluaran ini?">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="dropdown-item text-danger">Hapus</button>
                                                            </form>

                                                            <div class="dropdown-divider"></div>

                                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#activityLog-{{ $data->id }}">Aktifitas</button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {{ $pengeluarans->links() }}
                                </div>

                                @foreach ($pengeluarans as $data)
                                    <div class="modal fade" id="editPengeluaranModal-{{ $data->id }}" tabindex="-1"
                                        aria-labelledby="editPengeluaranModal-{{ $data->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="editPengeluaranModal-{{ $data->id }}">
                                                        Edit Pengeluaran</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        action="{{ route('superadmin.pengeluaran-kas-rw.update', $data->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-3">
                                                            <label for="nominal" class="form-label">Nominal</label>
                                                            <input type="number"
                                                                class="form-control @error('nominal') is-invalid @enderror"
                                                                id="nominal" name="nominal"
                                                                value="{{ $data->nominal }}">
                                                            @error('nominal')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="kegiatan_id" class="form-label">Kegiatan</label>
                                                            <select
                                                                class="form-control @error('kegiatan_id') is-invalid @enderror"
                                                                name="kegiatan_id">
                                                                <option value="">-- Untuk hal lain --</option>
                                                                @foreach ($kegiatans as $kegiatan)
                                                                    <option value="{{ $kegiatan->id }}"
                                                                        {{ isset($pengeluaran) && $pengeluaran->kegiatan_id == $kegiatan->id ? 'selected' : '' }}>
                                                                        {{ $kegiatan->nama_kegiatan }}
                                                                        (<small>{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('d M Y') }}</small>)
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('kegiatan_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="keterangan" class="form-label">Keterangan</label>
                                                            <input type="text"
                                                                class="form-control @error('keterangan') is-invalid @enderror"
                                                                id="exampleFormControlTextarea1" name="keterangan"
                                                                value="{{ $data->keterangan }}">
                                                            @error('keterangan')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="tgl_pengeluaran" class="form-label">Tanggal
                                                                Pengeluaran</label>
                                                            <input type="date"
                                                                class="form-control @error('tgl_pengeluaran') is-invalid @enderror"
                                                                id="tgl_pengeluaran" name="tgl_pengeluaran"
                                                                value="{{ $data->tgl_pengeluaran }}">
                                                            @error('tgl_pengeluaran')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan
                                                            Perubahan</button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $createLog = $data->activityLog->where('activity', 'create')->first();
                                        $updateLog = $data->activityLog->where('activity', 'update')->last();
                                    @endphp

                                    <div class="modal fade" id="activityLog-{{ $data->id }}" tabindex="-1"
                                        aria-labelledby="activityLog-{{ $data->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="activityLog-{{ $data->id }}">
                                                        Aktifitas CRUD
                                                    </h5>
                                                </div>

                                                <div class="modal-body">
                                                    <!-- Create Activity -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Dibuat oleh :</label>
                                                        <b>{{ $createLog?->user?->name ?? 'Tidak Diketahui' }}</b>
                                                        <label class="form-label">Aktivitas :</label>
                                                        <b>{{ $createLog?->activity ?? 'Tidak Diketahui' }}</b>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Waktu Aktivitas :</label>
                                                        <small>
                                                            {{ $createLog?->performed_at
                                                                ? \Carbon\Carbon::parse($createLog->performed_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s')
                                                                : '-' }}
                                                        </small>
                                                    </div>

                                                    <hr>

                                                    <!-- Update Activity -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Diubah oleh :</label>
                                                        <b>{{ $updateLog?->user?->name ?? 'Belum diedit' }}</b>
                                                        <label class="form-label">Aktivitas :</label>
                                                        <b>{{ $updateLog?->activity ?? '-' }}</b>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Deskripsi Perubahan:</label>
                                                        <small>
                                                            <ul>
                                                                <li>{!! $updateLog?->description ?? 'Belum ada perubahan' !!}</li>
                                                            </ul>
                                                        </small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Waktu Aktivitas :</label>
                                                        <small>
                                                            {{ $updateLog?->performed_at
                                                                ? \Carbon\Carbon::parse($updateLog->performed_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s')
                                                                : '-' }}
                                                        </small>
                                                    </div>

                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Kembali</button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.4.1/tinymce.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: '#exampleFormControlTextarea1',
                height: 300,
                toolbar: 'undo redo | bold italic | bullist numlist | alignleft aligncenter alignright | outdent indent',
                forced_root_block: false, // Mencegah TinyMCE menambahkan elemen HTML seperti <p>
                valid_elements: '', // Mencegah semua tag HTML
                setup: function(editor) {
                    editor.on('init', function() {
                        editor.getBody().style.width = '100%';
                    });

                    editor.on('input', function() {
                        editor.getBody().style.height = 'auto';
                        editor.getBody().style.height = (editor.getBody().scrollHeight) + 'px';
                    });
                },
            });
        });
    </script>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var editPengeluaranModal = new bootstrap.Modal(document.getElementById('editPengeluaranModal'));
                editPengeluaranModal.show();
            });
        </script>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".delete-form").forEach(function(form) {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();

                    let type = form.dataset.type; // Ambil tipe (uang tambahan atau pengeluaran)
                    let title = form.dataset.title || "Yakin ingin menghapus?";
                    let text = form.dataset.text || "Data akan dihapus secara permanen!";

                    let icon = type === "pengeluaran" ? "error" : "warning";
                    let confirmButtonColor = type === "pengeluaran" ? "#c82333" : "#d33";

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: confirmButtonColor,
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, Hapus!",
                        cancelButtonText: "Batal",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("form.delete-form").forEach(function(form) {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: form.dataset.title || "Yakin ingin menghapus?",
                        text: form.dataset.text || "Data akan dihapus secara permanen!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, Hapus!",
                        cancelButtonText: "Batal",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    @include('sweetalert::alert')

@endsection

@push('scripts')
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap4.js"></script>
    <script>
        new DataTable('#example', {
            pageLength: 10, // Jumlah data per halaman
            lengthChange: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ada data ditemukan",
                info: "Menampilkan _START_ hingga _END_ dari total _TOTAL_ data",
                infoEmpty: "Data tidak tersedia",
                infoFiltered: "(disaring dari _MAX_ total data)"
            }
        });
    </script>
@endpush
