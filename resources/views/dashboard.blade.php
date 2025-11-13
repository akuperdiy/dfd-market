@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h2>Dashboard</h2>
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-2">
                            <i class="bi bi-cash-stack me-2"></i>Penjualan Hari Ini
                        </h5>
                        <h3>Rp {{ number_format($salesToday, 0, ',', '.') }}</h3>
                    </div>
                    <i class="bi bi-graph-up-arrow" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-2">
                            <i class="bi bi-exclamation-triangle me-2"></i>Stock Menipis
                        </h5>
                        <h3>{{ $lowStockCount }} Produk</h3>
                    </div>
                    <i class="bi bi-box-seam" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-2">
                            <i class="bi bi-boxes me-2"></i>Total Produk
                        </h5>
                        <h3>{{ \App\Models\Product::count() }} Produk</h3>
                    </div>
                    <i class="bi bi-archive" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Top 5 Produk Hari Ini</h5>
            </div>
            <div class="card-body">
                @if($topProducts->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Jumlah Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->total_qty }} unit</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">Belum ada penjualan hari ini</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

