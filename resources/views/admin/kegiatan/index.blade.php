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
                            <h4 class="card-title">Tambah Kegiatan {{ Auth::user()->rt->nama_RT }}</h4>
                            <p class="card-description">
                                @if (session('error'))
                                    <script>
                                        alert("{{ session('error') }}");
                                    </script>
                                @endif
                            </p>

                            <form class="forms-sample" action="{{ route('admin.kegiatan.store', ['nama_RT' => $nama_RT]) }}"
                                method="POST" id="formData">
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
                                            <th>Jam Kegiatan</th>
                                            <th>Status</th>
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
                                                <td>{{ $data->status }}</td>
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
                                                                action="{{ route('admin.kegiatan.destroy', ['nama_RT' => $nama_RT, 'kegiatan' => $data->id]) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item"
                                                                    onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                                            </form>
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
                                                        Edit Kegiatan</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        action="{{ route('admin.kegiatan.update', ['nama_RT' => $nama_RT, 'kegiatan' => $data->id]) }}"
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
                                                                <option value="Sudah selesai"
                                                                    {{ $data->status == 'Sudah selesai' ? 'selected' : '' }}>
                                                                    Sudah selesai</option>
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
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function hapusPesan() {
            document.getElementById('formData').reset();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var editModal = new bootstrap.Modal(document.getElementById('editKegiatanModal'));
                editModal.show();
            });
        </script>
    @endif

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

    <script>
        (function() {
            var disqus_shortname = "mylaravelproject";
            var d = document,
                s = d.createElement('script');
            s.src = 'https://mylaravelproject.disqus.com/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        })();
    </script>

    @include('sweetalert::alert')

@endsection
