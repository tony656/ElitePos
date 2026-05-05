<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Return</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #004E89;
            --secondary-color: #1a659e;
            --accent-color: #f8f9fa;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --text-dark: #343a40;
            --text-light: #6c757d;
            --border-radius: 8px;
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            --transition: all 0.2s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; zoom: 1.1; }
        .main-container { max-width: 1900px; margin: 0 auto; padding: 0.75rem; }

        /* Header */
        .page-header { background: white; padding: 0.9rem 1.2rem; border-radius: var(--border-radius); margin-bottom: 0.75rem; box-shadow: var(--shadow); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .page-title { font-size: 1.4rem; font-weight: 700; color: var(--danger-color); display: flex; align-items: center; gap: 0.4rem; }
        
        .warning-banner { background: #fff5f5; border: 2px solid var(--danger-color); border-radius: var(--border-radius); padding: 0.75rem 1rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem; }
        .warning-banner i { color: var(--danger-color); font-size: 1.5rem; }
        .warning-banner div { flex: 1; }
        .warning-banner strong { color: var(--danger-color); }
        .warning-banner small { color: var(--text-light); }

        /* Layout */
        .main-layout { display: grid; grid-template-columns: 70% 30%; gap: 0.75rem; height: calc(100vh - 220px); }
        @media (max-width: 1200px) { .main-layout { grid-template-columns: 1fr; height: auto; } }

        /* Panels */
        .left-panel { background: white; border-radius: var(--border-radius); box-shadow: var(--shadow); display: flex; flex-direction: column; overflow: hidden; }
        .right-panel { background: white; border-radius: var(--border-radius); box-shadow: var(--shadow); display: flex; flex-direction: column; overflow: hidden; }
        .panel-header { background: linear-gradient(135deg, var(--danger-color), #c82333); color: white; padding: 0.75rem 1rem; font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; gap: 0.4rem; }
        .panel-body { padding: 0.75rem; flex: 1; display: flex; flex-direction: column; overflow: hidden; gap: 0.75rem; }

        /* Product Search */
        .product-search-section { flex-shrink: 0; position: relative; }
        .search-box { position: relative; }
        .search-box input { width: 100%; padding: 0.5rem 0.75rem 0.5rem 2.2rem; border: 2px solid #e9ecef; border-radius: 6px; font-size: 0.9rem; transition: var(--transition); }
        .search-box input:focus { border-color: var(--danger-color); box-shadow: 0 0 0 2px rgba(220,53,69,0.1); outline: none; }
        .search-box i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-light); font-size: 0.9rem; }
        .search-clear-btn { position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: var(--text-light); cursor: pointer; font-size: 0.9rem; padding: 0.2rem; display: none; }
        .search-clear-btn:hover { color: var(--danger-color); }
        .search-box:has(input:not(:placeholder-shown)) .search-clear-btn { display: block; }
        .search-hint { position: absolute; bottom: -25px; left: 0; font-size: 0.75rem; color: var(--text-light); opacity: 0.8; }

        /* Product Dropdown */
        .product-list-section { width: 100%; max-height: 400px; display: none; flex-direction: column; overflow: hidden; min-height: 0; background: white; border-radius: var(--border-radius); box-shadow: 0 4px 16px rgba(0,0,0,0.15); z-index: 1000; border: 1px solid #e9ecef; animation: slideDown 0.2s ease-out; }
        .product-list-section.active { display: flex; }
        @keyframes slideDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
        .product-list-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0; flex-shrink: 0; padding: 0.75rem; border-bottom: 1px solid #e9ecef; background: #fff5f5; }
        .product-list-title { font-weight: 600; color: var(--danger-color); font-size: 0.95rem; }
        .product-count { background: var(--danger-color); color: white; padding: 0.2rem 0.5rem; border-radius: 20px; font-size: 0.8rem; }
        .product-list { flex: 1; overflow-y: auto; border: none; border-radius: 0; background: white; min-height: 0; }

        .product-item { padding: 0.6rem 1rem; border-bottom: 1px solid #e9ecef; cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: 0.6rem; font-size: 0.9rem; position: relative; user-select: none; }
        .product-item:hover { background: #fff5f5; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transform: translateX(2px); }
        .product-item.added { background: #fff5f5; border-left: 3px solid var(--danger-color); }
        .product-info { flex: 1; min-width: 0; }
        .product-name { font-weight: 600; color: var(--text-dark); margin-bottom: 0.15rem; font-size: 0.9rem; }
        .product-prices { font-size: 0.75rem; color: var(--text-light); display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .add-indicator { position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: var(--danger-color); color: white; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; opacity: 0; transition: var(--transition); }
        .product-item.added .add-indicator { opacity: 1; }

        /* Form */
        .supplier-section { padding: 0.75rem; border-bottom: 1px solid #e9ecef; flex-shrink: 0; }
        .form-group-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
        @media (max-width: 768px) { .form-group-row { grid-template-columns: 1fr; } }
        .form-group { margin-bottom: 0; }
        .form-label { font-weight: 600; color: var(--text-dark); margin-bottom: 0.3rem; font-size: 0.85rem; display: flex; align-items: center; gap: 0.3rem; }
        .form-control, .form-select { border: 2px solid #e9ecef; border-radius: 6px; padding: 0.5rem 0.75rem; transition: var(--transition); font-size: 0.9rem; }
        .form-control:focus, .form-select:focus { border-color: var(--danger-color); box-shadow: 0 0 0 2px rgba(220,53,69,0.1); outline: none; }

        /* Cart */
        .cart-section { flex: 1; display: flex; flex-direction: column; overflow: hidden; min-height: 0; }
        .cart-header { padding: 0.75rem; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; }
        .cart-title { font-weight: 600; color: var(--text-dark); font-size: 1rem; display: flex; align-items: center; gap: 0.4rem; }
        .cart-count { background: var(--danger-color); color: white; padding: 0.2rem 0.5rem; border-radius: 20px; font-size: 0.8rem; }
        .cart-items { flex: 1; overflow-y: auto; padding: 0.75rem; min-height: 0; }
        .cart-items-table { width: 100%; border-collapse: collapse; }
        .cart-items-table thead { background: #fff5f5; position: sticky; top: 0; z-index: 10; }
        .cart-items-table th { padding: 0.5rem; text-align: left; font-weight: 600; font-size: 0.75rem; color: var(--danger-color); border-bottom: 2px solid #e9ecef; }
        .cart-items-table td { padding: 0.4rem; font-size: 0.8rem; border-bottom: 1px solid #e9ecef; vertical-align: middle; }
        .cart-items-table tbody tr:hover { background: #fff5f5; }
        .cart-product-cell { font-weight: 600; color: var(--text-dark); max-width: 100px; overflow: hidden; text-overflow: ellipsis; }
        .price-cell { text-align: right; font-size: 0.75rem; }
        .qty-cell { text-align: center; width: 50px; }
        .qty-cell input, .price-cell input { width: 100%; padding: 0.3rem; text-align: center; border: 1px solid #e9ecef; border-radius: 3px; font-size: 0.75rem; }
        .qty-cell input:focus, .price-cell input:focus { border-color: var(--danger-color); box-shadow: 0 0 0 2px rgba(220,53,69,0.1); outline: none; }
        .remove-item-table { text-align: center; width: 30px; }
        .remove-item-table button { background: transparent; border: none; color: var(--danger-color); cursor: pointer; font-size: 0.85rem; padding: 0; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; }
        .remove-item-table button:hover { background: rgba(220,53,69,0.1); border-radius: 3px; }

        /* Summary */
        .summary-section { padding: 0.75rem; border-top: 1px solid #e9ecef; background: #fff5f5; flex-shrink: 0; }
        .summary-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem; font-size: 0.9rem; }
        .summary-label { font-weight: 600; color: var(--text-dark); }
        .summary-value { font-weight: 700; font-size: 0.95rem; color: var(--danger-color); }
        .total-summary { background: linear-gradient(135deg, var(--danger-color), #c82333); color: white; padding: 0.8rem; border-radius: 6px; margin-top: 0.5rem; }
        .total-label { font-size: 0.9rem; font-weight: 600; margin-bottom: 0.3rem; }
        .total-amount { font-size: 1.4rem; font-weight: 800; text-align: right; }

        /* Action Buttons */
        .action-buttons { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; margin-top: 0.75rem; }
        .btn-primary-action { background: linear-gradient(135deg, var(--danger-color), #c82333); border: none; color: white; padding: 0.6rem; border-radius: 6px; font-weight: 700; cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: 0.3rem; font-size: 0.9rem; }
        .btn-primary-action:hover { transform: translateY(-1px); box-shadow: 0 4px 8px rgba(220,53,69,0.2); }
        .btn-secondary-action { background: #f8f9fa; border: 2px solid #e9ecef; color: var(--text-dark); padding: 0.6rem; border-radius: 6px; font-weight: 600; cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: 0.3rem; font-size: 0.9rem; }
        .btn-secondary-action:hover { background: #e9ecef; border-color: #dee2e6; }

        /* Empty State */
        .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem 1rem; text-align: center; color: var(--text-light); }
        .empty-state-icon { font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5; }
        .empty-state-title { font-size: 1rem; font-weight: 600; margin-bottom: 0.3rem; }
        .empty-state p { font-size: 0.85rem; margin: 0; }

        /* Alerts */
        .alert { border: none; border-radius: var(--border-radius); margin-bottom: 0.75rem; padding: 0.75rem 1rem; font-size: 0.9rem; }
        .alert-success { background: rgba(40,167,69,0.1); border-left: 4px solid var(--success-color); color: #155724; }
        .alert-danger  { background: rgba(220,53,69,0.1);  border-left: 4px solid var(--danger-color);  color: #721c24; }

        /* Toast */
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        .toast { background: white; border-radius: var(--border-radius); box-shadow: 0 4px 12px rgba(0,0,0,0.15); padding: 0.75rem 1rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; min-width: 300px; opacity: 0; transform: translateX(100%); transition: all 0.3s ease; }
        .toast.show { opacity: 1; transform: translateX(0); }
        .toast-success { border-left: 4px solid var(--success-color); }
        .toast-error   { border-left: 4px solid var(--danger-color); }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #d0d0d0; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
</head>
<body>
    <div class="row">
        @include('admin/sidenav')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="main-container">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-title">
                        <i class="bi bi-arrow-return-left"></i>
                        Make Return
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ url('admin/make-receiving') }}" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle me-1"></i> Make Receiving
                        </a>
                        <a href="{{ url('admin/view-returns') }}" class="btn btn-outline-danger">
                            <i class="bi bi-list-check me-1"></i> View Returns
                        </a>
                    </div>
                </div>

                <!-- Warning Banner -->
                <div class="warning-banner">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>Warning: This will decrease product quantities!</strong><br>
                        <small>Returning products will reduce the stock in your inventory. Make sure you have valid reasons for returning.</small>
                    </div>
                </div>

                <!-- Alerts -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="main-layout">
                    <!-- Left Panel -->
                    <div class="left-panel">
                        <div class="panel-header">
                            <i class="bi bi-search"></i> Select Products to Return
                        </div>

                        <div class="panel-body">
                            <!-- Search -->
                            <div class="product-search-section">
                                <div class="search-box">
                                    <i class="bi bi-search"></i>
                                    <input type="text" id="productSearch" placeholder="Search products by name...">
                                    <button class="search-clear-btn" id="clearSearch" title="Clear search">
                                        <i class="bi bi-x"></i>
                                    </button>
                                    <div class="search-hint">Click on any product to add to return list</div>
                                </div>

                                <!-- Product Dropdown -->
                                <div class="product-list-section" id="productListSection">
                                    <div class="product-list-header">
                                        <div class="product-list-title">Available Products</div>
                                        <div class="product-count" id="productCount">0</div>
                                    </div>
                                    <div class="product-list" id="productList"></div>
                                </div>
                            </div>

                            <!-- Cart -->
                            <div class="cart-section">
                                <div class="cart-header">
                                    <div class="cart-title">
                                        <i class="bi bi-arrow-return-left"></i> Products to Return
                                    </div>
                                    <div class="cart-count" id="cartCount">0</div>
                                </div>
                                <div class="cart-items" id="cartItems">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-arrow-return-left"></i></div>
                                        <div class="empty-state-title">No products added</div>
                                        <p>Search and click on products to add them to return list</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel -->
                    <div class="right-panel">
                        <div class="panel-header">
                            <i class="bi bi-clipboard"></i> Return Details
                        </div>

                        <div class="supplier-section">
                            <form id="orderForm">
                                @csrf
                                <div class="form-group-row">
                                    <div class="form-group">
                                        <label for="supplier" class="form-label">
                                            <i class="bi bi-shop"></i> Supplier
                                        </label>
                                        <select name="supplier" id="supplier" class="form-select" required>
                                            <option value="" disabled selected>Select Supplier</option>
                                            @foreach (DB::table('vendors')->get() as $vendor)
                                                <option value="{{ $vendor->name }}">{{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="served" class="form-label">
                                            <i class="bi bi-person"></i> Processed By
                                        </label>
                                        <select name="served" id="served" class="form-select" required>
                                            <option value="" disabled selected>Select Staff</option>
                                            @foreach (DB::table('users')->get() as $user)
                                                @if($user->account === getSessionAccountDisplayName() || $user->levelStatus === 'Admin')
                                                    <option value="{{ $user->name }}">{{ $user->name }} ({{ $user->levelStatus }})</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top: 0.6rem;">
                                    <label for="reason" class="form-label">
                                        <i class="bi bi-chat-text"></i> Reason for Return
                                    </label>
                                    <textarea name="reason" id="reason" class="form-control" rows="2" required
                                        placeholder="Please specify the reason for returning..."></textarea>
                                </div>

                                <div class="form-group" style="margin-top: 0.6rem;">
                                    <label for="transactionType" class="form-label">
                                        <i class="bi bi-credit-card"></i> Payment Type
                                    </label>
                                    <select name="transactionType" id="transactionType" class="form-select" onchange="applyTypeToAll(this.value)">
                                        <option value="Cash">Cash</option>
                                        <option value="Credit">Credit</option>
                                    </select>
                                </div>

                                @if(canUser('set_restock_date'))
                                <div class="form-group" style="margin-top: 0.6rem;">
                                    <label for="receivingDate" class="form-label">
                                        <i class="bi bi-calendar"></i> Return Date (Optional)
                                    </label>
                                    <input type="date" name="receivingDate" id="receivingDate" class="form-control" max="{{ date('Y-m-d') }}">
                                </div>
                                @endif
                            </form>
                        </div>

                        <!-- Summary -->
                        <div class="summary-section">
                            <div class="summary-item">
                                <span class="summary-label">Total Items</span>
                                <span class="summary-value" id="itemCount">0</span>
                            </div>
                            <div class="total-summary">
                                <div class="total-label">TOTAL RETURN VALUE</div>
                                <div class="total-amount" id="totalAmount">Tsh. 0.00</div>
                            </div>
                            <div class="action-buttons">
                                <button type="button" class="btn-secondary-action" id="clearCartBtn">
                                    <i class="bi bi-x-circle me-1"></i> Clear
                                </button>
                                <button type="button" class="btn-primary-action" id="submitOrderBtn">
                                    <i class="bi bi-check-circle me-1"></i> Submit Return
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
    // ==== STORAGE KEYS ====
    const STORAGE_KEY = 'returnCart';
    const CART_ITEMS_KEY = 'productsInReturnCart';

    function saveCartToStorage() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(shoppingCart));
            localStorage.setItem(CART_ITEMS_KEY, JSON.stringify(Array.from(productsInCart)));
        } catch (e) { console.error('Save cart error:', e); }
    }

    function loadCartFromStorage() {
        try {
            const savedCart = localStorage.getItem(STORAGE_KEY);
            if (savedCart) shoppingCart = JSON.parse(savedCart);

            const savedSet = localStorage.getItem(CART_ITEMS_KEY);
            if (savedSet) productsInCart = new Set(JSON.parse(savedSet));
        } catch (e) {
            shoppingCart = [];
            productsInCart = new Set();
        }
    }

    function clearCartStorage() {
        localStorage.removeItem(STORAGE_KEY);
        localStorage.removeItem(CART_ITEMS_KEY);
    }

    // ==== PRODUCT DATA (from PHP) ====
    const allProducts = [
        @if(DB::table('products')->where('name01','!=','')->where('account',getSessionAccountName())->count() > 0)
            @foreach (DB::table('products')->where('name01','!=','')->where('account',getSessionAccountName())->get() as $product)
            {
                id: "{{ $product->product_id }}",
                name: "{{ addslashes($product->name01) }}",
                cost: {{ $product->bPrice ?? 0 }},
                wholesale: {{ $product->wholesale ?? 0 }},
                retail: {{ $product->sPrice ?? 0 }},
                currentStock: {{ $product->quantity ?? 0 }}
            },
            @endforeach
        @else
            { id:'1', name:'Sugar 1kg', cost:2500, wholesale:2800, retail:3000, currentStock:100 },
            { id:'2', name:'Rice 5kg', cost:15000, wholesale:17000, retail:18000, currentStock:50 }
        @endif
    ];

    // ==== STATE ====
    let shoppingCart = [];
    let productsInCart = new Set();
    let lastSearchTerm = '';

    // ==== INIT ====
    document.addEventListener('DOMContentLoaded', function () {
        loadCartFromStorage();
        setupEventListeners();
        updateCounts();
        updateCartDisplay();
        updateSummary();

        const productList = document.getElementById('productList');
        if (productList && shoppingCart.length === 0) {
            productList.innerHTML = emptySearchHTML();
        }
    });

    function emptySearchHTML() {
        return `<div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-search"></i></div>
            <div class="empty-state-title">Search for products</div>
            <p>Type in the search box above to find ${allProducts.length} available products</p>
        </div>`;
    }

    // ==== EVENT LISTENERS ====
    function setupEventListeners() {
        const searchInput = document.getElementById('productSearch');
        if (searchInput) searchInput.addEventListener('input', handleProductSearch);

        const clearSearchBtn = document.getElementById('clearSearch');
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function () {
                document.getElementById('productSearch').value = '';
                document.getElementById('productListSection').classList.remove('active');
                document.getElementById('productList').innerHTML = emptySearchHTML();
                document.getElementById('productCount').textContent = '0';
            });
        }

        document.getElementById('clearCartBtn')?.addEventListener('click', clearCart);
        document.getElementById('submitOrderBtn')?.addEventListener('click', submitOrder);

        document.addEventListener('click', function (e) {
            const section = document.getElementById('productListSection');
            const search = document.getElementById('productSearch');
            if (section && !section.contains(e.target) && search && !search.contains(e.target)) {
                section.classList.remove('active');
            }
        });
    }

    // ==== SEARCH ====
    function handleProductSearch(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        lastSearchTerm = searchTerm;
        const section = document.getElementById('productListSection');

        if (searchTerm === '') {
            section.classList.remove('active');
            document.getElementById('productList').innerHTML = emptySearchHTML();
            document.getElementById('productCount').textContent = '0';
            return;
        }

        section.classList.add('active');

        const filtered = allProducts.filter(p =>
            p.name.toLowerCase().includes(searchTerm) && !productsInCart.has(p.id)
        );

        displayProducts(filtered);
    }

    function displayProducts(products) {
        const productList = document.getElementById('productList');
        if (!productList) return;

        if (products.length === 0) {
            productList.innerHTML = `<div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-search"></i></div>
                <div class="empty-state-title">No products found</div>
                <p>${productsInCart.size > 0 ? 'All matching products are in list or try a different term' : 'Try a different search term'}</p>
            </div>`;
            document.getElementById('productCount').textContent = '0';
            return;
        }

        productList.innerHTML = products.map(p => `
            <div class="product-item" data-product-id="${p.id}" onclick="handleProductClick('${p.id}', this)">
                <div class="product-info">
                    <div class="product-name">${p.name}</div>
                    <div class="product-prices">
                        <div><i class="bi bi-tag"></i> Cost: Tsh. ${p.cost.toLocaleString()}</div>
                        <div><i class="bi bi-box"></i> Current Stock: ${p.currentStock}</div>
                    </div>
                </div>
                <div class="add-indicator"><i class="bi bi-plus"></i></div>
            </div>
        `).join('');

        document.getElementById('productCount').textContent = products.length;
    }

    // ==== ADD TO CART ====
    function handleProductClick(productId, element) {
        const product = allProducts.find(p => p.id === productId);
        if (!product) return;

        if (product.currentStock < 1) {
            showToast('Cannot return product with zero stock!', 'error');
            return;
        }

        const existing = shoppingCart.find(i => i.productId === productId);

        if (existing) {
            if (existing.quantity < product.currentStock) {
                existing.quantity += 1;
                showToast(`Increased ${existing.name} quantity to ${existing.quantity}`, 'success');
            } else {
                showToast(`Cannot exceed available stock (${product.currentStock})`, 'error');
                return;
            }
        } else {
            shoppingCart.push({
                cartId: Date.now() + Math.random().toString(36).substr(2, 9),
                productId: product.id,
                name: product.name,
                cost: product.cost,
                wholesale: product.wholesale,
                retail: product.retail,
                quantity: 1,
                type: document.getElementById('transactionType')?.value || 'Cash',
                expiry: ''
            });
            productsInCart.add(productId);
            showToast(`${product.name} added to return list`, 'success');
        }

        saveCartToStorage();

        const searchInput = document.getElementById('productSearch');
        if (searchInput) { searchInput.value = ''; lastSearchTerm = ''; }
        setTimeout(() => { document.getElementById('productListSection').classList.remove('active'); }, 200);

        updateCartDisplay();
        updateCounts();
        updateSummary();
    }

    // ==== CART DISPLAY ====
    function updateCartDisplay() {
        const cartItems = document.getElementById('cartItems');
        if (!cartItems) return;

        if (shoppingCart.length === 0) {
            cartItems.innerHTML = `<div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-arrow-return-left"></i></div>
                <div class="empty-state-title">No products added</div>
                <p>Search and click on products to add them to return list</p>
            </div>`;
            return;
        }

        cartItems.innerHTML = `
            <table class="cart-items-table">
                <thead>
                    <tr>
                        <th style="width:25%;">Product</th>
                        <th style="width:12%; text-align:center;">Qty</th>
                        <th style="width:15%; text-align:right;">Cost</th>
                        <th style="width:15%; text-align:right;">Wholesale</th>
                        <th style="width:15%; text-align:right;">Retail</th>
                        <th style="width:6%; text-align:center;">Del</th>
                    </tr>
                </thead>
                <tbody>
                    ${shoppingCart.map(item => `
                        <tr>
                            <td class="cart-product-cell">${item.name}</td>
                            <td class="qty-cell">
                                <input type="number" value="${item.quantity}" min="1"
                                    oninput="updateQuantity('${item.cartId}', this.value); updateSummary(); saveCartToStorage()">
                            </td>
                            <td class="price-cell">
                                <input type="number" value="${item.cost}" step="0.01"
                                    oninput="updatePrice('${item.cartId}', 'cost', this.value); updateSummary(); saveCartToStorage()">
                            </td>
                            <td class="price-cell">
                                <input type="number" value="${item.wholesale}" step="0.01"
                                    oninput="updatePrice('${item.cartId}', 'wholesale', this.value); saveCartToStorage()">
                            </td>
                            <td class="price-cell">
                                <input type="number" value="${item.retail}" step="0.01"
                                    oninput="updatePrice('${item.cartId}', 'retail', this.value); saveCartToStorage()">
                            </td>
                            <td class="remove-item-table">
                                <button type="button" onclick="removeFromCart('${item.cartId}')" title="Remove">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>`;
    }

    function updateQuantity(cartId, value) {
        const item = shoppingCart.find(i => i.cartId === cartId);
        if (item) item.quantity = parseInt(value) || 1;
    }

    function updatePrice(cartId, type, value) {
        const item = shoppingCart.find(i => i.cartId === cartId);
        if (item) { item[type] = parseFloat(value) || 0; updateSummary(); }
    }

    function removeFromCart(cartId) {
        const item = shoppingCart.find(i => i.cartId === cartId);
        if (!item) return;
        shoppingCart = shoppingCart.filter(i => i.cartId !== cartId);
        productsInCart.delete(item.productId);
        saveCartToStorage();
        updateCartDisplay(); updateCounts(); updateSummary();
        if (lastSearchTerm) handleProductSearch({ target: { value: lastSearchTerm } });
        showToast(`${item.name} removed from return list`, 'success');
    }

    function clearCart() {
        if (shoppingCart.length === 0) return;
        if (!confirm('Are you sure you want to clear all products from the return list?')) return;
        productsInCart.clear();
        shoppingCart = [];
        clearCartStorage();
        updateCartDisplay(); updateCounts(); updateSummary();
        if (lastSearchTerm) handleProductSearch({ target: { value: lastSearchTerm } });
        showToast('Return list cleared', 'success');
    }

    function updateCounts() {
        const el = document.getElementById('cartCount');
        if (el) el.textContent = shoppingCart.length;
    }

    function updateSummary() {
        let total = 0, itemCount = 0;
        shoppingCart.forEach(item => {
            total += (item.cost || 0) * (item.quantity || 1);
            itemCount += item.quantity;
        });
        const fmt = n => `Tsh. ${n.toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
        const i = document.getElementById('itemCount');
        const t = document.getElementById('totalAmount');
        if (i) i.textContent = itemCount;
        if (t) t.textContent = fmt(total);
    }

    function applyTypeToAll(value) {
        shoppingCart.forEach(item => { item.type = value; });
        saveCartToStorage();
    }

    // ==== SUBMIT ORDER ====
    function submitOrder() {
        const supplier = document.getElementById('supplier').value;
        const served = document.getElementById('served').value;
        const reason = document.getElementById('reason').value;
        const receivingDate = document.getElementById('receivingDate')?.value || '';

        if (shoppingCart.length === 0) { showToast('Please add at least one product to return.', 'error'); return; }
        if (!supplier || !served) { showToast('Please select both supplier and staff.', 'error'); return; }
        if (!reason || reason.trim() === '') { showToast('Please provide a reason for the return.', 'error'); return; }

        if (!confirm('Are you sure you want to proceed with this return? This will Increase product quantities in your inventory.')) return;

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('supplier', supplier);
        formData.append('served', served);
        formData.append('reason', reason);
        if (receivingDate) formData.append('receivingDate', receivingDate);

        shoppingCart.forEach(item => {
            formData.append('product_id[]', item.productId);
            formData.append('quantity[]', item.quantity);
            formData.append('bPrice[]', item.cost);
            formData.append('wholesale[]', item.wholesale);
            formData.append('sPrice[]', item.retail);
            formData.append('transactionType[]', item.type);
            formData.append('expiry[]', item.expiry ?? '');
        });

        const submitBtn = document.getElementById('submitOrderBtn');
        const originalBtn = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Processing...';
        submitBtn.disabled = true;

        fetch('{{ route("admin.process-return") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(data => {
            productsInCart.clear();
            shoppingCart = [];
            clearCartStorage();
            updateCartDisplay(); updateCounts(); updateSummary();
            document.getElementById('orderForm').reset();
            document.getElementById('productSearch').value = '';
            lastSearchTerm = '';
            showToast('Return processed successfully! Product quantities have been updated.', 'success');
            setTimeout(() => { window.location.href = '{{ url("admin/view-returns") }}'; }, 1500);
        })
        .catch(() => showToast('An error occurred. Please try again.', 'error'))
        .finally(() => {
            submitBtn.innerHTML = originalBtn;
            submitBtn.disabled = false;
        });
    }

    // ==== TOAST ====
    function showToast(message, type = 'success') {
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `<i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'}"></i><span>${message}</span>`;
        container.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 300); }, 3000);
    }
    </script>
</body>
</html>