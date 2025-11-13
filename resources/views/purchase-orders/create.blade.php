@extends('layouts.app')

@section('title', 'Buat Purchase Order')

@section('content')
<h2>Buat Purchase Order</h2>
<form method="POST" action="{{ route('purchase-orders.store') }}" id="poForm">
    @csrf
    <div class="mb-3">
        <label for="supplier_id" class="form-label">Supplier</label>
        <select class="form-select" id="supplier_id" name="supplier_id" required>
            <option value="">Pilih Supplier</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5>Items</h5>
        </div>
        <div class="card-body">
            <div id="items-container">
                <div class="row item-row mb-2">
                    <div class="col-md-4">
                        <select class="form-select product-select" name="items[0][product_id]" required>
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->cost_price }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control qty-input" name="items[0][qty]" placeholder="Qty" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" class="form-control price-input" name="items[0][price]" placeholder="Harga" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-item">Hapus</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-secondary" id="add-item">Tambah Item</button>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Simpan PO</button>
    <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">Batal</a>
</form>

@push('scripts')
<script>
let itemIndex = 1;
document.getElementById('add-item').addEventListener('click', function() {
    const container = document.getElementById('items-container');
    const newRow = document.createElement('div');
    newRow.className = 'row item-row mb-2';
    newRow.innerHTML = `
        <div class="col-md-4">
            <select class="form-select product-select" name="items[${itemIndex}][product_id]" required>
                <option value="">Pilih Produk</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->cost_price }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control qty-input" name="items[${itemIndex}][qty]" placeholder="Qty" min="1" required>
        </div>
        <div class="col-md-2">
            <input type="number" step="0.01" class="form-control price-input" name="items[${itemIndex}][price]" placeholder="Harga" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-item">Hapus</button>
        </div>
    `;
    container.appendChild(newRow);
    itemIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('.item-row').remove();
    }
});

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('product-select')) {
        const price = e.target.options[e.target.selectedIndex].dataset.price;
        const priceInput = e.target.closest('.item-row').querySelector('.price-input');
        if (price) {
            priceInput.value = price;
        }
    }
});
</script>
@endpush
@endsection

