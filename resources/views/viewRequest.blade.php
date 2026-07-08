<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - {{ __('messages.requested_items') }}</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0B1E3D;
            --navy-mid: #112952;
            --navy-light: #1A3A6B;
            --amber: #F59E0B;
            --amber-pale: #FEF3C7;
            --emerald: #059669;
            --emerald-pale: #D1FAE5;
            --rose: #E11D48;
            --rose-pale: #FFE4E6;
            --violet: #7C3AED;
            --violet-pale: #EDE9FE;
            --sky: #0284C7;
            --sky-pale: #E0F2FE;
            --slate-50: #F8FAFC;
            --slate-100: #F1F5F9;
            --slate-200: #E2E8F0;
            --slate-300: #CBD5E1;
            --slate-400: #94A3B8;
            --slate-500: #64748B;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1E293B;
            --white: #FFFFFF;
            --radius: 12px;
            --shadow-sm: 0 1px 3px rgba(11,30,61,.08);
            --shadow: 0 4px 20px rgba(11,30,61,.12);
            --shadow-lg: 0 10px 40px rgba(11,30,61,.15);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--slate-50);
            color: var(--slate-800);
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            background: var(--white);
            border-bottom: 1px solid var(--slate-200);
            padding: 1.2rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            box-shadow: var(--shadow-sm);
        }

        .page-header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            color: var(--slate-500);
            text-decoration: none;
            font-size: .85rem;
            font-weight: 500;
            padding: .4rem .75rem;
            border-radius: var(--radius);
            transition: all .15s;
        }
        .back-btn:hover { background: var(--slate-100); color: var(--navy); }

        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--navy);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
        }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .5rem 1.2rem;
            border-radius: var(--radius);
            font-size: .85rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all .2s ease;
            white-space: nowrap;
        }
        .btn-primary { background: var(--navy); color: var(--white); }
        .btn-primary:hover { background: var(--navy-light); color: var(--white); transform: translateY(-2px); box-shadow: var(--shadow); }
        
        .btn-success { background: var(--emerald); color: var(--white); }
        .btn-success:hover { background: #047857; color: var(--white); transform: translateY(-2px); box-shadow: var(--shadow); }
        
        .btn-outline { background: transparent; color: var(--slate-700); border: 1px solid var(--slate-200); }
        .btn-outline:hover { background: var(--slate-100); border-color: var(--navy); }
        
        .btn-ghost { background: transparent; color: var(--navy); border: 1px solid rgba(11,30,61,.15); }
        .btn-ghost:hover { background: var(--slate-100); border-color: var(--navy); }
        
        .btn-sm { padding: .35rem .75rem; font-size: .8rem; }
        .btn-warning { background: var(--amber); color: var(--navy); }
        .btn-warning:hover { background: #d97706; color: var(--navy); transform: translateY(-2px); }

        .btn-print {
            background: var(--slate-600);
            color: var(--white);
        }
        .btn-print:hover {
            background: var(--slate-700);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        /* ===== STATS GRID ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.2rem;
            margin: 1.5rem 2rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 1.5rem 1.8rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--slate-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            transition: all .3s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card-blue::after { background: var(--navy); }
        .stat-card-amber::after { background: var(--amber); }
        .stat-card-emerald::after { background: var(--emerald); }

        .stat-label {
            font-size: .75rem;
            font-weight: 600;
            color: var(--slate-500);
            letter-spacing: .05em;
            text-transform: uppercase;
            margin-bottom: .3rem;
        }
        .stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            color: var(--navy);
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 1.4rem;
            transition: transform .3s ease;
        }
        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(-5deg);
        }
        .stat-icon-blue { background: var(--slate-100); color: var(--navy); }
        .stat-icon-amber { background: var(--amber-pale); color: #92400e; }
        .stat-icon-emerald { background: var(--emerald-pale); color: var(--emerald); }

        /* ===== TOOLBAR ===== */
        .toolbar {
            margin: 0 2rem 1rem;
            display: flex;
            gap: .75rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-wrap {
            flex: 1;
            min-width: 200px;
            position: relative;
        }
        .search-wrap i {
            position: absolute;
            left: .85rem; top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
            pointer-events: none;
        }
        .search-input {
            width: 100%;
            padding: .6rem 1rem .6rem 2.4rem;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: .875rem;
            background: var(--white);
            color: var(--slate-800);
            outline: none;
            transition: all .15s;
        }
        .search-input:focus {
            border-color: var(--navy);
            box-shadow: 0 0 0 3px rgba(11,30,61,.1);
        }

        .filter-group {
            display: flex;
            gap: .5rem;
            align-items: center;
            background: var(--white);
            padding: .25rem .5rem .25rem 1rem;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius);
            transition: all .15s;
        }
        .filter-group:focus-within {
            border-color: var(--navy);
            box-shadow: 0 0 0 3px rgba(11,30,61,.1);
        }
        
        .filter-group label {
            font-size: .8rem;
            color: var(--slate-500);
            white-space: nowrap;
        }
        
        .date-input, .shop-select {
            padding: .45rem .75rem;
            border: none;
            border-radius: var(--radius);
            font-family: inherit;
            font-size: .85rem;
            background: transparent;
            color: var(--slate-800);
            outline: none;
        }

        .date-range-badge {
            background: var(--slate-100);
            padding: .3rem .8rem;
            border-radius: 20px;
            font-size: .8rem;
            color: var(--navy);
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            border: 1px solid var(--slate-200);
        }
        
        .date-range-badge i {
            cursor: pointer;
            transition: opacity .2s;
            color: var(--slate-400);
        }
        
        .date-range-badge i:hover {
            opacity: .7;
            color: var(--rose);
        }

        /* ===== TABLE ===== */
        .table-card {
            margin: 0 2rem 2rem;
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--slate-200);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: all .3s ease;
        }
        .table-card:hover {
            box-shadow: var(--shadow);
        }

        .tbl { width: 100%; border-collapse: collapse; }

        .tbl thead tr {
            background: var(--navy);
        }
        .tbl thead th {
            padding: .9rem 1rem;
            font-size: .7rem;
            font-weight: 600;
            color: rgba(255,255,255,.8);
            text-transform: uppercase;
            letter-spacing: .08em;
            white-space: nowrap;
        }
        .tbl thead th:first-child { padding-left: 1.5rem; }
        .tbl thead th:last-child  { padding-right: 1.5rem; text-align: right; }

        .tbl tbody tr {
            border-bottom: 1px solid var(--slate-100);
            transition: all .15s;
        }
        .tbl tbody tr:last-child { border-bottom: none; }
        .tbl tbody tr:hover { background: var(--slate-50); }

        .tbl td {
            padding: .85rem 1rem;
            font-size: .875rem;
            vertical-align: middle;
        }
        .tbl td:first-child { padding-left: 1.5rem; }
        .tbl td:last-child  { padding-right: 1.5rem; }

        .td-actions { text-align: right; white-space: nowrap; }

        /* ===== PILLS ===== */
        .pill {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .3rem .8rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .pill-you     { background: var(--slate-100); color: var(--navy); }
        .pill-shop    { background: var(--slate-100); color: var(--slate-600); }
        .pill-pending { background: var(--amber-pale); color: #92400e; }
        .pill-approved{ background: var(--emerald-pale); color: var(--emerald); }
        .pill-rejected{ background: var(--rose-pale); color: var(--rose); }
        .pill-submitted{ background: var(--sky-pale); color: var(--sky); }
        .pill-mixed   { background: var(--violet-pale); color: var(--violet); }
        .pill-stock   { background: var(--amber-pale); color: #92400e; }
        
        .pill::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: currentColor;
            opacity: .6;
            display: inline-block;
        }

        .req-id {
            font-family: 'Syne', sans-serif;
            font-size: .78rem;
            font-weight: 600;
            color: var(--slate-500);
        }

        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }
        .empty-state-icon { font-size: 3rem; color: var(--slate-300); margin-bottom: 1rem; }
        .empty-state h5 { font-size: 1rem; font-weight: 600; color: var(--slate-500); margin-bottom: .4rem; }
        .empty-state p  { font-size: .85rem; color: var(--slate-400); }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(11,30,61,0.6);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .loading-spinner {
            background: var(--white);
            padding: 2rem;
            border-radius: var(--radius);
            text-align: center;
        }
        
        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-color: var(--navy);
            border-right-color: transparent;
        }

        /* ===== PRINT ===== */
        @media print {
            body * { visibility: hidden; }
            .print-request-container, 
            .print-request-container * { visibility: visible; }
            .print-request-container {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: white;
                z-index: 10000;
                padding: 20px;
                margin: 0;
                overflow: auto;
            }
            .print-header { page-break-after: avoid; page-break-inside: avoid; }
            .print-table tr { page-break-inside: avoid; }
            .print-table thead { display: table-header-group; }
            .print-footer { page-break-before: avoid; }
            .no-print { display: none !important; }
            @page { margin: 1.5cm; size: A4; }
            body { margin: 0; padding: 0; }
        }

        .print-request-container {
            display: none;
            font-family: 'DM Sans', sans-serif;
            background: white;
        }

        .print-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--navy);
        }
        .print-title {
            font-size: 24px;
            font-weight: 700;
            font-family: 'Syne', sans-serif;
            margin-bottom: 8px;
            color: var(--navy);
        }
        .print-subtitle {
            color: var(--slate-500);
            font-size: 12px;
        }
        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .print-table th, .print-table td {
            border: 1px solid var(--slate-200);
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }
        .print-table th {
            background: var(--slate-100);
            font-weight: 600;
        }
        .print-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: var(--slate-400);
            padding-top: 15px;
            border-top: 1px solid var(--slate-200);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .stats-grid .stat-card:last-child { grid-column: span 2; }
        }
        @media (max-width: 600px) {
            .stats-grid { grid-template-columns: 1fr; margin: 1rem; }
            .stats-grid .stat-card:last-child { grid-column: auto; }
            .table-card, .toolbar { margin-left: 1rem; margin-right: 1rem; }
            .page-header { padding: 1rem; }
            .page-title { font-size: 1rem; }
            .filter-group { flex-wrap: wrap; padding: .5rem; }
            .filter-group label { font-size: .7rem; }
        }
    </style>
