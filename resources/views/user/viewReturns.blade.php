<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} - View Returns</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --navy:          #0B1E3D;
            --navy-mid:      #112952;
            --navy-light:    #1A3A6B;
            --amber:         #F59E0B;
            --amber-pale:    #FEF3C7;
            --emerald:       #059669;
            --emerald-pale:  #D1FAE5;
            --rose:          #E11D48;
            --rose-pale:     #FFE4E6;
            --violet:        #7C3AED;
            --violet-pale:   #EDE9FE;
            --sky:           #0284C7;
            --sky-pale:      #E0F2FE;
            --slate-50:      #F8FAFC;
            --slate-100:     #F1F5F9;
            --slate-200:     #E2E8F0;
            --slate-300:     #CBD5E1;
            --slate-400:     #94A3B8;
            --slate-500:     #64748B;
            --slate-600:     #475569;
            --slate-700:     #334155;
            --slate-800:     #1E293B;
            --white:         #FFFFFF;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background: #EEF2F9;
            color: var(--slate-800);
            min-height: 100vh;
            line-height: 1.6;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        /* ── Main wrap ── */
        .main-wrap { max-width: 1900px; margin: 0 auto; padding: 1.25rem 1.5rem; }

        /* ── Page header ── */
        .pg-header {
            background: var(--navy);
            border-radius: 12px;
            padding: 1rem 1.4rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.28);
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
        }

        .pg-title {
            color: var(--white); font-size: 1.35rem; font-weight: 700;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .pg-title i { color: var(--violet); font-size: 1.25rem; }

        .hbtn-primary {
            display: inline-flex; align-items: center; gap: 0.45rem;
            font-size: 0.875rem; font-weight: 600;
            padding: 0.5rem 1.1rem;
            background: var(--amber); color: var(--navy);
            border: none; border-radius: 8px; cursor: pointer;
            box-shadow: 0 3px 12px rgba(245,158,11,0.3);
            transition: all 0.18s; text-decoration: none;
        }
        .hbtn-primary:hover {
            box-shadow: 0 5px 18px rgba(245,158,11,0.4);
            transform: translateY(-1px);
            color: var(--navy);
        }

        /* ── Alert ── */
        .alert-box {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            border-left: 4px solid;
        }
        .alert-success {
            background: var(--emerald-pale);
            border-color: var(--emerald);
            color: #065F46;
        }
        .alert-error {
            background: var(--rose-pale);
            border-color: var(--rose);
            color: #9F1239;
        }

        /* ── Returns container ── */
        .returns-panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            overflow: hidden;
            height: calc(100vh - 220px);
            display: flex;
            flex-direction: column;
        }

        .panel-head {
            background: var(--violet-pale);
            border-bottom: 1.5px solid var(--slate-200);
            padding: 1.1rem 1.25rem;
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
        }

        .panel-title-row {
            display: flex; align-items: center; gap: 0.75rem;
        }

        .toggle-btn {
            display: inline-flex; align-items: center; gap: 0.35rem;
            font-size: 0.78rem; font-weight: 600;
            padding: 0.4rem 0.8rem;
            border-radius: 7px;
            border: 1.5px solid var(--slate-300);
            background: var(--white);
            color: var(--slate-700);
            cursor: pointer;
            transition: all 0.15s;
        }
        .toggle-btn:hover { background: var(--slate-50); border-color: var(--violet); color: var(--violet); }

        .panel-title {
            font-size: 1.05rem; font-weight: 700; color: var(--violet);
            display: flex; align-items: center; gap: 0.5rem; margin: 0;
        }

        .filter-row {
            display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;
        }
        .filter-row label {
            font-size: 0.78rem; font-weight: 600; color: var(--slate-600);
            margin: 0; white-space: nowrap;
        }
        .filter-row input,
        .filter-row select {
            font-family: 'Outfit', sans-serif;
            font-size: 0.82rem;
            padding: 0.4rem 0.65rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .filter-row input:focus,
        .filter-row select:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }
        .filter-row select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.65rem center;
            padding-right: 2rem;
            appearance: none;
        }

        /* ── Panel body ── */
        .panel-body {
            flex: 1;
            overflow-y: auto;
            display: none;
        }
        .panel-body.visible { display: block; }

        /* ── Summary bar ── */
        .summary-bar {
            padding: 1rem 1.25rem;
            background: var(--slate-50);
            border-bottom: 1.5px solid var(--slate-200);
        }
        .summary-grid {
            display: flex; gap: 2rem; flex-wrap: wrap;
        }
        .summary-item {
            display: flex; flex-direction: column; gap: 0.15rem;
        }
        .summary-label {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.04em; color: var(--slate-400);
        }
        .summary-value {
            font-family: 'DM Mono', monospace;
            font-size: 1.05rem; font-weight: 500; color: var(--navy);
        }
        .summary-note {
            margin-top: 0.75rem;
            font-size: 0.78rem; color: var(--slate-500);
            display: flex; align-items: center; gap: 0.35rem;
        }

        /* ── Table ── */
        .table-wrap { overflow-y: auto; }

        table.returns-tbl { width: 100%; border-collapse: collapse; font-size: 0.845rem; }
        table.returns-tbl thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            padding: 0.65rem 0.8rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        table.returns-tbl tbody td {
            padding: 0.7rem 0.8rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
            color: var(--slate-800);
        }
        table.returns-tbl tbody tr:hover td { background: #F8FAFF; }

        .prod-name { font-weight: 600; color: var(--navy); }
        .qty-val {
            font-family: 'DM Mono', monospace;
            font-weight: 500; color: var(--violet); font-size: 0.82rem;
        }
        .price-val {
            font-family: 'DM Mono', monospace;
            font-weight: 500; color: var(--slate-700); font-size: 0.82rem;
        }
        .total-val {
            font-family: 'DM Mono', monospace;
            font-weight: 600; color: var(--navy); font-size: 0.85rem;
        }

        /* ── Badges ── */
        .pay-badge {
            display: inline-flex; align-items: center;
            font-size: 0.72rem; font-weight: 700;
            padding: 0.25rem 0.55rem; border-radius: 5px;
            text-transform: uppercase; letter-spacing: 0.04em;
        }
        .pay-badge.credit { background: var(--amber-pale); color: #92400E; }
        .pay-badge.cash   { background: var(--emerald-pale); color: #065F46; }

        .status-badge {
            display: inline-flex; align-items: center;
            font-size: 0.72rem; font-weight: 700;
            padding: 0.25rem 0.55rem; border-radius: 5px;
            text-transform: uppercase; letter-spacing: 0.04em;
        }
        .status-badge.pending  { background: var(--amber-pale);   color: #92400E; }
        .status-badge.returned { background: var(--violet-pale);  color: #5B21B6; }
        .status-badge.approved { background: var(--emerald-pale); color: #065F46; }

        /* ── Action buttons ── */
        .act-btn {
            width: 30px; height: 30px;
            border: 1.5px solid;
            border-radius: 6px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all 0.15s;
            background: transparent;
            margin-right: 4px;
        }
        .act-btn-approve {
            border-color: var(--emerald);
            color: var(--emerald);
        }
        .act-btn-approve:hover {
            background: var(--emerald-pale);
            transform: scale(1.08);
        }
        .act-btn-reject {
            border-color: var(--rose);
            color: var(--rose);
        }
        .act-btn-reject:hover {
            background: var(--rose-pale);
            transform: scale(1.08);
        }
        .act-btn-delete {
            border-color: var(--slate-400);
            color: var(--slate-500);
        }
        .act-btn-delete:hover {
            background: var(--slate-100);
            transform: scale(1.08);
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center; padding: 4rem 1.5rem;
            color: var(--slate-400);
        }
        .empty-state i {
            font-size: 4rem; display: block; margin-bottom: 0.75rem; opacity: 0.3;
            color: var(--violet);
        }
        .empty-state-title { font-size: 1.1rem; font-weight: 600; color: var(--slate-600); margin-bottom: 0.4rem; }
        .empty-state p { font-size: 0.875rem; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
            .pg-header { padding: 0.85rem 1.1rem; margin-bottom: 1rem; }
            .pg-title { font-size: 1.15rem; }
            .panel-head { flex-direction: column; align-items: flex-start; }
            .filter-row { width: 100%; }
        }

        /* ── Animation ── */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .returns-panel { animation: slideUp 0.4s ease forwards; }
    </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    @include("user/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
        <div class="main-wrap">

            {{-- ── Header ── --}}
            <div class="pg-header">
                <div class="pg-title">
                    <i class="bi bi-arrow-return-left"></i>
                    View Returns
                </div>
                <a href="{{ url('user/make-return') }}" class="hbtn-primary">
                    <i class="bi bi-plus-circle"></i> New Return
                </a>
            </div>

            {{-- ── Alerts ── --}}
            @if(session('success'))
            <div class="alert-box alert-success">
                <span><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert-box alert-error">
                <span><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- ── Returns Panel ── --}}
            <div class="returns-panel">
                <div class="panel-head">
                    <div class="panel-title-row">
                        <button type="button" class="toggle-btn" onclick="toggleTableData()" id="toggleBtn">
                            <i class="bi bi-plus-lg" id="toggleIcon"></i>
                            <span id="toggleText">Show</span>
                        </button>
                        <div class="panel-title">
                            <i class="bi bi-arrow-return-left"></i>
                            Returns List
                        </div>
                    </div>

                    <form method="GET" action="{{ url('user/view-returns') }}" class="filter-row">
                        <label for="date">Date:</label>
                        <input type="date" name="date" onchange="this.form.submit()" id="date" 
                            value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}">
                        
                        <label for="from_date">From:</label>
                        <input type="date" name="from_date" onchange="this.form.submit()" id="from_date" 
                            value="{{ $fromDate }}" max="{{ date('Y-m-d') }}">
                        
                        <label for="to_date">To:</label>
                        <input type="date" name="to_date" onchange="this.form.submit()" id="to_date" 
                            value="{{ $toDate }}" max="{{ date('Y-m-d') }}">
                        
                        <label for="status">Status:</label>
                        <select name="status" onchange="this.form.submit()" id="status">
                            <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="Pending" {{ $statusFilter == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ $statusFilter == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Returned" {{ $statusFilter == 'Returned' ? 'selected' : '' }}>Returned</option>
                        </select>

                        <label for="shop">Shop:</label>
                        <select name="shop" onchange="this.form.submit()" id="shop">
                            <option value="">All Shops</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ $shopFilter == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="panel-body" id="returnsBody">
                    @php
                        $totalQty = $products->sum('quantity');
                        $totalValue = $products->sum(function($p){ return ($p->quantity ?? 0) * ($p->price ?? 0); });
                    @endphp

                    {{-- Summary Bar --}}
                    <div class="summary-bar">
                        <div class="summary-grid">
                            <div class="summary-item">
                                <div class="summary-label">Total Returns</div>
                                <div class="summary-value">{{ number_format($products->count()) }}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">Total Quantity</div>
                                <div class="summary-value">{{ number_format($totalQty) }}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">Total Value</div>
                                <div class="summary-value">Tsh {{ number_format($totalValue, 2) }}</div>
                            </div>
                        </div>
                        <div class="summary-note">
                            <i class="bi bi-info-circle"></i>
                            Pending returns require admin approval before stock is deducted. Approved/Returned returns have already reduced inventory.
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="table-wrap">
                        <table class="returns-tbl">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Cost</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Supplier</th>
                                    <th>Allocated</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $index => $item)
                                <tr id="row-{{ $item->productId }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ date('M d, Y', strtotime($item->created_at)) }}</td>
                                    <td><div class="prod-name">{{ $item->productName }}</div></td>
                                    <td><span class="qty-val">{{ number_format($item->quantity) }}</span></td>
                                    <td><span class="price-val">Tsh {{ number_format($item->price, 2) }}</span></td>
                                    <td><span class="total-val">Tsh {{ number_format($item->price * $item->quantity, 2) }}</span></td>
                                    <td>
                                        @if($item->payment_type === 'credit')
                                            <span class="pay-badge credit">Credit</span>
                                        @else
                                            <span class="pay-badge cash">Cash</span>
                                        @endif
                                    </td>
                                    <td><i class="bi bi-person"></i> {{ $item->supplier ?? 'Unknown' }}</td>
                                    <td><i class="bi bi-person-check"></i> {{ $item->served_by ?? 'Unknown' }}</td>
                                    <td>
                                        @php $status = strtolower(trim($item->status ?? 'returned')); @endphp
                                        @if($status == 'pending')
                                            <span class="status-badge pending">Pending</span>
                                        @elseif($status == 'approved')
                                            <span class="status-badge approved">Approved</span>
                                        @else
                                            <span class="status-badge returned">Returned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php $status = strtolower(trim($item->status ?? 'returned')); @endphp
                                        @if($status == 'pending')
                                            <form method="post" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="return_id" value="{{ $item->id }}">
                                                <button formaction="{{ url('user/return/approve') }}"
                                                    class="act-btn act-btn-approve" title="Approve return"
                                                    onclick="return confirm('Approve this return? Stock will be deducted.')">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <form method="post" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="return_id" value="{{ $item->id }}">
                                                <button formaction="{{ url('user/return/reject') }}"
                                                    class="act-btn act-btn-reject" title="Reject return"
                                                    onclick="return confirm('Reject this return? No stock will be deducted.')">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="post" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item->productId }}">
                                            <input type="hidden" name="action" value="delete">
                                            <button formaction="{{ url('user/dltrestock') }}"
                                                class="act-btn act-btn-delete" title="Delete"
                                                onclick="return confirm('Delete this return record?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11">
                                        <div class="empty-state">
                                            <i class="bi bi-arrow-return-left"></i>
                                            <div class="empty-state-title">No Returns Found</div>
                                            <p>No returns found for the selected date range</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>
  </div>
</div>

<script>
// ════════════════════════════════════════════
// Toggle table visibility
// ════════════════════════════════════════════
const STORAGE_KEY = 'returnsTableVisible';

document.addEventListener('DOMContentLoaded', function() {
    const saved = localStorage.getItem(STORAGE_KEY);
    const isVisible = saved === 'true';
    updateToggleState(isVisible);
});

function toggleTableData() {
    const tbody = document.getElementById('returnsBody');
    const isCurrentlyHidden = !tbody.classList.contains('visible');
    localStorage.setItem(STORAGE_KEY, isCurrentlyHidden ? 'true' : 'false');
    updateToggleState(isCurrentlyHidden);
}

function updateToggleState(show) {
    const tbody = document.getElementById('returnsBody');
    const icon = document.getElementById('toggleIcon');
    const text = document.getElementById('toggleText');
    
    if (show) {
        tbody.classList.add('visible');
        icon.className = 'bi bi-dash-lg';
        text.textContent = 'Hide';
    } else {
        tbody.classList.remove('visible');
        icon.className = 'bi bi-plus-lg';
        text.textContent = 'Show';
    }
}
</script>

</body>
</html>