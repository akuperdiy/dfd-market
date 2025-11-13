<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DFD MARKET')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Smooth transitions */
        * {
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }
        
        /* Toast container */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .toast-item {
            min-width: 300px;
            margin-bottom: 10px;
            animation: slideInRight 0.3s ease;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
        }
        
        .spinner-border-lg {
            width: 3rem;
            height: 3rem;
            border-width: 0.3rem;
        }
        
        /* Card hover effects */
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* Button animations */
        .btn {
            transition: all 0.2s ease;
        }
        
        .btn:active {
            transform: scale(0.98);
        }
        
        /* Table row hover */
        .table tbody tr {
            transition: background-color 0.15s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        /* Fade in animation */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease;
        }
        
        /* Pulse animation for badges */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .badge.pulse {
            animation: pulse 2s infinite;
        }
        
        /* Active navigation item */
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.25) !important;
            border-radius: 5px;
            font-weight: 600;
            position: relative;
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background-color: #fff;
            border-radius: 0 4px 4px 0;
        }
        
        .nav-item {
            position: relative;
        }
        
        .nav-link:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }
        
        /* Dropdown active state */
        .dropdown-toggle.active {
            background-color: rgba(255, 255, 255, 0.2) !important;
            border-radius: 5px;
        }
        
        .dropdown-item.active {
            background-color: #0d6efd;
            color: #fff;
        }
        
        /* Breadcrumb */
        .breadcrumb-nav {
            background-color: #f8f9fa;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
        }
        
        .breadcrumb-nav .breadcrumb {
            margin-bottom: 0;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">DFD Market</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i>Dashboard
                            </a>
                        </li>
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('kasir'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('pos') ? 'active' : '' }}" href="{{ route('pos') }}">
                                    <i class="bi bi-cash-register me-1"></i>POS
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->hasRole('admin'))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('products.*') || request()->routeIs('suppliers.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-database me-1"></i>Master Data
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                                        <i class="bi bi-box-seam me-2"></i>Produk
                                    </a></li>
                                    <li><a class="dropdown-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                                        <i class="bi bi-truck me-2"></i>Supplier
                                    </a></li>
                                </ul>
                            </li>
                        @endif
                        @if(auth()->user()->hasRole('gudang') || auth()->user()->hasRole('admin'))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('purchase-orders.*') || request()->routeIs('stock.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-archive me-1"></i>Gudang
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}" href="{{ route('purchase-orders.index') }}">
                                        <i class="bi bi-cart-check me-2"></i>Purchase Order
                                    </a></li>
                                    <li><a class="dropdown-item {{ request()->routeIs('stock.*') ? 'active' : '' }}" href="{{ route('stock.index') }}">
                                        <i class="bi bi-boxes me-2"></i>Stock
                                    </a></li>
                                </ul>
                            </li>
                        @endif
                        @if(auth()->user()->hasRole('kasir') || auth()->user()->hasRole('admin'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('returns.*') ? 'active' : '' }}" href="{{ route('returns.index') }}">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Retur
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('admin'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.sales') }}">
                                    <i class="bi bi-graph-up me-1"></i>Laporan
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->hasRole('admin'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('backups.*') ? 'active' : '' }}" href="{{ route('backups.index') }}">
                                    <i class="bi bi-database-check me-1"></i>Backup
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role->name) }})
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4 fade-in">
        <!-- Breadcrumb Navigation -->
        @auth
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">
                            <i class="bi bi-house-door me-1"></i>Home
                        </a>
                    </li>
                    @if(request()->routeIs('dashboard'))
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    @elseif(request()->routeIs('pos'))
                        <li class="breadcrumb-item active" aria-current="page">POS</li>
                    @elseif(request()->routeIs('products.*'))
                        <li class="breadcrumb-item">
                            <a href="{{ route('products.index') }}" class="text-decoration-none">Master Data</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            @if(request()->routeIs('products.create'))
                                Tambah Produk
                            @elseif(request()->routeIs('products.edit'))
                                Edit Produk
                            @else
                                Produk
                            @endif
                        </li>
                    @elseif(request()->routeIs('suppliers.*'))
                        <li class="breadcrumb-item">
                            <a href="{{ route('suppliers.index') }}" class="text-decoration-none">Master Data</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            @if(request()->routeIs('suppliers.create'))
                                Tambah Supplier
                            @elseif(request()->routeIs('suppliers.edit'))
                                Edit Supplier
                            @else
                                Supplier
                            @endif
                        </li>
                    @elseif(request()->routeIs('purchase-orders.*'))
                        <li class="breadcrumb-item">
                            <a href="{{ route('purchase-orders.index') }}" class="text-decoration-none">Gudang</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            @if(request()->routeIs('purchase-orders.create'))
                                Buat Purchase Order
                            @elseif(request()->routeIs('purchase-orders.show'))
                                Detail Purchase Order
                            @else
                                Purchase Order
                            @endif
                        </li>
                    @elseif(request()->routeIs('stock.*'))
                        <li class="breadcrumb-item">
                            <a href="{{ route('stock.index') }}" class="text-decoration-none">Gudang</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            @if(request()->routeIs('stock.adjust'))
                                Stock Adjustment
                            @else
                                Stock
                            @endif
                        </li>
                    @elseif(request()->routeIs('returns.*'))
                        <li class="breadcrumb-item active" aria-current="page">
                            @if(request()->routeIs('returns.create'))
                                Buat Retur
                            @else
                                Retur
                            @endif
                        </li>
                    @elseif(request()->routeIs('reports.*'))
                        <li class="breadcrumb-item active" aria-current="page">
                            @if(request()->routeIs('reports.stock'))
                                Laporan Stock
                            @else
                                Laporan Penjualan
                            @endif
                        </li>
                    @elseif(request()->routeIs('backups.*'))
                        <li class="breadcrumb-item active" aria-current="page">Backup Database</li>
                    @endif
                </ol>
            </nav>
        @endauth

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" data-auto-dismiss="5000">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" data-auto-dismiss="7000">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
        
        <!-- Footer -->
        <footer class="mt-5 py-3 border-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <small class="text-muted">
                            &copy; {{ date('Y') }} DFD MARKET - Sistem Informasi Manajemen Supermarket
                        </small>
                        <br>
                        <small class="text-muted">
                            Dibuat oleh: 
                            <span class="ms-1">Muhammad Ferdiansyah (NIM: 231011403662)</span> | 
                            <span class="ms-1">Muhammad Farrel Hilmi (NIM: 231011402000)</span> | 
                            <span class="ms-1">Aqila Adam (NIM: 231011402782)</span>
                        </small>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Toast Container -->
    <div id="toast-container"></div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div class="text-center text-white">
            <div class="spinner-border spinner-border-lg mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div id="loading-message">Memproses...</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toast Notification System
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');
            const toastId = 'toast-' + Date.now();
            const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
            const icon = type === 'success' ? 'bi-check-circle-fill' : type === 'error' ? 'bi-exclamation-triangle-fill' : 'bi-info-circle-fill';
            
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `toast-item toast show ${bgClass} text-white`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="toast-header ${bgClass} text-white border-0">
                    <i class="bi ${icon} me-2"></i>
                    <strong class="me-auto">${type === 'success' ? 'Berhasil' : type === 'error' ? 'Error' : 'Info'}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            `;
            
            toastContainer.appendChild(toast);
            
            const bsToast = new bootstrap.Toast(toast, { autohide: true, delay: 5000 });
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
            });
        }

        // Loading Overlay
        function showLoading(message = 'Memproses...') {
            const overlay = document.getElementById('loading-overlay');
            const messageEl = document.getElementById('loading-message');
            messageEl.textContent = message;
            overlay.style.display = 'flex';
        }

        function hideLoading() {
            const overlay = document.getElementById('loading-overlay');
            overlay.style.display = 'none';
        }

        // Confirmation Dialog
        function confirmAction(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }

        // Better Confirmation Dialog with Bootstrap Modal
        function showConfirmDialog(title, message, confirmText = 'Ya', cancelText = 'Batal', callback) {
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.setAttribute('tabindex', '-1');
            modal.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${title}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            ${message}
                        </div>
                        <div class="modal-footer">
                            ${cancelText ? `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${cancelText}</button>` : ''}
                            <button type="button" class="btn btn-danger" id="confirm-btn">${confirmText}</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
            const confirmBtn = modal.querySelector('#confirm-btn');
            confirmBtn.addEventListener('click', () => {
                bsModal.hide();
                setTimeout(() => {
                    if (callback) callback();
                    modal.remove();
                }, 300);
            });
            
            modal.addEventListener('hidden.bs.modal', () => {
                setTimeout(() => modal.remove(), 100);
            });
        }

        // Auto-dismiss alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[data-auto-dismiss]');
            alerts.forEach(alert => {
                const delay = parseInt(alert.getAttribute('data-auto-dismiss'));
                setTimeout(() => {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                }, delay);
            });
        });

        // Form submission with loading
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.classList.contains('show-loading')) {
                showLoading('Menyimpan data...');
            }
        });

        // Handle form errors
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

