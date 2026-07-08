<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config("app.name") }} — @lang('messages.make_return_page_title')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
     
    <style>
        /* Your existing CSS styles here (same as before) */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:         #0B1E3D;
            --navy-mid:     #112952;
            --navy-light:   #1A3A6B;
            --amber:        #F59E0B;
            --amber-pale:   #FEF3C7;
            --emerald:      #059669;
            --emerald-pale: #D1FAE5;
            --rose:         #E11D48;
            --rose-pale:    #FFE4E6;
            --violet:       #7C3AED;
            --violet-pale:  #EDE9FE;
            --slate-50:     #F8FAFC;
            --slate-100:    #F1F5F9;
            --slate-200:    #E2E8F0;
            --slate-300:    #CBD5E1;
            --slate-400:    #94A3B8;
            --slate-500:    #64748B;
            --slate-600:    #475569;
            --slate-700:    #334155;
            --slate-800:    #1E293B;
            --white:        #FFFFFF;
            --font: 'Sora', system-ui, sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --r: 8px; --r-lg: 12px; --r-xl: 16px;
        }

        
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }
        .main-wrap { max-width: 1800px; margin: 0 auto; padding: 1.25rem 1.5rem; }

        .wrap { padding: 1.25rem 1.5rem 2rem; }

        .pg-header {
            background: var(--navy); 
            border-radius: var(--r-xl);
            padding: 1rem 1.5rem; 
            margin-bottom: 1rem;
            display: flex; 
            align-items: center; 
            justify-content: space-between;
            gap: 1rem; 
            flex-wrap: wrap; 
            position: relative; 
            overflow: hidden;
        }
        .pg-header::before { 
            content:''; 
            position:absolute; 
            top:-50px; 
            right:-30px; 
            width:160px; 
            height:160px; 
            border-radius:50%; 
            background:var(--navy-light); 
            opacity:.45; 
            pointer-events:none; 
        }
        .pg-header::after {  
            content:''; 
            position:absolute; 
            bottom:-50px; 
            right:80px; 
            width:110px; 
            height:110px; 
            border-radius:50%; 
            background:var(--rose); 
            opacity:.07; 
            pointer-events:none; 
        }
        .pg-left { 
            display:flex; 
            align-items:center; 
            gap:10px; 
            position:relative; 
            z-index:1; 
        }
        .header-icon { 
            width:38px; 
            height:38px; 
            border-radius:var(--r); 
            background:var(--rose); 
            display:flex; 
            align-items:center; 
            justify-content:center; 
            font-size:17px; 
            color:var(--white); 
            flex-shrink:0; 
        }
        .pg-title-text h1 { 
            font-size:15px; 
            font-weight:700; 
            color:var(--white); 
            letter-spacing:-.2px;
            margin: 0;
        }
        .pg-title-text p {  
            font-size:11.5px; 
            color:rgba(255,255,255,.4); 
            margin-top:1px;
            margin-bottom: 0;
        }
        .pg-right { 
            display:flex; 
            gap:7px; 
            position:relative; 
            z-index:1; 
            flex-wrap:wrap; 
        }

        .btn-ghost-hdr { 
            display:inline-flex; 
            align-items:center; 
            gap:5px; 
            padding:7px 13px; 
            border-radius:var(--r); 
            border:1px solid rgba(255,255,255,.18); 
            background:rgba(255,255,255,.07); 
            color:rgba(255,255,255,.75); 
            font-family:var(--font); 
            font-size:12.5px; 
            font-weight:500; 
            cursor:pointer; 
            transition:all .15s; 
            text-decoration:none; 
        }
        .btn-ghost-hdr:hover { 
            background:rgba(255,255,255,.15); 
            color:var(--white); 
        }

        .warn-banner {
            background: var(--rose-pale); 
            border: 1.5px solid rgba(225,29,72,.25);
            border-left: 4px solid var(--rose); 
            border-radius: var(--r-lg);
            padding: .75rem 1rem; 
            margin-bottom: 1rem;
            display: flex; 
            align-items: flex-start; 
            gap: 10px;
        }
        .warn-banner i { 
            color: var(--rose); 
            font-size: 18px; 
            flex-shrink: 0; 
            margin-top: 1px; 
        }
        .warn-banner strong { 
            color: var(--rose); 
            display: block; 
            font-size: 13px; 
        }
        .warn-banner small { 
            font-size: 12px; 
            color: var(--slate-600); 
        }

        .alert { 
            display:flex; 
            align-items:center; 
            gap:8px; 
            padding:.75rem 1rem; 
            border-radius:var(--r); 
            font-size:13px; 
            font-weight:500; 
            margin-bottom:1rem; 
        }
        .alert-success { 
            background:var(--emerald-pale); 
            color:var(--emerald); 
            border-left:3px solid var(--emerald); 
        }
        .alert-danger {  
            background:var(--rose-pale);    
            color:var(--rose);    
            border-left:3px solid var(--rose); 
        }

        .main-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1rem;
            height: calc(100vh - 200px);
            min-height: 500px;
        }
        @media(max-width:1000px) { 
            .main-grid { 
                grid-template-columns: 1fr; 
                height: auto; 
            } 
        }

        .panel {
            background: var(--white); 
            border: 1.5px solid var(--slate-200);
            border-radius: var(--r-xl); 
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(11,30,61,.05);
            display: flex; 
            flex-direction: column;
        }

        .panel-head {
            display: flex; 
            align-items: center; 
            gap: 8px;
            padding: .8rem 1.2rem; 
            border-bottom: 1.5px solid var(--slate-200);
            background: var(--slate-50); 
            flex-shrink: 0;
        }
        .panel-head-icon { 
            width: 28px; 
            height: 28px; 
            border-radius: var(--r); 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 13px; 
            flex-shrink: 0; 
        }
        .phi-rose    { background: var(--rose-pale);    color: var(--rose); }
        .phi-navy    { background: rgba(11,30,61,.08);  color: var(--navy-light); }
        .phi-amber   { background: var(--amber-pale);   color: #92400e; }
        .panel-title { 
            font-size: 13px; 
            font-weight: 700; 
            color: var(--navy); 
            flex: 1; 
        }
        .count-pill {  
            font-size: 11px; 
            font-weight: 700; 
            font-family: var(--mono); 
            padding: 2px 8px; 
            border-radius: 20px; 
            background: var(--rose); 
            color: var(--white); 
        }

        .panel-body { 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
            overflow: hidden; 
            padding: .9rem; 
            gap: .9rem; 
        }

        .search-wrap { 
            position: relative; 
            flex: 1;
        }
        .search-wrap i.si { 
            position: absolute; 
            left: 11px; 
            top: 50%; 
            transform: translateY(-50%); 
            color: var(--slate-400); 
            font-size: 13px; 
            pointer-events: none; 
        }
        .search-input {
            width: 100%; 
            padding: 9px 12px 9px 32px;
            border: 1.5px solid var(--slate-200); 
            border-radius: var(--r);
            font-family: var(--font); 
            font-size: 13.5px; 
            color: var(--slate-800);
            background: var(--slate-50); 
            outline: none;
            transition: border-color .18s, box-shadow .18s;
        }
        .search-input:focus { 
            border-color: var(--navy-light); 
            box-shadow: 0 0 0 3px rgba(26,58,107,.1); 
            background: var(--white); 
        }

        .results-drop {
            position: absolute; 
            top: calc(100% + 4px); 
            left: 0; 
            right: 0; 
            z-index: 200;
            background: var(--white); 
            border: 1.5px solid var(--navy-light);
            border-radius: var(--r-lg); 
            max-height: 350px; 
            overflow-y: auto;
            box-shadow: 0 8px 24px rgba(11,30,61,.14); 
            display: none;
        }
        .results-drop.open { display: block; }
        .results-head { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            padding: 8px 12px; 
            border-bottom: 1px solid var(--slate-100); 
            background: var(--slate-50); 
            position: sticky; 
            top: 0; 
            z-index: 1; 
        }
        .results-head-label { 
            font-size: 10.5px; 
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: .07em; 
            color: var(--slate-400); 
        }

        .prod-item { 
            padding: 10px 13px; 
            cursor: pointer; 
            border-bottom: 1px solid var(--slate-100); 
            transition: all .12s; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            gap: .5rem;
        }
        .prod-item:last-child { border-bottom: none; }
        .prod-item:hover { 
            background: var(--slate-50); 
            border-left: 3px solid var(--rose); 
            padding-left: 10px; 
        }
        .prod-item-info { flex: 1; }
        .prod-item-name {  
            font-size: 13px; 
            font-weight: 600; 
            color: var(--navy); 
            margin-bottom: 4px; 
        }
        .prod-item-meta  { 
            font-size: 11px; 
            color: var(--slate-500); 
            margin-top: 2px; 
            display: flex; 
            gap: 12px; 
            flex-wrap: wrap;
        }
        .prod-item-stock { 
            font-size: 11px; 
            font-weight: 700; 
            color: var(--emerald); 
            font-family: var(--mono); 
        }
        .btn-add-product {
            background: var(--navy);
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 600;
            transition: all .15s;
        }
        .btn-add-product:hover {
            background: var(--navy-light);
            transform: translateY(-1px);
        }
        .no-results { 
            padding: 1.5rem; 
            text-align: center; 
            font-size: 13px; 
            color: var(--slate-400);
        }

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
            font-size: 12px; 
        }
        .cart-tbl thead th {
            background: var(--navy); 
            color: rgba(255,255,255,.65);
            font-size: 10px; 
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: .07em;
            padding: 8px 6px; 
            border: none; 
            white-space: nowrap; 
            text-align: left;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .cart-tbl thead th:last-child { text-align: center; }
        .cart-tbl tbody tr { border-bottom: 1px solid var(--slate-100); }
        .cart-tbl tbody tr:last-child { border-bottom: none; }
        .cart-tbl tbody tr:hover td { background: var(--slate-50); }
        .cart-tbl td { padding: 6px 6px; vertical-align: middle; }

        .cart-prod-name { 
            font-weight: 600; 
            color: var(--navy); 
            font-size: 11px; 
            max-width: 150px;
            word-wrap: break-word;
        }
        .num-input {
            width: 60px; 
            padding: 4px 5px; 
            text-align: center;
            border: 1.5px solid var(--slate-200); 
            border-radius: var(--r);
            font-family: var(--mono); 
            font-size: 11px; 
            color: var(--slate-800);
            outline: none; 
            transition: border-color .15s;
        }
        .num-input:focus { border-color: var(--navy-light); }
        .del-btn {
            width: 24px; 
            height: 24px; 
            border-radius: var(--r); 
            background: var(--rose-pale);
            color: var(--rose); 
            border: none; 
            cursor: pointer; 
            display: flex;
            align-items: center; 
            justify-content: center; 
            font-size: 10px; 
            margin: auto;
            transition: all .15s;
        }
        .del-btn:hover { background: var(--rose); color: var(--white); }

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
        .empty-cart i { 
            font-size: 2rem; 
            margin-bottom: .5rem; 
            opacity: .3; 
            display: block; 
        }
        .empty-cart p { 
            font-size: 13px; 
            margin: 0;
        }

        .right-body { 
            flex: 1; 
            overflow-y: auto; 
            padding: .9rem; 
            display: flex; 
            flex-direction: column; 
            gap: .75rem; 
        }

        .field { 
            display: flex; 
            flex-direction: column; 
            gap: 4px; 
        }
        .field-label { 
            font-size: 10.5px; 
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: .07em; 
            color: var(--slate-400); 
            display: flex; 
            align-items: center; 
            gap: 5px; 
        }
        .field-input, .field-select, .field-textarea {
            width: 100%; 
            font-family: var(--font); 
            font-size: 13px;
            padding: 8px 11px; 
            border: 1.5px solid var(--slate-200);
            border-radius: var(--r); 
            background: var(--white);
            color: var(--slate-800); 
            outline: none;
            transition: border-color .18s, box-shadow .18s;
        }
        .field-input:focus, .field-select:focus, .field-textarea:focus { 
            border-color: var(--navy-light); 
            box-shadow: 0 0 0 3px rgba(26,58,107,.1); 
        }
        .field-select { 
            appearance: none; 
            cursor: pointer; 
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); 
            background-repeat:no-repeat; 
            background-position:right 10px center; 
            padding-right:2rem; 
        }
        .field-textarea { 
            resize: vertical; 
            min-height: 68px; 
        }
        .field-hint { 
            font-size: 11px; 
            color: var(--slate-400); 
            display: flex; 
            align-items: center; 
            gap: 4px; 
        }

        .summary-block { 
            margin-top: auto; 
            flex-shrink: 0; 
            padding: .9rem; 
            border-top: 1.5px solid var(--slate-200); 
            background: var(--slate-50); 
        }
        .summ-row { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            font-size: 12.5px; 
            padding: 4px 0; 
        }
        .summ-row .sl { color: var(--slate-500); }
        .summ-row .sv { 
            font-family: var(--mono); 
            font-weight: 600; 
            color: var(--slate-800); 
        }

        .total-box {
            background: var(--navy); 
            border-radius: var(--r-lg);
            padding: .9rem 1rem; 
            margin-top: .65rem;
            display: flex; 
            align-items: center; 
            justify-content: space-between;
        }
        .total-box-label { 
            font-size: 11px; 
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: .08em; 
            color: rgba(255,255,255,.5); 
        }
        .total-box-val {   
            font-family: var(--mono); 
            font-size: 19px; 
            font-weight: 700; 
            color: var(--rose); 
        }

        .action-row { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 8px; 
            margin-top: .65rem; 
        }
        .btn-clear-cart {
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 5px;
            padding: 10px; 
            border-radius: var(--r); 
            border: 1.5px solid var(--slate-200);
            background: transparent; 
            font-family: var(--font); 
            font-size: 13px; 
            font-weight: 600;
            color: var(--slate-600); 
            cursor: pointer; 
            transition: all .15s;
        }
        .btn-clear-cart:hover { background: var(--slate-100); }
        .btn-submit-return {
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 5px;
            padding: 10px; 
            border-radius: var(--r); 
            border: none;
            background: var(--rose); 
            color: var(--white);
            font-family: var(--font); 
            font-size: 13px; 
            font-weight: 700;
            cursor: pointer; 
            box-shadow: 0 3px 12px rgba(225,29,72,.3);
            transition: all .18s;
        }
        .btn-submit-return:hover { background: #be123c; transform: translateY(-1px); }
        .btn-submit-return:disabled { background: var(--slate-300); cursor: not-allowed; transform: none; box-shadow: none; }

        .toast-container { 
            position: fixed; 
            top: 20px; 
            right: 20px; 
            z-index: 9999; 
            display: flex; 
            flex-direction: column; 
            gap: 8px; 
        }
        .toast {
            background: var(--white); 
            border-radius: var(--r-lg);
            border: 1.5px solid var(--slate-200);
            box-shadow: 0 8px 24px rgba(11,30,61,.14);
            padding: .75rem 1.1rem; 
            display: flex; 
            align-items: center; 
            gap: 9px;
            min-width: 280px; 
            font-size: 13px; 
            font-weight: 500;
            opacity: 0; 
            transform: translateX(100%); 
            transition: all .25s ease;
        }
        .toast.show { opacity: 1; transform: translateX(0); }
        .toast-success { border-left: 3px solid var(--emerald); }
        .toast-success i { color: var(--emerald); }
        .toast-error   { border-left: 3px solid var(--rose); }
        .toast-error i { color: var(--rose); }

        .shop-row {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 1rem;
            align-items: start;
        }
        .shop-selector {
            min-width: 200px;
        }

        @media(max-width:768px) { 
            .wrap { padding: 1rem; }
            .shop-row { grid-template-columns: 1fr; }
        }
        
        .col-md-9 {
            flex: 1;
            padding: 0;
        }
        
        .row {
            display: flex;
            flex-wrap: nowrap;
        }
    </style>
</head>
<body>
    @include('sidenav')

        <main class="main-content">
            <div class="main-wrap">

            <div class="pg-header">
                <div class="pg-left">
                    <div class="header-icon"><i class="bi bi-arrow-return-left"></i></div>
                    <div class="pg-title-text">
                        <h1>{{ __('messages.make_return_page_header') }}</h1>
                        <p>{{ __('messages.select_products_return') }}</p>
                    </div>
                </div>
                <div class="pg-right">
                    <a href="{{ url('make-receiving') }}" class="btn-ghost-hdr">
                        <i class="bi bi-plus-circle"></i> {{ __('messages.make_receiving') }}
                    </a>
                    <a href="{{ url('view-returns') }}" class="btn-ghost-hdr">
                        <i class="bi bi-list-check"></i> {{ __('messages.view_returns') }}
                    </a>
                </div>
            </div>

            <div class="warn-banner">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <strong>{{ __('messages.return_approval_note') }}</strong>
                    <small>{{ __('messages.return_stock_note') }}</small>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                </div>
            @endif

            <div class="main-grid">

                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-head-icon phi-rose"><i class="bi bi-search"></i></div>
                        <span class="panel-title">Select Products to Return</span>
                        <span class="count-pill" id="cartCount">0</span>
                    </div>
                    <div class="panel-body">

                        <div class="shop-row">
                            <div class="field shop-selector">
                                <form action="{{ route('changeShop') }}" method="get" id="shopForm">
                                    <select name="shop_id" class="field-input" onchange="this.form.submit()">
                                        <option value="" disabled>Select shop</option>
                                        @foreach($allShops as $shop)
                                            <option value="{{ $shop['id'] }}" {{ $shop['id'] == session('selected_shop_id') ? 'selected' : '' }}>
                                                {{ $shop['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                            
                            <div class="search-wrap">
                                <i class="bi bi-search si"></i>
                                <input type="text" class="search-input" id="productSearch" placeholder="Search products by name…" autocomplete="off">
                                <div class="results-drop" id="productListSection">
                                    <div class="results-head">
                                        <span class="results-head-label">Available products</span>
                                        <span class="count-pill" id="productCount" style="background:var(--navy);">0</span>
                                    </div>
                                    <div id="productList"></div>
                                </div>
                            </div>
                        </div>

                        <div class="cart-wrap">
                            <div class="panel-head" style="border-radius:0; background:var(--slate-50); border:none; border-bottom:1.5px solid var(--slate-200); border-top:1.5px solid var(--slate-200); flex-shrink:0;">
                                <div class="panel-head-icon phi-navy"><i class="bi bi-cart3"></i></div>
                                <span class="panel-title">{{ __('messages.products_to_return') }}</span>
                            </div>
                            <div class="cart-scroll" id="cartItems">
                                <div class="empty-cart">
                                    <i class="bi bi-arrow-return-left"></i>
                                    <p>{{ __('messages.no_products_added_yet') }}<br><span style="font-size:11.5px;">{{ __('messages.search_and_click_product') }}</span></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-head-icon phi-amber"><i class="bi bi-clipboard-check"></i></div>
                        <span class="panel-title">{{ __('messages.return_details') }}</span>
                    </div>

                    <form id="orderForm" style="display:contents;">
                        @csrf
                        <div class="right-body">
                            <div class="field">
                                <label class="field-label"><i class="bi bi-shop"></i> Supplier</label>
                                <select id="supplier" name="supplier" class="field-select" required>
                                    <option value="" disabled selected>Select supplier…</option>
                                    @php
                                        $vendors = DB::table('vendors')->get();
                                    @endphp
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field">
                                <label class="field-label"><i class="bi bi-person"></i> Processed by</label>
                                <select id="served" name="served" class="field-select" required>
                                    <option value="" disabled selected>Select staff…</option>
                                    @php
                                        $users = DB::table('users')->orderBy('name', 'asc')->get();
                                    @endphp
                                    @foreach($users as $user)
                                        <option value="{{ $user->name }}">{{ $user->name }}</option>
                                    @endforeach
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
                                <label class="field-label"><i class="bi bi-calendar-check"></i> {{ __('messages.return_date') }}</label>
                                <input type="date" id="receivingDate" name="receivingDate" class="field-input"
                                       value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
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
                                <button id="submitOrderBtn" class="btn-submit-return" formaction="{{ route('process-return') }}">
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
@php
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
@endphp

// This uses JSON encoding which safely handles all special characters
const allProducts = @json($productsArray);

console.log('Products loaded:', allProducts.length);
if (allProducts.length === 0) {
    console.warn('No products found for shop ID: {{ $currentShopId }}');
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
                <p>{{ __('messages.no_products_added_yet') }}<br><span style="font-size:11.5px;">{{ __('messages.search_and_click_product') }}</span></p>
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
    
    fetch('{{ route("process-return") }}', {
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
            showToast(@json(__('messages.return_request_submitted')), 'success');
            shoppingCart = [];
            clearStoredCart();
            refreshCart();
            document.getElementById('orderForm').reset();
            document.getElementById('receivingDate').value = '{{ date("Y-m-d") }}';
            setTimeout(() => {
                window.location.href = '{{ url("view-returns") }}';
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
</html>