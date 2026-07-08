<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} — @lang('messages.supplier_credit_management')</title>
    @include("links")
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
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
        .au1 { animation-delay:0.04s; } .au2 { animation-delay:0.10s; }
        .au3 { animation-delay:0.16s; } .au4 { animation-delay:0.22s; }
        .au5 { animation-delay:0.28s; }

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
        .header-icon { width:40px; height:40px; border-radius:var(--r); background:var(--amber); display:flex; align-items:center; justify-content:center; font-size:18px; color:var(--navy); flex-shrink:0; }
        .pg-title-text h1 { font-size:16px; font-weight:700; color:var(--white); letter-spacing:-.2px; }
        .pg-title-text p  { font-size:12px; color:rgba(255,255,255,.45); margin-top:1px; }
        .pg-right { display:flex; gap:8px; align-items:center; position:relative; z-index:1; flex-wrap:wrap; }

        .btn-ghost { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:var(--r); background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.16); color:rgba(255,255,255,.75); font-family:var(--font); font-size:12.5px; font-weight:500; cursor:pointer; transition:all .15s; }
        .btn-ghost:hover { background:rgba(255,255,255,.14); color:var(--white); }

        /* ══ TOOLBAR ══ */
        .toolbar {
            background:var(--white); border:1.5px solid var(--slate-200);
            border-radius:var(--r-lg); padding:.9rem 1.2rem;
            margin-bottom:1.25rem; display:flex; align-items:center;
            gap:10px; flex-wrap:wrap;
            box-shadow:0 1px 4px rgba(11,30,61,.05);
        }
        .date-wrap { display:flex; align-items:center; gap:8px; flex:1; min-width:200px; border:1.5px solid var(--slate-200); border-radius:var(--r); padding:7px 12px; transition:border-color .18s, box-shadow .18s; }
        .date-wrap:focus-within { border-color:var(--navy-light); box-shadow:0 0 0 3px rgba(26,58,107,.1); }
        .date-wrap i { color:var(--slate-400); font-size:14px; flex-shrink:0; }
        .date-wrap input { border:none; outline:none; font-family:var(--font); font-size:13px; color:var(--slate-800); background:transparent; flex:1; }
        .btn-filter-sm { display:inline-flex; align-items:center; gap:5px; padding:8px 14px; border-radius:var(--r); background:var(--navy); color:var(--white); font-family:var(--font); font-size:13px; font-weight:600; border:none; cursor:pointer; transition:all .15s; white-space:nowrap; }
        .btn-filter-sm:hover { background:var(--navy-light); }
        .btn-clear-filter { display:inline-flex; align-items:center; gap:5px; padding:8px 12px; border-radius:var(--r); background:var(--rose-pale); color:var(--rose); border:none; font-family:var(--font); font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; transition:all .15s; white-space:nowrap; }
        .btn-clear-filter:hover { background:#fecdd3; color:var(--rose); }

        .date-display { font-size:12px; color:var(--slate-400); font-family:var(--mono); margin-left:auto; }

        /* ══ METRICS ══ */
        .metrics-grid { display:grid; grid-template-columns:repeat(4, minmax(0,1fr)); gap:1rem; margin-bottom:1.4rem; }
        @media(max-width:900px){ .metrics-grid { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:480px){ .metrics-grid { grid-template-columns:1fr; } }

        .metric-card { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-lg); padding:1.1rem 1.2rem; box-shadow:0 1px 4px rgba(11,30,61,.05); position:relative; overflow:hidden; transition:transform .2s, box-shadow .2s; }
        .metric-card::after { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:var(--mc); }
        .mc-navy    { --mc:var(--navy); }
        .mc-rose    { --mc:var(--rose); }
        .mc-emerald { --mc:var(--emerald); }
        .mc-amber   { --mc:var(--amber); }
        .metric-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(11,30,61,.1); }
        .metric-inner { display:flex; align-items:center; gap:12px; }
        .metric-icon { width:42px; height:42px; border-radius:var(--r); flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:17px; }
        .mi-navy    { background:rgba(11,30,61,.08); color:var(--navy); }
        .mi-rose    { background:var(--rose-pale);    color:var(--rose); }
        .mi-emerald { background:var(--emerald-pale); color:var(--emerald); }
        .mi-amber   { background:var(--amber-pale);   color:#92400e; }
        .metric-label { font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--slate-400); margin-bottom:4px; }
        .metric-value { font-family:var(--mono); font-size:20px; font-weight:500; color:var(--navy); letter-spacing:-.5px; line-height:1; }
        .mv-red   { color:var(--rose); }
        .mv-green { color:var(--emerald); }

        /* ══ PANEL / TABLE ══ */
        .panel { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-xl); overflow:hidden; box-shadow:0 1px 4px rgba(11,30,61,.05); }
        .panel-head { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.4rem; border-bottom:1.5px solid var(--slate-200); background:var(--slate-50); gap:1rem; flex-wrap:wrap; }
        .panel-head-left { display:flex; align-items:center; gap:10px; }
        .panel-head-icon { width:30px; height:30px; border-radius:var(--r); background:rgba(11,30,61,.07); color:var(--navy-light); display:flex; align-items:center; justify-content:center; font-size:13px; }
        .panel-title { font-size:13.5px; font-weight:700; color:var(--navy); }
        .result-pill { font-size:11px; font-weight:600; font-family:var(--mono); background:var(--slate-200); color:var(--slate-600); padding:2px 9px; border-radius:20px; }

        /* Date group header */
        .date-group-row td { background:var(--slate-50); padding:8px 16px; border-top:1.5px solid var(--slate-200); border-bottom:1px solid var(--slate-200); }
        .date-group-label { display:flex; align-items:center; gap:7px; font-size:12px; font-weight:700; color:var(--navy); text-transform:uppercase; letter-spacing:.05em; }
        .date-group-label i { color:var(--amber); }

        /* Table */
        .tbl-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; font-size:13px; }
        thead th { background:var(--navy); color:rgba(255,255,255,.65); font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; padding:10px 16px; white-space:nowrap; border:none; text-align:left; }
        thead th:last-child { text-align:right; }
        tbody tr { border-bottom:1px solid var(--slate-100); transition:background .12s; }
        tbody tr:last-child:not(.date-group-row) { border-bottom:none; }
        tbody tr:not(.date-group-row):hover td { background:#F7F9FF; }
        td { padding:11px 16px; vertical-align:middle; }
        td:last-child { text-align:right; }

        /* Cell types */
        .idx-cell  { font-size:12px; color:var(--slate-400); font-family:var(--mono); }
        .sup-avatar { width:32px; height:32px; border-radius:50%; background:var(--navy-mid); color:rgba(255,255,255,.85); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0; }
        .sup-cell   { display:flex; align-items:center; gap:10px; }
        .sup-name   { font-weight:600; color:var(--navy); font-size:13px; }

        .mono-val   { font-family:var(--mono); font-size:12.5px; font-weight:500; }
        .credit-val { color:var(--rose); }
        .paid-val   { color:var(--emerald); }
        .remaining-val { font-weight:700; }

        /* Status badges */
        .badge-paid      { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; background:var(--emerald-pale); color:var(--emerald); }
        .badge-credit    { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; background:var(--rose-pale);    color:var(--rose); }
        .badge-completed { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; background:var(--emerald-pale); color:var(--emerald); }

        /* Action buttons */
        .action-btns { display:flex; gap:5px; justify-content:flex-end; flex-wrap:wrap; }
        .act-btn { display:inline-flex; align-items:center; gap:5px; padding:6px 13px; border-radius:var(--r); font-family:var(--font); font-size:12px; font-weight:600; cursor:pointer; border:1.5px solid; transition:all .15s; white-space:nowrap; background:transparent; }
        .ab-pay  { border-color:var(--emerald); color:var(--emerald); }
        .ab-pay:hover  { background:var(--emerald); color:var(--white); }
        .ab-view { border-color:var(--navy-light); color:var(--navy-light); }
        .ab-view:hover { background:var(--navy); color:var(--white); }

        /* Empty state */
        .empty-state { text-align:center; padding:4rem 1.5rem; color:var(--slate-400); }
        .empty-state i  { font-size:2.5rem; display:block; margin-bottom:.75rem; opacity:.3; }
        .empty-state h4 { font-size:15px; font-weight:600; color:var(--slate-600); margin-bottom:5px; }
        .empty-state p  { font-size:13px; }

        /* ══ MODAL ══ */
        .modal-content { border:none; border-radius:var(--r-xl); overflow:hidden; box-shadow:0 20px 60px rgba(11,30,61,.2); }
        .modal-top { background:var(--navy); padding:1.15rem 1.4rem; display:flex; align-items:center; justify-content:space-between; }
        .modal-top-left { display:flex; align-items:center; gap:10px; }
        .modal-top-icon { width:32px; height:32px; border-radius:var(--r); display:flex; align-items:center; justify-content:center; font-size:14px; }
        .mti-amber   { background:var(--amber);   color:var(--navy); }
        .mti-navy    { background:rgba(255,255,255,.12); color:var(--white); }
        .mti-rose    { background:var(--rose);    color:var(--white); }
        .modal-top h5 { font-size:15px; font-weight:700; color:var(--white); margin:0; }
        .modal-top .btn-close { filter:invert(1) brightness(.75); }
        .modal-body { padding:1.5rem 1.4rem; }

        .field { margin-bottom:12px; }
        .field:last-child { margin-bottom:0; }
        .field-label { display:block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--slate-500); margin-bottom:5px; }
        .field-label .req { color:var(--rose); }
        .field-input { width:100%; font-family:var(--font); font-size:13.5px; padding:9px 12px; border:1.5px solid var(--slate-200); border-radius:var(--r); background:var(--white); color:var(--slate-800); outline:none; transition:border-color .18s, box-shadow .18s; }
        .field-input:focus { border-color:var(--navy-light); box-shadow:0 0 0 3px rgba(26,58,107,.1); }
        .field-input.readonly { background:var(--slate-50); color:var(--slate-500); cursor:default; }
        .field-input::placeholder { color:var(--slate-400); }
        .field-hint { font-size:11px; color:var(--slate-400); margin-top:4px; }

        .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; background:var(--slate-50); border-radius:var(--r); padding:.9rem 1rem; margin-bottom:1rem; }
        .info-item label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--slate-400); display:block; margin-bottom:3px; }
        .info-item span  { font-size:14px; font-weight:600; font-family:var(--mono); color:var(--slate-800); }
        .info-item.big span { font-size:18px; color:var(--rose); }

        .modal-footer-custom { padding:1rem 1.4rem; border-top:1.5px solid var(--slate-200); display:flex; justify-content:flex-end; gap:8px; }
        .btn-cancel { padding:9px 18px; border-radius:var(--r); border:1.5px solid var(--slate-200); background:transparent; font-family:var(--font); font-size:13px; color:var(--slate-600); cursor:pointer; transition:all .15s; }
        .btn-cancel:hover { background:var(--slate-100); }
        .btn-confirm-pay { padding:9px 20px; border-radius:var(--r); background:var(--emerald); color:var(--white); border:none; font-family:var(--font); font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:0 3px 14px rgba(5,150,105,.25); transition:all .18s; }
        .btn-confirm-pay:hover { background:#047857; transform:translateY(-1px); }
        .btn-confirm-del { padding:9px 20px; border-radius:var(--r); background:var(--rose); color:var(--white); border:none; font-family:var(--font); font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; transition:all .18s; }
        .btn-confirm-del:hover { background:#be123c; transform:translateY(-1px); }

        /* Products modal table */
        .modal-tbl { width:100%; border-collapse:collapse; font-size:13px; }
        .modal-tbl thead th { background:var(--navy); color:rgba(255,255,255,.65); font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; padding:8px 14px; border:none; text-align:left; }
        .modal-tbl tbody td { padding:10px 14px; border-bottom:1px solid var(--slate-100); vertical-align:middle; }
        .modal-tbl tbody tr:last-child td { border-bottom:none; }
        .modal-tbl tbody tr:hover td { background:var(--slate-50); }
        .modal-tbl-wrap { overflow-x:auto; }

        .modal-info-badge { display:inline-flex; align-items:center; gap:5px; margin-bottom:1rem; background:var(--navy-mid); color:rgba(255,255,255,.75); padding:5px 12px; border-radius:20px; font-size:12px; font-family:var(--mono); }

        .loading-state { text-align:center; padding:2.5rem; color:var(--slate-400); }
        .loading-state i { font-size:1.75rem; display:block; margin-bottom:.5rem; opacity:.4; }

        @media(max-width:768px){ .wrap{padding:1rem;} .action-btns{flex-direction:column;} }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @include("sidenav")

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
            <div class="wrap">

                {{-- Alerts --}}
                @if(session('success'))
                <div class="alert alert-success au au1">
                    <span><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</span>
                    <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger au au1">
                    <span><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</span>
                    <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
                </div>
                @endif

                {{-- Page header --}}
                <div class="pg-header au au1">
                    <div class="pg-left">
                        <div class="header-icon"><i class="bi bi-people-fill"></i></div>
                        <div class="pg-title-text">
                            <h1>Main Store Credit Management</h1>
                            <p>Track and manage outstanding supplier credits</p>
                        </div>
                    </div>
                    <div class="pg-right">
                        <button class="btn-ghost" onclick="window.print()">
                            <i class="bi bi-printer"></i> Print
                        </button>
                        <button class="btn-ghost">
                            <i class="bi bi-download"></i> Export
                        </button>
                    </div>
                </div>

                {{-- Toolbar with filters --}}
                <div class="toolbar au au2">
                    <form method="GET" action="{{ url()->current() }}" style="display:contents;">
              
                        {{-- Date range filter --}}
                        <div class="date-wrap" style="flex:0 0 320px;">
                            <i class="bi bi-calendar3"></i>
                            <input type="date" name="date_from"
                                   value="{{ request('date_from', date('Y-m-d')) }}"
                                   placeholder="From"
                                   title="From date">
                            <span style="color:var(--slate-300);">→</span>
                            <input type="date" name="date_to"
                                   value="{{ request('date_to', date('Y-m-d')) }}"
                                   placeholder="To"
                                   title="To date">
                        </div>

                        {{-- Filter/Clear buttons --}}
                        @if(request('date_from') || request('date_to') || request('shop'))
                            <a href="{{ url()->current() }}" class="btn-clear-filter">
                                <i class="bi bi-x-circle"></i> Clear all
                            </a>
                        @else
                            <button type="submit" class="btn-filter-sm">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        @endif
                    </form>
                    <a href="/main-credit" class="btn btn-filter-sm">
                                <i class="bi bi-money"></i> View Credits
                    </a>
                </div>


                {{-- Main table --}}
                <div class="panel au au4">
                    <div class="panel-head">
                        <div class="panel-head-left">
                            <div class="panel-head-icon"><i class="bi bi-table"></i></div>
                            <span class="panel-title">Payments</span>
                            <span class="result-pill">{{ $query->count() }} records</span>
                        </div>
                    </div>

                    <div class="tbl-wrap">
                        @if($query->isEmpty())
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <h4>No Payments found</h4>
                            <p>There are currently no outstanding supplier Payments orders</p>
                        </div>
                        @else
                        <table>
                            <thead>
                                <tr>
                                    <th width="4%">#</th>
                                    <th>Supplier</th>
                                    <th>Total paid</th>
                                    <th style="text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $rowIndex = 1; @endphp
                                @foreach ($query as $date => $suppliers)
                                    {{-- Date group row --}}
                                    <tr class="date-group-row">
                                        <td colspan="8">
                                            <div class="date-group-label">
                                                <i class="bi bi-calendar3"></i>
                                                {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}
                                                <span style="font-weight:400; color:var(--slate-400); margin-left:4px; font-family:var(--mono); font-size:11px;">
                                                    · {{ $suppliers->count() }} {{ Str::plural('order', $suppliers->count()) }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    @foreach ($query as $order)
                                    @php
                                    $name = DB::table('accounts')->where('id', $order->account)->value('name');

                                    @endphp
                                    <tr>
                                        <td class="idx-cell">{{ $rowIndex++ }}</td>
                                        <td>
                                            <div class="sup-cell">
                                                <span class="sup-name">{{ $name }}</span>
                                            </div>
                                        </td>
                                 
                                        <td><span class="mono-val">{{ number_format($order->amount) }}</span></td>
                                       
                                        <td>
                                            <div class="action-btns">
                                          
                                                <form action="dltSupPay" method="post">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $order->id }}">
                                                    <button type="submit" class="act-btn ab-view"
                                                    >
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

</body>
</html>