<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} — Banking Partners</title>
    @include("links")
    
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* Your CSS styles here (keep as is) */
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
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        .au  { animation: fadeUp 0.38s ease both; }
        .au1 { animation-delay:.04s; } .au2 { animation-delay:.10s; }
        .au3 { animation-delay:.16s; } .au4 { animation-delay:.22s; }

        /* ── Alerts ── */
        .alert { display:flex; align-items:center; justify-content:space-between; padding:.75rem 1rem; border-radius:var(--r); margin-bottom:1rem; font-size:13px; font-weight:500; }
        .alert-success { background:var(--emerald-pale); color:var(--emerald); border-left:3px solid var(--emerald); }
        .alert-danger  { background:var(--rose-pale);    color:var(--rose);    border-left:3px solid var(--rose); }
        .close-btn { background:none; border:none; cursor:pointer; color:inherit; font-size:16px; opacity:.6; }
        .close-btn:hover { opacity:1; }

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
        .back-btn { width:34px; height:34px; border-radius:var(--r); background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.15); display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,.7); text-decoration:none; flex-shrink:0; transition:all .15s; }
        .back-btn:hover { background:rgba(255,255,255,.16); color:var(--white); }
        .header-icon { width:40px; height:40px; border-radius:var(--r); background:var(--amber); display:flex; align-items:center; justify-content:center; font-size:18px; color:var(--navy); flex-shrink:0; }
        .pg-title-text h1 { font-size:16px; font-weight:700; color:var(--white); letter-spacing:-.2px; }
        .pg-title-text p  { font-size:12px; color:rgba(255,255,255,.45); margin-top:1px; }
        .pg-right { display:flex; gap:8px; position:relative; z-index:1; flex-wrap:wrap; }

        .btn-amber { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:var(--r); background:var(--amber); color:var(--navy); font-family:var(--font); font-size:13px; font-weight:700; border:none; cursor:pointer; box-shadow:0 3px 14px rgba(245,158,11,.35); transition:all .18s; text-decoration:none; }
        .btn-amber:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(245,158,11,.45); color:var(--navy); }

        /* ══ METRICS ROW ══ */
        .metrics-row { display:grid; grid-template-columns:repeat(4, minmax(0,1fr)); gap:1rem; margin-bottom:1.4rem; }
        @media(max-width:800px){ .metrics-row { grid-template-columns:repeat(2,1fr); } }

        .metric-card { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-lg); padding:1rem 1.2rem; box-shadow:0 1px 4px rgba(11,30,61,.05); position:relative; overflow:hidden; transition:transform .2s, box-shadow .2s; }
        .metric-card::after { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:var(--mc); }
        .mc-navy    { --mc:var(--navy); }
        .mc-sky     { --mc:var(--sky); }
        .mc-emerald { --mc:var(--emerald); }
        .mc-amber   { --mc:var(--amber); }
        .metric-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(11,30,61,.1); }
        .metric-inner { display:flex; align-items:center; gap:12px; }
        .metric-icon { width:40px; height:40px; border-radius:var(--r); flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:17px; }
        .mi-navy    { background:rgba(11,30,61,.08); color:var(--navy); }
        .mi-sky     { background:var(--sky-pale);    color:var(--sky); }
        .mi-emerald { background:var(--emerald-pale); color:var(--emerald); }
        .mi-amber   { background:var(--amber-pale);   color:#92400e; }
        .metric-label { font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--slate-400); margin-bottom:4px; }
        .metric-value { font-family:var(--mono); font-size:22px; font-weight:500; color:var(--navy); letter-spacing:-.5px; line-height:1; }

        /* ══ TABS ══ */
        .tab-shell { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-xl); overflow:hidden; box-shadow:0 1px 4px rgba(11,30,61,.05); }
        .tab-nav { display:flex; border-bottom:1.5px solid var(--slate-200); background:var(--slate-50); }
        .tab-btn {
            display:flex; align-items:center; gap:7px;
            padding:.9rem 1.6rem; background:transparent; border:none; cursor:pointer;
            font-family:var(--font); font-size:13px; font-weight:500; color:var(--slate-400);
            position:relative; transition:all .18s; white-space:nowrap;
        }
        .tab-btn i { font-size:15px; }
        .tab-count { font-size:10.5px; font-weight:700; padding:1px 7px; border-radius:20px; background:var(--slate-200); color:var(--slate-500); font-family:var(--mono); transition:all .18s; }
        .tab-btn.active { color:var(--navy); font-weight:700; background:var(--white); }
        .tab-btn.active::after { content:''; position:absolute; bottom:-1.5px; left:0; right:0; height:2.5px; background:var(--amber); border-radius:2px 2px 0 0; }
        .tab-btn.active .tab-count { background:var(--navy); color:var(--white); }
        .tab-btn:hover:not(.active) { color:var(--slate-700); background:rgba(11,30,61,.02); }
        .tab-pane { display:none; animation:fadeIn .22s ease; }
        .tab-pane.active { display:block; }

        /* ══ PARTNER TABLE PANEL ══ */
        .panel-head { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.4rem; border-bottom:1.5px solid var(--slate-200); gap:1rem; flex-wrap:wrap; }
        .panel-head-left { display:flex; align-items:center; gap:10px; }
        .panel-head-icon { width:30px; height:30px; border-radius:var(--r); background:rgba(11,30,61,.07); color:var(--navy-light); display:flex; align-items:center; justify-content:center; font-size:13px; }
        .panel-title { font-size:13.5px; font-weight:700; color:var(--navy); }

        /* Search */
        .search-wrap { position:relative; }
        .search-wrap i { position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--slate-400); font-size:13px; pointer-events:none; }
        .search-input { padding:7px 12px 7px 30px; border:1.5px solid var(--slate-200); border-radius:var(--r); font-family:var(--font); font-size:13px; color:var(--slate-800); background:var(--white); outline:none; width:200px; transition:all .18s; }
        .search-input:focus { border-color:var(--navy-light); box-shadow:0 0 0 3px rgba(26,58,107,.1); width:240px; }

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

        /* Partner cell */
        .partner-cell { display:flex; align-items:center; gap:10px; }
        .partner-avatar { width:34px; height:34px; border-radius:50%; background:var(--navy-mid); color:rgba(255,255,255,.85); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0; }
        .partner-name   { font-weight:600; color:var(--navy); font-size:13px; }
        .partner-addr   { font-size:11.5px; color:var(--slate-400); margin-top:1px; }

        /* Account cell */
        .acct-bank { font-weight:600; color:var(--slate-800); font-size:13px; }
        .acct-num  { font-family:var(--mono); font-size:11.5px; color:var(--slate-400); margin-top:1px; }
        .no-acct   { font-size:12.5px; color:var(--slate-300); font-style:italic; }

        /* Badges */
        .badge-primary  { display:inline-flex; align-items:center; gap:4px; padding:2px 9px; border-radius:20px; font-size:10.5px; font-weight:700; background:var(--navy-mid); color:var(--white); }
        .badge-add      { display:inline-flex; align-items:center; gap:4px; padding:2px 9px; border-radius:20px; font-size:10.5px; font-weight:700; background:var(--slate-100); color:var(--slate-500); }

        /* Action buttons */
        .action-btns { display:flex; gap:5px; justify-content:flex-end; flex-wrap:nowrap; }
        .act-btn { width:30px; height:30px; border-radius:var(--r); border:1.5px solid; background:transparent; display:flex; align-items:center; justify-content:center; font-size:12.5px; cursor:pointer; transition:all .15s; flex-shrink:0; }
        .ab-edit  { border-color:var(--amber);   color:var(--amber); }
        .ab-edit:hover  { background:var(--amber-pale); }
        .ab-add   { border-color:var(--sky);     color:var(--sky); }
        .ab-add:hover   { background:var(--sky-pale); }
        .ab-del   { border-color:var(--rose);    color:var(--rose); }
        .ab-del:hover   { background:var(--rose-pale); }
        .ab-accts { border-color:var(--navy-light); color:var(--navy-light); }
        .ab-accts:hover { background:rgba(26,58,107,.06); }

        /* ══ ACCOUNTS EXPAND PANEL ══ */
        .expand-row { display:none; }
        .expand-row.open { display:table-row; }
        .expand-cell { padding:0 !important; }
        .expand-inner { padding:1rem 1.4rem; background:var(--slate-50); border-top:1.5px solid var(--slate-200); }
        .expand-title { font-size:12px; font-weight:700; color:var(--navy); margin-bottom:.75rem; display:flex; align-items:center; gap:6px; }

        .sub-tbl { width:100%; border-collapse:collapse; font-size:12.5px; }
        .sub-tbl thead th { background:var(--navy-mid); color:rgba(255,255,255,.7); font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; padding:7px 12px; border:none; text-align:left; }
        .sub-tbl tbody td { padding:9px 12px; border-bottom:1px solid var(--slate-200); vertical-align:middle; }
        .sub-tbl tbody tr:last-child td { border-bottom:none; }
        .sub-tbl tbody tr:hover td { background:var(--slate-100); }
        .sub-tbl-wrap { overflow-x:auto; border-radius:var(--r-lg); border:1.5px solid var(--slate-200); overflow:hidden; }
        .sub-tbl td code { font-family:var(--mono); font-size:11.5px; background:rgba(11,30,61,.06); padding:2px 7px; border-radius:4px; }
        .sub-tbl td:last-child { text-align:right; }
        .sub-tbl thead th:last-child { text-align:right; }

        /* Empty state */
        .empty-state { text-align:center; padding:3.5rem 1.5rem; color:var(--slate-400); }
        .empty-state i { font-size:2.5rem; display:block; margin-bottom:.75rem; opacity:.3; }
        .empty-state h4 { font-size:15px; font-weight:600; color:var(--slate-600); margin-bottom:5px; }
        .empty-state p  { font-size:13px; margin-bottom:1.25rem; }

        /* ══ MODAL ══ */
        .modal-content { border:none; border-radius:var(--r-xl); overflow:hidden; box-shadow:0 20px 60px rgba(11,30,61,.2); }
        .modal-top { background:var(--navy); padding:1.15rem 1.4rem; display:flex; align-items:center; justify-content:space-between; }
        .modal-top-left { display:flex; align-items:center; gap:10px; }
        .modal-top-icon { width:32px; height:32px; border-radius:var(--r); background:var(--amber); display:flex; align-items:center; justify-content:center; color:var(--navy); font-size:14px; }
        .modal-top h5 { font-size:15px; font-weight:700; color:var(--white); margin:0; }
        .modal-top .btn-close { filter:invert(1) brightness(.75); }
        .modal-body { padding:1.4rem; }

        /* Modal inner tabs */
        .modal-tab-nav { display:flex; background:var(--slate-50); border:1.5px solid var(--slate-200); border-radius:var(--r); overflow:hidden; margin-bottom:1.25rem; }
        .modal-tab-btn { flex:1; padding:8px; background:transparent; border:none; font-family:var(--font); font-size:12.5px; font-weight:500; color:var(--slate-400); cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px; transition:all .15s; }
        .modal-tab-btn.active { background:var(--navy); color:var(--white); }
        .modal-tab-btn:hover:not(.active) { color:var(--slate-700); }
        .modal-tab-pane { display:none; }
        .modal-tab-pane.active { display:block; animation:fadeIn .2s ease; }

        /* Fields */
        .field-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        @media(max-width:560px){ .field-row { grid-template-columns:1fr; } }
        .field { margin-bottom:12px; }
        .field:last-child { margin-bottom:0; }
        .field-label { display:block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--slate-500); margin-bottom:5px; }
        .field-label .req { color:var(--rose); }
        .field-input { width:100%; font-family:var(--font); font-size:13.5px; padding:9px 12px; border:1.5px solid var(--slate-200); border-radius:var(--r); background:var(--white); color:var(--slate-800); outline:none; transition:border-color .18s, box-shadow .18s; }
        .field-input:focus { border-color:var(--navy-light); box-shadow:0 0 0 3px rgba(26,58,107,.1); }
        .field-input::placeholder { color:var(--slate-400); }
        textarea.field-input { resize:vertical; min-height:72px; }

        /* Primary checkbox */
        .check-row { display:flex; align-items:flex-start; gap:9px; padding:.75rem; background:var(--slate-50); border:1.5px solid var(--slate-200); border-radius:var(--r); }
        .check-box { width:16px; height:16px; border-radius:4px; border:1.5px solid var(--slate-300); appearance:none; -webkit-appearance:none; cursor:pointer; flex-shrink:0; margin-top:2px; transition:all .15s; }
        .check-box:checked { background:var(--amber); border-color:var(--amber); background-image:url("data:image/svg+xml,%3Csvg width='10' height='8' viewBox='0 0 10 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 3.5L4 6.5L9 1' stroke='%230B1E3D' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:center; }
        .check-label { font-size:13px; font-weight:500; color:var(--slate-700); cursor:pointer; }
        .check-label small { display:block; font-size:11.5px; color:var(--slate-400); font-weight:400; margin-top:1px; }

        .modal-divider { height:1px; background:var(--slate-200); border:none; margin:1rem 0; }
        .modal-section-label { font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--slate-400); margin-bottom:10px; }

        .modal-footer-custom { padding:1rem 1.4rem; border-top:1.5px solid var(--slate-200); display:flex; justify-content:flex-end; gap:8px; }
        .btn-cancel { padding:9px 18px; border-radius:var(--r); border:1.5px solid var(--slate-200); background:transparent; font-family:var(--font); font-size:13px; color:var(--slate-600); cursor:pointer; transition:all .15s; }
        .btn-cancel:hover { background:var(--slate-100); }
        .btn-save { padding:9px 22px; border-radius:var(--r); background:var(--amber); color:var(--navy); border:none; font-family:var(--font); font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:0 3px 14px rgba(245,158,11,.3); transition:all .18s; }
        .btn-save:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(245,158,11,.4); }

        @media(max-width:768px){ .wrap{padding:1rem;} .tab-btn{padding:.75rem 1rem;} }
    </style>
