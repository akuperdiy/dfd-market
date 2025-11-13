@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Produk</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Produk</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>SKU</th>
            <th>Barcode</th>
            <th>Nama</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Stock</th>
            <th>Track Batch</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->barcode ?? '-' }}</td>
                <td>{{ $product->name }}</td>
                <td>Rp {{ number_format($product->cost_price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                <td>
                    <span class="badge {{ $product->stock < 10 ? 'bg-danger' : 'bg-success' }}">
                        {{ $product->stock }}
                    </span>
                </td>
                <td>{{ $product->track_batch ? 'Ya' : 'Tidak' }}</td>
                <td>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $products->links() }}
@endsection

