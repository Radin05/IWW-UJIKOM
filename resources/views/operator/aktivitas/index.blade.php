@extends('layouts.app')

@section('title', 'Super Admin Add RT')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
@endsection

@section('content')

    <div class="main-panel mt-4">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card card-tale">
                        <div class="card-body">
                            <div class="table-responsive pt-3">
                                <table id="example" class="table table-dark table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Akun</th>
                                            <th>Aktivitasnya</th>
                                            <th>Deskripsi</th>
                                            <th>Waktu Aktivitas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($activityLogs as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->user->name }}</td>
                                                <td>
                                                    <span
                                                        class="
                                                        @if ($data->activity === 'create') text-success
                                                        @elseif ($data->activity === 'update') text-info
                                                        @elseif ($data->activity === 'delete') text-danger @endif
                                                        ">
                                                        {{ ucfirst($data->activity) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <ul>
                                                        {!! $data->description ?? 'Belum ada aktivitas' !!}
                                                    </ul>
                                                </td>
                                                <td>{{ $data->performed_at
                                                    ? \Carbon\Carbon::parse($data->performed_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s')
                                                    : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {{ $activityLogs->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap4.js"></script>
    <script>
        new DataTable('#example');
    </script>
@endpush
