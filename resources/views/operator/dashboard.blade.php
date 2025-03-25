@extends('layouts.app')

@section('title', 'Operator Dashboard')

@section('content')

    <div class="main-panel mt-4">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                            <h3 class="font-weight-bold">SELAMAT DATANG DIHALAMAN OPERATOR</h3>
                            <h6 class="font-weight-normal mb-5">Hallo {{ Auth::user()->name }}
                                <p>Disini Kamu dapat Atur data RT serta data Akun RT dan RW</p>
                                <span class="text-primary">Klik button Lihat!</span>
                            </h6>
                        </div>
                        <div class="col-12 col-xl-4 mt-3">
                            <div class="justify-content-end d-flex">
                                <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                    <i class="mdi mdi-calendar"></i>Today <small> {{ $carbon->format('d-m-Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card tale-bg">
                                <div class="card-people mt-auto text-center">
                                    <img src="{{ asset('assets/images/dashboard/people.svg') }}" alt="people" class="mb-3">
                                    <h4 class="font-weight-bold">Peraturan Operator</h4>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="button" class="btn btn-warning mt-2" data-bs-toggle="modal" data-bs-target="#aturanOperatorModal">
                                        Lihat Aturan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Aturan Operator -->
                        <div class="modal fade" id="aturanOperatorModal" tabindex="-1" aria-labelledby="aturanOperatorModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning text-white">
                                        <h5 class="modal-title" id="aturanOperatorModalLabel">Aturan Penggunaan Operator</h5>
                                    </div>
                                    <div class="modal-body">
                                        <ul class="list-group">
                                            <li class="list-group-item">1. Akun operator hanya dapat digunakan oleh pengguna yang telah disetujui oleh RW dan pembuat sistem.</li>
                                            <li class="list-group-item">2. Akun hanya dapat digunakan dalam durasi terbatas setiap harinya.</li>
                                            <li class="list-group-item">3. Aktivitas operator akan dicatat untuk keamanan dan transparansi.</li>
                                            <li class="list-group-item">4. Jika batas waktu penggunaan habis, pengguna harus keluar dan menunggu hari berikutnya.</li>
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 grid-margin transparent">
                            <div class="row">
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card card-tale">
                                        <div class="card-body">
                                            <h3 class="mb-4">Akun Admin</h3>
                                            <p class="fs-30 mb-2">{{ $admin }}</p>
                                            <p>
                                                <a href="{{ route('operator.manajemen-admin.index') }}">
                                                    <button type="button" class="btn-light mt-2">Lihat</button>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card card-dark-blue">
                                        <div class="card-body">
                                            <h3 class="mb-4">Akun Superadmin</h3>
                                            <p class="fs-30 mb-2">{{ $superadmin }}</p>
                                            <p>
                                                <a href="{{ route('operator.manajemen-superadmin.index') }}">
                                                    <button type="button" class="btn-light mt-2">Lihat</button>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                                    <div class="card card-light-blue">
                                        <div class="card-body">
                                            <h3 class="mb-4">Jumlah RT</h3>
                                            <p class="fs-30 mb-2">{{ $rts }}</p>
                                            <p>
                                                <a href="{{ route('operator.rt.index') }}">
                                                    <button type="button" class="btn-light mt-2">Lihat</button>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 stretch-card transparent">
                                    <div class="card card-light-danger">
                                        <div class="card-body">
                                            <h3 class="mb-4">Akun Operator</h3>
                                            <p class="fs-30 mb-2">{{ $operator }}</p>
                                            <p><button type="button" class="btn-light mt-2" data-bs-toggle="modal"
                                                    data-bs-target="#operatorModal">
                                                    Lihat
                                                </button></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Bootstrap -->
                                <div class="modal fade" id="operatorModal" tabindex="-1"
                                    aria-labelledby="operatorModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="operatorModalLabel">Daftar Akun Operator</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    @forelse($operatorData as $operator)
                                                        <div class="col-md-6 mb-3">
                                                            <div class="card border-light shadow-sm">
                                                                <div class="card-body">
                                                                    <h5 class="card-title text-danger">
                                                                        {{ $operator->name }}</h5>
                                                                    <p class="card-text"><strong>Email:</strong>
                                                                        {{ $operator->email }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <p class="text-center text-muted">Tidak ada operator terdaftar.</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