</head>
<body>
        @include("sidenav")

        <main class="main-content">
            <div class="wrap">

                {{-- Alerts --}}
                @if(session('success'))
                <div class="alert alert-success au au1">
                    <span><i class="bi bi-check-circle-fill" style="margin-right:6px;"></i>{{ session('success') }}</span>
                    <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger au au1">
                    <span><i class="bi bi-exclamation-circle-fill" style="margin-right:6px;"></i>{{ session('error') }}</span>
                    <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
                </div>
                @endif

                {{-- Page header --}}
                <div class="pg-header au au1">
                    <div class="pg-left">
                        <a href="#" onclick="history.back()" class="back-btn d-print-none">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                        <div class="header-icon"><i class="bi bi-bank2"></i></div>
                        <div class="pg-title-text">
                            <h1>Banking Partners</h1>
                            <p>Manage suppliers and beneficiary bank accounts</p>
                        </div>
                    </div>
                    <div class="pg-right">
                        @if(canUser("add_banking_supplier") || canUser("add_banking_beneficiary"))
                        <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
                            <i class="bi bi-plus-lg"></i> Add Partner
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Metrics --}}
                <div class="metrics-row au au2">
                    <div class="metric-card mc-navy">
                        <div class="metric-inner">
                            <div class="metric-icon mi-navy"><i class="bi bi-building"></i></div>
                            <div><div class="metric-label">{{ __('messages.suppliers') }}</div><div class="metric-value">{{ $suppliers->count() }}</div></div>
                        </div>
                    </div>
                    <div class="metric-card mc-sky">
                        <div class="metric-inner">
                            <div class="metric-icon mi-sky"><i class="bi bi-people"></i></div>
                            <div><div class="metric-label">Beneficiaries</div><div class="metric-value">{{ $beneficiaries->count() }}</div></div>
                        </div>
                    </div>
                    <div class="metric-card mc-emerald">
                        <div class="metric-inner">
                            <div class="metric-icon mi-emerald"><i class="bi bi-credit-card"></i></div>
                            <div><div class="metric-label">Total accounts</div><div class="metric-value">{{ $suppliers->sum(fn($s) => $s->accounts->count()) + $beneficiaries->sum(fn($b) => $b->accounts->count()) }}</div></div>
                        </div>
                    </div>
                    <div class="metric-card mc-amber">
                        <div class="metric-inner">
                            <div class="metric-icon mi-amber"><i class="bi bi-star"></i></div>
                            <div><div class="metric-label">Primary accounts</div><div class="metric-value">{{ $suppliers->sum(fn($s) => $s->accounts->where('is_primary',true)->count()) + $beneficiaries->sum(fn($b) => $b->accounts->where('is_primary',true)->count()) }}</div></div>
                        </div>
                    </div>
                </div>

                {{-- Tab shell --}}
                <div class="tab-shell au au3">
                    <div class="tab-nav">
                        <button class="tab-btn active" onclick="switchTab('suppliers', this)">
                            <i class="bi bi-building"></i> Suppliers
                            <span class="tab-count">{{ $suppliers->count() }}</span>
                        </button>
                        <button class="tab-btn" onclick="switchTab('beneficiaries', this)">
                            <i class="bi bi-people"></i> Beneficiaries
                            <span class="tab-count">{{ $beneficiaries->count() }}</span>
                        </button>
                    </div>

                    {{-- ═══ SUPPLIERS TAB ═══ --}}
                    <div id="tab-suppliers" class="tab-pane active">
                        <div class="panel-head">
                            <div class="panel-head-left">
                                <div class="panel-head-icon"><i class="bi bi-table"></i></div>
                                <span class="panel-title">Supplier Directory</span>
                            </div>
                            <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                                <div class="search-wrap">
                                    <i class="bi bi-search"></i>
                                    <input type="text" class="search-input" placeholder="Search suppliers…"
                                           oninput="filterTable(this, 'suppliersBody')">
                                </div>
                                <form method="GET" action="" style="display:flex; gap:0; align-items:center;">
                                    <select name="shop_id" class="search-input" style="width:auto; padding-left:10px; cursor:pointer;" onchange="this.form.submit()">
                                        <option value="">All shops</option>
                                        @foreach($shops as $shop)
                                            <option value="{{ $shop['id'] }}" {{ (isset($shopId) && $shopId == $shop['id']) ? 'selected' : '' }}>{{ $shop['name'] }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="tbl-wrap">
                            @if($suppliers->isEmpty())
                            <div class="empty-state">
                                <i class="bi bi-building"></i>
                                <h4>No banking suppliers yet</h4>
                                <p>Add your first banking supplier to get started</p>
                                @if(canUser("add_banking_supplier"))
                                <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
                                    <i class="bi bi-plus-lg"></i> Add Supplier
                                </button>
                                @endif
                            </div>
                            @else
                            <table>
                                <thead>
                                    <tr>
                                        <th width="4%">#</th>
                                        <th>Partner</th>
                                        <th>Primary account</th>
                                        <th>Accounts</th>
                                        <th style="text-align:right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="suppliersBody">
                                    @foreach($suppliers as $i => $supplier)
                                    @php
                                        $initials = strtoupper(substr($supplier->name,0,1));
                                        $parts    = explode(' ', trim($supplier->name));
                                        if(count($parts)>1) $initials .= strtoupper(substr($parts[1],0,1));
                                        $primary  = $supplier->accounts->where('is_primary',true)->first();
                                    @endphp
                                    <tr data-search="{{ strtolower($supplier->name) }}">
                                        <td style="font-size:11.5px; color:var(--slate-400); font-family:var(--mono);">{{ $i+1 }}</td>
                                        <td>
                                            <div class="partner-cell">
                                                <div class="partner-avatar">{{ $initials }}</div>
                                                <div>
                                                    <div class="partner-name">{{ $supplier->name }}</div>
                                                    @if($supplier->address)
                                                    <div class="partner-addr">{{ $supplier->address }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($primary)
                                            <div class="acct-bank">{{ $primary->bank_name }}</div>
                                            <div class="acct-num">{{ $primary->account_number }}</div>
                                            @else
                                            <span class="no-acct">No primary account</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($supplier->accounts->count() > 0)
                                            <button class="ab-accts act-btn" onclick="toggleExpand('sup-{{ $supplier->id }}')" title="View accounts" style="width:auto; padding:0 10px; border-radius:20px; font-size:11.5px; font-weight:600; gap:5px; display:inline-flex;">
                                                <i class="bi bi-credit-card"></i> {{ $supplier->accounts->count() }}
                                            </button>
                                            @else
                                            <span style="font-size:12px; color:var(--slate-300);">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-btns">
                                                @if(canUser("edit_banking_supplier"))
                                                <button class="act-btn ab-edit" data-bs-toggle="modal" data-bs-target="#editSupplier{{ $supplier->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                                @endif
                                                @if(canUser("add_banking_supplier"))
                                                <button class="act-btn ab-add" data-bs-toggle="modal" data-bs-target="#addAccount{{ $supplier->id }}-supplier" title="Add account"><i class="bi bi-plus-circle"></i></button>
                                                @endif
                                                @if(canUser("delete_banking_supplier"))
                                                <form action="/banking-supplier/delete/{{ $supplier->id }}" method="POST" style="display:contents;" onsubmit="return confirm('Delete this supplier?');">
                                                    @csrf
                                                    <button type="submit" class="act-btn ab-del" title="Delete"><i class="bi bi-trash"></i></button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Accounts expand row --}}
                                    @if($supplier->accounts->count() > 0)
                                    <tr class="expand-row" id="sup-{{ $supplier->id }}">
                                        <td colspan="5" class="expand-cell">
                                            <div class="expand-inner">
                                                <div class="expand-title"><i class="bi bi-credit-card" style="color:var(--amber);"></i> Bank accounts — {{ $supplier->name }}</div>
                                                <div class="sub-tbl-wrap">
                                                    <table class="sub-tbl">
                                                        <thead>
                                                            <tr>
                                                                <th>Status</th>
                                                                <th>Bank</th>
                                                                <th>Account #</th>
                                                                <th>Branch</th>
                                                                <th>SWIFT</th>
                                                                <th>Contact</th>
                                                                <th style="text-align:right;">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($supplier->accounts as $account)
                                                            <tr>
                                                                <td>
                                                                    @if($account->is_primary)
                                                                        <span class="badge-primary"><i class="bi bi-star-fill"></i> Primary</span>
                                                                    @else
                                                                        <span class="badge-add">Additional</span>
                                                                    @endif
                                                                </td>
                                                                <td style="font-weight:600;">{{ $account->bank_name }}</td>
                                                                <td><code>{{ $account->account_number }}</code></td>
                                                                <td style="color:var(--slate-500);">{{ $account->branch ?? '—' }}</td>
                                                                <td style="color:var(--slate-500); font-family:var(--mono); font-size:11.5px;">{{ $account->swift_code ?? '—' }}</td>
                                                                <td style="color:var(--slate-500);">{{ $account->contact ?? '—' }}</td>
                                                                <td style="text-align:right;">
                                                                    <div class="action-btns">
                                                                        @if(canUser("edit_banking_supplier"))
                                                                        <button class="act-btn ab-edit" data-bs-toggle="modal" data-bs-target="#editAccount{{ $account->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                                                        @endif
                                                                        @if(!$account->is_primary && canUser("delete_banking_supplier"))
                                                                        <form action="/banking-supplier/account/delete/{{ $account->id }}" method="POST" style="display:contents;" onsubmit="return confirm('Delete this account?');">
                                                                            @csrf
                                                                            <button type="submit" class="act-btn ab-del" title="Delete"><i class="bi bi-trash"></i></button>
                                                                        </form>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>
                    </div>

                    {{-- ═══ BENEFICIARIES TAB ═══ --}}
                    <div id="tab-beneficiaries" class="tab-pane">
                        <div class="panel-head">
                            <div class="panel-head-left">
                                <div class="panel-head-icon"><i class="bi bi-table"></i></div>
                                <span class="panel-title">Beneficiary Directory</span>
                            </div>
                            <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                                <div class="search-wrap">
                                    <i class="bi bi-search"></i>
                                    <input type="text" class="search-input" placeholder="Search beneficiaries…"
                                           oninput="filterTable(this, 'beneficiariesBody')">
                                </div>
                                <form method="GET" action="" style="display:flex; gap:0; align-items:center;">
                                    <select name="shop_id" class="search-input" style="width:auto; padding-left:10px; cursor:pointer;" onchange="this.form.submit()">
                                        <option value="">All shops</option>
                                        @foreach($shops as $shop)
                                            <option value="{{ $shop['id'] }}" {{ (isset($shopId) && $shopId == $shop['id']) ? 'selected' : '' }}>{{ $shop['name'] }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="tbl-wrap">
                            @if($beneficiaries->isEmpty())
                            <div class="empty-state">
                                <i class="bi bi-people"></i>
                                <h4>No banking beneficiaries yet</h4>
                                <p>Add your first banking beneficiary to get started</p>
                                @if(canUser("add_banking_beneficiary"))
                                <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
                                    <i class="bi bi-plus-lg"></i> Add Beneficiary
                                </button>
                                @endif
                            </div>
                            @else
                            <table>
                                <thead>
                                    <tr>
                                        <th width="4%">#</th>
                                        <th>Partner</th>
                                        <th>Primary account</th>
                                        <th>Accounts</th>
                                        <th style="text-align:right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="beneficiariesBody">
                                    @foreach($beneficiaries as $i => $beneficiary)
                                    @php
                                        $initials = strtoupper(substr($beneficiary->name,0,1));
                                        $parts    = explode(' ', trim($beneficiary->name));
                                        if(count($parts)>1) $initials .= strtoupper(substr($parts[1],0,1));
                                        $primary  = $beneficiary->accounts->where('is_primary',true)->first();
                                    @endphp
                                    <tr data-search="{{ strtolower($beneficiary->name) }}">
                                        <td style="font-size:11.5px; color:var(--slate-400); font-family:var(--mono);">{{ $i+1 }}</td>
                                        <td>
                                            <div class="partner-cell">
                                                <div class="partner-avatar" style="background:var(--sky);">{{ $initials }}</div>
                                                <div>
                                                    <div class="partner-name">{{ $beneficiary->name }}</div>
                                                    @if($beneficiary->address)
                                                    <div class="partner-addr">{{ $beneficiary->address }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($primary)
                                            <div class="acct-bank">{{ $primary->bank_name }}</div>
                                            <div class="acct-num">{{ $primary->account_number }}</div>
                                            @else
                                            <span class="no-acct">No primary account</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($beneficiary->accounts->count() > 0)
                                            <button class="ab-accts act-btn" onclick="toggleExpand('ben-{{ $beneficiary->id }}')" title="View accounts" style="width:auto; padding:0 10px; border-radius:20px; font-size:11.5px; font-weight:600; gap:5px; display:inline-flex;">
                                                <i class="bi bi-credit-card"></i> {{ $beneficiary->accounts->count() }}
                                            </button>
                                            @else
                                            <span style="font-size:12px; color:var(--slate-300);">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-btns">
                                                @if(canUser("edit_banking_beneficiary"))
                                                <button class="act-btn ab-edit" data-bs-toggle="modal" data-bs-target="#editBeneficiary{{ $beneficiary->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                                @endif
                                                @if(canUser("add_banking_beneficiary"))
                                                <button class="act-btn ab-add" data-bs-toggle="modal" data-bs-target="#addAccount{{ $beneficiary->id }}-beneficiary" title="Add account"><i class="bi bi-plus-circle"></i></button>
                                                @endif
                                                @if(canUser("delete_banking_beneficiary"))
                                                <form action="/banking-beneficiary/delete/{{ $beneficiary->id }}" method="POST" style="display:contents;" onsubmit="return confirm('Delete this beneficiary?');">
                                                    @csrf
                                                    <button type="submit" class="act-btn ab-del" title="Delete"><i class="bi bi-trash"></i></button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Accounts expand row --}}
                                    @if($beneficiary->accounts->count() > 0)
                                    <tr class="expand-row" id="ben-{{ $beneficiary->id }}">
                                        <td colspan="5" class="expand-cell">
                                            <div class="expand-inner">
                                                <div class="expand-title"><i class="bi bi-credit-card" style="color:var(--sky);"></i> Bank accounts — {{ $beneficiary->name }}</div>
                                                <div class="sub-tbl-wrap">
                                                    <table class="sub-tbl">
                                                        <thead>
                                                            <tr>
                                                                <th>Status</th>
                                                                <th>Bank</th>
                                                                <th>Account #</th>
                                                                <th>Branch</th>
                                                                <th>SWIFT</th>
                                                                <th>Contact</th>
                                                                <th style="text-align:right;">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($beneficiary->accounts as $account)
                                                            <tr>
                                                                <td>
                                                                    @if($account->is_primary)
                                                                        <span class="badge-primary"><i class="bi bi-star-fill"></i> Primary</span>
                                                                    @else
                                                                        <span class="badge-add">Additional</span>
                                                                    @endif
                                                                </td>
                                                                <td style="font-weight:600;">{{ $account->bank_name }}</td>
                                                                <td><code>{{ $account->account_number }}</code></td>
                                                                <td style="color:var(--slate-500);">{{ $account->branch ?? '—' }}</td>
                                                                <td style="color:var(--slate-500); font-family:var(--mono); font-size:11.5px;">{{ $account->swift_code ?? '—' }}</td>
                                                                <td style="color:var(--slate-500);">{{ $account->contact ?? '—' }}</td>
                                                                <td style="text-align:right;">
                                                                    <div class="action-btns">
                                                                        @if(canUser("edit_banking_beneficiary"))
                                                                        <button class="act-btn ab-edit" data-bs-toggle="modal" data-bs-target="#editAccount{{ $account->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                                                        @endif
                                                                        @if(!$account->is_primary && canUser("delete_banking_beneficiary"))
                                                                        <form action="/banking-beneficiary/account/delete/{{ $account->id }}" method="POST" style="display:contents;" onsubmit="return confirm('Delete this account?');">
                                                                            @csrf
                                                                            <button type="submit" class="act-btn ab-del" title="Delete"><i class="bi bi-trash"></i></button>
                                                                        </form>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </main>

