<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.make_receiving')</title>
    @include('links')
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

        /* ── Scrollbars ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        /* ── Wrapper ── */
        .main-wrap { max-width: 1800px; margin: 0 auto; padding: 1.1rem 1.25rem 1.5rem; }

        /* ── Page Header: flat white strip ── */
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

        .header-actions { display: flex; gap: .5rem; flex-wrap: wrap; align-items: center; }

        .hbtn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-family: 'DM Sans', sans-serif;
            font-size: .75rem; font-weight: 600;
            padding: .5rem .8rem;
            border-radius: var(--r);
            border: 1px solid transparent;
            cursor: pointer; text-decoration: none;
            transition: all 0.15s;
            letter-spacing: .01em;
        }
        .hbtn i { font-size: .82rem; }
        .hbtn-outline {
            background: #fff;
            border-color: #d4e1ed;
            color: #42617e;
        }
        .hbtn-outline:hover { background: #eff6ff; border-color: #0b68b4; color: #0756a6; transform: translateY(-1px); }
        .hbtn-outline-rose {
            background: var(--rose-pale);
            border-color: #f5b8c2;
            color: #c9314a;
        }
        .hbtn-outline-rose:hover { background: var(--rose); border-color: var(--rose); color: #fff; transform: translateY(-1px); }

        /* ── Header inline selects (shop + allocation) ── */
        .header-actions .field { min-width: 200px; margin-right: .25rem; }
        .header-actions .field-input {
            font-size: .76rem;
            padding: .45rem 2rem .45rem .7rem;
            border: 1px solid #d4e1ed;
            border-radius: var(--r);
            background: #fff;
            color: var(--slate-800);
            outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%238da4bb' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .65rem center;
            transition: border-color .18s, box-shadow .18s;
        }
        .header-actions .field-input:focus {
            border-color: #0b68b4;
            box-shadow: 0 0 0 3px rgba(11,104,180,.09);
        }

        /* ── Alerts ── */
        .alert-custom {
            display: flex; align-items: center; gap: 0.6rem;
            padding: .7rem .9rem;
            border-radius: var(--r-lg);
            font-size: .78rem; font-weight: 500;
            margin-bottom: .85rem;
            animation: fadeSlide 0.3s ease;
        }
        .alert-success-custom { background: var(--emerald-pale); border-left: 3px solid var(--emerald); color: #056f58; }
        .alert-danger-custom  { background: var(--rose-pale);    border-left: 3px solid var(--rose);    color: #c9314a; }
        @keyframes fadeSlide { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }

        /* ── Two-column layout ── */
        .layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: .85rem;
            height: calc(100vh - 175px);
            min-height: 520px;
        }
        @media (max-width: 1100px) { .layout { grid-template-columns: 1fr; height: auto; } }

        /* ── Panel ── */
        .panel {
            background: var(--white);
            border: 1px solid #dce7f1;
            border-radius: var(--r-lg);
            box-shadow: 0 2px 7px rgba(8,61,108,.045);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .panel-head {
            background: #07569b;
            padding: .65rem .9rem;
            display: flex; align-items: center; gap: 0.5rem;
            color: #fff;
            font-size: .82rem; font-weight: 600;
            flex-shrink: 0;
            letter-spacing: .01em;
        }
        .panel-head i { color: #b8dcff; font-size: .9rem; }

        /* ── Search area ── */
        .search-wrap {
            padding: .75rem .85rem 0;
            flex-shrink: 0;
            position: relative;
        }

        .search-box { position: relative; }
        .search-icon {
            position: absolute; left: 0.7rem; top: 50%; transform: translateY(-50%);
            color: var(--slate-400); font-size: .82rem; pointer-events: none;
        }
        .search-input {
            width: 100%;
            font-family: 'DM Sans', sans-serif;
            font-size: .78rem;
            padding: .55rem 2.2rem .55rem 2.15rem;
            border: 1px solid #d4e1ed;
            border-radius: var(--r);
            background: #fff;
            color: var(--slate-800);
            transition: border-color 0.18s, box-shadow 0.18s;
            outline: none;
        }
        .search-input:focus {
            border-color: #0b68b4;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(11,104,180,.09);
        }
        .search-input::placeholder { color: var(--slate-400); }
        .search-clear {
            position: absolute; right: 0.6rem; top: 50%; transform: translateY(-50%);
            background: none; border: none;
            color: var(--slate-400); cursor: pointer; font-size: .9rem;
            display: none; padding: 0.1rem;
            transition: color 0.15s;
        }
        .search-clear:hover { color: var(--rose); }
        .search-hint {
            font-size: .7rem; color: var(--slate-400);
            margin-top: .3rem; padding-left: .2rem;
        }

        /* ── Product dropdown ── */
        .product-dropdown {
            position: absolute;
            top: calc(100% - 4px);
            left: .85rem; right: .85rem;
            background: #fff;
            border: 1px solid #cfe2f5;
            border-radius: var(--r-lg);
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            display: none;
            flex-direction: column;
            max-height: 320px;
            overflow: hidden;
            animation: dropIn 0.18s ease;
        }
        .product-dropdown.open { display: flex; }
        @keyframes dropIn { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }

        .dd-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: .5rem .8rem;
            border-bottom: 1px solid #dce7f1;
            background: #f5f9fd;
            flex-shrink: 0;
        }
        .dd-header-label { font-size: .66rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: .06em; }
        .dd-count {
            background: #0866b2; color: #fff;
            font-size: .68rem; font-weight: 700;
            padding: 2px 8px; border-radius: 20px;
        }
        .dd-list { overflow-y: auto; flex: 1; }

        .prod-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: .55rem .8rem;
            border-bottom: 1px solid #edf3f9;
            cursor: pointer;
            transition: background 0.12s, transform 0.1s;
            user-select: none;
        }
        .prod-item:last-child { border-bottom: none; }
        .prod-item:hover { background: #edf6ff; transform: translateX(2px); }

        .prod-name { font-weight: 600; color: #164a78; font-size: .78rem; margin-bottom: .15rem; }
        .prod-meta {
            display: flex; gap: .65rem;
            font-size: .68rem; color: #7189a0;
            font-family: 'DM Mono', monospace;
        }
        .prod-meta span { display: flex; align-items: center; gap: .2rem; }
        .prod-meta i { font-size: .65rem; color: var(--slate-400); }

        .prod-add-btn {
            width: 24px; height: 24px;
            background: #0866b2;
            color: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem;
            flex-shrink: 0;
            transition: background 0.15s, transform 0.12s;
        }
        .prod-item:hover .prod-add-btn { background: #064f91; transform: scale(1.1); }

        /* ── Cart area ── */
        .cart-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding-top: .35rem;
        }

        .cart-head {
            display: flex; justify-content: space-between; align-items: center;
            padding: .5rem .9rem;
            border-bottom: 1px solid #dce7f1;
            flex-shrink: 0;
        }
        .cart-head-label {
            font-size: .68rem; font-weight: 700;
            color: var(--slate-500); text-transform: uppercase; letter-spacing: .06em;
            display: flex; align-items: center; gap: .35rem;
        }
        .cart-head-label i { color: #0866b2; font-size: .78rem; }
        .cart-badge {
            background: #eff6ff; color: #0756a6;
            font-size: .68rem; font-weight: 700;
            padding: 2px 9px; border-radius: 20px;
            border: 1px solid #cfe2f5;
            font-family: 'DM Mono', monospace;
        }

        .cart-scroll { flex: 1; overflow-y: auto; }

        /* ── Cart table (deep-blue header, dense rows) ── */
        table.cart-tbl {
            width: 100%; border-collapse: collapse;
            font-size: .74rem;
        }
        table.cart-tbl thead th {
            background: #07569b;
            color: #fff;
            font-size: .66rem; font-weight: 600;
            text-transform: none; letter-spacing: .015em;
            padding: .55rem .6rem;
            border: 1px solid rgba(255,255,255,.13);
            position: sticky; top: 0; z-index: 5;
            white-space: nowrap;
            text-align: left;
        }
        table.cart-tbl tbody td {
            padding: .42rem .6rem;
            border: 1px solid #e5edf5;
            vertical-align: middle;
            color: #244866;
        }
        table.cart-tbl tbody tr:nth-child(even) td { background: #f8fbfe; }
        table.cart-tbl tbody tr:hover td { background: #edf6ff; }

        .cart-prod-name {
            font-weight: 600; color: #164a78;
            max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
            font-size: .76rem;
        }

        .tbl-input {
            font-family: 'DM Mono', monospace;
            font-size: .72rem;
            width: 100%; padding: .25rem .4rem;
            border: 1px solid #d4e1ed;
            border-radius: 5px;
            background: #fff;
            color: var(--slate-800);
            text-align: center;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            min-width: 56px;
        }
        .tbl-input:focus { border-color: #0b68b4; box-shadow: 0 0 0 3px rgba(11,104,180,.09); }

        .del-btn {
            width: 26px; height: 25px;
            background: var(--rose-pale); color: #c9314a;
            border: 1px solid #f5b8c2;
            border-radius: 5px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: .72rem;
            transition: background 0.15s, transform 0.12s;
        }
        .del-btn:hover { background: var(--rose); color: #fff; border-color: var(--rose); transform: translateY(-1px); }

        /* ── Empty state ── */
        .empty-state {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 2.2rem 1rem; text-align: center;
            color: var(--slate-400);
        }
        .empty-state i { font-size: 1.9rem; margin-bottom: .6rem; opacity: 0.4; }
        .empty-state-title { font-size: .82rem; font-weight: 600; margin-bottom: .3rem; color: var(--slate-500); }
        .empty-state p { font-size: .74rem; margin: 0; color: var(--slate-400); }

        /* ── Right panel sections ── */
        .rp-section {
            padding: .85rem .95rem;
            border-bottom: 1px solid #dce7f1;
            flex-shrink: 0;
        }
        .rp-section:last-child { border-bottom: none; }

        .rp-section-title {
            font-size: .68rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .06em;
            color: var(--slate-500);
            margin-bottom: .65rem;
            display: flex; align-items: center; gap: .35rem;
        }
        .rp-section-title i { color: #0866b2; font-size: .78rem; }

        /* ── Form fields ── */
        .field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .55rem; }
        @media (max-width: 500px) { .field-grid { grid-template-columns: 1fr; } }
        .field-full { grid-column: 1 / -1; }

        .field { display: flex; flex-direction: column; gap: .25rem; }
        .field-label {
            font-size: .72rem; font-weight: 600;
            color: var(--slate-600);
            display: flex; align-items: center; gap: .3rem;
        }
        .field-label i { color: var(--slate-400); font-size: .7rem; }

        .field-input {
            font-family: 'DM Sans', sans-serif;
            font-size: .76rem;
            padding: .45rem .65rem;
            border: 1px solid #d4e1ed;
            border-radius: var(--r);
            background: #fff;
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
            appearance: none;
        }
        .field-input:focus {
            border-color: #0b68b4;
            box-shadow: 0 0 0 3px rgba(11,104,180,.09);
        }
        select.field-input {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%238da4bb' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .65rem center;
            padding-right: 1.9rem;
        }

        .field-hint { font-size: .68rem; color: var(--slate-400); margin-top: .15rem; }

        /* ── Summary ── */
        .summary-rows { display: flex; flex-direction: column; gap: .35rem; margin-bottom: .8rem; }
        .summary-row {
            display: flex; justify-content: space-between; align-items: center;
            font-size: .78rem;
        }
        .sum-label { color: var(--slate-600); font-weight: 500; }
        .sum-value { font-family: 'DM Mono', monospace; font-weight: 500; color: var(--slate-800); font-size: .76rem; }

        .total-card {
            background: linear-gradient(120deg, #064b86, #0870bd);
            border-radius: var(--r-lg);
            padding: .8rem 1rem;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 4px 14px rgba(8,61,108,.18);
        }
        .total-card-label { color: rgba(255,255,255,.65); font-size: .7rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; }
        .total-card-amount {
            font-family: 'DM Mono', monospace;
            font-size: 1.1rem; font-weight: 700;
            color: #fff;
        }
        .total-card-currency { font-size: .72rem; color: rgba(255,255,255,.5); margin-right: 3px; }

        /* ── Action buttons ── */
        .action-btns { display: grid; grid-template-columns: 1fr 2fr; gap: .5rem; margin-top: .8rem; }

        .abtn {
            display: flex; align-items: center; justify-content: center; gap: .4rem;
            font-family: 'DM Sans', sans-serif;
            font-size: .78rem; font-weight: 600;
            padding: .55rem;
            border-radius: var(--r);
            border: 1px solid transparent;
            cursor: pointer;
            transition: all .15s;
            letter-spacing: .01em;
        }
        .abtn:hover { transform: translateY(-1px); }
        .abtn:active { transform: translateY(0); }

        .abtn-clear {
            background: #fff;
            color: #42617e;
            border-color: #d4e1ed;
        }
        .abtn-clear:hover { background: #eff6ff; border-color: #0b68b4; color: #0756a6; }
        .abtn-save {
            background: #0866b2;
            color: #fff;
            border-color: #0866b2;
            box-shadow: 0 3px 10px rgba(8,102,178,.22);
        }
        .abtn-save:hover { background: #064f91; border-color: #064f91; box-shadow: 0 5px 16px rgba(8,102,178,.3); }
        .abtn-save:disabled { background: var(--slate-300); border-color: var(--slate-300); color: var(--slate-500); box-shadow: none; cursor: not-allowed; transform: none; }

        /* ── Toast ── */
        .toast-stack {
            position: fixed; top: 1.1rem; right: 1.1rem;
            z-index: 9999;
            display: flex; flex-direction: column; gap: .45rem;
        }
        .toast-item {
            display: flex; align-items: center; gap: .55rem;
            background: #fff;
            border: 1px solid #dce7f1;
            border-radius: var(--r-lg);
            box-shadow: var(--shadow-lg);
            padding: .6rem .9rem;
            min-width: 250px; max-width: 340px;
            font-size: .78rem; font-weight: 500;
            opacity: 0; transform: translateX(20px);
            transition: opacity 0.25s, transform 0.25s;
        }
        .toast-item.visible { opacity: 1; transform: translateX(0); }
        .toast-item.toast-success { border-left: 3px solid var(--emerald); }
        .toast-item.toast-error   { border-left: 3px solid var(--rose); }
        .toast-item .t-icon { font-size: .95rem; flex-shrink: 0; }
        .toast-item.toast-success .t-icon { color: var(--emerald); }
        .toast-item.toast-error   .t-icon { color: var(--rose); }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .main-wrap { padding: .8rem; }
            .pg-title { font-size: 1.2rem; }
            .pg-title-icon { width: 34px; height: 34px; font-size: .9rem; }
            .header-actions { width: 100%; }
            .header-actions .field { width: 100%; min-width: 100%; margin-right: 0; }
            .hbtn { flex: 1; justify-content: center; }
        }
    </style>
</head>
<body>
    @include('sidenav')

        <main class="main-content">
            <div class="main-wrap">

                {{-- ── Page Header ── --}}
                <div class="pg-header">
                    <div class="pg-title">
                        <div class="pg-title-icon"><i class="bi bi-plus-circle-fill"></i></div>
                        {{ __('messages.make_receiving_page_header') }}
                    </div>
                    <div class="header-actions">
                        {{-- Select Shop --}}
                        <div class="field">
                            <form action="changeShop" method="get">
                                <select name="shop_id" class="field-input" onchange="this.form.submit()">
                                    <option value="" disabled>Select shop</option>
                                    @foreach($allShops as $shop)
                                        <option value="{{ $shop['id'] }}" {{ $shop['id'] == getCurrentShopId() ? 'selected' : '' }}>
                                            {{ $shop['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>

                        {{-- Allocation (moved beside Select Shop) — linked to #orderForm --}}
                        <div class="field">
                            <select name="served" id="served" class="field-input" form="orderForm" required>
                                <option value="" disabled selected>Select staff</option>
                                @foreach (DB::table('users')->get() as $user)
                                    @if($user->levelStatus != 'Admin')
                                        <option value="{{ $user->name }}">{{ $user->name }} ({{ $user->levelStatus }})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <a href="{{ url('view-receivings') }}" class="hbtn hbtn-outline">
                            <i class="bi bi-list-check"></i> {{ __('messages.view_receivings') }}
                        </a>
                        <a href="{{ url('make-return') }}" class="hbtn hbtn-outline-rose">
                            <i class="bi bi-arrow-return-left"></i> {{ __('messages.make_return') }}
                        </a>
                    </div>
                </div>

                {{-- ── Alerts ── --}}
                @if(session('success'))
                <div class="alert-custom alert-success-custom">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="alert-custom alert-danger-custom">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                </div>
                @endif

                {{-- ── Layout ── --}}
                <div class="layout">

                    {{-- ── LEFT: Product search + cart ── --}}
                    <div class="panel">
                        <div class="panel-head">
                            <i class="bi bi-grid"></i> Select Products
                        </div>

                        {{-- Search --}}
                        <div class="search-wrap" id="searchWrap">
                            <div class="search-box">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" class="search-input" id="productSearch"
                                    placeholder="Search products by name…" autocomplete="off">
                                <button class="search-clear" id="clearSearch" title="Clear">
                                    <i class="bi bi-x-circle-fill"></i>
                                </button>
                            </div>
                            <div class="search-hint">Click any product to add it to cart</div>

                            {{-- Dropdown --}}
                            <div class="product-dropdown" id="productDropdown">
                                <div class="dd-header">
                                    <span class="dd-header-label">Results</span>
                                    <span class="dd-count" id="ddCount">0</span>
                                </div>
                                <div class="dd-list" id="ddList"></div>
                            </div>
                        </div>

                        {{-- Cart --}}
                        <div class="cart-wrap">
                            <div class="cart-head">
                                <span class="cart-head-label"><i class="bi bi-cart3"></i> Cart</span>
                                <span class="cart-badge" id="cartBadge">0</span>
                            </div>
                            <div class="cart-scroll" id="cartScroll">
                                <div id="cartContents">
                                    <div class="empty-state">
                                        <i class="bi bi-cart-x"></i>
                                        <div class="empty-state-title">Cart is empty</div>
                                        <p>Search for products and click to add them here</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── RIGHT: Order details + summary ── --}}
                    <div class="panel">
                        <div class="panel-head">
                            <i class="bi bi-receipt"></i> {{ __('messages.receiving_details') }}
                        </div>

                        {{-- Form fields --}}
                        <div class="rp-section" style="flex:1; overflow-y:auto;">
                            <form id="orderForm" action="{{ route('process-receiving') }}" method="POST">
                                @csrf

                                <div class="rp-section-title" style="margin-bottom:.75rem;">
                                    <i class="bi bi-person-lines-fill"></i> Order Info
                                </div>

                                {{-- Supplier only (Allocation moved to header) --}}
                                <div class="field" style="margin-bottom:.55rem;">
                                    <label class="field-label" for="supplier">
                                        <i class="bi bi-shop"></i> Supplier
                                    </label>
                                    <select name="supplier" id="supplier" class="field-input" required>
                                        <option value="" disabled selected>Select supplier</option>
                                        @foreach (DB::table('vendors')->get() as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field" style="margin-bottom:.55rem;">
                                    <label class="field-label" for="transactionType">
                                        <i class="bi bi-credit-card"></i> Payment Type
                                    </label>
                                    <select name="transactionType" id="transactionType" class="field-input"
                                        onchange="applyTypeToAll(this.value)">
                                        <option value="Credit">Credit</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                </div>

                                @if(canUser('set_restock_date'))
                                <div class="field">
                                    <label class="field-label" for="receivingDate">
                                        <i class="bi bi-calendar3"></i> {{ __('messages.receiving_date') }}
                                    </label>
                                    <input type="date" name="receivingDate" id="receivingDate"
                                        class="field-input" max="{{ date('Y-m-d') }}">
                                    <div class="field-hint">Leave blank to use today's date</div>
                                </div>
                                @endif

                            </form>
                        </div>

                        {{-- Summary --}}
                        <div class="rp-section">
                            <div class="rp-section-title">
                                <i class="bi bi-calculator"></i> Summary
                            </div>
                            <div class="summary-rows">
                                <div class="summary-row">
                                    <span class="sum-label">Products</span>
                                    <span class="sum-value" id="sumProducts">0</span>
                                </div>
                                <div class="summary-row">
                                    <span class="sum-label">Total Units</span>
                                    <span class="sum-value" id="sumUnits">0</span>
                                </div>
                                <div class="summary-row">
                                    <span class="sum-label">Subtotal</span>
                                    <span class="sum-value" id="sumSubtotal">Tsh 0.00</span>
                                </div>
                            </div>
                            <div class="total-card">
                                <div>
                                    <div class="total-card-label">Total Cost</div>
                                </div>
                                <div class="total-card-amount">
                                    <span class="total-card-currency">Tsh</span><span id="totalAmt">0.00</span>
                                </div>
                            </div>
                            <div class="action-btns">
                                <button type="button" class="abtn abtn-clear" id="clearCartBtn">
                                    <i class="bi bi-x-circle"></i> Clear
                                </button>
                                <button type="button" class="abtn abtn-save" id="submitBtn">
                                    <i class="bi bi-check-circle-fill"></i> {{ __('messages.save_receiving') }}
                                </button>
                            </div>
                        </div>
                    </div>

                </div>{{-- /layout --}}
            </div>
        </main>

    {{-- ── Toast container ── --}}
    <div class="toast-stack" id="toastStack"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
    // ════════════════════════════════════════════
    // Shop change handler
    // ════════════════════════════════════════════
    function changeShop(shopId) {
        if (!shopId) return;
        const url = new URL('{{ route("make-receiving") }}');
        url.searchParams.set('shop_id', shopId);
        window.location.href = url.toString();
    }

    // ════════════════════════════════════════════
    // Data
    // ════════════════════════════════════════════
    const allProducts = [
        @if(DB::table('products')->whereNotNull('name01')->where('name01','!=','')->where('account',session('selected_shop_id'))->count() > 0)
            @foreach(DB::table('products')->whereNotNull('name01')->where('name01','!=','')->where('account',session('selected_shop_id'))->get() as $product)
            {
                id:"{{ $product->product_id }}",
                name:"{{ addslashes($product->name01) }}",
                cost:{{ (float)($product->bPrice ?? 0) }},
                wholesale:{{ (float)($product->wholesale ?? 0) }},
                retail:{{ (float)($product->sPrice ?? 0) }},
                stock:{{ (int)($product->quantity ?? 0) }}
            },
            @endforeach
        @else
            { id:'demo1', name:'Sugar 1kg',  cost:2500,  wholesale:2800,  retail:3000,  stock:100 },
            { id:'demo2', name:'Rice 5kg',   cost:15000, wholesale:17000, retail:18000, stock:50  },
        @endif
    ];

    // ════════════════════════════════════════════
    // State
    // ════════════════════════════════════════════
    const CART_KEY = 'mkReceivingCart';
    const SET_KEY  = 'mkReceivingSet';

    let cart = [];
    let inCart = new Set();
    let lastTerm = '';

    // ════════════════════════════════════════════
    // Storage
    // ════════════════════════════════════════════
    function saveCart() {
        try {
            localStorage.setItem(CART_KEY, JSON.stringify(cart));
            localStorage.setItem(SET_KEY,  JSON.stringify([...inCart]));
        } catch(e) {}
    }
    function loadCart() {
        try {
            const c = localStorage.getItem(CART_KEY);
            const s = localStorage.getItem(SET_KEY);
            if (c) cart   = JSON.parse(c);
            if (s) inCart = new Set(JSON.parse(s));
        } catch(e) { cart = []; inCart = new Set(); }
    }
    function clearStorage() {
        localStorage.removeItem(CART_KEY);
        localStorage.removeItem(SET_KEY);
    }

    // ════════════════════════════════════════════
    // Init
    // ════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', () => {
        loadCart();
        renderCart();
        updateSummary();

        // Search
        const inp = document.getElementById('productSearch');
        inp.addEventListener('input', onSearch);
        inp.addEventListener('focus', () => { if (inp.value.trim()) onSearch({ target: inp }); });

        document.getElementById('clearSearch').addEventListener('click', clearSearch);
        document.getElementById('clearCartBtn').addEventListener('click', clearCart);
        document.getElementById('submitBtn').addEventListener('click', submitOrder);

        // Close dropdown on outside click
        document.addEventListener('click', e => {
            const dd = document.getElementById('productDropdown');
            const wrap = document.getElementById('searchWrap');
            if (dd && !wrap.contains(e.target)) dd.classList.remove('open');
        });
    });

    // ════════════════════════════════════════════
    // Search
    // ════════════════════════════════════════════
    function onSearch(e) {
        const term = e.target.value.trim().toLowerCase();
        lastTerm = term;
        const dd  = document.getElementById('productDropdown');
        const clearBtn = document.getElementById('clearSearch');
        clearBtn.style.display = term ? 'block' : 'none';

        if (!term) { dd.classList.remove('open'); return; }

        const results = allProducts.filter(p => p && p.name && p.name.toLowerCase().includes(term) && !inCart.has(p.id));
        renderDropdown(results);
        dd.classList.add('open');
    }

    function clearSearch() {
        document.getElementById('productSearch').value = '';
        document.getElementById('clearSearch').style.display = 'none';
        document.getElementById('productDropdown').classList.remove('open');
        lastTerm = '';
    }

    function renderDropdown(products) {
        const list  = document.getElementById('ddList');
        const count = document.getElementById('ddCount');
        count.textContent = products.length;

        if (!products.length) {
            list.innerHTML = `<div class="empty-state" style="padding:1.4rem;">
                <i class="bi bi-search"></i>
                <div class="empty-state-title">No results</div>
                <p>Try a different search term</p>
            </div>`;
            return;
        }

        list.innerHTML = products.map(p => `
            <div class="prod-item" onclick="addToCart('${p.id}')">
                <div class="prod-info">
                    <div class="prod-name">${p.name}</div>
                    <div class="prod-meta">
                        <span><i class="bi bi-tag-fill"></i> ${fmtMono(p.cost)}</span>
                        <span><i class="bi bi-box-seam"></i> ${p.stock}</span>
                    </div>
                </div>
                <div class="prod-add-btn"><i class="bi bi-plus-lg"></i></div>
            </div>
        `).join('');
    }

    // ════════════════════════════════════════════
    // Cart operations
    // ════════════════════════════════════════════
    function addToCart(id) {
        const p = allProducts.find(x => x.id === id);
        if (!p) return;

        if (inCart.has(id)) {
            const item = cart.find(x => x.productId === id);
            if (item) { item.quantity++; toast(`${p.name} — qty updated`, 'success'); }
        } else {
            cart.push({
                uid: Date.now() + id,
                productId: p.id,
                name: p.name,
                cost: p.cost,
                wholesale: p.wholesale,
                retail: p.retail,
                quantity: 1,
                type: document.getElementById('transactionType')?.value || 'Cash',
            });
            inCart.add(id);
            toast(`${p.name} added`, 'success');
        }

        saveCart();
        clearSearch();
        renderCart();
        updateSummary();
    }

    function removeFromCart(uid) {
        const item = cart.find(x => x.uid === uid);
        if (!item) return;
        cart = cart.filter(x => x.uid !== uid);
        inCart.delete(item.productId);
        saveCart();
        renderCart();
        updateSummary();
        if (lastTerm) onSearch({ target: { value: lastTerm } });
        toast(`${item.name} removed`, 'success');
    }

    function clearCart() {
        if (!cart.length) return;
        if (!confirm('Clear all products from cart?')) return;
        cart = []; inCart.clear();
        clearStorage();
        renderCart(); updateSummary();
        if (lastTerm) onSearch({ target: { value: lastTerm } });
        toast('Cart cleared', 'success');
    }

    function applyTypeToAll(val) { cart.forEach(i => i.type = val); saveCart(); }

    // ════════════════════════════════════════════
    // Render cart
    // ════════════════════════════════════════════
    function renderCart() {
        const el = document.getElementById('cartContents');
        document.getElementById('cartBadge').textContent = cart.length;

        if (!cart.length) {
            el.innerHTML = `<div class="empty-state">
                <i class="bi bi-cart-x"></i>
                <div class="empty-state-title">Cart is empty</div>
                <p>Search and click products to add them here</p>
            </div>`;
            return;
        }

        el.innerHTML = `<table class="cart-tbl">
            <thead>
                <tr>
                    <th style="width:28%">Product</th>
                    <th style="width:11%;text-align:center;">Qty</th>
                    <th style="width:16%;text-align:center;">Cost</th>
                    <th style="width:16%;text-align:center;">Wholesale</th>
                    <th style="width:16%;text-align:center;">Retail</th>
                    <th style="width:13%;text-align:right;">Total</th>
                    <th style="width:5%;text-align:center;"></th>
                </tr>
            </thead>
            <tbody>
                ${cart.slice().reverse().map(item => `
                <tr id="row-${item.uid}">
                    <td><div class="cart-prod-name" title="${item.name}">${item.name}</div></td>
                    <td><input class="tbl-input" type="number" min="1" value="${item.quantity}"
                        oninput="setField('${item.uid}','quantity',this.value);updateSummary();saveCart()"></td>
                    <td><input class="tbl-input" type="number" step="0.01" value="${item.cost}"
                        oninput="setField('${item.uid}','cost',this.value);updateRowTotal('${item.uid}');updateSummary();saveCart()"></td>
                    <td><input class="tbl-input" type="number" step="0.01" value="${item.wholesale}"
                        oninput="setField('${item.uid}','wholesale',this.value);saveCart()"></td>
                    <td><input class="tbl-input" type="number" step="0.01" value="${item.retail}"
                        oninput="setField('${item.uid}','retail',this.value);saveCart()"></td>
                    <td style="text-align:right;font-family:'DM Mono',monospace;font-size:.72rem;color:#084b87;font-weight:600;" id="rowTotal-${item.uid}">
                        ${fmtMono(item.cost * item.quantity)}
                    </td>
                    <td style="text-align:center;">
                        <button class="del-btn" onclick="removeFromCart('${item.uid}')" title="Remove">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </td>
                </tr>`).join('')}
            </tbody>
        </table>`;
    }

    function setField(uid, field, val) {
        const item = cart.find(x => x.uid === uid);
        if (!item) return;
        item[field] = field === 'quantity' ? (parseInt(val) || 1) : (parseFloat(val) || 0);
    }

    function updateRowTotal(uid) {
        const item = cart.find(x => x.uid === uid);
        const el   = document.getElementById('rowTotal-' + uid);
        if (item && el) el.textContent = fmtMono(item.cost * item.quantity);
    }

    // ════════════════════════════════════════════
    // Summary
    // ════════════════════════════════════════════
    function updateSummary() {
        let subtotal = 0, units = 0;
        cart.forEach(i => { subtotal += (i.cost || 0) * (i.quantity || 1); units += i.quantity; });

        document.getElementById('sumProducts').textContent = cart.length;
        document.getElementById('sumUnits').textContent    = units;
        document.getElementById('sumSubtotal').textContent = 'Tsh ' + subtotal.toLocaleString(undefined, { minimumFractionDigits: 2 });
        document.getElementById('totalAmt').textContent    = subtotal.toLocaleString(undefined, { minimumFractionDigits: 2 });
    }

    function fmtMono(n) { return n.toLocaleString(undefined, { minimumFractionDigits: 2 }); }

    // ════════════════════════════════════════════
    // Submit
    // ════════════════════════════════════════════
    function submitOrder() {
        const supplier = document.getElementById('supplier').value;
        const served   = document.getElementById('served').value;
        const dateEl   = document.getElementById('receivingDate');

        if (!cart.length)         { toast('Add at least one product to the cart.', 'error'); return; }
        if (!supplier || !served) { toast('Please select supplier and allocation.', 'error'); return; }

        const fd = new FormData();
        fd.append('_token', '{{ csrf_token() }}');
        fd.append('supplier', supplier);
        fd.append('served', served);
        if (dateEl?.value) fd.append('receivingDate', dateEl.value);

        cart.forEach(item => {
            fd.append('product_id[]',      item.productId);
            fd.append('quantity[]',         item.quantity);
            fd.append('bPrice[]',           item.cost);
            fd.append('wholesale[]',        item.wholesale);
            fd.append('sPrice[]',           item.retail);
            fd.append('transactionType[]',  item.type);
            fd.append('expiry[]',           '');
        });

        const btn = document.getElementById('submitBtn');
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving…';
        btn.disabled = true;

        fetch('{{ route("process-receiving") }}', {
            method: 'POST', body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.text())
        .then(() => {
            cart = []; inCart.clear(); clearStorage();
            renderCart(); updateSummary();
            document.getElementById('orderForm').reset();
            clearSearch();
            toast(@json(__('messages.receiving_saved_successfully')), 'success');
            setTimeout(() => { window.location.href = '{{ url("view-receivings") }}'; }, 1600);
        })
        .catch(() => toast('Something went wrong. Please try again.', 'error'))
        .finally(() => {
            btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> {{ __('messages.save_receiving') }}';
            btn.disabled = false;
        });
    }

    // ════════════════════════════════════════════
    // Toast
    // ════════════════════════════════════════════
    function toast(msg, type = 'success') {
        const stack = document.getElementById('toastStack');
        const el = document.createElement('div');
        el.className = `toast-item toast-${type}`;
        el.innerHTML = `<i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-x-circle-fill'} t-icon"></i><span>${msg}</span>`;
        stack.appendChild(el);
        requestAnimationFrame(() => { requestAnimationFrame(() => el.classList.add('visible')); });
        setTimeout(() => {
            el.classList.remove('visible');
            setTimeout(() => el.remove(), 280);
        }, 3000);
    }
    </script>
</body>
</html>