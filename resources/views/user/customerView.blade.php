<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} — Customer Details</title>
    @include('links')
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
            --font: 'Sora', system-ui, sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --r: 8px; --r-lg: 13px; --r-xl: 16px;
        }

        body { font-family: var(--font); background: #ECF0F8; color: var(--slate-800); min-height: 100vh; font-size: 14px; line-height: 1.6; }
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
        .pg-right { position:relative; z-index:1; }
        .btn-amber { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:var(--r); background:var(--amber); color:var(--navy); font-family:var(--font); font-size:13px; font-weight:700; border:none; cursor:pointer; box-shadow:0 3px 14px rgba(245,158,11,.35); transition:all .18s; }
        .btn-amber:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(245,158,11,.45); color:var(--navy); }

        /* ══ PAGE GRID ══ */
        .page-grid { display:grid; grid-template-columns: 300px 1fr; gap:1.25rem; align-items:start; }
        @media(max-width:900px) { .page-grid { grid-template-columns:1fr; } }

        /* ══ PROFILE CARD ══ */
        .profile-card {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-xl); overflow: hidden;
            box-shadow: 0 1px 4px rgba(11,30,61,.05);
            position: sticky; top: 1.5rem;
        }
        .profile-banner {
            height: 80px;
            background: var(--navy);
            position: relative;
        }
        .profile-banner::after { content:''; position:absolute; inset:0; background:linear-gradient(135deg, var(--navy-light) 0%, transparent 70%); }

        .profile-avatar-wrap {
            display: flex; flex-direction: column; align-items: center;
            padding: 0 1.25rem 1.25rem; margin-top: -44px; position: relative; z-index: 1;
        }
        .cust-avatar {
            width: 88px; height: 88px; border-radius: 50%;
            border: 4px solid var(--white); background: var(--navy-mid);
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; font-weight: 700; color: rgba(255,255,255,.85);
            box-shadow: 0 4px 16px rgba(11,30,61,.2); flex-shrink: 0;
        }
        .cust-name { font-size: 15px; font-weight: 700; color: var(--navy); margin-top: .875rem; text-align: center; }
        .cust-type-badge {
            display: inline-flex; align-items: center; gap: 5px; margin-top: 5px;
            padding: 3px 12px; border-radius: 20px;
            background: var(--sky-pale); color: var(--sky);
            font-size: 11.5px; font-weight: 700;
        }
        .cust-status-badge {
            display: inline-flex; align-items: center; gap: 5px; margin-top: 5px;
            padding: 3px 12px; border-radius: 20px;
            background: var(--emerald-pale); color: var(--emerald);
            font-size: 11px; font-weight: 700;
        }

        /* Stats strip */
        .cust-stats { display:grid; grid-template-columns:1fr 1fr; gap:1px; background:var(--slate-200); margin-top:1rem; }
        .cs-cell { background:var(--white); padding:.75rem 1rem; text-align:center; }
        .cs-val   { font-size:16px; font-weight:700; color:var(--navy); font-family:var(--mono); }
        .cs-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--slate-400); margin-top:2px; }

        /* Meta list */
        .cust-meta { padding: 1rem 1.25rem; }
        .meta-row { display:flex; align-items:flex-start; gap:9px; font-size:12.5px; color:var(--slate-600); padding:6px 0; border-bottom:1px solid var(--slate-100); }
        .meta-row:last-child { border-bottom:none; }
        .meta-icon { width:28px; height:28px; border-radius:var(--r); background:var(--slate-100); color:var(--slate-400); display:flex; align-items:center; justify-content:center; font-size:12px; flex-shrink:0; }
        .meta-content label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--slate-400); display:block; margin-bottom:1px; }
        .meta-content span  { font-weight:500; color:var(--slate-800); }
        .credit-val { color: var(--emerald); font-family:var(--mono); font-weight:700; font-size:14px; }

        /* ══ SALES PANEL ══ */
        .panel {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-xl); overflow: hidden;
            box-shadow: 0 1px 4px rgba(11,30,61,.05);
        }
        .panel-head {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.4rem; border-bottom: 1.5px solid var(--slate-200);
            background: var(--slate-50); gap: 1rem; flex-wrap: wrap;
        }
        .panel-head-left { display:flex; align-items:center; gap:10px; }
        .panel-head-icon { width:30px; height:30px; border-radius:var(--r); background:rgba(11,30,61,.07); color:var(--navy-light); display:flex; align-items:center; justify-content:center; font-size:13px; }
        .panel-title { font-size:13.5px; font-weight:700; color:var(--navy); }
        .result-pill { font-size:11px; font-weight:600; font-family:var(--mono); background:var(--slate-200); color:var(--slate-600); padding:2px 9px; border-radius:20px; }

        /* Search & Date Filter */
        .filter-wrap { position:relative; display:inline-flex; gap:8px; align-items:center; }
        .search-wrap { position:relative; }
        .search-wrap i { position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--slate-400); font-size:13px; pointer-events:none; }
        .search-input { padding:7px 12px 7px 30px; border:1.5px solid var(--slate-200); border-radius:var(--r); font-family:var(--font); font-size:13px; color:var(--slate-800); background:var(--white); outline:none; width:200px; transition:all .18s; }
        .search-input:focus { border-color:var(--navy-light); box-shadow:0 0 0 3px rgba(26,58,107,.1); width:230px; }
        .date-input { padding:7px 12px; border:1.5px solid var(--slate-200); border-radius:var(--r); font-family:var(--font); font-size:13px; color:var(--slate-800); background:var(--white); outline:none; width:140px; transition:all .18s; }
        .date-input:focus { border-color:var(--navy-light); box-shadow:0 0 0 3px rgba(26,58,107,.1); }
        .date-input::-webkit-calendar-picker-indicator { cursor:pointer; }

        /* Table */
        .tbl-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; font-size:13px; }
        thead th { background:var(--navy); color:rgba(255,255,255,.65); font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; padding:10px 16px; white-space:nowrap; border:none; text-align:left; }
        thead th:last-child { text-align:right; }
        tbody tr { border-bottom:1px solid var(--slate-100); transition:background .12s; }
        tbody tr:last-child { border-bottom:none; }
        tbody tr:hover td { background:#F7F9FF; }
        td { padding:11px 16px; vertical-align:middle; }
        td:last-child { text-align:right; }

        .idx-cell { font-size:11.5px; color:var(--slate-400); font-family:var(--mono); }
        .prod-name-primary   { font-weight:600; color:var(--navy); font-size:13px; }
        .prod-name-secondary { font-size:11.5px; color:var(--slate-400); margin-top:1px; }
        .qty-chip { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; background:var(--slate-100); font-family:var(--mono); font-size:12px; font-weight:600; color:var(--slate-600); }
        .qty-dot  { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .price-val { font-family:var(--mono); font-weight:600; color:var(--navy); font-size:13px; }
        .date-val  { font-family:var(--mono); font-size:11.5px; color:var(--slate-400); }
        .disc-badge { display:inline-block; padding:2px 9px; border-radius:20px; font-size:11px; font-weight:700; background:var(--amber-pale); color:#92400e; }

        /* Action button */
        .act-view { display:inline-flex; align-items:center; gap:4px; padding:5px 12px; border-radius:var(--r); background:rgba(11,30,61,.07); color:var(--navy-light); border:none; font-family:var(--font); font-size:12px; font-weight:600; cursor:pointer; transition:all .15s; }
        .act-view:hover { background:var(--navy); color:var(--white); }

        /* Empty state */
        .empty-state { text-align:center; padding:4rem 1.5rem; color:var(--slate-400); }
        .empty-state i { font-size:2.5rem; display:block; margin-bottom:.75rem; opacity:.3; }
        .empty-state h4 { font-size:15px; font-weight:600; color:var(--slate-600); margin-bottom:5px; }
        .empty-state p  { font-size:13px; }

        /* ══ MODAL ══ */
        .modal-content { border:none; border-radius:var(--r-xl); overflow:hidden; box-shadow:0 20px 60px rgba(11,30,61,.2); }
        .modal-top { background:var(--navy); padding:1.15rem 1.4rem; display:flex; align-items:center; justify-content:space-between; }
        .modal-top-left { display:flex; align-items:center; gap:10px; }
        .modal-top-icon { width:32px; height:32px; border-radius:var(--r); background:var(--amber); display:flex; align-items:center; justify-content:center; color:var(--navy); font-size:14px; }
        .modal-top h5 { font-size:15px; font-weight:700; color:var(--white); margin:0; }
        .modal-top .btn-close { filter:invert(1) brightness(.75); }
        .modal-body { padding:1.5rem 1.4rem; }

        .field-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px; }
        @media(max-width:560px) { .field-row { grid-template-columns:1fr; } }
        .field { display:flex; flex-direction:column; gap:5px; }
        .field-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--slate-500); }
        .field-label .req { color:var(--rose); }
        .field-input { width:100%; font-family:var(--font); font-size:13.5px; padding:9px 12px; border:1.5px solid var(--slate-200); border-radius:var(--r); background:var(--white); color:var(--slate-800); outline:none; transition:border-color .18s, box-shadow .18s; }
        .field-input:focus { border-color:var(--navy-light); box-shadow:0 0 0 3px rgba(26,58,107,.1); }
        .field-input::placeholder { color:var(--slate-400); }
        select.field-input { appearance:none; cursor:pointer; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 12px center; padding-right:2.25rem; }
        textarea.field-input { resize:vertical; min-height:80px; }
        .input-prefix-wrap { display:flex; border:1.5px solid var(--slate-200); border-radius:var(--r); overflow:hidden; transition:border-color .18s, box-shadow .18s; }
        .input-prefix-wrap:focus-within { border-color:var(--navy-light); box-shadow:0 0 0 3px rgba(26,58,107,.1); }
        .input-prefix { background:var(--slate-100); padding:9px 12px; font-size:13px; font-weight:600; color:var(--slate-500); border-right:1.5px solid var(--slate-200); white-space:nowrap; }
        .input-prefix-wrap input { flex:1; border:none; outline:none; padding:9px 12px; font-family:var(--mono); font-size:13.5px; color:var(--slate-800); background:transparent; }

        .modal-footer-custom { padding:1rem 1.4rem; border-top:1.5px solid var(--slate-200); display:flex; justify-content:flex-end; gap:8px; }
        .btn-cancel { padding:9px 18px; border-radius:var(--r); border:1.5px solid var(--slate-200); background:transparent; font-family:var(--font); font-size:13px; color:var(--slate-600); cursor:pointer; transition:all .15s; }
        .btn-cancel:hover { background:var(--slate-100); }
        .btn-save { padding:9px 22px; border-radius:var(--r); background:var(--amber); color:var(--navy); border:none; font-family:var(--font); font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:0 3px 14px rgba(245,158,11,.3); transition:all .18s; }
        .btn-save:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(245,158,11,.4); }

        @media(max-width:768px) { .wrap { padding:1rem; } }
    </style>
