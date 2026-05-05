<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} - KPI Dashboard</title>
    @include('links')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --navy:          #0B1E3D;
            --navy-mid:      #112952;
            --navy-light:    #1A3A6B;
            --amber:         #F59E0B;
            --amber-dark:    #D97706;
            --amber-pale:    #FEF3C7;
            --emerald:       #059669;
            --emerald-pale:  #D1FAE5;
            --rose:          #E11D48;
            --rose-pale:     #FFE4E6;
            --violet:        #7C3AED;
            --violet-pale:   #EDE9FE;
            --sky:           #0284C7;
            --sky-pale:      #E0F2FE;
            --gold:          #F59E0B;
            --silver:        #94A3B8;
            --bronze:        #CD7F32;
            --slate-50:      #F8FAFC;
            --slate-100:     #F1F5F9;
            --slate-200:     #E2E8F0;
            --slate-300:     #CBD5E1;
            --slate-400:     #94A3B8;
            --slate-500:     #64748B;
            --slate-600:     #475569;
            --slate-700:     #334155;
            --slate-800:     #1E293B;
            --white:         #FFFFFF;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background: #EEF2F9;
            color: var(--slate-800);
            min-height: 100vh;
            line-height: 1.6;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        /* ── Main wrap ── */
        .main-wrap { max-width: 1900px; margin: 0 auto; padding: 1.25rem 1.5rem; }

        /* ── Alerts ── */
        .alert {
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .alert-success {
            background: var(--emerald-pale);
            color: #065F46;
        }
        .alert-danger {
            background: var(--rose-pale);
            color: #9F1239;
        }

        /* ── Page header ── */
        .pg-header {
            background: var(--navy);
            border-radius: 12px;
            padding: 1.4rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.28);
            position: relative;
            overflow: hidden;
        }

        .pg-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 350px;
            height: 350px;
            background: rgba(245,158,11,0.08);
            border-radius: 50%;
            pointer-events: none;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .pg-icon-wrap {
            width: 52px; height: 52px;
            background: rgba(245,158,11,0.15);
            border: 1.5px solid rgba(245,158,11,0.3);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--amber);
            font-size: 1.5rem;
        }

        .pg-title-wrap h1 {
            color: var(--white); font-size: 1.45rem; font-weight: 700;
            margin: 0 0 0.15rem 0;
        }
        .pg-subtitle {
            color: rgba(255,255,255,0.7); font-size: 0.82rem;
            margin: 0;
        }

        .header-controls {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .month-picker {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.1);
            padding: 0.5rem 0.85rem;
            border-radius: 8px;
            border: 1.5px solid rgba(255,255,255,0.2);
        }

        .month-label {
            color: rgba(255,255,255,0.9);
            font-size: 0.82rem;
            font-weight: 600;
        }

        .month-input {
            padding: 0.4rem 0.65rem;
            border: none;
            border-radius: 6px;
            background: rgba(255,255,255,0.15);
            color: var(--white);
            font-size: 0.82rem;
            outline: none;
            cursor: pointer;
            font-family: 'Outfit', sans-serif;
        }
        .month-input::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        /* ── Shop filter ── */
        .filter-panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .filter-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--navy);
        }

        .filter-select {
            padding: 0.5rem 0.85rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            background: var(--slate-50);
            color: var(--slate-800);
            font-size: 0.82rem;
            outline: none;
            cursor: pointer;
            transition: all 0.18s;
            font-family: 'Outfit', sans-serif;
            min-width: 250px;
        }
        .filter-select:focus {
            border-color: var(--navy-light);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        /* ── Stats grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            opacity: 0;
            animation: slideUp 0.4s ease forwards;
        }

        .stat-card.s1 { animation-delay: 0s; border-top: 3px solid var(--emerald); }
        .stat-card.s2 { animation-delay: 0.05s; border-top: 3px solid var(--sky); }
        .stat-card.s3 { animation-delay: 0.1s; border-top: 3px solid var(--amber); }
        .stat-card.s4 { animation-delay: 0.15s; border-top: 3px solid var(--violet); }

        .stat-icon-box {
            width: 52px; height: 52px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 0.85rem;
        }

        .stat-icon-box.emerald { background: var(--emerald-pale); color: var(--emerald); }
        .stat-icon-box.sky { background: var(--sky-pale); color: var(--sky); }
        .stat-icon-box.amber { background: var(--amber-pale); color: #92400E; }
        .stat-icon-box.violet { background: var(--violet-pale); color: var(--violet); }

        .stat-value {
            font-family: 'DM Mono', monospace;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 0.35rem;
        }

        .stat-label {
            font-size: 0.78rem;
            color: var(--slate-500);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.75rem;
        }

        .stat-subtitle {
            font-size: 0.75rem;
            color: var(--slate-400);
        }

        /* ── View toggle ── */
        .controls-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .section-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--navy);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .view-toggle {
            display: flex;
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            overflow: hidden;
        }

        .view-btn {
            padding: 0.5rem 1rem;
            background: transparent;
            border: none;
            color: var(--slate-600);
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.18s;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .view-btn.active {
            background: var(--navy);
            color: var(--white);
        }

        .view-btn:hover:not(.active) {
            background: var(--slate-50);
        }

        /* ── Card view ── */
        .staff-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.25rem;
        }

        .staff-card {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            transition: all 0.18s;
            border-top: 3px solid var(--navy);
        }

        .staff-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(11,30,61,0.12);
        }

        .staff-card.rank-1 { border-top-color: var(--gold); }
        .staff-card.rank-2 { border-top-color: var(--silver); }
        .staff-card.rank-3 { border-top-color: var(--bronze); }

        .staff-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 1.5px solid var(--slate-200);
        }

        .rank-badge {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--white);
            background: var(--navy);
            font-family: 'DM Mono', monospace;
        }

        .rank-1 .rank-badge { background: var(--gold); color: var(--navy); }
        .rank-2 .rank-badge { background: var(--silver); color: var(--white); }
        .rank-3 .rank-badge { background: var(--bronze); color: var(--white); }

        .staff-avatar {
            width: 54px; height: 54px;
            border-radius: 10px;
            background: var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: var(--white);
            font-weight: 700;
        }

        .staff-info {
            flex: 1;
        }

        .staff-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 0.25rem;
        }

        .staff-rank-text {
            font-size: 0.75rem;
            color: var(--slate-500);
            font-weight: 600;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.85rem;
        }

        .metric-box {
            background: var(--slate-50);
            border-radius: 8px;
            padding: 0.85rem;
            text-align: center;
        }

        .metric-label {
            font-size: 0.7rem;
            color: var(--slate-500);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.4rem;
        }

        .metric-value {
            font-family: 'DM Mono', monospace;
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy);
        }

        .metric-box.full {
            grid-column: 1 / -1;
        }

        .progress-wrap {
            margin-top: 0.5rem;
        }

        .progress-bar {
            height: 6px;
            background: var(--slate-200);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.4s ease;
        }

        .progress-fill.emerald { background: var(--emerald); }
        .progress-fill.amber { background: var(--amber); }
        .progress-fill.rose { background: var(--rose); }

        /* ── List view ── */
        .staff-table-wrap {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            overflow: hidden;
        }

        table.staff-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        table.staff-tbl thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 0.85rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.18s;
        }

        table.staff-tbl thead th:hover {
            background: var(--slate-200);
            color: var(--navy);
        }

        table.staff-tbl tbody td {
            padding: 0.85rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
            color: var(--slate-800);
        }

        table.staff-tbl tbody tr:hover td {
            background: #F8FAFF;
        }

        table.staff-tbl tfoot td {
            background: var(--slate-50);
            font-weight: 700;
            padding: 0.85rem;
            border-top: 2px solid var(--slate-200);
        }

        .rank-cell {
            font-family: 'DM Mono', monospace;
            font-weight: 700;
            font-size: 1rem;
            color: var(--navy);
        }

        .rank-cell.gold { color: var(--gold); }
        .rank-cell.silver { color: var(--silver); }
        .rank-cell.bronze { color: var(--bronze); }

        .staff-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .mini-avatar {
            width: 38px; height: 38px;
            border-radius: 8px;
            background: var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            color: var(--white);
            font-weight: 700;
        }

        .amt-mono {
            font-family: 'DM Mono', monospace;
            font-weight: 600;
        }

        .perf-cell {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .mini-progress {
            width: 70px;
            height: 5px;
            background: var(--slate-200);
            border-radius: 3px;
            overflow: hidden;
        }

        .mini-progress-fill {
            height: 100%;
            border-radius: 3px;
        }

        .mini-progress-fill.emerald { background: var(--emerald); }
        .mini-progress-fill.amber { background: var(--amber); }
        .mini-progress-fill.rose { background: var(--rose); }

        .positive { color: var(--emerald); }
        .negative { color: var(--rose); }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 4rem 1.5rem;
        }
        .empty-icon {
            width: 80px; height: 80px;
            margin: 0 auto 1rem;
            display: flex; align-items: center; justify-content: center;
            background: var(--slate-100);
            border-radius: 50%;
            color: var(--slate-400);
            font-size: 2rem;
        }
        .empty-title {
            font-size: 1.1rem; font-weight: 700;
            color: var(--slate-600);
            margin-bottom: 0.4rem;
        }
        .empty-desc {
            font-size: 0.875rem; color: var(--slate-500);
        }

        /* ── Responsive ── */
        @media (max-width: 1200px) {
            .staff-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
            .pg-header { padding: 1rem; margin-bottom: 1rem; }
            .header-row { flex-direction: column; align-items: flex-start; }
            .header-controls { width: 100%; flex-direction: column; }
            .month-picker { width: 100%; }
            .filter-panel { flex-direction: column; align-items: flex-start; }
            .filter-select { width: 100%; }
            .stats-grid { grid-template-columns: 1fr; }
            .staff-grid { grid-template-columns: 1fr; }
            .controls-bar { flex-direction: column; align-items: flex-start; }
            .view-toggle { width: 100%; }
            .view-btn { flex: 1; justify-content: center; }

            table.staff-tbl thead { display: none; }
            table.staff-tbl tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1.5px solid var(--slate-200);
                border-radius: 10px;
                padding: 1rem;
                background: var(--white);
            }
            table.staff-tbl tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.65rem 0;
                border-bottom: 1px solid var(--slate-100);
            }
            table.staff-tbl tbody td:last-child {
                border-bottom: none;
                padding-top: 0.85rem;
                border-top: 1px solid var(--slate-200);
                margin-top: 0.5rem;
            }
            table.staff-tbl tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--slate-500);
                min-width: 100px;
                font-size: 0.75rem;
            }
        }

        /* ── Animation ── */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .hidden { display: none !important; }
    </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
        <div class="main-wrap">

            {{-- ── Alerts ── --}}
            @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
            </div>
            @endif

            {{-- ── Page Header ── --}}
            <div class="pg-header">
                <div class="header-row">
                    <div class="header-left">
                        <div class="pg-icon-wrap">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="pg-title-wrap">
                            <h1>KPI Dashboard</h1>
                            <p class="pg-subtitle">Staff Performance Analytics</p>
                        </div>
                    </div>
                    <div class="header-controls">
                        <div class="month-picker">
                            <i class="bi bi-calendar-week" style="color: rgba(255,255,255,0.7);"></i>
                            <span class="month-label">Month:</span>
                            <input type="month" id="reportMonth" class="month-input" 
                                value="{{ isset($monthParam) ? $monthParam : date('Y-m') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Shop Filter (Admin Only) ── --}}
            @if(isset($allShops) && $allShops->count() > 0)
            <div class="filter-panel">
                <div class="filter-label">
                    <i class="bi bi-shop"></i> Filter by Shop
                </div>
                <form method="GET" action="" style="flex: 1; max-width: 400px;">
                    <input type="hidden" name="month" value="{{ $monthParam }}">
                    <select name="shop_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Shops</option>
                        @foreach($allShops as $shop)
                            <option value="{{ $shop->id }}" {{ (request('shop_id') == $shop->id) ? 'selected' : '' }}>
                                {{ $shop->name }} ({{ $shop->location ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            @endif

            {{-- ── Stats Grid ── --}}
            <div class="stats-grid">
                <div class="stat-card s1">
                    <div class="stat-icon-box emerald">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-value">Tsh {{ number_format($totalAllSales) }}</div>
                    <div class="stat-label">Total Staff Sales</div>
                    <div class="stat-subtitle">Combined for {{ date('F Y', strtotime(isset($monthParam) ? $monthParam . '-01' : now())) }}</div>
                </div>

                <div class="stat-card s2">
                    <div class="stat-icon-box sky">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-value">{{ $staffKpis->count() }}</div>
                    <div class="stat-label">Active Staff</div>
                    <div class="stat-subtitle">Performers this month</div>
                </div>

                <div class="stat-card s3">
                    <div class="stat-icon-box amber">
                        <i class="bi bi-calculator"></i>
                    </div>
                    <div class="stat-value">Tsh {{ number_format($staffKpis->avg('avg_transaction_value')) }}</div>
                    <div class="stat-label">Avg Transaction</div>
                    <div class="stat-subtitle">Per transaction value</div>
                </div>

                <div class="stat-card s4">
                    <div class="stat-icon-box violet">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="stat-value">{{ number_format($staffKpis->avg('profit_margin'), 1) }}%</div>
                    <div class="stat-label">Avg Profit Margin</div>
                    <div class="stat-subtitle">Across all staff</div>
                </div>
            </div>

            {{-- ── Controls Bar ── --}}
            <div class="controls-bar">
                <div class="section-title">
                    <i class="bi bi-trophy"></i> Staff Performance
                </div>
                <div class="view-toggle">
                    <button class="view-btn active" onclick="switchView('card')" id="btnCard">
                        <i class="bi bi-grid-3x3-gap"></i> Card View
                    </button>
                    <button class="view-btn" onclick="switchView('list')" id="btnList">
                        <i class="bi bi-list-ul"></i> List View
                    </button>
                </div>
            </div>

            {{-- ── Card View ── --}}
            <div id="cardView">
                @if($staffKpis->isEmpty())
                    <div class="staff-grid">
                        <div style="grid-column: 1 / -1;">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="empty-title">No Staff Data Available</div>
                                <p class="empty-desc">No staff performance data found for the selected period.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="staff-grid">
                        @foreach ($staffKpis as $staff)
                            @php
                                $firstLetter = strtoupper(substr($staff->served_by, 0, 1));
                                $performanceColor = $staff->profit_margin >= 20 ? 'emerald' : ($staff->profit_margin >= 10 ? 'amber' : 'rose');
                                $rankClass = $staff->rank <= 3 ? 'rank-' . $staff->rank : '';
                            @endphp
                            <div class="staff-card {{ $rankClass }}">
                                <div class="staff-header">
                                    <div class="rank-badge">#{{ $staff->rank }}</div>
                                    <div class="staff-avatar">{{ $firstLetter }}</div>
                                    <div class="staff-info">
                                        <div class="staff-name">{{ $staff->served_by }}</div>
                                        <div class="staff-rank-text">Rank #{{ $staff->rank }} of {{ $staffKpis->count() }}</div>
                                    </div>
                                </div>

                                <div class="metrics-grid">
                                    <div class="metric-box">
                                        <div class="metric-label">Total Sales</div>
                                        <div class="metric-value">Tsh {{ number_format($staff->total_sales) }}</div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="metric-label">Transactions</div>
                                        <div class="metric-value">{{ number_format($staff->total_transactions) }}</div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="metric-label">Avg Value</div>
                                        <div class="metric-value">Tsh {{ number_format($staff->avg_transaction_value) }}</div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="metric-label">Cash Ratio</div>
                                        <div class="metric-value">{{ number_format($staff->cash_ratio, 1) }}%</div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="metric-label">Credit Ratio</div>
                                        <div class="metric-value">{{ number_format($staff->credit_ratio, 1) }}%</div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="metric-label">Discount Rate</div>
                                        <div class="metric-value">{{ number_format($staff->discount_rate, 1) }}%</div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="metric-label">Return Rate</div>
                                        <div class="metric-value">{{ number_format($staff->return_rate, 1) }}%</div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="metric-label">Debt Ratio</div>
                                        <div class="metric-value">{{ number_format($staff->debt_ratio, 1) }}%</div>
                                    </div>
                                    <div class="metric-box full">
                                        <div class="metric-label">Profit Margin</div>
                                        <div class="metric-value">{{ number_format($staff->profit_margin, 1) }}%</div>
                                        <div class="progress-wrap">
                                            <div class="progress-bar">
                                                <div class="progress-fill {{ $performanceColor }}" 
                                                    style="width: {{ min($staff->profit_margin, 100) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── List View ── --}}
            <div id="listView" class="hidden">
                @if($staffKpis->isEmpty())
                    <div class="staff-table-wrap">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <div class="empty-title">No KPI Data</div>
                            <p class="empty-desc">No staff performance metrics available for the selected period.</p>
                        </div>
                    </div>
                @else
                    <div class="staff-table-wrap">
                        <div class="table-responsive">
                            <table class="staff-tbl" id="staffTable">
                                <thead>
                                    <tr>
                                        <th width="5%">Rank</th>
                                        <th>Staff Name</th>
                                        <th>Total Sales</th>
                                        <th>Trans.</th>
                                        <th>Avg Value</th>
                                        <th>Cash %</th>
                                        <th>Credit %</th>
                                        <th>Discount %</th>
                                        <th>Return %</th>
                                        <th>Debt %</th>
                                        <th>Profit Margin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($staffKpis as $staff)
                                        @php
                                            $performanceColor = $staff->profit_margin >= 20 ? 'emerald' : ($staff->profit_margin >= 10 ? 'amber' : 'rose');
                                            $rankClass = '';
                                            if ($staff->rank == 1) $rankClass = 'gold';
                                            elseif ($staff->rank == 2) $rankClass = 'silver';
                                            elseif ($staff->rank == 3) $rankClass = 'bronze';
                                        @endphp
                                        <tr>
                                            <td data-label="Rank">
                                                <div class="rank-cell {{ $rankClass }}">#{{ $staff->rank }}</div>
                                            </td>
                                            <td data-label="Staff Name">
                                                <div class="staff-cell">
                                                    <div class="mini-avatar">
                                                        {{ strtoupper(substr($staff->served_by, 0, 1)) }}
                                                    </div>
                                                    <span>{{ $staff->served_by }}</span>
                                                </div>
                                            </td>
                                            <td data-label="Total Sales">
                                                <span class="amt-mono">Tsh {{ number_format($staff->total_sales) }}</span>
                                            </td>
                                            <td data-label="Transactions">
                                                <span class="amt-mono">{{ number_format($staff->total_transactions) }}</span>
                                            </td>
                                            <td data-label="Avg Value">
                                                <span class="amt-mono">Tsh {{ number_format($staff->avg_transaction_value) }}</span>
                                            </td>
                                            <td data-label="Cash %">
                                                <span class="amt-mono">{{ number_format($staff->cash_ratio, 1) }}%</span>
                                            </td>
                                            <td data-label="Credit %">
                                                <span class="amt-mono">{{ number_format($staff->credit_ratio, 1) }}%</span>
                                            </td>
                                            <td data-label="Discount %">
                                                <span class="amt-mono">{{ number_format($staff->discount_rate, 1) }}%</span>
                                            </td>
                                            <td data-label="Return %">
                                                <span class="amt-mono">{{ number_format($staff->return_rate, 1) }}%</span>
                                            </td>
                                            <td data-label="Debt %">
                                                <span class="amt-mono">{{ number_format($staff->debt_ratio, 1) }}%</span>
                                            </td>
                                            <td data-label="Profit Margin">
                                                <div class="perf-cell">
                                                    <div class="mini-progress">
                                                        <div class="mini-progress-fill {{ $performanceColor }}" 
                                                            style="width: {{ min($staff->profit_margin, 100) }}%"></div>
                                                    </div>
                                                    <span class="amt-mono {{ $staff->profit_margin >= 20 ? 'positive' : ($staff->profit_margin < 10 ? 'negative' : '') }}">
                                                        {{ number_format($staff->profit_margin, 1) }}%
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @if($staffKpis->isNotEmpty())
                                <tfoot>
                                    <tr>
                                        <td colspan="2" style="text-align: right;">AVERAGE:</td>
                                        <td><span class="amt-mono">Tsh {{ number_format($staffKpis->avg('total_sales')) }}</span></td>
                                        <td><span class="amt-mono">{{ number_format($staffKpis->avg('total_transactions'), 1) }}</span></td>
                                        <td><span class="amt-mono">Tsh {{ number_format($staffKpis->avg('avg_transaction_value')) }}</span></td>
                                        <td><span class="amt-mono">{{ number_format($staffKpis->avg('cash_ratio'), 1) }}%</span></td>
                                        <td><span class="amt-mono">{{ number_format($staffKpis->avg('credit_ratio'), 1) }}%</span></td>
                                        <td><span class="amt-mono">{{ number_format($staffKpis->avg('discount_rate'), 1) }}%</span></td>
                                        <td><span class="amt-mono">{{ number_format($staffKpis->avg('return_rate'), 1) }}%</span></td>
                                        <td><span class="amt-mono">{{ number_format($staffKpis->avg('debt_ratio'), 1) }}%</span></td>
                                        <td>
                                            <span class="amt-mono {{ $staffKpis->avg('profit_margin') >= 20 ? 'positive' : ($staffKpis->avg('profit_margin') < 10 ? 'negative' : '') }}">
                                                {{ number_format($staffKpis->avg('profit_margin'), 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </main>
  </div>
</div>

<script>
    // Month selector
    document.addEventListener('DOMContentLoaded', function() {
        const monthInput = document.getElementById('reportMonth');
        if (monthInput) {
            monthInput.addEventListener('change', function() {
                const selectedMonth = this.value;
                if (selectedMonth) {
                    const currentUrl = window.location.href;
                    const url = new URL(currentUrl);
                    url.searchParams.set('month', selectedMonth);
                    window.location.href = url.toString();
                }
            });
        }
    });

    // View switcher
    function switchView(view) {
        const cardView = document.getElementById('cardView');
        const listView = document.getElementById('listView');
        const btnCard = document.getElementById('btnCard');
        const btnList = document.getElementById('btnList');

        if (view === 'card') {
            cardView.classList.remove('hidden');
            listView.classList.add('hidden');
            btnCard.classList.add('active');
            btnList.classList.remove('active');
        } else {
            cardView.classList.add('hidden');
            listView.classList.remove('hidden');
            btnCard.classList.remove('active');
            btnList.classList.add('active');
        }

        // Save preference
        localStorage.setItem('kpiView', view);
    }

    // Load saved view preference
    document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('kpiView');
        if (savedView) {
            switchView(savedView);
        }
    });

    // Table sorting
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.getElementById('staffTable');
        if (table) {
            const headers = table.querySelectorAll('thead th');
            headers.forEach((header, index) => {
                if (index > 0) {
                    header.addEventListener('click', () => sortTable(table, index));
                }
            });
        }
    });

    function sortTable(table, column) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const isAsc = table.getAttribute('data-sort-' + column) !== 'asc';

        rows.sort((a, b) => {
            const aValue = a.cells[column].textContent.trim();
            const bValue = b.cells[column].textContent.trim();

            const aNum = parseFloat(aValue.replace(/[^0-9.-]/g, '')) || 0;
            const bNum = parseFloat(bValue.replace(/[^0-9.-]/g, '')) || 0;

            return isAsc ? aNum - bNum : bNum - aNum;
        });

        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }
        rows.forEach(row => tbody.appendChild(row));

        table.setAttribute('data-sort-' + column, isAsc ? 'asc' : 'desc');
    }
</script>

</body>
</html>