@extends('layouts.app')

@section('title', 'Buat Retur')

@section('content')
<h2>Retur untuk Invoice: {{ $sale->invoice_no }}</h2>

<form method="POST" action="{{ route('returns.store') }}">
    @csrf
    <input type="hidden" name="sale_id" value="{{ $sale->id }}">
    
    <div class="mb-3">
        <label for="reason" class="form-label">Alasan Retur</label>
        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
    </div>

    <table class="table table-striped mb-3">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty Terjual</th>
                <th>Harga</th>
                <th>Qty Retur</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>
                        <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $item->product_id }}">
                        <input type="hidden" name="items[{{ $loop->index }}][price]" value="{{ $item->price }}">
                        <input type="number" class="form-control" name="items[{{ $loop->index }}][qty]" min="0" max="{{ $item->qty }}" value="0" required>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <button type="submit" class="btn btn-primary">Proses Retur</button>
    <a href="{{ route('returns.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection

