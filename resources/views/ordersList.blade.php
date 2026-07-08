<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - @lang('messages.invoices_management')</title>
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
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
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

        .pg-header-content {
            display: flex; align-items: center; gap: 1rem;
            position: relative;
            z-index: 1;
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

        .pg-actions {
            display: flex; gap: 0.75rem;
            position: relative;
            z-index: 1;
        }

        .btn-header {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.82rem; font-weight: 600;
            padding: 0.5rem 0.95rem;
            border: 1.5px solid rgba(255,255,255,0.25);
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
            color: var(--white);
            cursor: pointer;
            transition: all 0.18s;
        }
        .btn-header:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.4);
            transform: translateY(-1px);
        }

        /* ── Stats grid ── */
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
            padding: 1.4rem 1.25rem;
            position: relative;
            overflow: hidden;
            transition: all 0.25s;
        }
        .stat-card:hover {
            box-shadow: 0 8px 24px rgba(11,30,61,0.12);
            transform: translateY(-3px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 3px;
        }
        .stat-card.total::before { background: var(--navy); }
        .stat-card.debt::before { background: var(--rose); }
        .stat-card.pending::before { background: var(--amber); }
        .stat-card.completed::before { background: var(--emerald); }

        .stat-layout {
            display: flex; justify-content: space-between; align-items: flex-start;
        }

        .stat-info { flex: 1; }

        .stat-label {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.04em; color: var(--slate-400);
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-family: 'DM Mono', monospace;
            font-size: 1.75rem; font-weight: 500;
            color: var(--navy);
            margin-bottom: 0.25rem;
        }

        .stat-meta {
            font-size: 0.78rem; color: var(--slate-500);
        }

        .stat-icon-box {
            width: 52px; height: 52px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .stat-card.total .stat-icon-box { background: rgba(11,30,61,0.1); color: var(--navy); }
        .stat-card.debt .stat-icon-box { background: var(--rose-pale); color: var(--rose); }
        .stat-card.pending .stat-icon-box { background: var(--amber-pale); color: var(--amber-dark); }
        .stat-card.completed .stat-icon-box { background: var(--emerald-pale); color: var(--emerald); }

        /* ── Search panel ── */
        .search-panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
        }

        .search-row {
            display: flex; gap: 0.85rem; flex-wrap: wrap; align-items: center;
        }

        .search-wrap {
            flex: 1;
            min-width: 280px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 0.6rem 0.9rem 0.6rem 2.5rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            background: var(--slate-50);
            font-size: 0.875rem;
            color: var(--slate-800);
            outline: none;
            transition: all 0.18s;
        }
        .search-input:focus {
            border-color: var(--navy-light);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        .search-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
            font-size: 0.95rem;
        }

        .search-actions {
            display: flex; gap: 0.75rem;
        }

        .btn-filter {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.82rem; font-weight: 600;
            padding: 0.55rem 1rem;
            background: var(--amber); color: var(--navy);
            border: none; border-radius: 8px;
            cursor: pointer;
            transition: all 0.18s;
            box-shadow: 0 3px 12px rgba(245,158,11,0.3);
        }
        .btn-filter:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 18px rgba(245,158,11,0.4);
        }

        .btn-clear {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.82rem; font-weight: 600;
            padding: 0.55rem 1rem;
            background: transparent;
            border: 1.5px solid var(--slate-300);
            border-radius: 8px;
            color: var(--slate-600);
            cursor: pointer;
            transition: all 0.18s;
        }
        .btn-clear:hover {
            background: var(--slate-50);
            border-color: var(--slate-400);
        }

        /* ── Table container ── */
        .table-container {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .table-head {
            background: var(--slate-50);
            border-bottom: 1.5px solid var(--slate-200);
            padding: 1.1rem 1.25rem;
            display: flex; justify-content: space-between; align-items: center;
        }

        .table-title {
            font-size: 1.05rem; font-weight: 700; color: var(--navy);
            margin: 0;
        }

        .table-count {
            font-size: 0.82rem; color: var(--slate-500);
        }

        .table-wrap { overflow-x: auto; }

        table.inv-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.845rem;
        }

        table.inv-tbl thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
        }

        table.inv-tbl tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
            color: var(--slate-800);
        }

        table.inv-tbl tbody tr:hover td {
            background: #F8FAFF;
        }

        /* ── Customer info ── */
        .cust-wrap {
            display: flex; align-items: center; gap: 0.75rem;
        }

        .cust-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: var(--navy);
            color: var(--amber);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.82rem; font-weight: 700;
            flex-shrink: 0;
        }

        .cust-name {
            font-weight: 600;
            color: var(--navy);
        }

        /* ── Status badge ── */
        .status-badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            font-size: 0.72rem; font-weight: 700;
            padding: 0.35rem 0.65rem;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .status-debt {
            background: var(--rose-pale);
            color: #9F1239;
        }

        .status-partial {
            background: var(--amber-pale);
            color: #92400E;
        }

        .status-completed {
            background: var(--emerald-pale);
            color: #065F46;
        }

        /* ── Amount ── */
        .amt-value {
            font-family: 'DM Mono', monospace;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--navy);
        }

        /* ── Action buttons ── */
        .action-row {
            display: flex; gap: 0.5rem; justify-content: flex-end;
        }

        .btn-view {
            display: inline-flex; align-items: center; gap: 0.35rem;
            font-size: 0.75rem; font-weight: 600;
            padding: 0.4rem 0.75rem;
            background: var(--sky-pale);
            color: #075985;
            border: 1.5px solid #BAE6FD;
            border-radius: 7px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-view:hover {
            background: #0EA5E9;
            color: var(--white);
            border-color: #0EA5E9;
            transform: scale(1.05);
        }

        .btn-pay {
            display: inline-flex; align-items: center; gap: 0.35rem;
            font-size: 0.75rem; font-weight: 600;
            padding: 0.4rem 0.75rem;
            background: var(--emerald);
            color: var(--white);
            border: none;
            border-radius: 7px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-pay:hover {
            background: #047857;
            transform: scale(1.05);
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }
        .empty-icon {
            width: 80px; height: 80px;
            margin: 0 auto 1.25rem;
            background: var(--slate-100);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--slate-400);
            font-size: 2.5rem;
        }
        .empty-title {
            font-size: 1.15rem; font-weight: 700;
            color: var(--slate-600);
            margin-bottom: 0.5rem;
        }
        .empty-desc {
            font-size: 0.875rem;
            color: var(--slate-500);
            margin-bottom: 1.5rem;
            max-width: 380px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ── Modal ── */
        .modal-content {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .modal-header-navy {
            background: var(--navy);
            color: var(--white);
            padding: 1.15rem 1.4rem;
            border-bottom: none;
        }
        .modal-header-navy .modal-title {
            font-size: 1.1rem; font-weight: 700;
            margin: 0;
        }
        .modal-header-navy .btn-close {
            filter: invert(1) brightness(0.8);
        }

        .modal-body {
            padding: 1.75rem 1.4rem;
        }

        .modal-footer {
            padding: 1.15rem 1.4rem;
            border-top: 1.5px solid var(--slate-200);
            background: var(--slate-50);
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            font-size: 0.82rem; font-weight: 600;
            color: var(--slate-600);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 0.6rem 0.85rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            background: var(--slate-50);
            font-size: 0.875rem;
            outline: none;
            transition: all 0.18s;
        }
        .form-control:focus {
            border-color: var(--navy-light);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        .input-group {
            display: flex;
        }
        .input-group-text {
            background: var(--slate-100);
            border: 1.5px solid var(--slate-200);
            border-right: none;
            border-radius: 8px 0 0 8px;
            padding: 0.6rem 0.85rem;
            font-size: 0.875rem;
            color: var(--slate-600);
            font-weight: 600;
        }
        .input-group .form-control {
            border-radius: 0 8px 8px 0;
        }

        .form-text {
            font-size: 0.75rem;
            color: var(--slate-500);
            margin-top: 0.4rem;
        }

        .btn-modal-cancel {
            padding: 0.55rem 1.1rem;
            background: transparent;
            border: 1.5px solid var(--slate-300);
            border-radius: 8px;
            color: var(--slate-600);
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.18s;
        }
        .btn-modal-cancel:hover {
            background: var(--slate-50);
            border-color: var(--slate-400);
        }

        .btn-modal-submit {
            padding: 0.55rem 1.1rem;
            background: var(--amber);
            color: var(--navy);
            border: none;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.18s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-modal-submit:hover {
            background: var(--amber-dark);
            transform: translateY(-1px);
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
            .pg-header { padding: 1rem; margin-bottom: 1rem; flex-direction: column; }
            .pg-header-content { width: 100%; }
            .pg-actions { width: 100%; }
            .stats-grid { grid-template-columns: 1fr; }
            .search-row { flex-direction: column; }
            .search-wrap { width: 100%; min-width: auto; }
            .search-actions { width: 100%; }
        }

        /* ── Animation ── */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .stat-card { animation: slideUp 0.4s ease forwards; }
        .stat-card:nth-child(1) { animation-delay: 0s; }
        .stat-card:nth-child(2) { animation-delay: 0.05s; }
        .stat-card:nth-child(3) { animation-delay: 0.1s; }
        .stat-card:nth-child(4) { animation-delay: 0.15s; }
    </style>
</head>
<body>

@include("sidenav")

    <main class="main-content">
        <div class="main-wrap">

            {{-- ── Page Header ── --}}
            <div class="pg-header">
                <div class="pg-header-content">
                        <div class="pg-icon-wrap">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <div class="pg-title-wrap">
                            <h1>@lang('messages.invoices_management')</h1>
                            <p class="pg-subtitle">@lang('messages.track_customer_orders')</p>
                        </div>
                    </div>
                    <div class="pg-actions">
                        <button class="btn-header" onclick="printReport()">
                            <i class="bi bi-printer"></i> @lang('messages.print')
                        </button>
                        <button class="btn-header" onclick="exportData()">
                            <i class="bi bi-download"></i> @lang('messages.export')
                        </button>
                </div>
            </div>

            {{-- ── Stats Cards ── --}}
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

            <div class="stats-grid">
                <div class="stat-card total">
                    <div class="stat-layout">
                        <div class="stat-info">
                            <div class="stat-label">@lang('messages.total_orders')</div>
                            <div class="stat-value">{{ number_format(count($orders)) }}</div>
                            <div class="stat-meta">@lang('messages.all_time')</div>
                        </div>
                        <div class="stat-icon-box">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card debt">
                    <div class="stat-layout">
                        <div class="stat-info">
                            <div class="stat-label">@lang('messages.total_debt')</div>
                            <div class="stat-value">{{ number_format($totalDebt) }}</div>
                            <div class="stat-meta">@lang('messages.outstanding_debt')</div>
                        </div>
                        <div class="stat-icon-box">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card pending">
                    <div class="stat-layout">
                        <div class="stat-info">
                            <div class="stat-label">@lang('messages.pending')</div>
                            <div class="stat-value">{{ number_format($pendingOrders) }}</div>
                            <div class="stat-meta">@lang('messages.awaiting_payment')</div>
                        </div>
                        <div class="stat-icon-box">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card completed">
                    <div class="stat-layout">
                        <div class="stat-info">
                            <div class="stat-label">@lang('messages.completed')</div>
                            <div class="stat-value">{{ number_format($completedOrders) }}</div>
                            <div class="stat-meta">@lang('messages.paid_in_full')</div>
                        </div>
                        <div class="stat-icon-box">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Search Panel ── --}}
            <div class="search-panel">
                <div class="search-row">
                    <div class="search-wrap">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" class="search-input" id="debtor-search" 
                            placeholder="@lang('messages.search_by_customer_name_order_id_status')" 
                            onkeyup="searchOrders()">
                    </div>
                    <div class="search-actions">
                        <button class="btn-filter" onclick="showFilters()">
                            <i class="bi bi-funnel-fill"></i> Filter
                        </button>
                        <button class="btn-clear" onclick="clearFilters()">
                            <i class="bi bi-x-circle"></i> Clear
                        </button>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════
                 UNPAID INVOICES
            ══════════════════════════════════════ --}}
            <div class="table-container">
                <div class="table-head">
                    <h2 class="table-title">@lang('messages.unpaid_invoices')</h2>
                    <span class="table-count" id="unpaid-count">
                        {{ count($orders) }} {{ count($orders) === 1 ? __('messages.order_singular') : __('messages.order_plural') }}
                    </span>
                </div>

                <div class="table-wrap">
                    <table class="inv-tbl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('messages.date')</th>
                                <th>@lang('messages.customer')</th>
                                <th>@lang('messages.status')</th>
                                <th>@lang('messages.amount')</th>
                                <th style="text-align:right;">@lang('messages.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($orders->isEmpty())
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <div class="empty-title">@lang('messages.no_unpaid_orders')</div>
                                        <p class="empty-desc">
                                            @lang('messages.no_unpaid_orders_desc')
                                        </p>
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
                                    
                                    $initials = '';
                                    $names = explode(' ', $order->cName);
                                    foreach($names as $name) {
                                        $initials .= strtoupper(substr($name, 0, 1));
                                        if(strlen($initials) >= 2) break;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</div>
                                        <div style="font-size:0.72rem; color:var(--slate-400);">
                                            {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="cust-wrap">
                                            <div class="cust-avatar">{{ $initials }}</div>
                                            <div class="cust-name">{{ $order->cName }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $statusClass }}">
                                            <i class="bi {{ $statusIcon }}"></i>
                                            {{ $order->status ?? __('messages.in_progress') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="amt-value">Tsh {{ number_format($order->credit) }}</div>
                                    </td>
                                    <td>
                                        <div class="action-row">
                                            <form action="viewOrder" method="post" class="d-inline">
                                                @csrf
                                                <button class="btn-view" name="customerId" value="{{ $order->cPhone }}">
                                                    <i class="bi bi-eye"></i> @lang('messages.view_order')
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

            {{-- ══════════════════════════════════════
                 PAID INVOICES
            ══════════════════════════════════════ --}}
            <div class="table-container">
                <div class="table-head">
                    <h2 class="table-title">@lang('messages.paid_invoices')</h2>
                    <span class="table-count" id="paid-count">
                        {{ count($paid) }} {{ count($paid) === 1 ? __('messages.order_singular') : __('messages.order_plural') }}
                    </span>
                </div>

                <div class="table-wrap">
                    <table class="inv-tbl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('messages.date')</th>
                                <th>@lang('messages.customer')</th>
                                <th>@lang('messages.status')</th>
                                <th>@lang('messages.amount')</th>
                                <th style="text-align:right;">@lang('messages.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($paid->isEmpty())
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <div class="empty-title">@lang('messages.no_paid_orders')</div>
                                        <p class="empty-desc">
                                            @lang('messages.no_paid_orders_desc')
                                        </p>
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
                                    
                                    $initials = '';
                                    $names = explode(' ', $order->cName);
                                    foreach($names as $name) {
                                        $initials .= strtoupper(substr($name, 0, 1));
                                        if(strlen($initials) >= 2) break;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</div>
                                        <div style="font-size:0.72rem; color:var(--slate-400);">
                                            {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="cust-wrap">
                                            <div class="cust-avatar">{{ $initials }}</div>
                                            <div class="cust-name">{{ $order->cName }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $statusClass }}">
                                            <i class="bi {{ $statusIcon }}"></i>
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="amt-value">Tsh {{ number_format($order->credit) }}</div>
                                    </td>
                                    <td>
                                        <div class="action-row">
                                            <form action="viewOrder" method="post" class="d-inline">
                                                @csrf
                                                <button class="btn-view" name="customerId" value="{{ $order->cPhone }}">
                                                    <i class="bi bi-eye"></i> @lang('messages.view_order')
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
    </main>

{{-- ══════════════════════════════════════
     PAYMENT MODAL
══════════════════════════════════════ --}}
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-navy">
                <h5 class="modal-title">
                    <i class="bi bi-credit-card me-2"></i>@lang('messages.process_payment')
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="processPayment">
                @csrf
                <input type="hidden" name="orderId" id="modalOrderName">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">@lang('messages.payment_amount')</label>
                        <div class="input-group">
                            <span class="input-group-text">Tsh</span>
                            <input type="number" 
                                class="form-control" 
                                name="paymentAmount" 
                                min="0" 
                                step="0.01" 
                                placeholder="@lang('messages.enter_amount')"
                                required>
                        </div>
                        <div class="form-text">@lang('messages.enter_amount_payment_order')</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">
                        @lang('messages.cancel')
                    </button>
                    <button type="submit" class="btn-modal-submit">
                        <i class="bi bi-check-lg"></i> @lang('messages.process_payment')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ════════════════════════════════════════════
// Search functionality
// ════════════════════════════════════════════
function searchOrders() {
    const searchTerm = document.getElementById('debtor-search').value.toLowerCase();
    const tables = document.querySelectorAll('.inv-tbl tbody');
    
    tables.forEach((tbody, tableIndex) => {
        const rows = tbody.querySelectorAll('tr');
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
        const countId = tableIndex === 0 ? 'unpaid-count' : 'paid-count';
        const countElement = document.getElementById(countId);
        if (countElement) {
            countElement.textContent = `${visibleCount} order${visibleCount !== 1 ? 's' : ''}`;
        }
    });
}

// ════════════════════════════════════════════
// Modal functionality
// ════════════════════════════════════════════
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

// ════════════════════════════════════════════
// Export & Print
// ════════════════════════════════════════════
function exportData() {
    alert('@lang('messages.export_functionality')');
}

function printReport() {
    window.print();
}

function showFilters() {
    alert('Filter panel would open here');
}

function clearFilters() {
    document.getElementById('debtor-search').value = '';
    searchOrders();
}
</script>

</body>
</html>