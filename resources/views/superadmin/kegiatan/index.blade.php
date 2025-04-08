@extends('layouts.app')

@section('title', 'Super Admin - Kegiatan RW')

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
                            <h4 class="card-title">Tambah Kegiatan RW</h4>
                            <p class="card-description">
                                @if (session('error'))
                                    <script>
                                        alert("{{ session('error') }}");
                                    </script>
                                @endif
                            </p>

                            <form class="forms-sample" action="{{ route('superadmin.kegiatan-rw.store') }}" method="POST"
                                id="formData">
                                @csrf

                                <div class="form-group mb-3">
                                    <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
                                    <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror"
                                        id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}">
                                    @error('nama_kegiatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status"
                                        class="form-control @error('status') is-invalid @enderror">
                                        <option value="Rapat" {{ 'Rapat' ? 'selected' : '' }}>Rapat</option>
                                        <option value="Kerja bakti" {{ 'Kerja bakti' ? 'selected' : '' }}>Kerja bakti
                                        </option>
                                        <option value="Kegiatan" {{ 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" id="exampleFormControlTextarea1"
                                        class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="tanggal_kegiatan" class="form-label">Tanggal Kegiatan</label>
                                    <input type="date"
                                        class="form-control @error('tanggal_kegiatan') is-invalid @enderror"
                                        id="tanggal_kegiatan" name="tanggal_kegiatan"
                                        value="{{ old('tanggal_kegiatan') }}">
                                    @error('tanggal_kegiatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="jam_kegiatan" class="form-label">Jam Kegiatan</label>
                                    <input type="time" class="form-control @error('jam_kegiatan') is-invalid @enderror"
                                        id="jam_kegiatan" name="jam_kegiatan" value="{{ old('jam_kegiatan') }}">
                                    @error('jam_kegiatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mt-2">
                                    <button type="button" class="btn btn-danger" onclick="hapusPesan()">Batal</button>
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card card-tale">
                        <div class="card-body">
                            <div class="table-responsive pt-3">
                                <table class="table table-dark">
                                    <thead>
                                        <tr>
                                            <th>Nama Kegiatan</th>
                                            <th>Deskripsi</th>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kegiatan as $key => $data)
                                            <tr>
                                                <td>{{ $data->nama_kegiatan }}</td>
                                                <td>{!! $data->deskripsi !!}</td>
                                                <td>{{ $data->tanggal_kegiatan }}</td>
                                                <td>{{ $data->jam_kegiatan }}</td>
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
                                                                data-bs-target="#editKegiatanModal-{{ $data->id }}">Edit</button>

                                                            <div class="dropdown-divider"></div>

                                                            <form
                                                                action="{{ route('superadmin.kegiatan-rw.destroy', $data->id) }}"
                                                                method="POST" class="d-inline" data-confirm="true"
                                                                data-title="Hapus Kegiatan?"
                                                                data-text="Yakin ingin menghapus Kegiatan ini?">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item">Hapus</button>
                                                            </form>

                                                            <div class="dropdown-divider"></div>

                                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#activityLog-{{ $data->id }}">
                                                                Aktivitas
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @foreach ($kegiatan as $data)
                                    <div class="modal fade" id="editKegiatanModal-{{ $data->id }}" tabindex="-1"
                                        aria-labelledby="editKegiatanModal-{{ $data->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editKegiatanModal-{{ $data->id }}">
                                                        Edit Kegiatan RW</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('superadmin.kegiatan-rw.update', $data->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-3">
                                                            <label for="nama_kegiatan-{{ $data->id }}"
                                                                class="form-label">Nama Kegiatan</label>
                                                            <input type="text" class="form-control"
                                                                id="nama_kegiatan-{{ $data->id }}"
                                                                name="nama_kegiatan" value="{{ $data->nama_kegiatan }}">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="status-{{ $data->id }}"
                                                                class="form-label">Status</label>
                                                            <select name="status" id="status-{{ $data->id }}"
                                                                class="form-control">
                                                                <option value="Rapat"
                                                                    {{ $data->status == 'Rapat' ? 'selected' : '' }}>Rapat
                                                                </option>
                                                                <option value="Kerja bakti"
                                                                    {{ $data->status == 'Kerja bakti' ? 'selected' : '' }}>
                                                                    Kerja bakti</option>
                                                                <option value="Kegiatan"
                                                                    {{ $data->status == 'Kegiatan' ? 'selected' : '' }}>
                                                                    Kegiatan</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="deskripsi-{{ $data->id }}"
                                                                class="form-label">Deskripsi</label>
                                                            <textarea class="form-control" id="exampleFormControlTextarea1" name="deskripsi">{{ $data->deskripsi }}</textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="tanggal_kegiatan-{{ $data->id }}"
                                                                class="form-label">Tanggal Kegiatan</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_kegiatan-{{ $data->id }}"
                                                                name="tanggal_kegiatan"
                                                                value="{{ $data->tanggal_kegiatan }}">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="jam_kegiatan-{{ $data->id }}"
                                                                class="form-label">Jam Kegiatan</label>
                                                            <input type="time" class="form-control"
                                                                id="jam_kegiatan-{{ $data->id }}" name="jam_kegiatan"
                                                                value="{{ \Carbon\Carbon::parse($data->jam_kegiatan)->format('H:i') }}">
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
                                        aria-labelledby="activityLogLabel-{{ $data->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark text-white">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">Aktivitas CRUD - {{ $data->nama_kegiatan }}
                                                    </h5>
                                                </div>

                                                <div class="modal-body">
                                                    <!-- Create Activity -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Dibuat oleh:</label>
                                                        <b>{{ $createLog?->user?->name ?? 'Tidak Diketahui' }}</b>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Waktu Aktivitas:</label>
                                                        <small>
                                                            {{ $createLog?->performed_at ? \Carbon\Carbon::parse($createLog->performed_at)->translatedFormat('d F Y H:i:s') : '-' }}
                                                        </small>
                                                    </div>

                                                    <hr class="border-light">

                                                    <!-- Update Activity -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Diubah oleh:</label>
                                                        <b>{{ $updateLog?->user?->name ?? '-' }}</b>
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
                                                        <label class="form-label">Waktu Aktivitas:</label>
                                                        <small>
                                                            {{ $updateLog?->performed_at ? \Carbon\Carbon::parse($updateLog->performed_at)->translatedFormat('d F Y H:i:s') : '-' }}
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
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


    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.4.1/tinymce.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function hapusPesan() {
            document.getElementById('formData').reset();
        }
    </script>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var editModal = new bootstrap.Modal(document.getElementById('editKegiatanModal'));
                editModal.show();
            });
        </script>
    @endif

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("form[data-confirm]").forEach(function(form) {
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
