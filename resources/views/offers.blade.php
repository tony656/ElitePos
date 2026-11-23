<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('messages.offered_products_report')</title>
    @include("links")
    
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

        /* Action buttons */
        .action-cell { display: flex; gap: .28rem; justify-content: center; flex-wrap: wrap; }
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
        .action-icon-btn.view   { background: var(--sky) !important; }
        .action-icon-btn.delete { background: var(--rose) !important; }
        .action-icon-btn:hover { background: #064f91 !important; transform: translateY(-1px); }
        .action-icon-btn.delete:hover { background: var(--rose) !important; }
        .action-icon-btn.view:hover { background: #0756a6 !important; }

        /* Status toggle badge */
        .badge-active {
            background: var(--emerald-pale);
            color: var(--emerald);
            padding: .22rem .55rem;
            border-radius: 20px;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .03em;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
        }
        .badge-inactive {
            background: var(--rose-pale);
            color: var(--rose);
            padding: .22rem .55rem;
            border-radius: 20px;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .03em;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
        }

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

        /* Offer search results */
        .offer-search-wrap { position: relative; }
        .offer-search-results {
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
        .offer-search-item {
            padding: 0.6rem 0.9rem;
            cursor: pointer;
            border-bottom: 1px solid var(--slate-100);
            transition: background 0.15s;
        }
        .offer-search-item:last-child { border-bottom: none; }
        .offer-search-item:hover, .offer-search-item.selected { background: #edf6ff; border-left: 3px solid #0b68b4; padding-left: calc(0.9rem - 3px); }
        .offer-search-item-name { font-size: .8rem; font-weight: 600; color: var(--navy); }
        .offer-search-item-stock { font-size: .72rem; color: var(--slate-500); margin-top: 2px; }

        /* Required items list */
        .required-item-row {
            display: flex;
            gap: .4rem;
            flex-wrap: wrap;
            align-items: flex-end;
            margin-bottom: .5rem;
        }
        .required-item-row .field { margin-bottom: 0; }
        .required-item-row .field-label { margin-bottom: .25rem; font-size: .66rem; }

        /* Required item remove button */
        .btn-remove-req {
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
        .btn-remove-req:hover { background: var(--rose); color: #fff; border-color: var(--rose); }

        @media (max-width: 768px) {
            .wrap { padding: .8rem; }
            .pg-header { padding: .25rem 0 .7rem; }
            .filters-row { flex-direction: column; align-items: stretch; }
            .filter-actions { width: 100%; justify-content: flex-end; }
            .table-wrap { max-height: none; }
        }
    </style>
</head>
<body>
    @include("sidenav")

    <main class="main-content">
        {{-- ── Alerts ── --}}
        @if(session('success'))
        <div class="alert alert-success">
            <span>{{ session('success') }}</span>
            <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">
            <span>{{ session('error') }}</span>
            <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
        </div>
        @endif

        <div class="wrap">

            {{-- Page header --}}
            <div class="pg-header">
                <div class="pg-left">
                    <div class="header-icon"><i class="bi bi-gift"></i></div>
                    <div class="pg-title-text">
                        <h1>@lang('messages.offered_products_report')</h1>
                        <p>Manage product offers — buy X get Y free</p>
                    </div>
                </div>
                <div class="pg-right">
                    <button class="btn-header" data-bs-toggle="modal" data-bs-target="#createOfferModal">
                        <i class="bi bi-plus-lg"></i> @lang('messages.create_offer')
                    </button>
                    <a href="{{ url('shopReport') }}" class="btn-header" style="background:transparent; color:var(--slate-500); border-color:transparent; box-shadow:none;">
                        <i class="bi bi-arrow-left"></i> @lang('messages.back_to_reports')
                    </a>
                </div>
            </div>

            {{-- Filters --}}
            <div class="filters-card">
                <form method="GET" action="{{ url('offers') }}">
                    <div class="filters-row">
                        <div class="filter-group">
                            <label class="filter-label">@lang('messages.products_select_shop')</label>
                            <select name="shop" id="offerShopSelect" class="filter-ctrl" onchange="this.form.submit()">
                                <option value="">-- @lang('messages.products_select_shop') --</option>
                                @foreach($accounts as $shop)
                                <option value="{{ $shop['id'] }}" {{ $shop['id'] == getCurrentShopId() ? 'selected' : '' }}>{{ $shop['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="btn-filter">
                                <i class="bi bi-funnel"></i> @lang('messages.filter')
                            </button>
                            <a href="{{ url('offers') }}" class="btn-reset">
                                <i class="bi bi-x"></i> @lang('messages.reset')
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Offers table --}}
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-left">
                        <div class="panel-head-icon"><i class="bi bi-gift"></i></div>
                        <span class="panel-title">@lang('messages.offered_products_report')</span>
                    </div>
                </div>

                @php
                    $offersList = isset($items) ? $items : (isset($offers) ? $offers : collect());
                @endphp

                @if(count($offersList) > 0)
                <div class="tbl-wrap">
                    <table id="offersTable">
                        <thead>
                            <tr>
                                <th style="width: 8%;">#</th>
                                <th style="width: 22%;">@lang('messages.item_to_buy')</th>
                                <th style="width: 22%;">@lang('messages.item_to_give_free')</th>
                                <th style="width: 14%;">@lang('messages.products_required_quantity_buy')</th>
                                <th style="width: 14%;">@lang('messages.products_offer_quantity_get_free')</th>
                                <th style="width: 12%;">@lang('messages.status')</th>
                                <th style="width: 8%;" class="text-right">@lang('messages.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offersList as $item)
                            @php
                                $firstReqItem = $item->requiredItems->first();
                                $firstReqQty = $firstReqItem ? $firstReqItem->required_quantity : 1;
                                $firstReqProductId = $firstReqItem ? $firstReqItem->product_id : $item->product_id;
                            @endphp
                            <tr data-id="{{ $item->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $products[$firstReqProductId] ?? __('messages.unknown_product') }}</strong>
                                        <div style="font-size: .68rem; color: var(--slate-400);">ID: {{ $firstReqProductId }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $products[$item->offer_product_id] ?? __('messages.unknown_product') }}</strong>
                                        <div style="font-size: .68rem; color: var(--slate-400);">ID: {{ $item->offer_product_id }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="inv-badge">
                                        <i class="bi bi-gift"></i> {{ number_format($firstReqQty) }}
                                    </span>
                                </td>
                                <td>{{ number_format($item->offer_quantity) }}</td>
                                <td>
                                    <button class="status-toggle" data-active="{{ $item->is_active ? '1' : '0' }}" data-id="{{ $item->id }}" title="@lang('messages.products_offer_active')" style="background:none; border:none; cursor:pointer; padding:0;">
                                        @if($item->is_active)
                                            <span class="badge-active"><i class="bi bi-check-circle-fill"></i> @lang('messages.active')</span>
                                        @else
                                            <span class="badge-inactive"><i class="bi bi-x-circle-fill"></i> @lang('messages.inactive')</span>
                                        @endif
                                    </button>
                                </td>
                                <td class="text-right">
                                    <div class="action-cell">
                                        <button class="action-icon-btn view edit-btn" title="Edit"
                                            data-id="{{ $item->id }}"
                                            data-product_id="{{ $firstReqProductId }}"
                                            data-offer_product_id="{{ $item->offer_product_id }}"
                                            data-required_quantity="{{ $firstReqQty }}"
                                            data-offer_quantity="{{ $item->offer_quantity }}"
                                            data-is_active="{{ $item->is_active ? '1' : '0' }}"
                                            data-account="{{ $item->account }}"
                                            data-required_items="{{ json_encode($item->requiredItems->map(function($ri) {
                                                return ['product_id' => $ri->product_id, 'required_quantity' => $ri->required_quantity];
                                            })->toArray()) }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="action-icon-btn delete delete-btn" title="@lang('messages.delete')" data-id="{{ $item->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-gift"></i></div>
                    <h4>@lang('messages.no_offered_products_found')</h4>
                    <p>@lang('messages.try_adjusting_date_range')</p>
                </div>
                @endif
            </div>

        </div>
    </main>

    {{-- Create Offer Modal --}}
    <div class="modal fade" id="createOfferModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle"></i> @lang('messages.create_offer')
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('saveOffer') }}" id="createOfferForm">
                    @csrf
                    <div class="modal-body">
                        <div class="field">
                            <label class="field-label">@lang('messages.products_select_shop') <span class="req">*</span></label>
                            <select name="account" id="offerShopSelectCreate" class="field-input" required>
                                <option value="">-- @lang('messages.products_select_shop') --</option>
                                @foreach($accounts as $shop)
                                <option value="{{ $shop['id'] }}">{{ $shop['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label class="field-label">@lang('messages.item_to_give_free') <span class="req">*</span></label>
                            <div class="offer-search-wrap">
                                <i class="bi bi-search"></i>
                                <input type="text" id="freeProductSearch" class="field-input" placeholder="@lang('messages.type_product_name')" autocomplete="off">
                                <input type="hidden" name="offer_product_id" id="freeProductId">
                            </div>
                            <div class="offer-search-results" id="freeSearchResults"></div>
                        </div>

                        <div class="field">
                            <label class="field-label">@lang('messages.products_offer_quantity_get_free') <span class="req">*</span></label>
                            <input type="number" name="offer_quantity" class="field-input" value="1" min="1" required>
                        </div>

                        <div class="field">
                            <label class="field-label">@lang('messages.item_to_buy') <span class="req">*</span></label>
                            <div class="offer-search-wrap">
                                <i class="bi bi-search"></i>
                                <input type="text" id="requiredProductSearch" class="field-input" placeholder="@lang('messages.type_product_name')" autocomplete="off">
                                <input type="hidden" name="required_product_id" id="requiredProductId">
                            </div>
                            <div class="offer-search-results" id="requiredSearchResults"></div>
                        </div>

                        <div class="field">
                            <label class="field-label">@lang('messages.products_required_quantity_buy') <span class="req">*</span></label>
                            <input type="number" name="required_quantity" class="field-input" value="1" min="1" required>
                        </div>

                        <div class="field">
                            <div class="form-check" style="padding-bottom:0;">
                                <input type="checkbox" name="is_active" id="isActiveOffer" checked class="form-check-input" style="width:1.1rem; height:1.1rem; margin-top:.05rem;">
                                <label class="form-check-label" for="isActiveOffer" style="margin-left:.4rem; font-size:.78rem; font-weight:600; color:var(--slate-800); cursor:pointer;">@lang('messages.products_offer_active')</label>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer-custom">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-check-lg"></i> @lang('messages.products_save_offer')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Offer Modal --}}
    <div class="modal fade" id="editOfferModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square"></i> Edit Offer
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="editOfferMessage" style="margin-bottom: .75rem;"></div>
                    <form method="POST" action="" id="editOfferForm">
                        @csrf
                        @method('PATCH')
                        <div class="field">
                            <label class="field-label">@lang('messages.products_select_shop') <span class="req">*</span></label>
                            <select name="account" id="editOfferShopSelect" class="field-input" required>
                                @foreach($accounts as $shop)
                                <option value="{{ $shop['id'] }}">{{ $shop['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label class="field-label">@lang('messages.item_to_give_free') <span class="req">*</span></label>
                            <div class="offer-search-wrap">
                                <i class="bi bi-search"></i>
                                <input type="text" id="editFreeProductSearch" class="field-input" placeholder="@lang('messages.type_product_name')" autocomplete="off">
                                <input type="hidden" name="offer_product_id" id="editFreeProductId">
                            </div>
                            <div class="offer-search-results" id="editFreeSearchResults"></div>
                        </div>

                        <div class="field">
                            <label class="field-label">@lang('messages.products_offer_quantity_get_free') <span class="req">*</span></label>
                            <input type="number" name="offer_quantity" id="editOfferQuantity" class="field-input" value="1" min="1" required>
                        </div>

                        <div class="field">
                            <label class="field-label">@lang('messages.item_to_buy') <span class="req">*</span></label>
                            <div class="offer-search-wrap">
                                <i class="bi bi-search"></i>
                                <input type="text" id="editRequiredProductSearch" class="field-input" placeholder="@lang('messages.type_product_name')" autocomplete="off">
                                <input type="hidden" name="required_product_id" id="editRequiredProductId">
                            </div>
                            <div class="offer-search-results" id="editRequiredSearchResults"></div>
                        </div>

                        <div class="field">
                            <label class="field-label">@lang('messages.products_required_quantity_buy') <span class="req">*</span></label>
                            <input type="number" name="required_quantity" id="editRequiredQuantity" class="field-input" value="1" min="1" required>
                        </div>

                        <div class="field">
                            <div class="form-check" style="padding-bottom:0;">
                                <input type="checkbox" name="is_active" id="editIsActiveOffer" class="form-check-input" style="width:1.1rem; height:1.1rem; margin-top:.05rem;">
                                <label class="form-check-label" for="editIsActiveOffer" style="margin-left:.4rem; font-size:.78rem; font-weight:600; color:var(--slate-800); cursor:pointer;">@lang('messages.products_offer_active')</label>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer-custom">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-check-lg"></i> Update Offer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteOfferModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle"></i> @lang('messages.confirm_delete')
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size: .78rem; margin-bottom: 0;">
                        @lang('messages.products_are_you_sure_delete_offer')
                    </p>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">@lang('messages.cancel')</button>
                    <button type="button" class="btn-submit" style="background:var(--rose);" id="confirmDeleteOfferBtn">
                        <i class="bi bi-trash"></i> @lang('messages.delete')
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let offerProductsCache = {};

        function loadOfferProducts(accountId) {
            if (!accountId) return Promise.resolve();
            const cacheKey = 'shop_' + accountId;
            if (offerProductsCache[cacheKey]) {
                return Promise.resolve(offerProductsCache[cacheKey]);
            }
            return fetch('{{ url('search-products-for-offer') }}?account=' + accountId + '&q=')
                .then(res => res.json())
                .then(data => {
                    offerProductsCache[cacheKey] = data.results || [];
                    return offerProductsCache[cacheKey];
                })
                .catch(() => {
                    return [];
                });
        }

        function setupOfferSearch(inputId, resultsId, hiddenId) {
            const searchInput = document.getElementById(inputId);
            const resultsDiv = document.getElementById(resultsId);
            const hiddenInput = document.getElementById(hiddenId);
            if (!searchInput || !resultsDiv || !hiddenInput) return;

            let debounceTimer;

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                hiddenInput.value = '';

                if (query.length < 1) {
                    resultsDiv.innerHTML = '';
                    resultsDiv.style.display = 'none';
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    const shopSelect = document.getElementById('offerShopSelectCreate') || document.getElementById('editOfferShopSelect');
                    const shopId = shopSelect ? shopSelect.value : null;

                    if (!shopId) {
                        resultsDiv.innerHTML = '<div class="offer-search-item" style="color:var(--slate-400);pointer-events:none;">Please select a shop first</div>';
                        resultsDiv.style.display = 'block';
                        return;
                    }

                    const cacheKey = 'shop_' + shopId;
                    const products = offerProductsCache[cacheKey] || [];

                    const filtered = products.filter(function(p) {
                        return p.name && p.name.toLowerCase().includes(query.toLowerCase());
                    });

                    if (filtered.length === 0) {
                        resultsDiv.innerHTML = '<div class="offer-search-item" style="color:var(--slate-400);pointer-events:none;">No matches found</div>';
                    } else {
                        resultsDiv.innerHTML = filtered.map(function(p) {
                            return '<div class="offer-search-item" data-id="' + p.id + '" data-name="' + p.name + '" data-stock="' + p.stock + '">' +
                                '<div class="offer-search-item-name">' + p.name + '</div>' +
                                '<div class="offer-search-item-stock">Stock: ' + p.stock + '</div>' +
                                '</div>';
                        }).join('');
                    }
                    resultsDiv.style.display = 'block';
                }, 200);
            });

            searchInput.addEventListener('focus', function() {
                if (this.value.trim().length >= 1) {
                    const evt = new Event('input');
                    this.dispatchEvent(evt);
                }
            });

            resultsDiv.addEventListener('click', function(e) {
                const item = e.target.closest('.offer-search-item');
                if (!item) return;
                const productId = item.getAttribute('data-id');
                const productName = item.getAttribute('data-name');
                const productStock = item.getAttribute('data-stock');
                searchInput.value = productName + ' (Stock: ' + productStock + ')';
                hiddenInput.value = productId;
                resultsDiv.style.display = 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            let deleteOfferId = null;

            // Create offer modal shop change
            const createShopSelect = document.getElementById('offerShopSelectCreate');
            if (createShopSelect) {
                createShopSelect.addEventListener('change', function() {
                    loadOfferProducts(this.value).then(function() {
                        const freeSearch = document.getElementById('freeProductSearch');
                        const freeId = document.getElementById('freeProductId');
                        if (freeSearch) { freeSearch.value = ''; }
                        if (freeId) { freeId.value = ''; }
                        document.getElementById('freeSearchResults').style.display = 'none';
                        const reqSearch = document.getElementById('requiredProductSearch');
                        const reqId = document.getElementById('requiredProductId');
                        if (reqSearch) { reqSearch.value = ''; }
                        if (reqId) { reqId.value = ''; }
                        document.getElementById('requiredSearchResults').style.display = 'none';
                    });
                });
                if (createShopSelect.value) {
                    loadOfferProducts(createShopSelect.value);
                }
            }

            // Edit offer modal setup
            const editShopSelect = document.getElementById('editOfferShopSelect');
            if (editShopSelect) {
                editShopSelect.addEventListener('change', function() {
                    loadOfferProducts(this.value);
                });
            }

            setupOfferSearch('freeProductSearch', 'freeSearchResults', 'freeProductId');
            setupOfferSearch('requiredProductSearch', 'requiredSearchResults', 'requiredProductId');
            setupOfferSearch('editFreeProductSearch', 'editFreeSearchResults', 'editFreeProductId');
            setupOfferSearch('editRequiredProductSearch', 'editRequiredSearchResults', 'editRequiredProductId');

            // Status toggle
            document.querySelectorAll('.status-toggle').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const offerId = this.dataset.id;
                    const isActive = this.dataset.active === '1';
                    const row = this.closest('tr');
                    const editBtn = row.querySelector('.edit-btn');
                    const requiredQty = editBtn.dataset.required_quantity;

                    fetch('{{ url("updateOffer") }}/' + offerId, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            offer_product_id: editBtn.dataset.offer_product_id,
                            offer_quantity: editBtn.dataset.offer_quantity,
                            required_quantity: requiredQty,
                            product_id: editBtn.dataset.product_id,
                            is_active: isActive ? '0' : '1',
                        })
                    }).then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.dataset.active = isActive ? '0' : '1';
                            if (isActive) {
                                this.innerHTML = '<span class="badge-inactive"><i class="bi bi-x-circle-fill"></i> Inactive</span>';
                            } else {
                                this.innerHTML = '<span class="badge-active"><i class="bi bi-check-circle-fill"></i> Active</span>';
                            }
                        } else {
                            alert(data.message || 'Failed to update status');
                        }
                    }).catch(err => {
                        console.error(err);
                        alert('Failed to update status');
                    });
                });
            });

            // Edit button click
            document.querySelectorAll('.edit-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const productId = this.dataset.product_id;
                    const offerProductId = this.dataset.offer_product_id;
                    const requiredQty = this.dataset.required_quantity;
                    const offerQty = this.dataset.offer_quantity;
                    const isActive = this.dataset.is_active;
                    const account = this.dataset.account;
                    let requiredItems = [];
                    try {
                        requiredItems = JSON.parse(this.dataset.required_items || '[]');
                    } catch (e) {}

                    document.getElementById('editOfferForm').action = '{{ url("updateOffer") }}/' + id;
                    document.getElementById('editOfferQuantity').value = offerQty;
                    document.getElementById('editRequiredQuantity').value = requiredQty;
                    document.getElementById('editIsActiveOffer').checked = isActive === '1';
                    document.getElementById('editOfferShopSelect').value = account;

                    const editMessage = document.getElementById('editOfferMessage');
                    if (editMessage) editMessage.innerHTML = '';

                    // Set required product
                    const reqSearch = document.getElementById('editRequiredProductSearch');
                    const reqId = document.getElementById('editRequiredProductId');
                    const products = offerProductsCache['shop_' + account] || [];
                    const reqProduct = products.find(function(p) { return p.id === productId; });
                    if (reqProduct && reqSearch) {
                        reqSearch.value = reqProduct.name + ' (Stock: ' + reqProduct.stock + ')';
                    } else if (reqSearch) {
                        reqSearch.value = productId;
                    }
                    if (reqId) reqId.value = productId;

                    const cacheKey = 'shop_' + account;
                    const setEditValues = function() {
                        const prods = offerProductsCache[cacheKey] || [];
                        const freeProduct = prods.find(function(p) { return p.id === offerProductId; });
                        const freeSearch = document.getElementById('editFreeProductSearch');
                        const freeId = document.getElementById('editFreeProductId');
                        if (freeProduct) {
                            if (freeSearch) freeSearch.value = freeProduct.name + ' (Stock: ' + freeProduct.stock + ')';
                            if (freeId) freeId.value = freeProduct.id;
                        } else {
                            if (freeSearch) freeSearch.value = offerProductId;
                            if (freeId) freeId.value = offerProductId;
                        }
                    };

                    if (offerProductsCache[cacheKey] && offerProductsCache[cacheKey].length > 0) {
                        setEditValues();
                        new bootstrap.Modal(document.getElementById('editOfferModal')).show();
                    } else {
                        loadOfferProducts(account).then(function() {
                            setEditValues();
                            new bootstrap.Modal(document.getElementById('editOfferModal')).show();
                        }).catch(function() {
                            const freeId = document.getElementById('editFreeProductId');
                            if (freeId) freeId.value = offerProductId;
                            new bootstrap.Modal(document.getElementById('editOfferModal')).show();
                        });
                    }
                });
            });

            // Edit form submit
            const editForm = document.getElementById('editOfferForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const actionUrl = this.action;

                    fetch(actionUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('editOfferMessage').innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i> Offer updated successfully!</div>';
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            document.getElementById('editOfferMessage').innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-circle-fill me-2"></i> ' + (data.message || 'Failed to update offer') + '</div>';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        document.getElementById('editOfferMessage').innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-circle-fill me-2"></i> Failed to update offer</div>';
                    });
                });
            }

            // Delete button click
            document.querySelectorAll('.delete-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    deleteOfferId = this.dataset.id;
                    new bootstrap.Modal(document.getElementById('deleteOfferModal')).show();
                });
            });

            // Confirm delete
            const confirmDeleteBtn = document.getElementById('confirmDeleteOfferBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', function() {
                    if (!deleteOfferId) return;

                    fetch('{{ url("deleteOffer") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ offer_id: deleteOfferId })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Failed to delete offer');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Failed to delete offer');
                    });
                });
            }

            // Close search dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.offer-search-wrap')) {
                    document.querySelectorAll('.offer-search-results').forEach(function(div) { div.style.display = 'none'; });
                }
            });
        });
    </script>
</body>
</html>
