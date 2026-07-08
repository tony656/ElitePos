<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@lang('messages.main_store_report') — {{ config("app.name") }}</title>
    @include("links")
    
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Mono:wght@300;400;500&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-dark: #0F172A;
            --primary: #1E293B;
            --primary-light: #334155;
            --accent: #F59E0B;
            --accent-dark: #D97706;
            --accent-glow: rgba(245, 158, 11, 0.15);
            --success: #10B981;
            --success-dark: #059669;
            --danger: #EF4444;
            --danger-dark: #DC2626;
            --info: #3B82F6;
            --warning: #F59E0B;
            --gray-50: #F8FAFC;
            --gray-100: #F1F5F9;
            --gray-200: #E2E8F0;
            --gray-300: #CBD5E1;
            --gray-400: #94A3B8;
            --gray-500: #64748B;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1E293B;
            --gray-900: #0F172A;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: #F0F4F8;
            color: var(--gray-800);
            min-height: 100vh;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gray-400); }

        /* ── Layout ── */
        .wrap { padding: 1.75rem 2rem; }

        /* ── Header ── */
        .pg-header {
            background: linear-gradient(140deg, var(--primary-dark) 0%, #162032 60%, #1a2a40 100%);
            border-radius: 24px;
            padding: 1.75rem 2rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
            box-shadow: 0 20px 60px -10px rgba(15,23,42,0.4);
            position: relative;
            overflow: hidden;
        }

        .pg-header::after {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 260px; height: 260px;
            background: radial-gradient(circle, rgba(245,158,11,0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .pg-header::before {
            content: '';
            position: absolute;
            bottom: -80px; left: 30%;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(59,130,246,0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .header-left { display: flex; align-items: center; gap: 1.25rem; position: relative; z-index: 1; }

        .header-icon {
            width: 56px; height: 56px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; color: white;
            box-shadow: 0 8px 24px rgba(245,158,11,0.35);
            flex-shrink: 0;
        }

        .header-eyebrow {
            font-size: 0.6875rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 0.25rem;
        }

        .header-title h1 {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: white;
        }

        .header-title p {
            font-size: 0.8125rem;
            color: rgba(255,255,255,0.5);
            margin-top: 0.25rem;
            display: flex; align-items: center; gap: 0.375rem;
        }

        .btn-print {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            color: white;
            border: 1px solid rgba(255,255,255,0.15);
            padding: 0.625rem 1.375rem;
            border-radius: 14px;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
            display: flex; align-items: center; gap: 0.5rem;
            position: relative; z-index: 1;
        }

        .btn-print:hover {
            background: rgba(255,255,255,0.15);
            border-color: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        /* ── Filter Card ── */
        .filter-card {
            background: white;
            border-radius: 20px;
            padding: 1.375rem 1.625rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--gray-200);
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }

        .filter-label {
            font-size: 0.6875rem;
            font-weight: 500;
            color: var(--gray-400);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.5rem;
            display: block;
        }

        .filter-input {
            width: 100%;
            padding: 0.625rem 1rem;
            border-radius: 12px;
            border: 1.5px solid var(--gray-200);
            font-size: 0.875rem;
            color: var(--gray-800);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: var(--gray-50);
        }

        .filter-input:focus {
            border-color: var(--accent);
            background: white;
            box-shadow: 0 0 0 4px rgba(245,158,11,0.08);
        }

        .btn-filter {
            padding: 0.625rem 1.5rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 0.5rem;
            border: none;
        }

        .btn-filter-primary {
            background: linear-gradient(135deg, var(--info) 0%, #2563EB 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(59,130,246,0.3);
        }

        .btn-filter-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(59,130,246,0.4); }

        .btn-filter-reset {
            background: var(--gray-100);
            color: var(--gray-600);
            border: 1.5px solid var(--gray-200) !important;
        }

        .btn-filter-reset:hover { background: var(--gray-200); }

        /* ── Stats Grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.125rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--gray-200);
            padding: 1.375rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.25s, box-shadow 0.25s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.08);
        }

        .stat-accent-bar {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 20px 20px 0 0;
        }

        .stat-bg-icon {
            position: absolute;
            bottom: -10px; right: -4px;
            font-size: 5rem;
            opacity: 0.04;
            line-height: 1;
        }

        .stat-label {
            
            font-size: 0.6875rem;
            font-weight: 500;
            color: var(--gray-400);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 1rem;
        }

        .stat-value {
            
            font-size: 1.625rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1;
        }

        .stat-sub {
            font-size: 0.75rem;
            color: var(--gray-400);
            margin-top: 0.5rem;
            display: flex; align-items: center; gap: 0.375rem;
        }

        /* ── Panel ── */
        .panel {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--gray-200);
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(0,0,0,0.05);
        }

        .panel-header {
            padding: 1.25rem 1.625rem;
            border-bottom: 1px solid var(--gray-100);
            display: flex; align-items: center; justify-content: space-between;
            background: white;
        }

        .panel-header-title {
            
            font-size: 1rem;
            font-weight: 700;
            display: flex; align-items: center; gap: 0.625rem;
            color: var(--gray-900);
        }

        .tx-count {
            
            font-size: 0.75rem;
            background: var(--gray-100);
            color: var(--gray-500);
            padding: 0.2rem 0.625rem;
            border-radius: 99px;
            font-weight: 400;
        }

        /* Toggle all button */
        .btn-toggle-all {
            
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--info);
            background: rgba(59,130,246,0.06);
            border: 1px solid rgba(59,130,246,0.15);
            padding: 0.375rem 0.875rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex; align-items: center; gap: 0.375rem;
        }

        .btn-toggle-all:hover {
            background: rgba(59,130,246,0.12);
            border-color: rgba(59,130,246,0.3);
        }

        /* ── Table ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead th {
            background: #FAFBFC;
            color: var(--gray-400);
            
            font-size: 0.6875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            padding: 0.875rem 1.25rem;
            border-bottom: 1px solid var(--gray-100);
            white-space: nowrap;
        }

        .data-table tbody tr.main-row {
            border-bottom: 1px solid var(--gray-100);
            transition: background 0.15s;
        }

        .data-table tbody tr.main-row:hover {
            background: #FAFBFD;
        }

        .data-table tbody td {
            padding: 1rem 1.25rem;
            font-size: 0.875rem;
            vertical-align: middle;
        }

        .mono {  font-size: 0.8125rem; }

        .cell-date {
            
            font-size: 0.8125rem;
            color: var(--gray-500);
            white-space: nowrap;
        }

        .cell-shop strong {
            display: block;
            font-weight: 600;
            color: var(--gray-800);
        }

        .cell-shop span {
            
            font-size: 0.75rem;
            color: var(--gray-400);
        }

        .cell-value {
            
            font-size: 0.875rem;
            font-weight: 600;
        }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.25rem 0.625rem;
            border-radius: 8px;
            font-size: 0.6875rem;
            font-weight: 600;
            
            letter-spacing: 0.02em;
        }

        .badge-credit { background: rgba(239,68,68,0.08); color: #DC2626; }
        .badge-cash { background: rgba(16,185,129,0.08); color: #059669; }
        .badge-pending { background: rgba(245,158,11,0.1); color: #B45309; }
        .badge-approved { background: rgba(16,185,129,0.1); color: #059669; }

        /* Row expand button */
        .btn-expand {
            display: inline-flex; align-items: center; gap: 0.375rem;
            
            font-size: 0.6875rem;
            font-weight: 500;
            color: var(--gray-400);
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
            padding: 0.3rem 0.75rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-expand:hover { background: var(--gray-200); color: var(--gray-600); }
        .btn-expand.active { background: rgba(59,130,246,0.08); border-color: rgba(59,130,246,0.2); color: var(--info); }

        .expand-icon { transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); display: inline-block; }
        .btn-expand.active .expand-icon { transform: rotate(180deg); }

        /* ── Details Sub-Row ── */
        .details-row td { padding: 0; border: none; }

        .details-inner {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.4s cubic-bezier(0.4,0,0.2,1);
        }

        .details-inner.open { max-height: 600px; }

        .details-body {
            background: #FAFBFD;
            border-top: 1px dashed var(--gray-200);
            border-bottom: 1px solid var(--gray-100);
            padding: 1rem 1.5rem 1.25rem;
        }

        .details-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 0.875rem;
        }

        .details-title {
            
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--gray-600);
            display: flex; align-items: center; gap: 0.5rem;
        }

        .details-count {
            
            font-size: 0.6875rem;
            color: var(--gray-400);
            background: white;
            border: 1px solid var(--gray-200);
            padding: 0.15rem 0.5rem;
            border-radius: 6px;
        }

        .sub-table { width: 100%; border-collapse: collapse; }

        .sub-table thead th {
            
            font-size: 0.6875rem;
            font-weight: 500;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--gray-400);
            padding: 0.5rem 0.75rem;
            text-align: left;
            border-bottom: 1px solid var(--gray-200);
            background: white;
        }

        .sub-table tbody tr { transition: background 0.1s; }
        .sub-table tbody tr:hover { background: white; }

        .sub-table tbody td {
            padding: 0.625rem 0.75rem;
            font-size: 0.8125rem;
            color: var(--gray-700);
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .sub-table tbody tr:last-child td { border-bottom: none; }

        .sub-table-wrap {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            overflow: hidden;
        }

        /* Mark paid button */
        .btn-pay {
            display: inline-flex; align-items: center; gap: 0.375rem;
            background: linear-gradient(135deg, var(--success) 0%, var(--success-dark) 100%);
            color: white;
            border: none;
            padding: 0.375rem 0.875rem;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 3px 10px rgba(16,185,129,0.25);
        }

        .btn-pay:hover { transform: translateY(-2px); box-shadow: 0 5px 16px rgba(16,185,129,0.35); }

        /* ── Footer totals ── */
        .tfoot-row td {
            padding: 1rem 1.25rem;
            background: linear-gradient(135deg, var(--gray-50) 0%, #F0F4F8 100%);
            border-top: 2px solid var(--gray-200);
            
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .tfoot-label {
            
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--gray-500);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray-300);
        }

        .empty-state i { font-size: 3.5rem; display: block; margin-bottom: 1rem; }
        .empty-state p {  font-size: 1rem; font-weight: 600; color: var(--gray-400); }
        .empty-state small { font-size: 0.8125rem; color: var(--gray-300); }

        /* ── Responsive ── */
        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .wrap { padding: 1rem; }
            .pg-header { flex-direction: column; gap: 1rem; text-align: center; }
            .stats-grid { grid-template-columns: 1fr; }
            .data-table { font-size: 0.75rem; }
        }

        /* ── Print ── */
        @media print {
            .sidebar, .pg-header, .filter-card, .btn-print, .btn-pay, .btn-expand, .btn-toggle-all { display: none !important; }
            main { margin: 0 !important; padding: 0 !important; }
            .panel { box-shadow: none; }
            .details-inner { max-height: none !important; }
        }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .animate-in { animation: fadeUp 0.45s ease both; }
    </style>
</head>
<body>
        @include("sidenav")
        <main class="main-content">
            <div class="wrap">

                <!-- ── Header ── -->
                <div class="pg-header animate-in">
                    <div class="header-left">
                        <div class="header-icon">
                            <i class="bi bi-building-fill"></i>
                        </div>
                        <div class="header-title">
                            <div class="header-eyebrow">@lang('messages.account_id_outgoing')</div>
                            <h1>@lang('messages.main_store_report')</h1>
                            <p><i class="bi bi-arrow-right-circle-fill"></i> @lang('messages.stock_transfers_to_shops')</p>
                        </div>
                    </div>
                    <button class="btn-print" onclick="window.print()">
                        <i class="bi bi-printer-fill"></i>
                        <span>@lang('messages.print')</span>
                    </button>
                </div>

                <!-- ── Filters ── -->
                <form method="GET" action="{{ url('mainStoreReport') }}" class="filter-card animate-in" style="animation-delay:.08s">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="filter-label"><i class="bi bi-calendar3"></i> @lang('messages.from_date')</label>
                            <input type="date" name="date_from" class="filter-input" value="{{ $dateFrom }}">
                        </div>
                        <div class="col-md-3">
                            <label class="filter-label"><i class="bi bi-calendar3"></i> @lang('messages.to_date')</label>
                            <input type="date" name="date_to" class="filter-input" value="{{ $dateTo }}">
                        </div>
                        <div class="col-md-6">
                            <div style="display:flex; gap:.75rem; padding-top:.1rem;">
                                <button type="submit" class="btn-filter btn-filter-primary">
                                    <i class="bi bi-funnel-fill"></i> @lang('messages.apply_filter')
                                </button>
                                <a href="{{ url('main-store-report') }}" class="btn-filter btn-filter-reset" style="text-decoration:none; border:1.5px solid var(--gray-200);">
                                    <i class="bi bi-arrow-counterclockwise"></i> @lang('messages.reset')
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- ── Stats ── -->
                <div class="stats-grid animate-in" style="animation-delay:.16s">
                    <div class="stat-card">
                        <div class="stat-accent-bar" style="background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);"></div>
                        <div class="stat-bg-icon"><i class="bi bi-coin"></i></div>
                        <div class="stat-label">@lang('messages.total_requested')</div>
                        <div class="stat-value" style="color:var(--gray-900)">Tsh {{ number_format($grandTotals->value) }}</div>
                        <div class="stat-sub"><i class="bi bi-box-seam"></i> @lang('messages.all_requests')</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-accent-bar" style="background: linear-gradient(90deg, var(--success) 0%, var(--success-dark) 100%);"></div>
                        <div class="stat-bg-icon"><i class="bi bi-check-circle"></i></div>
                        <div class="stat-label">@lang('messages.approved_value')</div>
                        <div class="stat-value" style="color:var(--success-dark)">Tsh {{ number_format($grandTotals->approved) }}</div>
                        <div class="stat-sub"><i class="bi bi-graph-up-arrow"></i> @lang('messages.processed')</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-accent-bar" style="background: linear-gradient(90deg, var(--danger) 0%, var(--danger-dark) 100%);"></div>
                        <div class="stat-bg-icon"><i class="bi bi-credit-card"></i></div>
                        <div class="stat-label">@lang('messages.total_credit')</div>
                        <div class="stat-value" style="color:var(--danger-dark)">Tsh {{ number_format($grandTotals->credit) }}</div>
                        <div class="stat-sub"><i class="bi bi-hourglass-split"></i> @lang('messages.pending_payments')</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-accent-bar" style="background: linear-gradient(90deg, var(--warning) 0%, #E58600 100%);"></div>
                        <div class="stat-bg-icon"><i class="bi bi-clock-history"></i></div>
                        <div class="stat-label">@lang('messages.pending_approval')</div>
                        <div class="stat-value" style="color:#B45309">Tsh {{ number_format($grandTotals->value - $grandTotals->approved) }}</div>
                        <div class="stat-sub"><i class="bi bi-exclamation-circle"></i> @lang('messages.awaiting')</div>
                    </div>
                </div>

                <!-- ── Main Table ── -->
                <div class="panel animate-in" style="animation-delay:.24s">
                    <div class="panel-header">
                        <div class="panel-header-title">
                            <i class="bi bi-table"></i>
                            @lang('messages.daily_transfer_logs')
                            <span class="tx-count">{{ $reportRows->count() }} @lang('messages.transactions')</span>
                        </div>
                        <button class="btn-toggle-all" id="toggleAllBtn" type="button" onclick="toggleAll()">
                            <i class="bi bi-chevron-expand"></i>
                            <span id="toggleAllLabel">@lang('messages.expand_all')</span>
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table" id="mainTable">
                            <thead>
                                <tr>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('messages.shop')</th>
                                    <th>@lang('messages.items')</th>
                                    <th>@lang('messages.total_amount')</th>
                                    <th>@lang('messages.approved')</th>
                                    <th>Approved Rec. Qty</th>
                                    <th>Approved Rec. Value</th>
                                    <th>Diff Qty</th>
                                    <th>Diff Value</th>
                                    <th>@lang('messages.credit')</th>
                                    <th>@lang('messages.cash')</th>
                                    <th>@lang('messages.sold')</th>
                                    <th>@lang('messages.payment')</th>
                                    <th>@lang('messages.return')</th>
                                    <th>@lang('messages.difference')</th>
                                    <th>@lang('messages.actions')</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reportRows as $row)
                              
                                <!-- Main Row -->
                                <tr class="main-row">
                                    <td class="cell-date">
                                        <i class="bi bi-calendar3" style="color:var(--gray-300); margin-right:.3rem;"></i>{{ date('M-d-Y', strtotime($row->date)) }}
                                    </td>
                                    <td class="cell-shop">
                                        <strong>{{ $row->shop_name }}</strong>
                                        <span>ID: {{ $row->shop_id }}</span>
                                    </td>
                                    <td>
                                        <span class="mono" style="color:var(--gray-600)">
                                            <i class="bi bi-boxes" style="color:var(--gray-300)"></i>
                                            {{ number_format($row->total_qty) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="cell-value" style="color:var(--gray-900)">
                                            Tsh {{ number_format($row->total_value) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="cell-value" style="color:var(--success-dark)">
                                            Tsh {{ number_format($row->approved_request_value) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="cell-value" style="color:var(--gray-900)">
                                            {{ number_format($row->approved_receiving_qty) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="cell-value" style="color:var(--gray-900)">
                                            Tsh {{ number_format($row->approved_receiving_value) }}
                                        </span>
                                    </td>
                                    <td style="color:var(--danger-dark)">
                                        <span class="cell-value">
                                            {{ number_format($row->approved_qty_diff) }}
                                        </span>
                                    </td>
                                    <td style="color:var(--danger-dark)">
                                        <span class="cell-value">
                                            Tsh {{ number_format($row->approved_value_diff) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="cell-value" style="color:var(--danger-dark)">
                                            Tsh {{ number_format($row->credit_value) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="cell-value" style="color:var(--info)">
                                            Tsh {{ number_format($row->cash_value) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="cell-value" style="color:var(--info)">
                                            Tsh {{ number_format(($row->shop_total_sales + $row->shop_discount) - $row->returned_value) }}
                                        </span>
                                    </td>
                                    <td class="cell-value">
                                        <div style="display:flex; gap:.25rem; flex-wrap:wrap;">
                                           Tsh {{ number_format($row->paidcash_value) }}
                                        </div>
                                    </td>
                                    <td class="cell-value">
                                       Tsh {{ number_format($row->returned_value) }}
                                    </td>
                                    <td class="cell-value" style="color:var(--danger-dark)">
                                        @if (($row->credit_value - $row->paidcash_value) == 0)
                                            0 <span class="badge badge-approved"><i class="bi bi-check-circle"></i> Settled</span>
                                        @else
                                            Tsh {{ number_format( $row->credit_value - $row->paidcash_value ) }}
                                        @endif
                                        
                                    </td>
                                    <td>
                                        <button class="btn-expand" type="button"
                                                onclick="toggleRow(this, 'details-{{ $loop->index }}')"
                                                 title="@lang('messages.toggle_item_details')">
                                              <span class="expand-icon"><i class="bi bi-chevron-down"></i></span>
                                              <span class="expand-label">@lang('messages.details')</span>
                                         </button>
                                    </td>
                                </tr>

                                <!-- Details Row -->
                                <tr class="details-row">
                                    <td colspan="15">
                                        <div class="details-inner" id="details-{{ $loop->index }}">
                                            <div class="details-body">
                                                <div class="details-header">
                                                    <div class="details-title">
                                                        <i class="bi bi-receipt"></i>
                                                        @lang('messages.item_breakdown')
                                                        <span class="details-count">{{ $row->items->count() }} @lang('messages.items')</span>
                                                    </div>
                                                </div>
                                                <div class="sub-table-wrap">
                                                    <table class="sub-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Product Name</th>
                                                                <th>Qty</th>
                                                                <th>Unit Price</th>
                                                                <th>Subtotal</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($row->items as $item)
                                                            @php
                                                                $theName = DB::table('products')->where('product_id', $item->productId)->value('name01');
                                                            @endphp
                                                            <tr>
                                                                <td style="font-weight:500">{{ $theName ?? 'N/A' }}</td>
                                                                <td class="mono">{{ number_format($item->quantity) }}</td>
                                                                <td class="mono">Tsh {{ number_format($item->price) }}</td>
                                                                <td class="mono" style="font-weight:600">Tsh {{ number_format($item->totalPrice) }}</td>
                                                                <td>
                                                                    @if($item->status == 'Approved')
                                                                        <span class="badge badge-approved"><i class="bi bi-check-circle"></i> Approved</span>
                                                                    @else
                                                                        <span class="badge badge-pending"><i class="bi bi-clock"></i> Pending</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="15">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <p>No requests found</p>
                                            <small>Try adjusting your date filter</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>

                            @if($reportRows->isNotEmpty())
                            <tfoot>
                                <tr class="tfoot-row">
                                    <td colspan="3" style="text-align:right">
                                        <span class="tfoot-label">Grand Totals</span>
                                    </td>
                                    <td class="mono" style="color:var(--gray-900)">Tsh {{ number_format($grandTotals->value) }}</td>
                                    <td class="mono" style="color:var(--success-dark)">Tsh {{ number_format($grandTotals->approved) }}</td>
                                    <td colspan="4"></td>
                                    <td class="mono" style="color:var(--danger-dark)">Tsh {{ number_format($grandTotals->credit) }}</td>
                                    <td colspan="6"></td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

            </div>
        </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    /* ── Per-row toggle ── */
    function toggleRow(btn, id) {
        const panel = document.getElementById(id);
        const isOpen = panel.classList.contains('open');

        panel.classList.toggle('open', !isOpen);
        btn.classList.toggle('active', !isOpen);

        const label = btn.querySelector('.expand-label');
        if (label) label.textContent = isOpen ? 'Details' : 'Hide';

        syncToggleAllLabel();
    }

    /* ── Expand / Collapse All ── */
    let allExpanded = false;

    function toggleAll() {
        allExpanded = !allExpanded;

        document.querySelectorAll('.details-inner').forEach(panel => {
            panel.classList.toggle('open', allExpanded);
        });

        document.querySelectorAll('.btn-expand').forEach(btn => {
            btn.classList.toggle('active', allExpanded);
            const label = btn.querySelector('.expand-label');
            if (label) label.textContent = allExpanded ? 'Hide' : 'Details';
        });

        const lbl = document.getElementById('toggleAllLabel');
        if (lbl) lbl.textContent = allExpanded ? 'Collapse All' : 'Expand All';

        const icon = document.querySelector('#toggleAllBtn i');
        if (icon) {
            icon.className = allExpanded ? 'bi bi-chevron-contract' : 'bi bi-chevron-expand';
        }
    }

    /* Keep "Expand All" label in sync when rows toggled individually */
    function syncToggleAllLabel() {
        const panels = document.querySelectorAll('.details-inner');
        const openCount = document.querySelectorAll('.details-inner.open').length;

        allExpanded = (openCount === panels.length && panels.length > 0);

        const lbl = document.getElementById('toggleAllLabel');
        if (lbl) lbl.textContent = allExpanded ? 'Collapse All' : 'Expand All';

        const icon = document.querySelector('#toggleAllBtn i');
        if (icon) {
            icon.className = allExpanded ? 'bi bi-chevron-contract' : 'bi bi-chevron-expand';
        }
    }

    /* ── Staggered entrance for stat cards ── */
    document.querySelectorAll('.stat-card').forEach((card, i) => {
        card.style.animationDelay = `${0.18 + i * 0.06}s`;
        card.classList.add('animate-in');
        card.style.opacity = '0';
    });
</script>
</body>
</html>