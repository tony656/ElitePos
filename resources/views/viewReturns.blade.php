<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} - @lang('messages.view_returns_page_title')</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* =========================================================
           KESSY POS BLUE THEME
           Existing Blade markup, IDs, forms and JavaScript retained.
           ========================================================= */
        :root {
            --navy:          #084b87;
            --navy-mid:      #0756a6;
            --navy-light:    #0b66b2;
            --amber:         #0b65b1;
            --amber-pale:    #e8f2fc;
            --emerald:       #07866b;
            --emerald-pale:  #e5f7f1;
            --rose:          #e5485d;
            --rose-pale:     #fff0f1;
            --violet:        #7844d6;
            --violet-pale:   #f1ebff;
            --sky:           #0874c5;
            --sky-pale:      #e8f3ff;
            --slate-50:      #f5f9fd;
            --slate-100:     #edf3f9;
            --slate-200:     #dce7f1;
            --slate-300:     #c5d5e5;
            --slate-400:     #8da4bb;
            --slate-500:     #607b96;
            --slate-600:     #45627e;
            --slate-700:     #2d4c69;
            --slate-800:     #183a5a;
            --white:         #ffffff;
            --shadow-sm: 0 2px 8px rgba(8,61,108,.06);
            --shadow-md: 0 5px 16px rgba(8,61,108,.09);
            --shadow-lg: 0 10px 28px rgba(8,61,108,.13);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f3f7fb;
            color: var(--slate-800);
            min-height: 100vh;
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        .main-wrap { max-width: 1900px; margin: 0 auto; padding: 1.1rem 1.25rem 1.5rem; }

        /* ── Page header: flat white strip ── */
        .pg-header {
            background: transparent;
            border: 0;
            border-radius: 0;
            padding: .25rem 0 .9rem;
            margin-bottom: .55rem;
            box-shadow: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pg-title {
            color: #064b86;
            font-size: 1.42rem;
            font-weight: 700;
            letter-spacing: -.35px;
            display: flex;
            align-items: center;
            gap: .55rem;
        }
        .pg-title i { color: #e5485d; font-size: 1.1rem; }
        .pg-title span { color: #0871c2; }

        .hbtn-primary {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .75rem;
            font-weight: 600;
            padding: .5rem .8rem;
            background: #0866b2;
            color: #fff;
            border: 1px solid #0866b2;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(8,102,178,.18);
            transition: all 0.18s;
            text-decoration: none;
            letter-spacing: .01em;
        }
        .hbtn-primary i { font-size: .82rem; }
        .hbtn-primary:hover {
            background: #064f91;
            border-color: #064f91;
            transform: translateY(-1px);
            color: #fff;
        }

        /* ── Alerts ── */
        .alert-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .7rem .9rem;
            border-radius: 8px;
            font-size: .78rem;
            font-weight: 500;
            margin-bottom: .85rem;
            border-left: 3px solid;
        }
        .alert-success { background: var(--emerald-pale); border-color: var(--emerald); color: #056f58; }
        .alert-error   { background: var(--rose-pale);    border-color: var(--rose);    color: #c9314a; }

        /* ── Filter bar ── */
        .filter-card {
            background: #fff;
            border: 1px solid #dce7f1;
            border-radius: 8px;
            padding: .8rem .9rem;
            margin-bottom: .85rem;
        }
        .filter-card .row { --bs-gutter-x: .6rem; --bs-gutter-y: .6rem; }
        .filter-card .form-label {
            font-size: .66rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--slate-500);
            margin-bottom: .35rem;
        }
        .filter-card .form-control,
        .filter-card .form-select {
            font-family: 'DM Sans', sans-serif;
            font-size: .76rem;
            padding: .4rem .65rem;
            border: 1px solid #d4e1ed;
            border-radius: 6px;
            color: var(--slate-800);
            outline: none;
            transition: border-color .18s, box-shadow .18s;
        }
        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            border-color: #0b68b4;
            box-shadow: 0 0 0 3px rgba(11,104,180,.09);
        }
        .filter-card .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%238da4bb' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .6rem center;
            padding-right: 1.8rem;
        }
        .btn-apply {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            width: 100%;
            font-size: .76rem;
            font-weight: 600;
            padding: .42rem .7rem;
            background: #0866b2;
            border: 1px solid #0866b2;
            color: #fff;
            border-radius: 6px;
            transition: all .15s;
            letter-spacing: .01em;
        }
        .btn-apply:hover { background: #064f91; border-color: #064f91; color: #fff; transform: translateY(-1px); }
        .btn-apply i { font-size: .78rem; }

        /* ── Date Groups ── */
        .groups-container { display: flex; flex-direction: column; gap: .8rem; }

        .date-group-card {
            background: #fff;
            border-radius: 8px;
            border: 1px solid #dce7f1;
            overflow: hidden;
            box-shadow: 0 2px 7px rgba(8,61,108,.045);
            transition: box-shadow .2s;
        }
        .date-group-card:hover { box-shadow: var(--shadow-md); }

        .group-header {
            background: #fff;
            padding: .75rem .9rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
            border-bottom: 1px solid #dce7f1;
            gap: .6rem;
            flex-wrap: wrap;
        }
        .group-header:hover { background: #f5f9fd; }

        .date-info {
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
        }

        .date-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: #0866b2;
            color: #fff;
            padding: .28rem .85rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: .74rem;
            letter-spacing: .01em;
        }
        .date-badge i { font-size: .72rem; color: #b8dcff; }

        .group-stats { display: flex; gap: .4rem; font-size: .7rem; flex-wrap: wrap; }

        .group-stats span {
            display: inline-flex;
            align-items: center;
            gap: .28rem;
            background: #eff6ff;
            padding: .22rem .6rem;
            border-radius: 20px;
            font-weight: 600;
            color: #0756a6;
            font-family: 'DM Mono', monospace;
            border: 1px solid #cfe2f5;
        }
        .group-stats span i { font-size: .64rem; color: #0866b2; }

        .expand-icon {
            font-size: 1rem;
            color: #0866b2;
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }

        .group-details {
            display: none;
            background: #fff;
            border-top: 1px solid #dce7f1;
            animation: slideDown 0.3s ease;
        }
        .group-details.expanded { display: block; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Group summary ── */
        .group-summary {
            background: #f5f9fd;
            padding: .6rem .9rem;
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            border-bottom: 1px solid #dce7f1;
            font-size: .74rem;
        }
        .group-summary-item {
            display: flex;
            align-items: baseline;
            gap: .35rem;
            color: var(--slate-600);
        }
        .group-summary-item strong {
            color: #084b87;
            font-weight: 700;
        }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }

        .returns-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: .74rem;
        }

        .returns-tbl thead th {
            background: #07569b;
            color: #fff;
            font-size: .66rem;
            font-weight: 600;
            text-transform: none;
            letter-spacing: .015em;
            padding: .62rem .68rem;
            border: 1px solid rgba(255,255,255,.13);
            position: sticky;
            top: 0;
            z-index: 10;
            text-align: left;
            white-space: nowrap;
        }

        .returns-tbl tbody td {
            padding: .52rem .68rem;
            border: 1px solid #e5edf5;
            vertical-align: middle;
            color: #244866;
        }
        .returns-tbl tbody tr:nth-child(even) td { background: #f8fbfe; }
        .returns-tbl tbody tr:hover td { background: #edf6ff; }

        .prod-name {
            font-weight: 600;
            color: #164a78;
            font-size: .76rem;
        }

        .pay-badge, .status-badge {
            display: inline-flex;
            align-items: center;
            font-size: .68rem;
            font-weight: 700;
            padding: .22rem .55rem;
            border-radius: 5px;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .pay-badge.credit { background: var(--amber-pale); color: #07569b; }
        .pay-badge.cash   { background: var(--emerald-pale); color: #056f58; }

        .status-badge.pending  { background: var(--amber-pale); color: #07569b; }
        .status-badge.approved { background: var(--emerald-pale); color: #056f58; }
        .status-badge.returned { background: var(--violet-pale); color: var(--violet); }

        .act-btn {
            width: 27px;
            height: 26px;
            border: 1px solid;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .72rem;
            cursor: pointer;
            transition: all 0.15s;
            background: #fff;
            margin: 0 2px;
            font-family: inherit;
        }
        .act-btn-approve { border-color: #b8e5d5; color: #056f58; background: var(--emerald-pale); }
        .act-btn-approve:hover { background: var(--emerald); color: #fff; border-color: var(--emerald); transform: translateY(-1px); }
        .act-btn-reject  { border-color: #f5b8c2; color: #c9314a; background: var(--rose-pale); }
        .act-btn-reject:hover  { background: var(--rose); color: #fff; border-color: var(--rose); transform: translateY(-1px); }
        .act-btn-delete  { border-color: #d4e1ed; color: #42617e; background: #fff; }
        .act-btn-delete:hover  { background: #edf3f9; transform: translateY(-1px); }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--slate-400);
            background: #fff;
            border: 1px solid #dce7f1;
            border-radius: 8px;
        }
        .empty-state i {
            font-size: 2.5rem;
            display: block;
            margin-bottom: .65rem;
            opacity: 0.3;
        }
        .empty-state-title {
            font-size: .92rem;
            font-weight: 600;
            color: var(--slate-600);
            margin-bottom: .3rem;
        }
        .empty-state p { font-size: .76rem; color: var(--slate-400); }

        /* ── Grand total ── */
        .grand-total {
            background: linear-gradient(120deg, #064b86, #0870bd);
            color: #fff;
            border-radius: 8px;
            padding: .85rem 1.1rem;
            margin-top: .85rem;
            box-shadow: 0 5px 18px rgba(8,61,108,.18);
        }
        .grand-total .row { --bs-gutter-x: .8rem; --bs-gutter-y: .6rem; }
        .grand-total small {
            font-size: .62rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255,255,255,.55);
            font-weight: 700;
            display: block;
            margin-bottom: .15rem;
        }
        .grand-total h5 {
            font-family: 'DM Mono', monospace;
            font-size: .95rem;
            font-weight: 700;
            margin: 0;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .main-wrap { padding: .8rem; }
            .pg-title { font-size: 1.2rem; }
            .group-header { flex-direction: column; align-items: flex-start; gap: .55rem; }
            .date-info { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

    @include("sidenav")

        <main class="main-content">
            <div class="main-wrap">
                <!-- Header -->
                <div class="pg-header">
                    <div class="pg-title">
                        <i class="bi bi-arrow-return-left"></i>
                        View <span>Returns</span>
                    </div>
                    <a href="{{ url('make-return') }}" class="hbtn-primary">
                        <i class="bi bi-plus-circle"></i> {{ __('messages.new_return') }}
                    </a>
                </div>

                <!-- Alerts -->
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

                <!-- Filter Bar -->
                <div class="filter-card">
                    <form method="GET" action="{{ url('view-returns') }}" class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date', $fromDate ?? '') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date', $toDate ?? '') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="all" {{ (request('status', $statusFilter ?? 'all') == 'all') ? 'selected' : '' }}>All Status</option>
                                <option value="Pending" {{ (request('status', $statusFilter ?? 'all') == 'Pending') ? 'selected' : '' }}>Pending</option>
                                <option value="Approved" {{ (request('status', $statusFilter ?? 'all') == 'Approved') ? 'selected' : '' }}>Approved</option>
                                <option value="Returned" {{ (request('status', $statusFilter ?? 'all') == 'Returned') ? 'selected' : '' }}>Returned</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Shop</label>
                            <select name="shop" class="form-select">
                                <option value="">All Shops</option>
                                @foreach($shops ?? [] as $shop)
                                    <option value="{{ $shop['id'] }}" {{ request('shop', $shopFilter ?? '') == $shop['id'] ? 'selected' : '' }}>
                                        {{ $shop['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn-apply">
                                <i class="bi bi-funnel"></i> Apply Filters
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Grouped Returns Display -->
                <div class="groups-container">
                    @php
                        // Group products by date
                        $groupedReturns = [];
                        foreach ($products as $item) {
                            $date = date('Y-m-d', strtotime($item->created_at));
                            if (!isset($groupedReturns[$date])) {
                                $groupedReturns[$date] = [];
                            }
                            $groupedReturns[$date][] = $item;
                        }
                        
                        // Sort dates descending
                        krsort($groupedReturns);
                        
                        $grandTotalQty = $products->sum('quantity');
                        $grandTotalValue = $products->sum(function($p){ return ($p->quantity ?? 0) * ($p->price ?? 0); });
                    @endphp

                    @if(count($groupedReturns) > 0)
                        @foreach($groupedReturns as $date => $dateItems)
                            @php
                                $dateTotalQty = collect($dateItems)->sum('quantity');
                                $dateTotalValue = collect($dateItems)->sum(function($item) { 
                                    return ($item->quantity ?? 0) * ($item->price ?? 0); 
                                });
                                $formattedDate = date('F d, Y', strtotime($date));
                            @endphp
                            
                            <div class="date-group-card" data-date="{{ $date }}">
                                <div class="group-header" onclick="toggleGroup(this)">
                                    <div class="date-info">
                                        <div class="date-badge">
                                            <i class="bi bi-calendar3"></i> {{ $formattedDate }}
                                        </div>
                                        <div class="group-stats">
                                            <span><i class="bi bi-box"></i> {{ count($dateItems) }} Items</span>
                                            <span><i class="bi bi-calculator"></i> Qty: {{ number_format($dateTotalQty) }}</span>
                                            <span><i class="bi bi-currency-dollar"></i> Tsh {{ number_format($dateTotalValue, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="expand-icon">
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                </div>
                                
                                <div class="group-details">
                                    <div class="group-summary">
                                        <div class="group-summary-item"><strong>📦 Total Returns:</strong> {{ count($dateItems) }}</div>
                                        <div class="group-summary-item"><strong>📊 Total Quantity:</strong> {{ number_format($dateTotalQty) }}</div>
                                        <div class="group-summary-item"><strong>💰 Total Value:</strong> Tsh {{ number_format($dateTotalValue, 2) }}</div>
                                    </div>
                                    
                                    <div class="table-wrap">
                                        <table class="returns-tbl">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product</th>
                                                    <th>Qty</th>
                                                    <th>Cost</th>
                                                    <th>Total</th>
                                                    <th>Payment</th>
                                                    <th>Supplier</th>
                                                    <th>From</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dateItems as $index => $item)
                                                @php
                                                    $supName = DB::table('vendors')->where('id', $item->supplier)->value('name');
                                                    $from = DB::table('accounts')->where('id', $item->account)->value('name');
                                                    $status = strtolower(trim($item->status ?? 'returned'));
                                                @endphp
                                                <tr id="row-{{ $item->productId }}">
                                                    <td style="color:var(--slate-400);font-size:.72rem;">{{ $index + 1 }}</td>
                                                    <td><div class="prod-name">{{ $item->productName }}</div></td>
                                                    <td style="font-family:'DM Mono',monospace;color:#31516e;">{{ number_format($item->quantity) }}</td>
                                                    <td style="font-family:'DM Mono',monospace;color:#31516e;">Tsh {{ number_format($item->price, 2) }}</td>
                                                    <td style="font-family:'DM Mono',monospace;color:#084b87;font-weight:600;">Tsh {{ number_format($item->price * $item->quantity, 2) }}</td>
                                                    <td>
                                                        @if($item->isDebt == 1)
                                                            <span class="pay-badge credit">Credit</span>
                                                        @else
                                                            <span class="pay-badge cash">Cash</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $supName ?? 'Unknown' }}</td>
                                                    <td>{{ $from ?? 'Unknown' }}</td>
                                                    <td>
                                                        @if($status == 'pending')
                                                            <span class="status-badge pending">Pending</span>
                                                        @elseif($status == 'approved')
                                                            <span class="status-badge approved">Approved</span>
                                                        @else
                                                            <span class="status-badge returned">Returned</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($status == 'pending')
                                                            <form method="post" style="display:inline;">
                                                                @csrf
                                                                <input type="hidden" name="return_id" value="{{ $item->id }}">
                                                                <button formaction="{{ url('return/approve') }}"
                                                                    class="act-btn act-btn-approve" title="Approve return"
                                                                    onclick="return confirm('Approve this return? Stock will be deducted.')">
                                                                    <i class="bi bi-check-lg"></i>
                                                                </button>
                                                            </form>
                                                            <form method="post" style="display:inline;">
                                                                @csrf
                                                                <input type="hidden" name="return_id" value="{{ $item->id }}">
                                                                <button formaction="{{ url('return/reject') }}"
                                                                    class="act-btn act-btn-reject" title="Reject return"
                                                                    onclick="return confirm('Reject this return? No stock will be deducted.')">
                                                                    <i class="bi bi-x-lg"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <form method="post" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="product_id" value="{{ $item->id }}">
                                                            <input type="hidden" name="action" value="delete">
                                                            <button formaction="{{ url('dltrestock') }}"
                                                                class="act-btn act-btn-delete" title="Delete"
                                                                onclick="return confirm('Delete this return record?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Grand Total Summary -->
                        <div class="grand-total">
                            <div class="row">
                                <div class="col-md-4">
                                    <small>Grand Total</small>
                                    <h5>{{ number_format(count($products)) }} Returns</h5>
                                </div>
                                <div class="col-md-4">
                                    <small>Total Quantity</small>
                                    <h5>{{ number_format($grandTotalQty) }} Units</h5>
                                </div>
                                <div class="col-md-4">
                                    <small>Total Value</small>
                                    <h5>Tsh {{ number_format($grandTotalValue, 2) }}</h5>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-arrow-return-left"></i>
                            <div class="empty-state-title">No Returns Found</div>
                            <p>No returns found for the selected criteria</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>

<script>
// Toggle group expansion function
function toggleGroup(headerElement) {
    const card = headerElement.closest('.date-group-card');
    const detailsDiv = card.querySelector('.group-details');
    const icon = headerElement.querySelector('.expand-icon i');
    
    if (detailsDiv.classList.contains('expanded')) {
        detailsDiv.classList.remove('expanded');
        icon.className = 'bi bi-chevron-down';
    } else {
        detailsDiv.classList.add('expanded');
        icon.className = 'bi bi-chevron-up';
    }
}

// Optional: Expand first group by default on page load
document.addEventListener('DOMContentLoaded', function() {
    const firstGroup = document.querySelector('.date-group-card');
    if (firstGroup) {
        const header = firstGroup.querySelector('.group-header');
        const details = firstGroup.querySelector('.group-details');
        const icon = firstGroup.querySelector('.expand-icon i');
        
        // Expand the first group by default
        if (details && !details.classList.contains('expanded')) {
            details.classList.add('expanded');
            if (icon) icon.className = 'bi bi-chevron-up';
        }
    }
    
    // Store expanded groups in localStorage (optional)
    const cards = document.querySelectorAll('.date-group-card');
    cards.forEach(card => {
        const date = card.getAttribute('data-date');
        const isExpanded = localStorage.getItem(`group_${date}`) === 'expanded';
        if (isExpanded) {
            const details = card.querySelector('.group-details');
            const icon = card.querySelector('.expand-icon i');
            if (details && !details.classList.contains('expanded')) {
                details.classList.add('expanded');
                if (icon) icon.className = 'bi bi-chevron-up';
            }
        }
        
        // Save state when toggling
        const header = card.querySelector('.group-header');
        header.addEventListener('click', function() {
            const detailsDiv = card.querySelector('.group-details');
            const newState = detailsDiv.classList.contains('expanded') ? 'expanded' : 'collapsed';
            localStorage.setItem(`group_${date}`, newState === 'expanded' ? 'expanded' : 'collapsed');
        });
    });
});
</script>
@include('footer')

</body>
</html>