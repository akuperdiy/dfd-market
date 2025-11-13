<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ReturnItem;
use App\Models\ReturnModel;
use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function createReturn($saleId)
    {
        $sale = Sale::with('items.product')->findOrFail($saleId);
        return view('returns.create', compact('sale'));
    }

    public function storeReturn(Request $request)
    {
        $validated = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'reason' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['qty'] * $item['price'];
            }

            $return = ReturnModel::create([
                'sale_id' => $validated['sale_id'],
                'user_id' => auth()->id(),
                'total' => $total,
                'reason' => $validated['reason'],
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                ReturnItem::create([
                    'return_id' => $return->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                ]);

                $product->increment('stock', $item['qty']);

                StockMovement::create([
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'type' => 'IN',
                    'ref' => 'RETURN-' . $return->id,
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();
            return redirect()->route('returns.index')->with('success', 'Retur berhasil diproses');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses retur: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $returns = ReturnModel::with('sale', 'user')->latest()->paginate(20);
        return view('returns.index', compact('returns'));
    }
}

