<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receiving Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #004E89;
            --secondary-color: #1a659e;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --text-dark: #343a40;
            --text-light: #6c757d;
            --border-radius: 8px;
            --shadow: 0 2px 8px rgba(0,0,0,0.06);
            --transition: all 0.2s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html { font-size: 16px; }
        body { min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; zoom: 1.1; }

        .main-container { max-width: 1900px; margin: 0 auto; padding: 0.75rem; }

        /* ── Header ── */
        .page-header {
            background: white; padding: 0.9rem 1.2rem; border-radius: var(--border-radius);
            margin-bottom: 0.75rem; box-shadow: var(--shadow);
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 1rem;
        }
        .page-title { font-size: 1.4rem; font-weight: 700; color: var(--primary-color); display: flex; align-items: center; gap: 0.4rem; }

        /* ── Tabs ── */
        .tab-navigation { background: white; border-radius: var(--border-radius); box-shadow: var(--shadow); margin-bottom: 0.75rem; overflow: hidden; }
        .nav-tabs { display: flex; border-bottom: 2px solid #e9ecef; padding: 0; margin: 0; }
        .nav-tab {
            flex: 1; text-align: center; padding: 0.8rem 1rem; background: #f8f9fa;
            border: none; cursor: pointer; transition: var(--transition); font-weight: 600;
            color: var(--text-dark); display: flex; align-items: center; justify-content: center;
            gap: 0.5rem; border-right: 1px solid #e9ecef;
        }
        .nav-tab:last-child { border-right: none; }
        .nav-tab:hover { background: #e9ecef; }
        .nav-tab.active { background: white; color: var(--primary-color); border-bottom: 3px solid var(--primary-color); }
        .tab-content { display: none; padding: 0; }
        .tab-content.active { display: block; }

        /* ── Tab 1 layout ── */
        .main-layout { display: grid; grid-template-columns: 70% 30%; gap: 0.75rem; height: calc(100vh - 180px); }
        @media (max-width: 1200px) { .main-layout { grid-template-columns: 1fr; height: auto; } }

        /* ── Tab 2 container ── */
        .receivings-container {
            background: white; border-radius: var(--border-radius); box-shadow: var(--shadow);
            height: calc(100vh - 180px); display: flex; flex-direction: column; overflow: hidden;
        }
        .receivings-header {
            width: 100%; padding: 1rem; border-bottom: 1px solid #e9ecef;
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 1rem; background: #f8f9fa;
        }
        .receivings-title { font-size: 1.2rem; font-weight: 600; color: var(--primary-color); display: flex; align-items: center; gap: 0.5rem; }
        .receivings-body { flex: 1; overflow-y: auto; }
        .table-container { height: 100%; overflow-y: auto; }

        /* ── Panels ── */
        .left-panel  { background: white; border-radius: var(--border-radius); box-shadow: var(--shadow); display: flex; flex-direction: column; overflow: hidden; }
        .right-panel { background: white; border-radius: var(--border-radius); box-shadow: var(--shadow); display: flex; flex-direction: column; overflow: hidden; }
        .panel-header { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 0.75rem 1rem; font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; gap: 0.4rem; }
        .panel-body { padding: 0.75rem; flex: 1; display: flex; flex-direction: column; overflow: hidden; gap: 0.75rem; }

        /* ── Search ── */
        .product-search-section { flex-shrink: 0; position: relative; }
        .search-box { position: relative; }
        .search-box input { width: 100%; padding: 0.5rem 0.75rem 0.5rem 2.2rem; border: 2px solid #e9ecef; border-radius: 6px; font-size: 0.9rem; transition: var(--transition); }
        .search-box input:focus { border-color: var(--primary-color); box-shadow: 0 0 0 2px rgba(0,78,137,0.1); outline: none; }
        .search-box > i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-light); font-size: 0.9rem; pointer-events: none; }
        .search-clear-btn { position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: var(--text-light); cursor: pointer; font-size: 0.9rem; padding: 0.2rem; display: none; }
        .search-clear-btn:hover { color: var(--danger-color); }
        .search-box:has(input:not(:placeholder-shown)) .search-clear-btn { display: block; }
        .search-hint { position: absolute; bottom: -22px; left: 0; font-size: 0.75rem; color: var(--text-light); }

        /* ── Product dropdown ── */
        .product-list-section {
            width: 100%; max-height: 400px; display: none; flex-direction: column;
            overflow: hidden; background: white; border-radius: var(--border-radius);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15); z-index: 1000; border: 1px solid #e9ecef;
        }
        .product-list-section.active { display: flex; }
        .product-list-header { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; border-bottom: 1px solid #e9ecef; background: #f8f9fa; flex-shrink: 0; }
        .product-list-title { font-weight: 600; color: var(--text-dark); font-size: 0.95rem; }
        .product-count { background: var(--primary-color); color: white; padding: 0.2rem 0.5rem; border-radius: 20px; font-size: 0.8rem; }
        .product-list { flex: 1; overflow-y: auto; background: white; }

        .product-item { padding: 0.6rem 1rem; border-bottom: 1px solid #e9ecef; cursor: pointer; transition: var(--transition); display: flex; align-items: center; gap: 0.6rem; font-size: 0.9rem; position: relative; user-select: none; }
        .product-item:hover { background: #f8f9fa; transform: translateX(2px); }
        .product-item:active { transform: translateX(0); }
        .product-item:last-child { border-bottom: none; }
        .product-info { flex: 1; min-width: 0; }
        .product-name { font-weight: 600; color: var(--text-dark); margin-bottom: 0.15rem; font-size: 0.9rem; }
        .product-prices { font-size: 0.75rem; color: var(--text-light); display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .product-price { display: flex; align-items: center; gap: 0.2rem; }
        .add-indicator { background: var(--success-color); color: white; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; flex-shrink: 0; }

        @keyframes quickAdd { 0%{transform:scale(1);} 50%{transform:scale(1.05);} 100%{transform:scale(1);} }
        .quick-add { animation: quickAdd 0.3s ease; }

        /* ── Form ── */
        .supplier-section { padding: 0.75rem; border-bottom: 1px solid #e9ecef; flex-shrink: 0; }
        .form-group-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
        @media (max-width: 768px) { .form-group-row { grid-template-columns: 1fr; } }
        .form-group { margin-bottom: 0; }
        .form-label { font-weight: 600; color: var(--text-dark); margin-bottom: 0.3rem; font-size: 0.85rem; display: flex; align-items: center; gap: 0.3rem; }
        .form-control, .form-select { border: 2px solid #e9ecef; border-radius: 6px; padding: 0.5rem 0.75rem; transition: var(--transition); font-size: 0.9rem; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 2px rgba(0,78,137,0.1); outline: none; }

        /* ── Cart ── */
        .cart-section { flex: 1; display: flex; flex-direction: column; overflow: hidden; min-height: 0; }
        .cart-header { padding: 0.75rem; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; }
        .cart-title { font-weight: 600; color: var(--text-dark); font-size: 1rem; display: flex; align-items: center; gap: 0.4rem; }
        .cart-count { background: var(--primary-color); color: white; padding: 0.2rem 0.5rem; border-radius: 20px; font-size: 0.8rem; }
        .cart-items { flex: 1; overflow-y: auto; padding: 0.75rem; min-height: 0; }

        .cart-items-table { width: 100%; border-collapse: collapse; }
        .cart-items-table thead { background: #f8f9fa; position: sticky; top: 0; z-index: 10; }
        .cart-items-table th { padding: 0.5rem; text-align: left; font-weight: 600; font-size: 0.75rem; color: var(--text-dark); border-bottom: 2px solid #e9ecef; }
        .cart-items-table td { padding: 0.4rem; font-size: 0.8rem; border-bottom: 1px solid #e9ecef; vertical-align: middle; }
        .cart-items-table tbody tr:hover { background: #f8f9fa; }
        .cart-product-cell { font-weight: 600; color: var(--text-dark); max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .price-cell { text-align: right; }
        .qty-cell { text-align: center; width: 55px; }
        .qty-cell input, .price-cell input { width: 100%; padding: 0.3rem; text-align: center; border: 1px solid #e9ecef; border-radius: 3px; font-size: 0.75rem; }
        .qty-cell input:focus, .price-cell input:focus { border-color: var(--primary-color); outline: none; }
        .remove-item-table { text-align: center; width: 30px; }
        .remove-item-table button { background: transparent; border: none; color: var(--danger-color); cursor: pointer; padding: 0; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; border-radius: 3px; }
        .remove-item-table button:hover { background: rgba(220,53,69,0.1); }

        /* ── Summary ── */
        .summary-section { padding: 0.75rem; border-top: 1px solid #e9ecef; background: #f8fafc; flex-shrink: 0; }
        .summary-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem; font-size: 0.9rem; }
        .summary-label { font-weight: 600; color: var(--text-dark); }
        .summary-value { font-weight: 700; font-size: 0.95rem; color: var(--primary-color); }
        .total-summary { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 0.8rem; border-radius: 6px; margin-top: 0.5rem; }
        .total-label { font-size: 0.9rem; font-weight: 600; margin-bottom: 0.3rem; }
        .total-amount { font-size: 1.4rem; font-weight: 800; text-align: right; }

        /* ── Action buttons ── */
        .action-buttons { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; margin-top: 0.75rem; }
        .btn-primary-action { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border: none; color: white; padding: 0.6rem; border-radius: 6px; font-weight: 700; cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: 0.3rem; font-size: 0.9rem; }
        .btn-primary-action:hover { transform: translateY(-1px); box-shadow: 0 4px 8px rgba(0,78,137,0.2); }
        .btn-primary-action:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .btn-secondary-action { background: #f8f9fa; border: 2px solid #e9ecef; color: var(--text-dark); padding: 0.6rem; border-radius: 6px; font-weight: 600; cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: 0.3rem; font-size: 0.9rem; }
        .btn-secondary-action:hover { background: #e9ecef; }

        /* ── Empty state ── */
        .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem 1rem; text-align: center; color: var(--text-light); }
        .empty-state-icon { font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5; }
        .empty-state-title { font-size: 1rem; font-weight: 600; margin-bottom: 0.3rem; }
        .empty-state p { font-size: 0.85rem; margin: 0; }

        /* ── Alerts ── */
        .alert { border: none; border-radius: var(--border-radius); margin-bottom: 0.75rem; padding: 0.75rem 1rem; font-size: 0.9rem; }
        .alert-success { background: rgba(40,167,69,0.1); border-left: 4px solid var(--success-color); color: #155724; }
        .alert-danger  { background: rgba(220,53,69,0.1);  border-left: 4px solid var(--danger-color);  color: #721c24; }

        /* ── Table ── */
        .table { margin-bottom: 0; font-size: 0.85rem; }
        .table thead th { padding: 0.5rem; font-weight: 600; border-bottom: 2px solid #e9ecef; background: #f8f9fa; position: sticky; top: 0; z-index: 10; }
        .table tbody td { padding: 0.5rem; border-bottom: 1px solid #e9ecef; vertical-align: middle; }
        .table tbody tr:hover { background: #f8f9fa; }
        .badge { font-size: 0.75rem; padding: 0.3rem 0.5rem; }

        /* ── Toast ── */
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 99999; }
        .toast { background: white; border-radius: var(--border-radius); box-shadow: 0 4px 12px rgba(0,0,0,0.15); padding: 0.75rem 1rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; min-width: 300px; opacity: 0; transform: translateX(100%); transition: all 0.3s ease; }
        .toast.show { opacity: 1; transform: translateX(0); }
        .toast-success { border-left: 4px solid var(--success-color); }
        .toast-error   { border-left: 4px solid var(--danger-color); }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #d0d0d0; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
</head>
<body>
    <div class="row">
        @include('user/sidenav')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="main-container">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-title">
                        <i class="bi bi-box-seam"></i> Receiving Management
                    </div>
                </div>

                <!-- Flash Alerts -->
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

                <!-- Tab Navigation -->
                <div class="tab-navigation">
                    <div class="nav-tabs">
                        <button class="nav-tab active" data-tab="add-receiving">
                            <i class="bi bi-plus-circle"></i> Add Receiving
                        </button>
                        <button class="nav-tab" data-tab="view-receivings">
                            <i class="bi bi-list-check"></i> View Receivings
                        </button>
                        <button class="nav-tab" data-tab="summary-receivings">
                            <i class="bi bi-bar-chart"></i> Summary
                        </button>
                    </div>
                </div>

                <!-- ═══ TAB 1: ADD RECEIVING ═══ -->
                <div class="tab-content active" id="add-receiving">
                    <div class="main-layout">

                        <!-- Left Panel -->
                        <div class="left-panel">
                            <div class="panel-header">
                                <i class="bi bi-search"></i> Select Products
                            </div>
                            <div class="panel-body">

                                <div class="product-search-section">
                                    <div class="search-box">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="productSearch" placeholder="Search products by name...">
                                        <button class="search-clear-btn" id="clearSearch" title="Clear search">
                                            <i class="bi bi-x"></i>
                                        </button>
                                        <span class="search-hint">Click a product to add it to the cart</span>
                                    </div>

                                    <div class="product-list-section" id="productListSection">
                                        <div class="product-list-header">
                                            <span class="product-list-title">Available Products</span>
                                            <span class="product-count" id="productCount">0</span>
                                        </div>
                                        <div class="product-list" id="productList"></div>
                                    </div>
                                </div>

                                <div class="cart-section">
                                    <div class="cart-header">
                                        <div class="cart-title">
                                            <i class="bi bi-cart"></i> Selected Products
                                        </div>
                                        <span class="cart-count" id="cartCount">0</span>
                                    </div>
                                    <div class="cart-items" id="cartItems">
                                        <div class="empty-state">
                                            <div class="empty-state-icon"><i class="bi bi-cart"></i></div>
                                            <div class="empty-state-title">No products added</div>
                                            <p>Search and click on products to add them</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Right Panel -->
                        <div class="right-panel">
                            <div class="panel-header">
                                <i class="bi bi-cart-plus"></i> Order Details
                            </div>

                            <div class="supplier-section">
                                <form id="orderForm">
                                    <div class="form-group-row">
                                        <div class="form-group">
                                            <label for="supplier" class="form-label">
                                                <i class="bi bi-shop"></i> Supplier
                                            </label>
                                            <select id="supplier" class="form-select" required>
                                                <option value="" disabled selected>Select Supplier</option>
                                                @foreach (DB::table('vendors')->where('account', getSessionAccountName())->get() as $vendor)
                                                    <option value="{{ $vendor->name }}">{{ $vendor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="served" class="form-label">
                                                <i class="bi bi-person"></i> Allocation
                                            </label>
                                            <select id="served" class="form-select" required>
                                                <option value="" disabled selected>Select Staff</option>
                                                @foreach (DB::table('users')->where('account', getSessionAccountName())->get() as $user)
                                                    <option value="{{ $user->name }}">{{ $user->name }} ({{ $user->levelStatus }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top:0.6rem;">
                                        <label for="operationType" class="form-label">
                                            <i class="bi bi-arrow-left-right"></i> Operation Type
                                        </label>
                                        <select id="operationType" class="form-select" onchange="applyOperationType(this.value)">
                                            <option value="Receiving" selected>Receiving</option>
                                            <option value="Return">Return</option>
                                        </select>
                                    </div>

                                    <div class="form-group" style="margin-top:0.6rem;" id="paymentTypeWrap">
                                        <label for="transactionType" class="form-label">
                                            <i class="bi bi-credit-card"></i> Payment Type
                                        </label>
                                        <select id="transactionType" class="form-select" onchange="applyTypeToAll(this.value)">
                                            <option value="Credit">Credit</option>
                                            <option value="Cash">Cash</option>
                                        </select>
                                    </div>

                                    @if(canUser('set_restock_date'))
                                    <div class="form-group" style="margin-top:0.6rem;">
                                        <label for="receivingDate" class="form-label">
                                            <i class="bi bi-calendar"></i> Receiving Date (Optional)
                                        </label>
                                        <input type="date" id="receivingDate" class="form-control" max="{{ date('Y-m-d') }}">
                                        <small class="text-muted">Leave blank to use current date</small>
                                    </div>
                                    @endif
                                </form>
                            </div>

                            <div class="summary-section">
                                <div class="summary-item">
                                    <span class="summary-label">Subtotal</span>
                                    <span class="summary-value" id="subtotal">Tsh. 0.00</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Items</span>
                                    <span class="summary-value" id="itemCount">0</span>
                                </div>
                                <div class="total-summary">
                                    <div class="total-label">TOTAL COST</div>
                                    <div class="total-amount" id="totalAmount">Tsh. 0.00</div>
                                </div>
                                <div class="action-buttons">
                                    <button type="button" class="btn-secondary-action" id="clearCartBtn">
                                        <i class="bi bi-x-circle me-1"></i> Clear
                                    </button>
                                    <button type="button" class="btn-primary-action" id="submitOrderBtn">
                                        <i class="bi bi-check-circle me-1"></i> Save
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ═══ TAB 2: VIEW RECEIVINGS ═══ -->
                <div class="tab-content" id="view-receivings">
                    <div class="receivings-container">
                        <div class="receivings-header">
                            <div class="receivings-title">
                                <i class="bi bi-receipt"></i> Receivings List
                            </div>
                            <div class="d-flex gap-2 align-items-center flex-wrap">
                                <button type="button" class="btn btn-success btn-sm" id="approveAllBtn" style="display:none;">
                                    <i class="bi bi-check-circle me-1"></i> Approve All
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" id="approveAllAllDatesBtn" onclick="approveAllAllDates()">
                                    <i class="bi bi-check2-all me-1"></i> Approve All (All Dates)
                                </button>
                                <form method="GET" action="{{ url('user/restock') }}" class="d-flex align-items-center gap-2">
                                    <label for="date" class="form-label mb-0 fw-semibold">Date:</label>
                                    <input type="date" name="date" onchange="this.form.submit()"
                                           class="form-control" id="date"
                                           value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}">
                                </form>
                            </div>
                        </div>

                        <div class="receivings-body">
                            <div class="table-container">
                                <table class="table table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th style="width:3%;"><input type="checkbox" id="selectAll" title="Select All"></th>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Cost</th>
                                            <th>Total</th>
                                            <th>Payment</th>
                                            <th>Condition</th>
                                            <th>Supplier</th>
                                            <th>Allocated</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $index => $item)
                                        <tr id="row-{{ $item->productId }}">
                                            <td>
                                                @if ($item->status != 'Returned' && $item->status != 'Approved')
                                                    <input type="checkbox" class="row-checkbox" data-product-id="{{ $item->productId }}">
                                                @endif
                                            </td>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ date('M d, Y', strtotime($item->created_at)) }}</td>
                                            <td><strong>{{ $item->productName }}</strong></td>
                                            <td>{{ number_format($item->quantity) }}</td>
                                            <td>Tsh. {{ number_format($item->price, 2) }}</td>
                                            <td><strong>Tsh. {{ number_format($item->price * $item->quantity, 2) }}</strong></td>
                                            <td>
                                                @if ($item->isPaid == 1)
                                                    <span class="badge bg-success">Cash</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Credit</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->status == 'Approved')
                                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approved</span>
                                                @elseif($item->status == 'Returned')
                                                    <span class="badge bg-danger"><i class="bi bi-arrow-return-left me-1"></i>Returned</span>
                                                @else
                                                    <span class="badge bg-secondary"><i class="bi bi-hourglass me-1"></i>Pending</span>
                                                @endif
                                            </td>
                                            <td><i class="bi bi-person"></i> {{ $item->supplier ?? 'Unknown' }}</td>
                                            <td><i class="bi bi-person-check"></i> {{ $item->served_by ?? 'Unknown' }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <form method="post">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $item->productId }}">

                                                        {{-- Approve: only when Pending --}}
                                                        @if ($item->status != 'Returned' && $item->status != 'Approved')
                                                            <button formaction="restockProd"
                                                                    class="btn btn-sm btn-success approve-btn"
                                                                    title="Approve">
                                                                <i class="bi bi-check"></i>
                                                            </button>
                                                        @endif

                                                        {{-- Return: only when Approved --}}
                                                        @if ($item->status == 'Approved')
                                                            <button type="button"
                                                                    class="btn btn-sm btn-warning return-btn"
                                                                    data-id="{{ $item->productId }}"
                                                                    data-name="{{ $item->productName }}"
                                                                    data-quantity="{{ $item->quantity }}"
                                                                    title="Return">
                                                                <i class="bi bi-arrow-return-left"></i>
                                                            </button>
                                                        @endif
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══ TAB 3: SUMMARY ═══ -->
                <div class="tab-content" id="summary-receivings">
                    @php
                        $totalRows = $products->count();
                        $totalQty = $products->sum('quantity');
                        $totalValue = $products->sum(function($p){ return ($p->quantity ?? 0) * ($p->price ?? 0); });
                        $approvedRows = $products->where('status', 'Approved')->count();
                        $approvedQty = $products->where('status', 'Approved')->sum('quantity');
                        $approvedValue = $products->where('status', 'Approved')->sum(function($p){ return ($p->quantity ?? 0) * ($p->price ?? 0); });
                        $pendingRows = $products->whereNotIn('status', ['Approved', 'Returned'])->count();
                        $pendingQty = $products->whereNotIn('status', ['Approved', 'Returned'])->sum('quantity');
                        $pendingValue = $products->whereNotIn('status', ['Approved', 'Returned'])->sum(function($p){ return ($p->quantity ?? 0) * ($p->price ?? 0); });
                        $returnedRows = $products->where('status', 'Returned')->count();
                        $returnedQty = $products->where('status', 'Returned')->sum('quantity');
                        $returnedValue = $products->where('status', 'Returned')->sum(function($p){ return ($p->quantity ?? 0) * ($p->price ?? 0); });
                    @endphp
                    <div class="receivings-container">
                        <div class="receivings-header">
                            <div class="receivings-title">
                                <i class="bi bi-bar-chart-line"></i> Receiving Summary
                            </div>
                            <form method="GET" action="{{ url('user/restock') }}" class="d-flex align-items-center gap-2">
                                <input type="hidden" name="tab" value="summary-receivings">
                                <label for="summary_date" class="form-label mb-0">Date:</label>
                                <input type="date" id="summary_date" name="date" class="form-control" value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}">
                                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                            </form>
                        </div>

                        <div class="receivings-body p-3">
                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body">
                                            <div class="text-muted small">All Receivings</div>
                                            <div class="fw-bold fs-5">{{ number_format($totalRows) }}</div>
                                            <div class="small">Qty: {{ number_format($totalQty) }}</div>
                                            <div class="small">Value: Tsh {{ number_format($totalValue, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body">
                                            <div class="text-success small">Approved</div>
                                            <div class="fw-bold fs-5 text-success">{{ number_format($approvedRows) }}</div>
                                            <div class="small">Qty: {{ number_format($approvedQty) }}</div>
                                            <div class="small">Value: Tsh {{ number_format($approvedValue, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body">
                                            <div class="text-warning small">Pending</div>
                                            <div class="fw-bold fs-5 text-warning">{{ number_format($pendingRows) }}</div>
                                            <div class="small">Qty: {{ number_format($pendingQty) }}</div>
                                            <div class="small">Value: Tsh {{ number_format($pendingValue, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body">
                                            <div class="text-danger small">Returned</div>
                                            <div class="fw-bold fs-5 text-danger">{{ number_format($returnedRows) }}</div>
                                            <div class="small">Qty: {{ number_format($returnedQty) }}</div>
                                            <div class="small">Value: Tsh {{ number_format($returnedValue, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mb-3">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Rows</th>
                                            <th>Quantity</th>
                                            <th>Total Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Approved</td>
                                            <td>{{ number_format($approvedRows) }}</td>
                                            <td>{{ number_format($approvedQty) }}</td>
                                            <td>Tsh {{ number_format($approvedValue, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Pending</td>
                                            <td>{{ number_format($pendingRows) }}</td>
                                            <td>{{ number_format($pendingQty) }}</td>
                                            <td>Tsh {{ number_format($pendingValue, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Returned</td>
                                            <td>{{ number_format($returnedRows) }}</td>
                                            <td>{{ number_format($returnedQty) }}</td>
                                            <td>Tsh {{ number_format($returnedValue, 2) }}</td>
                                        </tr>
                                        <tr class="fw-bold">
                                            <td>Total</td>
                                            <td>{{ number_format($totalRows) }}</td>
                                            <td>{{ number_format($totalQty) }}</td>
                                            <td>Tsh {{ number_format($totalValue, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Status</th>
                                            <th>Qty</th>
                                            <th>Cost</th>
                                            <th>Total</th>
                                            <th>Supplier</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($products as $idx => $item)
                                            <tr>
                                                <td>{{ $idx + 1 }}</td>
                                                <td>{{ $item->productName ?? 'Unknown' }}</td>
                                                <td>{{ $item->status ?? 'Pending' }}</td>
                                                <td>{{ number_format($item->quantity ?? 0) }}</td>
                                                <td>Tsh {{ number_format($item->price ?? 0, 2) }}</td>
                                                <td>Tsh {{ number_format(($item->quantity ?? 0) * ($item->price ?? 0), 2) }}</td>
                                                <td>{{ $item->supplier ?? '-' }}</td>
                                                <td>{{ date('M d, Y H:i', strtotime($item->created_at)) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">No receiving data for selected date.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- ═══ RETURN MODAL ═══ -->
    <div id="returnModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; padding:1rem;">
        <div style="background:white; border-radius:8px; width:100%; max-width:500px; max-height:90vh; overflow-y:auto; position:relative;">
            <div style="background:linear-gradient(135deg,#dc3545,#c82333); color:white; padding:1rem; border-radius:8px 8px 0 0; display:flex; justify-content:space-between; align-items:center;">
                <h5 style="margin:0; font-size:1.1rem; font-weight:600;">
                    <i class="bi bi-arrow-return-left"></i> Return Product
                </h5>
                <button type="button" id="closeReturnModal" style="background:rgba(255,255,255,0.2); border:none; color:white; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:1rem; padding:0;">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <form action="dltrestock" method="post" id="returnForm">
                @csrf
                <div style="padding:1.5rem;">
                    <input type="hidden" name="product_id" id="returnProductId">
                    <div class="mb-3">
                        <label style="font-weight:600; font-size:0.85rem;">Product</label>
                        <input type="text" class="form-control" id="returnProductName" readonly style="border:2px solid #e9ecef; background:#f8f9fa;">
                    </div>
                    <div class="mb-3">
                        <label style="font-weight:600; font-size:0.85rem;">
                            <i class="bi bi-box"></i> Quantity to Return (Max: <span id="maxQuantity">0</span>)
                        </label>
                        <input type="number" name="quantity" id="returnQuantity" class="form-control"
                               placeholder="Enter quantity to return" min="1" required style="border:2px solid #e9ecef;">
                    </div>
                    <div class="mb-3">
                        <label style="font-weight:600; font-size:0.85rem;">
                            <i class="bi bi-chat-text"></i> Reason for Return
                        </label>
                        <textarea name="reason" id="returnReason" class="form-control" rows="4" required
                                  style="border:2px solid #e9ecef;"
                                  placeholder="Please specify the reason for returning this product..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label style="font-weight:600; font-size:0.85rem;">
                            <i class="bi bi-sliders"></i> Return Mode
                        </label>
                        <select name="return_mode" id="returnMode" class="form-select" style="border:2px solid #e9ecef;">
                            <option value="auto" selected>Auto (Approved = stock, Pending = receiving only)</option>
                            <option value="receiving_only">Receiving only</option>
                            <option value="stock_and_receiving">Stock and receiving</option>
                        </select>
                    </div>
                </div>
                <div style="padding:1rem; border-top:1px solid #e9ecef; background:#f8f9fa; display:grid; grid-template-columns:1fr 1fr; gap:0.6rem;">
                    <button type="button" id="cancelReturn" style="background:#f8f9fa; border:2px solid #e9ecef; color:#343a40; padding:0.6rem; border-radius:6px; font-weight:600; cursor:pointer;">
                        Cancel
                    </button>
                    <button type="submit" style="background:#dc3545; border:none; color:white; padding:0.6rem; border-radius:6px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:0.3rem;">
                        <i class="bi bi-arrow-return-left"></i> Submit Return
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
    // ════════════════════════════════════════════
    //  STATE & STORAGE
    // ════════════════════════════════════════════
    const STORAGE_KEY    = 'shoppingCart';
    const CART_ITEMS_KEY = 'productsInCart';

    let shoppingCart   = [];
    let productsInCart = new Set();
    let lastSearchTerm = '';

    function saveCartToStorage() {
        try {
            localStorage.setItem(STORAGE_KEY,    JSON.stringify(shoppingCart));
            localStorage.setItem(CART_ITEMS_KEY, JSON.stringify([...productsInCart]));
        } catch (e) { console.warn('localStorage save error:', e); }
    }

    function loadCartFromStorage() {
        try {
            const cart   = localStorage.getItem(STORAGE_KEY);
            if (cart)    shoppingCart   = JSON.parse(cart);
            const inCart = localStorage.getItem(CART_ITEMS_KEY);
            if (inCart)  productsInCart = new Set(JSON.parse(inCart));
        } catch (e) { shoppingCart = []; productsInCart = new Set(); }
    }

    function clearCartStorage() {
        localStorage.removeItem(STORAGE_KEY);
        localStorage.removeItem(CART_ITEMS_KEY);
    }

    // ════════════════════════════════════════════
    //  TAB PERSISTENCE
    // ════════════════════════════════════════════
    function saveActiveTab(id) { localStorage.setItem('activeTab', id); }

    function restoreActiveTab() {
        const urlTab = new URLSearchParams(window.location.search).get('tab');
        const id  = urlTab || localStorage.getItem('activeTab');
        const btn = id ? document.querySelector(`.nav-tab[data-tab="${id}"]`) : null;
        if (!btn) return;
        document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.getElementById(id)?.classList.add('active');
    }

    // ════════════════════════════════════════════
    //  PRODUCT DATA
    // ════════════════════════════════════════════
    const allProducts = [
        @if(DB::table('products')->where('name01','!=','')->where('account',getSessionAccountName())->count() > 0)
            @foreach (DB::table('products')->where('name01','!=','')->where('account',getSessionAccountName())->get() as $product)
            {
                id:           "{{ $product->product_id }}",
                name:         "{{ addslashes($product->name01) }}",
                cost:         {{ (float)($product->bPrice ?? 0) }},
                wholesale:    {{ (float)($product->wholesale ?? 0) }},
                retail:       {{ (float)($product->sPrice ?? 0) }},
                currentStock: {{ (int)($product->quantity ?? 0) }}
            },
            @endforeach
        @else
            { id:'1', name:'Sugar 1kg',      cost:2500,  wholesale:2800,  retail:3000,  currentStock:100 },
            { id:'2', name:'Rice 5kg',        cost:15000, wholesale:17000, retail:18000, currentStock:50  },
            { id:'3', name:'Cooking Oil 3L',  cost:12000, wholesale:13500, retail:15000, currentStock:75  },
        @endif
    ];

    // ════════════════════════════════════════════
    //  INIT
    // ════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', function () {
        loadCartFromStorage();
        bindEvents();
        updateCounts();
        updateCartDisplay();
        updateSummary();
        restoreActiveTab();
        applyOperationType(document.getElementById('operationType')?.value || 'Receiving');
        const pl = document.getElementById('productList');
        if (pl) pl.innerHTML = emptySearchHTML('Type above to search ' + allProducts.length + ' products');
    });

    // ════════════════════════════════════════════
    //  HELPERS
    // ════════════════════════════════════════════
    function fmt(n) {
        return 'Tsh. ' + Number(n).toLocaleString(undefined, { minimumFractionDigits: 2 });
    }

    function emptySearchHTML(msg) {
        return `<div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-search"></i></div>
            <div class="empty-state-title">No results</div>
            <p>${msg}</p>
        </div>`;
    }

    // ════════════════════════════════════════════
    //  PRICE VALIDATION
    //  ✅ A price is valid if it is a finite number (0 is allowed).
    //     Only null / undefined / empty string / NaN are invalid.
    // ════════════════════════════════════════════
    function isValidPrice(val) {
        return val !== null && val !== undefined && val !== '' && !isNaN(Number(val));
    }

    // ════════════════════════════════════════════
    //  EVENT BINDING
    // ════════════════════════════════════════════
    function bindEvents() {
        document.querySelectorAll('.nav-tab').forEach(t => t.addEventListener('click', onTabClick));
        document.getElementById('productSearch')?.addEventListener('input', onSearch);
        document.getElementById('clearSearch')?.addEventListener('click', function () {
            document.getElementById('productSearch').value = '';
            lastSearchTerm = '';
            hideProductDropdown();
        });
        document.getElementById('clearCartBtn')?.addEventListener('click', clearCart);
        document.getElementById('submitOrderBtn')?.addEventListener('click', submitOrder);

        // Return modal open (delegation)
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.return-btn');
            if (btn) {
                e.preventDefault();
                openReturnModal(btn.dataset.id, btn.dataset.name, btn.dataset.quantity);
            }
        });

        // Return modal close
        document.getElementById('closeReturnModal')?.addEventListener('click', closeReturnModal);
        document.getElementById('cancelReturn')?.addEventListener('click',     closeReturnModal);
        document.getElementById('returnModal')?.addEventListener('click', function (e) {
            if (e.target === this) closeReturnModal();
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeReturnModal(); });

        // Close product dropdown on outside click
        document.addEventListener('click', function (e) {
            const section = document.getElementById('productListSection');
            const input   = document.getElementById('productSearch');
            if (section && !section.contains(e.target) && input && !input.contains(e.target)) {
                hideProductDropdown();
            }
        });

        // Select-all checkbox
        document.getElementById('selectAll')?.addEventListener('change', function () {
            document.querySelectorAll('.row-checkbox:not([disabled])').forEach(cb => cb.checked = this.checked);
            updateApproveAllBtn();
        });

        // Individual checkboxes (delegation)
        document.addEventListener('change', function (e) {
            if (!e.target.classList.contains('row-checkbox')) return;
            updateApproveAllBtn();
            syncSelectAll();
        });

        document.getElementById('approveAllBtn')?.addEventListener('click', approveSelected);
    }

    // ════════════════════════════════════════════
    //  TABS
    // ════════════════════════════════════════════
    function onTabClick(e) {
        const id = e.currentTarget.dataset.tab;
        saveActiveTab(id);
        document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
        e.currentTarget.classList.add('active');
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.getElementById(id)?.classList.add('active');
        hideProductDropdown();
    }

    function switchToViewTab() {
        document.querySelectorAll('.nav-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === 'view-receivings'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.toggle('active', c.id === 'view-receivings'));
        saveActiveTab('view-receivings');
    }

    // ════════════════════════════════════════════
    //  PRODUCT SEARCH & DROPDOWN
    // ════════════════════════════════════════════
    function onSearch(e) {
        const term = e.target.value.toLowerCase().trim();
        lastSearchTerm = term;
        if (!term) { hideProductDropdown(); return; }
        showProductDropdown();
        const filtered = allProducts.filter(p => p.name.toLowerCase().includes(term) && !productsInCart.has(p.id));
        renderProductList(filtered);
    }

    function showProductDropdown() { document.getElementById('productListSection')?.classList.add('active'); }

    function hideProductDropdown() {
        document.getElementById('productListSection')?.classList.remove('active');
        const pl = document.getElementById('productList');
        if (pl) pl.innerHTML = emptySearchHTML('Type above to search ' + allProducts.length + ' products');
        const pc = document.getElementById('productCount');
        if (pc) pc.textContent = '0';
    }

    function renderProductList(products) {
        const list = document.getElementById('productList');
        if (!list) return;
        if (!products.length) {
            list.innerHTML = emptySearchHTML(
                productsInCart.size > 0
                    ? 'All matching products are already in the cart'
                    : 'No products match your search'
            );
            document.getElementById('productCount').textContent = '0';
            return;
        }
        list.innerHTML = products.map(p => `
            <div class="product-item" onclick="addToCart('${p.id}', this)">
                <div class="product-info">
                    <div class="product-name">${p.name}</div>
                    <div class="product-prices">
                        <span class="product-price"><i class="bi bi-tag"></i> Cost: ${fmt(p.cost)}</span>
                        <span class="product-price"><i class="bi bi-box"></i> Stock: ${p.currentStock}</span>
                    </div>
                </div>
                <div class="add-indicator"><i class="bi bi-plus"></i></div>
            </div>`).join('');
        document.getElementById('productCount').textContent = products.length;
    }

    // ════════════════════════════════════════════
    //  CART OPERATIONS
    // ════════════════════════════════════════════
    function addToCart(productId, element) {
        const product = allProducts.find(p => p.id === productId);
        if (!product) return;

        element.classList.add('quick-add');
        setTimeout(() => element.classList.remove('quick-add'), 300);

        const existing = shoppingCart.find(i => i.productId === productId);
        if (existing) {
            existing.quantity++;
            showToast(`${product.name} — qty updated to ${existing.quantity}`, 'success');
        } else {
            shoppingCart.push({
                cartId:    Date.now() + Math.random().toString(36).slice(2),
                productId: product.id,
                name:      product.name,
                cost:      product.cost,       // can be 0
                wholesale: product.wholesale,   // can be 0
                retail:    product.retail,      // can be 0
                quantity:  1,
                type:      document.getElementById('transactionType')?.value || 'Cash',
                expiry:    ''
            });
            productsInCart.add(productId);
            showToast(`${product.name} added to cart`, 'success');
        }

        saveCartToStorage();
        const input = document.getElementById('productSearch');
        if (input) input.value = '';
        lastSearchTerm = '';
        setTimeout(hideProductDropdown, 150);
        updateCartDisplay(); updateCounts(); updateSummary();
    }

    function removeFromCart(cartId) {
        const idx = shoppingCart.findIndex(i => i.cartId === cartId);
        if (idx === -1) return;
        const item = shoppingCart.splice(idx, 1)[0];
        productsInCart.delete(item.productId);
        saveCartToStorage();
        updateCartDisplay(); updateCounts(); updateSummary();
        if (lastSearchTerm) onSearch({ target: { value: lastSearchTerm } });
        showToast(`${item.name} removed`, 'success');
    }

    function clearCart() {
        if (!shoppingCart.length) return;
        if (!confirm('Clear all items from the cart?')) return;
        shoppingCart = []; productsInCart.clear(); clearCartStorage();
        updateCartDisplay(); updateCounts(); updateSummary();
        if (lastSearchTerm) onSearch({ target: { value: lastSearchTerm } });
        showToast('Cart cleared', 'success');
    }

    function updateQuantity(cartId, val) {
        const item = shoppingCart.find(i => i.cartId === cartId);
        if (item) item.quantity = Math.max(1, parseInt(val) || 1);
    }

    function updatePrice(cartId, field, val) {
        const item = shoppingCart.find(i => i.cartId === cartId);
        if (item) {
            // ✅ Allow 0 — only fall back to 0 when the field is truly empty/NaN
            item[field] = (val === '' || val === null || val === undefined) ? 0 : parseFloat(val);
            updateSummary();
        }
    }

    function applyTypeToAll(val) {
        shoppingCart.forEach(i => i.type = val);
        saveCartToStorage();
    }

    function applyOperationType(value) {
        const paymentWrap = document.getElementById('paymentTypeWrap');
        if (paymentWrap) {
            paymentWrap.style.display = value === 'Return' ? 'none' : 'block';
        }
    }

    // ════════════════════════════════════════════
    //  CART DISPLAY
    // ════════════════════════════════════════════
    function updateCartDisplay() {
        const container = document.getElementById('cartItems');
        if (!container) return;
        if (!shoppingCart.length) {
            container.innerHTML = `<div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-cart"></i></div>
                <div class="empty-state-title">No products added</div>
                <p>Search and click on products to add them</p>
            </div>`;
            return;
        }
        container.innerHTML = `
            <table class="cart-items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="text-align:center;">Qty</th>
                        <th style="text-align:right;">Cost</th>
                        <th style="text-align:right;">Wholesale</th>
                        <th style="text-align:right;">Retail</th>
                        <th style="text-align:center;">Del</th>
                    </tr>
                </thead>
                <tbody>
                    ${shoppingCart.map(item => `
                        <tr>
                            <td class="cart-product-cell" title="${item.name}">${item.name}</td>
                            <td class="qty-cell">
                                <input type="number" value="${item.quantity}" min="1"
                                       oninput="updateQuantity('${item.cartId}',this.value);updateSummary();saveCartToStorage()">
                            </td>
                            <td class="price-cell">
                                <input type="number" value="${item.cost}" step="0.01" min="0"
                                       oninput="updatePrice('${item.cartId}','cost',this.value);saveCartToStorage()">
                            </td>
                            <td class="price-cell">
                                <input type="number" value="${item.wholesale}" step="0.01" min="0"
                                       oninput="updatePrice('${item.cartId}','wholesale',this.value);saveCartToStorage()">
                            </td>
                            <td class="price-cell">
                                <input type="number" value="${item.retail}" step="0.01" min="0"
                                       oninput="updatePrice('${item.cartId}','retail',this.value);saveCartToStorage()">
                            </td>
                            <td class="remove-item-table">
                                <button type="button" onclick="removeFromCart('${item.cartId}')" title="Remove">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>`).join('')}
                </tbody>
            </table>`;
    }

    function updateCounts() {
        const el = document.getElementById('cartCount');
        if (el) el.textContent = shoppingCart.length;
    }

    function updateSummary() {
        let subtotal = 0, items = 0;
        shoppingCart.forEach(i => { subtotal += (i.cost || 0) * (i.quantity || 1); items += i.quantity; });
        const s = document.getElementById('subtotal');
        const c = document.getElementById('itemCount');
        const t = document.getElementById('totalAmount');
        if (s) s.textContent = fmt(subtotal);
        if (c) c.textContent = items;
        if (t) t.textContent = fmt(subtotal);
    }

    // ════════════════════════════════════════════
    //  SUBMIT ORDER
    // ════════════════════════════════════════════
    function submitOrder() {
        const supplier      = document.getElementById('supplier')?.value || '';
        const served        = document.getElementById('served')?.value   || '';
        const receivingDate = document.getElementById('receivingDate')?.value || '';
        const operationType = document.getElementById('operationType')?.value || 'Receiving';

        if (!shoppingCart.length)  { showToast('Add at least one product first.', 'error'); return; }
        if (!supplier || !served)  { showToast('Please select a supplier and staff member.', 'error'); return; }

        // ✅ Price validation: 0 is valid. Only reject null/undefined/empty string/NaN.
        const invalid = shoppingCart.filter(i =>
            !isValidPrice(i.cost) || !isValidPrice(i.wholesale) || !isValidPrice(i.retail)
        );
        if (invalid.length) {
            showToast(`Missing prices for: ${invalid.map(i => i.name).join(', ')}`, 'error');
            return;
        }

        const formData = new FormData();
        formData.append('_token',   '{{ csrf_token() }}');
        formData.append('supplier', supplier);
        formData.append('served',   served);
        formData.append('operationType', operationType);
        if (receivingDate) formData.append('receivingDate', receivingDate);

        shoppingCart.forEach(item => {
            formData.append('product_id[]',     item.productId);
            formData.append('quantity[]',        item.quantity);
            formData.append('bPrice[]',          item.cost);       // 0 is sent as "0"
            formData.append('wholesale[]',       item.wholesale);
            formData.append('sPrice[]',          item.retail);
            formData.append('transactionType[]', item.type);
            formData.append('expiry[]',          item.expiry || '');
        });

        const btn      = document.getElementById('submitOrderBtn');
        const origHTML = btn.innerHTML;
        btn.innerHTML  = '<i class="bi bi-hourglass-split me-1"></i> Saving...';
        btn.disabled   = true;

        fetch('restock', {
            method:  'POST',
            body:    formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => {
            const isJson = res.headers.get('content-type')?.includes('application/json');
            return isJson
                ? res.json().then(d => ({ ok: res.ok, data: d, isJson: true  }))
                : res.text().then(d => ({ ok: res.ok, data: d, isJson: false }));
        })
        .then(result => {
            const success = result.isJson
                ? result.ok && result.data?.success === true
                : result.ok && /success/i.test(result.data);

            if (success) {
                shoppingCart = []; productsInCart.clear(); clearCartStorage();
                updateCartDisplay(); updateCounts(); updateSummary();
                document.getElementById('orderForm')?.reset();
                document.getElementById('productSearch').value = '';
                lastSearchTerm = '';
                showToast(operationType === 'Return' ? 'Return saved successfully!' : 'Receiving saved successfully!', 'success');
                setTimeout(() => { switchToViewTab(); location.reload(); }, 1000);
            } else {
                const msg = result.isJson
                    ? (result.data?.message || 'Error saving receiving.')
                    : 'Error saving receiving. Please try again.';
                showToast(msg, 'error');
            }
        })
        .catch(() => showToast('Network error. Please try again.', 'error'))
        .finally(() => { btn.innerHTML = origHTML; btn.disabled = false; });
    }

    // ════════════════════════════════════════════
    //  RETURN MODAL
    // ════════════════════════════════════════════
    function openReturnModal(id, name, quantity) {
        document.getElementById('returnProductId').value    = id;
        document.getElementById('returnProductName').value  = name;
        document.getElementById('maxQuantity').textContent  = quantity;
        document.getElementById('returnQuantity').max       = quantity;
        document.getElementById('returnQuantity').value     = '';
        document.getElementById('returnQuantity').placeholder = `Enter quantity (max: ${quantity})`;
        document.getElementById('returnReason').value       = '';
        document.getElementById('returnModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('returnQuantity').focus(), 100);
    }

    function closeReturnModal() {
        document.getElementById('returnModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    // ════════════════════════════════════════════
    //  SELECT-ALL / APPROVE-ALL
    // ════════════════════════════════════════════
    function updateApproveAllBtn() {
        const n   = document.querySelectorAll('.row-checkbox:checked:not([disabled])').length;
        const btn = document.getElementById('approveAllBtn');
        if (btn) btn.style.display = n > 0 ? 'inline-block' : 'none';
    }

    function syncSelectAll() {
        const all     = document.querySelectorAll('.row-checkbox:not([disabled])');
        const checked = document.querySelectorAll('.row-checkbox:checked:not([disabled])');
        const sa      = document.getElementById('selectAll');
        if (!sa) return;
        sa.checked       = all.length > 0 && checked.length === all.length;
        sa.indeterminate = checked.length > 0 && checked.length < all.length;
    }

    async function approveSelected() {
        const checkboxes = document.querySelectorAll('.row-checkbox:checked:not([disabled])');
        if (!checkboxes.length) { showToast('No products selected.', 'error'); return; }
        if (!confirm(`Approve ${checkboxes.length} selected product(s)?`)) return;

        const btn = document.getElementById('approveAllBtn');
        btn.disabled  = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Approving...';

        let ok = 0, fail = 0;
        for (const cb of checkboxes) {
            try {
                const fd = new FormData();
                fd.append('_token',     '{{ csrf_token() }}');
                fd.append('product_id', cb.dataset.productId);
                const res = await fetch('restockProd', {
                    method:  'POST', body: fd,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (res.ok) { ok++; cb.disabled = true; cb.checked = false; }
                else fail++;
            } catch { fail++; }
        }

        btn.disabled  = false;
        btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Approve All';
        if (ok)   showToast(`${ok} product(s) approved!`, 'success');
        if (fail) showToast(`${fail} product(s) failed.`, 'error');
        updateApproveAllBtn(); syncSelectAll();
        if (ok) setTimeout(() => location.reload(), 1200);
    }

    // ════════════════════════════════════════════
    //  TOAST
    // ════════════════════════════════════════════
    function showToast(message, type = 'success') {
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = Object.assign(document.createElement('div'), { id:'toastContainer', className:'toast-container' });
            document.body.appendChild(container);
        }
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `<i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'}"></i><span>${message}</span>`;
        container.appendChild(toast);
        requestAnimationFrame(() => requestAnimationFrame(() => toast.classList.add('show')));
        setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 350); }, 3200);
    }

    // ════════════════════════════════════════════
    //  APPROVE ALL (ALL DATES)
    // ════════════════════════════════════════════
    function approveAllAllDates() {
        if (!confirm('Are you sure you want to approve ALL pending receivings from ALL dates? This will add all items to your inventory.')) return;

        const btn = document.getElementById('approveAllAllDatesBtn');
        if (btn) {
            btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Approving...';
            btn.disabled = true;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');

        fetch('approve-all-receivings-all-dates', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => {
            const isJson = res.headers.get('content-type')?.includes('application/json');
            return isJson
                ? res.json().then(d => ({ ok: res.ok, data: d, isJson: true }))
                : res.text().then(d => ({ ok: res.ok, data: d, isJson: false }));
        })
        .then(result => {
            const success = result.isJson
                ? (result.ok && result.data?.success === true)
                : (result.ok && result.data.includes('success'));

            if (success) {
                const msg = result.isJson ? (result.data?.message || 'Approved successfully!') : 'Approved successfully!';
                showToast(msg, 'success');
            } else {
                const msg = result.isJson ? (result.data?.message || 'Error approving receivings.') : 'Error approving receivings.';
                showToast(msg, 'error');
            }
        })
        .catch(() => showToast('An error occurred. Please try again.', 'error'))
        .finally(() => {
            if (btn) {
                btn.innerHTML = '<i class="bi bi-check2-all me-1"></i> Approve All (All Dates)';
                btn.disabled = false;
            }
            setTimeout(() => location.reload(), 1500);
        });
    }
    </script>
</body>
</html>