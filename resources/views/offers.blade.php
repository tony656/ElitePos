<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.offered_products_report')</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
            --shadow-sm: 0 1px 3px rgba(11,30,61,.06);
            --shadow: 0 4px 20px rgba(11,30,61,.08);
            --shadow-lg: 0 10px 40px rgba(11,30,61,.12);
            --shadow-xl: 0 20px 60px rgba(11,30,61,.15);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--slate-50);
            color: var(--slate-800);
        }

        .main-content {
            padding: 1.5rem;
        }

        /* ══ CARD ══ */
        .card-modern {
            background: var(--white);
            border: none;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }
        
        .card-modern:hover {
            box-shadow: var(--shadow-lg);
        }
        
        .card-header-modern {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
            color: var(--white);
            padding: 20px 28px;
            border-bottom: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .card-header-modern h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: -0.3px;
        }
        
        .card-header-modern h5 i {
            color: var(--amber);
            margin-right: 10px;
        }
        
        .card-body-modern {
            padding: 28px;
        }

        /* ══ STATS GRID ══ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }
        
        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 20px 24px;
            border: 1px solid var(--slate-200);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            cursor: default;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }
        
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
        }
        
        .stat-card.amber::before { background: linear-gradient(90deg, var(--amber), #d97706); }
        .stat-card.emerald::before { background: linear-gradient(90deg, var(--emerald), #047857); }
        .stat-card.violet::before { background: linear-gradient(90deg, var(--violet), #6d28d9); }
        .stat-card.sky::before { background: linear-gradient(90deg, var(--sky), #0369a1); }
        
        .stat-card .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(-5deg);
        }
        
        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--navy);
            line-height: 1.2;
            letter-spacing: -0.5px;
        }
        
        .stat-card .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--slate-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-card .stat-trend {
            margin-top: 8px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 10px;
            border-radius: 20px;
        }
        
        .stat-trend.up { color: var(--emerald); background: var(--emerald-pale); }
        .stat-trend.down { color: var(--rose); background: var(--rose-pale); }
        .stat-trend.neutral { color: var(--slate-500); background: var(--slate-100); }

        /* ══ FILTER SECTION ══ */
        .filter-section {
            background: var(--slate-50);
            padding: 20px 24px;
            border-radius: var(--radius);
            margin-bottom: 24px;
            border: 1px solid var(--slate-200);
        }
        
        .filter-section .form-label {
            font-weight: 600;
            color: var(--slate-600);
            font-size: 0.8rem;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        
        .filter-section .form-control {
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            padding: 9px 14px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .filter-section .form-control:focus {
            border-color: var(--amber);
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }

        /* ══ BUTTONS ══ */
        .btn-primary-custom {
            background: var(--amber);
            border: none;
            color: var(--navy);
            font-weight: 700;
            padding: 9px 24px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-primary-custom:hover {
            background: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
            color: var(--navy);
        }
        
        .btn-outline-secondary-custom {
            border: 1.5px solid var(--slate-300);
            color: var(--slate-600);
            padding: 9px 24px;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: transparent;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-outline-secondary-custom:hover {
            background: var(--slate-100);
            border-color: var(--slate-400);
        }

        .btn-light-custom {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            color: var(--white);
            padding: 8px 18px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }
        
        .btn-light-custom:hover {
            background: rgba(255,255,255,0.25);
            color: var(--white);
        }

        /* ══ TABLE ══ */
        .table-modern {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        
        .table-modern thead {
            background: var(--navy);
        }
        
        .table-modern thead th {
            padding: 14px 18px;
            text-align: left;
            color: rgba(255,255,255,0.8);
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        
        .table-modern tbody td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--slate-100);
            color: var(--slate-700);
            vertical-align: middle;
        }
        
        .table-modern tbody tr {
            transition: background 0.2s ease;
        }
        
        .table-modern tbody tr:hover {
            background: var(--slate-50);
        }
        
        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        /* ══ BADGES ══ */
        .offer-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--amber-pale);
            color: #92400e;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .badge-active {
            background: var(--emerald-pale);
            color: var(--emerald);
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        
        .badge-active:hover {
            transform: scale(1.05);
        }
        
        .badge-inactive {
            background: var(--rose-pale);
            color: var(--rose);
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        
        .badge-inactive:hover {
            transform: scale(1.05);
        }

        /* ══ ACTION BUTTONS ══ */
        .action-btns {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }
        
        .btn-edit {
            background: var(--sky-pale);
            color: var(--sky);
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .btn-edit:hover {
            background: var(--sky);
            color: var(--white);
            transform: translateY(-1px);
        }
        
        .btn-delete {
            background: var(--rose-pale);
            color: var(--rose);
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .btn-delete:hover {
            background: var(--rose);
            color: var(--white);
            transform: translateY(-1px);
        }

        /* ══ EMPTY STATE ══ */
        .empty-state {
            padding: 80px 20px;
            text-align: center;
        }
        
        .empty-state i {
            font-size: 4.5rem;
            color: var(--slate-300);
            margin-bottom: 16px;
        }
        
        .empty-state h5 {
            font-size: 1.1rem;
            color: var(--slate-600);
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .empty-state p {
            color: var(--slate-400);
            font-size: 0.9rem;
        }

        /* ══ MODAL ══ */
        .modal-content {
            border-radius: var(--radius);
            border: none;
            box-shadow: var(--shadow-xl);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
            color: var(--white);
            border-bottom: none;
            padding: 18px 24px;
        }
        
        .modal-header .modal-title {
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .modal-header .modal-title i {
            color: var(--amber);
            margin-right: 8px;
        }
        
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.5;
        }
        
        .modal-header .btn-close:hover {
            opacity: 1;
        }
        
        .modal-body {
            padding: 28px;
        }
        
        .modal-footer {
            border-top: 1px solid var(--slate-200);
            padding: 16px 24px;
        }
        
        .modal .form-label {
            font-weight: 600;
            color: var(--slate-600);
            font-size: 0.8rem;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        
        .modal .form-control {
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            padding: 9px 14px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .modal .form-control:focus {
            border-color: var(--amber);
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }
        
        .modal .form-check-input:checked {
            background-color: var(--amber);
            border-color: var(--amber);
        }
        
        .modal .form-check-input:focus {
            border-color: var(--amber);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
        }

        .modal .form-check-label {
            color: var(--slate-600);
            font-weight: 500;
        }

        /* ══ OFFER PRODUCT SEARCH ══ */
        .offer-search-wrap {
            position: relative;
        }

        .offer-search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1050;
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            margin-top: 4px;
            max-height: 220px;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
        }

        .offer-search-item {
            padding: 10px 14px;
            cursor: pointer;
            border-bottom: 1px solid var(--slate-100);
            transition: background 0.15s;
            font-size: 0.9rem;
        }

        .offer-search-item:last-child {
            border-bottom: none;
        }

        .offer-search-item:hover {
            background: var(--slate-50);
        }

        .offer-search-item-name {
            font-weight: 600;
            color: var(--slate-800);
            font-size: 0.875rem;
        }

        .offer-search-item-stock {
            font-size: 0.75rem;
            color: var(--slate-400);
            margin-top: 2px;
        }

        /* ══ ALERTS ══ */
        .alert {
            border: none;
            border-radius: 8px;
            padding: 12px 16px;
            font-weight: 500;
        }
        
        .alert-success {
            background: var(--emerald-pale);
            color: var(--emerald);
            border-left: 4px solid var(--emerald);
        }
        
        .alert-danger {
            background: var(--rose-pale);
            color: var(--rose);
            border-left: 4px solid var(--rose);
        }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }
            
            .card-body-modern {
                padding: 16px;
            }
            
            .card-header-modern {
                padding: 16px 20px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .stat-card .stat-value {
                font-size: 1.5rem;
            }
            
            .card-header-modern {
                flex-direction: column;
                text-align: center;
            }
            
            .filter-section .row {
                flex-direction: column;
            }
            
            .modal-body {
                padding: 16px;
            }
            
            .action-btns {
                flex-direction: column;
                gap: 4px;
            }
            
            .table-modern {
                font-size: 0.8rem;
            }
            
            .table-modern thead th,
            .table-modern tbody td {
                padding: 10px 12px;
            }
            
            .btn-light-custom {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    @include("sidenav")

    <main class="main-content">
        <div class="card-modern">
            <!-- Header -->
            <div class="card-header-modern">
                <h5><i class="bi bi-gift"></i> @lang('messages.offered_products_report')</h5>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <button type="button" class="btn-light-custom" data-bs-toggle="modal" data-bs-target="#createOfferModal">
                        <i class="bi bi-plus-circle"></i> @lang('messages.create_offer')
                    </button>
                    <a href="{{ url('shopReport') }}" class="btn-light-custom">
                        <i class="bi bi-arrow-left"></i> @lang('messages.back_to_reports')
                    </a>
                </div>
            </div>
            
            <div class="card-body-modern">
                <!-- Filter Section -->
                <div class="filter-section">
                    <form method="GET" action="{{ url('offers') }}" class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.products_select_shop')</label>
                            <select name="shop" id="offerShopSelect" class="form-control" onchange="this.form.submit()">
                                <option value="">-- @lang('messages.products_select_shop') --</option>
                                @foreach($accounts as $shop)
                                    <option value="{{ $shop['id'] }}" {{ $shop['id'] == getCurrentShopId() ? 'selected' : '' }}>{{ $shop['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <button type="submit" class="btn-primary-custom">
                                    <i class="bi bi-filter"></i> @lang('messages.filter')
                                </button>
                                <a href="{{ url('offers') }}" class="btn-outline-secondary-custom">
                                    <i class="bi bi-arrow-counterclockwise"></i> @lang('messages.reset')
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                @if(isset($items) && count($items) > 0 || isset($offers) && count($offers) > 0)
                    <div class="table-responsive">
                        <table class="table-modern" id="offersTable">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 20%;">@lang('messages.item_to_buy')</th>
                                    <th style="width: 20%;">@lang('messages.item_to_give_free')</th>
                                    <th style="width: 12%;">@lang('messages.products_required_quantity_buy')</th>
                                    <th style="width: 12%;">@lang('messages.products_offer_quantity_get_free')</th>
                                    <th style="width: 12%;">@lang('messages.status')</th>
                                    <th style="width: 19%;">@lang('messages.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $offersList = isset($items) ? $items : (isset($offers) ? $offers : collect());
                                @endphp
                                @foreach($offersList as $item)
                                @php
                                    $firstReqItem = $item->requiredItems->first();
                                    $firstReqQty = $firstReqItem ? $firstReqItem->required_quantity : 1;
                                    $firstReqProductId = $firstReqItem ? $firstReqItem->product_id : $item->product_id;
                                @endphp
                                <tr data-id="{{ $item->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $products[$firstReqProductId] ?? __('messages.unknown_product') }}</strong>
                                        <div style="font-size: 0.7rem; color: var(--slate-400);">ID: {{ $firstReqProductId }}</div>
                                    </td>
                                    <td>
                                        {{ $products[$item->offer_product_id] ?? __('messages.unknown_product') }}
                                        <div style="font-size: 0.7rem; color: var(--slate-400);">ID: {{ $item->offer_product_id }}</div>
                                    </td>
                                    <td>
                                        <span class="offer-badge">
                                            <i class="bi bi-gift"></i> {{ number_format($firstReqQty) }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($item->offer_quantity) }}</td>
                                    <td>
                                        <button class="status-toggle" data-active="{{ $item->is_active ? '1' : '0' }}" data-id="{{ $item->id }}" title="@lang('messages.products_offer_active')">
                                            @if($item->is_active)
                                                <span class="badge-active"><i class="bi bi-check-circle-fill"></i> @lang('messages.active')</span>
                                            @else
                                                <span class="badge-inactive"><i class="bi bi-x-circle-fill"></i> @lang('messages.inactive')</span>
                                            @endif
                                        </button>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="btn-edit edit-btn"
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
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button class="btn-delete delete-btn" data-id="{{ $item->id }}">
                                                <i class="bi bi-trash"></i> @lang('messages.delete')
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
                        <i class="bi bi-gift"></i>
                        <h5>@lang('messages.no_offered_products_found')</h5>
                        <p>@lang('messages.try_adjusting_date_range')</p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Create Offer Modal -->
    <div class="modal fade" id="createOfferModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle"></i> @lang('messages.create_offer')
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('saveOffer') }}" id="createOfferForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">@lang('messages.products_select_shop')</label>
                                <select name="account" id="offerShopSelectCreate" class="form-control" required>
                                    <option value="">-- @lang('messages.products_select_shop') --</option>
                                    @foreach($accounts as $shop)
                                        <option value="{{ $shop['id'] }}">{{ $shop['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 offer-search-wrap">
                                <label class="form-label">@lang('messages.item_to_give_free')</label>
                                <input type="text" id="freeProductSearch" class="form-control" placeholder="@lang('messages.type_product_name')" autocomplete="off">
                                <input type="hidden" name="offer_product_id" id="freeProductId">
                                <div id="freeSearchResults" class="offer-search-results" style="display:none;"></div>
                            </div>
                            </div>
                            <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">@lang('messages.products_offer_quantity_get_free')</label>
                                <input type="number" name="offer_quantity" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check" style="padding-bottom:0.5rem;">
                                    <input type="checkbox" name="is_active" id="isActiveOffer" checked class="form-check-input">
                                    <label class="form-check-label" for="isActiveOffer">@lang('messages.products_offer_active')</label>
                                </div>
                            </div>
                            </div>
                            <div class="row g-3">
                            <div class="col-md-12">
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:0.5rem;margin-bottom:0.5rem;">
                                    <label class="form-label" style="margin-bottom:0;font-weight:700;">Required Items</label>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="addRequiredItemBtn" style="border-radius:8px;font-weight:600;">
                                        <i class="bi bi-plus-lg"></i> Add
                                    </button>
                                </div>
                                <div id="requiredItemsList">
                                    <div class="required-item-row" style="display:grid;grid-template-columns:1fr 100px 32px;gap:0.5rem;align-items:end;margin-bottom:0.5rem;">
                                        <div class="offer-search-wrap">
                                            <label class="form-label" style="font-size:0.75rem;font-weight:700;color:var(--slate-600);text-transform:uppercase;letter-spacing:0.05em;">@lang('messages.item_to_buy')</label>
                                            <input type="text" class="form-control req-item-search" placeholder="@lang('messages.type_product_name')" autocomplete="off">
                                            <input type="hidden" name="required_items[0][product_id]" class="req-item-id">
                                            <div class="offer-search-results req-item-results" style="display:none;"></div>
                                        </div>
                                        <div>
                                            <label class="form-label" style="font-size:0.75rem;font-weight:700;color:var(--slate-600);text-transform:uppercase;letter-spacing:0.05em;">@lang('messages.products_required_quantity_buy')</label>
                                            <input type="number" name="required_items[0][required_quantity]" class="form-control req-item-qty" value="1" min="1" required>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-req-item-btn" style="border-radius:8px;display:none;" title="Remove">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top:0.75rem;">
                                <button type="submit" class="btn btn-primary w-100" style="border-radius:8px;font-weight:600;">
                                    <i class="bi bi-check-lg"></i> @lang('messages.products_save_offer')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Offer Modal -->
    <div class="modal fade" id="editOfferModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square"></i> Edit Offer
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="editOfferMessage" style="margin-bottom: 1rem;"></div>
                    <form method="POST" action="" id="editOfferForm">
                        @csrf
                        @method('PATCH')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">@lang('messages.products_select_shop')</label>
                                <select name="account" id="editOfferShopSelect" class="form-control" required>
                                    @foreach($accounts as $shop)
                                        <option value="{{ $shop['id'] }}">{{ $shop['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 offer-search-wrap">
                                <label class="form-label">@lang('messages.item_to_give_free')</label>
                                <input type="text" id="editFreeProductSearch" class="form-control" placeholder="@lang('messages.type_product_name')" autocomplete="off">
                                <input type="hidden" name="offer_product_id" id="editFreeProductId">
                                <div id="editFreeSearchResults" class="offer-search-results" style="display:none;"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">@lang('messages.products_offer_quantity_get_free')</label>
                                <input type="number" name="offer_quantity" id="editOfferQuantity" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="form-check" style="padding-bottom:0.5rem;">
                                    <input type="checkbox" name="is_active" id="editIsActiveOffer" class="form-check-input">
                                    <label class="form-check-label" for="editIsActiveOffer">@lang('messages.products_offer_active')</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:0.5rem;margin-bottom:0.5rem;">
                                    <label class="form-label" style="margin-bottom:0;font-weight:700;">Required Items</label>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="editAddRequiredItemBtn" style="border-radius:8px;font-weight:600;">
                                        <i class="bi bi-plus-lg"></i> Add
                                    </button>
                                </div>
                                <div id="editRequiredItemsList">
                                    <div class="required-item-row" style="display:grid;grid-template-columns:1fr 100px 32px;gap:0.5rem;align-items:end;margin-bottom:0.5rem;">
                                        <div class="offer-search-wrap">
                                            <label class="form-label" style="font-size:0.75rem;font-weight:700;color:var(--slate-600);text-transform:uppercase;letter-spacing:0.05em;">Item to Buy</label>
                                            <input type="text" class="form-control edit-req-item-search" placeholder="Type product name" autocomplete="off">
                                            <input type="hidden" name="required_items[0][product_id]" class="edit-req-item-id">
                                            <div class="offer-search-results edit-req-item-results" style="display:none;"></div>
                                        </div>
                                        <div>
                                            <label class="form-label" style="font-size:0.75rem;font-weight:700;color:var(--slate-600);text-transform:uppercase;letter-spacing:0.05em;">Qty</label>
                                            <input type="number" name="required_items[0][required_quantity]" class="form-control edit-req-item-qty" value="1" min="1" required>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-danger edit-remove-req-item-btn" style="border-radius:8px;display:none;" title="Remove">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top:0.75rem;">
                                <button type="submit" class="btn btn-primary w-100" style="border-radius:8px;font-weight:600;">
                                    <i class="bi bi-check-lg"></i> Update Offer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
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
                    <p style="font-size: 1rem; margin-bottom: 0;">
                        @lang('messages.products_are_you_sure_delete_offer')
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline-secondary-custom" data-bs-dismiss="modal">@lang('messages.cancel')</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteOfferBtn" style="padding: 9px 24px; border-radius: 8px; font-weight: 600;">
                        <i class="bi bi-trash"></i> @lang('messages.delete')
                    </button>
                </div>
            </div>
        </div>
    </div>

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
        setupOfferSearch('editFreeProductSearch', 'editFreeSearchResults', 'editFreeProductId');

        let reqItemCount = 1;
        const reqItemsList = document.getElementById('requiredItemsList');
        const addReqItemBtn = document.getElementById('addRequiredItemBtn');

        function createRequiredItemRow(index) {
            const div = document.createElement('div');
            div.className = 'required-item-row';
            div.style.cssText = 'display:grid;grid-template-columns:1fr 100px 32px;gap:0.5rem;align-items:end;margin-bottom:0.5rem;';
            div.innerHTML = `
                <div class="offer-search-wrap">
                    <label class="form-label" style="font-size:0.75rem;font-weight:700;color:var(--slate-600);text-transform:uppercase;letter-spacing:0.05em;">Item to Buy</label>
                    <input type="text" class="form-control req-item-search" placeholder="Type product name" autocomplete="off">
                    <input type="hidden" name="required_items[${index}][product_id]" class="req-item-id">
                    <div class="offer-search-results req-item-results" style="display:none;"></div>
                </div>
                <div>
                    <label class="form-label" style="font-size:0.75rem;font-weight:700;color:var(--slate-600);text-transform:uppercase;letter-spacing:0.05em;">Qty</label>
                    <input type="number" name="required_items[${index}][required_quantity]" class="form-control req-item-qty" value="1" min="1" required>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-req-item-btn" style="border-radius:8px;" title="Remove">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            return div;
        }

        function updateRequiredItemIndices() {
            const rows = reqItemsList.querySelectorAll('.required-item-row');
            rows.forEach((row, index) => {
                row.querySelector('.req-item-id').name = `required_items[${index}][product_id]`;
                row.querySelector('.req-item-qty').name = `required_items[${index}][required_quantity]`;
            });
            reqItemCount = rows.length;
        }

        if (addReqItemBtn && reqItemsList) {
            addReqItemBtn.addEventListener('click', function() {
                const newRow = createRequiredItemRow(reqItemCount);
                reqItemsList.appendChild(newRow);
                updateRequiredItemIndices();
            });
        }

        reqItemsList.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-req-item-btn');
            if (!removeBtn) return;
            const row = removeBtn.closest('.required-item-row');
            const rows = reqItemsList.querySelectorAll('.required-item-row');
            if (rows.length > 1) {
                row.remove();
                updateRequiredItemIndices();
            }
        });

        reqItemsList.addEventListener('input', function(e) {
            const searchInput = e.target.closest('.req-item-search');
            if (!searchInput) return;
            const resultsDiv = searchInput.nextElementSibling?.nextElementSibling;
            const hiddenInput = searchInput.nextElementSibling;
            if (!resultsDiv || !hiddenInput) return;

            const query = searchInput.value.trim();
            hiddenInput.value = '';
            if (query.length < 1) {
                resultsDiv.innerHTML = '';
                resultsDiv.style.display = 'none';
                return;
            }

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
        });

        reqItemsList.addEventListener('click', function(e) {
            const item = e.target.closest('.offer-search-item');
            if (!item) return;
            const productId = item.getAttribute('data-id');
            const productName = item.getAttribute('data-name');
            const row = item.closest('.required-item-row');
            const searchInput = row.querySelector('.req-item-search');
            const hiddenInput = row.querySelector('.req-item-id');
            const resultsDiv = row.querySelector('.req-item-results');
            if (searchInput) searchInput.value = productName;
            if (hiddenInput) hiddenInput.value = productId;
            if (resultsDiv) resultsDiv.style.display = 'none';
        });

        // Close search dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.offer-search-wrap')) {
                document.querySelectorAll('.offer-search-results').forEach(function(div) { div.style.display = 'none'; });
            }
        });

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
                            this.innerHTML = '<span class="badge-active"><i class="bi bi-check-circle-fill"></i> @lang('messages.active')</span>';
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
                document.getElementById('editIsActiveOffer').checked = isActive === '1';
                document.getElementById('editOfferShopSelect').value = account;

                const editMessage = document.getElementById('editOfferMessage');
                if (editMessage) editMessage.innerHTML = '';

                const editReqList = document.getElementById('editRequiredItemsList');
                if (editReqList) {
                    editReqList.innerHTML = '';
                    const itemsToShow = requiredItems.length > 0 ? requiredItems : [{ product_id: productId, required_quantity: requiredQty }];
                    itemsToShow.forEach(function(item, index) {
                        const row = createEditRequiredItemRow(index, item.product_id, item.required_quantity);
                        editReqList.appendChild(row);
                    });
                }

                const cacheKey = 'shop_' + account;
                const setEditValues = function() {
                    const products = offerProductsCache[cacheKey] || [];
                    const freeProduct = products.find(function(p) { return p.id === offerProductId; });
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

        function createEditRequiredItemRow(index, productId, quantity) {
            const div = document.createElement('div');
            div.className = 'required-item-row';
            div.style.cssText = 'display:grid;grid-template-columns:1fr 100px 32px;gap:0.5rem;align-items:end;margin-bottom:0.5rem;';
            const pid = productId || '';
            const qty = quantity || 1;
            div.innerHTML = `
                <div class="offer-search-wrap">
                    <label class="form-label" style="font-size:0.75rem;font-weight:700;color:var(--slate-600);text-transform:uppercase;letter-spacing:0.05em;">Item to Buy</label>
                    <input type="text" class="form-control edit-req-item-search" placeholder="Type product name" autocomplete="off" value="${pid}">
                    <input type="hidden" name="required_items[${index}][product_id]" class="edit-req-item-id" value="${pid}">
                    <div class="offer-search-results edit-req-item-results" style="display:none;"></div>
                </div>
                <div>
                    <label class="form-label" style="font-size:0.75rem;font-weight:700;color:var(--slate-600);text-transform:uppercase;letter-spacing:0.05em;">Qty</label>
                    <input type="number" name="required_items[${index}][required_quantity]" class="form-control edit-req-item-qty" value="${qty}" min="1" required>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-danger edit-remove-req-item-btn" style="border-radius:8px;display:none;" title="Remove">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            return div;
        }

        const editReqItemsList = document.getElementById('editRequiredItemsList');
        const editAddReqItemBtn = document.getElementById('editAddRequiredItemBtn');
        let editReqItemCount = 0;

        function updateEditRequiredItemIndices() {
            const rows = editReqItemsList.querySelectorAll('.required-item-row');
            rows.forEach((row, index) => {
                row.querySelector('.edit-req-item-id').name = `required_items[${index}][product_id]`;
                row.querySelector('.edit-req-item-qty').name = `required_items[${index}][required_quantity]`;
            });
            editReqItemCount = rows.length;
        }

        if (editAddReqItemBtn && editReqItemsList) {
            editAddReqItemBtn.addEventListener('click', function() {
                const newRow = createEditRequiredItemRow(editReqItemCount);
                editReqItemsList.appendChild(newRow);
                updateEditRequiredItemIndices();
            });
        }

        if (editReqItemsList) {
            editReqItemsList.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.edit-remove-req-item-btn');
                if (!removeBtn) return;
                const row = removeBtn.closest('.required-item-row');
                const rows = editReqItemsList.querySelectorAll('.required-item-row');
                if (rows.length > 1) {
                    row.remove();
                    updateEditRequiredItemIndices();
                }
            });

            editReqItemsList.addEventListener('input', function(e) {
                const searchInput = e.target.closest('.edit-req-item-search');
                if (!searchInput) return;
                const resultsDiv = searchInput.nextElementSibling?.nextElementSibling;
                const hiddenInput = searchInput.nextElementSibling;
                if (!resultsDiv || !hiddenInput) return;

                const query = searchInput.value.trim();
                hiddenInput.value = '';
                if (query.length < 1) {
                    resultsDiv.innerHTML = '';
                    resultsDiv.style.display = 'none';
                    return;
                }

                const shopId = document.getElementById('editOfferShopSelect')?.value || null;
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
            });

            editReqItemsList.addEventListener('click', function(e) {
                const item = e.target.closest('.offer-search-item');
                if (!item) return;
                const productId = item.getAttribute('data-id');
                const productName = item.getAttribute('data-name');
                const row = item.closest('.required-item-row');
                const searchInput = row.querySelector('.edit-req-item-search');
                const hiddenInput = row.querySelector('.edit-req-item-id');
                const resultsDiv = row.querySelector('.edit-req-item-results');
                if (searchInput) searchInput.value = productName;
                if (hiddenInput) hiddenInput.value = productId;
                if (resultsDiv) resultsDiv.style.display = 'none';
            });
        }
    });
    </script>
</body>
</html>