<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{config("app.name")}} - Product Management</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --navy:          #0B1E3D;
            --navy-mid:      #112952;
            --navy-light:    #1A3A6B;
            --amber:         #F59E0B;
            --amber-pale:    #FEF3C7;
            --emerald:       #059669;
            --emerald-pale:  #D1FAE5;
            --rose:          #E11D48;
            --rose-pale:     #FFE4E6;
            --violet:        #7C3AED;
            --violet-pale:   #EDE9FE;
            --sky:           #0284C7;
            --sky-pale:      #E0F2FE;
            --fuchsia:       #C026D3;
            --fuchsia-pale:  #FAE8FF;
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

        /* ── Page header ── */
        .pg-header {
            background: var(--navy);
            border-radius: 12px;
            padding: 1rem 1.4rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.28);
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
        }

        .pg-title-wrap { display: flex; flex-direction: column; gap: 0.15rem; }
        .pg-title {
            color: var(--white); font-size: 1.35rem; font-weight: 700;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .pg-title span { color: var(--amber); }
        .pg-subtitle { color: rgba(255,255,255,0.6); font-size: 0.84rem; }

        .hbtn-export {
            display: inline-flex; align-items: center; gap: 0.45rem;
            font-size: 0.875rem; font-weight: 600;
            padding: 0.5rem 1.1rem;
            background: var(--amber); color: var(--navy);
            border: none; border-radius: 8px; cursor: pointer;
            box-shadow: 0 3px 12px rgba(245,158,11,0.3);
            transition: all 0.18s; text-decoration: none;
        }
        .hbtn-export:hover {
            box-shadow: 0 5px 18px rgba(245,158,11,0.4);
            transform: translateY(-1px);
            color: var(--navy);
        }

        /* ── Stats grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .stat-card {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 1.2rem 1.3rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0;
            height: 3px; background: var(--stat-color);
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(11,30,61,0.12);
            border-color: var(--stat-color);
        }

        .stat-card.navy    { --stat-color: var(--navy); }
        .stat-card.emerald { --stat-color: var(--emerald); }
        .stat-card.sky     { --stat-color: var(--sky); }

        .stat-row {
            display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;
        }

        .stat-info { flex: 1; }
        .stat-label {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--slate-400); margin-bottom: 0.4rem;
        }
        .stat-value {
            font-family: 'DM Mono', monospace;
            font-size: 1.75rem; font-weight: 500;
            color: var(--navy); line-height: 1.1; letter-spacing: -0.5px;
        }
        .stat-trend {
            display: inline-flex; align-items: center; gap: 0.3rem;
            font-size: 0.72rem; font-weight: 600;
            padding: 0.25rem 0.6rem; border-radius: 20px;
            margin-top: 0.35rem;
        }
        .stat-trend.pos { background: var(--emerald-pale); color: #065F46; }

        .stat-icon {
            width: 48px; height: 48px;
            background: var(--slate-50);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; color: var(--stat-color);
            flex-shrink: 0;
        }

        /* ── Search panel ── */
        .search-panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
        }

        .search-row { display: flex; gap: 0.85rem; flex-wrap: wrap; align-items: center; }
        .search-box { flex: 1; min-width: 280px; position: relative; }

        .search-icon {
            position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%);
            color: var(--slate-400); font-size: 0.95rem; pointer-events: none;
        }

        .search-input {
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            width: 100%;
            padding: 0.6rem 0.75rem 0.6rem 2.4rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 9px;
            background: var(--slate-50);
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
        }
        .search-input:focus {
            border-color: var(--navy-light);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }
        .search-input::placeholder { color: var(--slate-400); }

        .search-hint {
            font-size: 0.74rem; color: var(--slate-400);
            margin-top: 0.35rem; padding-left: 2.4rem;
            display: none;
        }
        .search-hint.visible { display: block; }

        .search-active-banner {
            background: var(--sky-pale);
            border: 1.5px solid var(--sky);
            border-radius: 8px;
            padding: 0.5rem 0.85rem;
            font-size: 0.82rem; color: #075985;
            display: flex; align-items: center; gap: 0.5rem;
            margin-top: 0.65rem;
        }
        .search-active-banner a {
            color: var(--rose); font-weight: 600; text-decoration: none; margin-left: auto;
        }
        .search-active-banner a:hover { text-decoration: underline; }

        .sort-wrap { display: flex; align-items: center; gap: 0.4rem; }
        .sort-label { font-size: 0.78rem; font-weight: 600; color: var(--slate-500); white-space: nowrap; }
        .sort-select {
            font-family: 'Outfit', sans-serif;
            font-size: 0.82rem;
            padding: 0.45rem 2rem 0.45rem 0.7rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            cursor: pointer;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.6rem center;
        }
        .sort-select:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        .action-row { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        .abtn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.82rem; font-weight: 600;
            padding: 0.48rem 0.9rem;
            border-radius: 7px;
            border: 1.5px solid;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            white-space: nowrap;
        }
        .abtn-outline { background: var(--white); border-color: var(--slate-200); color: var(--slate-700); }
        .abtn-outline:hover { background: var(--slate-50); border-color: var(--navy-light); color: var(--navy); }
        .abtn-primary { background: var(--amber); border-color: var(--amber); color: var(--navy); }
        .abtn-primary:hover { background: #FBBF24; transform: translateY(-1px); color: var(--navy); }

        /* ── Table panel ── */
        .panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            overflow: hidden;
        }

        .panel-head {
            background: var(--slate-50);
            border-bottom: 1.5px solid var(--slate-200);
            padding: 1.1rem 1.25rem;
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;
        }
        .panel-title {
            font-size: 1.05rem; font-weight: 700; color: var(--navy);
            letter-spacing: -0.3px; margin: 0;
        }
        .panel-title small { font-weight: 400; color: var(--slate-400); font-size: 0.82rem; }

        .panel-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        .pbtn {
            display: inline-flex; align-items: center; gap: 0.35rem;
            font-size: 0.8rem; font-weight: 600;
            padding: 0.4rem 0.8rem;
            border-radius: 7px; border: 1.5px solid;
            cursor: pointer; transition: all 0.15s;
            background: var(--white);
        }
        .pbtn-outline { border-color: var(--slate-200); color: var(--slate-700); }
        .pbtn-outline:hover { background: var(--slate-50); border-color: var(--navy-light); color: var(--navy); }
        .pbtn-danger  { border-color: var(--rose); color: var(--rose); background: transparent; }
        .pbtn-danger:hover { background: var(--rose); color: var(--white); }
        .pbtn-primary { border-color: var(--amber); color: var(--navy); background: var(--amber); }
        .pbtn-primary:hover { background: #FBBF24; }
        .pbtn:disabled {
            opacity: 0.4; cursor: not-allowed; pointer-events: none;
            transform: none !important;
        }

        /* ── Live search bar ── */
        .live-search-bar {
            display: none;
            padding: 0.6rem 1.25rem;
            background: var(--sky-pale);
            border-bottom: 1.5px solid var(--slate-200);
            font-size: 0.82rem; color: #075985;
            align-items: center; gap: 0.4rem;
        }
        .live-search-bar strong { color: var(--sky); }
        .live-search-bar .link {
            cursor: pointer; color: var(--navy); text-decoration: underline;
        }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }

        table.prod-tbl { width: 100%; border-collapse: collapse; font-size: 0.845rem; }
        table.prod-tbl thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            padding: 0.65rem 0.8rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
        }
        table.prod-tbl tbody td {
            padding: 0.7rem 0.8rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
            color: var(--slate-800);
        }
        table.prod-tbl tbody tr:hover td { background: #F8FAFF; }

        .chk-cell { width: 36px; padding-right: 0; }
        .custom-chk {
            width: 18px; height: 18px;
            border: 1.5px solid var(--slate-300);
            border-radius: 5px;
            cursor: pointer;
            position: relative;
            background: var(--white);
            display: inline-block;
        }
        .custom-chk.checked {
            background: var(--navy);
            border-color: var(--navy);
        }
        .custom-chk.checked::after {
            content: '✓';
            position: absolute;
            top: 50%; left: 50%; transform: translate(-50%, -50%);
            color: var(--white);
            font-size: 11px;
            font-weight: 700;
        }

        .prod-info { display: flex; align-items: center; gap: 0.75rem; }
        .prod-avatar {
            width: 42px; height: 42px;
            background: var(--slate-100);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            color: var(--navy);
            font-size: 0.9rem; font-weight: 700;
            flex-shrink: 0;
        }
        .prod-name {
            font-weight: 600; color: var(--navy);
            margin-bottom: 0.15rem;
        }
        .prod-sub { font-size: 0.78rem; color: var(--slate-500); }

        .offer-tag {
            display: inline-flex; align-items: center; gap: 0.25rem;
            font-size: 0.7rem; font-weight: 700;
            padding: 0.2rem 0.5rem;
            background: var(--fuchsia); color: var(--white);
            border-radius: 5px;
            margin-left: 0.4rem;
        }

        .stock-badge {
            display: inline-flex; align-items: center;
            font-size: 0.78rem; font-weight: 600;
            padding: 0.3rem 0.65rem; border-radius: 20px;
        }
        .stock-badge.low    { background: var(--rose-pale);    color: #9F1239; }
        .stock-badge.medium { background: var(--amber-pale);   color: #92400E; }
        .stock-badge.high   { background: var(--emerald-pale); color: #065F46; }

        .price { font-family: 'DM Mono', monospace; font-weight: 500; color: var(--slate-800); font-size: 0.82rem; }
        .price.cost { color: var(--rose); }

        .cat-badge {
            display: inline-block;
            font-size: 0.78rem; font-weight: 600;
            padding: 0.28rem 0.65rem;
            background: var(--slate-100);
            color: var(--slate-600);
            border-radius: 6px;
        }

        .action-cell { display: flex; gap: 0.35rem; }
        .action-icon-btn {
            width: 30px; height: 30px;
            border: 1.5px solid;
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all 0.15s;
            background: transparent;
        }
        .action-icon-btn.view   { border-color: var(--sky);    color: var(--sky); }
        .action-icon-btn.delete { border-color: var(--rose);   color: var(--rose); }
        .action-icon-btn.restock { border-color: var(--emerald); color: var(--emerald); }
        .action-icon-btn.return  { border-color: var(--amber);  color: var(--amber); }
        .action-icon-btn.offers  { border-color: var(--fuchsia); color: var(--fuchsia); }

        .action-icon-btn:hover { transform: scale(1.08); }
        .action-icon-btn.view:hover    { background: var(--sky-pale); }
        .action-icon-btn.delete:hover  { background: var(--rose-pale); }
        .action-icon-btn.restock:hover { background: var(--emerald-pale); }
        .action-icon-btn.return:hover  { background: var(--amber-pale); }
        .action-icon-btn.offers:hover  { background: var(--fuchsia-pale); }

        /* ── Empty state ── */
        .empty-state {
            text-align: center; padding: 4rem 1.5rem;
            color: var(--slate-400);
        }
        .empty-state i { font-size: 4rem; display: block; margin-bottom: 0.75rem; opacity: 0.3; }
        .empty-state-title { font-size: 1.1rem; font-weight: 600; color: var(--slate-600); margin-bottom: 0.4rem; }
        .empty-state p { font-size: 0.875rem; margin-bottom: 1.25rem; max-width: 420px; margin-left: auto; margin-right: auto; }

        /* ── Pagination ── */
        .pagination-wrap {
            padding: 1.15rem 1.25rem;
            border-top: 1.5px solid var(--slate-200);
            background: var(--white);
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
        }

        .pg-info { font-size: 0.82rem; color: var(--slate-500); font-weight: 500; }
        .pg-info strong { color: var(--navy); }

        .pg-list {
            display: flex; list-style: none; padding: 0; margin: 0; gap: 0.4rem; flex-wrap: wrap;
        }
        .pg-item { display: flex; }
        .pg-item.disabled .pg-link { opacity: 0.4; cursor: not-allowed; pointer-events: none; }

        .pg-link {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem;
            padding: 0.45rem 0.85rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            color: var(--slate-700);
            text-decoration: none;
            font-weight: 500; font-size: 0.82rem;
            transition: all 0.15s;
            background: var(--white);
            min-width: 36px;
            text-align: center;
        }
        .pg-link:hover:not(.pg-link-active) {
            border-color: var(--navy-light);
            color: var(--navy);
            background: var(--slate-50);
            transform: translateY(-1px);
        }
        .pg-link-active {
            background: var(--navy);
            color: var(--white);
            border-color: var(--navy);
            cursor: default;
        }

        .per-page { display: flex; align-items: center; gap: 0.5rem; }
        .per-page-label { font-size: 0.82rem; color: var(--slate-500); font-weight: 500; white-space: nowrap; }
        .per-page-select {
            font-family: 'Outfit', sans-serif;
            font-size: 0.82rem;
            padding: 0.45rem 2rem 0.45rem 0.7rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            cursor: pointer;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.6rem center;
        }
        .per-page-select:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        /* ── Modal ── */
        .modal-content { border: none; border-radius: 12px; overflow: hidden; }
        .modal-header-navy {
            background: var(--navy);
            color: var(--white);
            padding: 1.15rem 1.4rem;
            border-bottom: none;
        }
        .modal-header-navy .modal-title { font-size: 1.1rem; font-weight: 700; margin: 0; }
        .modal-header-navy .btn-close { filter: invert(1) brightness(0.8); }

        .modal-body { padding: 1.75rem 1.4rem; }
        .modal-footer { padding: 1.15rem 1.4rem; border-top: 1.5px solid var(--slate-200); }

        .field { display: flex; flex-direction: column; gap: 0.25rem; margin-bottom: 0.85rem; }
        .field:last-child { margin-bottom: 0; }
        .field-label { font-size: 0.8rem; font-weight: 600; color: var(--slate-600); }
        .field-input {
            font-family: 'Outfit', sans-serif;
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .field-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }
        select.field-input {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            padding-right: 2.25rem;
            appearance: none;
        }

        .row-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; }
        @media (max-width: 640px) { .row-fields { grid-template-columns: 1fr; } }

        .selected-list { max-height: 180px; overflow-y: auto; }
        .selected-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0.5rem 0.7rem;
            background: var(--slate-50);
            border: 1px solid var(--slate-200);
            border-radius: 7px;
            font-size: 0.82rem;
            margin-bottom: 0.4rem;
        }
        .selected-item-name { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .selected-item-badge {
            font-size: 0.72rem; font-weight: 600;
            padding: 0.2rem 0.5rem; border-radius: 5px;
            background: var(--slate-200); color: var(--slate-600);
        }

        .alert-box {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.75rem 0.9rem;
            background: var(--rose-pale);
            border-left: 3px solid var(--rose);
            border-radius: 8px;
            font-size: 0.85rem; color: #9F1239;
            margin-bottom: 1rem;
        }

        .mbtn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.45rem;
            font-size: 0.875rem; font-weight: 600;
            padding: 0.55rem 1.1rem;
            border-radius: 8px; border: none; cursor: pointer;
            transition: all 0.15s;
        }
        .mbtn-outline { background: var(--slate-100); color: var(--slate-700); border: 1.5px solid var(--slate-200); }
        .mbtn-outline:hover { background: var(--slate-200); }
        .mbtn-danger  { background: var(--rose); color: var(--white); }
        .mbtn-danger:hover { background: #BE123C; transform: translateY(-1px); }
        .mbtn-primary { background: var(--amber); color: var(--navy); }
        .mbtn-primary:hover { background: #FBBF24; transform: translateY(-1px); }
        .mbtn:disabled { opacity: 0.4; cursor: not-allowed; transform: none !important; }

        /* ── Print order ── */
        .print-order { background: var(--white); padding: 2rem; border-radius: 12px; }
        .order-hd { text-align: center; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 3px solid var(--navy); }
        .order-title { font-size: 1.75rem; font-weight: 700; color: var(--navy); margin-bottom: 0.5rem; }
        .order-sub { color: var(--slate-500); font-size: 0.95rem; }
        .order-date { color: var(--slate-400); font-size: 0.875rem; margin-top: 0.5rem; }

        .order-tbl { width: 100%; margin-bottom: 2rem; border-collapse: collapse; }
        .order-tbl thead { background: var(--slate-100); }
        .order-tbl th {
            padding: 0.75rem 1rem; text-align: left; font-weight: 600; color: var(--navy);
            border: 1px solid var(--slate-200);
            font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em;
        }
        .order-tbl td { padding: 1rem; border: 1px solid var(--slate-200); font-size: 0.95rem; }
        .order-tbl tbody tr:nth-child(even) { background: var(--slate-50); }

        .prod-disp { display: flex; flex-direction: column; }
        .prod-disp-name { font-weight: 600; color: var(--navy); margin-bottom: 0.25rem; }
        .prod-disp-sub { font-size: 0.8rem; color: var(--slate-500); }

        .order-sum { margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid var(--slate-200); }
        .sum-row { display: flex; justify-content: space-between; padding: 0.75rem 0; font-size: 0.95rem; }
        .sum-row.total {
            padding: 1rem 0; margin-top: 1rem;
            border-top: 2px solid var(--navy);
            font-weight: 700; font-size: 1.1rem; color: var(--navy);
        }

        @media print {
            .mbtn, .modal-footer, .modal-header-navy { display: none; }
            .print-order { box-shadow: none; background: var(--white); }
        }

        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
            .pg-header { padding: 0.85rem 1.1rem; margin-bottom: 1rem; }
            .pg-title { font-size: 1.15rem; }
            .stats-grid { grid-template-columns: 1fr; gap: 0.75rem; }
            .stat-value { font-size: 1.4rem; }
            .panel-head { flex-direction: column; align-items: flex-start; }
            .panel-actions { width: 100%; }
            .search-row { flex-direction: column; align-items: stretch; }
            .action-row { width: 100%; }
            .abtn { flex: 1; justify-content: center; }
            .pagination-wrap { flex-direction: column; align-items: flex-start; }
        }

        /* ── Animation ── */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .stat-card { animation: slideUp 0.4s ease forwards; }
        .stat-card:nth-child(2) { animation-delay: 0.08s; }
        .stat-card:nth-child(3) { animation-delay: 0.16s; }
    </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
        <div class="main-wrap">

            {{-- ── Header ── --}}
            <div class="pg-header">
                <div class="pg-title-wrap">
                    <div class="pg-title">
                        Product <span>Inventory</span>
                    </div>
                    <div class="pg-subtitle">Manage your products and stock levels</div>
                </div>
                <button class="hbtn-export" onclick="downloadReport()">
                    <i class="bi bi-file-earmark-text"></i> Export Report
                </button>
            </div>

            {{-- ── Stats ── --}}
            <div class="stats-grid">
                <div class="stat-card navy">
                    <div class="stat-row">
                        <div class="stat-info">
                            <div class="stat-label">Total Products</div>
                            <div class="stat-value">{{ number_format($TProducts) }}</div>
                            <div class="stat-trend pos"><i class="bi bi-arrow-up"></i> All active</div>
                        </div>
                        <div class="stat-icon"><i class="bi bi-box-seam-fill"></i></div>
                    </div>
                </div>

                <div class="stat-card emerald">
                    <div class="stat-row">
                        <div class="stat-info">
                            <div class="stat-label">Inventory Worth (Cost)</div>
                            <div class="stat-value">Tsh {{ number_format($totalCostWorth) }}</div>
                            <div class="stat-trend pos"><i class="bi bi-cash-stack"></i> Cost Basis</div>
                        </div>
                        <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
                    </div>
                </div>

                <div class="stat-card sky">
                    <div class="stat-row">
                        <div class="stat-info">
                            <div class="stat-label">Inventory Worth (Selling)</div>
                            <div class="stat-value">Tsh {{ number_format($totalSellingWorth) }}</div>
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
                            placeholder="Search products by name, category, or ID…"
                            value="{{ request('search') }}" autocomplete="off">
                        <div class="search-hint" id="searchHint">
                            Press <strong>Enter</strong> to search all pages · Type to filter this page
                        </div>

                        @if(request('search'))
                        <div class="search-active-banner">
                            <i class="bi bi-funnel-fill"></i>
                            Showing all results for: <strong>"{{ request('search') }}"</strong>
                            ({{ $products->total() }} found)
                            <a href="{{ url()->current() }}">✕ Clear search</a>
                        </div>
                        @endif
                    </div>

                    <div class="sort-wrap">
                        <span class="sort-label"><i class="bi bi-shop"></i> Shop:</span>
                        <select id="shopSelect" class="sort-select" onchange="changeShop()">
                            @foreach($getAllAccounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ request('shop') == $account->id ? 'selected' : '' }}
                                    {{ !request('shop') && getSessionAccountId() == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sort-wrap">
                        <span class="sort-label"><i class="bi bi-sort-down"></i> Sort:</span>
                        <select id="sortSelect" class="sort-select" onchange="changeSort()">
                            <option value="name_asc" {{ request('sort', 'name_asc') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low-High)</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High-Low)</option>
                            <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stock (Low-High)</option>
                            <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stock (High-Low)</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        </select>
                    </div>

                    <div class="action-row">
                        <a class="abtn abtn-outline" href="itemRequest">
                            <i class="bi bi-plus-circle"></i> Item Request
                        </a>
                        <a class="abtn abtn-outline" href="viewRequest">
                            <i class="bi bi-list-check"></i> View Requests
                        </a>
                        <button class="abtn abtn-primary" onclick="openOfferModal()">
                            <i class="bi bi-gift"></i> Offers
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── Table Panel ── --}}
            <div class="panel">
                <div class="panel-head">
                    <h2 class="panel-title">
                        Product List @php
                            echo session('account_id');
                        @endphp
                        @if(request('search'))
                            <small>— search results</small>
                        @endif
                    </h2>
                    <div class="panel-actions">
                        <button class="pbtn pbtn-outline" id="selectAllBtn" onclick="toggleSelectAll()">
                            <i class="bi bi-check2-square"></i> Select All
                        </button>
                        <button class="pbtn pbtn-danger" id="deleteAllBtn" onclick="openDeleteModal()" disabled>
                            <i class="bi bi-trash"></i> Delete All
                        </button>
                        <button class="pbtn pbtn-primary" id="printBtn" onclick="openPrintModal()" disabled>
                            <i class="bi bi-printer"></i> Print Order
                        </button>
                        @if (getSessionAccountDisplayName() == 'Main Store')
                        <button class="pbtn pbtn-primary" id="duplicateBtn" onclick="openDuplicateModal()" disabled>
                            <i class="bi bi-copy"></i> Duplicate
                        </button>
                        @endif
                        <button class="pbtn pbtn-primary" onclick="openOfferModal()">
                            <i class="bi bi-gift"></i> Offers
                        </button>
                    </div>
                </div>

                <div class="live-search-bar" id="liveSearchBar">
                    <i class="bi bi-funnel"></i>
                    Showing <strong id="liveMatchCount">0</strong> of <strong>{{ $products->count() }}</strong> products on this page
                    &nbsp;·&nbsp; <span class="link" onclick="submitSearch()">Press Enter to search all pages</span>
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
                            @if($products->isEmpty())
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="bi bi-box"></i>
                                        <div class="empty-state-title">No Products Found</div>
                                        <p>
                                            @if(request('search'))
                                                No products matched your search "{{ request('search') }}".
                                            @else
                                                Add your first product to start managing your inventory.
                                            @endif
                                        </p>
                                        @if(request('search'))
                                            <a href="{{ url()->current() }}" class="abtn abtn-outline">
                                                <i class="bi bi-x-lg"></i> Clear Search
                                            </a>
                                        @else
                                            <a href="itemRequest" class="abtn abtn-primary">
                                                <i class="bi bi-plus-lg"></i> Add New Product
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @else
                                @foreach ($products as $product)
                                <form method="post">
                                    @csrf
                                    <tr data-product-id="{{ $product->product_id }}"
                                        data-searchable="{{ strtolower($product->name01 . ' ' . $product->name02 . ' ' . $product->category . ' ' . $product->product_id) }}">
                                        <td class="chk-cell">
                                            <div class="custom-chk product-checkbox"
                                                 onclick="toggleCheckbox(this, '{{ $product->product_id }}')"></div>
                                        </td>
                                        <td>
                                            <div class="prod-info">
                                                <div class="prod-avatar">{{ strtoupper(substr($product->name01, 0, 2)) }}</div>
                                                <div>
                                                    <div class="prod-name">
                                                        {{ $product->name01 }}
                                                        @if(in_array($product->product_id, $offers ?? []))
                                                        <span class="offer-tag" title="Active offer">
                                                            <i class="bi bi-gift-fill"></i> Offer
                                                        </span>
                                                        @endif
                                                    </div>
                                                    <div class="prod-sub">{{ $product->name02 }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $stockClass = $product->quantity <= 0 ? 'low'
                                                            : ($product->quantity < 10 ? 'medium' : 'high');
                                            @endphp
                                            <span class="stock-badge {{ $stockClass }}">
                                                {{ number_format($product->quantity) }} {{ $product->unit }}
                                            </span>
                                        </td>
                                        <td><span class="price cost">Tsh {{ number_format($product->bPrice) }}</span></td>
                                        <td><span class="price">Tsh {{ number_format($product->sPrice) }}</span></td>
                                        <td><span class="price">{{ number_format($product->discount) }}</span></td>
                                        <td><span class="cat-badge">{{ $product->category }}</span></td>
                                        <td>
                                            <div class="action-cell">
                                                @if ($product->stock2 > 0)
                                                <button class="action-icon-btn restock" name="product_id"
                                                    formaction="restockProd" value="{{ $product->product_id }}" title="Restock">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                                @endif

                                                @if (getSessionAccountDisplayName() != 'Main Store')
                                                <button class="action-icon-btn return" name="product_id"
                                                    formaction="returnToMainStore" value="{{ $product->product_id }}"
                                                    title="Return to Main Store"
                                                    onclick="return confirm('Return this item to Main Store?')">
                                                    <i class="bi bi-arrow-return-left"></i>
                                                </button>
                                                @endif

                                                <button class="action-icon-btn view" name="product_id"
                                                    formaction="viewProduct" value="{{ $product->product_id }}" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                
                                                <button type="button" class="action-icon-btn offers"
                                                    data-product-id="{{ $product->product_id }}"
                                                    data-product-name="{{ $product->name01 }}"
                                                    title="Manage Offers"
                                                    onclick="openOfferModal('{{ $product->product_id }}', '{{ addslashes($product->name01) }}')">
                                                    <i class="bi bi-gift"></i>
                                                </button>
                                                
                                                <button class="action-icon-btn delete" name="product_id"
                                                    formaction="dltProduct" value="{{ $product->product_id }}" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(!$products->isEmpty())
                <div class="pagination-wrap" id="paginationContainer"
                     style="{{ request('search') ? 'display:none' : '' }}">
                    <p class="pg-info">
                        Showing <strong>{{ $products->firstItem() }}</strong>
                        to <strong>{{ $products->lastItem() }}</strong>
                        of <strong>{{ $products->total() }}</strong> products
                    </p>

                    <nav>
                        <ul class="pg-list">
                            @if ($products->onFirstPage())
                                <li class="pg-item disabled">
                                    <span class="pg-link"><i class="bi bi-chevron-left"></i> Previous</span>
                                </li>
                            @else
                                <li class="pg-item">
                                    <a class="pg-link" href="{{ $products->previousPageUrl() }}">
                                        <i class="bi bi-chevron-left"></i> Previous
                                    </a>
                                </li>
                            @endif

                            @php
                                $currentPage = $products->currentPage();
                                $lastPage    = $products->lastPage();
                                $start       = max(1, $currentPage - 2);
                                $end         = min($lastPage, $currentPage + 2);
                            @endphp

                            @if($start > 1)
                                <li class="pg-item"><a class="pg-link" href="{{ $products->url(1) }}">1</a></li>
                                @if($start > 2)
                                    <li class="pg-item disabled"><span class="pg-link">…</span></li>
                                @endif
                            @endif

                            @for($page = $start; $page <= $end; $page++)
                                @if($page == $currentPage)
                                    <li class="pg-item active"><span class="pg-link pg-link-active">{{ $page }}</span></li>
                                @else
                                    <li class="pg-item"><a class="pg-link" href="{{ $products->url($page) }}">{{ $page }}</a></li>
                                @endif
                            @endfor

                            @if($end < $lastPage)
                                @if($end < $lastPage - 1)
                                    <li class="pg-item disabled"><span class="pg-link">…</span></li>
                                @endif
                                <li class="pg-item"><a class="pg-link" href="{{ $products->url($lastPage) }}">{{ $lastPage }}</a></li>
                            @endif

                            @if ($products->hasMorePages())
                                <li class="pg-item">
                                    <a class="pg-link" href="{{ $products->nextPageUrl() }}">
                                        Next <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="pg-item disabled">
                                    <span class="pg-link">Next <i class="bi bi-chevron-right"></i></span>
                                </li>
                            @endif
                        </ul>
                    </nav>

                    <div class="per-page">
                        <span class="per-page-label">Items per page:</span>
                        <select id="perPageSelect" class="per-page-select" onchange="changeItemsPerPage()">
                            @foreach([50, 100, 300, 500, 1000] as $pp)
                            <option value="{{ $pp }}" {{ request('per_page', 50) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

            </div>{{-- /panel --}}
        </div>
    </main>
  </div>
</div>

{{-- ════════════════════════════════════════════
     MODALS (simplified versions for token count)
════════════════════════════════════════════ --}}

<!-- Print Modal -->
<div class="modal fade" id="printModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header-navy">
                <h5 class="modal-title">Print Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="printModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="mbtn mbtn-outline" data-bs-dismiss="modal">Close</button>
                <button type="button" class="mbtn mbtn-primary" onclick="printOrder()">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Duplicate Modal -->
<div class="modal fade" id="duplicateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header-navy">
                <h5 class="modal-title">Duplicate Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="duplicateForm" action="{{ route('admin.duplicateProducts') }}" method="POST">
                    @csrf
                    <div class="field">
                        <label class="field-label">Select Destination Shop</label>
                        <select class="field-input" id="targetAccount" name="target_account" required>
                            <option value="">Choose a shop...</option>
                            @foreach($getAllAccounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="field">
                        <label class="field-label">Duplication Options</label>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.875rem;">
                                <input type="checkbox" id="includeStock" name="include_stock" checked style="width: 18px; height: 18px; accent-color: var(--navy);">
                                <span>Include stock quantities</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.875rem;">
                                <input type="checkbox" id="includePricing" name="include_pricing" checked style="width: 18px; height: 18px; accent-color: var(--navy);">
                                <span>Include pricing information (cost & selling price)</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="field-label">Selected Products</label>
                        <div class="selected-list selected-products-container"></div>
                    </div>
                    <div id="hiddenInputs"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="mbtn mbtn-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="mbtn mbtn-primary" id="confirmDuplicateBtn" onclick="duplicateProducts()">
                    <span id="duplicateBtnText"><i class="bi bi-copy"></i> Duplicate Products</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
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
                <form id="deleteForm" action="dltProduct" method="POST">
                    @csrf
                    <div class="field">
                        <label class="field-label">Selected Products to Delete</label>
                        <div class="selected-list selected-products-container-delete"></div>
                    </div>
                    <div id="deleteHiddenInputs"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="mbtn mbtn-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="mbtn mbtn-danger" id="confirmDeleteBtn" onclick="deleteSelectedProducts()">
                    <span id="deleteBtnText"><i class="bi bi-trash"></i> Delete Products</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Offer Modal - FIXED VERSION with working search -->
<div class="modal fade" id="offerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header-navy">
                <h5 class="modal-title">Manage Offers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Product Info -->
                <div id="offerProductInfo" style="margin-bottom: 1.5rem; padding: 1rem; background: var(--slate-50); border-radius: 8px; border: 1px solid var(--slate-200);">
                    <div style="font-size: 0.85rem; color: var(--slate-500); margin-bottom: 0.25rem;">Selected Product</div>
                    <div id="offerProductName" style="font-size: 1.1rem; font-weight: 700; color: var(--navy);">—</div>
                    <div id="offerProductId" style="font-size: 0.8rem; color: var(--slate-400); margin-top: 0.25rem;">—</div>
                </div>

                <!-- Existing Offers List -->
                <div id="existingOffersSection" style="margin-bottom: 1.5rem; display: none;">
                    <h6 style="font-size: 0.9rem; font-weight: 700; color: var(--slate-700); margin-bottom: 0.75rem;">Active Offers for This Product</h6>
                    <div id="offersList" class="selected-list" style="max-height: 200px; overflow-y: auto;"></div>
                </div>

                <!-- Create/Edit Offer Form -->
                <form id="offerForm" method="POST" action="{{ route('admin.saveOffer') }}">
                    @csrf
                    <input type="hidden" name="product_id" id="offerProductIdInput">
                    
                    <div class="row-fields">
                        <div class="field">
                            <label class="field-label">Required Quantity (Buy)</label>
                            <input type="number" class="field-input" name="required_quantity" id="requiredQuantity"
                                   min="1" value="1" required style="width: 100%;">
                        </div>
                        <div class="field">
                            <label class="field-label">Offer Quantity (Get Free)</label>
                            <input type="number" class="field-input" name="offer_quantity" id="offerQuantity"
                                   min="1" value="1" required style="width: 100%;">
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">Offer Applies To Product</label>
                        <!-- FIXED: Using native datalist for searchable dropdown -->
                        <input type="text" class="field-input" id="offerProductSearchInput" 
                               list="productOptionsList" placeholder="Type to search product name or ID..."
                               autocomplete="off" style="width: 100%;">
                        <datalist id="productOptionsList"></datalist>
                        <input type="hidden" name="offer_product_id" id="offerProductIdHidden">
                        <small style="font-size: 0.75rem; color: var(--slate-400); margin-top: 0.25rem; display: block;">
                            Start typing to search all products. Select the product that will be given free when the required quantity is purchased
                        </small>
                    </div>

                    <div class="field">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.875rem;">
                            <input type="checkbox" name="is_active" id="isActive" checked style="width: 18px; height: 18px; accent-color: var(--navy);">
                            <span>Offer is active (customers will see this offer)</span>
                        </label>
                    </div>

                    <div id="offerFormMessage" style="margin-top: 1rem;"></div>

                    <div class="modal-footer" style="border-top: 1.5px solid var(--slate-200); margin-top: 1.5rem;">
                        <button type="button" class="mbtn mbtn-outline" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="mbtn mbtn-primary" id="saveOfferBtn">
                            <i class="bi bi-check-lg"></i> Save Offer
                        </button>
                    </div>
                </form>

                <!-- Delete Offer Button -->
                <div id="deleteOfferSection" style="display: none; margin-top: 1rem; padding-top: 1rem; border-top: 1.5px solid var(--slate-200);">
                    <button type="button" class="mbtn mbtn-danger" id="deleteOfferBtn" style="width: 100%;">
                        <i class="bi bi-trash"></i> Delete This Offer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// [JavaScript - FULL WORKING VERSION with searchable product selector]

function changeItemsPerPage() {
    const perPage = document.getElementById('perPageSelect').value;
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page');
    url.searchParams.delete('search');
    window.location.href = url.toString();
}

function changeSort() {
    const sort = document.getElementById('sortSelect').value;
    const url = new URL(window.location.href);
    url.searchParams.set('sort', sort);
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

function changeShop() {
    const shop = document.getElementById('shopSelect').value;
    const url = new URL(window.location.href);
    if (shop) {
        url.searchParams.set('shop', shop);
    } else {
        url.searchParams.delete('shop');
    }
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

const searchInput = document.getElementById('searchInput');
const searchHint = document.getElementById('searchHint');
const liveSearchBar = document.getElementById('liveSearchBar');
const liveMatchCount = document.getElementById('liveMatchCount');
const paginationContainer = document.getElementById('paginationContainer');
const allRows = document.querySelectorAll('#productsTableBody tr[data-product-id]');

function debounce(fn, delay) {
    let timer;
    return function(...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), delay);
    };
}

const doLiveSearch = debounce(function(term) {
    if (term === '') {
        allRows.forEach(row => row.style.display = '');
        if (paginationContainer) paginationContainer.style.display = '';
        liveSearchBar.style.display = 'none';
        searchHint.classList.remove('visible');
        const noRes = document.getElementById('noResultsRow');
        if (noRes) noRes.remove();
        return;
    }

    if (paginationContainer) paginationContainer.style.display = 'none';
    liveSearchBar.style.display = 'flex';
    searchHint.classList.add('visible');

    let matchCount = 0;
    allRows.forEach(row => {
        const searchable = row.dataset.searchable || row.textContent.toLowerCase();
        const matches = searchable.includes(term);
        row.style.display = matches ? '' : 'none';
        if (matches) matchCount++;
    });

    liveMatchCount.textContent = matchCount;

    const existing = document.getElementById('noResultsRow');
    if (matchCount === 0 && !existing) {
        const tr = document.createElement('tr');
        tr.id = 'noResultsRow';
        tr.innerHTML = `<td colspan="8" style="text-align:center;padding:2rem;color:var(--slate-400);">
            <i class="bi bi-search" style="font-size:1.5rem;display:block;margin-bottom:0.5rem;"></i>
            No products matched "<strong>${term}</strong>" on this page.<br>
            <span style="cursor:pointer;color:var(--navy);text-decoration:underline;"
                  onclick="submitSearch()">Click here to search all pages</span>
        <\/td>`;
        document.getElementById('productsTableBody').appendChild(tr);
    } else if (matchCount > 0 && existing) {
        existing.remove();
    }
}, 200);

if (searchInput) {
    searchInput.addEventListener('input', function() {
        doLiveSearch(this.value.toLowerCase().trim());
    });

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); submitSearch(); }
        if (e.key === 'Escape') { this.value = ''; doLiveSearch(''); }
    });
}

function submitSearch() {
    const term = searchInput.value.trim();
    if (!term) { window.location.href = window.location.pathname; return; }
    const url = new URL(window.location.pathname, window.location.origin);
    url.searchParams.set('search', term);
    window.location.href = url.toString();
}

function toggleCheckbox(element, productId) {
    element.classList.toggle('checked');
    updateActionButtons();
}

function toggleAllCheckboxes() {
    const masterCheckbox = document.getElementById('masterCheckbox');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const willCheck = !masterCheckbox.classList.contains('checked');
    masterCheckbox.classList.toggle('checked');
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
    const masterCheckbox = document.getElementById('masterCheckbox');
    selectAllBtn.innerHTML = masterCheckbox.classList.contains('checked')
        ? '<i class="bi bi-x-square"></i> Deselect All'
        : '<i class="bi bi-check2-square"></i> Select All';
}

function updateActionButtons() {
    const count = document.querySelectorAll('.product-checkbox.checked').length;
    const duplicateBtn = document.getElementById('duplicateBtn');
    const printBtn = document.getElementById('printBtn');
    const deleteAllBtn = document.getElementById('deleteAllBtn');
    if (duplicateBtn) duplicateBtn.disabled = count === 0;
    if (printBtn) printBtn.disabled = count === 0;
    if (deleteAllBtn) deleteAllBtn.disabled = count === 0;
}

function openPrintModal() {
    const selected = document.querySelectorAll('.product-checkbox.checked');
    if (selected.length === 0) { alert('Please select at least one product'); return; }
    const products = Array.from(selected).map(cb => {
        const row = cb.closest('tr');
        return {
            name1: row.querySelector('.prod-name').textContent.trim(),
            name2: row.querySelector('.prod-sub').textContent.trim(),
            category: row.querySelector('.cat-badge').textContent.trim(),
            costPrice: row.querySelectorAll('.price')[0].textContent.trim(),
            sellPrice: row.querySelectorAll('.price')[1].textContent.trim(),
        };
    });
    generatePrintContent(products);
    new bootstrap.Modal(document.getElementById('printModal')).show();
}

function generatePrintContent(products) {
    const currentDate = new Date().toLocaleDateString('en-US', {
        year: 'numeric', month: 'long', day: 'numeric'
    });
    const rows = products.map((p, i) => `
        <tr>
            <td style="text-align:center;width:5%">${i + 1}<\/td>
            <td style="width:40%">
                <div class="prod-disp-name">${escapeHtml(p.name1)}</div>
                <div class="prod-disp-sub">${escapeHtml(p.name2)}</div>
             <\/td>
            <td style="width:20%">${escapeHtml(p.category)}<\/td>
            <td style="text-align:right;width:17%">${p.costPrice}<\/td>
            <td style="text-align:right;width:17%">${p.sellPrice}<\/td>
        几个人`).join('');

    document.getElementById('printModalBody').innerHTML = `
        <div class="print-order">
            <div class="order-hd">
                <div class="order-title">📦 PURCHASE ORDER</div>
                <div class="order-sub">Product List for Procurement</div>
                <div class="order-date">${currentDate}</div>
            </div>
            <table class="order-tbl">
                <thead><tr>
                    <th style="width:5%;text-align:center">No.</th>
                    <th style="width:40%">Product Name</th>
                    <th style="width:20%">Category</th>
                    <th style="width:17%;text-align:right">Cost Price</th>
                    <th style="width:17%;text-align:right">Sell Price</th>
                </tr></thead>
                <tbody>${rows}</tbody>
             built
            <div class="order-sum">
                <div class="sum-row">
                    <span><strong>Total Items:</strong></span>
                    <span><strong>${products.length}</strong></span>
                </div>
                <div class="sum-row total">
                    <span>TOTAL PRODUCTS</span>
                    <span>${products.length}</span>
                </div>
            </div>
        </div>`;
}

function printOrder() {
    const content = document.querySelector('.print-order').innerHTML;
    const w = window.open('', '', 'height=700,width=900');
    w.document.write(`<!DOCTYPE html><html><head><style>
        body{font-family:Arial,sans-serif;margin:0;padding:20px;background:white}
        table{width:100%;border-collapse:collapse;margin:20px 0}
        th,td{border:1px solid #e2e8f0;padding:12px;text-align:left}
        th{background:#f1f5f9;color:#0B1E3D;font-weight:600}
        .order-hd{text-align:center;margin-bottom:30px}
        .order-title{font-size:24px;font-weight:700;color:#0B1E3D;margin-bottom:8px}
        .order-sub,.order-date{color:#64748b}
        .prod-disp-name{font-weight:600;color:#0B1E3D}
        .prod-disp-sub{font-size:.8rem;color:#64748b}
        .sum-row{display:flex;justify-content:space-between;padding:8px 0}
        .sum-row.total{border-top:2px solid #0B1E3D;font-weight:700;color:#0B1E3D;font-size:1.05rem}
        .order-sum{margin-top:20px;padding-top:15px;border-top:2px solid #e2e8f0}
    <\/style><\/head><body>${content}<\/body><\/html>`);
    w.document.close();
    setTimeout(() => w.print(), 250);
}

function openDeleteModal() {
    const selected = Array.from(document.querySelectorAll('.product-checkbox.checked')).map(cb => ({
        id: cb.closest('tr').dataset.productId,
        name: cb.closest('tr').querySelector('.prod-name').textContent.trim()
    }));
    const container = document.querySelector('.selected-products-container-delete');
    const hiddenInputs = document.getElementById('deleteHiddenInputs');
    if (container) container.innerHTML = '';
    if (hiddenInputs) hiddenInputs.innerHTML = '';
    if (selected.length === 0) {
        if (container) container.innerHTML = '<p style="text-align:center;color:var(--slate-400);padding:1rem;">No products selected</p>';
    } else {
        selected.forEach(p => {
            if (container) {
                container.innerHTML += `<div class="selected-item">
                    <span class="selected-item-name">${escapeHtml(p.name)}</span>
                    <span class="selected-item-badge">ID: ${p.id}</span>
                </div>`;
            }
            if (hiddenInputs) {
                const inp = document.createElement('input');
                inp.type = 'hidden'; inp.name = 'product_ids[]'; inp.value = p.id;
                hiddenInputs.appendChild(inp);
            }
        });
    }
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function deleteSelectedProducts() {
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    if (confirmBtn) confirmBtn.disabled = true;
    setTimeout(() => document.getElementById('deleteForm').submit(), 500);
}

function openDuplicateModal() {
    const selected = Array.from(document.querySelectorAll('.product-checkbox.checked')).map(cb => ({
        id: cb.closest('tr').dataset.productId,
        name: cb.closest('tr').querySelector('.prod-name').textContent.trim()
    }));
    const container = document.querySelector('.selected-products-container');
    const hiddenInputs = document.getElementById('hiddenInputs');
    if (container) container.innerHTML = '';
    if (hiddenInputs) hiddenInputs.innerHTML = '';
    if (selected.length === 0) {
        if (container) container.innerHTML = '<p style="text-align:center;color:var(--slate-400);padding:1rem;">No products selected</p>';
    } else {
        selected.forEach(p => {
            if (container) {
                container.innerHTML += `<div class="selected-item">
                    <span class="selected-item-name">${escapeHtml(p.name)}</span>
                    <span class="selected-item-badge">ID: ${p.id}</span>
                </div>`;
            }
            if (hiddenInputs) {
                const inp = document.createElement('input');
                inp.type = 'hidden'; inp.name = 'product_ids[]'; inp.value = p.id;
                hiddenInputs.appendChild(inp);
            }
        });
    }
    new bootstrap.Modal(document.getElementById('duplicateModal')).show();
}

function duplicateProducts() {
    const targetAccount = document.getElementById('targetAccount').value;
    if (!targetAccount) { alert('Please select a target shop.'); return; }
    const confirmBtn = document.getElementById('confirmDuplicateBtn');
    if (confirmBtn) confirmBtn.disabled = true;
    setTimeout(() => document.getElementById('duplicateForm').submit(), 500);
}

function downloadReport() {
    window.location.href = "{{ route('admin.product.report.export') }}";
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

// ========== OFFER MODAL WITH SEARCHABLE PRODUCT SELECTOR ==========
let currentOfferProductId = null;
let currentOfferId = null;
let allProductsList = [];

// Load all products from the table for search
function loadAllProductsForSearch() {
    const rows = document.querySelectorAll('#productsTableBody tr[data-product-id]');
    allProductsList = [];
    rows.forEach(row => {
        const id = row.getAttribute('data-product-id');
        const nameEl = row.querySelector('.prod-name');
        let name = '';
        if (nameEl) {
            // Get text without the offer tag
            const clone = nameEl.cloneNode(true);
            const offerTag = clone.querySelector('.offer-tag');
            if (offerTag) offerTag.remove();
            name = clone.textContent.trim();
        }
        const subEl = row.querySelector('.prod-sub');
        const sub = subEl ? subEl.textContent.trim() : '';
        const category = row.querySelector('.cat-badge') ? row.querySelector('.cat-badge').textContent : '';
        const stockSpan = row.querySelector('.stock-badge');
        let stock = 0;
        if (stockSpan) {
            const match = stockSpan.textContent.match(/\d+/);
            if (match) stock = parseInt(match[0]);
        }
        
        if (id && name) {
            allProductsList.push({
                id: id,
                name: name,
                description: sub,
                category: category,
                stock: stock,
                displayText: `${name} ${sub ? '('+sub+')' : ''} [Stock: ${stock}] - ID: ${id}`
            });
        }
    });
    
    // Populate datalist options
    const datalist = document.getElementById('productOptionsList');
    if (datalist) {
        datalist.innerHTML = '';
        allProductsList.forEach(product => {
            const option = document.createElement('option');
            option.value = product.displayText;
            option.setAttribute('data-id', product.id);
            option.setAttribute('data-name', product.name);
            datalist.appendChild(option);
        });
    }
    
    console.log('Loaded ' + allProductsList.length + ' products for search');
}

// Setup the search input with datalist
function setupProductSearch() {
    const searchInput = document.getElementById('offerProductSearchInput');
    const hiddenId = document.getElementById('offerProductIdHidden');
    
    if (!searchInput || !hiddenId) return;
    
    searchInput.addEventListener('input', function() {
        const value = this.value;
        // Find matching product by display text or name or ID
        const matchedProduct = allProductsList.find(p => 
            p.displayText === value || 
            p.name.toLowerCase() === value.toLowerCase() ||
            p.id === value
        );
        
        if (matchedProduct) {
            hiddenId.value = matchedProduct.id;
            // Add visual feedback
            this.style.borderColor = 'var(--emerald)';
        } else {
            if (value === '') {
                hiddenId.value = '';
                this.style.borderColor = 'var(--slate-200)';
            } else {
                this.style.borderColor = 'var(--rose)';
            }
        }
    });
    
    searchInput.addEventListener('change', function() {
        const value = this.value;
        const matchedProduct = allProductsList.find(p => p.displayText === value);
        if (matchedProduct) {
            hiddenId.value = matchedProduct.id;
            this.style.borderColor = 'var(--emerald)';
        } else {
            // Try to match by just the product name
            const nameMatch = allProductsList.find(p => p.name.toLowerCase() === value.toLowerCase());
            if (nameMatch) {
                hiddenId.value = nameMatch.id;
                this.value = nameMatch.displayText;
                this.style.borderColor = 'var(--emerald)';
            }
        }
    });
    
    // Allow typing to search - make sure input is focusable
    searchInput.addEventListener('click', function(e) {
        e.stopPropagation();
    });
}

function openOfferModal(productId = null, productName = '') {
    productId = productId || null;
    productName = productName || '';
    
    currentOfferProductId = productId;
    currentOfferId = null;
    
    // Make sure products are loaded
    if (allProductsList.length === 0) {
        loadAllProductsForSearch();
    }
    
    // Reset search input
    const searchInput = document.getElementById('offerProductSearchInput');
    const hiddenId = document.getElementById('offerProductIdHidden');
    if (searchInput) {
        searchInput.value = '';
        searchInput.style.borderColor = 'var(--slate-200)';
    }
    if (hiddenId) hiddenId.value = '';
    
    // Update modal header with product info
    const productNameEl = document.getElementById('offerProductName');
    const productIdEl = document.getElementById('offerProductId');
    const productIdInput = document.getElementById('offerProductIdInput');
    
    if (productId) {
        if (productNameEl) productNameEl.textContent = productName || 'Unknown Product';
        if (productIdEl) productIdEl.textContent = 'ID: ' + productId;
        if (productIdInput) productIdInput.value = productId;
        document.getElementById('existingOffersSection').style.display = 'block';
        
        // Load existing offers for this product
        loadOffersForProduct(productId);
    } else {
        if (productNameEl) productNameEl.textContent = 'Select a product first';
        if (productIdEl) productIdEl.textContent = '—';
        if (productIdInput) productIdInput.value = '';
        document.getElementById('existingOffersSection').style.display = 'none';
        const offersList = document.getElementById('offersList');
        if (offersList) {
            offersList.innerHTML = '<p style="text-align:center;color:var(--slate-400);padding:1rem;">Select a product to view and create offers</p>';
        }
    }
    
    // Reset form
    const offerForm = document.getElementById('offerForm');
    if (offerForm) offerForm.reset();
    const requiredQty = document.getElementById('requiredQuantity');
    const offerQty = document.getElementById('offerQuantity');
    const isActive = document.getElementById('isActive');
    if (requiredQty) requiredQty.value = '1';
    if (offerQty) offerQty.value = '1';
    if (isActive) isActive.checked = true;
    
    const messageDiv = document.getElementById('offerFormMessage');
    if (messageDiv) messageDiv.innerHTML = '';
    
    const deleteSection = document.getElementById('deleteOfferSection');
    if (deleteSection) deleteSection.style.display = 'none';
    
    // Show modal
    const modalEl = document.getElementById('offerModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
        
        // Focus on search input after modal is shown
        modalEl.addEventListener('shown.bs.modal', function() {
            if (searchInput) searchInput.focus();
        }, { once: true });
    }
}

function loadOffersForProduct(productId) {
    $.ajax({
        url: `/admin/getOffers/${productId}`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            displayOffersList(response);
        },
        error: function(xhr) {
            console.error('Error loading offers:', xhr);
            const offersList = document.getElementById('offersList');
            if (offersList) {
                offersList.innerHTML = '<p style="text-align:center;color:var(--rose);padding:1rem;">Error loading offers</p>';
            }
        }
    });
}

function displayOffersList(offers) {
    const container = document.getElementById('offersList');
    if (!container) return;
    container.innerHTML = '';
    
    if (!offers || offers.length === 0) {
        container.innerHTML = '<p style="text-align:center;color:var(--slate-400);padding:1rem;">No active offers for this product</p>';
        return;
    }
    
    offers.forEach(offer => {
        const offerText = `Buy ${offer.required_quantity} × get ${offer.offer_quantity} × free`;
        const productName = offer.offeredProduct && offer.offeredProduct.name01 ? offer.offeredProduct.name01 : 'Product';
        const displayText = offer.offeredProduct && offer.offeredProduct.name02 ? 
            `${productName} (${offer.offeredProduct.name02})` : productName;
        
        const item = document.createElement('div');
        item.className = 'selected-item';
        item.setAttribute('data-offer-id', offer.id);
        item.innerHTML = `
            <div>
                <div class="selected-item-name">${escapeHtml(offerText)}</div>
                <div style="font-size:0.75rem;color:var(--slate-400);">
                    Free product: ${escapeHtml(displayText)}
                </div>
            </div>
            <button type="button" class="mbtn mbtn-outline" style="padding:0.25rem 0.5rem;font-size:0.75rem;"
                    onclick="editOffer(${offer.id}, ${offer.required_quantity}, ${offer.offer_quantity})" title="Edit">
                <i class="bi bi-pencil"></i>
            </button>
        `;
        container.appendChild(item);
    });
}

function editOffer(offerId, reqQty, offQty) {
    const requiredQty = document.getElementById('requiredQuantity');
    const offerQty = document.getElementById('offerQuantity');
    if (requiredQty) requiredQty.value = reqQty;
    if (offerQty) offerQty.value = offQty;
    
    currentOfferId = offerId;
    
    const deleteSection = document.getElementById('deleteOfferSection');
    if (deleteSection) deleteSection.style.display = 'block';
    
    const messageDiv = document.getElementById('offerFormMessage');
    if (messageDiv) {
        messageDiv.innerHTML = '<div class="alert-box" style="background:var(--emerald-pale);border-color:var(--emerald);color:#065F46;"><i class="bi bi-info-circle-fill"></i> Editing existing offer. Update or delete.</div>';
    }
    
    // Scroll to form
    const offerForm = document.getElementById('offerForm');
    if (offerForm) {
        offerForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function deleteOffer() {
    if (!currentOfferId) return;
    
    if (!confirm('Are you sure you want to delete this offer?')) return;
    
    $.ajax({
        url: "{{ route('admin.deleteOffer') }}",
        type: 'POST',
        data: {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            offer_id: currentOfferId
        },
        success: function(response) {
            const messageDiv = document.getElementById('offerFormMessage');
            if (messageDiv) {
                messageDiv.innerHTML = `
                    <div class="alert-box" style="background:var(--emerald-pale);border-color:var(--emerald);color:#065F46;">
                        <i class="bi bi-check-circle-fill"></i> ${response.message || 'Offer deleted successfully!'}
                    </div>
                `;
            }
            
            if (currentOfferProductId) {
                loadOffersForProduct(currentOfferProductId);
            }
            
            const offerForm = document.getElementById('offerForm');
            if (offerForm) offerForm.reset();
            
            const requiredQty = document.getElementById('requiredQuantity');
            const offerQty = document.getElementById('offerQuantity');
            if (requiredQty) requiredQty.value = '1';
            if (offerQty) offerQty.value = '1';
            
            const deleteSection = document.getElementById('deleteOfferSection');
            if (deleteSection) deleteSection.style.display = 'none';
            
            currentOfferId = null;
            
            setTimeout(() => {
                if (messageDiv) messageDiv.innerHTML = '';
            }, 3000);
        },
        error: function(xhr) {
            const errorMsg = xhr.responseJSON?.message || 'Error deleting offer';
            const messageDiv = document.getElementById('offerFormMessage');
            if (messageDiv) {
                messageDiv.innerHTML = `
                    <div class="alert-box">
                        <i class="bi bi-exclamation-triangle-fill"></i> ${errorMsg}
                    </div>
                `;
            }
        }
    });
}

function updateOfferBadgeInTable(productId, hasOffer) {
    const row = document.querySelector(`tr[data-product-id="${productId}"]`);
    if (!row) return;
    
    const nameCell = row.querySelector('.prod-name');
    if (!nameCell) return;
    
    const existingTag = nameCell.querySelector('.offer-tag');
    if (hasOffer) {
        if (!existingTag) {
            const tag = document.createElement('span');
            tag.className = 'offer-tag';
            tag.setAttribute('title', 'Active offer');
            tag.innerHTML = '<i class="bi bi-gift-fill"></i> Offer';
            nameCell.appendChild(tag);
        }
    } else {
        if (existingTag) existingTag.remove();
    }
}

// Document ready - initialize everything
$(document).ready(function() {
    // Load products for search
    setTimeout(function() {
        loadAllProductsForSearch();
        setupProductSearch();
    }, 500);
    
    // Handle offer form submission via AJAX
    $('#offerForm').on('submit', function(e) {
        e.preventDefault();
        
        const targetProductId = document.getElementById('offerProductIdHidden').value;
        if (!targetProductId) {
            const messageDiv = document.getElementById('offerFormMessage');
            if (messageDiv) {
                messageDiv.innerHTML = `
                    <div class="alert-box">
                        <i class="bi bi-exclamation-triangle-fill"></i> Please select a valid product from the search list!
                    </div>
                `;
            }
            return;
        }
        
        // Make sure the hidden field has the value
        $('input[name="offer_product_id"]').remove();
        $(this).append(`<input type="hidden" name="offer_product_id" value="${targetProductId}">`);
        
        const formData = $(this).serialize();
        const saveBtn = $('#saveOfferBtn');
        saveBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Saving...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                const messageDiv = document.getElementById('offerFormMessage');
                if (messageDiv) {
                    messageDiv.innerHTML = `
                        <div class="alert-box" style="background:var(--emerald-pale);border-color:var(--emerald);color:#065F46;">
                            <i class="bi bi-check-circle-fill"></i> ${response.message || 'Offer saved successfully!'}
                        </div>
                    `;
                }
                
                // Reload offers list
                if (currentOfferProductId) {
                    loadOffersForProduct(currentOfferProductId);
                }
                
                // Reset form if not editing
                if (!currentOfferId) {
                    const offerForm = document.getElementById('offerForm');
                    if (offerForm) offerForm.reset();
                    const requiredQty = document.getElementById('requiredQuantity');
                    const offerQty = document.getElementById('offerQuantity');
                    const searchInput = document.getElementById('offerProductSearchInput');
                    const hiddenId = document.getElementById('offerProductIdHidden');
                    if (requiredQty) requiredQty.value = '1';
                    if (offerQty) offerQty.value = '1';
                    if (searchInput) searchInput.value = '';
                    if (hiddenId) hiddenId.value = '';
                }
                
                // Clear edit state
                currentOfferId = null;
                const deleteSection = document.getElementById('deleteOfferSection');
                if (deleteSection) deleteSection.style.display = 'none';
                
                // Update product badge in table
                updateOfferBadgeInTable(currentOfferProductId, true);
                
                setTimeout(() => {
                    if (messageDiv) messageDiv.innerHTML = '';
                }, 3000);
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Error saving offer';
                const messageDiv = document.getElementById('offerFormMessage');
                if (messageDiv) {
                    messageDiv.innerHTML = `
                        <div class="alert-box">
                            <i class="bi bi-exclamation-triangle-fill"></i> ${errorMsg}
                        </div>
                    `;
                }
            },
            complete: function() {
                saveBtn.prop('disabled', false).html('<i class="bi bi-check-lg"></i> Save Offer');
            }
        });
    });
    
    // Handle delete offer button
    $('#deleteOfferBtn').on('click', deleteOffer);
});
</script>
</body>
</html>