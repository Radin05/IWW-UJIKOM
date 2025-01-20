@extends('layouts.navbar')

@section('title', 'Super Admin Add RT')

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

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-4 grid-margin stretch-card">
                    <div class="card card-tale">
                        <div class="card-body">
                            <h4 class="card-title">Tambah RT</h4>
                            <p class="card-description">
                                @if (session('error'))
                                    <script>
                                        alert("{{ session('error') }}");
                                    </script>
                                @endif
                            </p>
                            <form class="forms-sample" action="{{ route('superadmin.rt.store') }}" method="POST"
                                id="formData">
                                @csrf

                                <div class="form-group mb-3">
                                    <label for="nama_RT" class="form-label">Nama RT</label>
                                    <input type="text" class="form-control @error('nama_RT') is-invalid @enderror"
                                        id="nama_RT" name="nama_RT" value="{{ old('nama_RT') }}">
                                    @error('nama_RT')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="nama_jalan" class="form-label">Nama Jalan</label>
                                    <input type="text" class="form-control @error('nama_jalan') is-invalid @enderror"
                                        id="nama_jalan" name="nama_jalan" value="{{ old('nama_jalan') }}">
                                    @error('nama_jalan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="button" class="btn btn-danger" onclick="hapusPesan()">Batal</button>
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 grid-margin stretch-card">
                    <div class="card card-tale">
                        <div class="card-body">


                            <div class="table-responsive pt-3">
                                <table class="table table-dark">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>RT</th>
                                            <th>Nama Jalan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rts as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->nama_RT }}</td>
                                                <td>{{ $data->nama_jalan }}</td>
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
                                                                data-bs-target="#editRTModal-{{ $data->id }}">Edit</button>

                                                            <div class="dropdown-divider"></div>

                                                            <form action="{{ route('superadmin.rt.destroy', $data->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus RT ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item">Hapus</button>
                                                            </form>

                                                            <div class="dropdown-divider"></div>

                                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#activityLog">Aktifitas</button>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @foreach ($rts as $data)
                                    <div class="modal fade" id="editRTModal-{{ $data->id }}" tabindex="-1"
                                        aria-labelledby="editRTModal-{{ $data->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editRTModal-{{ $data->id }}">
                                                        Edit RT</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('superadmin.rt.update', $data->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-3">
                                                            <label for="nama_RT-{{ $data->id }}"
                                                                class="form-label">Nama RT</label>
                                                            <input type="text"
                                                                class="form-control @error('nama_RT') is-invalid @enderror"
                                                                id="nama_RT-{{ $data->id }}" name="nama_RT"
                                                                value="{{ $data->nama_RT }}">
                                                            @error('nama_RT')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="nama_jalan-{{ $data->id }}"
                                                                class="form-label">Nama Jalan</label>
                                                            <input type="text"
                                                                class="form-control @error('nama_jalan') is-invalid @enderror"
                                                                id="nama_jalan-{{ $data->id }}" name="nama_jalan"
                                                                value="{{ $data->nama_jalan }}">
                                                            @error('nama_jalan')
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
                                @endforeach

                                @foreach ($rts as $data)
                                    @php
                                    $createLog = $data->activityLog->where('activity', 'create')->first();
                                    $updateLog = $data->activityLog->where('activity', 'update')->last();
                                    @endphp
                                    <div class="modal fade" id="activityLog" tabindex="-1" aria-labelledby="activityLog"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="activityLog">
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
                var editRTModal = new bootstrap.Modal(document.getElementById('editRTModal'));
                editRTModal.show();
            });
        </script>
    @endif

@endsection
