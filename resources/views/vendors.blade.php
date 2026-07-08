<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} — Supplier Management</title>

    @include("links")
    
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

            --font:   'Sora', system-ui, sans-serif;
            --mono:   'JetBrains Mono', monospace;
            --r:      8px;
            --r-lg:   13px;
            --r-xl:   16px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(11,30,61,0.08);
            --shadow-lg: 0 12px 32px rgba(11,30,61,0.12);
        }

        body {
            font-family: var(--font);
            background: #ECF0F8;
            color: var(--slate-800);
            min-height: 100vh;
            font-size: 14px;
            line-height: 1.6;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        /* ── Layout ── */
        .wrap { padding: 1.5rem 1.75rem 3rem; max-width: 1600px; margin: 0 auto; }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .au  { animation: fadeUp 0.4s ease both; }
        .au1 { animation-delay: 0.05s; }
        .au2 { animation-delay: 0.12s; }
        .au3 { animation-delay: 0.19s; }
        .au4 { animation-delay: 0.26s; }
        .au5 { animation-delay: 0.33s; }

        /* ══════════════════════════════════════
           PAGE HEADER
        ══════════════════════════════════════ */
        .pg-header {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%);
            border-radius: var(--r-xl);
            padding: 1.4rem 2rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }
        /* Decorative elements */
        .pg-header::before {
            content: '⚡';
            position: absolute;
            bottom: -30px;
            right: -20px;
            font-size: 140px;
            opacity: 0.03;
            pointer-events: none;
            transform: rotate(-15deg);
        }
        .pg-header::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245,158,11,0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .pg-left { display: flex; align-items: center; gap: 14px; position: relative; z-index: 1; }

        .back-btn {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            border-radius: var(--r);
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 16px;
            transition: all 0.2s ease;
        }
        .back-btn:hover {
            background: rgba(255,255,255,0.2);
            color: var(--white);
            transform: translateX(-2px);
        }

        .header-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--r-lg);
            background: linear-gradient(135deg, var(--amber) 0%, #FBBF24 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--navy);
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(245,158,11,0.3);
        }

        .pg-title-text h1 {
            font-size: 20px;
            font-weight: 700;
            color: var(--white);
            letter-spacing: -0.3px;
            line-height: 1.2;
            margin: 0;
        }
        .pg-title-text p {
            font-size: 12px;
            color: rgba(255,255,255,0.6);
            margin: 4px 0 0;
        }

        .pg-right { display: flex; gap: 10px; align-items: center; position: relative; z-index: 1; }

        .btn-amber {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--r-lg);
            background: linear-gradient(135deg, var(--amber) 0%, #FBBF24 100%);
            color: var(--navy);
            font-family: var(--font);
            font-size: 13px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(245,158,11,0.4);
            transition: all 0.2s ease;
        }
        .btn-amber:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245,158,11,0.5);
            color: var(--navy);
        }
        .btn-amber:active { transform: translateY(0); }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: var(--r-lg);
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.8);
            font-family: var(--font);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-ghost:hover {
            background: rgba(255,255,255,0.15);
            color: var(--white);
            transform: translateY(-1px);
        }

        /* ══════════════════════════════════════
           FILTER SECTION
        ══════════════════════════════════════ */
        .filter-section {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1rem;
            margin-bottom: 1.75rem;
            align-items: end;
        }
        @media (max-width: 768px) {
            .filter-section {
                grid-template-columns: 1fr;
            }
        }

        .search-bar {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg);
            padding: 0.85rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }
        .search-bar:focus-within {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 4px rgba(26,58,107,0.08), var(--shadow-sm);
        }
        .search-bar i {
            color: var(--slate-400);
            font-size: 16px;
            flex-shrink: 0;
        }
        .search-bar input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            font-family: var(--font);
            font-size: 13.5px;
            color: var(--slate-800);
        }
        .search-bar input::placeholder {
            color: var(--slate-400);
        }
        .search-count {
            font-size: 12px;
            color: var(--slate-500);
            background: var(--slate-100);
            border-radius: 20px;
            padding: 4px 12px;
            font-family: var(--mono);
            flex-shrink: 0;
            font-weight: 500;
        }
        .btn-clear {
            font-size: 12px;
            font-weight: 600;
            padding: 6px 14px;
            background: var(--slate-100);
            border: 1px solid var(--slate-200);
            border-radius: var(--r);
            color: var(--slate-600);
            cursor: pointer;
            transition: all 0.15s;
            flex-shrink: 0;
        }
        .btn-clear:hover {
            background: var(--slate-200);
            color: var(--slate-800);
            transform: scale(0.98);
        }

        .shop-filter-card {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg);
            padding: 0.85rem 1.2rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s;
        }
        .shop-filter-card:focus-within {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 4px rgba(26,58,107,0.08);
        }
        .shop-filter-card label {
            font-size: 11px;
            font-weight: 700;
            color: var(--slate-500);
            margin-bottom: 6px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }
        .shop-filter-card select {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-family: var(--font);
            font-size: 13.5px;
            color: var(--slate-800);
            cursor: pointer;
            padding: 0;
        }

        /* ══════════════════════════════════════
           STATS GRID
        ══════════════════════════════════════ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 540px) {
            .stats-grid { grid-template-columns: 1fr; }
        }

        .stat-card {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: var(--r-xl);
            padding: 1.3rem 1.4rem;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
            transition: all 0.25s ease;
            cursor: pointer;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--sc);
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .sc-navy    { --sc: var(--navy); }
        .sc-emerald { --sc: var(--emerald); }
        .sc-sky     { --sc: var(--sky); }
        .sc-amber   { --sc: var(--amber); }

        .stat-inner { display: flex; align-items: center; gap: 14px; }
        .stat-icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: var(--r-lg);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }
        .si-navy    { background: linear-gradient(135deg, rgba(11,30,61,0.08) 0%, rgba(11,30,61,0.04) 100%); color: var(--navy); }
        .si-emerald { background: linear-gradient(135deg, var(--emerald-pale) 0%, #A7F3D0 100%); color: var(--emerald); }
        .si-sky     { background: linear-gradient(135deg, var(--sky-pale) 0%, #BAE6FD 100%); color: var(--sky); }
        .si-amber   { background: linear-gradient(135deg, var(--amber-pale) 0%, #FDE68A 100%); color: var(--amber); }

        .stat-body { flex: 1; }
        .stat-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--slate-500);
            margin-bottom: 6px;
        }
        .stat-value {
            font-family: var(--mono);
            font-size: 26px;
            font-weight: 600;
            color: var(--navy);
            letter-spacing: -0.5px;
            line-height: 1;
        }

        /* ══════════════════════════════════════
           PANEL / TABLE
        ══════════════════════════════════════ */
        .panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: var(--r-xl);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: box-shadow 0.2s;
        }
        .panel:hover {
            box-shadow: var(--shadow-md);
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.2rem 1.6rem;
            border-bottom: 2px solid var(--slate-100);
            background: linear-gradient(to bottom, var(--slate-50) 0%, var(--white) 100%);
            gap: 1rem;
            flex-wrap: wrap;
        }
        .panel-head-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .panel-head-icon {
            width: 34px;
            height: 34px;
            border-radius: var(--r);
            background: linear-gradient(135deg, rgba(11,30,61,0.08) 0%, rgba(11,30,61,0.04) 100%);
            color: var(--navy-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        .panel-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
        }
        .result-pill {
            font-size: 11px;
            font-weight: 600;
            font-family: var(--mono);
            background: var(--slate-200);
            color: var(--slate-700);
            padding: 3px 10px;
            border-radius: 20px;
        }

        .panel-tools { display: flex; gap: 8px; align-items: center; }
        .tool-btn {
            width: 34px;
            height: 34px;
            border-radius: var(--r);
            border: 1.5px solid var(--slate-200);
            background: var(--white);
            color: var(--slate-600);
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .tool-btn:hover {
            background: var(--slate-100);
            color: var(--navy);
            transform: scale(1.05);
            border-color: var(--slate-300);
        }

        /* Table */
        .tbl-wrap {
            overflow-x: auto;
            max-height: 600px;
            overflow-y: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead th {
            background: var(--navy);
            color: rgba(255,255,255,0.7);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 14px 16px;
            white-space: nowrap;
            border: none;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        tbody tr {
            border-bottom: 1px solid var(--slate-100);
            transition: all 0.15s ease;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover {
            background: linear-gradient(90deg, #F8FAFF 0%, var(--white) 100%);
            transform: scale(1.001);
        }

        td { padding: 14px 16px; vertical-align: middle; }

        .idx {
            font-size: 12px;
            color: var(--slate-500);
            font-family: var(--mono);
            font-weight: 500;
        }

        .vendor-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--navy-mid) 0%, var(--navy-light) 100%);
            color: rgba(255,255,255,0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(11,30,61,0.15);
        }
        .vendor-cell { display: flex; align-items: center; gap: 12px; }
        .vendor-name {
            font-weight: 700;
            color: var(--navy);
            font-size: 13.5px;
            margin-bottom: 2px;
        }
        .vendor-loc {
            font-size: 11px;
            color: var(--slate-500);
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .vendor-loc i {
            font-size: 10px;
        }

        .contact-val {
            font-size: 13px;
            color: var(--slate-700);
            font-family: var(--mono);
        }

        .type-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .type-badge i { font-size: 11px; }
        .tb-wholesale    { background: var(--sky-pale);     color: var(--sky); }
        .tb-manufacturer { background: var(--violet-pale);  color: var(--violet); }
        .tb-distributor  { background: var(--emerald-pale); color: var(--emerald); }
        .tb-retailer     { background: var(--amber-pale);   color: #92400e; }
        .tb-default      { background: var(--slate-100);    color: var(--slate-600); }

        .credit-pos {
            font-family: var(--mono);
            font-weight: 700;
            color: var(--emerald);
            font-size: 13px;
        }
        .credit-nil {
            font-family: var(--mono);
            font-weight: 500;
            color: var(--slate-400);
            font-size: 13px;
        }

        /* Action buttons */
        .action-btns {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }
        .act-btn {
            width: 32px;
            height: 32px;
            border-radius: var(--r);
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1.5px solid;
        }
        .ab-view   { border-color: var(--sky);     color: var(--sky); }
        .ab-edit   { border-color: var(--amber);   color: var(--amber); }
        .ab-delete { border-color: var(--rose);    color: var(--rose); }
        .ab-view:hover {
            background: var(--sky-pale);
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(2,132,199,0.2);
        }
        .ab-edit:hover {
            background: var(--amber-pale);
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(245,158,11,0.2);
        }
        .ab-delete:hover {
            background: var(--rose-pale);
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(225,29,72,0.2);
        }

        /* Bottom CTA */
        .panel-footer {
            padding: 1.3rem 1.6rem;
            border-top: 2px solid var(--slate-100);
            display: flex;
            justify-content: center;
            background: var(--slate-50);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--slate-400);
        }
        .empty-state i {
            font-size: 4rem;
            display: block;
            margin-bottom: 1rem;
            opacity: 0.3;
        }
        .empty-state h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--slate-600);
            margin-bottom: 8px;
        }
        .empty-state p {
            font-size: 13px;
            margin-bottom: 1.5rem;
            color: var(--slate-500);
        }

        /* ══════════════════════════════════════
           MODAL STYLES (Fixed)
        ══════════════════════════════════════ */
        .modal.fade .modal-dialog {
            transform: scale(0.9) translateY(-50px);
            transition: transform 0.25s ease-out, opacity 0.2s ease-out;
        }
        .modal.show .modal-dialog {
            transform: scale(1) translateY(0);
        }
        .modal-content {
            border: none;
            border-radius: var(--r-xl);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%);
            padding: 1.2rem 1.6rem;
            border-bottom: none;
        }
        .modal-header .modal-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .modal-header .modal-title i {
            width: 34px;
            height: 34px;
            border-radius: var(--r);
            background: var(--amber);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--navy);
            font-size: 16px;
        }
        .modal-header h5 {
            font-size: 16px;
            font-weight: 700;
            color: var(--white);
            margin: 0;
        }
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        .modal-header .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 1.8rem;
            background: var(--white);
        }

        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }
        @media (max-width: 560px) {
            .field-row { grid-template-columns: 1fr; gap: 12px; }
        }

        .field {
            margin-bottom: 16px;
        }
        .field:last-child { margin-bottom: 0; }

        .field-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--slate-600);
            margin-bottom: 8px;
        }
        .field-label .req {
            color: var(--rose);
            margin-left: 3px;
        }

        .field-input {
            width: 100%;
            font-family: var(--font);
            font-size: 13.5px;
            padding: 10px 14px;
            border: 1.5px solid var(--slate-200);
            border-radius: var(--r);
            background: var(--white);
            color: var(--slate-800);
            outline: none;
            transition: all 0.2s ease;
        }
        .field-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }
        .field-input::placeholder {
            color: var(--slate-400);
        }
        select.field-input {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 2.5rem;
        }
        textarea.field-input {
            resize: vertical;
            min-height: 90px;
        }

        .modal-divider {
            height: 1px;
            background: linear-gradient(to right, var(--slate-200), transparent);
            margin: 1.2rem 0;
            border: none;
        }
        .modal-section-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--slate-500);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .modal-section-label i {
            font-size: 12px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            background: linear-gradient(135deg, var(--amber) 0%, #FBBF24 100%);
            color: var(--navy);
            font-family: var(--font);
            font-size: 14px;
            font-weight: 700;
            border: none;
            border-radius: var(--r-lg);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(245,158,11,0.35);
            transition: all 0.2s ease;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245,158,11,0.45);
        }
        .btn-submit:active {
            transform: translateY(0);
        }

        /* ── Dropdown menu ── */
        .dropdown-menu {
            border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg);
            box-shadow: var(--shadow-lg);
            padding: 0.5rem 0;
            min-width: 160px;
        }
        .dropdown-item {
            padding: 0.6rem 1.2rem;
            font-size: 13px;
            font-weight: 500;
            color: var(--slate-700);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s ease;
        }
        .dropdown-item:hover {
            background: linear-gradient(90deg, var(--slate-50) 0%, var(--white) 100%);
            color: var(--navy);
            padding-left: 1.5rem;
        }

        /* ── Responsive mobile card table ── */
        @media (max-width: 720px) {
            .wrap { padding: 1rem; }
            .pg-header { padding: 1.2rem 1.4rem; }
            table thead { display: none; }
            tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1.5px solid var(--slate-200);
                border-radius: var(--r-lg);
                padding: 1rem;
                background: var(--white);
            }
            tbody tr td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 0;
                border-bottom: 1px solid var(--slate-100);
            }
            tbody tr td:last-child {
                border-bottom: none;
                padding-top: 12px;
                margin-top: 8px;
                border-top: 2px solid var(--slate-200);
            }
            tbody tr td::before {
                content: attr(data-label);
                font-size: 10.5px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: var(--slate-500);
                min-width: 100px;
            }
            .action-btns { justify-content: flex-end; }
        }

        @media print {
            .d-print-none, .pg-right, .search-bar, .panel-tools, .action-btns, .panel-footer, .filter-section { display: none !important; }
            body { background: white; }
            .panel { box-shadow: none; border: 1px solid #ccc; }
            .stat-card { break-inside: avoid; }
        }
    </style>
</head>
<body>
@include("sidenav")
<main class="main-content">
            <div class="wrap">

                {{-- ══ PAGE HEADER ══ --}}
                <div class="pg-header au au1">
                    <div class="pg-left">
                        <a href="#" onclick="history.back()" class="back-btn d-print-none">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div class="header-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="pg-title-text">
                            <h1>Shops</h1>
                            <p><i class="bi bi-database"></i> {{ count($fetch) }} active suppliers</p>
                        </div>
                    </div>
                    <div class="pg-right">
                        <button class="btn-ghost d-print-none" onclick="window.print()">
                            <i class="bi bi-printer"></i> Print
                        </button>
                        <a href="/settings" class="btn-amber" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                            <i class="bi bi-plus-lg"></i> New Shops
                        </a>
                    </div>
                </div>

                {{-- ══ FILTER SECTION ══ --}}
                <div class="filter-section au au2">
                    <div class="search-bar">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" placeholder="Search by name, contact, or business type…">
                        <span class="search-count" id="searchCount">{{ count($fetch) }} results</span>
                        <button class="btn-clear" id="clearBtn">Clear</button>
                    </div>

                    @if(isset($shops) && count($shops) > 0)
                    <div class="shop-filter-card">
                        <label><i class="bi bi-shop"></i> Filter by Shop</label>
                        <form method="GET" action="{{ url('vendors') }}" id="shopFilterForm">
                            <select name="shop" id="shopFilter">
                                <option value="">All Shops</option>
                                @foreach($shops as $shop)
                                    <option value="{{ $shop['id'] }}" {{ (isset($selectedShopId) && $selectedShopId == $shop['id']) ? 'selected' : '' }}>
                                        🏪 {{ $shop['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    @endif
                </div>

              

                {{-- ══ TABLE PANEL ══ --}}
                <div class="panel au au5">
                    <div class="panel-head">
                        <div class="panel-head-left">
                            <div class="panel-head-icon"><i class="bi bi-table"></i></div>
                            <span class="panel-title">Shops</span>
                            <span class="result-pill" id="tableCount">{{ count($fetch) }}</span>
                        </div>
                        <div class="panel-tools">
                            <div class="dropdown">
                                <a class="tool-btn" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="window.print()">
                                            <i class="bi bi-printer"></i> Print
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="downloadReport()">
                                            <i class="bi bi-download"></i> Export
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if($fetch->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h4>No suppliers found</h4>
                        <p>Get started by adding your first supplier to the system</p>
                        <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                            <i class="bi bi-plus-lg"></i> Add Supplier
                        </button>
                    </div>
                    @else
                    <div class="tbl-wrap">
                        <table id="vendorTable">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Shop Name</th>
                                    <th>Contact</th>
                                    <th>Type</th>
                                    <th>Credit (TSh)</th>
                                    <th width="15%" style="text-align:center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="vendorBody">
                                @foreach ($fetch as $index => $vendor)
                                @php
                                    $initials = strtoupper(substr($vendor->name, 0, 1));
                                    $parts    = explode(' ', trim($vendor->name));
                                    if (count($parts) > 1) $initials .= strtoupper(substr($parts[1], 0, 1));
                                    $type = $vendor->businessType ?? '';
                                    $typeCls = match($type) {
                                        'Wholesale'    => 'tb-wholesale',
                                        'Manufacturer' => 'tb-manufacturer',
                                        'Distributor'  => 'tb-distributor',
                                        'Retailer'     => 'tb-retailer',
                                        default        => 'tb-default',
                                    };
                                    $typeIcon = match($type) {
                                        'Wholesale'    => '� wholesale',
                                        'Manufacturer' => '🏭',
                                        'Distributor'  => '🚚',
                                        'Retailer'     => '🛍️',
                                        default        => '📦',
                                    };
                                @endphp
                                <tr data-search="{{ strtolower($vendor->name . ' ' . $vendor->contact . ' ' . $vendor->businessType . ' ' . $vendor->location) }}">
                                    <td data-label="#" class="idx">{{ $index + 1 }}</td>
                                    <td data-label="Supplier">
                                        <div class="vendor-cell">
                                            <div class="vendor-avatar">{{ $initials }}</div>
                                            <div>
                                                <div class="vendor-name">{{ $vendor->name }}</div>
                                                <div class="vendor-loc">
                                                    <i class="bi bi-geo-alt-fill"></i> {{ $vendor->location }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Contact">
                                        <div class="contact-val">
                                            <i class="bi bi-telephone-fill" style="font-size: 11px;"></i> {{ $vendor->contact }}
                                        </div>
                                    </td>
                                    <td data-label="Type">
                                        <span class="type-badge {{ $typeCls }}">
                                            <i class="bi bi-tag-fill"></i> {{ $type }}
                                        </span>
                                    </td>
                                    <td data-label="Credit" class="{{ $vendor->credit > 0 ? 'credit-pos' : 'credit-nil' }}">
                                        {{ number_format($vendor->credit) }}
                                    </td>
                                    <td data-label="Actions">
                                        <div class="action-btns">
                                            <form action="" method="post" style="display:contents;">
                                                @csrf
                                                <button formaction="viewVendor" class="act-btn ab-view"
                                                        name="vendorId" value="{{ $vendor->id }}" title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                @if (canUser('manage_suppliers'))

                                                <button type="button" class="act-btn ab-edit"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editVendor{{ $vendor->id }}" title="Edit Supplier">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button formaction="dltVendeor" class="act-btn ab-delete"
                                                        name="product_id" value="{{ $vendor->id }}"
                                                        onclick="return confirm('⚠️ Delete this supplier? This action cannot be undone.')"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                @endif
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Edit Modal for each vendor --}}
                                <div class="modal fade" id="editVendor{{ $vendor->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="modal-title">
                                                    <i class="bi bi-pencil-fill"></i>
                                                    <h5>Edit Shop</h5>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="updateVendor" method="post">
                                                    @csrf
                                                    <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">

                                                    <div class="field-row">
                                                        <div class="field">
                                                            <label class="field-label">Name <span class="req">*</span></label>
                                                            <input type="text" class="field-input" name="name" value="{{ $vendor->name }}" required>
                                                        </div>
                                                        <div class="field">
                                                            <label class="field-label">Contact <span class="req">*</span></label>
                                                            <input type="text" class="field-input" name="contact" value="{{ $vendor->contact }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="field">
                                                        <label class="field-label">Address <span class="req">*</span></label>
                                                        <input type="text" class="field-input" name="address" value="{{ $vendor->location }}" required>
                                                    </div>

                                                    <div class="field-row">
                                                        <div class="field">
                                                            <label class="field-label">Business Type <span class="req">*</span></label>
                                                            <select name="type" class="field-input" required>
                                                                <option value="Wholesale"    {{ $vendor->businessType == 'Wholesale'    ? 'selected' : '' }}>Wholesale</option>
                                                                <option value="Manufacturer" {{ $vendor->businessType == 'Manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                                                                <option value="Distributor"  {{ $vendor->businessType == 'Distributor'  ? 'selected' : '' }}>Distributor</option>
                                                                <option value="Retailer"     {{ $vendor->businessType == 'Retailer'     ? 'selected' : '' }}>Retailer</option>
                                                            </select>
                                                        </div>
                                                        <div class="field">
                                                            <label class="field-label">Bank Name</label>
                                                            <input type="text" class="field-input" name="bank" value="{{ $vendor->bank }}" placeholder="Optional">
                                                        </div>
                                                    </div>

                                                    <div class="field">
                                                        <label class="field-label">Account Number</label>
                                                        <input type="text" class="field-input" name="account" value="{{ $vendor->account }}" placeholder="Optional">
                                                    </div>

                                                    <div class="field">
                                                        <label class="field-label">Description</label>
                                                        <textarea name="description" class="field-input" placeholder="Additional information about this supplier">{{ $vendor->description }}</textarea>
                                                    </div>

                                                    <button type="submit" class="btn-submit">
                                                        <i class="bi bi-check-circle-fill"></i> Update Supplier
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="panel-footer">
                        <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                            <i class="bi bi-plus-lg"></i> Add New Shop
                        </button>
                    </div>
                    @endif
                </div>

            </div>
        </main>

{{-- ══════════════════════════════════════
     ADD SUPPLIER MODAL
══════════════════════════════════════ --}}
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="bi bi-person-plus-fill"></i>
                    <h5>Add New Supplier</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="newVendor" method="post" id="addSupplierForm">
                    @csrf

                    <div class="modal-section-label">
                        <i class="bi bi-info-circle-fill"></i> Basic Information
                    </div>
                    <div class="field-row">
                        <div class="field">
                            <label class="field-label">Supplier name <span class="req">*</span></label>
                            <input type="text" class="field-input" name="name" placeholder="e.g. Kariakoo Traders" required>
                        </div>
                        <div class="field">
                            <label class="field-label">Contact <span class="req">*</span></label>
                            <input type="text" class="field-input" name="contact" placeholder="Phone number or email" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">Business address <span class="req">*</span></label>
                        <input type="text" class="field-input" name="address" placeholder="Physical location" required>
                    </div>

                    <div class="field">
                        <label class="field-label">Business type <span class="req">*</span></label>
                        <select name="type" class="field-input" required>
                            <option value="" disabled selected>Select business type</option>
                            <option value="Wholesale">� wholesale</option>
                            <option value="Manufacturer">🏭 Manufacturer</option>
                            <option value="Distributor">🚚 Distributor</option>
                            <option value="Retailer">🛍️ Retailer</option>
                        </select>
                    </div>

                    <hr class="modal-divider">
                    
                    <div class="modal-section-label">
                        <i class="bi bi-bank2"></i> Banking Information (Optional)
                    </div>

                    <div class="field-row">
                        <div class="field">
                            <label class="field-label">Bank name</label>
                            <input type="text" class="field-input" name="bank" placeholder="e.g. CRDB, NMB, NBC">
                        </div>
                        <div class="field">
                            <label class="field-label">Account number</label>
                            <input type="text" class="field-input" name="account" placeholder="Bank account number">
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">Description</label>
                        <textarea name="description" class="field-input" placeholder="Additional notes, payment terms, or special requirements..."></textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="bi bi-save-fill"></i> Save Supplier
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-submit when shop filter changes
    const shopFilter = document.getElementById('shopFilter');
    if (shopFilter) {
        shopFilter.addEventListener('change', function () {
            this.form.submit();
        });
    }

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearBtn');
    const rows = document.querySelectorAll('#vendorBody tr[data-search]');
    const countEl = document.getElementById('searchCount');
    const tableCountEl = document.getElementById('tableCount');

    function updateCount(visible) {
        const text = visible + ' result' + (visible !== 1 ? 's' : '');
        if (countEl) countEl.textContent = text;
        if (tableCountEl) tableCountEl.textContent = visible;
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            let visible = 0;
            rows.forEach(row => {
                const match = !query || row.dataset.search.includes(query);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            updateCount(visible);
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            if (searchInput) {
                searchInput.value = '';
                const event = new Event('input');
                searchInput.dispatchEvent(event);
                searchInput.focus();
            }
            if (shopFilter && shopFilter.value !== '') {
                shopFilter.value = '';
                shopFilter.form.submit();
            }
        });
    }


    // Initialize tooltips if any
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide modals on form submit (optional)
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                setTimeout(() => {
                    const modals = document.querySelectorAll('.modal.show');
                    modals.forEach(modal => {
                        const bsModal = bootstrap.Modal.getInstance(modal);
                        if (bsModal) bsModal.hide();
                    });
                }, 100);
            });
        });
    });
</script>

@if(session('success'))
<script>
    // Show success notification if needed
    alert('{{ session('success') }}');
</script>
@endif

@if($errors->any())
<script>
    // Show error notification if needed
    alert('{{ $errors->first() }}');
</script>
@endif
@include('footer')

</body>
</html>