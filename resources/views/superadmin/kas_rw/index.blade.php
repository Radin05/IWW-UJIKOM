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

                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <!-- Card untuk jumlah kas RW -->
                                <div class="card text-center shadow-sm">
                                    <div class="card-body">
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

                            <div class="table-responsive">
                                <table class="table table-dark">
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
                                                            {{--
                                                            <a href="{{ route('superadmin.kas.destroy', $data->id) }}"
                                                                class="dropdown-item" data-confirm-delete="true">Hapus</a> --}}

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

                                    {{-- @php
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
                                                        <label class="form-label">Waktu Aktifitas :</label>
                                                        <small>
                                                            {{ $createLog?->performed_at
                                                                ? \Carbon\Carbon::parse($createLog->performed_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s')
                                                                : '-' }}
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="modal-body">
                                                    <!-- Update Activity -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Diubah oleh :</label>
                                                        <b>{{ $updateLog?->user?->name ?? 'Belum diedit' }}</b>
                                                        <label class="form-label">Aktivitas :</label>
                                                        <b>{{ $updateLog?->activity ?? 'Belum diedit' }}</b>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Deskripsi Perubahan:</label>
                                                        <small>
                                                            <ul>
                                                                {!! $updateLog?->description ?? 'Belum ada perubahan' !!}
                                                            </ul>
                                                        </small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Waktu Aktifitas :</label>
                                                        <small>
                                                            {{ $updateLog?->performed_at
                                                                ? \Carbon\Carbon::parse($updateLog->performed_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s')
                                                                : '-' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
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

    @include('sweetalert::alert')

@endsection
