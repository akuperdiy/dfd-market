<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\PurchaseOrder;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with('supplier', 'creator')->latest()->paginate(20);
        return view('purchase-orders.index', compact('orders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchase-orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $po = PurchaseOrder::create([
                'po_no' => 'PO-' . date('Ymd') . '-' . str_pad(PurchaseOrder::count() + 1, 4, '0', STR_PAD_LEFT),
                'supplier_id' => $validated['supplier_id'],
                'status' => 'pending',
                'total' => 0,
                'created_by' => auth()->id(),
            ]);

            $total = 0;
            foreach ($validated['items'] as $item) {
                PurchaseItem::create([
                    'po_id' => $po->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                ]);
                $total += $item['qty'] * $item['price'];
            }

            $po->update(['total' => $total]);
            DB::commit();

            return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat Purchase Order: ' . $e->getMessage());
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('items.product', 'supplier', 'creator');
        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function receive(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'received') {
            return back()->with('error', 'PO sudah diterima sebelumnya');
        }

        DB::beginTransaction();
        try {
            foreach ($purchaseOrder->items as $item) {
                $product = $item->product;

                if ($product->track_batch) {
                    $batchNo = request('batches')[$item->id]['batch_no'] ?? 'BATCH-' . date('Ymd') . '-' . $item->id;
                    $expiry = request('batches')[$item->id]['expiry'] ?? null;

                    ProductBatch::create([
                        'product_id' => $product->id,
                        'batch_no' => $batchNo,
                        'qty' => $item->qty,
                        'expiry' => $expiry,
                    ]);
                }

                $product->increment('stock', $item->qty);

                StockMovement::create([
                    'product_id' => $product->id,
                    'qty' => $item->qty,
                    'type' => 'IN',
                    'ref' => 'PO-' . $purchaseOrder->po_no,
                    'user_id' => auth()->id(),
                ]);
            }

            $purchaseOrder->update(['status' => 'received']);
            DB::commit();

            return redirect()->route('purchase-orders.show', $purchaseOrder)->with('success', 'Stock berhasil diterima');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menerima stock: ' . $e->getMessage());
        }
    }
}

