<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function posPage()
    {
        return view('sales.pos');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'customer_name' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock < $item['qty']) {
                    DB::rollBack();
                    return response()->json([
                        'error' => 'Stock tidak cukup untuk produk: ' . $product->name
                    ], 400);
                }
                $total += $item['qty'] * $item['price'];
            }

            $discount = $validated['discount'] ?? 0;
            $finalTotal = $total - $discount;

            $sale = Sale::create([
                'invoice_no' => 'INV-' . date('Ymd') . '-' . str_pad(Sale::count() + 1, 4, '0', STR_PAD_LEFT),
                'cashier_id' => auth()->id(),
                'customer_name' => $validated['customer_name'] ?? null,
                'total' => $finalTotal,
                'discount' => $discount,
                'payment_method' => $validated['payment_method'],
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                ]);

                $product->decrement('stock', $item['qty']);

                StockMovement::create([
                    'product_id' => $product->id,
                    'qty' => -$item['qty'],
                    'type' => 'OUT',
                    'ref' => 'SALE-' . $sale->invoice_no,
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'invoice_no' => $sale->invoice_no,
                'total' => $finalTotal,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Gagal membuat penjualan: ' . $e->getMessage()
            ], 500);
        }
    }
}

