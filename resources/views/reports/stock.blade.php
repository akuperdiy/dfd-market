@extends('layouts.app')

@section('title', 'Laporan Stock')

@section('content')
<h2>Laporan Stock</h2>

<div class="alert alert-warning">
    <h5>Stock Menipis (< 10)</h5>
    <ul>
        @foreach($lowStock as $product)
            <li>{{ $product->name }} - Stock: {{ $product->stock }}</li>
        @endforeach
    </ul>
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
@endsection

