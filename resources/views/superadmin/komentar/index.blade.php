@extends('layouts.app')

@section('content')

    <style>
        .btn-outline-info {
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            border: none;
        }
    </style>

    <div class="container">
        <h3 class="mt-5 mb-5 text-center">Daftar Komentar</h3>

        <!-- Informasi Kegiatan Mendatang -->
        @if ($kegiatanMendatang->isNotEmpty())
            <div class="row">
                @foreach ($kegiatanMendatang as $kegiatan)
                    <div class="col-md-4">
                        <div class="card kegiatan-card mb-3" data-id="{{ $kegiatan->id }}"
                            data-nama="{{ $kegiatan->nama_kegiatan }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $kegiatan->nama_kegiatan }}</h5>
                                <p class="card-text">
                                    <strong>Tanggal:</strong>
                                    {{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('d/m/Y') }}<br>
                                    <strong>Jam:</strong>
                                    {{ \Carbon\Carbon::parse($kegiatan->jam_kegiatan)->format('H:i') }}<br>
                                    <strong>Deskripsi:</strong> {!! Str::limit($kegiatan->deskripsi, 100) !!}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Daftar Komentar -->
        <div id="komentarSection" style="display: none;">
            <h4 class="mt-4">Komentar untuk <span id="namaKegiatan"></span></h4>
            <ul id="listKomentar">
                @foreach ($komentars as $komentar)
                    <li class="komentar-item" data-kegiatan-id="{{ $komentar }}" style="display: none;">
                        <strong>{{ $komentar->user->name }}</strong>:
                        <span class="konten">{{ $komentar->konten }}</span>
                        <br>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($komentar->performed_at)->diffForHumans() }}
                        </small>

                        @if ($komentar->user_id === auth()->id())
                            <div class="dropdown d-inline">
                                <button type="button" class="btn btn-outline-info btn-sm" data-toggle="dropdown">
                                    <i class="icon-ellipsis"></i>
                                </button>
                                <div class="dropdown-menu bg-info">
                                    <button class="dropdown-item edit" data-id="{{ $komentar->id }}"
                                        data-konten="{{ $komentar->konten }}">
                                        Edit
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item delete" data-id="{{ $komentar->id }}">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>

            <hr>

            <!-- Form Tambah Komentar -->
            <form id="formTambahKomentar">
                <input type="hidden" id="selectedKegiatanId">
                <div class="mb-3">
                    <textarea id="konten" class="form-control" placeholder="Tulis komentar..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Tambah Komentar
            $('#formTambahKomentar').submit(function(e) {
                e.preventDefault();
                let konten = $('#konten').val();

                $.ajax({
                    url: "{{ route('superadmin.komentar.store') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        konten: konten
                    },
                    success: function(response) {
                        let editButton = response.komentar.user_id === {{ auth()->id() }} ? `
                    <button class="btn btn-warning btn-sm edit" data-id="${response.komentar.id}" data-konten="${response.komentar.konten}">Edit</button>
                    <button class="btn btn-danger btn-sm delete" data-id="${response.komentar.id}">Hapus</button>
                ` : '';

                        $('#listKomentar').prepend(`
                    <li id="komentar-${response.komentar.id}">
                        <strong>{{ auth()->user()->name }}</strong>:
                        <span class="konten">${response.komentar.konten}</span>
                        <br>
                        <small class="text-muted">
                            ${new Date(response.komentar.performed_at).toLocaleString()}
                        </small>
                        @if ($komentar->user_id === auth()->id())
                            <div class="dropdown d-inline">
                                <button type="button" class="btn btn-outline-info btn-sm" data-toggle="dropdown">
                                    <i class="icon-ellipsis"></i>
                                </button>
                                <div class="dropdown-menu bg-info">
                                    <button class="dropdown-item edit" data-id="{{ $komentar->id }}"
                                        data-konten="{{ $komentar->konten }}">
                                        Edit
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item delete" data-id="{{ $komentar->id }}">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @endif
                    </li>
                `);
                        $('#konten').val('');
                    }
                });
            });

            // Edit Komentar (Menampilkan Modal)
            $(document).on('click', '.edit', function() {
                let id = $(this).data('id');
                let konten = $(this).data('konten');
                $('#editKomentarId').val(id);
                $('#editKonten').val(konten);
                $('#modalEdit').show();
            });

            // Update Komentar
            $('#btnUpdateKomentar').click(function() {
                let id = $('#editKomentarId').val();
                let konten = $('#editKonten').val();

                $.ajax({
                    url: `/superadmin/komentar/${id}`,
                    method: "PUT",
                    data: {
                        _token: "{{ csrf_token() }}",
                        konten: konten
                    },
                    success: function(response) {
                        $(`#komentar-${id} .konten`).text(response.komentar.konten);
                        $('#modalEdit').hide();
                    },
                    error: function(xhr) {
                        if (xhr.status === 403) {
                            alert('Anda tidak memiliki izin untuk mengedit komentar ini.');
                        }
                    }
                });
            });

            // Hapus Komentar
            $(document).on('click', '.delete', function() {
                let id = $(this).data('id');

                $.ajax({
                    url: `/superadmin/komentar/${id}`,
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        $(`#komentar-${id}`).remove();
                    },
                    error: function(xhr) {
                        if (xhr.status === 403) {
                            alert('Anda tidak memiliki izin untuk menghapus komentar ini.');
                        }
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Tampilkan Modal Edit
            $(document).on('click', '.edit', function() {
                let id = $(this).data('id');
                let konten = $(this).data('konten');

                $('#editKomentarId').val(id);
                $('#editKonten').val(konten);
                $('#modalEditKomentar').modal('show'); // Buka modal
            });

            // Update Komentar
            $('#btnUpdateKomentar').click(function() {
                let id = $('#editKomentarId').val();
                let konten = $('#editKonten').val();

                $.ajax({
                    url: `/superadmin/komentar/${id}`,
                    method: "PUT",
                    data: {
                        _token: "{{ csrf_token() }}",
                        konten: konten
                    },
                    success: function(response) {
                        $(`#komentar-${id} .konten`).text(response.komentar.konten);
                        $('#modalEditKomentar').modal('hide'); // Tutup modal setelah update
                    },
                    error: function(xhr) {
                        if (xhr.status === 403) {
                            alert('Anda tidak memiliki izin untuk mengedit komentar ini.');
                        }
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const kegiatanCards = document.querySelectorAll(".kegiatan-card");
            const komentarSection = document.getElementById("komentarSection");
            const listKomentar = document.getElementById("listKomentar");
            const namaKegiatan = document.getElementById("namaKegiatan");
            const inputKegiatanId = document.getElementById("selectedKegiatanId");

            function tampilkanKomentar(kegiatanId, kegiatanNama) {
                komentarSection.style.display = "block";
                namaKegiatan.textContent = kegiatanNama;
                inputKegiatanId.value = kegiatanId;

                // Simpan kegiatan yang terakhir dipilih ke localStorage
                localStorage.setItem("selectedKegiatanId", kegiatanId);
                localStorage.setItem("selectedKegiatanNama", kegiatanNama);

                // Sembunyikan semua komentar terlebih dahulu
                document.querySelectorAll(".komentar-item").forEach(item => {
                    item.style.display = "none";
                });

                // Tampilkan hanya komentar yang sesuai dengan kegiatan yang dipilih
                document.querySelectorAll(`.komentar-item[data-kegiatan-id='${kegiatanId}']`).forEach(item => {
                    item.style.display = "block";
                });
            }

            kegiatanCards.forEach(card => {
                card.addEventListener("click", function() {
                    const kegiatanId = this.getAttribute("data-id");
                    const kegiatanNama = this.getAttribute("data-nama");
                    tampilkanKomentar(kegiatanId, kegiatanNama);
                });
            });

            // Cek apakah ada kegiatan yang terakhir dipilih sebelum reload
            const lastKegiatanId = localStorage.getItem("selectedKegiatanId");
            const lastKegiatanNama = localStorage.getItem("selectedKegiatanNama");

            if (lastKegiatanId && lastKegiatanNama) {
                tampilkanKomentar(lastKegiatanId, lastKegiatanNama);
            }
        });
    </script>

@endsection
