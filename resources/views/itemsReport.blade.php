<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.items_report')</title>
    @include('links')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --navy:       #0B1E3D;
            --navy-mid:   #112952;
            --navy-light: #1A3A6B;
            --amber:      var(--theme-primary);
            --amber:      var(--theme-primary);
            --amber-pale: #FEF3C7;
            --emerald:    #059669;
            --emerald-pale: #D1FAE5;
            --rose:       #E11D48;
            --rose-pale:  #FFE4E6;
            --slate-50:   #F8FAFC;
            --slate-100:  #F1F5F9;
            --slate-200:  #E2E8F0;
            --slate-400:  #94A3B8;
            --slate-600:  #475569;
            --slate-800:  #1E293B;
            --violet:     #7C3AED;
            --violet-pale: #EDE9FE;
            --violet:     #7C3AED;
            --violet-pale: #EDE9FE;
            --white:      #FFFFFF;
            --font: 'Sora', system-ui, sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --r: 8px; --r-lg: 12px; --r-xl: 16px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            background: #ECF0F8;
            color: var(--slate-800);
            min-height: 100vh;
        }

        .wrap { padding: 1.25rem 1.5rem 2rem; }

        /* ── Page header ── */
        .pg-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            background: var(--navy);
            border-radius: var(--r-xl);
            padding: 1rem 1.5rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.25);
        }

        .pg-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--white);
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .pg-title-icon {
            width: 40px;
            height: 40px;
            background: rgba(245,158,11,0.2);
            border: 1px solid rgba(245,158,11,0.35);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--amber);
            font-size: 1.1rem;
        }

        .pg-title span { color: var(--amber); }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0.5rem 1rem;
            border-radius: var(--r);
            border: 1px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.07);
            color: var(--white);
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.15);
            color: var(--white);
        }

        /* ── API Comparison Panel ── */
        .api-compare-panel {
            background: var(--white);
            border: 2px solid var(--slate-200);
            border-radius: var(--r-lg);
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            transition: all 0.3s ease;
        }

        .api-compare-panel.has-mismatch {
            border-color: var(--rose);
            background: var(--rose-pale);
        }

        .api-compare-panel.has-match {
            border-color: var(--emerald);
            background: var(--emerald-pale);
        }

        .api-compare-panel.loading {
            border-color: var(--violet);
            background: var(--violet-pale);
        }

        .api-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .api-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy);
        }

        .api-title i {
            font-size: 1.2rem;
            color: var(--violet);
        }

        .api-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
        }

        .api-status.idle {
            background: var(--slate-100);
            color: var(--slate-500);
        }

        .api-status.loading {
            background: var(--violet-pale);
            color: var(--violet);
        }

        .api-status.success {
            background: var(--emerald-pale);
            color: var(--emerald);
        }

        .api-status.error {
            background: var(--rose-pale);
            color: var(--rose);
        }

        .api-status.mismatch {
            background: var(--amber-pale);
            color: #92400E;
        }

        .status-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 0.4rem;
        }

        .status-dot.idle { background: var(--slate-300); }
        .status-dot.loading { background: var(--violet); animation: pulse-dot 1s ease-in-out infinite; }
        .status-dot.success { background: var(--emerald); }
        .status-dot.error { background: var(--rose); }
        .status-dot.mismatch { background: #92400E; }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .api-controls {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
            margin-top: 0.75rem;
        }

        .api-date-input {
            font-size: 0.82rem;
            padding: 0.4rem 0.7rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }

        .api-date-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        .api-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1.5px solid var(--slate-200);
        }

        .api-summary-item {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .api-summary-item .label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--slate-400);
        }

        .api-summary-item .value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--navy);
        }

        .api-summary-item .value.mismatch { color: var(--rose); }
        .api-summary-item .value.match { color: var(--emerald); }

        .abtn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.48rem 0.9rem;
            border-radius: 7px;
            border: 1.5px solid;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            white-space: nowrap;
        }

        .abtn-outline {
            background: var(--white);
            border-color: var(--slate-200);
            color: var(--slate-700);
        }

        .abtn-outline:hover {
            background: var(--slate-50);
            border-color: var(--navy-light);
            color: var(--navy);
        }

        .abtn-primary {
            background: var(--amber);
            border-color: var(--amber);
            color: var(--navy);
        }

        .abtn-primary:hover {
            background: #FBBF24;
            transform: translateY(-1px);
            color: var(--navy);
        }

        .abtn-violet {
            background: var(--violet);
            border-color: var(--violet);
            color: var(--white);
        }

        .abtn-violet:hover {
            background: #6D28D9;
            transform: translateY(-1px);
            color: var(--white);
        }

        /* ── Mismatch Table ── */
        .mismatch-table-wrap {
            margin-top: 1rem;
            max-height: 350px;
            overflow-y: auto;
            border: 1px solid var(--slate-200);
            border-radius: var(--r);
        }

        .mismatch-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        .mismatch-table thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.5rem 0.7rem;
            border-bottom: 2px solid var(--slate-200);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .mismatch-table tbody td {
            padding: 0.5rem 0.7rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
        }

        .mismatch-table tbody tr:hover td {
            background: #F8FAFF;
        }

        .diff-badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
        }

        .diff-badge.mismatch {
            background: var(--rose-pale);
            color: var(--rose);
        }

        .diff-badge.match {
            background: var(--emerald-pale);
            color: var(--emerald);
        }

        .diff-badge.partial {
            background: var(--amber-pale);
            color: #92400E;
        }

        .no-mismatch-msg {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--slate-400);
        }

        .no-mismatch-msg i {
            font-size: 2rem;
            display: block;
            margin-bottom: 0.5rem;
            color: var(--emerald);
        }

        /* ── API Comparison Panel ── */
        .api-compare-panel {
            background: var(--white);
            border: 2px solid var(--slate-200);
            border-radius: var(--r-lg);
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            transition: all 0.3s ease;
        }

        .api-compare-panel.has-mismatch {
            border-color: var(--rose);
            background: var(--rose-pale);
        }

        .api-compare-panel.has-match {
            border-color: var(--emerald);
            background: var(--emerald-pale);
        }

        .api-compare-panel.loading {
            border-color: var(--violet);
            background: var(--violet-pale);
        }

        .api-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .api-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy);
        }

        .api-title i {
            font-size: 1.2rem;
            color: var(--violet);
        }

        .api-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
        }

        .api-status.idle {
            background: var(--slate-100);
            color: var(--slate-500);
        }

        .api-status.loading {
            background: var(--violet-pale);
            color: var(--violet);
        }

        .api-status.success {
            background: var(--emerald-pale);
            color: var(--emerald);
        }

        .api-status.error {
            background: var(--rose-pale);
            color: var(--rose);
        }

        .api-status.mismatch {
            background: var(--amber-pale);
            color: #92400E;
        }

        .status-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 0.4rem;
        }

        .status-dot.idle { background: var(--slate-300); }
        .status-dot.loading { background: var(--violet); animation: pulse-dot 1s ease-in-out infinite; }
        .status-dot.success { background: var(--emerald); }
        .status-dot.error { background: var(--rose); }
        .status-dot.mismatch { background: #92400E; }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .api-controls {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
            margin-top: 0.75rem;
        }

        .api-date-input {
            font-size: 0.82rem;
            padding: 0.4rem 0.7rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }

        .api-date-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        .api-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1.5px solid var(--slate-200);
        }

        .api-summary-item {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .api-summary-item .label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--slate-400);
        }

        .api-summary-item .value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--navy);
        }

        .api-summary-item .value.mismatch { color: var(--rose); }
        .api-summary-item .value.match { color: var(--emerald); }

        .abtn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.48rem 0.9rem;
            border-radius: 7px;
            border: 1.5px solid;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            white-space: nowrap;
        }

        .abtn-outline {
            background: var(--white);
            border-color: var(--slate-200);
            color: var(--slate-700);
        }

        .abtn-outline:hover {
            background: var(--slate-50);
            border-color: var(--navy-light);
            color: var(--navy);
        }

        .abtn-primary {
            background: var(--amber);
            border-color: var(--amber);
            color: var(--navy);
        }

        .abtn-primary:hover {
            background: #FBBF24;
            transform: translateY(-1px);
            color: var(--navy);
        }

        .abtn-violet {
            background: var(--violet);
            border-color: var(--violet);
            color: var(--white);
        }

        .abtn-violet:hover {
            background: #6D28D9;
            transform: translateY(-1px);
            color: var(--white);
        }

        /* ── Mismatch Table ── */
        .mismatch-table-wrap {
            margin-top: 1rem;
            max-height: 350px;
            overflow-y: auto;
            border: 1px solid var(--slate-200);
            border-radius: var(--r);
        }

        .mismatch-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        .mismatch-table thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.5rem 0.7rem;
            border-bottom: 2px solid var(--slate-200);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .mismatch-table tbody td {
            padding: 0.5rem 0.7rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
        }

        .mismatch-table tbody tr:hover td {
            background: #F8FAFF;
        }

        .diff-badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
        }

        .diff-badge.mismatch {
            background: var(--rose-pale);
            color: var(--rose);
        }

        .diff-badge.match {
            background: var(--emerald-pale);
            color: var(--emerald);
        }

        .diff-badge.partial {
            background: var(--amber-pale);
            color: #92400E;
        }

        .no-mismatch-msg {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--slate-400);
        }

        .no-mismatch-msg i {
            font-size: 2rem;
            display: block;
            margin-bottom: 0.5rem;
            color: var(--emerald);
        }

        /* ── Filter bar ── */
        .filter-bar {
            background: var(--slate-100);
            border-bottom: 1px solid var(--slate-200);
            padding: 0.8rem 1.25rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
            border-radius: var(--r-lg) var(--r-lg) 0 0;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .filter-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--slate-600);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
        }

        .filter-input {
            font-size: 0.825rem;
            padding: 0.38rem 0.65rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            transition: border-color 0.18s, box-shadow 0.18s;
            outline: none;
            min-width: 0;
        }

        .filter-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        select.filter-input { cursor: pointer; }

        /* ── Summary bar ── */
        .summary-bar {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 0.7rem 1.25rem;
            background: linear-gradient(90deg, #EBF0FA 0%, #F1F5F9 100%);
            border-bottom: 1px solid var(--slate-200);
            flex-wrap: wrap;
        }

        .summary-stat {
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
        }

        .summary-stat-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-stat-value {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--navy);
        }

        .summary-stat-value.amber { color: var(--amber); }
        .summary-stat-value.emerald { color: var(--emerald); }
        .summary-stat-value.rose { color: var(--rose); }
        .summary-stat-value.violet { color: var(--violet); }
        .summary-stat-value.violet { color: var(--violet); }

        .summary-divider {
            width: 1px;
            height: 32px;
            background: var(--slate-200);
        }

        /* ── Table ── */
        .table-scroll { overflow-x: auto; }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.825rem;
        }

        table.report-table thead th {
            background: var(--slate-100);
            color: var(--slate-600);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 0.65rem 0.75rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 5;
        }

        table.report-table tbody td {
            padding: 0.55rem 0.75rem;
            border-bottom: 1px solid var(--slate-200);
            vertical-align: middle;
            color: var(--slate-800);
        }

        table.report-table tbody tr { transition: background 0.12s; }
        table.report-table tbody tr:hover td { background: #EFF4FF; }

        .product-name { font-weight: 600; color: var(--navy); }
        .mono { font-family: var(--mono); font-size: 0.8rem; }

        .api-compare-badge {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            margin-left: 0.5rem;
            background: var(--rose);
            color: white;
        }

        .api-compare-badge.match {
            background: var(--emerald);
        }
        .mono { font-family: var(--mono); font-size: 0.8rem; }

        .api-compare-badge {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            margin-left: 0.5rem;
            background: var(--rose);
            color: white;
        }

        .api-compare-badge.match {
            background: var(--emerald);
        }

        .empty-state {
            text-align: center;
            padding: 3.5rem 1rem;
            color: var(--slate-400);
        }
        .empty-state i { font-size: 2.5rem; margin-bottom: 0.75rem; display: block; }
        .empty-state p { font-size: 0.9rem; margin: 0; }

        /* ── Card panel ── */
        .card-panel {
            background: var(--white);
            border-radius: 0 0 var(--r-lg) var(--r-lg);
            box-shadow: 0 2px 16px rgba(11,30,61,0.08);
            overflow: hidden;
        }

        @media(max-width:768px) {
            .wrap { padding: 1rem; }
            .pg-header { padding: 0.75rem 1rem; }
            .filter-bar { padding: 0.6rem 1rem; }
            .summary-bar { padding: 0.5rem 1rem; gap: 0.75rem; }
            .api-summary { grid-template-columns: 1fr 1fr; }
            .api-summary { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>
@include('sidenav')

    <main class="main-content">
        <div class="wrap">
            @if(session('success'))
                <div class="alert alert-success d-flex justify-content-between">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger d-flex justify-content-between">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success d-flex justify-content-between">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger d-flex justify-content-between">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Page Header --}}
            <div class="pg-header">
                <div class="pg-title">
                    <div class="pg-title-icon"><i class="bi bi-box-seam"></i></div>
                    @lang('messages.items') <span>@lang('messages.report')</span>
                    @lang('messages.items') <span>@lang('messages.report')</span>
                </div>
                <div>
                    <a href="{{ url('items-report') }}" class="btn-back">
                        <i class="bi bi-arrow-left"></i> @lang('messages.back_to_items')
                    </a>
                </div>
            </div>

            {{-- ── API Comparison Panel ── --}}
            <div class="api-compare-panel" id="apiComparePanel">
                <div class="api-header">
                    <div class="api-title">
                        <i class="bi bi-cloud-check-fill"></i>
                        <span>MainStore API Comparison</span>
                    </div>
                    <div class="api-status idle" id="apiStatus">
                        <span class="status-dot idle"></span>
                        <span id="apiStatusText">Idle — Click "Compare" to check</span>
                    </div>
                </div>

                <div class="api-controls">
                    <div>
                        <label style="font-size:0.78rem;font-weight:600;color:var(--slate-500);">Start Date</label>
                        <input type="date" class="api-date-input" id="apiStartDate" value="{{ $apiCompareDate ?? date('Y-m-d', strtotime('-1 day')) }}">
                    </div>
                    <div>
                        <label style="font-size:0.78rem;font-weight:600;color:var(--slate-500);">End Date</label>
                        <input type="date" class="api-date-input" id="apiEndDate" value="{{ $apiCompareDate ?? date('Y-m-d', strtotime('-1 day')) }}">
                    </div>
                    <button class="abtn abtn-primary" id="apiCompareBtn" onclick="runApiComparison()" style="margin-top:0.5rem;">
                        <i class="bi bi-arrow-repeat"></i> Compare
                    </button>
                    <button class="abtn abtn-outline" id="apiHideBtn" onclick="toggleApiPanel()" style="margin-top:0.5rem;">
                        <i class="bi bi-eye-slash"></i> Hide
                    </button>
                </div>

                <div class="api-summary" id="apiSummary" style="display:none;">
                    <div class="api-summary-item">
                        <span class="label">Total API Items</span>
                        <span class="value" id="apiTotalItems">0</span>
                    </div>
                    <div class="api-summary-item">
                        <span class="label">Matched</span>
                        <span class="value match" id="apiMatchedCount">0</span>
                    </div>
                    <div class="api-summary-item">
                        <span class="label">Mismatched</span>
                        <span class="value mismatch" id="apiMismatchCount">0</span>
                    </div>
                    <div class="api-summary-item">
                        <span class="label">Status</span>
                        <span class="value" id="apiComparisonStatus">—</span>
                    </div>
                </div>

                <div id="apiMismatchContainer" style="display:none;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:1rem;margin-bottom:0.5rem;">
                        <h6 style="font-size:0.9rem;font-weight:700;color:var(--slate-700);margin:0;">
                            <i class="bi bi-exclamation-triangle" style="color:var(--rose);"></i> Mismatched Items
                        </h6>
                        <span style="font-size:0.78rem;color:var(--slate-400);" id="mismatchCountLabel">0 items</span>
                    </div>
                    <div class="mismatch-table-wrap">
                        <table class="mismatch-table" id="mismatchTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Local Closing</th>
                                    <th>API Closing</th>
                                    <th>Difference</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="mismatchTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="apiNoMismatch" style="display:none;">
                    <div class="no-mismatch-msg">
                        <i class="bi bi-check-circle-fill" style="color:var(--emerald);"></i>
                        <strong style="color:var(--emerald);">All items match!</strong>
                        <p style="color:var(--slate-500);margin-top:0.25rem;">No discrepancies found between local report and API data.</p>
                    </div>
                </div>
                <div>
                    <a href="{{ url('items-report') }}" class="btn-back">
                        <i class="bi bi-arrow-left"></i> @lang('messages.back_to_items')
                    </a>
                </div>
            </div>

            {{-- ── API Comparison Panel ── --}}
            <div class="api-compare-panel" id="apiComparePanel">
                <div class="api-header">
                    <div class="api-title">
                        <i class="bi bi-cloud-check-fill"></i>
                        <span>MainStore API Comparison</span>
                    </div>
                    <div class="api-status idle" id="apiStatus">
                        <span class="status-dot idle"></span>
                        <span id="apiStatusText">Idle — Click "Compare" to check</span>
                    </div>
                </div>

                <div class="api-controls">
                    <div>
                        <label style="font-size:0.78rem;font-weight:600;color:var(--slate-500);">Start Date</label>
                        <input type="date" class="api-date-input" id="apiStartDate" value="{{ date('Y-m-d', strtotime('-7 days')) }}">
                    </div>
                    <div>
                        <label style="font-size:0.78rem;font-weight:600;color:var(--slate-500);">End Date</label>
                        <input type="date" class="api-date-input" id="apiEndDate" value="{{ date('Y-m-d') }}">
                    </div>
                    <button class="abtn abtn-primary" id="apiCompareBtn" onclick="runApiComparison()" style="margin-top:0.5rem;">
                        <i class="bi bi-arrow-repeat"></i> Compare
                    </button>
                    <button class="abtn abtn-outline" id="apiHideBtn" onclick="toggleApiPanel()" style="margin-top:0.5rem;">
                        <i class="bi bi-eye-slash"></i> Hide
                    </button>
                </div>

                <div class="api-summary" id="apiSummary" style="display:none;">
                    <div class="api-summary-item">
                        <span class="label">Total API Items</span>
                        <span class="value" id="apiTotalItems">0</span>
                    </div>
                    <div class="api-summary-item">
                        <span class="label">Matched</span>
                        <span class="value match" id="apiMatchedCount">0</span>
                    </div>
                    <div class="api-summary-item">
                        <span class="label">Mismatched</span>
                        <span class="value mismatch" id="apiMismatchCount">0</span>
                    </div>
                    <div class="api-summary-item">
                        <span class="label">Status</span>
                        <span class="value" id="apiComparisonStatus">—</span>
                    </div>
                </div>

                <div id="apiMismatchContainer" style="display:none;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:1rem;margin-bottom:0.5rem;">
                        <h6 style="font-size:0.9rem;font-weight:700;color:var(--slate-700);margin:0;">
                            <i class="bi bi-exclamation-triangle" style="color:var(--rose);"></i> Mismatched Items
                        </h6>
                        <span style="font-size:0.78rem;color:var(--slate-400);" id="mismatchCountLabel">0 items</span>
                    </div>
                    <div class="mismatch-table-wrap">
                        <table class="mismatch-table" id="mismatchTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Local Closing</th>
                                    <th>API Closing</th>
                                    <th>Difference</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="mismatchTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="apiNoMismatch" style="display:none;">
                    <div class="no-mismatch-msg">
                        <i class="bi bi-check-circle-fill" style="color:var(--emerald);"></i>
                        <strong style="color:var(--emerald);">All items match!</strong>
                        <p style="color:var(--slate-500);margin-top:0.25rem;">No discrepancies found between local report and API data.</p>
                    </div>
                </div>
            </div>

            {{-- Filter Bar --}}
            <form method="GET" action="{{ url('items-report') }}" style="display:contents;">
                <div class="filter-bar">
                    <div class="filter-group">
                        <span class="filter-label">@lang('messages.shop')</span>
                        <select name="shop" class="filter-input" id="shop" onchange="this.form.submit()">
                            <option value="">@lang('messages.all_shops')</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop['id'] }}" {{ $accountFilter == $shop['id'] ? 'selected' : '' }}>
                                    {{ $shop['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">@lang('messages.date')</span>
                        <input type="date" name="date" class="filter-input" id="date"
                            value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}"
                            onchange="this.form.submit()">
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">@lang('messages.from')</span>
                        <input type="date" name="from_date" class="filter-input" id="from_date"
                            value="{{ $fromDate }}" max="{{ date('Y-m-d') }}"
                            onchange="this.form.submit()">
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">@lang('messages.to')</span>
                        <input type="date" name="to_date" class="filter-input" id="to_date"
                            value="{{ $toDate }}" max="{{ date('Y-m-d') }}"
                            onchange="this.form.submit()">
                    </div>
                </div>
            </form>

            {{-- Summary Bar --}}
            <div class="summary-bar">
                <div class="summary-stat">
                    <span class="summary-stat-label">@lang('messages.items')</span>
                    <span class="summary-stat-value">{{ number_format(count($reportRows)) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">@lang('messages.received_qty')</span>
                    <span class="summary-stat-value emerald">{{ number_format($totalReceived) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">@lang('messages.returned_qty')</span>
                    <span class="summary-stat-value rose">{{ number_format($totalReturned) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">@lang('messages.sold_qty')</span>
                    <span class="summary-stat-value" style="color:var(--navy-light);">{{ number_format($totalSold) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">@lang('messages.received_total')</span>
                    <span class="summary-stat-value emerald">Tsh {{ number_format($totalReceivedPrice, 2) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">@lang('messages.sold_total')</span>
                    <span class="summary-stat-value amber">Tsh {{ number_format($totalSoldPrice, 2) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">@lang('messages.remaining_qty')</span>
                    <span class="summary-stat-value" style="color:var(--navy);">{{ number_format($totalRemainingQty) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">@lang('messages.remaining_value')</span>
                    <span class="summary-stat-value" style="color:var(--navy);">Tsh {{ number_format($totalRemainingValue, 2) }}</span>
                </div>
            </div>

            {{-- Table --}}
            <div class="card-panel">
                <div class="table-scroll">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('messages.product')</th>
                                @if (getCurrentShopId() == 7)
                                    <th>@lang('messages.items_taken_main_store')</th>
                                @endif
                                
                                <th>@lang('messages.received_qty')</th>
                                <th>@lang('messages.returned_qty')</th>
                                <th>@lang('messages.sold_qty')</th>
                                <th>Opening Stock</th>
                                <th>Closing Stock</th>
                                <th>Opening Stock</th>
                                <th>Closing Stock</th>
                                <th>@lang('messages.received_total')</th>
                                <th>@lang('messages.sold_total')</th>
                                <th>@lang('messages.remaining_qty')</th>
                                <th>@lang('messages.remaining_value')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(empty($reportRows))
                            <tr>
                                <td colspan="12">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>@lang('messages.no_data_found_for_filters')</p>
                                    </div>
                                </td>
                            </tr>
                            @else
                                @php $index = 1; @endphp
                                @foreach($reportRows as $row)
                                    <tr data-product-name="{{ strtolower($row['productName']) }}" data-closing-stock="{{ $row['closingStock'] ?? 0 }}">
                                    <tr data-product-name="{{ strtolower($row['productName']) }}" data-closing-stock="{{ $row['closingStock'] ?? 0 }}">
                                        <td style="color:var(--slate-400);font-size:0.78rem;">{{ $index++ }}</td>
                                        <td>
                                            <span class="product-name">{{ $row['productName'] }}</span>
                                            <span class="api-compare-badge" id="api-badge-{{ $index - 1 }}" style="display:none;"></span>
                                        </td>
                                        <td>
                                            <span class="product-name">{{ $row['productName'] }}</span>
                                            <span class="api-compare-badge" id="api-badge-{{ $index - 1 }}" style="display:none;"></span>
                                        </td>
                                        @if (getCurrentShopId() == 7)
                                        <td><span class="mono">{{ $row['mainQty'] ?? 0 }}</span></td>
                                        @endif
                                        <td><span class="mono" style="color:var(--emerald);font-weight:600;">{{ number_format($row['receivedQty']) }}</span></td>
                                        <td><span class="mono" style="color:var(--rose);">{{ number_format($row['returnedQty']) }}</span></td>
                                        <td><span class="mono" style="color:var(--navy-light);font-weight:600;">{{ number_format($row['soldQty']) }}</span></td>
                                        <td><span class="mono">{{ number_format($row['openingStock'] ?? 0) }}</span></td>
                                        <td>
                                            <span class="mono" style="font-weight:600;" id="closing-stock-{{ $index - 1 }}">{{ number_format($row['closingStock'] ?? 0) }}</span>
                                        </td>
                                        <td><span class="mono">{{ number_format($row['openingStock'] ?? 0) }}</span></td>
                                        <td>
                                            <span class="mono" style="font-weight:600;" id="closing-stock-{{ $index - 1 }}">{{ number_format($row['closingStock'] ?? 0) }}</span>
                                        </td>
                                        <td><span class="mono" style="color:var(--emerald);">Tsh {{ number_format($row['receivedTotal'], 2) }}</span></td>
                                        <td><span class="mono" style="color:var(--amber);">Tsh {{ number_format($row['soldTotal'], 2) }}</span></td>
                                        <td><span class="mono" style="font-weight:600;">{{ number_format($row['remainingQty']) }}</span></td>
                                        <td><span class="mono">Tsh {{ number_format($row['remainingValue'], 2) }}</span></td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($reportRows->hasPages())
                <div style="padding:0.75rem 1.25rem;background:var(--slate-100);border-top:1px solid var(--slate-200);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;">
                    <span style="font-size:0.78rem;color:var(--slate-600);">
                        Showing {{ $reportRows->firstItem() ?? 0 }} to {{ $reportRows->lastItem() ?? 0 }} of {{ number_format($reportRows->total()) }} items
                    </span>
                    <div style="display:flex;gap:0.35rem;align-items:center;">
                        @if($reportRows->onFirstPage())
                            <span style="padding:0.38rem 0.7rem;border-radius:7px;border:1.5px solid var(--slate-200);background:var(--slate-100);color:var(--slate-400);font-size:0.78rem;cursor:not-allowed;">&laquo; Prev</span>
                        @else
                            <a href="{{ $reportRows->previousPageUrl() }}" style="padding:0.38rem 0.7rem;border-radius:7px;border:1.5px solid var(--slate-200);background:var(--white);color:var(--navy);font-size:0.78rem;text-decoration:none;transition:all 0.15s;" onmouseover="this.style.background='var(--slate-100)'" onmouseout="this.style.background='var(--white)'">&laquo; Prev</a>
                        @endif

                        @foreach(range(1, $reportRows->lastPage()) as $page)
                            @if($page == $reportRows->currentPage())
                                <span style="padding:0.38rem 0.7rem;border-radius:7px;border:1.5px solid var(--navy);background:var(--navy);color:var(--white);font-size:0.78rem;font-weight:600;">{{ $page }}</span>
                            @else
                                <a href="{{ $reportRows->url($page) }}" style="padding:0.38rem 0.7rem;border-radius:7px;border:1.5px solid var(--slate-200);background:var(--white);color:var(--slate-600);font-size:0.78rem;text-decoration:none;transition:all 0.15s;" onmouseover="this.style.background='var(--slate-100)'" onmouseout="this.style.background='var(--white)'">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($reportRows->hasMorePages())
                            <a href="{{ $reportRows->nextPageUrl() }}" style="padding:0.38rem 0.7rem;border-radius:7px;border:1.5px solid var(--slate-200);background:var(--white);color:var(--navy);font-size:0.78rem;text-decoration:none;transition:all 0.15s;" onmouseover="this.style.background='var(--slate-100)'" onmouseout="this.style.background='var(--white)'">Next &raquo;</a>
                        @else
                            <span style="padding:0.38rem 0.7rem;border-radius:7px;border:1.5px solid var(--slate-200);background:var(--slate-100);color:var(--slate-400);font-size:0.78rem;cursor:not-allowed;">Next &raquo;</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

        </div>
    </main>

{{-- Past Sales Modal --}}
<div class="modal fade" id="pastSalesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-navy">
                 <h5 class="modal-title"><i class="bi bi-pencil-square"></i> @lang('messages.edit_past_sales')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="info-box mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    @lang('messages.search_a_past_sale')
                </div>
                <div style="position:relative;margin-bottom:1rem;">
                    <div class="sbox">
                        <i class="bi bi-search sbox-icon"></i>
                        <input type="text" id="pastSalesSearch" class="sbox-input" placeholder="@lang('messages.search_by_customer_name')" autocomplete="off">
                    </div>
                </div>
                <div id="pastSalesResults" style="max-height:400px;overflow-y:auto;"></div>
            </div>
        </div>
    </div>
</div>

{{-- Confirm Return Modal --}}
<div class="modal fade" id="confirmReturnModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-navy">
                <h5 class="modal-title"><i class="bi bi-arrow-return-left"></i> @lang('messages.confirm_return')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="margin-bottom:0.75rem;font-size:0.9rem;color:var(--slate-600);">
                    @lang('messages.returning_this_sale_to_orders')
                </p>
                <div id="returnSaleDetails" class="cust-info" style="display:none;"></div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('messages.cancel')</button>
                <button type="button" id="confirmReturnBtn" class="submit-btn" style="width:auto;padding:0.5rem 1.2rem;">
                    <i class="bi bi-check-circle-fill"></i> @lang('messages.confirm')
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// ============================================================
// API COMPARISON JAVASCRIPT
// ============================================================

const API_PROXY_URL = '/main-store/proxy';

function toggleApiPanel() {
    const panel = document.getElementById('apiComparePanel');
    const btn = document.getElementById('apiHideBtn');
    if (panel.style.display === 'none') {
        panel.style.display = 'block';
        btn.innerHTML = '<i class="bi bi-eye-slash"></i> Hide';
    } else {
        panel.style.display = 'none';
        btn.innerHTML = '<i class="bi bi-eye"></i> Show';
    }
}

function updateApiStatus(status, message) {
    const statusEl = document.getElementById('apiStatus');
    const statusText = document.getElementById('apiStatusText');
    
    statusEl.className = 'api-status ' + status;
    statusEl.querySelector('.status-dot').className = 'status-dot ' + status;
    statusText.textContent = message;
}

function showApiMismatches(mismatches) {
    const container = document.getElementById('apiMismatchContainer');
    const noMismatch = document.getElementById('apiNoMismatch');
    const tbody = document.getElementById('mismatchTableBody');
    const countLabel = document.getElementById('mismatchCountLabel');
    
    if (!mismatches || mismatches.length === 0) {
        container.style.display = 'none';
        noMismatch.style.display = 'block';
        return;
    }
    
    container.style.display = 'block';
    noMismatch.style.display = 'none';
    countLabel.textContent = mismatches.length + ' items';
    
    tbody.innerHTML = '';
    mismatches.forEach((item, index) => {
        const diff = item.local_closing - item.api_closing;
        const statusClass = Math.abs(diff) <= 1 ? 'match' : 'mismatch';
        const statusText = Math.abs(diff) <= 1 ? '✓ Match' : '⚠ Mismatch';
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td><strong>${escapeHtml(item.product_name)}</strong></td>
            <td>${item.local_closing}</td>
            <td>${item.api_closing}</td>
            <td>${diff > 0 ? '+' : ''}${diff}</td>
            <td><span class="diff-badge ${statusClass}">${statusText}</span></td>
        `;
        tbody.appendChild(row);
    });
}

function updateSummary(total, matched, mismatched) {
    document.getElementById('apiTotalItems').textContent = total;
    document.getElementById('apiMatchedCount').textContent = matched;
    document.getElementById('apiMismatchCount').textContent = mismatched;
    document.getElementById('apiSummary').style.display = 'grid';
    
    if (mismatched === 0 && total > 0) {
        document.getElementById('apiComparisonStatus').textContent = '✅ All match!';
        document.getElementById('apiComparisonStatus').style.color = 'var(--emerald)';
    } else if (total === 0) {
        document.getElementById('apiComparisonStatus').textContent = '— No data';
        document.getElementById('apiComparisonStatus').style.color = 'var(--slate-400)';
    } else {
        document.getElementById('apiComparisonStatus').textContent = '⚠ ' + mismatched + ' mismatches';
        document.getElementById('apiComparisonStatus').style.color = 'var(--rose)';
    }
}

async function runApiComparison() {
    const panel = document.getElementById('apiComparePanel');
    panel.className = 'api-compare-panel loading';
    
    const startDate = document.getElementById('apiStartDate').value;
    const endDate = document.getElementById('apiEndDate').value;
    
    if (!startDate || !endDate) {
        alert('Please select both start and end dates');
        panel.className = 'api-compare-panel';
        return;
    }
    
    updateApiStatus('loading', 'Fetching stock report from MainStore...');
    
    try {
        const proxyResponse = await fetch(API_PROXY_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                action: 'stock_report',
                start_date: startDate,
                end_date: endDate
            })
        });
        
        if (!proxyResponse.ok) {
            const errorData = await proxyResponse.json().catch(() => ({}));
            throw new Error(errorData.error || 'Proxy request failed: ' + proxyResponse.status);
        }
        
        const reportData = await proxyResponse.json();
        console.log('Report response:', reportData);
        
        // Parse the API response - try different possible structures
        let apiItems = [];
        if (Array.isArray(reportData)) {
            apiItems = reportData;
        } else if (reportData.data && Array.isArray(reportData.data)) {
            apiItems = reportData.data;
        } else if (reportData.items && Array.isArray(reportData.items)) {
            apiItems = reportData.items;
        } else if (reportData.stock && Array.isArray(reportData.stock)) {
            apiItems = reportData.stock;
        } else if (reportData.results && Array.isArray(reportData.results)) {
            apiItems = reportData.results;
        } else {
            // Try to find any array in the response
            for (const key in reportData) {
                if (Array.isArray(reportData[key])) {
                    apiItems = reportData[key];
                    break;
                }
            }
        }
        
        // If still no items, check if the response itself has items in a different format
        if (apiItems.length === 0 && reportData.data && typeof reportData.data === 'object') {
            if (reportData.data.items && Array.isArray(reportData.data.items)) {
                apiItems = reportData.data.items;
            } else if (reportData.data.results && Array.isArray(reportData.data.results)) {
                apiItems = reportData.data.results;
            }
        }
        
        console.log('Parsed API items:', apiItems.length, 'items');
        
        if (apiItems.length === 0) {
            updateApiStatus('error', 'No data returned from API. Response: ' + JSON.stringify(reportData).substring(0, 200));
            panel.className = 'api-compare-panel';
            document.getElementById('apiSummary').style.display = 'grid';
            document.getElementById('apiTotalItems').textContent = '0';
            document.getElementById('apiMatchedCount').textContent = '0';
            document.getElementById('apiMismatchCount').textContent = '0';
            document.getElementById('apiComparisonStatus').textContent = 'No data';
            return;
        }
        
        // Step 3: Compare with local report data
        updateApiStatus('loading', 'Comparing with local report data...');
        
        // Build local product map from report table
        const localProducts = {};
        const rows = document.querySelectorAll('table.report-table tbody tr[data-product-name]');
        rows.forEach((row, index) => {
            const name = row.dataset.productName || '';
            const closingStockEl = document.getElementById('closing-stock-' + index);
            const closingStock = closingStockEl ? parseInt(closingStockEl.textContent.replace(/,/g, '')) || 0 : 0;
            if (name) {
                localProducts[name.toLowerCase().trim()] = {
                    closing: closingStock,
                    rowIndex: index
                };
            }
        });
        
        let matched = 0;
        let mismatched = [];
        let apiTotal = apiItems.length;
        
        apiItems.forEach((item, idx) => {
            // Try different possible field names for product name and closing stock
            const apiName = (item.item_name || item.name || item.product_name || item.Item_Name || item.product || '').trim();
            const apiClosing = parseInt(
                item.closing_stock || 
                item.closing || 
                item.stock || 
                item.quantity || 
                item.total || 
                item.closing_balance ||
                0
            );
            
            console.log(`Item ${idx + 1}: Name="${apiName}", Closing=${apiClosing}`);
            
            if (!apiName) return;
            
            const searchName = apiName.toLowerCase().trim();
            let localData = localProducts[searchName];
            
            // Try to find by partial match if not found exactly
            if (!localData && searchName.includes('-')) {
                const baseName = searchName.split('-')[0].trim();
                for (const [key, value] of Object.entries(localProducts)) {
                    if (key.includes(baseName) || baseName.includes(key)) {
                        localData = value;
                        break;
                    }
                }
            }
            
            // Try partial word matching
            if (!localData) {
                const words = searchName.split(/\s+/).filter(w => w.length > 2);
                for (const [key, value] of Object.entries(localProducts)) {
                    let matchCount = 0;
                    for (const word of words) {
                        if (key.includes(word)) matchCount++;
                    }
                    if (matchCount >= Math.min(words.length, 2)) {
                        localData = value;
                        break;
                    }
                }
            }
            
            if (localData) {
                if (Math.abs(localData.closing - apiClosing) <= 1) {
                    matched++;
                } else {
                    mismatched.push({
                        product_name: apiName,
                        local_closing: localData.closing,
                        api_closing: apiClosing,
                        diff: localData.closing - apiClosing
                    });
                    // Show badge on the row
                    const badge = document.getElementById('api-badge-' + localData.rowIndex);
                    if (badge) {
                        badge.style.display = 'inline-block';
                        badge.className = 'api-compare-badge';
                        badge.textContent = '⚠ API: ' + apiClosing;
                    }
                }
            } else {
                // Not found in local report
                mismatched.push({
                    product_name: apiName,
                    local_closing: 0,
                    api_closing: apiClosing,
                    diff: -apiClosing
                });
            }
        });
        
        // Update UI
        updateSummary(apiTotal, matched, mismatched.length);
        showApiMismatches(mismatched);
        
        if (mismatched.length === 0 && apiTotal > 0) {
            updateApiStatus('success', 'All ' + apiTotal + ' items match!');
            panel.className = 'api-compare-panel has-match';
        } else if (mismatched.length > 0) {
            updateApiStatus('mismatch', mismatched.length + ' items mismatch out of ' + apiTotal);
            panel.className = 'api-compare-panel has-mismatch';
        } else {
            updateApiStatus('idle', 'No items to compare');
            panel.className = 'api-compare-panel';
        }
        
    } catch (error) {
        console.error('API Comparison Error:', error);
        updateApiStatus('error', 'Error: ' + error.message);
        panel.className = 'api-compare-panel';
        document.getElementById('apiSummary').style.display = 'grid';
        document.getElementById('apiTotalItems').textContent = 'Error';
        document.getElementById('apiMatchedCount').textContent = '-';
        document.getElementById('apiMismatchCount').textContent = '-';
        document.getElementById('apiComparisonStatus').textContent = error.message;
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// ============================================================
// PAST SALES FUNCTIONS (existing)
// ============================================================

// ============================================================
// API COMPARISON JAVASCRIPT
// ============================================================

const API_BASE_URL = 'https://mainstore.co.tz/api';

function toggleApiPanel() {
    const panel = document.getElementById('apiComparePanel');
    const btn = document.getElementById('apiHideBtn');
    if (panel.style.display === 'none') {
        panel.style.display = 'block';
        btn.innerHTML = '<i class="bi bi-eye-slash"></i> Hide';
    } else {
        panel.style.display = 'none';
        btn.innerHTML = '<i class="bi bi-eye"></i> Show';
    }
}

function updateApiStatus(status, message) {
    const statusEl = document.getElementById('apiStatus');
    const statusText = document.getElementById('apiStatusText');
    
    statusEl.className = 'api-status ' + status;
    statusEl.querySelector('.status-dot').className = 'status-dot ' + status;
    statusText.textContent = message;
}

function showApiMismatches(mismatches) {
    const container = document.getElementById('apiMismatchContainer');
    const noMismatch = document.getElementById('apiNoMismatch');
    const tbody = document.getElementById('mismatchTableBody');
    const countLabel = document.getElementById('mismatchCountLabel');
    
    if (!mismatches || mismatches.length === 0) {
        container.style.display = 'none';
        noMismatch.style.display = 'block';
        return;
    }
    
    container.style.display = 'block';
    noMismatch.style.display = 'none';
    countLabel.textContent = mismatches.length + ' items';
    
    tbody.innerHTML = '';
    mismatches.forEach((item, index) => {
        const diff = item.local_closing - item.api_closing;
        const statusClass = Math.abs(diff) <= 1 ? 'match' : 'mismatch';
        const statusText = Math.abs(diff) <= 1 ? '✓ Match' : '⚠ Mismatch';
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td><strong>${escapeHtml(item.product_name)}</strong></td>
            <td>${item.local_closing}</td>
            <td>${item.api_closing}</td>
            <td>${diff > 0 ? '+' : ''}${diff}</td>
            <td><span class="diff-badge ${statusClass}">${statusText}</span></td>
        `;
        tbody.appendChild(row);
    });
}

function updateSummary(total, matched, mismatched) {
    document.getElementById('apiTotalItems').textContent = total;
    document.getElementById('apiMatchedCount').textContent = matched;
    document.getElementById('apiMismatchCount').textContent = mismatched;
    document.getElementById('apiSummary').style.display = 'grid';
    
    if (mismatched === 0 && total > 0) {
        document.getElementById('apiComparisonStatus').textContent = '✅ All match!';
        document.getElementById('apiComparisonStatus').style.color = 'var(--emerald)';
    } else if (total === 0) {
        document.getElementById('apiComparisonStatus').textContent = '— No data';
        document.getElementById('apiComparisonStatus').style.color = 'var(--slate-400)';
    } else {
        document.getElementById('apiComparisonStatus').textContent = '⚠ ' + mismatched + ' mismatches';
        document.getElementById('apiComparisonStatus').style.color = 'var(--rose)';
    }
}

async function runApiComparison() {
    const panel = document.getElementById('apiComparePanel');
    panel.className = 'api-compare-panel loading';
    
    const startDate = document.getElementById('apiStartDate').value;
    const endDate = document.getElementById('apiEndDate').value;
    
    if (!startDate || !endDate) {
        alert('Please select both start and end dates');
        panel.className = 'api-compare-panel';
        return;
    }
    
    updateApiStatus('loading', 'Authenticating with MainStore API...');
    
    try {
        // Step 1: Login to get token
        const loginResponse = await fetch(API_BASE_URL + '/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                username: 'api_stock',
                password: 'af8c184628122ae501'
            })
        });
        
        if (!loginResponse.ok) {
            const errorText = await loginResponse.text();
            throw new Error('Login failed: ' + loginResponse.status + ' - ' + errorText);
        }
        
        const loginData = await loginResponse.json();
        console.log('Login response:', loginData);
        
        // Extract token - handle different response structures
        let token = null;
        if (loginData.data && loginData.data.token) {
            token = loginData.data.token;
        } else if (loginData.token) {
            token = loginData.token;
        } else if (loginData.data && loginData.data.access_token) {
            token = loginData.data.access_token;
        }
        
        if (!token) {
            console.error('Full login response:', JSON.stringify(loginData, null, 2));
            throw new Error('No token received from login. Response structure: ' + JSON.stringify(loginData));
        }
        
        updateApiStatus('loading', 'Fetching stock report from MainStore...');
        
        // Step 2: Fetch stock report
        const reportResponse = await fetch(
            API_BASE_URL + '/stock/report?start_date=' + startDate + '&end_date=' + endDate,
            {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }
        );
        
        if (!reportResponse.ok) {
            const errorText = await reportResponse.text();
            throw new Error('Stock report failed: ' + reportResponse.status + ' - ' + errorText);
        }
        
        const reportData = await reportResponse.json();
        console.log('Report response:', reportData);
        
        // Parse the API response - try different possible structures
        let apiItems = [];
        if (Array.isArray(reportData)) {
            apiItems = reportData;
        } else if (reportData.data && Array.isArray(reportData.data)) {
            apiItems = reportData.data;
        } else if (reportData.items && Array.isArray(reportData.items)) {
            apiItems = reportData.items;
        } else if (reportData.stock && Array.isArray(reportData.stock)) {
            apiItems = reportData.stock;
        } else if (reportData.results && Array.isArray(reportData.results)) {
            apiItems = reportData.results;
        } else {
            // Try to find any array in the response
            for (const key in reportData) {
                if (Array.isArray(reportData[key])) {
                    apiItems = reportData[key];
                    break;
                }
            }
        }
        
        // If still no items, check if the response itself has items in a different format
        if (apiItems.length === 0 && reportData.data && typeof reportData.data === 'object') {
            // Some APIs wrap items in data.items
            if (reportData.data.items && Array.isArray(reportData.data.items)) {
                apiItems = reportData.data.items;
            } else if (reportData.data.results && Array.isArray(reportData.data.results)) {
                apiItems = reportData.data.results;
            }
        }
        
        console.log('Parsed API items:', apiItems.length, 'items');
        
        if (apiItems.length === 0) {
            updateApiStatus('error', 'No data returned from API. Response: ' + JSON.stringify(reportData).substring(0, 200));
            panel.className = 'api-compare-panel';
            document.getElementById('apiSummary').style.display = 'grid';
            document.getElementById('apiTotalItems').textContent = '0';
            document.getElementById('apiMatchedCount').textContent = '0';
            document.getElementById('apiMismatchCount').textContent = '0';
            document.getElementById('apiComparisonStatus').textContent = 'No data';
            return;
        }
        
        // Step 3: Compare with local report data
        updateApiStatus('loading', 'Comparing with local report data...');
        
        // Build local product map from report table
        const localProducts = {};
        const rows = document.querySelectorAll('table.report-table tbody tr[data-product-name]');
        rows.forEach((row, index) => {
            const name = row.dataset.productName || '';
            const closingStockEl = document.getElementById('closing-stock-' + index);
            const closingStock = closingStockEl ? parseInt(closingStockEl.textContent.replace(/,/g, '')) || 0 : 0;
            if (name) {
                localProducts[name.toLowerCase().trim()] = {
                    closing: closingStock,
                    rowIndex: index
                };
            }
        });
        
        let matched = 0;
        let mismatched = [];
        let apiTotal = apiItems.length;
        
        apiItems.forEach((item, idx) => {
            // Try different possible field names for product name and closing stock
            const apiName = (item.item_name || item.name || item.product_name || item.Item_Name || item.product || '').trim();
            const apiClosing = parseInt(
                item.closing_stock || 
                item.closing || 
                item.stock || 
                item.quantity || 
                item.total || 
                item.closing_balance ||
                0
            );
            
            console.log(`Item ${idx + 1}: Name="${apiName}", Closing=${apiClosing}`);
            
            if (!apiName) return;
            
            const searchName = apiName.toLowerCase().trim();
            let localData = localProducts[searchName];
            
            // Try to find by partial match if not found exactly
            if (!localData && searchName.includes('-')) {
                const baseName = searchName.split('-')[0].trim();
                for (const [key, value] of Object.entries(localProducts)) {
                    if (key.includes(baseName) || baseName.includes(key)) {
                        localData = value;
                        break;
                    }
                }
            }
            
            // Try partial word matching
            if (!localData) {
                const words = searchName.split(/\s+/).filter(w => w.length > 2);
                for (const [key, value] of Object.entries(localProducts)) {
                    let matchCount = 0;
                    for (const word of words) {
                        if (key.includes(word)) matchCount++;
                    }
                    if (matchCount >= Math.min(words.length, 2)) {
                        localData = value;
                        break;
                    }
                }
            }
            
            if (localData) {
                if (Math.abs(localData.closing - apiClosing) <= 1) {
                    matched++;
                } else {
                    mismatched.push({
                        product_name: apiName,
                        local_closing: localData.closing,
                        api_closing: apiClosing,
                        diff: localData.closing - apiClosing
                    });
                    // Show badge on the row
                    const badge = document.getElementById('api-badge-' + localData.rowIndex);
                    if (badge) {
                        badge.style.display = 'inline-block';
                        badge.className = 'api-compare-badge';
                        badge.textContent = '⚠ API: ' + apiClosing;
                    }
                }
            } else {
                // Not found in local report
                mismatched.push({
                    product_name: apiName,
                    local_closing: 0,
                    api_closing: apiClosing,
                    diff: -apiClosing
                });
            }
        });
        
        // Update UI
        updateSummary(apiTotal, matched, mismatched.length);
        showApiMismatches(mismatched);
        
        if (mismatched.length === 0 && apiTotal > 0) {
            updateApiStatus('success', 'All ' + apiTotal + ' items match!');
            panel.className = 'api-compare-panel has-match';
        } else if (mismatched.length > 0) {
            updateApiStatus('mismatch', mismatched.length + ' items mismatch out of ' + apiTotal);
            panel.className = 'api-compare-panel has-mismatch';
        } else {
            updateApiStatus('idle', 'No items to compare');
            panel.className = 'api-compare-panel';
        }
        
    } catch (error) {
        console.error('API Comparison Error:', error);
        updateApiStatus('error', 'Error: ' + error.message);
        panel.className = 'api-compare-panel';
        document.getElementById('apiSummary').style.display = 'grid';
        document.getElementById('apiTotalItems').textContent = 'Error';
        document.getElementById('apiMatchedCount').textContent = '-';
        document.getElementById('apiMismatchCount').textContent = '-';
        document.getElementById('apiComparisonStatus').textContent = error.message;
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// ============================================================
// PAST SALES FUNCTIONS (existing)
// ============================================================

let pendingReturnId = null;

document.getElementById('pastSalesModal').addEventListener('shown.bs.modal', function () {
    loadPastSales('');
    document.getElementById('pastSalesSearch').focus();
});

document.getElementById('pastSalesSearch').addEventListener('input', function (e) {
    loadPastSales(e.target.value.trim());
});

async function loadPastSales(q) {
    var el = document.getElementById('pastSalesResults');
    el.innerHTML = '<div class="dd-loading">@lang('messages.searching')</div>';
    try {
        var url = q ? '{{ url('searchSales') }}?search=' + encodeURIComponent(q) : '{{ url('searchSales') }}';
        var response = await fetch(url);
        if (!response.ok) throw new Error('HTTP error! status: ' + response.status);
        var data = await response.json();
        if (!data.sales || !data.sales.length) {
            el.innerHTML = '<div class="dd-empty">@lang('messages.no_past_sales_found')</div>';
            return;
        }
        el.innerHTML = data.sales.map(function (s) {
            var d = new Date(s.created_at);
            var dt = d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
            var stBadge = s.status === 'Debt'
                ? '<span class="dd-badge dd-badge-offer">@lang('messages.credit')</span>'
                : '<span class="dd-badge dd-badge-price">@lang('messages.paid')</span>';
            return '<div class="dd-item" onclick="selectPastSale(\'' + s.sales_id + '\',\'' + escHtml(s.cName || 'Unknown') + '\',' + s.totalAmount + ',' + s.totalQuantity + ',\'' + dt + '\')">' +
                '<div class="dd-item-name">' + escHtml(s.cName || '@lang('messages.unknown_customer')') + ' ' + stBadge + '</div>' +
                '<div class="dd-item-meta">' +
                    '<span><i class="bi bi-calendar3"></i> ' + dt + '</span>' +
                    '<span><i class="bi bi-box-seam"></i> ' + s.totalQuantity + ' @lang('messages.order_plural')</span>' +
                    '<span class="dd-badge dd-badge-price">' + Number(s.totalAmount).toLocaleString() + ' Tsh</span>' +
                '</div>' +
            '</div>';
        }).join('');
    } catch (error) {
        console.error('Error loading past sales:', error);
        el.innerHTML = '<div class="dd-empty" style="color:var(--rose);">@lang('messages.error_loading_sales') ' + error.message + '</div>';
    }
}

function selectPastSale(id, name, total, qty, date) {
    pendingReturnId = id;
    var det = document.getElementById('returnSaleDetails');
    det.innerHTML =
        '<div class="cust-row"><span class="cust-key">@lang('messages.customer')</span><span class="cust-val">' + name + '</span></div>' +
        '<div class="cust-row"><span class="cust-key">@lang('messages.date')</span><span class="cust-val">' + date + '</span></div>' +
        '<div class="cust-row"><span class="cust-key">@lang('messages.total')</span><span class="cust-val">' + Number(total).toLocaleString() + ' Tsh</span></div>' +
        '<div class="cust-row"><span class="cust-key">@lang('messages.items')</span><span class="cust-val">' + qty + '</span></div>';
    det.style.display = '';
    new bootstrap.Modal(document.getElementById('confirmReturnModal')).show();
}

document.getElementById('confirmReturnBtn').addEventListener('click', async function () {
    if (!pendingReturnId) return;
    var btn = document.getElementById('confirmReturnBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> @lang('messages.processing')';
    try {
        var fd = new FormData();
        fd.append('sales_id', pendingReturnId);
        var data = await (await fetch('{{ url('returnSaleToOrder') }}', {
            method: 'POST',
            body: fd,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        })).json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('confirmReturnModal'))?.hide();
            bootstrap.Modal.getInstance(document.getElementById('pastSalesModal'))?.hide();
            window.location.href = '{{ url('newOrder') }}';
        } else {
            alert('Error: ' + data.message);
        }
    } catch (e) {
        alert('Error: ' + e.message);
    }
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> @lang('messages.confirm')';
    pendingReturnId = null;
});

function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

// Auto-run comparison on page load
window.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        runApiComparison();
    }, 800);
});

// Auto-run comparison after page load with a delay
setTimeout(function() {
    // Uncomment to auto-run on page load:
    // runApiComparison();
}, 3000);
</script>
</body>
</html>