</head>
<body>

@php
    $isAdmin = Auth::check() && Auth::user()->levelStatus === 'Admin';
@endphp

@include("sidenav")
<main class="main-content">
  
  @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show d-flex justify-content-between" role="alert" style="margin: 0 2rem 1rem; border-radius: var(--radius);">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
  @endif
  
  @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show d-flex justify-content-between" role="alert" style="margin: 0 2rem 1rem; border-radius: var(--radius);">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
  @endif
  
        <div class="page-header">
            <div class="page-header-left">
                <a href="#" onclick="history.back()" class="back-btn">
                    <i class="bi bi-chevron-left"></i> {{ __('messages.back') }}
                </a>
                <span class="page-title">{{ __('messages.requested_items') }}</span>
            </div>
            <div class="header-actions">
                <a href="{{ url('itemRequest') }}" class="btn btn-outline">
                    <i class="bi bi-list-ul"></i> {{ __('messages.item_requests') }}
                </a>
                <a href="{{ url('itemRequest') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> {{ __('messages.new_request') }}
                </a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card stat-card-blue">
                <div>
                    <div class="stat-label">{{ __('messages.total_requests') }}</div>
                    <div class="stat-value">{{ number_format($totalRequest) }}</div>
                </div>
                <div class="stat-icon stat-icon-blue">
                    <i class="bi bi-inbox-fill"></i>
                </div>
            </div>
            <div class="stat-card stat-card-amber">
                <div>
                    <div class="stat-label">{{ __('messages.pending') }}</div>
                    <div class="stat-value">{{ number_format($totalPednding) }}</div>
                </div>
                <div class="stat-icon stat-icon-amber">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
            <div class="stat-card stat-card-emerald">
                <div>
                    <div class="stat-label">{{ __('messages.submitted') }}</div>
                    <div class="stat-value">{{ number_format($totalSub) }}</div>
                </div>
                <div class="stat-icon stat-icon-emerald">
                    <i class="bi bi-send-check-fill"></i>
                </div>
            </div>
        </div>

        <div class="toolbar">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="search" class="search-input" id="search-input" placeholder="{{ __('messages.search_by_request_id') }}">
            </div>
            
            <form method="GET" action="{{ url('viewRequest') }}" class="d-flex gap-2 flex-wrap align-items-center" id="filter-form" style="flex:2">
                <div class="filter-group">
                    <label><i class="bi bi-calendar3"></i> {{ __('messages.from') }}</label>
                    <input type="date" name="date_from" id="dateFrom" class="date-input" 
                           value="{{ request('date_from', date('Y-m-d')) }}" 
                           onchange="this.form.submit()">
                </div>
                
                <div class="filter-group">
                    <label><i class="bi bi-calendar3"></i> {{ __('messages.to') }}</label>
                    <input type="date" name="date_to" id="dateTo" class="date-input" 
                           value="{{ request('date_to', date('Y-m-d')) }}"
                           onchange="this.form.submit()">
                </div>
                
                <div class="filter-group">
                    <label><i class="bi bi-shop"></i> {{ __('messages.shop_label') }}</label>
                    <select name="shop" id="shopFilter" class="shop-select" onchange="this.form.submit()">
                        <option value="">{{ __('messages.all_shops') }}</option>
                        @foreach($shops ?? [] as $s)
                            <option value="{{ $s['id'] }}" {{ (request('shop', '')) == $s['id'] ? 'selected' : '' }}>{{ $s['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary btn-sm" id="apply-filters">
                    <i class="bi bi-funnel"></i> {{ __('messages.apply') }}
                </button>
                
                @if(request('date_from') || request('date_to') || request('shop'))
                <a href="{{ url('viewRequest') }}" class="btn btn-outline btn-sm" id="clear-filters">
                    <i class="bi bi-x-circle"></i> {{ __('messages.clear_all') }}
                </a>
                @endif
            </form>
        </div>
        
        @if(request('date_from') || request('date_to') || request('shop'))
        <div style="margin: -0.5rem 2rem 1rem 2rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
            @if(request('date_from') && request('date_to'))
                @if(request('date_from') == request('date_to'))
                <div class="date-range-badge">
                    <i class="bi bi-calendar-check"></i> 
                    {{ date('M d, Y', strtotime(request('date_from'))) }}
                    <i class="bi bi-x-circle-fill" onclick="clearDateFilter()" style="cursor:pointer"></i>
                </div>
                @else
                <div class="date-range-badge">
                    <i class="bi bi-calendar-range"></i> 
                    {{ date('M d', strtotime(request('date_from'))) }} - {{ date('M d, Y', strtotime(request('date_to'))) }}
                    <i class="bi bi-x-circle-fill" onclick="clearDateFilter()" style="cursor:pointer"></i>
                </div>
                @endif
            @elseif(request('date_from'))
                <div class="date-range-badge">
                    <i class="bi bi-calendar"></i> From {{ date('M d, Y', strtotime(request('date_from'))) }}
                    <i class="bi bi-x-circle-fill" onclick="clearDateFilter()" style="cursor:pointer"></i>
                </div>
            @elseif(request('date_to'))
                <div class="date-range-badge">
                    <i class="bi bi-calendar"></i> Until {{ date('M d, Y', strtotime(request('date_to'))) }}
                    <i class="bi bi-x-circle-fill" onclick="clearDateFilter()" style="cursor:pointer"></i>
                </div>
            @endif
            
            @if(request('shop'))
                @php $selectedShop = collect($shops)->firstWhere('id', request('shop')); @endphp
                <div class="date-range-badge">
                    <i class="bi bi-shop"></i> {{ $selectedShop->name ?? 'Shop' }}
                    <i class="bi bi-x-circle-fill" onclick="clearShopFilter()" style="cursor:pointer"></i>
                </div>
            @endif
        </div>
        @endif

        <div class="table-card">
            <div class="table-responsive">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.request_id') }}</th>
                            <th><i class="bi bi-arrow-right-circle me-1"></i>{{ __('messages.from_shop') }}</th>
                            <th><i class="bi bi-arrow-left-circle me-1"></i>{{ __('messages.to_shop') }}</th>
                            <th>{{ __('messages.items') }}</th>
                            <th>{{ __('messages.total') }}</th>
                            <th>{{ __('messages.payment') }}</th>
                            <th>{{ __('messages.assigned_to') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="request-table-body">

                        @if(empty($groupedRequests) || count($groupedRequests) == 0)
                        <tr>
                            <td colspan="13">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                                    <h5>{{ __('messages.no_requests_found') }}</h5>
                                    <p>
                                        @if(request('date_from') || request('date_to') || request('shop'))
                                            {{ __('messages.no_requests_match_filters') }}
                                        @else
                                            {{ __('messages.no_item_requests_yet') }}
                                        @endif
                                    </p>
                                    @if(request('date_from') || request('date_to') || request('shop'))
                                    <a href="{{ url('viewRequest') }}" class="btn btn-primary mt-3">
                                        <i class="bi bi-eraser"></i> {{ __('messages.clear_all_filters') }}
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @else
                            @php $index = 1; @endphp
                            @foreach ($groupedRequests as $requestId => $items)
                                @php
                                    $requesterAccount = $items[0]->account ?? '';
                                    $supplierAccount  = $items[0]->supplierId ?? '';

                                    $iAmRequester = (getCurrentShopId() === (int)$requesterAccount);
                                    $iAmReceiver  = (getCurrentShopId() === (int)$supplierAccount);

                                    $requesterAccountName = DB::table('accounts')->where('id', $requesterAccount)->value('name');
                                    $supplierAccountName = DB::table('accounts')->where('id', $supplierAccount)->value('name');

                                    $totalQuantity = 0;
                                    $totalPrice    = 0;
                                    foreach ($items as $item) {
                                        $totalQuantity += $item->quantity;
                                        $totalPrice    += $item->quantity * $item->price;
                                    }

                                    $requestDate          = $items[0]->created_at ?? now();
                                    $requestDateFormatted = date('Y-m-d', strtotime($requestDate));

                                    $statuses = array_unique(array_column($items->toArray(), 'status'));
                                    if (count($statuses) === 1) {
                                        $overallStatus = $statuses[0];
                                    } elseif (in_array('Pending', $statuses)) {
                                        $overallStatus = 'Pending';
                                    } elseif (in_array('Approved', $statuses)) {
                                        $overallStatus = 'Approved';
                                    } else {
                                        $overallStatus = 'Mixed';
                                    }
                                    
                                    $itemsJson = [];
                                    foreach ($items as $item) {
                                        $productId = $item->productId;
                                        $productName = 'Unknown Product';
                                        
                                        if (!empty($productId)) {
                                            $product = DB::table('products')->where('id', $productId)->first();
                                            if (!$product) {
                                                $product = DB::table('products')->where('product_id', $productId)->first();
                                            }
                                            if ($product) {
                                                $productName = $product->name ?? $product->name01 ?? $product->product_name ?? 'Unknown Product';
                                            }
                                        }
                                        
                                        $itemsJson[] = [
                                            'productId' => $productId,
                                            'productName' => $productName,
                                            'quantity' => (int)$item->quantity,
                                            'price' => (float)$item->price,
                                            'status' => $item->status,
                                            'payment_type' => $item->payment_type ?? 'cash'
                                        ];
                                    }
                                @endphp

                                <tr class="request-row" data-date="{{ $requestDateFormatted }}" data-search="{{ $requestId }} {{ $requesterAccountName }} {{ $supplierAccountName }} {{ $overallStatus }}"
                                    data-request-id="{{ $requestId }}"
                                    data-requester="{{ $requesterAccountName }}"
                                    data-supplier="{{ $supplierAccountName }}"
                                    data-date-full="{{ date('M d, Y', strtotime($requestDate)) }}"
                                    data-total-items="{{ count($items) }}"
                                    data-total-qty="{{ $totalQuantity }}"
                                    data-total-price="{{ $totalPrice }}"
                                    data-payment-type="{{ $items[0]->payment_type ?? 'cash' }}"
                                    data-overall-status="{{ $overallStatus }}"
                                    data-items-json='{{ json_encode($itemsJson) }}'>
                                    <td style="color:var(--slate-400); font-size:.8rem;">{{ $index++ }}</td>
                                    <td style="white-space:nowrap; font-size:.83rem;">
                                        {{ date('M d, Y', strtotime($requestDate)) }}
                                    </td>
                                    <td><span class="req-id">{{ $requestId }}</span></td>

                                    <td>
                                        @if($iAmRequester)
                                            <span class="pill pill-you"><i class="bi bi-person-fill"></i> {{ __('messages.you') }}</span>
                                        @else
                                            <span class="pill pill-shop">{{ $requesterAccountName ?: __('messages.na') }}</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($iAmReceiver)
                                            <span class="pill pill-you"><i class="bi bi-person-fill"></i> {{ __('messages.you') }}</span>
                                        @elseif($supplierAccount)
                                            <span class="pill pill-shop">{{ $supplierAccountName }}</span>
                                        @else
                                            <span style="color:var(--slate-400); font-size:.8rem;">—</span>
                                        @endif
                                    </td>

                                    <td><strong>{{ count($items) }}</strong></td>
                                    <td style="white-space:nowrap;">Tsh {{ number_format($totalPrice) }}</td>
                                    <td>
                                        <span class="pill {{ $items[0]->payment_type === 'cash' ? 'pill-approved' : 'pill-submitted' }}">
                                            {{ $items[0]->payment_type ? ucfirst($items[0]->payment_type) : 'Cash' }}
                                        </span>
                                    </td>
                                    <td>{{ $items[0]->assignedToName ?? ($items[0]->assigned_to ?? 'N/A') }}</td>

                                    <td>
                                        @php
                                            $pillMap = [
                                                'Pending'      => 'pill-pending',
                                                'Approved'     => 'pill-approved',
                                                'Rejected'     => 'pill-rejected',
                                                'Submitted'    => 'pill-submitted',
                                                'Out of Stock' => 'pill-stock',
                                                'Alert'        => 'pill-mixed',
                                            ];
                                            $pillClass = $pillMap[$overallStatus] ?? 'pill-mixed';
                                        @endphp
                                        <span class="pill {{ $pillClass }}">{{ $overallStatus }}</span>
                                    </td>
                                    
                                    <td class="td-actions">
                                        @if (canUser('print_item_request'))
                                      <button type="button" class="btn btn-print btn-sm" onclick="printRequestFromRow(this)" title="{{ __('messages.print_request') }}">
                                            <i class="bi bi-printer"></i>
                                        </button>  
                                    @endif
                                        
                                        <a href="{{ url('/viewRequestDetails/' . $requestId) }}" class="btn btn-ghost btn-sm" title="{{ __('messages.view_request') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if($isAdmin && ($overallStatus == 'Submitted' || $overallStatus == 'Approved'))
                                        <form method="post" class="d-inline" action="{{ route('request.redoRequest') }}" onsubmit="return confirm('{{ __('messages.redo_request_confirm') }}')">
                                                @csrf
                                                <input type="hidden" name="requestName" value="{{ $requestId }}">
                                                <button type="submit" title="{{ __('messages.redo_request') }}" class="btn btn-warning btn-sm">
                                                    <i class="bi bi-arrow-clockwise"></i> 
                                                </button>
                                            </form>
                                        @endif

                                        @if(canUser('manage_item_request') && $overallStatus == 'Submitted' && $overallStatus !== 'Approved')
                                            <form method="post" class="d-inline" action="{{ route('request.approveAll') }}" onsubmit="return confirm('{{ __('messages.approve_all_confirm') }}')">
                                                @csrf
                                                <input type="hidden" name="requestName" value="{{ $requestId }}">
                                                <input type="hidden" name="supplierId" value="{{ $supplierAccount }}">
                                                <button type="submit" name="status" value="approve" class="btn btn-success btn-sm" title="{{ __('messages.approve_request') }}">
                                                    <i class="bi bi-check2-all"></i> 
                                                </button>
                                            </form>
                                        @endif

                                        @if(canUser('manage_item_request') && $overallStatus == 'Submitted' && $overallStatus !== 'Approved')
                                            <form method="post" class="d-inline" action="{{ route('request.approveAll') }}" onsubmit="return confirm('{{ __('messages.reject_all_confirm') }}')">
                                                @csrf
                                                <input type="hidden" name="requestName" value="{{ $requestId }}">
                                                <input type="hidden" name="supplierId" value="{{ $supplierAccount }}">
                                                <button type="submit" name="status" value="reject" class="btn btn-danger btn-sm" title="{{ __('messages.reject_request') }}">
                                                    <i class="bi bi-x-lg"></i> 
                                                </button>
                                            </form>
                                        @endif

                                        @if(canUser('delete_item_request'))
                                            <form method="post" class="d-inline" action="{{ route('request.delete') }}" onsubmit="return confirm('{{ __('messages.delete_request_confirm') }}');">
                                                @csrf
                                                <input type="hidden" name="requestName" value="{{ $requestId }}">
                                                <button type="submit" title="{{ __('messages.delete_request') }}" class="btn btn-sm" style="background: var(--rose-pale); color: var(--rose); border: none; border-radius: var(--radius); padding: .35rem .75rem;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>
        </div>

    </main>

<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 mb-0">{{ __('messages.filtering_requests') }}</p>
    </div>
</div>

<div id="printContainer" class="print-request-container"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const printTranslations = {
    title: '{{ __('messages.item_request_page_title') }}',
    requestId: '{{ __('messages.request_id') }}',
    requestDate: '{{ __('messages.date') }}',
    fromShop: '{{ __('messages.from_shop') }}',
    toShop: '{{ __('messages.to_shop') }}',
    status: '{{ __('messages.status') }}',
    totalItems: '{{ __('messages.total_items') }}',
    totalQuantity: '{{ __('messages.total_quantity') }}',
    paymentType: '{{ __('messages.payment_type') }}',
    grandTotal: '{{ __('messages.grand_total') }}',
    productName: '{{ __('messages.col_product') }}',
    qty: '{{ __('messages.col_qty') }}',
    unitPrice: '{{ __('messages.col_unit_price') }}',
    total: '{{ __('messages.total') }}',
    itemStatus: '{{ __('messages.item_status') }}'
};
</script>
<script>
$(document).ready(function () {
    $('#search-input').on('input', function () {
        var searchTerm = $(this).val().toLowerCase().trim();
        $('.request-row').each(function () {
            var searchText = $(this).data('search')?.toLowerCase() || '';
            var visible = searchTerm === '' || searchText.includes(searchTerm);
            $(this).toggle(visible);
        });
    });
    
    $('#dateFrom, #dateTo, #shopFilter').on('change', function() {
        $('#loadingOverlay').show();
        $('#filter-form').submit();
    });
    
    $('#apply-filters').on('click', function(e) {
        $('#loadingOverlay').show();
    });
    
    window.clearDateFilter = function() {
        $('#dateFrom').val('');
        $('#dateTo').val('');
        $('#loadingOverlay').show();
        $('#filter-form').submit();
    };
    
    window.clearShopFilter = function() {
        $('#shopFilter').val('');
        $('#loadingOverlay').show();
        $('#filter-form').submit();
    };
    
    $(window).on('load', function() {
        setTimeout(function() {
            $('#loadingOverlay').fadeOut();
        }, 500);
    });
});

function printRequestFromRow(button) {
    var row = $(button).closest('.request-row');
    
    var requestId = row.data('request-id');
    var requester = row.data('requester') || 'N/A';
    var supplier = row.data('supplier') || 'N/A';
    var requestDate = row.data('date-full') || new Date().toLocaleDateString();
    var totalItems = row.data('total-items') || 0;
    var totalQty = row.data('total-qty') || 0;
    var totalPrice = row.data('total-price') || 0;
    var paymentType = row.data('payment-type') || 'cash';
    var overallStatus = row.data('overall-status') || 'Pending';
    
    var itemsJson = row.data('items-json');
    var items = [];
    
    if (itemsJson) {
        try {
            items = typeof itemsJson === 'string' ? JSON.parse(itemsJson) : itemsJson;
        } catch(e) {
            console.error('Failed to parse items:', e);
            items = [];
        }
    }
    
    var printHtml = buildPrintHTML(requestId, requester, supplier, requestDate, 
                                  totalItems, totalQty, totalPrice, paymentType, 
                                  overallStatus, items);
    
    var printContainer = document.getElementById('printContainer');
    printContainer.innerHTML = printHtml;
    printContainer.style.display = 'block';
    
    window.print();
    
    setTimeout(function() {
        printContainer.style.display = 'none';
        printContainer.innerHTML = '';
    }, 1000);
}

function buildPrintHTML(requestId, requester, supplier, requestDate, totalItems, totalQty, totalPrice, paymentType, overallStatus, items) {
    var totalPriceNum = parseFloat(String(totalPrice).replace(/[^0-9.-]/g, '')) || 0;
    
    var itemsHtml = '';
    if (items && items.length > 0) {
        for (var i = 0; i < items.length; i++) {
            var item = items[i];
            var itemTotal = (parseFloat(item.quantity) || 0) * (parseFloat(item.price) || 0);
            itemsHtml += `
                <tr>
                    <td style="border: 1px solid #e5e9f2; padding: 8px;">${i + 1}</td>
                    <td style="border: 1px solid #e5e9f2; padding: 8px;">
                        <strong>${escapeHtml(item.productName || 'Unknown Product')}</strong>
                    </td>
                    <td style="border: 1px solid #e5e9f2; padding: 8px; text-align: center;">${Number(item.quantity).toLocaleString()}</td>
                    <td style="border: 1px solid #e5e9f2; padding: 8px; text-align: right;">Tsh ${Number(item.price || 0).toLocaleString()}</td>
                    <td style="border: 1px solid #e5e9f2; padding: 8px; text-align: right;">Tsh ${itemTotal.toLocaleString()}</td>
                    <td style="border: 1px solid #e5e9f2; padding: 8px;">${escapeHtml(item.status || 'Pending')}</td>
                </tr>
            `;
        }
    } else {
        itemsHtml = `
            <tr><td colspan="6" style="text-align: center; padding: 20px;">
                No items found in this request.
            </td></tr>
        `;
    }
    
    var statusColor = overallStatus === 'Approved' ? '#059669' : (overallStatus === 'Pending' ? '#92400e' : '#6b7280');
    
    return `
        <div class="print-request-container" style="display:block; background: white; padding: 20px;">
            <div class="print-header">
                <div class="print-title">{{ config("app.name") }} - ${printTranslations.title}</div>
                <div class="print-subtitle">${printTranslations.requestId}: ${escapeHtml(requestId)} | Printed: ${new Date().toLocaleString()}</div>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                <div><strong>${printTranslations.requestDate}:</strong> ${escapeHtml(requestDate)}</div>
                <div><strong>${printTranslations.fromShop}:</strong> ${escapeHtml(requester)}</div>
                <div><strong>${printTranslations.toShop}:</strong> ${escapeHtml(supplier)}</div>
                <div><strong>${printTranslations.status}:</strong> <span style="color: ${statusColor}; font-weight: bold;">${escapeHtml(overallStatus)}</span></div>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 20px; background: #f4f6fb; padding: 15px; border-radius: 8px; flex-wrap: wrap; gap: 10px;">
                <div><strong>${printTranslations.totalItems}:</strong> ${Number(totalItems).toLocaleString()}</div>
                <div><strong>${printTranslations.totalQuantity}:</strong> ${Number(totalQty).toLocaleString()}</div>
                <div><strong>${printTranslations.paymentType}:</strong> ${escapeHtml(paymentType).toUpperCase()}</div>
                <div><strong>${printTranslations.grandTotal}:</strong> <span style="color: #059669; font-size: 1.1em;">Tsh ${totalPriceNum.toLocaleString()}</span></div>
            </div>
            
            <table class="print-table" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead>
                    <tr style="background: #f4f6fb;">
                        <th style="border: 1px solid #e5e9f2; padding: 10px; text-align: left; width: 5%;">#</th>
                        <th style="border: 1px solid #e5e9f2; padding: 10px; text-align: left; width: 45%;">${printTranslations.productName}</th>
                        <th style="border: 1px solid #e5e9f2; padding: 10px; text-align: center; width: 10%;">${printTranslations.qty}</th>
                        <th style="border: 1px solid #e5e9f2; padding: 10px; text-align: right; width: 15%;">${printTranslations.unitPrice}</th>
                        <th style="border: 1px solid #e5e9f2; padding: 10px; text-align: right; width: 15%;">${printTranslations.total}</th>
                        <th style="border: 1px solid #e5e9f2; padding: 10px; text-align: left; width: 10%;">${printTranslations.itemStatus}</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHtml}
                </tbody>
            </table>
            
            <div class="print-footer">
                Generated by LERUMA POS System | ${new Date().toLocaleString()}
            </div>
        </div>
    `;
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}
</script>
@include('footer')

</body>
</html>