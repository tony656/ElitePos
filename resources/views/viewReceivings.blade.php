<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.view_receivings')</title>
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

        /* Main layout */
        .main-wrap {
            max-width: 1800px;
            margin: 0 auto;
            padding: 1.25rem 1.5rem;
        }

        /* Page header */
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
            text-decoration: none;
            transition: all 0.18s;
            box-shadow: 0 2px 8px rgba(245,158,11,0.35);
        }
        .btn-new-receiving:hover {
            background: #FBBF24;
            transform: translateY(-1px);
            color: var(--navy);
        }

        /* Alerts */
        .alert-custom {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.75rem 1.1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1rem;
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

        /* Card panel */
        .card-panel {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(11,30,61,0.08);
            overflow: hidden;
        }

        /* Toolbar */
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
            flex-wrap: wrap;
        }

        .toolbar-label {
            color: rgba(255,255,255,0.75);
            font-size: 0.82rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

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
            transition: all 0.15s;
            text-decoration: none;
        }
        .tbtn:hover { filter: brightness(1.1); transform: translateY(-1px); }

        .tbtn-success  { background: var(--emerald); color: #fff; }
        .tbtn-primary  { background: var(--navy-light); color: #fff; border: 1px solid rgba(255,255,255,0.12); }
        .tbtn-warning  { background: var(--amber); color: var(--navy); }
        .tbtn-danger   { background: var(--rose); color: #fff; }
        .tbtn-info     { background: #0284C7; color: #fff; }
        .tbtn-ghost    { background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.85); border: 1px solid rgba(255,255,255,0.15); }

        /* Filter bar */
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
            white-space: nowrap;
        }

        .filter-input {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.825rem;
            padding: 0.38rem 0.65rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            outline: none;
        }
        .filter-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        /* Summary bar */
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
        }

        .summary-stat-value {
            font-family: 'DM Mono', monospace;
            font-size: 1rem;
            font-weight: 500;
            color: var(--navy);
        }

        /* Table */
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
            padding: 0.65rem 0.85rem;
            border-bottom: 2px solid var(--slate-200);
            position: sticky;
            top: 0;
            z-index: 5;
        }

        table.recv-table tbody td {
            padding: 0.6rem 0.85rem;
            border-bottom: 1px solid var(--slate-200);
            vertical-align: middle;
        }

        table.recv-table tbody tr:hover td {
            background: #EFF4FF;
        }

        .product-name { font-weight: 600; color: var(--navy); }
        .mono { font-family: 'DM Mono', monospace; font-size: 0.825rem; }
        .amount-total { font-weight: 500; color: var(--navy); }

        /* Badges */
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
        }
        .badge-cash    { background: var(--emerald-pale); color: #065F46; }
        .badge-credit  { background: var(--amber-pale); color: #92400E; }
        .badge-approved { background: var(--emerald-pale); color: #065F46; }
        .badge-pending  { background: var(--slate-100); color: var(--slate-600); }
        .badge-returned { background: #EDE9FE; color: #5B21B6; }

        .person-cell {
            display: flex;
            align-items: center;
            gap: 0.4rem;
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
        }

        .action-wrap { display: flex; gap: 0.35rem; align-items: center; }
        .act-btn {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.12s;
        }
        .act-btn:hover { filter: brightness(0.9); transform: scale(1.08); }
        .act-delete  { background: var(--rose-pale); color: var(--rose); }
        .act-approve { background: var(--emerald-pale); color: var(--emerald); }
        .act-undo    { background: var(--amber-pale); color: #D97706; }
        .act-print   { background: #E0F2FE; color: #0284C7; }

        .form-check-input {
            width: 15px;
            height: 15px;
            cursor: pointer;
        }

        .empty-state { text-align: center; padding: 3.5rem 1rem; color: var(--slate-400); }
        .empty-state i { font-size: 2.5rem; margin-bottom: 0.75rem; display: block; }

        .collapsed-hint {
            padding: 2rem 1.25rem;
            text-align: center;
            color: var(--slate-400);
        }

        /* PRINT STYLES - Hidden by default, appear only when printing */
        @media print {
            .sidenav, .pg-header, .toolbar, .filter-bar, .summary-bar,
            .act-btn, .action-wrap form, .tbtn, .btn-new-receiving,
            #toggleBtn, .toolbar-left, .collapsed-hint, .alert-custom,
            .pg-header .btn-new-receiving, .filter-bar form,
            .action-wrap .act-print, .action-wrap .act-delete,
            .action-wrap .act-approve, .action-wrap .act-undo,
            .col-md-9, .col-lg-10, .px-md-4, .row, [class*="col-"] {
                display: none !important;
            }

            body, .main-wrap, .card-panel, .receiving-print-area {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            .receiving-print-area {
                display: block !important;
                padding: 20px !important;
            }

            .print-header {
                display: flex !important;
                justify-content: space-between !important;
                margin-bottom: 20px !important;
                border-bottom: 2px solid #0B1E3D !important;
                padding-bottom: 10px !important;
            }

            .print-title {
                font-size: 24px !important;
                font-weight: bold !important;
                color: #0B1E3D !important;
            }

            .print-date {
                font-size: 12px !important;
                color: #666 !important;
            }

            .print-summary {
                margin: 15px 0 !important;
                padding: 10px !important;
                background: #f5f5f5 !important;
                border-radius: 8px !important;
            }

            .print-table {
                width: 100% !important;
                border-collapse: collapse !important;
                margin-top: 15px !important;
            }

            .print-table th, .print-table td {
                border: 1px solid #ddd !important;
                padding: 8px !important;
                text-align: left !important;
            }

            .print-table th {
                background: #f0f0f0 !important;
                font-weight: bold !important;
            }

            .receiving-print-area {
                display: block !important;
            }
        }

        .receiving-print-area {
            display: none;
        }
    </style>
</head>
<body>
        @include('sidenav')

        <main class="main-content">
            <div class="main-wrap">

                {{-- Page Header --}}
                <div class="pg-header">
                    <div class="pg-title">
                        <div class="pg-title-icon"><i class="bi bi-box-seam-fill"></i></div>
                        {{ __('messages.view_receivings_page_header') }}
                    </div>
                    <a href="{{ url('make-receiving') }}" class="btn-new-receiving">
                        <i class="bi bi-plus-circle-fill"></i> {{ __('messages.new_receiving') }}
                    </a>
                </div>

                {{-- Alerts --}}
                @if(session('success'))
                <div class="alert-custom alert-success-custom">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="alert-custom alert-danger-custom">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                </div>
                @endif

                {{-- Main Card --}}
                <div class="card-panel">

                    {{-- Toolbar with Print Button --}}
                    <div class="toolbar">
                        <div class="toolbar-left">
                            <button type="button" class="tbtn tbtn-ghost" onclick="toggleTableData()" id="toggleBtn">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                                <span id="toggleText">Show</span>
                            </button>
                            <div class="toolbar-label">
                                <i class="bi bi-receipt"></i> {{ __('messages.receivings_list') }}
                            </div>
                        </div>
                        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                            <!-- NEW: Print Selected Button -->
                            @if (canUser('print_receiving'))
                            <button type="button" class="tbtn tbtn-info" onclick="printSelectedItems()">
                                <i class="bi bi-printer-fill"></i> Print Selected
                            </button>
                            <!-- NEW: Print All Button -->
                            <button type="button" class="tbtn tbtn-info" onclick="printAllItems()" style="background:#0F5B8C;">
                                <i class="bi bi-printer"></i> Print All
                            </button>
                            @endif
                            @if (canUser('approve_receiving'))
                            <form method="POST" action="{{ route('approve-selected-receivings') }}" id="approveSelectedForm">
                                @csrf
                                <input type="hidden" name="shop" value="{{ $shopFilter }}">
                                <button type="submit" class="tbtn tbtn-primary" onclick="return confirm('Approve selected receivings?')">
                                    <i class="bi bi-check-circle"></i> Approve Selected
                                </button>
                            </form>
                            @endif
                            @if (canUser('undo_receiving'))
                            <form method="POST" action="{{ route('undo-receivings') }}" id="undoForm">
                                @csrf
                                <button type="submit" class="tbtn tbtn-warning" onclick="return confirm('Undo selected receivings?')">
                                    <i class="bi bi-arrow-counterclockwise"></i> Undo
                                </button>
                            </form>
                            @endif
                            @if (canUser('delete_receiving'))
                            <form method="POST" action="{{ route('delete-selected-receivings') }}" id="deleteSelectedForm">
                                @csrf
                                <button type="submit" class="tbtn tbtn-danger" onclick="return confirm('Delete selected receivings?')">
                                    <i class="bi bi-trash3"></i> Delete Selected
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    {{-- Filter Bar --}}
                    <div class="filter-bar">
                        <form method="GET" action="{{ url('view-receivings') }}" style="display:contents;">
                            <div class="filter-group">
                                <span class="filter-label">Shop</span>
                                <select name="shop" class="filter-input" onchange="this.form.submit()">
                                    <option value="" {{ empty($shopFilter) ? 'selected' : '' }}>All Shops</option>
                                    @foreach($shops as $shop)
                                        <option value="{{ $shop['id'] }}" {{ $shopFilter == $shop['id'] ? 'selected' : '' }}>
                                            {{ $shop['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-group">
                                <span class="filter-label">Date</span>
                                <input type="date" name="date" class="filter-input" value="{{ $selectedDate }}" onchange="this.form.submit()">
                            </div>
                            <div class="filter-group">
                                <span class="filter-label">From</span>
                                <input type="date" name="from_date" class="filter-input" value="{{ $fromDate }}" onchange="this.form.submit()">
                            </div>
                            <div class="filter-group">
                                <span class="filter-label">To</span>
                                <input type="date" name="to_date" class="filter-input" value="{{ $toDate }}" onchange="this.form.submit()">
                            </div>
                            <div class="filter-group">
                                <span class="filter-label">Status</span>
                                <select name="status" class="filter-input" onchange="this.form.submit()">
                                    <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>All</option>
                                    <option value="Pending" {{ $statusFilter == 'Pending' ? 'selected' : '' }}>Pending</option>
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
                        $totalReturnValue = $totalReturnValue ?? 0;
                    @endphp
                    <div class="summary-bar">
                        <div class="summary-stat"><span class="summary-stat-label">Items</span><span class="summary-stat-value">{{ number_format($products->count()) }}</span></div>
                        <div class="summary-stat"><span class="summary-stat-label">Total Qty</span><span class="summary-stat-value">{{ number_format($totalQty) }}</span></div>
                        <div class="summary-stat"><span class="summary-stat-label">Total Value</span><span class="summary-stat-value">Tsh {{ number_format($totalValue, 2) }}</span></div>
                        <div class="summary-stat"><span class="summary-stat-label">Return Value</span><span class="summary-stat-value">Tsh {{ number_format($totalReturnValue, 2) }}</span></div>
                    </div>

                    {{-- Table Body --}}
                    <div id="receivingsBody" style="display:none;">
                        <div class="table-scroll">
                            <table class="recv-table" id="receivingsTable">
                                <thead>
                                    <tr>
                                        <th width="36"><input type="checkbox" id="selectAll" class="form-check-input" onclick="toggleSelectAll()"></th>
                                        <th>#</th><th>Date</th><th>Product</th><th>Qty</th><th>Cost</th><th>Total</th>
                                        <th>Status</th><th>Payment</th><th>Supplier</th><th>Allocated</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $index => $item)
                                    <tr data-product-id="{{ $item->productId }}" data-product-name="{{ $item->productName }}" 
                                        data-quantity="{{ $item->quantity }}" data-price="{{ $item->price }}" 
                                        data-status="{{ $item->status }}" data-payment="{{ $item->isDebt == 1 ? 'Credit' : 'Cash' }}"
                                        data-supplier="{{ DB::table('accounts')->where('id', $item->supplier)->value('name') ?? 'Unknown' }}"
                                        data-allocated="{{ DB::table('accounts')->where('id', $item->account)->value('name') ?? 'Unknown' }}"
                                        data-date="{{ date('M d, Y', strtotime($item->created_at)) }}">
                                        <td><input type="checkbox" name="product_ids[]" value="{{ $item->id }}" class="item-checkbox form-check-input"></td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ date('M d, Y', strtotime($item->created_at)) }}</td>
                                        <td><span class="product-name">{{ $item->productName }}</span></td>
                                        <td><span class="mono">{{ number_format($item->quantity) }}</span></td>
                                        <td><span class="mono">Tsh {{ number_format($item->price, 2) }}</span></td>
                                        <td><span class="mono amount-total">Tsh {{ number_format($item->price * $item->quantity, 2) }}</span></td>
                                        <td>
                                            @if($item->status == 'Approved')<span class="badge-pill badge-approved">Approved</span>
                                            @elseif($item->status == 'Returned')<span class="badge-pill badge-returned">Returned</span>
                                            @else<span class="badge-pill badge-pending">Pending</span>@endif
                                         </td>
                                        <td>
                                            @if($item->isDebt == 1)<span class="badge-pill badge-credit">Credit</span>
                                            @else<span class="badge-pill badge-cash">Cash</span>@endif
                                         </td>
                                        <td>
                                            <div class="person-cell"><div class="person-avatar">{{ substr(DB::table('accounts')->where('id', $item->supplier)->value('name') ?? 'U', 0, 2) }}</div>
                                            {{ DB::table('accounts')->where('id', $item->supplier)->value('name') ?? 'Unknown Supplier' }}</div>
                                         </td>
                                        <td>
                                            <div class="person-cell"><div class="person-avatar">{{ substr(DB::table('accounts')->where('id', $item->account)->value('name') ?? 'U', 0, 2) }}</div>
                                            {{ DB::table('accounts')->where('id', $item->account)->value('name') ?? 'Unknown' }}</div>
                                         </td>
                                        <td>
                                            <div class="action-wrap">
                                                <form method="post">
                                                    @csrf
                                                    <input type="hidden" value="{{ $item->id }}" name="product_id">
                                                    @if (canUser('delete_receiving'))
                                                    <button formaction="{{ url('dltrestock') }}" class="act-btn act-delete" onclick="return confirm('Delete?')"><i class="bi bi-trash3"></i>
                                                    </button>                                        

                                                    @endif

                                                </form>
                                                @if($item->status != 'Approved' && $item->status != 'Returned')
                                                <form method="post">
                                                    @csrf
                                                    <input type="hidden" name="receiving_id" value="{{ $item->id }}">
                                                    @if (canUser('approve_receiving'))
                                                    <button formaction="{{ url('restockProd') }}" class="act-btn act-approve"><i class="bi bi-check-lg"></i>
                                                    </button>
                                                    @endif
                                                </form>
                                                @endif
                                                @if (canUser('print_receiving'))
                                                <button type="button" class="act-btn act-print" onclick="printSingleItem(this)" data-id="{{ $item->id }}"><i class="bi bi-printer"></i></button>
                                                @endif
                                            </div>
                                         </td>
                                     </tr>
                                    @empty
                                    <tr><td colspan="12"><div class="empty-state"><i class="bi bi-inbox"></i><p>No receivings found.</p></div></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="collapsedHint"><div class="collapsed-hint"><i class="bi bi-table"></i> Click <strong>Show</strong> to expand the receivings table</div></div>
                </div>
            </div>
        </main>

    {{-- Hidden print area --}}
    <div id="printArea" class="receiving-print-area"></div>

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
        const body = document.getElementById('receivingsBody');
        const hint = document.getElementById('collapsedHint');
        const icon = document.getElementById('toggleIcon');
        const text = document.getElementById('toggleText');
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

    // PRINT FUNCTIONS
    function printSingleItem(btn) {
        const row = btn.closest('tr');
        const items = [getItemDataFromRow(row)];
        generatePrint(items, 'Single Item');
    }

    function printSelectedItems() {
        const selectedRows = document.querySelectorAll('.item-checkbox:checked');
        if (selectedRows.length === 0) {
            alert('Please select at least one item to print.');
            return;
        }
        const items = [];
        selectedRows.forEach(cb => {
            const row = cb.closest('tr');
            items.push(getItemDataFromRow(row));
        });
        generatePrint(items, 'Selected Items');
    }

    function printAllItems() {
        const allRows = document.querySelectorAll('#receivingsTable tbody tr');
        if (allRows.length === 0 || (allRows.length === 1 && allRows[0].querySelector('.empty-state'))) {
            alert('No items to print.');
            return;
        }
        const items = [];
        allRows.forEach(row => {
            if (!row.querySelector('.empty-state')) {
                items.push(getItemDataFromRow(row));
            }
        });
        generatePrint(items, 'All Receivings');
    }

    function getItemDataFromRow(row) {
        return {
            date: row.cells[2]?.innerText || row.querySelector('td:nth-child(3)')?.innerText || '',
            product: row.querySelector('.product-name')?.innerText || row.cells[3]?.innerText || '',
            quantity: row.cells[4]?.innerText || '',
            price: row.cells[5]?.innerText || '',
            total: row.cells[6]?.innerText || '',
            status: row.cells[7]?.innerText?.trim() || '',
            payment: row.cells[8]?.innerText?.trim() || '',
            supplier: row.cells[9]?.innerText?.trim() || '',
            allocated: row.cells[10]?.innerText?.trim() || ''
        };
    }

    function generatePrint(items, title) {
        const totalQty = items.reduce((sum, item) => {
            const qty = parseFloat(item.quantity?.replace(/,/g, '')) || 0;
            return sum + qty;
        }, 0);
        
        const totalValue = items.reduce((sum, item) => {
            const total = parseFloat(item.total?.replace(/[^0-9.-]/g, '')) || 0;
            return sum + total;
        }, 0);

        const shopName = document.querySelector('.filter-bar select[name="shop"]')?.selectedOptions[0]?.innerText || 'All Shops';
        const currentDate = new Date().toLocaleString();

        let html = `
            <div class="print-header">
                <div class="print-title">RECEIVINGS REPORT</div>
                <div class="print-date">Printed: ${currentDate}</div>
            </div>
            <div class="print-summary">
                <strong>Shop:</strong> ${shopName} &nbsp;|&nbsp;
                <strong>Total Items:</strong> ${items.length} &nbsp;|&nbsp;
                <strong>Total Quantity:</strong> ${totalQty} &nbsp;|&nbsp;
                <strong>Total Value:</strong> Tsh ${totalValue.toFixed(2)}
            </div>
            <table class="print-table">
                <thead>
                    <tr><th>#</th><th>Date</th><th>Product</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr>
                </thead>
                <tbody>
        `;

        items.forEach((item, idx) => {
            html += `<tr>
                        <td>${idx + 1}</td>
                        <td>${item.date}</td>
                        <td>${item.product}</td>
                        <td>${item.quantity}</td>
                        <td>${item.price}</td>
                        <td>${item.total}</td>
                     </tr>`;
        });

        html += `
                </tbody>
            </table>
            <div style="margin-top:20px; text-align:center; font-size:10px; color:#999;">
                Generated from Receiving System | ${new Date().toLocaleDateString()}
            </div>
        `;

        const printArea = document.getElementById('printArea');
        printArea.innerHTML = html;
        printArea.style.display = 'block';
        
        window.print();
        
        // Reset after print
        setTimeout(() => {
            printArea.style.display = 'none';
            printArea.innerHTML = '';
        }, 500);
    }

    // Form submission handlers
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

    document.getElementById('approveSelectedForm')?.addEventListener('submit', function(e) {
        if (!document.querySelectorAll('.item-checkbox:checked').length) { e.preventDefault(); alert('Select at least one item.'); }
        else collectSelected(this);
    });
    document.getElementById('undoForm')?.addEventListener('submit', function(e) {
        if (!document.querySelectorAll('.item-checkbox:checked').length) { e.preventDefault(); alert('Select at least one item.'); }
        else collectSelected(this);
    });
    document.getElementById('deleteSelectedForm')?.addEventListener('submit', function(e) {
        if (!document.querySelectorAll('.item-checkbox:checked').length) { e.preventDefault(); alert('Select at least one item.'); }
        else collectSelected(this);
    });
    </script>
    @include('footer')

</body>
</html>