{{-- ADD PARTNER MODAL --}}
@if(canUser("add_banking_supplier") || canUser("add_banking_beneficiary"))
<div class="modal fade" id="addPartnerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-top">
                <div class="modal-top-left">
                    <div class="modal-top-icon"><i class="bi bi-plus-lg"></i></div>
                    <h5>Add Banking Partner</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="modal-tab-nav">
                    <button class="modal-tab-btn active" onclick="switchModalTab('add-supplier', this)">
                        <i class="bi bi-building"></i> Supplier
                    </button>
                    <button class="modal-tab-btn" onclick="switchModalTab('add-beneficiary', this)">
                        <i class="bi bi-people"></i> Beneficiary
                    </button>
                </div>

                {{-- Add Supplier --}}
                <div id="add-supplier" class="modal-tab-pane active">
                    <form action="/banking-supplier/store" method="post">
                        @csrf
                        <div class="modal-section-label">Basic information</div>
                        <div class="field-row" style="margin-bottom:12px;">
                            <div class="field"><label class="field-label">Name <span class="req">*</span></label><input type="text" class="field-input" name="name" placeholder="Supplier name" required></div>
                            <div class="field"><label class="field-label">Contact</label><input type="text" class="field-input" name="contact" placeholder="Phone or email"></div>
                        </div>
                        <div class="field" style="margin-bottom:12px;"><label class="field-label">Address</label><input type="text" class="field-input" name="address" placeholder="Physical address"></div>
                        <hr class="modal-divider">
                        <div class="modal-section-label">Primary bank account</div>
                        <div class="field-row" style="margin-bottom:12px;">
                            <div class="field"><label class="field-label">Bank name <span class="req">*</span></label><input type="text" class="field-input" name="bank_name" placeholder="e.g. CRDB Bank" required></div>
                            <div class="field"><label class="field-label">Account number <span class="req">*</span></label><input type="text" class="field-input" name="account_number" placeholder="Account number" required></div>
                        </div>
                        <div class="field-row" style="margin-bottom:12px;">
                            <div class="field"><label class="field-label">Branch</label><input type="text" class="field-input" name="branch" placeholder="Branch (optional)"></div>
                            <div class="field"><label class="field-label">SWIFT code</label><input type="text" class="field-input" name="swift_code" placeholder="SWIFT code (optional)"></div>
                        </div>
                        <div class="field" style="margin-bottom:12px;"><label class="field-label">Description</label><textarea class="field-input" name="description" placeholder="Additional notes (optional)"></textarea></div>
                        <div class="modal-footer-custom" style="padding:0; border:none; margin-top:1rem;">
                            <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Save supplier</button>
                        </div>
                    </form>
                </div>

                {{-- Add Beneficiary --}}
                <div id="add-beneficiary" class="modal-tab-pane">
                    <form action="/banking-beneficiary/store" method="post">
                        @csrf
                        <div class="modal-section-label">Basic information</div>
                        <div class="field-row" style="margin-bottom:12px;">
                            <div class="field"><label class="field-label">Name <span class="req">*</span></label><input type="text" class="field-input" name="name" placeholder="Beneficiary name" required></div>
                            <div class="field"><label class="field-label">Contact</label><input type="text" class="field-input" name="contact" placeholder="Phone or email"></div>
                        </div>
                        <div class="field" style="margin-bottom:12px;"><label class="field-label">Address</label><input type="text" class="field-input" name="address" placeholder="Physical address"></div>
                        <hr class="modal-divider">
                        <div class="modal-section-label">Primary bank account</div>
                        <div class="field-row" style="margin-bottom:12px;">
                            <div class="field"><label class="field-label">Bank name <span class="req">*</span></label><input type="text" class="field-input" name="bank_name" placeholder="e.g. CRDB Bank" required></div>
                            <div class="field"><label class="field-label">Account number <span class="req">*</span></label><input type="text" class="field-input" name="account_number" placeholder="Account number" required></div>
                        </div>
                        <div class="field-row" style="margin-bottom:12px;">
                            <div class="field"><label class="field-label">Branch</label><input type="text" class="field-input" name="branch" placeholder="Branch (optional)"></div>
                            <div class="field"><label class="field-label">SWIFT code</label><input type="text" class="field-input" name="swift_code" placeholder="SWIFT code (optional)"></div>
                        </div>
                        <div class="field" style="margin-bottom:12px;"><label class="field-label">Description</label><textarea class="field-input" name="description" placeholder="Additional notes (optional)"></textarea></div>
                        <div class="modal-footer-custom" style="padding:0; border:none; margin-top:1rem;">
                            <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Save beneficiary</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- EDIT SUPPLIER MODALS --}}
