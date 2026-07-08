<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} — Customer Debt Products</title>
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

        body {  background: #ECF0F8; color: var(--slate-800); min-height: 100vh; font-size: 14px; line-height: 1.6; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }
        .wrap { padding: 1.5rem 1.75rem 3rem; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
        .au  { animation: fadeUp 0.38s ease both; }
        .au1 { animation-delay:0.04s; } .au2 { animation-delay:0.10s; }
        .au3 { animation-delay:0.16s; } .au4 { animation-delay:0.22s; }
        .au5 { animation-delay:0.28s; }

        /* ── Alerts ── */
        .alert { display:flex; align-items:center; justify-content:space-between; padding:.75rem 1rem; border-radius:var(--r); margin-bottom:1rem; font-size:13px; font-weight:500; }
        .alert-success { background:var(--emerald-pale); color:var(--emerald); border-left:3px solid var(--emerald); }
        .alert-danger  { background:var(--rose-pale);    color:var(--rose);    border-left:3px solid var(--rose); }
        .close-btn { background:none; border:none; cursor:pointer; color:inherit; font-size:16px; opacity:.6; }
        .close-btn:hover { opacity:1; }

        /* ── Breadcrumb ── */
        .breadcrumb { display:flex; align-items:center; gap:6px; margin-bottom:1.1rem; flex-wrap:wrap; }
        .bc-link { display:inline-flex; align-items:center; gap:4px; font-size:12px; color:var(--slate-400); text-decoration:none; transition:color .15s; }
        .bc-link:hover { color:var(--navy); }
        .bc-sep { font-size:12px; color:var(--slate-300); }
        .bc-cur { font-size:12px; font-weight:600; color:var(--slate-700); }

        /* ── Page header ── */
        .pg-header {
            background: var(--navy); border-radius:var(--r-xl);
            padding:1.2rem 1.6rem; margin-bottom:1.4rem;
            display:flex; align-items:center; justify-content:space-between;
            gap:1rem; flex-wrap:wrap; position:relative; overflow:hidden;
        }
        .pg-header::before { content:''; position:absolute; top:-50px; right:-30px; width:180px; height:180px; border-radius:50%; background:var(--navy-light); opacity:.45; pointer-events:none; }
        .pg-header::after  { content:''; position:absolute; bottom:-55px; right:100px; width:120px; height:120px; border-radius:50%; background:var(--rose); opacity:.06; pointer-events:none; }
        .pg-left { display:flex; align-items:center; gap:10px; position:relative; z-index:1; }
        .header-icon { width:40px; height:40px; border-radius:var(--r); background:var(--rose); display:flex; align-items:center; justify-content:center; font-size:18px; color:var(--white); flex-shrink:0; }
        .pg-title-text h1 { font-size:16px; font-weight:700; color:var(--white); letter-spacing:-.2px; }
        .pg-title-text p  { font-size:12px; color:rgba(255,255,255,.45); margin-top:1px; }
        .pg-right { display:flex; gap:8px; align-items:center; position:relative; z-index:1; flex-wrap:wrap; }

        /* ── Buttons ── */
        .btn-amber { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:var(--r); background:var(--amber); color:var(--navy);  font-size:13px; font-weight:700; border:none; cursor:pointer; box-shadow:0 3px 14px rgba(245,158,11,.35); transition:all .18s; text-decoration:none; }
        .btn-amber:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(245,158,11,.45); color:var(--navy); }
        .btn-ghost { display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:var(--r); background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.16); color:rgba(255,255,255,.75);  font-size:13px; font-weight:500; cursor:pointer; transition:all .15s; text-decoration:none; }
        .btn-ghost:hover { background:rgba(255,255,255,.14); color:var(--white); }

        /* ── Customer strip ── */
        .cust-strip {
            background:var(--white); border:1.5px solid var(--slate-200);
            border-left:4px solid var(--rose);
            border-radius:var(--r-lg); padding:.9rem 1.3rem;
            margin-bottom:1.25rem;
            display:flex; align-items:center; gap:1rem; flex-wrap:wrap;
            box-shadow:0 1px 4px rgba(11,30,61,.05);
        }
        .cust-avatar { width:44px; height:44px; border-radius:50%; background:var(--navy-mid); color:rgba(255,255,255,.85); display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700; flex-shrink:0; }
        .cust-info h4 { font-size:15px; font-weight:700; color:var(--navy); }
        .cust-info p  { font-size:12px; color:var(--slate-400); margin-top:2px; }
        .cust-totals { margin-left:auto; display:flex; gap:1.5rem; flex-wrap:wrap; }
        .cust-total-item { text-align:right; }
        .ct-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--slate-400); margin-bottom:2px; }
        .ct-val {  font-size:18px; font-weight:600; line-height:1; }
        .ct-red   { color:var(--rose); }
        .ct-green { color:var(--emerald); }

        /* ── Filter form ── */
        .filter-card { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-lg); padding:1rem 1.2rem; margin-bottom:1.25rem; box-shadow:0 1px 4px rgba(11,30,61,.05); }
        .filter-row  { display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end; }
        .filter-group { flex:1; min-width:130px; }
        .filter-label { display:block; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--slate-400); margin-bottom:5px; }
        .filter-ctrl { width:100%;  font-size:13px; padding:8px 12px; border:1.5px solid var(--slate-200); border-radius:var(--r); background:var(--white); color:var(--slate-800); outline:none; transition:border-color .18s, box-shadow .18s; }
        .filter-ctrl:focus { border-color:var(--navy-light); box-shadow:0 0 0 3px rgba(26,58,107,.1); }
        .filter-actions { display:flex; gap:8px; flex-shrink:0; padding-bottom:1px; }
        .btn-filter { display:inline-flex; align-items:center; gap:5px; padding:8px 16px; border-radius:var(--r); background:var(--navy); color:var(--white);  font-size:13px; font-weight:600; border:none; cursor:pointer; transition:all .15s; }
        .btn-filter:hover { background:var(--navy-light); }
        .btn-reset  { display:inline-flex; align-items:center; gap:5px; padding:8px 14px; border-radius:var(--r); border:1.5px solid var(--slate-200); background:transparent;  font-size:13px; color:var(--slate-600); cursor:pointer; text-decoration:none; transition:all .15s; }
        .btn-reset:hover { background:var(--slate-100); }

        .date-active-badge { display:inline-flex; align-items:center; gap:5px; margin-bottom:1rem; background:var(--navy-mid); color:rgba(255,255,255,.8); padding:4px 12px; border-radius:20px; font-size:12px;  }

        /* ── Metrics ── */
        .metrics-grid { display:grid; grid-template-columns:repeat(4, minmax(0,1fr)); gap:1rem; margin-bottom:1.4rem; }
        @media(max-width:900px){ .metrics-grid { grid-template-columns:repeat(2,1fr); } }
        .metric-card { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-lg); padding:1.1rem 1.2rem; box-shadow:0 1px 4px rgba(11,30,61,.05); position:relative; overflow:hidden; transition:transform .2s, box-shadow .2s; }
        .metric-card::after { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:var(--mc); }
        .mc-rose    { --mc:var(--rose); }
        .mc-emerald { --mc:var(--emerald); }
        .mc-navy    { --mc:var(--navy); }
        .mc-amber   { --mc:var(--amber); }
        .metric-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(11,30,61,.1); }
        .metric-inner { display:flex; align-items:center; gap:12px; }
        .metric-icon { width:42px; height:42px; border-radius:var(--r); flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:17px; }
        .mi-rose    { background:var(--rose-pale);    color:var(--rose); }
        .mi-emerald { background:var(--emerald-pale); color:var(--emerald); }
        .mi-navy    { background:rgba(11,30,61,.08);  color:var(--navy); }
        .mi-amber   { background:var(--amber-pale);   color:#92400e; }
        .metric-label { font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--slate-400); margin-bottom:4px; }
        .metric-value {  font-size:20px; font-weight:500; color:var(--navy); letter-spacing:-.5px; line-height:1; }
        .mv-red   { color:var(--rose); }
        .mv-green { color:var(--emerald); }

        /* ── VIEW TOGGLE ── */
        .view-controls { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; flex-wrap:wrap; gap:.75rem; }
        .view-label { font-size:13px; font-weight:600; color:var(--navy); }
        .view-toggle { display:flex; border:1.5px solid var(--slate-200); border-radius:var(--r); overflow:hidden; }
        .view-btn { width:34px; height:34px; border:none; background:transparent; color:var(--slate-400); font-size:14px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .15s; }
        .view-btn.active { background:var(--navy); color:var(--white); }
        .view-btn:not(.active):hover { background:var(--slate-100); color:var(--slate-700); }

        /* ══════════════════════════════════
           CARD VIEW
        ══════════════════════════════════ */
        #cardView {}

        .date-section-head {
            display:flex; align-items:center; gap:10px;
            padding:.6rem 1rem .6rem 1.2rem;
            background:var(--navy); border-radius:var(--r-lg);
            margin-bottom:1rem;
        }
        .date-section-head i { color:var(--amber); font-size:14px; }
        .date-section-head h5 { font-size:13px; font-weight:700; color:var(--white); margin:0; }
        .date-section-head span { font-size:11.5px; color:rgba(255,255,255,.45); margin-left:auto;  }

        .invoice-card {
            background:var(--white); border:1.5px solid var(--slate-200);
            border-radius:var(--r-xl); margin-bottom:1.25rem; overflow:hidden;
            box-shadow:0 1px 4px rgba(11,30,61,.05);
            transition:box-shadow .2s;
        }
        .invoice-card:hover { box-shadow:0 4px 16px rgba(11,30,61,.1); }

        .inv-card-head {
            display:flex; align-items:center; justify-content:space-between;
            padding:.9rem 1.3rem; border-bottom:1.5px solid var(--slate-200);
            gap:.75rem; flex-wrap:wrap;
        }
        .inv-head-left { display:flex; align-items:center; gap:10px; }
        .inv-icon { width:32px; height:32px; border-radius:var(--r); background:rgba(11,30,61,.08); color:var(--navy-light); display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; }
        .inv-name { font-size:13.5px; font-weight:700; color:var(--navy); }

        /* Status badges */
        .badge-paid    { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; background:var(--emerald-pale); color:var(--emerald); }
        .badge-partial { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; background:var(--amber-pale); color:#92400e; }
        .badge-unpaid  { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; background:var(--rose-pale); color:var(--rose); }

        /* Progress */
        .progress-section { padding:.9rem 1.3rem; border-bottom:1.5px solid var(--slate-200); }
        .amounts-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:.75rem; }
        .amt-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--slate-400); margin-bottom:3px; }
        .amt-val   {  font-size:15px; font-weight:600; color:var(--slate-800); }
        .amt-val.green { color:var(--emerald); }
        .amt-val.red   { color:var(--rose); }
        .progress-track { height:6px; background:var(--slate-100); border-radius:3px; overflow:hidden; margin-bottom:.35rem; }
        .progress-fill  { height:100%; background:var(--emerald); border-radius:3px; transition:width .4s ease; }
        .progress-labels { display:flex; justify-content:space-between; font-size:11px; color:var(--slate-400);  }

        /* Description band */
        .items-desc-band {
            background:var(--slate-50); border-bottom:1.5px solid var(--slate-200);
            padding:.6rem 1.3rem; display:flex; align-items:flex-start; gap:8px;
        }
        .items-desc-band i { color:var(--slate-400); font-size:13px; flex-shrink:0; margin-top:2px; }
        .items-desc-text { font-size:12.5px; color:var(--slate-600); font-style:italic; line-height:1.5; }

        /* Products mini-table inside card */
        .card-tbl-wrap { overflow-x:auto; }
        .card-tbl { width:100%; border-collapse:collapse; font-size:12.5px; }
        .card-tbl thead th { background:var(--slate-50); color:var(--slate-400); font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; padding:7px 14px; border-bottom:1px solid var(--slate-200); white-space:nowrap; text-align:left; }
        .card-tbl tbody td { padding:9px 14px; border-bottom:1px solid var(--slate-100); vertical-align:middle; }
        .card-tbl tbody tr:last-child td { border-bottom:none; }
        .card-tbl tbody tr:hover td { background:#F7F9FF; }

        .prod-name  { font-weight:600; color:var(--slate-800); }
        .qty-pill   { display:inline-flex; align-items:center; padding:2px 8px; background:var(--slate-100); border-radius:20px; font-size:11px; font-weight:600; color:var(--slate-600);  }
        .price-mono {  font-size:12.5px; font-weight:600; }
        .date-mono  {  font-size:11px; color:var(--slate-400); }

        .btn-undo { display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:var(--r); background:rgba(11,30,61,.07); color:var(--navy-light); border:none;  font-size:11.5px; font-weight:600; cursor:pointer; transition:all .15s; }
        .btn-undo:hover { background:var(--navy); color:var(--white); }
        .btn-edit { display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:var(--r); background:rgba(5,150,105,.08); color:var(--emerald); border:none;  font-size:11.5px; font-weight:600; cursor:pointer; transition:all .15s; }
        .btn-edit:hover { background:var(--emerald); color:var(--white); }

        .btn-edit { display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:var(--r); background:rgba(11,30,61,.07); color:var(--navy-light); border:none;  font-size:11.5px; font-weight:600; cursor:pointer; transition:all .15s; }
        .btn-edit:hover { background:var(--navy); color:var(--white); }

        /* Pay footer */
        .pay-footer { padding:.875rem 1.3rem; display:flex; align-items:center; justify-content:flex-end; border-top:1.5px solid var(--slate-200); background:var(--slate-50); gap:8px; }
        .btn-pay { display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:var(--r); background:var(--emerald); color:var(--white);  font-size:13px; font-weight:700; border:none; cursor:pointer; box-shadow:0 3px 12px rgba(5,150,105,.25); transition:all .18s; }
        .btn-pay:hover { background:#047857; transform:translateY(-1px); box-shadow:0 6px 18px rgba(5,150,105,.3); }

        .btn-delete { display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:var(--r); background:transparent; color:var(--rose); border:1.5px solid var(--rose);  font-size:13px; font-weight:700; cursor:pointer; transition:all .18s; }
        .btn-delete:hover { background:var(--rose); color:var(--white); }

        /* ══════════════════════════════════
           TABLE VIEW
        ══════════════════════════════════ */
        #tableView { display:none; }

        .tbl-panel { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-xl); overflow:hidden; box-shadow:0 1px 4px rgba(11,30,61,.05); }
        .tbl-panel-head { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.4rem; border-bottom:1.5px solid var(--slate-200); background:var(--slate-50); gap:1rem; flex-wrap:wrap; }
        .tbl-panel-title { font-size:13.5px; font-weight:700; color:var(--navy); display:flex; align-items:center; gap:8px; }
        .result-pill { font-size:11px; font-weight:600;  background:var(--slate-200); color:var(--slate-600); padding:2px 9px; border-radius:20px; }

        .tbl-scroll { overflow-x:auto; }
        .main-tbl   { width:100%; border-collapse:collapse; font-size:13px; }

        .main-tbl thead th { background:var(--navy); color:rgba(255,255,255,.65); font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; padding:10px 16px; white-space:nowrap; border:none; text-align:left; }
        .main-tbl tbody tr { border-bottom:1px solid var(--slate-100); transition:background .12s; }
        .main-tbl tbody tr:last-child { border-bottom:none; }
        .main-tbl tbody tr:hover td { background:#F7F9FF; }
        .main-tbl td { padding:11px 16px; vertical-align:middle; }

        .inv-pill { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:20px; background:rgba(11,30,61,.07); color:var(--navy); font-size:11px; font-weight:700;  }
        .desc-cell { font-size:12.5px; color:var(--slate-600); font-style:italic; max-width:240px; }
        .tbl-prod-name { font-weight:600; color:var(--navy); font-size:13px; }
        .tbl-qty   {  font-size:12.5px; color:var(--slate-600); }
        .tbl-price {  font-weight:600; color:var(--slate-800); }
        .tbl-total {  font-weight:700; color:var(--emerald); }
        .tbl-date  {  font-size:11.5px; color:var(--slate-400); }

        /* ── Modal ── */
        .modal-content { border:none; border-radius:var(--r-xl); overflow:hidden; box-shadow:0 20px 60px rgba(11,30,61,.2); }
        .modal-top { background:var(--navy); padding:1.15rem 1.4rem; display:flex; align-items:center; justify-content:space-between; }
        .modal-top-left { display:flex; align-items:center; gap:10px; }
        .modal-top-icon { width:32px; height:32px; border-radius:var(--r); background:var(--emerald); display:flex; align-items:center; justify-content:center; color:var(--white); font-size:14px; }
        .modal-top h5 { font-size:15px; font-weight:700; color:var(--white); margin:0; }
        .modal-top .btn-close { filter:invert(1) brightness(.75); }
        .modal-body { padding:1.5rem 1.4rem; }
        .field { margin-bottom:12px; }
        .field-label { display:block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--slate-500); margin-bottom:5px; }
        .field-input { width:100%;  font-size:13.5px; padding:9px 12px; border:1.5px solid var(--slate-200); border-radius:var(--r); background:var(--white); color:var(--slate-800); outline:none; transition:border-color .18s, box-shadow .18s; }
        .field-input:focus { border-color:var(--navy-light); box-shadow:0 0 0 3px rgba(26,58,107,.1); }
        .field-input.readonly { background:var(--slate-50); color:var(--slate-500); }
        .field-hint { font-size:11px; color:var(--slate-400); margin-top:4px; }
        .modal-footer-custom { padding:1rem 1.4rem; border-top:1.5px solid var(--slate-200); display:flex; justify-content:flex-end; gap:8px; }
        .btn-cancel { padding:9px 18px; border-radius:var(--r); border:1.5px solid var(--slate-200); background:transparent;  font-size:13px; color:var(--slate-600); cursor:pointer; transition:all .15s; }
        .btn-cancel:hover { background:var(--slate-100); }
        .btn-confirm { padding:9px 20px; border-radius:var(--r); background:var(--emerald); color:var(--white); border:none;  font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:0 3px 14px rgba(5,150,105,.25); transition:all .18s; }
        .btn-confirm:hover { background:#047857; transform:translateY(-1px); }

        /* ── Empty ── */
        .empty-state { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-xl); text-align:center; padding:4rem 1.5rem; color:var(--slate-400); box-shadow:0 1px 4px rgba(11,30,61,.05); }
        .empty-state i { font-size:2.5rem; display:block; margin-bottom:.75rem; opacity:.3; }
        .empty-state h4 { font-size:15px; font-weight:600; color:var(--slate-600); margin-bottom:5px; }
        .empty-state p  { font-size:13px; }

        @media(max-width:768px) { .wrap{padding:1rem;} .cust-totals{margin-left:0;} .amounts-grid{grid-template-columns:1fr 1fr;} }
    </style>
</head>
<body>
        @include("sidenav")

        <main class="main-content">
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

                {{-- Breadcrumb --}}
                <div class="breadcrumb au au1">
                    <a href="{{ url('shopInvoices') }}" class="bc-link"><i class="bi bi-shop"></i> Shops</a>
                    <span class="bc-sep">/</span>
                    <a href="{{ url('shopDebtors/' . urlencode($shopName)) }}" class="bc-link">{{ $shopName }}</a>
                    <span class="bc-sep">/</span>
                    <span class="bc-cur">{{ $customerName }}</span>
                </div>

                {{-- Page header --}}
                <div class="pg-header au au1">
                    <div class="pg-left">
                        <div class="header-icon"><i class="bi bi-receipt"></i></div>
                        <div class="pg-title-text">
                            <h1>@lang('messages.debt_products')</h1>
                            <p>@lang('messages.all_products_outstanding_invoices')</p>
                        </div>
                    </div>
                    <div class="pg-right">
                        <a href="{{ url('shopDebtors/' . urlencode($shopName)) }}" class="btn-ghost">
                            <i class="bi bi-arrow-left"></i> @lang('messages.back')
                        </a>
                        <a href="{{ url('customer-kpi') }}" class="btn-ghost" style="margin-left: 8px;">
                            <i class="bi bi-graph-up"></i> {{ __('messages.customer_kpi') }}
                        </a>
                    </div>
                </div>

                {{-- Filter --}}
                <div class="filter-card au au2">
                    <form action="{{ url('customerDebtProducts') }}" method="post">
                        @csrf
                        <input type="hidden" name="customer" value="{{ $customerName }}">
                        <input type="hidden" name="shop"     value="{{ $shopName }}">
                        <div class="filter-row">
                            <div class="filter-group">
                                <label class="filter-label">{{ __('messages.from') }}</label>
                                <input type="date" name="start_date" class="filter-ctrl" value="{{ $startDate ?? '' }}">
                            </div>
                            <div class="filter-group">
                                <label class="filter-label">{{ __('messages.to') }}</label>
                                <input type="date" name="end_date" class="filter-ctrl" value="{{ $endDate ?? '' }}">
                            </div>
                            <div class="filter-actions">
                                <button type="submit" class="btn-filter"><i class="bi bi-funnel"></i> {{ __('messages.filter') }}</button>
                                <button type="submit" name="reset" value="1" class="btn-reset"><i class="bi bi-x"></i> {{ __('messages.reset') }}</button>
                            </div>
                        </div>
                    </form>
                </div>

                @if(!empty($startDate) || !empty($endDate))
                <div class="date-active-badge au au2">
                    <i class="bi bi-calendar-range"></i>
                    @if(!empty($startDate)) {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} @else From start @endif
                    &nbsp;—&nbsp;
                    @if(!empty($endDate)) {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }} @else to today @endif
                </div>
                @endif

                @php
                    $groupedInvoices = $debtProducts->groupBy('orderName');
                    $groupedByDate   = $debtProducts->groupBy(fn($i) => \Carbon\Carbon::parse($i->created_at)->format('Y-m-d'));
                    $totalDebt       = $debtProducts->sum('totalPrice') - $debtProducts->sum('paid');
                    $totalPaid       = collect($invoicePayments)->sum('paid');
                    $totalItems      = $debtProducts->count();
                    $invoiceCount    = $groupedInvoices->count();
                @endphp

                {{-- Customer strip --}}
                <div class="cust-strip au au2">
                    @php
                        $initials = strtoupper(substr($customerName, 0, 1));
                        $parts    = explode(' ', trim($customerName));
                        if (count($parts) > 1) $initials .= strtoupper(substr($parts[1], 0, 1));
                    @endphp
                    <div class="cust-avatar">{{ $initials }}</div>
                    <div class="cust-info">
                        <h4>{{ $customerName }}</h4>
                        <p><i class="bi bi-building"></i> {{ $shopName }} &middot; {{ $invoiceCount }} {{ Str::plural('invoice', $invoiceCount) }}</p>
                    </div>
                    <div class="cust-totals">
                        <div class="cust-total-item">
                            <div class="ct-label">Total debt</div>
                            <div class="ct-val ct-red">{{ number_format($totalDebt) }}</div>
                        </div>
                        <div class="cust-total-item">
                            <div class="ct-label">Paid so far</div>
                            <div class="ct-val ct-green">{{ number_format($totalPaid) }}</div>
                        </div>
                    </div>
                </div>

                {{-- Metrics --}}
                <div class="metrics-grid au au3">
                    <div class="metric-card mc-navy">
                        <div class="metric-inner">
                            <div class="metric-icon mi-navy"><i class="bi bi-receipt"></i></div>
                            <div><div class="metric-label">Invoices</div><div class="metric-value">{{ $invoiceCount }}</div></div>
                        </div>
                    </div>
                    <div class="metric-card mc-rose">
                        <div class="metric-inner">
                            <div class="metric-icon mi-rose"><i class="bi bi-exclamation-circle"></i></div>
                            <div><div class="metric-label">Total debt</div><div class="metric-value mv-red" style="font-size:16px;">{{ number_format($totalDebt) }}</div></div>
                        </div>
                    </div>
                    <div class="metric-card mc-emerald">
                        <div class="metric-inner">
                            <div class="metric-icon mi-emerald"><i class="bi bi-cash-stack"></i></div>
                            <div><div class="metric-label">Paid so far</div><div class="metric-value mv-green" style="font-size:16px;">{{ number_format($totalPaid) }}</div></div>
                        </div>
                    </div>
                    <div class="metric-card mc-amber">
                        <div class="metric-inner">
                            <div class="metric-icon mi-amber"><i class="bi bi-box"></i></div>
                            <div><div class="metric-label">Line items</div><div class="metric-value">{{ $totalItems }}</div></div>
                        </div>
                    </div>
                </div>

                {{-- View toggle --}}
                <div class="view-controls au au4">
                    <span class="view-label">{{ $invoiceCount }} {{ Str::plural('invoice', $invoiceCount) }} · {{ $totalItems }} items</span>
                    <div class="view-toggle">
                        <button class="view-btn active" id="cardViewBtn" onclick="setView('card')" title="Card view">
                            <i class="bi bi-card-list"></i>
                        </button>
                        <button class="view-btn" id="tableViewBtn" onclick="setView('table')" title="Table view">
                            <i class="bi bi-table"></i>
                        </button>
                    </div>
                </div>

                {{-- ══════════════ CARD VIEW ══════════════ --}}
                <div id="cardView">
                    @php $cardModalCounter = 0; @endphp
                    @forelse($groupedByDate as $date => $dayItems)
                        @php $dateInvoices = $dayItems->groupBy('orderName'); @endphp

                        <div class="date-section-head">
                            <i class="bi bi-calendar3"></i>
                            <h5>{{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</h5>
                            <span>{{ $dateInvoices->count() }} {{ Str::plural('invoice', $dateInvoices->count()) }}</span>
                        </div>

                        @foreach($dateInvoices as $invoiceName => $items)
                            @php
                                $invoiceTotal = $items->sum('credit');
                                if ($invoiceTotal < 1) {
                                    $invoiceTotal = $items->sum('totalPrice');
                                }
                                $paymentInfo  = $invoicePayments[$invoiceName] ?? ['paid'=>0,'remaining'=>$invoiceTotal];
                                $remaining    = $paymentInfo['remaining'];
                                $paid         = $paymentInfo['paid'];
                                $isPaid       = $remaining <= 0;
                                $paidPct      = $invoiceTotal > 0 ? round(($paid/$invoiceTotal)*100) : 0;
                                $modalId = 'payDebtModal_card_' . $cardModalCounter++;

                                // Build description string of all items
                                $itemDesc = $items->map(fn($p) =>
                                    ($p->name01 ?? $p->productId ?? 'Item') . ' ×' . ($p->pQuantity ?? 1)
                                )->implode(', ');
                            @endphp

                        <div class="invoice-card">
                            {{-- Card header --}}
                            <div class="inv-card-head">
                                <div class="inv-head-left">
                                    <div class="inv-icon"><i class="bi bi-receipt"></i></div>
                                    <span class="inv-name">Invoice #{{ $invoiceName }}</span>
                                </div>
                                @if($isPaid)
                                    <span class="badge-paid"><i class="bi bi-check-circle-fill"></i> Fully paid</span>
                                @elseif($paidPct > 0)
                                    <span class="badge-partial"><i class="bi bi-clock-fill"></i> Partial ({{ $paidPct }}%)</span>
                                @else
                                    <span class="badge-unpaid"><i class="bi bi-x-circle-fill"></i> Unpaid</span>
                                @endif
                            </div>


                            {{-- Progress --}}
                            <div class="progress-section">
                                <div class="amounts-grid">
                                    <div>
                                        <div class="amt-label">Total</div>
                                        <div class="amt-val">{{ number_format($invoiceTotal) }}</div>
                                    </div>
                                    <div>
                                        <div class="amt-label">Paid</div>
                                        <div class="amt-val green">{{ number_format($paid) }}</div>
                                    </div>
                                    <div>
                                        <div class="amt-label">Remaining</div>
                                        <div class="amt-val {{ $remaining > 0 ? 'red' : '' }}">{{ number_format($remaining) }}</div>
                                    </div>
                                </div>
                                <div class="progress-track">
                                    <div class="progress-fill" style="width:{{ $paidPct }}%"></div>
                                </div>
                                <div class="progress-labels">
                                    <span>{{ $paidPct }}% paid</span>
                                    <span>{{ $isPaid ? 'Completed' : (100 - $paidPct).'% remaining' }}</span>
                                </div>
                            </div>

                            {{-- Products mini-table --}}
                            <div class="card-tbl-wrap">
                                <table class="card-tbl">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Unit price</th>
                                            <th>Total</th>
                                            <th>Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $product)
                                        <tr>
                                            <td class="prod-name">{{ $product->name01 ?? $product->productId ?? 'N/A' }}</td>
                                            <td><span class="qty-pill">×{{ $product->pQuantity ?? 0 }}</span></td>
                                            <td class="price-mono">{{ number_format($product->productPrice ?? 0) }}</td>
                                            <td class="price-mono" style="color:var(--emerald);">{{ number_format($product->totalPrice) }}</td>
                                            <td class="date-mono">{{ \Carbon\Carbon::parse($product->created_at)->format('d M, H:i') }}</td>
                                            <td>
                                                <div style="display:flex; gap:4px; flex-wrap:wrap;">
                                                    @if(canUser('edit_debts'))
                                                    <button type="button" class="btn-edit"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editProductModal_{{ $product->id }}"
                                                        onclick="openEditDebtProductModal(this)"
                                                        data-product-id="{{ $product->id }}"
                                                        data-product-name="{{ $product->name01 ?? $product->productId ?? '' }}"
                                                        data-qty="{{ $product->pQuantity ?? 0 }}"
                                                        data-price="{{ $product->productPrice ?? 0 }}"
                                                        data-total="{{ $product->totalPrice }}"
                                                        data-order-name="{{ $product->orderName }}"
                                                        data-order-id="{{ $product->order_id }}"
                                                        data-shop="{{ $shopName }}"
                                                        data-payment-info="{{ json_encode($paymentInfo) }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    @endif
                                                    @if(canUser('delete_orders'))
                                                    <button type="button" class="btn-delete"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteProductModal_{{ $product->id }}"
                                                        style="padding:4px 10px; font-size:11.5px;">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    @endif
                                                    @if($paid > 0 && canUser('pay_debts'))
                                                    <form action="{{ url('undoInvoiceDebt') }}" method="post" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="invoiceName" value="{{ $product->orderName }}">
                                                        <input type="hidden" name="shopName" value="{{ $shopName }}">
                                                        <button type="submit" class="btn-undo"
                                                             onclick="return confirm(@json(__('messages.undo_last_payment_invoice', ['id' => $product->orderName])))">
                                                            <i class="bi bi-arrow-counterclockwise"></i> Undo
                                                        </button>
                                                    </form>
                                                    @elseif(!canUser('pay_debts') && !canUser('delete_orders'))
                                                        <span style="font-size:11px; color:var(--slate-300);">—</span>
                                                    @endif
                                                </div>
                                                {{-- Delete product modal --}}
                                                @if(canUser('delete_orders'))
                                                <div class="modal fade" id="deleteProductModal_{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-top">
                                                                <div class="modal-top-left">
                                                                    <div class="modal-top-icon" style="background:var(--rose);"><i class="bi bi-exclamation-triangle"></i></div>
                                                                     <h5>{{ __('messages.confirm_delete') }}</h5>
                                                                </div>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="{{ url('deleteDebtProduct') }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="field">
                                                                         <p>@lang('messages.delete_product_from_invoice', ['product' => $product->name01 ?? $product->productId, 'id' => $product->orderName])</p>
                                                                         <p style="font-size:12px; color:var(--rose); margin-top:10px;">
                                                                             <i class="bi bi-info-circle"></i> @lang('messages.product_stock_restored_invoice_remains_open')
                                                                         </p>
                                                                    </div>
                                                                    <input type="hidden" name="productId" value="{{ $product->id }}">
                                                                    <input type="hidden" name="shopName" value="{{ $shopName }}">
                                                                    <input type="hidden" name="orderId" value="{{ $product->order_id }}">
                                                                </div>
                                                                <div class="modal-footer-custom">
                                                                     <button type="button" class="btn-cancel" data-bs-dismiss="modal">@lang('messages.cancel')</button>
                                                                     <button type="submit" class="btn-confirm" style="background:var(--rose);">
                                                                         <i class="bi bi-trash"></i> @lang('messages.delete')
                                                                     </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pay footer --}}
                            @if(!$isPaid && canUser('pay_debts'))
                            <div class="pay-footer">
                                <button type="button" class="btn-pay"
                                    data-bs-toggle="modal"
                                    data-bs-target="#{{ $modalId }}">
                                    <i class="bi bi-cash-stack"></i>
                                    Pay remaining — {{ number_format($remaining) }} Tsh
                                </button>
                                @if(canUser('delete_orders'))
                                <button type="button" class="btn-delete"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal_card_{{ $invoiceName }}_{{ $cardModalCounter }}">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                                @endif
                            </div>
                            @endif
                        </div>

                        {{-- Pay modal --}}
                        @if(!$isPaid && canUser('pay_debts'))
                        <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-top">
                                        <div class="modal-top-left">
                                            <div class="modal-top-icon"><i class="bi bi-cash-stack"></i></div>
                                            <h5>Pay debt — #{{ $invoiceName }}</h5>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ url('payInvoiceDebt') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="field">
                                                <label class="field-label">Customer</label>
                                                <input type="text" class="field-input readonly" value="{{ $customerName }}" readonly>
                                            </div>
                                            <div class="field">
                                                <label class="field-label">Remaining amount</label>
                                                <input type="text" class="field-input readonly" value="{{ number_format($remaining) }} Tsh" readonly>
                                            </div>
                                            <div class="field">
                                                <label class="field-label">Payment method <span style="color:var(--rose);">*</span></label>
                                                <select class="field-input" name="payment_method" id="payment_method_{{ $modalId }}" required onchange="toggleChipField('{{ $modalId }}', {{ $availableChip }})">
                                                    <option value="cash">Cash</option>
                                                    @if($availableChip > 0)
                                                    <option value="chip">Chip</option>
                                                    @else
                                                    <option value="chip" disabled>Chip (unavailable)</option>
                                                    @endif
                                                </select>
                                                @if($availableChip <= 0)
                                                <div class="field-hint" style="color:var(--rose);">Chip balance is zero. Cash only.</div>
                                                @endif
                                            </div>
                                            <div class="field" id="chip_amount_field_{{ $modalId }}" style="display: none;">
                                                <label class="field-label">Chip amount <span style="color:var(--rose);">*</span></label>
                                                <input type="number" class="field-input" name="chip_amount"
                                                    min="0" max="{{ $availableChip }}" step="0.01"
                                                    placeholder="Enter chip amount…" id="chip_amount_input_{{ $modalId }}">
                                                <div class="field-hint">Available chip: <strong>{{ number_format($availableChip) }} Tsh</strong></div>
                                                <div class="field-hint" style="color: var(--emerald);">Cash portion will be: <strong id="cash_portion_{{ $modalId }}">{{ number_format($remaining) }}</strong> Tsh</div>
                                            </div>
                                            <div class="field">
                                                <label class="field-label">Payment amount <span style="color:var(--rose);">*</span></label>
                                                <input type="number" class="field-input" name="paymentAmount"
                                                    max="{{ $remaining }}" step="1" required
                                                    placeholder="Enter amount…" id="payment_amount_{{ $modalId }}"
                                                    oninput="updateCashPortion('{{ $modalId }}', {{ $availableChip }})">
                                                <div class="field-hint">Maximum: {{ number_format($remaining) }} Tsh</div>
                                            </div>
                                            <div class="field">
                                                <label class="field-label">Payment date</label>
                                                <input type="date" class="field-input" name="payment_date" value="{{ date('Y-m-d') }}" required>
                                            </div>
                                            <input type="hidden" name="invoiceName" value="{{ $invoiceName }}">
                                            <input type="hidden" name="shopName"    value="{{ $shopName }}">
                                        </div>
                                        <div class="modal-footer-custom">
                                            <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn-confirm">
                                                <i class="bi bi-check-circle-fill"></i> Confirm payment
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Delete Modal for Card View --}}
                        @if(canUser('delete_orders'))
                        <div class="modal fade" id="deleteModal_card_{{ $invoiceName }}_{{ $cardModalCounter }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-top">
                                        <div class="modal-top-left">
                                            <div class="modal-top-icon" style="background:var(--rose);"><i class="bi bi-exclamation-triangle"></i></div>
                                            <h5>Confirm Delete</h5>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ url('deleteOrder') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="field">
                                                <p>Are you sure you want to delete invoice <strong>#{{ $invoiceName }}</strong>?</p>
                                                <p style="font-size:12px; color:var(--rose); margin-top:10px;">
                                                    <i class="bi bi-info-circle"></i> This will permanently delete all items in this order and restore product stock.
                                                </p>
                                            </div>
                                            <input type="hidden" name="orderId" value="{{ $invoiceName }}">
                                        </div>
                                        <div class="modal-footer-custom">
                                            <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn-confirm" style="background:var(--rose);">
                                                <i class="bi bi-trash"></i> Delete Invoice
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        @endforeach
                    @empty
                    <div class="empty-state">
                        <i class="bi bi-check-circle"></i>
                        <h4>All clear!</h4>
                        <p>No debt products found for this customer</p>
                    </div>
                    @endforelse
                </div>

                {{-- Global Edit Product Modal --}}
                <div class="modal fade" id="editProductGlobalModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-top">
                                <div class="modal-top-left">
                                    <div class="modal-top-icon"><i class="bi bi-pencil"></i></div>
                                    <h5 id="editModalTitle">Edit Product</h5>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ url('editDebtProduct') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="field">
                                        <label class="field-label">Product</label>
                                        <input type="text" class="field-input readonly" id="editModalProductName" readonly>
                                    </div>
                                    <div class="field">
                                        <label class="field-label">Quantity <span style="color:var(--rose);">*</span></label>
                                        <input type="number" class="field-input" name="pQuantity" id="editModalQty" min="1" required>
                                        <div class="field-hint" style="color:var(--emerald);">New total will be: <strong id="editModalNewTotal">—</strong> Tsh</div>
                                    </div>
                                    <div class="field">
                                        <label class="field-label">Unit price <span style="color:var(--rose);">*</span></label>
                                        <input type="number" class="field-input" name="productPrice" id="editModalPrice" min="0" step="1" required>
                                    </div>
                                    <input type="hidden" name="productId" id="editModalProductId">
                                    <input type="hidden" name="orderId" id="editModalOrderId">
                                    <input type="hidden" name="shopName" id="editModalShopName">
                                </div>
                                <div class="modal-footer-custom">
                                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn-confirm">
                                        <i class="bi bi-check-circle-fill"></i> Save changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ══════════════ TABLE VIEW ══════════════ --}}
                <div id="tableView">
                    <div class="tbl-panel">
                        <div class="tbl-panel-head">
                            <div class="tbl-panel-title">
                                <i class="bi bi-table" style="color:var(--navy-light);"></i>
                                All Invoices — Summary View
                                <span class="result-pill">{{ $invoiceCount }} {{ Str::plural('invoice', $invoiceCount) }}</span>
                            </div>
                        </div>
                        <div class="tbl-scroll">
                            @if($groupedInvoices->count() > 0)
                            <table class="main-tbl">
                                <thead>
                                    <tr>
                                        <th style="width:40px;"></th>
                                        <th>Date</th>
                                        <th>Invoice #</th>
                                        
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th>Remaining</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupedInvoices as $invoiceName => $items)
                                    @php
                                                                              
                                        $invoiceTotal = $items->sum('totalPrice') - $items->sum('paid');
                                        
                                        $paymentInfo  = $invoicePayments[$invoiceName] ?? ['paid'=>0,'remaining'=>$invoiceTotal];
                                        $remaining    = $paymentInfo['remaining'];
                                        $paid         = $paymentInfo['paid'];
                                        $isPaid       = $remaining <= 0;
                                        $paidPct      = $invoiceTotal > 0 ? round(($paid/$invoiceTotal)*100) : 0;
                                        $itemCount    = $items->count();
                                        $invoiceDate  = $items->first()->created_at ?? null;
                                        $tableModalId = 'payDebtModal_table_' . $loop->index;
                                    @endphp
                                    <tr class="invoice-row" data-invoice="{{ $invoiceName }}" style="cursor:pointer;">
                                        <td style="text-align:center;">
                                            <i class="bi bi-chevron-right" style="color:var(--slate-400); font-size:16px;"></i>
                                        </td>
                                         <td class="tbl-date">
                                            {{ $invoiceDate ? \Carbon\Carbon::parse($invoiceDate)->format('d M Y') : 'N/A' }}
                                        </td>
                                        <td>
                                            <span class="inv-pill">#{{ $invoiceName }}</span>
                                        </td>
                                       
                                        <td>
                                            <span style="font-size:13px; font-weight:600; color:var(--slate-700);">
                                                {{ $itemCount }} {{ Str::plural('item', $itemCount) }}
                                            </span>
                                        </td>
                                        <td class="tbl-total">{{ number_format($invoiceTotal) }}</td>
                                        <td class="tbl-price" style="color:var(--emerald); font-weight:700;">
                                            {{ number_format($paid) }}
                                        </td>
                                        <td class="tbl-price" style="color:var(--rose); font-weight:700;">
                                            {{ number_format($remaining) }}
                                        </td>
                                        <td>
                                            @if($isPaid)
                                                <span class="badge-paid" style="font-size:10.5px;"><i class="bi bi-check-circle-fill"></i> Paid</span>
                                            @elseif($paidPct > 0)
                                                <span class="badge-partial" style="font-size:10.5px;"><i class="bi bi-clock-fill"></i> {{ $paidPct }}%</span>
                                            @else
                                                <span class="badge-unpaid" style="font-size:10.5px;"><i class="bi bi-x-circle-fill"></i> Unpaid</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$isPaid && canUser('pay_debts'))
                                            <button type="button" class="btn-pay"
                                                data-bs-toggle="modal"
                                                data-bs-target="#payDebtModal_table_{{ $invoiceName }}_{{ $loop->index }}"
                                                onclick="toggleChipField('payDebtModal_table_{{ $invoiceName }}_{{ $loop->index }}', {{ $availableChip }})"
                                                style="padding:6px 12px; font-size:12px;">
                                                <i class="bi bi-cash-stack"></i> Pay
                                            </button>
                                            @endif
                                            @if(canUser('delete_orders'))
                                            <button type="button" class="btn-delete"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal_table_{{ $invoiceName }}_{{ $loop->index }}"
                                                style="padding:6px 12px; font-size:12px; margin-left:5px;">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                            @endif
                                            @if($isPaid && !canUser('delete_orders'))
                                                <span style="font-size:11px; color:var(--slate-400);">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    {{-- Pay modal for this invoice (inside same loop so variable is available) --}}
                                    @if(!$isPaid && canUser('pay_debts'))
                                    <div class="modal fade" id="payDebtModal_table_{{ $invoiceName }}_{{ $loop->index }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-top">
                                                    <div class="modal-top-left">
                                                        <div class="modal-top-icon"><i class="bi bi-cash-stack"></i></div>
                                                        <h5>@lang('messages.pay_debt_invoice', ['id' => $invoiceName])</h5>
                                                    </div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ url('payInvoiceDebt') }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="field">
                                                <label class="field-label">@lang('messages.customer')</label>
                                                            <input type="text" class="field-input readonly" value="{{ $customerName }}" readonly>
                                                        </div>
                                                        <div class="field">
                                                            <label class="field-label">Remaining amount</label>
                                                            <input type="text" class="field-input readonly" value="{{ number_format($remaining) }} Tsh" readonly>
                                                        </div>
                                                        <div class="field">
                                                            <label class="field-label">Payment method <span style="color:var(--rose);">*</span></label>
                                                            <select class="field-input" name="payment_method" id="payment_method_payDebtModal_table_{{ $invoiceName }}_{{ $loop->index }}" required onchange="toggleChipField('payDebtModal_table_{{ $invoiceName }}_{{ $loop->index }}', {{ $availableChip }})">
                                                                <option value="cash">Cash</option>
                                                                <option value="chip">Chip</option>
                                                            </select>
                                                        </div>
                                                        <div class="field" id="chip_amount_field_payDebtModal_table_{{ $invoiceName }}_{{ $loop->index }}" style="display: none;">
                                                            <label class="field-label">Chip amount <span style="color:var(--rose);">*</span></label>
                                                            <input type="number" class="field-input" name="chip_amount"
                                                                min="0" max="{{ $availableChip }}" step="0.01"
                                                                placeholder="Enter chip amount…" id="chip_amount_input_payDebtModal_table_{{ $invoiceName }}_{{ $loop->index }}">
                                                            <div class="field-hint">Available chip: <strong>{{ number_format($availableChip) }} Tsh</strong></div>
                                                            <div class="field-hint" style="color: var(--emerald);">Cash portion will be: <strong id="cash_portion_payDebtModal_table_{{ $invoiceName }}_{{ $loop->index }}">{{ number_format($remaining) }}</strong> Tsh</div>
                                                        </div>
                                                        <div class="field">
                                                            <label class="field-label">Payment amount <span style="color:var(--rose);">*</span></label>
                                                            <input type="number" class="field-input" name="paymentAmount"
                                                                max="{{ $remaining }}" step="1" required
                                                                placeholder="Enter amount…" id="payment_amount_payDebtModal_table_{{ $invoiceName }}_{{ $loop->index }}"
                                                                oninput="updateCashPortion('payDebtModal_table_{{ $invoiceName }}_{{ $loop->index }}', {{ $availableChip }})">
                                                            <div class="field-hint">Maximum: {{ number_format($remaining) }} Tsh</div>
                                                        </div>
                                                        <div class="field">
                                                            <label class="field-label">Payment date</label>
                                                            <input type="date" class="field-input" name="payment_date" value="{{ date('Y-m-d') }}" required>
                                                        </div>
                                                        <input type="hidden" name="invoiceName" value="{{ $invoiceName }}">
                                                        <input type="hidden" name="shopName"    value="{{ $shopName }}">
                                                    </div>
                                                    <div class="modal-footer-custom">
                                                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn-confirm">
                                                            <i class="bi bi-check-circle-fill"></i> Confirm payment
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Delete Modal for Table View --}}
                                    @if(canUser('delete_orders'))
                                    <div class="modal fade" id="deleteModal_table_{{ $invoiceName }}_{{ $loop->index }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-top">
                                                    <div class="modal-top-left">
                                                        <div class="modal-top-icon" style="background:var(--rose);"><i class="bi bi-exclamation-triangle"></i></div>
                                                        <h5>Confirm Delete</h5>
                                                    </div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ url('deleteOrder') }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="field">
                                                            <p>Are you sure you want to delete invoice <strong>#{{ $invoiceName }}</strong>?</p>
                                                            <p style="font-size:12px; color:var(--rose); margin-top:10px;">
                                                                <i class="bi bi-info-circle"></i> This will permanently delete all items in this order and restore product stock.
                                                            </p>
                                                        </div>
                                                        <input type="hidden" name="orderId" value="{{ $invoiceName }}">
                                                    </div>
                                                    <div class="modal-footer-custom">
                                                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn-confirm" style="background:var(--rose);">
                                                            <i class="bi bi-trash"></i> Delete Invoice
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <tr class="invoice-details-row" id="details-{{ $invoiceName }}" style="display:none;">
                                        <td colspan="8" style="padding:0; border:none;">
                                            <div style="background:var(--slate-50); padding:1rem 1.5rem; border-bottom:1.5px solid var(--slate-200);">
                                                <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap; margin-bottom:0.75rem;">
                                                    <i class="bi bi-receipt" style="color:var(--navy); font-size:14px;"></i>
                                                    <span style="font-size:13px; font-weight:700; color:var(--navy);">Invoice #{{ $invoiceName }} — Items</span>
                                                    <span class="badge-unpaid" style="font-size:11px;"><i class="bi bi-box-seam"></i> {{ $itemCount }} {{ Str::plural('item', $itemCount) }}</span>
                                                </div>
                                                <div class="card-tbl-wrap" style="background:var(--white); border:1px solid var(--slate-200); border-radius:var(--r); overflow:hidden;">
                                                        <table class="card-tbl" style="margin-bottom:0;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width:35%;">Product</th>
                                                                    <th style="width:15%; text-align:center;">Qty</th>
                                                                    <th style="width:20%; text-align:right;">Unit price</th>
                                                                    <th style="width:20%; text-align:right;">Total</th>
                                                                    <th style="width:10%; text-align:center;">Action</th>
                                                                </tr>
                                                            </thead>
                                                        <tbody>
                                                            @foreach($items as $product)
                                                            <tr>
                                                                <td style="font-weight:600; color:var(--slate-800);">
                                                                    {{ $product->name01 ?? $product->productId ?? 'N/A' }}
                                                                </td>
                                                                <td style="text-align:center;">
                                                                    <span class="qty-pill">×{{ $product->pQuantity ?? 0 }}</span>
                                                                </td>
                                                                <td style="text-align:right;  font-size:12.5px;">
                                                                    {{ number_format($product->productPrice ?? 0) }}
                                                                </td>
                                                            <td style="text-align:right;  font-weight:600; color:var(--emerald);">
                                                                {{ number_format($product->totalPrice) }}
                                                            </td>
                                                            <td style="text-align:center;">
                                                                <div style="display:flex; gap:3px; justify-content:center; flex-wrap:wrap;">
                                                                    @if(canUser('pay_debts'))
                                                                    <button type="button" class="btn-edit"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editProductGlobalModal"
                                                                        onclick="openEditDebtProductModal(this)"
                                                                        data-product-id="{{ $product->id }}"
                                                                        data-product-name="{{ $product->name01 ?? $product->productId ?? '' }}"
                                                                        data-qty="{{ $product->pQuantity ?? 0 }}"
                                                                        data-price="{{ $product->productPrice ?? 0 }}"
                                                                        data-total="{{ $product->totalPrice }}"
                                                                        data-order-name="{{ $product->orderName }}"
                                                                        data-order-id="{{ $product->order_id }}"
                                                                        data-shop="{{ $shopName }}"
                                                                        data-payment-info="{{ json_encode($paymentInfo ?? ['paid'=>0,'remaining'=>$product->totalPrice]) }}"
                                                                        style="padding:4px 8px; font-size:11px;">
                                                                        <i class="bi bi-pencil"></i>
                                                                    </button>
                                                                    @endif
                                                                    @if(canUser('delete_orders'))
                                                                    <form action="{{ url('deleteDebtProduct') }}" method="POST"
                                                                        onsubmit="return confirm('Delete {{ $product->name01 ?? $product->productId }} from this invoice? Stock will be restored.');"
                                                                        style="display:inline;">
                                                                        @csrf
                                                                        <input type="hidden" name="productId" value="{{ $product->id }}">
                                                                        <input type="hidden" name="shopName" value="{{ $shopName }}">
                                                                        <input type="hidden" name="orderId" value="{{ $product->order_id }}">
                                                                        <button type="submit" class="btn-delete"
                                                                            style="padding:4px 8px; font-size:11px;">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>
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
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="empty-state" style="border:none; border-radius:0;">
                                <i class="bi bi-inbox"></i>
                                <h4>No invoices found</h4>
                                <p>No debt invoices match the selected filters</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

        </div>
    </main>

