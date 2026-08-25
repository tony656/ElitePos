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
        /* ── CSS Variables ── */
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

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--gray-100); border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gray-400); }

        /* ── Main Wrapper ── */
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

        /* ── Page Header ── */
        .page-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-light) 100%);
            border-radius: var(--radius);
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
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

        /* ── Shop Select ── */
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

        /* ── Export Button ── */
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

        /* ── Alert Messages ── */
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

        /* ── Stats Grid ── */
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

        /* ── Content Grid ── */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 1.5rem;
            align-items: start;
            width: 100%;
        }

        @media (max-width: 1200px) {
            .content-grid { 
                grid-template-columns: 1fr 300px;
                gap: 1.25rem;
            }
        }

        @media (max-width: 992px) {
            .content-grid { 
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
        }

        /* ── Search Panel ── */
        .search-panel {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            padding: 0.85rem 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: var(--shadow-sm);
        }

        .search-wrapper {
            position: relative;
        }

        .search-wrapper .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 1.1rem;
        }

        .search-wrapper input {
            width: 100%;
            padding: 0.7rem 1rem 0.7rem 3rem;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            background: var(--gray-50);
            font-size: 0.875rem;
            color: var(--gray-800);
            outline: none;
            transition: var(--transition);
            font-family: inherit;
        }

        .search-wrapper input::placeholder {
            color: var(--gray-400);
        }

        .search-wrapper input:focus {
            border-color: var(--primary-light);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(26, 58, 107, 0.08);
        }

        /* ── Table Card ── */
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

        /* ── Amount Mono ── */
        .amount-mono {
            font-family: 'DM Mono', monospace;
            font-weight: 600;
            font-size: 0.8rem;
        }

        /* ── Status Badges ── */
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

        /* ── Action Buttons ── */
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

        /* ── Empty State ── */
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

        /* ── Sidebar ── */
        .sidebar-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            width: 100%;
        }

        .sidebar-section {
            width: 100%;
        }

        /* ── Calendar ── */
        .calendar-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
            width: 100%;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.85rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .calendar-header h6 {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--primary-dark);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .calendar-header .calendar-hint {
            font-size: 0.65rem;
            color: var(--gray-400);
        }

        .calendar-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .calendar-nav-btn {
            width: 34px;
            height: 34px;
            border-radius: var(--radius-xs);
            background: transparent;
            border: 1.5px solid var(--gray-200);
            color: var(--gray-700);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .calendar-nav-btn:hover {
            background: var(--primary-dark);
            color: var(--white);
            border-color: var(--primary-dark);
        }

        .calendar-month-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gray-800);
        }

        .calendar-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar-grid th {
            padding: 0.4rem 0.2rem;
            font-size: 0.65rem;
            font-weight: 600;
            color: var(--gray-500);
            text-align: center;
        }

        .calendar-grid td {
            padding: 0.4rem 0.2rem;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.78rem;
            color: var(--gray-700);
            border-radius: var(--radius-xs);
        }

        .calendar-grid td:hover {
            background: var(--accent-pale);
            color: #92400E;
        }

        .calendar-grid td.today {
            background: var(--primary-dark);
            color: var(--white);
            font-weight: 700;
        }

        .calendar-grid td.has-sale {
            background: var(--success-pale);
            color: #065F46;
            font-weight: 600;
        }

        .calendar-grid td.has-sale:hover {
            background: var(--success);
            color: var(--white);
        }

        /* ── Summary Card ── */
        .summary-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
            width: 100%;
        }

        .summary-card h6 {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--primary-dark);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0 0 1rem 0;
            padding-bottom: 0.85rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-row .label {
            font-size: 0.8rem;
            color: var(--gray-500);
        }

        .summary-row .value {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--gray-800);
        }

        /* ── Responsive ── */
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

        @media (max-width: 992px) {
            .content-grid { 
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
            
            .sidebar-wrapper {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1.25rem;
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

            .sidebar-wrapper {
                grid-template-columns: 1fr;
            }

            /* Mobile table */
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
            .content-grid { grid-template-columns: 1fr; }
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

        /* ── Helper Utilities ── */
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
    </style>
</head>
<body>

@include("sidenav")

<main class="main-content">
    <div class="main-wrap">

        {{-- ── Page Header ── --}}
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
                    @if(isset($allShops) && count($allShops) > 1)
                    <div class="shop-select-wrapper">
                        <span class="shop-select-label"><i class="bi bi-shop"></i> {{ __('messages.shop_label') }}</span>
                        <select class="shop-select" onchange="changeShop(this.value)">
                            @foreach($allShops as $shop)
                            <option value="{{ $shop['id'] }}" 
                                {{ (getCurrentShopId() == $shop['id'] ? 'selected' : '') }}>
                                {{ $shop['name'] }} ({{ $shop['location'] ?? __('messages.na') }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <a href="{{ route('sales.export', ['selectedDate' => request('selectedDate')]) }}" class="btn-export">
                        <i class="bi bi-download"></i> {{ __('messages.excel_report') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- ── Alerts ── --}}
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

        {{-- ── Stat Cards ── --}}
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

        {{-- ── Content Grid ── --}}
        <div class="content-grid">
            {{-- Main Content --}}
            <div>
                {{-- Search Panel --}}
                <div class="search-panel">
                    <div class="search-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input type="search" id="searchInput" class="search-input" 
                               placeholder="{{ __('messages.search_sales_placeholder') }}" 
                               aria-label="{{ __('messages.search_sales_placeholder') }}">
                    </div>
                </div>

                {{-- Sales Table --}}
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
                                            <span class="amount-mono fw-bold">Tsh {{ number_format(($sale->totalPaid ?? 0) + ($sale->totalCredit ?? 0) + ($sale->totalBank ?? 0)) }}</span>
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

            {{-- Sidebar --}}
            @if (Auth::user()->levelStatus === 'Admin')
            <div class="sidebar-wrapper">
                {{-- Calendar Widget --}}
                <div class="sidebar-section">
                    <div class="calendar-card">
                        <div class="calendar-header">
                            <h6><i class="bi bi-calendar3"></i> {{ __('messages.filter_by_date') }}</h6>
                            <span class="calendar-hint">{{ __('messages.click_date') }}</span>
                        </div>

                        <div class="calendar-nav">
                            <button id="prevMonth" class="calendar-nav-btn" aria-label="{{ __('messages.previous_month') }}">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <span class="calendar-month-label" id="currentMonthYear"></span>
                            <button id="nextMonth" class="calendar-nav-btn" aria-label="{{ __('messages.next_month') }}">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>

                        <div id="calendar"></div>

                        <form id="dateForm" action="{{ route('saleDate') }}" method="post">
                            @csrf
                            <input type="hidden" id="selectedDate" name="selectedDate">
                        </form>
                    </div>
                </div>

                {{-- Summary Card --}}
                <div class="sidebar-section">
                    <div class="summary-card">
                        <h6><i class="bi bi-pie-chart"></i> {{ __('messages.summary') }}</h6>
                        <div class="summary-row">
                            <span class="label">{{ __('messages.total_sales') }}</span>
                            <span class="value">Tsh {{ number_format($Tsale ?? 0) }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">{{ __('messages.transactions') }}</span>
                            <span class="value">{{ $sales->count() }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">{{ __('messages.avg_sale') }}</span>
                            <span class="value">Tsh {{ number_format(($sales->count() > 0 ? ($Tsale ?? 0) / $sales->count() : 0)) }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">{{ __('messages.total_debt') }}</span>
                            <span class="value" style="color:var(--danger);">Tsh {{ number_format($Tdebt ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let saleDates = @json($monthlySaleDates);

    function fetchSalesDates(year, month) {
        const shopId = new URLSearchParams(window.location.search).get('shop_id');
        let url = `/getSalesDates?year=${year}&month=${month}`;
        if (shopId) {
            url += `&shop_id=${shopId}`;
        }
        fetch(url)
            .then(response => response.json())
            .then(data => {
                saleDates = data.dates;
                createCalendar(month, year);
            })
            .catch(error => console.error('{{ __('messages.error_fetching_sales_dates') }}', error));
    }

    function checkSalesForDate(dateString) {
        return saleDates.includes(dateString);
    }

    $(document).ready(function() {
        $('#searchInput').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $('tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });

    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

    function createCalendar(month, year) {
        const calendar = document.getElementById('calendar');
        calendar.innerHTML = '';

        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const firstDay = new Date(year, month, 1).getDay();

        const monthNames = [
            '{{ __('messages.january') }}', '{{ __('messages.february') }}', '{{ __('messages.march') }}', 
            '{{ __('messages.april') }}', '{{ __('messages.may') }}', '{{ __('messages.june') }}',
            '{{ __('messages.july') }}', '{{ __('messages.august') }}', '{{ __('messages.september') }}',
            '{{ __('messages.october') }}', '{{ __('messages.november') }}', '{{ __('messages.december') }}'
        ];
        document.getElementById('currentMonthYear').innerText = `${monthNames[month]} ${year}`;

        const today = new Date();
        const todayDay = today.getDate();
        const todayMonth = today.getMonth();
        const todayYear = today.getFullYear();

        const daysHeader = ['{{ __('messages.day_sun') }}', '{{ __('messages.day_mon') }}', '{{ __('messages.day_tue') }}', 
                           '{{ __('messages.day_wed') }}', '{{ __('messages.day_thu') }}', '{{ __('messages.day_fri') }}', 
                           '{{ __('messages.day_sat') }}'];
        
        const table = document.createElement('table');
        table.classList.add('calendar-grid');

        const headerRow = document.createElement('tr');
        daysHeader.forEach(day => {
            const th = document.createElement('th');
            th.innerText = day;
            headerRow.appendChild(th);
        });
        table.appendChild(headerRow);

        let row = document.createElement('tr');

        for (let i = 0; i < firstDay; i++) {
            const td = document.createElement('td');
            row.appendChild(td);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const td = document.createElement('td');
            td.innerText = day;

            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            if (day === todayDay && month === todayMonth && year === todayYear) {
                td.classList.add('today');
            }

            if (saleDates.includes(dateString)) {
                td.classList.add('has-sale');
            }

            td.addEventListener('click', function() {
                const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                if (checkSalesForDate(formattedDate)) {
                    document.getElementById('selectedDate').value = formattedDate;
                    document.getElementById('dateForm').submit();
                }
            });

            row.appendChild(td);

            if ((day + firstDay) % 7 === 0) {
                table.appendChild(row);
                row = document.createElement('tr');
            }
        }

        if (row.children.length > 0) {
            table.appendChild(row);
        }

        calendar.appendChild(table);
    }

    document.getElementById('prevMonth').addEventListener('click', function() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        fetchSalesDates(currentYear, currentMonth + 1);
    });

    document.getElementById('nextMonth').addEventListener('click', function() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        fetchSalesDates(currentYear, currentMonth + 1);
    });

    createCalendar(currentMonth, currentYear);

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