@extends('layouts.app')

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

    <div class="main-panel mt-4">
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
                            <form class="forms-sample" action="{{ route('operator.rt.store') }}" method="POST"
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

                                <div id="Fields_nr"></div>

                                <div>
                                    <button type="button" class="btn btn-secondary" onclick="addField()">Tambah Lagi</button>
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-danger" onclick="hapusPesan()">Batal</button>
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                </div>
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
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rts as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->nama_RT }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-outline-info"
                                                            id="dropdownMenuIconButton3" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            <i class="icon-ellipsis"></i>
                                                        </button>
                                                        <div class="dropdown-menu bg-info"
                                                            aria-labelledby="dropdownMenuIconButton3">

                                                            {{-- <button class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#editRTModal-{{ $data->id }}">Edit</button> --}}

                                                            <a href="{{ route('operator.rt.destroy', $data->id) }}"
                                                                class="dropdown-item" data-confirm-delete="true">
                                                                Hapus
                                                            </a>

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

                                {{-- @foreach ($rts as $data)
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
                                @endforeach --}}

                                @foreach ($rts as $data)
                                    @php
                                        $createLog = $data->activityLog->where('activity', 'create')->first();
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
                                                        <label class="form-label">Dibuat oleh :
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

    <script>
        function addField() {
            // Menambahkan input untuk 'fields_nr'
            const containerNr = document.getElementById('Fields_nr');
            const inputGroupNr = document.createElement('div');
            inputGroupNr.classList.add('mb-3');
            inputGroupNr.innerHTML = `
            <input type="text" class="form-control @error('nama_RT') is-invalid @enderror"
                name="additional_fields_nr[]">
            `;
            containerNr.appendChild(inputGroupNr);
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

    @include('sweetalert::alert')

@endsection