</head>
<body>
<div class="row">
    @include("user/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3">
        <div class="wrap">

            @if(!isset($get))
            <div class="alert-box alert-danger" style="margin-bottom:1rem;">
                <span><i class="bi bi-exclamation-circle me-2"></i>Customer not found or may have been deleted.</span>
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
                    <a href="{{ url('user/customer-kpi') }}" class="btn-amber" style="margin-right: 10px;">
                        <i class="bi bi-graph-up"></i> KPI
                    </a>
                    <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#editCustomer">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
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
                            <span class="cust-type-badge">
                                <i class="bi bi-building" style="font-size:11px;"></i>
                                {{ $get->business ?? 'N/A' }}
                            </span>
                            <span class="cust-status-badge">
                                <i class="bi bi-check-circle-fill" style="font-size:10px;"></i>
                                Active
                            </span>
                        </div>

                        <div class="cust-stats">
                            <div class="cs-cell">
                                <div class="cs-val">{{ $sales->count() }}</div>
                                <div class="cs-label">Purchases</div>
                            </div>
                            <div class="cs-cell">
                                <div class="cs-val" style="font-size:13px; color:var(--emerald);">{{ number_format($get->limits ?? 0) }}</div>
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

                {{-- ═══ RIGHT — Sales history ═══ --}}
                <div class="au au3">
                    <div class="panel">
                        <div class="panel-head">
                            <div class="panel-head-left">
                                <div class="panel-head-icon"><i class="bi bi-receipt"></i></div>
                                <span class="panel-title">Sales History</span>
                                <span class="result-pill" id="resultCount">{{ $sales->count() }}</span>
                            </div>
                            @if(!$sales->isEmpty())
                            <div class="filter-wrap">
                                <form method="GET" action="customerView" id="dateFilterForm" style="display:contents;">
                                    <input type="hidden" name="name" value="{{ $req->input('name') }}">
                                    <div class="date-wrap">
                                        <input type="date" class="date-input" id="dateFilter"
                                               name="selectedDate"
                                               value="{{ $selectedDate ?? date('Y-m-d') }}" onchange="filterByDate()">
                                    </div>
                                </form>
                                <div class="search-wrap">
                                    <i class="bi bi-search"></i>
                                    <input type="text" class="search-input" id="salesSearch"
                                           placeholder="Search products…" oninput="filterRows()">
                                </div>
                            </div>
                            @endif
                        </div>

                        @if($sales->isEmpty())
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
                                        <th width="4%">#</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Unit price</th>
                                        <th>Total</th>
                                        <th>Discount</th>
                                        <th>Date</th>
                                        <th style="text-align:right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="salesBody">
                                    @foreach($sales as $index => $product)
                                    @php
                                        $productz = DB::table('products')
                                            ->where('account', getSessionAccountName())
                                            ->where('product_id', $product->productId)
                                            ->first();
                                        $qtyColor = $product->pQuantity <= 0
                                            ? 'var(--rose)'
                                            : ($product->pQuantity < 10 ? 'var(--amber)' : 'var(--emerald)');
                                        $searchStr = strtolower(($productz->name01 ?? '') . ' ' . ($productz->name02 ?? ''));
                                    @endphp
                                    <tr data-search="{{ $searchStr }}">
                                        <td class="idx-cell">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="prod-name-primary">{{ $productz->name01 ?? 'N/A' }}</div>
                                            @if(!empty($productz->name02))
                                            <div class="prod-name-secondary">{{ $productz->name02 }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="qty-chip">
                                                <span class="qty-dot" style="background:{{ $qtyColor }};"></span>
                                                {{ number_format($product->pQuantity) }}
                                                @if($product->unit) {{ $product->unit }} @endif
                                            </span>
                                        </td>
                                        <td><span class="price-val">Tsh {{ number_format($product->productPrice) }}</span></td>
                                        <td><span class="price-val">Tsh {{ number_format($product->totalPrice) }}</span></td>
                                        <td>
                                            @if($product->discount)
                                                <span class="disc-badge">{{ $product->discount }}</span>
                                            @else
                                                <span style="color:var(--slate-300); font-size:12px;">—</span>
                                            @endif
                                        </td>
                                        <td class="date-val">
                                            {{ \Carbon\Carbon::parse($product->created_at)->format('d M Y') }}
                                        </td>
                                        <td>
                                            <form method="post" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="act-view"
                                                        name="salesName" value="{{ $product->sales_id }}"
                                                        formaction="viewSales">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>

            </div>{{-- end page-grid --}}
        </div>
    </main>
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
                        <div class="field">
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
                        <div class="field">
                            <label class="field-label">Credit limit <span class="req">*</span></label>
                            <div class="input-prefix-wrap">
                                <span class="input-prefix">Tsh</span>
                                <input type="number" name="credit" value="{{ $get->limits }}" placeholder="0" required>
                            </div>
                        </div>
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
        document.getElementById('resultCount').textContent = visible;
    }
    
    // Submit date filter form when date changes
    function filterByDate() {
        document.getElementById('dateFilterForm').submit();
    }
</script>
</body>
</html>