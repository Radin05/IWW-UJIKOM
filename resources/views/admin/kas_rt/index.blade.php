@extends('layouts.app')

@section('title', 'Admin Data Uang Kas Warga')

@section('content')
    <div class="main-panel mt-4">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card card-tale">
                        <div class="card-body">
                            <!-- Summary Cards Row -->
                            <div class="row justify-content-center align-items-start mb-4">
                                <!-- Cash Balance Card -->
                                <div class="col-md-4">
                                    <div class="card text-center shadow-sm">
                                        <div class="card-body p-4">
                                            <h3 class="text-dark fw-bold mb-3">Jumlah Kas RT</h3>

                                            @if (!$kas)
                                                <p class="card-text text-danger">Kas belum tersedia.</p>
                                            @else
                                                <h2 class="card-text fw-bold text-success">
                                                    Rp {{ number_format($kas->jumlah_kas_rt, 0, ',', '.') }}
                                                </h2>
                                            @endif

                                            <div class="mt-4">
                                                <form action="{{ route('admin.kas.update', ['nama_RT' => $nama_RT]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary mb-2 w-100">Perbarui
                                                        Kas</button>
                                                </form>

                                                <button type="button" class="btn btn-secondary w-100"
                                                    data-bs-toggle="modal" data-bs-target="#uangEksternalModal">
                                                    Uang Eksternal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Transaction History Card -->
                                <div class="col-md-8">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h4 class="text-center text-dark fw-bold mb-3">Riwayat Kas RT</h4>
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <thead class="table-dark text-center">
                                                        <tr>
                                                            <th width="10%">No</th>
                                                            <th>Nominal</th>
                                                            <th width="15%">RT</th>
                                                            <th>Tanggal Pembaruan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($riwayatKas as $paginated => $item)
                                                            <tr>
                                                                <td class="text-center">
                                                                    {{ $paginated + $riwayatKas->firstItem() }}</td>
                                                                <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                                                <td class="text-center">RT {{ $item->rt_id }}</td>
                                                                <td class="text-center">
                                                                    {{ \Carbon\Carbon::parse($item->tgl_pembaruan_kas)->format('d M Y') }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center">Belum ada histori kas
                                                                    RT.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="d-flex justify-content-center mt-3">
                                                {{ $riwayatKas->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- External Funds Section -->
                            <div class="card bg-dark text-white p-4 mb-4">
                                <h4 class="mb-3 fw-bold">Uang Tambahan Kas</h4>
                                <div class="table-responsive">
                                    <table class="table table-dark table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th width="10%">No</th>
                                                <th width="15%">Nominal</th>
                                                <th>Keterangan</th>
                                                <th width="20%">Tanggal</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kasTambahanRT as $key => $data)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>Rp {{ number_format($data->nominal ?? 0, 2, ',', '.') }}</td>
                                                    <td>{!! $data->keterangan ?: '-' !!}</td>
                                                    <td>{{ $data->created_at ? $data->created_at->format('d F Y') : '-' }}
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-outline-info"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                                <i class="icon-ellipsis"></i>
                                                            </button>
                                                            <div class="dropdown-menu bg-info">
                                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                                    data-bs-target="#editUangTambahanModal-{{ $data->id }}">Edit</button>

                                                                <div class="dropdown-divider"></div>

                                                                <form
                                                                    action="{{ route('admin.uang-tambahan-kas.destroy', ['nama_RT' => $nama_RT, 'id' => $data->id]) }}"
                                                                    method="POST" class="d-inline delete-form"
                                                                    data-type="uang-tambahan"
                                                                    data-title="Hapus Uang Tambahan?"
                                                                    data-text="Yakin ingin menghapus uang tambahan ini?">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="dropdown-item text-danger">Hapus</button>
                                                                </form>

                                                                <div class="dropdown-divider"></div>

                                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                                    data-bs-target="#activityLogTambahan-{{ $data->id }}">Aktivitas</button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $kasTambahanRT->links() }}
                                </div>
                            </div>

                            <!-- Expenditure Form -->
                            <div class="card bg-dark text-white p-4 mb-4">
                                <h4 class="mb-3 fw-bold">Tambah Pengeluaran Kas RT</h4>

                                <form action="{{ route('admin.pengeluaran.store', ['nama_RT' => $nama_RT]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="nominal" class="form-label">Nominal</label>
                                                <input type="number"
                                                    class="form-control @error('nominal') is-invalid @enderror"
                                                    id="nominal" name="nominal" min="0" step="0.01" required>
                                                @error('nominal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="kegiatan_id" class="form-label">Kegiatan</label>
                                                <select class="form-control @error('kegiatan_id') is-invalid @enderror"
                                                    name="kegiatan_id">
                                                    <option value="">-- Untuk hal lain --</option>
                                                    @foreach ($kegiatans as $kegiatan)
                                                        <option value="{{ $kegiatan->id }}">
                                                            {{ $kegiatan->nama_kegiatan }}
                                                            (<small>{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('d M Y') }}</small>)
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('kegiatan_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="tgl_pengeluaran" class="form-label">Tanggal Pengeluaran
                                                    Kas</label>
                                                <input type="date"
                                                    class="form-control @error('tgl_pengeluaran') is-invalid @enderror"
                                                    id="tgl_pengeluaran" name="tgl_pengeluaran"
                                                    value="{{ old('tgl_pengeluaran') }}" required>
                                                @error('tgl_pengeluaran')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="keterangan" class="form-label">Keterangan</label>
                                                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="exampleFormControlTextarea1"
                                                    name="keterangan" rows="3"></textarea>
                                                @error('keterangan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                            </div>
                            <!-- Expenditure Table -->
                            <div class="card bg-dark text-white p-4 shadow">
                                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                                    <!-- Judul -->
                                    <h4 class="fw-bold text-white m-0">Daftar Pengeluaran</h4>

                                    <!-- Total Pengeluaran Card -->
                                    <div class="card border-success shadow-sm m-0" style="min-width: 300px;">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0 text-dark fw-bold me-4">Total Pengeluaran Kas RT = &nbsp;</h5>
                                            <h5 class="mb-0 text-success fw-bold">
                                                Rp {{ number_format($totalPengeluaran, 2, ',', '.') }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabel Pengeluaran -->
                                <div class="table-responsive">
                                    <table class="table table-dark table-striped table-hover align-middle text-white">
                                        <thead class="text-center">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="15%">Nominal</th>
                                                <th width="20%">Kegiatan</th>
                                                <th>Keterangan</th>
                                                <th width="15%">Tanggal</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengeluarans as $key => $data)
                                                <tr>
                                                    <td class="text-center">{{ $key + 1 }}</td>
                                                    <td>Rp {{ number_format($data->nominal, 2, ',', '.') }}</td>
                                                    <td>{{ $data->kegiatan->nama_kegiatan ?? '-- Untuk hal lain --' }}</td>
                                                    <td>{!! $data->keterangan !!}</td>
                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($data->tgl_pengeluaran)->format('d M Y') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-outline-info"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                                <i class="icon-ellipsis"></i>
                                                            </button>
                                                            <div class="dropdown-menu bg-info">
                                                                <!-- Edit -->
                                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                                    data-bs-target="#editPengeluaranModal-{{ $data->id }}">
                                                                    Edit
                                                                </button>

                                                                <div class="dropdown-divider"></div>

                                                                <!-- Delete -->
                                                                <form
                                                                    action="{{ route('admin.pengeluaran-kas-rt.destroy', ['nama_RT' => $nama_RT, 'id' => $data->id]) }}"
                                                                    method="POST" class="d-inline delete-form"
                                                                    data-type="pengeluaran"
                                                                    data-title="Hapus Pengeluaran?"
                                                                    data-text="Yakin ingin menghapus pengeluaran ini?">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="dropdown-item text-danger">Hapus</button>
                                                                </form>

                                                                <div class="dropdown-divider"></div>

                                                                <!-- Activity Log -->
                                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                                    data-bs-target="#activityLogPengeluaran-{{ $data->id }}">
                                                                    Aktivitas
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>
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
    </div>

    <!-- MODAL SECTION -->

    <!-- Modal Uang Eksternal -->
    <div class="modal fade" id="uangEksternalModal" tabindex="-1" aria-labelledby="uangEksternalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <form action="{{ route('admin.uang-tambahan-kas.store', ['nama_RT' => $nama_RT]) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="uangEksternalModalLabel">Tambah Uang Eksternal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nominal" class="form-label">Nominal Uang Eksternal</label>
                            <input type="number" class="form-control" id="nominal" name="nominal" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modals for Edit External Funds -->
    @foreach ($kasTambahanRT as $data)
        <!-- Modal Edit Uang Tambahan -->
        <div class="modal fade" id="editUangTambahanModal-{{ $data->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Uang Tambahan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form
                            action="{{ route('admin.uang-tambahan-kas.update', ['nama_RT' => $nama_RT, 'id' => $data->id]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nominal" class="form-label">Nominal</label>
                                <input type="number" class="form-control" name="nominal" value="{{ $data->nominal }}">
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <input type="text" class="form-control" name="keterangan"
                                    value="{{ $data->keterangan }}">
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Log Modal for External Funds -->
        @php
            $createLog = $data->activityLog->where('activity', 'create')->last();
            $updateLog = $data->activityLog->where('activity', 'update')->last();
        @endphp

        <div class="modal fade" id="activityLogTambahan-{{ $data->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title">Aktivitas CRUD</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card mb-3">
                            <div class="card-header bg-secondary text-white">Pembuatan</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4"><strong>Dibuat oleh:</strong></div>
                                    <div class="col-md-8">{{ $createLog?->user?->name ?? 'Tidak Diketahui' }}</div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4"><strong>Aktivitas:</strong></div>
                                    <div class="col-md-8">{{ $createLog?->activity ?? 'Tidak Diketahui' }}</div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4"><strong>Waktu:</strong></div>
                                    <div class="col-md-8">
                                        @if ($createLog && $createLog->performed_at)
                                            {{ \Carbon\Carbon::parse($createLog->performed_at)->translatedFormat('d F Y H:i:s') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-secondary text-white">Pembaruan Terakhir</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4"><strong>Diubah oleh:</strong></div>
                                    <div class="col-md-8">{{ $updateLog?->user?->name ?? 'Belum diedit' }}</div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4"><strong>Deskripsi:</strong></div>
                                    <div class="col-md-8">{!! nl2br(e($updateLog?->description ?? 'Belum ada perubahan')) !!}</div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4"><strong>Waktu:</strong></div>
                                    <div class="col-md-8">
                                        @if ($updateLog && $updateLog->performed_at)
                                            {{ \Carbon\Carbon::parse($updateLog->performed_at)->translatedFormat('d F Y H:i:s') }}
                                        @else
                                            Belum diedit
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modals for Expenditures -->
    @foreach ($pengeluarans as $data)
        <!-- Modal Edit Pengeluaran -->
        <div class="modal fade" id="editPengeluaranModal-{{ $data->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Pengeluaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form
                            action="{{ route('admin.pengeluaran-kas-rt.update', ['nama_RT' => $nama_RT, 'id' => $data->id]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nominal" class="form-label">Nominal</label>
                                        <input type="number" class="form-control @error('nominal') is-invalid @enderror"
                                            id="nominal" name="nominal" value="{{ $data->nominal }}">
                                        @error('nominal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="kegiatan_id" class="form-label">Kegiatan</label>
                                        <select class="form-control @error('kegiatan_id') is-invalid @enderror"
                                            name="kegiatan_id">
                                            <option value="">-- Untuk hal lain --</option>
                                            @foreach ($kegiatans as $kegiatan)
                                                <option value="{{ $kegiatan->id }}"
                                                    {{ $data->kegiatan_id == $kegiatan->id ? 'selected' : '' }}>
                                                    {{ $kegiatan->nama_kegiatan }}
                                                    (<small>{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('d M Y') }}</small>)
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kegiatan_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tgl_pengeluaran" class="form-label">Tanggal Pengeluaran</label>
                                        <input type="date"
                                            class="form-control @error('tgl_pengeluaran') is-invalid @enderror"
                                            id="tgl_pengeluaran" name="tgl_pengeluaran"
                                            value="{{ $data->tgl_pengeluaran }}">
                                        @error('tgl_pengeluaran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <input type="text"
                                            class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                                            name="keterangan" value="{{ $data->keterangan }}">
                                        @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Log Modal for Expenditures -->
        @php
            $createLog = $data->activityLog->where('activity', 'create')->last();
            $updateLog = $data->activityLog->where('activity', 'update')->last();
        @endphp

        <div class="modal fade" id="activityLogPengeluaran-{{ $data->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header">
                        <h5 class="modal-title">Aktivitas Pengeluaran Kas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card mb-3">
                            <div class="card-header bg-secondary text-white">Pembuatan</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4"><strong>Dibuat oleh:</strong></div>
                                    <div class="col-md-8">{{ $createLog?->user?->name ?? 'Tidak Diketahui' }}</div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4"><strong>Aktivitas:</strong></div>
                                    <div class="col-md-8">{{ $createLog?->activity ?? 'Tidak Diketahui' }}</div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4"><strong>Waktu:</strong></div>
                                    <div class="col-md-8">
                                        @if ($createLog && $createLog->performed_at)
                                            {{ \Carbon\Carbon::parse($createLog->performed_at)->translatedFormat('d F Y H:i:s') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-secondary text-white">Pembaruan Terakhir</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4"><strong>Diubah oleh:</strong></div>
                                    <div class="col-md-8">{{ $updateLog?->user?->name ?? 'Belum diedit' }}</div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4"><strong>Deskripsi:</strong></div>
                                    <div class="col-md-8">{!! nl2br(e($updateLog?->description ?? 'Belum ada perubahan')) !!}</div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4"><strong>Waktu:</strong></div>
                                    <div class="col-md-8">
                                        @if ($updateLog && $updateLog->performed_at)
                                            {{ \Carbon\Carbon::parse($updateLog->performed_at)->translatedFormat('d F Y H:i:s') }}
                                        @else
                                            Belum diedit
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
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

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var editPengeluaranModal = new bootstrap.Modal(document.getElementById('editPengeluaranModal'));
                editPengeluaranModal.show();
            });
        </script>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("form.delete-form").forEach(function(form) {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: form.dataset.title || "Yakin ingin menghapus?",
                        text: form.dataset.text || "Data akan dihapus secara permanen!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, Hapus!",
                        cancelButtonText: "Batal",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("form.delete-form").forEach(function(form) {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();

                    let type = form.dataset.type; // uang-tambahan atau pengeluaran
                    let title = form.dataset.title || "Yakin ingin menghapus?";
                    let text = form.dataset.text || "Data akan dihapus secara permanen!";
                    let icon = type === "pengeluaran" ? "error" : "warning";
                    let confirmButtonColor = type === "pengeluaran" ? "#c82333" : "#d33";

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: confirmButtonColor,
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, Hapus!",
                        cancelButtonText: "Batal",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // form akan submit ke route dengan method DELETE
                        }
                    });
                });
            });
        });
    </script>

    @include('sweetalert::alert')

@endsection
