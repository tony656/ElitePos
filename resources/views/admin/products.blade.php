<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{config("app.name")}} - Product Management</title>
    @include("links")
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #eef2ff;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --danger-color: #ef4444;
            --danger-light: #fee2e2;
            --warning-color: #f59e0b;
            --warning-light: #fef3c7;
            --success-color: #10b981;
            --success-light: #d1fae5;
            --light-bg: #f8fafc;
            --dark-text: #1e293b;
            --light-text: #64748b;
            --border-color: #e2e8f0;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.1);
            --hover-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
        
        * {
            transition: all 0.2s ease;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
            line-height: 1.5;
        }
        
        .dashboard-header {
            background: white;
            box-shadow: var(--card-shadow);
            padding: 1.25rem 2rem;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-color);
        }
        
        .stat-card.total::before { background: var(--primary-color); }
        .stat-card.out-of-stock::before { background: var(--danger-color); }
        .stat-card.expired::before { background: var(--warning-color); }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--hover-shadow);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .stat-icon {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: var(--primary-light);
            color: var(--primary-color);
            font-size: 1.5rem;
        }
        
        .stat-icon.out-of-stock {
            background: var(--danger-light);
            color: var(--danger-color);
        }
        
        .stat-icon.expired {
            background: var(--warning-light);
            color: var(--warning-color);
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
            margin: 0.5rem 0;
            color: var(--dark-text);
        }
        
        .stat-label {
            color: var(--light-text);
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .trend-indicator {
            display: inline-flex;
            align-items: center;
            font-size: 0.875rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            margin-top: 0.5rem;
        }
        
        .trend-positive {
            background: var(--success-light);
            color: var(--success-color);
        }
        
        .trend-negative {
            background: var(--danger-light);
            color: var(--danger-color);
        }
        
        .search-section {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
        }
        
        .search-container {
            position: relative;
            flex: 1;
        }
        
        .search-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 0.9375rem;
            background: white;
            transition: all 0.2s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--light-text);
        }
        
        .action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }
        
        .btn-outline {
            background: white;
            border-color: var(--border-color);
            color: var(--dark-text);
        }
        
        .btn-outline:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: var(--primary-light);
        }
        
        .btn-danger {
            background: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }
        
        .products-table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
        }
        
        .table-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
        }
        
        .table-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }
        
        .table-actions {
            display: flex;
            gap: 0.75rem;
        }
        
        .table {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        
        .table thead {
            background: #f8fafc;
        }
        
        .table th {
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--light-text);
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
        }
        
        .table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background: #f8fafc;
        }
        
        .product-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .product-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.25rem;
        }
        
        .product-details {
            flex: 1;
        }
        
        .product-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--dark-text);
        }
        
        .product-subtitle {
            font-size: 0.875rem;
            color: var(--light-text);
        }
        
        .stock-indicator {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .stock-low {
            background: var(--danger-light);
            color: var(--danger-color);
        }
        
        .stock-medium {
            background: var(--warning-light);
            color: var(--warning-color);
        }
        
        .stock-high {
            background: var(--success-light);
            color: var(--success-color);
        }
        
        .price-tag {
            font-weight: 600;
            color: var(--dark-text);
        }
        
        .price-tag.cost {
            color: var(--danger-color);
        }
        
        .category-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            background: #f1f5f9;
            color: var(--light-text);
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .action-buttons-cell {
            display: flex;
            gap: 0.5rem;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 2px solid var(--border-color);
            background: white;
            color: var(--light-text);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .action-btn.view:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .action-btn.delete:hover {
            border-color: var(--danger-color);
            color: var(--danger-color);
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }
        
        .empty-state-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            border-radius: 50%;
            color: var(--light-text);
            font-size: 2rem;
        }
        
        .empty-state-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark-text);
        }
        
        .empty-state-description {
            color: var(--light-text);
            max-width: 400px;
            margin: 0 auto 1.5rem;
        }
        
        .checkbox-cell {
            width: 40px;
            padding-right: 0;
        }
        
        .custom-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            cursor: pointer;
            position: relative;
            background: white;
        }
        
        .custom-checkbox.checked {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .custom-checkbox.checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        
        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 1.5rem;
        }
        
        .form-select, .form-control {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
        }
        
        .form-select:focus, .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        /* Print Order Styles */
        .print-order-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
        }

        .order-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 3px solid var(--primary-color);
        }

        .order-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .order-subtitle {
            color: var(--light-text);
            font-size: 0.95rem;
        }

        .order-date {
            color: var(--light-text);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .order-items-table {
            width: 100%;
            margin-bottom: 2rem;
            border-collapse: collapse;
        }

        .order-items-table thead {
            background: var(--primary-light);
        }

        .order-items-table th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--primary-color);
            border: 1px solid var(--border-color);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .order-items-table td {
            padding: 1rem;
            border: 1px solid var(--border-color);
            font-size: 0.95rem;
        }

        .order-items-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .product-display {
            display: flex;
            flex-direction: column;
        }

        .product-display-name {
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 0.25rem;
        }

        .product-display-subtitle {
            font-size: 0.8rem;
            color: var(--light-text);
        }

        .order-summary {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            font-size: 0.95rem;
        }

        .summary-row.total {
            padding: 1rem 0;
            margin-top: 1rem;
            border-top: 2px solid var(--primary-color);
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--primary-color);
        }

        .print-button-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        @media print {
            body {
                background: white;
            }
            
            .btn, .modal-footer, .modal-header {
                display: none;
            }
            
            .print-order-container {
                box-shadow: none;
                background: white;
            }
        }

        @media (max-width: 768px) {
            .dashboard-header {
                padding: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .table-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
            
            .table-actions {
                flex-wrap: wrap;
            }
            
            .search-section {
                padding: 1rem;
            }
            
            .action-buttons {
                width: 100%;
            }
            
            .btn {
                flex: 1;
                justify-content: center;
            }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">

        <!-- Header -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
               
                    <h1 class="h4 mb-0 fw-bold">Product Inventory</h1>
                    <p class="text-muted mb-0">Manage your products and stock levels</p>
                </div>
                <div>
                    <button class="btn btn-primary" onclick="downloadReport()">
                        <i class="bi bi-file-earmark-text"></i>
                        Export Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-header">
                    <div>
                        <div class="stat-label">Total Products</div>
                        <div class="stat-value">{{ number_format($TProducts) }}</div>
                        <div class="trend-indicator trend-positive">
                            <i class="bi bi-arrow-up"></i> All active
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card out-of-stock">
                <div class="stat-header">
                    <div>
                        <div class="stat-label">Out of Stock</div>
                        <div class="stat-value">{{ number_format($ofs) }}</div>
                        <div class="trend-indicator trend-negative">
                            <i class="bi bi-exclamation-triangle"></i> Needs attention
                        </div>
                    </div>
                    <div class="stat-icon out-of-stock">
                        <i class="bi bi-exclamation-octagon"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card expired">
                <div class="stat-header">
                    <div>
                        <div class="stat-label">Expired Products</div>
                        <div class="stat-value">{{ number_format($CMofs) }}</div>
                        <div class="trend-indicator trend-negative">
                            <i class="bi bi-clock"></i> Check expiry dates
                        </div>
                    </div>
                    <div class="stat-icon expired">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Action Bar -->
        <div class="search-section">
            <div class="d-flex flex-column flex-lg-row gap-3">
                <div class="search-container">
                    <i class="bi bi-search search-icon"></i>
                    <input type="search" class="search-input" id="search-input" placeholder="Search products by name, category, or ID...">
                </div>
                <div class="action-buttons">
                    <a class="btn btn-outline" href="itemRequest">
                        <i class="bi bi-plus-circle"></i> Item Request
                    </a>
                    <a class="btn btn-outline" href="viewRequest">
                        <i class="bi bi-list-check"></i> View Requests
                    </a>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="products-table-container">
                    
            <div class="table-header">
                <h2 class="table-title">Product List</h2>
                <div class="table-actions">
                    <button class="btn btn-outline" id="selectAllBtn" onclick="toggleSelectAll()">
                        <i class="bi bi-check2-square"></i> Select All
                    </button>
                    <button class="btn btn-primary" id="printBtn" onclick="openPrintModal()">
                        <i class="bi bi-printer"></i> Print Order
                    </button>
                       @if (session('account') == 'Loliondo SHop') 
                    <button class="btn btn-primary" id="duplicateBtn" onclick="openDuplicateModal()" disabled>
                        <i class="bi bi-copy"></i> Duplicate
                    </button>
                      @endif
                </div>
            </div>
          
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="checkbox-cell">
                                <div class="custom-checkbox" id="masterCheckbox" onclick="toggleAllCheckboxes()"></div>
                            </th>
                            <th>PRODUCT</th>
                            <th>STOCK</th>
                            <th>COST PRICE</th>
                            <th>SELLING PRICE</th>
                            <th>CATEGORY</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($products->isEmpty())
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-box"></i>
                                    </div>
                                    <h3 class="empty-state-title">No Products Found</h3>
                                    <p class="empty-state-description">Add your first product to start managing your inventory</p>
                                    <a href="itemRequest" class="btn btn-primary">
                                        <i class="bi bi-plus-lg"></i> Add New Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @else
                            @foreach ($products as $index => $product)
                            <form method="post">
                                @csrf
                                <tr data-product-id="{{ $product->product_id }}">
                                    <td class="checkbox-cell">
                                        <div class="custom-checkbox product-checkbox" onclick="toggleCheckbox(this, '{{ $product->product_id }}')"></div>
                                    </td>
                                    <td>
                                        <div class="product-info">
                                            <div class="product-avatar">
                                                {{ strtoupper(substr($product->name01, 0, 2)) }}
                                            </div>
                                            <div class="product-details">
                                                <div class="product-name">{{ $product->name01 }}</div>
                                                <div class="product-subtitle">{{ $product->name02 }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $stockClass = '';
                                            if($product->quantity <= 0) {
                                                $stockClass = 'stock-low';
                                            } elseif($product->quantity < 10) {
                                                $stockClass = 'stock-medium';
                                            } else {
                                                $stockClass = 'stock-high';
                                            }
                                        @endphp
                                        <span class="stock-indicator {{ $stockClass }}">
                                            {{ number_format($product->quantity) }} {{ $product->unit }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="price-tag cost">Tsh {{ number_format($product->bPrice) }}</span>
                                    </td>
                                    <td>
                                        <span class="price-tag">Tsh {{ number_format($product->sPrice) }}</span>
                                    </td>
                                    <td>
                                        <span class="category-badge">{{ $product->category }}</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons-cell">
                                            @if ($product->stock2 > 0)
                                            <button class="action-btn" name="product_id" formaction="restockProd" value="{{ $product->product_id }}" title="Restock">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                            @endif
                                            <button class="action-btn view" name="product_id" formaction="viewProduct" value="{{ $product->product_id }}" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="action-btn delete" name="product_id" formaction="dltProduct" value="{{ $product->product_id }}" title="Delete Product">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </form>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </main>
  </div>
</div>

<!-- Print Order Modal -->
<div class="modal fade" id="printModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Print Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="printModalBody">
                <!-- Print content will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printOrder()">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Duplicate Products Modal -->
<div class="modal fade" id="duplicateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Duplicate Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="duplicateForm" action="{{ route('admin.duplicateProducts') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="targetAccount" class="form-label fw-semibold mb-2">Select Destination Shop</label>
                        <select class="form-select" id="targetAccount" name="target_account" required>
                            <option value="">Choose a shop...</option>
                            @foreach($getAllAccounts as $account)
                                <option value="{{ $account->name }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text mt-1">Products will be copied to the selected shop</div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">Options</h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="includeStock" name="include_stock" checked>
                            <label class="form-check-label" for="includeStock">
                                Include stock quantities
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="includePricing" name="include_pricing" checked>
                            <label class="form-check-label" for="includePricing">
                                Include pricing information
                            </label>
                        </div>
                    </div>
                    
                    <div id="selectedProductsList" class="mb-4">
                        <h6 class="fw-semibold mb-3">Selected Products</h6>
                        <div class="selected-products-container" style="max-height: 200px; overflow-y: auto;">
                            <!-- Products will be added here -->
                        </div>
                    </div>
                    
                    <div id="hiddenInputs"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmDuplicateBtn" onclick="duplicateProducts()">
                    <span id="duplicateBtnText">
                        <i class="bi bi-copy"></i> Duplicate Products
                    </span>
                    <span id="duplicateLoading" style="display: none;">
                        <span class="spinner-border spinner-border-sm" role="status"></span> Duplicating...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Search functionality
    document.getElementById('search-input').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr[data-product-id]');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Checkbox management
    function toggleCheckbox(element, productId) {
        element.classList.toggle('checked');
        updateActionButtons();
    }

    function toggleAllCheckboxes() {
        const masterCheckbox = document.getElementById('masterCheckbox');
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const isChecked = masterCheckbox.classList.contains('checked');
        
        masterCheckbox.classList.toggle('checked');
        
        checkboxes.forEach(checkbox => {
            if (isChecked) {
                checkbox.classList.remove('checked');
            } else {
                checkbox.classList.add('checked');
            }
        });
        
        updateActionButtons();
    }

    function toggleSelectAll() {
        toggleAllCheckboxes();
        const selectAllBtn = document.getElementById('selectAllBtn');
        const masterCheckbox = document.getElementById('masterCheckbox');
        
        if (masterCheckbox.classList.contains('checked')) {
            selectAllBtn.innerHTML = '<i class="bi bi-x-square"></i> Deselect All';
        } else {
            selectAllBtn.innerHTML = '<i class="bi bi-check2-square"></i> Select All';
        }
    }

    function updateActionButtons() {
        const checkboxes = document.querySelectorAll('.product-checkbox.checked');
        const duplicateBtn = document.getElementById('duplicateBtn');
        const printBtn = document.getElementById('printBtn');
        
        const isEnabled = checkboxes.length > 0;
        duplicateBtn.disabled = !isEnabled;
        printBtn.disabled = !isEnabled;
    }

    // Print Order Modal Functions
    function openPrintModal() {
        const selectedCheckboxes = document.querySelectorAll('.product-checkbox.checked');
        
        if (selectedCheckboxes.length === 0) {
            alert('Please select at least one product');
            return;
        }

        const selectedProducts = Array.from(selectedCheckboxes).map(cb => {
            const row = cb.closest('tr');
            return {
                id: row.dataset.productId,
                name1: row.querySelector('.product-name').textContent,
                name2: row.querySelector('.product-subtitle').textContent,
                category: row.querySelector('.category-badge').textContent,
                price: row.querySelector('.price-tag').textContent
            };
        });

        generatePrintContent(selectedProducts);
        
        const modal = new bootstrap.Modal(document.getElementById('printModal'));
        modal.show();
    }

    function generatePrintContent(products) {
        const currentDate = new Date().toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });

        let tableRows = products.map((product, index) => `
            <tr>
                <td style="text-align: center; width: 5%;">${index + 1}</td>
                <td style="width: 45%;">
                    <div class="product-display">
                        <div class="product-display-name">${product.name1}</div>
                        <div class="product-display-subtitle">${product.name2}</div>
                    </div>
                </td>
                <td style="width: 20%;">${product.category}</td>
                <td style="text-align: center; width: 15%;">${product.price}</td>
            </tr>
        `).join('');

        const printContent = `
            <div class="print-order-container">
                <div class="order-header">
                    <div class="order-title">📦 PURCHASE ORDER</div>
                    <div class="order-subtitle">Product List for Procurement</div>
                    <div class="order-date">${currentDate}</div>
                </div>

                <table class="order-items-table">
                    <thead>
                        <tr>
                            <th style="width: 5%; text-align: center;">No.</th>
                            <th style="width: 45%;">Product Name</th>
                            <th style="width: 20%;">Category</th>
                            <th style="width: 15%; text-align: center;">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${tableRows}
                    </tbody>
                </table>

                <div class="order-summary">
                    <div class="summary-row">
                        <span><strong>Total Items:</strong></span>
                        <span><strong>${products.length}</strong></span>
                    </div>
                    <div class="summary-row total">
                        <span>TOTAL PRODUCTS</span>
                        <span>${products.length}</span>
                    </div>
                </div>

                <div style="margin-top: 3rem; padding-top: 2rem; border-top: 2px solid #e2e8f0; display: flex; justify-content: space-between;">
                    <div style="text-align: center; width: 30%;">
                        <div style="height: 60px; margin-bottom: 0.5rem;"></div>
                        <div style="border-top: 2px solid #1e293b; font-size: 0.875rem;">Prepared by</div>
                    </div>
                    <div style="text-align: center; width: 30%;">
                        <div style="height: 60px; margin-bottom: 0.5rem;"></div>
                        <div style="border-top: 2px solid #1e293b; font-size: 0.875rem;">Approved by</div>
                    </div>
                    <div style="text-align: center; width: 30%;">
                        <div style="height: 60px; margin-bottom: 0.5rem;"></div>
                        <div style="border-top: 2px solid #1e293b; font-size: 0.875rem;">Date</div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('printModalBody').innerHTML = printContent;
    }

    function printOrder() {
        const printContent = document.querySelector('.print-order-container');
        const windowToOpen = window.open('', '', 'height=600,width=800');
        
        windowToOpen.document.write(`
            <!DOCTYPE html>
            <html>
                <head>
                    <style>
                        body {
                            font-family: 'Inter', Arial, sans-serif;
                            margin: 0;
                            padding: 20px;
                            background: white;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin: 20px 0;
                        }
                        th, td {
                            border: 1px solid #e2e8f0;
                            padding: 12px;
                            text-align: left;
                        }
                        th {
                            background-color: #eef2ff;
                            color: #4361ee;
                            font-weight: 600;
                        }
                        .order-header {
                            text-align: center;
                            margin-bottom: 30px;
                        }
                        .order-title {
                            font-size: 24px;
                            font-weight: 700;
                            color: #4361ee;
                            margin-bottom: 10px;
                        }
                        .order-subtitle {
                            color: #64748b;
                            font-size: 16px;
                        }
                        .order-date {
                            color: #64748b;
                            font-size: 14px;
                            margin-top: 10px;
                        }
                        .order-summary {
                            margin-top: 30px;
                            padding-top: 20px;
                            border-top: 2px solid #e2e8f0;
                        }
                        .summary-row {
                            display: flex;
                            justify-content: space-between;
                            padding: 10px 0;
                            font-size: 15px;
                        }
                        .summary-row.total {
                            padding: 15px 0;
                            margin-top: 10px;
                            border-top: 2px solid #4361ee;
                            font-weight: 700;
                            font-size: 16px;
                            color: #4361ee;
                        }
                        .signature-section {
                            margin-top: 60px;
                            padding-top: 30px;
                            border-top: 2px solid #e2e8f0;
                            display: flex;
                            justify-content: space-between;
                        }
                        .signature-box {
                            text-align: center;
                            width: 30%;
                        }
                        .signature-line {
                            height: 60px;
                            margin-bottom: 10px;
                        }
                        .signature-label {
                            border-top: 2px solid #1e293b;
                            font-size: 13px;
                            padding-top: 5px;
                        }
                    </style>
                </head>
                <body>
                    ${printContent.innerHTML}
                </body>
            </html>
        `);
        
        windowToOpen.document.close();
        setTimeout(() => {
            windowToOpen.print();
        }, 250);
    }

    function openDuplicateModal() {
        const selectedCheckboxes = document.querySelectorAll('.product-checkbox.checked');
        const selectedProducts = Array.from(selectedCheckboxes).map(cb => {
            const row = cb.closest('tr');
            return {
                id: row.dataset.productId,
                name: row.querySelector('.product-name').textContent
            };
        });

        const container = document.querySelector('.selected-products-container');
        const hiddenInputs = document.getElementById('hiddenInputs');
        
        container.innerHTML = '';
        hiddenInputs.innerHTML = '';

        if (selectedProducts.length === 0) {
            container.innerHTML = '<p class="text-muted text-center py-3">No products selected</p>';
            return;
        }

        selectedProducts.forEach(product => {
            container.innerHTML += `
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                    <span class="text-truncate" style="max-width: 70%;">${product.name}</span>
                    <span class="badge bg-light text-dark">ID: ${product.id}</span>
                </div>
            `;

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'product_ids[]';
            hiddenInput.value = product.id;
            hiddenInputs.appendChild(hiddenInput);
        });

        const modal = new bootstrap.Modal(document.getElementById('duplicateModal'));
        modal.show();
    }

    function duplicateProducts() {
        const form = document.getElementById('duplicateForm');
        const targetAccount = document.getElementById('targetAccount').value;
        const confirmBtn = document.getElementById('confirmDuplicateBtn');
        const btnText = document.getElementById('duplicateBtnText');
        const loading = document.getElementById('duplicateLoading');

        if (!targetAccount) {
            alert('Please select a target shop.');
            return;
        }

        // Show loading state
        btnText.style.display = 'none';
        loading.style.display = 'inline';

        // Disable button
        confirmBtn.disabled = true;

        // Submit form
        setTimeout(() => {
            form.submit();
        }, 1000);
    }

    function downloadReport() {
        window.location.href = "{{ route('admin.product.report.export') }}";
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Reset modal when closed
    document.getElementById('duplicateModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('targetAccount').value = '';
        document.getElementById('includeStock').checked = true;
        document.getElementById('includePricing').checked = true;
        document.querySelector('.selected-products-container').innerHTML = '';
        
        const confirmBtn = document.getElementById('confirmDuplicateBtn');
        const btnText = document.getElementById('duplicateBtnText');
        const loading = document.getElementById('duplicateLoading');
        
        btnText.style.display = 'inline';
        loading.style.display = 'none';
        confirmBtn.disabled = false;
    });

    document.getElementById('printModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('printModalBody').innerHTML = '';
    });
</script>
</body>
</html>