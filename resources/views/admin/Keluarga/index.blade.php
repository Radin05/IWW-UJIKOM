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
    </style>

    <div class="main-panel mt-4">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card card-tale">
                        <div class="card-body">

                            <style>
                                .form-sample {
                                    margin: 0 auto;
                                    max-width: 600px;
                                }

                                .form-group h4 {
                                    color: black;
                                    margin-bottom: 10px;
                                    /* Jarak antara judul dan elemen form */
                                }

                                .form-select {
                                    width: 100%;
                                    /* Pastikan dropdown mengikuti lebar penuh */
                                }
                            </style>


                            <button type="submit" class="add btn btn-primary todo-list-add-btn" data-bs-toggle="modal"
                                data-bs-target="#createKeluargaModal" id="add-task">Add
                            </button>

                            <div class="modal fade" id="createKeluargaModal" tabindex="-1"
                                aria-labelledby="createKeluargaModalLabel" aria-hidden="true">

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if (session('error'))
                                    <script>
                                        alert("{{ session('error') }}");
                                    </script>
                                @endif

                                <div class="modal-dialog">
                                    <div class="modal-content bg-dark">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="createKeluargaModalLabel">Tambah Keluarga</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('admin.warga.store', ['nama_RT' => $nama_RT]) }}"
                                                method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="no_kk" class="form-label">Nomor KK</label>
                                                    <input type="text"
                                                        class="form-control @error('no_kk') is-invalid @enderror"
                                                        id="no_kk" name="no_kk" value="{{ old('no_kk') }}">
                                                    @error('no_kk')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="nama_keluarga" class="form-label">Nama Keluarga</label>
                                                    <input type="text"
                                                        class="form-control @error('nama_keluarga') is-invalid @enderror"
                                                        id="nama_keluarga" name="nama_keluarga"
                                                        value="{{ old('nama_keluarga') }}">
                                                    @error('nama_keluarga')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="alamat" class="form-label">Alamat</label>
                                                    <input type="text"
                                                        class="form-control @error('alamat') is-invalid @enderror"
                                                        id="alamat" name="alamat" value="{{ old('alamat') }}">
                                                    @error('alamat')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="no_telp" class="form-label">Nomor HP</label>
                                                    <input type="text"
                                                        class="form-control @error('no_telp') is-invalid @enderror"
                                                        id="no_telp" name="no_telp" value="{{ old('no_telp') }}">
                                                    @error('no_telp')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="rt_id" class="form-label">RT</label>
                                                    <select class="form-control @error('rt_id') is-invalid @enderror"
                                                        id="rt_id" name="rt_id">
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

                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive pt-3">
                                <table class="table table-dark">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Keluarga</th>
                                            <th>Alamat</th>
                                            <th>Email</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($keluargas as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->nama_keluarga }}</td>
                                                <td>{{ $data->alamat }}</td>
                                                <td>
                                                    @php
                                                        $user = $users->firstWhere('no_kk_keluarga', $data->no_kk);
                                                    @endphp
                                                    {{ $user->email ?? '-' }}
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

                                                            @php
                                                                $user = $users->firstWhere(
                                                                    'no_kk_keluarga',
                                                                    $data->no_kk,
                                                                );
                                                            @endphp

                                                            @if ($user)
                                                                <!-- Jika sudah memiliki akun -->
                                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                                    data-bs-target="#editAkunUserModal-{{ $data->no_kk }}">
                                                                    Edit Akun Keluarga
                                                                </button>
                                                            @else
                                                                <!-- Jika belum memiliki akun -->
                                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                                    data-bs-target="#addAkunUserModal-{{ $data->no_kk }}">
                                                                    Tambahkan Akun Keluarga
                                                                </button>
                                                            @endif

                                                            <div class="dropdown-divider"></div>

                                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#editUserModal-{{ $data->no_kk }}">Edit</button>

                                                            <div class="dropdown-divider"></div>

                                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#activityLog-{{ $data->no_kk }}">Aktifitas</button>

                                                            <div class="dropdown-divider"></div>

                                                            <form
                                                                action="{{ route('admin.warga.destroy', ['nama_RT' => $nama_RT, 'warga' => $data->no_kk]) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="dropdown-item">Hapus</button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                                @foreach ($keluargas as $data)
                                    {{-- Modal Add Akun --}}
                                    <div class="modal fade" id="addAkunUserModal-{{ $data->no_kk }}" tabindex="-1"
                                        aria-labelledby="addAkunUserModalLabel-{{ $data->no_kk }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="addAkunUserModalLabel-{{ $data->no_kk }}">
                                                        Buat Akun Keluarga {{ $data->nama_keluarga }}</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        action="{{ route('admin.warga.storeAkun', ['nama_RT' => $nama_RT, 'keluarga' => $data->no_kk]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="no_kk_keluarga" class="form-label">Nomor KK
                                                                Keluarga</label>
                                                            <input type="text"
                                                                class="form-control @error('no_kk_keluarga') is-invalid @enderror"
                                                                id="no_kk_keluarga" name="no_kk_keluarga"
                                                                value="{{ old('no_kk_keluarga', $data->no_kk) }}"
                                                                readonly>
                                                            @error('no_kk_keluarga')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input type="email"
                                                                class="form-control @error('email') is-invalid @enderror"
                                                                id="email" name="email"
                                                                value="{{ old('email') }}">
                                                            @error('email')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="password" class="form-label">Password</label>
                                                            <input type="password"
                                                                class="form-control @error('password') is-invalid @enderror"
                                                                id="password" name="password">
                                                            @error('password')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group mb-2">
                                                            <label for="password_confirmation"
                                                                class="form-label">Konfirmasi
                                                                Password</label>
                                                            <input type="password" class="form-control"
                                                                id="password_confirmation" name="password_confirmation">
                                                        </div>

                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Edit Akun --}}
                                    <div class="modal fade" id="editAkunUserModal-{{ $data->no_kk }}" tabindex="-1"
                                        aria-labelledby="editAkunUserModalLabel-{{ $data->no_kk }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="editAkunUserModalLabel-{{ $data->no_kk }}">
                                                        Edit Akun Keluarga {{ $data->nama_keluarga }}
                                                    </h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        action="{{ route('admin.warga.updateAkun', ['nama_RT' => $nama_RT, 'no_kk_keluarga' => $data->no_kk]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input type="email" class="form-control" id="email"
                                                                name="email" value="{{ $user?->email }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="password" class="form-label">Password Baru</label>
                                                            <input type="password" class="form-control" id="password"
                                                                name="password">
                                                        </div>

                                                        <div class="form-group mb-2">
                                                            <label for="password_confirmation"
                                                                class="form-label">Konfirmasi
                                                                Password Baru</label>
                                                            <input type="password" class="form-control"
                                                                id="password_confirmation" name="password_confirmation">
                                                        </div>

                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Activity Log --}}
                                    @php
                                        $createLog = $data->activityLog
                                            ->where('activity', 'create')
                                            ->where('target_table', 'keluargas')
                                            ->first();

                                        $updateLog = $data->activityLog
                                            ->where('activity', 'update')
                                            ->where('target_table', 'keluargas')
                                            ->last();
                                    @endphp

                                    <div class="modal fade" id="activityLog-{{ $data->no_kk }}" tabindex="-3"
                                        aria-labelledby="activityLogLabel-{{ $data->no_kk }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h3 class="modal-title text-warning"
                                                        id="activityLogLabel-{{ $data->no_kk }}">
                                                        Aktifitas CRUD Keluarga {{ $data->nama_keluarga }}
                                                    </h3>
                                                </div>

                                                <div class="modal-body">
                                                    <h4>Log Aktivitas Keluarga</h4>

                                                    <div class="dropdown-divider"></div>

                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            Keluarga ini dibuat oleh:
                                                            <b>{{ $createLog?->user?->name ?? 'Tidak Diketahui' }}</b>
                                                        </label>
                                                        <small class="text-muted">
                                                            ({{ $createLog?->activity ?? 'Tidak Diketahui' }})
                                                        </small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Waktu Dibuat:
                                                            <small>
                                                                {{ $createLog?->performed_at
                                                                    ? \Carbon\Carbon::parse($createLog->performed_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s')
                                                                    : '-' }}
                                                            </small>
                                                        </label>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            Keluarga ini diubah oleh:
                                                            <b>{{ $updateLog?->user?->name ?? 'Belum Diubah' }}</b>
                                                        </label>
                                                        <small class="text-muted">
                                                            ({{ $updateLog?->activity ?? 'Belum Diubah' }})
                                                        </small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Waktu Diubah:
                                                            <small>
                                                                {{ $updateLog?->performed_at
                                                                    ? \Carbon\Carbon::parse($updateLog->performed_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s')
                                                                    : '-' }}
                                                            </small>
                                                        </label>
                                                    </div>

                                                    @foreach ($users as $user)
                                                        @php
                                                            $createLogUser = $user->activityLog
                                                                ->where('activity', 'create')
                                                                ->where('target_table', 'users')
                                                                ->first();

                                                            $updateLogUser = $user->activityLog
                                                                ->where('activity', 'update')
                                                                ->where('target_table', 'users')
                                                                ->first();
                                                        @endphp

                                                        <h4>Log Aktivitas Akun Keluarga</h4>

                                                        <div class="dropdown-divider"></div>

                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                Akun Keluarga {{ $data->nama_keluarga }} dibuat oleh:
                                                                <b>{{ $createLogUser?->user?->name ?? 'Tidak Diketahui' }}</b>
                                                            </label>
                                                            <small class="text-muted">
                                                                ({{ $createLogUser?->activity ?? 'Tidak Diketahui' }})
                                                            </small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Waktu Dibuat:
                                                                <small>
                                                                    {{ $createLogUser?->performed_at
                                                                        ? \Carbon\Carbon::parse($createLogUser->performed_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s')
                                                                        : '-' }}
                                                                </small>
                                                            </label>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                Akun Keluarga {{ $data->nama_keluarga }} diubah oleh:
                                                                <b>{{ $updateLogUser?->user?->name ?? 'Tidak Diketahui' }}</b>
                                                            </label>
                                                            <small class="text-muted">
                                                                ({{ $updateLogUser?->activity ?? 'Tidak Diketahui' }})
                                                            </small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Waktu Diubah:
                                                                <small>
                                                                    {{ $updateLogUser?->performed_at
                                                                        ? \Carbon\Carbon::parse($updateLogUser->performed_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s')
                                                                        : '-' }}
                                                                </small>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Edit Keluarga --}}
                                    <div class="modal fade" id="editUserModal-{{ $data->no_kk }}" tabindex="-2"
                                        aria-labelledby="editUserModalLabel-{{ $data->no_kk }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editUserModalLabel-{{ $data->no_kk }}">
                                                        Edit Keluarga</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        action="{{ route('admin.warga.update', ['nama_RT' => $nama_RT, 'warga' => $data->no_kk]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-3">
                                                            <label for="no_kk" class="form-label">No KK</label>
                                                            <input type="text"
                                                                class="form-control @error('no_kk') is-invalid @enderror"
                                                                id="no_kk" name="no_kk"
                                                                value="{{ $data->no_kk }}">
                                                            @error('no_kk')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="nama_keluarga" class="form-label">Nama
                                                                Keluarga</label>
                                                            <input type="text"
                                                                class="form-control @error('nama_keluarga') is-invalid @enderror"
                                                                id="nama_keluarga" name="nama_keluarga"
                                                                value="{{ $data->nama_keluarga }}">
                                                            @error('nama_keluarga')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="alamat" class="form-label">Alamat</label>
                                                            <input type="text"
                                                                class="form-control @error('alamat') is-invalid @enderror"
                                                                id="alamat" name="alamat"
                                                                value="{{ $data->alamat }}">
                                                            @error('alamat')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="no_telp" class="form-label">No HP</label>
                                                            <input type="text"
                                                                class="form-control @error('no_telp') is-invalid @enderror"
                                                                id="no_telp" name="no_telp"
                                                                value="{{ $data->no_telp }}">
                                                            @error('no_telp')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="rt_id" class="form-label">RT</label>
                                                            <select
                                                                class="form-control @error('rt_id') is-invalid @enderror"
                                                                id="rt_id" name="rt_id">
                                                                @foreach ($rts as $rt)
                                                                    <option value="{{ $rt->id }}"
                                                                        {{ $rt->id == $data->rt_id ? 'selected' : '' }}>
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
                var createKeluargaModal = new bootstrap.Modal(document.getElementById('createKeluargaModal'));
                createKeluargaModal.show();
            });
        </script>
    @endif

@endsection
