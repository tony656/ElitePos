<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Report — {{ date('F d, Y', strtotime($dateParam)) }}</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #F7F6F2;
            --surface:   #FFFFFF;
            --border:    #E4E2DA;
            --border-md: #CECCC4;
            --text:      #0B1E3D;
            --muted:     #7A7870;
            --accent:    #F59E0B;
            --green:     #1A6B45;
            --green-bg:  #E6F4ED;
            --red:       #B63A2F;
            --red-bg:    #FDECEA;
            --amber:     #F59E0B;
            --amber-bg:  #FEF3D7;
            --purple:    #1A3A6B;
            --purple-bg: #EEECFA;
            --shadow:    0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --radius:    10px;
            --radius-sm: 6px;
            --font:      'DM Sans', system-ui, sans-serif;
            --mono:      'DM Mono', monospace;
        }

        body {
            background: var(--bg);
            font-family: var(--font);
            color: var(--text);
            font-size: 14px;
            line-height: 1.5;
        }

        /* ── Layout ── */
        .layout { display: flex; min-height: 100vh; }
        .sidebar-wrap { flex-shrink: 0; }
        .main {
            flex: 1;
            min-width: 0;
            padding: 2rem 2.5rem;
        }

        /* ── Alerts ── */
        .alert {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.75rem 1rem; border-radius: var(--radius-sm);
            margin-bottom: 1.25rem; font-size: 13px;
        }
        .alert-success { background: var(--green-bg); color: var(--green); border: 1px solid #B2DFC5; }
        .alert-danger  { background: var(--red-bg);   color: var(--red);   border: 1px solid #F5C6C2; }
        .btn-close-sm { background: none; border: none; cursor: pointer; font-size: 16px; color: inherit; opacity: 0.6; }
        .btn-close-sm:hover { opacity: 1; }

        /* ── Page header ── */
        .page-header {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 2rem; gap: 1rem; flex-wrap: wrap;
        }
        .page-title { font-size: 22px; font-weight: 600; letter-spacing: -0.3px; }
        .page-sub   { font-size: 13px; color: var(--muted); margin-top: 3px; }

        .header-actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }

        /* ── Date form ── */
        .date-form { display: flex; border: 1px solid var(--border-md); border-radius: var(--radius-sm); overflow: hidden; background: var(--surface); }
        .date-form input[type=date] {
            border: none; outline: none; padding: 7px 12px;
            font-family: var(--font); font-size: 13px;
            background: transparent; color: var(--text);
        }
        .date-form button {
            background: var(--accent); color: #fff; border: none;
            padding: 7px 14px; font-family: var(--font); font-size: 13px;
            font-weight: 500; cursor: pointer; white-space: nowrap;
        }
        .date-form button:hover { opacity: 0.85; }

        /* ── Icon buttons ── */
        .btn-icon {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 14px; border-radius: var(--radius-sm);
            font-family: var(--font); font-size: 13px; font-weight: 500;
            cursor: pointer; border: 1px solid var(--border-md);
            background: var(--surface); color: var(--text);
            text-decoration: none; transition: background 0.15s;
        }
        .btn-icon:hover { background: var(--bg); }
        .btn-icon svg { width: 14px; height: 14px; }

        /* ── Metric cards ── */
        .metrics { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 12px; margin-bottom: 2rem; }
        @media (max-width: 900px) { .metrics { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 560px) { .metrics { grid-template-columns: 1fr; } }

        .metric {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 1.1rem 1.25rem;
            box-shadow: var(--shadow);
        }
        .metric-label { font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; color: var(--muted); margin-bottom: 8px; }
        .metric-value { font-size: 24px; font-weight: 600; letter-spacing: -0.5px; font-family: var(--mono); }
        .metric-sub   { font-size: 12px; color: var(--muted); margin-top: 5px; }

        .metric.purple .metric-value { color: var(--purple); }
        .metric.green  .metric-value { color: var(--green);  }
        .metric.red    .metric-value { color: var(--red);    }
        .metric.amber  .metric-value { color: var(--amber);  }

        /* ── Panel ── */
        .panel {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden;
        }
        .panel-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.25rem; border-bottom: 1px solid var(--border);
            gap: 1rem; flex-wrap: wrap;
        }
        .panel-title { font-size: 14px; font-weight: 600; }

        /* ── Search & Sort ── */
        .search-wrap {
            display: flex; align-items: center; gap: 7px;
            border: 1px solid var(--border-md); border-radius: var(--radius-sm);
            padding: 6px 10px; background: var(--bg);
        }
        .search-wrap svg { width: 13px; height: 13px; color: var(--muted); flex-shrink: 0; }
        .search-wrap input {
            border: none; background: transparent; outline: none;
            font-family: var(--font); font-size: 13px; color: var(--text); width: 180px;
        }
        .search-wrap input::placeholder { color: var(--muted); }

        /* ── Sort dropdown ── */
        .sort-wrap {
            display: flex; align-items: center; gap: 7px;
            border: 1px solid var(--border-md); border-radius: var(--radius-sm);
            padding: 6px 10px; background: var(--bg);
        }
        .sort-wrap select {
            border: none; background: transparent; outline: none;
            font-family: var(--font); font-size: 13px; color: var(--text);
            cursor: pointer; min-width: 150px;
        }
        .sort-wrap label { font-size: 12px; color: var(--muted); white-space: nowrap; }

        /* ── Table ── */
        .tbl-scroll { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }

        thead th {
            background: var(--bg); color: var(--muted);
            font-size: 10.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em;
            padding: 9px 12px; text-align: right; white-space: nowrap;
            border-bottom: 1px solid var(--border);
        }
        thead th:nth-child(1),
        thead th:nth-child(2) { text-align: left; }

        tbody tr { border-bottom: 1px solid var(--border); transition: background 0.12s; cursor: pointer; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #F9F8F4; }
        tbody tr.expanded { background: #F3F2EE; }
        tbody tr.warn-row { background: #FFFBF0; }
        tbody tr.warn-row:hover { background: #FFF5D6; }

        td { padding: 10px 12px; text-align: right; vertical-align: middle; }
        td:nth-child(1),
        td:nth-child(2) { text-align: left; }

        /* ── Shop cell ── */
        .shop-cell { display: flex; align-items: center; gap: 10px; }
        .avatar {
            width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
            background: var(--purple-bg); color: var(--purple);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 600;
        }
        .shop-name { font-weight: 500; }
        .shop-loc  { font-size: 11px; color: var(--muted); }

        /* ── Num ── */
        .num { font-family: var(--mono); font-size: 12.5px; }

        /* ── Badges ── */
        .badge {
            display: inline-block; padding: 2px 8px; border-radius: 20px;
            font-size: 10.5px; font-weight: 600; letter-spacing: 0.03em;
        }
        .badge-ok   { background: var(--green-bg); color: var(--green); }
        .badge-over { background: var(--amber-bg); color: var(--amber); }
        .badge-bad  { background: var(--red-bg);   color: var(--red); }

        .txt-green { color: var(--green); }
        .txt-red   { color: var(--red); }
        .txt-amber { color: var(--amber); }
        .txt-muted { color: var(--muted); }
        .fw6 { font-weight: 600; }

        /* ── Status icons ── */
        .ico-ok   { color: var(--green); font-size: 15px; }
        .ico-warn { color: var(--amber); font-size: 15px; cursor: help; }

        /* ── Detail row ── */
        .detail-row { display: none; }
        .detail-row.open { display: table-row; }
        .detail-row td { background: #F3F2EE; padding: 0; }
        .detail-row.warn-row td { background: #FFFBF0; }

        .detail-inner {
            padding: 1.25rem 1.5rem;
            border-left: 3px solid var(--purple);
        }
        .detail-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr)); gap: 1rem;
        }
        .detail-item label { font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.06em; color: var(--muted); display: block; margin-bottom: 4px; }
        .detail-item span  { font-size: 14px; font-weight: 500; font-family: var(--mono); }

        /* ── Footer ── */
        tfoot td {
            background: var(--bg); border-top: 1px solid var(--border-md);
            font-size: 12px; font-weight: 600; font-family: var(--mono);
            padding: 10px 12px; text-align: right; color: var(--muted);
        }
        tfoot td:nth-child(1),
        tfoot td:nth-child(2) { text-align: left; color: var(--text); }

        /* ── Empty state ── */
        .empty-state { text-align: center; padding: 4rem 2rem; color: var(--muted); }
        .empty-state h4 { font-size: 16px; font-weight: 500; margin-bottom: 8px; color: var(--text); }
        .empty-state p  { font-size: 13px; }

        /* ── Modal ── */
        .modal-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.35);
            z-index: 9999; align-items: center; justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: var(--surface); border-radius: var(--radius);
            width: 100%; max-width: 480px; margin: 1rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        .modal-top {
            padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-top h2 { font-size: 16px; font-weight: 600; }
        .modal-body { padding: 1.5rem; }
        .modal-foot { padding: 1rem 1.5rem; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 8px; }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem 1.5rem; background: var(--bg); border-radius: var(--radius-sm); padding: 1rem; margin-bottom: 1.25rem; }
        .info-item label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); display: block; margin-bottom: 3px; }
        .info-item span  { font-size: 14px; font-weight: 500; font-family: var(--mono); }
        .info-item.big span { font-size: 20px; color: var(--green); }

        .field-group { margin-bottom: 1rem; }
        .field-group label { display: block; font-size: 12px; font-weight: 500; margin-bottom: 6px; }
        .input-row { display: flex; border: 1px solid var(--border-md); border-radius: var(--radius-sm); overflow: hidden; }
        .input-prefix { background: var(--bg); padding: 8px 12px; font-size: 13px; color: var(--muted); border-right: 1px solid var(--border-md); white-space: nowrap; }
        .input-row input[type=number] {
            flex: 1; border: none; outline: none; padding: 8px 12px;
            font-family: var(--mono); font-size: 14px; color: var(--text); background: transparent;
        }

        .quick-amounts { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
        .quick-btn {
            background: var(--bg); border: 1px solid var(--border-md);
            border-radius: var(--radius-sm); padding: 4px 10px; font-size: 12px;
            font-family: var(--mono); cursor: pointer; color: var(--text);
            transition: background 0.12s, border-color 0.12s;
        }
        .quick-btn:hover { background: var(--accent); color: #fff; border-color: var(--accent); }

        .btn-primary {
            background: var(--accent); color: #fff; border: none;
            padding: 8px 18px; border-radius: var(--radius-sm);
            font-family: var(--font); font-size: 13px; font-weight: 500; cursor: pointer;
        }
        .btn-primary:hover { opacity: 0.85; }
        .btn-secondary {
            background: transparent; color: var(--muted); border: 1px solid var(--border-md);
            padding: 8px 18px; border-radius: var(--radius-sm);
            font-family: var(--font); font-size: 13px; cursor: pointer;
        }
        .btn-secondary:hover { background: var(--bg); }

        /* ── Divider in info grid ── */
        .info-divider { grid-column: 1/-1; border: none; border-top: 1px solid var(--border); }
    </style>
</head>
<body>
<div class="row">
        @include('admin/sidenav')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <button class="btn-close-sm" onclick="this.closest('.alert').remove()">×</button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
                <button class="btn-close-sm" onclick="this.closest('.alert').remove()">×</button>
            </div>
        @endif

        {{-- Page header --}}
        <div class="page-header">
            <div>
                <div class="page-title">Shop report</div>
                <div class="page-sub">{{ date('l, F d Y', strtotime($dateParam)) }}</div>
            </div>
            <div class="header-actions">
                <form method="GET" action="shopReport" id="dateFilterForm">
                    <div class="date-form">
                        <input type="date" name="date" id="dateInput" value="{{ $dateParam }}">
                        <button type="submit">View</button>
                    </div>
                </form>
                <a class="btn-icon" onclick="exportToExcel()">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="1" y="1" width="14" height="14" rx="2"/><path d="M5 5l6 6M11 5l-6 6"/></svg>
                    Excel
                </a>
                <a class="btn-icon" onclick="exportToPDF()">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 1h6l4 4v10H4V1z"/><path d="M10 1v4h4"/><path d="M6 10h4M6 13h2"/></svg>
                    PDF
                </a>
            </div>
        </div>

        {{-- Metric cards --}}
        <div class="metrics">
            <div class="metric purple">
                <div class="metric-label">Total shops</div>
                <div class="metric-value">{{ $activeShopsCount }}</div>
                <div class="metric-sub">{{ $shopsWithSalesCount }} with sales today</div>
            </div>
            <div class="metric red">
                <div class="metric-label">Total sales</div>
                <div class="metric-value">{{ number_format($totals->total_sales) }}</div>
                <div class="metric-sub">Cash {{ number_format($totals->cash_sales) }} · Credit {{ number_format($totals->credit_sales) }}</div>
            </div>
            <div class="metric green">
                <div class="metric-label">Total profit</div>
                <div class="metric-value">{{ number_format($totals->profit) }}</div>
                <div class="metric-sub">After expenses &amp; debt</div>
            </div>
            <div class="metric amber">
                <div class="metric-label">Cash expected</div>
                <div class="metric-value">{{ number_format($totals->cash_amount) }}</div>
                <div class="metric-sub">Submitted {{ number_format($totals->cash_submitted) }}</div>
            </div>
        </div>

        {{-- Main table panel --}}
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">All shops — {{ date('F d, Y', strtotime($dateParam)) }}</span>
                <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <div class="sort-wrap">
                        <label>Sort by:</label>
                        <select id="sortSelect" onchange="sortShops()">
                            <option value="name_asc">Name (A-Z)</option>
                            <option value="name_desc">Name (Z-A)</option>
                            <option value="balanced">Balanced</option>
                            <option value="unbalanced">Unbalanced</option>
                            <option value="status_settled">Status: Settled</option>
                            <option value="status_underpaid">Status: Underpaid</option>
                            <option value="status_overpaid">Status: Overpaid</option>
                            <option value="sales_high">Sales (High-Low)</option>
                            <option value="sales_low">Sales (Low-High)</option>
                            <option value="profit_high">Profit (High-Low)</option>
                            <option value="profit_low">Profit (Low-High)</option>
                        </select>
                    </div>
                    <div class="search-wrap">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="7" cy="7" r="5"/><path d="M11 11l3 3"/>
                        </svg>
                        <input type="text" id="shopSearch" placeholder="Search shops…" oninput="filterShops()">
                    </div>
                </div>
            </div>

            <div class="tbl-scroll">
                @if($shopReports->isEmpty())
                    <div class="empty-state">
                        <h4>No shops found</h4>
                        <p>No active shops found for {{ date('F d, Y', strtotime($dateParam)) }}.</p>
                    </div>
                @else
                <table id="shopTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Shop</th>
                            <th title="Cash Sale">C.Sale</th>
                            <th title="Credit Sale">Cr.Sale</th>
                            <th title="Total Sale">T.Sales</th>
                            <th title="Return">T.Ret</th>
                            <th title="Offered Items">Offered</th>
                            <th title="Discount">Dsc</th>
                            <th title="Expenses">Exp</th>
                            <th title="Profit / Loss">P/L</th>
                            <th title="Paid Invoice">P.Inv</th>
                            <th title="Cash Receivings">Ca.R</th>
                            <th title="Credit Receivings">Cr.R</th>
                            <th title="Paid Receivings">P.R</th>
                            <th title="Cash Amount">C.A</th>
                            <th title="Cash Submit">C.S</th>
                            <th title="Bank Deposit">B.D</th>
                            <th title="Chip Deposit">Chip.D</th>
                            <th title="Chip Used">Chip.U</th>
                            <th title="Bank Difference">B.Diff</th>
                            <th title="Difference">Diff</th>
                            <th title="Cost Worth">Inventory</th>
                            <th>Status</th>
                            <th>Balance</th> x
                        </tr>
                    </thead>
                    <tbody id="shopTableBody">
                        @foreach ($shopReports as $index => $shop)
                            @php
                                $cashSale   = $shop->cash_sales;
                                $creditSale = $shop->credit_sales;
                                $totalSale  = $shop->total_sales;
                                $cashAmount = $shop->cash_amount;
                                $cashSubmit = $shop->cash_submitted;
                                $diff       = $cashAmount - $cashSubmit;

                                $profitVal = $totalSale - $shop->total_return
                                    - ($shop->expenses ?? 0)
                                    - ($shop->cash_receivings ?? 0)
                                    - ($shop->credit_receivings ?? 0);

                                $diffClass = abs($diff) < 0.01 ? 'txt-muted' : ($diff > 0 ? 'txt-red' : 'txt-green');

                                $salesBalanced = abs(($cashSale + $creditSale) - $totalSale) < 0.01;
                                $cashBalanced  = abs($diff) < 0.01;
                                $isBalanced    = $salesBalanced && $cashBalanced;

                                $isDiffZero = abs($diff) < 0.1;
                                $statusBadge = $isDiffZero ? 'badge-ok'
                                             : ($diff > 0  ? 'badge-bad' : 'badge-over');
                                $statusText  = $isDiffZero ? 'Settled'
                                             : ($diff > 0  ? 'Underpaid' : 'Overpaid');

                                $balanceIssues = [];
                                if (!$salesBalanced) {
                                    $balanceIssues[] = 'Sales mismatch: ' . number_format($cashSale) . ' + ' . number_format($creditSale) . ' ≠ ' . number_format($totalSale);
                                }
                                if (!$cashBalanced) {
                                    $balanceIssues[] = 'Cash: expected ' . number_format($cashAmount) . ', got ' . number_format($cashSubmit);
                                }
                                $initials = strtoupper(substr($shop->shop_name, 0, 1));
                                $parts    = explode(' ', trim($shop->shop_name));
                                if (count($parts) > 1) $initials .= strtoupper(substr($parts[1], 0, 1));
                            @endphp

                            {{-- Main row --}}
                            <tr class="shop-row {{ !$isBalanced ? 'warn-row' : '' }}"
                                data-name="{{ strtolower($shop->shop_name) }}"
                                onclick="toggleDetail({{ $index }})">
                                <td class="txt-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="shop-cell">
                                        <div class="avatar">{{ $initials }}</div>
                                        <div>
                                            <div class="shop-name">{{ $shop->shop_name }}</div>
                                            <div class="shop-loc">{{ $shop->location }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="num">{{ number_format($cashSale) }}</td>
                                <td class="num">{{ number_format($creditSale) }}</td>
                                <td class="num fw6">{{ number_format($totalSale) }} <span class="txt-muted" style="font-size:11px;">({{ $shop->total_product_quantity }})</span></td>
                                <td class="num txt-red">-{{ number_format($shop->total_return ?? 0) }}</td>
                                <td class="num text-info fw6">{{ number_format($shop->total_offer ?? 0) }}</td>
                                <td class="num txt-muted">{{ number_format($shop->discount) }}</td>
                                <td class="num txt-muted">{{ number_format($shop->expenses) }}</td>
                                <td class="num {{ $profitVal >= 0 ? 'txt-green' : 'txt-red' }}">{{ number_format($profitVal) }}</td>
                                <td class="num">{{ number_format($shop->paid_invoices) }}</td>
                                <td class="num">{{ number_format($shop->cash_receivings) }}</td>
                                <td class="num">{{ number_format($shop->credit_receivings) }} <span class="txt-muted" style="font-size:11px;">({{ number_format($shop->credit_receivings_quantity ?? 0) }})</span></td>
                                <td class="num">{{ number_format($shop->paid_receivings) }} <span class="txt-muted" style="font-size:11px;">({{ number_format($shop->paid_receivings_quantity ?? 0) }})</span></td>
                                <td class="num">{{ number_format($cashAmount) }}</td>
                                <td class="num">{{ number_format($cashSubmit) }}</td>
                                <td class="num">{{ number_format($shop->total_bank) }}</td>
                                <td class="num">{{ number_format($shop->totalChip) }}</td>
                                <td class="num">{{ number_format($shop->chip_used) }}</td>
                                <td class="num">{{ number_format(($shop->bank_diff)) }}</td>
                                <td class="num fw6 {{ $diffClass }}">{{ number_format(abs($diff)) }}</td>
                                <td class="num">{{ number_format(($shop->cost_worth ?? 0)) }}</td>
                                <td>
                                    <span class="badge {{ $statusBadge }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    @if(!$isBalanced)
                                        <span class="ico-warn" title="{{ implode(' | ', $balanceIssues) }}">&#9651;</span>
                                    @else
                                        <span class="ico-ok">&#10003;</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Detail row --}}
                            <tr id="detail-{{ $index }}" class="detail-row {{ !$isBalanced ? 'warn-row' : '' }}">
                                <td colspan="21">
                                    <div class="detail-inner">
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <label>Shop</label>
                                                <span>{{ $shop->shop_name }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <label>Location</label>
                                                <span>{{ $shop->location }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <label>Expected cash</label>
                                                <span>{{ number_format($shop->expected_cash) }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <label>Submitted</label>
                                                <span>{{ number_format($shop->cash_submitted) }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <label>Difference</label>
                                                <span class="{{ $diffClass }}">{{ number_format($diff) }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <label>Total debt</label>
                                                <span>{{ number_format($shop->debt) }}</span>
                                            </div>
                                        </div>
                                        <div style="margin-top:1rem; display: flex; gap: 8px;">
                                            <button class="btn-primary" style="font-size:12px; padding:6px 14px;"
                                                onclick="event.stopPropagation(); openCashModal({{ $index }})">
                                                Submit cash
                                            </button>
                                            @if($shop->cash_submitted > 0)
                                            <form method="POST" action="{{ url('user/cashDelete') }}" style="display: inline;" onsubmit="return confirm('Delete cash submission for {{ $shop->shop_name }}?');">
                                                @csrf
                                                <input type="hidden" name="shop_id" value="{{ $shop->shop_id }}">
                                                <input type="hidden" name="date" value="{{ $dateParam }}">
                                                <button type="submit" class="btn-icon" style="font-size:12px; padding:6px 14px; background: var(--red); border-color: var(--red); color: white;">
                                                    🗑️ Delete
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="2">Totals</td>
                            <td>{{ number_format($totals->cash_sales) }}</td>
                            <td>{{ number_format($totals->credit_sales) }}</td>
                            <td class="fw6">{{ number_format($totals->total_sales - $totals->total_return) }}</td>
                            <td>{{ number_format($totals->total_return ?? 0) }}</td>
                            <td class="fw6">{{ number_format($totals->offer ?? 0) }}</td>
                            <td>{{ number_format($totals->discount) }}</td>
                            <td>{{ number_format($totals->expenses) }}</td>
                            <td class="{{ $totals->profit >= 0 ? 'txt-green' : 'txt-red' }}">{{ number_format($totals->profit) }}</td>
                            <td>{{ number_format($totals->paid_invoices) }}</td>
                            <td>{{ number_format($totals->cash_receivings) }}</td>
                            <td>{{ number_format($totals->credit_receivings) }}</td>
                            <td>{{ number_format($totals->paid_receivings) }}</td>
                            <td>{{ number_format($totals->cash_amount) }}</td>
                            <td>{{ number_format($totals->cash_submitted) }}</td>
                            <td>{{ number_format($totals->total_bank) }}</td>
                            <td>{{ number_format($totals->totalChip) }}</td>
                            <td>{{ number_format($totals->chip_used) }}</td>
                         
                            <td class="fw6">
                                {{ number_format(abs($totals->bank_diff)) }}
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                @endif
            </div>
        </div>

    </main>
</div>

{{-- Cash Submit Modal --}}
<div class="modal-overlay" id="cashModal">
    <div class="modal-box">
        <div class="modal-top">
            <h2>Submit cash</h2>
            <button class="btn-close-sm" onclick="closeModal()">×</button>
        </div>
        <form id="cashForm" method="POST" action="{{ url('user/cashSubmit') }}">
            @csrf
            <div class="modal-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Shop</label>
                        <span id="m-shop">—</span>
                    </div>
                    <div class="info-item">
                        <label>Date</label>
                        <span>{{ $dateParam }}</span>
                    </div>
                    <hr class="info-divider">
                    <div class="info-item">
                        <label>Total sales</label>
                        <span id="m-sales">0</span>
                    </div>
                    <div class="info-item">
                        <label>Cash sales</label>
                        <span id="m-cash-sales">0</span>
                    </div>
                    <div class="info-item">
                        <label>Expenses</label>
                        <span id="m-exp">0</span>
                    </div>
                    <div class="info-item">
                        <label>Discount</label>
                        <span id="m-dsc">0</span>
                    </div>
                    <hr class="info-divider">
                    <div class="info-item big">
                        <label>Expected cash</label>
                        <span id="m-expected">0</span>
                    </div>
                    <div class="info-item">
                        <label>Remaining</label>
                        <span id="m-remaining" style="color:var(--red);">0</span>
                    </div>
                </div>

                <div class="field-group">
                    <label for="cashAmount">Cash amount to submit</label>
                    <div class="input-row">
                        <span class="input-prefix">TSh</span>
                        <input type="number" id="cashAmount" name="submitted_cash" step="0.01" min="0.01" required>
                    </div>
                    <div class="quick-amounts">
                        <button type="button" class="quick-btn" onclick="setAmt(5000)">5,000</button>
                        <button type="button" class="quick-btn" onclick="setAmt(10000)">10,000</button>
                        <button type="button" class="quick-btn" onclick="setAmt(20000)">20,000</button>
                        <button type="button" class="quick-btn" onclick="setAmt(50000)">50,000</button>
                        <button type="button" class="quick-btn" onclick="setAmt(100000)">100,000</button>
                    </div>
                </div>

                <input type="hidden" name="shop_id" id="m-shop-id">
                <input type="hidden" name="date" value="{{ $dateParam }}">
                <input type="hidden" name="sales" id="m-sales-hidden">
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-primary">Submit cash</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const shopData = @json($shopReports);

    // ── Toggle detail row ──
    function toggleDetail(i) {
        const detail = document.getElementById('detail-' + i);
        const main   = detail.previousElementSibling;
        const isOpen = detail.classList.contains('open');

        document.querySelectorAll('.detail-row.open').forEach(r => r.classList.remove('open'));
        document.querySelectorAll('.shop-row.expanded').forEach(r => r.classList.remove('expanded'));

        if (!isOpen) {
            detail.classList.add('open');
            main.classList.add('expanded');
        }
    }

    // ── Search filter ──
    function filterShops() {
        const q = document.getElementById('shopSearch').value.toLowerCase();
        document.querySelectorAll('#shopTableBody .shop-row').forEach(row => {
            const match = row.getAttribute('data-name').includes(q);
            row.style.display = match ? '' : 'none';
            const next = row.nextElementSibling;
            if (next && next.classList.contains('detail-row')) {
                next.style.display = match ? '' : 'none';
            }
        });
    }

    // ── Sort shops ──
    function sortShops() {
        const sortValue = document.getElementById('sortSelect').value;
        const tbody = document.getElementById('shopTableBody');
        const rows = Array.from(tbody.querySelectorAll('.shop-row'));
        
        // Get sort key and order
        let sortKey, ascending;
        
        switch(sortValue) {
            case 'name_asc':
                sortKey = 'name';
                ascending = true;
                break;
            case 'name_desc':
                sortKey = 'name';
                ascending = false;
                break;
            case 'balanced':
                sortKey = 'balanced';
                ascending = true;
                break;
            case 'unbalanced':
                sortKey = 'balanced';
                ascending = false;
                break;
            case 'status_settled':
                sortKey = 'status';
                ascending = true;
                break;
            case 'status_underpaid':
                sortKey = 'status';
                ascending = false; // Will filter for underpaid
                break;
            case 'status_overpaid':
                sortKey = 'status';
                ascending = false; // Will filter for overpaid
                break;
            case 'sales_high':
                sortKey = 'total_sales';
                ascending = false;
                break;
            case 'sales_low':
                sortKey = 'total_sales';
                ascending = true;
                break;
            case 'profit_high':
                sortKey = 'profit';
                ascending = false;
                break;
            case 'profit_low':
                sortKey = 'profit';
                ascending = true;
                break;
            default:
                sortKey = 'name';
                ascending = true;
        }
        
        // Sort rows
        rows.sort((a, b) => {
            let aVal, bVal;
            
            if (sortKey === 'name') {
                aVal = a.getAttribute('data-name');
                bVal = b.getAttribute('data-name');
                return ascending ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
            }
            else if (sortKey === 'balanced') {
                // Check if row has warn-row class (unbalanced)
                const aBalanced = !a.classList.contains('warn-row');
                const bBalanced = !b.classList.contains('warn-row');
                if (ascending) {
                    return aBalanced === bBalanced ? 0 : aBalanced ? -1 : 1;
                } else {
                    return aBalanced === bBalanced ? 0 : aBalanced ? 1 : -1;
                }
            }
            else if (sortKey === 'status') {
                // Get status from badge
                const aStatus = a.querySelector('.badge').textContent.trim().toLowerCase();
                const bStatus = b.querySelector('.badge').textContent.trim().toLowerCase();
                
                if (sortValue === 'status_settled') {
                    return aStatus === 'settled' && bStatus !== 'settled' ? -1 : 1;
                } else if (sortValue === 'status_underpaid') {
                    return aStatus === 'underpaid' && bStatus !== 'underpaid' ? -1 : 1;
                } else if (sortValue === 'status_overpaid') {
                    return aStatus === 'overpaid' && bStatus !== 'overpaid' ? -1 : 1;
                }
                return 0;
            }
            else {
                // Numeric sorting for sales and profit
                const aNum = parseFloat(a.querySelector(`td:nth-child(${getColumnIndex(sortKey)})`).textContent.replace(/,/g, '')) || 0;
                const bNum = parseFloat(b.querySelector(`td:nth-child(${getColumnIndex(sortKey)})`).textContent.replace(/,/g, '')) || 0;
                return ascending ? aNum - bNum : bNum - aNum;
            }
        });
        
        // Re-append rows in sorted order
        rows.forEach(row => {
            tbody.appendChild(row);
            // Also move the detail row if it exists
            const detailRow = row.nextElementSibling;
            if (detailRow && detailRow.classList.contains('detail-row')) {
                tbody.appendChild(detailRow);
            }
        });
        
        // Update row numbers
        updateRowNumbers();
    }
    
    function getColumnIndex(field) {
        const columnMap = {
            'total_sales': 6,  // T.Sales column (1-indexed)
            'profit': 11        // P/L column (1-indexed)
        };
        return columnMap[field] || 1;
    }
    
    function updateRowNumbers() {
        document.querySelectorAll('#shopTableBody .shop-row').forEach((row, index) => {
            row.cells[0].textContent = index + 1;
        });
    }

    // ── Cash modal ──
    function fmt(n) { return new Intl.NumberFormat().format(Math.round(n || 0)); }

    function openCashModal(i) {
        const s = shopData[i];
        document.getElementById('m-shop').textContent       = s.shop_name;
        document.getElementById('m-sales').textContent      = fmt(s.total_sales);
        document.getElementById('m-cash-sales').textContent = fmt(s.cash_sales);
        document.getElementById('m-exp').textContent        = fmt(s.expenses);
        document.getElementById('m-dsc').textContent        = fmt(s.discount);
        document.getElementById('m-expected').textContent   = fmt(s.expected_cash);
        document.getElementById('m-remaining').textContent  = fmt(s.cash_difference);
        document.getElementById('m-shop-id').value          = s.shop_id;
        document.getElementById('m-sales-hidden').value     = s.expected_cash;
        document.getElementById('cashAmount').value         = s.cash_difference > 0 ? s.cash_difference : 0;
        document.getElementById('cashModal').classList.add('open');
    }
    
    function openEditCashModal(i) {
        const s = shopData[i];
        document.getElementById('m-shop').textContent       = s.shop_name;
        document.getElementById('m-sales').textContent      = fmt(s.total_sales);
        document.getElementById('m-cash-sales').textContent = fmt(s.cash_sales);
        document.getElementById('m-exp').textContent        = fmt(s.expenses);
        document.getElementById('m-dsc').textContent        = fmt(s.discount);
        document.getElementById('m-expected').textContent   = fmt(s.expected_cash);
        document.getElementById('m-remaining').textContent  = fmt(s.cash_submitted);
        document.getElementById('m-shop-id').value          = s.shop_id;
        document.getElementById('m-sales-hidden').value     = s.expected_cash;
        document.getElementById('cashAmount').value         = s.cash_submitted;
        
        // Update modal title
        document.querySelector('#cashModal .modal-top h2').textContent = 'Edit Submitted Cash';
        
        document.getElementById('cashModal').classList.add('open');
    }

    function closeModal() {
        document.getElementById('cashModal').classList.remove('open');
        document.getElementById('cashAmount').value = '';
    }

    // Close on overlay click
    document.getElementById('cashModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    function setAmt(n) { document.getElementById('cashAmount').value = n; }

    // ── Exports ──
    function exportToExcel() { window.location.href = 'exportShopReport?date={{ $dateParam }}&format=excel'; }
    function exportToPDF()   { window.location.href = 'exportShopReport?date={{ $dateParam }}&format=pdf'; }

    // ── Auto-submit on date change ──
    document.getElementById('dateInput').addEventListener('change', function() {
        document.getElementById('dateFilterForm').submit();
    });
</script>
</body>
</html>