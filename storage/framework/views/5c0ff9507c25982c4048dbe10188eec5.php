<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e(config("app.name")); ?> - Shop Debtors</title>
    <?php echo $__env->make("links", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <style>
        :root {
            --pos-blue: #0759A6;
            --pos-blue-dark: #06477F;
            --pos-blue-mid: #0B70C2;
            --pos-blue-soft: #EAF3FC;
            --pos-green: #07966B;
            --pos-green-soft: #E9F8F1;
            --pos-red: #E33E52;
            --pos-red-soft: #FFF0F1;
            --pos-purple: #7B3FE4;
            --pos-purple-soft: #F2EDFF;
            --pos-orange: #F07824;
            --pos-orange-soft: #FFF3E9;
            --pos-ink: #19324D;
            --pos-muted: #71849A;
            --pos-border: #DCE6F0;
            --pos-page: #F2F6FB;
            --pos-white: #FFFFFF;
            --pos-radius: 10px;
            --pos-shadow: 0 2px 8px rgba(28, 66, 105, .045);
        }

        *, *::before, *::after { box-sizing: border-box; }
        body {
            min-height: 100vh;
            margin: 0;
            color: var(--pos-ink);
            background: var(--pos-page);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 13px;
        }
        main.main-content {
            padding: 1.25rem 1.4rem 2.25rem !important;
            max-width: 1800px;
            margin-left: auto;
            margin-right: auto;
        }
        button, input { font: inherit; }
        a { -webkit-tap-highlight-color: transparent; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #EDF2F7; }
        ::-webkit-scrollbar-thumb { background: #C5D3E1; border-radius: 8px; }

        /* Alerts */
        .alert {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 14px;
            margin-bottom: 12px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
        }
        .alert-success { color: #087A59; background: var(--pos-green-soft); border-color: #CBECDD; }
        .alert-danger { color: #C92D43; background: var(--pos-red-soft); border-color: #F5D2D8; }
        .alert .btn-close { font-size: 11px; }

        /* Back navigation */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin: 0 0 11px 2px;
            color: #647D95;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            transition: .18s ease;
        }
        .back-link:hover { color: var(--pos-blue); }

        /* Page header */
        .page-top {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            overflow: hidden;
            padding: 15px 18px;
            margin-bottom: 13px;
            color: #fff;
            background: linear-gradient(110deg, #064B8D 0%, #0870C5 100%);
            border-radius: 10px;
            box-shadow: 0 4px 13px rgba(5, 75, 141, .14);
        }
        .page-top::before, .page-top::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.065);
            pointer-events: none;
        }
        .page-top::before { width: 170px; height: 170px; right: -30px; top: -95px; }
        .page-top::after { width: 100px; height: 100px; right: 110px; bottom: -72px; }
        .page-heading {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 11px;
        }
        .page-heading-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            color: #fff;
            background: rgba(255,255,255,.16);
            border: 1px solid rgba(255,255,255,.17);
            border-radius: 9px;
            font-size: 19px;
        }
        .page-top h4 {
            margin: 0;
            color: #fff;
            font-size: 16px;
            line-height: 1.35;
            font-weight: 700;
            letter-spacing: -.2px;
        }
        .page-top p {
            margin: 3px 0 0;
            color: rgba(255,255,255,.78);
            font-size: 11px;
        }
        .page-top .search-wrap {
            position: relative;
            z-index: 1;
            width: min(270px, 100%);
        }
        .search-wrap i {
            position: absolute;
            top: 50%;
            left: 11px;
            transform: translateY(-50%);
            color: #8196AA;
            pointer-events: none;
            font-size: 12px;
        }
        .search-input {
            width: 100%;
            height: 36px;
            padding: 7px 11px 7px 32px;
            color: var(--pos-ink);
            background: #fff;
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 7px;
            outline: none;
            font-size: 11.5px;
            transition: .18s ease;
        }
        .search-input::placeholder { color: #91A2B3; }
        .search-input:focus {
            border-color: #B7D9F5;
            box-shadow: 0 0 0 3px rgba(255,255,255,.16);
        }

        /* Shop information panel */
        .shop-banner {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            margin-bottom: 13px;
            overflow: hidden;
            background: #fff;
            border: 1px solid var(--pos-border);
            border-radius: var(--pos-radius);
            box-shadow: var(--pos-shadow);
        }
        .shop-banner::before {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 3px;
            background: var(--pos-blue);
        }
        .shop-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            color: #fff;
            background: linear-gradient(145deg, #0874C9, #07529A);
            border-radius: 8px;
            font-size: 17px;
            box-shadow: 0 3px 8px rgba(7, 89, 166, .16);
        }
        .shop-banner-name { color: #153B61; font-size: 12px; font-weight: 700; }
        .shop-banner-loc { margin-top: 2px; color: #8497A9; font-size: 10.5px; }

        /* Statistics cards */
        .metrics-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 11px;
            margin-bottom: 14px;
        }
        .metric {
            position: relative;
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
            overflow: hidden;
            padding: 12px 13px;
            background: #fff;
            border: 1px solid var(--pos-border);
            border-radius: 10px;
            box-shadow: var(--pos-shadow);
        }
        .metric::after {
            content: "";
            position: absolute;
            top: -29px;
            right: -20px;
            width: 82px;
            height: 82px;
            border-radius: 50%;
            background: currentColor;
            opacity: .035;
        }
        .metric:nth-child(1) { color: var(--pos-blue); background: #F0F7FF; border-color: #D9EAFB; }
        .metric:nth-child(2) { color: var(--pos-red); background: #FFF4F4; border-color: #F8DEDF; }
        .metric:nth-child(3) { color: var(--pos-green); background: #EFFAF5; border-color: #D5F0E3; }
        .metric:nth-child(4) { color: var(--pos-purple); background: #F6F2FF; border-color: #E6DCFA; }
        .metric-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            color: #fff;
            border-radius: 9px;
            font-size: 17px;
            box-shadow: 0 3px 8px rgba(0,0,0,.09);
        }
        .metric:nth-child(1) .metric-icon { background: linear-gradient(145deg,#0874C9,#07529A); }
        .metric:nth-child(2) .metric-icon { background: linear-gradient(145deg,#F05265,#D92F49); }
        .metric:nth-child(3) .metric-icon { background: linear-gradient(145deg,#12AD7C,#07845D); }
        .metric:nth-child(4) .metric-icon { background: linear-gradient(145deg,#9258ED,#6D35D0); }
        .metric-info { min-width: 0; }
        .metric-label {
            margin-bottom: 2px;
            color: #71849A;
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .045em;
        }
        .metric-value {
            color: var(--pos-blue-dark);
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 20px;
            line-height: 1.25;
            font-weight: 700;
            letter-spacing: -.5px;
            white-space: nowrap;
        }
        .metric:nth-child(2) .metric-value { color: #D92F49; }
        .metric:nth-child(3) .metric-value { color: #07845D; }
        .metric:nth-child(4) .metric-value { color: #7138D0; }

        /* Table container and toolbar */
        .table-card {
            overflow: hidden;
            background: #fff;
            border: 1px solid var(--pos-border);
            border-radius: 10px;
            box-shadow: var(--pos-shadow);
        }
        .table-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 11px 14px;
            background: #fff;
            border-bottom: 1px solid #E5EDF5;
        }
        .table-card-title {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #173D63;
            font-size: 11.5px;
            font-weight: 700;
        }
        .table-card-title i { color: var(--pos-blue); font-size: 14px; }
        .record-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 20px;
            padding: 0 6px;
            color: var(--pos-blue);
            background: var(--pos-blue-soft);
            border-radius: 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 9px;
            font-weight: 700;
        }
        .table-responsive { width: 100%; overflow-x: auto; }
        .table { width: 100%; margin: 0; border-collapse: separate; border-spacing: 0; }
        .table thead th {
            padding: 10px 12px;
            color: #fff;
            background: linear-gradient(180deg, #0A68B5, #07549A);
            border: 0;
            border-right: 1px solid rgba(255,255,255,.13);
            border-bottom: 1px solid #06477F;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .045em;
            white-space: nowrap;
            cursor: pointer;
            user-select: none;
        }
        .table thead th:first-child { padding-left: 15px; }
        .table thead th:last-child { border-right: 0; }
        .table thead th:hover { background: #07549A; color: #fff; }
        .table tbody td {
            padding: 10px 12px;
            color: #38536D;
            background: #fff;
            border-bottom: 1px solid #E8EEF4;
            vertical-align: middle;
            font-size: 11px;
            white-space: nowrap;
        }
        .table tbody tr:nth-child(even) td { background: #F8FBFE; }
        .table tbody tr:last-child td { border-bottom: 0; }
        .table tbody tr:hover td { background: #EDF6FF; }
        .table tbody tr.fully-paid-row td { background: #F0FAF5; }
        .table tbody tr.fully-paid-row:hover td { background: #E4F6ED; }

        /* Customer */
        .customer-cell { display: flex; align-items: center; gap: 9px; min-width: 155px; }
        .customer-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            flex: 0 0 32px;
            color: #fff;
            background: linear-gradient(145deg,#0874C9,#07529A);
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .fully-paid-row .customer-avatar { background: linear-gradient(145deg,#12AD7C,#07845D); }
        .customer-name { color: #173D63; font-size: 11px; font-weight: 700; }
        .debt-amount {
            color: #D92F49;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 11px;
            font-weight: 700;
        }
        .text-success.fw-bold {
            color: #07845D !important;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 11px;
            font-weight: 700 !important;
        }
        .orders-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            color: #9A5B0A;
            background: #FFF3DB;
            border: 1px solid #F8E3B8;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
        }
        .date-text { color: #71849A; font-size: 10px; }
        .badge.bg-success {
            padding: 5px 8px;
            color: #fff !important;
            background: #0AA875 !important;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
        }
        .badge.bg-warning {
            padding: 5px 8px;
            color: #92510A !important;
            background: #FFE9C2 !important;
            border: 1px solid #F5DCA9;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
        }

        /* Action button */
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 6px 10px;
            color: #fff;
            background: linear-gradient(145deg,#0874C9,#07529A);
            border: 0;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: .18s ease;
            box-shadow: 0 2px 5px rgba(7,89,166,.14);
        }
        .btn-action:hover { color: #fff; background: #06477F; transform: translateY(-1px); }
        .sort-icon { margin-left: 3px; color: rgba(255,255,255,.65); font-size: 8px; }
        .sort-icon.active { color: #fff; opacity: 1; }

        /* Empty state */
        .empty-state { padding: 38px 18px; color: #8A9BAD; text-align: center; }
        .empty-state i { display: block; margin-bottom: 9px; font-size: 31px; }
        .empty-state p { margin: 0; color: #637B92; font-size: 11px; font-weight: 600; }

        @media (max-width: 1100px) {
            .metrics-row { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 768px) {
            main.main-content { padding: 1rem !important; }
            .page-top { align-items: flex-start; }
            .page-top .search-wrap { width: 100%; }
            .table thead th, .table tbody td { padding: 9px 10px; }
            .table thead th:nth-child(4), .table tbody td:nth-child(4) { display: none; }
        }
        @media (max-width: 576px) {
            main.main-content { padding: .75rem !important; }
            .metrics-row { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
            .metric { padding: 10px; gap: 8px; }
            .metric-icon { width: 34px; height: 34px; flex-basis: 34px; font-size: 14px; }
            .metric-value { font-size: 16px; }
            .metric-label { font-size: 8px; }
            .page-top { padding: 12px; }
            .page-top h4 { font-size: 14px; }
            .page-heading-icon { width: 37px; height: 37px; flex-basis: 37px; }
            .shop-banner { padding: 10px; }
            .table thead th:nth-child(5), .table tbody td:nth-child(5),
            .table thead th:nth-child(8), .table tbody td:nth-child(8) { display: none; }
        }
        @media (max-width: 390px) {
            .metric { align-items: flex-start; flex-direction: column; }
            .metric-value { font-size: 15px; }
        }
    </style>
</head>
<body>
    <?php echo $__env->make("sidenav", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <main class="main-content">

        <?php if(session('success')): ?>
            <div class="alert alert-success d-flex justify-content-between">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger d-flex justify-content-between">
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <a href="<?php echo e(url('shopInvoices')); ?>" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to shops
        </a>

        <div class="page-top">
            <div class="page-heading">
                <div class="page-heading-icon"><i class="bi bi-people"></i></div>
                <div>
                    <h4>Customers with debts</h4>
                    <p>Outstanding debts and payment status for <?php echo e($shop->name); ?></p>
                </div>
            </div>
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" id="debtorSearch" class="search-input" placeholder="Search customer name or phone...">
            </div>
        </div>

        <div class="shop-banner">
            <div class="shop-icon"><i class="bi bi-building"></i></div>
            <div>
                <div class="shop-banner-name"><?php echo e($shop->name); ?></div>
                <div class="shop-banner-loc"><i class="bi bi-geo-alt"></i> <?php echo e($shop->location); ?></div>
            </div>
        </div>

        <div class="metrics-row">
            <div class="metric">
                <div class="metric-icon"><i class="bi bi-people-fill"></i></div>
                <div class="metric-info">
                    <div class="metric-label">Customers</div>
                    <div class="metric-value" id="metricCust"><?php echo e($debtors->count()); ?></div>
                </div>
            </div>
            <div class="metric">
                <div class="metric-icon"><i class="bi bi-receipt"></i></div>
                <div class="metric-info">
                    <div class="metric-label">Total debt</div>
                    <div class="metric-value danger" id="metricDebt"><?php echo e(number_format($debtors->sum('total_debt'))); ?></div>
                </div>
            </div>
            <div class="metric">
                <div class="metric-icon"><i class="bi bi-check-circle-fill"></i></div>
                <div class="metric-info">
                    <div class="metric-label">Total paid</div>
                    <div class="metric-value success" id="metricPaid"><?php echo e(number_format($debtors->sum('total_paid'))); ?></div>
                </div>
            </div>
            <div class="metric">
                <div class="metric-icon"><i class="bi bi-wallet2"></i></div>
                <div class="metric-info">
                    <div class="metric-label">Remaining balance</div>
                    <div class="metric-value" id="metricRemaining"><?php echo e(number_format($debtors->sum('remaining'))); ?></div>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-card-head">
                <div class="table-card-title">
                    <i class="bi bi-list-ul"></i>
                    Debtor records
                    <span class="record-count"><?php echo e($debtors->count()); ?></span>
                </div>
                <small class="text-muted">Select a customer to view debt details</small>
            </div>
            <div class="table-responsive">
                <table class="table" id="debtorTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">Customer <span class="sort-icon bi bi-chevron-expand"></span></th>
                            <th>Phone</th>
                            <th onclick="sortTable(2)">Total <span class="sort-icon bi bi-chevron-expand"></span></th>
                            <th onclick="sortTable(3)">Paid <span class="sort-icon bi bi-chevron-expand"></span></th>
                            <th onclick="sortTable(4)">Returns <span class="sort-icon bi bi-chevron-expand"></span></th>
                            <th onclick="sortTable(5)">Remaining <span class="sort-icon bi bi-chevron-expand"></span></th>
                            <th onclick="sortTable(6)">Status <span class="sort-icon bi bi-chevron-expand"></span></th>
                            <th onclick="sortTable(7)">Last activity <span class="sort-icon bi bi-chevron-expand"></span></th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $debtors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $debtor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="debtor-row <?php echo e($debtor->is_fully_paid ? 'fully-paid-row' : ''); ?>" data-search="<?php echo e(strtolower($debtor->cName)); ?> <?php echo e(strtolower($debtor->cPhone ?? '')); ?>">
                                <td>
                                    <div class="customer-cell">
                                        <div class="customer-avatar">
                                            <?php echo e(strtoupper(substr($debtor->cName, 0, 1))); ?><?php echo e(strtoupper(substr(strstr($debtor->cName, ' '), 1, 1))); ?>

                                        </div>
                                        <span class="customer-name"><?php echo e($debtor->cName); ?></span>
                                    </div>
                                </td>
                                <td><?php echo e($debtor->cPhone ?? 'N/A'); ?></td>
                                <td><span class="debt-amount"><?php echo e(number_format($debtor->total_debt)); ?></span></td>
                                <td><span class="text-success fw-bold"><?php echo e(number_format($debtor->total_paid)); ?></span></td>
                                <td>
                                    <?php if(($debtor->returns ?? 0) > 0): ?>
                                        <span class="fw-bold" style="color:#0B70C2; font-family:'JetBrains Mono',monospace;"><?php echo e(number_format($debtor->returns)); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">0</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="<?php echo e($debtor->remaining > 0 ? 'debt-amount' : 'text-success fw-bold'); ?>"><?php echo e(number_format($debtor->remaining)); ?></span>
                                </td>
                                <td>
                                    <?php if($debtor->is_fully_paid): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Fully Paid</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><?php echo e($debtor->paid_invoices); ?>/<?php echo e($debtor->order_count); ?> paid</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="date-text"><?php echo e($debtor->last_order_date ? \Carbon\Carbon::parse($debtor->last_order_date)->format('M d, Y') : 'N/A'); ?></span>
                                </td>
                                <td>
                                    <form action="<?php echo e(url('customerDebtProducts')); ?>" method="post">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="customer" value="<?php echo e($debtor->cName); ?>">
                                        <input type="hidden" name="shop" value="<?php echo e($shop->id); ?>">
                                        <button type="submit" class="btn-action">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <p>No customers with debts for this shop</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        // Customer search
        document.getElementById('debtorSearch').addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('.debtor-row').forEach(row => {
                row.style.display = row.dataset.search.includes(q) ? '' : 'none';
            });
        });

        // Sort table columns
        let sortDir = [null, null, 'desc', null, null, null, null, null];

        function sortTable(col) {
            const tbody = document.querySelector('#debtorTable tbody');
            const rows = Array.from(tbody.querySelectorAll('.debtor-row'));
            const dir = sortDir[col] === 'asc' ? 'desc' : 'asc';

            sortDir = sortDir.map(() => null);
            sortDir[col] = dir;

            rows.sort((a, b) => {
                const av = a.cells[col].innerText.trim().replace(/,/g, '');
                const bv = b.cells[col].innerText.trim().replace(/,/g, '');
                const an = parseFloat(av);
                const bn = parseFloat(bv);
                const cmp = !Number.isNaN(an) && !Number.isNaN(bn)
                    ? an - bn
                    : av.localeCompare(bv, undefined, { numeric: true, sensitivity: 'base' });

                return dir === 'asc' ? cmp : -cmp;
            });

            rows.forEach(row => tbody.appendChild(row));

            document.querySelectorAll('#debtorTable thead th .sort-icon').forEach(icon => {
                icon.className = 'sort-icon bi bi-chevron-expand';
            });

            const activeIcon = document.querySelectorAll('#debtorTable thead th .sort-icon')[col];
            if (activeIcon) {
                activeIcon.className = 'sort-icon bi ' + (dir === 'asc'
                    ? 'bi-chevron-up active'
                    : 'bi-chevron-down active');
            }
        }
    </script>

    <?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH C:\Users\HomePC\Music\leruma pos\resources\views/shopDebtors.blade.php ENDPATH**/ ?>