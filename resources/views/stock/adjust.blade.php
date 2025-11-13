@extends('layouts.app')

@section('title', 'Stock Adjustment')

@section('content')
<h2>Stock Adjustment</h2>
<form method="POST" action="{{ route('stock.adjust.process') }}">
    @csrf
    <div class="mb-3">
        <label for="product_id" class="form-label">Produk</label>
        <select class="form-select" id="product_id" name="product_id" required>
            <option value="">Pilih Produk</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">
                    {{ $product->name }} (Stock: {{ $product->stock }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="qty" class="form-label">Jumlah Adjustment (positif untuk tambah, negatif untuk kurang)</label>
        <input type="number" class="form-control" id="qty" name="qty" required>
        <small class="text-muted">Contoh: +10 untuk menambah 10, -5 untuk mengurangi 5</small>
    </div>
    <div class="mb-3">
        <label for="reason" class="form-label">Alasan</label>
        <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Proses Adjustment</button>
    <a href="{{ route('stock.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection

