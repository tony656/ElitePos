<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@if(isset($supplier)){{ $supplier->name }} - @endif{{ config("app.name") }} — Main Store Credit Management</title>
    @include("links")
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --navy:         #084b87;
            --navy-mid:     #0756a6;
            --navy-light:   #0b66b2;
            --amber:        #0b65b1;
            --amber-pale:   #e8f2fc;
            --emerald:      #07866b;
            --emerald-pale: #e5f7f1;
            --rose:         #e5485d;
            --rose-pale:    #fff0f1;
            --violet:       #7844d6;
            --violet-pale:  #f1ebff;
            --sky:          #0874c5;
            --sky-pale:     #e8f3ff;
            --fuchsia:      #c026a8;
            --fuchsia-pale: #fceafa;
            --slate-50:     #f5f9fd;
            --slate-100:    #edf3f9;
            --slate-200:    #dce7f1;
            --slate-300:    #c5d5e5;
            --slate-400:    #8da4bb;
            --slate-500:    #607b96;
            --slate-600:    #45627e;
            --slate-700:    #2d4c69;
            --slate-800:    #183a5a;
            --white:        #ffffff;
            --font:         'DM Sans', system-ui, sans-serif;
            --radius:       8px;
            --radius-sm:    6px;
            --shadow-sm:    0 2px 8px rgba(8, 61, 108, .06);
            --shadow-md:    0 5px 16px rgba(8, 61, 108, .09);
            --shadow-lg:    0 10px 28px rgba(8, 61, 108, .13);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font);
            background: #f3f7fb;
            color: var(--slate-800);
            min-height: 100vh;
            font-size: 14px;
            line-height: 1.6;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        .wrap { padding: 1.1rem 1.25rem 1.5rem; max-width: 1800px; margin: 0 auto; }

        /* ── Page header ── */
        .pg-header {
            background: transparent;
            border: 0;
            border-radius: 0;
            padding: .25rem 0 .9rem;
            margin-bottom: .55rem;
            box-shadow: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            position: relative;
            overflow: visible;
        }
        .pg-header::before, .pg-header::after { display: none; }
        .pg-left { display: flex; align-items: center; gap: 10px; position: relative; z-index: 1; }
        .header-icon {
            width: 30px; height: 30px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem; background: rgba(8,75,135,0.08); color: var(--navy-light);
            flex-shrink: 0;
        }
        .pg-title-text h1 { font-size: 1.42rem; font-weight: 700; color: #064b86; letter-spacing: -0.35px; margin: 0; display: flex; align-items: center; gap: 0.35rem; }
        .pg-title-text h1 span { color: #0871c2; }
        .pg-title-text p  { font-size: .78rem; color: #647f99; font-weight: 400; letter-spacing: 0.01em; margin-top: 1px; }
        .pg-right { display: flex; gap: 8px; align-items: center; position: relative; z-index: 1; flex-wrap: wrap; }

        /* ── Detail header ── */
        .detail-header {
            background: transparent;
            border: 0;
            border-radius: 0;
            padding: .25rem 0 .9rem;
            margin-bottom: .55rem;
            box-shadow: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            position: relative;
            overflow: visible;
        }
        .detail-header-left { display: flex; align-items: center; gap: 10px; }
        .detail-header-icon {
            width: 30px; height: 30px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem; background: rgba(8,75,135,0.08); color: var(--navy-light);
            flex-shrink: 0;
        }
        .detail-header h3 { font-size: 1.42rem; font-weight: 700; color: #064b86; letter-spacing: -0.35px; margin: 0; display: flex; align-items: center; gap: 0.35rem; }
        .detail-header p  { font-size: .78rem; color: #647f99; font-weight: 400; letter-spacing: 0.01em; margin-top: 1px; }

        /* ── Buttons ── */
        .btn-header {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: .58rem 1rem;
            font-size: .78rem;
            font-weight: 600;
            background: #0866b2;
            color: #fff;
            border: 1px solid #0866b2;
            border-radius: var(--radius);
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 3px 8px rgba(8, 102, 178, .18);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: 0.01em;
        }
        .btn-header:hover { background: #064f91; border-color: #064f91; color: #fff; transform: translateY(-1px); }
        .btn-header:active { transform: translateY(0); }
        .btn-header-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: .58rem 1rem;
            font-size: .78rem;
            font-weight: 600;
            background: transparent;
            color: var(--slate-600);
            border: 1px solid #d4e1ed;
            border-radius: var(--radius);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: 0.01em;
        }
        .btn-header-outline:hover { background: #eff6ff; border-color: #0b68b4; color: #0756a6; }

        /* ── Alerts ── */
        .alert {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .7rem .9rem;
            border-radius: var(--radius);
            margin-bottom: .85rem;
            font-size: .78rem;
            font-weight: 500;
        }
        .alert-success { background: var(--emerald-pale); color: #056f58; border-left: 3px solid var(--emerald); }
        .alert-danger  { background: var(--rose-pale);    color: #c9314a; border-left: 3px solid var(--rose); }
        .close-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: inherit;
            font-size: 1.05rem;
            opacity: 0.6;
            line-height: 1;
            font-family: inherit;
        }
        .close-btn:hover { opacity: 1; }

        /* ── Toolbar / Filters ── */
        .filters-card {
            background: var(--white);
            border: 1px solid #dce7f1;
            border-radius: var(--radius-sm);
            box-shadow: 0 2px 7px rgba(8, 61, 108, .045);
            padding: .8rem .95rem;
            margin-bottom: .8rem;
        }
        .filters-row {
            display: flex;
            gap: .7rem;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        .filter-group { flex: 1; min-width: 140px; }
        .filter-label {
            display: block;
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--slate-500);
            margin-bottom: .4rem;
        }
        .filter-ctrl {
            width: 100%;
            font-family: var(--font);
            font-size: .78rem;
            padding: .58rem .8rem .58rem 2.35rem;
            border: 1px solid #d4e1ed;
            border-radius: var(--radius);
            background: #fff;
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            font-family: inherit;
        }
        .filter-ctrl:focus {
            border-color: #0b68b4;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(11,104,180,.09);
        }
        select.filter-ctrl {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%238da4bb' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3E/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            padding-right: 2.25rem;
            cursor: pointer;
        }
        .filter-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            padding-bottom: 1px;
            flex-shrink: 0;
        }
        .btn-filter {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: .55rem .9rem;
            border: none;
            border-radius: var(--radius);
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            letter-spacing: 0.01em;
            background: #0866b2;
            color: #fff;
        }
        .btn-filter:hover:not(:disabled) { background: #064f91; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(8,102,178,.25); }
        .btn-reset {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: .55rem .9rem;
            border-radius: var(--radius);
            border: 1px solid #d4e1ed;
            background: transparent;
            font-family: inherit;
            font-size: .78rem;
            color: var(--slate-600);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-reset:hover { background: #eff6ff; border-color: #0b68b4; color: #0756a6; }

        /* ── Metrics ── */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .7rem;
            margin-bottom: .85rem;
        }
        @media (max-width: 900px) { .metrics-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 480px) { .metrics-grid { grid-template-columns: 1fr; } }

        .metric-card {
            background: var(--white);
            border: 1px solid #dce8f3;
            border-radius: var(--radius);
            padding: .8rem .95rem;
            box-shadow: none;
            animation: none;
        }
        .metric-card::before { display: none; }
        .metric-card::after { width: 90px; height: 90px; opacity: .25; }
        .metric-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .metric-inner { display: flex; align-items: center; gap: .8rem; }
        .metric-icon {
            width: 30px; height: 30px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem; flex-shrink: 0;
        }
        .mi-emerald { background: var(--emerald-pale); color: var(--emerald); }
        .mi-navy    { background: rgba(8,75,135,0.08); color: var(--navy-light); }
        .mi-amber   { background: var(--amber-pale); color: var(--amber); }
        .mi-violet  { background: var(--violet-pale); color: var(--violet); }
        .mi-rose    { background: var(--rose-pale); color: var(--rose); }
        .mi-blue    { background: var(--sky-pale); color: var(--sky); }
        .mc-emerald { --mc: var(--emerald); }
        .mc-navy    { --mc: var(--navy); }
        .mc-amber   { --mc: var(--amber); }
        .mc-violet  { --mc: var(--violet); }
        .mc-rose    { --mc: var(--rose); }
        .mc-blue    { --mc: var(--sky); }
        .metric-label {
            font-size: .68rem;
            font-weight: 700;
            color: #59748f;
            text-transform: none;
            letter-spacing: 0;
            margin-bottom: .18rem;
        }
        .metric-value {
            font-family: var(--font);
            font-size: 1.45rem;
            color: #084b87;
            font-weight: 700;
            line-height: 1.12;
            letter-spacing: -.35px;
        }
        .metric-sub {
            font-size: 10px;
            color: var(--slate-400);
            margin-top: 2px;
        }

        /* ── Panel / Table ── */
        .panel {
            background: var(--white);
            border: 1px solid #dce7f1;
            border-radius: var(--radius-sm);
            box-shadow: 0 2px 7px rgba(8, 61, 108, .045);
            overflow: hidden;
        }
        .panel-head {
            padding: .75rem .9rem;
            background: #fff;
            border-bottom: 1px solid #dce7f1;
        }
        .panel-head-left { display: flex; align-items: center; gap: .6rem; }
        .panel-head-icon {
            width: 30px; height: 30px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem; background: rgba(8,75,135,0.08); color: var(--navy-light);
            flex-shrink: 0;
        }
        .panel-title { font-size: .96rem; font-weight: 700; color: #084b87; margin: 0; }
        .panel-title::before {
            content: '';
            width: 3px; height: 17px;
            background: #0b68b4;
            border-radius: 2px;
            display: inline-block;
            margin-right: .4rem;
        }
        .shop-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .72rem;
            font-weight: 600;
            padding: .22rem .55rem;
            background: #edf3f9;
            color: var(--slate-500);
            border-radius: 20px;
            margin-left: auto;
        }

        /* Tabs */
        .tabs-wrap {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            align-items: center;
            padding: .5rem;
            background: #fff;
            border: 1px solid #dce7f1;
            border-radius: var(--radius-sm);
        }
        .tab-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: .45rem .85rem;
            border-radius: var(--radius);
            border: 1px solid #d4e1ed;
            background: transparent;
            font-size: .78rem;
            color: var(--slate-600);
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            text-decoration: none;
            letter-spacing: 0.01em;
        }
        .tab-link:hover { background: #eff6ff; border-color: #0b68b4; color: #0756a6; }
        .tab-link.active {
            background: #0866b2;
            border-color: #0866b2;
            color: #fff;
        }

        /* Table */
        .tbl-wrap {
            overflow-x: auto;
            border-radius: var(--radius-sm);
            border: 1px solid #e5edf5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: .76rem;
            min-width: 1000px;
        }
        thead th {
            background: #07569b;
            color: #fff;
            font-size: .66rem;
            font-weight: 600;
            letter-spacing: .015em;
            padding: .62rem .68rem;
            border: 1px solid rgba(255,255,255,.13);
            white-space: nowrap;
            text-align: left;
        }
        thead th:last-child { text-align: right; }
        tbody td {
            padding: .52rem .68rem;
            border: 1px solid #e5edf5;
            color: #244866;
            font-size: .74rem;
            vertical-align: middle;
        }
        tbody tr:nth-child(even) td { background: #f8fbfe; }
        tbody tr:hover td { background: #edf6ff; }
        td.text-right { text-align: right; }

        .sup-cell { display: flex; align-items: center; gap: .6rem; }
        .sup-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            flex-shrink: 0;
            background: var(--navy-light);
            color: var(--white);
            display: flex; align-items: center; justify-content: center;
            font-size: .78rem; font-weight: 700;
        }
        .sup-name { font-weight: 600; color: #164a78; font-size: .76rem; }

        .num { font-variant-numeric: tabular-nums; font-weight: 500; }
        .num.fw6 { font-weight: 600; }

        .inv-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: .22rem .55rem;
            border-radius: 20px;
            background: var(--emerald-pale);
            color: var(--emerald);
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .03em;
        }

        .amount-val { font-weight: 700; color: var(--navy); font-variant-numeric: tabular-nums; font-size: .74rem; }
        .credit-val { color: var(--rose); }
        .paid-val   { color: var(--emerald); }

        .badge-paid   { background: var(--emerald-pale); color: var(--emerald); padding: .22rem .55rem; border-radius: 20px; font-size: .68rem; font-weight: 700; letter-spacing: .03em; }
        .badge-credit { background: var(--rose-pale);    color: var(--rose);    padding: .22rem .55rem; border-radius: 20px; font-size: .68rem; font-weight: 700; letter-spacing: .03em; }

        .txt-green { color: var(--emerald); font-weight: 600; }
        .txt-red   { color: var(--rose); font-weight: 600; }
        .txt-muted { color: var(--slate-500); }

        /* Action buttons */
        .action-cell { display: flex; gap: .28rem; justify-content: center; flex-wrap: wrap; }
        .act-btn {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .55rem .9rem;
            border-radius: var(--radius);
            border: 1.5px solid;
            background: transparent;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            text-decoration: none;
            white-space: nowrap;
        }
        .ab-pay  { border-color: var(--emerald); color: var(--emerald); }
        .ab-pay:hover  { background: var(--emerald); color: var(--white); }
        .ab-view { border-color: var(--navy-light); color: var(--navy-light); }
        .ab-view:hover { background: var(--navy); color: var(--white); }
        .ab-del  { border-color: var(--rose); color: var(--rose); }
        .ab-del:hover  { background: var(--rose); color: var(--white); }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 2.5rem 1.5rem;
            border: 1px dashed #c5d5e5;
            border-radius: var(--radius-sm);
            background: #f8fbfe;
            margin: 1rem;
        }
        .empty-icon { font-size: 2.2rem; margin-bottom: .6rem; opacity: .35; color: var(--slate-400); }
        .empty-state h4 { font-size: .9rem; font-weight: 600; color: var(--slate-600); margin-bottom: 4px; }
        .empty-state p  { font-size: .78rem; color: var(--slate-400); }

        /* ── Manual credit form ── */
        .manual-credit-card {
            background: var(--white);
            border: 1px solid #dce7f1;
            border-radius: var(--radius-sm);
            box-shadow: 0 2px 7px rgba(8, 61, 108, .045);
            margin-bottom: .8rem;
            overflow: hidden;
        }
        .manual-credit-head {
            padding: .75rem .9rem;
            background: #fff;
            border-bottom: 1px solid #dce7f1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .6rem;
        }
        .manual-credit-head-left { display: flex; align-items: center; gap: .6rem; }
        .manual-credit-head-icon {
            width: 30px; height: 30px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem; background: rgba(8,75,135,0.08); color: var(--navy-light);
            flex-shrink: 0;
        }
        .manual-credit-title { font-size: .96rem; font-weight: 700; color: #084b87; margin: 0; }

        .field { margin-bottom: 1rem; }
        .field:last-child { margin-bottom: 0; }
        .field-label {
            display: block;
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--slate-500);
            margin-bottom: .4rem;
        }
        .field-label .req { color: var(--rose); }
        .field-input {
            width: 100%;
            padding: .58rem .8rem .58rem 2.35rem;
            border: 1px solid #d4e1ed;
            border-radius: var(--radius);
            font-size: .78rem;
            color: var(--slate-800);
            background: #fff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            font-family: inherit;
        }
        .field-input:focus {
            border-color: #0b68b4;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(11,104,180,.09);
        }
        .field-input::placeholder { color: var(--slate-400); }
        .field-input.readonly { background: #f8fbfe; color: var(--slate-500); }
        select.field-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%238da4bb' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3E/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            padding-right: 2.25rem;
            cursor: pointer;
        }

        .row-cols { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: .75rem; }

        /* ── Modal ── */
        .modal-content {
            border: none;
            border-radius: var(--radius-sm);
            overflow: hidden;
            box-shadow: 0 10px 28px rgba(8, 61, 108, .13);
        }
        .modal-header {
            background: linear-gradient(120deg, #064b86, #0870bd);
            color: #fff;
            border-bottom: none;
            padding: .75rem .9rem;
        }
        .modal-header .modal-title { font-size: .96rem; font-weight: 700; color: #fff; margin: 0; }
        .modal-header .btn-close { filter: invert(1); opacity: .7; }
        .modal-header .btn-close:hover { opacity: 1; }
        .modal-body { padding: .9rem; font-size: .78rem; }
        .modal-footer { border-top: 1px solid #dce7f1; padding: .75rem .9rem; background: #fff; }

        .modal-field { margin-bottom: 1rem; }
        .modal-field:last-child { margin-bottom: 0; }
        .modal-field label {
            display: block;
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--slate-500);
            margin-bottom: .4rem;
        }
        .modal-field input {
            width: 100%;
            padding: .58rem .8rem .58rem 2.35rem;
            border: 1px solid #d4e1ed;
            border-radius: var(--radius);
            font-size: .78rem;
            color: var(--slate-800);
            background: #fff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            font-family: inherit;
        }
        .modal-field input:focus {
            border-color: #0b68b4;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(11,104,180,.09);
        }
        .modal-field input[readonly] { background: #f8fbfe; color: var(--slate-500); cursor: default; }
        .modal-field select {
            width: 100%;
            padding: .58rem .8rem .58rem 2.35rem;
            border: 1px solid #d4e1ed;
            border-radius: var(--radius);
            font-size: .78rem;
            color: var(--slate-800);
            background: #fff;
            font-family: inherit;
        }

        .modal-remaining-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .55rem .85rem;
            border-radius: var(--radius);
            background: var(--rose-pale);
            color: var(--rose);
            font-size: .72rem;
            font-weight: 600;
            width: 100%;
        }
        .modal-remaining-badge i { font-size: .75rem; }

        .btn-modal-cancel {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .55rem .9rem;
            border-radius: var(--radius);
            border: 1px solid #d4e1ed;
            background: transparent;
            font-size: .78rem;
            color: var(--slate-600);
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            letter-spacing: 0.01em;
        }
        .btn-modal-cancel:hover { background: #eff6ff; border-color: #0b68b4; color: #0756a6; }
        .btn-modal-pay {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .55rem .9rem;
            border-radius: var(--radius);
            border: none;
            background: var(--emerald);
            color: #fff;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            letter-spacing: 0.01em;
        }
        .btn-modal-pay:hover { background: #056f58; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(7,134,107,.28); }

        /* Item breakdown */
        .item-breakdown td { background: #f8fbfe; border: none; }
        .breakdown-inner { padding: .75rem 1rem; }
        .breakdown-title {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--slate-500);
            margin-bottom: .5rem;
            display: block;
        }
        .breakdown-table { font-size: .72rem; margin-top: .25rem; }
        .breakdown-table th {
            background: #07569b;
            color: #fff;
            font-size: .64rem;
            font-weight: 600;
            padding: .5rem .65rem;
            text-align: left;
        }
        .breakdown-table td { padding: .45rem .65rem; border-bottom: 1px solid #e5edf5; }

        @media (max-width: 768px) {
            .wrap { padding: .8rem; }
            .pg-header { padding: .25rem 0 .7rem; }
            .filters-row { flex-direction: column; align-items: stretch; }
            .filter-actions { width: 100%; justify-content: flex-end; }
            .metrics-grid { grid-template-columns: 1fr; }
            table { min-width: 800px; }
        }
    </style>
</head>
<body>
    @include("sidenav")

    <main class="main-content">
        {{-- ── Alerts ── --}}
        @if(session('success'))
        <div class="alert alert-success">
            <span>{{ session('success') }}</span>
            <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">
            <span>{{ session('error') }}</span>
            <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
        </div>
        @endif

        <div class="wrap">

        @if(isset($supplier))
        {{-- ==================== SUPPLIER DETAIL VIEW ==================== --}}
        {{-- Detail header --}}
        <div class="detail-header">
            <div class="detail-header-left">
                <div class="detail-header-icon"><i class="bi bi-person-fill"></i></div>
                <div>
                    <h3>{{ $supplier->name }}</h3>
                    <p>Supplier ID: {{ $supplier->id }} | Total Orders: {{ count($groupedDetails) }}</p>
                </div>
            </div>
            <a href="{{ url('main-credit') }}?{{ http_build_query(request()->except('supplierId')) }}" class="btn-header-outline">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        {{-- Metrics --}}
        <div class="metrics-grid">
            <div class="metric-card mc-navy">
                <div class="metric-inner">
                    <div class="metric-icon mi-navy"><i class="bi bi-calendar3"></i></div>
                    <div><div class="metric-label">Days</div><div class="metric-value">{{ count($groupedDetails) }}</div></div>
                </div>
            </div>
            <div class="metric-card mc-rose">
                <div class="metric-inner">
                    <div class="metric-icon mi-rose"><i class="bi bi-exclamation-circle"></i></div>
                    <div><div class="metric-label">Total Debt</div><div class="metric-value">{{ number_format($totalDebt) }}</div></div>
                </div>
            </div>
            <div class="metric-card mc-emerald">
                <div class="metric-inner">
                    <div class="metric-icon mi-emerald"><i class="bi bi-cash-stack"></i></div>
                    <div><div class="metric-label">Total Paid</div><div class="metric-value" style="font-size:16px;">{{ number_format($totalPaidDetail) }}</div></div>
                </div>
            </div>
            <div class="metric-card mc-amber">
                <div class="metric-inner">
                    <div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div>
                    <div><div class="metric-label">Remaining</div><div class="metric-value" style="font-size:16px;">{{ number_format($totalDebt - $totalPaidDetail) }}</div></div>
                </div>
            </div>
        </div>

        {{-- Debt details table --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-left">
                    <div class="panel-head-icon"><i class="bi bi-table"></i></div>
                    <span class="panel-title">Credit Records</span>
                    <span class="shop-pill">{{ count($groupedDetails) }} days</span>
                </div>
            </div>

            <div class="tbl-wrap">
                @if(empty($groupedDetails))
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                    <h4>No credit records found</h4>
                    <p>This supplier has no credit orders</p>
                </div>
                @else
                <table>
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Paid</th>
                            <th>Remaining</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $rowIndex = 1; @endphp
                        @foreach ($groupedDetails as $group)
                        @php
                            $isFullyPaid = $group['remaining'] <= 0;
                        @endphp
                        <tr>
                            <td class="idx-cell">{{ $rowIndex++ }}</td>
                            <td><span class="mono-val">{{ $group['date'] }}</span></td>
                            <td><span class="amount-val credit-val">{{ number_format($group['total']) }}</span></td>
                            <td><span class="amount-val paid-val">{{ number_format($group['paid']) }}</span></td>
                            <td>
                                <span class="amount-val {{ $group['remaining'] > 0 ? 'credit-val' : 'paid-val' }}">{{ number_format($group['remaining']) }}</span>
                            </td>
                            <td>
                                <div class="action-cell">
                                    @if(!$isFullyPaid && count($group['items']) > 0)
                                    @php
                                        $groupReceivingIds = implode(',', array_map(fn ($item) => $item['receiving']->receivingId, $group['items']));
                                        $firstReceiving = $group['items'][0]['receiving'];
                                    @endphp
                                    <form method="POST" action="{{ url('process-debt-payment') }}" class="inline-form" style="display:inline-flex;" onsubmit="event.preventDefault(); openPaymentModal('{{ $groupReceivingIds }}', '{{ $supplier->id }}', '{{ $group['remaining'] }}');">
                                        @csrf
                                        <input type="hidden" name="receivingId" value="{{ $groupReceivingIds }}" id="pay-receiving-{{ $firstReceiving->receivingId }}">
                                        <input type="hidden" name="supplierId" value="{{ $supplier->id }}">
                                        <input type="hidden" name="shop" value="{{ $selectedShopId }}">
                                        <button type="submit" class="act-btn ab-pay" title="Pay">
                                            <i class="bi bi-cash-coin"></i> Pay
                                        </button>
                                    </form>
                                    @foreach($group['items'] as $mItem)
                                        @php $mRec = $mItem['receiving']; @endphp
                                        @if(str_starts_with($mRec->receivingId, 'MC-'))
                                        <form method="POST" action="{{ url('delete-manual-credit') }}" style="display:inline;" onsubmit="return confirm('Delete this manual credit?');">
                                            @csrf
                                            <input type="hidden" name="receiving_id" value="{{ $mRec->receivingId }}">
                                            <button type="submit" class="act-btn ab-del" style="padding:.4rem .7rem;font-size:.66rem;" title="Delete"><i class="bi bi-trash-fill"></i></button>
                                        </form>
                                        <button type="button" class="act-btn ab-view" style="padding:.4rem .7rem;font-size:.66rem;" title="Edit" data-id="{{ $mRec->receivingId }}" data-desc="{{ $mRec->receivingName }}" data-amt="{{ $mRec->price }}" data-date="{{ substr($mRec->created_at, 0, 10) }}" onclick="openManualEditModal(this)"><i class="bi bi-pencil-fill"></i></button>
                                        @endif
                                    @endforeach
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Expandable item breakdown --}}
                        <tr class="item-breakdown">
                            <td colspan="6" style="padding:0;">
                                <div class="breakdown-inner">
                                    <span class="breakdown-title">Items for {{ $group['date'] }}</span>
                                    <table class="breakdown-table">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th width="10%">Quantity</th>
                                                <th width="12%">Unit Price</th>
                                                <th width="12%">Line Total</th>
                                                <th width="12%">Paid</th>
                                                <th width="12%">Remaining</th>
                                                <th width="10%" style="text-align:right;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($group['items'] as $item)
                                            @php
                                                $itemReceiving = $item['receiving'];
                                                $itemPaid = $item['paid'];
                                                $itemRemaining = $item['remaining'];
                                                $itemLineTotal = $itemReceiving->price * $itemReceiving->quantity;
                                            @endphp
                                            <tr>
                                                <td>{{ str_starts_with($itemReceiving->receivingId, 'MC-') ? ($itemReceiving->receivingName ?: 'Manual Credit') : (\App\Models\productsModel::where('product_id', $itemReceiving->productId)->value('name01') ?: $itemReceiving->productId) }}</td>
                                                <td>{{ number_format($itemReceiving->quantity) }}</td>
                                                <td>{{ number_format($itemReceiving->price, 2) }}</td>
                                                <td>{{ number_format($itemLineTotal) }}</td>
                                                <td>{{ number_format($itemPaid) }}</td>
                                                <td>{{ number_format($itemRemaining) }}</td>
                                                <td style="white-space:nowrap; text-align:right;">
                                                    @if(str_starts_with($itemReceiving->receivingId, 'MC-'))
                                                    <div style="display:flex;gap:.2rem;align-items:center;justify-content:flex-end;">
                                                        <form method="POST" action="{{ url('delete-manual-credit') }}" style="display:inline;" onsubmit="return confirm('Delete this manual credit?');">
                                                            @csrf
                                                            <input type="hidden" name="receiving_id" value="{{ $itemReceiving->receivingId }}">
                                                            <button type="submit" class="act-btn ab-del" style="padding:.35rem .65rem;font-size:.62rem;" title="Delete"><i class="bi bi-trash-fill"></i></button>
                                                        </form>
                                                        <button type="button" class="act-btn ab-view" style="padding:.35rem .65rem;font-size:.62rem;margin-left:.15rem;" title="Edit" data-id="{{ $itemReceiving->receivingId }}" data-desc="{{ $itemReceiving->receivingName }}" data-amt="{{ $itemReceiving->price }}" data-date="{{ substr($itemReceiving->created_at, 0, 10) }}" onclick="openManualEditModal(this)"><i class="bi bi-pencil-fill"></i></button>
                                                    </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        @else
        {{-- ==================== SUPPLIER LIST VIEW ==================== --}}
        {{-- Page header --}}
        <div class="pg-header">
            <div class="pg-left">
                <div class="header-icon"><i class="bi bi-people-fill"></i></div>
                <div class="pg-title-text">
                    <h1>Main Store Credit Management</h1>
                    <p>Track and manage outstanding supplier credits</p>
                </div>
            </div>
            <div class="pg-right">
                <button class="btn-header" onclick="window.print()">
                    <i class="bi bi-printer"></i> Print
                </button>
                <button class="btn-header" onclick="toggleManualCredit()">
                    <i class="bi bi-plus-circle"></i> Create Manual Credit
                </button>
            </div>
        </div>

        {{-- Manual Credit Form --}}
        <div id="manualCreditCard" class="manual-credit-card" style="display:none;">
            <div class="manual-credit-head">
                <div class="manual-credit-head-left">
                    <div class="manual-credit-head-icon"><i class="bi bi-plus-circle"></i></div>
                    <h5 class="manual-credit-title">Create Manual Credit</h5>
                </div>
                <button type="button" class="btn-header-outline" style="background:var(--rose-pale); color:var(--rose); border-color:var(--rose-pale);" onclick="toggleManualCredit()">
                    <i class="bi bi-x-circle"></i> Close
                </button>
            </div>
            <form method="POST" action="{{ url('create-manual-credit') }}">
                @csrf
                <div style="padding:.95rem 1rem;">
                    <div class="row-cols">
                        <div class="field">
                            <label class="field-label">Supplier <span class="req">*</span></label>
                            <select name="supplier" class="field-input" required>
                                <option value="">Select Supplier</option>
                                @foreach($allSuppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label class="field-label">Credit Amount <span class="req">*</span></label>
                            <input type="number" name="amount" class="field-input" step="0.01" min="0" placeholder="Enter amount" required>
                        </div>
                        <div class="field">
                            <label class="field-label">Date</label>
                            <input type="date" name="date" class="field-input" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="field">
                            <label class="field-label">Description</label>
                            <input type="text" name="description" class="field-input" value="Manual Credit" placeholder="e.g. Opening balance, adjustment">
                        </div>
                    </div>
                    <input type="hidden" name="shop" value="{{ $selectedShopId }}">
                    <div style="display:flex; justify-content:flex-end; gap:.5rem; margin-top:1rem;">
                        <button type="button" class="btn-modal-cancel" onclick="toggleManualCredit()">Cancel</button>
                        <button type="submit" class="btn-modal-pay">
                            <i class="bi bi-check-circle"></i> Create Credit
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Toolbar with filters --}}
        <div class="filters-card">
            <form method="GET" action="{{ url()->current() }}" style="display:contents;">
                {{-- Toolbar row: shop, date range, filter buttons, view payments --}}
                <div class="filters-row">
                    {{-- Shop filter --}}
                    @if(isset($shops) && count($shops) > 0)
                    <div style="flex:1; min-width:auto;">
                        <label class="filter-label">Shop</label>
                        <select name="shop" class="filter-ctrl" onchange="this.form.submit()" style="width:220px;">
                            <option value="">All Shops</option>
                            @foreach($shops as $shop)
                            <option value="{{ $shop['id'] }}" {{ request('shop') == $shop['id'] ? 'selected' : '' }}>
                                {{ $shop['name'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- Date range + filter buttons grouped together --}}
                    <div style="display:flex; align-items:flex-end; gap:.3rem; white-space:nowrap;">
                        <div style="display:flex; flex-direction:column; gap:.1rem; margin-bottom:.1rem;">
                            <label class="filter-label" style="margin-bottom:0;">Date Range</label>
                            <div style="display:flex; gap:.3rem; align-items:end;">
                                <input type="date" name="date_from"
                                       value="{{ request('date_from', $dateFrom ?? now()->startOfYear()->format('Y-m-d')) }}"
                                       class="filter-ctrl" style="padding-left:.6rem; width:130px;" placeholder="From">
                                <span style="font-size:.72rem; color:var(--slate-500); margin-top:.12rem;">—</span>
                                <input type="date" name="date_to"
                                       value="{{ request('date_to', $dateTo ?? now()->format('Y-m-d')) }}"
                                       class="filter-ctrl" style="padding-left:.6rem; width:130px;" placeholder="To">
                            </div>
                        </div>
                        <input type="hidden" name="tab" value="{{ $tab ?? 'all' }}">
                        <div class="filter-actions" style="gap:.4rem;">
                            {{-- Filter button always visible; reset only when date filtered --}}
                            @if(request('shop') || request('tab'))
                            <a href="{{ url()->current() }}" class="btn-reset">
                                <i class="bi bi-x"></i> Reset
                            </a>
                            @else
                            <button type="submit" class="btn-filter">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            @endif
                            <a href="{{ url('main-paid') }}" class="btn-filter">
                                <i class="bi bi-money"></i> View Payments
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabs --}}
        <div class="tabs-wrap">
            <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->query(), ['tab' => 'all'])) }}"
               class="tab-link {{ ($tab ?? 'all') == 'all' ? 'active' : '' }}">
                <i class="bi bi-people"></i> All Suppliers
            </a>
            <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->query(), ['tab' => 'completed'])) }}"
               class="tab-link {{ ($tab ?? 'all') == 'completed' ? 'active' : '' }}">
                <i class="bi bi-check-circle"></i> Completed
            </a>
            <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->query(), ['tab' => 'incomplete'])) }}"
               class="tab-link {{ ($tab ?? 'all') == 'incomplete' ? 'active' : '' }}">
                <i class="bi bi-exclamation-circle"></i> Incomplete
            </a>
            <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->query(), ['tab' => 'daily-payments'])) }}"
               class="tab-link {{ ($tab ?? 'all') == 'daily-payments' ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Daily Payments
            </a>
        </div>

        {{-- Metrics --}}
        @php
            $totalSuppliers = $totalSuppliers ?? 0;
            $totalCreditAmount = $totalCreditAmount ?? 0;
            $totalPaidAmount = $totalPaidAmount ?? 0;
            $totalRemainingAmount = $totalRemainingAmount ?? 0;
            $totalOutstanding = $totalOutstanding ?? 0;
            $hasCreditOrders = !empty($creditReceivings) && $creditReceivings->count() > 0;
        @endphp

        @if(!$hasCreditOrders)
        <div class="alert alert-danger" style="background:var(--rose-pale); color:var(--rose); border-left:3px solid var(--rose); border-radius:var(--radius); padding:.85rem 1rem; font-size:13.5px; font-weight:500;">
            <i class="bi bi-exclamation-circle me-2"></i>
            No credit orders found in the selected date range. Try widening the date filter or changing the shop.
        </div>
        @endif

        <div class="metrics-grid">
            <div class="metric-card mc-navy">
                <div class="metric-inner">
                    <div class="metric-icon mi-navy"><i class="bi bi-truck"></i></div>
                    <div><div class="metric-label">Total Suppliers</div><div class="metric-value">{{ $totalSuppliers }}</div></div>
                </div>
            </div>
            <div class="metric-card mc-rose">
                <div class="metric-inner">
                    <div class="metric-icon mi-rose"><i class="bi bi-exclamation-circle"></i></div>
                    <div><div class="metric-label">Outstanding</div><div class="metric-value">{{ $totalOutstanding }}</div></div>
                </div>
            </div>
            <div class="metric-card mc-emerald">
                <div class="metric-inner">
                    <div class="metric-icon mi-emerald"><i class="bi bi-cash-stack"></i></div>
                    <div><div class="metric-label">Total Paid</div><div class="metric-value" style="font-size:16px;">{{ number_format($totalPaidAmount) }}</div></div>
                </div>
            </div>
            <div class="metric-card mc-amber">
                <div class="metric-inner">
                    <div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div>
                    <div><div class="metric-label">Remaining</div><div class="metric-value" style="font-size:16px;">{{ number_format($totalRemainingAmount) }}</div></div>
                </div>
            </div>
        </div>

        {{-- Main table --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-left">
                    <div class="panel-head-icon"><i class="bi bi-table"></i></div>
                    <span class="panel-title">
                        @switch($tab ?? 'all')
                            @case('completed') Completed Orders @break
                            @case('incomplete') Incomplete Orders @break
                            @case('daily-payments') Daily Payments @break
                            @default All Credit Orders
                        @endswitch
                    </span>
                    <span class="shop-pill">{{ count($supplierData ?? []) }} records</span>
                </div>
            </div>

            <div class="tbl-wrap">
                @if(empty($supplierData) || count($supplierData) == 0)
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                    <h4>No suppliers found</h4>
                    <p>There are currently no suppliers matching this filter</p>
                </div>
                @else
                <table>
                    <thead>
                        <tr>
                            <th width="4%">#</th>
                            <th>Supplier</th>
                            <th>Orders</th>
                            <th>Total credit</th>
                            <th>Total paid</th>
                            <th>Remaining</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $rowIndex = 1; @endphp
                        @foreach ($supplierData as $data)
                        @php
                            $supplier = $data['supplier'];
                            $supplierName = $supplier->name ?? 'Unknown';
                            $initials = strtoupper(substr($supplierName, 0, 1));
                            $parts = explode(' ', trim($supplierName));
                            if (count($parts) > 1) $initials .= strtoupper(substr($parts[1], 0, 1));
                        @endphp
                        <tr>
                            <td class="idx-cell">{{ $rowIndex++ }}</td>
                            <td>
                                <div class="sup-cell">
                                    <div class="sup-avatar">{{ $initials }}</div>
                                    <span class="sup-name">{{ $supplierName }}</span>
                                </div>
                            </td>
                            <td><span class="num">{{ $data['credit_count'] }}</span></td>
                            <td><span class="amount-val credit-val">{{ number_format($data['total_credit']) }}</span></td>
                            <td><span class="amount-val paid-val">{{ number_format($data['total_paid']) }}</span></td>
                            <td>
                                <span class="amount-val {{ $data['total_remaining'] > 0 ? 'credit-val' : 'paid-val' }}">{{ number_format($data['total_remaining']) }}</span>
                            </td>
                            <td>
                                <div class="action-cell">
                                    <form method="GET" action="{{ url()->current() }}" style="display:inline;">
                                        @foreach(request()->query() as $key => $value)
                                        @if($key !== 'supplierId')
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                        @endif
                                        @endforeach
                                        <input type="hidden" name="supplierId" value="{{ $supplier->id }}">
                                        <button type="submit" class="act-btn ab-view">
                                            <i class="bi bi-eye"></i> View Details
                                        </button>
                                    </form>

                                    <button type="button" class="act-btn ab-pay" onclick="openPaymentModal('', '{{ $supplier->id }}', '{{ $data['total_remaining'] }}')">
                                        <i class="bi bi-cash-coin"></i> Pay
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
        @endif

        </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Prevent double-click on action buttons
    document.querySelectorAll('.act-btn, .btn-sm').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (this.dataset.submitted) {
                e.preventDefault();
                return false;
            }
            this.dataset.submitted = 'true';
            setTimeout(() => { this.dataset.submitted = ''; }, 3000);
        });
    });

    function toggleManualCredit() {
        var card = document.getElementById('manualCreditCard');
        card.style.display = (card.style.display === 'none' || card.style.display === '') ? 'block' : 'none';
    }

    function openPaymentModal(receivingId, supplierId, maxAmount) {
        document.getElementById('paymentReceivingId').value = receivingId || '';
        document.getElementById('paymentSupplierId').value = supplierId || '';
        const amountInput = document.getElementById('paymentAmount');
        const remainingInput = document.getElementById('paymentRemaining');
        amountInput.value = '';
        amountInput.disabled = false;
        if (maxAmount) {
            remainingInput.textContent = 'Tsh ' + Number(maxAmount).toLocaleString(undefined, { minimumFractionDigits: 2 });
        } else {
            remainingInput.textContent = 'Tsh 0.00';
        }

        document.getElementById('paymentModal').classList.add('show');
        setTimeout(() => amountInput.focus(), 150);
    }
    function closePaymentModal() {
        document.getElementById('paymentModal').classList.remove('show');
    }

    function openManualEditModal(btn) {
        document.getElementById('editReceivingId').value = btn.dataset.id || '';
        document.getElementById('editDescription').value = btn.dataset.desc || '';
        document.getElementById('editAmount').value = btn.dataset.amt || '';
        document.getElementById('editDate').value = btn.dataset.date || '';
        document.getElementById('manualEditModal').classList.add('show');
    }
    function closeManualEditModal() {
        document.getElementById('manualEditModal').classList.remove('show');
    }
    document.getElementById('manualEditModal').addEventListener('click', function(e) {
        if (e.target === this) closeManualEditModal();
    });

