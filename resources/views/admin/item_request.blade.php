<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} — Item Request</title>
    @include("links")
    <link href="{{asset('css/dashboard.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

            --font:  'Sora', system-ui, sans-serif;
            --mono:  'JetBrains Mono', monospace;
            --radius: 10px;
            --radius-sm: 6px;
            --radius-lg: 14px;
        }

        body {
            font-family: var(--font);
            background: var(--slate-100);
            color: var(--slate-800);
            font-size: 14px;
            line-height: 1.6;
        }

        /* ── Layout ── */
        .layout { display: flex; min-height: 100vh; }
        main { flex: 1; min-width: 0; padding: 2rem 2rem 3rem; margin-left: 270px; }

        /* Responsive: remove left margin on mobile (sidebar overlays) */
        @media (max-width: 768px) {
            main { margin-left: 0; }
        }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.35s ease both; }
        .fade-up-1 { animation-delay: 0.05s; }
        .fade-up-2 { animation-delay: 0.12s; }
        .fade-up-3 { animation-delay: 0.19s; }

        /* ── Alerts ── */
        .alert {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.75rem 1rem; border-radius: var(--radius-sm);
            margin-bottom: 1rem; font-size: 13px; font-weight: 500;
        }
        .alert-success { background: var(--emerald-pale); color: var(--emerald); border-left: 3px solid var(--emerald); }
        .alert-danger  { background: var(--rose-pale);    color: var(--rose);    border-left: 3px solid var(--rose); }
        .alert-info    { background: var(--violet-pale);  color: var(--violet);  border-left: 3px solid var(--violet); font-size: 13px; }
        .alert .close-btn { background: none; border: none; cursor: pointer; color: inherit; font-size: 16px; opacity: 0.6; }
        .alert .close-btn:hover { opacity: 1; }

        /* ── Page header ── */
        .page-header {
            background: var(--navy);
            border-radius: var(--radius-lg);
            padding: 1.4rem 1.75rem;
            margin-bottom: 1.75rem;
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; flex-wrap: wrap;
            position: relative; overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute; top: -40px; right: -40px;
            width: 160px; height: 160px; border-radius: 50%;
            background: var(--navy-light); opacity: 0.5;
        }
        .page-header::after {
            content: '';
            position: absolute; bottom: -50px; right: 60px;
            width: 100px; height: 100px; border-radius: 50%;
            background: var(--amber); opacity: 0.08;
        }
        .header-title { display: flex; align-items: center; gap: 10px; position: relative; z-index: 1; }
        .header-icon {
            width: 38px; height: 38px; border-radius: var(--radius-sm);
            background: var(--amber); display: flex; align-items: center; justify-content: center;
        }
        .header-icon svg { width: 18px; height: 18px; color: var(--navy); }
        .header-label { font-size: 16px; font-weight: 700; color: var(--white); letter-spacing: -0.2px; }
        .header-sub   { font-size: 12px; color: var(--slate-400); margin-top: 1px; }

        .btn-header {
            position: relative; z-index: 1;
            display: inline-flex; align-items: center; gap: 7px;
            padding: 7px 16px; border-radius: var(--radius-sm);
            border: 1px solid rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.07);
            color: var(--white); font-family: var(--font); font-size: 13px;
            font-weight: 500; cursor: pointer; text-decoration: none;
            transition: background 0.2s;
        }
        .btn-header:hover { background: rgba(255,255,255,0.14); color: var(--white); }
        .btn-header svg { width: 14px; height: 14px; }

        /* ── Grid ── */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; align-items: start; }
        @media (max-width: 900px) { .two-col { grid-template-columns: 1fr; } }

        /* ── Card ── */
        .card {
            background: var(--white); border-radius: var(--radius-lg);
            border: 1px solid var(--slate-200);
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }
        .card-header {
            display: flex; align-items: center; gap: 8px;
            padding: 1.1rem 1.4rem; border-bottom: 1px solid var(--slate-200);
        }
        .card-header-icon {
            width: 28px; height: 28px; border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .card-header-icon svg { width: 14px; height: 14px; }
        .icon-amber   { background: var(--amber-pale);   color: var(--amber); }
        .icon-emerald { background: var(--emerald-pale); color: var(--emerald); }
        .icon-violet  { background: var(--violet-pale);  color: var(--violet); }
        .icon-navy    { background: rgba(11,30,61,0.08); color: var(--navy-light); }

        .card-title { font-size: 13px; font-weight: 600; color: var(--slate-800); }
        .card-body  { padding: 1.4rem; }

        /* ── Form elements ── */
        .form-group { margin-bottom: 1.1rem; }
        .form-label {
            display: block; font-size: 11.5px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.06em;
            color: var(--slate-500); margin-bottom: 6px;
        }
        .form-control, .form-select {
            width: 100%; padding: 9px 12px;
            border: 1.5px solid var(--slate-200); border-radius: var(--radius-sm);
            font-family: var(--font); font-size: 13.5px; color: var(--slate-800);
            background: var(--white); outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }
        .form-control::placeholder { color: var(--slate-400); }
        .form-hint { font-size: 11.5px; color: var(--slate-400); margin-top: 5px; }

        /* ── Product search ── */
        .search-wrap { position: relative; }
        #search-results {
            position: absolute; top: calc(100% + 4px); left: 0; right: 0;
            z-index: 1000; background: var(--white);
            border: 1.5px solid var(--navy-light); border-radius: var(--radius);
            max-height: 280px; overflow-y: auto;
            box-shadow: 0 8px 24px rgba(11,30,61,0.14);
            display: none;
        }
        .search-item {
            padding: 10px 14px; cursor: pointer;
            border-bottom: 1px solid var(--slate-100);
            transition: background 0.15s;
        }
        .search-item:last-child { border-bottom: none; }
        .search-item:hover, .search-item.selected {
            background: var(--slate-50); border-left: 3px solid var(--amber); padding-left: 11px;
        }
        .search-item-name { font-size: 13px; font-weight: 600; color: var(--slate-800); }
        .search-item-price { font-size: 12px; color: var(--emerald); font-family: var(--mono); font-weight: 500; margin-top: 2px; }
        .search-no-results { padding: 1.25rem; text-align: center; font-size: 13px; color: var(--slate-400); }

        /* ── Radio payment ── */
        .radio-group { display: flex; gap: 10px; }
        .radio-pill {
            flex: 1; position: relative;
        }
        .radio-pill input[type=radio] { position: absolute; opacity: 0; width: 0; height: 0; }
        .radio-pill label {
            display: flex; align-items: center; justify-content: center; gap: 7px;
            padding: 9px 12px; border-radius: var(--radius-sm);
            border: 1.5px solid var(--slate-200); cursor: pointer;
            font-size: 13px; font-weight: 500; color: var(--slate-600);
            transition: all 0.18s; background: var(--white);
        }
        .radio-pill label svg { width: 15px; height: 15px; }
        .radio-pill input[type=radio]:checked + label {
            border-color: var(--navy-light); color: var(--navy);
            background: rgba(26,58,107,0.05);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.08);
        }
        .radio-credit input[type=radio]:checked + label { border-color: var(--violet); color: var(--violet); background: var(--violet-pale); box-shadow: 0 0 0 3px rgba(124,58,237,0.1); }
        .radio-cash   input[type=radio]:checked + label { border-color: var(--emerald); color: var(--emerald); background: var(--emerald-pale); box-shadow: 0 0 0 3px rgba(5,150,105,0.1); }

        /* ── Submit button ── */
        .btn-add {
            width: 100%; padding: 11px; border: none; border-radius: var(--radius-sm);
            background: var(--navy); color: var(--white);
            font-family: var(--font); font-size: 14px; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-add:hover:not(:disabled) { background: var(--navy-light); transform: translateY(-1px); }
        .btn-add:disabled { background: var(--slate-300); cursor: not-allowed; }
        .btn-add svg { width: 16px; height: 16px; }

        /* ── Items table ── */
        .table-wrap { overflow-x: auto; border-radius: var(--radius); border: 1px solid var(--slate-200); }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th {
            background: var(--navy); color: rgba(255,255,255,0.8);
            font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em;
            padding: 10px 14px; text-align: left; white-space: nowrap;
        }
        thead th:last-child { text-align: right; }
        tbody tr { border-bottom: 1px solid var(--slate-100); transition: background 0.12s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--slate-50); }
        td { padding: 10px 14px; vertical-align: middle; }
        td.text-right { text-align: right; }

        .prod-name { font-weight: 600; font-size: 13px; color: var(--slate-800); }
        .prod-id   { font-size: 11px; color: var(--slate-400); font-family: var(--mono); }

        .qty-input {
            width: 72px; padding: 5px 8px; text-align: center;
            border: 1.5px solid var(--slate-200); border-radius: var(--radius-sm);
            font-family: var(--mono); font-size: 13px; color: var(--slate-800);
            outline: none;
        }
        .qty-input:focus { border-color: var(--navy-light); box-shadow: 0 0 0 3px rgba(26,58,107,0.1); }

        .price-val { font-family: var(--mono); font-size: 12.5px; font-weight: 500; color: var(--emerald); }
        .total-val { font-family: var(--mono); font-size: 13px;   font-weight: 600; color: var(--slate-800); }

        .btn-del {
            display: inline-flex; align-items: center; justify-content: center;
            width: 30px; height: 30px; border-radius: var(--radius-sm);
            background: var(--rose-pale); color: var(--rose);
            border: 1px solid #fecdd3; cursor: pointer; transition: all 0.15s;
        }
        .btn-del:hover { background: var(--rose); color: var(--white); border-color: var(--rose); }
        .btn-del svg { width: 13px; height: 13px; pointer-events: none; }

        tfoot td {
            background: var(--slate-50); border-top: 2px solid var(--slate-200);
            font-weight: 700; padding: 10px 14px;
            font-family: var(--mono); font-size: 13px;
        }
        .tfoot-label { text-align: right; color: var(--slate-600); }
        .tfoot-total { text-align: right; color: var(--navy); font-size: 14px; }

        /* ── Empty state ── */
        .empty-state {
            text-align: center; padding: 3rem 1.5rem;
            border: 1.5px dashed var(--slate-300); border-radius: var(--radius);
            background: var(--slate-50); margin-top: 1.25rem;
        }
        .empty-icon { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.35; }
        .empty-state h5 { font-size: 14px; font-weight: 600; color: var(--slate-600); margin-bottom: 4px; }
        .empty-state p  { font-size: 12.5px; color: var(--slate-400); }

        /* ── Order info block ── */
        .order-info {
            background: var(--slate-50); border: 1px solid var(--slate-200);
            border-radius: var(--radius-sm); padding: 1rem 1.1rem; margin-bottom: 1.25rem;
        }
        .order-info-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 5px 0; border-bottom: 1px solid var(--slate-100); font-size: 13px;
        }
        .order-info-row:last-child { border-bottom: none; padding-bottom: 0; }
        .order-info-row:first-child { padding-top: 0; }
        .info-label { color: var(--slate-500); font-size: 12px; }
        .info-val   { font-weight: 600; color: var(--slate-800); font-family: var(--mono); font-size: 12.5px; }

        .badge {
            display: inline-block; padding: 3px 9px; border-radius: 20px;
            font-size: 11px; font-weight: 600; letter-spacing: 0.03em;
        }
        .badge-pending  { background: var(--amber-pale);   color: #92400e; }
        .badge-approved { background: var(--emerald-pale); color: var(--emerald); }
        .badge-info     { background: var(--violet-pale);  color: var(--violet); }

        /* ── Pricing summary ── */
        .pricing-block {
            background: var(--navy); border-radius: var(--radius-sm);
            padding: 1.1rem 1.25rem; margin: 1.25rem 0;
        }
        .price-row {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 13px; color: rgba(255,255,255,0.65); padding: 4px 0;
        }
        .price-row.grand {
            border-top: 1px solid rgba(255,255,255,0.15);
            margin-top: 8px; padding-top: 10px;
            color: var(--white); font-size: 15px; font-weight: 700;
        }
        .price-row .price-num { font-family: var(--mono); font-weight: 600; color: var(--amber); }
        .price-row.grand .price-num { font-size: 17px; color: var(--amber); }

        /* ── Section divider ── */
        .section-label {
            font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.08em;
            font-weight: 700; color: var(--slate-400); margin-bottom: 8px;
        }

        /* ── Submit request button ── */
        .btn-submit {
            width: 100%; padding: 12px; border: none; border-radius: var(--radius-sm);
            background: var(--emerald); color: var(--white);
            font-family: var(--font); font-size: 14px; font-weight: 700;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-submit:hover { background: #047857; transform: translateY(-1px); }
        .btn-submit svg { width: 16px; height: 16px; }

        /* ── Hidden fields ── */
        .hidden-fields { position: absolute; opacity: 0; pointer-events: none; height: 0; overflow: hidden; }
    </style>
</head>
<body>


<div class="layout">
    @include("admin/sidenav")

    <main class="fade-up">

        {{-- Alerts --}}
        @if(session('success'))
        <div class="alert alert-success fade-up fade-up-1">
            <span>{{ session('success') }}</span>
            <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger fade-up fade-up-1">
            <span>{{ session('error') }}</span>
            <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
        </div>
        @endif

        {{-- Page header --}}
        <div class="page-header fade-up fade-up-1">
            <div class="header-title">
                <div class="header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                </div>
                <div>
                    <div class="header-label">Item Request</div>
                    <div class="header-sub">{{ date('l, d M Y') }}</div>
                </div>
            </div>
            <a href="{{ url('user/viewRequest') }}" class="btn-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                    <line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/>
                    <line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                </svg>
                View All Requests
            </a>
        </div>

        {{-- Two-column layout --}}
        <div class="two-col">

            {{-- ======== LEFT COLUMN ======== --}}
            <div style="display:flex; flex-direction:column; gap:1.25rem;">

                {{-- Add Item Form --}}
                <div class="card fade-up fade-up-2">
                    <div class="card-header">
                        <div class="card-header-icon icon-amber">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/>
                                <line x1="8" y1="12" x2="16" y2="12"/>
                            </svg>
                        </div>
                        <span class="card-title">Add new item</span>
                    </div>
                    <div class="card-body">
                        <form action="/storeSession" method="post">
                            @csrf
                                <div class="form-group" style="margin-bottom:0;">
                                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
                                        <label class="form-label" style="margin-bottom:0;">Select shop</label>
                                    </div>
                                    <select class="form-select" name="shopId" id="shopId" onchange="this.form.submit()">
                                        <option value="">— Choose shop —</option>
                                        @foreach ($shops as $shop)
                                            <option value="{{ $shop->id }}"
                                                {{ ((getCurrentShopId()) == $shop->id ) ? 'selected' : '' }}>
                                                {{ $shop->name }} — ID: {{ $shop->id }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                        </form>
                        <form action="/admin/itemRequest" method="post" autocomplete="off" id="addItemForm">
                            @csrf
                            <input type="hidden" name="OrderName" value="{{ $orders->orderName ?? '' }}">
                                
                            {{-- Product search --}}
                            <div class="form-group search-wrap">
                                <label class="form-label">Search product</label>
                                <input type="search" class="form-control" id="product-name"
                                       placeholder="Type product name…" autocomplete="off">
                                <div id="search-results"></div>
                            </div>

                            <input type="hidden" name="pQuantity" value="1">
                            <input type="hidden" name="requestDate" id="formRequestDate" value="{{ old('requestDate', date('Y-m-d')) }}">

                            <div class="hidden-fields">
                                <input type="text" id="pId"    name="pId"    readonly>
                                <input type="text" id="pPrice" name="pPrice" readonly>
                                <input type="text" value="{{ $orders->order_id  ?? '' }}" name="OrdersIds"   readonly>
                                <input type="text" value="{{ $orders->orderName ?? '' }}" name="OrdersNames" readonly>
                                <input type="number" id="maxDiscount" name="maxDiscount" readonly>
                            </div>

                            {{-- Payment type --}}
                            <div class="form-group">
                                <label class="form-label">Payment type</label>
                                <div class="radio-group">
                                    <div class="radio-pill radio-credit">
                                        <input type="radio" name="paymentType" id="paymentCredit" value="credit" checked>
                                        <label for="paymentCredit">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                                            </svg>
                                            Credit
                                        </label>
                                    </div>
                                    <div class="radio-pill radio-cash">
                                        <input type="radio" name="paymentType" id="paymentCash" value="cash">
                                        <label for="paymentCash">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="2" y="6" width="20" height="12" rx="2"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            Cash
                                        </label>
                                    </div>
                                </div>
                            </div>                          

                            <button class="btn-add" type="submit" id="addItemBtn" disabled>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                Add to current request
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Items table --}}
                <div class="card fade-up fade-up-3">
                    <div class="card-header">
                        <div class="card-header-icon icon-navy">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.95-1.56L23 6H6"/>
                            </svg>
                        </div>
                        <span class="card-title">Current items
                            @if(count($carts) > 0)
                                <span style="margin-left:6px; background:var(--navy); color:#fff; font-size:10px; padding:1px 7px; border-radius:20px;">
                                    {{ count($carts) }}
                                </span>
                            @endif
                        </span>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        @if(count($carts) > 0)
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th style="text-align:right;">Unit price</th>
                                        <th style="text-align:right;">Subtotal</th>
                                        <th style="text-align:right;">Del</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($carts as $row)
                                    @php
                                        $name = DB::table('products')->where('product_id', $row->productId)->value('name01');
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="prod-name">{{ $name ?? 'Product not found' }}</div>
                                            <div class="prod-id">ID: {{ $row->productId }}</div>
                                        </td>
                                        <td>
                                            <form action="/admin/requpdQuant" method="post">
                                                @csrf
                                                <input type="hidden" name="OrdersIds" value="{{ $row->requestName ?? 'NEW-ORDER' }}">
                                                <input type="hidden" name="prodId"    value="{{ $row->productId }}">
                                                <input type="number" class="qty-input"
                                                       name="prodQuantity"
                                                       value="{{ $row->quantity }}"
                                                       min="1"
                                                       onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td class="text-right">
                                            <span class="price-val">{{ number_format($row->price, 2) }}</span>
                                        </td>
                                        <td class="text-right">
                                            <span class="total-val">{{ number_format($row->totalPrice, 2) }}</span>
                                        </td>
                                        <td class="text-right">
                                            <form action="/admin/dltItemReq" method="post" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="itemId"       value="{{ $row->productId }}">
                                                <input type="hidden" name="reqName"      value="{{ $row->requestName ?? 'NEW-ORDER' }}">
                                                <input type="hidden" name="prodQuantity" value="{{ $row->quantity }}">
                                                <button type="submit" class="btn-del"
                                                        onclick="return confirm('Remove this item?')">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
                                                        <path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="tfoot-label">Grand total</td>
                                        <td colspan="2" class="tfoot-total">{{ number_format($carts->sum('totalPrice'), 2) }} TZS</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="empty-state" style="margin: 1.25rem;">
                            <div class="empty-icon">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.95-1.56L23 6H6"/>
                                </svg>
                            </div>
                            <h5>No items yet</h5>
                            <p>Search and add products above to begin this request</p>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ======== RIGHT COLUMN ======== --}}
            <div class="fade-up fade-up-3">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-icon icon-violet">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                            </svg>
                        </div>
                        <span class="card-title">Request summary</span>
                    </div>
                    <div class="card-body">

                        {{-- Order info --}}
                        <div class="order-info">
                            <div class="order-info-row">
                                <span class="info-label">Request ID</span>
                                <span class="info-val">{{ $orders->requestName ?? 'NEW-ORDER' }}</span>
                            </div>
                            <div class="order-info-row">
                                <span class="info-label">Date</span>
                                <span class="info-val" style="font-size:12px;">{{ $orders->created_at ?? '' }}</span>
                            </div>
                            <div class="order-info-row">
                                <span class="info-label">Status</span>
                                <span class="badge badge-pending">Pending</span>
                            </div>
                            <div class="order-info-row">
                                <span class="info-label">Served by</span>
                                <span class="info-val">{{ $orders->served_by ?? '' }}</span>
                            </div>
                        </div>

                       
                    

                        {{-- Supplier --}}
                        <div style="margin-bottom:1.25rem;">
                            <div class="section-label">Supplier</div>
                            <form action="/admin/saveInfo" method="post">
                                @csrf
                                <input type="hidden" name="requestName" value="{{ $orders->requestName ?? 'NEW-ORDER' }}">
                                <div class="form-group" style="margin-bottom:0;">
                                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
                                        <label class="form-label" style="margin-bottom:0;">Select supplier</label>
                                        <span style="font-size:12px; color:var(--slate-500);">Current: <strong>{{ $supplierName }}</strong></span>
                                    </div>
                                    <select class="form-select" name="selectedCustomer" onchange="this.form.submit()">
                                        <option value="">— Choose supplier —</option>
                                        <option value="7">Main Store</option>
                                        @foreach ($Customers as $customer)
                                            <option value="{{ $customer->id }}">
                                                {{ $customer->name }} — {{ $customer->id }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                               
                            </form>

                            <form action="/admin/saveInfo" method="post">
                                @csrf
                                <input type="hidden" name="requestName" value="{{ $orders->requestName ?? 'NEW-ORDER' }}">
                                  {{-- Assign to --}}
                            <div class="form-group">
                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
                                       <label class="form-label" for="assignedTo">Assign to</label>
                                        <span style="font-size:12px; color:var(--slate-500);">Current: <strong>{{ $Allocation }}</strong></span>
                                    </div>
                              
                                    </div>
                                <select class="form-select" name="assignedTo" id="assignedTo" onchange="this.form.submit()">
                                    <option value="">— Select user / location —</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ (old('assignedTo') == $user->id || ($orders && $orders->assigned_to == $user->id)) ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->levelStatus ?? 'User' }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="form-hint">Select the user or location for this request</p>
                            </div>
                            </form>
                        </div>

                        {{-- Pricing --}}
                        <div class="pricing-block">
                            <div class="price-row">
                                <span>Subtotal</span>
                                <span class="price-num">{{ number_format($carts->sum('totalPrice'), 2) }} TZS</span>
                            </div>
                            <div class="price-row grand">
                                <span>Grand total</span>
                                <span class="price-num">{{ number_format($carts->sum('totalPrice'), 2) }} TZS</span>
                            </div>
                        </div>

                        {{-- Submit --}}
                        @if($orders->requestName ?? '')
                        <form action="/admin/requestSubmit" method="POST">
                            @csrf
                                <div style="margin-bottom:1.25rem;">
                            <div class="section-label">Request Shop</div>
   
                                <input type="hidden" name="requestName" value="{{ $orders->requestName ?? 'NEW-ORDER' }}">
                            
                        </div>
                            <div class="form-group">
                                <label class="form-label" for="requestDate">Request date</label>
                                <input type="date" class="form-control" id="requestDate"
                                       name="requestDatePicker"
                                       value="{{ old('requestDate', date('Y-m-d')) }}">
                            </div>
     
                            <button type="submit" class="btn-submit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                Submit request
                            </button>
                        </form>
                        @else
                        <div class="alert alert-info" style="margin:0;">
                            <span>Add products to begin your request</span>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
