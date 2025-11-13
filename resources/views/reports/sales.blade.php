@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<h2>Laporan Penjualan</h2>

<form method="GET" action="{{ route('reports.sales') }}" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <label for="from" class="form-label">Dari Tanggal</label>
            <input type="date" class="form-control" id="from" name="from" value="{{ $from }}" required>
        </div>
        <div class="col-md-4">
            <label for="to" class="form-label">Sampai Tanggal</label>
            <input type="date" class="form-control" id="to" name="to" value="{{ $to }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">&nbsp;</label>
            <button type="submit" class="btn btn-primary d-block">Filter</button>
        </div>
    </div>
</form>

<div class="row mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>Total Penjualan</h5>
                <h3>Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>Total Transaksi</h5>
                <h3>{{ $totalTransactions }}</h3>
            </div>
        </div>
    </div>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Invoice</th>
            <th>Kasir</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Diskon</th>
            <th>Metode</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->invoice_no }}</td>
                <td>{{ $sale->cashier->name }}</td>
                <td>{{ $sale->customer_name ?? '-' }}</td>
                <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($sale->discount, 0, ',', '.') }}</td>
                <td>{{ ucfirst($sale->payment_method) }}</td>
                <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection

