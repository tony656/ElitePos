<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Sales Dashboard</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --navy:          #0B1E3D;
            --navy-mid:      #112952;
            --navy-light:    #1A3A6B;
            --amber:         #F59E0B;
            --amber-dark:    #D97706;
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
            padding: 1.4rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.28);
            position: relative;
            overflow: hidden;
        }

        .pg-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 350px;
            height: 350px;
            background: rgba(245,158,11,0.08);
            border-radius: 50%;
            pointer-events: none;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .back-btn {
            width: 42px; height: 42px;
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
            border: 1.5px solid rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            color: var(--white);
            cursor: pointer;
            transition: all 0.18s;
            text-decoration: none;
        }
        .back-btn:hover {
            background: rgba(255,255,255,0.2);
            border-color: var(--amber);
            color: var(--amber);
        }

        .pg-icon-wrap {
            width: 52px; height: 52px;
            background: rgba(245,158,11,0.15);
            border: 1.5px solid rgba(245,158,11,0.3);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--amber);
            font-size: 1.5rem;
        }

        .pg-title-wrap h1 {
            color: var(--white); font-size: 1.45rem; font-weight: 700;
            margin: 0 0 0.15rem 0;
        }
        .pg-subtitle {
            color: rgba(255,255,255,0.7); font-size: 0.82rem;
            margin: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            flex-wrap: wrap;
        }

        .shop-select-wrap {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .shop-label {
            color: rgba(255,255,255,0.9);
            font-size: 0.82rem;
            font-weight: 600;
        }

        .shop-select {
            padding: 0.5rem 0.85rem;
            border: 1.5px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
            color: var(--white);
            font-size: 0.82rem;
            outline: none;
            cursor: pointer;
            transition: all 0.18s;
            min-width: 200px;
            font-family: 'Outfit', sans-serif;
        }
        .shop-select:focus {
            background: rgba(255,255,255,0.2);
            border-color: var(--amber);
        }
        .shop-select option {
            background: var(--navy);
            color: var(--white);
        }

        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.55rem 1rem;
            background: var(--amber);
            color: var(--navy);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 3px 12px rgba(245,158,11,0.3);
            transition: all 0.18s;
            text-decoration: none;
        }
        .btn-export:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 18px rgba(245,158,11,0.4);
            color: var(--navy);
        }

        /* ── Stat cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            opacity: 0;
            animation: slideUp 0.4s ease forwards;
        }

        .stat-card.s1 { animation-delay: 0s; border-top: 3px solid var(--navy); }
        .stat-card.s2 { animation-delay: 0.05s; border-top: 3px solid var(--rose); }
        .stat-card.s3 { animation-delay: 0.1s; border-top: 3px solid var(--amber); }
        .stat-card.s4 { animation-delay: 0.15s; border-top: 3px solid var(--emerald); }

        .stat-icon-box {
            width: 52px; height: 52px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 0.85rem;
        }

        .stat-icon-box.navy { background: rgba(11,30,61,0.1); color: var(--navy); }
        .stat-icon-box.rose { background: var(--rose-pale); color: var(--rose); }
        .stat-icon-box.amber { background: var(--amber-pale); color: #92400E; }
        .stat-icon-box.emerald { background: var(--emerald-pale); color: var(--emerald); }

        .stat-value {
            font-family: 'DM Mono', monospace;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 0.35rem;
        }

        .stat-label {
            font-size: 0.78rem;
            color: var(--slate-500);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.75rem;
        }

        .stat-meta {
            display: flex;
            justify-content: space-between;
            padding-top: 0.75rem;
            border-top: 1px solid var(--slate-200);
            font-size: 0.78rem;
            color: var(--slate-500);
        }

        .stat-meta-value {
            font-family: 'DM Mono', monospace;
            font-weight: 600;
            color: var(--slate-700);
        }

        /* ── Content grid ── */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1.5rem;
        }

        /* ── Search panel ── */
        .search-panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
        }

        .search-wrap {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: 0.65rem 1rem 0.65rem 2.75rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            background: var(--slate-50);
            font-size: 0.875rem;
            color: var(--slate-800);
            outline: none;
            transition: all 0.18s;
            font-family: 'Outfit', sans-serif;
        }
        .search-input::placeholder {
            color: var(--slate-400);
        }
        .search-input:focus {
            border-color: var(--navy-light);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        /* ── Sales table ── */
        .table-card {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            overflow: hidden;
        }

        table.sales-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        table.sales-tbl thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 0.85rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
        }

        table.sales-tbl tbody td {
            padding: 0.85rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
            color: var(--slate-800);
        }

        table.sales-tbl tbody tr:hover td {
            background: #F8FAFF;
        }

        .amt-mono {
            font-family: 'DM Mono', monospace;
            font-weight: 600;
            font-size: 0.82rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.3rem 0.6rem;
            border-radius: 5px;
        }

        .status-badge.success {
            background: var(--emerald-pale);
            color: #065F46;
        }
        .status-badge.danger {
            background: var(--rose-pale);
            color: #9F1239;
        }
        .status-badge.secondary {
            background: var(--slate-200);
            color: var(--slate-700);
        }

        .action-btns {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .btn-view {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.4rem 0.75rem;
            background: transparent;
            color: var(--sky);
            border: 1.5px solid var(--sky);
            border-radius: 7px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-view:hover {
            background: var(--sky);
            color: var(--white);
        }

        .btn-undo {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.4rem 0.75rem;
            background: transparent;
            color: var(--slate-600);
            border: 1.5px solid var(--slate-300);
            border-radius: 7px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-undo:hover {
            background: var(--slate-600);
            color: var(--white);
            border-color: var(--slate-600);
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 4rem 1.5rem;
        }
        .empty-icon {
            width: 80px; height: 80px;
            margin: 0 auto 1rem;
            display: flex; align-items: center; justify-content: center;
            background: var(--slate-100);
            border-radius: 50%;
            color: var(--slate-400);
            font-size: 2rem;
        }
        .empty-title {
            font-size: 1.1rem; font-weight: 700;
            color: var(--slate-600);
            margin-bottom: 0.4rem;
        }
        .empty-desc {
            font-size: 0.875rem; color: var(--slate-500);
        }

        /* ── Sidebar ── */
        .sidebar-section {
            margin-bottom: 1.25rem;
        }

        /* ── Calendar ── */
        .calendar-card {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.85rem;
            border-bottom: 1.5px solid var(--slate-200);
        }

        .calendar-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .calendar-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.85rem;
        }

        .calendar-nav-btn {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: transparent;
            border: 1.5px solid var(--slate-300);
            color: var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.18s;
        }
        .calendar-nav-btn:hover {
            background: var(--navy);
            color: var(--white);
            border-color: var(--navy);
        }

        .calendar-month {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--navy);
        }

        .calendar-table {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar-table th {
            padding: 0.5rem 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--slate-500);
            text-align: center;
        }

        .calendar-table td {
            padding: 0.45rem 0.25rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.15s;
            font-size: 0.8rem;
            color: var(--slate-700);
        }

        .calendar-table td:hover {
            background: var(--amber-pale);
            border-radius: 6px;
            color: #92400E;
        }

        .calendar-table td.current-date {
            background: var(--navy);
            color: var(--white);
            border-radius: 6px;
            font-weight: 700;
        }

        .calendar-table td.sales-date {
            background: var(--emerald-pale);
            color: #065F46;
            border-radius: 6px;
            font-weight: 600;
        }

        .calendar-table td.sales-date:hover {
            background: var(--emerald);
            color: var(--white);
        }

        /* ── Summary card ── */
        .summary-card {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
        }

        .summary-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 1rem;
            padding-bottom: 0.85rem;
            border-bottom: 1.5px solid var(--slate-200);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.65rem 0;
            border-bottom: 1px solid var(--slate-100);
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            font-size: 0.82rem;
            color: var(--slate-600);
        }

        .summary-value {
            font-family: 'DM Mono', monospace;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--navy);
        }

        /* ── Responsive ── */
        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
            .pg-header { padding: 1rem; margin-bottom: 1rem; }
            .header-row { flex-direction: column; align-items: flex-start; }
            .header-actions { width: 100%; flex-direction: column; }
            .shop-select { width: 100%; }
            .btn-export { width: 100%; justify-content: center; }
            .stats-grid { grid-template-columns: 1fr; }
            .content-grid { grid-template-columns: 1fr; }

            table.sales-tbl thead { display: none; }
            table.sales-tbl tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1.5px solid var(--slate-200);
                border-radius: 10px;
                padding: 1rem;
                background: var(--white);
            }
            table.sales-tbl tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.65rem 0;
                border-bottom: 1px solid var(--slate-100);
            }
            table.sales-tbl tbody td:last-child {
                border-bottom: none;
                padding-top: 0.85rem;
                border-top: 1px solid var(--slate-200);
                margin-top: 0.5rem;
            }
            table.sales-tbl tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--slate-500);
                min-width: 100px;
                font-size: 0.75rem;
            }
            .action-btns { width: 100%; justify-content: flex-end; }
        }

        /* ── Animation ── */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
        <div class="main-wrap">

            {{-- ── Page Header ── --}}
            <div class="pg-header">
                <div class="header-row">
                    <div class="header-left">
                        <a href="javascript:history.back()" class="back-btn">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                        <div class="pg-icon-wrap">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="pg-title-wrap">
                            <h1>Sales Dashboard</h1>
                            <p class="pg-subtitle">Track and analyze sales performance</p>
                        </div>
                    </div>
                    <div class="header-actions">
                        @if(isset($allShops) && $allShops->count() > 1)
                        <div class="shop-select-wrap">
                            <label class="shop-label">Shop:</label>
                            <select class="shop-select" onchange="changeShop(this.value)">
                                @foreach($allShops as $shop)
                                <option value="{{ $shop->id }}" 
                                    {{ (session('selected_shop_id') == $shop->id || (!session('selected_shop_id') && $shop->is_primary)) ? 'selected' : '' }}>
                                    {{ $shop->name }} ({{ $shop->location ?? 'N/A' }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <a href="{{ route('admin.sales.export', ['selectedDate' => request('selectedDate')]) }}" 
                           class="btn-export">
                            <i class="bi bi-download"></i> Excel Report
                        </a>
                    </div>
                </div>
            </div>

            {{-- ── Stat Cards ── --}}
            <div class="stats-grid">
                <div class="stat-card s1">
                    <div class="stat-icon-box navy">
                        <i class="bi bi-currency-exchange"></i>
                    </div>
                    <div class="stat-value">Tsh {{ number_format($Tsale) }}</div>
                    <div class="stat-label">Total Sales</div>
                    <div class="stat-meta">
                        <span>This Month</span>
                        <span class="stat-meta-value">Tsh {{ number_format($Msale) }}</span>
                    </div>
                </div>

                <div class="stat-card s2">
                    <div class="stat-icon-box rose">
                        <i class="bi bi-tag"></i>
                    </div>
                    <div class="stat-value">Tsh {{ number_format($Tdiscount) }}</div>
                    <div class="stat-label">Total Discounts</div>
                    <div class="stat-meta">
                        <span>This Month</span>
                        <span class="stat-meta-value">Tsh {{ number_format($Mdiscount) }}</span>
                    </div>
                </div>

                <div class="stat-card s3">
                    <div class="stat-icon-box amber">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="stat-value">{{ number_format($Tdebt) }}</div>
                    <div class="stat-label">Total Debts</div>
                    <div class="stat-meta">
                        <span>This Month</span>
                        <span class="stat-meta-value">{{ number_format($Mdebt) }}</span>
                    </div>
                </div>

                <div class="stat-card s4">
                    <div class="stat-icon-box emerald">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-value">Tsh {{ number_format($TNetProfit) }}</div>
                    <div class="stat-label">Total Profit</div>
                    <div class="stat-meta">
                        <span>This Month</span>
                        <span class="stat-meta-value">Tsh {{ number_format($MoNetProfit) }}</span>
                    </div>
                </div>
            </div>

            {{-- ── Content Grid ── --}}
            <div class="content-grid">
                {{-- Main content --}}
                <div>
                    {{-- Search Panel --}}
                    <div class="search-panel">
                        <div class="search-wrap">
                            <i class="bi bi-search search-icon"></i>
                            <input type="search" id="search-input" class="search-input" 
                                placeholder="Search by customer name, sales ID, agent...">
                        </div>
                    </div>

                    {{-- Sales Table --}}
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="sales-tbl">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Date</th>
                                        <th>Sales ID</th>
                                        <th>Customer</th>
                                        <th>Sales Agent</th>
                                        <th>Status</th>
                                        <th style="text-align:center;">Qty</th>
                                        <th style="text-align:right;">Paid</th>
                                        <th style="text-align:right;">Credit</th>
                                        <th style="text-align:right;">Total</th>
                                        <th style="text-align:right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($sales->isEmpty())
                                    <tr>
                                        <td colspan="11">
                                            <div class="empty-state">
                                                <div class="empty-icon">
                                                    <i class="bi bi-graph-up"></i>
                                                </div>
                                                <div class="empty-title">No Sales Found</div>
                                                <p class="empty-desc">Sales transactions will appear here</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @else
                                        @foreach ($sales as $index => $sale)
                                        @php
                                            if($sale->status == 'Debt') {
                                                $statusClass = 'danger';
                                                $statusText = 'Credit';
                                            } else if($sale->status == 'Return') {
                                                $statusClass = 'secondary';
                                                $statusText = 'Returned';
                                            } else {
                                                $statusClass = 'success';
                                                $statusText = $sale->transactionType ?? 'Cash';
                                            }
                                        @endphp
                                        <tr>
                                            <td data-label="#">{{ $index + 1 }}</td>
                                            <td data-label="Date">{{ date('M d, Y', strtotime($sale->created_at)) }}</td>
                                            <td data-label="Sales ID">{{ $sale->salesName }}</td>
                                            <td data-label="Customer">{{ $sale->cName }}</td>
                                            <td data-label="Sales Agent">{{ $sale->served_by }}</td>
                                            <td data-label="Status">
                                                <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td data-label="Qty" style="text-align:center;">
                                                <span class="amt-mono">{{ number_format($sale->totalQuantity ?? 0) }}</span>
                                            </td>
                                            <td data-label="Paid" style="text-align:right;">
                                                <span class="amt-mono">Tsh {{ number_format($sale->totalPaid ?? 0) }}</span>
                                            </td>
                                            <td data-label="Credit" style="text-align:right;">
                                                <span class="amt-mono">Tsh {{ number_format($sale->totalCredit ?? 0) }}</span>
                                            </td>
                                            <td data-label="Total" style="text-align:right;">
                                                <span class="amt-mono">Tsh {{ number_format($sale->totalPrice ?? 0) }}</span>
                                            </td>
                                            <td data-label="Actions">
                                                <div class="action-btns">
                                                    <form method="post" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="account" value="{{ $sale->account }}">
                                                        <button class="btn-view" formaction="/admin/viewSales" 
                                                            name="sales_id" value="{{ $sale->sales_id }}">
                                                            <i class="bi bi-eye"></i> View
                                                        </button>
                                                        <button class="btn-undo" formaction="/admin/undoSales" 
                                                            name="salesName" value="{{ $sale->salesName }}">
                                                            <i class="bi bi-x-circle"></i> Undo
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div>
                    {{-- Calendar Widget --}}
                    <div class="sidebar-section">
                        <div class="calendar-card">
                            <div class="calendar-header">
                                <h6 class="calendar-title">Filter by Date</h6>
                            </div>

                            <div class="calendar-nav">
                                <button id="prevMonth" class="calendar-nav-btn">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <div class="calendar-month" id="currentMonthYear"></div>
                                <button id="nextMonth" class="calendar-nav-btn">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>

                            <div id="calendar"></div>

                            <form id="dateForm" action="{{ route('admin.saleDate') }}" method="post">
                                @csrf
                                <input type="hidden" id="selectedDate" name="selectedDate">
                            </form>
                        </div>
                    </div>

                    {{-- Summary Card --}}
                    <div class="sidebar-section">
                        <div class="summary-card">
                            <h6 class="summary-title">Summary</h6>
                            <div class="summary-row">
                                <span class="summary-label">Total Sales</span>
                                <span class="summary-value">Tsh {{ number_format($Tsale ?? 0) }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Transactions</span>
                                <span class="summary-value">{{ $sales->count() }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Avg. Sale</span>
                                <span class="summary-value">Tsh {{ number_format(($sales->count() > 0 ? ($Tsale ?? 0) / $sales->count() : 0)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let saleDates = @json($monthlySaleDates);

    // Function to fetch sales dates for a specific month
    function fetchSalesDates(year, month) {
        const shopId = new URLSearchParams(window.location.search).get('shop_id');
        let url = `/admin/getSalesDates?year=${year}&month=${month}`;
        if (shopId) {
            url += `&shop_id=${shopId}`;
        }
        fetch(url)
            .then(response => response.json())
            .then(data => {
                saleDates = data.dates;
                createCalendar(month, year);
            })
            .catch(error => console.error('Error fetching sales dates:', error));
    }

    // Function to check if there are sales on a specific date
    function checkSalesForDate(dateString) {
        return saleDates.includes(dateString);
    }

    // Search functionality
    $(document).ready(function() {
        $('#search-input').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    // Calendar functionality
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

    function createCalendar(month, year) {
        const calendar = document.getElementById('calendar');
        calendar.innerHTML = '';

        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const firstDay = new Date(year, month, 1).getDay();

        const monthNames = [
            'January', 'February', 'March', 'April', 'May',
            'June', 'July', 'August', 'September', 'October',
            'November', 'December'
        ];
        document.getElementById('currentMonthYear').innerText = `${monthNames[month]} ${year}`;

        const today = new Date();
        const todayDay = today.getDate();
        const todayMonth = today.getMonth();
        const todayYear = today.getFullYear();

        const daysHeader = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
        const table = document.createElement('table');
        table.classList.add('calendar-table');

        const headerRow = document.createElement('tr');
        daysHeader.forEach(day => {
            const th = document.createElement('th');
            th.innerText = day;
            headerRow.appendChild(th);
        });
        table.appendChild(headerRow);

        let row = document.createElement('tr');

        for (let i = 0; i < firstDay; i++) {
            const td = document.createElement('td');
            row.appendChild(td);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const td = document.createElement('td');
            td.innerText = day;

            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            // Highlight today's date
            if (day === todayDay && month === todayMonth && year === todayYear) {
                td.classList.add('current-date');
            }

            // Highlight sale dates
            if (saleDates.includes(dateString)) {
                td.classList.add('sales-date');
            }

            td.addEventListener('click', function() {
                const clickedMonth = month;
                const clickedYear = year;
                const formattedDate = `${clickedYear}-${String(clickedMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                
                // Only submit if there are sales on this date
                if (checkSalesForDate(formattedDate)) {
                    document.getElementById('selectedDate').value = formattedDate;
                    document.getElementById('dateForm').submit();
                }
            });

            row.appendChild(td);

            if ((day + firstDay) % 7 === 0) {
                table.appendChild(row);
                row = document.createElement('tr');
            }
        }

        if (row.children.length > 0) {
            table.appendChild(row);
        }

        calendar.appendChild(table);
    }

    document.getElementById('prevMonth').addEventListener('click', function() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        fetchSalesDates(currentYear, currentMonth + 1);
    });

    document.getElementById('nextMonth').addEventListener('click', function() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        fetchSalesDates(currentYear, currentMonth + 1);
    });

    createCalendar(currentMonth, currentYear);

    // Shop change function
    function changeShop(shopId) {
        const url = new URL(window.location.href);
        url.searchParams.set('shop_id', shopId);
        window.location.href = url.toString();
    }
</script>

</body>
</html>