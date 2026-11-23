<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} — Cleanup Debt Duplicates</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:         #0B1E3D;
            --navy-mid:     #112952;
            --navy-light:   #1A3A6B;
            --amber:        var(--theme-primary);
            --amber-pale:   #FEF3C7;
            --emerald:      #059669;
            --emerald-pale: #D1FAE5;
            --rose:         #E11D48;
            --rose-pale:    #FFE4E6;
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

        .alert { display:flex; align-items:center; justify-content:space-between; padding:.75rem 1rem; border-radius:var(--r); margin-bottom:1rem; font-size:13px; font-weight:500; }
        .alert-success { background:var(--emerald-pale); color:var(--emerald); border-left:3px solid var(--emerald); }
        .alert-danger  { background:var(--rose-pale);    color:var(--rose);    border-left:3px solid var(--rose); }
        .close-btn { background:none; border:none; cursor:pointer; color:inherit; font-size:16px; opacity:.6; }
        .close-btn:hover { opacity:1; }

        .breadcrumb { display:flex; align-items:center; gap:6px; margin-bottom:1.1rem; flex-wrap:wrap; }
        .bc-link { display:inline-flex; align-items:center; gap:4px; font-size:12px; color:var(--slate-400); text-decoration:none; transition:color .15s; }
        .bc-link:hover { color:var(--navy); }
        .bc-sep { font-size:12px; color:var(--slate-300); }
        .bc-cur { font-size:12px; font-weight:600; color:var(--slate-700); }

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

        .btn-amber { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:var(--r); background:var(--amber); color:var(--navy);  font-size:13px; font-weight:700; border:none; cursor:pointer; box-shadow:0 3px 14px rgba(245,158,11,.35); transition:all .18s; text-decoration:none; }
        .btn-amber:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(245,158,11,.45); color:var(--navy); }
        .btn-ghost { display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:var(--r); background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.16); color:rgba(255,255,255,.75);  font-size:13px; font-weight:500; cursor:pointer; transition:all .15s; text-decoration:none; }
        .btn-ghost:hover { background:rgba(255,255,255,.14); color:var(--white); }

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

        .badge-dup { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; background:var(--rose-pale); color:var(--rose); }
        .badge-keep { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; background:var(--emerald-pale); color:var(--emerald); }

        .summary-card { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-lg); padding:1rem 1.2rem; margin-bottom:1.25rem; box-shadow:0 1px 4px rgba(11,30,61,.05); }
        .summary-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
        .summary-item { text-align:center; }
        .summary-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--slate-400); margin-bottom:4px; }
        .summary-value { font-size:18px; font-weight:600; color:var(--navy); }
        .summary-value.red { color:var(--rose); }
        .summary-value.green { color:var(--emerald); }

        .empty-state { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-xl); text-align:center; padding:4rem 1.5rem; color:var(--slate-400); box-shadow:0 1px 4px rgba(11,30,61,.05); }
        .empty-state i { font-size:2.5rem; display:block; margin-bottom:.75rem; opacity:.3; }
        .empty-state h4 { font-size:15px; font-weight:600; color:var(--slate-600); margin-bottom:5px; }
        .empty-state p  { font-size:13px; }

        @media(max-width:768px) { .wrap{padding:1rem;} .summary-grid{grid-template-columns:1fr;} }
    </style>
