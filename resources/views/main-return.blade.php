<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} — @lang('messages.make_return_page_title')</title>
    @include('links')
    
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
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

        body { font-family: var(--font); background: #ECF0F8; color: var(--slate-800); min-height: 100vh; font-size: 14px; line-height: 1.6; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }

        .wrap { padding: 1.25rem 1.5rem 2rem; }

        /* ══ PAGE HEADER ══ */
        .pg-header {
            background: var(--navy); border-radius: var(--r-xl);
            padding: 1rem 1.5rem; margin-bottom: 1rem;
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; flex-wrap: wrap; position: relative; overflow: hidden;
        }
        .pg-header::before { content:''; position:absolute; top:-50px; right:-30px; width:160px; height:160px; border-radius:50%; background:var(--navy-light); opacity:.45; pointer-events:none; }
        .pg-header::after  { content:''; position:absolute; bottom:-50px; right:80px; width:110px; height:110px; border-radius:50%; background:var(--rose); opacity:.07; pointer-events:none; }
        .pg-left { display:flex; align-items:center; gap:10px; position:relative; z-index:1; }
        .header-icon { width:38px; height:38px; border-radius:var(--r); background:var(--rose); display:flex; align-items:center; justify-content:center; font-size:17px; color:var(--white); flex-shrink:0; }
        .pg-title-text h1 { font-size:15px; font-weight:700; color:var(--white); letter-spacing:-.2px; }
        .pg-title-text p  { font-size:11.5px; color:rgba(255,255,255,.4); margin-top:1px; }
        .pg-right { display:flex; gap:7px; position:relative; z-index:1; flex-wrap:wrap; }

        .btn-ghost-hdr { display:inline-flex; align-items:center; gap:5px; padding:7px 13px; border-radius:var(--r); border:1px solid rgba(255,255,255,.18); background:rgba(255,255,255,.07); color:rgba(255,255,255,.75); font-family:var(--font); font-size:12.5px; font-weight:500; cursor:pointer; transition:all .15s; text-decoration:none; }
        .btn-ghost-hdr:hover { background:rgba(255,255,255,.15); color:var(--white); }

        /* ══ WARNING BANNER ══ */
        .warn-banner {
            background: var(--rose-pale); border: 1.5px solid rgba(225,29,72,.25);
            border-left: 4px solid var(--rose); border-radius: var(--r-lg);
            padding: .75rem 1rem; margin-bottom: 1rem;
            display: flex; align-items: flex-start; gap: 10px;
        }
        .warn-banner i { color: var(--rose); font-size: 18px; flex-shrink: 0; margin-top: 1px; }
        .warn-banner strong { color: var(--rose); display: block; font-size: 13px; }
        .warn-banner small { font-size: 12px; color: var(--slate-600); }

        /* Alerts */
        .alert { display:flex; align-items:center; gap:8px; padding:.75rem 1rem; border-radius:var(--r); font-size:13px; font-weight:500; margin-bottom:1rem; }
        .alert-success { background:var(--emerald-pale); color:var(--emerald); border-left:3px solid var(--emerald); }
        .alert-danger  { background:var(--rose-pale);    color:var(--rose);    border-left:3px solid var(--rose); }

        /* ══ MAIN LAYOUT ══ */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1rem;
            height: calc(100vh - 200px);
            min-height: 500px;
        }
        @media(max-width:1000px) { .main-grid { grid-template-columns: 1fr; height: auto; } }

        /* ══ PANELS ══ */
        .panel {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-xl); overflow: hidden;
            box-shadow: 0 1px 4px rgba(11,30,61,.05);
            display: flex; flex-direction: column;
        }

        .panel-head {
            display: flex; align-items: center; gap: 8px;
            padding: .8rem 1.2rem; border-bottom: 1.5px solid var(--slate-200);
            background: var(--slate-50); flex-shrink: 0;
        }
        .panel-head-icon { width: 28px; height: 28px; border-radius: var(--r); display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0; }
        .phi-rose    { background: var(--rose-pale);    color: var(--rose); }
        .phi-navy    { background: rgba(11,30,61,.08);  color: var(--navy-light); }
        .phi-amber   { background: var(--amber-pale);   color: #92400e; }
        .panel-title { font-size: 13px; font-weight: 700; color: var(--navy); flex: 1; }
        .count-pill  { font-size: 11px; font-weight: 700; font-family: var(--mono); padding: 2px 8px; border-radius: 20px; background: var(--rose); color: var(--white); }
        .count-pill.navy { background: var(--navy); }

        .panel-body { flex: 1; display: flex; flex-direction: column; overflow: hidden; padding: .9rem; gap: .9rem; }

        /* ══ PRODUCT SEARCH ══ */
        .search-wrap { position: relative; flex-shrink: 0; }
        .search-wrap i.si { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--slate-400); font-size: 13px; pointer-events: none; }
        .search-input {
            width: 100%; padding: 9px 12px 9px 32px;
            border: 1.5px solid var(--slate-200); border-radius: var(--r);
            font-family: var(--font); font-size: 13.5px; color: var(--slate-800);
            background: var(--slate-50); outline: none;
            transition: border-color .18s, box-shadow .18s;
        }
        .search-input:focus { border-color: var(--navy-light); box-shadow: 0 0 0 3px rgba(26,58,107,.1); background: var(--white); }
        .search-input::placeholder { color: var(--slate-400); }

        /* Dropdown results */
        .results-drop {
            position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 200;
            background: var(--white); border: 1.5px solid var(--navy-light);
            border-radius: var(--r-lg); max-height: 280px; overflow-y: auto;
            box-shadow: 0 8px 24px rgba(11,30,61,.14); display: none;
        }
        .results-drop.open { display: block; }
        .results-head { display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; border-bottom: 1px solid var(--slate-100); background: var(--slate-50); }
        .results-head-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--slate-400); }

        .prod-item { padding: 9px 13px; cursor: pointer; border-bottom: 1px solid var(--slate-100); transition: background .12s; display: flex; align-items: center; justify-content: space-between; gap: .5rem; }
        .prod-item:last-child { border-bottom: none; }
        .prod-item:hover { background: var(--slate-50); border-left: 3px solid var(--rose); padding-left: 10px; }
        .prod-item-name  { font-size: 13px; font-weight: 600; color: var(--navy); }
        .prod-item-meta  { font-size: 11.5px; color: var(--slate-400); margin-top: 1px; font-family: var(--mono); }
        .prod-item-stock { font-size: 11.5px; font-weight: 700; color: var(--emerald); font-family: var(--mono); white-space: nowrap; }
        .no-results { padding: 1.25rem; text-align: center; font-size: 13px; color: var(--slate-400); }

        /* ══ CART TABLE ══ */
        .cart-wrap { flex: 1; overflow: hidden; display: flex; flex-direction: column; }
        .cart-scroll { flex: 1; overflow-y: auto; }

        .cart-tbl { width: 100%; border-collapse: collapse; font-size: 12.5px; }
        .cart-tbl thead th {
            background: var(--navy); color: rgba(255,255,255,.65);
            font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em;
            padding: 8px 10px; border: none; white-space: nowrap; text-align: left;
        }
        .cart-tbl thead th:last-child { text-align: center; }
        .cart-tbl tbody tr { border-bottom: 1px solid var(--slate-100); }
        .cart-tbl tbody tr:last-child { border-bottom: none; }
        .cart-tbl tbody tr:hover td { background: var(--slate-50); }
        .cart-tbl td { padding: 8px 10px; vertical-align: middle; }

        .cart-prod-name { font-weight: 600; color: var(--navy); font-size: 12.5px; }
        .num-input {
            width: 72px; padding: 5px 7px; text-align: center;
            border: 1.5px solid var(--slate-200); border-radius: var(--r);
            font-family: var(--mono); font-size: 12.5px; color: var(--slate-800);
            outline: none; transition: border-color .15s;
        }
        .num-input:focus { border-color: var(--navy-light); }
        .del-btn {
            width: 28px; height: 28px; border-radius: var(--r); background: var(--rose-pale);
            color: var(--rose); border: none; cursor: pointer; display: flex;
            align-items: center; justify-content: center; font-size: 12px; margin: auto;
            transition: all .15s;
        }
        .del-btn:hover { background: var(--rose); color: var(--white); }

        /* Empty cart */
        .empty-cart { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2.5rem 1.5rem; text-align: center; color: var(--slate-400); flex: 1; }
        .empty-cart i { font-size: 2rem; margin-bottom: .5rem; opacity: .3; display: block; }
        .empty-cart p { font-size: 13px; }

        /* ══ RIGHT PANEL ══ */
        .right-body { flex: 1; overflow-y: auto; padding: .9rem; display: flex; flex-direction: column; gap: .75rem; }

        /* Field */
        .field { display: flex; flex-direction: column; gap: 4px; }
        .field-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--slate-400); display: flex; align-items: center; gap: 5px; }
        .field-label i { font-size: 12px; }
        .field-input, .field-select, .field-textarea {
            width: 100%; font-family: var(--font); font-size: 13px;
            padding: 8px 11px; border: 1.5px solid var(--slate-200);
            border-radius: var(--r); background: var(--white);
            color: var(--slate-800); outline: none;
            transition: border-color .18s, box-shadow .18s;
        }
        .field-input:focus, .field-select:focus, .field-textarea:focus { border-color: var(--navy-light); box-shadow: 0 0 0 3px rgba(26,58,107,.1); }
        .field-select { appearance: none; cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; padding-right:2rem; }
        .field-textarea { resize: vertical; min-height: 68px; }
        .field-hint { font-size: 11px; color: var(--slate-400); display: flex; align-items: center; gap: 4px; }

        /* Summary block */
        .summary-block { margin-top: auto; flex-shrink: 0; padding: .9rem; border-top: 1.5px solid var(--slate-200); background: var(--slate-50); }
        .summ-row { display: flex; justify-content: space-between; align-items: center; font-size: 12.5px; padding: 4px 0; }
        .summ-row .sl { color: var(--slate-500); }
        .summ-row .sv { font-family: var(--mono); font-weight: 600; color: var(--slate-800); }

        .total-box {
            background: var(--navy); border-radius: var(--r-lg);
            padding: .9rem 1rem; margin-top: .65rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .total-box-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: rgba(255,255,255,.5); }
        .total-box-val   { font-family: var(--mono); font-size: 19px; font-weight: 700; color: var(--rose); }

        .action-row { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: .65rem; }
        .btn-clear-cart {
            display: flex; align-items: center; justify-content: center; gap: 5px;
            padding: 10px; border-radius: var(--r); border: 1.5px solid var(--slate-200);
            background: transparent; font-family: var(--font); font-size: 13px; font-weight: 600;
            color: var(--slate-600); cursor: pointer; transition: all .15s;
        }
        .btn-clear-cart:hover { background: var(--slate-100); }
        .btn-submit-return {
            display: flex; align-items: center; justify-content: center; gap: 5px;
            padding: 10px; border-radius: var(--r); border: none;
            background: var(--rose); color: var(--white);
            font-family: var(--font); font-size: 13px; font-weight: 700;
            cursor: pointer; box-shadow: 0 3px 12px rgba(225,29,72,.3);
            transition: all .18s;
        }
        .btn-submit-return:hover { background: #be123c; transform: translateY(-1px); }
        .btn-submit-return:disabled { background: var(--slate-300); cursor: not-allowed; transform: none; box-shadow: none; }

        /* ══ TOAST ══ */
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; }
        .toast {
            background: var(--white); border-radius: var(--r-lg);
            border: 1.5px solid var(--slate-200);
            box-shadow: 0 8px 24px rgba(11,30,61,.14);
            padding: .75rem 1.1rem; display: flex; align-items: center; gap: 9px;
            min-width: 280px; font-size: 13px; font-weight: 500;
            opacity: 0; transform: translateX(100%); transition: all .25s ease;
        }
        .toast.show { opacity: 1; transform: translateX(0); }
        .toast-success { border-left: 3px solid var(--emerald); }
        .toast-success i { color: var(--emerald); }
        .toast-error   { border-left: 3px solid var(--rose); }
        .toast-error i { color: var(--rose); }

        @media(max-width:768px) { .wrap { padding: 1rem; } }
    </style>