@if(canUser("edit_banking_supplier"))
@foreach($suppliers as $supplier)
<div class="modal fade" id="editSupplier{{ $supplier->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-top">
                <div class="modal-top-left">
                    <div class="modal-top-icon"><i class="bi bi-pencil-fill"></i></div>
                    <h5>Edit Supplier</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/banking-supplier/update/{{ $supplier->id }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="field-row" style="margin-bottom:12px;">
                        <div class="field"><label class="field-label">Name <span class="req">*</span></label><input type="text" class="field-input" name="name" value="{{ $supplier->name }}" required></div>
                        <div class="field"><label class="field-label">Contact</label><input type="text" class="field-input" name="contact" value="{{ $supplier->contact }}"></div>
                    </div>
                    <div class="field" style="margin-bottom:12px;"><label class="field-label">Address</label><input type="text" class="field-input" name="address" value="{{ $supplier->address }}"></div>
                    <div class="field"><label class="field-label">Description</label><textarea class="field-input" name="description">{{ $supplier->description }}</textarea></div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endif

{{-- EDIT BENEFICIARY MODALS --}}
@if(canUser("edit_banking_beneficiary"))
@foreach($beneficiaries as $beneficiary)
<div class="modal fade" id="editBeneficiary{{ $beneficiary->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-top">
                <div class="modal-top-left">
                    <div class="modal-top-icon"><i class="bi bi-pencil-fill"></i></div>
                    <h5>Edit Beneficiary</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/banking-beneficiary/update/{{ $beneficiary->id }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="field-row" style="margin-bottom:12px;">
                        <div class="field"><label class="field-label">Name <span class="req">*</span></label><input type="text" class="field-input" name="name" value="{{ $beneficiary->name }}" required></div>
                        <div class="field"><label class="field-label">Contact</label><input type="text" class="field-input" name="contact" value="{{ $beneficiary->contact }}"></div>
                    </div>
                    <div class="field" style="margin-bottom:12px;"><label class="field-label">Address</label><input type="text" class="field-input" name="address" value="{{ $beneficiary->address }}"></div>
                    <div class="field"><label class="field-label">Description</label><textarea class="field-input" name="description">{{ $beneficiary->description }}</textarea></div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endif

{{-- ADD ACCOUNT MODALS --}}
@if(canUser("add_banking_supplier"))
@foreach($suppliers as $supplier)
<div class="modal fade" id="addAccount{{ $supplier->id }}-supplier" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-top">
                <div class="modal-top-left">
                    <div class="modal-top-icon"><i class="bi bi-plus-lg"></i></div>
                    <h5>Add Bank Account — {{ $supplier->name }}</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/banking-supplier/account/store/{{ $supplier->id }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="field-row" style="margin-bottom:12px;">
                        <div class="field"><label class="field-label">Bank name <span class="req">*</span></label><input type="text" class="field-input" name="bank_name" placeholder="e.g. CRDB Bank" required></div>
                        <div class="field"><label class="field-label">Account number <span class="req">*</span></label><input type="text" class="field-input" name="account_number" placeholder="Account number" required></div>
                    </div>
                    <div class="field-row" style="margin-bottom:12px;">
                        <div class="field"><label class="field-label">Branch</label><input type="text" class="field-input" name="branch" placeholder="Branch (optional)"></div>
                        <div class="field"><label class="field-label">SWIFT code</label><input type="text" class="field-input" name="swift_code" placeholder="SWIFT code (optional)"></div>
                    </div>
                    <div class="field"><label class="field-label">Contact</label><input type="text" class="field-input" name="contact" placeholder="Contact person"></div>
                    <div class="field"><label class="field-label">Address</label><input type="text" class="field-input" name="address" placeholder="Branch address (optional)"></div>
                    <div class="field"><label class="field-label">Description</label><textarea class="field-input" name="description" placeholder="Additional notes (optional)"></textarea></div>
                    <label class="check-row">
                        <input type="checkbox" class="check-box" name="is_primary" value="1">
                        <span class="check-label">Set as primary account<small>This account will be used as the default for transfers</small></span>
                    </label>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Add account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endif

@if(canUser("add_banking_beneficiary"))
@foreach($beneficiaries as $beneficiary)
<div class="modal fade" id="addAccount{{ $beneficiary->id }}-beneficiary" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-top">
                <div class="modal-top-left">
                    <div class="modal-top-icon"><i class="bi bi-plus-lg"></i></div>
                    <h5>Add Bank Account — {{ $beneficiary->name }}</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/banking-beneficiary/account/store/{{ $beneficiary->id }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="field-row" style="margin-bottom:12px;">
                        <div class="field"><label class="field-label">Bank name <span class="req">*</span></label><input type="text" class="field-input" name="bank_name" placeholder="e.g. CRDB Bank" required></div>
                        <div class="field"><label class="field-label">Account number <span class="req">*</span></label><input type="text" class="field-input" name="account_number" placeholder="Account number" required></div>
                    </div>
                    <div class="field-row" style="margin-bottom:12px;">
                        <div class="field"><label class="field-label">Branch</label><input type="text" class="field-input" name="branch" placeholder="Branch (optional)"></div>
                        <div class="field"><label class="field-label">SWIFT code</label><input type="text" class="field-input" name="swift_code" placeholder="SWIFT code (optional)"></div>
                    </div>
                    <div class="field"><label class="field-label">Contact</label><input type="text" class="field-input" name="contact" placeholder="Contact person"></div>
                    <div class="field"><label class="field-label">Address</label><input type="text" class="field-input" name="address" placeholder="Branch address (optional)"></div>
                    <div class="field"><label class="field-label">Description</label><textarea class="field-input" name="description" placeholder="Additional notes (optional)"></textarea></div>
                    <label class="check-row">
                        <input type="checkbox" class="check-box" name="is_primary" value="1">
                        <span class="check-label">Set as primary account<small>This account will be used as the default for transfers</small></span>
                    </label>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Add account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endif

{{-- EDIT ACCOUNT MODALS --}}
@if(canUser("edit_banking_supplier") || canUser("edit_banking_beneficiary"))
@foreach($suppliers as $supplier)
    @foreach($supplier->accounts as $account)
    <div class="modal fade" id="editAccount{{ $account->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-top">
                    <div class="modal-top-left">
                        <div class="modal-top-icon"><i class="bi bi-credit-card-fill"></i></div>
                        <h5>Edit Account — {{ $supplier->name }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/banking-supplier/account/update/{{ $account->id }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="field-row" style="margin-bottom:12px;">
                            <div class="field"><label class="field-label">Bank name <span class="req">*</span></label><input type="text" class="field-input" name="bank_name" value="{{ $account->bank_name }}" required></div>
                            <div class="field"><label class="field-label">Account number <span class="req">*</span></label><input type="text" class="field-input" name="account_number" value="{{ $account->account_number }}" required></div>
                        </div>
                        <div class="field-row" style="margin-bottom:12px;">
                            <div class="field"><label class="field-label">Branch</label><input type="text" class="field-input" name="branch" value="{{ $account->branch }}"></div>
                            <div class="field"><label class="field-label">SWIFT code</label><input type="text" class="field-input" name="swift_code" value="{{ $account->swift_code }}"></div>
                        </div>
                        <div class="field"><label class="field-label">Contact</label><input type="text" class="field-input" name="contact" value="{{ $account->contact }}"></div>
                        <label class="check-row">
                            <input type="checkbox" class="check-box" name="is_primary" value="1" {{ $account->is_primary ? 'checked' : '' }}>
                            <span class="check-label">Set as primary account<small>This account will be used as the default for transfers</small></span>
                        </label>
                    </div>
                    <div class="modal-footer-custom">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endforeach

@foreach($beneficiaries as $beneficiary)
    @foreach($beneficiary->accounts as $account)
    <div class="modal fade" id="editAccount{{ $account->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-top">
                    <div class="modal-top-left">
                        <div class="modal-top-icon"><i class="bi bi-credit-card-fill"></i></div>
                        <h5>Edit Account — {{ $beneficiary->name }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/banking-beneficiary/account/update/{{ $account->id }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="field-row" style="margin-bottom:12px;">
                            <div class="field"><label class="field-label">Bank name <span class="req">*</span></label><input type="text" class="field-input" name="bank_name" value="{{ $account->bank_name }}" required></div>
                            <div class="field"><label class="field-label">Account number <span class="req">*</span></label><input type="text" class="field-input" name="account_number" value="{{ $account->account_number }}" required></div>
                        </div>
                        <div class="field-row" style="margin-bottom:12px;">
                            <div class="field"><label class="field-label">Branch</label><input type="text" class="field-input" name="branch" value="{{ $account->branch }}"></div>
                            <div class="field"><label class="field-label">SWIFT code</label><input type="text" class="field-input" name="swift_code" value="{{ $account->swift_code }}"></div>
                        </div>
                        <div class="field"><label class="field-label">Contact</label><input type="text" class="field-input" name="contact" value="{{ $account->contact }}"></div>
                        <label class="check-row">
                            <input type="checkbox" class="check-box" name="is_primary" value="1" {{ $account->is_primary ? 'checked' : '' }}>
                            <span class="check-label">Set as primary account<small>This account will be used as the default for transfers</small></span>
                        </label>
                    </div>
                    <div class="modal-footer-custom">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endforeach
@endif

<script>
    /* ── Tab switching ── */
    function switchTab(id, el) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('tab-' + id).classList.add('active');
    }

    /* ── Modal inner tabs ── */
    function switchModalTab(id, el) {
        el.closest('.modal-body').querySelectorAll('.modal-tab-btn').forEach(b => b.classList.remove('active'));
        el.closest('.modal-body').querySelectorAll('.modal-tab-pane').forEach(p => p.classList.remove('active'));
        el.classList.add('active');
        document.getElementById(id).classList.add('active');
    }

    /* ── Accounts expand toggle ── */
    function toggleExpand(id) {
        const row = document.getElementById(id);
        if (!row) return;
        const isOpen = row.classList.contains('open');
        document.querySelectorAll('.expand-row.open').forEach(r => r.classList.remove('open'));
        if (!isOpen) row.classList.add('open');
    }

    /* ── Table search filter ── */
    function filterTable(input, tbodyId) {
        const q = input.value.toLowerCase().trim();
        document.querySelectorAll('#' + tbodyId + ' tr[data-search]').forEach(row => {
            const match = !q || row.dataset.search.includes(q);
            row.style.display = match ? '' : 'none';
            /* Also hide/show expand row */
            const next = row.nextElementSibling;
            if (next && next.classList.contains('expand-row')) {
                next.style.display = match ? '' : 'none';
            }
        });
    }
</script>
</body>
</html>