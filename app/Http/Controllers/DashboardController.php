<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $salesToday = Sale::whereDate('created_at', today())->sum('total');
        $lowStockCount = Product::where('stock', '<', 10)->count();

        $topProducts = Sale::select('products.name', DB::raw('SUM(sale_items.qty) as total_qty'))
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereDate('sales.created_at', today())
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('salesToday', 'lowStockCount', 'topProducts'));
    }
}

