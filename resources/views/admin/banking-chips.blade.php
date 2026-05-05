<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} — Banking Chips</title>
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
        @media(max-width:1000px){ .metrics-row { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:500px){ .metrics-row { grid-template-columns:1fr; } }

        .metric-card { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-lg); padding:1rem 1.2rem; box-shadow:0 1px 4px rgba(11,30,61,.05); position:relative; overflow:hidden; transition:transform .2s, box-shadow .2s; }
        .metric-card::after { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:var(--mc); }
        .mc-navy    { --mc:var(--navy); }
        .mc-sky     { --mc:var(--sky); }
        .mc-emerald { --mc:var(--emerald); }
        .mc-rose    { --mc:var(--rose); }
        .metric-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(11,30,61,.1); }
        .metric-inner { display:flex; align-items:center; gap:12px; }
        .metric-icon { width:40px; height:40px; border-radius:var(--r); flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:17px; }
        .mi-navy    { background:rgba(11,30,61,.08); color:var(--navy); }
        .mi-sky     { background:var(--sky-pale);    color:var(--sky); }
        .mi-emerald { background:var(--emerald-pale); color:var(--emerald); }
        .mi-rose    { background:var(--rose-pale);   color:var(--rose); }
        .metric-label { font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--slate-400); margin-bottom:4px; }
        .metric-value { font-family:var(--mono); font-size:22px; font-weight:500; color:var(--navy); letter-spacing:-.5px; line-height:1; }

        /* ══ TABLE ══ */
        .tbl-wrap { overflow-x:auto; background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-xl); box-shadow:0 1px 4px rgba(11,30,61,.05); }
        table { width:100%; border-collapse:collapse; font-size:13px; }
        thead th { background:var(--navy); color:rgba(255,255,255,.65); font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; padding:10px 16px; white-space:nowrap; border:none; text-align:left; }
        thead th:last-child { text-align:right; }
        tbody tr { border-bottom:1px solid var(--slate-100); transition:background .12s; }
        tbody tr:last-child { border-bottom:none; }
        tbody tr:hover td { background:#F7F9FF; }
        td { padding:11px 16px; vertical-align:middle; }
        td:last-child { text-align:right; }

        /* Badges */
        .badge-primary  { display:inline-flex; align-items:center; gap:4px; padding:2px 9px; border-radius:20px; font-size:10.5px; font-weight:700; background:var(--navy-mid); color:var(--white); }
        .badge-add      { display:inline-flex; align-items:center; gap:4px; padding:2px 9px; border-radius:20px; font-size:10.5px; font-weight:700; background:var(--slate-100); color:var(--slate-500); }

        /* Action buttons */
        .action-btns { display:flex; gap:5px; justify-content:flex-end; flex-wrap:nowrap; }
        .act-btn { width:30px; height:30px; border-radius:var(--r); border:1.5px solid; background:transparent; display:flex; align-items:center; justify-content:center; font-size:12.5px; cursor:pointer; transition:all .15s; flex-shrink:0; }
        .ab-edit  { border-color:var(--amber);   color:var(--amber); }
        .ab-edit:hover  { background:var(--amber-pale); }
        .ab-del   { border-color:var(--rose);    color:var(--rose); }
        .ab-del:hover   { background:var(--rose-pale); }

        /* ══ FILTER BAR ══ */
        .filter-bar { display:flex; gap:10px; margin-bottom:1rem; flex-wrap:wrap; }
        .filter-group { display:flex; align-items:center; gap:6px; }
        .filter-label { font-size:12px; font-weight:600; color:var(--slate-600); }
        .filter-select { padding:7px 10px; border:1.5px solid var(--slate-200); border-radius:var(--r); font-family:var(--font); font-size:13px; color:var(--slate-800); background:var(--white); outline:none; min-width:150px; }
        .filter-select:focus { border-color:var(--navy-light); box-shadow:0 0 0 3px rgba(26,58,107,.1); }

        /* ══ MODAL ══ */
        .modal-content { border:none; border-radius:var(--r-xl); overflow:hidden; box-shadow:0 20px 60px rgba(11,30,61,.2); }
        .modal-top { background:var(--navy); padding:1.15rem 1.4rem; display:flex; align-items:center; justify-content:space-between; }
        .modal-top-left { display:flex; align-items:center; gap:10px; }
        .modal-top-icon { width:32px; height:32px; border-radius:var(--r); background:var(--amber); display:flex; align-items:center; justify-content:center; color:var(--navy); font-size:14px; }
        .modal-top h5 { font-size:15px; font-weight:700; color:var(--white); margin:0; }
        .modal-top .btn-close { filter:invert(1) brightness(.75); }
        .modal-body { padding:1.4rem; }

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

        .modal-footer-custom { padding:1rem 1.4rem; border-top:1.5px solid var(--slate-200); display:flex; justify-content:flex-end; gap:8px; }
        .btn-cancel { padding:9px 18px; border-radius:var(--r); border:1.5px solid var(--slate-200); background:transparent; font-family:var(--font); font-size:13px; color:var(--slate-600); cursor:pointer; transition:all .15s; }
        .btn-cancel:hover { background:var(--slate-100); }
        .btn-save { padding:9px 22px; border-radius:var(--r); background:var(--amber); color:var(--navy); border:none; font-family:var(--font); font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:0 3px 14px rgba(245,158,11,.3); transition:all .18s; }
        .btn-save:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(245,158,11,.4); }

        @media(max-width:768px){ .wrap{padding:1rem;} }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @include("user/sidenav")

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3">
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
                        <a href="{{ url()->previous() }}" class="back-btn d-print-none">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                        <div class="header-icon"><i class="bi bi-cpu"></i></div>
                        <div class="pg-title-text">
                            <h1>Banking Chips</h1>
                            <p>Manage chip balances for shops</p>
                        </div>
                    </div>
                    <div class="pg-right">
                        @if(canUser("add_banking_transfer"))
                        <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#addChipModal">
                            <i class="bi bi-plus-lg"></i> Add Chip Entry
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Metrics --}}
                <div class="metrics-row au au2">
                    <div class="metric-card mc-navy">
                        <div class="metric-inner">
                            <div class="metric-icon mi-navy"><i class="bi bi-cpu"></i></div>
                            <div><div class="metric-label">Total Chip Entries</div><div class="metric-value">{{ $chips->count() }}</div></div>
                        </div>
                    </div>
                    <div class="metric-card mc-emerald">
                        <div class="metric-inner">
                            <div class="metric-icon mi-emerald"><i class="bi bi-graph-up"></i></div>
                            <div><div class="metric-label">Total Available Chip</div><div class="metric-value">{{ number_format($chips->sum('available_chip')) }} Tsh</div></div>
                        </div>
                    </div>
                    <div class="metric-card mc-amber">
                        <div class="metric-inner">
                            <div class="metric-icon mi-amber"><i class="bi bi-bank2"></i></div>
                            <div><div class="metric-label">Shops with Chip</div><div class="metric-value">{{ $chips->pluck('shop_id')->unique()->count() }}</div></div>
                        </div>
                    </div>
                    <div class="metric-card mc-rose">
                        <div class="metric-inner">
                            <div class="metric-icon mi-rose"><i class="bi bi-cash-stack"></i></div>
                            <div><div class="metric-label">Total Deposit</div><div class="metric-value">{{ number_format($totalDeposit) }} Tsh</div></div>
                        </div>
                    </div>
                </div>

                {{-- Filter Bar --}}
                <div class="filter-bar au au3">
                    <div class="filter-group">
                        <label class="filter-label">Shop:</label>
                        <select class="filter-select" id="filterShop" onchange="applyFilters()">
                            <option value="">All Shops</option>
                            @foreach($shops as $shop)
                            <option value="{{ $shop->id }}"
                                {{ (request('shop_id') == $shop->id) || (!request()->has('shop_id') && $loop->first) ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">From:</label>
                        <input type="date" class="filter-select" id="filterFrom" value="{{ request('date_from') }}" onchange="applyFilters()">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">To:</label>
                        <input type="date" class="filter-select" id="filterTo" value="{{ request('date_to') }}" onchange="applyFilters()">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Sort by:</label>
                        <select class="filter-select" id="filterSort" onchange="applyFilters()">
                            <option value="transfer_date_desc" {{ request('sort_by') == 'transfer_date' && request('sort_direction') == 'desc' ? 'selected' : '' }}>Date (Newest)</option>
                            <option value="transfer_date_asc" {{ request('sort_by') == 'transfer_date' && request('sort_direction') == 'asc' ? 'selected' : '' }}>Date (Oldest)</option>
                            <option value="chip_amount_desc" {{ request('sort_by') == 'chip_amount' && request('sort_direction') == 'desc' ? 'selected' : '' }}>Amount (High to Low)</option>
                            <option value="chip_amount_asc" {{ request('sort_by') == 'chip_amount' && request('sort_direction') == 'asc' ? 'selected' : '' }}>Amount (Low to High)</option>
                        </select>
                    </div>
                </div>

                {{-- Table --}}
                <div class="tbl-wrap au au4">
                    @if($chips->isEmpty())
                    <div class="empty-state" style="padding:3.5rem 1.5rem;">
                        <i class="bi bi-cpu" style="font-size:2.5rem; display:block; margin-bottom:.75rem; opacity:.3;"></i>
                        <h4 style="font-size:15px; font-weight:600; color:var(--slate-600); margin-bottom:5px;">No chip entries yet</h4>
                        <p style="font-size:13px; margin-bottom:1.25rem;">Add chip entries to track available chip balances for shops</p>
                        @if(canUser("add_banking_transfer"))
                        <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#addChipModal">
                            <i class="bi bi-plus-lg"></i> Add Chip Entry
                        </button>
                        @endif
                    </div>
                    @else
                    <table>
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Date</th>
                                <th>Shop</th>
                                <th>Chip Amount</th>
                                <th>Available Chip</th>
                                <th>Created By</th>
                                <th style="text-align:right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chips as $i => $chip)
                            <tr data-search="{{ strtolower($chip->shop->name ?? '') }} {{ strtolower($chip->created_by ?? '') }}">
                                <td style="font-size:11.5px; color:var(--slate-400); font-family:var(--mono);">{{ $i+1 }}</td>
                                <td style="font-weight:600;">{{ $chip->transfer_date->format('Y-m-d') }}</td>
                                <td>
                                    <div style="font-weight:600; color:var(--navy);">{{ $chip->shop->name ?? 'Unknown Shop' }}</div>
                                    <div style="font-size:11.5px; color:var(--slate-400);">ID: {{ $chip->shop_id }}</div>
                                </td>
                                <td style="font-family:var(--mono); font-weight:600; color:var(--emerald);">
                                    +{{ number_format($chip->chip_amount, 2) }} Tsh
                                </td>
                                <td style="font-family:var(--mono); font-weight:700; color:var(--navy);">
                                    {{ number_format($chip->available_chip, 2) }} Tsh
                                </td>
                                <td style="color:var(--slate-500);">{{ $chip->created_by ?? '—' }}</td>
                                <td>
                                    <div class="action-btns">
                                        @if(canUser("add_banking_transfer"))
                                        <button class="act-btn ab-edit" data-bs-toggle="modal" data-bs-target="#editChip{{ $chip->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                        @endif
                                        @if(canUser("delete_banking_transfer"))
                                        <form action="/admin/banking-chip/delete/{{ $chip->id }}" method="POST" style="display:contents;" onsubmit="return confirm('Delete this chip entry? This will recalculate all subsequent entries.');">
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
                    @endif
                </div>

            </div>
        </main>
    </div>
</div>

{{-- ══════════════════════════════════════
     ADD CHIP MODAL
═════════════════════════════════════ --}}
@if(canUser("add_banking_transfer"))
<div class="modal fade" id="addChipModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-top">
                <div class="modal-top-left">
                    <div class="modal-top-icon"><i class="bi bi-plus-lg"></i></div>
                    <h5>Add Chip Entry</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/banking-chip/store" method="post">
                @csrf
                <div class="modal-body">
                    <div class="field-row" style="margin-bottom:12px;">
                        <div class="field">
                            <label class="field-label">Shop <span class="req">*</span></label>
                            <select class="field-input" name="shop_id" required>
                                <option value="">Select shop...</option>
                                @foreach($shops as $shop)
                                <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label class="field-label">Chip Amount (Tsh) <span class="req">*</span></label>
                            <input type="number" step="0.01" min="0.01" class="field-input" name="chip_amount" placeholder="Enter chip amount" required>
                        </div>
                    </div>
                    <div class="field" style="margin-bottom:12px;">
                        <label class="field-label">Transfer Date <span class="req">*</span></label>
                        <input type="date" class="field-input" name="transfer_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="field" style="margin-bottom:12px;">
                        <label class="field-label">Notes</label>
                        <textarea class="field-input" name="description" placeholder="Optional description"></textarea>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Save Chip Entry</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════
     EDIT CHIP MODALS
═════════════════════════════════════ --}}
@if(canUser("add_banking_transfer"))
@foreach($chips as $chip)
<div class="modal fade" id="editChip{{ $chip->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-top">
                <div class="modal-top-left">
                    <div class="modal-top-icon"><i class="bi bi-pencil-fill"></i></div>
                    <h5>Edit Chip Entry</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/banking-chip/update/{{ $chip->id }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="field-row" style="margin-bottom:12px;">
                        <div class="field">
                            <label class="field-label">Shop</label>
                            <input type="text" class="field-input" value="{{ $chip->shop->name ?? 'Unknown' }}" disabled style="background:var(--slate-100);">
                        </div>
                        <div class="field">
                            <label class="field-label">Chip Amount (Tsh) <span class="req">*</span></label>
                            <input type="number" step="0.01" min="0.01" class="field-input" name="chip_amount" value="{{ $chip->chip_amount }}" required>
                        </div>
                    </div>
                    <div class="field" style="margin-bottom:12px;">
                        <label class="field-label">Transfer Date <span class="req">*</span></label>
                        <input type="date" class="field-input" name="transfer_date" value="{{ $chip->transfer_date->format('Y-m-d') }}" required>
                    </div>
                    <div class="alert alert-info" style="background:var(--sky-pale); color:var(--sky); border-left:3px solid var(--sky); font-size:12px; margin-bottom:0;">
                        <i class="bi bi-info-circle-fill" style="margin-right:6px;"></i>
                        Editing this entry will automatically recalculate available chip for all subsequent entries.
                    </div>
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

<script>
    function applyFilters() {
        const shop = document.getElementById('filterShop').value;
        const from = document.getElementById('filterFrom').value;
        const to = document.getElementById('filterTo').value;
        const sort = document.getElementById('filterSort').value;
        
        const params = new URLSearchParams(window.location.search);
        if (shop) params.set('shop_id', shop); else params.delete('shop_id');
        if (from) params.set('date_from', from); else params.delete('date_from');
        if (to) params.set('date_to', to); else params.delete('date_to');
        
        if (sort) {
            const [sortBy, sortDir] = sort.split('_');
            params.set('sort_by', sortBy);
            params.set('sort_direction', sortDir);
        }
        
        window.location.href = '{{ url("/admin/banking-chips") }}?' + params.toString();
    }
</script>
</body>
</html>