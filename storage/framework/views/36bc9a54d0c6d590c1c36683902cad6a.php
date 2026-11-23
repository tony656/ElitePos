<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config("app.name")); ?> — <?php echo app('translator')->get('messages.make_return_page_title'); ?></title>
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
            --font: 'DM Sans', system-ui, sans-serif;
            --mono: 'DM Mono', monospace;
            --r: 6px; --r-lg: 8px; --r-xl: 8px;
            --shadow-sm: 0 2px 8px rgba(8,61,108,.06);
            --shadow-md: 0 5px 16px rgba(8,61,108,.09);
            --shadow-lg: 0 10px 28px rgba(8,61,108,.13);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font);
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

        .main-wrap { max-width: 1800px; margin: 0 auto; padding: 1.1rem 1.25rem 1.5rem; }

        /* ── Page Header: flat white strip ── */
        .pg-header {
            background: transparent;
            border: 0;
            border-radius: 0;
            padding: .25rem 0 .9rem;
            margin-bottom: .55rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            position: relative;
            overflow: visible;
        }
        .pg-header::before, .pg-header::after { display: none; }

        .pg-left { display: flex; align-items: center; gap: .7rem; position: relative; z-index: 1; }

        .header-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--r-lg);
            background: var(--rose-pale);
            border: 1px solid #f5b8c2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            color: #c9314a;
            flex-shrink: 0;
        }

        .pg-title-text h1 {
            font-size: 1.42rem;
            font-weight: 700;
            color: #064b86;
            letter-spacing: -.35px;
            margin: 0;
        }
        .pg-title-text p {
            font-size: .78rem;
            color: #647f99;
            margin-top: 2px;
            margin-bottom: 0;
            font-weight: 400;
        }

        .pg-right { display: flex; gap: .5rem; position: relative; z-index: 1; flex-wrap: wrap; }

        .btn-ghost-hdr {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .5rem .8rem;
            border-radius: var(--r);
            border: 1px solid #d4e1ed;
            background: #fff;
            color: #42617e;
            font-family: var(--font);
            font-size: .75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
            letter-spacing: .01em;
        }
        .btn-ghost-hdr i { font-size: .82rem; }
        .btn-ghost-hdr:hover {
            background: #eff6ff;
            border-color: #0b68b4;
            color: #0756a6;
            transform: translateY(-1px);
        }

        /* ── Alerts ── */
        .alert {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .7rem .9rem;
            border-radius: var(--r-lg);
            font-size: .78rem;
            font-weight: 500;
            margin-bottom: .85rem;
        }
        .alert-success { background: var(--emerald-pale); color: #056f58; border-left: 3px solid var(--emerald); }
        .alert-danger  { background: var(--rose-pale);    color: #c9314a; border-left: 3px solid var(--rose); }

        /* ── Main grid ── */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: .85rem;
            height: calc(100vh - 200px);
            min-height: 500px;
        }
        @media(max-width:1000px) { .main-grid { grid-template-columns: 1fr; height: auto; } }

        /* ── Panel ── */
        .panel {
            background: var(--white);
            border: 1px solid #dce7f1;
            border-radius: var(--r-lg);
            overflow: hidden;
            box-shadow: 0 2px 7px rgba(8,61,108,.045);
            display: flex;
            flex-direction: column;
        }

        .panel-head {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .65rem .9rem;
            border-bottom: 1px solid #dce7f1;
            background: #fff;
            flex-shrink: 0;
        }
        .panel-head-icon {
            width: 28px;
            height: 28px;
            border-radius: var(--r);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .82rem;
            flex-shrink: 0;
        }
        .phi-rose  { background: var(--rose-pale);    color: #c9314a; }
        .phi-navy  { background: #e8f2fc;              color: #0866b2; }
        .phi-amber { background: var(--amber-pale);    color: #0756a6; }

        .panel-title {
            font-size: .82rem;
            font-weight: 700;
            color: var(--navy);
            flex: 1;
        }
        .count-pill {
            font-size: .68rem;
            font-weight: 700;
            font-family: var(--mono);
            padding: 2px 8px;
            border-radius: 20px;
            background: #0866b2;
            color: #fff;
        }

        .panel-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding: .8rem;
            gap: .8rem;
        }

        /* ── Shop row / search ── */
        .shop-row {
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: .7rem;
            align-items: start;
        }
        @media(max-width:768px) { .shop-row { grid-template-columns: 1fr; } }

        .search-wrap { position: relative; flex: 1; }
        .search-wrap i.si {
            position: absolute;
            left: .7rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
            font-size: .82rem;
            pointer-events: none;
        }
        .search-input {
            width: 100%;
            padding: .55rem 2rem .55rem 2.15rem;
            border: 1px solid #d4e1ed;
            border-radius: var(--r);
            font-family: var(--font);
            font-size: .78rem;
            color: var(--slate-800);
            background: #fff;
            outline: none;
            transition: border-color .18s, box-shadow .18s;
        }
        .search-input:focus {
            border-color: #0b68b4;
            box-shadow: 0 0 0 3px rgba(11,104,180,.09);
        }

        /* ── Search dropdown ── */
        .results-drop {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            z-index: 200;
            background: #fff;
            border: 1px solid #cfe2f5;
            border-radius: var(--r-lg);
            max-height: 350px;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
            display: none;
        }
        .results-drop.open { display: block; }
        .results-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .5rem .8rem;
            border-bottom: 1px solid #dce7f1;
            background: #f5f9fd;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .results-head-label {
            font-size: .62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--slate-500);
        }

        .prod-item {
            padding: .55rem .8rem;
            cursor: pointer;
            border-bottom: 1px solid #edf3f9;
            transition: all .12s;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
        }
        .prod-item:last-child { border-bottom: none; }
        .prod-item:hover {
            background: #edf6ff;
            border-left: 3px solid #0b68b4;
            padding-left: calc(.8rem - 3px);
        }
        .prod-item-info { flex: 1; }
        .prod-item-name {
            font-size: .78rem;
            font-weight: 600;
            color: #164a78;
            margin-bottom: 3px;
        }
        .prod-item-meta {
            font-size: .68rem;
            color: #7189a0;
            margin-top: 2px;
            display: flex;
            gap: .65rem;
            flex-wrap: wrap;
            font-family: var(--mono);
        }
        .prod-item-stock {
            font-size: .68rem;
            font-weight: 700;
            color: var(--emerald);
            font-family: var(--mono);
        }
        .btn-add-product {
            background: #0866b2;
            color: #fff;
            border: 1px solid #0866b2;
            padding: .3rem .65rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: .68rem;
            font-weight: 600;
            transition: all .15s;
            font-family: inherit;
            margin-top: 4px;
        }
        .btn-add-product:hover {
            background: #064f91;
            border-color: #064f91;
            transform: translateY(-1px);
        }
        .no-results {
            padding: 1.4rem;
            text-align: center;
            font-size: .78rem;
            color: var(--slate-400);
        }

        /* ── Cart ── */
        .cart-wrap {
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .cart-scroll {
            flex: 1;
            overflow-y: auto;
            max-height: 400px;
        }

        .cart-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: .74rem;
        }
        .cart-tbl thead th {
            background: #07569b;
            color: #fff;
            font-size: .66rem;
            font-weight: 600;
            text-transform: none;
            letter-spacing: .015em;
            padding: .55rem .6rem;
            border: 1px solid rgba(255,255,255,.13);
            white-space: nowrap;
            text-align: left;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .cart-tbl thead th:last-child { text-align: center; }
        .cart-tbl tbody td {
            padding: .42rem .6rem;
            border: 1px solid #e5edf5;
            vertical-align: middle;
            color: #244866;
        }
        .cart-tbl tbody tr:nth-child(even) td { background: #f8fbfe; }
        .cart-tbl tbody tr:hover td { background: #edf6ff; }

        .cart-prod-name {
            font-weight: 600;
            color: #164a78;
            font-size: .74rem;
            max-width: 150px;
            word-wrap: break-word;
        }
        .num-input {
            width: 60px;
            padding: .25rem .35rem;
            text-align: center;
            border: 1px solid #d4e1ed;
            border-radius: 5px;
            font-family: var(--mono);
            font-size: .72rem;
            color: var(--slate-800);
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .num-input:focus { border-color: #0b68b4; box-shadow: 0 0 0 3px rgba(11,104,180,.09); }
        .del-btn {
            width: 26px;
            height: 25px;
            border-radius: 5px;
            background: var(--rose-pale);
            color: #c9314a;
            border: 1px solid #f5b8c2;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .7rem;
            margin: auto;
            transition: all .15s;
        }
        .del-btn:hover { background: var(--rose); color: #fff; border-color: var(--rose); }

        .empty-cart {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            text-align: center;
            color: var(--slate-400);
            flex: 1;
        }
        .empty-cart i { font-size: 1.9rem; margin-bottom: .5rem; opacity: .35; display: block; }
        .empty-cart p { font-size: .78rem; margin: 0; color: var(--slate-400); }

        /* ── Right panel body ── */
        .right-body {
            flex: 1;
            overflow-y: auto;
            padding: .8rem;
            display: flex;
            flex-direction: column;
            gap: .65rem;
        }

        .field { display: flex; flex-direction: column; gap: .25rem; }
        .field-label {
            font-size: .66rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--slate-500);
            display: flex;
            align-items: center;
            gap: .35rem;
        }
        .field-label i { font-size: .68rem; color: #0866b2; }

        .field-input, .field-select, .field-textarea {
            width: 100%;
            font-family: var(--font);
            font-size: .76rem;
            padding: .45rem .65rem;
            border: 1px solid #d4e1ed;
            border-radius: var(--r);
            background: #fff;
            color: var(--slate-800);
            outline: none;
            transition: border-color .18s, box-shadow .18s;
        }
        .field-input:focus, .field-select:focus, .field-textarea:focus {
            border-color: #0b68b4;
            box-shadow: 0 0 0 3px rgba(11,104,180,.09);
        }
        .field-select {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%238da4bb' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .65rem center;
            padding-right: 1.9rem;
        }
        .field-textarea { resize: vertical; min-height: 64px; }
        .field-hint {
            font-size: .68rem;
            color: var(--slate-400);
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        /* ── Summary block ── */
        .summary-block {
            margin-top: auto;
            flex-shrink: 0;
            padding: .8rem .9rem;
            border-top: 1px solid #dce7f1;
            background: #f5f9fd;
        }
        .summ-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: .76rem;
            padding: .2rem 0;
        }
        .summ-row .sl { color: var(--slate-600); }
        .summ-row .sv { font-family: var(--mono); font-weight: 600; color: var(--slate-800); }

        .total-box {
            background: linear-gradient(120deg, #064b86, #0870bd);
            border-radius: var(--r-lg);
            padding: .8rem 1rem;
            margin-top: .55rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 14px rgba(8,61,108,.18);
        }
        .total-box-label {
            font-size: .66rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255,255,255,.65);
        }
        .total-box-val {
            font-family: var(--mono);
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
        }

        .action-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .5rem;
            margin-top: .55rem;
        }
        .btn-clear-cart {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            padding: .55rem;
            border-radius: var(--r);
            border: 1px solid #d4e1ed;
            background: #fff;
            font-family: var(--font);
            font-size: .78rem;
            font-weight: 600;
            color: #42617e;
            cursor: pointer;
            transition: all .15s;
        }
        .btn-clear-cart:hover { background: #eff6ff; border-color: #0b68b4; color: #0756a6; }
        .btn-submit-return {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            padding: .55rem;
            border-radius: var(--r);
            border: 1px solid #0866b2;
            background: #0866b2;
            color: #fff;
            font-family: var(--font);
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(8,102,178,.22);
            transition: all .18s;
        }
        .btn-submit-return:hover { background: #064f91; border-color: #064f91; transform: translateY(-1px); }
        .btn-submit-return:disabled { background: var(--slate-300); border-color: var(--slate-300); cursor: not-allowed; transform: none; box-shadow: none; color: var(--slate-500); }

        /* ── Toast ── */
        .toast-container {
            position: fixed;
            top: 1.1rem;
            right: 1.1rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: .45rem;
        }
        .toast {
            background: #fff;
            border-radius: var(--r-lg);
            border: 1px solid #dce7f1;
            box-shadow: var(--shadow-lg);
            padding: .6rem .9rem;
            display: flex;
            align-items: center;
            gap: .55rem;
            min-width: 250px;
            font-size: .78rem;
            font-weight: 500;
            opacity: 0;
            transform: translateX(100%);
            transition: all .25s ease;
        }
        .toast.show { opacity: 1; transform: translateX(0); }
        .toast-success { border-left: 3px solid var(--emerald); }
        .toast-success i { color: var(--emerald); font-size: .95rem; }
        .toast-error   { border-left: 3px solid var(--rose); }
        .toast-error i { color: var(--rose); font-size: .95rem; }

        /* ── Responsive ── */
        @media(max-width:768px) {
            .main-wrap { padding: .8rem; }
            .pg-title-text h1 { font-size: 1.2rem; }
            .header-icon { width: 34px; height: 34px; font-size: .9rem; }
            .pg-right { width: 100%; }
            .btn-ghost-hdr { flex: 1; justify-content: center; }
        }

        .col-md-9 { flex: 1; padding: 0; }
        .row { display: flex; flex-wrap: nowrap; }
    </style>
</head>
<body>
    <?php echo $__env->make('sidenav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <main class="main-content">
            <div class="main-wrap">

            <div class="pg-header">
                <div class="pg-left">
                    <div class="header-icon"><i class="bi bi-arrow-return-left"></i></div>
                    <div class="pg-title-text">
                        <h1><?php echo e(__('messages.make_return_page_header')); ?></h1>
                        <p><?php echo e(__('messages.select_products_return')); ?></p>
                    </div>
                </div>
                <div class="pg-right">
                    <a href="<?php echo e(url('make-receiving')); ?>" class="btn-ghost-hdr">
                        <i class="bi bi-plus-circle"></i> <?php echo e(__('messages.make_receiving')); ?>

                    </a>
                    <a href="<?php echo e(url('view-returns')); ?>" class="btn-ghost-hdr">
                        <i class="bi bi-list-check"></i> <?php echo e(__('messages.view_returns')); ?>

                    </a>
                </div>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i> <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <div class="main-grid">

                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-head-icon phi-rose"><i class="bi bi-search"></i></div>
                        <span class="panel-title">Select Products to Return</span>
                        <span class="count-pill" id="cartCount">0</span>
                    </div>
                    <div class="panel-body">

                        <div class="shop-row">
                            <div class="field shop-selector" style="min-width: 200px;">
                                <form action="<?php echo e(route('changeShop')); ?>" method="get" id="shopForm">
                                    <select name="shop_id" class="field-input" onchange="this.form.submit()">
                                        <option value="" disabled>Select shop</option>
                                        <?php $__currentLoopData = $allShops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($shop['id']); ?>" <?php echo e($shop['id'] == session('selected_shop_id') ? 'selected' : ''); ?>>
                                                <?php echo e($shop['name']); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </form>
                            </div>

                            <div class="search-wrap">
                                <i class="bi bi-search si"></i>
                                <input type="text" class="search-input" id="productSearch" placeholder="Search products by name…" autocomplete="off">
                                <div class="results-drop" id="productListSection">
                                    <div class="results-head">
                                        <span class="results-head-label">Available products</span>
                                        <span class="count-pill" id="productCount" style="background:#07569b;">0</span>
                                    </div>
                                    <div id="productList"></div>
                                </div>
                            </div>
                        </div>

                        <div class="cart-wrap">
                            <div class="panel-head" style="border-radius:0; background:#f5f9fd; border:none; border-bottom:1px solid #dce7f1; border-top:1px solid #dce7f1; flex-shrink:0; padding:.55rem .8rem;">
                                <div class="panel-head-icon phi-navy"><i class="bi bi-cart3"></i></div>
                                <span class="panel-title"><?php echo e(__('messages.products_to_return')); ?></span>
                            </div>
                            <div class="cart-scroll" id="cartItems">
                                <div class="empty-cart">
                                    <i class="bi bi-arrow-return-left"></i>
                                    <p><?php echo e(__('messages.no_products_added_yet')); ?><br><span style="font-size:.72rem;"><?php echo e(__('messages.search_and_click_product')); ?></span></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-head-icon phi-amber"><i class="bi bi-clipboard-check"></i></div>
                        <span class="panel-title"><?php echo e(__('messages.return_details')); ?></span>
                    </div>

                    <form id="orderForm" style="display:contents;">
                        <?php echo csrf_field(); ?>
                        <div class="right-body">
                            <div class="field">
                                <label class="field-label"><i class="bi bi-shop"></i> Supplier</label>
                                <select id="supplier" name="supplier" class="field-select" required>
                                    <option value="" disabled selected>Select supplier…</option>
                                    <?php
                                        $vendors = DB::table('vendors')->get();
                                    ?>
                                    <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($vendor->id); ?>"><?php echo e($vendor->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="field">
                                <label class="field-label"><i class="bi bi-person"></i> Processed by</label>
                                <select id="served" name="served" class="field-select" required>
                                    <option value="" disabled selected>Select staff…</option>
                                    <?php
                                        $users = DB::table('users')->orderBy('name', 'asc')->get();
                                    ?>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->name); ?>"><?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="field">
                                <label class="field-label"><i class="bi bi-chat-text"></i> Reason for return</label>
                                <textarea id="reason" name="reason" class="field-textarea" placeholder="Specify the reason for return…" required></textarea>
                            </div>

                            <div class="field">
                                <label class="field-label"><i class="bi bi-credit-card"></i> Payment type</label>
                                <select id="transactionType" name="transactionType" class="field-select">
                                    <option value="Cash">Cash</option>
                                    <option value="Credit">Credit</option>
                                </select>
                            </div>

                            <div class="field">
                                <label class="field-label"><i class="bi bi-calendar-check"></i> <?php echo e(__('messages.return_date')); ?></label>
                                <input type="date" id="receivingDate" name="receivingDate" class="field-input"
                                       value="<?php echo e(date('Y-m-d')); ?>" max="<?php echo e(date('Y-m-d')); ?>">
                                <span class="field-hint"><i class="bi bi-info-circle"></i> Defaults to today</span>
                            </div>
                        </div>

                        <div class="summary-block">
                            <div class="summ-row">
                                <span class="sl">Total items</span>
                                <span class="sv" id="itemCount">0</span>
                            </div>
                            <div class="summ-row">
                                <span class="sl">Products in cart</span>
                                <span class="sv" id="cartCountSumm">0</span>
                            </div>

                            <div class="total-box">
                                <div>
                                    <div class="total-box-label">Total return value</div>
                                </div>
                                <div class="total-box-val" id="totalAmount">Tsh 0</div>
                            </div>

                            <div class="action-row">
                                <button type="button" class="btn-clear-cart" id="clearCartBtn">
                                    <i class="bi bi-x-circle"></i> Clear
                                </button>
                                <button id="submitOrderBtn" class="btn-submit-return" formaction="<?php echo e(route('process-return')); ?>">
                                    <i class="bi bi-check-circle-fill"></i> Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </main>

<div class="toast-container" id="toastContainer"></div>

<script>
// ════════════════════════════════════════════
// PRODUCT DATA FROM SERVER (USING JSON ENCODE FOR SAFETY)
// ════════════════════════════════════════════
<?php
    $currentShopId = session('selected_shop_id');
    $products = DB::table('products')
        ->where('name01', '!=', '')
        ->where('account', $currentShopId)
        ->get();
    
    $productsArray = [];
    foreach($products as $p) {
        $productsArray[] = [
            'id' => $p->product_id,
            'name' => $p->name01,
            'cost' => (float)($p->bPrice ?? 0),
            'wholesale' => (float)($p->wholesale ?? 0),
            'retail' => (float)($p->sPrice ?? 0),
            'currentStock' => (int)($p->quantity ?? 0)
        ];
    }
?>

// This uses JSON encoding which safely handles all special characters
const allProducts = <?php echo json_encode($productsArray, 15, 512) ?>;

console.log('Products loaded:', allProducts.length);
if (allProducts.length === 0) {
    console.warn('No products found for shop ID: <?php echo e($currentShopId); ?>');
}

// ════════════════════════════════════════════
// CART STATE MANAGEMENT
// ════════════════════════════════════════════
const STORAGE_KEY = 'returnCartUser';
let shoppingCart = [];

function saveCart() { 
    localStorage.setItem(STORAGE_KEY, JSON.stringify(shoppingCart)); 
}

function loadCart() {  
    try { 
        const s = localStorage.getItem(STORAGE_KEY); 
        if(s) shoppingCart = JSON.parse(s); 
    } catch(e) { 
        console.error('Failed to load cart:', e);
        shoppingCart = []; 
    } 
}

function clearStoredCart() { 
    localStorage.removeItem(STORAGE_KEY); 
}

// ════════════════════════════════════════════
// PRODUCT SEARCH FUNCTIONALITY
// ════════════════════════════════════════════
function initProductSearch() {
    const searchInput = document.getElementById('productSearch');
    const resultsDrop = document.getElementById('productListSection');
    
    if (!searchInput) {
        console.error('Search input not found');
        return;
    }
    
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        
        if (searchTerm.length === 0) {
            resultsDrop.classList.remove('open');
            return;
        }
        
        const filteredProducts = allProducts.filter(product => 
            product.name && product.name.toLowerCase().includes(searchTerm)
        );
        
        displaySearchResults(filteredProducts);
        resultsDrop.classList.add('open');
    });
    
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsDrop.contains(e.target)) {
            resultsDrop.classList.remove('open');
        }
    });
    
    if (resultsDrop) {
        resultsDrop.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
}

function displaySearchResults(products) {
    const productList = document.getElementById('productList');
    const productCount = document.getElementById('productCount');
    
    if (!productList) return;
    
    productCount.textContent = products.length;
    
    if (products.length === 0) {
        productList.innerHTML = '<div class="no-results">No products found</div>';
        return;
    }
    
    productList.innerHTML = products.map(product => {
        // Safely escape the product name
        const safeName = escapeHtml(String(product.name));
        const cost = Number(product.cost).toLocaleString();
        const wholesale = Number(product.wholesale).toLocaleString();
        const retail = Number(product.retail).toLocaleString();
        const stock = Number(product.currentStock);
        
        return `
            <div class="prod-item" data-product-id="${product.id}">
                <div class="prod-item-info">
                    <div class="prod-item-name">${safeName}</div>
                    <div class="prod-item-meta">
                        <span>💰 Cost: Tsh ${cost}</span>
                        <span>🏷️ Wholesale: Tsh ${wholesale}</span>
                        <span>🛍️ Retail: Tsh ${retail}</span>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div class="prod-item-stock">📦 Stock: ${stock}</div>
                    <button class="btn-add-product" onclick="addToCart('${product.id}')">
                        <i class="bi bi-plus-lg"></i> Add
                    </button>
                </div>
            </div>
        `;
    }).join('');
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        })
        .replace(/[\uD800-\uDBFF][\uDC00-\uDFFF]/g, function(c) {
            return c;
        });
}

// ════════════════════════════════════════════
// CART OPERATIONS
// ════════════════════════════════════════════
window.addToCart = function(productId) {
    const product = allProducts.find(p => p.id === productId);
    
    if (!product) {
        showToast('Product not found!', 'error');
        return;
    }
    
    if (product.currentStock <= 0) {
        showToast(`Cannot return "${product.name}" - stock is empty!`, 'error');
        return;
    }
    
    const existingItem = shoppingCart.find(item => item.productId === productId);
    
    if (existingItem) {
        if (existingItem.quantity < product.currentStock) {
            existingItem.quantity++;
            saveCart();
            refreshCart();
            showToast(`Increased "${product.name}" quantity to ${existingItem.quantity}`, 'success');
        } else {
            showToast(`Cannot add more "${product.name}" - only ${product.currentStock} in stock!`, 'error');
        }
    } else {
        shoppingCart.push({
            cartId: Date.now() + Math.random().toString(36).substr(2, 9),
            productId: product.id,
            name: product.name,
            cost: product.cost,
            wholesale: product.wholesale,
            retail: product.retail,
            quantity: 1
        });
        saveCart();
        refreshCart();
        showToast(`✅ "${product.name}" added to cart`, 'success');
    }
    
    const searchInput = document.getElementById('productSearch');
    const resultsDrop = document.getElementById('productListSection');
    if (searchInput) searchInput.value = '';
    if (resultsDrop) resultsDrop.classList.remove('open');
};

function removeFromCart(cartId) {
    const item = shoppingCart.find(x => x.cartId === cartId);
    if (item) {
        shoppingCart = shoppingCart.filter(x => x.cartId !== cartId);
        saveCart();
        refreshCart();
        showToast(`Removed "${item.name}" from cart`, 'success');
    }
}

function updateQuantity(cartId, newQuantity) {
    const item = shoppingCart.find(x => x.cartId === cartId);
    if (item) {
        const product = allProducts.find(p => p.id === item.productId);
        let quantity = parseInt(newQuantity) || 1;
        
        if (quantity < 1) quantity = 1;
        if (product && quantity > product.currentStock) {
            showToast(`Cannot exceed available stock (${product.currentStock})`, 'error');
            quantity = product.currentStock;
        }
        
        item.quantity = quantity;
        saveCart();
        refreshCart();
    }
}

function updatePrice(cartId, field, value) {
    const item = shoppingCart.find(x => x.cartId === cartId);
    if (item) {
        item[field] = parseFloat(value) || 0;
        saveCart();
        refreshSummary();
    }
}

function clearCart() {
    if (shoppingCart.length === 0) return;
    
    if (confirm(`Clear all ${shoppingCart.length} items from cart?`)) {
        shoppingCart = [];
        clearStoredCart();
        refreshCart();
        showToast('Cart cleared successfully', 'success');
    }
}

// ════════════════════════════════════════════
// UI REFRESH FUNCTIONS
// ════════════════════════════════════════════
function refreshCart() {
    const cartContainer = document.getElementById('cartItems');
    
    if (!cartContainer) return;
    
    if (shoppingCart.length === 0) {
        cartContainer.innerHTML = `
            <div class="empty-cart">
                <i class="bi bi-arrow-return-left"></i>
                <p><?php echo e(__('messages.no_products_added_yet')); ?><br><span style="font-size:.72rem;"><?php echo e(__('messages.search_and_click_product')); ?></span></p>
            </div>
        `;
        refreshSummary();
        updateCountPills();
        return;
    }
    
    cartContainer.innerHTML = `
        <table class="cart-tbl">
            <thead>
                <tr>
                    <th>Product</th>
                    <th style="width:70px">Qty</th>
                    <th>Cost</th>
                    <th>Wholesale</th>
                    <th>Retail</th>
                    <th style="width:40px; text-align:center;">Del</th>
                </tr>
            </thead>
            <tbody>
                ${shoppingCart.map(item => `
                    <tr>
                        <td data-label="Product" class="cart-prod-name">${escapeHtml(item.name)}</td>
                        <td data-label="Qty">
                            <input type="number" class="num-input" value="${item.quantity}" min="1" 
                                   onchange="updateQuantity('${item.cartId}', this.value)">
                        </td>
                        <td data-label="Cost">
                            <input type="number" class="num-input" value="${item.cost}" step="0.01" 
                                   onchange="updatePrice('${item.cartId}', 'cost', this.value)">
                        </td>
                        <td data-label="Wholesale">
                            <input type="number" class="num-input" value="${item.wholesale}" step="0.01" 
                                   onchange="updatePrice('${item.cartId}', 'wholesale', this.value)">
                        </td>
                        <td data-label="Retail">
                            <input type="number" class="num-input" value="${item.retail}" step="0.01" 
                                   onchange="updatePrice('${item.cartId}', 'retail', this.value)">
                        </td>
                        <td data-label="Delete" style="text-align:center;">
                            <button class="del-btn" onclick="removeFromCart('${item.cartId}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
    
    refreshSummary();
    updateCountPills();
}

function refreshSummary() {
    let totalValue = 0;
    let totalItems = 0;
    
    shoppingCart.forEach(item => {
        totalValue += (item.cost || 0) * (item.quantity || 0);
        totalItems += item.quantity || 0;
    });
    
    const itemCountElem = document.getElementById('itemCount');
    const cartCountSummElem = document.getElementById('cartCountSumm');
    const totalAmountElem = document.getElementById('totalAmount');
    
    if (itemCountElem) itemCountElem.textContent = totalItems;
    if (cartCountSummElem) cartCountSummElem.textContent = shoppingCart.length;
    if (totalAmountElem) totalAmountElem.textContent = 'Tsh ' + Math.round(totalValue).toLocaleString();
}

function updateCountPills() {
    const cartCountElem = document.getElementById('cartCount');
    if (cartCountElem) cartCountElem.textContent = shoppingCart.length;
}

// ════════════════════════════════════════════
// SUBMIT ORDER
// ════════════════════════════════════════════
function submitOrder() {
    const supplier = document.getElementById('supplier').value;
    const served = document.getElementById('served').value;
    const reason = document.getElementById('reason').value;
    const receivingDate = document.getElementById('receivingDate').value;
    const transactionType = document.getElementById('transactionType').value;
    
    if (shoppingCart.length === 0) {
        showToast('Please add products to cart first!', 'error');
        return;
    }
    
    if (!supplier) {
        showToast('Please select a supplier!', 'error');
        return;
    }
    
    if (!served) {
        showToast('Please select staff member!', 'error');
        return;
    }
    
    if (!reason || reason.trim() === '') {
        showToast('Please provide a reason for return!', 'error');
        return;
    }
    
    if (!confirm(`Submit return request for ${shoppingCart.length} product(s)?\n\nThis will be sent for admin approval.`)) {
        return;
    }
    
    const submitBtn = document.getElementById('submitOrderBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
    submitBtn.disabled = true;
    
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('supplier', supplier);
    formData.append('served', served);
    formData.append('reason', reason);
    formData.append('receivingDate', receivingDate);
    formData.append('transactionType', transactionType);
    
    shoppingCart.forEach(item => {
        formData.append('product_id[]', item.productId);
        formData.append('quantity[]', item.quantity);
        formData.append('bPrice[]', item.cost);
        formData.append('wholesale[]', item.wholesale);
        formData.append('sPrice[]', item.retail);
        formData.append('transactionType[]', transactionType);
        formData.append('expiry[]', '');
    });
    
    fetch('<?php echo e(route("process-return")); ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast(<?php echo json_encode(__('messages.return_request_submitted'), 15, 512) ?>, 'success');
            shoppingCart = [];
            clearStoredCart();
            refreshCart();
            document.getElementById('orderForm').reset();
            document.getElementById('receivingDate').value = '<?php echo e(date("Y-m-d")); ?>';
            setTimeout(() => {
                window.location.href = '<?php echo e(url("view-returns")); ?>';
            }, 2000);
        } else {
            throw new Error(data.message || 'Submission failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('❌ Error processing return. Please try again.', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// ════════════════════════════════════════════
// TOAST NOTIFICATIONS
// ════════════════════════════════════════════
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill';
    toast.innerHTML = `<i class="bi ${icon}"></i> ${escapeHtml(message)}`;
    
    container.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// ════════════════════════════════════════════
// EVENT BINDINGS AND INITIALIZATION
// ════════════════════════════════════════════
function bindEvents() {
    const clearCartBtn = document.getElementById('clearCartBtn');
    const submitBtn = document.getElementById('submitOrderBtn');
    
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', clearCart);
    }
    
    if (submitBtn) {
        submitBtn.addEventListener('click', submitOrder);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing...');
    loadCart();
    bindEvents();
    refreshCart();
    initProductSearch();
    console.log('Available products for search:', allProducts.length);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH C:\Users\HomePC\Music\leruma pos\resources\views/makeReturn.blade.php ENDPATH**/ ?>