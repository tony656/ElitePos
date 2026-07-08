<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} — All Invoices</title>
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

        /* ── Layout ── */
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

        /* ── Alerts ── */
        .alert {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.75rem 1rem; border-radius: var(--r);
            margin-bottom: 1rem; font-size: 13px; font-weight: 500;
        }
        .alert-success { background: var(--emerald-pale); color: var(--emerald); border-left: 3px solid var(--emerald); }
        .alert-danger  { background: var(--rose-pale);    color: var(--rose);    border-left: 3px solid var(--rose); }
        .alert .close-btn { background: none; border: none; cursor: pointer; color: inherit; font-size: 16px; opacity: 0.6; }
        .alert .close-btn:hover { opacity: 1; }

        /* ══════════════════════════════════════
           PAGE HEADER
        ══════════════════════════════════════ */
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
            content: '';
            position: absolute; top: -50px; right: -30px;
            width: 180px; height: 180px; border-radius: 50%;
            background: var(--navy-light); opacity: 0.45;
            pointer-events: none;
        }
        .pg-header::after {
            content: '';
            position: absolute; bottom: -55px; right: 100px;
            width: 120px; height: 120px; border-radius: 50%;
            background: var(--amber); opacity: 0.07;
            pointer-events: none;
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
            border: none; cursor: pointer; text-decoration: none;
            box-shadow: 0 3px 14px rgba(245,158,11,0.35);
            transition: all 0.18s;
        }
        .btn-amber:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245,158,11,0.45); color: var(--navy); }

        /* ══════════════════════════════════════
           TOOLBAR (shop selector + search)
        ══════════════════════════════════════ */
        .toolbar {
            display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
            margin-bottom: 1.25rem;
        }
        @media (max-width: 720px) { .toolbar { grid-template-columns: 1fr; } }

        .toolbar-card {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg); padding: 1rem 1.2rem;
            box-shadow: 0 1px 4px rgba(11,30,61,0.05);
        }
        .toolbar-label {
            font-size: 10.5px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.07em; color: var(--slate-400); margin-bottom: 7px;
            display: flex; align-items: center; gap: 6px;
        }
        .toolbar-label i { font-size: 13px; }

        .form-select-custom, .search-input {
            width: 100%; font-family: var(--font); font-size: 13.5px;
            padding: 9px 12px; border: 1.5px solid var(--slate-200);
            border-radius: var(--r); background: var(--white);
            color: var(--slate-800); outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .form-select-custom {
            appearance: none; cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center;
            padding-right: 2.25rem;
        }
        .form-select-custom:focus, .search-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        .search-wrap { position: relative; }
        .search-wrap i {
            position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
            color: var(--slate-400); font-size: 14px; pointer-events: none;
        }
        .search-wrap .search-input { padding-left: 34px; }

        /* ══════════════════════════════════════
           SHOP INFO STRIP
        ══════════════════════════════════════ */
        .shop-strip {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-left: 4px solid var(--amber);
            border-radius: var(--r-lg); padding: 0.9rem 1.2rem;
            margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap;
            box-shadow: 0 1px 4px rgba(11,30,61,0.05);
        }
        .shop-strip-icon {
            width: 40px; height: 40px; border-radius: var(--r);
            background: var(--amber-pale); color: var(--amber);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .shop-strip-info h5 { font-size: 14px; font-weight: 700; color: var(--navy); }
        .shop-strip-info p  { font-size: 12.5px; color: var(--slate-500); margin: 0; }
        .shop-strip-pills { display: flex; gap: 8px; flex-wrap: wrap; margin-left: auto; }
        .strip-pill {
            display: flex; align-items: center; gap: 5px;
            font-size: 12px; font-weight: 600; padding: 4px 11px;
            border-radius: 20px;
        }
        .sp-amber   { background: var(--amber-pale);   color: #92400e; }
        .sp-emerald { background: var(--emerald-pale); color: var(--emerald); }
        .sp-navy    { background: rgba(11,30,61,0.07); color: var(--navy); }

        /* ══════════════════════════════════════
           PANEL / TABLE
        ══════════════════════════════════════ */
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
            background: rgba(11,30,61,0.07); color: var(--navy-light);
            display: flex; align-items: center; justify-content: center; font-size: 13px;
        }
        .panel-title { font-size: 13.5px; font-weight: 700; color: var(--navy); }
        .result-pill {
            font-size: 11px; font-weight: 600; font-family: var(--mono);
            background: var(--slate-200); color: var(--slate-600);
            padding: 2px 9px; border-radius: 20px;
        }

        /* Table */
        .tbl-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }

        thead th {
            background: var(--navy);
            color: rgba(255,255,255,0.65);
            font-size: 10.5px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.07em; padding: 10px 16px;
            white-space: nowrap; border: none;
        }

        tbody tr { border-bottom: 1px solid var(--slate-100); transition: background 0.12s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover td { background: #F7F9FF; }
        td { padding: 11px 16px; vertical-align: middle; }

        .inv-num { font-family: var(--mono); font-size: 12.5px; font-weight: 500; color: var(--navy); }
        .cust-name { font-weight: 600; color: var(--slate-800); font-size: 13px; }
        .cust-phone { font-size: 11.5px; color: var(--slate-400); font-family: var(--mono); margin-top: 1px; }
        .amount-val { font-family: var(--mono); font-weight: 600; color: var(--slate-800); font-size: 13px; }
        .served-val { font-size: 12.5px; color: var(--slate-600); }
        .date-val   { font-size: 12px; color: var(--slate-500); font-family: var(--mono); }

        /* Status badges */
        .badge {
            display: inline-block; padding: 3px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 700; letter-spacing: 0.03em;
        }
        .b-paid    { background: var(--emerald-pale); color: var(--emerald); }
        .b-debt    { background: var(--rose-pale);    color: var(--rose); }
        .b-partial { background: var(--amber-pale);   color: #92400e; }
        .b-other   { background: var(--slate-100);    color: var(--slate-600); }

        /* Empty state */
        .empty-state {
            text-align: center; padding: 4rem 1.5rem; color: var(--slate-400);
        }
        .empty-state i { font-size: 3rem; display: block; margin-bottom: 0.75rem; opacity: 0.3; }
        .empty-state h4 { font-size: 15px; font-weight: 600; color: var(--slate-600); margin-bottom: 5px; }
        .empty-state p  { font-size: 13px; }

        /* ══════════════════════════════════════
           MODAL
        ══════════════════════════════════════ */
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
        .field-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }
        .field-input::placeholder { color: var(--slate-400); }
        select.field-input {
            appearance: none; cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center;
            padding-right: 2.25rem;
        }
        textarea.field-input { resize: vertical; min-height: 72px; }

        .input-prefix-wrap { display: flex; border: 1.5px solid var(--slate-200); border-radius: var(--r); overflow: hidden; transition: border-color 0.18s, box-shadow 0.18s; }
        .input-prefix-wrap:focus-within { border-color: var(--navy-light); box-shadow: 0 0 0 3px rgba(26,58,107,0.1); }
        .input-prefix { background: var(--slate-100); padding: 9px 12px; font-size: 13px; font-weight: 600; color: var(--slate-500); border-right: 1.5px solid var(--slate-200); white-space: nowrap; }
        .input-prefix-wrap input { flex: 1; border: none; outline: none; padding: 9px 12px; font-family: var(--mono); font-size: 13.5px; color: var(--slate-800); background: transparent; }

        /* Customer search results */
        .cust-results {
            position: absolute; top: calc(100% + 4px); left: 0; right: 0;
            z-index: 1050; background: var(--white);
            border: 1.5px solid var(--navy-light); border-radius: var(--r);
            max-height: 220px; overflow-y: auto;
            box-shadow: 0 8px 24px rgba(11,30,61,0.14); display: none;
        }
        .cust-item {
            padding: 9px 13px; cursor: pointer;
            border-bottom: 1px solid var(--slate-100); transition: background 0.12s;
        }
        .cust-item:last-child { border-bottom: none; }
        .cust-item:hover { background: var(--slate-50); border-left: 3px solid var(--amber); padding-left: 10px; }
        .cust-item-name { font-size: 13px; font-weight: 600; color: var(--slate-800); }
        .cust-item-sub  { font-size: 11.5px; color: var(--slate-400); font-family: var(--mono); margin-top: 1px; }
        .cust-item-none { padding: 12px; text-align: center; font-size: 13px; color: var(--slate-400); }
        .selected-cust-tag {
            display: inline-flex; align-items: center; gap: 5px;
            margin-top: 6px; background: var(--emerald-pale); color: var(--emerald);
            padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;
        }

        .modal-footer-custom {
            padding: 1rem 1.4rem; border-top: 1.5px solid var(--slate-200);
            display: flex; justify-content: flex-end; gap: 8px;
        }
        .btn-cancel {
            padding: 9px 18px; border-radius: var(--r);
            border: 1.5px solid var(--slate-200); background: transparent;
            font-family: var(--font); font-size: 13px; color: var(--slate-600); cursor: pointer;
            transition: all 0.15s;
        }
        .btn-cancel:hover { background: var(--slate-100); color: var(--slate-800); }
        .btn-submit {
            padding: 9px 20px; border-radius: var(--r);
            background: var(--amber); color: var(--navy); border: none;
            font-family: var(--font); font-size: 13px; font-weight: 700;
            cursor: pointer; display: flex; align-items: center; gap: 6px;
            box-shadow: 0 3px 14px rgba(245,158,11,0.3);
            transition: all 0.18s;
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245,158,11,0.4); }

        .modal-divider { height: 1px; background: var(--slate-200); border: none; margin: 1rem 0; }
        .modal-section-label {
            font-size: 10.5px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: var(--slate-400); margin-bottom: 10px;
        }
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        @media (max-width: 520px) { .field-row { grid-template-columns: 1fr; } }

        /* ── Responsive ── */
        @media (max-width: 720px) {
            .wrap { padding: 1rem; }
            .pg-header { padding: 0.9rem 1.1rem; }
            .shop-strip-pills { margin-left: 0; }
        }

        @media print {
            .d-print-none, .pg-right, .toolbar, .panel-head { display: none !important; }
            body { background: white; }
            .panel { box-shadow: none; border: 1px solid #ccc; }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @include("sidenav")

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
                        <div class="header-icon"><i class="bi bi-receipt-cutoff"></i></div>
                        <div class="pg-title-text">
                            <h1>All Shops Invoices</h1>
                            <p>Browse and filter invoices across all shops</p>
                        </div>
                    </div>
                    <div class="pg-right">
                        <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#manualInvoiceModal">
                            <i class="bi bi-plus-lg"></i> Create Manual Invoice
                        </button>
                    </div>
                </div>

                {{-- Toolbar: shop selector + search --}}
                <div class="toolbar au au2">
                    <div class="toolbar-card">
                        <div class="toolbar-label"><i class="bi bi-shop"></i> Select shop</div>
                        <form action="allInvoices" method="get" id="shopForm">
                            <select name="shop" class="form-select-custom" onchange="document.getElementById('shopForm').submit()">
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}" {{ $selectedShop == $shop->id ? 'selected' : '' }}>
                                        {{ $shop->name }} — {{ $shop->location }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="toolbar-card">
                        <div class="toolbar-label"><i class="bi bi-search"></i> Search invoices</div>
                        <div class="search-wrap">
                            <i class="bi bi-search"></i>
                            <input type="text" class="search-input" id="invoiceSearch"
                                   placeholder="Invoice #, customer, status…">
                        </div>
                    </div>
                </div>

                {{-- Shop info strip --}}
                @if($shopDetails)
                <div class="shop-strip au au3">
                    <div class="shop-strip-icon"><i class="bi bi-building"></i></div>
                    <div class="shop-strip-info">
                        <h5>{{ $shopDetails->name }}</h5>
                        <p>{{ $shopDetails->location }}</p>
                    </div>
                    <div class="shop-strip-pills">
                        <span class="strip-pill sp-navy">
                            <i class="bi bi-receipt"></i> {{ $invoices->count() }} invoices
                        </span>
                        <span class="strip-pill sp-emerald">
                            <i class="bi bi-check-circle"></i> {{ $invoices->where('status','Paid')->count() }} paid
                        </span>
                        <span class="strip-pill sp-amber">
                            <i class="bi bi-clock"></i> {{ $invoices->where('status','Debt')->count() + $invoices->where('status','Partial')->count() }} pending
                        </span>
                    </div>
                </div>
                @endif

                {{-- Invoices table --}}
                <div class="panel au au4">
                    <div class="panel-head">
                        <div class="panel-head-left">
                            <div class="panel-head-icon"><i class="bi bi-table"></i></div>
                            <span class="panel-title">Invoice Directory</span>
                            <span class="result-pill" id="rowCount">{{ $invoices->count() }}</span>
                        </div>
                    </div>

                    <div class="tbl-wrap">
                        <table id="invoiceTable">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Amount (Tsh)</th>
                                    <th>Status</th>
                                    <th>Served by</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceBody">
                                @forelse($invoices as $invoice)
                                <tr data-search="{{ strtolower($invoice->orderName . ' ' . ($invoice->cName ?? '') . ' ' . $invoice->status . ' ' . ($invoice->served_by ?? '')) }}">
                                    <td>
                                        <span class="inv-num">{{ $invoice->orderName }}</span>
                                    </td>
                                    <td>
                                        <div class="cust-name">{{ $invoice->cName ?? 'N/A' }}</div>
                                        <div class="cust-phone">{{ $invoice->cPhone ?? '—' }}</div>
                                    </td>
                                    <td>
                                        <span class="amount-val">{{ number_format($invoice->totalPrice ?? 0) }}</span>
                                    </td>
                                    <td>
                                        @if($invoice->status == 'Paid')
                                            <span class="badge b-paid">Paid</span>
                                        @elseif($invoice->status == 'Debt')
                                            <span class="badge b-debt">Debt</span>
                                        @elseif($invoice->status == 'Partial')
                                            <span class="badge b-partial">Partial</span>
                                        @else
                                            <span class="badge b-other">{{ $invoice->status }}</span>
                                        @endif
                                    </td>
                                    <td class="served-val">{{ $invoice->served_by ?? 'N/A' }}</td>
                                    <td class="date-val">
                                        {{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <h4>No invoices found</h4>
                                            <p>No invoices have been recorded for this shop yet</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

{{-- ══════════════════════════════════════
     MANUAL INVOICE MODAL
══════════════════════════════════════ --}}
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
            <form action="{{ url('createManualInvoice') }}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="modal-section-label">Shop &amp; customer</div>

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
                        <div class="search-wrap">
                            <i class="bi bi-search"></i>
                            <input type="text" class="search-input" id="customerSearch"
                                   placeholder="Search by name or phone…"
                                   name="customer_search" autocomplete="off" required>
                        </div>
                        <input type="hidden" name="customer_id"   id="customerId">
                        <input type="hidden" name="customer_name" id="customerNameHidden">
                        <div class="cust-results" id="customerSearchResults"></div>
                        <div id="selectedCustomerTag"></div>
                    </div>

                    <hr class="modal-divider">
                    <div class="modal-section-label">Invoice details</div>

                    <div class="field-row">
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
                    </div>

                    <div class="field">
                        <label class="field-label">Notes (optional)</label>
                        <textarea class="field-input" name="notes" placeholder="Additional notes about this invoice…"></textarea>
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
    /* ── Live search within table ── */
    const searchInput = document.getElementById('invoiceSearch');
    const rows        = document.querySelectorAll('#invoiceBody tr[data-search]');
    const rowCountEl  = document.getElementById('rowCount');

    searchInput && searchInput.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let visible = 0;
        rows.forEach(row => {
            const match = !q || row.dataset.search.includes(q);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        if (rowCountEl) rowCountEl.textContent = visible;
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

        fetch(`{{ url('searchCustomers') }}?query=${encodeURIComponent(q)}&account=${encodeURIComponent(selectedShop)}`)
            .then(r => r.json())
            .then(data => {
                if (!data.length) {
                    custResults.innerHTML = '<div class="cust-item-none">No customers found</div>';
                } else {
                    custResults.innerHTML = data.map(c => `
                        <div class="cust-item" onclick="selectCustomer(${c.id}, '${c.name.replace(/'/g,"\\'")}')">
                            <div class="cust-item-name">${c.name}</div>
                            <div class="cust-item-sub">${c.phone || 'No phone'} · Limit: ${Number(c.limits||0).toLocaleString()}</div>
                        </div>
                    `).join('');
                }
                custResults.style.display = 'block';
            })
            .catch(() => {
                custResults.innerHTML = '<div class="cust-item-none" style="color:var(--rose);">Error loading customers</div>';
                custResults.style.display = 'block';
            });
    });

    function selectCustomer(id, name) {
        document.getElementById('customerId').value          = id;
        document.getElementById('customerNameHidden').value  = name;
        custInput.value                                      = name;
        custResults.style.display                            = 'none';
        custTag.innerHTML = `<span class="selected-cust-tag"><i class="bi bi-check-circle-fill"></i> ${name}</span>`;
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