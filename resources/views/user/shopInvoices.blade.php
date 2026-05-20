<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} — Shop Invoices</title>
    @include("links")
    <link href="{{asset('css/dashboard.css')}}" rel="stylesheet">
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

        /* ── Alerts ── */
        .alert {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.75rem 1rem; border-radius: var(--r);
            margin-bottom: 1rem; font-size: 13px; font-weight: 500;
        }
        .alert-success { background: var(--emerald-pale); color: var(--emerald); border-left: 3px solid var(--emerald); }
        .alert-danger  { background: var(--rose-pale);    color: var(--rose);    border-left: 3px solid var(--rose); }
        .close-btn { background: none; border: none; cursor: pointer; color: inherit; font-size: 16px; opacity: 0.6; }
        .close-btn:hover { opacity: 1; }

        /* ══ PAGE HEADER ══ */
        .pg-header {
            background: var(--navy);
            border-radius: var(--r-xl);
            padding: 1.2rem 1.6rem;
            margin-bottom: 1.4rem;
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
            background: var(--amber); opacity: 0.07; pointer-events: none;
        }
        .pg-left { display: flex; align-items: center; gap: 10px; position: relative; z-index: 1; }
        .header-icon {
            width: 40px; height: 40px; border-radius: var(--r);
            background: var(--amber); display: flex; align-items: center;
            justify-content: center; font-size: 18px; color: var(--navy); flex-shrink: 0;
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

        /* ══ CONTROLS BAR ══ */
        .controls-bar {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg); padding: 0.85rem 1.2rem;
            margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
            box-shadow: 0 1px 4px rgba(11,30,61,0.05);
        }

        /* Search */
        .search-wrap { position: relative; flex: 1; min-width: 160px; }
        .search-wrap i {
            position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
            color: var(--slate-400); font-size: 13px; pointer-events: none;
        }
        .search-input {
            width: 100%; padding: 8px 12px 8px 32px;
            border: 1.5px solid var(--slate-200); border-radius: var(--r);
            font-family: var(--font); font-size: 13px; color: var(--slate-800);
            background: var(--slate-50); outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .search-input:focus { border-color: var(--navy-light); box-shadow: 0 0 0 3px rgba(26,58,107,0.1); background: var(--white); }
        .search-input::placeholder { color: var(--slate-400); }

        /* Date filter */
        .date-filter {
            display: flex; align-items: center; gap: 6px;
            background: var(--slate-50); border: 1.5px solid var(--slate-200);
            border-radius: var(--r); padding: 6px 10px;
        }
        .date-filter label { font-size: 11px; font-weight: 600; color: var(--slate-400); text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
        .date-input-ctrl {
            border: none; outline: none; background: transparent;
            font-family: var(--font); font-size: 13px; color: var(--slate-800);
            width: 116px;
        }
        .date-sep { color: var(--slate-400); font-size: 12px; }

        .btn-filter {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 7px 13px; border-radius: var(--r);
            background: var(--navy); color: var(--white);
            font-family: var(--font); font-size: 12.5px; font-weight: 600;
            border: none; cursor: pointer; transition: all 0.15s; white-space: nowrap;
        }
        .btn-filter:hover { background: var(--navy-light); }

        .btn-clear-filter {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 12px; color: var(--rose); font-weight: 600;
            background: var(--rose-pale); border: none; border-radius: var(--r);
            padding: 7px 11px; cursor: pointer; text-decoration: none;
            transition: all 0.15s;
        }
        .btn-clear-filter:hover { background: #fecdd3; color: var(--rose); }

        /* View toggle */
        .view-toggle {
            display: flex; border: 1.5px solid var(--slate-200);
            border-radius: var(--r); overflow: hidden; flex-shrink: 0;
        }
        .view-btn {
            width: 34px; height: 34px; border: none; background: transparent;
            color: var(--slate-400); font-size: 14px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.15s;
        }
        .view-btn.active { background: var(--navy); color: var(--white); }
        .view-btn:not(.active):hover { background: var(--slate-100); color: var(--slate-700); }

        /* ══ METRICS ══ */
        .metrics-grid {
            display: grid; grid-template-columns: repeat(3, minmax(0,1fr));
            gap: 1rem; margin-bottom: 1.4rem;
        }
        @media (max-width: 640px) { .metrics-grid { grid-template-columns: 1fr; } }

        .metric-card {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg); padding: 1.1rem 1.2rem;
            box-shadow: 0 1px 4px rgba(11,30,61,0.05);
            position: relative; overflow: hidden;
        }
        .metric-card::after {
            content: ''; position: absolute; top: 0; left: 0; right: 0;
            height: 3px; background: var(--mc);
        }
        .mc-navy    { --mc: var(--navy); }
        .mc-emerald { --mc: var(--emerald); }
        .mc-rose    { --mc: var(--rose); }

        .metric-inner { display: flex; align-items: center; gap: 12px; }
        .metric-icon {
            width: 42px; height: 42px; border-radius: var(--r); flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 17px;
        }
        .mi-navy    { background: rgba(11,30,61,0.08); color: var(--navy); }
        .mi-emerald { background: var(--emerald-pale); color: var(--emerald); }
        .mi-rose    { background: var(--rose-pale);    color: var(--rose); }

        .metric-body {}
        .metric-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: var(--slate-400); margin-bottom: 4px; }
        .metric-value { font-family: var(--mono); font-size: 22px; font-weight: 500; color: var(--navy); letter-spacing: -0.5px; line-height: 1; }
        .metric-value.green { color: var(--emerald); }
        .metric-value.red   { color: var(--rose); }

        /* ══ CARD GRID ══ */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1rem;
        }

        .shop-card {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-xl); padding: 1.25rem;
            text-decoration: none; display: block;
            box-shadow: 0 1px 4px rgba(11,30,61,0.05);
            transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
            position: relative; overflow: hidden;
        }
        .shop-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0;
            height: 3px; background: var(--navy); opacity: 0;
            transition: opacity 0.18s;
        }
        .shop-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 28px rgba(11,30,61,0.12);
            border-color: var(--navy-light);
        }
        .shop-card:hover::before { opacity: 1; }

        .shop-card.all-paid { border-color: #a7f3d0; }
        .shop-card.all-paid::before { background: var(--emerald); }
        .shop-card.all-paid:hover { border-color: var(--emerald); box-shadow: 0 10px 28px rgba(5,150,105,0.12); }

        .card-top {
            display: flex; align-items: flex-start;
            justify-content: space-between; margin-bottom: 10px;
        }

        .shop-avatar {
            width: 40px; height: 40px; border-radius: var(--r);
            background: var(--navy-mid); color: rgba(255,255,255,0.85);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; flex-shrink: 0;
        }
        .shop-card.all-paid .shop-avatar { background: var(--emerald); }

        .card-badge {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 20px;
        }
        .badge-debt { background: var(--rose-pale);    color: var(--rose); }
        .badge-paid { background: var(--emerald-pale); color: var(--emerald); }

        .card-name     { font-size: 14px; font-weight: 700; color: var(--navy); margin-bottom: 3px; }
        .card-location { font-size: 12px; color: var(--slate-400); display: flex; align-items: center; gap: 4px; }

        .card-divider { border: none; border-top: 1px solid var(--slate-200); margin: 12px 0; }

        .card-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; }
        .cs-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-400); margin-bottom: 3px; }
        .cs-value { font-size: 14px; font-weight: 700; color: var(--slate-800); font-family: var(--mono); }
        .cs-value.green { color: var(--emerald); }
        .cs-value.red   { color: var(--rose); }

        .card-cta {
            display: flex; align-items: center; gap: 5px;
            margin-top: 12px; font-size: 12px; font-weight: 700;
            color: var(--navy-light);
        }
        .shop-card.all-paid .card-cta { color: var(--emerald); }

        /* ══ LIST VIEW ══ */
        .shop-list {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-xl); overflow: hidden;
            box-shadow: 0 1px 4px rgba(11,30,61,0.05);
        }

        .list-item {
            display: flex; align-items: center; gap: 12px;
            padding: 1rem 1.4rem; text-decoration: none;
            border-bottom: 1px solid var(--slate-100);
            transition: background 0.12s;
        }
        .list-item:last-child { border-bottom: none; }
        .list-item:hover { background: #F7F9FF; }

        .list-info { flex: 1; min-width: 0; }
        .list-name     { font-size: 13.5px; font-weight: 700; color: var(--navy); }
        .list-location { font-size: 12px; color: var(--slate-400); display: flex; align-items: center; gap: 4px; margin-top: 2px; }

        .list-stats { display: flex; gap: 2rem; align-items: center; flex-shrink: 0; }
        .ls-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-400); }
        .ls-value { font-size: 13px; font-weight: 700; color: var(--slate-800); font-family: var(--mono); text-align: right; margin-top: 2px; }
        .ls-value.green { color: var(--emerald); }
        .ls-value.red   { color: var(--rose); }

        .list-chevron { color: var(--slate-300); font-size: 14px; flex-shrink: 0; }
        .list-item:hover .list-chevron { color: var(--navy); }

        /* ══ EMPTY / HIDDEN ══ */
        .hidden { display: none !important; }

        .empty-state { text-align: center; padding: 3.5rem 1.5rem; color: var(--slate-400); }
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
        }
        .field-input:focus { border-color: var(--navy-light); box-shadow: 0 0 0 3px rgba(26,58,107,0.1); }
        .field-input::placeholder { color: var(--slate-400); }
        textarea.field-input { resize: vertical; min-height: 72px; }

        .input-prefix-wrap { display: flex; border: 1.5px solid var(--slate-200); border-radius: var(--r); overflow: hidden; transition: border-color 0.18s, box-shadow 0.18s; }
        .input-prefix-wrap:focus-within { border-color: var(--navy-light); box-shadow: 0 0 0 3px rgba(26,58,107,0.1); }
        .input-prefix { background: var(--slate-100); padding: 9px 12px; font-size: 13px; font-weight: 600; color: var(--slate-500); border-right: 1.5px solid var(--slate-200); white-space: nowrap; }
        .input-prefix-wrap input { flex: 1; border: none; outline: none; padding: 9px 12px; font-family: var(--mono); font-size: 13.5px; color: var(--slate-800); background: transparent; }

        .modal-search-wrap { position: relative; }
        .modal-search-wrap i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--slate-400); font-size: 13px; pointer-events: none; }
        .modal-search-wrap .field-input { padding-left: 32px; }

        .cust-results {
            position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 1050;
            background: var(--white); border: 1.5px solid var(--navy-light); border-radius: var(--r);
            max-height: 220px; overflow-y: auto; box-shadow: 0 8px 24px rgba(11,30,61,0.14); display: none;
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

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .wrap { padding: 1rem; }
            .controls-bar { flex-direction: column; align-items: stretch; }
            .date-filter { flex-wrap: wrap; }
            .list-stats { gap: 1rem; }
        }
        @media (max-width: 480px) {
            .metrics-grid { grid-template-columns: 1fr; }
            .list-stats { display: none; }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @include("admin/sidenav")

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3">
            <div class="wrap">

                {{-- Alerts --}}
                @if(session('success'))
                <div class="alert alert-success au au1">
                    <span>{{ session('success') }}</span>
                    <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger au au1">
                    <span>{{ session('error') }}</span>
                    <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
                </div>
                @endif

                {{-- Page header --}}
                <div class="pg-header au au1">
                    <div class="pg-left">
                        <div class="header-icon"><i class="bi bi-shop"></i></div>
                        <div class="pg-title-text">
                            <h1>Shops with Invoices</h1>
                            <p>Select a shop to view customers with debts</p>
                        </div>
                    </div>
                    <div class="pg-right">
                        <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#manualInvoiceModal">
                            <i class="bi bi-plus-lg"></i> Manual Invoice
                        </button>
                    </div>
                </div>

                {{-- Controls bar --}}
                <div class="controls-bar au au2">
                    <div class="search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" id="shopSearch" class="search-input" placeholder="Search shops…">
                    </div>

                    <form method="GET" action="{{ url('admin/shopInvoices') }}" style="display:contents;">
                        <div class="date-filter">
                            <label>From</label>
                            <input type="date" name="start_date" class="date-input-ctrl" value="{{ $startDate ?? '' }}">
                            <span class="date-sep">—</span>
                            <label>To</label>
                            <input type="date" name="end_date" class="date-input-ctrl" value="{{ $endDate ?? '' }}">
                            <button type="submit" class="btn-filter">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        </div>
                        @if($startDate || $endDate)
                            <a href="{{ url('admin/shopInvoices') }}" class="btn-clear-filter">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                        @endif
                    </form>

                    <div class="view-toggle">
                        <button class="view-btn active" id="cardViewBtn" onclick="setView('card')" title="Card view">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </button>
                        <button class="view-btn" id="listViewBtn" onclick="setView('list')" title="List view">
                            <i class="bi bi-list-ul"></i>
                        </button>
                    </div>
                </div>

                {{-- Metrics --}}
                <div class="metrics-grid au au3">
                    <div class="metric-card mc-navy">
                        <div class="metric-inner">
                            <div class="metric-icon mi-navy"><i class="bi bi-shop"></i></div>
                            <div class="metric-body">
                                <div class="metric-label">Total shops</div>
                                <div class="metric-value" id="metricShops">{{ count($shopsWithInvoices) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="metric-card mc-emerald">
                        <div class="metric-inner">
                            <div class="metric-icon mi-emerald"><i class="bi bi-receipt"></i></div>
                            <div class="metric-body">
                                <div class="metric-label">Total invoices</div>
                                <div class="metric-value green" id="metricInvoices">{{ $shopsWithInvoices->sum('invoice_count') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="metric-card mc-rose">
                        <div class="metric-inner">
                            <div class="metric-icon mi-rose"><i class="bi bi-exclamation-circle"></i></div>
                            <div class="metric-body">
                                <div class="metric-label">Total debts</div>
                                <div class="metric-value red" id="metricDebts">{{ $shopsWithInvoices->sum('debt_count') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card view --}}
                <div id="cardView" class="card-grid au au4">
                    @forelse($shopsWithInvoices as $shop)
                    @php
                        $initials = strtoupper(substr($shop['name'], 0, 1));
                        $parts = explode(' ', trim($shop['name']));
                        if (count($parts) > 1) $initials .= strtoupper(substr($parts[1], 0, 1));
                        $allPaid = $shop['debt_count'] == 0;
                    @endphp
                    <a href="{{ url('admin/shopDebtors/'.$shop['id']) }}"
                       class="shop-card {{ $allPaid ? 'all-paid' : '' }}"
                       data-name="{{ strtolower($shop['name'] . ' ' . $shop['location']) }}">
                        <div class="card-top">
                            <div class="shop-avatar">{{ $initials }}</div>
                            @if($allPaid)
                                <span class="card-badge badge-paid"><i class="bi bi-check-circle-fill"></i> All paid</span>
                            @else
                                <span class="card-badge badge-debt"><i class="bi bi-exclamation-circle-fill"></i> {{ $shop['debt_count'] }} debts</span>
                            @endif
                        </div>
                        <div class="card-name">{{ $shop['name'] }}</div>
                        <div class="card-location"><i class="bi bi-geo-alt"></i> {{ $shop['location'] }}</div>
                        <hr class="card-divider">
                        <div class="card-stats">
                            <div>
                                <div class="cs-label">Invoices</div>
                                <div class="cs-value">{{ $shop['invoice_count'] }}</div>
                            </div>
                            <div>
                                <div class="cs-label">Total</div>
                                <div class="cs-value green">{{ number_format($shop['total_amount']) }}</div>
                            </div>
                            <div>
                                <div class="cs-label">Debts</div>
                                <div class="cs-value {{ $shop['debt_count'] > 0 ? 'red' : 'green' }}">{{ $shop['debt_count'] }}</div>
                            </div>
                        </div>
                        <div class="card-cta">
                            View details <i class="bi bi-arrow-right"></i>
                        </div>
                    </a>
                    @empty
                    <div style="grid-column:1/-1;">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <h4>No shops found</h4>
                            <p>No shops with invoices have been recorded yet</p>
                        </div>
                    </div>
                    @endforelse
                </div>

                {{-- List view --}}
                <div id="listView" class="shop-list hidden au au4">
                    @forelse($shopsWithInvoices as $shop)
                    @php
                        $initials = strtoupper(substr($shop['name'], 0, 1));
                        $parts = explode(' ', trim($shop['name']));
                        if (count($parts) > 1) $initials .= strtoupper(substr($parts[1], 0, 1));
                    @endphp
                    <a href="{{ url('admin/shopDebtors/'.$shop['id']) }}"
                       class="list-item"
                       data-name="{{ strtolower($shop['name'] . ' ' . $shop['location']) }}">
                        <div class="shop-avatar">{{ $initials }}</div>
                        <div class="list-info">
                            <div class="list-name">{{ $shop['name'] }}</div>
                            <div class="list-location"><i class="bi bi-geo-alt"></i> {{ $shop['location'] }}</div>
                        </div>
                        <div class="list-stats">
                            <div>
                                <div class="ls-label">Invoices</div>
                                <div class="ls-value">{{ $shop['invoice_count'] }}</div>
                            </div>
                            <div>
                                <div class="ls-label">Total</div>
                                <div class="ls-value green">{{ number_format($shop['total_amount']) }}</div>
                            </div>
                            <div>
                                <div class="ls-label">Debts</div>
                                <div class="ls-value {{ $shop['debt_count'] > 0 ? 'red' : 'green' }}">{{ $shop['debt_count'] }}</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right list-chevron"></i>
                    </a>
                    @empty
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h4>No shops found</h4>
                        <p>No shops with invoices have been recorded yet</p>
                    </div>
                    @endforelse
                </div>

                {{-- No results --}}
                <div id="noResults" class="empty-state hidden" style="background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-xl);">
                    <i class="bi bi-search"></i>
                    <h4>No matches</h4>
                    <p>No shops match your search query</p>
                </div>

            </div>
        </main>
    </div>
</div>

{{-- Manual Invoice Modal --}}
<div class="modal fade" id="manualInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-top">
                <div class="modal-top-left">
                    <div class="modal-top-icon"><i class="bi bi-receipt"></i></div>
                    <h5>Create Manual Invoice</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('admin/createManualInvoice') }}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="field">
                        <label class="field-label">Shop <span class="req">*</span></label>
                        <select class="field-input" name="account" id="shopSelect" required>
                            <option value="">Select a shop…</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}">{{ $shop->name }} — {{ $shop->location }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field" style="position:relative;">
                        <label class="field-label">Customer <span class="req">*</span></label>
                        <div class="modal-search-wrap">
                            <i class="bi bi-search"></i>
                            <input type="text" class="field-input" id="customerSearch"
                                   placeholder="Search by name or phone…"
                                   name="customer_search" autocomplete="off" required>
                        </div>
                        <input type="hidden" name="customer_id"   id="customerId">
                        <input type="hidden" name="customer_name" id="customerNameHidden">
                        <div class="cust-results" id="customerSearchResults"></div>
                        <div id="selectedCustomerTag"></div>
                    </div>

                    <div class="field">
                        <label class="field-label">Invoice date <span class="req">*</span></label>
                        <input type="date" class="field-input" name="invoice_date" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="field">
                        <label class="field-label">Amount (TZS) <span class="req">*</span></label>
                        <div class="input-prefix-wrap">
                            <span class="input-prefix">Tsh</span>
                            <input type="number" name="amount" min="0" step="0.01" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">Notes (optional)</label>
                        <textarea class="field-input" name="notes" placeholder="Additional notes…"></textarea>
                    </div>

                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-circle-fill"></i> Create Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    /* ── View toggle ── */
    let currentView = localStorage.getItem('shopInvView') || 'card';

    function setView(v) {
        currentView = v;
        document.getElementById('cardView').classList.toggle('hidden', v !== 'card');
        document.getElementById('listView').classList.toggle('hidden', v !== 'list');
        document.getElementById('cardViewBtn').classList.toggle('active', v === 'card');
        document.getElementById('listViewBtn').classList.toggle('active', v === 'list');
        localStorage.setItem('shopInvView', v);
    }
    setView(currentView);

    /* ── Shop search ── */
    document.getElementById('shopSearch').addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        const cards = document.querySelectorAll('#cardView .shop-card');
        const items = document.querySelectorAll('#listView .list-item');
        let visible = 0;

        function filter(els) {
            els.forEach(el => {
                const match = !q || el.dataset.name.includes(q);
                el.classList.toggle('hidden', !match);
                if (match) visible++;
            });
        }

        filter(cards);
        filter(items);
        document.getElementById('noResults').classList.toggle('hidden', visible > 0 || !q);
    });

    /* ── Customer search in modal ── */
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

        fetch(`{{ url('admin/searchCustomers') }}?query=${encodeURIComponent(q)}&account=${encodeURIComponent(selectedShop)}`)
            .then(r => r.json())
            .then(data => {
                custResults.innerHTML = data.length
                    ? data.map(c => `
                        <div class="cust-item" onclick="selectCustomer(${c.id}, '${c.name.replace(/'/g,"\\'")}')">
                            <div class="cust-item-name">${c.name}</div>
                            <div class="cust-item-sub">${c.phone || 'No phone'} · Limit: ${Number(c.limits||0).toLocaleString()}</div>
                        </div>`).join('')
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