<script>
    function setView(v) {
        document.getElementById('cardView').style.display  = v === 'card'  ? 'block' : 'none';
        document.getElementById('tableView').style.display = v === 'table' ? 'block' : 'none';
        document.getElementById('cardViewBtn').classList.toggle('active',  v === 'card');
        document.getElementById('tableViewBtn').classList.toggle('active', v === 'table');
        localStorage.setItem('debtProdView', v);
    }

    // Expand/collapse invoice details in table view
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.invoice-row');
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                // Don't toggle if clicking on a button
                if (e.target.closest('button')) {
                    return;
                }
                const invoiceName = this.getAttribute('data-invoice');
                const detailsRow = document.getElementById('details-' + invoiceName);
                const chevron = this.querySelector('i.bi-chevron-right');
                
                if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                    // Close all other rows first
                    document.querySelectorAll('.invoice-details-row').forEach(r => {
                        r.style.display = 'none';
                        const otherChevron = r.previousElementSibling.querySelector('i.bi-chevron-right');
                        if (otherChevron) {
                            otherChevron.style.transform = 'rotate(0deg)';
                        }
                    });
                    // Open this row
                    detailsRow.style.display = 'table-row';
                    chevron.style.transform = 'rotate(90deg)';
                } else {
                    // Close this row
                    detailsRow.style.display = 'none';
                    chevron.style.transform = 'rotate(0deg)';
                }
            });
        });
    });

    const saved = localStorage.getItem('debtProdView') || 'table';
    setView(saved);

    // Open and populate the global edit product modal
    function openEditDebtProductModal(btn) {
        const modal    = document.getElementById('editProductGlobalModal');
        const product  = btn.getAttribute('data-product-name') || 'Product';
        const qty      = parseInt(btn.getAttribute('data-qty')) || 0;
        const price    = parseFloat(btn.getAttribute('data-price')) || 0;
        const total    = parseFloat(btn.getAttribute('data-total')) || 0;

        document.getElementById('editModalProductName').value  = product;
        document.getElementById('editModalQty').value          = qty;
        document.getElementById('editModalPrice').value        = price;
        document.getElementById('editModalProductId').value    = btn.getAttribute('data-product-id');
        document.getElementById('editModalOrderId').value      = btn.getAttribute('data-order-id');
        document.getElementById('editModalShopName').value     = btn.getAttribute('data-shop');
        document.getElementById('editModalNewTotal').textContent = (qty * price).toLocaleString('en-US');

        modal.addEventListener('shown.bs.modal', updateEditModalTotal);
    }

    function updateEditModalTotal() {
        const qtyEl   = document.getElementById('editModalQty');
        const priceEl = document.getElementById('editModalPrice');
        const span    = document.getElementById('editModalNewTotal');
        if (!qtyEl || !priceEl) return;

        function recalc() {
            const q = parseInt(qtyEl.value) || 0;
            const p = parseFloat(priceEl.value) || 0;
            span.textContent = (q * p).toLocaleString('en-US');
        }
        qtyEl.addEventListener('input', recalc);
        priceEl.addEventListener('input', recalc);
    }

    // Payment method toggle functions
    function toggleChipField(modalId, availableChip) {
        const paymentMethod = document.getElementById('payment_method_' + modalId);
        const chipAmountField = document.getElementById('chip_amount_field_' + modalId);
        const chipAmountInput = document.getElementById('chip_amount_input_' + modalId);
        const paymentAmountInput = document.getElementById('payment_amount_' + modalId);
        const cashPortionSpan = document.getElementById('cash_portion_' + modalId);
        
        if (paymentMethod.value === 'chip') {
            chipAmountField.style.display = 'block';
            chipAmountInput.required = true;
            // Update cash portion
            updateCashPortion(modalId, availableChip);
        } else {
            chipAmountField.style.display = 'none';
            chipAmountInput.required = false;
            chipAmountInput.value = '';
            // Reset cash portion display to full amount
            if (cashPortionSpan && paymentAmountInput.value) {
                cashPortionSpan.textContent = formatNumber(paymentAmountInput.value);
            }
        }
    }
    
    function updateCashPortion(modalId, availableChip) {
        const paymentMethod = document.getElementById('payment_method_' + modalId);
        const paymentAmountInput = document.getElementById('payment_amount_' + modalId);
        const chipAmountInput = document.getElementById('chip_amount_input_' + modalId);
        const cashPortionSpan = document.getElementById('cash_portion_' + modalId);
        
        if (!paymentAmountInput || !cashPortionSpan) return;
        
        const paymentAmount = parseFloat(paymentAmountInput.value) || 0;
        
        if (paymentMethod.value === 'chip') {
            const chipAmount = parseFloat(chipAmountInput.value) || 0;
            const cashPortion = Math.max(0, paymentAmount - chipAmount);
            cashPortionSpan.textContent = formatNumber(cashPortion);
        } else {
            cashPortionSpan.textContent = formatNumber(paymentAmount);
        }
    }
    
    function formatNumber(num) {
        return new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(num);
    }
</script>
</body>
</html>