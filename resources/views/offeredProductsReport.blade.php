<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.offered_products_report')</title>
    @include("links")
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
            --shadow: 0 4px 20px rgba(11,30,61,.08);
            --shadow-lg: 0 10px 40px rgba(11,30,61,.12);
        }

        .card-modern {
            background: var(--white);
            border: none;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        
        .card-header-modern {
            background: var(--navy);
            color: var(--white);
            padding: 18px 24px;
            border-bottom: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header-modern h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .card-header-modern h5 i {
            color: var(--amber);
            margin-right: 8px;
        }
        
        .card-body-modern {
            padding: 24px;
        }
        
        /* Stats Grid - Improved */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }
        
        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 20px 24px;
            border: 1px solid var(--slate-200);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
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
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        
        .stat-card.amber::before { background: var(--amber); }
        .stat-card.emerald::before { background: var(--emerald); }
        .stat-card.violet::before { background: var(--violet); }
        .stat-card.sky::before { background: var(--sky); }
        
        .stat-card .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        
        .stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .stat-card.amber .stat-icon {
            background: var(--amber-pale);
            color: #92400e;
        }
        
        .stat-card.emerald .stat-icon {
            background: var(--emerald-pale);
            color: var(--emerald);
        }
        
        .stat-card.violet .stat-icon {
            background: var(--violet-pale);
            color: var(--violet);
        }
        
        .stat-card.sky .stat-icon {
            background: var(--sky-pale);
            color: var(--sky);
        }
        
        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--navy);
            line-height: 1.2;
        }
        
        .stat-card .stat-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--slate-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-card .stat-change {
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 8px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 10px;
            border-radius: 20px;
        }
        
        .stat-change.up {
            color: var(--emerald);
            background: var(--emerald-pale);
        }
        
        .stat-change.down {
            color: var(--rose);
            background: var(--rose-pale);
        }
        
        .stat-change.neutral {
            color: var(--slate-500);
            background: var(--slate-100);
        }
        
        /* Filter Section */
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
            font-size: 0.85rem;
            margin-bottom: 6px;
        }
        
        .filter-section .form-control {
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .filter-section .form-control:focus {
            border-color: var(--amber);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
        }
        
        .btn-primary {
            background: var(--amber);
            border: none;
            color: var(--navy);
            font-weight: 700;
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }
        
        .btn-outline-secondary {
            border: 1.5px solid var(--slate-300);
            color: var(--slate-600);
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-outline-secondary:hover {
            background: var(--slate-100);
            border-color: var(--slate-400);
        }
        
        .btn-success {
            background: var(--emerald);
            border: none;
            color: var(--white);
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            background: #047857;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        }
        
        .btn-light {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            color: var(--white);
            transition: all 0.3s ease;
        }
        
        .btn-light:hover {
            background: rgba(255,255,255,0.25);
            color: var(--white);
        }
        
        /* Table */
        .table-modern {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table-modern thead {
            background: var(--navy);
        }
        
        .table-modern thead th {
            padding: 12px 16px;
            text-align: left;
            color: rgba(255,255,255,0.8);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table-modern tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--slate-100);
            color: var(--slate-700);
            font-size: 0.9rem;
        }
        
        .table-modern tbody tr:hover {
            background: var(--slate-50);
        }
        
        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }
        
        .offer-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: var(--amber-pale);
            color: #92400e;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .badge-active {
            background: var(--emerald-pale);
            color: var(--emerald);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-inactive {
            background: var(--rose-pale);
            color: var(--rose);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        /* Modal Styles */
        .modal-content {
            border-radius: var(--radius);
            border: none;
            box-shadow: var(--shadow-lg);
        }
        
        .modal-header {
            background: var(--navy);
            color: var(--white);
            border-bottom: none;
            padding: 18px 24px;
        }
        
        .modal-header .modal-title {
            font-weight: 700;
        }
        
        .modal-header .modal-title i {
            color: var(--amber);
            margin-right: 8px;
        }
        
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.6;
        }
        
        .modal-header .btn-close:hover {
            opacity: 1;
        }
        
        .modal-body {
            padding: 24px;
        }
        
        .modal-footer {
            border-top: 1px solid var(--slate-200);
            padding: 16px 24px;
        }
        
        .modal .form-label {
            font-weight: 600;
            color: var(--slate-600);
            font-size: 0.85rem;
            margin-bottom: 6px;
        }
        
        .modal .form-control {
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .modal .form-control:focus {
            border-color: var(--amber);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
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
        
        .alert {
            border: none;
            border-radius: 8px;
            padding: 12px 16px;
        }
        
        .alert-success {
            background: var(--emerald-pale);
            color: var(--emerald);
        }
        
        .alert-danger {
            background: var(--rose-pale);
            color: var(--rose);
        }
        
        .empty-state {
            padding: 60px 20px;
            text-align: center;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: var(--slate-300);
        }
        
        .empty-state h5 {
            margin-top: 16px;
            color: var(--slate-500);
            font-weight: 600;
        }
        
        .empty-state p {
            color: var(--slate-400);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .card-body-modern {
                padding: 16px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .stat-card .stat-value {
                font-size: 1.4rem;
            }
            
            .card-header-modern {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            
            .filter-section .d-flex {
                flex-direction: column;
                gap: 8px;
            }
            
            .filter-section .d-flex .btn {
                width: 100%;
            }
            
            .modal-body {
                padding: 16px;
            }
        }
    </style>
</head>
<body>

@include("sidenav")

    <main class="main-content">
                    <h5><i class="bi bi-gift"></i> @lang('messages.offered_products_report')</h5>
                    <div>
                        <button type="button" class="btn btn-light btn-sm me-2" data-bs-toggle="modal" data-bs-target="#createOfferModal">
                            <i class="bi bi-plus-circle"></i> @lang('messages.create_offer')
                        </button>
                        <a href="{{ url('shopReport') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left"></i> @lang('messages.back_to_reports')
                        </a>
                    </div>
                </div>
                <div class="card-body-modern">
                    <!-- Filter Section -->
                    <div class="filter-section">
                        <form method="GET" action="{{ url('offeredProductsReport') }}" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">@lang('messages.start_date')</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">@lang('messages.end_date')</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-filter"></i> @lang('messages.filter')
                                </button>
                                <a href="{{ url('offeredProductsReport') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> @lang('messages.reset')
                                </a>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Stats Grid -->
                    <div class="stats-grid">
                        <div class="stat-card amber">
                            <div class="stat-top">
                                <div>
                                    <div class="stat-label">@lang('messages.total_free_items_given')</div>
                                    <div class="stat-value">{{ number_format($totalOfferedItems) }}</div>
                                </div>
                                <div class="stat-icon">
                                    <i class="bi bi-gift"></i>
                                </div>
                            </div>
                            <div class="stat-change up">
                                <i class="bi bi-arrow-up"></i> 12.5%
                            </div>
                        </div>
                        
                        <div class="stat-card emerald">
                            <div class="stat-top">
                                <div>
                                    <div class="stat-label">@lang('messages.orders_with_offers')</div>
                                    <div class="stat-value">{{ number_format($totalOrdersWithOffers) }}</div>
                                </div>
                                <div class="stat-icon">
                                    <i class="bi bi-cart"></i>
                                </div>
                            </div>
                            <div class="stat-change up">
                                <i class="bi bi-arrow-up"></i> 8.3%
                            </div>
                        </div>
                        
                        <div class="stat-card violet">
                            <div class="stat-top">
                                <div>
                                    <div class="stat-label">@lang('messages.active_offers')</div>
                                    <div class="stat-value">{{ number_format($totalActiveOffers ?? 0) }}</div>
                                </div>
                                <div class="stat-icon">
                                    <i class="bi bi-tags"></i>
                                </div>
                            </div>
                            <div class="stat-change neutral">
                                <i class="bi bi-dash"></i> No change
                            </div>
                        </div>
                        
                        <div class="stat-card sky">
                            <div class="stat-top">
                                <div>
                                    <div class="stat-label">@lang('messages.products_with_offers')</div>
                                    <div class="stat-value">{{ number_format($totalProductsWithOffers ?? 0) }}</div>
                                </div>
                                <div class="stat-icon">
                                    <i class="bi bi-box"></i>
                                </div>
                            </div>
                            <div class="stat-change down">
                                <i class="bi bi-arrow-down"></i> 2.1%
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    @if(count($offeredProducts) > 0)
                    <div class="table-responsive">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('messages.product_name')</th>
                                    <th>@lang('messages.free_quantity_given')</th>
                                    <th>@lang('messages.times_given')</th>
                                    <th>@lang('messages.status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($offeredProducts as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $products[$item->productId] ?? __('messages.unknown_product') }}</strong>
                                    </td>
                                    <td>
                                        <span class="offer-badge">
                                            <i class="bi bi-gift"></i> {{ number_format($item->total_quantity) }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($item->order_count) }} @lang('messages.order_plural')</td>
                                    <td>
                                        <span class="badge-active">
                                            <i class="bi bi-check-circle-fill"></i> @lang('messages.active')
                                        </span>
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
        </div>
    </div>
</div>

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
                            <select name="account" id="offerShopSelect" class="form-control" required>
                                <option value="">-- @lang('messages.products_select_shop') --</option>
                                @foreach($shops as $shop)
                                    <option value="{{ $shop['id'] }}">{{ $shop['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.item_to_buy')</label>
                            <select name="product_id" id="buyProductSelect" class="form-control" required>
                                <option value="">-- @lang('messages.item_to_buy') --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.item_to_give_free')</label>
                            <select name="offer_product_id" id="freeProductSelect" class="form-control" required>
                                <option value="">-- @lang('messages.item_to_give_free') --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">@lang('messages.products_required_quantity_buy')</label>
                            <input type="number" name="required_quantity" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">@lang('messages.products_offer_quantity_get_free')</label>
                            <input type="number" name="offer_quantity" class="form-control" value="1" min="1" required>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="isActiveOffer" checked class="form-check-input">
                                <label class="form-check-label" for="isActiveOffer">@lang('messages.products_offer_active')</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-lg"></i> @lang('messages.products_save_offer')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function loadOfferProducts(accountId) {
    const buySelect = document.getElementById('buyProductSelect');
    const freeSelect = document.getElementById('freeProductSelect');
    const langItemToBuy = @json(__('messages.item_to_buy'));
    const langItemToGive = @json(__('messages.item_to_give_free'));
    const langSearching = @json(__('messages.searching'));
    const langErrorLoading = @json(__('messages.error_loading_products'));

    if (!accountId) {
        buySelect.innerHTML = '<option value="">' + langItemToBuy + '</option>';
        freeSelect.innerHTML = '<option value="">' + langItemToGive + '</option>';
        return;
    }

    buySelect.innerHTML = '<option value="">' + langSearching + '</option>';
    freeSelect.innerHTML = '<option value="">' + langSearching + '</option>';

    fetch('{{ url('search-products-for-offer') }}?account=' + accountId + '&q=')
        .then(res => res.json())
        .then(data => {
            const options = '<option value="">' + langItemToBuy + '</option>' +
                data.results.map(p => '<option value="' + p.id + '">' + p.name + ' (Stock: ' + p.stock + ')</option>').join('');
            buySelect.innerHTML = options;
            freeSelect.innerHTML = options;
        })
        .catch(() => {
            buySelect.innerHTML = '<option value="">' + langErrorLoading + '</option>';
            freeSelect.innerHTML = '<option value="">' + langErrorLoading + '</option>';
        });
}

document.addEventListener('DOMContentLoaded', function() {
    const shopSelect = document.getElementById('offerShopSelect');
    if (shopSelect) {
        shopSelect.addEventListener('change', function() {
            loadOfferProducts(this.value);
        });

        if (shopSelect.value) {
            loadOfferProducts(shopSelect.value);
        }
    }
});
</script>

</body>
</html>