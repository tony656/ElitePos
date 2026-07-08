<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} - Customer KPI Dashboard</title>
    @include('links')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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

        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        .main-wrap { max-width: 1900px; margin: 0 auto; padding: 1.25rem 1.5rem; }

        .alert {
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .alert-success { background: var(--emerald-pale); color: #065F46; }
        .alert-danger { background: var(--rose-pale); color: #9F1239; }

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

        .customer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.25rem;
        }
        .customer-card {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            transition: all 0.18s;
            border-top: 3px solid var(--navy);
        }
        .customer-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(11,30,61,0.12);
        }
        .customer-card.rank-1 { border-top-color: var(--gold); }
        .customer-card.rank-2 { border-top-color: var(--silver); }
        .customer-card.rank-3 { border-top-color: var(--bronze); }
        .customer-header {
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
            align-items: center; justify-content: center;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--white);
            background: var(--navy);
            font-family: 'DM Mono', monospace;
        }
        .rank-1 .rank-badge { background: var(--gold); color: var(--navy); }
        .rank-2 .rank-badge { background: var(--silver); color: var(--white); }
        .rank-3 .rank-badge { background: var(--bronze); color: var(--white); }
        .customer-avatar {
            width: 54px; height: 54px;
            border-radius: 10px;
            background: var(--navy);
            display: flex;
            align-items: center; justify-content: center;
            font-size: 1.4rem;
            color: var(--white);
            font-weight: 700;
        }
        .customer-info {
            flex: 1;
        }
        .customer-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 0.25rem;
        }
        .customer-rank-text {
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

        .customer-table-wrap {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            overflow: hidden;
        }
        table.customer-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }
        table.customer-tbl thead th {
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
        table.customer-tbl thead th:hover {
            background: var(--slate-200);
            color: var(--navy);
        }
        table.customer-tbl tbody td {
            padding: 0.85rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
            color: var(--slate-800);
        }
        table.customer-tbl tbody tr:hover td {
            background: #F8FAFF;
        }
        table.customer-tbl tfoot td {
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
        .customer-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .mini-avatar {
            width: 38px; height: 38px;
            border-radius: 8px;
            background: var(--navy);
            display: flex;
            align-items: center; justify-content: center;
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

        @media (max-width: 1200px) {
            .customer-grid {
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
            .customer-grid { grid-template-columns: 1fr; }
            .controls-bar { flex-direction: column; align-items: flex-start; }
            .view-toggle { width: 100%; }
            .view-btn { flex: 1; justify-content: center; }
            table.customer-tbl thead { display: none; }
            table.customer-tbl tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1.5px solid var(--slate-200);
                border-radius: 10px;
                padding: 1rem;
                background: var(--white);
            }
            table.customer-tbl tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.65rem 0;
                border-bottom: 1px solid var(--slate-100);
            }
            table.customer-tbl tbody td:last-child {
                border-bottom: none;
                padding-top: 0.85rem;
                border-top: 1.5px solid var(--slate-200);
                margin-top: 0.5rem;
            }
            table.customer-tbl tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--slate-500);
                min-width: 100px;
                font-size: 0.75rem;
            }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .hidden { display: none !important; }

        /* ── new chart toggle & container ── */
        .chart-toggle-wrap {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1rem;
        }
        .chart-toggle-btn {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            font-size: 0.82rem;
            color: var(--navy);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }
        .chart-toggle-btn:hover {
            background: var(--slate-50);
            border-color: var(--navy-light);
        }
        .chart-container {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 1.5rem 1.5rem 1rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            transition: all 0.25s;
        }
        .chart-container.hidden { display: none; }
        .chart-container canvas { max-height: 280px; max-width: 100%; }
        .chart-title {
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 0.75rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body>

    @include("sidenav")

    <main class="main-content">
        <div class="main-wrap">

            {{-- Alerts --}}
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

            {{-- Page Header --}}
            <div class="pg-header">
                <div class="header-row">
                    <div class="header-left">
                        <div class="pg-icon-wrap">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="pg-title-wrap">
                            <h1>Customer KPI Dashboard</h1>
                            <p class="pg-subtitle">Customer Performance Analytics</p>
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

            {{-- Shop Filter --}}
            @if(isset($allShops) && count($allShops) > 0)
            <div class="filter-panel">
                <div class="filter-label">
                    <i class="bi bi-shop"></i> Filter by Shop
                </div>
                <form method="GET" action="" style="flex: 1; max-width: 400px;">
                    <input type="hidden" name="month" value="{{ $monthParam }}">
                    <select name="shop_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Shops</option>
                        @foreach($allShops as $shop)
                            <option value="{{ $shop['id'] }}" {{ (request('shop_id') == $shop['id']) ? 'selected' : '' }}>
                                {{ $shop['name'] }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            @endif

            {{-- Stats Grid --}}
            <div class="stats-grid">
                <div class="stat-card s1">
                    <div class="stat-icon-box emerald">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-value">Tsh {{ number_format($customerKpis->sum('total_spent')) }}</div>
                    <div class="stat-label">Total Customer Spending</div>
                    <div class="stat-subtitle">Combined for {{ date('F Y', strtotime(isset($monthParam) ? $monthParam . '-01' : now())) }}</div>
                </div>

                <div class="stat-card s2">
                    <div class="stat-icon-box sky">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-value">{{ $customerKpis->count() }}</div>
                    <div class="stat-label">Active Customers</div>
                    <div class="stat-subtitle">With purchases this month</div>
                </div>

                <div class="stat-card s3">
                    <div class="stat-icon-box amber">
                        <i class="bi bi-calculator"></i>
                    </div>
                    <div class="stat-value">Tsh {{ number_format($customerKpis->avg('avg_transaction_value')) }}</div>
                    <div class="stat-label">Avg Transaction</div>
                    <div class="stat-subtitle">Per customer purchase</div>
                </div>

                <div class="stat-card s4">
                    <div class="stat-icon-box violet">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="stat-value">{{ number_format($customerKpis->avg('visit_count'), 1) }}</div>
                    <div class="stat-label">Avg Visits</div>
                    <div class="stat-subtitle">Visits per customer</div>
                </div>
            </div>

            {{-- ── NEW: bar chart (staff stats) hidden by default ── --}}
            <div class="chart-toggle-wrap">
                <button class="chart-toggle-btn" id="chartToggleBtn">
                    <i class="bi bi-bar-chart-fill"></i> 
                    <span id="chartToggleLabel">Show Staff Chart</span>
                </button>
            </div>
            <div class="chart-container hidden" id="staffChartContainer">
                <div class="chart-title"><i class="bi bi-person-badge" style="color: var(--navy-light);"></i> Staff · total sales by staff (from customer data)</div>
                <canvas id="staffChart"></canvas>
                <p style="font-size:0.7rem; color:var(--slate-400); margin-top:0.6rem; text-align:right;">* based on customer transactions</p>
            </div>

            {{-- Controls Bar --}}
            <div class="controls-bar">
                <div class="section-title">
                    <i class="bi bi-trophy"></i> Customer Performance
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

            {{-- Card View --}}
            <div id="cardView">
                @if($customerKpis->isEmpty())
                    <div class="customer-grid">
                        <div style="grid-column: 1 / -1;">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="empty-title">No Customer Data Available</div>
                                <p class="empty-desc">No customer purchase data found for the selected period.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="customer-grid">
                        @foreach ($customerKpis as $customer)
                            @php
                                $firstLetter = strtoupper(substr($customer->customer_name, 0, 1));
                                $performanceColor = $customer->credit_ratio <= 20 ? 'emerald' : ($customer->credit_ratio <= 50 ? 'amber' : 'rose');
                                $rankClass = $customer->rank <= 3 ? 'rank-' . $customer->rank : '';
                            @endphp
                            <div class="customer-card {{ $rankClass }}">
                                <div class="customer-header">
                                    <div class="rank-badge">#{{ $customer->rank }}</div>
                                    <div class="customer-avatar">{{ $firstLetter }}</div>
                                    <div class="customer-info">
                                        <div class="customer-name">{{ $customer->customer_name }}</div>
                                        <div class="customer-rank-text">Rank #{{ $customer->rank }} of {{ $customerKpis->count() }}</div>
                                    </div>
                                </div>

                                <div class="metrics-grid">
                                    <div class="metric-box">
                                        <div class="metric-label">Total Spent</div>
                                        <div class="metric-value">Tsh {{ number_format($customer->total_spent) }}</div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="metric-label">Purchases</div>
                                        <div class="metric-value">{{ number_format($customer->total_purchases) }}</div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="metric-label">Avg Purchase</div>
                                        <div class="metric-value">Tsh {{ number_format($customer->avg_transaction_value) }}</div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="metric-label">Visits</div>
                                        <div class="metric-value">{{ number_format($customer->visit_count) }}</div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="metric-label">Cash %</div>
                                        <div class="metric-value">{{ number_format($customer->cash_ratio, 1) }}%</div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="metric-label">Credit %</div>
                                        <div class="metric-value">{{ number_format($customer->credit_ratio, 1) }}%</div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="metric-label">Returns</div>
                                        <div class="metric-value">{{ number_format($customer->total_returns) }}</div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="metric-label">Discount %</div>
                                        <div class="metric-value">{{ number_format($customer->discount_rate, 1) }}%</div>
                                    </div>
                                    <div class="metric-box full">
                                        <div class="metric-label">Debt Ratio</div>
                                        <div class="metric-value">{{ number_format($customer->credit_ratio, 1) }}%</div>
                                        <div class="progress-wrap">
                                            <div class="progress-bar">
                                                <div class="progress-fill {{ $performanceColor }}" 
                                                    style="width: {{ min($customer->credit_ratio, 100) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- List View --}}
            <div id="listView" class="hidden">
                @if($customerKpis->isEmpty())
                    <div class="customer-table-wrap">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <div class="empty-title">No KPI Data</div>
                            <p class="empty-desc">No customer performance metrics available for the selected period.</p>
                        </div>
                    </div>
                @else
                    <div class="customer-table-wrap">
                        <div class="table-responsive">
                            <table class="customer-tbl" id="customerTable">
                                <thead>
                                    <tr>
                                        <th width="5%">Rank</th>
                                        <th>Customer</th>
                                        <th>Total Spent</th>
                                        <th>Purchases</th>
                                        <th>Avg Purchase</th>
                                        <th>Visits</th>
                                        <th>Cash %</th>
                                        <th>Credit %</th>
                                        <th>Returns</th>
                                        <th>Discount %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customerKpis as $customer)
                                        @php
                                            $performanceColor = $customer->credit_ratio <= 20 ? 'emerald' : ($customer->credit_ratio <= 50 ? 'amber' : 'rose');
                                            $rankClass = '';
                                            if ($customer->rank == 1) $rankClass = 'gold';
                                            elseif ($customer->rank == 2) $rankClass = 'silver';
                                            elseif ($customer->rank == 3) $rankClass = 'bronze';
                                        @endphp
                                        <tr>
                                            <td data-label="Rank">
                                                <div class="rank-cell {{ $rankClass }}">#{{ $customer->rank }}</div>
                                            </td>
                                            <td data-label="Customer">
                                                <div class="customer-cell">
                                                    <div class="mini-avatar">
                                                        {{ strtoupper(substr($customer->customer_name, 0, 1)) }}
                                                    </div>
                                                    <span>{{ $customer->customer_name }}</span>
                                                </div>
                                            </td>
                                            <td data-label="Total Spent">
                                                <span class="amt-mono">Tsh {{ number_format($customer->total_spent) }}</span>
                                            </td>
                                            <td data-label="Purchases">
                                                <span class="amt-mono">{{ number_format($customer->total_purchases) }}</span>
                                            </td>
                                            <td data-label="Avg Purchase">
                                                <span class="amt-mono">Tsh {{ number_format($customer->avg_transaction_value) }}</span>
                                            </td>
                                            <td data-label="Visits">
                                                <span class="amt-mono">{{ number_format($customer->visit_count) }}</span>
                                            </td>
                                            <td data-label="Cash %">
                                                <span class="amt-mono">{{ number_format($customer->cash_ratio, 1) }}%</span>
                                            </td>
                                            <td data-label="Credit %">
                                                <span class="amt-mono">{{ number_format($customer->credit_ratio, 1) }}%</span>
                                            </td>
                                            <td data-label="Returns">
                                                <span class="amt-mono">{{ number_format($customer->total_returns) }}</span>
                                            </td>
                                            <td data-label="Discount %">
                                                <div class="perf-cell">
                                                    <div class="mini-progress">
                                                        <div class="mini-progress-fill {{ $performanceColor }}" 
                                                            style="width: {{ min($customer->credit_ratio, 100) }}%"></div>
                                                    </div>
                                                    <span class="amt-mono {{ $customer->credit_ratio <= 20 ? 'positive' : ($customer->credit_ratio > 50 ? 'negative' : '') }}">
                                                        {{ number_format($customer->discount_rate, 1) }}%
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @if($customerKpis->isNotEmpty())
                                <tfoot>
                                    <tr>
                                        <td colspan="2" style="text-align: right;">AVERAGE:</td>
                                        <td><span class="amt-mono">Tsh {{ number_format($customerKpis->avg('total_spent')) }}</span></td>
                                        <td><span class="amt-mono">{{ number_format($customerKpis->avg('total_purchases'), 1) }}</span></td>
                                        <td><span class="amt-mono">Tsh {{ number_format($customerKpis->avg('avg_transaction_value')) }}</span></td>
                                        <td><span class="amt-mono">{{ number_format($customerKpis->avg('visit_count'), 1) }}</span></td>
                                        <td><span class="amt-mono">{{ number_format($customerKpis->avg('cash_ratio'), 1) }}%</span></td>
                                        <td><span class="amt-mono">{{ number_format($customerKpis->avg('credit_ratio'), 1) }}%</span></td>
                                        <td><span class="amt-mono">{{ number_format($customerKpis->avg('total_returns')) }}</span></td>
                                        <td>
                                            <span class="amt-mono {{ $customerKpis->avg('credit_ratio') <= 20 ? 'positive' : ($customerKpis->avg('credit_ratio') > 50 ? 'negative' : '') }}">
                                                {{ number_format($customerKpis->avg('discount_rate'), 1) }}%
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
        localStorage.setItem('customerKpiView', view);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('customerKpiView');
        if (savedView) {
            switchView(savedView);
        }
    });

    // Table sorting
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.getElementById('customerTable');
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

    // ── STAFF BAR CHART (hidden by default) ──
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('staffChart').getContext('2d');
        // simulate staff data from customer KPIs – we derive staff names from customers? 
        // In real app, inject staff data from backend. We'll use customer names as labels and total_spent as values.
        const labels = @json($customerKpis->pluck('customer_name'));
        const values = @json($customerKpis->pluck('total_spent'));

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels.length ? labels : ['No data'],
                datasets: [{
                    label: 'Staff Sales (Tsh)',
                    data: values.length ? values : [0],
                    backgroundColor: 'rgba(7, 89, 133, 0.75)',
                    borderColor: '#0B1E3D',
                    borderWidth: 1.5,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { 
                        callbacks: {
                            label: function(context) {
                                return 'Tsh ' + Number(context.raw).toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            callback: function(value) { return 'Tsh ' + value.toLocaleString(); },
                            font: { size: 10 }
                        },
                        grid: { color: 'rgba(0,0,0,0.04)' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 9 }, maxRotation: 45 }
                    }
                }
            }
        });

        // ── Toggle show/hide chart ──
        const toggleBtn = document.getElementById('chartToggleBtn');
        const chartContainer = document.getElementById('staffChartContainer');
        const toggleLabel = document.getElementById('chartToggleLabel');
        let chartVisible = false;

        toggleBtn.addEventListener('click', function() {
            chartVisible = !chartVisible;
            if (chartVisible) {
                chartContainer.classList.remove('hidden');
                toggleLabel.textContent = 'Hide Staff Chart';
                toggleBtn.innerHTML = '<i class="bi bi-eye-slash-fill"></i> <span id="chartToggleLabel">Hide Staff Chart</span>';
                setTimeout(() => chart.resize(), 100);
            } else {
                chartContainer.classList.add('hidden');
                toggleLabel.textContent = 'Show Staff Chart';
                toggleBtn.innerHTML = '<i class="bi bi-bar-chart-fill"></i> <span id="chartToggleLabel">Show Staff Chart</span>';
            }
        });

        // hidden by default
        chartContainer.classList.add('hidden');
        toggleLabel.textContent = 'Show Staff Chart';
        toggleBtn.innerHTML = '<i class="bi bi-bar-chart-fill"></i> <span id="chartToggleLabel">Show Staff Chart</span>';
    });
</script>

</body>
</html>