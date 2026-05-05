<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} — Supplier Management</title>

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

            --font:   'Sora', system-ui, sans-serif;
            --mono:   'JetBrains Mono', monospace;
            --r:      8px;
            --r-lg:   13px;
            --r-xl:   16px;
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
        .wrap { padding: 1.5rem 1.75rem 3rem; max-width: 1600px; }

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
        /* Decorative circles */
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

        .back-btn {
            width: 34px; height: 34px; flex-shrink: 0;
            border-radius: var(--r);
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.16);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.7); text-decoration: none; font-size: 13px;
            transition: all 0.15s;
        }
        .back-btn:hover { background: rgba(255,255,255,0.16); color: var(--white); }

        .header-icon {
            width: 38px; height: 38px; border-radius: var(--r);
            background: var(--amber); display: flex; align-items: center;
            justify-content: center; font-size: 17px; color: var(--navy); flex-shrink: 0;
        }

        .pg-title-text h1 {
            font-size: 16px; font-weight: 700; color: var(--white);
            letter-spacing: -0.2px; line-height: 1.2;
        }
        .pg-title-text p { font-size: 12px; color: rgba(255,255,255,0.45); margin-top: 1px; }

        .pg-right { display: flex; gap: 8px; align-items: center; position: relative; z-index: 1; }

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

        .btn-ghost {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 14px; border-radius: var(--r);
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.16);
            color: rgba(255,255,255,0.75); font-family: var(--font);
            font-size: 13px; font-weight: 500; cursor: pointer;
            transition: all 0.15s; text-decoration: none;
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.14); color: var(--white); }

        /* ══════════════════════════════════════
           SEARCH BAR
        ══════════════════════════════════════ */
        .search-bar {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg); padding: 0.75rem 1.1rem;
            margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 10px;
            box-shadow: 0 1px 4px rgba(11,30,61,0.05);
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .search-bar:focus-within {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.09), 0 1px 4px rgba(11,30,61,0.05);
        }
        .search-bar i { color: var(--slate-400); font-size: 15px; flex-shrink: 0; }
        .search-bar input {
            flex: 1; border: none; outline: none; background: transparent;
            font-family: var(--font); font-size: 13.5px; color: var(--slate-800);
        }
        .search-bar input::placeholder { color: var(--slate-400); }
        .search-count {
            font-size: 12px; color: var(--slate-400);
            background: var(--slate-100); border-radius: 20px;
            padding: 2px 10px; font-family: var(--mono); flex-shrink: 0;
        }
        .btn-clear {
            font-size: 12px; font-weight: 600; padding: 4px 12px;
            background: var(--slate-100); border: 1px solid var(--slate-200);
            border-radius: 6px; color: var(--slate-600); cursor: pointer;
            transition: all 0.15s; flex-shrink: 0;
        }
        .btn-clear:hover { background: var(--slate-200); color: var(--slate-800); }

        /* ══════════════════════════════════════
           STATS GRID
        ══════════════════════════════════════ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem; margin-bottom: 1.4rem;
        }
        @media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 540px) { .stats-grid { grid-template-columns: 1fr; } }

        .stat-card {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg);
            padding: 1.1rem 1.2rem;
            box-shadow: 0 1px 4px rgba(11,30,61,0.05);
            position: relative; overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card::after {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px; background: var(--sc);
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(11,30,61,0.1); }

        .sc-navy    { --sc: var(--navy); }
        .sc-emerald { --sc: var(--emerald); }
        .sc-sky     { --sc: var(--sky); }
        .sc-amber   { --sc: var(--amber); }

        .stat-inner { display: flex; align-items: center; gap: 12px; }
        .stat-icon-wrap {
            width: 42px; height: 42px; border-radius: var(--r); flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 18px;
        }
        .si-navy    { background: rgba(11,30,61,0.08);   color: var(--navy); }
        .si-emerald { background: var(--emerald-pale);   color: var(--emerald); }
        .si-sky     { background: var(--sky-pale);       color: var(--sky); }
        .si-amber   { background: var(--amber-pale);     color: var(--amber); }

        .stat-body {}
        .stat-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: var(--slate-400); margin-bottom: 4px; }
        .stat-value { font-family: var(--mono); font-size: 22px; font-weight: 500; color: var(--navy); letter-spacing: -0.5px; line-height: 1; }

        /* ══════════════════════════════════════
           PANEL / TABLE
        ══════════════════════════════════════ */
        .panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: var(--r-xl);
            box-shadow: 0 1px 4px rgba(11,30,61,0.05);
            overflow: hidden;
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
            display: flex; align-items: center; justify-content: center; font-size: 14px;
        }
        .panel-title { font-size: 13.5px; font-weight: 700; color: var(--navy); }
        .result-pill {
            font-size: 11px; font-weight: 600; font-family: var(--mono);
            background: var(--slate-200); color: var(--slate-600);
            padding: 2px 9px; border-radius: 20px;
        }

        .panel-tools { display: flex; gap: 6px; align-items: center; }
        .tool-btn {
            width: 30px; height: 30px; border-radius: var(--r);
            border: 1px solid var(--slate-200); background: transparent;
            color: var(--slate-500); font-size: 13px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.15s; text-decoration: none;
        }
        .tool-btn:hover { background: var(--slate-100); color: var(--navy); }
        .tool-btn::after { display: none !important; }

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
        thead th:first-child { border-radius: 0; color: rgba(255,255,255,0.4); }

        tbody tr { border-bottom: 1px solid var(--slate-100); transition: background 0.12s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover td { background: #F7F9FF; }

        td { padding: 11px 16px; vertical-align: middle; }

        .idx { font-size: 11.5px; color: var(--slate-400); font-family: var(--mono); }

        .vendor-avatar {
            width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
            background: var(--navy-mid); color: rgba(255,255,255,0.85);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 600;
        }
        .vendor-cell { display: flex; align-items: center; gap: 10px; }
        .vendor-name { font-weight: 600; color: var(--navy); font-size: 13px; }
        .vendor-loc  { font-size: 11.5px; color: var(--slate-400); margin-top: 1px; }

        .contact-val { font-size: 13px; color: var(--slate-700); }

        .type-badge {
            display: inline-block; padding: 3px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 600; letter-spacing: 0.02em;
        }
        .tb-wholesale    { background: var(--sky-pale);     color: var(--sky); }
        .tb-manufacturer { background: var(--violet-pale);  color: var(--violet); }
        .tb-distributor  { background: var(--emerald-pale); color: var(--emerald); }
        .tb-retailer     { background: var(--amber-pale);   color: #92400e; }
        .tb-default      { background: var(--slate-100);    color: var(--slate-600); }

        .credit-pos { font-family: var(--mono); font-weight: 500; color: var(--emerald); font-size: 12.5px; }
        .credit-nil { font-family: var(--mono); font-weight: 400; color: var(--slate-400); font-size: 12.5px; }

        /* Action buttons */
        .action-btns { display: flex; gap: 5px; justify-content: flex-end; }
        .act-btn {
            width: 30px; height: 30px; border-radius: var(--r);
            border: 1.5px solid; background: transparent;
            display: flex; align-items: center; justify-content: center;
            font-size: 12.5px; cursor: pointer; transition: all 0.15s;
        }
        .ab-view   { border-color: var(--sky);     color: var(--sky); }
        .ab-edit   { border-color: var(--amber);   color: var(--amber); }
        .ab-delete { border-color: var(--rose);    color: var(--rose); }
        .ab-view:hover   { background: var(--sky-pale);    transform: scale(1.1); }
        .ab-edit:hover   { background: var(--amber-pale);  transform: scale(1.1); }
        .ab-delete:hover { background: var(--rose-pale);   transform: scale(1.1); }

        /* Bottom CTA */
        .panel-footer {
            padding: 1.25rem 1.4rem;
            border-top: 1.5px solid var(--slate-200);
            display: flex; justify-content: center;
        }

        /* Empty state */
        .empty-state {
            text-align: center; padding: 4rem 1.5rem;
            color: var(--slate-400);
        }
        .empty-state i { font-size: 3rem; display: block; margin-bottom: 0.75rem; opacity: 0.3; }
        .empty-state h4 { font-size: 15px; font-weight: 600; color: var(--slate-600); margin-bottom: 5px; }
        .empty-state p  { font-size: 13px; margin-bottom: 1.25rem; }

        /* ══════════════════════════════════════
           MODAL
        ══════════════════════════════════════ */
        .modal-content { border: none; border-radius: var(--r-xl); overflow: hidden; box-shadow: 0 20px 60px rgba(11,30,61,0.2); }

        .modal-top {
            background: var(--navy); padding: 1.15rem 1.4rem;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: none;
        }
        .modal-top-left { display: flex; align-items: center; gap: 10px; }
        .modal-top-icon {
            width: 32px; height: 32px; border-radius: var(--r);
            background: var(--amber); display: flex; align-items: center;
            justify-content: center; color: var(--navy); font-size: 14px;
        }
        .modal-top h5 { font-size: 15px; font-weight: 700; color: var(--white); margin: 0; }
        .modal-top .btn-close { filter: invert(1) brightness(0.75); }

        .modal-body { padding: 1.6rem 1.4rem; }

        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        @media (max-width: 560px) { .field-row { grid-template-columns: 1fr; } }

        .field { margin-bottom: 12px; }
        .field:last-child { margin-bottom: 0; }

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
            cursor: pointer; appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center;
            padding-right: 2.25rem;
        }
        textarea.field-input { resize: vertical; min-height: 80px; }

        .modal-divider {
            height: 1px; background: var(--slate-200);
            margin: 1rem 0; border: none;
        }
        .modal-section-label {
            font-size: 10.5px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: var(--slate-400); margin-bottom: 10px;
        }

        .btn-submit {
            width: 100%; padding: 11px; margin-top: 6px;
            background: var(--amber); color: var(--navy);
            font-family: var(--font); font-size: 14px; font-weight: 700;
            border: none; border-radius: var(--r); cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 7px;
            box-shadow: 0 3px 14px rgba(245,158,11,0.3);
            transition: all 0.18s;
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245,158,11,0.4); }

        /* ── Dropdown menu ── */
        .dropdown-menu {
            border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg);
            box-shadow: 0 8px 32px rgba(11,30,61,0.13);
            padding: 0.4rem 0; min-width: 150px;
        }
        .dropdown-item {
            padding: 0.55rem 1rem; font-size: 13px; font-weight: 500;
            color: var(--slate-700); display: flex; align-items: center; gap: 7px;
            transition: all 0.12s;
        }
        .dropdown-item:hover { background: var(--slate-50); color: var(--navy); }

        /* ── Responsive mobile card table ── */
        @media (max-width: 720px) {
            .wrap { padding: 1rem; }
            .pg-header { padding: 0.9rem 1.1rem; }
            table thead { display: none; }
            tbody tr {
                display: block; margin-bottom: 0.85rem;
                border: 1.5px solid var(--slate-200); border-radius: var(--r-lg);
                padding: 0.85rem 1rem; background: var(--white);
            }
            tbody tr td {
                display: flex; justify-content: space-between;
                align-items: center; padding: 6px 0;
                border-bottom: 1px solid var(--slate-100);
            }
            tbody tr td:last-child { border-bottom: none; padding-top: 10px; margin-top: 6px; border-top: 1.5px solid var(--slate-200); }
            tbody tr td::before {
                content: attr(data-label);
                font-size: 10.5px; font-weight: 700; text-transform: uppercase;
                letter-spacing: 0.05em; color: var(--slate-400); min-width: 90px;
            }
        }

        @media print {
            .d-print-none, .pg-right, .search-bar, .panel-tools, .action-btns, .panel-footer { display: none !important; }
            body { background: white; }
            .panel { box-shadow: none; border: 1px solid #ccc; }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @include("user/sidenav")

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3">
            <div class="wrap">

                {{-- ══ PAGE HEADER ══ --}}
                <div class="pg-header au au1">
                    <div class="pg-left">
                        <a href="#" onclick="history.back()" class="back-btn d-print-none">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                        <div class="header-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="pg-title-text">
                            <h1>Supplier Management</h1>
                            <p>{{ count($fetch) }} suppliers registered</p>
                        </div>
                    </div>
                    <div class="pg-right">
                        <button class="btn-ghost d-print-none" onclick="window.print()">
                            <i class="bi bi-printer"></i> Print
                        </button>
                        @if(canUser('add_suppliers'))
                            <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                            <i class="bi bi-plus-lg"></i> New Supplier
                        </button>
                        @endif
                    </div>
                </div>

                {{-- ══ SHOP FILTER ══ --}}
                @if(isset($shops) && $shops->count() > 0)
                <form method="GET" action="{{ url('admin/vendors') }}" id="shopFilterForm" style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
                    <div style="flex: 1; min-width: 200px;">
                        <label for="shopFilter" style="font-size: 11px; font-weight: 700; color: var(--slate-500); margin-bottom: 4px; display: block;">Filter by Shop</label>
                        <select name="shop" id="shopFilter" class="form-control" style="border: 1.5px solid var(--slate-200); border-radius: var(--r-lg); padding: 0.65rem 2rem 0.65rem 0.75rem; background: white; font-size: 13.5px; color: var(--slate-800); outline: none; cursor: pointer; transition: border-color 0.18s; min-width: 250px;">
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ (isset($selectedShopId) && $selectedShopId == $shop->id) ? 'selected' : '' }}>
                                    {{ $shop->name }} - {{ $shop->location }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
                @endif

                {{-- ══ SEARCH ══ --}}
                <div class="search-bar au au2">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Search by name, contact, or business type…">
                    <span class="search-count" id="searchCount">{{ count($fetch) }} results</span>
                    <button class="btn-clear" id="clearBtn">Clear</button>
                </div>

                {{-- ══ STATS ══ --}}
                <div class="stats-grid">
                    <div class="stat-card sc-navy au au2">
                        <div class="stat-inner">
                            <div class="stat-icon-wrap si-navy"><i class="bi bi-people-fill"></i></div>
                            <div class="stat-body">
                                <div class="stat-label">Total suppliers</div>
                                <div class="stat-value">{{ count($fetch) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card sc-emerald au au3">
                        <div class="stat-inner">
                            <div class="stat-icon-wrap si-emerald"><i class="bi bi-box-seam-fill"></i></div>
                            <div class="stat-body">
                                <div class="stat-label">Wholesale</div>
                                <div class="stat-value">{{ $fetch->where('businessType', 'Wholesale')->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card sc-sky au au4">
                        <div class="stat-inner">
                            <div class="stat-icon-wrap si-sky"><i class="bi bi-credit-card-fill"></i></div>
                            <div class="stat-body">
                                <div class="stat-label">Active credit</div>
                                <div class="stat-value" style="font-size:17px;">{{ number_format($fetch->sum('credit')) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card sc-amber au au5">
                        <div class="stat-inner">
                            <div class="stat-icon-wrap si-amber"><i class="bi bi-gear-fill"></i></div>
                            <div class="stat-body">
                                <div class="stat-label">Manufacturers</div>
                                <div class="stat-value">{{ $fetch->where('businessType', 'Manufacturer')->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══ TABLE PANEL ══ --}}
                <div class="panel au au5">
                    <div class="panel-head">
                        <div class="panel-head-left">
                            <div class="panel-head-icon"><i class="bi bi-table"></i></div>
                            <span class="panel-title">Supplier Directory</span>
                            <span class="result-pill" id="tableCount">{{ count($fetch) }}</span>
                        </div>
                        <div class="panel-tools">
                            <div class="dropdown">
                                <a class="tool-btn" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="window.print()">
                                            <i class="bi bi-printer"></i> Print
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="downloadReport()">
                                            <i class="bi bi-download"></i> Export
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if($fetch->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-people"></i>
                        <h4>No suppliers found</h4>
                        <p>Add your first supplier to get started managing your vendors</p>
                        <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                            <i class="bi bi-plus-lg"></i> Add Supplier
                        </button>
                    </div>
                    @else
                    <div class="tbl-wrap">
                        <table id="vendorTable">
                            <thead>
                                <tr>
                                    <th width="4%">#</th>
                                    <th>Supplier</th>
                                    <th>Contact</th>
                                    <th>Type</th>
                                    <th>Credit (Tsh)</th>
                                    <th style="text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="vendorBody">
                                @foreach ($fetch as $index => $vendor)
                                @php
                                    $initials = strtoupper(substr($vendor->name, 0, 1));
                                    $parts    = explode(' ', trim($vendor->name));
                                    if (count($parts) > 1) $initials .= strtoupper(substr($parts[1], 0, 1));
                                    $type = $vendor->businessType ?? '';
                                    $typeCls = match($type) {
                                        'Wholesale'    => 'tb-wholesale',
                                        'Manufacturer' => 'tb-manufacturer',
                                        'Distributor'  => 'tb-distributor',
                                        'Retailer'     => 'tb-retailer',
                                        default        => 'tb-default',
                                    };
                                @endphp
                                <tr data-search="{{ strtolower($vendor->name . ' ' . $vendor->contact . ' ' . $vendor->businessType) }}">
                                    <td data-label="#" class="idx">{{ $index + 1 }}</td>
                                    <td data-label="Supplier">
                                        <div class="vendor-cell">
                                            <div class="vendor-avatar">{{ $initials }}</div>
                                            <div>
                                                <div class="vendor-name">{{ $vendor->name }}</div>
                                                <div class="vendor-loc">{{ $vendor->location }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Contact" class="contact-val">{{ $vendor->contact }}</td>
                                    <td data-label="Type">
                                        <span class="type-badge {{ $typeCls }}">{{ $type }}</span>
                                    </td>
                                    <td data-label="Credit" class="{{ $vendor->credit > 0 ? 'credit-pos' : 'credit-nil' }}">
                                        {{ number_format($vendor->credit) }}
                                    </td>
                                    <td data-label="Actions">
                                        <div class="action-btns">
                                            <form action="" method="post" style="display:contents;">
                                                @csrf
                                                <button formaction="viewVendor" class="act-btn ab-view"
                                                        name="vendorId" value="{{ $vendor->id }}" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                @if (canUser('edit_suppliers'))
                                                    <button type="button" class="act-btn ab-edit"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editVendor{{ $vendor->id }}" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                @endif
                                                @if (canUser('delete_suppliers'))
                                                <button formaction="dltVendeor" class="act-btn ab-delete"
                                                        name="product_id" value="{{ $vendor->id }}"
                                                        onclick="return confirm('Delete this supplier? This cannot be undone.')"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                @endif
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Edit modal --}}
                                <div class="modal fade" id="editVendor{{ $vendor->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-top">
                                                <div class="modal-top-left">
                                                    <div class="modal-top-icon"><i class="bi bi-pencil-fill"></i></div>
                                                    <h5>Edit Supplier</h5>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="updateVendor" method="post">
                                                    @csrf
                                                    <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">

                                                    <div class="field-row">
                                                        <div class="field">
                                                            <label class="field-label">Name <span class="req">*</span></label>
                                                            <input type="text" class="field-input" name="name" value="{{ $vendor->name }}" required>
                                                        </div>
                                                        <div class="field">
                                                            <label class="field-label">Contact <span class="req">*</span></label>
                                                            <input type="text" class="field-input" name="contact" value="{{ $vendor->contact }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="field">
                                                        <label class="field-label">Address <span class="req">*</span></label>
                                                        <input type="text" class="field-input" name="address" value="{{ $vendor->location }}" required>
                                                    </div>

                                                    <div class="field-row">
                                                        <div class="field">
                                                            <label class="field-label">Business Type <span class="req">*</span></label>
                                                            <select name="type" class="field-input" required>
                                                                <option value="Wholesale"    {{ $vendor->businessType == 'Wholesale'    ? 'selected' : '' }}>Wholesale</option>
                                                                <option value="Manufacturer" {{ $vendor->businessType == 'Manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                                                                <option value="Distributor"  {{ $vendor->businessType == 'Distributor'  ? 'selected' : '' }}>Distributor</option>
                                                                <option value="Retailer"     {{ $vendor->businessType == 'Retailer'     ? 'selected' : '' }}>Retailer</option>
                                                            </select>
                                                        </div>
                                                        <div class="field">
                                                            <label class="field-label">Bank Name</label>
                                                            <input type="text" class="field-input" name="bank" value="{{ $vendor->bank }}">
                                                        </div>
                                                    </div>

                                                    <div class="field">
                                                        <label class="field-label">Account Number</label>
                                                        <input type="text" class="field-input" name="account" value="{{ $vendor->account }}">
                                                    </div>

                                                    <div class="field">
                                                        <label class="field-label">Description</label>
                                                        <textarea name="description" class="field-input">{{ $vendor->description }}</textarea>
                                                    </div>

                                                    <button type="submit" class="btn-submit">
                                                        <i class="bi bi-check-circle-fill"></i> Update Supplier
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="panel-footer">
                        <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                            <i class="bi bi-plus-lg"></i> Add New Supplier
                        </button>
                    </div>
                    @endif
                </div>

            </div>
        </main>
    </div>
</div>

{{-- ══════════════════════════════════════
     ADD SUPPLIER MODAL
══════════════════════════════════════ --}}
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-top">
                <div class="modal-top-left">
                    <div class="modal-top-icon"><i class="bi bi-person-plus-fill"></i></div>
                    <h5>Add New Supplier</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="newVendor" method="post">
                    @csrf

                    <div class="modal-section-label">Basic information</div>
                    <div class="field-row">
                        <div class="field">
                            <label class="field-label">Supplier name <span class="req">*</span></label>
                            <input type="text" class="field-input" name="name" placeholder="e.g. Kariakoo Traders" required>
                        </div>
                        <div class="field">
                            <label class="field-label">Contact <span class="req">*</span></label>
                            <input type="text" class="field-input" name="contact" placeholder="Phone or email" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">Business address <span class="req">*</span></label>
                        <input type="text" class="field-input" name="address" placeholder="Physical location" required>
                    </div>

                    <div class="field">
                        <label class="field-label">Business type <span class="req">*</span></label>
                        <select name="type" class="field-input" required>
                            <option value="" disabled selected>Select type</option>
                            <option value="Wholesale">Wholesale</option>
                            <option value="Manufacturer">Manufacturer</option>
                            <option value="Distributor">Distributor</option>
                            <option value="Retailer">Retailer</option>
                        </select>
                    </div>

                    <hr class="modal-divider">
                    <div class="modal-section-label">Banking (optional)</div>

                    <div class="field-row">
                        <div class="field">
                            <label class="field-label">Bank name</label>
                            <input type="text" class="field-input" name="bank" placeholder="e.g. CRDB Bank">
                        </div>
                        <div class="field">
                            <label class="field-label">Account number</label>
                            <input type="text" class="field-input" name="account" placeholder="Account number">
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">Description</label>
                        <textarea name="description" class="field-input" placeholder="Additional notes about this supplier"></textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="bi bi-save-fill"></i> Save Supplier
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const shopFilter = document.getElementById('shopFilter');
    const searchInput = document.getElementById('searchInput');
    const clearBtn    = document.getElementById('clearBtn');
    const rows        = document.querySelectorAll('#vendorBody tr[data-search]');
    const countEl     = document.getElementById('searchCount');
    const tableCountEl = document.getElementById('tableCount');

    // Auto-submit when shop filter changes
    shopFilter && shopFilter.addEventListener('change', function () {
        this.form.submit();
    });

    function updateCount(visible) {
        const t = visible + ' result' + (visible !== 1 ? 's' : '');
        if (countEl)     countEl.textContent = t;
        if (tableCountEl) tableCountEl.textContent = visible;
    }

    searchInput && searchInput.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let visible = 0;
        rows.forEach(row => {
            const match = !q || row.dataset.search.includes(q);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        updateCount(visible);
    });

    clearBtn && clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        shopFilter.value = '';
        rows.forEach(row => row.style.display = '');
        updateCount(rows.length);
        searchInput.focus();
    });

    function downloadReport() {
        window.location.href = "{{ route('admin.product.report.export') }}";
    }
</script>
</body>
</html>