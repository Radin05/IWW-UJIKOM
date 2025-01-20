@extends('layouts.navbar')

@section('title', 'Super Admin Add Admin RT')

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
                            <form class="forms-sample" action="{{ route('superadmin.manajemen-admin.store') }}"
                                method="POST" id="formData">
                                @csrf

                                <div class="form-group mb-2">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-2">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-2">
                                    <label for="rt_id" class="form-label">RT</label>
                                    <select class="form-control @error('rt_id') is-invalid @enderror" id="rt_id"
                                        name="rt_id" required>
                                        <option value="" disabled selected>Select RT</option>
                                        @foreach ($rts as $rt)
                                            <option value="{{ $rt->id }}"
                                                {{ old('rt_id') == $rt->id ? 'selected' : '' }}>
                                                {{ $rt->nama_RT }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('rt_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-2">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-2">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation">
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
                            <h4 class="text-light text-center">Data Admin</h4>

                            <div class="table-responsive pt-3">
                                <table class="table table-bordered text-light bg-dark">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>RT</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($admin as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->rt->nama_RT }}</td>

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
                                                                data-bs-target="#editAdminModal-{{ $user->id }}">Edit</button>

                                                            <div class="dropdown-divider"></div>

                                                            <form action="{{ route('superadmin.manajemen-admin.destroy', $user->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus RT ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="dropdown-item" type="submit">Hapus</button>
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


                                @foreach ($admin as $data)
                                    <div class="modal fade" id="editAdminModal-{{ $data->id }}" tabindex="-1"
                                        aria-labelledby="editAdminModal-{{ $data->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editAdminModal-{{ $data->id }}">
                                                        Edit RT</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        action="{{ route('superadmin.manajemen-admin.update', $data->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-3">
                                                            <label for="name" class="form-label">Nama
                                                                RT</label>
                                                            <input type="text"
                                                                class="form-control @error('name') is-invalid @enderror"
                                                                id="name" name="name"
                                                                value="{{ $data->name }}">
                                                            @error('name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="email"
                                                                class="form-label">Email</label>
                                                            <input type="email"
                                                                class="form-control @error('email') is-invalid @enderror"
                                                                id="email" name="email"
                                                                value="{{ $data->email }}">
                                                            @error('email')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label for="rt_id-{{ $data->id }}"
                                                                class="form-label">RT</label>
                                                            <select
                                                                class="form-control @error('rt_id') is-invalid @enderror"
                                                                id="rt_id-{{ $data->id }}" name="rt_id" required>
                                                                <option value="" disabled
                                                                    {{ old('rt_id', $data->rt_id) ? '' : 'selected' }}>
                                                                    Select RT</option>
                                                                @foreach ($rts as $rt)
                                                                    <option value="{{ $rt->id }}"
                                                                        {{ old('rt_id', $data->rt_id) == $rt->id ? 'selected' : '' }}>
                                                                        {{ $rt->nama_RT }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('rt_id')
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

                                @foreach ($admin as $data)
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var createAdminModal = new bootstrap.Modal(document.getElementById('createAdminModal'));
                createAdminModal.show();
            });
        </script>
    @endif

@endsection
