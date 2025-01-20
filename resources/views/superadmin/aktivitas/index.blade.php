@extends('layouts.navbar')

@section('title', 'Super Admin Add RT')

@section('content')

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card card-tale">
                        <div class="card-body">

                            <div class="table-responsive pt-3">
                                <table class="table table-dark" id="example">
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
                                                    <span class="
                                                        @if ($data->activity === 'create') text-success
                                                        @elseif ($data->activity === 'update') text-info
                                                        @elseif ($data->activity === 'delete') text-danger
                                                        @endif
                                                        ">
                                                        {{ ucfirst($data->activity) }}
                                                    </span>
                                                </td>
                                                <td>{{ $data->description }}</td>
                                                <td>{{ $data->performed_at
                                                    ? \Carbon\Carbon::parse($data->performed_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s')
                                                    : '-' }}
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

@endsection

