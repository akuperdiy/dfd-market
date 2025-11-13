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
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Total</h5>
                <button type="button" class="btn btn-sm btn-outline-info" onclick="showKeyboardShortcuts()" title="Keyboard Shortcuts">
                    <i class="bi bi-keyboard"></i>
                </button>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="invoice-content">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="printInvoice()">Print</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let cart = [];
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // F1 - Focus barcode input
    if (e.key === 'F1') {
        e.preventDefault();
        document.getElementById('barcode-input').focus();
        return;
    }
    
    // F2 - Process payment
    if (e.key === 'F2' && cart.length > 0) {
        e.preventDefault();
        processPayment();
        return;
    }
    
    // F3 - Clear cart
    if (e.key === 'F3') {
        e.preventDefault();
        if (cart.length > 0) {
            showConfirmDialog(
                'Bersihkan Keranjang',
                'Apakah Anda yakin ingin membersihkan semua item di keranjang?',
                'Ya, Bersihkan',
                'Batal',
                function() {
                    cart = [];
                    updateCartDisplay();
                    document.getElementById('barcode-input').focus();
                    showToast('Keranjang telah dibersihkan', 'info');
                }
            );
        }
        return;
    }
    
    // Escape - Clear barcode input
    if (e.key === 'Escape' && document.activeElement.id === 'barcode-input') {
        document.getElementById('barcode-input').value = '';
        return;
    }
    
    // Ctrl/Cmd + Enter - Process payment
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter' && cart.length > 0) {
        e.preventDefault();
        processPayment();
        return;
    }
});

// Show keyboard shortcuts info
function showKeyboardShortcuts() {
    const shortcuts = `
        <div class="mb-2"><kbd>F1</kbd> - Fokus ke input barcode</div>
        <div class="mb-2"><kbd>F2</kbd> - Proses pembayaran</div>
        <div class="mb-2"><kbd>F3</kbd> - Bersihkan keranjang</div>
        <div class="mb-2"><kbd>Esc</kbd> - Hapus input barcode</div>
        <div class="mb-2"><kbd>Ctrl/Cmd + Enter</kbd> - Proses pembayaran</div>
        <div class="mb-2"><kbd>Enter</kbd> - Cari produk (di input barcode)</div>
    `;
    
    showConfirmDialog(
        'Keyboard Shortcuts',
        shortcuts,
        'Tutup',
        '',
        function() {}
    );
}

document.getElementById('barcode-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        searchProduct();
    }
});

