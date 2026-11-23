<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - {{ __('messages.sales_dashboard') }}</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-dark:     #0B1E3D;
            --primary-mid:      #112952;
            --primary-light:    #1A3A6B;
            --accent:           var(--theme-primary, #F59E0B);
            --accent-dark:      #D97706;
            --accent-pale:      #FEF3C7;
            --success:          #059669;
            --success-pale:     #D1FAE5;
            --danger:           #E11D48;
            --danger-pale:      #FFE4E6;
            --info:             #7C3AED;
            --info-pale:        #EDE9FE;
            --sky:              #0284C7;
            --sky-pale:         #E0F2FE;
            --gray-50:          #F8FAFC;
            --gray-100:         #F1F5F9;
            --gray-200:         #E2E8F0;
            --gray-300:         #CBD5E1;
            --gray-400:         #94A3B8;
            --gray-500:         #64748B;
            --gray-600:         #475569;
            --gray-700:         #334155;
            --gray-800:         #1E293B;
            --gray-900:         #0F172A;
            --white:            #FFFFFF;
            --shadow-sm:        0 1px 3px rgba(0,0,0,0.06);
            --shadow-md:        0 4px 16px rgba(0,0,0,0.08);
            --shadow-lg:        0 8px 32px rgba(0,0,0,0.12);
            --radius:           12px;
            --radius-sm:        8px;
            --radius-xs:        6px;
            --transition:       all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #EEF2F9;
            color: var(--gray-800);
            min-height: 100vh;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--gray-100); border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gray-400); }

        .main-wrap {
            max-width: 1920px;
            margin: 0 auto;
            padding: 1.5rem 2rem;
            width: 100%;
        }

        @media (max-width: 1024px) {
            .main-wrap { padding: 1.25rem; }
        }

        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-light) 100%);
            border-radius: var(--radius);
            padding: 1.5rem 2rem;
            margin-bottom: 1.25rem;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -5%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: 10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.03) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .header-content {
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
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        .back-button {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            background: rgba(255, 255, 255, 0.08);
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            flex-shrink: 0;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.16);
            border-color: var(--accent);
            color: var(--accent);
            transform: translateX(-2px);
        }

        .header-icon-wrapper {
            width: 56px;
            height: 56px;
            background: rgba(245, 158, 11, 0.12);
            border: 1.5px solid rgba(245, 158, 11, 0.25);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 1.75rem;
            flex-shrink: 0;
        }

        .header-title h1 {
            color: var(--white);
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 0.1rem 0;
            letter-spacing: -0.3px;
        }

        .header-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
            margin: 0;
            font-weight: 400;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .shop-select-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .shop-select-label {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.8rem;
            font-weight: 600;
        }

        .shop-select {
            padding: 0.5rem 0.75rem;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-sm);
            background: rgba(255, 255, 255, 0.08);
            color: var(--white);
            font-size: 0.8rem;
            outline: none;
            cursor: pointer;
            transition: var(--transition);
            min-width: 180px;
            font-family: inherit;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='rgba(255,255,255,0.6)' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            padding-right: 2.25rem;
        }

        .shop-select:focus {
            background: rgba(255, 255, 255, 0.16);
            border-color: var(--accent);
        }

        .shop-select option {
            background: var(--primary-dark);
            color: var(--white);
        }

        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.55rem 1.25rem;
            background: var(--accent);
            color: var(--primary-dark);
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            box-shadow: 0 3px 12px rgba(245, 158, 11, 0.3);
            transition: var(--transition);
            text-decoration: none;
            font-family: inherit;
            white-space: nowrap;
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(245, 158, 11, 0.4);
            color: var(--primary-dark);
        }

        .btn-export i { font-size: 1.1rem; }

        .alert {
            padding: 0.9rem 1.25rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.875rem;
            font-weight: 500;
            animation: slideDown 0.35s ease;
        }

        .alert-success {
            background: var(--success-pale);
            color: #065F46;
            border-left: 4px solid var(--success);
        }

        .alert-danger {
            background: var(--danger-pale);
            color: #9F1239;
            border-left: 4px solid var(--danger);
        }

        .alert i { font-size: 1.15rem; margin-right: 0.5rem; }

        .btn-close-custom {
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            color: inherit;
            opacity: 0.5;
            padding: 0 0.25rem;
            transition: var(--transition);
            line-height: 1;
        }

        .btn-close-custom:hover { opacity: 1; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .top-filter-bar {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: var(--shadow-sm);
        }

        .filter-bar-inner {
            display: flex;
            align-items: flex-end;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .filter-field {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex: 1 1 160px;
            min-width: 140px;
        }

        .filter-field label {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .filter-field input,
        .filter-field select {
            padding: 0.55rem 0.75rem;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            background: var(--gray-50);
            font-size: 0.875rem;
            color: var(--gray-800);
            outline: none;
            transition: var(--transition);
            font-family: inherit;
            width: 100%;
        }

        .filter-shop-select {
            padding: 0.55rem 0.75rem;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            background: var(--gray-50);
            font-size: 0.875rem;
            color: var(--gray-800);
            outline: none;
            transition: var(--transition);
            font-family: inherit;
            width: 100%;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='rgba(100,116,139,0.8)' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            padding-right: 2.25rem;
            cursor: pointer;
        }

        .filter-shop-select:focus,
        .filter-field input:focus,
        .filter-field select:focus {
            border-color: var(--primary-light);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(26, 58, 107, 0.08);
        }

        .filter-shop-select option {
            background: var(--white);
            color: var(--gray-800);
        }

        .filter-actions {
            display: flex;
            gap: 0.6rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-apply {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.6rem 1.1rem;
            background: var(--primary-dark);
            color: var(--white);
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
            white-space: nowrap;
        }

        .btn-apply:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
        }

        .btn-reset {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.6rem 1.1rem;
            background: var(--white);
            color: var(--gray-600);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            font-family: inherit;
            white-space: nowrap;
        }

        .btn-reset:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }

        .stat-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .stat-card .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .stat-icon.primary { background: rgba(11, 30, 61, 0.08); color: var(--primary-dark); }
        .stat-icon.danger  { background: var(--danger-pale); color: var(--danger); }
        .stat-icon.warning { background: var(--accent-pale); color: #92400E; }
        .stat-icon.success { background: var(--success-pale); color: var(--success); }

        .stat-trend {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
        }

        .stat-trend.up { background: var(--success-pale); color: var(--success); }
        .stat-trend.down { background: var(--danger-pale); color: var(--danger); }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--gray-900);
            letter-spacing: -0.5px;
            margin-bottom: 0.2rem;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--gray-500);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .stat-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--gray-100);
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        .stat-footer span strong {
            color: var(--gray-700);
            font-weight: 600;
        }

        .table-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            width: 100%;
        }

        .table-responsive {
            overflow-x: auto;
            padding: 0 4px;
            -webkit-overflow-scrolling: touch;
        }

        .sales-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
            min-width: 900px;
        }

        .sales-table thead th {
            background: var(--gray-50);
            color: var(--gray-500);
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0.85rem 1rem;
            border-bottom: 2px solid var(--gray-200);
            white-space: nowrap;
            text-align: left;
        }

        .sales-table thead th:last-child { text-align: right; }

        .sales-table tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
            color: var(--gray-800);
        }

        .sales-table tbody td:last-child { text-align: right; }

        .sales-table tbody tr {
            transition: var(--transition);
        }

        .sales-table tbody tr:hover {
            background: #F8FAFF;
        }

        .sales-table tbody tr:last-child td {
            border-bottom: none;
        }

        .amount-mono {
            font-family: 'DM Mono', monospace;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.25rem 0.7rem;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }

        .status-badge.success {
            background: var(--success-pale);
            color: #065F46;
        }

        .status-badge.danger {
            background: var(--danger-pale);
            color: #9F1239;
        }

        .status-badge.secondary {
            background: var(--gray-200);
            color: var(--gray-600);
        }

        .status-badge.warning {
            background: var(--accent-pale);
            color: #92400E;
        }

        .status-badge i { font-size: 0.6rem; }

        .action-group {
            display: flex;
            gap: 0.4rem;
            justify-content: flex-end;
            align-items: center;
            flex-wrap: wrap;
        }

        .action-group form {
            display: inline;
            margin: 0;
        }

        .action-group .d-flex {
            display: flex;
            gap: 0.4rem;
            flex-wrap: wrap;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.35rem 0.7rem;
            border-radius: var(--radius-xs);
            cursor: pointer;
            transition: var(--transition);
            border: 1.5px solid transparent;
            font-family: inherit;
            background: transparent;
            white-space: nowrap;
        }

        .btn-action i { font-size: 0.9rem; }

        .btn-action.track {
            color: var(--info);
            border-color: var(--info);
            background: transparent;
        }

        .btn-action.track:hover {
            background: var(--info);
            color: var(--white);
        }

        .btn-action.view {
            color: var(--sky);
            border-color: var(--sky);
            background: transparent;
        }

        .btn-action.view:hover {
            background: var(--sky);
            color: var(--white);
        }

        .btn-action.undo {
            color: var(--gray-600);
            border-color: var(--gray-300);
            background: transparent;
        }

        .btn-action.undo:hover {
            background: var(--gray-600);
            color: var(--white);
            border-color: var(--gray-600);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
        }

        .empty-state .empty-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-100);
            border-radius: 50%;
            color: var(--gray-400);
            font-size: 2rem;
        }

        .empty-state h4 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-600);
            margin-bottom: 0.3rem;
        }

        .empty-state p {
            font-size: 0.85rem;
            color: var(--gray-500);
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-muted { color: var(--gray-500); }
        .fw-bold { font-weight: 700; }
        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .gap-1 { gap: 0.5rem; }
        .gap-2 { gap: 1rem; }
        .d-flex { display: flex; }
        .align-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .flex-wrap { flex-wrap: wrap; }
        .flex-column { flex-direction: column; }
        .w-100 { width: 100%; }

        @media (max-width: 1024px) {
            .header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .header-actions {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .shop-select-wrapper {
                width: 100%;
                flex-wrap: wrap;
            }

            .shop-select {
                flex: 1;
                min-width: 120px;
            }

            .btn-export {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .header-left {
                flex-wrap: wrap;
                width: 100%;
            }

            .header-icon-wrapper {
                width: 44px;
                height: 44px;
                font-size: 1.25rem;
            }

            .header-title h1 {
                font-size: 1.15rem;
            }

            .page-header {
                padding: 1.25rem;
            }

            .filter-bar-inner {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-actions {
                width: 100%;
            }

            .btn-apply,
            .btn-reset {
                flex: 1;
            }

            .sales-table thead { display: none; }

            .sales-table tbody tr {
                display: block;
                margin-bottom: 0.75rem;
                border: 1px solid var(--gray-200);
                border-radius: var(--radius-sm);
                padding: 0.75rem;
                background: var(--white);
            }

            .sales-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0;
                border-bottom: 1px solid var(--gray-100);
                text-align: left !important;
            }

            .sales-table tbody td:last-child {
                border-bottom: none;
                padding-top: 0.75rem;
                border-top: 1px solid var(--gray-200);
                margin-top: 0.5rem;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .sales-table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--gray-500);
                font-size: 0.7rem;
                text-transform: uppercase;
                letter-spacing: 0.04em;
            }

            .action-group {
                width: 100%;
                justify-content: flex-end;
            }

            .action-group .d-flex {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
            .stat-card { padding: 1rem; }
            .stat-value { font-size: 1.3rem; }
            .header-actions { gap: 0.5rem; }
            .shop-select { min-width: 100px; font-size: 0.75rem; }
            .btn-export { font-size: 0.75rem; padding: 0.45rem 1rem; }
        }

        @media (max-width: 360px) {
            .main-wrap { padding: 0.5rem; }
            .page-header { padding: 0.75rem; }
            .header-title h1 { font-size: 1rem; }
            .header-subtitle { font-size: 0.75rem; }
            .back-button { width: 36px; height: 36px; }
            .header-icon-wrapper { width: 38px; height: 38px; font-size: 1rem; }
        }
    </style>
</head>
<body>

@include("sidenav")

<main class="main-content">
    <div class="main-wrap">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="header-content">
                <div class="header-left">
                    <a href="javascript:history.back()" class="back-button" aria-label="{{ __('messages.back') }}">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <div class="header-icon-wrapper">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>
                    <div class="header-title">
                        <h1>{{ __('messages.sales_dashboard') }}</h1>
                        <p class="header-subtitle">{{ __('messages.track_and_analyze_sales') }}</p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="{{ route('sales.export', ['selectedDate' => request('selectedDate'), 'from_date' => request('from_date'), 'to_date' => request('to_date'), 'shop_id' => request('shop_id')]) }}" class="btn-export">
                        <i class="bi bi-download"></i> {{ __('messages.excel_report') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
        <div class="alert alert-success">
            <span><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</span>
            <button type="button" class="btn-close-custom" data-bs-dismiss="alert" aria-label="{{ __('messages.close') }}">×</button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            <span><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</span>
            <button type="button" class="btn-close-custom" data-bs-dismiss="alert" aria-label="{{ __('messages.close') }}">×</button>
        </div>
        @endif

        {{-- Top Filter Bar --}}
        <form class="top-filter-bar" action="{{ route('saleDate') }}" method="post">
            @csrf
            <div class="filter-bar-inner">
                @if(isset($allShops) && count($allShops) > 1)
                <div class="filter-field">
                    <label>Shop</label>
                    <select name="shop_id" class="filter-shop-select" onchange="this.form.submit()">
                        @foreach($allShops as $shop)
                        <option value="{{ $shop['id'] }}"
                            {{ (getCurrentShopId() == $shop['id'] ? 'selected' : '') }}>
                            {{ $shop['name'] }} ({{ $shop['location'] ?? __('messages.na') }})
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="filter-field">
                    <label>From</label>
                    <input type="date" name="from_date" value="{{ $fromDate ?? '' }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="filter-field">
                    <label>To</label>
                    <input type="date" name="to_date" value="{{ $toDate ?? '' }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="filter-field">
                    <label>Single Date</label>
                    <input type="date" name="selectedDate" value="{{ $selectedDate ?? '' }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn-apply">
                        <i class="bi bi-funnel"></i> {{ __('messages.apply_filter') }}
                    </button>
                    <a href="{{ url('sales') }}" class="btn-reset">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Stat Cards --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon primary"><i class="bi bi-currency-exchange"></i></div>
                    <span class="stat-trend up"><i class="bi bi-arrow-up"></i> +12%</span>
                </div>
                <div class="stat-value">Tsh {{ number_format($Tsale) }}</div>
                <div class="stat-label">{{ __('messages.total_sales') }}</div>
                <div class="stat-footer">
                    <span>{{ __('messages.dashboard_this_month') }}</span>
                    <span><strong>Tsh {{ number_format($Msale) }}</strong></span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon danger"><i class="bi bi-tag"></i></div>
                    <span class="stat-trend down"><i class="bi bi-arrow-down"></i> -8%</span>
                </div>
                <div class="stat-value">Tsh {{ number_format($Tdiscount) }}</div>
                <div class="stat-label">{{ __('messages.total_discount') }}</div>
                <div class="stat-footer">
                    <span>{{ __('messages.dashboard_this_month') }}</span>
                    <span><strong>Tsh {{ number_format($Mdiscount) }}</strong></span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon warning"><i class="bi bi-exclamation-triangle"></i></div>
                    <span class="stat-trend up"><i class="bi bi-arrow-up"></i> +5%</span>
                </div>
                <div class="stat-value">{{ number_format($Tdebt) }}</div>
                <div class="stat-label">{{ __('messages.total_debt') }}</div>
                <div class="stat-footer">
                    <span>{{ __('messages.dashboard_this_month') }}</span>
                    <span><strong>{{ number_format($Mdebt) }}</strong></span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon success"><i class="bi bi-graph-up-arrow"></i></div>
                    <span class="stat-trend up"><i class="bi bi-arrow-up"></i> +18%</span>
                </div>
                <div class="stat-value">Tsh {{ number_format($TNetProfit) }}</div>
                <div class="stat-label">{{ __('messages.total_profit') }}</div>
                <div class="stat-footer">
                    <span>{{ __('messages.dashboard_this_month') }}</span>
                    <span><strong>Tsh {{ number_format($MoNetProfit) }}</strong></span>
                </div>
            </div>
        </div>

        {{-- Search + Table --}}
        <div class="search-panel">
            <div class="search-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input type="search" id="searchInput" class="search-input"
                       placeholder="{{ __('messages.search_sales_placeholder') }}"
                       aria-label="{{ __('messages.search_sales_placeholder') }}">
            </div>
        </div>

        <div class="table-card">
            <div class="table-responsive">
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.sales_id') }}</th>
                            <th>{{ __('messages.customer') }}</th>
                            <th>{{ __('messages.sales_agent') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th class="text-center">{{ __('messages.col_qty') }}</th>
                            <th class="text-right">{{ __('messages.paid') }}</th>
                            <th class="text-right">{{ __('messages.credit') }}</th>
                            <th class="text-right">{{ __('messages.bank') }}</th>
                            <th class="text-right">{{ __('messages.total') }}</th>
                            <th class="text-right">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($sales->isEmpty())
                        <tr>
                            <td colspan="12">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>
                                    <h4>{{ __('messages.no_sales_found') }}</h4>
                                    <p>{{ __('messages.sales_transactions_will_appear') }}</p>
                                </div>
                            </td>
                        </tr>
                        @else
                            @foreach ($sales as $index => $sale)
                            @php
                                if($sale->status == 'Debt') {
                                    $statusClass = 'danger';
                                    $statusIcon = 'bi-exclamation-circle';
                                    $statusText = __('messages.credit');
                                } else if($sale->status == 'Return') {
                                    $statusClass = 'secondary';
                                    $statusIcon = 'bi-arrow-return-left';
                                    $statusText = __('messages.returned');
                                } else {
                                    $statusClass = 'success';
                                    $statusIcon = 'bi-check-circle';
                                    $statusText = $sale->transactionType ?? __('messages.cash_status');
                                }

                                // For returns, show the returned amount; otherwise paid + credit + bank
                                if($sale->status == 'Return') {
                                    $rowTotal = $sale->totalReturn ?: ($sale->totalPrice ?? 0);
                                } else {
                                    $rowTotal = ($sale->totalPaid ?? 0) + ($sale->totalCredit ?? 0) + ($sale->totalBank ?? 0);
                                }
                            @endphp
                            <tr>
                                <td data-label="#">{{ $index + 1 }}</td>
                                <td data-label="{{ __('messages.date') }}">{{ date('M d, Y', strtotime($sale->created_at)) }}</td>
                                <td data-label="{{ __('messages.sales_id') }}"><span class="fw-bold">{{ $sale->salesName }}</span></td>
                                <td data-label="{{ __('messages.customer') }}">{{ $sale->cName }}</td>
                                <td data-label="{{ __('messages.sales_agent') }}">{{ $sale->served_by }}</td>
                                <td data-label="{{ __('messages.status') }}">
                                    <span class="status-badge {{ $statusClass }}">
                                        <i class="bi {{ $statusIcon }}"></i> {{ $statusText }}
                                    </span>
                                </td>
                                <td data-label="{{ __('messages.col_qty') }}" class="text-center">
                                    <span class="amount-mono">{{ number_format($sale->totalQuantity ?? 0) }}</span>
                                </td>
                                <td data-label="{{ __('messages.paid') }}" class="text-right">
                                    <span class="amount-mono">Tsh {{ number_format($sale->totalPaid ?? 0) }}</span>
                                </td>
                                <td data-label="{{ __('messages.credit') }}" class="text-right">
                                    <span class="amount-mono">Tsh {{ number_format($sale->totalCredit ?? 0) }}</span>
                                </td>
                                <td data-label="{{ __('messages.bank') }}" class="text-right">
                                    <span class="amount-mono">Tsh {{ number_format($sale->totalBank ?? 0) }}</span>
                                </td>
                                <td data-label="{{ __('messages.total') }}" class="text-right">
                                    <span class="amount-mono fw-bold">Tsh {{ number_format($rowTotal) }}</span>
                                </td>
                                <td data-label="{{ __('messages.actions') }}">
                                    <div class="action-group">
                                        <button class="btn-action track"
                                                onclick="copyAndTrack('{{ $sale->salesName }}')"
                                                title="{{ __('messages.track_this_sale') }}">
                                            <i class="bi bi-diagram-3"></i>
                                        </button>
                                        <form method="post" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="account" value="{{ $sale->account }}">
                                            <div class="d-flex align-center gap-1">
                                                <button class="btn-action view" formaction="/viewSales"
                                                        name="sales_id" value="{{ $sale->sales_id }}"
                                                        title="{{ __('messages.view_sale') }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn-action undo" formaction="/undoSales"
                                                        name="salesName" value="{{ $sale->salesName }}"
                                                        title="{{ __('messages.undo_sale') }}">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#searchInput').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $('tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });

    function changeShop(shopId) {
        const url = new URL(window.location.href);
        url.searchParams.set('shop_id', shopId);
        window.location.href = url.toString();
    }

    function copyAndTrack(salesName) {
        navigator.clipboard.writeText(salesName).then(function() {
            window.location.href = '/track-sale?sales_name=' + encodeURIComponent(salesName);
        }).catch(function() {
            window.location.href = '/track-sale?sales_name=' + encodeURIComponent(salesName);
        });
    }
</script>

@include('footer')

</body>
</html>