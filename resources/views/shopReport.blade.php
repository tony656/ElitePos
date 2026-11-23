<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('messages.shop_report') }} — {{ is_string($dateParam) && str_contains($dateParam, 'to') ? $dateParam : date('F d, Y', strtotime($dateParam)) }}</title>
    @include("links")
    
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

        /* ── Filters ── */
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
            min-width: 1400px;
        }
        /* Sticky shop name column when scrolling horizontally */
        #shopTable thead th:first-child,
        #shopTable tbody td:first-child {
            width: 55px;
        }
        #shopTable thead th:nth-child(2),
        #shopTable tbody td:nth-child(2) {
            position: sticky;
            left: 55px;
            background: #fff;
            z-index: 2;
            box-shadow: 2px 0 6px -2px rgba(0,0,0,.08);
        }
        #shopTable tbody td:nth-child(2) .shop-cell {
            background: #fff;
        }
        #shopTable tbody tr:nth-child(even) td:nth-child(2) { background: #f8fbfe; }
        #shopTable tbody tr:hover td:nth-child(2) { background: #edf6ff; }
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

        .date-val { font-size: .74rem; color: #31516e; font-variant-numeric: tabular-nums; }
        .date-val span { display: block; font-size: .68rem; color: #7189a0; margin-top: 1px; }
        .cust-name { font-weight: 600; color: #164a78; font-size: .76rem; margin-bottom: .08rem; }

        .shop-cell {
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .shop-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            flex-shrink: 0;
            background: var(--navy-light);
            color: var(--white);
            display: flex; align-items: center; justify-content: center;
            font-size: .78rem; font-weight: 700;
        }
        .shop-name { font-weight: 600; color: #164a78; font-size: .76rem; }
        .shop-loc { font-size: .68rem; color: var(--slate-500); }

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
        .shop-tag {
            display: inline-block;
            padding: .22rem .5rem;
            border-radius: 5px;
            background: var(--slate-100);
            color: var(--slate-600);
            font-size: .68rem;
            font-weight: 600;
        }

        /* Badges */
        .badge-ok   { background: var(--emerald-pale); color: var(--emerald); padding: .22rem .55rem; border-radius: 20px; font-size: .68rem; font-weight: 700; letter-spacing: .03em; }
        .badge-over { background: var(--amber-pale); color: var(--amber); padding: .22rem .55rem; border-radius: 20px; font-size: .68rem; font-weight: 700; letter-spacing: .03em; }
        .badge-bad  { background: var(--rose-pale); color: var(--rose); padding: .22rem .55rem; border-radius: 20px; font-size: .68rem; font-weight: 700; letter-spacing: .03em; }

        .txt-green { color: var(--emerald); font-weight: 600; }
        .txt-red   { color: var(--rose); font-weight: 600; }
        .txt-amber { color: var(--amber); font-weight: 600; }
        .txt-muted { color: var(--slate-500); }

        .ico-ok   { color: var(--emerald); font-size: .85rem; }
        .ico-warn { color: var(--amber); font-size: .85rem; cursor: help; }

        /* Action buttons */
        .action-cell { display: flex; gap: .28rem; justify-content: center; flex-wrap: wrap; }
        .action-icon-btn {
            width: 28px;
            height: 27px;
            border-radius: 5px;
            border: 0;
            color: #fff !important;
            background: #0870bd !important;
            font-size: .75rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .action-icon-btn.view   { background: var(--sky) !important; }
        .action-icon-btn.delete { background: var(--rose) !important; }
        .action-icon-btn:hover { background: #064f91 !important; transform: translateY(-1px); }
        .action-icon-btn.delete:hover { background: var(--rose) !important; }
        .action-icon-btn.view:hover { background: #0756a6 !important; }

        /* Detail row */
        .detail-row { display: none; }
        .detail-row.open { display: table-row; }
        .detail-row td {
            background: #f8fbfe;
            padding: 0;
        }
        .detail-inner {
            padding: .9rem;
            background: #fff;
            border: 1px solid #dce7f1;
            border-radius: var(--radius-sm);
            margin: 0 4px;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: .75rem;
        }
        .detail-item label {
            font-size: .66rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--slate-500);
            display: block;
            margin-bottom: .25rem;
        }
        .detail-item span {
            font-size: .76rem;
            font-weight: 600;
            color: var(--slate-800);
        }
        .detail-actions {
            margin-top: .75rem;
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            padding-top: .75rem;
            border-top: 1px solid #dce7f1;
        }
        .balance-issues {
            margin-top: .75rem;
            padding: .75rem 1rem;
            background: var(--rose-pale);
            border: 1px solid #f5b8c2;
            border-radius: var(--radius-sm);
        }
        .balance-issues-title {
            font-size: .7rem;
            font-weight: 700;
            color: var(--rose);
            margin-bottom: .4rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }
        .balance-issues ul { margin: 0; padding-left: 1.2rem; font-size: .72rem; color: var(--rose); }
        .balance-issues li { margin-bottom: .25rem; }

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
        textarea.field-input { resize: vertical; min-height: 72px; }

        .modal-search-wrap { position: relative; }
        .modal-search-wrap i {
            position: absolute;
            left: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
            font-size: .85rem;
            pointer-events: none;
        }
        .modal-search-wrap .field-input { padding-left: 2.35rem; }

        .input-prefix-wrap {
            display: flex;
            border: 1px solid #d4e1ed;
            border-radius: var(--radius);
            overflow: hidden;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-prefix-wrap:focus-within { border-color: #0b68b4; box-shadow: 0 0 0 3px rgba(11,104,180,.09); }
        .input-prefix {
            background: var(--slate-100);
            padding: .58rem .8rem;
            font-size: .78rem;
            font-weight: 600;
            color: var(--slate-500);
            border-right: 1px solid #d4e1ed;
            white-space: nowrap;
        }
        .input-prefix-wrap input {
            flex: 1;
            border: none;
            outline: none;
            padding: .58rem .8rem;
            font-family: inherit;
            font-size: .78rem;
            color: var(--slate-800);
            background: transparent;
        }

        .modal-footer-custom {
            padding: .75rem .9rem;
            border-top: 1px solid #dce7f1;
            display: flex;
            justify-content: flex-end;
            gap: .5rem;
        }
        .btn-cancel {
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
        .btn-cancel:hover { background: #eff6ff; border-color: #0b68b4; color: #0756a6; }
        .btn-submit {
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            letter-spacing: 0.01em;
        }
        .btn-submit:hover { background: #056f58; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(7,134,107,.28); }
        .btn-submit-rose {
            background: var(--rose);
        }
        .btn-submit-rose:hover { background: #c92d43; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(229,72,93,.28); }

        /* Required items list */
        .required-item-row {
            display: flex;
            gap: .4rem;
            flex-wrap: wrap;
            align-items: flex-end;
            margin-bottom: .5rem;
        }
        .required-item-row .field { margin-bottom: 0; }
        .required-item-row .field-label { margin-bottom: .25rem; font-size: .66rem; }

        .btn-remove-req {
            width: 28px;
            height: 27px;
            border-radius: 5px;
            background: #fff0f1;
            color: var(--rose);
            border: 1px solid #f5b8c2;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
        }
        .btn-remove-req:hover { background: var(--rose); color: #fff; border-color: var(--rose); }

        /* Wizard / tooltip */
        .wizard-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(8, 61, 108, 0.4);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.25s ease;
        }
        .wizard-overlay.open { display: flex; }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .wizard-box {
            background: var(--white);
            border-radius: var(--radius-sm);
            width: 100%;
            max-width: 420px;
            margin: 1rem;
            box-shadow: var(--shadow-lg);
            animation: slideUp 0.3s ease;
            max-height: 90vh;
            overflow-y: auto;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .wizard-head {
            background: linear-gradient(120deg, #064b86, #0870bd);
            color: #fff;
            padding: .75rem .9rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .wizard-head-left { display: flex; align-items: center; gap: .5rem; }
        .wizard-head h5 { font-size: .86rem; font-weight: 700; color: #fff; margin: 0; }
        .wizard-step { font-size: .66rem; background: rgba(255,255,255,.2); padding: 2px 8px; border-radius: 10px; }
        .wizard-close {
            background: none;
            border: none;
            color: #fff;
            font-size: 1rem;
            opacity: .7;
            cursor: pointer;
            line-height: 1;
            padding: 0 4px;
        }
        .wizard-close:hover { opacity: 1; }
        .wizard-body { padding: .9rem; font-size: .76rem; }
        .wizard-label {
            font-size: .66rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--slate-500);
            margin-bottom: .3rem;
            display: block;
        }
        .wizard-fullname { font-size: .82rem; font-weight: 700; color: var(--navy); margin-bottom: .5rem; }
        .wizard-explanation { font-size: .72rem; line-height: 1.5; color: var(--slate-700); margin-bottom: .6rem; }
        .wizard-calc {
            background: var(--slate-50);
            border-left: 3px solid #0b68b4;
            padding: .5rem .75rem;
            border-radius: var(--radius-sm);
            font-size: .7rem;
            color: var(--slate-800);
            margin-bottom: .5rem;
        }
        .wizard-calc-label {
            font-size: .64rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--slate-500);
            margin-bottom: .15rem;
            letter-spacing: .06em;
        }
        .wizard-balance {
            background: var(--emerald-pale);
            border: 1px solid #cfe0ef;
            border-radius: var(--radius-sm);
            padding: .5rem .75rem;
            font-size: .74rem;
            color: var(--slate-800);
            margin-bottom: .5rem;
        }
        .wizard-balance strong { color: var(--emerald); }
        .wizard-foot {
            padding: .75rem .9rem;
            border-top: 1px solid #dce7f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .5rem;
        }
        .wizard-progress { font-size: .7rem; color: var(--slate-500); }
        .wizard-nav { display: flex; gap: .4rem; }
        .wizard-btn {
            padding: .45rem .8rem;
            border-radius: var(--radius);
            border: 1px solid #d4e1ed;
            background: transparent;
            font-size: .7rem;
            font-weight: 600;
            color: var(--slate-600);
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .wizard-btn:hover { background: #eff6ff; border-color: #0b68b4; color: #0756a6; }
        .wizard-btn-primary {
            background: #0866b2;
            border-color: #0866b2;
            color: #fff;
        }
        .wizard-btn-primary:hover { background: #064f91; border-color: #064f91; }
        .wizard-skip {
            background: transparent;
            border: none;
            color: var(--slate-500);
            font-size: .7rem;
            cursor: pointer;
            text-decoration: underline;
            padding: .45rem .6rem;
        }
        .wizard-skip:hover { color: var(--slate-800); }
        .wizard-hint {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(8, 61, 108, 0.9);
            color: var(--white);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 500;
            z-index: 10002;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .wizard-hint.show { opacity: 1; }

        @media (max-width: 768px) {
            .wrap { padding: .8rem; }
            .pg-header { padding: .25rem 0 .7rem; }
            .filters-row { flex-direction: column; align-items: stretch; }
            .filter-actions { width: 100%; justify-content: flex-end; }
            .metrics-grid { grid-template-columns: 1fr; }
            table { min-width: 1000px; }
        }
    </style>
</head>
<body>
@include('sidenav')

<main class="main-content">

    {{-- Alerts --}}
    @if(session('success'))
    <div class="alert alert-success">
        <span><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</span>
        <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">
        <span><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</span>
        <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
    </div>
    @endif

    <div class="wrap">

        {{-- Page header --}}
        <div class="pg-header">
            <div class="pg-left">
                <div class="header-icon"><i class="bi bi-bar-chart-fill"></i></div>
                <div class="pg-title-text">
                    <h1>{{ __('messages.shop_report') }}</h1>
                    <p><i class="bi bi-info-circle"></i> {{ __('messages.overview_of_all_shop_performance') }}</p>
                </div>
            </div>
            <div class="pg-right">
                <form method="GET" action="shopReport" id="dateFilterForm" style="display: contents;">
                    <div class="filter-group" style="flex:0 0 auto; min-width:auto;">
                        <label class="filter-label" style="margin-bottom:0;">Date Range</label>
                        <div style="display:flex; align:center; gap:.3rem;">
                            <input type="date" name="date_from" id="dateFromInput" class="filter-ctrl" value="{{ $dateFrom ?? '' }}" style="padding-left:.6rem; width:130px;" placeholder="{{ __('messages.from') }}">
                            <span style="font-size:.72rem; color:var(--slate-500); margin-top:.12rem;">—</span>
                            <input type="date" name="date_to" id="dateToInput" class="filter-ctrl" value="{{ $dateTo ?? '' }}" style="padding-left:.6rem; width:130px;" placeholder="{{ __('messages.to') }}">
                        </div>
                    </div>
                    <button type="submit" class="btn-filter" style="margin-left:.4rem;">
                        <i class="bi bi-funnel"></i> {{ __('messages.filter') }}
                    </button>
                </form>
                <a href="{{ url('shopReport') }}" class="btn-reset">
                    <i class="bi bi-x"></i> {{ __('messages.reset') }}
                </a>
                <button class="btn-header-outline" onclick="exportToExcel()">
                    <i class="bi bi-file-earmark-excel"></i> {{ __('messages.excel') }}
                </button>
                <button class="btn-header-outline" onclick="exportToPDF()">
                    <i class="bi bi-file-earmark-pdf"></i> {{ __('messages.pdf') }}
                </button>
            </div>
        </div>

        {{-- Metrics --}}
        <div class="metrics-grid">
            <div class="metric-card mc-navy">
                <div class="metric-inner">
                    <div class="metric-icon mi-navy"><i class="bi bi-shop"></i></div>
                    <div>
                        <div class="metric-label">{{ __('messages.total_shops') }}</div>
                        <div class="metric-value">{{ $activeShopsCount }}</div>
                        <div class="metric-sub">{{ $shopsWithSalesCount }} {{ __('messages.with_sales_today') }}</div>
                    </div>
                </div>
            </div>
            <div class="metric-card mc-rose">
                <div class="metric-inner">
                    <div class="metric-icon mi-rose"><i class="bi bi-cash-stack"></i></div>
                    <div>
                        <div class="metric-label">{{ __('messages.total_sales') }}</div>
                        <div class="metric-value">{{ number_format($totals->total_sales) }}</div>
                        <div class="metric-sub"><i class="bi bi-cash"></i> {{ number_format($totals->cash_sales) }} · <i class="bi bi-credit-card"></i> {{ number_format($totals->credit_sales) }}</div>
                    </div>
                </div>
            </div>
            <div class="metric-card mc-emerald">
                <div class="metric-inner">
                    <div class="metric-icon mi-emerald"><i class="bi bi-graph-up-arrow"></i></div>
                    <div>
                        <div class="metric-label">{{ __('messages.total_profit') }}</div>
                        <div class="metric-value">{{ number_format($totals->profit) }}</div>
                        <div class="metric-sub">{{ __('messages.after_expenses_and_debt') }}</div>
                    </div>
                </div>
            </div>
            <div class="metric-card mc-amber">
                <div class="metric-inner">
                    <div class="metric-icon mi-amber"><i class="bi bi-bullseye"></i></div>
                    <div>
                        <div class="metric-label">{{ __('messages.cash_expected') }}</div>
                        <div class="metric-value">{{ number_format($totals->cash_amount) }}</div>
                        <div class="metric-sub">{{ __('messages.submitted') }} {{ number_format($totals->cash_submitted) }}</div>
                    </div>
                </div>
            </div>
            <div class="metric-card mc-blue">
                <div class="metric-inner">
                    <div class="metric-icon mi-blue"><i class="bi bi-check-circle"></i></div>
                    <div>
                        <div class="metric-label">{{ __('messages.cash_submitted') }}</div>
                        <div class="metric-value">{{ number_format($totals->cash_submitted) }}</div>
                        <div class="metric-sub">{{ __('messages.cash_expected') }} {{ number_format($totals->cash_amount) }}</div>
                    </div>
                </div>
            </div>
            <div class="metric-card mc-rose">
                <div class="metric-inner">
                    <div class="metric-icon mi-rose"><i class="bi bi-exclamation-diamond"></i></div>
                    <div>
                        <div class="metric-label">{{ __('messages.unbalanced_shops') }}</div>
                        <div class="metric-value">{{ $unbalancedShopsCount }}</div>
                        <div class="metric-sub">{{ $unbalancedReasonsText ?: __('messages.all_balanced') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table Panel --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-left">
                    <div class="panel-head-icon"><i class="bi bi-table"></i></div>
                    <span class="panel-title">{{ __('messages.all_shops') }}</span>
                </div>
                <span class="shop-pill">
                    <i class="bi bi-shop"></i> {{ $shopReports->count() }} {{ __('messages.shops') }}
                </span>
            </div>

            <div class="tbl-wrap" id="tableScroll">
                @if($shopReports->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                    <h4>{{ __('messages.no_shops_found') }}</h4>
                    <p>{{ __('messages.no_active_shops_found_for') }} {{ date('F d, Y', strtotime($dateParam)) }}.</p>
                </div>
                @else
                <table id="shopTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.shop_name') }}</th>
                            <th title="{{ __('messages.cash_sale') }}">{{ __('messages.c_sale') }}</th>
                            <th title="{{ __('messages.credit_sale') }}">{{ __('messages.cr_sale') }}</th>
                            <th title="{{ __('messages.bank_sale_colon') }}">{{ __('messages.b_s') }}</th>
                            <th title="{{ __('messages.total_sales') }}">{{ __('messages.t_sales') }}</th>
                            <th title="{{ __('messages.total_return') }}">{{ __('messages.t_ret') }}</th>
                            <th title="{{ __('messages.offered_items_colon') }}">{{ __('messages.offered') }}</th>
                            <th title="{{ __('messages.discount_colon') }}">{{ __('messages.dsc') }}</th>
                            <th title="{{ __('messages.expenses_colon') }}">{{ __('messages.exp') }}</th>
                            <th title="{{ __('messages.profit_loss_colon') }}">{{ __('messages.p_l') }}</th>
                            <th title="{{ __('messages.paid_invoices_colon') }}">{{ __('messages.p_inv') }}</th>
                            <th title="{{ __('messages.cash_receivings_colon') }}">{{ __('messages.ca_r') }}</th>
                            <th title="{{ __('messages.credit_receivings_colon') }}">{{ __('messages.cr_r') }}</th>
                            <th title="{{ __('messages.chip_deposit_colon') }}">{{ __('messages.chip_d') }}</th>
                            <th title="{{ __('messages.chip_used_colon') }}">{{ __('messages.chip_u') }}</th>
                            <th title="{{ __('messages.cash_amount_expected_colon') }}">{{ __('messages.c_a') }}</th>
                            <th title="{{ __('messages.cash_submit_colon') }}">{{ __('messages.c_s') }}</th>
                            <th title="{{ __('messages.bank_deposit_colon') }}">{{ __('messages.b_d') }}</th>
                            <th title="{{ __('messages.bank_difference_colon') }}">{{ __('messages.b_diff') }}</th>
                            <th title="{{ __('messages.difference_colon') }}">{{ __('messages.diff') }}</th>
                            <th title="{{ __('messages.main_store_difference') }}">{{ __('messages.m_s_diff') }}</th>
                            <th title="{{ __('messages.cost_worth_inventory_colon') }}">{{ __('messages.inventory') }}</th>
                            <th><i class="bi bi-info-circle"></i> {{ __('messages.status') }}</th>
                            <th>{{ __('messages.balance') }}</th>
                        </tr>
                    </thead>
                    <tbody id="shopTableBody">
                        @foreach ($shopReports as $index => $shop)
                        @php
                            $cashSale   = $shop->cash_sales;
                            $return     = $shop->total_return;
                            $creditSale = $shop->credit_sales;
                            $bankSales = $shop->sales_bank ?? 0;
                            $totalSale  = $shop->total_sales;
                            $cashAmount = $shop->cash_amount;
                            $cashSubmit = $shop->cash_submitted;

                            $mainDiff = ($shop->main_store - $shop->credit_receivings - $shop->total_return) < 1 && ($shop->main_store - $shop->credit_receivings - ($shop->total_return - $shop->returned_receivings)) > -1;
                            $bankDiff = $shop->bank_diff < 1 && $shop->bank_diff > -1;
                            $diff = $cashAmount - $cashSubmit;

                            $profitVal = $totalSale - ($shop->total_return ?? 0)
                                - ($shop->expenses ?? 0)
                                - ($shop->cash_receivings ?? 0)
                                - ($shop->credit_receivings ?? 0);

                            $diffClass = abs($diff) < 0.01 ? 'txt-muted' : ($diff > 0 ? 'txt-red' : 'txt-green');

                            $salesBalanced = abs(($cashSale + $creditSale + $bankSales) - $totalSale) < 0.04;
                            $cashBalanced  = abs($diff) < 0.04;
                            $inventory = abs($shop->cost_worth) < 0.01 && abs($shop->cost_worth) > -0.01;
                            $isBalanced    = $salesBalanced && $cashBalanced && $bankDiff && $mainDiff;

                            $isDiffZero = abs($diff) < 0.1 && abs($shop->bank_diff) < 0.1 && abs($shop->main_store - $shop->credit_receivings - $shop->total_return) < 0.1 && $inventory;
                            $statusBadge = $isDiffZero ? 'badge-ok' : ($diff > 0 ? 'badge-bad' : 'badge-over');
                            $statusText  = $isDiffZero ? __('messages.settled') : ($diff > 0 ? __('messages.underpaid') : __('messages.overpaid'));

                            $balanceIssues = [];
                            if (!$salesBalanced) {
                                $balanceIssues[] = __('messages.balance_issue_sales_mismatch', ['cash' => number_format($cashSale), 'credit' => number_format($creditSale), 'total' => number_format($totalSale)]);
                            }
                            if (!$cashBalanced) {
                                $balanceIssues[] = __('messages.balance_issue_cash_expected', ['expected' => number_format($cashAmount), 'got' => number_format($cashSubmit)]);
                            }
                            $initials = strtoupper(substr($shop->shop_name, 0, 1));
                            $parts = explode(' ', trim($shop->shop_name));
                            if (count($parts) > 1) $initials .= strtoupper(substr($parts[1], 0, 1));
                        @endphp

                        <tr class="shop-row {{ !$isBalanced ? 'warn-row' : '' }}"
                            data-name="{{ strtolower($shop->shop_name) }}"
                            data-status="{{ $isDiffZero ? 'settled' : ($diff > 0 ? 'underpaid' : 'overpaid') }}"
                            onclick="toggleDetail({{ $index }})">
                            <td class="txt-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="shop-cell">
                                    <div class="shop-avatar">{{ $initials }}</div>
                                    <div>
                                        <div class="shop-name">{{ $shop->shop_name }}</div>
                                        <div class="shop-loc"><i class="bi bi-geo-alt"></i> {{ $shop->location }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="num">{{ number_format($cashSale) }}</td>
                            <td class="num">{{ number_format($creditSale) }}</td>
                            <td class="num">{{ number_format($shop->sales_bank ?? 0) }}</td>
                            <td class="num fw6">{{ number_format($totalSale) }}</td>
                            <td class="num txt-red">{{ number_format($shop->total_return ?? 0) }}</td>
                            <td class="num txt-amber fw6">{{ number_format($shop->total_offer ?? 0) }}</td>
                            <td class="num txt-muted">{{ number_format($shop->discount) }}</td>
                            <td class="num txt-muted">{{ number_format($shop->expenses) }}</td>
                            <td class="num {{ $profitVal >= 0 ? 'txt-green' : 'txt-red' }}">{{ number_format($profitVal) }}</td>
                            <td class="num">{{ number_format($shop->paid_invoices) }}</td>
                            <td class="num">{{ number_format($shop->cash_receivings) }}</td>
                            <td class="num">{{ number_format($shop->credit_receivings) }}</td>
                            <td class="num">{{ number_format($shop->totalChip) }}</td>
                            <td class="num">{{ number_format($shop->chip_used) }}</td>
                            <td class="num">{{ number_format($cashAmount) }}</td>
                            <td class="num">{{ number_format($cashSubmit, 2) }}</td>
                            <td class="num">{{ number_format($shop->total_bank, 2) }}</td>
                            <td class="num">{{ number_format($shop->bank_diff) }}</td>
                            <td class="num fw6 {{ $diffClass }}">{{ number_format(abs($diff)) }}</td>
                            <td class="num">{{ number_format($shop->main_store - $shop->credit_receivings - ($shop->returned_receivings), 2 ) }}</td>
                            <td class="num">{{ number_format($shop->cost_worth ?? 0) }}</td>
                            <td><span class="badge {{ $statusBadge }}">{{ $statusText }}</span></td>
                            <td>
                                @if(!$isBalanced)
                                <span class="ico-warn" title="{{ implode(' | ', $balanceIssues) }}"><i class="bi bi-exclamation-triangle-fill"></i></span>
                                @else
                                <span class="ico-ok"><i class="bi bi-check-circle-fill"></i></span>
                                @endif
                            </td>
                        </tr>

                        <tr id="detail-{{ $index }}" class="detail-row {{ !$isBalanced ? 'warn-row' : '' }}">
                            <td colspan="26">
                                <div class="detail-inner">
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <label><i class="bi bi-shop"></i> {{ __('messages.shop_name') }}</label>
                                            <span>{{ $shop->shop_name }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <label><i class="bi bi-geo-alt"></i> {{ __('messages.location') }}</label>
                                            <span>{{ $shop->location }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <label><i class="bi bi-bullseye"></i> {{ __('messages.expected_cash') }}</label>
                                            <span>{{ number_format($shop->expected_cash) }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <label><i class="bi bi-check-circle"></i> {{ __('messages.cash_submit') }}</label>
                                            <span>{{ number_format($shop->cash_submitted) }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <label><i class="bi bi-arrow-left-right"></i> {{ __('messages.difference') }}</label>
                                            <span class="{{ $diffClass }}">{{ number_format($diff) }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <label><i class="bi bi-currency-dollar"></i> {{ __('messages.total_debt') }}</label>
                                            <span>{{ number_format($shop->debt) }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <label><i class="bi bi-scale"></i> {{ __('messages.balance_status') }}</label>
                                            <span class="{{ $isBalanced ? 'txt-green' : 'txt-red' }}">
                                                {{ $isBalanced ? __('messages.balanced') : __('messages.unbalanced') }}
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <label><i class="bi bi-arrow-left-right"></i> {{ __('messages.transactions') }}</label>
                                            <span>{{ number_format($shop->total_transactions) }}</span>
                                        </div>
                                    </div>

                                    @if(!$isBalanced && count($balanceIssues) > 0)
                                    <div class="balance-issues">
                                        <div class="balance-issues-title"><i class="bi bi-exclamation-triangle-fill"></i> {{ __('messages.balance_issues_detected') }}</div>
                                        <ul>
                                            @foreach($balanceIssues as $issue)
                                            <li>{{ $issue }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    <div class="detail-actions">
                                        <button class="btn-submit" style="font-size:.72rem; padding:.4rem .8rem;"
                                            onclick="event.stopPropagation(); openCashModal({{ $index }})">
                                            <i class="bi bi-cash-coin"></i> {{ __('messages.submit_cash') }}
                                        </button>
                                        @if($shop->cash_submitted > 0)
                                        <form method="POST" action="{{ url('cashDelete') }}" style="display: inline;" onsubmit="return confirm('{{ __('messages.delete_cash_submission_for') }} {{ $shop->shop_name }}?');">
                                            @csrf
                                            <input type="hidden" name="shop_id" value="{{ $shop->shop_id }}">
                                            <input type="hidden" name="date" value="{{ $dateParam }}">
                                            <button type="submit" class="btn-submit btn-submit-rose" style="font-size:.72rem; padding:.4rem .8rem;">
                                                <i class="bi bi-trash3"></i> {{ __('messages.delete') }}
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="2"><i class="bi bi-calculator"></i> {{ __('messages.totals') }}</td>
                            <td class="num">{{ number_format($totals->cash_sales) }}</td>
                            <td class="num">{{ number_format($totals->credit_sales) }}</td>
                            <td class="num fw6">{{ number_format($totals->total_sales - $totals->total_return) }}</td>
                            <td class="num">{{ number_format($totals->total_return ?? 0) }}</td>
                            <td class="num">{{ number_format($totals->offer ?? 0) }}</td>
                            <td class="num">{{ number_format($totals->discount) }}</td>
                            <td class="num">{{ number_format($totals->expenses) }}</td>
                            <td class="num {{ $totals->profit >= 0 ? 'txt-green' : 'txt-red' }}">{{ number_format($totals->profit) }}</td>
                            <td class="num">{{ number_format($totals->paid_invoices) }}</td>
                            <td class="num">{{ number_format($totals->cash_receivings) }}</td>
                            <td class="num">{{ number_format($totals->credit_receivings) }}</td>
                            <td class="num">{{ number_format($totals->totalChip) }}</td>
                            <td class="num">{{ number_format($totals->chip_used) }}</td>
                            <td class="num">{{ number_format($totals->cash_amount) }}</td>
                            <td class="num">{{ number_format($totals->cash_submitted) }}</td>
                            <td class="num">{{ number_format($totals->total_bank) }}</td>
                            <td class="num">{{ number_format($totals->sales_bank ?? 0) }}</td>
                            @php
                                $totalDiff = $totals->cash_amount - $totals->cash_submitted;
                                $totalDiffClass = abs($totalDiff) < 0.01 ? '' : ($totalDiff > 0 ? 'txt-red' : 'txt-green');
                            @endphp
                            <td class="num {{ $totalDiffClass }} fw6">{{ number_format(abs($totalDiff)) }}</td>
                            <td class="num">{{ number_format($totals->main_store) }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                @endif
            </div>
        </div>

    </div>
</main>

 {{-- Column Tooltip Wizard --}}
<div class="wizard-overlay" id="wizardOverlay">
    <div class="wizard-box" id="wizardTooltip">
        <div class="wizard-head">
            <div class="wizard-head-left">
                <span class="wizard-step" id="tooltipStep">1/22</span>
                <h5 id="tooltipTitle">C.Sale</h5>
            </div>
            <button class="wizard-close" onclick="closeWizard()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="wizard-body">
            <div class="wizard-label"><i class="bi bi-info-circle"></i> {{ __('messages.column_explanation') }}</div>
            <div class="wizard-fullname" id="tooltipFullName">{{ __('messages.cash_sales_wizard') }}</div>
            <div class="wizard-explanation" id="tooltipExplanation">
                {{ __('messages.total_cash_sales_made_by_shop_today') }}
            </div>
            <div class="wizard-calc">
                <div class="wizard-calc-label"><i class="bi bi-calculator"></i> {{ __('messages.calculation') }}</div>
                <div id="tooltipCalculation">{{ __('messages.sum_of_all_cash_sales') }}</div>
            </div>
            <div class="wizard-balance">
                <strong><i class="bi bi-scale"></i> {{ __('messages.balance_colon') }}</strong> <span id="tooltipBalance">{{ __('messages.part_of_total_sales') }}</span>
            </div>
        </div>
        <div class="wizard-foot">
            <div class="wizard-progress" id="tooltipProgress">{{ __('messages.wizard_step_progress', ['step' => 1, 'total' => 22]) }}</div>
            <div class="wizard-nav">
                <button class="wizard-skip" onclick="closeWizard()">{{ __('messages.skip') }}</button>
                <button class="wizard-btn" id="prevBtn" onclick="prevStep()"><i class="bi bi-chevron-left"></i> {{ __('messages.previous') }}</button>
                <button class="wizard-btn wizard-btn-primary" id="nextBtn" onclick="nextStep()">{{ __('messages.next') }} <i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
    </div>
    <div class="wizard-hint" id="closeHint"><i class="bi bi-escape"></i> {{ __('messages.press_esc_or_click_outside_to_close') }}</div>
</div>

 {{-- Cash Submit Modal --}}
<div class="modal fade" id="cashModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-cash-coin"></i> {{ __('messages.submit_cash') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="cashForm" method="POST" action="{{ url('cashSubmit') }}">
                @csrf
                <div class="modal-body">
                    <div class="field">
                        <label class="field-label"><i class="bi bi-shop"></i> {{ __('messages.shop') }}</label>
                        <input type="text" class="field-input readonly" id="m-shop" value="—" readonly>
                    </div>
                    <div class="field">
                        <label class="field-label"><i class="bi bi-calendar3"></i> {{ __('messages.date') }}</label>
                        @if(canUser("cash_submit_date"))
                        <input type="date" class="field-input" name="date" id="m-date" value="{{ date('Y-m-d') }}">
                        @else
                        <input type="hidden" name="date" id="m-date" value="{{ date('Y-m-d') }}">
                        <input type="text" class="field-input readonly" value="{{ date('Y-m-d') }}" readonly>
                        @endif
                    </div>
                    <div class="field">
                        <label class="field-label"><i class="bi bi-bar-chart"></i> {{ __('messages.total_sales') }}</label>
                        <input type="text" class="field-input readonly" id="m-sales" value="0" readonly>
                    </div>
                    <div class="field">
                        <label class="field-label"><i class="bi bi-cash"></i> {{ __('messages.cash_sales') }}</label>
                        <input type="text" class="field-input readonly" id="m-cash-sales" value="0" readonly>
                    </div>
                    <div class="field">
                        <label class="field-label"><i class="bi bi-wallet2"></i> {{ __('messages.expenses') }}</label>
                        <input type="text" class="field-input readonly" id="m-exp" value="0" readonly>
                    </div>
                    <div class="field">
                        <label class="field-label"><i class="bi bi-tag"></i> {{ __('messages.discount') }}</label>
                        <input type="text" class="field-input readonly" id="m-dsc" value="0" readonly>
                    </div>
                    <div class="field">
                        <label class="field-label"><i class="bi bi-bullseye"></i> {{ __('messages.expected_cash') }}</label>
                        <input type="text" class="field-input readonly" id="m-expected" value="0" readonly>
                    </div>
                    <div class="field">
                        <label class="field-label"><i class="bi bi-coin"></i> {{ __('messages.remaining') }}</label>
                        <input type="text" class="field-input readonly" id="m-remaining" value="0" readonly style="color:var(--rose);font-weight:600;">
                    </div>

                    <div class="field">
                        <label class="field-label">{{ __('messages.modal_cash_amount_to_submit') }} <span class="req">*</span></label>
                        <div class="input-prefix-wrap">
                            <span class="input-prefix">TSh</span>
                            <input type="number" id="cashAmount" name="submitted_cash" step="0.01" min="0.01" required placeholder="0.00">
                        </div>
                        <div class="quick-amounts">
                            <button type="button" class="btn-add" style="font-size:.66rem; padding:.35rem .6rem;" onclick="setAmt(5000)">5,000</button>
                            <button type="button" class="btn-add" style="font-size:.66rem; padding:.35rem .6rem;" onclick="setAmt(10000)">10,000</button>
                            <button type="button" class="btn-add" style="font-size:.66rem; padding:.35rem .6rem;" onclick="setAmt(20000)">20,000</button>
                            <button type="button" class="btn-add" style="font-size:.66rem; padding:.35rem .6rem;" onclick="setAmt(50000)">50,000</button>
                            <button type="button" class="btn-add" style="font-size:.66rem; padding:.35rem .6rem;" onclick="setAmt(100000)">100,000</button>
                        </div>
                    </div>

                    <input type="hidden" name="shop_id" id="m-shop-id">
                    <input type="hidden" name="sales" id="m-sales-hidden">
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> {{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn-submit"><i class="bi bi-check-circle"></i> {{ __('messages.submit_cash') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const shopData = @json($shopReports);

    // ── Toggle detail row ──
    function toggleDetail(i) {
        const detail = document.getElementById('detail-' + i);
        const main = detail.previousElementSibling;
        const isOpen = detail.classList.contains('open');

        document.querySelectorAll('.detail-row.open').forEach(r => r.classList.remove('open'));
        document.querySelectorAll('.shop-row.expanded').forEach(r => r.classList.remove('expanded'));

        if (!isOpen) {
            detail.classList.add('open');
            main.classList.add('expanded');
            setTimeout(() => {
                detail.scrollIntoView({ block: 'center', behavior: 'smooth' });
            }, 100);
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
                const aStatus = a.getAttribute('data-status');
                const bStatus = b.getAttribute('data-status');
                if (sortValue === 'status_settled' || sortValue === 'status_underpaid' || sortValue === 'status_overpaid') {
                    const target = sortValue.replace('status_', '');
                    return (bStatus === target ? 1 : 0) - (aStatus === target ? 1 : 0);
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
        document.getElementById('m-shop').textContent = s.shop_name;
        document.getElementById('m-sales').textContent = fmt(s.total_sales);
        document.getElementById('m-cash-sales').textContent = fmt(s.cash_sales);
        document.getElementById('m-exp').textContent = fmt(s.expenses);
        document.getElementById('m-dsc').textContent = fmt(s.discount);
        document.getElementById('m-expected').textContent = fmt(s.expected_cash);
        document.getElementById('m-remaining').textContent = fmt(s.cash_difference);
        document.getElementById('m-shop-id').value = s.shop_id;
        document.getElementById('m-sales-hidden').value = s.expected_cash;
        document.getElementById('cashAmount').value = s.cash_difference > 0 ? s.cash_difference : 0;
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        document.getElementById('m-date').value = `${year}-${month}-${day}`;
        new bootstrap.Modal(document.getElementById('cashModal')).show();
    }

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


    // ── Column Wizard ──
    const wizardData = [
        { title: 'C.Sale', fullName: @json(__('messages.cash_sales_wizard')), explanation: @json(__('messages.total_cash_sales_made_by_shop_today')), calculation: @json(__('messages.sum_of_all_cash_sales')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Cr.Sale', fullName: @json(__('messages.credit_sale')), explanation: @json(__('messages.total_credit_sales')), calculation: @json(__('messages.calc_sum_cash_sales')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'T.Sales', fullName: @json(__('messages.total_sales')), explanation: @json(__('messages.total_sales_net')), calculation: @json(__('messages.calc_cash_plus_credit')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'T.Ret', fullName: @json(__('messages.total_return')), explanation: @json(__('messages.total_return_sum')), calculation: @json(__('messages.calc_sum_returns')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Offered', fullName: @json(__('messages.offered')), explanation: @json(__('messages.total_offered_items')), calculation: @json(__('messages.calc_sum_offered')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Dsc', fullName: @json(__('messages.discount')), explanation: @json(__('messages.total_discount')), calculation: @json(__('messages.calc_sum_discounts')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Exp', fullName: @json(__('messages.expenses')), explanation: @json(__('messages.total_expenses')), calculation: @json(__('messages.calc_sum_expenses')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'P/L', fullName: @json(__('messages.profit_loss')), explanation: @json(__('messages.total_profit_loss')), calculation: @json(__('messages.calc_total_sales_minus')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'P.Inv', fullName: @json(__('messages.paid_invoice')), explanation: @json(__('messages.total_paid_invoices')), calculation: @json(__('messages.calc_sum_paid_invoices')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Ca.R', fullName: @json(__('messages.cash_receivings')), explanation: @json(__('messages.total_cash_receivings')), calculation: @json(__('messages.calc_sum_cash_receivings')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Cr.R', fullName: @json(__('messages.credit_receivings')), explanation: @json(__('messages.total_credit_receivings')), calculation: @json(__('messages.calc_sum_credit_receivings')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Chip.D', fullName: @json(__('messages.chip_deposit')), explanation: @json(__('messages.total_chip_deposit')), calculation: @json(__('messages.calc_sum_chip_deposits')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Chip.U', fullName: @json(__('messages.chip_used')), explanation: @json(__('messages.total_chip_used')), calculation: @json(__('messages.calc_sum_chip_usage')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'C.A', fullName: @json(__('messages.cash_amount')), explanation: @json(__('messages.total_cash_amount_expected')), calculation: @json(__('messages.calc_sum_expected_cash')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'C.S', fullName: @json(__('messages.cash_submit')), explanation: @json(__('messages.total_cash_submitted')), calculation: @json(__('messages.calc_sum_submitted_cash')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'B.D', fullName: @json(__('messages.bank_deposit')), explanation: @json(__('messages.total_bank_deposit')), calculation: @json(__('messages.calc_sum_bank_deposits')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'B.Diff', fullName: @json(__('messages.bank_difference')), explanation: @json(__('messages.bank_difference_colon')), calculation: @json(__('messages.calc_bank_minus_cash')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Diff', fullName: @json(__('messages.difference')), explanation: @json(__('messages.difference_colon')), calculation: @json(__('messages.calc_expected_minus_submitted')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Inventory', fullName: @json(__('messages.inventory')), explanation: @json(__('messages.cost_worth_inventory_colon')), calculation: @json(__('messages.calc_sum_cost_worth')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Status', fullName: @json(__('messages.status')), explanation: @json(__('messages.calc_settled_status')), calculation: @json(__('messages.part_of_total_sales')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Balance', fullName: @json(__('messages.balance')), explanation: @json(__('messages.calc_balanced_status')), calculation: @json(__('messages.part_of_total_sales')), balance: @json(__('messages.part_of_total_sales')) }
    ];

    let currentStep = 0;

    function updateWizard() {
        const step = wizardData[currentStep];
        document.getElementById('tooltipStep').textContent = (currentStep + 1) + '/22';
        document.getElementById('tooltipTitle').textContent = step.title;
        document.getElementById('tooltipFullName').textContent = step.fullName;
        document.getElementById('tooltipExplanation').textContent = step.explanation;
        document.getElementById('tooltipCalculation').textContent = step.calculation;
        document.getElementById('tooltipBalance').textContent = step.balance;

        const stepProgress = @json(__('messages.wizard_step_progress'))
            .replace(':step', currentStep + 1)
            .replace(':total', 22);
        document.getElementById('tooltipProgress').textContent = stepProgress;

        document.getElementById('prevBtn').style.display = currentStep === 0 ? 'none' : '';
        document.getElementById('nextBtn').style.display = currentStep === wizardData.length - 1 ? 'none' : '';
    }

    function nextStep() {
        if (currentStep < wizardData.length - 1) {
            currentStep++;
            updateWizard();
        }
    }

    function prevStep() {
        if (currentStep > 0) {
            currentStep--;
            updateWizard();
        }
    }

    function closeWizard() {
        document.getElementById('wizardOverlay').classList.remove('open');
        document.getElementById('closeHint').classList.remove('show');
    }

    document.getElementById('wizardOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeWizard();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeWizard();
    });

    // ── Scroll shadow indicator for sticky columns ──
    const tableScroll = document.getElementById('tableScroll');
    if (tableScroll) {
        tableScroll.addEventListener('scroll', function() {
            if (this.scrollLeft > 10) {
                this.classList.add('scrolling-right');
            } else {
                this.classList.remove('scrolling-right');
            }
        });
    }
</script>
@include('footer')
</body>
</html>
