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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f8fafc;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            max-width: 1900px;
            margin: 0 auto;
            padding: 0.75rem;
        }

        /* Header Styles */
        .page-header {
            background: white;
            padding: 0.9rem 1.2rem;
            border-radius: var(--border-radius);
            margin-bottom: 0.75rem;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .date-picker-group {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            background: #f8f9fa;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
        }

        .date-picker-group label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .date-picker-group input {
            border: none;
            background: transparent;
            padding: 0.2rem;
            font-size: 0.85rem;
            color: var(--text-dark);
            width: 120px;
        }

        .date-picker-group .btn {
            background: var(--primary-color);
            border: none;
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            transition: var(--transition);
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .date-picker-group .btn:hover {
            background: var(--secondary-color);
        }

        /* Main Layout */
        .main-layout {
            display: grid;
            grid-template-columns: 70% 30%;
            gap: 0.75rem;
            height: calc(100vh - 150px);
        }

        @media (max-width: 1200px) {
            .main-layout {
                grid-template-columns: 1fr;
                height: auto;
            }
        }

        html {
            font-size: 16px;
        }

        body {
            zoom: 1.1;
        }

        /* Left Panel - Product Search & Selection */
        .left-panel {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .panel-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.75rem 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .panel-body {
            padding: 0.75rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            gap: 0.75rem;
        }

        /* Product Search */
        .product-search-section {
            flex-shrink: 0;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 0.5rem 0.75rem 0.5rem 2.2rem;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .search-box input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 78, 137, 0.1);
            outline: none;
        }

        .search-box i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 0.9rem;
        }

        /* Product List */
        .product-list-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-height: 0;
            display: none;
        }

        .product-list-section.active {
            display: flex;
        }

        .product-list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.4rem;
            flex-shrink: 0;
        }

        .product-list-title {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .product-count {
            background: var(--primary-color);
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .product-list {
            flex: 1;
            overflow-y: auto;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            background: #f8f9fa;
            min-height: 0;
        }

        .product-item {
            padding: 0.6rem 0.75rem;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.9rem;
        }

        .product-item:hover {
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-info {
            flex: 1;
            min-width: 0;
        }

        .product-name {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.15rem;
            font-size: 0.9rem;
        }

        .product-prices {
            font-size: 0.75rem;
            color: var(--text-light);
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 0.2rem;
        }

        .add-to-cart-btn {
            background: var(--success-color);
            border: none;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            flex-shrink: 0;
            font-size: 0.9rem;
        }

        .add-to-cart-btn:hover {
            transform: scale(1.08);
            box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
        }

        /* Right Panel - Order Details */
        .right-panel {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Supplier Section */
        .supplier-section {
            padding: 0.75rem;
            border-bottom: 1px solid #e9ecef;
            flex-shrink: 0;
        }

        .form-group-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.6rem;
        }

        @media (max-width: 768px) {
            .form-group-row {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.3rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            transition: var(--transition);
            font-size: 0.9rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 78, 137, 0.1);
            outline: none;
        }

        /* Cart Section */
        .cart-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-height: 0;
        }

        .cart-header {
            padding: 0.75rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .cart-title {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .cart-count {
            background: var(--primary-color);
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 0.75rem;
            min-height: 0;
        }

        .cart-item {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 0.75rem;
            margin-bottom: 0.6rem;
            transition: var(--transition);
            display: none;
        }

        .cart-item.table-mode {
            display: table-row;
            padding: 0;
            margin: 0;
            background: none;
            border: none;
            border-bottom: 1px solid #e9ecef;
        }

        .cart-items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-items-table thead {
            background: #f8f9fa;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .cart-items-table th {
            padding: 0.5rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.75rem;
            color: var(--text-dark);
            border-bottom: 2px solid #e9ecef;
        }

        .cart-items-table td {
            padding: 0.4rem;
            font-size: 0.8rem;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .cart-items-table tbody tr:hover {
            background: #f8f9fa;
        }

        .cart-product-cell {
            font-weight: 600;
            color: var(--text-dark);
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .price-cell {
            text-align: right;
            font-size: 0.75rem;
        }

        .qty-cell {
            text-align: center;
            width: 50px;
        }

        .qty-cell input,
        .price-cell input {
            width: 100%;
            padding: 0.3rem;
            text-align: center;
            border: 1px solid #e9ecef;
            border-radius: 3px;
            font-size: 0.75rem;
        }

        .qty-cell input:focus,
        .price-cell input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 78, 137, 0.1);
            outline: none;
        }

        .remove-item-table {
            text-align: center;
            width: 30px;
        }

        .remove-item-table button {
            background: transparent;
            border: none;
            color: var(--danger-color);
            cursor: pointer;
            font-size: 0.85rem;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .remove-item-table button:hover {
            background: rgba(220, 53, 69, 0.1);
            border-radius: 3px;
        }

        /* Summary Section */
        .summary-section {
            padding: 0.75rem;
            border-top: 1px solid #e9ecef;
            background: #f8fafc;
            flex-shrink: 0;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
        }

        .summary-label {
            font-weight: 600;
            color: var(--text-dark);
        }

        .summary-value {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--primary-color);
        }

        .total-summary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.8rem;
            border-radius: 6px;
            margin-top: 0.5rem;
        }

        .total-label {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .total-amount {
            font-size: 1.4rem;
            font-weight: 800;
            text-align: right;
        }

        /* Action Buttons */
        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.6rem;
            margin-top: 0.75rem;
        }

        .btn-primary-action {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 0.6rem;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            font-size: 0.9rem;
        }

        .btn-primary-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 78, 137, 0.2);
        }

        .btn-secondary-action {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            color: var(--text-dark);
            padding: 0.6rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            font-size: 0.9rem;
        }

        .btn-secondary-action:hover {
            background: #e9ecef;
            border-color: #dee2e6;
        }

        /* Empty States */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            text-align: center;
            color: var(--text-light);
        }

        .empty-state-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            opacity: 0.5;
        }

        .empty-state-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .empty-state p {
            font-size: 0.85rem;
            margin: 0;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            margin-bottom: 0.75rem;
            animation: slideDown 0.3s ease-out;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border-left: 4px solid var(--success-color);
            color: #155724;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border-left: 4px solid var(--danger-color);
            color: #721c24;
        }

        /* Receivings List Section */
        .receivings-list-section {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-top: 0.75rem;
            max-height: 350px;
        }

        .list-header {
            background: #f8f9fa;
            padding: 0.75rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            border-bottom: 2px solid #e9ecef;
        }

        .list-header:hover {
            background: #e9ecef;
        }

        .list-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .list-count {
            background: var(--primary-color);
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .list-body {
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .list-body.expanded {
            max-height: 280px;
            overflow-y: auto;
        }

        .table-responsive {
            margin-bottom: 0;
        }

        .table {
            margin-bottom: 0;
            font-size: 0.85rem;
        }

        .table thead th {
            padding: 0.5rem;
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
            background: #f8f9fa;
        }

        .table tbody td {
            padding: 0.5rem;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.3rem 0.5rem;
        }

        /* Return Modal */
        .return-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .return-modal.active {
            display: flex;
        }

        .return-modal-content {
            background: white;
            border-radius: var(--border-radius);
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .return-modal-header {
            background: linear-gradient(135deg, var(--danger-color), #c82333);
            color: white;
            padding: 1rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .return-modal-title {
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .return-modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1rem;
            transition: var(--transition);
        }

        .return-modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .return-modal-body {
            padding: 1.5rem;
        }

        .return-modal-body .form-group {
            margin-bottom: 1rem;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #d0d0d0;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
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
                    <i class="bi bi-box-seam"></i>
                    Receiving Management
                </div>
                <div class="date-picker-group">
                    <form method="GET" action="{{ url('restock') }}" class="d-flex align-items-center gap-2">
                        <label for="date" class="form-label mb-0">Date:</label>
                        <input type="date" name="date" id="date" value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}">
                        <button type="submit" class="btn">
                            <i class="bi bi-calendar-check me-1"></i>View
                        </button>
                    </form>
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Main Layout -->
            <div class="main-layout">
                <!-- Left Panel: Product Search & Selection -->
                <div class="left-panel">
                    <div class="panel-header">
                        <i class="bi bi-search"></i>
                        Select Products
                    </div>
                    
                    <div class="panel-body">
                        <!-- Product Search -->
                        <div class="product-search-section">
                            <div class="search-box">
                                <i class="bi bi-search"></i>
                                <input type="text" id="productSearch" placeholder="Search products by name...">
                            </div>
                        </div>

                        <!-- Product List -->
                        <div class="product-list-section">
                            <div class="product-list-header">
                                <div class="product-list-title">
                                    Available Products
                                </div>
                                <div class="product-count" id="productCount">0</div>
                            </div>
                            
                            <div class="product-list" id="productList">
                                <!-- Product items will be loaded here -->
                            </div>
                        </div>
                        
                        <div class="cart-section">
                            <div class="cart-header">
                                <div class="cart-title">
                                    <i class="bi bi-cart"></i>
                                    Selected
                                </div>
                                <div class="cart-count" id="cartCount">0</div>
                            </div>
                            
                            <div class="cart-items" id="cartItems">
                                <!-- Cart items will be added here -->
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                    <div class="empty-state-title">No products</div>
                                    <p>Add products to get started</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Order Details -->
                <div class="right-panel">
                    <div class="panel-header">
                        <i class="bi bi-cart-plus"></i>
                        Order Details
                    </div>
                    
                    <div class="supplier-section">
                        <form id="orderForm">
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
                                        <i class="bi bi-person"></i> Allocation
                                    </label>
                                    <select name="served" id="served" class="form-select" required>
                                        <option value="" disabled selected>Select Staff</option>
                                        @foreach (DB::table('users')->where('account', session('account'))->get() as $user)
                                            <option value="{{ $user->name }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                        </form>
                    </div>

                    <!-- Summary Section -->
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
                        
                        <!-- Action Buttons -->
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

            <!-- Receivings List (Collapsible) -->
            <div class="receivings-list-section">
                <div class="list-header" id="toggleList">
                    <div class="list-title">
                        <i class="bi bi-list"></i>
                        Recent Receivings
                    </div>
                    <div class="list-count">{{ $products->count() }}</div>
                </div>
                
                <div class="list-body" id="listBody">
                    @if($products->isEmpty())
                    <div style="padding: 1rem; text-align: center; color: var(--text-light); font-size: 0.9rem;">
                        No receivings yet
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Cost</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Supplier</th>
                                    <th>Allocated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $item)
                                <tr id="row-{{ $item->productId }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ date('M d, Y', strtotime($item->created_at)) }}</td>
                                    <td>
                                        <strong>{{ $item->productName }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ number_format($item->quantity) }}</span>
                                    </td>
                                    <td>Tsh. {{ number_format($item->price, 2) }}</td>
                                    <td>
                                        <strong>Tsh. {{ number_format($item->price * $item->quantity, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if ($item->isPaid == 1)
                                            <span class="badge bg-success">Cash</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Credit</span>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="bi bi-person"></i> {{ $item->supplier ?? 'Unknown' }}
                                    </td>
                                    <td>
                                        <i class="bi bi-person-check"></i> {{ $item->served_by ?? 'Unknown' }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @if ($item->status != 'Approved')
                                            <button type="button" 
                                                    class="btn btn-sm btn-success approve-btn" 
                                                    onclick="approveProduct('{{ $item->productId }}', this)"
                                                    title="Approve"
                                                    data-approved="false">
                                                <i class="bi bi-check"></i>
                                            </button>
                                            @endif
                                            
                                            <button type="button" class="btn btn-sm btn-outline-danger return-btn" 
                                                    data-id="{{ $item->productId }}"
                                                    data-name="{{ $item->productName }}"
                                                    data-quantity="{{ $item->quantity }}"
                                                    title="Return">
                                                <i class="bi bi-arrow-return-left"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        </main>
    </div>

    <!-- Return Modal -->
    <div class="return-modal" id="returnModal">
        <div class="return-modal-content">
            <div class="return-modal-header">
                <div class="return-modal-title">
                    <i class="bi bi-arrow-return-left"></i>
                    Return Product
                </div>
                <button class="return-modal-close" id="closeReturnModal">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="return-modal-body">
                <form action="dltrestock" method="post" id="returnForm">
                    @csrf
                    <input type="hidden" name="product_id" id="returnProductId">
                    
                    <div class="form-group">
                        <label class="form-label">Product</label>
                        <input type="text" class="form-control" id="returnProductName" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="returnQuantity" class="form-label">
                            <i class="bi bi-box"></i> Quantity to Return (Max: <span id="maxQuantity">0</span>)
                        </label>
                        <input type="number" name="quantity" id="returnQuantity" class="form-control" 
                               placeholder="Enter quantity to return" min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="returnReason" class="form-label">
                            <i class="bi bi-chat-text"></i> Reason for Return
                        </label>
                        <textarea name="reason" id="returnReason" class="form-control" rows="4" 
                                  placeholder="Please specify the reason for returning this product..." required></textarea>
                    </div>
                    
                    <div class="action-buttons">
                        <button type="button" class="btn-secondary-action" id="cancelReturn">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary-action" style="background: linear-gradient(135deg, var(--danger-color), #c82333);">
                            <i class="bi bi-arrow-return-left me-1"></i> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Product data from PHP
        const allProducts = [
            @foreach (DB::table('products')->where('name01', '!=', '')->where('account', session('account'))->get() as $product)
            {
                id: "{{ $product->product_id }}",
                name: "{{ $product->name01 }}",
                cost: {{ $product->bPrice ?? 0 }},
                wholesale: {{ $product->wholesale ?? 0 }},
                retail: {{ $product->sPrice ?? 0 }},
                currentStock: {{ $product->quantity ?? 0 }}
            },
            @endforeach
        ];

        // Shopping cart
        let shoppingCart = [];
        let selectedProductId = null;
        let isProcessingApproval = false;

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Load products
            displayProducts(allProducts);
            
            // Setup event listeners
            setupEventListeners();
            
            // Update counts
            updateCounts();
        });

        // Setup all event listeners
        function setupEventListeners() {
            // Product search
            document.getElementById('productSearch').addEventListener('input', handleProductSearch);
            
            // Clear cart button
            document.getElementById('clearCartBtn').addEventListener('click', clearCart);
            
            // Submit order button
            document.getElementById('submitOrderBtn').addEventListener('click', submitOrder);
            
            // Toggle list visibility
            document.getElementById('toggleList').addEventListener('click', toggleList);
            
            // Return modal
            document.querySelectorAll('.return-btn').forEach(btn => {
                btn.addEventListener('click', openReturnModal);
            });
            
            document.getElementById('closeReturnModal').addEventListener('click', closeReturnModal);
            document.getElementById('cancelReturn').addEventListener('click', closeReturnModal);
            
            // Close modal when clicking outside
            document.getElementById('returnModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeReturnModal();
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeReturnModal();
                }
            });
        }

        // Display products in the list
        function displayProducts(products) {
            const productList = document.getElementById('productList');
            
            if (products.length === 0) {
                productList.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <div class="empty-state-title">No products</div>
                        <p>Try a different search</p>
                    </div>
                `;
                return;
            }
            
            productList.innerHTML = products.map(product => `
                <div class="product-item" data-product-id="${product.id}">
                    <div class="product-info">
                        <div class="product-name">${product.name}</div>
                        <div class="product-prices">
                            <div class="product-price">
                                <i class="bi bi-tag"></i>
                                Tsh. ${product.cost.toLocaleString()}
                            </div>
                            <div class="product-price">
                                <i class="bi bi-box"></i>
                                Stock: ${product.currentStock}
                            </div>
                        </div>
                    </div>
                    <button type="button" class="add-to-cart-btn" onclick="addToCart('${product.id}')">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            `).join('');
            
            // Update product count
            document.getElementById('productCount').textContent = products.length;
        }

        // Handle product search
        function handleProductSearch(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            const productListSection = document.querySelector('.product-list-section');
            
            if (searchTerm === '') {
                productListSection.classList.remove('active');
                return;
            }
            
            productListSection.classList.add('active');
            
            const filteredProducts = allProducts.filter(product =>
                product.name.toLowerCase().includes(searchTerm)
            );
            
            displayProducts(filteredProducts);
        }

        // Add product to cart
        function addToCart(productId) {
            const product = allProducts.find(p => p.id === productId);
            
            if (!product) return;
            
            // Check if product is already in cart
            const existingItem = shoppingCart.find(item => item.productId === productId);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                // Add new item to cart
                shoppingCart.push({
                    cartId: Date.now() + Math.random().toString(36).substr(2, 9),
                    productId: product.id,
                    name: product.name,
                    cost: product.cost,
                    wholesale: product.wholesale,
                    retail: product.retail,
                    quantity: 1,
                    type: 'Cash',
                    expiry: ''
                });
            }
            
            // Update cart display
            updateCartDisplay();
            updateCounts();
            updateSummary();
        }

        // Update cart display
        function updateCartDisplay() {
            const cartItems = document.getElementById('cartItems');
            
            if (shoppingCart.length === 0) {
                cartItems.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-cart"></i>
                        </div>
                        <div class="empty-state-title">No products</div>
                        <p>Add products to get started</p>
                    </div>
                `;
                return;
            }
            
            cartItems.innerHTML = `
                <table class="cart-items-table">
                    <thead>
                        <tr>
                            <th style="width: 18%;">Product</th>
                            <th style="width: 8%; text-align: center;">Qty</th>
                            <th style="width: 12%; text-align: right;">Cost</th>
                            <th style="width: 12%; text-align: right;">Wholesale</th>
                            <th style="width: 12%; text-align: right;">Retail</th>
                            <th style="width: 12%; text-align: right;">Total</th>
                            <th style="width: 14%; text-align: center;">Expiry</th>
                            <th style="width: 6%; text-align: center;">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${shoppingCart.map(item => `
                            <tr>
                                <td class="cart-product-cell">${item.name}</td>
                                <td class="qty-cell">
                                    <input type="number" 
                                           class="quantity" 
                                           data-cart-id="${item.cartId}"
                                           value="${item.quantity}"
                                           min="1"
                                           oninput="updateQuantity('${item.cartId}', this.value); updateSummary()">
                                </td>
                                <td class="price-cell">
                                    <input type="number" 
                                           class="cost-price" 
                                           data-cart-id="${item.cartId}"
                                           value="${item.cost}"
                                           step="0.01"
                                           oninput="updatePrice('${item.cartId}', 'cost', this.value); updateSummary()">
                                </td>
                                <td class="price-cell">
                                    <input type="number" 
                                           class="wholesale-price" 
                                           data-cart-id="${item.cartId}"
                                           value="${item.wholesale}"
                                           step="0.01"
                                           oninput="updatePrice('${item.cartId}', 'wholesale', this.value)">
                                </td>
                                <td class="price-cell">
                                    <input type="number" 
                                           class="retail-price" 
                                           data-cart-id="${item.cartId}"
                                           value="${item.retail}"
                                           step="0.01"
                                           oninput="updatePrice('${item.cartId}', 'retail', this.value)">
                                </td>
                                <td class="price-cell">
                                    <strong>Tsh ${(item.cost * item.quantity).toLocaleString()}</strong>
                                </td>
                                <td style="text-align: center;">
                                    <input type="month" 
                                           class="expiry" 
                                           data-cart-id="${item.cartId}"
                                           value="${item.expiry}"
                                           onchange="updateExpiry('${item.cartId}', this.value)"
                                           style="padding: 0.3rem; font-size: 0.75rem; text-align: center; border: 1px solid #e9ecef; border-radius: 3px;">
                                </td>
                                <td class="remove-item-table">
                                    <button type="button" onclick="removeFromCart('${item.cartId}')" title="Remove">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        }

        // Update item price
        function updatePrice(cartId, priceType, value) {
            const item = shoppingCart.find(i => i.cartId === cartId);
            if (item) {
                item[priceType] = parseFloat(value) || 0;
            }
        }

        // Update item quantity
        function updateQuantity(cartId, value) {
            const item = shoppingCart.find(i => i.cartId === cartId);
            if (item) {
                item.quantity = parseInt(value) || 1;
            }
        }

        // Apply transaction type to all items
        function applyTypeToAll(value) {
            shoppingCart.forEach(item => {
                item.type = value;
            });
        }

        // Update expiry date
        function updateExpiry(cartId, value) {
            const item = shoppingCart.find(i => i.cartId === cartId);
            if (item) {
                item.expiry = value;
            }
        }

        // Remove item from cart
        function removeFromCart(cartId) {
            shoppingCart = shoppingCart.filter(item => item.cartId !== cartId);
            updateCartDisplay();
            updateCounts();
            updateSummary();
        }

        // Clear cart
        function clearCart() {
            if (shoppingCart.length === 0) return;
            
            if (confirm('Are you sure you want to clear all products?')) {
                shoppingCart = [];
                updateCartDisplay();
                updateCounts();
                updateSummary();
            }
        }

        // Update counts
        function updateCounts() {
            document.getElementById('cartCount').textContent = shoppingCart.length;
            document.getElementById('productCount').textContent = allProducts.length;
        }

        // Update summary
        function updateSummary() {
            let subtotal = 0;
            let itemCount = 0;
            
            shoppingCart.forEach(item => {
                const itemTotal = (item.cost || 0) * (item.quantity || 1);
                subtotal += itemTotal;
                itemCount += item.quantity;
            });
            
            // Update display
            document.getElementById('subtotal').textContent = `Tsh. ${subtotal.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
            document.getElementById('itemCount').textContent = itemCount;
            document.getElementById('totalAmount').textContent = `Tsh. ${subtotal.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        }

        // Submit order
        function submitOrder() {
            const supplier = document.getElementById('supplier').value;
            const served = document.getElementById('served').value;
            
            if (shoppingCart.length === 0) {
                alert('Please add at least one product to the cart.');
                return;
            }
            
            if (!supplier || !served) {
                alert('Please select both supplier and allocation.');
                return;
            }
            
            // Validate all cart items
            let isValid = true;
            shoppingCart.forEach(item => {
                if (!item.cost || !item.quantity) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                alert('Please fill in all required fields for each product.');
                return;
            }
            
            // Prepare form data
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('supplier', supplier);
            formData.append('served', served);
            
            shoppingCart.forEach((item, index) => {
                formData.append(`product_id[]`, item.productId);
                formData.append(`quantity[]`, item.quantity);
                formData.append(`bPrice[]`, item.cost);
                formData.append(`wholesale[]`, item.wholesale);
                formData.append(`sPrice[]`, item.retail);
                formData.append(`type[]`, item.type);
                formData.append(`expiry[]`, item.expiry);
            });
            
            // Show loading state
            const submitBtn = document.getElementById('submitOrderBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Saving...';
            submitBtn.disabled = true;
            
            // Submit the form
            fetch('restock', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(data => {
                // Handle response
                if (data.includes('success') || data.includes('Success')) {
                    // Clear cart and show success message
                    shoppingCart = [];
                    updateCartDisplay();
                    updateCounts();
                    updateSummary();
                    
                    // Show success alert (you might want to redirect or reload instead)
                    alert('Receiving saved successfully!');
                    
                    // Reload the page to show updated list
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert('Error saving receiving. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            })
            .finally(() => {
                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }

        // Toggle list visibility
        function toggleList() {
            const listBody = document.getElementById('listBody');
            listBody.classList.toggle('expanded');
        }

        // Return modal functions
        function openReturnModal(e) {
            const productId = e.currentTarget.getAttribute('data-id');
            const productName = e.currentTarget.getAttribute('data-name');
            const maxQuantity = e.currentTarget.getAttribute('data-quantity');
            
            document.getElementById('returnProductId').value = productId;
            document.getElementById('returnProductName').value = productName;
            document.getElementById('maxQuantity').textContent = maxQuantity;
            document.getElementById('returnQuantity').max = maxQuantity;
            document.getElementById('returnQuantity').placeholder = `Enter quantity (max: ${maxQuantity})`;
            
            document.getElementById('returnModal').classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Focus on reason textarea
            setTimeout(() => {
                document.getElementById('returnReason').focus();
            }, 300);
        }

        function closeReturnModal() {
            document.getElementById('returnModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            
            // Reset form
            document.getElementById('returnForm').reset();
        }

        // APPROVE PRODUCT FUNCTION - FIXED VERSION
        async function approveProduct(productId, button) {
            // Prevent multiple clicks
            if (isProcessingApproval) {
                return;
            }
            
            // Check if already approved
            if (button.getAttribute('data-approved') === 'true') {
                showToast('This product is already approved', 'error');
                return;
            }
            
            // Disable button immediately
            button.disabled = true;
            button.classList.add('loading');
            isProcessingApproval = true;
            
            try {
                // Create form data
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('product_id', productId);
                
                // Send approval request
                const response = await fetch('restockProd', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.text();
                
                if (response.ok) {
                    // Mark as approved
                    button.setAttribute('data-approved', 'true');
                    button.innerHTML = '<i class="bi bi-check"></i>';
                    button.style.opacity = '0.6';
                    button.style.cursor = 'not-allowed';
                    button.title = 'Approved';
                    
                    // Mark the row as approved visually
                    const row = document.getElementById(`row-${productId}`);
                    if (row) {
                        row.style.backgroundColor = '#f0fff4';
                        row.style.transition = 'background-color 0.3s ease';
                    }
                    
                    showToast('Product approved successfully!', 'success');
                    
                    // Optionally remove the button after approval
                    setTimeout(() => {
                        button.remove();
                    }, 2000);
                    
                } else {
                    throw new Error('Approval failed');
                }
                
            } catch (error) {
                console.error('Approval error:', error);
                showToast('Failed to approve product. Please try again.', 'error');
                
                // Re-enable button on error
                button.disabled = false;
                button.classList.remove('loading');
            } finally {
                // Re-enable processing after a delay
                setTimeout(() => {
                    isProcessingApproval = false;
                }, 1000);
            }
        }

        // Toast notification function
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'}" 
                   style="color: ${type === 'success' ? 'var(--success-color)' : 'var(--danger-color)'}"></i>
                <span>${message}</span>
            `;
            
            toastContainer.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        // Add some example products for demonstration if none exist
        if (allProducts.length === 0) {
            allProducts.push(
                { id: '1', name: 'Sugar 1kg', cost: 2500, wholesale: 2800, retail: 3000, currentStock: 100 },
                { id: '2', name: 'Rice 5kg', cost: 15000, wholesale: 17000, retail: 18000, currentStock: 50 },
                { id: '3', name: 'Cooking Oil 3L', cost: 12000, wholesale: 13500, retail: 15000, currentStock: 75 },
                { id: '4', name: 'Wheat Flour 2kg', cost: 3500, wholesale: 4000, retail: 4500, currentStock: 40 },
                { id: '5', name: 'Tea Leaves 500g', cost: 4500, wholesale: 5000, retail: 5500, currentStock: 60 }
            );
            displayProducts(allProducts);
            updateCounts();
        }
    </script>
</body>
</html>