</head>
<body>
        @include("sidenav")

        <main class="main-content">
            <div class="wrap">

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

                <div class="breadcrumb au au1">
                    <a href="{{ url('shopInvoices') }}" class="bc-link"><i class="bi bi-shop"></i> Shops</a>
                    <span class="bc-sep">/</span>
                    <a href="{{ url('shopDebtors/' . urlencode($shopName)) }}" class="bc-link">{{ $shopName }}</a>
                    <span class="bc-sep">/</span>
                    <span class="bc-cur">Cleanup Duplicates</span>
                </div>

                <div class="pg-header au au1">
                    <div class="pg-left">
                        <div class="header-icon"><i class="bi bi-bug"></i></div>
                        <div class="pg-title-text">
                            <h1>Cleanup Debt Duplicates</h1>
                            <p>Find and remove duplicate order items by date range</p>
                        </div>
                    </div>
                    <div class="pg-right">
                        <a href="{{ url('shopDebtors/' . urlencode($shopName)) }}" class="btn-ghost">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="filter-card au au2">
                    <form action="{{ url('cleanupDebtDuplicates') }}" method="post">
                        @csrf
                        <input type="hidden" name="shop" value="{{ $shopName }}">
                        <div class="filter-row">
                            <div class="filter-group">
                                <label class="filter-label">From</label>
                                <input type="date" name="start_date" class="filter-ctrl" value="{{ $startDate ?? '' }}" required>
                            </div>
                            <div class="filter-group">
                                <label class="filter-label">To</label>
                                <input type="date" name="end_date" class="filter-ctrl" value="{{ $endDate ?? '' }}" required>
                            </div>
                            <div class="filter-actions">
                                <button type="submit" class="btn-filter"><i class="bi bi-search"></i> Find Duplicates</button>
                            </div>
                        </div>
                    </form>
                </div>

                @if(isset($duplicates) && count($duplicates) > 0)
                <div class="summary-card au au3">
                    <div class="summary-grid">
                        <div class="summary-item">
                            <div class="summary-label">Duplicate Invoices</div>
                            <div class="summary-value red">{{ count($duplicates) }}</div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-label">Duplicate Items</div>
                            <div class="summary-value red">{{ $totalDuplicates }}</div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-label">Action</div>
                            <div class="summary-value" style="font-size:14px;">
                                @if(!$confirmed)
                                <form action="{{ url('cleanupDebtDuplicates') }}" method="post" style="display:inline;" onsubmit="return confirm('This will permanently delete {{ $totalDuplicates }} duplicate item(s) and restore product stock. Are you sure?');">
                                    @csrf
                                    <input type="hidden" name="shop" value="{{ $shopName }}">
                                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                                    <input type="hidden" name="confirmed" value="1">
                                    <button type="submit" class="btn-confirm" style="background:var(--rose); padding:6px 14px; font-size:12px;">
                                        <i class="bi bi-trash"></i> Delete All Duplicates
                                    </button>
                                </form>
                                @else
                                <span style="color:var(--emerald); font-size:13px;">Cleanup complete</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="tbl-panel au au4">
                    <div class="tbl-panel-head">
                        <div class="tbl-panel-title">
                            <i class="bi bi-list-ul" style="color:var(--navy-light);"></i>
                            Duplicate Items Found
                            @if(isset($duplicates))
                            <span class="result-pill">{{ count($duplicates) }} invoices</span>
                            @endif
                        </div>
                    </div>
                    <div class="tbl-scroll">
                        @if(isset($duplicates) && count($duplicates) > 0)
                        <table class="main-tbl">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Product ID</th>
                                    <th>Count</th>
                                    <th>Keep ID</th>
                                    <th>Delete IDs</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($duplicates as $dup)
                                <tr>
                                    <td><span class="inv-pill">#{{ $dup['orderName'] }}</span></td>
                                    <td>{{ $dup['productId'] }}</td>
                                    <td><span class="badge-dup">{{ $dup['count'] }} items</span></td>
                                    <td><span class="badge-keep">Keep {{ $dup['keep_id'] }}</span></td>
                                    <td style="font-size:11px; color:var(--rose);">{{ implode(', ', $dup['duplicate_ids']) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @elseif(isset($startDate) && isset($endDate))
                        <div class="empty-state" style="border:none; border-radius:0;">
                            <i class="bi bi-check-circle"></i>
                            <h4>No duplicates found</h4>
                            <p>No duplicate order items were found for the selected date range.</p>
                        </div>
                        @else
                        <div class="empty-state" style="border:none; border-radius:0;">
                            <i class="bi bi-calendar-range"></i>
                            <h4>Select a date range</h4>
                            <p>Choose a start and end date above to scan for duplicates.</p>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </main>

</body>
</html>
