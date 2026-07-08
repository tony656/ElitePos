<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} — Paid Invoices</title>
    @include("links")
    
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:         #0B1E3D;
            --navy-mid:     #112952;
            --navy-light:   #1A3A6B;
            --amber:        #F59E0B;
            --amber-pale:   #FEF3C7;
            --emerald:      #059669;
            --emerald-pale: #D1FAE5;
            --rose:         #E11D48;
            --rose-pale:    #FFE4E6;
            --violet:       #7C3AED;
            --violet-pale:  #EDE9FE;
            --sky:          #0284C7;
            --sky-pale:     #E0F2FE;
            --slate-50:     #F8FAFC;
            --slate-100:    #F1F5F9;
            --slate-200:    #E2E8F0;
            --slate-300:    #CBD5E1;
            --slate-400:    #94A3B8;
            --slate-500:    #64748B;
            --slate-600:    #475569;
            --slate-700:    #334155;
            --slate-800:    #1E293B;
            --white:        #FFFFFF;

            --font: 'Sora', system-ui, sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --r:    8px;
            --r-lg: 13px;
            --r-xl: 16px;
        }

        body {
            font-family: var(--font);
            background: #ECF0F8;
            color: var(--slate-800);
            min-height: 100vh;
            font-size: 14px;
            line-height: 1.6;
        }

        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }

        .wrap { padding: 1.5rem 1.75rem 3rem; }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .au  { animation: fadeUp 0.38s ease both; }
        .au1 { animation-delay: 0.04s; }
        .au2 { animation-delay: 0.10s; }
        .au3 { animation-delay: 0.16s; }
        .au4 { animation-delay: 0.22s; }
        .au5 { animation-delay: 0.28s; }

        /* ══ BREADCRUMB ══ */
        .breadcrumb {
            display: flex; align-items: center; gap: 6px;
            margin-bottom: 1.1rem; flex-wrap: wrap;
        }
        .bc-link {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 12px; color: var(--slate-400); text-decoration: none;
            transition: color 0.15s;
        }
        .bc-link:hover { color: var(--navy); }
        .bc-sep { font-size: 12px; color: var(--slate-300); }
        .bc-cur { font-size: 12px; font-weight: 600; color: var(--slate-700); }

        /* ══ PAGE HEADER ══ */
        .pg-header {
            background: var(--navy); border-radius: var(--r-xl);
            padding: 1.2rem 1.6rem; margin-bottom: 1.4rem;
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; flex-wrap: wrap;
            position: relative; overflow: hidden;
        }
        .pg-header::before {
            content: ''; position: absolute; top: -50px; right: -30px;
            width: 180px; height: 180px; border-radius: 50%;
            background: var(--navy-light); opacity: 0.45; pointer-events: none;
        }
        .pg-header::after {
            content: ''; position: absolute; bottom: -55px; right: 100px;
            width: 120px; height: 120px; border-radius: 50%;
            background: var(--emerald); opacity: 0.07; pointer-events: none;
        }
        .pg-left { display: flex; align-items: center; gap: 10px; position: relative; z-index: 1; }
        .header-icon {
            width: 40px; height: 40px; border-radius: var(--r);
            background: var(--emerald); display: flex; align-items: center;
            justify-content: center; font-size: 18px; color: var(--white); flex-shrink: 0;
        }
        .pg-title-text h1 { font-size: 16px; font-weight: 700; color: var(--white); letter-spacing: -0.2px; }
        .pg-title-text p  { font-size: 12px; color: rgba(255,255,255,0.45); margin-top: 1px; }
        .pg-right { display: flex; gap: 8px; align-items: center; position: relative; z-index: 1; flex-wrap: wrap; }

        .btn-amber {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: var(--r);
            background: var(--amber); color: var(--navy);
            font-family: var(--font); font-size: 13px; font-weight: 700;
            border: none; cursor: pointer;
            box-shadow: 0 3px 14px rgba(245,158,11,0.35);
            transition: all 0.18s;
        }
        .btn-amber:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245,158,11,0.45); color: var(--navy); }

        /* ══ FILTERS ══ */
        .filters-card {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg); padding: 1rem 1.2rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 4px rgba(11,30,61,0.05);
        }
        .filters-row {
            display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end;
        }
        .filter-group { flex: 1; min-width: 140px; }
        .filter-label {
            display: block; font-size: 10.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.07em;
            color: var(--slate-400); margin-bottom: 5px;
        }
        .filter-ctrl {
            width: 100%; font-family: var(--font); font-size: 13px;
            padding: 8px 12px; border: 1.5px solid var(--slate-200);
            border-radius: var(--r); background: var(--white);
            color: var(--slate-800); outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
            appearance: none;
        }
        .filter-ctrl:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }
        select.filter-ctrl {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center;
            padding-right: 2.25rem; cursor: pointer;
        }
        .filter-actions { display: flex; gap: 8px; align-items: center; padding-bottom: 1px; flex-shrink: 0; }
        .btn-filter {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 8px 16px; border-radius: var(--r);
            background: var(--navy); color: var(--white);
            font-family: var(--font); font-size: 13px; font-weight: 600;
            border: none; cursor: pointer; transition: all 0.15s;
        }
        .btn-filter:hover { background: var(--navy-light); }
        .btn-reset {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 8px 14px; border-radius: var(--r);
            border: 1.5px solid var(--slate-200); background: transparent;
            font-family: var(--font); font-size: 13px; color: var(--slate-600);
            cursor: pointer; text-decoration: none; transition: all 0.15s;
        }
        .btn-reset:hover { background: var(--slate-100); color: var(--slate-800); }

        /* ══ METRICS ══ */
        .metrics-grid {
            display: grid; grid-template-columns: repeat(4, minmax(0,1fr));
            gap: 1rem; margin-bottom: 1.4rem;
        }
        @media (max-width: 900px) { .metrics-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 480px) { .metrics-grid { grid-template-columns: 1fr; } }

        .metric-card {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg); padding: 1.1rem 1.2rem;
            box-shadow: 0 1px 4px rgba(11,30,61,0.05);
            position: relative; overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .metric-card::after {
            content: ''; position: absolute; top: 0; left: 0; right: 0;
            height: 3px; background: var(--mc);
        }
        .mc-emerald { --mc: var(--emerald); }
        .mc-navy    { --mc: var(--navy); }
        .mc-amber   { --mc: var(--amber); }
        .mc-violet  { --mc: var(--violet); }

        .metric-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(11,30,61,0.1); }
        .metric-inner { display: flex; align-items: center; gap: 12px; }
        .metric-icon {
            width: 42px; height: 42px; border-radius: var(--r); flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 17px;
        }
        .mi-emerald { background: var(--emerald-pale); color: var(--emerald); }
        .mi-navy    { background: rgba(11,30,61,0.08); color: var(--navy); }
        .mi-amber   { background: var(--amber-pale);   color: #92400e; }
        .mi-violet  { background: var(--violet-pale);  color: var(--violet); }

        .metric-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: var(--slate-400); margin-bottom: 4px; }
        .metric-value { font-family: var(--mono); font-size: 20px; font-weight: 500; color: var(--navy); letter-spacing: -0.5px; line-height: 1; }

        /* ══ PANEL / TABLE ══ */
        .panel {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-xl); overflow: hidden;
            box-shadow: 0 1px 4px rgba(11,30,61,0.05);
        }
        .panel-head {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.4rem; border-bottom: 1.5px solid var(--slate-200);
            background: var(--slate-50); gap: 1rem; flex-wrap: wrap;
        }
        .panel-head-left { display: flex; align-items: center; gap: 10px; }
        .panel-head-icon {
            width: 30px; height: 30px; border-radius: var(--r);
            background: var(--emerald-pale); color: var(--emerald);
            display: flex; align-items: center; justify-content: center; font-size: 13px;
        }
        .panel-title { font-size: 13.5px; font-weight: 700; color: var(--navy); }

        .shop-pill {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 12px; font-weight: 600; padding: 4px 12px;
            background: rgba(11,30,61,0.07); color: var(--navy);
            border-radius: 20px; font-family: var(--mono);
        }

        /* Table */
        .tbl-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }

        thead th {
            background: var(--navy); color: rgba(255,255,255,0.65);
            font-size: 10.5px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.07em; padding: 10px 16px;
            white-space: nowrap; border: none; text-align: left;
        }

        tbody tr { border-bottom: 1px solid var(--slate-100); transition: background 0.12s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover td { background: #F7F9FF; }
        td { padding: 11px 16px; vertical-align: middle; }

        /* Cell types */
        .date-val {
            font-family: var(--mono); font-size: 12px; color: var(--slate-500);
        }
        .date-val span { display: block; font-size: 11px; color: var(--slate-400); margin-top: 1px; }

        .cust-name { font-weight: 600; color: var(--navy); font-size: 13px; }

        .inv-badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px; border-radius: 20px;
            background: var(--emerald-pale); color: var(--emerald);
            font-size: 11px; font-weight: 700; font-family: var(--mono);
        }

        .amount-val { font-family: var(--mono); font-weight: 700; color: var(--emerald); font-size: 13px; }

        .shop-tag {
            display: inline-block; padding: 3px 10px; border-radius: 20px;
            background: var(--slate-100); color: var(--slate-600);
            font-size: 11.5px; font-weight: 600;
        }

        /* Empty state */
        .empty-state { text-align: center; padding: 4rem 1.5rem; color: var(--slate-400); }
        .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; opacity: 0.3; }
        .empty-state h4 { font-size: 15px; font-weight: 600; color: var(--slate-600); margin-bottom: 5px; }
        .empty-state p  { font-size: 13px; }

        /* ══ MODAL ══ */
        .modal-content { border: none; border-radius: var(--r-xl); overflow: hidden; box-shadow: 0 20px 60px rgba(11,30,61,0.2); }
        .modal-top {
            background: var(--navy); padding: 1.15rem 1.4rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-top-left { display: flex; align-items: center; gap: 10px; }
        .modal-top-icon {
            width: 32px; height: 32px; border-radius: var(--r);
            background: var(--amber); display: flex; align-items: center;
            justify-content: center; color: var(--navy); font-size: 14px;
        }
        .modal-top h5 { font-size: 15px; font-weight: 700; color: var(--white); margin: 0; }
        .modal-top .btn-close { filter: invert(1) brightness(0.75); }
        .modal-body { padding: 1.5rem 1.4rem; }

        .field { margin-bottom: 12px; }
        .field-label {
            display: block; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.06em;
            color: var(--slate-500); margin-bottom: 5px;
        }
        .field-label .req { color: var(--rose); }
        .field-input {
            width: 100%; font-family: var(--font); font-size: 13.5px;
            padding: 9px 12px; border: 1.5px solid var(--slate-200);
            border-radius: var(--r); background: var(--white);
            color: var(--slate-800); outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
            appearance: none;
        }
        .field-input:focus { border-color: var(--navy-light); box-shadow: 0 0 0 3px rgba(26,58,107,0.1); }
        .field-input::placeholder { color: var(--slate-400); }
        select.field-input {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center;
            padding-right: 2.25rem;
        }
        textarea.field-input { resize: vertical; min-height: 72px; }

        .modal-search-wrap { position: relative; }
        .modal-search-wrap i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--slate-400); font-size: 13px; pointer-events: none; }
        .modal-search-wrap .field-input { padding-left: 32px; }

        .input-prefix-wrap { display: flex; border: 1.5px solid var(--slate-200); border-radius: var(--r); overflow: hidden; transition: border-color 0.18s, box-shadow 0.18s; }
        .input-prefix-wrap:focus-within { border-color: var(--navy-light); box-shadow: 0 0 0 3px rgba(26,58,107,0.1); }
        .input-prefix { background: var(--slate-100); padding: 9px 12px; font-size: 13px; font-weight: 600; color: var(--slate-500); border-right: 1.5px solid var(--slate-200); white-space: nowrap; }
        .input-prefix-wrap input { flex: 1; border: none; outline: none; padding: 9px 12px; font-family: var(--mono); font-size: 13.5px; color: var(--slate-800); background: transparent; }

        .cust-results {
            position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 1050;
            background: var(--white); border: 1.5px solid var(--navy-light); border-radius: var(--r);
            max-height: 220px; overflow-y: auto;
            box-shadow: 0 8px 24px rgba(11,30,61,0.14); display: none;
        }
        .cust-item { padding: 9px 13px; cursor: pointer; border-bottom: 1px solid var(--slate-100); transition: background 0.12s; }
        .cust-item:last-child { border-bottom: none; }
        .cust-item:hover { background: var(--slate-50); border-left: 3px solid var(--amber); padding-left: 10px; }
        .cust-item-name { font-size: 13px; font-weight: 600; color: var(--slate-800); }
        .cust-item-sub  { font-size: 11.5px; color: var(--slate-400); font-family: var(--mono); margin-top: 1px; }
        .cust-item-none { padding: 12px; text-align: center; font-size: 13px; color: var(--slate-400); }
        .selected-tag {
            display: inline-flex; align-items: center; gap: 5px; margin-top: 6px;
            background: var(--emerald-pale); color: var(--emerald);
            padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;
        }

        .modal-footer-custom {
            padding: 1rem 1.4rem; border-top: 1.5px solid var(--slate-200);
            display: flex; justify-content: flex-end; gap: 8px;
        }
        .btn-cancel {
            padding: 9px 18px; border-radius: var(--r);
            border: 1.5px solid var(--slate-200); background: transparent;
            font-family: var(--font); font-size: 13px; color: var(--slate-600); cursor: pointer; transition: all 0.15s;
        }
        .btn-cancel:hover { background: var(--slate-100); }
        .btn-submit {
            padding: 9px 20px; border-radius: var(--r);
            background: var(--amber); color: var(--navy); border: none;
            font-family: var(--font); font-size: 13px; font-weight: 700;
            cursor: pointer; display: flex; align-items: center; gap: 6px;
            box-shadow: 0 3px 14px rgba(245,158,11,0.3); transition: all 0.18s;
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245,158,11,0.4); }

        @media (max-width: 768px) {
            .wrap { padding: 1rem; }
            .filters-row { flex-direction: column; }
            .filter-actions { width: 100%; }
        }
    
        /* Delete button */
        .btn-delete {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: var(--r);
            background: var(--rose-pale); color: var(--rose);
            font-size: 14px; border: none; cursor: pointer;
            transition: all 0.15s;
        }
        .btn-delete:hover {
            background: var(--rose); color: var(--white);
            transform: translateY(-1px);
        }
