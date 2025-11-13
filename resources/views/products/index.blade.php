@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Produk</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Produk
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="search-input" class="form-control" placeholder="Cari produk (nama, SKU, barcode)...">
                </div>
            </div>
            <div class="col-md-6 text-end">
                <span class="text-muted" id="result-count">{{ $products->total() }} produk ditemukan</span>
            </div>
        </div>
    </div>
</div>

<table class="table table-striped table-hover" id="products-table">
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
            <tr data-search="{{ strtolower($product->name . ' ' . $product->sku . ' ' . ($product->barcode ?? '')) }}">
                <td>{{ $product->sku }}</td>
                <td>{{ $product->barcode ?? '-' }}</td>
                <td>{{ $product->name }}</td>
                <td>Rp {{ number_format($product->cost_price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                <td>
                    <span class="badge {{ $product->stock < 10 ? 'bg-danger pulse' : 'bg-success' }}">
                        {{ $product->stock }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $product->track_batch ? 'bg-info' : 'bg-secondary' }}">
                        {{ $product->track_batch ? 'Ya' : 'Tidak' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')">
                        <i class="bi bi-trash me-1"></i>Hapus
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $products->links() }}

<!-- Hidden delete form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    // Real-time search
    document.getElementById('search-input').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#products-table tbody tr');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const searchText = row.getAttribute('data-search');
            if (searchText.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        document.getElementById('result-count').textContent = visibleCount + ' produk ditemukan';
    });

    // Delete product with confirmation
    function deleteProduct(id, name) {
        showConfirmDialog(
            'Hapus Produk',
            `Apakah Anda yakin ingin menghapus produk "${name}"? Tindakan ini tidak dapat dibatalkan.`,
            'Ya, Hapus',
            'Batal',
            function() {
                const form = document.getElementById('delete-form');
                form.action = '{{ url('products') }}/' + id;
                form.submit();
            }
        );
    }
</script>
@endpush
@endsection

