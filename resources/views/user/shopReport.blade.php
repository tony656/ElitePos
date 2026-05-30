<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Report — {{ is_string($dateParam) && str_contains($dateParam, 'to') ? $dateParam : date('F d, Y', strtotime($dateParam)) }}</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #F7F6F2;
            --surface:   #FFFFFF;
            --border:    #E4E2DA;
            --border-md: #CECCC4;
            --text:      #0B1E3D;
            --muted:     #7A7870;
            --accent:    #F59E0B;
            --green:     #1A6B45;
            --green-bg:  #E6F4ED;
            --red:       #B63A2F;
            --red-bg:    #FDECEA;
            --amber:     #F59E0B;
            --amber-bg:  #FEF3D7;
            --purple:    #1A3A6B;
            --purple-bg: #EEECFA;
            --shadow:    0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --radius:    10px;
            --radius-sm: 6px;
            --font:      'DM Sans', system-ui, sans-serif;
            --mono:      'DM Mono', monospace;
        }

        body {
            background: var(--bg);
            font-family: var(--font);
            color: var(--text);
            font-size: 14px;
            line-height: 1.5;
        }

        /* ── Layout ── */
        .layout { display: flex; min-height: 100vh; }
        .sidebar-wrap { flex-shrink: 0; }
        .main {
            flex: 1;
            min-width: 0;
            padding: 2rem 2.5rem;
        }

        /* ── Alerts ── */
        .alert {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.75rem 1rem; border-radius: var(--radius-sm);
            margin-bottom: 1.25rem; font-size: 13px;
        }
        .alert-success { background: var(--green-bg); color: var(--green); border: 1px solid #B2DFC5; }
        .alert-danger  { background: var(--red-bg);   color: var(--red);   border: 1px solid #F5C6C2; }
        .btn-close-sm { background: none; border: none; cursor: pointer; font-size: 16px; color: inherit; opacity: 0.6; }
        .btn-close-sm:hover { opacity: 1; }

        /* ── Page header ── */
        .page-header {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 2rem; gap: 1rem; flex-wrap: wrap;
        }
        .page-title { font-size: 22px; font-weight: 600; letter-spacing: -0.3px; }
        .page-sub   { font-size: 13px; color: var(--muted); margin-top: 3px; }

        .header-actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }

        /* ── Date form ── */
        .date-form { display: flex; border: 1px solid var(--border-md); border-radius: var(--radius-sm); overflow: hidden; background: var(--surface); }
        .date-form input[type=date] {
            border: none; outline: none; padding: 7px 12px;
            font-family: var(--font); font-size: 13px;
            background: transparent; color: var(--text);
        }
        .date-form button {
            background: var(--accent); color: #fff; border: none;
            padding: 7px 14px; font-family: var(--font); font-size: 13px;
            font-weight: 500; cursor: pointer; white-space: nowrap;
        }
        .date-form button:hover { opacity: 0.85; }

        /* ── Icon buttons ── */
        .btn-icon {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 14px; border-radius: var(--radius-sm);
            font-family: var(--font); font-size: 13px; font-weight: 500;
            cursor: pointer; border: 1px solid var(--border-md);
            background: var(--surface); color: var(--text);
            text-decoration: none; transition: background 0.15s;
        }
        .btn-icon:hover { background: var(--bg); }
        .btn-icon svg { width: 14px; height: 14px; }

        /* ── Metric cards ── */
        .metrics { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 12px; margin-bottom: 2rem; }
        @media (max-width: 900px) { .metrics { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 560px) { .metrics { grid-template-columns: 1fr; } }

        .metric {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 1.1rem 1.25rem;
            box-shadow: var(--shadow);
        }
        .metric-label { font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; color: var(--muted); margin-bottom: 8px; }
        .metric-value { font-size: 24px; font-weight: 600; letter-spacing: -0.5px; font-family: var(--mono); }
        .metric-sub   { font-size: 12px; color: var(--muted); margin-top: 5px; }

        .metric.purple .metric-value { color: var(--purple); }
        .metric.green  .metric-value { color: var(--green);  }
        .metric.red    .metric-value { color: var(--red);    }
        .metric.amber  .metric-value { color: var(--amber);  }

        /* ── Panel ── */
        .panel {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden;
        }
        .panel-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.25rem; border-bottom: 1px solid var(--border);
            gap: 1rem; flex-wrap: wrap;
        }
        .panel-title { font-size: 14px; font-weight: 600; }

        /* ── Search & Sort ── */
        .search-wrap {
            display: flex; align-items: center; gap: 7px;
            border: 1px solid var(--border-md); border-radius: var(--radius-sm);
            padding: 6px 10px; background: var(--bg);
        }
        .search-wrap svg { width: 13px; height: 13px; color: var(--muted); flex-shrink: 0; }
        .search-wrap input {
            border: none; background: transparent; outline: none;
            font-family: var(--font); font-size: 13px; color: var(--text); width: 180px;
        }
        .search-wrap input::placeholder { color: var(--muted); }

        /* ── Sort dropdown ── */
        .sort-wrap {
            display: flex; align-items: center; gap: 7px;
            border: 1px solid var(--border-md); border-radius: var(--radius-sm);
            padding: 6px 10px; background: var(--bg);
        }
        .sort-wrap select {
            border: none; background: transparent; outline: none;
            font-family: var(--font); font-size: 13px; color: var(--text);
            cursor: pointer; min-width: 150px;
        }
        .sort-wrap label { font-size: 12px; color: var(--muted); white-space: nowrap; }

        /* ── Table ── */
        .tbl-scroll { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }

        thead th {
            background: var(--bg); color: var(--muted);
            font-size: 10.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em;
            padding: 9px 12px; text-align: right; white-space: nowrap;
            border-bottom: 1px solid var(--border);
            position: relative;
        }
        thead th:nth-child(1),
        thead th:nth-child(2) { text-align: left; }

        tbody tr { border-bottom: 1px solid var(--border); transition: background 0.12s; cursor: pointer; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #F9F8F4; }
        tbody tr.expanded { background: #F3F2EE; }
        tbody tr.warn-row { background: #FFFBF0; }
        tbody tr.warn-row:hover { background: #FFF5D6; }

        td { padding: 10px 12px; text-align: right; vertical-align: middle; position: relative; }
        td:nth-child(1),
        td:nth-child(2) { text-align: left; }

        /* ── Shop cell ── */
        .shop-cell { display: flex; align-items: center; gap: 10px; }
        .avatar {
            width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
            background: var(--purple-bg); color: var(--purple);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 600;
        }
        .shop-name { font-weight: 500; }
        .shop-loc  { font-size: 11px; color: var(--muted); }

        /* ── Num ── */
        .num { font-family: var(--mono); font-size: 12.5px; }

        /* ── Badges ── */
        .badge {
            display: inline-block; padding: 2px 8px; border-radius: 20px;
            font-size: 10.5px; font-weight: 600; letter-spacing: 0.03em;
        }
        .badge-ok   { background: var(--green-bg); color: var(--green); }
        .badge-over { background: var(--amber-bg); color: var(--amber); }
        .badge-bad  { background: var(--red-bg);   color: var(--red); }

        .txt-green { color: var(--green); }
        .txt-red   { color: var(--red); }
        .txt-amber { color: var(--amber); }
        .txt-muted { color: var(--muted); }
        .fw6 { font-weight: 600; }

        /* ── Status icons ── */
        .ico-ok   { color: var(--green); font-size: 15px; }
        .ico-warn { color: var(--amber); font-size: 15px; cursor: help; }

        /* ── Detail row ── */
        .detail-row { display: none; }
        .detail-row.open { display: table-row; }
        .detail-row td { background: #F3F2EE; padding: 0; }
        .detail-row.warn-row td { background: #FFFBF0; }

        .detail-inner {
            padding: 1.25rem 1.5rem;
            border-left: 3px solid var(--purple);
        }
        .detail-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr)); gap: 1rem;
        }
        .detail-item label { font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.06em; color: var(--muted); display: block; margin-bottom: 4px; }
        .detail-item span  { font-size: 14px; font-weight: 500; font-family: var(--mono); }

        /* ── Footer ── */
        tfoot td {
            background: var(--bg); border-top: 1px solid var(--border-md);
            font-size: 12px; font-weight: 600; font-family: var(--mono);
            padding: 10px 12px; text-align: right; color: var(--muted);
        }
        tfoot td:nth-child(1),
        tfoot td:nth-child(2) { text-align: left; color: var(--text); }

        /* ── Empty state ── */
        .empty-state { text-align: center; padding: 4rem 2rem; color: var(--muted); }
        .empty-state h4 { font-size: 16px; font-weight: 500; margin-bottom: 8px; color: var(--text); }
        .empty-state p  { font-size: 13px; }

        /* ── Modal ── */
        .modal-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.35);
            z-index: 9999; align-items: center; justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: var(--surface); border-radius: var(--radius);
            width: 100%; max-width: 480px; margin: 1rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        .modal-top {
            padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-top h2 { font-size: 16px; font-weight: 600; }
        .modal-body { padding: 1.5rem; }
        .modal-foot { padding: 1rem 1.5rem; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 8px; }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem 1.5rem; background: var(--bg); border-radius: var(--radius-sm); padding: 1rem; margin-bottom: 1.25rem; }
        .info-item label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); display: block; margin-bottom: 3px; }
        .info-item span  { font-size: 14px; font-weight: 500; font-family: var(--mono); }
        .info-item.big span { font-size: 20px; color: var(--green); }

        .field-group { margin-bottom: 1rem; }
        .field-group label { display: block; font-size: 12px; font-weight: 500; margin-bottom: 6px; }
        .input-row { display: flex; border: 1px solid var(--border-md); border-radius: var(--radius-sm); overflow: hidden; }
        .input-prefix { background: var(--bg); padding: 8px 12px; font-size: 13px; color: var(--muted); border-right: 1px solid var(--border-md); white-space: nowrap; }
        .input-row input[type=number] {
            flex: 1; border: none; outline: none; padding: 8px 12px;
            font-family: var(--mono); font-size: 14px; color: var(--text); background: transparent;
        }

        .quick-amounts { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
        .quick-btn {
            background: var(--bg); border: 1px solid var(--border-md);
            border-radius: var(--radius-sm); padding: 4px 10px; font-size: 12px;
            font-family: var(--mono); cursor: pointer; color: var(--text);
            transition: background 0.12s, border-color 0.12s;
        }
        .quick-btn:hover { background: var(--accent); color: #fff; border-color: var(--accent); }

        .btn-primary {
            background: var(--accent); color: #fff; border: none;
            padding: 8px 18px; border-radius: var(--radius-sm);
            font-family: var(--font); font-size: 13px; font-weight: 500; cursor: pointer;
        }
        .btn-primary:hover { opacity: 0.85; }
        .btn-secondary {
            background: transparent; color: var(--muted); border: 1px solid var(--border-md);
            padding: 8px 18px; border-radius: var(--radius-sm);
            font-family: var(--font); font-size: 13px; cursor: pointer;
        }
        .btn-secondary:hover { background: var(--bg); }

        /* ── Divider in info grid ── */
        .info-divider { grid-column: 1/-1; border: none; border-top: 1px solid var(--border); }

        /* ── Column Wizard Tooltip ── */
        .wizard-overlay {
            display: none; position: fixed; inset: 0;
            z-index: 10000; align-items: center; justify-content: center;
        }
        .wizard-overlay.open { display: flex; }

        .wizard-tooltip {
            position: absolute;
            background: var(--surface);
            border: 2px solid var(--accent);
            border-radius: var(--radius);
            box-shadow: 0 10px 40px rgba(0,0,0,0.25), 0 0 0 4px rgba(245, 158, 11, 0.2);
            width: 320px;
            max-width: calc(100vw - 40px);
            z-index: 10001;
            opacity: 0;
            transform: scale(0.9);
            transition: opacity 0.3s ease, transform 0.3s ease;
            pointer-events: none;
        }
        .wizard-tooltip.open {
            opacity: 1;
            transform: scale(1);
            pointer-events: auto;
        }

        .wizard-tooltip::before {
            content: '';
            position: absolute;
            width: 0; height: 0;
            border: 8px solid transparent;
        }

        .wizard-tooltip.position-right::before {
            left: -16px; top: 50%; transform: translateY(-50%);
            border-right-color: var(--accent);
        }
        .wizard-tooltip.position-left::before {
            right: -16px; top: 50%; transform: translateY(-50%);
            border-left-color: var(--accent);
        }
        .wizard-tooltip.position-top::before {
            bottom: -16px; left: 50%; transform: translateX(-50%);
            border-top-color: var(--accent);
        }
        .wizard-tooltip.position-bottom::before {
            top: -16px; left: 50%; transform: translateX(-50%);
            border-bottom-color: var(--accent);
        }

        .wizard-tooltip-header {
            padding: 1rem 1.25rem 0.75rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center; justify-content: space-between;
            background: linear-gradient(135deg, var(--purple) 0%, #1a3a6b 100%);
            color: white;
            border-radius: 8px 8px 0 0;
        }
        .wizard-tooltip-title {
            font-size: 15px; font-weight: 600;
            display: flex; align-items: center; gap: 8px;
        }
        .wizard-tooltip-step {
            background: rgba(255,255,255,0.2); padding: 2px 8px;
            border-radius: 10px; font-size: 10px; font-weight: 500;
        }

        .wizard-tooltip-close {
            background: none; border: none; color: white;
            font-size: 20px; cursor: pointer; opacity: 0.8;
            line-height: 1; padding: 0; width: 24px; height: 24px;
            display: flex; align-items: center; justify-content: center;
        }
        .wizard-tooltip-close:hover { opacity: 1; }

        .wizard-tooltip-body {
            padding: 1.25rem;
        }

        .wizard-tooltip-highlight {
            display: inline-block;
            background: var(--accent); color: white;
            padding: 2px 10px; border-radius: 4px;
            font-size: 18px; font-weight: 700;
            font-family: var(--mono);
            margin-bottom: 0.75rem;
        }

        .wizard-tooltip-label {
            font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em;
            color: var(--muted); margin-bottom: 4px; font-weight: 600;
        }

        .wizard-tooltip-fullname {
            font-size: 14px; font-weight: 600; color: var(--purple);
            margin-bottom: 0.75rem;
        }

        .wizard-tooltip-explanation {
            font-size: 13px; line-height: 1.6; color: var(--text);
            margin-bottom: 1rem;
        }

        .wizard-tooltip-calculation {
            background: var(--bg); border-left: 3px solid var(--green);
            padding: 0.6rem 0.75rem; border-radius: var(--radius-sm);
            font-family: var(--mono); font-size: 11.5px; color: var(--text);
            margin-bottom: 0.75rem;
        }
        .wizard-tooltip-calculation .calc-label {
            font-size: 9px; text-transform: uppercase; color: var(--muted);
            margin-bottom: 2px; font-weight: 600;
        }

        .wizard-tooltip-balance {
            background: var(--green-bg); border: 1px solid #B2DFC5;
            border-radius: var(--radius-sm); padding: 0.6rem 0.75rem;
            font-size: 11.5px; color: var(--text);
        }
        .wizard-tooltip-balance strong {
            color: var(--green);
        }

        .wizard-tooltip-footer {
            padding: 0.5rem 1.25rem 1rem;
            display: flex; align-items: center; justify-content: space-between;
            gap: 8px;
        }

        .wizard-tooltip-progress {
            font-size: 11px; color: var(--muted); font-weight: 500;
        }

        .wizard-tooltip-nav {
            display: flex; gap: 6px;
        }

        .wizard-tooltip-btn {
            padding: 5px 12px; border-radius: var(--radius-sm);
            font-family: var(--font); font-size: 12px; font-weight: 500;
            cursor: pointer; border: 1px solid var(--border-md);
            background: var(--surface); color: var(--text);
            transition: all 0.15s;
        }
        .wizard-tooltip-btn:hover {
            background: var(--bg); border-color: var(--border);
        }
        .wizard-tooltip-btn-primary {
            background: var(--accent); border-color: var(--accent);
            color: white;
        }
        .wizard-tooltip-btn-primary:hover {
            background: #e58e0b; border-color: #e58e0b;
        }
        .wizard-tooltip-btn:disabled {
            opacity: 0.4; cursor: not-allowed;
        }

        .wizard-tooltip-btn-skip {
            background: transparent; border: none; color: var(--muted);
            font-size: 11px; cursor: pointer; text-decoration: underline;
        }
        .wizard-tooltip-btn-skip:hover {
            color: var(--text);
        }

        /* Highlight animation for column */
        @keyframes pulse-highlight {
            0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(245, 158, 11, 0); }
        }
        .wizard-highlight-column {
            position: relative;
            animation: pulse-highlight 2s infinite;
            border-radius: 4px;
        }
        .wizard-highlight-column::after {
            content: '';
            position: absolute; inset: -3px;
            border: 2px solid var(--accent);
            border-radius: 6px;
            pointer-events: none;
        }

        /* Close hint */
        .wizard-close-hint {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            background: rgba(11, 30, 61, 0.9); color: white;
            padding: 8px 16px; border-radius: 20px;
            font-size: 12px; font-weight: 500;
            z-index: 10002; pointer-events: none;
            opacity: 0; transition: opacity 0.3s;
        }
        .wizard-close-hint.show {
            opacity: 1;
        }
    </style>
</head>
<body>
<div class="row">
        @include('user/sidenav')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <button class="btn-close-sm" onclick="this.closest('.alert').remove()">×</button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
                <button class="btn-close-sm" onclick="this.closest('.alert').remove()">×</button>
            </div>
        @endif

        {{-- Page header --}}
        <div class="page-header">
            <div>
                <div class="page-title">Shop report</div>
                <div class="page-sub">
                    @if(is_string($dateParam) && str_contains($dateParam, 'to'))
                        {{ $dateParam }}
                    @else
                        {{ date('l, F d Y', strtotime($dateParam)) }}
                    @endif
                </div>
            </div>
            <div class="header-actions">
                <form method="GET" action="shopReport" id="dateFilterForm">
                    <div class="date-form" style="display: flex; gap: 8px; align-items: center;">
                        <input type="date" name="date_from" id="dateFromInput" value="{{ $dateFrom ?? '' }}" placeholder="From">
                        <span style="color: var(--muted);">to</span>
                        <input type="date" name="date_to" id="dateToInput" value="{{ $dateTo ?? '' }}" placeholder="To">
                        <button type="submit">Filter</button>
                    </div>
                </form>
                <a class="btn-icon" onclick="exportToExcel()">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="1" y="1" width="14" height="14" rx="2"/><path d="M5 5l6 6M11 5l-6 6"/></svg>
                    Excel
                </a>
                <a class="btn-icon" onclick="exportToPDF()">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 1h6l4 4v10H4V1z"/><path d="M10 1v4h4"/><path d="M6 10h4M6 13h2"/></svg>
                    PDF
                </a>
                <button class="btn-icon" onclick="startWizard()" style="background: var(--purple); color: white; border-color: var(--purple);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                    </svg>
                    Column Guide
                </button>
            </div>
        </div>

        {{-- Metric cards --}}
        <div class="metrics">
            <div class="metric purple">
                <div class="metric-label">Total shops</div>
                <div class="metric-value">{{ $activeShopsCount }}</div>
                <div class="metric-sub">{{ $shopsWithSalesCount }} with sales today</div>
            </div>
            <div class="metric red">
                <div class="metric-label">Total sales</div>
                <div class="metric-value">{{ number_format($totals->total_sales) }}</div>
                <div class="metric-sub">Cash {{ number_format($totals->cash_sales) }} · Credit {{ number_format($totals->credit_sales) }}</div>
            </div>
            <div class="metric green">
                <div class="metric-label">Total profit</div>
                <div class="metric-value">{{ number_format($totals->profit) }}</div>
                <div class="metric-sub">After expenses & debt</div>
            </div>
            <div class="metric amber">
                <div class="metric-label">Cash expected</div>
                <div class="metric-value">{{ number_format($totals->cash_amount) }}</div>
                <div class="metric-sub">Submitted {{ number_format($totals->cash_submitted) }}</div>
            </div>
        </div>

        {{-- Main table panel --}}
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">All shops — {{ date('F d, Y', strtotime($dateParam)) }}</span>
                <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <div class="sort-wrap">
                        <label>Sort by:</label>
                        <select id="sortSelect" onchange="sortShops()">
                            <option value="name_asc">Name (A-Z)</option>
                            <option value="name_desc">Name (Z-A)</option>
                            <option value="balanced">Balanced</option>
                            <option value="unbalanced">Unbalanced</option>
                            <option value="status_settled">Status: Settled</option>
                            <option value="status_underpaid">Status: Underpaid</option>
                            <option value="status_overpaid">Status: Overpaid</option>
                            <option value="sales_high">Sales (High-Low)</option>
                            <option value="sales_low">Sales (Low-High)</option>
                            <option value="profit_high">Profit (High-Low)</option>
                            <option value="profit_low">Profit (Low-High)</option>
                        </select>
                    </div>
                    <div class="search-wrap">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="7" cy="7" r="5"/><path d="M11 11l3 3"/>
                        </svg>
                        <input type="text" id="shopSearch" placeholder="Search shops…" oninput="filterShops()">
                    </div>
                </div>
            </div>

            <div class="tbl-scroll">
                @if($shopReports->isEmpty())
                    <div class="empty-state">
                        <h4>No shops found</h4>
                        <p>No active shops found for {{ date('F d, Y', strtotime($dateParam)) }}.</p>
                    </div>
                @else
                <table id="shopTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Shop</th>
                            <th title="Cash Sale">C.Sale</th>
                            <th title="Credit Sale">Cr.Sale</th>
                            <th title="Total Sale">T.Sales</th>
                            <th title="Return">T.Ret</th>
                            <th title="Offered Items">Offered</th>
                            <th title="Discount">Dsc</th>
                            <th title="Expenses">Exp</th>
                            <th title="Profit / Loss">P/L</th>
                            <th title="Paid Invoice">P.Inv</th>
                            <th title="Cash Receivings">Ca.R</th>
                            <th title="Credit Receivings">Cr.R</th>
                            <th title="Paid Receivings">P.R</th>
                            <th title="Chip Deposit">Chip.D</th>
                            <th title="Chip Used">Chip.U</th>
                            <th title="Cash Amount">C.A</th>
                            <th title="Cash Submit">C.S</th>
                            <th title="Bank Deposit">B.D</th>
                            
                            <th title="Bank Difference">B.Diff</th>
                            <th title="Difference">Diff</th>
                            <th title="Cost Worth">Inventory</th>
                            <th>Status</th>
                            <th>Balance</th> x
                        </tr>
                    </thead>
                    <tbody id="shopTableBody">
                        @foreach ($shopReports as $index => $shop)
                            @php
                                $cashSale   = $shop->cash_sales;
                                $creditSale = $shop->credit_sales;
                                $totalSale  = $shop->total_sales;
                                $cashAmount = $shop->cash_amount;
                                $cashSubmit = $shop->cash_submitted;
                                $bankDiff = $shop->bank_diff < 1 && $shop->bank_diff > -1;

                                $diff       = $cashAmount - $cashSubmit;
                
                                
                                $profitVal = $totalSale
                                    - ($shop->expenses ?? 0)
                                    - ($shop->cash_receivings ?? 0)
                                    - ($shop->credit_receivings ?? 0);

                                $diffClass = abs($diff) < 0.01 ? 'txt-muted' : ($diff > 0 ? 'txt-red' : 'txt-green');

                                $salesBalanced = abs(($cashSale + $creditSale) - $totalSale) < 0.01;
                                $cashBalanced  = abs($diff) < 0.01;
                                $isBalanced    = $salesBalanced && $cashBalanced && $bankDiff;
                                
                                $isDiffZero = abs($diff) < 0.1;
                                $statusBadge = $isDiffZero ? 'badge-ok'
                                                     : ($diff > 0  ? 'badge-bad' : 'badge-over');
                                $statusText  = $isDiffZero ? 'Settled'
                                                     : ($diff > 0  ? 'Underpaid' : 'Overpaid');

                                $balanceIssues = [];
                                if (!$salesBalanced) {
                                    $balanceIssues[] = 'Sales mismatch: ' . number_format($cashSale) . ' + ' . number_format($creditSale) . ' ≠ ' . number_format($totalSale);
                                }
                                if (!$cashBalanced) {
                                    $balanceIssues[] = 'Cash: expected ' . number_format($cashAmount) . ', got ' . number_format($cashSubmit);
                                }
                                $initials = strtoupper(substr($shop->shop_name, 0, 1));
                                $parts    = explode(' ', trim($shop->shop_name));
                                if (count($parts) > 1) $initials .= strtoupper(substr($parts[1], 0, 1));
                            @endphp

                            {{-- Main row --}}
                            <tr class="shop-row {{ !$isBalanced ? 'warn-row' : '' }}"
                                data-name="{{ strtolower($shop->shop_name) }}"
                                onclick="toggleDetail({{ $index }})">
                                <td class="txt-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="shop-cell">
                                        <div class="avatar">{{ $initials }}</div>
                                        <div>
                                            <div class="shop-name">{{ $shop->shop_name }}</div>
                                            <div class="shop-loc">{{ $shop->location }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="num" title="Cash Sale: {{ number_format($cashSale) }}">{{ number_format($cashSale) }}</td>
                                <td class="num" title="Credit Sale: {{ number_format($creditSale) }}">{{ number_format($creditSale) }}</td>
                                <td class="num fw6" title="Total Sales: {{ number_format($cashSale) }} + {{ number_format($creditSale) }} = {{ number_format($totalSale) }}">{{ number_format($totalSale) }} <span class="txt-muted" style="font-size:11px;">({{ $shop->total_product_quantity }})</span></td>
                                <td class="num txt-red" title="Total Return: -{{ number_format($shop->total_return ?? 0) }}">-{{ number_format($shop->total_return ?? 0) }}</td>
                                <td class="num text-info fw6" title="Offered Items: {{ number_format($shop->total_offer ?? 0) }}">{{ number_format($shop->total_offer ?? 0) }}</td>
                                <td class="num txt-muted" title="Discount: {{ number_format($shop->discount) }}">{{ number_format($shop->discount) }}</td>
                                <td class="num txt-muted" title="Expenses: {{ number_format($shop->expenses) }}">{{ number_format($shop->expenses) }}</td>
                                <td class="num {{ $profitVal >= 0 ? 'txt-green' : 'txt-red' }}" title="Profit/Loss: {{ number_format($totalSale) }} - {{ number_format($shop->expenses) }} - {{ number_format($shop->cash_receivings) }} - {{ number_format($shop->credit_receivings) }} = {{ number_format($profitVal) }}">{{ number_format($profitVal) }}</td>
                                <td class="num" title="Paid Invoices: {{ number_format($shop->paid_invoices) }}">{{ number_format($shop->paid_invoices) }}</td>
                                <td class="num" title="Cash Receivings: {{ number_format($shop->cash_receivings) }}">{{ number_format($shop->cash_receivings) }}</td>
                                <td class="num" title="Credit Receivings: {{ number_format($shop->credit_receivings) }} (Qty: {{ number_format($shop->credit_receivings_quantity ?? 0) }})">{{ number_format($shop->credit_receivings) }} <span class="txt-muted" style="font-size:11px;">({{ number_format(($shop->credit_receivings_quantity ?? 0)) }})</span></td>
                                <td class="num" title="Paid Receivings: {{ number_format($shop->paid_receivings) }} - Returns {{ number_format($shop->paid_receivings) }} (Qty: {{ number_format($shop->paid_receivings_quantity ?? 0) }})">{{ number_format($shop->paid_receivings) }} <span class="txt-muted" style="font-size:11px;">({{ number_format($shop->paid_receivings_quantity ?? 0) }})</span></td>
                                <td class="num" title="Chip Deposit: {{ number_format($shop->totalChip) }}">{{ number_format($shop->totalChip) }}</td>
                                <td class="num" title="Chip Used: {{ number_format($shop->chip_used) }}">{{ number_format($shop->chip_used) }}</td>
                                <td class="num" title="Cash Amount (Expected): {{ number_format($cashAmount) }}">{{ number_format($cashAmount) }}</td>
                                <td class="num" title="Cash Submit: {{ number_format($cashSubmit) }}">{{ number_format($cashSubmit) }}</td>
                                <td class="num" title="Bank Deposit: {{ number_format($shop->total_bank) }}">{{ number_format($shop->total_bank) }}</td>
                                
                                <td class="num" title="Bank Difference: {{ number_format($shop->bank_diff) }}">{{ number_format($shop->bank_diff) }}</td>
                                <td class="num fw6 {{ $diffClass }}" title="Difference: Expected ({{ number_format($cashAmount) }}) - Submitted ({{ number_format($cashSubmit) }}) = {{ number_format(abs($diff)) }}">{{ number_format(abs($diff)) }}</td>
                                <td class="num" title="Cost Worth (Inventory): {{ number_format($shop->cost_worth ?? 0) }}">{{ number_format($shop->cost_worth ?? 0) }}</td>
                                <td>
                                    <span class="badge {{ $statusBadge }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    @if(!$isBalanced)
                                        <a href="{{ url('user/balance-check/discrepancies/' . $shop->shop_id . '/' . $dateParam) }}"
                                           class="ico-warn"
                                           title="View discrepancies: {{ implode(' | ', $balanceIssues) }}"
                                           onclick="event.stopPropagation();"
                                           style="text-decoration: none;">
                                           &#9651;
                                        </a>
                                    @else
                                        <span class="ico-ok">&#10003;</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Detail row --}}
                            <tr id="detail-{{ $index }}" class="detail-row {{ !$isBalanced ? 'warn-row' : '' }}">
                                <td colspan="22">
                                    <div class="detail-inner">
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <label>Shop</label>
                                                <span>{{ $shop->shop_name }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <label>Location</label>
                                                <span>{{ $shop->location }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <label>Expected cash</label>
                                                <span>{{ number_format($shop->expected_cash) }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <label>Submitted</label>
                                                <span>{{ number_format($shop->cash_submitted) }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <label>Difference</label>
                                                <span class="{{ $diffClass }}">{{ number_format($diff) }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <label>Total debt</label>
                                                <span>{{ number_format($shop->debt) }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <label>Balance Status</label>
                                                <span class="{{ $isBalanced ? 'txt-green' : 'txt-red' }}">
                                                    {{ $isBalanced ? 'Balanced' : 'Unbalanced' }}
                                                </span>
                                            </div>
                                            <div class="detail-item">
                                                <label>Transactions</label>
                                                <span>{{ number_format($shop->total_transactions) }}</span>
                                            </div>
                                        </div>
                                        
                                        @if(!$isBalanced && count($balanceIssues) > 0)
                                        <div style="margin-top:1rem; padding: 0.75rem; background: var(--red-bg); border: 1px solid #F5C6C2; border-radius: var(--radius-sm);">
                                            <div style="font-size: 12px; font-weight: 600; color: var(--red); margin-bottom: 6px;">⚠️ Balance Issues Detected:</div>
                                            <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: var(--red);">
                                                @foreach($balanceIssues as $issue)
                                                    <li style="margin-bottom: 4px;">{{ $issue }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif
                                        
                                        <div style="margin-top:1rem; display: flex; gap: 8px; flex-wrap: wrap;">
                                            <button class="btn-primary" style="font-size:12px; padding:6px 14px;"
                                                onclick="event.stopPropagation(); openCashModal({{ $index }})">
                                                Submit cash
                                            </button>
                                            @if($shop->cash_submitted > 0)
                                            <form method="POST" action="{{ url('user/cashDelete') }}" style="display: inline;" onsubmit="return confirm('Delete cash submission for {{ $shop->shop_name }}?');">
                                                @csrf
                                                <input type="hidden" name="shop_id" value="{{ $shop->shop_id }}">
                                                <input type="hidden" name="date" value="{{ $dateParam }}">
                                                <button type="submit" class="btn-icon" style="font-size:12px; padding:6px 14px; background: var(--red); border-color: var(--red); color: white;">
                                                    🗑️ Delete
                                                </button>
                                            </form>
                                            @endif
                                            @if(!$isBalanced)
                                            <a href="{{ url('user/balance-check/discrepancies/' . $shop->shop_id . '/' . $dateParam) }}"
                                               class="btn-icon"
                                               style="font-size:12px; padding:6px 14px; background: var(--amber); border-color: var(--amber); color: white;"
                                               onclick="event.stopPropagation();">
                                                🔍 View Issues
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="2">Totals</td>
                            <td title="Total Cash Sales: SUM of all shop cash sales">{{ number_format($totals->cash_sales) }}</td>
                            <td title="Total Credit Sales: SUM of all shop credit sales">{{ number_format($totals->credit_sales) }}</td>
                            <td class="fw6" title="Total Sales (Net): {{ number_format($totals->total_sales) }} - {{ number_format($totals->total_return) }} = {{ number_format($totals->total_sales - $totals->total_return) }}">{{ number_format($totals->total_sales - $totals->total_return) }}</td>
                            <td title="Total Return: SUM of all shop returns">{{ number_format($totals->total_return ?? 0) }}</td>
                            <td title="Total Returned Receivings: SUM of all shop returned receivings">{{ number_format($totals->returned_receivings ?? 0) }}</td>
                            <td class="fw6" title="Total Offered Items: SUM of all shop offered items">{{ number_format($totals->offer ?? 0) }}</td>
                            <td title="Total Discount: SUM of all shop discounts">{{ number_format($totals->discount) }}</td>
                            <td title="Total Expenses: SUM of all shop expenses">{{ number_format($totals->expenses) }}</td>
                            <td class="{{ $totals->profit >= 0 ? 'txt-green' : 'txt-red' }}" title="Total Profit/Loss: (Total Sales - Returns) - Expenses - Cash Receivings - Credit Receivings = {{ number_format($totals->profit) }}">{{ number_format($totals->profit) }}</td>
                            <td title="Total Paid Invoices: SUM of all shop paid invoices">{{ number_format($totals->paid_invoices) }}</td>
                            <td title="Total Cash Receivings: SUM of all shop cash receivings">{{ number_format($totals->cash_receivings) }}</td>
                            <td title="Total Credit Receivings: SUM of all shop credit receivings">{{ number_format($totals->credit_receivings) }}</td>
                            <td title="Total Paid Receivings: SUM of all shop paid receivings">{{ number_format($totals->paid_receivings) }}</td>
                            <td title="Total Chip Deposit: SUM of all shop chip deposits">{{ number_format($totals->totalChip) }}</td>
                            <td title="Total Chip Used: SUM of all shop chip usage">{{ number_format($totals->chip_used) }}</td>
                            <td title="Total Cash Amount (Expected): SUM of all shop expected cash">{{ number_format($totals->cash_amount) }}</td>
                            <td title="Total Cash Submitted: SUM of all shop submitted cash">{{ number_format($totals->cash_submitted) }}</td>
                            <td title="Total Bank Deposit: SUM of all shop bank deposits">{{ number_format($totals->total_bank) }}</td>
                            
                            @php
                                $totalDiff = $totals->cash_amount - $totals->cash_submitted;
                                $totalDiffClass = abs($totalDiff) < 0.01 ? '' : ($totalDiff > 0 ? 'txt-red' : 'txt-green');
                            @endphp
                            <td class="{{ $totalDiffClass }} fw6" title="Total Difference: Expected Cash ({{ number_format($totals->cash_amount) }}) - Submitted ({{ number_format($totals->cash_submitted) }}) = {{ number_format(abs($totalDiff)) }}">
                                {{ number_format(abs($totalDiff)) }}
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                @endif
            </div>
        </div>

    </main>
</div>

{{-- Column Tooltip Wizard --}}
<div class="wizard-overlay" id="wizardOverlay">
    <div class="wizard-tooltip" id="wizardTooltip">
        <div class="wizard-tooltip-header">
            <div class="wizard-tooltip-title">
                <span class="wizard-tooltip-step" id="tooltipStep">1/22</span>
                <span id="tooltipTitle">C.Sale</span>
            </div>
            <button class="wizard-tooltip-close" onclick="closeWizard()">×</button>
        </div>
        <div class="wizard-tooltip-body">
            <div class="wizard-tooltip-label">Column Explanation</div>
            <div class="wizard-tooltip-fullname" id="tooltipFullName">Cash Sales</div>
            <div class="wizard-tooltip-explanation" id="tooltipExplanation">
                Total cash sales made by the shop today.
            </div>
            <div class="wizard-tooltip-calculation">
                <div class="calc-label">Calculation</div>
                <div id="tooltipCalculation">SUM of all cash sales</div>
            </div>
            <div class="wizard-tooltip-balance">
                <strong>✓ Balance:</strong> <span id="tooltipBalance">Part of total sales</span>
            </div>
        </div>
        <div class="wizard-tooltip-footer">
            <div class="wizard-tooltip-progress" id="tooltipProgress">1 of 22</div>
            <div class="wizard-tooltip-nav">
                <button class="wizard-tooltip-btn-skip" onclick="closeWizard()">Skip</button>
                <button class="wizard-tooltip-btn" id="prevBtn" onclick="prevStep()">← Previous</button>
                <button class="wizard-tooltip-btn wizard-tooltip-btn-primary" id="nextBtn" onclick="nextStep()">Next →</button>
            </div>
        </div>
    </div>
    <div class="wizard-close-hint" id="closeHint">Press ESC or click outside to close</div>
</div>

{{-- Cash Submit Modal --}}
<div class="modal-overlay" id="cashModal">
    <div class="modal-box">
        <div class="modal-top">
            <h2>Submit cash</h2>
            <button class="btn-close-sm" onclick="closeModal()">×</button>
        </div>
        <form id="cashForm" method="POST" action="{{ url('user/cashSubmit') }}">
            @csrf
            <div class="modal-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Shop</label>
                        <span id="m-shop">—</span>
                    </div>
                    <div class="info-item">
                        <label>Date</label>
                        <input type="date" name="date" id="m-date" style="width: 100%; font-family: var(--mono); font-weight: 500; font-size: 14px; color: var(--text); background: var(--surface); border: 1px solid var(--border-md); border-radius: var(--radius-sm); padding: 2px 6px;">
                    </div>
                    <hr class="info-divider">
                    <div class="info-item">
                        <label>Total sales</label>
                        <span id="m-sales">0</span>
                    </div>
                    <div class="info-item">
                        <label>Cash sales</label>
                        <span id="m-cash-sales">0</span>
                    </div>
                    <div class="info-item">
                        <label>Expenses</label>
                        <span id="m-exp">0</span>
                    </div>
                    <div class="info-item">
                        <label>Discount</label>
                        <span id="m-dsc">0</span>
                    </div>
                    <hr class="info-divider">
                    <div class="info-item big">
                        <label>Expected cash</label>
                        <span id="m-expected">0</span>
                    </div>
                    <div class="info-item">
                        <label>Remaining</label>
                        <span id="m-remaining" style="color:var(--red);">0</span>
                    </div>
                </div>

                <div class="field-group">
                    <label for="cashAmount">Cash amount to submit</label>
                    <div class="input-row">
                        <span class="input-prefix">TSh</span>
                        <input type="number" id="cashAmount" name="submitted_cash" step="0.01" min="0.01" required>
                    </div>
                    <div class="quick-amounts">
                        <button type="button" class="quick-btn" onclick="setAmt(5000)">5,000</button>
                        <button type="button" class="quick-btn" onclick="setAmt(10000)">10,000</button>
                        <button type="button" class="quick-btn" onclick="setAmt(20000)">20,000</button>
                        <button type="button" class="quick-btn" onclick="setAmt(50000)">50,000</button>
                        <button type="button" class="quick-btn" onclick="setAmt(100000)">100,000</button>
                    </div>
                </div>

                <input type="hidden" name="shop_id" id="m-shop-id">
                <input type="hidden" name="sales" id="m-sales-hidden">
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-primary">Submit cash</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const shopData = @json($shopReports);

    // ── Toggle detail row ──
    function toggleDetail(i) {
        const detail = document.getElementById('detail-' + i);
        const main   = detail.previousElementSibling;
        const isOpen = detail.classList.contains('open');

        document.querySelectorAll('.detail-row.open').forEach(r => r.classList.remove('open'));
        document.querySelectorAll('.shop-row.expanded').forEach(r => r.classList.remove('expanded'));

        if (!isOpen) {
            detail.classList.add('open');
            main.classList.add('expanded');
        }
    }

    // ── Search filter ──
    function filterShops() {
        const q = document.getElementById('shopSearch').value.toLowerCase();
        document.querySelectorAll('#shopTableBody .shop-row').forEach(row => {
            const match = row.getAttribute('data-name').includes(q);
            row.style.display = match ? '' : 'none';
            const next = row.nextElementSibling;
            if (next && next.classList.contains('detail-row')) {
                next.style.display = match ? '' : 'none';
            }
        });
    }

    // ── Sort shops ──
    function sortShops() {
        const sortValue = document.getElementById('sortSelect').value;
        const tbody = document.getElementById('shopTableBody');
        const rows = Array.from(tbody.querySelectorAll('.shop-row'));
        
        let sortKey, ascending;
        
        switch(sortValue) {
            case 'name_asc': sortKey = 'name'; ascending = true; break;
            case 'name_desc': sortKey = 'name'; ascending = false; break;
            case 'balanced': sortKey = 'balanced'; ascending = true; break;
            case 'unbalanced': sortKey = 'balanced'; ascending = false; break;
            case 'status_settled': sortKey = 'status'; ascending = true; break;
            case 'status_underpaid': sortKey = 'status'; ascending = false; break;
            case 'status_overpaid': sortKey = 'status'; ascending = false; break;
            case 'sales_high': sortKey = 'total_sales'; ascending = false; break;
            case 'sales_low': sortKey = 'total_sales'; ascending = true; break;
            case 'profit_high': sortKey = 'profit'; ascending = false; break;
            case 'profit_low': sortKey = 'profit'; ascending = true; break;
            default: sortKey = 'name'; ascending = true;
        }
        
        rows.sort((a, b) => {
            let aVal, bVal;
            
            if (sortKey === 'name') {
                aVal = a.getAttribute('data-name');
                bVal = b.getAttribute('data-name');
                return ascending ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
            }
            else if (sortKey === 'balanced') {
                const aBalanced = !a.classList.contains('warn-row');
                const bBalanced = !b.classList.contains('warn-row');
                if (ascending) {
                    return aBalanced === bBalanced ? 0 : aBalanced ? -1 : 1;
                } else {
                    return aBalanced === bBalanced ? 0 : aBalanced ? 1 : -1;
                }
            }
            else if (sortKey === 'status') {
                const aStatus = a.querySelector('.badge').textContent.trim().toLowerCase();
                const bStatus = b.querySelector('.badge').textContent.trim().toLowerCase();
                
                if (sortValue === 'status_settled') {
                    return aStatus === 'settled' && bStatus !== 'settled' ? -1 : 1;
                } else if (sortValue === 'status_underpaid') {
                    return aStatus === 'underpaid' && bStatus !== 'underpaid' ? -1 : 1;
                } else if (sortValue === 'status_overpaid') {
                    return aStatus === 'overpaid' && bStatus !== 'overpaid' ? -1 : 1;
                }
                return 0;
            }
            else {
                const aNum = parseFloat(a.querySelector(`td:nth-child(${getColumnIndex(sortKey)})`).textContent.replace(/,/g, '')) || 0;
                const bNum = parseFloat(b.querySelector(`td:nth-child(${getColumnIndex(sortKey)})`).textContent.replace(/,/g, '')) || 0;
                return ascending ? aNum - bNum : bNum - aNum;
            }
        });
        
        rows.forEach(row => {
            tbody.appendChild(row);
            const detailRow = row.nextElementSibling;
            if (detailRow && detailRow.classList.contains('detail-row')) {
                tbody.appendChild(detailRow);
            }
        });
        
        updateRowNumbers();
    }
    
    function getColumnIndex(field) {
        const columnMap = {
            'cash_sales': 3,
            'credit_sales': 4,
            'total_sales': 5,
            'total_return': 6,
            'total_offer': 7,
            'discount': 8,
            'expenses': 9,
            'profit': 10,
            'paid_invoices': 11,
            'cash_receivings': 12,
            'credit_receivings': 13,
            'paid_receivings': 14,
            'totalChip': 15,
            'chip_used': 16,
            'cash_amount': 17,
            'cash_submitted': 18,
            'total_bank': 19,
            'bank_diff': 20,
            'cost_worth': 22
        };
        return columnMap[field] || 1;
    }
    
    function updateRowNumbers() {
        document.querySelectorAll('#shopTableBody .shop-row').forEach((row, index) => {
            row.cells[0].textContent = index + 1;
        });
    }

    // ── Cash modal ──
    function fmt(n) { return new Intl.NumberFormat().format(Math.round(n || 0)); }

    function openCashModal(i) {
        const s = shopData[i];
        document.getElementById('m-shop').textContent       = s.shop_name;
        document.getElementById('m-sales').textContent      = fmt(s.total_sales);
        document.getElementById('m-cash-sales').textContent = fmt(s.cash_sales);
        document.getElementById('m-exp').textContent        = fmt(s.expenses);
        document.getElementById('m-dsc').textContent        = fmt(s.discount);
        document.getElementById('m-expected').textContent   = fmt(s.expected_cash);
        document.getElementById('m-remaining').textContent  = fmt(s.cash_difference);
        document.getElementById('m-shop-id').value          = s.shop_id;
        document.getElementById('m-sales-hidden').value     = s.expected_cash;
        document.getElementById('cashAmount').value         = s.cash_difference > 0 ? s.cash_difference : 0;
        // Set date to today
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        document.getElementById('m-date').value = `${year}-${month}-${day}`;
        document.getElementById('cashModal').classList.add('open');
    }
    
    function openEditCashModal(i) {
        const s = shopData[i];
        document.getElementById('m-shop').textContent       = s.shop_name;
        document.getElementById('m-sales').textContent      = fmt(s.total_sales);
        document.getElementById('m-cash-sales').textContent = fmt(s.cash_sales);
        document.getElementById('m-exp').textContent        = fmt(s.expenses);
        document.getElementById('m-dsc').textContent        = fmt(s.discount);
        document.getElementById('m-expected').textContent   = fmt(s.expected_cash);
        document.getElementById('m-remaining').textContent  = fmt(s.cash_submitted);
        document.getElementById('m-shop-id').value          = s.shop_id;
        document.getElementById('m-sales-hidden').value     = s.expected_cash;
        document.getElementById('cashAmount').value         = s.cash_submitted;
        
        document.querySelector('#cashModal .modal-top h2').textContent = 'Edit Submitted Cash';
        
        document.getElementById('cashModal').classList.add('open');
    }

    function closeModal() {
        document.getElementById('cashModal').classList.remove('open');
        document.getElementById('cashAmount').value = '';
    }

    document.getElementById('cashModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    function setAmt(n) { document.getElementById('cashAmount').value = n; }

    // ── Exports ──
    function exportToExcel() {
        const dateFrom = document.getElementById('dateFromInput').value;
        const dateTo = document.getElementById('dateToInput').value;
        let url = 'exportShopReport?format=excel';
        if (dateFrom && dateTo) {
            url += '&date_from=' + dateFrom + '&date_to=' + dateTo;
        } else {
            url += '&date={{ $dateParam }}';
        }
        window.location.href = url;
    }
    function exportToPDF() {
        const dateFrom = document.getElementById('dateFromInput').value;
        const dateTo = document.getElementById('dateToInput').value;
        let url = 'exportShopReport?format=pdf';
        if (dateFrom && dateTo) {
            url += '&date_from=' + dateFrom + '&date_to=' + dateTo;
        } else {
            url += '&date={{ $dateParam }}';
        }
        window.location.href = url;
    }

    // ── Auto-submit on date change ──
    document.getElementById('dateInput').addEventListener('change', function() {
        document.getElementById('dateFilterForm').submit();
    });

    // ── Column Wizard Tooltip System ──
    const wizardColumns = [
        {
            title: "#",
            fullName: "Row Number",
            explanation: "Shows the sequential row number for each shop. Reference only, no calculations.",
            calculation: "Index + 1 (starting from 1)",
            balanceCheck: "N/A - Reference only"
        },
        {
            title: "Shop",
            fullName: "Shop Name & Location",
            explanation: "Displays the shop name and its location. Each shop has an avatar with initials. Click to expand details.",
            calculation: "From shops table",
            balanceCheck: "N/A - Identification only"
        },
        {
            title: "C.Sale",
            fullName: "Cash Sales",
            explanation: "Total cash sales made by the shop today (TSh transactions). Key component of expected cash.",
            calculation: "SUM of all cash sales from sales table",
            balanceCheck: "Part of total sales: Cash + Credit = Total Sales"
        },
        {
            title: "Cr.Sale",
            fullName: "Credit Sales",
            explanation: "Total credit sales where payment is deferred. Combined with cash sales to form total sales.",
            calculation: "SUM of all credit sales from sales table",
            balanceCheck: "Part of total sales: Cash + Credit = Total Sales"
        },
        {
            title: "T.Sales",
            fullName: "Total Sales",
            explanation: "Total revenue including cash and credit sales. Parentheses show quantity of products sold.",
            calculation: "Cash Sales + Credit Sales",
            balanceCheck: "Must equal Cash Sale + Credit Sale (within 0.01 tolerance)"
        },
        {
            title: "T.Ret",
            fullName: "Total Returns",
            explanation: "Total value of product returns (shown as negative/red). Reduces revenue but not cash balance.",
            calculation: "SUM of all returns (negative amounts)",
            balanceCheck: "Returns reduce profit but don't affect cash balance directly"
        },
        {
            title: "Offered",
            fullName: "Offered Items Discount",
            explanation: "Discount from promotional/offered items. Value reduction from special pricing.",
            calculation: "SUM of all offer discounts",
            balanceCheck: "Reduces profit and total sales amount"
        },
        {
            title: "Dsc",
            fullName: "Discount",
            explanation: "Regular discounts (coupons, promotions). Further reduces effective sales amount.",
            calculation: "SUM of all discount amounts",
            balanceCheck: "Reduces profit and total sales amount"
        },
        {
            title: "Exp",
            fullName: "Expenses",
            explanation: "Daily expenses (rent, utilities, etc.). Deducted from revenue for profit calculation.",
            calculation: "SUM of all expenses for the shop",
            balanceCheck: "Deducted from profit calculation"
        },
        {
            title: "P/L",
            fullName: "Profit / Loss",
            explanation: "Net profit (green) or loss (red). Green = profitable, Red = loss.",
            calculation: "Total Sales - Returns - Expenses - Cash Receivings - Credit Receivings",
            balanceCheck: "Shows business health; negative values indicate losses"
        },
        {
            title: "P.Inv",
            fullName: "Paid Invoices",
            explanation: "Total paid invoices from customers today. Collected payments from credit sales.",
            calculation: "SUM of paid invoice amounts",
            balanceCheck: "Increases cash available"
        },
        {
            title: "Ca.R",
            fullName: "Cash Receivings",
            explanation: "Cash received from miscellaneous sources (rebates, other inflows).",
            calculation: "SUM of all cash receivings entries",
            balanceCheck: "Added to expected cash: increases cash amount"
        },
        {
            title: "Cr.R",
            fullName: "Credit Receivings",
            explanation: "Credit received (non-cash). Parentheses show quantity. Represents inventory/services on credit.",
            calculation: "SUM of credit receivings amounts",
            balanceCheck: "Doesn't affect cash balance directly (credit transaction)"
        },
        {
            title: "P.R",
            fullName: "Paid Receivings",
            explanation: "Payments for previously received credit. Parentheses show quantity. Reduces outstanding credit.",
            calculation: "SUM of paid receivings amounts",
            balanceCheck: "May affect cash if paid in cash"
        },
        {
            title: "C.A",
            fullName: "Cash Amount (Expected)",
            explanation: "Expected cash in register at day end. Based on cash sales, expenses, and receivings.",
            calculation: "Cash Sales - Expenses + Cash Receivings - Paid Receivings",
            balanceCheck: "This is the TARGET amount that should be submitted"
        },
        {
            title: "C.S",
            fullName: "Cash Submit",
            explanation: "Actual cash submitted to main office. Difference from Expected shows shortfall/excess.",
            calculation: "Actual cash submitted (via 'Submit cash' button)",
            balanceCheck: "Should equal Cash Amount (C.A) for the shop to be balanced"
        },
        {
            title: "B.D",
            fullName: "Bank Deposit",
            explanation: "Total amount deposited directly to bank. Separate from cash submission.",
            calculation: "SUM of bank deposits for the shop",
            balanceCheck: "Part of overall cash flow tracking"
        },
        {
            title: "Chip.D",
            fullName: "Chip Deposit",
            explanation: "Amount deposited into shop's chip account (digital/prepaid balance).",
            calculation: "SUM of chip deposits",
            balanceCheck: "Tracks digital wallet activity"
        },
        {
            title: "Chip.U",
            fullName: "Chip Used",
            explanation: "Chips spent/used today. Reduces chip balance.",
            calculation: "SUM of chip usage/expenditure",
            balanceCheck: "Chip deposits minus chip used = net chip change"
        },
        {
            title: "B.Diff",
            fullName: "Bank Difference",
            explanation: "Difference between expected and actual bank deposits.",
            calculation: "Expected bank amount - Actual bank deposit",
            balanceCheck: "Should be zero for balanced books"
        },
        {
            title: "Diff",
            fullName: "Difference (Cash)",
            explanation: "Critical: Expected (C.A) minus Submitted (C.S). Green/negative = overpaid. Red/positive = underpaid.",
            calculation: "Cash Amount (Expected) - Cash Submit (Actual)",
            balanceCheck: "ZERO = balanced. Positive = underpaid. Negative = overpaid."
        },
        {
            title: "Inventory",
            fullName: "Cost Worth (Inventory Value)",
            explanation: "Total cost value of inventory in stock (wholesale cost, not selling price).",
            calculation: "SUM of (quantity × cost price) for all products in stock",
            balanceCheck: "N/A - Asset valuation, not a daily transaction"
        },
        {
            title: "Status",
            fullName: "Balance Status",
            explanation: "Overall financial status: Settled (balanced), Underpaid (shortfall), Overpaid (excess).",
            calculation: "Based on Difference (Diff) value",
            balanceCheck: "Settled when Diff ≈ 0. Underpaid if Diff > 0. Overpaid if Diff < 0."
        },
        {
            title: "Balance",
            fullName: "Balance Check Icon",
            explanation: "Visual indicator: ✓ Green = balanced, ▲ Amber = imbalance (hover for details).",
            calculation: "Checks: 1) Cash + Credit = Total Sales, 2) Cash Amount = Cash Submit",
            balanceCheck: "Both conditions must be true for a green checkmark"
        }
    ];

    let currentWizardStep = 0;
    let wizardActive = false;
    let currentHighlightedColumn = null;

    function startWizard() {
        if (wizardActive) return;
        currentWizardStep = 0;
        wizardActive = true;
        document.getElementById('wizardOverlay').classList.add('open');
        showStep();
    }

    function closeWizard() {
        document.getElementById('wizardOverlay').classList.remove('open');
        clearHighlight();
        wizardActive = false;
    }

    function showStep() {
        const data = wizardColumns[currentWizardStep];
        const totalSteps = wizardColumns.length;
        const stepNum = currentWizardStep + 1;

        // Update tooltip content
        document.getElementById('tooltipStep').textContent = `${stepNum}/${totalSteps}`;
        document.getElementById('tooltipTitle').textContent = data.title;
        document.getElementById('tooltipFullName').textContent = data.fullName;
        document.getElementById('tooltipExplanation').innerHTML = data.explanation;
        document.getElementById('tooltipCalculation').textContent = data.calculation;
        document.getElementById('tooltipBalance').innerHTML = data.balanceCheck;
        document.getElementById('tooltipProgress').textContent = `${stepNum} of ${totalSteps}`;

        // Update buttons
        document.getElementById('prevBtn').disabled = currentWizardStep === 0;
        document.getElementById('nextBtn').textContent = currentWizardStep === totalSteps - 1 ? 'Finish' : 'Next →';

        // Position tooltip near the corresponding column
        positionTooltip(currentWizardStep);

        // Show tooltip with animation
        setTimeout(() => {
            document.getElementById('wizardTooltip').classList.add('open');
        }, 50);
    }

    function positionTooltip(stepIndex) {
        const tooltip = document.getElementById('wizardTooltip');
        const table = document.getElementById('shopTable');
        const thead = table.querySelector('thead');
        const thElements = thead.querySelectorAll('th');
        
        // Column index mapping (0-based, excluding # and Shop columns)
        // Column indices in the table (0-based): #=0, Shop=1, C.Sale=2, ..., Balance=23
        const columnIndices = [
            0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23
        ];
        
        const columnIndex = columnIndices[stepIndex];
        const targetTh = thElements[columnIndex];
        
        if (!targetTh) {
            // Fallback: center tooltip
            tooltip.style.position = 'fixed';
            tooltip.style.left = '50%';
            tooltip.style.top = '50%';
            tooltip.style.transform = 'translate(-50%, -50%) scale(0.9)';
            return;
        }

        const rect = targetTh.getBoundingClientRect();
        const tooltipRect = tooltip.getBoundingClientRect();
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;

        // Clear previous positioning classes
        tooltip.classList.remove('position-right', 'position-left', 'position-top', 'position-bottom');

        // Calculate best position
        let left, top, positionClass;

        // Default: position to the right of column
        const spaceRight = viewportWidth - rect.right;
        const spaceLeft = rect.left;
        const spaceTop = rect.top;
        const spaceBottom = viewportHeight - rect.bottom;

        // Choose best position based on available space
        if (spaceRight >= tooltipRect.width + 20) {
            // Position to right
            left = rect.right + 15;
            top = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
            positionClass = 'position-right';
        } else if (spaceLeft >= tooltipRect.width + 20) {
            // Position to left
            left = rect.left - tooltipRect.width - 15;
            top = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
            positionClass = 'position-left';
        } else if (spaceTop >= tooltipRect.height + 20) {
            // Position above
            left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
            top = rect.top - tooltipRect.height - 15;
            positionClass = 'position-top';
        } else {
            // Position below
            left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
            top = rect.bottom + 15;
            positionClass = 'position-bottom';
        }

        // Ensure tooltip stays within viewport
        left = Math.max(10, Math.min(viewportWidth - tooltipRect.width - 10, left));
        top = Math.max(10, Math.min(viewportHeight - tooltipRect.height - 10, top));

        tooltip.style.position = 'absolute';
        tooltip.style.left = left + 'px';
        tooltip.style.top = top + 'px';
        tooltip.classList.add(positionClass);

        // Highlight the column
        highlightColumn(stepIndex);
    }

    function highlightColumn(stepIndex) {
        clearHighlight();
        
        const table = document.getElementById('shopTable');
        const thead = table.querySelector('thead');
        const thElements = thead.querySelectorAll('th');
        
        const columnIndices = [
            2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24
        ];
        
        const columnIndex = columnIndices[stepIndex];
        const targetTh = thElements[columnIndex];
        
        if (targetTh) {
            targetTh.classList.add('wizard-highlight-column');
            currentHighlightedColumn = targetTh;
        }
    }

    function clearHighlight() {
        if (currentHighlightedColumn) {
            currentHighlightedColumn.classList.remove('wizard-highlight-column');
            currentHighlightedColumn = null;
        }
    }

    function nextStep() {
        if (currentWizardStep < wizardColumns.length - 1) {
            // Hide current tooltip
            document.getElementById('wizardTooltip').classList.remove('open');
            clearHighlight();
            
            currentWizardStep++;
            setTimeout(() => {
                showStep();
            }, 200);
        } else {
            closeWizard();
        }
    }

    function prevStep() {
        if (currentWizardStep > 0) {
            document.getElementById('wizardTooltip').classList.remove('open');
            clearHighlight();
            
            currentWizardStep--;
            setTimeout(() => {
                showStep();
            }, 200);
        }
    }

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (!wizardActive) return;
        
        if (e.key === 'Escape') closeWizard();
        if (e.key === 'ArrowRight') nextStep();
        if (e.key === 'ArrowLeft') prevStep();
    });

    // Close on overlay click
    document.getElementById('wizardOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeWizard();
    // Apply default sorting on page load
    document.addEventListener('DOMContentLoaded', function() {
        sortShops();
    });
    });
</script>
</body>
</html>