function searchProduct() {
    const barcode = document.getElementById('barcode-input').value.trim();
    if (!barcode) {
        showToast('Masukkan barcode produk', 'error');
        return;
    }

    const input = document.getElementById('barcode-input');
    input.disabled = true;
    
    fetch(`/api/products?barcode=${encodeURIComponent(barcode)}`, {
        headers: {
            'Authorization': 'Bearer ' + (localStorage.getItem('token') || ''),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        input.disabled = false;
        if (data.error) {
            showToast('Produk tidak ditemukan', 'error');
            return;
        }
        addToCart(data);
        document.getElementById('barcode-input').value = '';
        document.getElementById('barcode-input').focus();
        showToast(`${data.name} ditambahkan ke keranjang`, 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        input.disabled = false;
        showToast('Error mencari produk', 'error');
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
    if (cart.length === 0) {
        showToast('Keranjang masih kosong', 'error');
        return;
    }
    
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
    
    const payBtn = document.getElementById('pay-btn');
    payBtn.disabled = true;
    payBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    showLoading('Memproses pembayaran...');
    
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
        hideLoading();
        if (data.error) {
            showToast('Error: ' + data.error, 'error');
            payBtn.disabled = false;
            payBtn.innerHTML = 'Bayar';
            return;
        }
        
        showInvoice(data);
        cart = [];
        updateCartDisplay();
        document.getElementById('barcode-input').focus();
        showToast('Pembayaran berhasil diproses!', 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        hideLoading();
        showToast('Error memproses pembayaran', 'error');
        payBtn.disabled = false;
        payBtn.innerHTML = 'Bayar';
    });
}

function showInvoice(data) {
    let itemsHtml = '';
    data.items.forEach(item => {
        itemsHtml += `
            <tr>
                <td>${item.name}</td>
                <td class="text-center">${item.qty}</td>
                <td class="text-end">Rp ${parseFloat(item.price).toLocaleString('id-ID')}</td>
                <td class="text-end">Rp ${parseFloat(item.subtotal).toLocaleString('id-ID')}</td>
            </tr>
        `;
    });

    const content = `
        <div class="invoice-content">
            <div class="text-center mb-4">
                <h3>DFD Market</h3>
                <p class="mb-0">Invoice: <strong>${data.invoice_no}</strong></p>
                <p class="mb-0">Tanggal: ${data.date}</p>
                ${data.customer_name ? `<p class="mb-0">Customer: ${data.customer_name}</p>` : ''}
                <p class="mb-0">Kasir: ${data.cashier_name}</p>
            </div>
            <hr>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHtml}
                </tbody>
            </table>
            <hr>
            <div class="row">
                <div class="col-8 text-end"><strong>Subtotal:</strong></div>
                <div class="col-4 text-end"><strong>Rp ${parseFloat(data.subtotal).toLocaleString('id-ID')}</strong></div>
            </div>
            ${data.discount > 0 ? `
            <div class="row">
                <div class="col-8 text-end">Diskon:</div>
                <div class="col-4 text-end">Rp ${parseFloat(data.discount).toLocaleString('id-ID')}</div>
            </div>
            ` : ''}
            <div class="row">
                <div class="col-8 text-end"><strong>TOTAL:</strong></div>
                <div class="col-4 text-end"><strong>Rp ${parseFloat(data.total).toLocaleString('id-ID')}</strong></div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <small>Metode Pembayaran: ${data.payment_method.toUpperCase()}</small>
                </div>
            </div>
            <hr>
            <div class="text-center mt-4">
                <p class="mb-0"><strong>Terima kasih atas pembelian Anda!</strong></p>
            </div>
        </div>
    `;
    
    document.getElementById('invoice-content').innerHTML = content;
    
    // Store invoice data for printing
    window.invoiceData = data;
    window.invoiceHtml = content;
    
    const modal = new bootstrap.Modal(document.getElementById('invoice-modal'));
    modal.show();
}

function printInvoice() {
    if (!window.invoiceData) {
        alert('Tidak ada invoice untuk dicetak');
        return;
    }
    
    const data = window.invoiceData;
    let itemsHtml = '';
    data.items.forEach(item => {
        itemsHtml += `
            <tr>
                <td>${item.name}</td>
                <td style="text-align: center;">${item.qty}</td>
                <td style="text-align: right;">Rp ${parseFloat(item.price).toLocaleString('id-ID')}</td>
                <td style="text-align: right;">Rp ${parseFloat(item.subtotal).toLocaleString('id-ID')}</td>
            </tr>
        `;
    });
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Invoice ${data.invoice_no}</title>
            <style>
                @media print {
                    @page {
                        size: A4;
                        margin: 10mm;
                    }
                    body {
                        margin: 0;
                        padding: 20px;
                    }
                }
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    padding: 20px;
                    max-width: 800px;
                    margin: 0 auto;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header h3 {
                    margin: 10px 0;
                    font-size: 18px;
                }
                .header p {
                    margin: 5px 0;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 15px 0;
                }
                table th, table td {
                    padding: 8px;
                    border-bottom: 1px solid #ddd;
                    text-align: left;
                }
                table th {
                    background-color: #f8f9fa;
                    font-weight: bold;
                }
                .text-center {
                    text-align: center;
                }
                .text-end {
                    text-align: right;
                }
                .summary {
                    margin-top: 15px;
                }
                .summary-row {
                    display: flex;
                    justify-content: space-between;
                    padding: 5px 0;
                }
                .summary-row.total {
                    font-weight: bold;
                    font-size: 14px;
                    border-top: 2px solid #000;
                    padding-top: 10px;
                    margin-top: 10px;
                }
                hr {
                    margin: 15px 0;
                    border: none;
                    border-top: 1px solid #ddd;
                }
                .footer {
                    text-align: center;
                    margin-top: 30px;
                }
                .payment-method {
                    text-align: center;
                    margin-top: 15px;
                    font-size: 11px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h3>DFD MARKET</h3>
                <p><strong>Invoice: ${data.invoice_no}</strong></p>
                <p>Tanggal: ${data.date}</p>
                ${data.customer_name ? `<p>Customer: ${data.customer_name}</p>` : ''}
                <p>Kasir: ${data.cashier_name}</p>
            </div>
            <hr>
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHtml}
                </tbody>
            </table>
            <hr>
            <div class="summary">
                <div class="summary-row">
                    <span><strong>Subtotal:</strong></span>
                    <span><strong>Rp ${parseFloat(data.subtotal).toLocaleString('id-ID')}</strong></span>
                </div>
                ${data.discount > 0 ? `
                <div class="summary-row">
                    <span>Diskon:</span>
                    <span>Rp ${parseFloat(data.discount).toLocaleString('id-ID')}</span>
                </div>
                ` : ''}
                <div class="summary-row total">
                    <span>TOTAL:</span>
                    <span>Rp ${parseFloat(data.total).toLocaleString('id-ID')}</span>
                </div>
            </div>
            <div class="payment-method">
                Metode Pembayaran: ${data.payment_method.toUpperCase()}
            </div>
            <hr>
            <div class="footer">
                <p><strong>Terima kasih atas pembelian Anda!</strong></p>
            </div>
        </body>
        </html>
    `);
    printWindow.document.close();
    
    // Wait for content to load, then print
    setTimeout(() => {
        printWindow.print();
        printWindow.onafterprint = () => printWindow.close();
    }, 250);
}

document.getElementById('discount').addEventListener('input', updateCartDisplay);
</script>
@endpush
@endsection

