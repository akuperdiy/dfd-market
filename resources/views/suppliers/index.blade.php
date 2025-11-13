@extends('layouts.app')

@section('title', 'Supplier')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Supplier</h2>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Supplier
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="search-input" class="form-control" placeholder="Cari supplier (nama, telepon, alamat)...">
                </div>
            </div>
            <div class="col-md-6 text-end">
                <span class="text-muted" id="result-count">{{ $suppliers->total() }} supplier ditemukan</span>
            </div>
        </div>
    </div>
</div>

<table class="table table-striped table-hover" id="suppliers-table">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($suppliers as $supplier)
            <tr data-search="{{ strtolower($supplier->name . ' ' . ($supplier->phone ?? '') . ' ' . ($supplier->address ?? '')) }}">
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->phone ?? '-' }}</td>
                <td>{{ $supplier->address ?? '-' }}</td>
                <td>
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteSupplier({{ $supplier->id }}, '{{ $supplier->name }}')">
                        <i class="bi bi-trash me-1"></i>Hapus
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $suppliers->links() }}

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
        const rows = document.querySelectorAll('#suppliers-table tbody tr');
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
        
        document.getElementById('result-count').textContent = visibleCount + ' supplier ditemukan';
    });

    // Delete supplier with confirmation
    function deleteSupplier(id, name) {
        showConfirmDialog(
            'Hapus Supplier',
            `Apakah Anda yakin ingin menghapus supplier "${name}"? Tindakan ini tidak dapat dibatalkan.`,
            'Ya, Hapus',
            'Batal',
            function() {
                const form = document.getElementById('delete-form');
                form.action = '{{ url('suppliers') }}/' + id;
                form.submit();
            }
        );
    }
</script>
@endpush
@endsection

