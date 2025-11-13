@extends('layouts.app')

@section('title', 'Backup Database')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Backup Database</h2>
    <form method="POST" action="{{ route('backups.create') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-primary">Buat Backup</button>
    </form>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Nama File</th>
            <th>Tanggal Dibuat</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($backups as $backup)
            <tr>
                <td>{{ $backup->filename }}</td>
                <td>{{ $backup->created_at->format('d/m/Y H:i:s') }}</td>
                <td>
                    <a href="{{ route('backups.download', $backup->id) }}" class="btn btn-sm btn-primary">Download</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection

