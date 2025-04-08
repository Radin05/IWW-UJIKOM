@extends('layouts.app2')

@section('title', 'Profil Warga')

@section('content')

    <style>
        /* Profil Card */
        .profile-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            text-align: center;
            max-width: 400px;
            margin: 0 auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .profile-header {
            background: linear-gradient(135deg, #3fbbc0, #2a7d83);
            padding: 20px;
            color: white;
            position: relative;
        }

        .back-button {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 20px;
            text-decoration: none;
        }

        /* Avatar */
        .profile-avatar {
            margin-top: -40px;
        }

        .profile-avatar img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid white;
            background: #ddd;
        }

        /* Info */
        .profile-info {
            padding: 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
            background: #f9f9f9;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .info-item i {
            color: #3fbbc0;
            margin-right: 10px;
            font-size: 18px;
        }

        /* Edit Button */
        .edit-profile-button {
            display: block;
            width: 90%;
            margin: 20px auto;
            padding: 10px;
            background: linear-gradient(135deg, #3fbbc0, #2a7d83);
            color: white;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .edit-profile-button:hover {
            background: #2a7d83;
        }

        .edit-profile-button,
        .edit-password-button {
            display: block;
            width: 90%;
            margin: 10px auto;
            padding: 10px;
            background: linear-gradient(135deg, #3fbbc0, #2a7d83);
            color: white;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            border: none;
        }

        .edit-profile-button:hover,
        .edit-password-button:hover {
            background: #2a7d83;
        }
    </style>

@section('navbar')
    <div class="branding d-flex align-items-center">
        <div class="container position-relative d-flex align-items-center justify-content-end">
            <a href="" class="logo d-flex align-items-center me-auto">
                <img src="{{ asset('asset/img/RR2.png') }}" alt="">
            </a>
            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="{{ route('warga.index') }}#beranda">Beranda</a></li>
                    <li><a href="{{ route('warga.index') }}#about">Tentang</a></li>
                    <li><a href="{{ route('warga.index') }}#layanan">Layanan</a></li>
                    <li><a href="{{ route('warga.index') }}#pengurus">Pengurus RW</a></li>
                    <li><a href="{{ route('warga.index') }}#kegiatan">Kegiatan</a></li>
                    <li><a href="{{ route('warga.index') }}#lokasi">Lokasi</a></li>
                    <li><a href="{{ route('warga.iuran') }}">Iuran</a></li>
                    <li class="dropdown"><a href="{{ route('warga.profil') }}" class="active"><span>PROFIL</span> <i
                                class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            <li><a href="{{ route('warga.profil') }}" class="active">Profil</a></li>
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <span>Logout</span>
                                    <i class="bi bi-power"></i>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        </div>
    </div>
@endsection

<!-- Profil -->
<div class="container mt-4">
    <div class="profile-card">
        <div class="profile-header">
            <a href="{{ route('warga.index') }}" class="back-button"><i class="bi bi-arrow-left"></i></a>
            <h2>Profil Keluarga Anda</h2>
        </div>
        <div class="profile-avatar">
            <img src="{{ asset('asset/img/avatar.png') }}" alt="Profile">
        </div>
        <div class="profile-info">
            <div class="info-item">
                <i class="bi bi-person"></i>
                <span>{{ $keluarga->nama_keluarga }}</span>
            </div>
            <div class="info-item">
                <i class="bi bi-card-list"></i>
                <span>{{ $user->no_kk_keluarga }}</span>
            </div>
            <div class="info-item">
                <i class="bi bi-envelope"></i>
                <span>{{ $user->email }}</span>
            </div>
            <div class="info-item">
                <i class="bi bi-house-door"></i>
                <span>RT {{ $keluarga ? $keluarga->rt_id : 'Tidak Diketahui' }}</span>
            </div>
            <div class="info-item">
                <i class="bi bi-telephone"></i>
                <span>{{ $keluarga->no_telp ?? 'Tidak Ada Nomor' }}</span>
            </div>
            <div class="info-item">
                <i class="bi bi-geo-alt"></i>
                <span>{{ $keluarga->alamat ?? 'Alamat Tidak Tersedia' }}</span>
            </div>
            <div class="info-item">
                <i class="bi bi-calendar-check"></i>
                <span>{{ $user->created_at->format('d-m-Y H:i') }}</span>
            </div>
        </div>
        <!-- Tombol Edit -->
        <button class="edit-profile-button" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit
            Profil</button>
        <button class="edit-password-button mb-4" data-bs-toggle="modal" data-bs-target="#editPasswordModal">Edit
            Password</button>
    </div>
</div>

<hr>

<section id="rt" class="tabs section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Kas RT</h2>
        <p>Ini merupakan pengelolaan iuran yang sudah didistribusikan ke kas RT Anda</p>
    </div>

    <div class="container mt-4" data-aos="fade-up">
        <div class="row">
            <!-- Filter Bulan & Tahun -->
            <div class="col-lg-4">
                <form method="GET" action="{{ route('warga.profil') }}">
                    <div class="form-group">
                        <label for="month">Pilih Bulan</label>
                        <select name="month" id="month" class="form-control">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <label for="year">Pilih Tahun</label>
                        <select name="year" id="year" class="form-control">
                            @for ($y = now()->year - 5; $y <= now()->year + 1; $y++)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Tampilkan</button>
                </form>
            </div>

            <!-- Informasi Kas RT -->
            <div class="col-lg-6">
                <div class="info-box bg-success text-white p-3 rounded shadow-sm">
                    <h4>Kas RT Saat Ini</h4>
                    <p><strong>Jumlah Kas RT:</strong> Rp {{ number_format($kasRt->jumlah_kas_rt ?? 0, 2, ',', '.') }}
                    </p>
                </div>

                <div class="info-box bg-primary text-white p-3 rounded shadow-sm mt-3">
                    <h4>Kas RT Kumulatif Hingga {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                        {{ $year }}</h4>
                    <p><strong>Jumlah Kas RT:</strong> Rp {{ number_format($jumlah_kas_rt, 2, ',', '.') }}</p>
                    <p><strong>Tanggal Pembaruan Terakhir:</strong> {{ $tgl_pembaruan }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Pengeluaran Kas RT -->
    <div class="container mt-4">
        <h4>Pengeluaran Kas RT ({{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
            {{ $year }})</h4>
        <div class="table-responsive">
            <table class="table table-warning table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nominal</th>
                        <th>Kegiatan</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengeluaranRt as $index => $pengeluaran)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>Rp {{ number_format($pengeluaran->nominal, 2, ',', '.') }}</td>
                            <td>{{ $pengeluaran->kegiatan->nama_kegiatan ?? '-' }}</td>
                            <td>{!! $pengeluaran->keterangan !!}</td>
                            <td>{{ \Carbon\Carbon::parse($pengeluaran->tgl_pengeluaran)->format('d-m-Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada pengeluaran pada bulan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <!-- Tabel Uang Tambahan Kas RT -->
    <div class="container mt-4">
        <h4>Uang Eksternal Tambahan Kas RT ({{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
            {{ $year }})</h4>
        <div class="table-responsive">
            <table class="table table-success table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($uangTambahansRt as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>Rp {{ number_format($data->nominal ?? 0, 2, ',', '.') }}</td>
                            <td>{!! $data->keterangan ?: '-' !!}</td>
                            <td>{{ $data->created_at ? $data->created_at->format('d F Y') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada uang tambahan kas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $uangTambahansRt->links() }}
            </div>
        </div>
    </div>


</section>

<!-- Modal Edit Profil -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('warga.updateProfile') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama_keluarga" class="form-control"
                            value="{{ $keluarga->nama_keluarga }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="no_telp" class="form-control"
                            value="{{ $keluarga->no_telp ?? '' }}">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Password -->
<div class="modal fade" id="editPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('warga.updatePassword') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('footer')
    <div class="col-lg-3 col-md-3 footer-links">
        <ul>
            <li class="mt-4"><a href="{{ route('warga.index') }}#tentang">Tentang RR2</a></li>
            <li class="mt-4"><a href="{{ route('warga.index') }}#layanan">Layanan</a></li>
            <li class="mt-4"><a href="{{ route('warga.index') }}#kegiatan">Kegiatan Terdekat</a></li>
        </ul>
    </div>

    <div class="col-lg-3 col-md-3 footer-links">
        <ul>
            <li class="mt-4"><a href="{{ route('warga.iuran') }}#rw">Pengelolaan Kas RW</a></li>
            <li class="mt-4"><a href="{{ route('warga.iuran') }}#rt">Pengelolaan Kas Tiap RT</a></li>
            <li class="mt-4"><a href="{{ route('warga.index') }}#pengurus">Pengurus RW</a></li>
        </ul>
    </div>

    <div class="col-lg-2 col-md-3 footer-links">
        <ul>
            <li class="mt-4"><a href="#">Profil Saya</a></li>
            <li class="mt-4"><a href="{{ route('warga.index') }}#lokasi">Lokasi</a></li>
        </ul>
    </div>
@endsection

@endsection
