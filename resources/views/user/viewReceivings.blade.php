<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Receivings</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:       #0B1E3D;
            --navy-mid:   #112952;
            --navy-light: #1A3A6B;
            --amber:      #F59E0B;
            --amber-pale: #FEF3C7;
            --emerald:    #059669;
            --emerald-pale: #D1FAE5;
            --rose:       #E11D48;
            --rose-pale:  #FFE4E6;
            --slate-100:  #F1F5F9;
            --slate-200:  #E2E8F0;
            --slate-400:  #94A3B8;
            --slate-600:  #475569;
            --slate-800:  #1E293B;
            --white:      #FFFFFF;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #F0F4FA;
            color: var(--slate-800);
            min-height: 100vh;
        }

        /* ── Layout ── */
        .main-wrap {
            max-width: 1800px;
            margin: 0 auto;
            padding: 1.25rem 1.5rem;
        }

        /* ── Page header ── */
        .pg-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            background: var(--navy);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.25);
        }

        .pg-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--white);
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .pg-title-icon {
            width: 40px;
            height: 40px;
            background: rgba(245,158,11,0.2);
            border: 1px solid rgba(245,158,11,0.35);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--amber);
            font-size: 1.1rem;
        }

        .pg-title span { color: var(--amber); }

        .btn-new-receiving {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--amber);
            color: var(--navy);
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.55rem 1.1rem;
            border-radius: 8px;
            border: none;
            text-decoration: none;
            transition: background 0.18s, transform 0.15s, box-shadow 0.18s;
            box-shadow: 0 2px 8px rgba(245,158,11,0.35);
        }
        .btn-new-receiving:hover {
            background: #FBBF24;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(245,158,11,0.4);
            color: var(--navy);
        }

        /* ── Alerts ── */
        .alert-custom {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.75rem 1.1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1rem;
            animation: slideDown 0.3s ease;
        }
        .alert-success-custom {
            background: var(--emerald-pale);
            border-left: 4px solid var(--emerald);
            color: #065F46;
        }
        .alert-danger-custom {
            background: var(--rose-pale);
            border-left: 4px solid var(--rose);
            color: #9F1239;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Card ── */
        .card-panel {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(11,30,61,0.08);
            overflow: hidden;
        }

        /* ── Toolbar ── */
        .toolbar {
            background: var(--navy);
            padding: 0.9rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .toolbar-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .toolbar-label {
            color: rgba(255,255,255,0.75);
            font-size: 0.82rem;
            font-weight: 500;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .toolbar-label i { color: var(--amber); }

        /* ── Toolbar action buttons ── */
        .tbtn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.42rem 0.9rem;
            border-radius: 7px;
            border: none;
            cursor: pointer;
            transition: filter 0.15s, transform 0.12s;
            white-space: nowrap;
            text-decoration: none;
        }
        .tbtn:hover { filter: brightness(1.1); transform: translateY(-1px); }
        .tbtn:active { transform: translateY(0); }

        .tbtn-success  { background: var(--emerald); color: #fff; }
        .tbtn-primary  { background: var(--navy-light); color: #fff; border: 1px solid rgba(255,255,255,0.12); }
        .tbtn-warning  { background: var(--amber); color: var(--navy); }
        .tbtn-danger   { background: var(--rose); color: #fff; }
        .tbtn-ghost    {
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.85);
            border: 1px solid rgba(255,255,255,0.15);
        }
        .tbtn-ghost:hover { background: rgba(255,255,255,0.16); }

        /* ── Filter bar ── */
        .filter-bar {
            background: var(--slate-100);
            border-bottom: 1px solid var(--slate-200);
            padding: 0.8rem 1.25rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .filter-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--slate-600);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
        }

        .filter-input {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.825rem;
            padding: 0.38rem 0.65rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            transition: border-color 0.18s, box-shadow 0.18s;
            outline: none;
            min-width: 0;
        }
        .filter-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }
        select.filter-input { cursor: pointer; }

        /* ── Summary bar ── */
        .summary-bar {
            display: flex;
            align-items: center;
            gap: 2rem;
            padding: 0.7rem 1.25rem;
            background: linear-gradient(90deg, #EBF0FA 0%, #F1F5F9 100%);
            border-bottom: 1px solid var(--slate-200);
            flex-wrap: wrap;
        }

        .summary-stat {
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
        }

        .summary-stat-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-stat-value {
            font-family: 'DM Mono', monospace;
            font-size: 1rem;
            font-weight: 500;
            color: var(--navy);
        }

        .summary-stat-value.amber { color: var(--amber); }
        .summary-stat-value.emerald { color: var(--emerald); }

        .summary-divider {
            width: 1px;
            height: 32px;
            background: var(--slate-200);
        }

        /* ── Table ── */
        .table-scroll {
            overflow-x: auto;
            overflow-y: auto;
            max-height: calc(100vh - 360px);
        }

        table.recv-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.845rem;
        }

        table.recv-table thead th {
            background: var(--slate-100);
            color: var(--slate-600);
            font-size: 0.73rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.65rem 0.85rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 5;
        }

        table.recv-table tbody td {
            padding: 0.6rem 0.85rem;
            border-bottom: 1px solid var(--slate-200);
            vertical-align: middle;
            color: var(--slate-800);
        }

        table.recv-table tbody tr {
            transition: background 0.12s;
        }
        table.recv-table tbody tr:hover td {
            background: #EFF4FF;
        }

        table.recv-table tbody tr.row-approved td {
            background: #F0FDF4;
        }
        table.recv-table tbody tr.row-approved:hover td {
            background: #DCFCE7;
        }

        /* ── Mono numbers ── */
        .mono {
            font-family: 'DM Mono', monospace;
            font-size: 0.825rem;
        }

        .amount { font-weight: 500; color: var(--slate-800); }
        .amount-total { font-weight: 500; color: var(--navy); }

        .currency-prefix {
            font-size: 0.7rem;
            color: var(--slate-400);
            margin-right: 1px;
        }

        /* ── Product name ── */
        .product-name {
            font-weight: 600;
            color: var(--navy);
        }

        /* ── Badges ── */
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            letter-spacing: 0.02em;
        }
        .badge-pill::before {
            content: '';
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
            opacity: 0.7;
        }

        .badge-cash    { background: var(--emerald-pale); color: #065F46; }
        .badge-credit  { background: var(--amber-pale); color: #92400E; }
        .badge-approved { background: var(--emerald-pale); color: #065F46; }
        .badge-pending  { background: var(--slate-100); color: var(--slate-600); border: 1px solid var(--slate-200); }
        .badge-returned { background: #EDE9FE; color: #5B21B6; }

        /* ── Supplier / Allocated ── */
        .person-cell {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: var(--slate-600);
            font-size: 0.825rem;
        }

        .person-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--slate-200);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--slate-600);
            flex-shrink: 0;
            text-transform: uppercase;
        }

        /* ── Row index ── */
        .row-index {
            font-family: 'DM Mono', monospace;
            font-size: 0.75rem;
            color: var(--slate-400);
            font-weight: 500;
        }

        /* ── Action buttons ── */
        .action-wrap {
            display: flex;
            gap: 0.35rem;
            align-items: center;
        }

        .act-btn {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            cursor: pointer;
            transition: filter 0.15s, transform 0.12s;
            flex-shrink: 0;
        }
        .act-btn:hover { filter: brightness(0.9); transform: scale(1.08); }
        .act-delete  { background: var(--rose-pale);  color: var(--rose); }
        .act-approve { background: var(--emerald-pale); color: var(--emerald); }
        .act-undo    { background: var(--amber-pale);  color: #D97706; }

        /* ── Checkbox ── */
        .form-check-input {
            width: 15px;
            height: 15px;
            cursor: pointer;
            border: 1.5px solid var(--slate-400);
            border-radius: 4px;
        }
        .form-check-input:checked {
            background-color: var(--navy-light);
            border-color: var(--navy-light);
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 3.5rem 1rem;
            color: var(--slate-400);
        }
        .empty-state i { font-size: 2.5rem; margin-bottom: 0.75rem; display: block; }
        .empty-state p { font-size: 0.9rem; margin: 0; }

        /* ── Date cell ── */
        .date-cell {
            font-family: 'DM Mono', monospace;
            font-size: 0.78rem;
            color: var(--slate-600);
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

        /* ── Collapsed state placeholder ── */
        .collapsed-hint {
            padding: 2rem 1.25rem;
            text-align: center;
            color: var(--slate-400);
            font-size: 0.875rem;
        }
        .collapsed-hint i { font-size: 1.5rem; display: block; margin-bottom: 0.5rem; opacity: 0.5; }
    </style>
</head>
<body>
    <div class="row">
        @include('user/sidenav')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="main-wrap">

                {{-- ── Page Header ── --}}
                <div class="pg-header">
                    <div class="pg-title">
                        <div class="pg-title-icon"><i class="bi bi-box-seam-fill"></i></div>
                        View <span>Receivings</span>
                    </div>
                    <a href="{{ url('admin/make-receiving') }}" class="btn-new-receiving">
                        <i class="bi bi-plus-circle-fill"></i> New Receiving
                    </a>
                </div>

                {{-- ── Alerts ── --}}
                @if(session('success'))
                <div class="alert-custom alert-success-custom">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="alert-custom alert-danger-custom">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ session('error') }}
                </div>
                @endif

                {{-- ── Main Card ── --}}
                <div class="card-panel">

                    {{-- Toolbar --}}
                    <div class="toolbar">
                        <div class="toolbar-left">
                            <button type="button" class="tbtn tbtn-ghost" onclick="toggleTableData()" id="toggleBtn">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                                <span id="toggleText">Show</span>
                            </button>
                            <div class="toolbar-label">
                                <i class="bi bi-receipt"></i>
                                Receivings List — Non-Returns Only
                            </div>
                        </div>
                        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center;">
                            <form method="POST" action="{{ route('admin.approve-all-receivings') }}">
                                @csrf
                                <input type="hidden" name="date" value="{{ $selectedDate }}">
                                <input type="hidden" name="shop" value="{{ $shopFilter ?? '' }}">
                                <button type="submit" class="tbtn tbtn-success" onclick="return confirm('Approve all pending receivings?')">
                                    <i class="bi bi-check-all"></i> Approve All
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.approve-selected-receivings') }}" id="approveSelectedForm">
                                @csrf
                                <input type="hidden" name="shop" value="{{ $shopFilter ?? '' }}">
                                <button type="submit" class="tbtn tbtn-primary" onclick="return confirm('Approve selected receivings?')">
                                    <i class="bi bi-check-circle"></i> Approve Selected
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.undo-receivings') }}" id="undoForm">
                                @csrf
                                <input type="hidden" name="date" value="{{ $selectedDate }}">
                                <button type="submit" class="tbtn tbtn-warning" onclick="return confirm('Undo all approved receivings?')">
                                    <i class="bi bi-arrow-counterclockwise"></i> Undo
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.delete-selected-receivings') }}" id="deleteSelectedForm">
                                @csrf
                                <button type="submit" class="tbtn tbtn-danger" onclick="return confirm('Delete selected receivings? Only pending receivings can be deleted.')">
                                    <i class="bi bi-trash3"></i> Delete Selected
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Filter Bar --}}
                    <div class="filter-bar">
                        <form method="GET" action="{{ url('admin/view-receivings') }}" style="display:contents;">
                            <div class="filter-group">
                                <span class="filter-label">Shop</span>
                                <select name="shop" class="filter-input" id="shop" onchange="this.form.submit()">
                                    <option value="" {{ empty($shopFilter) ? 'selected' : '' }}>All Shops</option>
                                    @foreach($shops as $shop)
                                        <option value="{{ $shop->id }}" {{ $shopFilter == $shop->id ? 'selected' : '' }}>
                                            {{ $shop->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-group">
                                <span class="filter-label">Date</span>
                                <input type="date" name="date" class="filter-input" id="date"
                                    value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}"
                                    onchange="this.form.submit()">
                            </div>
                            <div class="filter-group">
                                <span class="filter-label">From</span>
                                <input type="date" name="from_date" class="filter-input" id="from_date"
                                    value="{{ $fromDate }}" max="{{ date('Y-m-d') }}"
                                    onchange="this.form.submit()">
                            </div>
                            <div class="filter-group">
                                <span class="filter-label">To</span>
                                <input type="date" name="to_date" class="filter-input" id="to_date"
                                    value="{{ $toDate }}" max="{{ date('Y-m-d') }}"
                                    onchange="this.form.submit()">
                            </div>
                            <div class="filter-group">
                                <span class="filter-label">Status</span>
                                <select name="status" class="filter-input" id="status" onchange="this.form.submit()">
                                    <option value="all"      {{ $statusFilter == 'all'      ? 'selected' : '' }}>All</option>
                                    <option value="Pending"  {{ $statusFilter == 'Pending'  ? 'selected' : '' }}>Pending</option>
                                    <option value="Approved" {{ $statusFilter == 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Returned" {{ $statusFilter == 'Returned' ? 'selected' : '' }}>Returned</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    {{-- Summary Bar --}}
                    @php
                        $totalQty   = $products->sum('quantity');
                        $totalValue = $products->sum(fn($p) => ($p->quantity ?? 0) * ($p->price ?? 0));
                    @endphp
                    <div class="summary-bar">
                        <div class="summary-stat">
                            <span class="summary-stat-label">Items</span>
                            <span class="summary-stat-value">{{ number_format($products->count()) }}</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-stat">
                            <span class="summary-stat-label">Total Qty</span>
                            <span class="summary-stat-value amber">{{ number_format($totalQty) }}</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-stat">
                            <span class="summary-stat-label">Total Value</span>
                            <span class="summary-stat-value emerald">Tsh {{ number_format($totalValue, 2) }}</span>
                        </div>
                    </div>

                    {{-- Table Body (toggle) --}}
                    <div id="receivingsBody" style="display:none;">
                        <div class="table-scroll">
                            <table class="recv-table">
                                <thead>
                                    <tr>
                                        <th width="36"><input type="checkbox" id="selectAll" class="form-check-input" onclick="toggleSelectAll()"></th>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Cost</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Supplier</th>
                                        <th>Allocated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $index => $item)
                                    <tr id="row-{{ $item->productId }}" class="{{ $item->status == 'Approved' ? 'row-approved' : '' }}">
                                        <td>
                                            <input type="checkbox" name="product_ids[]" value="{{ $item->productId }}" class="item-checkbox form-check-input">
                                        </td>
                                        <td><span class="row-index">{{ $index + 1 }}</span></td>
                                        <td><span class="date-cell">{{ date('M d, Y', strtotime($item->created_at)) }}</span></td>
                                        <td><span class="product-name">{{ $item->productName }}</span></td>
                                        <td><span class="mono amount">{{ number_format($item->quantity) }}</span></td>
                                        <td>
                                            <span class="mono amount">
                                                <span class="currency-prefix">Tsh</span>{{ number_format($item->price, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="mono amount-total">
                                                <span class="currency-prefix">Tsh</span>{{ number_format($item->price * $item->quantity, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->status == 'Approved')
                                                <span class="badge-pill badge-approved">Approved</span>
                                            @elseif($item->status == 'Returned')
                                                <span class="badge-pill badge-returned">Returned</span>
                                            @else
                                                <span class="badge-pill badge-pending">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->isDebt == 1)
                                                <span class="badge-pill badge-credit"><i class="bi bi-credit-card" style="margin-right:1px;"></i>Credit</span>
                                            @else
                                                <span class="badge-pill badge-cash"><i class="bi bi-cash" style="margin-right:1px;"></i>Cash</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="person-cell">
                                                <div class="person-avatar">{{ substr($item->supplierName ?? 'U', 0, 2) }}</div>
                                                {{ $item->supplierName ?? 'Unknown Supplier' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="person-cell">
                                                <div class="person-avatar" style="background:#E0E7FF;color:#3730A3;">{{ substr($item->servedByName ?? 'U', 0, 2) }}</div>
                                                {{ $item->servedByName ?? 'Unknown' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-wrap">
                                                <form method="post">
                                                    @csrf
                                                    <input type="hidden" value="{{ $item->productId }}" name="product_id">
                                                    <input type="hidden" name="receiving_id" value="{{ $item->id }}">
                                                    <input type="hidden" name="action" value="delete">
                                                    <button formaction="{{ url('admin/dltrestock') }}" class="act-btn act-delete" title="Delete"
                                                        onclick="return confirm('Delete this receiving? Admin can delete any status. Are you sure?')">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>

                                                @if($item->status != 'Approved' && $item->status != 'Returned')
                                                <form method="post">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $item->productId }}">
                                                    <input type="hidden" name="shop" value="{{ $shopFilter ?? '' }}">
                                                    <button formaction="{{ url('admin/restockProd') }}" class="act-btn act-approve" title="Approve">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                                @endif

                                                @if($item->status == 'Approved')
                                                <form method="post">
                                                    @csrf
                                                    <input type="hidden" name="receiving_id" value="{{ $item->id }}">
                                                    <input type="hidden" name="date" value="{{ $selectedDate }}">
                                                    <button formaction="{{ url('admin/undo-receivings') }}" class="act-btn act-undo" title="Undo"
                                                        onclick="return confirm('Undo this receiving? This will only undo this single row.')">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="12">
                                            <div class="empty-state">
                                                <i class="bi bi-inbox"></i>
                                                <p>No receivings found for the selected filters.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Collapsed hint --}}
                    <div id="collapsedHint">
                        <div class="collapsed-hint">
                            <i class="bi bi-table"></i>
                            Click <strong>Show</strong> to expand the receivings table
                        </div>
                    </div>

                </div><!-- /card-panel -->
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
    const STORAGE_KEY = 'receivingsTableVisible';

    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem(STORAGE_KEY);
        updateToggleState(saved === 'true');
    });

    function toggleTableData() {
        const body = document.getElementById('receivingsBody');
        const isHidden = body.style.display === 'none';
        localStorage.setItem(STORAGE_KEY, isHidden);
        updateToggleState(isHidden);
    }

    function updateToggleState(show) {
        const body  = document.getElementById('receivingsBody');
        const hint  = document.getElementById('collapsedHint');
        const icon  = document.getElementById('toggleIcon');
        const text  = document.getElementById('toggleText');

        if (show) {
            body.style.display = '';
            hint.style.display = 'none';
            icon.className = 'bi bi-eye-slash';
            text.textContent = 'Hide';
        } else {
            body.style.display = 'none';
            hint.style.display = '';
            icon.className = 'bi bi-eye';
            text.textContent = 'Show';
        }
    }

    function toggleSelectAll() {
        const all = document.getElementById('selectAll').checked;
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = all);
    }

    function collectSelected(form) {
        form.querySelectorAll('input[name="product_ids[]"]').forEach(i => i.remove());
        document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'product_ids[]';
            inp.value = cb.value;
            form.appendChild(inp);
        });
    }

    document.getElementById('approveSelectedForm').addEventListener('submit', function(e) {
        const checked = document.querySelectorAll('.item-checkbox:checked');
        if (!checked.length) { e.preventDefault(); alert('Select at least one item to approve.'); return; }
        collectSelected(this);
    });

    document.getElementById('deleteSelectedForm').addEventListener('submit', function(e) {
        const checked = document.querySelectorAll('.item-checkbox:checked');
        if (!checked.length) { e.preventDefault(); alert('Select at least one item to delete.'); return; }
        collectSelected(this);
    });
    </script>
</body>
</html>