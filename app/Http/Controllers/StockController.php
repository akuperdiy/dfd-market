<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::with('stockMovements')->latest()->paginate(20);
        return view('stock.index', compact('products'));
    }

    public function adjust()
    {
        $products = Product::all();
        return view('stock.adjust', compact('products'));
    }

    public function processAdjust(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer',
            'reason' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($validated['product_id']);
            $newStock = $product->stock + $validated['qty'];

            if ($newStock < 0) {
                return back()->with('error', 'Stock tidak boleh negatif');
            }

            $product->update(['stock' => $newStock]);

            StockMovement::create([
                'product_id' => $product->id,
                'qty' => $validated['qty'],
                'type' => 'ADJUST',
                'ref' => $validated['reason'] ?? 'Stock Adjustment',
                'user_id' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('stock.index')->with('success', 'Stock berhasil disesuaikan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyesuaikan stock: ' . $e->getMessage());
        }
    }
}

