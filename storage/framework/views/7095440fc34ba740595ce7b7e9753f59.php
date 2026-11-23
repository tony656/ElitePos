<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config("app.name")); ?> — Item Request</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            background: #f3f7fb;
            color: var(--slate-800);
            font-family: var(--font);
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        .layout { display: flex; min-height: 100vh; }
        main { flex: 1; min-width: 0; padding: 1.1rem 1.25rem 1.5rem; margin-left: 270px; overflow-x: hidden; }
        @media (max-width: 768px) { main { margin-left: 0; padding: .8rem; } }

        /* ── Page header: flat white surface ── */
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
        .pg-title-wrap { display: flex; flex-direction: column; gap: .25rem; position: relative; z-index: 1; }
        .pg-title {
            color: #064b86;
            font-size: 1.42rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            letter-spacing: -0.35px;
        }
        .pg-title span { color: #0871c2; }
        .pg-subtitle {
            color: #647f99;
            font-size: .78rem;
            font-weight: 400;
            letter-spacing: 0.01em;
        }
        .btn-header {
            position: relative;
            z-index: 1;
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
        .btn-header:hover {
            background: #064f91;
            border-color: #064f91;
            color: #fff;
            transform: translateY(-1px);
        }
        .btn-header:active { transform: translateY(0); }

        /* ── Layout grid ── */
        .two-col { display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; align-items: start; }
        @media (max-width: 900px) { .two-col { grid-template-columns: 1fr; gap: .85rem; } }

        /* ── Cards ── */
        .card {
            background: var(--white);
            border: 1px solid #dce7f1;
            border-radius: var(--radius);
            box-shadow: 0 2px 7px rgba(8, 61, 108, .045);
        }
        .card-header {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: .75rem .9rem;
            border-bottom: 1px solid #dce7f1;
            background: #fff;
        }
        .card-header-icon {
            width: 30px; height: 30px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            font-size: .9rem;
        }
        .icon-amber   { background: var(--amber-pale);   color: var(--amber); }
        .icon-emerald { background: var(--emerald-pale); color: var(--emerald); }
        .icon-violet  { background: var(--violet-pale);  color: var(--violet); }
        .icon-navy    { background: rgba(8,75,135,0.08); color: var(--navy-light); }
        .icon-sky     { background: var(--sky-pale);     color: var(--sky); }
        .card-title { font-size: .96rem; font-weight: 700; color: var(--navy); letter-spacing: -0.2px; display: flex; align-items: center; gap: .4rem; }
        .card-title::before {
            content: '';
            width: 3px; height: 17px;
            background: #0b68b4;
            border-radius: 2px;
            display: inline-block;
        }
        .card-header .card-header-icon + .card-title::before { display: none; }
        .card-body { padding: .9rem; }

        /* ── Form elements ── */
        .form-group { margin-bottom: 1rem; }
        .form-group:last-child { margin-bottom: 0; }
        .form-label {
            display: block;
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--slate-500);
            margin-bottom: .4rem;
        }
        .form-control, .form-select {
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
        .form-control:focus, .form-select:focus {
            border-color: #0b68b4;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(11,104,180,.09);
        }
        .form-control::placeholder { color: var(--slate-400); }
        .form-hint { font-size: .72rem; color: var(--slate-400); margin-top: .35rem; }
        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%238da4bb' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
        }

        /* ── Product search ── */
        .search-icon {
            position: absolute;
            left: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
            font-size: .85rem;
            pointer-events: none;
            transition: color 0.2s;
        }
        .search-wrap { position: relative; }
        .search-wrap:focus-within .search-icon { color: var(--navy-light); }
        #search-results {
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
        .search-item {
            padding: 0.6rem 0.9rem;
            cursor: pointer;
            border-bottom: 1px solid var(--slate-100);
            transition: background 0.15s;
        }
        .search-item:last-child { border-bottom: none; }
        .search-item:hover, .search-item.selected {
            background: #edf6ff;
            border-left: 3px solid #0b68b4;
            padding-left: calc(0.9rem - 3px);
        }
        .search-item-name { font-size: .8rem; font-weight: 600; color: var(--navy); }
        .search-item-quantity { font-size: .72rem; color: var(--slate-500); font-weight: 600; }
        .search-item-price { font-size: .75rem; color: var(--emerald); font-weight: 500; margin-top: 2px; }
        .search-no-results { padding: 1rem; text-align: center; font-size: .78rem; color: var(--slate-400); }

        /* ── Radio payment pills ── */
        .radio-group { display: flex; gap: 0.5rem; }
        .radio-pill { flex: 1; position: relative; }
        .radio-pill input[type=radio] { position: absolute; opacity: 0; width: 0; height: 0; }
        .radio-pill label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: .5rem .6rem;
            border-radius: var(--radius);
            border: 1px solid #d4e1ed;
            cursor: pointer;
            font-size: .76rem;
            font-weight: 600;
            color: var(--slate-600);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fff;
            letter-spacing: 0.01em;
            margin-bottom: 0;
        }
        .radio-pill label i { font-size: .85rem; }
        .radio-pill input[type=radio]:checked + label {
            border-color: #0b66b2;
            color: #fff;
            background: #0866b2;
            box-shadow: 0 3px 8px rgba(8,102,178,.18);
        }
        .radio-pill label:hover { border-color: #0b68b4; background: #eff6ff; }
        .radio-pill input[type=radio]:checked + label:hover { background: #0866b2; }

        /* ── Buttons ── */
        .btn-add, .btn-submit {
            width: 100%;
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
        }
        .btn-add { background: #0866b2; color: #fff; }
        .btn-add:hover:not(:disabled) { background: #064f91; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(8,102,178,.25); }
        .btn-add:active:not(:disabled) { transform: translateY(0); }
        .btn-add:disabled { background: var(--slate-300); cursor: not-allowed; opacity: 0.7; }
        .btn-submit { background: var(--emerald); color: #fff; }
        .btn-submit:hover { background: #056f58; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(7,134,107,.28); }
        .btn-submit:active { transform: translateY(0); }
        .btn-add i, .btn-submit i { font-size: .95rem; }

        /* ── Items table ── */
        .table-wrap {
            overflow-x: auto;
            border-radius: var(--radius-sm);
            border: 1px solid #e5edf5;
        }
        table { width: 100%; border-collapse: collapse; font-size: .76rem; }
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

        .prod-name { font-weight: 600; color: #164a78; font-size: .76rem; margin-bottom: .08rem; }
        .prod-id   { font-size: .68rem; color: #7189a0; }

        .qty-input {
            width: 64px;
            padding: .35rem .5rem;
            text-align: center;
            border: 1px solid #d4e1ed;
            border-radius: var(--radius-sm);
            font-size: .75rem;
            color: var(--slate-800);
            outline: none;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .qty-input:focus { border-color: #0b68b4; box-shadow: 0 0 0 3px rgba(11,104,180,.09); }

        .price-val { font-size: .74rem; font-weight: 500; color: #31516e; font-variant-numeric: tabular-nums; }
        .total-val { font-size: .74rem; font-weight: 600; color: var(--navy); font-variant-numeric: tabular-nums; }

        .btn-del {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 27px;
            border-radius: 5px;
            background: #fff0f1;
            color: var(--rose);
            border: 1px solid #f5b8c2;
            cursor: pointer;
            transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
        }
        .btn-del:hover { background: var(--rose); color: #fff; border-color: var(--rose); transform: translateY(-1px); }
        .btn-del i { font-size: .78rem; pointer-events: none; }

        tfoot td {
            background: var(--slate-50);
            border-top: 1px solid #dce7f1;
            font-weight: 700;
            padding: .62rem .68rem;
        }
        .tfoot-label { text-align: right; color: var(--slate-500); font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; }
        .tfoot-total { text-align: right; color: var(--navy); font-size: .82rem; font-variant-numeric: tabular-nums; }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 2.5rem 1.5rem;
            border: 1px dashed #c5d5e5;
            border-radius: var(--radius-sm);
            background: #f8fbfe;
            margin: 1rem;
        }
        .empty-icon { font-size: 2.2rem; margin-bottom: .6rem; opacity: .35; color: var(--slate-400); }
        .empty-state h5 { font-size: .9rem; font-weight: 600; color: var(--slate-600); margin-bottom: 4px; }
        .empty-state p { font-size: .78rem; color: var(--slate-400); }

        /* ── Order info block ── */
        .order-info {
            background: #f8fbfe;
            border: 1px solid #dce7f1;
            border-radius: var(--radius-sm);
            padding: .8rem .9rem;
            margin-bottom: 1.1rem;
        }
        .order-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .35rem 0;
            border-bottom: 1px solid #e5edf5;
            font-size: .78rem;
        }
        .order-info-row:last-child { border-bottom: none; padding-bottom: 0; }
        .info-label { color: var(--slate-500); font-size: .72rem; font-weight: 500; }
        .info-val { font-weight: 600; color: var(--slate-800); font-size: .76rem; }

        /* ── Badges ── */
        .badge {
            display: inline-block;
            padding: .22rem .55rem;
            border-radius: 20px;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .03em;
        }
        .badge-pending { background: var(--amber-pale); color: #07569b; }
        .badge-approved { background: var(--emerald-pale); color: var(--emerald); }

        /* ── Pricing summary: deep blue block ── */
        .pricing-block {
            background: linear-gradient(120deg, #064b86, #0870bd);
            border-radius: var(--radius-sm);
            padding: 1rem 1.1rem;
            margin: 1.1rem 0;
            color: rgba(255,255,255,.9);
            box-shadow: 0 6px 18px rgba(8,61,108,.18);
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: .78rem;
            padding: 4px 0;
        }
        .price-row .price-num { font-weight: 600; color: #b8dcff; font-variant-numeric: tabular-nums; }
        .price-row.grand {
            border-top: 1px solid rgba(255,255,255,.15);
            margin-top: 8px;
            padding-top: 10px;
            color: #fff;
            font-size: .9rem;
            font-weight: 700;
        }
        .price-row.grand .price-num { font-size: 1.05rem; color: #fff; }

        /* ── Section divider ── */
        .section-label {
            font-size: .66rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            font-weight: 700;
            color: var(--slate-400);
            margin-bottom: .45rem;
            display: flex;
            align-items: center;
            gap: .4rem;
        }
        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #dce7f1;
        }

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
        .alert-info    { background: var(--sky-pale);     color: #07569b; border-left: 3px solid #0b68b4; }
        .alert .close-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: inherit;
            font-size: 1.05rem;
            opacity: 0.6;
            line-height: 1;
            font-family: inherit;
        }
        .alert .close-btn:hover { opacity: 1; }

        /* ── Hidden fields ── */
        .hidden-fields { position: absolute; opacity: 0; pointer-events: none; height: 0; overflow: hidden; }

        /* ── Search result counter ── */
        .result-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            background: #0866b2;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            border-radius: 20px;
            margin-left: 6px;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .pg-header { padding: .25rem 0 .7rem; }
            .pg-title { font-size: 1.2rem; }
            .two-col { gap: .85rem; }
            .card-body { padding: .8rem; }
            .card-header { padding: .65rem .8rem; }
            .btn-header { width: 100%; justify-content: center; }
            .row-fields { grid-template-columns: 1fr; }
            .form-control, .form-select { font-size: .78rem; }
        }
    </style>
</head>
<body>
    <div class="layout">
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

            
            <div class="pg-header">
                <div class="pg-title-wrap">
                    <div class="pg-title">
                        Item Request <span>Orders</span>
                    </div>
                    <div class="pg-subtitle">Build a product request and submit it for approval</div>
                </div>
                <a href="<?php echo e(url('viewRequest')); ?>" class="btn-header">
                    <i class="bi bi-box-arrow-in-right"></i> View Requests
                </a>
            </div>

            
            <div class="two-col">

                
                <div style="display:flex; flex-direction:column; gap:1rem;">

                    
                    <div class="card d-flex flex-column">
                        <div class="card-header">
                            <div class="card-header-icon icon-amber">
                                <i class="bi bi-plus-circle-fill"></i>
                            </div>
                            <span class="card-title">Add New Item</span>
                        </div>
                        <div class="card-body d-flex ">
                            
                            <form action="/storeSession" method="post" class="w-50" style="margin-bottom:1.1rem;">
                                <?php echo csrf_field(); ?>
                                <div class="form-group" style="margin-bottom:0;">
                                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
                                        <label class="form-label" style="margin-bottom:0;">Select Shop</label>
                                        <span style="font-size:0.72rem; color:var(--slate-500);">
                                            Current: <strong><?php echo e($currentShopName ?? 'Not selected'); ?></strong>
                                        </span>
                                    </div>
                                    <select class="form-select" name="shopId" id="shopId" onchange="this.form.submit()" style="padding-left:0.9rem;">
                                        <option value="">— Choose Shop —</option>
                                        <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($shop['id']); ?>" <?php echo e(((getCurrentShopId()) == $shop['id'] ) ? 'selected' : ''); ?>>
                                                <?php echo e($shop['name']); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </form>

                            
                            <form action="/itemRequest"  class="w-50" method="post" autocomplete="off" id="addItemForm">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="OrderName" value="<?php echo e($orders->orderName ?? ''); ?>">
                                <input type="hidden" name="pQuantity" value="1">
                                <input type="hidden" name="requestDate" id="formRequestDate" value="<?php echo e(old('requestDate', date('Y-m-d'))); ?>">

                                <div class="form-group search-wrap">
                                    <label class="form-label">Search Product</label>
                                    <i class="bi bi-search search-icon"></i>
                                    <input type="search" class="form-control" id="product-name"
                                           placeholder="Type product name to search..." autocomplete="off">
                                    <div id="search-results"></div>
                                </div>

                                <div class="hidden-fields">
                                    <input type="text" id="pId"    name="pId"    readonly>
                                    <input type="text" id="pPrice" name="pPrice" readonly>
                                    <input type="text" value="<?php echo e($orders->order_id  ?? ''); ?>" name="OrdersIds"   readonly>
                                    <input type="text" value="<?php echo e($orders->orderName ?? ''); ?>" name="OrdersNames" readonly>
                                    <input type="number" id="maxDiscount" name="maxDiscount" readonly>
                                </div>
                            </form>
                        </div>
                    </div>

                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-icon icon-navy">
                                <i class="bi bi-cart3"></i>
                            </div>
                            <span class="card-title">
                                Current Items
                                <?php if(count($carts) > 0): ?>
                                    <span class="result-count"><?php echo e(count($carts)); ?></span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <?php if(count($carts) > 0): ?>
                            <div class="table-wrap" style="margin: 1rem;">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th style="text-align:right;">Unit Price</th>
                                            <th style="text-align:right;">Subtotal</th>
                                            <th style="text-align:right;">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $carts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $name = DB::table('products')->where('product_id', $row->productId)->value('name01');
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="prod-name"><?php echo e($name ?? 'Product Not Found'); ?></div>
                                                <div class="prod-id">ID: <?php echo e($row->productId); ?></div>
                                            </td>
                                            <td>
                                                <form action="/requpdQuant" method="post">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="OrdersIds" value="<?php echo e($row->requestName ?? 'NEW-ORDER'); ?>">
                                                    <input type="hidden" name="prodId"    value="<?php echo e($row->productId); ?>">
                                                    <input type="number" class="qty-input" name="prodQuantity" value="<?php echo e($row->quantity); ?>" min="1" onchange="this.form.submit()">
                                                </form>
                                            </td>
                                            <td class="text-right"><span class="price-val"><?php echo e(number_format($row->price, 2)); ?></span></td>
                                            <td class="text-right"><span class="total-val"><?php echo e(number_format($row->totalPrice, 2)); ?></span></td>
                                            <td class="text-right">
                                                <form action="/dltItemReq" method="post" style="display:inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="itemId"       value="<?php echo e($row->productId); ?>">
                                                    <input type="hidden" name="reqName"      value="<?php echo e($row->requestName ?? 'NEW-ORDER'); ?>">
                                                    <input type="hidden" name="prodQuantity" value="<?php echo e($row->quantity); ?>">
                                                    <button type="submit" class="btn-del" onclick="return confirm('Remove this item from the request?')">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="tfoot-label">Grand Total</td>
                                            <td colspan="2" class="tfoot-total"><?php echo e(number_format($carts->sum('totalPrice'), 2)); ?> TZS</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-cart3"></i>
                                </div>
                                <h5>No items yet</h5>
                                <p>Search and add products to begin your request</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                
                <div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-icon icon-violet">
                                <i class="bi bi-clipboard-check"></i>
                            </div>
                            <span class="card-title">Request Summary</span>
                        </div>
                        <div class="card-body">

                            
                            <div class="order-info">
                                <div class="order-info-row">
                                    <span class="info-label">Request ID</span>
                                    <span class="info-val"><?php echo e($orders->requestName ?? 'NEW-ORDER'); ?></span>
                                </div>
                                <div class="order-info-row">
                                    <span class="info-label">Date</span>
                                    <span class="info-val" style="font-size:12px;"><?php echo e($orders->created_at ?? ''); ?></span>
                                </div>
                                <div class="order-info-row">
                                    <span class="info-label">Status</span>
                                    <span class="badge badge-pending">Pending</span>
                                </div>
                                <div class="order-info-row">
                                    <span class="info-label">Served By</span>
                                    <span class="info-val"><?php echo e($orders->served_by ?? ''); ?></span>
                                </div>
                            </div>

                            
                            <div style="margin-bottom:1.1rem;">
                                <div class="section-label">Supplier</div>
                                <form action="/saveInfo" method="post">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="requestName" value="<?php echo e($orders->requestName ?? 'NEW-ORDER'); ?>">
                                    <div class="form-group" style="margin-bottom:0;">
                                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
                                            <label class="form-label" style="margin-bottom:0;">Select Supplier</label>
                                            <span style="font-size:0.72rem; color:var(--slate-500);">
                                                Current: <strong><?php echo e($supplierName); ?></strong>
                                            </span>
                                        </div>
                                        <select class="form-select" value="<?php echo e($orders->supplierId ?? ''); ?>" name="selectedCustomer" onchange="this.form.submit()" style="padding-left:0.9rem;">
                                            <option value="" selected disabled>Choose supplier</option>
                                            <option value="7">Main Store</option>
                                        </select>
                                    </div>
                                </form>
                            </div>

                            
                            <div style="margin-bottom:1.1rem;">
                                <div class="section-label">Assign To</div>
                                <form action="/saveInfo" method="post">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="requestName" value="<?php echo e($orders->requestName ?? 'NEW-ORDER'); ?>">
                                    <div class="form-group">
                                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
                                            <label class="form-label" style="margin-bottom:0;">User Allocation</label>
                                            <span style="font-size:0.72rem; color:var(--slate-500);">
                                                Current: <strong><?php echo e($Allocation); ?></strong>
                                            </span>
                                        </div>
                                        <select class="form-select" name="assignedTo" id="assignedTo" onchange="this.form.submit()" style="padding-left:0.9rem;">
                                            <option value="">— Select user —</option>
                                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($user->id); ?>" <?php echo e((old('assignedTo') == $user->id || ($orders && $orders->assigned_to == $user->id)) ? 'selected' : ''); ?>>
                                                    <?php echo e($user->name); ?> (<?php echo e($user->levelStatus ?? 'User'); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <p class="form-hint">Select the user responsible for this location's request.</p>
                                    </div>
                                </form>
                            </div>

                            
                            <div style="margin-bottom:1.1rem;">
                                <div class="section-label">Payment Type</div>
                                <form action="/saveInfo" method="post">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="requestName" value="<?php echo e($orders->requestName ?? 'NEW-ORDER'); ?>">
                                    <div class="radio-group">
                                        <div class="radio-pill">
                                            <input type="radio" id="payCash" name="paymentType" value="cash" <?php echo e($payment_type == 'cash' ? 'checked' : ''); ?> onchange="this.form.submit()">
                                            <label for="payCash">
                                                <i class="bi bi-cash-stack"></i> Cash
                                            </label>
                                        </div>
                                        <div class="radio-pill">
                                            <input type="radio" id="payCredit" name="paymentType" value="credit" <?php echo e($payment_type == 'credit' ? 'checked' : ''); ?> onchange="this.form.submit()">
                                            <label for="payCredit">
                                                <i class="bi bi-credit-card"></i> Credit
                                            </label>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            
                            <div class="pricing-block">
                                <div class="price-row">
                                    <span>Subtotal</span>
                                    <span class="price-num"><?php echo e(number_format($carts->sum('totalPrice'), 2)); ?> TZS</span>
                                </div>
                                <div class="price-row grand">
                                    <span>Grand Total</span>
                                    <span class="price-num"><?php echo e(number_format($carts->sum('totalPrice'), 2)); ?> TZS</span>
                                </div>
                            </div>

                            
                            <?php if($orders->requestName ?? ''): ?>
                            <form action="/requestSubmit" method="POST">
                                <?php echo csrf_field(); ?>
                                <div style="margin-bottom:1.1rem;">
                                    <div class="section-label">Request Shop</div>
                                    <input type="hidden" name="requestName" value="<?php echo e($orders->requestName ?? 'NEW-ORDER'); ?>">
                                </div>
                                <?php if(canUser('item_request_date')): ?>
                                <div class="form-group">
                                    <label class="form-label" for="requestDate">Request Date</label>
                                    <input type="date" class="form-control" id="requestDate" name="requestDatePicker" value="<?php echo e(old('requestDate', date('Y-m-d'))); ?>" style="padding-left:0.9rem;">
                                </div>
                                <?php endif; ?>
                                <button type="submit" class="btn-submit">
                                    <i class="bi bi-check2-circle"></i>
                                    Submit Request
                                </button>
                            </form>
                            <?php else: ?>
                            <div class="alert alert-info" style="margin:0;">
                                <span><i class="bi bi-info-circle-fill me-2"></i>Add products to begin your request</span>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
    $(document).ready(function () {
        function positionDropdown() {
            var input = $('#product-name')[0];
            if (!input) return;
            var rect = input.getBoundingClientRect();
            $('#search-results').css({
                left: rect.left + 'px',
                top: (rect.bottom + 6) + 'px',
                width: rect.width + 'px'
            });
        }
        $('#product-name').on('input', function () {
            let query = $(this).val().trim();
            if (query.length > 1) {
                $.ajax({
                    url: "<?php echo e(url('requestSearch')); ?>",
                    method: 'GET',
                    data: { query: query },
                    success: function (data) {
                        if (!data || data.error) {
                            $('#search-results').html('<div class="search-no-results">' + (data.error || 'No products found') + '</div>');
                            positionDropdown();
                            $('#search-results').show();
                            return;
                        }
                        let output = '';
                        data.forEach(function (product) {
                            let price = parseFloat(product.sPrice) || 0;
                            output += `
                                <div class="search-item"
                                     data-product_id="${product.product_id}"
                                     data-name01="${product.name01}"
                                     data-price="${price}"
                                     data-discount="${product.discount || 0}">
                                    <div class="search-item-name">${product.name01}</div>
                                    <div class="search-item-quantity float-end">${product.quantity}</div>
                                    <div class="search-item-price">${price.toFixed(2)} TZS</div>
                                </div>`;
                        });
                        $('#search-results').html(output);
                        positionDropdown();
                        $('#search-results').show();
                    },
                    error: function (xhr) {
                        let msg = 'Error loading results';
                        if (xhr.responseJSON && xhr.responseJSON.error) { msg = xhr.responseJSON.error; }
                        else if (xhr.status === 401) { msg = 'Session expired. Please refresh.'; }
                        else if (xhr.status === 0) { msg = 'Cannot connect to server'; }
                        $('#search-results').html('<div class="search-no-results">' + msg + '</div>');
                        positionDropdown();
                        $('#search-results').show();
                    }
                });
            } else {
                $('#search-results').hide().html('');
            }
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('#product-name, #search-results').length) {
                $('#search-results').hide();
            }
        });

        $(document).on('click', '#search-results .search-item', function () {
            $('#search-results .search-item').removeClass('selected');
            $(this).addClass('selected');

            let productName        = $(this).data('name01');
            let productPrice       = $(this).data('price');
            let productId          = $(this).data('product_id');
            let productMaxDiscount = $(this).data('discount') || 0;

            $('#product-name').val(productName);
            $('#pId').val(productId);
            $('#pPrice').val(productPrice);
            $('#maxDiscount').val(productMaxDiscount);
            $('#search-results').hide();
            $('#formRequestDate').val($('#requestDate').val());
            $('#addItemBtn').prop('disabled', false);
            setTimeout(function () { $('#addItemForm').submit(); }, 100);
        });

        $('#requestDate').on('change', function () {
            $('#formRequestDate').val($(this).val());
        });
    });
    </script>
</body>
</html><?php /**PATH C:\Users\HomePC\Music\leruma pos\resources\views/item_request.blade.php ENDPATH**/ ?>