</style>
</head>
<body>
    @include("sidenav")

    <main class="main-content">
        {{-- ── Alerts ── --}}
            @if(session('success'))
            <div class="alert-bar alert-ok">
                <span><i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}</span>
                <button onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert-bar alert-err">
                <span><i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}</span>
                <button onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
            </div>
            @endif
        <div class="wrap">

            {{-- Breadcrumb --}}
            <div class="breadcrumb au au1">
                <a href="{{ url('home') }}" class="bc-link"><i class="bi bi-house"></i> Home</a>
                <span class="bc-sep">/</span>
                <a href="{{ url('shopInvoices') }}" class="bc-link">{{ __('messages.shops_with_invoices') }}</a>
                <span class="bc-sep">/</span>
                <span class="bc-cur">Paid Invoices</span>
            </div>

            {{-- Page header --}}
            <div class="pg-header au au1">
                <div class="pg-left">
                    <div class="header-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <div class="pg-title-text">
                        <h1>{{ __('messages.paid_invoices') }}</h1>
                        <p>{{ __('messages.view_all_paid_invoices') }}</p>
                    </div>
                </div>
                <div class="pg-right">
                    <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#manualInvoiceModal">
                        <i class="bi bi-plus-lg"></i> {{ __('messages.manual_invoice') }}
                    </button>
                </div>
            </div>

            {{-- Filters --}}
            <div class="filters-card au au2">
                <form method="GET" action="{{ url('paidInvoices') }}">
                    <div class="filters-row">
                        <div class="filter-group">
                            <label class="filter-label">{{ __('messages.shop_label') }}</label>
                            <select name="shop" class="filter-ctrl">
                                @foreach($shops as $shop)
                                <option value="{{ $shop['id'] }}" {{ $shopName == $shop['id'] ? 'selected' : '' }}>
                                    {{ $shop['name'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">{{ __('messages.from') }}</label>
                            <input type="date" name="start_date" class="filter-ctrl" value="{{ $startDate ?? '' }}">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">{{ __('messages.to') }}</label>
                            <input type="date" name="end_date" class="filter-ctrl" value="{{ $endDate ?? '' }}">
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="btn-filter">
                                <i class="bi bi-funnel"></i> {{ __('messages.filter') }}
                            </button>
                            <a href="{{ url('paidInvoices') }}" class="btn-reset">
                                <i class="bi bi-x"></i> {{ __('messages.reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Metrics --}}
            <div class="metrics-grid au au3">
                <div class="metric-card mc-emerald">
                    <div class="metric-inner">
                        <div class="metric-icon mi-emerald"><i class="bi bi-cash-stack"></i></div>
                        <div>
                            <div class="metric-label">{{ __('messages.total_paid') }}</div>
                            <div class="metric-value" style="font-size:16px;">{{ number_format($totalPaid) }}</div>
                            <div style="font-size:10px; color:var(--slate-400); margin-top:2px;">TZS</div>
                        </div>
                    </div>
                </div>
                <div class="metric-card mc-navy">
                    <div class="metric-inner">
                        <div class="metric-icon mi-navy"><i class="bi bi-receipt"></i></div>
                        <div>
                            <div class="metric-label">{{ __('messages.payments_made') }}</div>
                            <div class="metric-value">{{ $paymentCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="metric-card mc-amber">
                    <div class="metric-inner">
                        <div class="metric-icon mi-amber"><i class="bi bi-file-earmark-check"></i></div>
                        <div>
                            <div class="metric-label">{{ __('messages.invoices_cleared') }}</div>
                            <div class="metric-value">{{ $uniqueInvoices }}</div>
                        </div>
                    </div>
                </div>
                <div class="metric-card mc-violet">
                    <div class="metric-inner">
                        <div class="metric-icon mi-violet"><i class="bi bi-people"></i></div>
                        <div>
                            <div class="metric-label">{{ __('messages.customers') }}</div>
                            <div class="metric-value">{{ $customersWithPayments }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment table --}}
            <div class="panel au au4">
                <div class="panel-head">
                    <div class="panel-head-left">
                        <div class="panel-head-icon"><i class="bi bi-list-check"></i></div>
                        <span class="panel-title">{{ __('messages.payment_history') }}</span>
                    </div>
                    <span class="shop-pill">
                        <i class="bi bi-shop"></i> {{ $shop->id ?? $shopName }}
                    </span>
                </div>

                @if($payments->count() > 0)
                <div class="tbl-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>@lang('messages.date') &amp; Time</th>
                                <th>@lang('messages.customer')</th>
                                <th>@lang('messages.invoice')</th>
                                <th>@lang('messages.amount_paid')</th>
                                <th>@lang('messages.shop_label')</th>
                                <th>@lang('messages.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td>
                                    <div class="date-val">
                                        {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y') }}
                                        <span>{{ \Carbon\Carbon::parse($payment->created_at)->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td class="cust-name">{{ $payment->cName }}</td>
                                <td>
                                    <span class="inv-badge">
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ $payment->orderId }}
                                    </span>
                                </td>
                                <td class="amount-val">{{ number_format($payment->amount) }} Tsh</td>
                                @php
                                    $account = DB::table('accounts')->where('id', $payment->account)->first();
                                    $accountName = $account ? $account->name : 'Unknown';
                                @endphp
                                <td><span class="shop-tag">{{ $accountName }}</span></td>
                                <td>
                                    <form action="{{ url('deletePaidInvoice') }}" method="POST" class="delete-form" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                        <button type="submit" class="btn-delete" onclick="return confirmDelete(event, '{{ $payment->orderId }}', {{ $payment->amount }})">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h4>{{ __('messages.no_payments_found') }}</h4>
                    <p>{{ __('messages.no_payments_match_filters') }}</p>
                </div>
                @endif
            </div>

        </div>
    </main>

{{-- Manual Invoice Modal --}}
<div class="modal fade" id="manualInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-top">
                <div class="modal-top-left">
                    <div class="modal-top-icon"><i class="bi bi-receipt"></i></div>
                    <h5>{{ __('messages.create_manual_invoice') }}</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('createManualInvoice') }}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="field">
                        <label class="field-label">{{ __('messages.shop_label') }} <span class="req">*</span></label>
                        <select class="field-input" name="account" id="shopSelect" required>
                            <option value="">{{ __('messages.select_a_shop') }}</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop['id'] }}">{{ $shop['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field" style="position:relative;">
                        <label class="field-label">{{ __('messages.customer') }} <span class="req">*</span></label>
                        <div class="modal-search-wrap">
                            <i class="bi bi-search"></i>
                            <input type="text" class="field-input" id="customerSearch"
                                   placeholder="{{ __('messages.search_by_name_or_phone') }}"
                                   name="customer_search" autocomplete="off" required>
                        </div>
                        <input type="hidden" name="customer_id"   id="customerId">
                        <input type="hidden" name="customer_name" id="customerNameHidden">
                        <div class="cust-results" id="customerSearchResults"></div>
                        <div id="selectedCustomerTag"></div>
                    </div>

                    <div class="field">
                        <label class="field-label">{{ __('messages.invoice_date') }} <span class="req">*</span></label>
                        <input type="date" class="field-input" name="invoice_date" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="field">
                        <label class="field-label">{{ __('messages.amount_tzs') }} <span class="req">*</span></label>
                        <div class="input-prefix-wrap">
                            <span class="input-prefix">Tsh</span>
                            <input type="number" name="amount" min="0" step="0.01" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">{{ __('messages.notes_optional') }}</label>
                        <textarea class="field-input" name="notes" placeholder="Additional notes…"></textarea>
                    </div>

                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-circle-fill"></i> {{ __('messages.create_invoice') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const custInput   = document.getElementById('customerSearch');
    const custResults = document.getElementById('customerSearchResults');
    const custTag     = document.getElementById('selectedCustomerTag');

    custInput && custInput.addEventListener('input', function () {
        const q = this.value.trim();
        const shopSelect = document.getElementById('shopSelect');
        const selectedShop = shopSelect ? shopSelect.value : '';
        
        if (q.length < 2) { custResults.style.display = 'none'; return; }
        if (!selectedShop) {
            custResults.innerHTML = '<div class="cust-item-none">Please select a shop first</div>';
            custResults.style.display = 'block';
            return;
        }

        fetch(`{{ url('searchCustomers') }}?query=${encodeURIComponent(q)}&account=${encodeURIComponent(selectedShop)}`)
            .then(r => r.json())
            .then(data => {
                custResults.innerHTML = data.length
                    ? data.map(c => `
                        <div class="cust-item" onclick="selectCustomer(${c.id}, '${c.name.replace(/'/g,"\\'")}')">
                            <div class="cust-item-name">${c.name}</div>
                            <div class="cust-item-sub">${c.phone || 'No phone'} · Limit: ${Number(c.limits||0).toLocaleString()}</div>
                        </div>`).join('')
    function confirmDelete(event, invoiceId, amount) {
        event.preventDefault();
        
        if (confirm(`Are you sure you want to delete this payment?\n\nInvoice: ${invoiceId}\nAmount: Tsh ${Number(amount).toLocaleString()}`)) {
            event.target.closest('form').submit();
        }
        return false;
    }

                    : '<div class="cust-item-none">No customers found</div>';
                custResults.style.display = 'block';
            })
            .catch(() => {
                custResults.innerHTML = '<div class="cust-item-none" style="color:var(--rose);">Error loading customers</div>';
                custResults.style.display = 'block';
            });
    });

    function selectCustomer(id, name) {
        document.getElementById('customerId').value         = id;
        document.getElementById('customerNameHidden').value = name;
        custInput.value = name;
        custResults.style.display = 'none';
        custTag.innerHTML = `<span class="selected-tag"><i class="bi bi-check-circle-fill"></i> ${name}</span>`;
    }

    document.addEventListener('click', function (e) {
        if (custInput && custResults &&
            !custInput.contains(e.target) && !custResults.contains(e.target)) {
            custResults.style.display = 'none';
        }
    });
</script>
</body>
</html>