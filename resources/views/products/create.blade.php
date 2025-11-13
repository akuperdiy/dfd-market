@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<h2>Tambah Produk</h2>
<form method="POST" action="{{ route('products.store') }}" class="show-loading">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="sku" class="form-label">SKU</label>
                <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku') }}" required>
                @error('sku')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="barcode" class="form-label">Barcode</label>
                <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="barcode" name="barcode" value="{{ old('barcode') }}">
                @error('barcode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Nama Produk</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="cost_price" class="form-label">Harga Beli</label>
                <input type="number" step="0.01" class="form-control @error('cost_price') is-invalid @enderror" id="cost_price" name="cost_price" value="{{ old('cost_price') }}" required>
                @error('cost_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="sell_price" class="form-label">Harga Jual</label>
                <input type="number" step="0.01" class="form-control @error('sell_price') is-invalid @enderror" id="sell_price" name="sell_price" value="{{ old('sell_price') }}" required>
                @error('sell_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock Awal</label>
                <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="track_batch" name="track_batch" value="1" {{ old('track_batch') ? 'checked' : '' }}>
                <label class="form-check-label" for="track_batch">Track Batch</label>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-2"></i>Simpan
    </button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        <i class="bi bi-x-circle me-2"></i>Batal
    </a>
</form>

@push('scripts')
<script>
    // Real-time validation feedback
    document.querySelectorAll('input[required]').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '' && this.hasAttribute('required')) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });
    
    // Auto-format currency
    const priceInputs = document.querySelectorAll('#cost_price, #sell_price');
    priceInputs.forEach(input => {
        input.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (!isNaN(value) && value < 0) {
                this.setCustomValidity('Harga tidak boleh negatif');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });
    });
</script>
@endpush
@endsection

