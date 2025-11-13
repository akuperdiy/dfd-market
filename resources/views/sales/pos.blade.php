@extends('layouts.app')

@section('title', 'Point of Sale')

@section('content')
<h2>Point of Sale (POS)</h2>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" id="barcode-input" placeholder="Scan atau ketik barcode, lalu tekan Enter" autofocus>
                    <button class="btn btn-primary" type="button" onclick="searchProduct()">Cari</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Keranjang</h5>
            </div>
            <div class="card-body">
                <table class="table" id="cart-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="cart-body">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Total</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="customer_name" class="form-label">Nama Customer (Opsional)</label>
                    <input type="text" class="form-control" id="customer_name">
                </div>
                <div class="mb-3">
                    <label for="discount" class="form-label">Diskon</label>
                    <input type="number" step="0.01" class="form-control" id="discount" value="0">
                </div>
                <div class="mb-3">
                    <label for="payment_method" class="form-label">Metode Pembayaran</label>
                    <select class="form-select" id="payment_method" required>
                        <option value="cash">Cash</option>
                        <option value="debit">Debit</option>
                        <option value="credit">Credit</option>
                    </select>
                </div>
                <hr>
                <h4>Total: <span id="total-display">Rp 0</span></h4>
                <button class="btn btn-success btn-lg w-100 mt-3" id="pay-btn" onclick="processPayment()" disabled>Bayar</button>
            </div>
        </div>
    </div>
</div>

<div id="invoice-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invoice</h5>
            </div>
            <div class="modal-body" id="invoice-content">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">Print</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let cart = [];
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

document.getElementById('barcode-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        searchProduct();
    }
});

function searchProduct() {
    const barcode = document.getElementById('barcode-input').value.trim();
    if (!barcode) return;

    fetch(`/api/products?barcode=${encodeURIComponent(barcode)}`, {
        headers: {
            'Authorization': 'Bearer ' + (localStorage.getItem('token') || ''),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Produk tidak ditemukan');
            return;
        }
        addToCart(data);
        document.getElementById('barcode-input').value = '';
        document.getElementById('barcode-input').focus();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error mencari produk');
    });
}

function addToCart(product) {
    const existing = cart.find(item => item.product_id === product.id);
    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({
            product_id: product.id,
            name: product.name,
            qty: 1,
            price: parseFloat(product.sell_price),
            stock: product.stock
        });
    }
    updateCartDisplay();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartDisplay();
}

function updateQty(index, change) {
    cart[index].qty += change;
    if (cart[index].qty <= 0) {
        cart.splice(index, 1);
    }
    updateCartDisplay();
}

function updateCartDisplay() {
    const tbody = document.getElementById('cart-body');
    tbody.innerHTML = '';
    
    let total = 0;
    cart.forEach((item, index) => {
        const subtotal = item.qty * item.price;
        total += subtotal;
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.name}</td>
            <td>
                <button class="btn btn-sm btn-secondary" onclick="updateQty(${index}, -1)">-</button>
                <span class="mx-2">${item.qty}</span>
                <button class="btn btn-sm btn-secondary" onclick="updateQty(${index}, 1)">+</button>
            </td>
            <td>Rp ${item.price.toLocaleString('id-ID')}</td>
            <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
            <td><button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">Hapus</button></td>
        `;
        tbody.appendChild(row);
    });
    
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const finalTotal = total - discount;
    document.getElementById('total-display').textContent = 'Rp ' + finalTotal.toLocaleString('id-ID');
    document.getElementById('pay-btn').disabled = cart.length === 0;
}

function processPayment() {
    if (cart.length === 0) return;
    
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const paymentMethod = document.getElementById('payment_method').value;
    const customerName = document.getElementById('customer_name').value;
    
    const data = {
        items: cart.map(item => ({
            product_id: item.product_id,
            qty: item.qty,
            price: item.price
        })),
        discount: discount,
        payment_method: paymentMethod,
        customer_name: customerName
    };
    
    document.getElementById('pay-btn').disabled = true;
    
    fetch('/api/sales', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
            document.getElementById('pay-btn').disabled = false;
            return;
        }
        
        showInvoice(data);
        cart = [];
        updateCartDisplay();
        document.getElementById('barcode-input').focus();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error memproses pembayaran');
        document.getElementById('pay-btn').disabled = false;
    });
}

function showInvoice(data) {
    const content = `
        <h5>Invoice: ${data.invoice_no}</h5>
        <p>Total: Rp ${data.total.toLocaleString('id-ID')}</p>
        <p>Terima kasih atas pembelian Anda!</p>
    `;
    document.getElementById('invoice-content').innerHTML = content;
    const modal = new bootstrap.Modal(document.getElementById('invoice-modal'));
    modal.show();
}

document.getElementById('discount').addEventListener('input', updateCartDisplay);
</script>
@endpush
@endsection

