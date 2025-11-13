@extends('layouts.app')

@section('title', 'Purchase Order')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Purchase Order</h2>
    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">Buat PO</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>PO No</th>
            <th>Supplier</th>
            <th>Status</th>
            <th>Total</th>
            <th>Dibuat Oleh</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->po_no }}</td>
                <td>{{ $order->supplier->name }}</td>
                <td>
                    <span class="badge bg-{{ $order->status === 'received' ? 'success' : ($order->status === 'confirmed' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                <td>{{ $order->creator->name }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('purchase-orders.show', $order) }}" class="btn btn-sm btn-info">Detail</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $orders->links() }}
@endsection

