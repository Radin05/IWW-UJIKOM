@extends('layouts.app')

@section('title', 'Admin Data Uang Kas Warga')

@section('content')


    <div class="main-panel mt-4">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card card-tale">
                        <div class="card-body">
                            <h4 class="mb-4">Jumlah Kas RT</h4>

                            <form action="{{ route('admin.kas.update', ['nama_RT' => $nama_RT]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Perbarui Kas</button>
                            </form>

                            <div class="mt-4">
                                @if (!$kas)
                                    <p>Kas belum tersedia.</p>
                                @else
                                    <h4>Total Uang Kas: <strong>Rp
                                            {{ number_format($kas->jumlah_kas_rt, 0, ',', '.') }}</strong></h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
