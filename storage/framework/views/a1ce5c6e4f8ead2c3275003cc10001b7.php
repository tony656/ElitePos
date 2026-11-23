<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e(config("app.name")); ?> — Supplier Payments</title>
    <?php echo $__env->make("links", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
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
            gap: .4rem;
            align-items: center;
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

        /* Date group header */
        .date-group-row td { background: #f8fbfe; padding: .52rem .68rem; border-top: 1px solid #e5edf5; border-bottom: 1px solid #e5edf5; }
        .date-group-label { display: flex; align-items: center; gap: .4rem; font-size: .72rem; font-weight: 700; color: #064b86; text-transform: uppercase; letter-spacing: .05em; }
        .date-group-label i { color: var(--navy-light); font-size: .8rem; }

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
            min-width: 900px;
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

        @media (max-width: 768px) {
            .wrap { padding: .8rem; }
            .pg-header { padding: .25rem 0 .7rem; }
            .filters-row { flex-direction: column; align-items: stretch; }
            .filter-actions { width: 100%; justify-content: flex-end; }
            .metrics-grid { grid-template-columns: 1fr; }
            table { min-width: 700px; }
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
                <div class="header-icon"><i class="bi bi-cash-stack"></i></div>
                <div class="pg-title-text">
                    <h1>Supplier Payments</h1>
                    <p>View all supplier payment history</p>
                </div>
            </div>
            <div class="pg-right">
                <a href="<?php echo e(url('main-credit')); ?>" class="btn-header-outline">
                    <i class="bi bi-arrow-left"></i> Back to Credits
                </a>
                <button class="btn-header" onclick="window.print()">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
        </div>

        
        <div class="filters-card">
            <form method="GET" action="<?php echo e(url()->current()); ?>" style="display:contents;">
                <div class="filters-row">
                    
                    <?php if(isset($shops) && count($shops) > 0): ?>
                    <div style="flex:1; min-width:auto;">
                        <label class="filter-label">Shop</label>
                        <select name="shop" class="filter-ctrl" onchange="this.form.submit()" style="width:220px;">
                            <option value="">All Shops</option>
                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($shop['id']); ?>" <?php echo e(request('shop') == $shop['id'] ? 'selected' : ''); ?>>
                                <?php echo e($shop['name']); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    
                    <div style="display:flex; align-items:flex-end; gap:.3rem; white-space:nowrap;">
                        <div style="display:flex; flex-direction:column; gap:.1rem; margin-bottom:.1rem;">
                            <label class="filter-label" style="margin-bottom:0;">Date Range</label>
                            <div style="display:flex; gap:.3rem; align-items:end;">
                                <input type="date" name="date_from"
                                       value="<?php echo e(request('date_from', date('Y-m-d'))); ?>"
                                       class="filter-ctrl" style="padding-left:.6rem; width:130px;" placeholder="From">
                                <span style="font-size:.72rem; color:var(--slate-500); margin-top:.12rem;">—</span>
                                <input type="date" name="date_to"
                                       value="<?php echo e(request('date_to', date('Y-m-d'))); ?>"
                                       class="filter-ctrl" style="padding-left:.6rem; width:130px;" placeholder="To">
                            </div>
                        </div>
                        <div class="filter-actions" style="gap:.4rem;">
                            <?php if(request('date_from') || request('date_to') || request('shop')): ?>
                            <a href="<?php echo e(url()->current()); ?>" class="btn-reset">
                                <i class="bi bi-x"></i> Reset
                            </a>
                            <?php else: ?>
                            <button type="submit" class="btn-filter">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        
        <?php
            $allPayments = $query->flatten();
            $totalPayments = $allPayments->count();
            $totalAmount   = $allPayments->sum('amount');
            $supplierCount = $allPayments->pluck('supplier_name')->unique()->count();
            $dateFrom = request('date_from', date('Y-m-d'));
            $dateTo   = request('date_to', date('Y-m-d'));
        ?>
        <div class="metrics-grid">
            <div class="metric-card mc-navy">
                <div class="metric-inner">
                    <div class="metric-icon mi-navy"><i class="bi bi-receipt"></i></div>
                    <div><div class="metric-label">Total Payments</div><div class="metric-value"><?php echo e($totalPayments); ?></div></div>
                </div>
            </div>
            <div class="metric-card mc-emerald">
                <div class="metric-inner">
                    <div class="metric-icon mi-emerald"><i class="bi bi-cash-stack"></i></div>
                    <div><div class="metric-label">Total Amount</div><div class="metric-value" style="font-size:16px;"><?php echo e(number_format($totalAmount, 2)); ?></div></div>
                </div>
            </div>
            <div class="metric-card mc-amber">
                <div class="metric-inner">
                    <div class="metric-icon mi-amber"><i class="bi bi-shop"></i></div>
                    <div><div class="metric-label">Suppliers</div><div class="metric-value"><?php echo e($supplierCount); ?></div></div>
                </div>
            </div>
            <div class="metric-card mc-blue">
                <div class="metric-inner">
                    <div class="metric-icon mi-blue"><i class="bi bi-calendar3"></i></div>
                    <div><div class="metric-label">Date Range</div><div class="metric-value" style="font-size:14px;"><?php echo e(date('M j', strtotime($dateFrom . ' UTC'))); ?> - <?php echo e(date('M j, Y', strtotime($dateTo . ' UTC'))); ?></div></div>
                </div>
            </div>
        </div>

        
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-left">
                    <div class="panel-head-icon"><i class="bi bi-table"></i></div>
                    <span class="panel-title">Payments</span>
                    <span class="shop-pill"><?php echo e($totalPayments); ?> records</span>
                </div>
            </div>

            <div class="tbl-wrap">
                <?php if($query->isEmpty()): ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                    <h4>No Payments found</h4>
                    <p>There are currently no supplier payments for the selected filter</p>
                </div>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th width="4%">#</th>
                            <th>Supplier</th>
                            <th>Amount</th>
                            <th>Date Paid</th>
                            <th>Method</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rowIndex = 1; ?>
                        <?php $__currentLoopData = $query; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $payments): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="date-group-row">
                            <td colspan="6">
                                <div class="date-group-label">
                                    <i class="bi bi-calendar3"></i>
                                    <?php echo e(\Carbon\Carbon::parse($date)->format('l, F j, Y')); ?>

                                    <span>· <?php echo e($payments->count()); ?> <?php echo e(Str::plural('payment', $payments->count())); ?></span>
                                </div>
                            </td>
                        </tr>
                        <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="idx-cell"><?php echo e($rowIndex++); ?></td>
                            <td>
                                <div class="sup-cell">
                                    <div class="sup-avatar"><?php echo e(strtoupper(substr($payment->supplier_name, 0, 1))); ?></div>
                                    <span class="sup-name"><?php echo e($payment->supplier_name); ?></span>
                                </div>
                            </td>
                            <td><span class="num paid-val"><?php echo e(number_format($payment->amount, 2)); ?></span></td>
                            <td><span class="num"><?php echo e($payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') : ($payment->created_at ? \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d') : 'N/A')); ?></span></td>
                            <td>
                                <span style="text-transform:capitalize; font-size: .7rem; color: var(--slate-600);">
                                    <?php echo e($payment->payment_method ?: 'cash'); ?>

                                </span>
                            </td>
                            <td>
                                <div class="action-cell">
                                    <form action="<?php echo e(url('dltSupPay')); ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this payment?')">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo e($payment->id); ?>">
                                        <button type="submit" class="act-btn ab-del">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

    </div>
</main>
<?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH C:\Users\HomePC\Music\leruma pos\resources\views/main-paid.blade.php ENDPATH**/ ?>