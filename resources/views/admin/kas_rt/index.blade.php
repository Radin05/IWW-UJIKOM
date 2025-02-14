@extends('layouts.app')

@section('title', 'Admin Data Uang Kas Warga')

@section('content')


    <div class="main-panel mt-4">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card card-tale">
                        <div class="card-body">


                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <!-- Card untuk jumlah kas RT -->
                                <div class="card text-center shadow-sm">
                                    <div class="card-body">

                                        <h3 class="text-dark mb-3">Jumlah Kas RT</h3>

                                        @if (!$kas)
                                            <p class="card-text text-danger">Kas belum tersedia.</p>
                                        @else
                                            <h2 class="card-text fw-bold text-success">Rp
                                                {{ number_format($kas->jumlah_kas_rt, 0, ',', '.') }}</h2>
                                        @endif


                                        <form action="{{ route('admin.kas.update', ['nama_RT' => $nama_RT]) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary mt-5">Perbarui Kas</button>
                                        </form>

                                    </div>
                                </div>
                            </div>


                            <div class="card bg-dark text-white p-4">
                                <h5 class="mb-3">Tambah Pengeluaran Kas RT</h5>

                                <form action="{{ route('admin.pengeluaran.store', ['nama_RT' => $nama_RT]) }}"
                                    method="POST">
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
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                            rows="3"></textarea>
                                        @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="tgl_pengeluaran" class="form-label">Tanggal
                                            Pengeluaran Kas</label>
                                        <input type="date"
                                            class="form-control @error('tgl_pengeluaran') is-invalid @enderror"
                                            id="tgl_pengeluaran" name="tgl_pengeluaran" value="{{ old('tgl_pengeluaran') }}"
                                            required>
                                        @error('tgl_pengeluaran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                            </div>

                            <hr class="my-4 text-white">

                            <form action="{{ route('admin.kas.update-tahunan', ['nama_RT' => $nama_RT]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Update Per Tahun</button>
                            </form>


                            <hr class="my-4 text-white">

                            <div class="table-responsive">
                                <table class="table table-dark">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nominal</th>
                                            <th>Keterangan</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pengeluarans as $key => $pengeluaran)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>Rp {{ number_format($pengeluaran->nominal, 2, ',', '.') }}</td>
                                                <td>{{ $pengeluaran->keterangan }}</td>
                                                <td>{{ $pengeluaran->tgl_pengeluaran }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('sweetalert::alert')

@endsection
