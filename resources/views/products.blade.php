<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{config("app.name")}} - Products</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Bootstrap (for modals) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --navy: #0B1E3D;
            --navy-mid: #112952;
            --navy-light: #1A3A6B;
            --amber: #F59E0B;
            --amber-pale: #FEF3C7;
            --emerald: #059669;
            --emerald-pale: #D1FAE5;
            --rose: #E11D48;
            --rose-pale: #FFE4E6;
            --violet: #7C3AED;
            --violet-pale: #EDE9FE;
            --sky: #0284C7;
            --sky-pale: #E0F2FE;
            --fuchsia: #C026D3;
            --fuchsia-pale: #FAE8FF;
            --slate-50: #F8FAFC;
            --slate-100: #F1F5F9;
            --slate-200: #E2E8F0;
            --slate-300: #CBD5E1;
            --slate-400: #94A3B8;
            --slate-500: #64748B;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1E293B;
            --white: #FFFFFF;
            --shadow-sm: 0 1px 3px rgba(11,30,61,0.06);
            --shadow-md: 0 4px 12px rgba(11,30,61,0.08);
            --shadow-lg: 0 8px 30px rgba(11,30,61,0.12);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--slate-50);
            color: var(--slate-800);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        .main-wrap {
            max-width: 1600px;
            margin: 0 auto;
            padding: 1.5rem 2rem;
        }

        /* ── Header ── */
        .pg-header {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%);
            border-radius: 16px;
            padding: 1.5rem 2rem;
            margin-bottom: 1.75rem;
            box-shadow: 0 12px 40px rgba(11,30,61,0.25);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }
        .pg-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(245,158,11,0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .pg-header::after {
            content: '';
            position: absolute;
            bottom: -60%;
            left: 20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.03) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .pg-title-wrap { display: flex; flex-direction: column; gap: 0.35rem; position: relative; z-index: 1; }
        .pg-title {
            color: var(--white);
            font-size: 1.6rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: -0.5px;
        }
        .pg-title span { color: var(--amber); }
        .pg-subtitle {
            color: rgba(255,255,255,0.55);
            font-size: 0.875rem;
            font-weight: 400;
            letter-spacing: 0.01em;
        }

        .hbtn-export {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.65rem 1.4rem;
            background: var(--amber);
            color: var(--navy);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(245,158,11,0.35);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            position: relative;
            z-index: 1;
            letter-spacing: 0.01em;
        }
        .hbtn-export:hover {
            box-shadow: 0 6px 24px rgba(245,158,11,0.5);
            transform: translateY(-2px);
            color: var(--navy);
            background: #FBBF24;
        }
        .hbtn-export:active {
            transform: translateY(0);
        }

        /* ── Stats Cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: var(--white);
            border: 1px solid var(--slate-200);
            border-radius: 14px;
            padding: 1.4rem 1.5rem;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.25s;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: var(--stat-color);
            border-radius: 14px 14px 0 0;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, var(--stat-bg) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(30%, -30%);
            pointer-events: none;
            opacity: 0.5;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--stat-color);
        }

        .stat-card.navy    { --stat-color: var(--navy);    --stat-bg: rgba(11,30,61,0.06); }
        .stat-card.emerald { --stat-color: var(--emerald); --stat-bg: rgba(5,150,105,0.08); }
        .stat-card.sky     { --stat-color: var(--sky);     --stat-bg: rgba(2,132,199,0.08); }

        .stat-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .stat-info { flex: 1; }
        .stat-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--slate-400);
            margin-bottom: 0.5rem;
        }
        .stat-value {
            font-size: 1.85rem;
            font-weight: 600;
            color: var(--navy);
            line-height: 1.15;
            letter-spacing: -0.75px;
            font-variant-numeric: tabular-nums;
        }
        .stat-trend {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.3rem 0.7rem;
            border-radius: 20px;
            margin-top: 0.5rem;
        }
        .stat-trend.pos { background: var(--emerald-pale); color: #065F46; }

        .stat-icon {
            width: 52px;
            height: 52px;
            background: var(--stat-bg);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: var(--stat-color);
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }
        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(-5deg);
        }

        /* ── Search Panel ── */
        .search-panel {
            background: var(--white);
            border: 1px solid var(--slate-200);
            border-radius: 14px;
            padding: 1.2rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .search-row {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }
        .search-box {
            flex: 1;
            min-width: 280px;
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
            font-size: 1rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .search-input {
            font-size: 0.9rem;
            width: 100%;
            padding: 0.7rem 0.9rem 0.7rem 2.6rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 10px;
            background: var(--slate-50);
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            font-family: inherit;
        }
        .search-input:focus {
            border-color: var(--navy-light);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(26,58,107,0.08);
        }
        .search-input:focus ~ .search-icon {
            color: var(--navy-light);
        }
        .search-input::placeholder { color: var(--slate-400); font-weight: 400; }

        .search-hint {
            font-size: 0.74rem;
            color: var(--slate-400);
            margin-top: 0.4rem;
            padding-left: 2.6rem;
            display: none;
        }
        .search-hint.visible { display: block; }

        .sort-wrap {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .sort-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--slate-500);
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }
        .sort-select {
            font-size: 0.85rem;
            padding: 0.55rem 2.2rem 0.55rem 0.8rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 9px;
            background: var(--white);
            color: var(--slate-800);
            cursor: pointer;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            font-family: inherit;
        }
        .sort-select:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 4px rgba(26,58,107,0.08);
        }
        .sort-select:hover {
            border-color: var(--slate-300);
        }

        /* ── Table Panel ── */
        .panel {
            background: var(--white);
            border: 1px solid var(--slate-200);
            border-radius: 14px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .panel-head {
            background: linear-gradient(to bottom, var(--slate-50), var(--white));
            border-bottom: 1px solid var(--slate-200);
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.85rem;
        }
        .panel-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--navy);
            letter-spacing: -0.3px;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .panel-title::before {
            content: '';
            width: 4px;
            height: 20px;
            background: var(--amber);
            border-radius: 2px;
        }
        .panel-title small {
            font-weight: 400;
            color: var(--slate-400);
            font-size: 0.82rem;
        }

        .panel-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        .pbtn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.5rem 0.9rem;
            border-radius: 9px;
            border: 1.5px solid;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--white);
            font-family: inherit;
            letter-spacing: 0.01em;
            white-space: nowrap;
        }
        .pbtn-outline {
            border-color: var(--slate-200);
            color: var(--slate-600);
            background: var(--white);
        }
        .pbtn-outline:hover {
            background: var(--slate-50);
            border-color: var(--navy-light);
            color: var(--navy);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(11,30,61,0.08);
        }
        .pbtn-danger {
            border-color: var(--rose);
            color: var(--rose);
            background: transparent;
        }
        .pbtn-danger:hover {
            background: var(--rose);
            color: var(--white);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(225,29,72,0.3);
        }
        .pbtn-primary {
            border-color: var(--amber);
            color: var(--navy);
            background: var(--amber);
        }
        .pbtn-primary:hover {
            background: #FBBF24;
            border-color: #FBBF24;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245,158,11,0.35);
        }
        .pbtn-warning {
            border-color: #92400E;
            color: #92400E;
            background: var(--amber-pale);
        }
        .pbtn-warning:hover {
            background: #FBBF24;
            border-color: #FBBF24;
            color: var(--navy);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245,158,11,0.3);
        }
        .pbtn:disabled {
            opacity: 0.35;
            cursor: not-allowed;
            pointer-events: none;
            transform: none !important;
            box-shadow: none !important;
        }
        .pbtn i {
            font-size: 0.95rem;
        }

        /* ── Table ── */
        .table-wrap {
            overflow-x: auto;
            overflow-y: auto;
            max-height: 70vh;
            position: relative;
        }

        table.prod-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }
        table.prod-tbl thead {
            position: sticky;
            top: 0;
            z-index: 20;
        }
        table.prod-tbl thead th {
            background: var(--slate-50);
            color: var(--slate-500);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0.8rem 1rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
            position: relative;
            z-index: 20;
            text-align: left;
        }
        table.prod-tbl tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
            color: var(--slate-800);
            transition: background 0.15s;
        }
        table.prod-tbl tbody tr {
            transition: background 0.15s;
        }
        table.prod-tbl tbody tr:hover td {
            background: #F8FAFF;
        }
        table.prod-tbl tbody tr:last-child td {
            border-bottom: none;
        }

        .chk-cell { width: 44px; padding-right: 0; }
        .custom-chk {
            width: 20px;
            height: 20px;
            border: 2px solid var(--slate-300);
            border-radius: 6px;
            cursor: pointer;
            position: relative;
            background: var(--white);
            display: inline-block;
            transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .custom-chk:hover {
            border-color: var(--navy-light);
        }
        .custom-chk.checked {
            background: var(--navy);
            border-color: var(--navy);
            transform: scale(1.05);
        }
        .custom-chk.checked::after {
            content: '✓';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            color: var(--white);
            font-size: 12px;
            font-weight: 800;
        }

        .prod-info { display: flex; align-items: center; gap: 0.85rem; }
        .prod-avatar {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--slate-100), var(--slate-50));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--navy);
            font-size: 0.95rem;
            font-weight: 700;
            flex-shrink: 0;
            overflow: hidden;
            border: 1px solid var(--slate-200);
            position: relative;
        }
        .prod-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        tr:hover .prod-avatar img {
            transform: scale(1.5);
        }

        .prod-name {
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 0.2rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.3rem;
        }
        .prod-sub {
            font-size: 0.78rem;
            color: var(--slate-500);
            font-weight: 400;
        }

        .offer-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.15rem 0.45rem;
            background: linear-gradient(135deg, var(--fuchsia), #A21CAF);
            color: var(--white);
            border-radius: 5px;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            letter-spacing: 0.01em;
            white-space: nowrap;
        }
        .stock-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            opacity: 0.7;
        }
        .stock-badge.low    { background: var(--rose-pale);    color: #9F1239; }
        .stock-badge.medium { background: var(--amber-pale);   color: #92400E; }
        .stock-badge.high   { background: var(--emerald-pale); color: #065F46; }

        .price {
            font-weight: 500;
            color: var(--slate-700);
            font-size: 0.85rem;
            font-variant-numeric: tabular-nums;
        }
        .price.cost {
            color: var(--rose);
            font-weight: 600;
        }

        .cat-badge {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.3rem 0.7rem;
            background: var(--slate-100);
            color: var(--slate-600);
            border-radius: 7px;
            letter-spacing: 0.01em;
            border: 1px solid var(--slate-200);
        }

        .action-cell { display: flex; gap: 0.4rem; }
        .action-icon-btn {
            width: 34px;
            height: 34px;
            border: 1.5px solid;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
            font-family: inherit;
        }
        .action-icon-btn.view   { border-color: var(--sky);    color: var(--sky); }
        .action-icon-btn.delete { border-color: var(--rose);   color: var(--rose); }
        .action-icon-btn.restock { border-color: var(--emerald); color: var(--emerald); }
        .action-icon-btn.return  { border-color: var(--amber);  color: var(--amber); }
        .action-icon-btn.offers  { border-color: var(--fuchsia); color: var(--fuchsia); }
        .action-icon-btn.compare { border-color: var(--violet); color: var(--violet); }

        .action-icon-btn:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }
        .action-icon-btn.view:hover    { background: var(--sky-pale); }
        .action-icon-btn.delete:hover  { background: var(--rose-pale); }
        .action-icon-btn.restock:hover { background: var(--emerald-pale); }
        .action-icon-btn.return:hover  { background: var(--amber-pale); }
        .action-icon-btn.offers:hover  { background: var(--fuchsia-pale); }
        .action-icon-btn.compare:hover { background: var(--violet-pale); }

        .empty-state {
            text-align: center;
            padding: 4rem 1.5rem;
            color: var(--slate-400);
        }
        .empty-state i {
            font-size: 4rem;
            display: block;
            margin-bottom: 1rem;
            opacity: 0.25;
        }
        .empty-state-title {
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--slate-600);
            margin-bottom: 0.4rem;
        }
        .empty-state p {
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        /* ── Pagination ── */
        .pagination-wrap {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--slate-200);
            background: var(--white);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pg-info {
            font-size: 0.82rem;
            color: var(--slate-500);
            font-weight: 500;
        }
        .pg-info strong {
            color: var(--navy);
            font-weight: 700;
        }

        .pg-list {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 0.35rem;
            flex-wrap: wrap;
        }
        .pg-item { display: flex; }
        .pg-item.disabled .pg-link {
            opacity: 0.35;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pg-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.5rem 0.9rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 9px;
            color: var(--slate-600);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.82rem;
            transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--white);
            min-width: 38px;
            text-align: center;
            font-family: inherit;
        }
        .pg-link:hover:not(.pg-link-active) {
            border-color: var(--navy-light);
            color: var(--navy);
            background: var(--slate-50);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(11,30,61,0.08);
        }
        .pg-link-active {
            background: var(--navy);
            color: var(--white);
            border-color: var(--navy);
            cursor: default;
            font-weight: 600;
        }

        .per-page { display: flex; align-items: center; gap: 0.6rem; }
        .per-page-label {
            font-size: 0.82rem;
            color: var(--slate-500);
            font-weight: 500;
            white-space: nowrap;
        }
        .per-page-select {
            font-size: 0.82rem;
            padding: 0.5rem 2.2rem 0.5rem 0.8rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 9px;
            background: var(--white);
            color: var(--slate-800);
            cursor: pointer;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            font-family: inherit;
        }
        .per-page-select:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 4px rgba(26,58,107,0.08);
        }

        /* ── Alerts & misc ── */
        .alert-box {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.85rem 1.1rem;
            background: var(--rose-pale);
            border-left: 4px solid var(--rose);
            border-radius: 10px;
            font-size: 0.85rem;
            color: #9F1239;
            margin-bottom: 1.25rem;
            font-weight: 500;
        }

        .image-preview-tooltip {
            position: fixed;
            pointer-events: none;
            z-index: 99999;
            display: none;
            width: 240px;
            height: 240px;
            background: #fff;
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }
        .image-preview-tooltip img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ── Modal overrides ── */
        .modal-header-navy {
            background: linear-gradient(135deg, var(--navy), var(--navy-mid));
            color: var(--white);
            border-bottom: none;
            padding: 1.1rem 1.5rem;
        }
        .modal-header-navy .modal-title {
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: -0.3px;
        }
        .modal-header-navy .btn-close {
            filter: invert(1);
            opacity: 0.7;
        }
        .modal-header-navy .btn-close:hover {
            opacity: 1;
        }
        .modal-content {
            border: none;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 24px 64px rgba(11,30,61,0.35);
        }
        .modal-body { padding: 1.75rem; }
        .modal-footer {
            border-top: 1px solid var(--slate-200);
            padding: 1.1rem 1.75rem;
        }
        .field { margin-bottom: 1.15rem; }
        .field:last-child { margin-bottom: 0; }
        .field-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--slate-600);
            margin-bottom: 0.4rem;
            letter-spacing: 0.01em;
        }
        .field-input {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 9px;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
            font-family: inherit;
            color: var(--slate-800);
            background: var(--white);
        }
        .field-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 4px rgba(26,58,107,0.08);
        }
        .mbtn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.6rem 1.25rem;
            border-radius: 9px;
            font-size: 0.875rem;
            font-weight: 600;
            border: 1.5px solid;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
        }
        .mbtn-outline {
            border-color: var(--slate-200);
            background: var(--white);
            color: var(--slate-600);
        }
        .mbtn-outline:hover {
            background: var(--slate-50);
            border-color: var(--slate-300);
        }
        .mbtn-primary {
            border-color: var(--amber);
            background: var(--amber);
            color: var(--navy);
        }
        .mbtn-primary:hover {
            background: #FBBF24;
            border-color: #FBBF24;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245,158,11,0.35);
        }
        .mbtn-danger {
            border-color: var(--rose);
            background: var(--rose);
            color: var(--white);
        }
        .mbtn-danger:hover {
            background: #BE123C;
            border-color: #BE123C;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(225,29,72,0.3);
        }
        .mbtn-warning {
            border-color: #92400E;
            background: var(--amber-pale);
            color: #92400E;
        }
        .mbtn-warning:hover {
            background: #FBBF24;
            border-color: #FBBF24;
            color: var(--navy);
            transform: translateY(-1px);
        }
        .row-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }
        .selected-list {
            max-height: 200px;
            overflow-y: auto;
            border: 1.5px solid var(--slate-200);
            border-radius: 10px;
            padding: 0.35rem;
            background: var(--slate-50);
        }
        .selected-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0.85rem;
            border-bottom: 1px solid var(--slate-100);
            font-size: 0.85rem;
            background: var(--white);
            border-radius: 7px;
            margin-bottom: 0.25rem;
        }
        .selected-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .selected-item-name {
            font-weight: 600;
            color: var(--navy);
            font-size: 0.85rem;
        }
        .selected-item-badge {
            font-size: 0.72rem;
            background: var(--slate-100);
            padding: 0.2rem 0.6rem;
            border-radius: 5px;
            color: var(--slate-500);
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        /* ── API Panel ── */
        .api-compare-panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 14px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .api-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.85rem;
        }

        .api-title {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy);
        }
        .api-title i {
            font-size: 1.2rem;
            color: var(--sky);
        }

        .api-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            background: var(--slate-100);
            color: var(--slate-500);
        }

        .api-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 1.25rem;
            padding-top: 1.25rem;
            border-top: 1.5px solid var(--slate-200);
        }

        .api-summary-item {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .api-summary-item .label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--slate-400);
        }

        .api-summary-item .value {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--navy);
            font-variant-numeric: tabular-nums;
        }

        /* ── Animations ── */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .stat-card {
            animation: slideUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        .stat-card:nth-child(1) { animation-delay: 0.05s; }
        .stat-card:nth-child(2) { animation-delay: 0.12s; }
        .stat-card:nth-child(3) { animation-delay: 0.19s; }

        /* ── Responsive ── */
        @media (max-width: 1024px) {
            .main-wrap { padding: 1.25rem; }
        }

        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
            .pg-header { padding: 1.1rem 1.25rem; margin-bottom: 1.25rem; border-radius: 12px; }
            .pg-title { font-size: 1.3rem; }
            .pg-subtitle { font-size: 0.8rem; }
            .stats-grid { grid-template-columns: 1fr; gap: 0.85rem; }
            .stat-value { font-size: 1.5rem; }
            .stat-card { padding: 1.2rem 1.25rem; }
            .panel-head { flex-direction: column; align-items: flex-start; padding: 1rem 1.25rem; }
            .panel-actions { width: 100%; }
            .pbtn { flex: 1; justify-content: center; font-size: 0.78rem; padding: 0.5rem 0.7rem; }
            .search-row { flex-direction: column; align-items: stretch; }
            .search-panel { padding: 1rem 1.25rem; }
            .pagination-wrap { flex-direction: column; align-items: flex-start; padding: 1rem 1.25rem; }
            .row-fields { grid-template-columns: 1fr; }
            .api-summary { grid-template-columns: 1fr 1fr; }
            .hbtn-export { width: 100%; justify-content: center; }
        }

        @media (max-width: 480px) {
            .main-wrap { padding: 0.75rem; }
            .pg-title { font-size: 1.15rem; }
            .stat-value { font-size: 1.35rem; }
            .stat-icon { width: 44px; height: 44px; font-size: 1.2rem; }
            .panel-actions { flex-direction: column; }
            .pbtn { width: 100%; justify-content: center; }
            .pg-list { justify-content: center; }
            .pg-link { padding: 0.4rem 0.7rem; font-size: 0.78rem; min-width: 34px; }
        }


        /* =========================================================
           KESSY POS BLUE THEME — visual redesign overrides
           Existing Blade markup, IDs, modals and JavaScript retained.
           ========================================================= */
        :root {
            --navy: #084b87;
            --navy-mid: #0756a6;
            --navy-light: #0b66b2;
            --amber: #0b65b1;
            --amber-pale: #e8f2fc;
            --emerald: #07866b;
            --emerald-pale: #e5f7f1;
            --rose: #e5485d;
            --rose-pale: #fff0f1;
            --violet: #7844d6;
            --violet-pale: #f1ebff;
            --sky: #0874c5;
            --sky-pale: #e8f3ff;
            --fuchsia: #c026a8;
            --fuchsia-pale: #fceafa;
            --slate-50: #f5f9fd;
            --slate-100: #edf3f9;
            --slate-200: #dce7f1;
            --slate-300: #c5d5e5;
            --slate-400: #8da4bb;
            --slate-500: #607b96;
            --slate-600: #45627e;
            --slate-700: #2d4c69;
            --slate-800: #183a5a;
            --white: #ffffff;
            --shadow-sm: 0 2px 8px rgba(8, 61, 108, .06);
            --shadow-md: 0 5px 16px rgba(8, 61, 108, .09);
            --shadow-lg: 0 10px 28px rgba(8, 61, 108, .13);
        }

        body { background: #f3f7fb; color: #183a5a; }
        .main-wrap { max-width: 1800px; padding: 1.1rem 1.25rem 1.5rem; }

        /* Page heading: clean white surface like the reference screen. */
        .pg-header {
            background: transparent;
            border: 0;
            border-radius: 0;
            box-shadow: none;
            padding: .25rem 0 .9rem;
            margin-bottom: .55rem;
            overflow: visible;
        }
        .pg-header::before, .pg-header::after { display: none; }
        .pg-title { color: #064b86; font-size: 1.42rem; letter-spacing: -.35px; }
        .pg-title span { color: #0871c2; }
        .pg-subtitle { color: #647f99; font-size: .78rem; }
        .hbtn-export {
            color: #fff;
            background: #0866b2;
            border: 1px solid #0866b2;
            border-radius: 6px;
            box-shadow: 0 3px 8px rgba(8, 102, 178, .18);
            padding: .58rem 1rem;
            font-size: .78rem;
        }
        .hbtn-export:hover { color: #fff; background: #064f91; border-color: #064f91; }

        /* Compact pastel statistic cards. */
        .stats-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .7rem;
            margin-bottom: .85rem;
        }
        .stat-card {
            min-height: 88px;
            padding: .8rem .95rem;
            border: 1px solid #dce8f3;
            border-radius: 8px;
            box-shadow: none;
            animation: none;
        }
        .stat-card::before { display: none; }
        .stat-card::after { width: 90px; height: 90px; opacity: .25; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-card.navy { background: #eff6ff; --stat-color: #0866b2; --stat-bg: #d8eaff; }
        .stat-card.emerald { background: #effaf6; --stat-color: #07866b; --stat-bg: #d6f3e8; }
        .stat-card.sky { background: #f1f5ff; --stat-color: #7146cf; --stat-bg: #e4dcff; }
        .stat-row { align-items: center; }
        .stat-label {
            color: #59748f;
            font-size: .68rem;
            text-transform: none;
            letter-spacing: 0;
            margin-bottom: .18rem;
        }
        .stat-value {
            color: #084b87;
            font-size: 1.45rem;
            line-height: 1.12;
            letter-spacing: -.35px;
            font-weight: 700;
        }
        .stat-trend {
            padding: 0;
            margin-top: .22rem;
            background: transparent !important;
            color: #6b849c !important;
            font-size: .66rem;
        }
        .stat-icon {
            width: 43px;
            height: 43px;
            border-radius: 9px;
            color: #fff;
            background: var(--stat-color);
            font-size: 1.18rem;
        }

        /* Search and product panel. */
        .search-panel, .panel, .api-compare-panel {
            border: 1px solid #dce7f1;
            border-radius: 8px;
            box-shadow: 0 2px 7px rgba(8, 61, 108, .045);
        }
        .search-panel { padding: .8rem .95rem; margin-bottom: .8rem; }
        .search-row { gap: .7rem; }
        .search-input {
            background: #fff;
            border: 1px solid #d4e1ed;
            border-radius: 6px;
            padding: .58rem .8rem .58rem 2.35rem;
            font-size: .78rem;
        }
        .search-input:focus { border-color: #0b68b4; box-shadow: 0 0 0 3px rgba(11,104,180,.09); }
        .sort-select, .per-page-select {
            border: 1px solid #d4e1ed;
            border-radius: 6px;
            font-size: .76rem;
            padding-top: .48rem;
            padding-bottom: .48rem;
        }
        .sort-label { color: #55728e; font-size: .75rem; }
        .panel { overflow: hidden; background: #fff; }
        .panel-head {
            padding: .75rem .9rem;
            background: #fff;
            border-bottom: 1px solid #dce7f1;
        }
        .panel-title { color: #084b87; font-size: .96rem; }
        .panel-title::before { background: #0b68b4; width: 3px; height: 17px; }

        /* Buttons: blue primary, neutral outline, clear danger. */
        .pbtn {
            border-radius: 6px;
            padding: .43rem .68rem;
            font-size: .73rem;
        }
        .pbtn-primary {
            border-color: #0866b2;
            color: #fff;
            background: #0866b2;
        }
        .pbtn-primary:hover { color: #fff; background: #064f91; border-color: #064f91; }
        .pbtn-outline { border-color: #d4e1ed; color: #42617e; }
        .pbtn-outline:hover { border-color: #0b68b4; color: #0756a6; background: #eff6ff; }
        .pbtn-warning { border-color: #0b68b4; color: #0756a6; background: #eaf4ff; }
        .pbtn-warning:hover { border-color: #0756a6; color: #fff; background: #0756a6; }
        .pbtn-danger { border-color: #e5485d; color: #d9364c; }

        /* Dense table with the same deep-blue header and fine grid lines. */
        .table-wrap { max-height: 68vh; }
        table.prod-tbl { font-size: .76rem; }
        table.prod-tbl thead th {
            background: #07569b;
            color: #fff;
            font-size: .66rem;
            letter-spacing: .015em;
            text-transform: none;
            font-weight: 600;
            padding: .62rem .68rem;
            border: 1px solid rgba(255,255,255,.13);
        }
        table.prod-tbl tbody td {
            padding: .52rem .68rem;
            border: 1px solid #e5edf5;
            color: #244866;
            font-size: .74rem;
        }
        table.prod-tbl tbody tr:nth-child(even) td { background: #f8fbfe; }
        table.prod-tbl tbody tr:hover td { background: #edf6ff; }
        .chk-cell { width: 38px; }
        .custom-chk { width: 17px; height: 17px; border-radius: 4px; }
        .custom-chk.checked { background: #0866b2; border-color: #0866b2; }
        .prod-info { gap: .6rem; }
        .prod-avatar { width: 35px; height: 35px; border-radius: 7px; }
        .prod-name { color: #164a78; font-size: .76rem; margin-bottom: .08rem; }
        .prod-sub { color: #7189a0; font-size: .68rem; }
        .stock-badge { padding: .22rem .52rem; font-size: .68rem; }
        .price { color: #31516e; font-size: .74rem; }
        .price.cost { color: #d33c50; }
        .cat-badge { padding: .22rem .5rem; font-size: .68rem; border-radius: 5px; }
        .action-cell { gap: .28rem; }
        .action-icon-btn {
            width: 28px; height: 27px; border-radius: 5px;
            border: 0; color: #fff !important; background: #0870bd !important;
            font-size: .75rem;
        }
        .action-icon-btn:hover { background: #064f91 !important; transform: translateY(-1px); }

        /* Footer and modal accents. */
        .pagination-wrap { padding: .75rem .9rem; border-top: 1px solid #dce7f1; }
        .pg-link { border-radius: 6px; border-color: #d6e2ed; font-size: .75rem; padding: .4rem .65rem; }
        .pg-link-active { background: #0866b2; border-color: #0866b2; color: #fff; }
        .modal-header-navy { background: linear-gradient(120deg, #064b86, #0870bd); }
        .mbtn-primary { background: #0866b2; border-color: #0866b2; color: #fff; }
        .mbtn-primary:hover { background: #064f91; border-color: #064f91; color: #fff; }

        @media (min-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }
        @media (max-width: 768px) {
            .main-wrap { padding: .8rem; }
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .stat-card { min-height: 82px; padding: .7rem; }
            .stat-value { font-size: 1.2rem; }
            .stat-icon { width: 37px; height: 37px; font-size: 1rem; }
            .panel-actions { gap: .4rem; }
            .table-wrap { max-height: none; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
            .pg-header { align-items: flex-start; }
            .hbtn-export { width: auto; }
            .search-box { min-width: 100%; }
        }

    </style>
</head>
<body>
    @include("sidenav")
<main class="main-content">
    <div class="main-wrap">

        {{-- ── Header ── --}}
        <div class="pg-header">
            <div class="pg-title-wrap">
                <div class="pg-title">
                    Products / <span>Inventory</span>
                </div>
                <div class="pg-subtitle">Manage your product catalog, stock levels, and pricing</div>
            </div>
            <button class="hbtn-export" onclick="downloadReport()">
                <i class="bi bi-file-earmark-text"></i> Export Report
            </button>
        </div>

        {{-- ── Stats Cards ── --}}
        <div class="stats-grid">
            <div class="stat-card navy">
                <div class="stat-row">
                    <div class="stat-info">
                        <div class="stat-label">Total Products</div>
                        <div class="stat-value">{{ number_format($TProducts) }}</div>
                        <div class="stat-trend pos"><i class="bi bi-arrow-up"></i> All Active</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-box-seam-fill"></i></div>
                </div>
            </div>

            <div class="stat-card emerald">
                <div class="stat-row">
                    <div class="stat-info">
                        <div class="stat-label">Inventory Worth (Cost)</div>
                        <div class="stat-value">Tsh {{ number_format($totalCostWorth, 2) }}</div>
                        <div class="stat-trend pos"><i class="bi bi-cash-stack"></i> Cost Basis</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
                </div>
            </div>

            <div class="stat-card sky">
                <div class="stat-row">
                    <div class="stat-info">
                        <div class="stat-label">Inventory Worth (Selling)</div>
                        <div class="stat-value">Tsh {{ number_format($totalSellingWorth, 2) }}</div>
                        <div class="stat-trend pos"><i class="bi bi-graph-up-arrow"></i> Expected Revenue</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-tags-fill"></i></div>
                </div>
            </div>
        </div>

        {{-- ── Search Panel ── --}}
        <div class="search-panel">
            <div class="search-row">
                <div class="search-box">
                    <i class="bi bi-search search-icon"></i>
                    <input type="search" class="search-input" id="searchInput"
                        placeholder="Search products by name, ID, or category..."
                        autocomplete="off"
                        value="{{ old('search', request('search') ?? '') }}">
                    <div class="search-hint" id="searchHint">
                        Press <strong>Enter</strong> to search all pages · Type to filter this page
                    </div>
                </div>

                <div class="sort-wrap">
                    <span class="sort-label"><i class="bi bi-shop"></i> Shop</span>
                    <select id="shopSelect" class="sort-select">
                        <option disabled {{ !session('selected_shop_id') ? 'selected' : '' }}>Select shop</option>
                        @foreach($getAllAccounts as $shop)
                            <option value="{{ $shop['id'] }}"
                            {{ (getCurrentShopId() == $shop['id'] ? 'selected' : '') }}>
                            {{ $shop['name'] }} ({{ $shop['location'] ?? __('messages.na') }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="sort-wrap">
                    <span class="sort-label"><i class="bi bi-sort-down"></i> Sort</span>
                    <select id="sortSelect" class="sort-select">
                        <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Name (A–Z)</option>
                        <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Name (Z–A)</option>
                        <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price (Low–High)</option>
                        <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price (High–Low)</option>
                        <option value="stock_asc" {{ $sort === 'stock_asc' ? 'selected' : '' }}>Stock (Low–High)</option>
                        <option value="stock_desc" {{ $sort === 'stock_desc' ? 'selected' : '' }}>Stock (High–Low)</option>
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ── Table Panel ── --}}
        <div class="panel">
            <div class="panel-head">
                <h2 class="panel-title">
                    Product List
                </h2>
                <div class="panel-actions">
                    <button class="pbtn pbtn-outline" id="selectAllBtn" onclick="toggleSelectAll()">
                        <i class="bi bi-check2-square"></i> Select All
                    </button>
                    <button class="pbtn pbtn-danger" id="deleteAllBtn" disabled>
                        <i class="bi bi-trash"></i> Delete All
                    </button>
                    <button class="pbtn pbtn-primary" id="printBtn" disabled>
                        <i class="bi bi-printer"></i> Print Order
                    </button>
                    <button class="pbtn pbtn-primary" id="duplicateBtn" disabled>
                        <i class="bi bi-copy"></i> Duplicate
                    </button>
                    <button class="pbtn pbtn-primary" id="requestBtn">
                        <i class="bi bi-plus-lg"></i> Request
                    </button>
                    <button class="pbtn pbtn-warning" id="syncPricesBtn" disabled>
                        <i class="bi bi-arrow-repeat"></i> Sync Prices
                    </button>
                    <button class="pbtn pbtn-danger" id="clearQuantityBtn" disabled>
                        <i class="bi bi-dash-circle"></i> Clear Quantity
                    </button>
                    <button class="pbtn pbtn-primary" id="syncFromApiBtn">
                        <i class="bi bi-cloud-download"></i> Sync from MainStore
                    </button>
                </div>
            </div>

            <div class="table-wrap">
                <table class="prod-tbl" id="productsTable">
                    <thead>
                        <tr>
                            <th class="chk-cell">
                                <div class="custom-chk" id="masterCheckbox" onclick="toggleAllCheckboxes()"></div>
                            </th>
                            <th>Product</th>
                            <th>Stock</th>
                            <th>Cost Price</th>
                            <th>Selling Price</th>
                            <th>Discount</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        @if(empty($products) || $products->isEmpty())
                            <tr>
                                <td colspan="9" class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-box-seam"></i></div>
                                    <p>No products found</p>
                                </td>
                            </tr>
                        @else
                            @foreach($products as $product)
                                <tr data-product-id="{{ $product->product_id }}">
                                    <td class="chk-cell">
                                        <div class="custom-chk product-checkbox" onclick="toggleCheckbox(this, '{{ $product->product_id }}')"></div>
                                    </td>
                                    <td>
                                        <div class="prod-info">
                                            <div class="prod-avatar">{{ strtoupper(substr($product->name01, 0, 1)) }}</div>
                                            <div>
                                                <div class="prod-name">{{ $product->name01 }}</div>
                                                @if(!empty($product->name02))
                                                    <div class="prod-sub">{{ $product->name02 }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="stock-badge {{ $product->quantity == 0 ? 'low' : ($product->quantity < 10 ? 'medium' : 'high') }}">
                                            {{ $product->quantity }} {{ $product->unit ?? 'pcs' }}
                                        </span>
                                    </td>
                                    <td><span class="price cost">{{ number_format($product->bPrice ?? 0, 2) }}</span></td>
                                    <td><span class="price">{{ number_format($product->sPrice ?? 0, 2) }}</span></td>
                                    <td><span class="price">{{ number_format($product->discount ?? 0, 2) }}</span></td>
                                    <td><span class="cat-badge">{{ $product->category ?? '-' }}</span></td>
                                    <td>
                                        <div class="action-cell">
                                            <button class="action-icon-btn view" title="View" data-product-id="{{ $product->product_id }}"><i class="bi bi-eye"></i></button>
                                            <button class="action-icon-btn offers" title="Offers" data-product-id="{{ $product->product_id }}"><i class="bi bi-gift"></i></button>
                                            <button class="action-icon-btn delete" title="Delete" data-product-id="{{ $product->product_id }}"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="pagination-wrap" id="paginationContainer">
                <p class="pg-info">
                    Showing <strong>{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong> of <strong>{{ $products->total() }}</strong> products
                </p>

                <nav>
                    <ul class="pg-list">
                        <li class="pg-item {{ $products->currentPage() == 1 ? 'disabled' : '' }}">
                            <a class="pg-link" href="{{ $products->currentPage() > 1 ? $products->url($products->currentPage() - 1) : '#' }}"><i class="bi bi-chevron-left"></i> Previous</a>
                        </li>
                        @for ($i = 1; $i <= min($products->lastPage(), 3); $i++)
                            <li class="pg-item {{ $products->currentPage() == $i ? 'active' : '' }}">
                                <a class="pg-link {{ $products->currentPage() == $i ? 'pg-link-active' : '' }}" href="{{ $products->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        @if ($products->lastPage() > 3)
                            <li class="pg-item disabled"><span class="pg-link">…</span></li>
                            <li class="pg-item"><a class="pg-link" href="{{ $products->url($products->lastPage()) }}">{{ $products->lastPage() }}</a></li>
                        @endif
                        <li class="pg-item {{ $products->currentPage() == $products->lastPage() ? 'disabled' : '' }}">
                            <a class="pg-link" href="{{ $products->currentPage() < $products->lastPage() ? $products->url($products->currentPage() + 1) : '#' }}">Next <i class="bi bi-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
                
            </div>
        </div>{{-- /panel --}}
    </div>
</main>
    <!-- Modals (simplified for demo) -->
    <div class="modal fade" id="printModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header-navy">
                    <h5 class="modal-title">Print Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="printModalBody">
                    <p>Print preview will appear here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="mbtn mbtn-outline" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="mbtn mbtn-primary" onclick="printOrder()">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="duplicateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header-navy">
                    <h5 class="modal-title">Duplicate Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="field">
                        <label class="field-label">Select Destination Shop</label>
                        <select class="field-input">
                            <option>Main Store</option>
                            <option>Branch A</option>
                            <option>Branch B</option>
                        </select>
                    </div>
                    <div class="field">
                        <label class="field-label">Selected Products</label>
                        <div class="selected-list">
                            <div class="selected-item">
                                <span class="selected-item-name">Apple iPhone 15 Pro</span>
                                <span class="selected-item-badge">ID: PRD-001</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="mbtn mbtn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="mbtn mbtn-primary">
                        <i class="bi bi-copy"></i> Duplicate
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header-navy">
                    <h5 class="modal-title">Delete Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert-box">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Warning!</strong> This action cannot be undone.
                    </div>
                    <div class="field">
                        <label class="field-label">Selected Products</label>
                        <div class="selected-list">
                            <div class="selected-item">
                                <span class="selected-item-name">Apple iPhone 15 Pro</span>
                                <span class="selected-item-badge">ID: PRD-001</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="mbtn mbtn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="mbtn mbtn-danger">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple demo interactions
        function toggleCheckbox(el, id) {
            el.classList.toggle('checked');
            updateActionButtons();
        }

        function toggleAllCheckboxes() {
            const master = document.getElementById('masterCheckbox');
            const checkboxes = document.querySelectorAll('.product-checkbox');
            const willCheck = !master.classList.contains('checked');
            master.classList.toggle('checked');
            checkboxes.forEach(cb => {
                const row = cb.closest('tr');
                if (row && row.style.display !== 'none') {
                    willCheck ? cb.classList.add('checked') : cb.classList.remove('checked');
                }
            });
            updateActionButtons();
        }

        function toggleSelectAll() {
            toggleAllCheckboxes();
            const selectAllBtn = document.getElementById('selectAllBtn');
            const master = document.getElementById('masterCheckbox');
            selectAllBtn.innerHTML = master.classList.contains('checked')
                ? '<i class="bi bi-x-square"></i> Deselect All'
                : '<i class="bi bi-check2-square"></i> Select All';
        }

        function updateActionButtons() {
            const count = document.querySelectorAll('.product-checkbox.checked').length;
            document.getElementById('duplicateBtn').disabled = count === 0;
            document.getElementById('printBtn').disabled = count === 0;
            document.getElementById('deleteAllBtn').disabled = count === 0;
            document.getElementById('requestBtn').disabled = count === 0;
            document.getElementById('clearQuantityBtn').disabled = count === 0;
            document.getElementById('syncPricesBtn').disabled = count === 0;
        }

        // Live search demo
        const searchInput = document.getElementById('searchInput');
        const allRows = document.querySelectorAll('#productsTableBody tr[data-product-id]');
        const paginationContainer = document.getElementById('paginationContainer');

        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            if (!term) {
                allRows.forEach(row => row.style.display = '');
                if (paginationContainer) paginationContainer.style.display = '';
                return;
            }
            if (paginationContainer) paginationContainer.style.display = 'none';
            let matchCount = 0;
            allRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const matches = text.includes(term);
                row.style.display = matches ? '' : 'none';
                if (matches) matchCount++;
            });
        });

        // Sort / shop / per-page: apply choice and remember across reloads
        const sortSelect = document.getElementById('sortSelect');
        const shopSelect = document.getElementById('shopSelect');
        const perPageSelect = document.getElementById('perPageSelect');

        function navigateWithParam(param, value) {
            const url = new URL(window.location.href);
            url.searchParams.set(param, value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                navigateWithParam('sort', this.value);
            });
        }

        if (shopSelect) {
            shopSelect.addEventListener('change', function() {
                if (this.value) {
                    navigateWithParam('shop', this.value);
                }
            });
        }

        if (perPageSelect) {
            perPageSelect.addEventListener('change', function() {
                navigateWithParam('per_page', this.value);
            });
        }

        // Search: press Enter to send to server (keeps pagination/sort/shop intact)
        if (searchInput) {
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    navigateWithParam('search', this.value.trim());
                }
            });
        }

        // Placeholder functions
        function downloadReport() {
            alert('Export report functionality would be here.');
        }

        function printOrder() {
            window.print();
        }

        // Wire up row action buttons: View, Offers, Delete
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        function postForm(action, data) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = action;
            form.style.display = 'none';
            Object.entries(data).forEach(([key, val]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = val;
                form.appendChild(input);
            });
            document.body.appendChild(form);
            form.submit();
        }

        document.querySelectorAll('.action-icon-btn.view').forEach(btn => {
            btn.addEventListener('click', function () {
                const productId = this.getAttribute('data-product-id');
                postForm('/viewProduct', { product_id: productId, _token: csrfToken });
            });
        });

        document.querySelectorAll('.action-icon-btn.offers').forEach(btn => {
            btn.addEventListener('click', function () {
                const productId = this.getAttribute('data-product-id');
                window.location.href = '/getOffers/' + productId;
            });
        });

        document.querySelectorAll('.action-icon-btn.delete').forEach(btn => {
            btn.addEventListener('click', function () {
                const productId = this.getAttribute('data-product-id');
                if (!confirm('Are you sure you want to delete this product?')) return;
                postForm('/dltProduct', { product_id: productId, _token: csrfToken });
            });
        });
    </script>
</body>
</html>