</script>

<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-cash-coin"></i> Make Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ url('process-debt-payment') }}" id="paymentForm" onsubmit="return validatePaymentForm(event)">
                @csrf
                <div class="modal-body">
                    <p>Enter the amount you want to pay. You can pay full or partial.</p>
                    <div class="modal-field">
                        <label>Remaining Balance</label>
                        <div class="modal-remaining-badge">
                            <i class="bi bi-wallet2"></i>
                            <span id="paymentRemaining">Tsh 0.00</span>
                        </div>
                    </div>
                    <div class="modal-field">
                        <label>Amount to Pay</label>
                        <input type="number" name="amount" id="paymentAmount" step="0.01" min="0" required placeholder="Enter amount">
                    </div>
                    <div class="modal-field">
                        <label>Payment Type</label>
                        <select name="payment_type" id="paymentType" class="form-select" required>
                            <option value="cash">Cash</option>
                            <option value="crdb">CRDB</option>
                            <option value="nmb">NMB</option>
                            <option value="nbc">NBC</option>
                            <option value="azania">Azania</option>
                        </select>
                    </div>
                    <div class="modal-field">
                        <label>Payment Date</label>
                        <input type="date" name="payment_date" id="paymentDate" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <input type="hidden" name="receivingId" id="paymentReceivingId">
                    <input type="hidden" name="supplierId" id="paymentSupplierId">
                    <input type="hidden" name="shop" id="paymentShop" value="{{ $selectedShopId ?? '' }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-pay">
                        <i class="bi bi-cash-coin"></i> Pay Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Manual Credit Edit Modal -->
<div class="modal fade" id="manualEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Manual Credit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ url('update-manual-credit') }}">
                @csrf
                <input type="hidden" name="receiving_id" id="editReceivingId">
                <div class="modal-body">
                    <div class="modal-field">
                        <label>Description</label>
                        <input type="text" name="description" id="editDescription" required>
                    </div>
                    <div class="modal-field">
                        <label>Amount</label>
                        <input type="number" name="amount" id="editAmount" step="0.01" min="0" required>
                    </div>
                    <div class="modal-field">
                        <label>Date</label>
                        <input type="date" name="date" id="editDate" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-pay">
                        <i class="bi bi-check-circle"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('footer')
</body>
</html>
