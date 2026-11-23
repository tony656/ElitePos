<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e(config("app.name")); ?> - Paid Invoices</title>
    <?php echo $__env->make("links", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
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
        .mc-emerald { --mc: var(--emerald); }
        .mc-navy    { --mc: var(--navy); }
        .mc-amber   { --mc: var(--amber); }
        .mc-violet  { --mc: var(--violet); }
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

        .date-val { font-size: .74rem; color: #31516e; font-variant-numeric: tabular-nums; }
        .date-val span { display: block; font-size: .68rem; color: #7189a0; margin-top: 1px; }
        .cust-name { font-weight: 600; color: #164a78; font-size: .76rem; margin-bottom: .08rem; }
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

        /* Action buttons */
        .action-cell { display: flex; gap: .28rem; justify-content: center; }
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
        .action-icon-btn.delete { background: var(--rose) !important; }
        .action-icon-btn:hover { background: #064f91 !important; transform: translateY(-1px); }
        .action-icon-btn.delete:hover { background: var(--rose) !important; }

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

        .cust-results {
            position: fixed;
            z-index: 1000;
            background: var(--white);
            border: 1px solid #0b66b2;
            border-radius: var(--radius);
            max-height: 300px;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
            display: none;
            width: 340px;
        }
        .cust-item {
            padding: 0.6rem 0.9rem;
            cursor: pointer;
            border-bottom: 1px solid var(--slate-100);
            transition: background 0.15s;
        }
        .cust-item:last-child { border-bottom: none; }
        .cust-item:hover, .cust-item.selected { background: #edf6ff; border-left: 3px solid #0b68b4; padding-left: calc(0.9rem - 3px); }
        .cust-item-name { font-size: .8rem; font-weight: 600; color: var(--navy); }
        .cust-item-sub { font-size: .72rem; color: var(--slate-500); font-weight: 600; margin-top: 2px; }
        .cust-item-none { padding: 1rem; text-align: center; font-size: .78rem; color: var(--slate-400); }
        .selected-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: 4px;
            background: var(--emerald-pale);
            color: var(--emerald);
            padding: .22rem .55rem;
            border-radius: 20px;
            font-size: .68rem;
            font-weight: 700;
        }

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

        /* Delete confirmation */
        .btn-delete-small {
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
        .btn-delete-small:hover { background: var(--rose); color: #fff; border-color: var(--rose); transform: translateY(-1px); }

        @media (max-width: 768px) {
            .wrap { padding: .8rem; }
            .pg-header { padding: .25rem 0 .7rem; }
            .filters-row { flex-direction: column; align-items: stretch; }
            .filter-actions { width: 100%; justify-content: flex-end; }
            .metrics-grid { grid-template-columns: 1fr; }
            .table-wrap { max-height: none; }
        }
    </style>
</head>
<body>
    <?php echo $__env->make("sidenav", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <main class="main-content">
        
        <?php if(session('success')): ?>
        <div class="alert alert-success">
            <span><?php echo e(session('success')); ?></span>
            <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
        </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <span><?php echo e(session('error')); ?></span>
            <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
        </div>
        <?php endif; ?>

        <div class="wrap">

            
            
            <div class="pg-header">
                <div class="pg-left">
                    <div class="header-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <div class="pg-title-text">
                        <h1><?php echo e(__('messages.paid_invoices')); ?></h1>
                        <p><?php echo e(__('messages.view_all_paid_invoices')); ?></p>
                    </div>
                </div>
                <div class="pg-right">
                    <button class="btn-header" data-bs-toggle="modal" data-bs-target="#manualInvoiceModal">
                        <i class="bi bi-plus-lg"></i> <?php echo e(__('messages.manual_invoice')); ?>

                    </button>
                </div>
            </div>

            
            <div class="filters-card">
                <form method="GET" action="<?php echo e(url('paidInvoices')); ?>">
                    <div class="filters-row">
                        <div class="filter-group">
                            <label class="filter-label"><?php echo e(__('messages.shop_label')); ?></label>
                            <select name="shop" class="filter-ctrl">
                                <option value=""><?php echo e(__('messages.all_shops')); ?></option>
                                <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($shop['id']); ?>" <?php echo e($shopName == $shop['id'] ? 'selected' : ''); ?>>
                                    <?php echo e($shop['name']); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label"><?php echo e(__('messages.from')); ?></label>
                            <input type="date" name="start_date" class="filter-ctrl" value="<?php echo e($startDate ?? ''); ?>">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label"><?php echo e(__('messages.to')); ?></label>
                            <input type="date" name="end_date" class="filter-ctrl" value="<?php echo e($endDate ?? ''); ?>">
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="btn-filter">
                                <i class="bi bi-funnel"></i> <?php echo e(__('messages.filter')); ?>

                            </button>
                            <a href="<?php echo e(url('paidInvoices')); ?>" class="btn-reset">
                                <i class="bi bi-x"></i> <?php echo e(__('messages.reset')); ?>

                            </a>
                        </div>
                    </div>
                </form>
            </div>

            
            <div class="metrics-grid">
                <div class="metric-card mc-emerald">
                    <div class="metric-inner">
                        <div class="metric-icon mi-emerald"><i class="bi bi-cash-stack"></i></div>
                        <div>
                            <div class="metric-label"><?php echo e(__('messages.total_paid')); ?></div>
                            <div class="metric-value"><?php echo e(number_format($totalPaid)); ?></div>
                            <div style="font-size:10px; color:var(--slate-400); margin-top:2px;">TZS</div>
                        </div>
                    </div>
                </div>
                <div class="metric-card mc-navy">
                    <div class="metric-inner">
                        <div class="metric-icon mi-navy"><i class="bi bi-receipt"></i></div>
                        <div>
                            <div class="metric-label"><?php echo e(__('messages.payments_made')); ?></div>
                            <div class="metric-value"><?php echo e($paymentCount); ?></div>
                        </div>
                    </div>
                </div>
                <div class="metric-card mc-amber">
                    <div class="metric-inner">
                        <div class="metric-icon mi-amber"><i class="bi bi-file-earmark-check"></i></div>
                        <div>
                            <div class="metric-label"><?php echo e(__('messages.invoices_cleared')); ?></div>
                            <div class="metric-value"><?php echo e($uniqueInvoices); ?></div>
                        </div>
                    </div>
                </div>
                <div class="metric-card mc-violet">
                    <div class="metric-inner">
                        <div class="metric-icon mi-violet"><i class="bi bi-people"></i></div>
                        <div>
                            <div class="metric-label"><?php echo e(__('messages.customers')); ?></div>
                            <div class="metric-value"><?php echo e($customersWithPayments); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-left">
                        <div class="panel-head-icon"><i class="bi bi-list-check"></i></div>
                        <span class="panel-title"><?php echo e(__('messages.payment_history')); ?></span>
                    </div>
                    <span class="shop-pill">
                        <i class="bi bi-shop"></i> <?php echo e($shop->id ?? $shopName); ?>

                    </span>
                </div>

                <?php if($payments->count() > 0): ?>
                <div class="tbl-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('messages.date'); ?> &amp; Time</th>
                                <th><?php echo app('translator')->get('messages.customer'); ?></th>
                                <th><?php echo app('translator')->get('messages.invoice'); ?></th>
                                <th><?php echo app('translator')->get('messages.amount_paid'); ?></th>
                                <th><?php echo app('translator')->get('messages.shop_label'); ?></th>
                                <th style="text-align:right;"><?php echo app('translator')->get('messages.actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="date-val">
                                        <?php echo e(\Carbon\Carbon::parse($payment->created_at)->format('d M Y')); ?>

                                        <span><?php echo e(\Carbon\Carbon::parse($payment->created_at)->format('h:i A')); ?></span>
                                    </div>
                                </td>
                                <td class="cust-name"><?php echo e($payment->cName); ?></td>
                                <td>
                                    <span class="inv-badge">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <?php echo e($payment->orderId); ?>

                                    </span>
                                </td>
                                <td class="amount-val"><?php echo e(number_format($payment->amount)); ?> Tsh</td>
                                <?php
                                    $account = DB::table('accounts')->where('id', $payment->account)->first();
                                    $accountName = $account ? $account->name : 'Unknown';
                                ?>
                                <td><span class="shop-tag"><?php echo e($accountName); ?></span></td>
                                <td class="text-right">
                                    <form action="<?php echo e(url('deletePaidInvoice')); ?>" method="POST" class="delete-form" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="payment_id" value="<?php echo e($payment->id); ?>">
                                        <button type="submit" class="btn-delete-small" title="Delete" onclick="return confirmDelete(event, '<?php echo e($payment->orderId); ?>', <?php echo e($payment->amount); ?>)">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                    <h4><?php echo e(__('messages.no_payments_found')); ?></h4>
                    <p><?php echo e(__('messages.no_payments_match_filters')); ?></p>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </main>

    
    <div class="modal fade" id="manualInvoiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(__('messages.create_manual_invoice')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(url('createManualInvoice')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">

                        <div class="field">
                            <label class="field-label"><?php echo e(__('messages.shop_label')); ?> <span class="req">*</span></label>
                            <select class="field-input" name="account" id="shopSelect" required>
                                <option value=""><?php echo e(__('messages.select_a_shop')); ?></option>
                                <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($shop['id']); ?>"><?php echo e($shop['name']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="field" style="position:relative;">
                            <label class="field-label"><?php echo e(__('messages.customer')); ?> <span class="req">*</span></label>
                            <div class="modal-search-wrap">
                                <i class="bi bi-search"></i>
                                <input type="text" class="field-input" id="customerSearch"
                                       placeholder="<?php echo e(__('messages.search_by_name_or_phone')); ?>"
                                       name="customer_search" autocomplete="off" required>
                            </div>
                            <input type="hidden" name="customer_id"   id="customerId">
                            <input type="hidden" name="customer_name" id="customerNameHidden">
                            <div class="cust-results" id="customerSearchResults"></div>
                            <div id="selectedCustomerTag"></div>
                        </div>

                        <div class="field">
                            <label class="field-label"><?php echo e(__('messages.invoice_date')); ?> <span class="req">*</span></label>
                            <input type="date" class="field-input" name="invoice_date" value="<?php echo e(date('Y-m-d')); ?>" required>
                        </div>

                        <div class="field">
                            <label class="field-label"><?php echo e(__('messages.amount_tzs')); ?> <span class="req">*</span></label>
                            <div class="input-prefix-wrap">
                                <span class="input-prefix">Tsh</span>
                                <input type="number" name="amount" min="0" step="0.01" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="field-label"><?php echo e(__('messages.notes_optional')); ?></label>
                            <textarea class="field-input" name="notes" placeholder="Additional notes…"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer-custom">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal"><?php echo e(__('messages.cancel')); ?></button>
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-check-circle-fill"></i> <?php echo e(__('messages.create_invoice')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const custInput   = document.getElementById('customerSearch');
        const custResults = document.getElementById('customerSearchResults');
        const custTag     = document.getElementById('selectedCustomerTag');

        custInput && custInput.addEventListener('input', function () {
            const q = this.value.trim();
            const shopSelect = document.getElementById('shopSelect');
            const selectedShop = shopSelect ? shopSelect.value : '';

            if (q.length < 2) { custResults.style.display = 'none'; return; }
            if (!selectedShop) {
                custResults.innerHTML = '<div class="cust-item-none">Please select a shop first</div>';
                custResults.style.display = 'block';
                return;
            }

            fetch(`<?php echo e(url('searchCustomers')); ?>?query=${encodeURIComponent(q)}&account=${encodeURIComponent(selectedShop)}`)
                .then(r => r.json())
                .then(data => {
                    custResults.innerHTML = data.length
                        ? data.map(c => `
                            <div class="cust-item" onclick="selectCustomer(${c.id}, '${c.name.replace(/'/g,"\\'")}')">
                                <div class="cust-item-name">${c.name}</div>
                                <div class="cust-item-sub">${c.phone || 'No phone'} · Limit: ${Number(c.limits||0).toLocaleString()}</div>
                            </div>`).join('')
                        : '<div class="cust-item-none">No customers found</div>';
                    custResults.style.display = 'block';
                })
                .catch(() => {
                    custResults.innerHTML = '<div class="cust-item-none" style="color:var(--rose);">Error loading customers</div>';
                    custResults.style.display = 'block';
                });
        });

        function confirmDelete(event, invoiceId, amount) {
            event.preventDefault();

            if (confirm(`Are you sure you want to delete this payment?\n\nInvoice: ${invoiceId}\nAmount: Tsh ${Number(amount).toLocaleString()}`)) {
                event.target.closest('form').submit();
            }
            return false;
        }

        function selectCustomer(id, name) {
            document.getElementById('customerId').value         = id;
            document.getElementById('customerNameHidden').value = name;
            custInput.value = name;
            custResults.style.display = 'none';
            custTag.innerHTML = `<span class="selected-tag"><i class="bi bi-check-circle-fill"></i> ${name}</span>`;
        }

        document.addEventListener('click', function (e) {
            if (custInput && custResults &&
                !custInput.contains(e.target) && !custResults.contains(e.target)) {
                custResults.style.display = 'none';
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\HomePC\Music\leruma pos\resources\views/paidInvoices.blade.php ENDPATH**/ ?>