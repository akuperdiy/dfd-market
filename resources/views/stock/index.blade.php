@extends('layouts.app')

@section('title', 'Stock')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Stock</h2>
    <a href="{{ route('stock.adjust') }}" class="btn btn-primary">Stock Adjustment</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>SKU</th>
            <th>Nama Produk</th>
            <th>Stock</th>
            <th>Harga Jual</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->name }}</td>
                <td>
                    <span class="badge {{ $product->stock < 10 ? 'bg-danger' : 'bg-success' }}">
                        {{ $product->stock }}
                    </span>
                </td>
                <td>Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                <td>{{ $product->stock < 10 ? 'Stock Menipis' : 'Aman' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $products->links() }}
@endsection