$(document).ready(function () {

    $('#product-name').on('input', function () {
        let query = $(this).val().trim();
        if (query.length > 1) {
            $.ajax({
                url: "{{ url('admin/searchProduct') }}",
                method: 'GET',
                data: { query: query },
                success: function (data) {
                    if (!data || data.error) {
                        $('#search-results')
                            .html('<div class="search-no-results">' + (data.error || 'No results found') + '</div>')
                            .show();
                        return;
                    }
                    let output = '';
                    data.forEach(function (product) {
                        let price = parseFloat(product.sPrice) || 0;
                        output += `
                            <div class="search-item"
                                 data-product_id="${product.product_id}"
                                 data-name01="${product.name01}"
                                 data-price="${price}"
                                 data-discount="${product.discount || 0}">
                                <div class="search-item-name">${product.name01}</div>
                                <div class="search-item-price">${price.toFixed(2)} TZS</div>
                            </div>`;
                    });
                    $('#search-results').html(output).show();
                },
                error: function (xhr) {
                    let msg = 'Error loading results';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        msg = xhr.responseJSON.error;
                    } else if (xhr.status === 401) {
                        msg = 'Session expired. Please refresh the page and try again.';
                    } else if (xhr.status === 0) {
                        msg = 'Cannot connect to server. Please check your connection.';
                    }
                    $('#search-results')
                        .html('<div class="search-no-results">' + msg + '</div>')
                        .show();
                }
            });
        } else {
            $('#search-results').hide().html('');
        }
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#product-name, #search-results').length) {
            $('#search-results').hide();
        }
    });

    $(document).on('click', '#search-results .search-item', function () {
        $('#search-results .search-item').removeClass('selected');
        $(this).addClass('selected');

        let productName        = $(this).data('name01');
        let productPrice       = $(this).data('price');
        let productId          = $(this).data('product_id');
        let productMaxDiscount = $(this).data('discount') || 0;

        $('#product-name').val(productName);
        $('#pId').val(productId);
        $('#pPrice').val(productPrice);
        $('#maxDiscount').val(productMaxDiscount);
        $('#search-results').hide();

        $('#formRequestDate').val($('#requestDate').val());

        $('#addItemBtn').prop('disabled', false);

        setTimeout(function () {
            $('#addItemForm').submit();
        }, 100);
    });

    $('#requestDate').on('change', function () {
        $('#formRequestDate').val($(this).val());
    });

});
</script>
</body>
</html>