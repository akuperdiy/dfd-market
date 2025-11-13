<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $from = $request->input('from', date('Y-m-01'));
        $to = $request->input('to', date('Y-m-d'));

        $sales = Sale::whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->with('cashier')
            ->latest()
            ->get();

        $totalSales = $sales->sum('total');
        $totalTransactions = $sales->count();

        return view('reports.sales', compact('sales', 'totalSales', 'totalTransactions', 'from', 'to'));
    }

    public function stockReport()
    {
        $products = Product::with('stockMovements')->get();
        $lowStock = Product::where('stock', '<', 10)->get();

        return view('reports.stock', compact('products', 'lowStock'));
    }
}