</head>
<body>
    @include('sidenav')

    <main class="main-content">
        <div class="wrap">

            {{-- Page header --}}
            <div class="pg-header">
                <div class="pg-left">
                    <div class="header-icon"><i class="bi bi-arrow-return-left"></i></div>
                    <div class="pg-title-text">
                        <h1>{{ __('messages.make_return_page_header') }}</h1>
                        <p>{{ __('messages.select_products_return') }}</p>
                    </div>
                </div>
                <div class="pg-right">
                    
                    <a href="{{ url('main-receiving') }}" class="btn-ghost-hdr">
                        <i class="bi bi-plus-circle"></i> {{ __('messages.make_receiving') }}
                    </a>
                    <a href="{{ url('main-returns') }}" class="btn-ghost-hdr">
                        <i class="bi bi-list-check"></i> {{ __('messages.view_returns') }}
                    </a>
                </div>
            </div>

            {{-- Warning --}}
            <div class="warn-banner">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <strong>{{ __('messages.return_approval_note') }}</strong>
                    <small>{{ __('messages.return_stock_note') }}</small>
                </div>
            </div>

            {{-- Alerts --}}
            @if(session('success'))<div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>@endif

            {{-- Main grid --}}
            <div class="main-grid">

                {{-- ═══ LEFT — Products + Cart ═══ --}}
                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-head-icon phi-rose"><i class="bi bi-search"></i></div>
                        <span class="panel-title">{{ __('messages.select_products_return') }}</span>
                        <span class="count-pill" id="cartCount">0</span>
                    </div>
                    <div class="panel-body">

                        {{-- Search --}}
                        <div class="row">
                  
                            <div class="col-md-10">
                            <div   div class="search-wrap">
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
                        </div>

                        {{-- Cart --}}
                        <div class="cart-wrap">
                            <div class="panel-head" style="border-radius:0; margin:-0; background:var(--slate-50); border:none; border-bottom:1.5px solid var(--slate-200); border-top:1.5px solid var(--slate-200); flex-shrink:0;">
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

                {{-- ═══ RIGHT — Details + Summary ═══ --}}
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
                                    @foreach(DB::table('vendors')->where('account', 7)->get() as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field">
                                <label class="field-label"><i class="bi bi-person"></i> Processed by</label>
                                <select id="served" name="served" class="field-select" required>
                                    <option value="" disabled selected>Select staff…</option>
                                    @foreach(DB::table('users')->where('account', 7)->orderBy('name', 'asc')->get() as $user)
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

                        {{-- Summary footer --}}
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
                                <button type="button" class="btn-submit-return" id="submitOrderBtn">
                                    <i class="bi bi-check-circle-fill"></i> Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>{{-- end main-grid --}}
        </div>
    </main>

<div class="toast-container" id="toastContainer"></div>

<script>
// ════════════════════════════════════════════
// Shop change handler
// ════════════════════════════════════════════
function changeShop(shopId) {
    if (!shopId) return;
    const url = new URL('{{ route("make-return") }}');
    url.searchParams.set('shop_id', shopId);
    window.location.href = url.toString();
}

/* ══ Products data from server ══ */
const allProducts = [
    @if(DB::table('products')->where('name01','!=','')->where('account',session('selected_shop_id'))->count() > 0)
    @foreach(DB::table('products')->where('name01','!=','')->where('account',session('selected_shop_id'))->get() as $p)
    {id:"{{$p->product_id}}",name:"{{addslashes($p->name01)}}",cost:{{$p->bPrice??0}},wholesale:{{$p->wholesale??0}},retail:{{$p->sPrice??0}},currentStock:{{$p->quantity??0}}},
    @endforeach
    @endif
];

    /* ══ Cart state ══ */
    const STORAGE_KEY = 'returnCartUser';
    let shoppingCart = [];

    function saveCart()  { localStorage.setItem(STORAGE_KEY, JSON.stringify(shoppingCart)); }
    function loadCart()  { try { const s = localStorage.getItem(STORAGE_KEY); if(s) shoppingCart = JSON.parse(s); } catch(e) { shoppingCart = []; } }
    function clearStoredCart() { localStorage.removeItem(STORAGE_KEY); }

    /* ══ Init ══ */
    document.addEventListener('DOMContentLoaded', () => {
        loadCart();
        bindEvents();
        refreshCart();
    });

    function bindEvents() {
        document.getElementById('productSearch').addEventListener('input', handleSearch);
        document.getElementById('clearCartBtn').addEventListener('click', clearCart);
        document.getElementById('submitOrderBtn').addEventListener('click', submitOrder);
        document.addEventListener('click', e => {
            const drop = document.getElementById('productListSection');
            const inp  = document.getElementById('productSearch');
            if (drop && !drop.contains(e.target) && inp && !inp.contains(e.target)) {
                drop.classList.remove('open');
            }
        });
    }

    /* ══ Search ══ */
    function handleSearch(e) {
        const q    = e.target.value.toLowerCase().trim();
        const drop = document.getElementById('productListSection');
        if (!q) { drop.classList.remove('open'); return; }
        const results = allProducts.filter(p => p.name.toLowerCase().includes(q));
        renderResults(results);
        drop.classList.add('open');
    }

    function renderResults(products) {
        const list = document.getElementById('productList');
        const cnt  = document.getElementById('productCount');
        cnt.textContent = products.length;
        if (!products.length) {
            list.innerHTML = '<div class="no-results">No products found</div>';
            return;
        }
        list.innerHTML = products.map(p => `
            <div class="prod-item" onclick="addToCart('${p.id}')">
                <div>
                    <div class="prod-item-name">${p.name}</div>
                    <div class="prod-item-meta">Cost: ${p.cost.toLocaleString()} Tsh</div>
                </div>
                <span class="prod-item-stock">Stock: ${p.currentStock}</span>
            </div>
        `).join('');
    }

    /* ══ Cart logic ══ */
    function addToCart(id) {
        const p = allProducts.find(x => x.id === id);
        if (!p) return;
        if (p.currentStock < 1) { showToast('Cannot return — zero stock!', 'error'); return; }
        const existing = shoppingCart.find(x => x.productId === id);
        if (existing) {
            if (existing.quantity < p.currentStock) { existing.quantity++; }
            else { showToast('Cannot exceed current stock', 'error'); return; }
        } else {
            shoppingCart.push({
                cartId: Date.now() + Math.random().toString(36).substr(2, 9),
                productId: p.id, name: p.name,
                cost: p.cost, wholesale: p.wholesale, retail: p.retail,
                quantity: 1
            });
        }
        saveCart();
        document.getElementById('productSearch').value = '';
        document.getElementById('productListSection').classList.remove('open');
        showToast(`${p.name} added to cart`);
        refreshCart();
    }

    function removeFromCart(cartId) {
        shoppingCart = shoppingCart.filter(x => x.cartId !== cartId);
        saveCart(); refreshCart(); showToast('Item removed');
    }

    function updateQuantity(cartId, val) {
        const item = shoppingCart.find(x => x.cartId === cartId);
        if (item) item.quantity = parseInt(val) || 1;
        saveCart(); refreshSummary();
    }

    function updatePrice(cartId, field, val) {
        const item = shoppingCart.find(x => x.cartId === cartId);
        if (item) item[field] = parseFloat(val) || 0;
        refreshSummary();
    }

    function clearCart() {
        if (!shoppingCart.length) return;
        if (!confirm('Clear all items from cart?')) return;
        shoppingCart = []; clearStoredCart(); refreshCart(); showToast('Cart cleared');
    }

    function refreshCart() {
        const wrap = document.getElementById('cartItems');
        if (!shoppingCart.length) {
            wrap.innerHTML = `<div class="empty-cart"><i class="bi bi-arrow-return-left"></i><p>No products added yet<br><span style="font-size:11.5px;">Search and click a product to add it</span></p></div>`;
            refreshSummary(); updateCountPills(); return;
        }
        wrap.innerHTML = `
            <table class="cart-tbl">
                <thead><tr>
                    <th>Product</th><th>Qty</th><th>Cost</th><th>Wholesale</th><th>Retail</th><th style="text-align:center;">Del</th>
                </tr></thead>
                <tbody>
                ${shoppingCart.map(i => `
                    <tr>
                        <td class="cart-prod-name">${i.name}</td>
                        <td><input type="number" class="num-input" value="${i.quantity}" min="1"
                               oninput="updateQuantity('${i.cartId}',this.value)"></td>
                        <td><input type="number" class="num-input" value="${i.cost}" step="0.01"
                               oninput="updatePrice('${i.cartId}','cost',this.value)"></td>
                        <td><input type="number" class="num-input" value="${i.wholesale}" step="0.01"
                               oninput="updatePrice('${i.cartId}','wholesale',this.value)"></td>
                        <td><input type="number" class="num-input" value="${i.retail}" step="0.01"
                               oninput="updatePrice('${i.cartId}','retail',this.value)"></td>
                        <td><button class="del-btn" onclick="removeFromCart('${i.cartId}')"><i class="bi bi-trash"></i></button></td>
                    </tr>
                `).join('')}
                </tbody>
            </table>`;
        refreshSummary(); updateCountPills();
    }

    function refreshSummary() {
        let total = 0, items = 0;
        shoppingCart.forEach(x => { total += (x.cost || 0) * (x.quantity || 1); items += x.quantity; });
        document.getElementById('itemCount').textContent      = items;
        document.getElementById('cartCountSumm').textContent  = shoppingCart.length;
        document.getElementById('totalAmount').textContent    = 'Tsh ' + Math.round(total).toLocaleString();
    }

    function updateCountPills() {
        document.getElementById('cartCount').textContent = shoppingCart.length;
    }

    /* ══ Submit ══ */
    function submitOrder() {
        const sup    = document.getElementById('supplier').value;
        const srv    = document.getElementById('served').value;
        const reason = document.getElementById('reason').value;
        if (!shoppingCart.length)    { showToast('Add products first', 'error'); return; }
        if (!sup || !srv)            { showToast('Select supplier and staff', 'error'); return; }
        if (!reason.trim())          { showToast('Provide a reason for return', 'error'); return; }
        if (!confirm('This will decrease product quantities in inventory. Continue?')) return;

        const btn = document.getElementById('submitOrderBtn');
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing…';
        btn.disabled  = true;

        const fd = new FormData();
        fd.append('_token', '{{csrf_token()}}');
        fd.append('supplier', sup);
        fd.append('served', srv);
        fd.append('reason', reason);
        fd.append('receivingDate', document.getElementById('receivingDate').value);
        fd.append('transactionType', document.getElementById('transactionType').value);
        shoppingCart.forEach(i => {
            fd.append('product_id[]', i.productId);
            fd.append('quantity[]', i.quantity);
            fd.append('bPrice[]', i.cost);
            fd.append('wholesale[]', i.wholesale);
            fd.append('sPrice[]', i.retail);
            fd.append('transactionType[]', i.type || 'Cash');
            fd.append('expiry[]', '');
        });

        fetch('{{route("process-return")}}', {
            method: 'POST', body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.text())
        .then(() => {
            shoppingCart = []; clearStoredCart();
            refreshCart();
            document.getElementById('orderForm').reset();
            showToast(@json(__('messages.return_request_submitted_toast')), 'success');
            setTimeout(() => location.href = '{{url("view-returns")}}', 1500);
        })
        .catch(() => showToast('Error processing return. Please try again.', 'error'))
        .finally(() => {
            btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Submit';
            btn.disabled  = false;
        });
    }

    /* ══ Toast ══ */
    function showToast(msg, type = 'success') {
        let container = document.getElementById('toastContainer');
        const t = document.createElement('div');
        const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill';
        t.className = `toast toast-${type}`;
        t.innerHTML = `<i class="bi ${icon}"></i> ${msg}`;
        container.appendChild(t);
        setTimeout(() => t.classList.add('show'), 10);
        setTimeout(() => { t.classList.remove('show'); setTimeout(() => t.remove(), 280); }, 3000);
    }
</script>
</body>
</html>