@extends('layouts.app')

@section('title', 'Detail Purchase Order')

@section('content')
<h2>Detail Purchase Order: {{ $purchaseOrder->po_no }}</h2>

<div class="row mb-3">
    <div class="col-md-6">
        <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name }}</p>
        <p><strong>Status:</strong> 
            <span class="badge bg-{{ $purchaseOrder->status === 'received' ? 'success' : ($purchaseOrder->status === 'confirmed' ? 'warning' : 'secondary') }}">
                {{ ucfirst($purchaseOrder->status) }}
            </span>
        </p>
    </div>
    <div class="col-md-6">
        <p><strong>Total:</strong> Rp {{ number_format($purchaseOrder->total, 0, ',', '.') }}</p>
        <p><strong>Dibuat Oleh:</strong> {{ $purchaseOrder->creator->name }}</p>
    </div>
</div>

<table class="table table-striped mb-3">
    <thead>
        <tr>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseOrder->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->qty }}</td>
                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->qty * $item->price, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@if($purchaseOrder->status !== 'received')
    <form method="POST" action="{{ route('purchase-orders.receive', $purchaseOrder) }}">
        @csrf
        @foreach($purchaseOrder->items as $item)
            @if($item->product->track_batch)
                <div class="card mb-2">
                    <div class="card-body">
                        <h6>{{ $item->product->name }}</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Batch No</label>
                                <input type="text" class="form-control" name="batches[{{ $item->id }}][batch_no]" value="BATCH-{{ date('Ymd') }}-{{ $item->id }}" required>
                            </div>
                            <div class="col-md-4">
                                <label>Expiry Date</label>
                                <input type="date" class="form-control" name="batches[{{ $item->id }}][expiry]">
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
        <button type="submit" class="btn btn-success">Terima Stock</button>
    </form>
@endif

<a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">Kembali</a>
@endsection

