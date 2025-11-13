@extends('layouts.app')

@section('title', 'Retur')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Retur</h2>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Invoice</th>
            <th>Total</th>
            <th>Alasan</th>
            <th>Dibuat Oleh</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($returns as $return)
            <tr>
                <td>{{ $return->sale->invoice_no }}</td>
                <td>Rp {{ number_format($return->total, 0, ',', '.') }}</td>
                <td>{{ $return->reason }}</td>
                <td>{{ $return->user->name }}</td>
                <td>{{ $return->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $returns->links() }}
@endsection

