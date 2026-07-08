<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} — Customer Details</title>
    @include('links')
    
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy: #0B1E3D;
            --navy-mid: #112952;
            --navy-light: #1A3A6B;
            --amber: #F59E0B;
            --amber-pale: #FEF3C7;
            --emerald: #059669;
            --emerald-pale: #D1FAE5;
            --rose: #E11D48;
            --rose-pale: #FFE4E6;
            --violet: #7C3AED;
            --violet-pale: #EDE9FE;
            --sky: #0284C7;
            --sky-pale: #E0F2FE;
            --slate-50: #F8FAFC;
            --slate-100: #F1F5F9;
            --slate-200: #E2E8F0;
            --slate-300: #CBD5E1;
            --slate-400: #94A3B8;
            --slate-500: #64748B;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1E293B;
            --white: #FFFFFF;
            --font: 'Sora', system-ui, sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --r: 8px; --r-lg: 13px; --r-xl: 16px;
            --shadow-sm: 0 1px 4px rgba(11,30,61,.06);
            --shadow: 0 4px 20px rgba(11,30,61,.08);
            --shadow-lg: 0 10px 40px rgba(11,30,61,.12);
        }

        body { background: var(--slate-50); color: var(--slate-800); min-height: 100vh; font-size: 14px; line-height: 1.6; font-family: var(--font); }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }
        .wrap { padding: 1.5rem 1.75rem 3rem; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
        .au  { animation: fadeUp 0.38s ease both; }
        .au1 { animation-delay:.04s; } .au2 { animation-delay:.10s; }
        .au3 { animation-delay:.16s; } .au4 { animation-delay:.22s; }

        /* ══ PAGE HEADER ══ */
        .pg-header {
            background: var(--navy); border-radius: var(--r-xl);
            padding: 1.2rem 1.6rem; margin-bottom: 1.4rem;
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; flex-wrap: wrap; position: relative; overflow: hidden;
        }
        .pg-header::before { content:''; position:absolute; top:-50px; right:-30px; width:180px; height:180px; border-radius:50%; background:var(--navy-light); opacity:.45; pointer-events:none; }
        .pg-header::after  { content:''; position:absolute; bottom:-55px; right:100px; width:120px; height:120px; border-radius:50%; background:var(--amber); opacity:.07; pointer-events:none; }
        .pg-left { display:flex; align-items:center; gap:10px; position:relative; z-index:1; }
        .back-btn { width:34px; height:34px; border-radius:var(--r); background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.15); display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,.7); cursor:pointer; flex-shrink:0; transition:all .15s; text-decoration:none; }
        .back-btn:hover { background:rgba(255,255,255,.16); color:var(--white); }
        .header-icon { width:40px; height:40px; border-radius:var(--r); background:var(--amber); display:flex; align-items:center; justify-content:center; font-size:18px; color:var(--navy); flex-shrink:0; }
        .pg-title-text h1 { font-size:16px; font-weight:700; color:var(--white); letter-spacing:-.2px; }
        .pg-title-text p  { font-size:12px; color:rgba(255,255,255,.45); margin-top:1px; }
        .pg-right { position:relative; z-index:1; display:flex; gap:8px; flex-wrap:wrap; }
        .btn-amber { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:var(--r); background:var(--amber); color:var(--navy); font-size:13px; font-weight:700; border:none; cursor:pointer; box-shadow:0 3px 14px rgba(245,158,11,.35); transition:all .18s; text-decoration:none; }
        .btn-amber:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(245,158,11,.45); color:var(--navy); }
        .btn-outline-amber { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:var(--r); background:transparent; color:var(--white); font-size:13px; font-weight:600; border:1.5px solid rgba(255,255,255,.15); cursor:pointer; transition:all .18s; text-decoration:none; }
        .btn-outline-amber:hover { background:rgba(255,255,255,.08); border-color:var(--amber); color:var(--amber); }

        /* ══ PAGE GRID ══ */
        .page-grid { display:grid; grid-template-columns: 320px 1fr; gap:1.5rem; align-items:start; }
        @media(max-width:900px) { .page-grid { grid-template-columns:1fr; } }

        /* ══ PROFILE CARD ══ */
        .profile-card {
            background: var(--white); border: 1px solid var(--slate-200);
            border-radius: var(--r-xl); overflow: hidden;
            box-shadow: var(--shadow-sm);
            position: sticky; top: 1.5rem;
            transition: box-shadow .3s ease;
        }
        .profile-card:hover { box-shadow: var(--shadow); }

        .profile-banner {
            height: 90px;
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
            position: relative;
        }
        .profile-banner::after { content:''; position:absolute; inset:0; background:linear-gradient(135deg, var(--navy-light) 0%, transparent 70%); }

        .profile-avatar-wrap {
            display: flex; flex-direction: column; align-items: center;
            padding: 0 1.25rem 1.25rem; margin-top: -50px; position: relative; z-index: 1;
        }
        .cust-avatar {
            width: 96px; height: 96px; border-radius: 50%;
            border: 4px solid var(--white); background: var(--navy-mid);
            display: flex; align-items: center; justify-content: center;
            font-size: 32px; font-weight: 700; color: rgba(255,255,255,.9);
            box-shadow: 0 4px 20px rgba(11,30,61,.2); flex-shrink: 0;
        }
        .cust-name { font-size: 17px; font-weight: 700; color: var(--navy); margin-top: .75rem; text-align: center; }
        .cust-type-badge {
            display: inline-flex; align-items: center; gap: 5px; margin-top: 4px;
            padding: 3px 14px; border-radius: 20px;
            background: var(--sky-pale); color: var(--sky);
            font-size: 11.5px; font-weight: 600;
        }
        .cust-status-badge {
            display: inline-flex; align-items: center; gap: 5px; margin-top: 4px;
            padding: 3px 14px; border-radius: 20px;
            background: var(--emerald-pale); color: var(--emerald);
            font-size: 11px; font-weight: 600;
        }

        /* Stats strip */
        .cust-stats { display:grid; grid-template-columns:1fr 1fr; gap:1px; background:var(--slate-200); margin-top:1rem; }
        .cs-cell { background:var(--white); padding:.85rem 1rem; text-align:center; }
        .cs-val   { font-size:18px; font-weight:700; color:var(--navy); font-family:var(--mono); }
        .cs-label { font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:.07em; color:var(--slate-400); margin-top:2px; }
        .cs-val.credit { color:var(--emerald); }

        /* Meta list */
        .cust-meta { padding: 1rem 1.25rem; }
        .meta-row { display:flex; align-items:flex-start; gap:10px; font-size:12.5px; color:var(--slate-600); padding:7px 0; border-bottom:1px solid var(--slate-100); }
        .meta-row:last-child { border-bottom:none; }
        .meta-icon { width:30px; height:30px; border-radius:var(--r); background:var(--slate-100); color:var(--slate-400); display:flex; align-items:center; justify-content:center; font-size:13px; flex-shrink:0; }
        .meta-content label { font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--slate-400); display:block; margin-bottom:1px; }
        .meta-content span  { font-weight:500; color:var(--slate-800); }
        .credit-val { color: var(--emerald); font-weight:700; font-size:14px; }

        /* ══ SALES PANEL ══ */
        .panel {
            background: var(--white); border: 1px solid var(--slate-200);
            border-radius: var(--r-xl); overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: box-shadow .3s ease;
        }
        .panel:hover { box-shadow: var(--shadow); }

        .panel-head {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.4rem; border-bottom: 1px solid var(--slate-200);
            background: var(--slate-50); gap: 1rem; flex-wrap: wrap;
        }
        .panel-head-left { display:flex; align-items:center; gap:10px; }
        .panel-head-icon { width:32px; height:32px; border-radius:var(--r); background:rgba(245,158,11,.12); color:var(--amber); display:flex; align-items:center; justify-content:center; font-size:14px; }
        .panel-title { font-size:14px; font-weight:700; color:var(--navy); }
        .result-pill { font-size:11px; font-weight:600; background:var(--slate-200); color:var(--slate-600); padding:2px 10px; border-radius:20px; }

        /* Filter */
        .filter-wrap { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
        .search-wrap { position:relative; }
        .search-wrap i { position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--slate-400); font-size:13px; pointer-events:none; }
        .search-input { padding:7px 12px 7px 30px; border:1.5px solid var(--slate-200); border-radius:var(--r); font-size:13px; color:var(--slate-800); background:var(--white); outline:none; width:180px; transition:all .18s; }
        .search-input:focus { border-color:var(--amber); box-shadow:0 0 0 3px rgba(245,158,11,.12); width:210px; }
        .date-input { padding:7px 12px; border:1.5px solid var(--slate-200); border-radius:var(--r); font-size:13px; color:var(--slate-800); background:var(--white); outline:none; width:140px; transition:all .18s; }
        .date-input:focus { border-color:var(--amber); box-shadow:0 0 0 3px rgba(245,158,11,.12); }
        .date-input::-webkit-calendar-picker-indicator { cursor:pointer; }

        /* Table */
        .tbl-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; font-size:13px; }
        thead th { background:var(--navy); color:rgba(255,255,255,.7); font-size:10.5px; font-weight:600; text-transform:uppercase; letter-spacing:.07em; padding:10px 16px; white-space:nowrap; border:none; text-align:left; }
        thead th:last-child { text-align:right; }
        tbody tr { border-bottom:1px solid var(--slate-100); transition:background .15s; }
        tbody tr:last-child { border-bottom:none; }
        tbody tr:hover td { background:var(--slate-50); }
        td { padding:11px 16px; vertical-align:middle; }
        td:last-child { text-align:right; }

        .idx-cell { font-size:11.5px; color:var(--slate-400); font-family:var(--mono); }
        .prod-name-primary   { font-weight:600; color:var(--navy); font-size:13px; }
        .prod-name-secondary { font-size:11.5px; color:var(--slate-400); margin-top:1px; }
        .qty-chip { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; background:var(--slate-100); font-size:12px; font-weight:600; color:var(--slate-600); }
        .qty-dot  { width:6px; height:6px; border-radius:50%; flex-shrink:0; background:var(--navy-light); }
        .price-val { font-weight:600; color:var(--navy); font-size:13px; }
        .date-val  { font-size:11.5px; color:var(--slate-400); }

        /* Action button */
        .act-view { display:inline-flex; align-items:center; gap:4px; padding:5px 14px; border-radius:var(--r); background:rgba(11,30,61,.06); color:var(--navy); border:none; font-size:12px; font-weight:600; cursor:pointer; transition:all .15s; text-decoration:none; }
        .act-view:hover { background:var(--navy); color:var(--white); }
        .act-view i { font-size:11px; }

        /* Empty state */
        .empty-state { text-align:center; padding:4rem 1.5rem; color:var(--slate-400); }
        .empty-state i { font-size:3rem; display:block; margin-bottom:.75rem; opacity:.3; color:var(--slate-300); }
        .empty-state h4 { font-size:16px; font-weight:600; color:var(--slate-600); margin-bottom:5px; }
        .empty-state p  { font-size:13px; }

        /* Pagination */
        .pagination-wrap { padding:1rem 1.4rem; display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--slate-200); flex-wrap:wrap; gap:0.75rem; }
        .pagination-info { font-size:12px; color:var(--slate-400); }

        /* ══ MODAL ══ */
        .modal-content { border:none; border-radius:var(--r-xl); overflow:hidden; box-shadow:var(--shadow-lg); }
        .modal-top { background:var(--navy); padding:1.15rem 1.4rem; display:flex; align-items:center; justify-content:space-between; }
        .modal-top-left { display:flex; align-items:center; gap:10px; }
        .modal-top-icon { width:32px; height:32px; border-radius:var(--r); background:var(--amber); display:flex; align-items:center; justify-content:center; color:var(--navy); font-size:14px; }
        .modal-top h5 { font-size:15px; font-weight:700; color:var(--white); margin:0; }
        .modal-top .btn-close { filter:invert(1) brightness(.75); }
        .modal-body { padding:1.5rem 1.4rem; }

        .field-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px; }
        @media(max-width:560px) { .field-row { grid-template-columns:1fr; } }
        .field { display:flex; flex-direction:column; gap:5px; }
        .field-label { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--slate-500); }
        .field-label .req { color:var(--rose); }
        .field-input { width:100%; font-size:13.5px; padding:9px 12px; border:1.5px solid var(--slate-200); border-radius:var(--r); background:var(--white); color:var(--slate-800); outline:none; transition:all .18s; }
        .field-input:focus { border-color:var(--amber); box-shadow:0 0 0 3px rgba(245,158,11,.12); }
        .field-input::placeholder { color:var(--slate-400); }
        select.field-input { appearance:none; cursor:pointer; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 12px center; padding-right:2.25rem; }
        textarea.field-input { resize:vertical; min-height:80px; }
        .input-prefix-wrap { display:flex; border:1.5px solid var(--slate-200); border-radius:var(--r); overflow:hidden; transition:all .18s; }
        .input-prefix-wrap:focus-within { border-color:var(--amber); box-shadow:0 0 0 3px rgba(245,158,11,.12); }
        .input-prefix { background:var(--slate-100); padding:9px 12px; font-size:13px; font-weight:600; color:var(--slate-500); border-right:1.5px solid var(--slate-200); white-space:nowrap; }
        .input-prefix-wrap input { flex:1; border:none; outline:none; padding:9px 12px; font-size:13.5px; color:var(--slate-800); background:transparent; }

        .modal-footer-custom { padding:1rem 1.4rem; border-top:1px solid var(--slate-200); display:flex; justify-content:flex-end; gap:8px; }
        .btn-cancel { padding:9px 18px; border-radius:var(--r); border:1.5px solid var(--slate-200); background:transparent; font-size:13px; color:var(--slate-600); cursor:pointer; transition:all .15s; }
        .btn-cancel:hover { background:var(--slate-100); }
        .btn-save { padding:9px 22px; border-radius:var(--r); background:var(--amber); color:var(--navy); border:none; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:0 3px 14px rgba(245,158,11,.3); transition:all .18s; }
        .btn-save:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(245,158,11,.4); }

        @media(max-width:768px) { .wrap { padding:1rem; } .pg-header { padding:1rem; } }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @include("sidenav")

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="wrap">

                    @if(!isset($get))
                    <div class="alert alert-danger" style="margin-bottom:1rem;">
                        <i class="bi bi-exclamation-circle me-2"></i>Customer not found or may have been deleted.
                    </div>
                    @endif

                    {{-- Page header --}}
                    <div class="pg-header au au1">
                        <div class="pg-left">
                            <a href="#" onclick="history.back()" class="back-btn"><i class="bi bi-chevron-left"></i></a>
                            <div class="header-icon"><i class="bi bi-person-fill"></i></div>
                            <div class="pg-title-text">
                                <h1>Customer Details</h1>
                                <p>Profile, contact info and sales history</p>
                            </div>
                        </div>
                        <div class="pg-right">
                            <a href="tel:{{ $get->phone }}" class="btn-outline-amber">
                                <i class="bi bi-telephone"></i> Call
                            </a>
                            <a href="{{ url('customer-kpi') }}" class="btn-outline-amber">
                                <i class="bi bi-graph-up"></i> KPI
                            </a>
                            @if (canUser('manage_customers'))
                                <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#editCustomer">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                            @endif                    
                        </div>
                    </div>

                    <div class="page-grid">

                        {{-- ═══ LEFT — Profile card ═══ --}}
                        <aside class="au au2">
                            <div class="profile-card">
                                <div class="profile-banner"></div>
                                <div class="profile-avatar-wrap">
                                    @php
                                        $initials = strtoupper(substr($get->name ?? 'C', 0, 1));
                                        $parts    = explode(' ', trim($get->name ?? ''));
                                        if (count($parts) > 1) $initials .= strtoupper(substr($parts[1], 0, 1));
                                    @endphp
                                    <div class="cust-avatar">{{ $initials }}</div>
                                    <div class="cust-name">{{ $get->name }}</div>
                                    <div style="display:flex; gap:4px; flex-wrap:wrap; justify-content:center;">
                                        <span class="cust-type-badge">
                                            <i class="bi bi-building" style="font-size:11px;"></i>
                                            {{ $get->business ?? 'N/A' }}
                                        </span>
                                        <span class="cust-status-badge">
                                            <i class="bi bi-check-circle-fill" style="font-size:10px;"></i>
                                            Active
                                        </span>
                                    </div>
                                </div>

                                <div class="cust-stats">
                                    <div class="cs-cell">
                                        <div class="cs-val">{{ $paginator->total() }}</div>
                                        <div class="cs-label">Purchases</div>
                                    </div>
                                    <div class="cs-cell">
                                        <div class="cs-val credit">{{ number_format($get->limits ?? 0) }}</div>
                                        <div class="cs-label">Credit limit</div>
                                    </div>
                                </div>

                                <div class="cust-meta">
                                    <div class="meta-row">
                                        <div class="meta-icon"><i class="bi bi-geo-alt-fill"></i></div>
                                        <div class="meta-content">
                                            <label>Location</label>
                                            <span>{{ $get->address ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="meta-row">
                                        <div class="meta-icon"><i class="bi bi-telephone-fill"></i></div>
                                        <div class="meta-content">
                                            <label>Contact</label>
                                            <span>{{ $get->phone ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="meta-row">
                                        <div class="meta-icon"><i class="bi bi-cash-coin"></i></div>
                                        <div class="meta-content">
                                            <label>Credit limit</label>
                                            <span class="credit-val">Tsh {{ number_format($get->limits ?? 0) }}</span>
                                        </div>
                                    </div>
                                    <div class="meta-row">
                                        <div class="meta-icon"><i class="bi bi-calendar3"></i></div>
                                        <div class="meta-content">
                                            <label>Due Date</label>
                                            <span class="credit-val">{{ $get->due ?? 'N/A' }} days</span>
                                        </div>
                                    </div>
                                    <div class="meta-row">
                                        <div class="meta-icon"><i class="bi bi-calendar3"></i></div>
                                        <div class="meta-content">
                                            <label>Bad Debtor</label>
                                            <span class="credit-val">{{ $get->bad_debtor ?? 'N/A' }} days</span>
                                        </div>
                                    </div>
                                    @if($get->description)
                                    <div class="meta-row">
                                        <div class="meta-icon"><i class="bi bi-chat-left-text"></i></div>
                                        <div class="meta-content">
                                            <label>Notes</label>
                                            <span>{{ $get->description }}</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </aside>

                        {{-- ═══ RIGHT — Sales History ═══ --}}
                        <div class="au au3">
                            <div class="panel">
                                <div class="panel-head">
                                    <div class="panel-head-left">
                                        <div class="panel-head-icon"><i class="bi bi-cart-check"></i></div>
                                        <span class="panel-title">Purchase History</span>
                                    </div>
                                    <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                                        <div class="search-wrap">
                                            <i class="bi bi-search"></i>
                                            <input type="text" class="search-input" id="salesSearch" placeholder="Search orders…" oninput="filterRows()">
                                        </div>
                                        <form method="GET" action="" id="dateFilterForm" style="display:flex; gap:6px; align-items:center;">
                                            <input type="date" name="date_from" class="date-input" value="{{ request('date_from') }}" onchange="filterByDate()" placeholder="From">
                                            <input type="date" name="date_to" class="date-input" value="{{ request('date_to') }}" onchange="filterByDate()" placeholder="To">
                                        </form>
                                        <span class="result-pill" id="resultCount">{{ $paginator->total() }}</span>
                                    </div>
                                </div>

                                @if(empty($groupedSales))
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <h4>No sales yet</h4>
                                    <p>No sales records found for this customer</p>
                                </div>
                                @else
                                <div class="tbl-wrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th>Order / Items</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                                <th>Date</th>
                                                <th style="text-align:right;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="salesBody">
                                            @foreach($groupedSales as $index => $order)
                                            @php
                                                $firstItem = $order['items']->first();
                                                $productNames = $order['items']->map(function($item) {
                                                    $p = DB::table('products')->where('account', $item->account)->where('product_id', $item->productId)->first();
                                                    return $p->name01 ?? ('Product #' . $item->productId);
                                                })->join(', ');
                                                $searchStr = strtolower($productNames . ' order ' . $order['salesName']);
                                                $rowNumber = ($paginator->firstItem() ?? 0) + $index;
                                            @endphp
                                            <tr data-search="{{ $searchStr }}">
                                                <td class="idx-cell">{{ $rowNumber }}</td>
                                                <td>
                                                    <div class="prod-name-primary">Order #{{ $order['salesName'] }}</div>
                                                    <div class="prod-name-secondary">{{ $order['item_count'] }} item(s) — {{ $productNames }}</div>
                                                </td>
                                                <td>
                                                    <span class="qty-chip">
                                                        <span class="qty-dot"></span>
                                                        {{ number_format($order['total_qty']) }}
                                                    </span>
                                                </td>
                                                <td><span class="price-val">Tsh {{ number_format($order['total_amount']) }}</span></td>
                                                <td class="date-val">
                                                    {{ \Carbon\Carbon::parse($order['last_date'])->format('d M Y') }}
                                                </td>
                                                <td>
                                                    <a href="{{ url('viewSales') }}?sales_id={{ $order['sales_id'] }}" class="act-view">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if($paginator->hasPages())
                                <div class="pagination-wrap">
                                    <span class="pagination-info">
                                        Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} orders
                                    </span>
                                    <div>{!! $paginator->links() !!}</div>
                                </div>
                                @endif
                                @endif
                            </div>
                        </div>

                    </div>{{-- end page-grid --}}
                </div>
            </main>
        </div>
    </div>

    {{-- ══ EDIT CUSTOMER MODAL ══ --}}
    <div class="modal fade" id="editCustomer" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-top">
                    <div class="modal-top-left">
                        <div class="modal-top-icon"><i class="bi bi-pencil-fill"></i></div>
                        <h5>Edit Customer</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="editCustomer" method="post">
                    @csrf
                    <input type="hidden" name="customerId" value="{{ $get->id }}">
                    <div class="modal-body">

                        <div class="field-row">
                            <div class="field">
                                <label class="field-label">Full name <span class="req">*</span></label>
                                <input type="text" class="field-input" name="name" value="{{ $get->name }}" placeholder="Customer name" required>
                            </div>
                            <div class="field">
                                <label class="field-label">Contact <span class="req">*</span></label>
                                <input type="text" class="field-input" name="contact" value="{{ $get->phone }}" placeholder="Phone number" required>
                            </div>
                        </div>

                        <div class="field-row">
                            <div class="field" style="grid-column:1/-1;">
                                <label class="field-label">Address <span class="req">*</span></label>
                                <input type="text" class="field-input" name="address" value="{{ $get->address }}" placeholder="Customer address" required>
                            </div>
                        </div>
                        <div class="field-row">
                            <div class="field" style="grid-column:1/-1;">
                                <label class="field-label">Business type <span class="req">*</span></label>
                                <select class="field-input" name="type" required>
                                    <option value="{{ $get->business }}">{{ $get->business }}</option>
                                    <option disabled>— Choose type —</option>
                                    <option value="Wholesale">Wholesale</option>
                                    <option value="Manufacturer">Manufacturer</option>
                                    <option value="Distributor">Distributor</option>
                                    <option value="Retailer">Retailer</option>
                                </select>
                            </div>
                        </div>
                        <div class="field-row">
                            
                            <div class="field">
                                <label class="field-label">Credit limit <span class="req">*</span></label>
                                <div class="input-prefix-wrap">
                                    <span class="input-prefix">Tsh</span>
                                    <input type="number" name="credit" value="{{ $get->limits }}" placeholder="0" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="field-label">Due Date <span class="req">*</span></label>
                                <div class="input-prefix-wrap">
                                    <span class="input-prefix">Days</span>
                                    <input type="number" name="due" value="{{ $get->due }}" placeholder="0" required>
                                </div>
                            </div>
                        </div>

                        @if(isset($accounts) && count($accounts) > 0)
                        <div class="field">
                            <label class="field-label">Assign to Shop/Account</label>
                            <select class="field-input" name="account">
                                @foreach($accounts as $account)
                                    <option value="{{ $account['id'] }}" {{ $get->account == $account['id'] ? 'selected' : '' }}>
                                        {{ $account['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="field">
                            <label class="field-label">Assign to Employee</label>
                            <select class="field-input" name="allocation">
                                <option value="" {{ is_null($get->employeeId) ? 'selected' : '' }}>Select Employee</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $get->employeeId == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label class="field-label">Group</label>
                            <select class="field-input" name="groups">
                                <option value="">Select Group</option>
                                @if(isset($groups) && count($groups) > 0)
                                    @foreach($groups as $group)
                                        <option value="{{ $group }}" {{ old('groups', $get->groups ?? '') == $group ? 'selected' : '' }}>
                                            {{ $group }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="field">
                            <label class="field-label">Notes</label>
                            <textarea class="field-input" name="description" placeholder="Additional notes…">{{ trim($get->description ?? '') }}</textarea>
                        </div>

                    </div>
                    <div class="modal-footer-custom">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function filterRows() {
            const q = document.getElementById('salesSearch').value.toLowerCase().trim();
            const rows = document.querySelectorAll('#salesBody tr[data-search]');
            let visible = 0;
            rows.forEach(r => {
                const match = !q || r.dataset.search.includes(q);
                r.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            const resultCount = document.getElementById('resultCount');
            if (resultCount) resultCount.textContent = visible;
        }
        
        function filterByDate() {
            document.getElementById('dateFilterForm').submit();
        }
    </script>
</body>
</html>