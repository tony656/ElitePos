<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Debtors Management</title>
    @include("links")
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #eef2ff;
            --secondary-color: #3a0ca3;
            --success-color: #2ec4b6;
            --warning-color: #ff9f1c;
            --danger-color: #e63946;
            --danger-light: #ffe5e9;
            --info-color: #4895ef;
            --dark-color: #1a1a2e;
            --light-color: #f8f9fa;
            --border-color: #e9ecef;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #334155;
            line-height: 1.6;
        }
        
        /* Header Styles */
        .dashboard-header {
            background: white;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .header-icon {
            width: 48px;
            height: 48px;
            background: var(--primary-light);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.5rem;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        
        .stat-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
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
        }
        
        .stat-card.total::before { background: var(--primary-color); }
        .stat-card.pending::before { background: var(--warning-color); }
        .stat-card.completed::before { background: var(--success-color); }
        .stat-card.debt::before { background: var(--danger-color); }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        
        .stat-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .stat-info h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0.5rem 0;
            color: var(--dark-color);
        }
        
        .stat-label {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-icon.total { background: var(--primary-light); color: var(--primary-color); }
        .stat-icon.pending { background: #fff7e6; color: var(--warning-color); }
        .stat-icon.completed { background: #e6f7f5; color: var(--success-color); }
        .stat-icon.debt { background: var(--danger-light); color: var(--danger-color); }
        
        /* Search Section */
        .search-section {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
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
            border-radius: var(--radius-md);
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
            color: #94a3b8;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.75rem;
        }
        
        /* Table Styles */
        .table-container {
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-md);
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
            color: var(--dark-color);
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
            color: #64748b;
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
        
        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.8125rem;
            font-weight: 500;
            gap: 0.375rem;
        }
        
        .status-badge i {
            font-size: 0.75rem;
        }
        
        .status-pending {
            background: #fff7e6;
            color: #f59e0b;
            border: 1px solid #fed7aa;
        }
        
        .status-completed {
            background: #d1fae5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }
        
        .status-debt {
            background: var(--danger-light);
            color: var(--danger-color);
            border: 1px solid #fca5a5;
        }
        
        .status-partial {
            background: #fef3c7;
            color: #d97706;
            border: 1px solid #fcd34d;
        }
        
        /* Action Buttons */
        .action-buttons-cell {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius-md);
            font-weight: 500;
            font-size: 0.875rem;
            border: 2px solid transparent;
            transition: all 0.2s ease;
            cursor: pointer;
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
            color: var(--dark-color);
        }
        
        .btn-outline:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: var(--primary-light);
        }
        
        .btn-success {
            background: var(--success-color);
            color: white;
        }
        
        .btn-success:hover {
            background: #26a69a;
            transform: translateY(-2px);
        }
        
        .btn-view {
            background: #f0f9ff;
            color: #0369a1;
            border-color: #bae6fd;
        }
        
        .btn-view:hover {
            background: #e0f2fe;
            color: #075985;
        }
        
        /* Order ID Styling */
        .order-id {
            font-family: 'SF Mono', Monaco, 'Courier New', monospace;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .customer-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .customer-avatar {
            width: 36px;
            height: 36px;
            background: var(--primary-light);
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        /* Empty State */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
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
            color: #94a3b8;
            font-size: 2rem;
        }
        
        .empty-state-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #475569;
        }
        
        .empty-state-description {
            color: #64748b;
            max-width: 400px;
            margin: 0 auto 1.5rem;
        }
        
        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
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
        
        .form-control {
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        /* Amount Styling */
        .amount {
            font-weight: 600;
            font-family: 'SF Mono', Monaco, 'Courier New', monospace;
        }
        
        .amount.total {
            color: var(--dark-color);
        }
        
        .amount.paid {
            color: var(--success-color);
        }
        
        .amount.remaining {
            color: var(--danger-color);
        }
        
        /* Responsive Design */
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
            
            .action-buttons {
                width: 100%;
                flex-wrap: wrap;
            }
            
            .btn {
                flex: 1;
                justify-content: center;
            }
            
            .search-section {
                padding: 1rem;
            }
        }
        
        /* Custom Scrollbar */
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
            @include("user/sidenav")
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Header -->
                <div class="dashboard-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="header-title">
                            <div class="header-icon">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <div>
                                <h1 class="h4 mb-1 fw-bold">Invoices Management</h1>
                                <p class="text-muted mb-0">Manage customer orders and payments</p>
                            </div>
                        </div>
                        <div class="action-buttons">
                            <button class="btn btn-outline" onclick="printReport()">
                                <i class="bi bi-printer"></i> Print
                            </button>
                            <button class="btn btn-outline" onclick="exportData()">
                                <i class="bi bi-download"></i> Export
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    @php
                        $totalDebt = 0;
                        $pendingOrders = 0;
                        $completedOrders = 0;
                        foreach($orders as $order) {
                            $totalDebt += $order->credit;
                            if($order->status == 'Debt' || $order->status == 'Partial') {
                                $pendingOrders++;
                            } else {
                                $completedOrders++;
                            }
                        }
                    @endphp
                    
                    <div class="stat-card total">
                        <div class="stat-content">
                            <div class="stat-info">
                                <div class="stat-label">Total Orders</div>
                                <h3>{{ number_format(count($orders)) }}</h3>
                                <span class="text-muted">All time</span>
                            </div>
                            <div class="stat-icon total">
                                <i class="bi bi-receipt"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card debt">
                        <div class="stat-content">
                            <div class="stat-info">
                                <div class="stat-label">Total Debt</div>
                                <h3>Tsh {{ number_format($totalDebt) }}</h3>
                                <span class="text-muted">Outstanding</span>
                            </div>
                            <div class="stat-icon debt">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card pending">
                        <div class="stat-content">
                            <div class="stat-info">
                                <div class="stat-label">Pending Orders</div>
                                <h3>{{ number_format($pendingOrders) }}</h3>
                                <span class="text-muted">Awaiting payment</span>
                            </div>
                            <div class="stat-icon pending">
                                <i class="bi bi-clock-history"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card completed">
                        <div class="stat-content">
                            <div class="stat-info">
                                <div class="stat-label">Completed Orders</div>
                                <h3>{{ number_format($completedOrders) }}</h3>
                                <span class="text-muted">Paid in full</span>
                            </div>
                            <div class="stat-icon completed">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Section -->
                <div class="search-section">
                    <div class="d-flex flex-column flex-lg-row gap-3">
                        <div class="search-container">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" 
                                   class="search-input" 
                                   id="debtor-search" 
                                   placeholder="Search orders by customer name, order ID, or status..."
                                   onkeyup="searchOrders()">
                        </div>
                        <div class="action-buttons">
                            <button class="btn btn-primary" onclick="showFilters()">
                                <i class="bi bi-funnel-fill"></i> Filter Orders
                            </button>
                            <button class="btn btn-outline" onclick="clearFilters()">
                                <i class="bi bi-x-circle"></i> Clear
                            </button>
                        </div>
                    </div>
                    <div id="search-results" style="display: none;"></div>
                </div>

                <!-- Orders Table -->
                <div class="table-container">
                    <div class="table-header">
                        <h2 class="table-title">Unpaid Invoice</h2>
                        <div class="table-actions">
                            <span class="text-muted">
                                {{ count($orders) }} order{{ count($orders) !== 1 ? 's' : '' }} found
                            </span>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>DATE</th>
                                    <th>CUSTOMER</th>
                                    <th>STATUS</th>
                                    <th>TOTAL AMOUNT</th>
                                    
                                    <th class="text-end">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($orders->isEmpty())
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="bi bi-inboxes"></i>
                                            </div>
                                            <h3 class="empty-state-title">No Orders Found</h3>
                                            <p class="empty-state-description">
                                                There are currently no orders in the system. 
                                                Start by creating a new order.
                                            </p>
                                            <button class="btn btn-primary">
                                                <i class="bi bi-plus-lg"></i> Create New Order
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @else
                                @foreach ($orders as $index => $order)
                                @php
                                    $statusClass = '';
                                    $statusIcon = '';
                                    if($order->status == 'Debt') {
                                        $statusClass = 'status-debt';
                                        $statusIcon = 'bi-exclamation-triangle';
                                    } elseif($order->status == 'Partial') {
                                        $statusClass = 'status-partial';
                                        $statusIcon = 'bi-hourglass-split';
                                    } else {
                                        $statusClass = 'status-completed';
                                        $statusIcon = 'bi-check-circle';
                                    }
                                    
                                    // Get customer initials
                                    $initials = '';
                                    $names = explode(' ', $order->cName);
                                    foreach($names as $name) {
                                        $initials .= strtoupper(substr($name, 0, 1));
                                        if(strlen($initials) >= 2) break;
                                    }
                                @endphp
                                <tr>
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                     <td>
                                        <div class="text-muted">
                                            {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                                        </div>
                                        <div class="small text-muted">
                                            {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}
                                        </div>
                                    </td>
                                  
                                    <td>
                                        <div class="customer-info">
                                            <div class="customer-avatar">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $order->cName }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $statusClass }}">
                                            <i class="bi {{ $statusIcon }}"></i>
                                            {{ $order->status ?? 'In Progress' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="amount total">Tsh {{ number_format($order->credit) }}</div>
                                    </td>
                                   
                                    <td>
                                        <div class="action-buttons-cell">
                                            <form action="viewOrder" method="post" class="d-inline">
                                                @csrf
                                                <button class="btn btn-view" 
                                                        name="customerId" 
                                                        value="{{ $order->cPhone }}">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                            </form>
                                            <!--@if($order->status == 'Debt' || $order->status == 'Partial')
                                            <button class="btn btn-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#paymentModal"
                                                    data-orderid="{{ $order->orderName }}">
                                                <i class="bi bi-credit-card"></i> Pay
                                            </button>
                                            @endif-->
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--Paid lists -->
                 <div class="table-container">
                    <div class="table-header">
                        <h2 class="table-title">Paid Invoice</h2>
                        <div class="table-actions">
                            <span class="text-muted">
                                {{ count($orders) }} order{{ count($orders) !== 1 ? 's' : '' }} found
                            </span>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>DATE</th>
                                    <th>CUSTOMER</th>
                                    <th>STATUS</th>
                                    <th>TOTAL AMOUNT</th>
                                    
                                    <th class="text-end">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($paid->isEmpty())
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="bi bi-inboxes"></i>
                                            </div>
                                            <h3 class="empty-state-title">No Orders Found</h3>
                                            <p class="empty-state-description">
                                                There are currently no orders in the system. 
                                                Start by creating a new order.
                                            </p>
                                            <button class="btn btn-primary">
                                                <i class="bi bi-plus-lg"></i> Create New Order
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @else
                                @foreach ($paid as $index => $order)
                                @php
                                    $statusClass = '';
                                    $statusIcon = '';
                                    if($order->status == 'Debt') {
                                        $statusClass = 'status-debt';
                                        $statusIcon = 'bi-exclamation-triangle';
                                    } elseif($order->status == 'Partial') {
                                        $statusClass = 'status-partial';
                                        $statusIcon = 'bi-hourglass-split';
                                    } else {
                                        $statusClass = 'status-completed';
                                        $statusIcon = 'bi-check-circle';
                                    }
                                    
                                    // Get customer initials
                                    $initials = '';
                                    $names = explode(' ', $order->cName);
                                    foreach($names as $name) {
                                        $initials .= strtoupper(substr($name, 0, 1));
                                        if(strlen($initials) >= 2) break;
                                    }
                                @endphp
                                <tr>
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                     <td>
                                        <div class="text-muted">
                                            {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                                        </div>
                                        <div class="small text-muted">
                                            {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}
                                        </div>
                                    </td>
                                  
                                    <td>
                                        <div class="customer-info">
                                            <div class="customer-avatar">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $order->cName }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $statusClass }}">
                                            <i class="bi {{ $statusIcon }}"></i>
                                            {{ $order->status}}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="amount total">Tsh {{ number_format($order->credit) }}</div>
                                    </td>
                                   
                                    <td>
                                        <div class="action-buttons-cell">
                                            <form action="viewOrder" method="post" class="d-inline">
                                                @csrf
                                                <button class="btn btn-view" 
                                                        name="customerId" 
                                                        value="{{ $order->cPhone }}">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                            </form>
                                            <!--@if($order->status == 'Debt' || $order->status == 'Partial')
                                            <button class="btn btn-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#paymentModal"
                                                    data-orderid="{{ $order->orderName }}">
                                                <i class="bi bi-credit-card"></i> Pay
                                            </button>
                                            @endif-->
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-credit-card me-2"></i>Process Payment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="paymentForm" method="post" action="processPayment">
                    @csrf
                    <input type="hidden" name="orderId" id="modalOrderName">
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="paymentAmount" class="form-label fw-semibold mb-2">
                                Payment Amount
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Tsh</span>
                                <input type="number" 
                                       class="form-control" 
                                       id="paymentAmount" 
                                       name="paymentAmount" 
                                       min="0" 
                                       step="0.01" 
                                       placeholder="Enter amount"
                                       required>
                            </div>
                            <div class="form-text mt-2">
                                Enter the amount being paid for this order.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Process Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        function searchOrders() {
            const searchTerm = document.getElementById('debtor-search').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                if (row.querySelector('.empty-state')) return;
                
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Update count
            const countElement = document.querySelector('.table-actions span');
            if (countElement) {
                countElement.textContent = `${visibleCount} order${visibleCount !== 1 ? 's' : ''} found`;
            }
        }
        
        // Modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const paymentModal = document.getElementById('paymentModal');
            if (paymentModal) {
                paymentModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const orderId = button.getAttribute('data-orderid');
                    const modalOrderInput = paymentModal.querySelector('#modalOrderName');
                    if (modalOrderInput) {
                        modalOrderInput.value = orderId;
                    }
                });
            }
        });
        
        // Export and Print functions
        function exportData() {
            alert('Export functionality would be implemented here');
            // In production, this would trigger a file download
        }
        
        function printReport() {
            window.print();
        }
        
        function showFilters() {
            alert('Filter functionality would open a filter panel here');
        }
        
        function clearFilters() {
            document.getElementById('debtor-search').value = '';
            searchOrders();
        }
        
        // Format dates on load
        document.addEventListener('DOMContentLoaded', function() {
            const dateCells = document.querySelectorAll('.date-cell');
            dateCells.forEach(cell => {
                const dateText = cell.textContent;
                if (dateText) {
                    const date = new Date(dateText);
                    cell.textContent = date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            });
        });
    </script>
</body>
</html>