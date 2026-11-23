<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e(config("app.name")); ?> - Customer Debt Products</title>
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

        /* Customer banner */
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

        /* ── Override modals & forms to match blue theme ── */
        .modal-content { border: none; border-radius: var(--pos-radius); overflow: hidden; box-shadow: 0 24px 64px rgba(5,75,141,.25); }
        .modal-header {
            background: linear-gradient(110deg, #064B8D 0%, #0870C5 100%);
            color: #fff;
            border-bottom: none;
            padding: 12px 16px;
        }
        .modal-header .modal-title { font-size: 13px; font-weight: 700; letter-spacing: -.2px; }
        .modal-header .btn-close { filter: invert(1); opacity: .7; }
        .modal-header .btn-close:hover { opacity: 1; }
        .modal-body { padding: 16px; font-size: 12px; }
        .modal-footer { border-top: 1px solid var(--pos-border); padding: 12px 16px; background: #FAFCFF; }
        .field { margin-bottom: 12px; }
        .field-label { display: block; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #71849A; margin-bottom: 4px; }
        .field-input {
            width: 100%;
            font-size: 12px;
            padding: 7px 11px;
            border: 1.5px solid var(--pos-border);
            border-radius: 7px;
            background: var(--pos-white);
            color: var(--pos-ink);
            outline: none;
            transition: border-color .18s, box-shadow .18s;
        }
        .field-input:focus { border-color: var(--pos-blue-mid); box-shadow: 0 0 0 3px rgba(7,112,194,.1); }
        .field-input.readonly { background: #EDF2F7; color: var(--pos-muted); }
        .field-hint { font-size: 10px; color: var(--pos-muted); margin-top: 3px; }

        .btn-modal-cancel {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 7px 16px; border-radius: 7px;
            border: 1.5px solid var(--pos-border);
            background: transparent;
            font-size: 11px; color: var(--pos-muted);
            cursor: pointer; transition: .15s;
        }
        .btn-modal-cancel:hover { background: #EDF2F7; }
        .btn-modal-confirm {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 7px 18px; border-radius: 7px;
            background: linear-gradient(145deg,#0874C9,#07529A);
            border: 0; color: #fff; font-size: 11px; font-weight: 700;
            cursor: pointer; transition: .18s;
            box-shadow: 0 2px 6px rgba(7,89,166,.14);
        }
        .btn-modal-confirm:hover { background: #06477F; transform: translateY(-1px); }
        .btn-modal-danger {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 7px 18px; border-radius: 7px;
            background: var(--pos-red);
            border: 0; color: #fff; font-size: 11px; font-weight: 700;
            cursor: pointer; transition: .18s;
        }
        .btn-modal-danger:hover { background: #C92D43; transform: translateY(-1px); }

        /* Invoice pill in table */
        .inv-pill {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            color: #07549A;
            background: var(--pos-blue-soft);
            border: 1px solid #B7D9F5;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
        }

        /* Comment styles */
        .comment-item {
            background: #EDF2F7;
            border: 1px solid var(--pos-border);
            border-radius: var(--pos-radius);
            padding: 8px 11px;
            margin-bottom: 6px;
            font-size: 11px;
        }
        .comment-meta { display: flex; justify-content: space-between; gap: 8px; font-size: 9.5px; color: var(--pos-muted); margin-bottom: 3px; }
        .comment-user { font-weight: 700; color: var(--pos-blue); }
        .comment-text { color: var(--pos-ink); }

        /* Date active badge */
        .date-active-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
            background: var(--pos-blue-mid);
            color: rgba(255,255,255,.85);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
        }

        @media (max-width: 1100px) {
            .metrics-row { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 768px) {
            main.main-content { padding: 1rem !important; }
            .page-top { align-items: flex-start; }
            .page-top .search-wrap { width: 100%; }
            .table thead th, .table tbody td { padding: 9px 10px; }
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

        
        <a href="<?php echo e(url('shopDebtors/' . urlencode($shopName))); ?>" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to <?php echo e($shopName); ?>

        </a>

        
        <div class="page-top">
            <div class="page-heading">
                <div class="page-heading-icon"><i class="bi bi-receipt"></i></div>
                <div>
                    <h4>Debt Products — <?php echo e($customerName); ?></h4>
                    <p>Outstanding products and invoices for <?php echo e($customerName); ?></p>
                </div>
            </div>
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" id="invoiceSearch" class="search-input" placeholder="Search invoice or product...">
            </div>
        </div>

        
        <div class="shop-banner">
            <div class="shop-icon"><i class="bi bi-person-fill"></i></div>
            <div>
                <div class="shop-banner-name"><?php echo e($customerName); ?></div>
                <div class="shop-banner-loc"><i class="bi bi-building"></i> <?php echo e($shopName); ?></div>
            </div>
        </div>

        
        <?php
            $totalDebt   = collect($invoicePayments)->sum('total');
            $totalPaid   = collect($invoicePayments)->sum('paid');
            $totalReturns= collect($invoicePayments)->sum('returns');
            $totalRemaining = collect($invoicePayments)->sum('remaining');
            $invoiceCount   = $invoices->count();
            $totalItems   = $debtProducts->count();
        ?>
        <div class="metrics-row">
            <div class="metric">
                <div class="metric-icon"><i class="bi bi-receipt"></i></div>
                <div class="metric-info">
                    <div class="metric-label">Total debt</div>
                    <div class="metric-value"><?php echo e(number_format($totalDebt)); ?></div>
                </div>
            </div>
            <div class="metric">
                <div class="metric-icon"><i class="bi bi-check-circle-fill"></i></div>
                <div class="metric-info">
                    <div class="metric-label">Total paid</div>
                    <div class="metric-value success"><?php echo e(number_format($totalPaid)); ?></div>
                </div>
            </div>
            <div class="metric">
                <div class="metric-icon"><i class="bi bi-box-arrow-in-left"></i></div>
                <div class="metric-info">
                    <div class="metric-label">Returns</div>
                    <div class="metric-value"><?php echo e(number_format($totalReturns)); ?></div>
                </div>
            </div>
            <div class="metric">
                <div class="metric-icon"><i class="bi bi-wallet2"></i></div>
                <div class="metric-info">
                    <div class="metric-label">Remaining balance</div>
                    <div class="metric-value"><?php echo e(number_format($totalRemaining)); ?></div>
                </div>
            </div>
        </div>

        
        <form action="<?php echo e(url('customerDebtProducts')); ?>" method="post" style="margin-bottom:13px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="customer" value="<?php echo e($customerName); ?>">
            <input type="hidden" name="shop"     value="<?php echo e($shopName); ?>">
            <label style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#71849A; margin-right:6px; white-space:nowrap;">From</label>
            <input type="date" name="start_date" style="height:36px; padding:7px 11px; border:1.5px solid var(--pos-border); border-radius:7px; background:#fff; font-size:11.5px; color:var(--pos-ink);" value="<?php echo e($startDate ?? ''); ?>">
            <label style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#71849A; margin:0 6px; white-space:nowrap;">To</label>
            <input type="date" name="end_date" style="height:36px; padding:7px 11px; border:1.5px solid var(--pos-border); border-radius:7px; background:#fff; font-size:11.5px; color:var(--pos-ink);" value="<?php echo e($endDate ?? ''); ?>">
            <button type="submit" class="btn-action" style="font-size:10px; padding:6px 14px;"><i class="bi bi-funnel"></i> Filter</button>
            <?php if(!empty($startDate) || !empty($endDate)): ?>
            <button type="submit" name="reset" value="1" style="height:36px; padding:7px 14px; border:1.5px solid var(--pos-border); border-radius:7px; background:transparent; font-size:11px; color:var(--pos-muted); cursor:pointer;">Reset</button>
            <?php endif; ?>
        </form>

        
        <?php if(!empty($startDate) || !empty($endDate)): ?>
        <div class="date-active-badge" style="margin-bottom:13px;">
            <i class="bi bi-calendar-range"></i>
            <?php if(!empty($startDate)): ?> <?php echo e(\Carbon\Carbon::parse($startDate)->format('d M Y')); ?> <?php else: ?> From start <?php endif; ?>
            &nbsp;—&nbsp;
            <?php if(!empty($endDate)): ?> <?php echo e(\Carbon\Carbon::parse($endDate)->format('d M Y')); ?> <?php else: ?> to today <?php endif; ?>
        </div>
        <?php endif; ?>

        
        <div class="table-card">
            <div class="table-card-head">
                <div class="table-card-title">
                    <i class="bi bi-list-ul"></i>
                    Invoices
                    <span class="record-count"><?php echo e($invoiceCount); ?></span>
                </div>
                <small class="text-muted"><?php echo e($totalItems); ?> items</small>
            </div>
            <div class="table-responsive">
                <table class="table" id="invoiceTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Invoice #</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Returns</th>
                            <th>Remaining</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoiceName => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $paymentInfo  = $invoicePayments[$invoiceName] ?? ['total'=>0,'paid'=>0,'remaining'=>0,'returns'=>0,'transferred_in'=>0];
                                $total        = $paymentInfo['total'];
                                $paid         = $paymentInfo['paid'];
                                $remaining    = $paymentInfo['remaining'];
                                $returns      = $paymentInfo['returns'];
                                $invoiceDate  = $items->first()->created_at ?? null;
                                $itemCount    = $items->count();
                                $isPaid       = $remaining <= 0;
                                $paidPct      = $total > 0 ? round(($paid/$total)*100) : 0;
                            ?>
                            <tr class="<?php echo e($isPaid ? 'fully-paid-row' : ''); ?> invoice-row" data-search="<?php echo e(strtolower($invoiceName)); ?>">
                                <td>
                                    <span class="date-text"><?php echo e($invoiceDate ? \Carbon\Carbon::parse($invoiceDate)->format('M d, Y') : 'N/A'); ?></span>
                                </td>
                                <td><span class="inv-pill">#<?php echo e($invoiceName); ?></span></td>
                                <td><?php echo e($itemCount); ?> <?php echo e(Str::plural('item', $itemCount)); ?></td>
                                <td><span class="debt-amount"><?php echo e(number_format($total)); ?></span></td>
                                <td><span class="text-success fw-bold"><?php echo e(number_format($paid)); ?></span></td>
                                <td>
                                    <?php if($returns > 0): ?>
                                        <span style="color:#0B70C2; font-family:'JetBrains Mono',monospace;"><?php echo e(number_format($returns)); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">0</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="<?php echo e($remaining > 0 ? 'debt-amount' : 'text-success fw-bold'); ?>"><?php echo e(number_format($remaining)); ?></span>
                                </td>
                                <td>
                                    <?php if($isPaid): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Fully Paid</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><?php echo e($paidPct); ?>% paid</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display:flex; gap:4px;">
                                        <?php if(!$isPaid && canUser('pay_debts')): ?>
                                        <button type="button" class="btn-action"
                                            data-bs-toggle="modal"
                                            data-bs-target="#payModal_<?php echo e($invoiceName); ?>">
                                            <i class="bi bi-cash-stack"></i> Pay
                                        </button>
                                        <?php endif; ?>
                                        <?php if(canUser('delete_orders')): ?>
                                        <form action="<?php echo e(url('deleteOrder')); ?>" method="post" style="display:inline;" onsubmit="return confirm('Delete invoice #<?php echo e($invoiceName); ?>? This will remove all items and restore stock.');">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="orderId" value="<?php echo e($invoiceName); ?>">
                                            <button type="submit" class="btn-action" style="background:var(--pos-red);">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        <?php if(canUser('edit_debts') || canUser('delete_orders')): ?>
                                        <button type="button" class="btn-action" style="background:var(--pos-border); color:var(--pos-ink); border:1px solid var(--pos-border);"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailsModal_<?php echo e($invoiceName); ?>">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>No debt invoices found for this customer</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoiceName => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $paymentInfo  = $invoicePayments[$invoiceName] ?? ['total'=>0,'paid'=>0,'remaining'=>0,'returns'=>0];
                $remaining    = $paymentInfo['remaining'];
                $isPaid       = $remaining <= 0;
                $modalId      = 'payModal_' . str_replace('#', '', $invoiceName);
            ?>
            <?php if(!$isPaid && canUser('pay_debts')): ?>
            <div class="modal fade" id="<?php echo e($modalId); ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Pay debt — #<?php echo e($invoiceName); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="<?php echo e(url('payInvoiceDebt')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="modal-body">
                                <div class="field">
                                    <label class="field-label">Customer</label>
                                    <input type="text" class="field-input readonly" value="<?php echo e($customerName); ?>" readonly>
                                </div>
                                <div class="field">
                                    <label class="field-label">Remaining amount</label>
                                    <input type="text" class="field-input readonly" value="<?php echo e(number_format($remaining)); ?> Tsh" readonly>
                                </div>
                                <div class="field">
                                    <label class="field-label">Payment method <span style="color:var(--pos-red);">*</span></label>
                                    <select class="field-input" name="payment_method" required>
                                        <option value="cash">Cash</option>
                                        <?php if($availableChip > 0): ?>
                                        <option value="chip">Chip</option>
                                        <?php else: ?>
                                        <option value="chip" disabled>Chip (unavailable)</option>
                                        <?php endif; ?>
                                    </select>
                                    <?php if($availableChip <= 0): ?>
                                    <div class="field-hint" style="color:var(--pos-red);">Chip balance is zero. Cash only.</div>
                                    <?php endif; ?>
                                </div>
                                <div class="field">
                                    <label class="field-label">Payment amount <span style="color:var(--pos-red);">*</span></label>
                                    <input type="number" class="field-input" name="paymentAmount"
                                        max="<?php echo e($remaining); ?>" step="1" required
                                        placeholder="Enter amount…"
                                        oninput="updateCashPortion('<?php echo e($modalId); ?>', <?php echo e($availableChip); ?>)">
                                    <div class="field-hint">Maximum: <?php echo e(number_format($remaining)); ?> Tsh</div>
                                </div>
                                <div class="field">
                                    <label class="field-label">Payment date</label>
                                    <?php if(canUser("payment_date")): ?>
                                    <input type="date" class="field-input" name="payment_date" value="<?php echo e(date('Y-m-d')); ?>" required>
                                    <?php else: ?>
                                    <input type="hidden" name="payment_date" value="<?php echo e(date('Y-m-d')); ?>">
                                    <?php endif; ?>
                                </div>
                                <input type="hidden" name="invoiceName" value="<?php echo e($invoiceName); ?>">
                                <input type="hidden" name="shopName" value="<?php echo e($shopName); ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn-modal-confirm">
                                    <i class="bi bi-check-circle-fill"></i> Confirm payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoiceName => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $invoiceTotal = $items->sum('totalPrice');
                $paymentInfo = $invoicePayments[$invoiceName] ?? ['total'=>$invoiceTotal,'paid'=>0,'remaining'=>$invoiceTotal];
                $total    = $paymentInfo['total'];
                $remaining    = $paymentInfo['remaining'];
                $paid         = $paymentInfo['paid'];
                $isPaid       = $remaining <= 0;
                $paidPct      = $invoiceTotal > 0 ? round(($paid/$invoiceTotal)*100) : 0;
                $itemDesc = $items->map(fn($p) => ($p->name01 ?? $p->productId ?? 'Item') . ' ×' . ($p->pQuantity ?? 1))->implode(', ');
                $invoiceComments = $debtComments[$items->first()->order_id] ?? collect();
            ?>
            <div class="modal fade" id="detailsModal_<?php echo e($invoiceName); ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Invoice #<?php echo e($invoiceName); ?> — Items</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="field">
                                <label class="field-label">Date</label>
                                <input type="text" class="field-input readonly" value="<?php echo e($items->first()->created_at ? \Carbon\Carbon::parse($items->first()->created_at)->format('d M Y, H:i') : 'N/A'); ?>" readonly>
                            </div>
                            <div class="field">
                                <label class="field-label">Total</label>
                                <div style="display:flex; justify-content:space-between; font-family:'JetBrains Mono',monospace; font-weight:700; font-size:14px;">
                                    <span>Total</span>
                                    <span><?php echo e(number_format($total)); ?></span>
                                </div>
                                <div style="display:flex; justify-content:space-between; font-size:11px; margin-top:2px;">
                                    <span style="color:#07845D;">Paid: <?php echo e(number_format($paid)); ?></span>
                                    <span style="color:var(--pos-red);">Remaining: <?php echo e(number_format($remaining)); ?></span>
                                </div>
                            </div>
                            <table class="table" style="margin-top:12px; font-size:11px;">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($product->name01 ?? $product->productId ?? 'N/A'); ?></td>
                                        <td><?php echo e($product->pQuantity ?? 0); ?></td>
                                        <td><?php echo e(number_format($product->productPrice ?? 0)); ?></td>
                                        <td style="font-weight:700;"><?php echo e(number_format($product->totalPrice)); ?></td>
                                        <td style="white-space:nowrap;">
                                            <?php if(canUser('edit_debts')): ?>
                                            <button type="button" class="btn-action" style="font-size:9px; padding:4px 8px;" data-bs-toggle="modal" data-bs-target="#editProductGlobalModal" onclick="openEditDebtProductModal(this)"
                                                data-product-id="<?php echo e($product->id); ?>"
                                                data-product-name="<?php echo e($product->name01 ?? $product->productId ?? ''); ?>"
                                                data-qty="<?php echo e($product->pQuantity ?? 0); ?>"
                                                data-price="<?php echo e($product->productPrice ?? 0); ?>"
                                                data-total="<?php echo e($product->totalPrice); ?>"
                                                data-order-name="<?php echo e($product->orderName); ?>"
                                                data-order-id="<?php echo e($product->order_id); ?>"
                                                data-shop="<?php echo e($shopName); ?>"
                                                data-payment-info="<?php echo e(json_encode($paymentInfo)); ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <?php endif; ?>
                                            <?php if(canUser('delete_orders')): ?>
                                            <form action="<?php echo e(url('deleteDebtProduct')); ?>" method="post" style="display:inline;" onsubmit="return confirm('Delete this product from invoice #<?php echo e($invoiceName); ?>? Stock will be restored.');">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="productId" value="<?php echo e($product->id); ?>">
                                                <input type="hidden" name="shopName" value="<?php echo e($shopName); ?>">
                                                <input type="hidden" name="orderId" value="<?php echo e($product->order_id); ?>">
                                                <button type="submit" class="btn-action" style="background:var(--pos-red); font-size:9px; padding:4px 8px;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <div class="modal fade" id="editProductGlobalModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?php echo e(url('editDebtProduct')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="field">
                                <label class="field-label">Product</label>
                                <input type="text" class="field-input readonly" id="editModalProductName" readonly>
                            </div>
                            <div class="field">
                                <label class="field-label">Quantity <span style="color:var(--pos-red);">*</span></label>
                                <input type="number" class="field-input" name="pQuantity" id="editModalQty" min="1" required>
                                <div class="field-hint" style="color:var(--pos-green);">New total will be: <strong id="editModalNewTotal">—</strong> Tsh</div>
                            </div>
                            <div class="field">
                                <label class="field-label">Unit price <span style="color:var(--pos-red);">*</span></label>
                                <input type="number" class="field-input" name="productPrice" id="editModalPrice" min="0" step="1" required>
                            </div>
                            <input type="hidden" name="productId" id="editModalProductId">
                            <input type="hidden" name="orderId" id="editModalOrderId">
                            <input type="hidden" name="shopName" id="editModalShopName">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-modal-confirm">
                                <i class="bi bi-check-circle-fill"></i> Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background:linear-gradient(110deg,#C92D43 0%,#E33E52 100%);">
                        <h5 class="modal-title">Confirm Delete Invoice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="deleteOrderForm" action="<?php echo e(url('deleteOrder')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="field">
                                <p>Are you sure you want to delete this invoice? This will permanently delete all items in this order and restore product stock.</p>
                                <p style="font-size:11px; color:var(--pos-red); margin-top:8px;">
                                    <i class="bi bi-info-circle"></i> This action cannot be undone.
                                </p>
                            </div>
                            <input type="hidden" name="orderId" id="deleteOrderId">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-modal-danger">
                                <i class="bi bi-trash"></i> Delete Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background:linear-gradient(110deg,#C92D43 0%,#E33E52 100%);">
                        <h5 class="modal-title">Confirm Delete Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="deleteProductForm" action="<?php echo e(url('deleteDebtProduct')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="field">
                                <p id="deleteProductMsg">Are you sure you want to delete this product? Stock will be restored.</p>
                            </div>
                            <input type="hidden" name="productId" id="deleteProductId">
                            <input type="hidden" name="shopName" id="deleteProductShop">
                            <input type="hidden" name="orderId" id="deleteProductOrder">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-modal-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoiceName => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $invoiceComments = $debtComments[$items->first()->order_id] ?? collect();
                $commentsCount = $invoiceComments->count();
            ?>
            <?php if($commentsCount > 0): ?>
            <div class="modal fade" id="commentsModal_<?php echo e($invoiceName); ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Comments — #<?php echo e($invoiceName); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <?php $__currentLoopData = $invoiceComments->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="comment-item">
                                <div class="comment-meta">
                                    <span class="comment-user"><?php echo e($comment->user_name); ?></span>
                                    <span><?php echo e(\Carbon\Carbon::parse($comment->created_at)->format('d M Y, H:i')); ?></span>
                                </div>
                                <div class="comment-text"><?php echo e($comment->comment); ?></div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(canUser('add_debt_comments')): ?>
                            <form action="<?php echo e(url('saveDebtComment')); ?>" method="POST" style="display:flex; gap:6px; margin-top:12px;">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="order_id" value="<?php echo e($items->first()->order_id); ?>">
                                <input type="hidden" name="order_name" value="<?php echo e($invoiceName); ?>">
                                <input type="text" name="comment" placeholder="Add a comment..." required style="flex:1; font-size:11px; padding:7px 11px; border:1.5px solid var(--pos-border); border-radius:7px;">
                                <button type="submit" class="btn-modal-confirm" style="font-size:10px; padding:7px 14px;">Add</button>
                            </form>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Invoice search filter
            document.getElementById('invoiceSearch').addEventListener('input', function () {
                const q = this.value.toLowerCase().trim();
                document.querySelectorAll('.invoice-row').forEach(row => {
                    row.style.display = row.dataset.search.includes(q) ? '' : 'none';
                });
            });

            // Wire delete order modal with the correct orderId
            document.querySelectorAll('form[action*="deleteOrder"]').forEach(form => {
                form.addEventListener('submit', function (e) {
                    if (!confirm('Are you sure you want to delete this invoice? All items will be removed and stock restored.')) {
                        e.preventDefault();
                    }
                });
            });
        </script>

        <?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </main>
</body>
</html>
<?php /**PATH C:\Users\HomePC\Music\leruma pos\resources\views/customerDebtProducts.blade.php ENDPATH**/ ?>