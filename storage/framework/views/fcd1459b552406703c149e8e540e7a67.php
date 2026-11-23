<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo app('translator')->get('messages.view_receivings'); ?></title>
    <?php echo $__env->make('links', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* =========================================================
           KESSY POS BLUE THEME
           Existing Blade markup, IDs, forms and JavaScript retained.
           ========================================================= */
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

            --r:    6px;
            --r-lg: 8px;
            --shadow-sm: 0 2px 8px rgba(8,61,108,.06);
            --shadow-md: 0 5px 16px rgba(8,61,108,.09);
            --shadow-lg: 0 10px 28px rgba(8,61,108,.13);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f3f7fb;
            color: var(--slate-800);
            min-height: 100vh;
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        /* Main layout */
        .main-wrap {
            max-width: 1800px;
            margin: 0 auto;
            padding: 1.1rem 1.25rem 1.5rem;
        }

        /* Page header — flat white strip */
        .pg-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            background: transparent;
            border: 0;
            border-radius: 0;
            padding: .25rem 0 .9rem;
            margin-bottom: .55rem;
            box-shadow: none;
        }

        .pg-title {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            color: #064b86;
            font-size: 1.42rem;
            font-weight: 700;
            letter-spacing: -.35px;
        }
        .pg-title span { color: #0871c2; }

        .pg-title-icon {
            width: 40px; height: 40px;
            background: #e8f2fc;
            border: 1px solid #cfe2f5;
            border-radius: var(--r-lg);
            display: flex; align-items: center; justify-content: center;
            color: #0866b2;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .btn-new-receiving {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: #0866b2;
            color: #fff;
            font-weight: 600;
            font-size: .75rem;
            padding: .5rem .8rem;
            border-radius: var(--r);
            border: 1px solid #0866b2;
            text-decoration: none;
            transition: all 0.18s;
            box-shadow: 0 3px 8px rgba(8,102,178,.18);
            letter-spacing: .01em;
        }
        .btn-new-receiving i { font-size: .82rem; }
        .btn-new-receiving:hover {
            background: #064f91;
            border-color: #064f91;
            transform: translateY(-1px);
            color: #fff;
        }

        /* Alerts */
        .alert-custom {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: .7rem .9rem;
            border-radius: var(--r-lg);
            font-size: .78rem;
            font-weight: 500;
            margin-bottom: .85rem;
        }
        .alert-success-custom {
            background: var(--emerald-pale);
            border-left: 3px solid var(--emerald);
            color: #056f58;
        }
        .alert-danger-custom {
            background: var(--rose-pale);
            border-left: 3px solid var(--rose);
            color: #c9314a;
        }

        /* Card panel */
        .card-panel {
            background: var(--white);
            border: 1px solid #dce7f1;
            border-radius: var(--r-lg);
            box-shadow: 0 2px 7px rgba(8,61,108,.045);
            overflow: hidden;
        }

        /* Toolbar — deep blue like table header */
        .toolbar {
            background: #07569b;
            padding: .65rem .9rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: .6rem;
        }

        .toolbar-left {
            display: flex;
            align-items: center;
            gap: .55rem;
            flex-wrap: wrap;
        }

        .toolbar-label {
            color: rgba(255,255,255,.85);
            font-size: .78rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .35rem;
        }
        .toolbar-label i { font-size: .82rem; color: #b8dcff; }

        .tbtn {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-family: 'DM Sans', sans-serif;
            font-size: .72rem;
            font-weight: 600;
            padding: .4rem .7rem;
            border-radius: var(--r);
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            letter-spacing: .01em;
        }
        .tbtn i { font-size: .78rem; }
        .tbtn:hover { transform: translateY(-1px); }

        .tbtn-success  { background: var(--emerald); color: #fff; border-color: var(--emerald); }
        .tbtn-success:hover { background: #056f58; border-color: #056f58; }
        .tbtn-primary  { background: #0866b2; color: #fff; border-color: #0866b2; }
        .tbtn-primary:hover { background: #064f91; border-color: #064f91; }
        .tbtn-warning  { background: #eaf4ff; color: #0756a6; border-color: #cfe2f5; }
        .tbtn-warning:hover { background: #0756a6; color: #fff; border-color: #0756a6; }
        .tbtn-danger   { background: var(--rose-pale); color: #c9314a; border-color: #f5b8c2; }
        .tbtn-danger:hover { background: var(--rose); color: #fff; border-color: var(--rose); }
        .tbtn-info     { background: #e8f3ff; color: #07569b; border-color: #cfe2f5; }
        .tbtn-info:hover { background: #07569b; color: #fff; border-color: #07569b; }
        .tbtn-ghost    { background: rgba(255,255,255,.1); color: #fff; border-color: rgba(255,255,255,.2); }
        .tbtn-ghost:hover { background: rgba(255,255,255,.2); }

        /* Filter bar */
        .filter-bar {
            background: #f5f9fd;
            border-bottom: 1px solid #dce7f1;
            padding: .6rem .9rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .55rem;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: .35rem;
        }

        .filter-label {
            font-size: .66rem;
            font-weight: 700;
            color: var(--slate-500);
            text-transform: uppercase;
            letter-spacing: .06em;
            white-space: nowrap;
        }

        .filter-input {
            font-family: 'DM Sans', sans-serif;
            font-size: .76rem;
            padding: .35rem .6rem;
            border: 1px solid #d4e1ed;
            border-radius: var(--r);
            background: #fff;
            outline: none;
            color: var(--slate-800);
            transition: border-color .18s, box-shadow .18s;
            appearance: none;
        }
        select.filter-input {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%238da4bb' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .55rem center;
            padding-right: 1.8rem;
            cursor: pointer;
        }
        .filter-input:focus {
            border-color: #0b68b4;
            box-shadow: 0 0 0 3px rgba(11,104,180,.09);
        }

        /* Summary bar */
        .summary-bar {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: .65rem .9rem;
            background: #f5f9fd;
            border-bottom: 1px solid #dce7f1;
            flex-wrap: wrap;
        }

        .summary-stat {
            display: flex;
            flex-direction: column;
            gap: .1rem;
        }

        .summary-stat-label {
            font-size: .62rem;
            font-weight: 700;
            color: var(--slate-500);
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .summary-stat-value {
            font-family: 'DM Mono', monospace;
            font-size: .85rem;
            font-weight: 600;
            color: #084b87;
            font-variant-numeric: tabular-nums;
        }

        /* Table */
        .table-scroll {
            overflow-x: auto;
            overflow-y: auto;
            max-height: calc(100vh - 360px);
        }

        table.recv-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .74rem;
        }

        table.recv-table thead th {
            background: #07569b;
            color: #fff;
            font-size: .66rem;
            font-weight: 600;
            text-transform: none;
            letter-spacing: .015em;
            padding: .62rem .68rem;
            border: 1px solid rgba(255,255,255,.13);
            position: sticky;
            top: 0;
            z-index: 5;
            text-align: left;
            white-space: nowrap;
        }

        table.recv-table tbody td {
            padding: .52rem .68rem;
            border: 1px solid #e5edf5;
            vertical-align: middle;
            color: #244866;
        }

        table.recv-table tbody tr:nth-child(even) td { background: #f8fbfe; }
        table.recv-table tbody tr:hover td { background: #edf6ff; }

        .product-name { font-weight: 600; color: #164a78; font-size: .76rem; }
        .mono { font-family: 'DM Mono', monospace; font-size: .72rem; color: #31516e; }
        .amount-total { font-weight: 600; color: #084b87; }

        /* Badges */
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            font-size: .68rem;
            font-weight: 700;
            padding: .22rem .55rem;
            border-radius: 20px;
            letter-spacing: .01em;
            white-space: nowrap;
        }
        .badge-cash     { background: var(--emerald-pale); color: #056f58; }
        .badge-credit   { background: var(--amber-pale);   color: #07569b; }
        .badge-approved { background: var(--emerald-pale); color: #056f58; }
        .badge-pending  { background: var(--slate-100);    color: var(--slate-600); }
        .badge-returned { background: var(--violet-pale);  color: var(--violet); }

        .person-cell {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .72rem;
        }
        .person-avatar {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: linear-gradient(135deg, #0756a6, #0870bd);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .62rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .action-wrap { display: flex; gap: .25rem; align-items: center; }
        .act-btn {
            width: 27px;
            height: 26px;
            border-radius: 5px;
            border: 1px solid;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
            background: #fff;
            font-size: .72rem;
            font-family: inherit;
        }
        .act-btn i { font-size: .72rem; }
        .act-btn:hover { transform: translateY(-1px); }
        .act-delete  { background: var(--rose-pale); color: #c9314a; border-color: #f5b8c2; }
        .act-delete:hover  { background: var(--rose); color: #fff; border-color: var(--rose); }
        .act-approve { background: var(--emerald-pale); color: #056f58; border-color: #b8e5d5; }
        .act-approve:hover { background: var(--emerald); color: #fff; border-color: var(--emerald); }
        .act-undo    { background: var(--amber-pale); color: #0756a6; border-color: #cfe2f5; }
        .act-undo:hover { background: #0756a6; color: #fff; border-color: #0756a6; }
        .act-print   { background: #e8f3ff; color: #07569b; border-color: #cfe2f5; }
        .act-print:hover { background: #07569b; color: #fff; border-color: #07569b; }

        .form-check-input {
            width: 15px;
            height: 15px;
            cursor: pointer;
            border: 1.5px solid #c5d5e5;
            border-radius: 4px;
        }
        .form-check-input:checked {
            background-color: #0866b2;
            border-color: #0866b2;
        }
        .form-check-input:focus {
            border-color: #0b68b4;
            box-shadow: 0 0 0 3px rgba(11,104,180,.09);
        }

        .empty-state { text-align: center; padding: 3rem 1rem; color: var(--slate-400); }
        .empty-state i { font-size: 2.2rem; margin-bottom: .65rem; display: block; opacity: .35; }
        .empty-state p { font-size: .78rem; }

        .collapsed-hint {
            padding: 2rem 1.25rem;
            text-align: center;
            color: var(--slate-400);
            font-size: .78rem;
        }
        .collapsed-hint i { margin-right: .3rem; }

        /* PRINT STYLES */
        @media print {
            .sidenav, .pg-header, .toolbar, .filter-bar, .summary-bar,
            .act-btn, .action-wrap form, .tbtn, .btn-new-receiving,
            #toggleBtn, .toolbar-left, .collapsed-hint, .alert-custom,
            .pg-header .btn-new-receiving, .filter-bar form,
            .action-wrap .act-print, .action-wrap .act-delete,
            .action-wrap .act-approve, .action-wrap .act-undo,
            .col-md-9, .col-lg-10, .px-md-4, .row, [class*="col-"] {
                display: none !important;
            }

            body, .main-wrap, .card-panel, .receiving-print-area {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            .receiving-print-area {
                display: block !important;
                padding: 20px !important;
            }

            .print-header {
                display: flex !important;
                justify-content: space-between !important;
                margin-bottom: 20px !important;
                border-bottom: 2px solid #084b87 !important;
                padding-bottom: 10px !important;
            }

            .print-title {
                font-size: 22px !important;
                font-weight: bold !important;
                color: #084b87 !important;
            }

            .print-date {
                font-size: 12px !important;
                color: #607b96 !important;
            }

            .print-summary {
                margin: 15px 0 !important;
                padding: 10px !important;
                background: #f4f9fd !important;
                border-radius: 8px !important;
            }

            .print-table {
                width: 100% !important;
                border-collapse: collapse !important;
                margin-top: 15px !important;
            }

            .print-table th, .print-table td {
                border: 1px solid #dce7f1 !important;
                padding: 8px !important;
                text-align: left !important;
            }

            .print-table th {
                background: #eaf3fb !important;
                font-weight: bold !important;
            }

            .receiving-print-area {
                display: block !important;
            }
        }

        .receiving-print-area {
            display: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-wrap { padding: .8rem; }
            .pg-title { font-size: 1.2rem; }
            .pg-title-icon { width: 34px; height: 34px; font-size: .9rem; }
            .toolbar { padding: .55rem .75rem; }
            .toolbar-left { width: 100%; }
            .filter-bar { padding: .5rem .75rem; }
            .summary-bar { gap: 1rem; padding: .5rem .75rem; }
            .summary-stat-value { font-size: .78rem; }
            .table-scroll { max-height: none; }
        }
    </style>
</head>
<body>
        <?php echo $__env->make('sidenav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <main class="main-content">
            <div class="main-wrap">

                
                <div class="pg-header">
                    <div class="pg-title">
                        <div class="pg-title-icon"><i class="bi bi-box-seam-fill"></i></div>
                        <?php echo e(__('messages.view_receivings_page_header')); ?>

                    </div>
                    <a href="<?php echo e(url('make-receiving')); ?>" class="btn-new-receiving">
                        <i class="bi bi-plus-circle-fill"></i> <?php echo e(__('messages.new_receiving')); ?>

                    </a>
                </div>

                
                <?php if(session('success')): ?>
                <div class="alert-custom alert-success-custom">
                    <i class="bi bi-check-circle-fill"></i> <?php echo e(session('success')); ?>

                </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                <div class="alert-custom alert-danger-custom">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?php echo e(session('error')); ?>

                </div>
                <?php endif; ?>

                
                <div class="card-panel">

                    
                    <div class="toolbar">
                        <div class="toolbar-left">
                            <button type="button" class="tbtn tbtn-ghost" onclick="toggleTableData()" id="toggleBtn">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                                <span id="toggleText">Show</span>
                            </button>
                            <div class="toolbar-label">
                                <i class="bi bi-receipt"></i> <?php echo e(__('messages.receivings_list')); ?>

                            </div>
                        </div>
                        <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                            <?php if(canUser('print_receiving')): ?>
                            <button type="button" class="tbtn tbtn-info" onclick="printSelectedItems()">
                                <i class="bi bi-printer-fill"></i> Print Selected
                            </button>
                            <button type="button" class="tbtn tbtn-info" onclick="printAllItems()" style="background:#07569b;border-color:#07569b;color:#fff;">
                                <i class="bi bi-printer"></i> Print All
                            </button>
                            <?php endif; ?>
                            <?php if(canUser('approve_receiving')): ?>
                            <form method="POST" action="<?php echo e(route('approve-selected-receivings')); ?>" id="approveSelectedForm">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="shop" value="<?php echo e($shopFilter); ?>">
                                <button type="submit" class="tbtn tbtn-primary" onclick="return confirm('Approve selected receivings?')">
                                    <i class="bi bi-check-circle"></i> Approve Selected
                                </button>
                            </form>
                            <?php endif; ?>
                            <?php if(canUser('undo_receiving')): ?>
                            <form method="POST" action="<?php echo e(route('undo-receivings')); ?>" id="undoForm">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="tbtn tbtn-warning" onclick="return confirm('Undo selected receivings?')">
                                    <i class="bi bi-arrow-counterclockwise"></i> Undo
                                </button>
                            </form>
                            <?php endif; ?>
                            <?php if(canUser('delete_receiving')): ?>
                            <form method="POST" action="<?php echo e(route('delete-selected-receivings')); ?>" id="deleteSelectedForm">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="tbtn tbtn-danger" onclick="return confirm('Delete selected receivings?')">
                                    <i class="bi bi-trash3"></i> Delete Selected
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="filter-bar">
                        <form method="GET" action="<?php echo e(url('view-receivings')); ?>" style="display:contents;">
                            <div class="filter-group">
                                <span class="filter-label">Shop</span>
                                <select name="shop" class="filter-input" onchange="this.form.submit()">
                                    <option value="" <?php echo e(empty($shopFilter) ? 'selected' : ''); ?>>All Shops</option>
                                    <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($shop['id']); ?>" <?php echo e($shopFilter == $shop['id'] ? 'selected' : ''); ?>>
                                            <?php echo e($shop['name']); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="filter-group">
                                <span class="filter-label">Date</span>
                                <input type="date" name="date" class="filter-input" value="<?php echo e($selectedDate); ?>" onchange="this.form.submit()">
                            </div>
                            <div class="filter-group">
                                <span class="filter-label">From</span>
                                <input type="date" name="from_date" class="filter-input" value="<?php echo e($fromDate); ?>" onchange="this.form.submit()">
                            </div>
                            <div class="filter-group">
                                <span class="filter-label">To</span>
                                <input type="date" name="to_date" class="filter-input" value="<?php echo e($toDate); ?>" onchange="this.form.submit()">
                            </div>
                            <div class="filter-group">
                                <span class="filter-label">Status</span>
                                <select name="status" class="filter-input" onchange="this.form.submit()">
                                    <option value="all" <?php echo e($statusFilter == 'all' ? 'selected' : ''); ?>>All</option>
                                    <option value="Pending" <?php echo e($statusFilter == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                    <option value="Approved" <?php echo e($statusFilter == 'Approved' ? 'selected' : ''); ?>>Approved</option>
                                    <option value="Returned" <?php echo e($statusFilter == 'Returned' ? 'selected' : ''); ?>>Returned</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    
                    <?php
                        $totalQty   = $products->sum('quantity');
                        $totalValue = $products->sum(fn($p) => ($p->quantity ?? 0) * ($p->price ?? 0));
                        $totalReturnValue = $totalReturnValue ?? 0;
                        $approvedProducts = $products->where('status', 'Approved');
                        $approvedQty = $approvedProducts->sum('quantity');
                        $approvedValue = $approvedProducts->sum(fn($p) => ($p->quantity ?? 0) * ($p->price ?? 0));
                        $pendingQty = $totalQty - $approvedQty;
                        $pendingValue = $products->where('status', '!=', 'Approved')->sum(fn($p) => (($p->requestedQuantity ?? 0) - ($p->quantity ?? 0)) * ($p->price ?? 0));
                    ?>
                    <div class="summary-bar">
                        <div class="summary-stat"><span class="summary-stat-label">Items</span><span class="summary-stat-value"><?php echo e(number_format($products->count())); ?></span></div>
                        <div class="summary-stat"><span class="summary-stat-label">Total Qty</span><span class="summary-stat-value"><?php echo e(number_format($totalQty)); ?></span></div>
                        <div class="summary-stat"><span class="summary-stat-label">Total Value</span><span class="summary-stat-value">Tsh <?php echo e(number_format($totalValue, 2)); ?></span></div>
                        <div class="summary-stat"><span class="summary-stat-label">Approved Qty</span><span class="summary-stat-value" style="color:#07866b;"><?php echo e(number_format($approvedQty)); ?></span></div>
                        <div class="summary-stat"><span class="summary-stat-label">Approved Value</span><span class="summary-stat-value" style="color:#07866b;">Tsh <?php echo e(number_format($approvedValue, 2)); ?></span></div>
                        <div class="summary-stat"><span class="summary-stat-label">Pending / Diff</span><span class="summary-stat-value" style="color:#07569b;"><?php echo e(number_format($pendingQty)); ?></span></div>
                        <div class="summary-stat"><span class="summary-stat-label">Pending Value</span><span class="summary-stat-value" style="color:#07569b;">Tsh <?php echo e(number_format($pendingValue, 2)); ?></span></div>
                        <div class="summary-stat"><span class="summary-stat-label">Return Value</span><span class="summary-stat-value"><?php echo e(number_format($totalReturnValue, 2)); ?></span></div>
                    </div>

                    
                    <div id="receivingsBody" style="display:none;">
                        <div class="table-scroll">
                            <table class="recv-table" id="receivingsTable">
                                <thead>
                                    <tr>
                                        <th width="36"><input type="checkbox" id="selectAll" class="form-check-input" onclick="toggleSelectAll()"></th>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Mainstore</th>
                                        <!--<th>Req.</th>-->
                                        <th>Leruma.</th>
                                        <th>Diff.</th>
                                        <th>Cost</th>
                                        <th>Total</th>
                                        <!--<th>Status</th>-->
                                        <th>Payment</th>
                                        <!--<th>Supplier</th>-->
                                        <th>Allocated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                                    <tr data-product-id="<?php echo e($item->productId); ?>" data-product-name="<?php echo e($item->productName); ?>"
                                        data-quantity="<?php echo e($item->quantity); ?>" data-price="<?php echo e($item->price); ?>" 
                                        data-status="<?php echo e($item->status); ?>" data-payment="<?php echo e($item->isDebt == 1 ? 'Credit' : 'Cash'); ?>"
                                        data-supplier="<?php echo e(DB::table('accounts')->where('id', $item->supplier)->value('name') ?? 'Unknown'); ?>"
                                        data-allocated="<?php echo e(DB::table('accounts')->where('id', $item->account)->value('name') ?? 'Unknown'); ?>"
                                        data-date="<?php echo e(date('M d, Y', strtotime($item->created_at))); ?>"
                                        data-requested="<?php echo e($item->requestedQuantity); ?>" data-diff="<?php echo e($item->quantityDiff); ?>"
                                        data-approved-return="<?php echo e($item->approvedReturnQuantity ?? 0); ?>" data-pending-return="<?php echo e($item->unapprovedReturnQuantity ?? 0); ?>">
                                        <td><input type="checkbox" name="product_ids[]" value="<?php echo e($item->id); ?>" class="item-checkbox form-check-input"></td>
                                        <td style="color:var(--slate-400);font-size:.72rem;"><?php echo e($index + 1); ?></td>
                                        <td style="white-space:nowrap;font-size:.74rem;"><?php echo e(date('M d, Y', strtotime($item->created_at))); ?></td>
                                        <td><span class="product-name"><?php echo e($item->productName); ?></span></td>
                                        <td><span class="mono"><?php echo e(number_format($item->quantity - (($item->approvedReturnQuantity ?? 0)))); ?></span></td>
                                         <td><span class="mono">
                                            <?php echo e(($item->status == 'Approved' ? $item->quantity : 0) - ($item->approvedReturnQuantity ?? 0) - ($item->unapprovedReturnQuantity ?? 0)); ?>

                                        </span></td>
                                         <td><span class="mono"><?php echo e(number_format($item->quantity - ($item->status == 'Approved' ? $item->quantity : 0) - ($item->unapprovedReturnQuantity ?? 0))); ?></span></td>
                                        <td><span class="mono">Tsh <?php echo e(number_format($item->price, 2)); ?></span></td>
                                        <td><span class="mono amount-total">Tsh <?php echo e(number_format(($item->quantity - ($item->approvedReturnQuantity)) * $item->price, 2)); ?></span></td>
                                        <td>
                                            <?php if($item->isDebt == 1): ?><span class="badge-pill badge-credit">Credit</span>
                                            <?php else: ?><span class="badge-pill badge-cash">Cash</span><?php endif; ?>
                                         </td>
                                        <td>
                                            <div class="person-cell"><div class="person-avatar"><?php echo e(substr(DB::table('accounts')->where('id', $item->account)->value('name') ?? 'U', 0, 2)); ?></div>
                                            <?php echo e(DB::table('accounts')->where('id', $item->account)->value('name') ?? 'Unknown'); ?></div>
                                         </td>
                                        <td>
                                            <div class="action-wrap">
                                                <form method="post">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" value="<?php echo e($item->id); ?>" name="product_id">
                                                    <input type="hidden" name="action" value="delete">
                                                    <?php if(canUser('delete_receiving')): ?>
                                                    <button formaction="<?php echo e(url('dltrestock')); ?>" class="act-btn act-delete" onclick="return confirm('Delete?')"><i class="bi bi-trash3"></i>
                                                    </button>                                        
                                                    <?php endif; ?>
                                                </form>
                                                <?php if($item->status != 'Approved' && $item->status != 'Returned'): ?>
                                                <form method="post">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="product_id" value="<?php echo e($item->id); ?>">
                                                    <?php if(canUser('approve_receiving')): ?>
                                                    <button formaction="<?php echo e(url('restockProd')); ?>" class="act-btn act-approve"><i class="bi bi-check-lg"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                </form>
                                                <?php endif; ?>
                                                <?php if(canUser('print_receiving')): ?>
                                                <button type="button" class="act-btn act-print" onclick="printSingleItem(this)" data-id="<?php echo e($item->id); ?>"><i class="bi bi-printer"></i></button>
                                                <?php endif; ?>
                                            </div>
                                         </td>
                                     </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="14"><div class="empty-state"><i class="bi bi-inbox"></i><p>No receivings found.</p></div></td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="collapsedHint"><div class="collapsed-hint"><i class="bi bi-table"></i> Click <strong>Show</strong> to expand the receivings table</div></div>
                </div>
            </div>
        </main>

    
    <div id="printArea" class="receiving-print-area"></div>

    <script>
    const STORAGE_KEY = 'receivingsTableVisible';

    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem(STORAGE_KEY);
        updateToggleState(saved === 'true');
    });

    function toggleTableData() {
        const body = document.getElementById('receivingsBody');
        const isHidden = body.style.display === 'none';
        localStorage.setItem(STORAGE_KEY, isHidden);
        updateToggleState(isHidden);
    }

    function updateToggleState(show) {
        const body = document.getElementById('receivingsBody');
        const hint = document.getElementById('collapsedHint');
        const icon = document.getElementById('toggleIcon');
        const text = document.getElementById('toggleText');
        if (show) {
            body.style.display = '';
            hint.style.display = 'none';
            icon.className = 'bi bi-eye-slash';
            text.textContent = 'Hide';
        } else {
            body.style.display = 'none';
            hint.style.display = '';
            icon.className = 'bi bi-eye';
            text.textContent = 'Show';
        }
    }

    function toggleSelectAll() {
        const all = document.getElementById('selectAll').checked;
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = all);
    }

    // PRINT FUNCTIONS
    function printSingleItem(btn) {
        const row = btn.closest('tr');
        const items = [getItemDataFromRow(row)];
        generatePrint(items, 'Single Item');
    }

    function printSelectedItems() {
        const selectedRows = document.querySelectorAll('.item-checkbox:checked');
        if (selectedRows.length === 0) {
            alert('Please select at least one item to print.');
            return;
        }
        const items = [];
        selectedRows.forEach(cb => {
            const row = cb.closest('tr');
            items.push(getItemDataFromRow(row));
        });
        generatePrint(items, 'Selected Items');
    }

    function printAllItems() {
        const allRows = document.querySelectorAll('#receivingsTable tbody tr');
        if (allRows.length === 0 || (allRows.length === 1 && allRows[0].querySelector('.empty-state'))) {
            alert('No items to print.');
            return;
        }
        const items = [];
        allRows.forEach(row => {
            if (!row.querySelector('.empty-state')) {
                items.push(getItemDataFromRow(row));
            }
        });
        generatePrint(items, 'All Receivings');
    }

    function getItemDataFromRow(row) {
        return {
            date: row.cells[2]?.innerText || row.querySelector('td:nth-child(3)')?.innerText || '',
            product: row.querySelector('.product-name')?.innerText || row.cells[3]?.innerText || '',
            quantity: row.cells[4]?.innerText || '',
            requested: row.cells[5]?.innerText || '',
            diff: row.cells[6]?.innerText || '',
            price: row.cells[7]?.innerText || '',
            total: row.cells[8]?.innerText || '',
            status: row.cells[9]?.innerText?.trim() || '',
            payment: row.cells[10]?.innerText?.trim() || '',
            supplier: row.cells[11]?.innerText?.trim() || '',
            allocated: row.cells[12]?.innerText?.trim() || ''
        };
    }

    function generatePrint(items, title) {
        const totalQty = items.reduce((sum, item) => {
            const qty = parseFloat(item.quantity?.replace(/,/g, '')) || 0;
            return sum + qty;
        }, 0);
        
        const totalValue = items.reduce((sum, item) => {
            const total = parseFloat(item.total?.replace(/[^0-9.-]/g, '')) || 0;
            return sum + total;
        }, 0);

        const shopName = document.querySelector('.filter-bar select[name="shop"]')?.selectedOptions[0]?.innerText || 'All Shops';
        const currentDate = new Date().toLocaleString();

        let html = `
            <div class="print-header">
                <div class="print-title">RECEIVINGS REPORT</div>
                <div class="print-date">Printed: ${currentDate}</div>
            </div>
            <div class="print-summary">
                <strong>Shop:</strong> ${shopName} &nbsp;|&nbsp;
                <strong>Total Items:</strong> ${items.length} &nbsp;|&nbsp;
                <strong>Total Quantity:</strong> ${totalQty} &nbsp;|&nbsp;
                <strong>Total Value:</strong> Tsh ${totalValue.toFixed(2)}
            </div>
            <table class="print-table">
                <thead>
                    <tr><th>#</th><th>Date</th><th>Product</th><th>Qty</th><th>Req.</th><th>Diff.</th><th>Unit Price</th><th>Total</th></tr>
                </thead>
                <tbody>
        `;

        items.forEach((item, idx) => {
            html += `<tr>
                        <td>${idx + 1}</td>
                        <td>${item.date}</td>
                        <td>${item.product}</td>
                        <td>${item.quantity}</td>
                        <td>${item.requested || ''}</td>
                        <td>${item.diff || ''}</td>
                        <td>${item.price}</td>
                        <td>${item.total}</td>
                     </tr>`;
        });

        html += `
                </tbody>
            </table>
            <div style="margin-top:20px; text-align:center; font-size:10px; color:#999;">
                Generated from Receiving System | ${new Date().toLocaleDateString()}
            </div>
        `;

        const printArea = document.getElementById('printArea');
        printArea.innerHTML = html;
        printArea.style.display = 'block';
        
        window.print();
        
        // Reset after print
        setTimeout(() => {
            printArea.style.display = 'none';
            printArea.innerHTML = '';
        }, 500);
    }

    // Form submission handlers
    function collectSelected(form) {
        form.querySelectorAll('input[name="product_ids[]"]').forEach(i => i.remove());
        document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'product_ids[]';
            inp.value = cb.value;
            form.appendChild(inp);
        });
    }

    document.getElementById('approveSelectedForm')?.addEventListener('submit', function(e) {
        if (!document.querySelectorAll('.item-checkbox:checked').length) { e.preventDefault(); alert('Select at least one item.'); }
        else collectSelected(this);
    });
    document.getElementById('undoForm')?.addEventListener('submit', function(e) {
        if (!document.querySelectorAll('.item-checkbox:checked').length) { e.preventDefault(); alert('Select at least one item.'); }
        else collectSelected(this);
    });
    document.getElementById('deleteSelectedForm')?.addEventListener('submit', function(e) {
        if (!document.querySelectorAll('.item-checkbox:checked').length) { e.preventDefault(); alert('Select at least one item.'); }
        else collectSelected(this);
    });
    </script>
    <?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</body>
</html><?php /**PATH C:\Users\HomePC\Music\leruma pos\resources\views/viewReceivings.blade.php ENDPATH**/ ?>