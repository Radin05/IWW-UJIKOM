@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card bg-dark text-white">
            <div class="card-body">
                <h3>Edit Data Keluarga</h3>

                <form action="{{ route('admin.warga.update', ['nama_RT' => $nama_RT, 'warga' => $warga->id]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="no_kk" class="form-label">No KK</label>
                        <input type="text" class="form-control @error('no_kk') is-invalid @enderror" id="no_kk"
                            name="no_kk" value="{{ old('no_kk', $warga->no_kk) }}">
                        @error('no_kk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nama_keluarga" class="form-label">Nama Keluarga</label>
                        <input type="text" class="form-control @error('nama_keluarga') is-invalid @enderror"
                            id="nama_keluarga" name="nama_keluarga"
                            value="{{ old('nama_keluarga', $warga->nama_keluarga) }}">
                        @error('nama_keluarga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                            name="alamat" value="{{ old('alamat', $warga->alamat) }}">
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="no_telp" class="form-label">No HP</label>
                        <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp"
                            name="no_telp" value="{{ old('no_telp', $warga->no_telp) }}">
                        @error('no_telp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <a href="{{ route('admin.warga.index', ['nama_RT' => $nama_RT]) }}"
                        class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

    @include('sweetalert::alert')
@endsection
