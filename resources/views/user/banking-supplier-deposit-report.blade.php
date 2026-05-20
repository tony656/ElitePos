<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Supplier Deposit Report</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --navy:          #0B1E3D;
            --navy-mid:      #112952;
            --navy-light:    #1A3A6B;
            --amber:         #F59E0B;
            --amber-dark:    #D97706;
            --amber-pale:    #FEF3C7;
            --emerald:       #059669;
            --emerald-pale:  #D1FAE5;
            --rose:          #E11D48;
            --rose-pale:     #FFE4E6;
            --violet:        #7C3AED;
            --violet-pale:   #EDE9FE;
            --sky:           #0284C7;
            --sky-pale:      #E0F2FE;
            --slate-50:      #F8FAFC;
            --slate-100:     #F1F5F9;
            --slate-200:     #E2E8F0;
            --slate-300:     #CBD5E1;
            --slate-400:     #94A3B8;
            --slate-500:     #64748B;
            --slate-600:     #475569;
            --slate-700:     #334155;
            --slate-800:     #1E293B;
            --white:         #FFFFFF;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background: #EEF2F9;
            color: var(--slate-800);
            min-height: 100vh;
            line-height: 1.6;
        }

        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        .main-wrap { max-width: 1900px; margin: 0 auto; padding: 1.25rem 1.5rem; }

        .pg-header {
            background: var(--navy);
            border-radius: 12px;
            padding: 1.4rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.28);
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
            position: relative;
            overflow: hidden;
        }

        .pg-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 350px;
            height: 350px;
            background: rgba(245,158,11,0.08);
            border-radius: 50%;
            pointer-events: none;
        }

        .pg-header-content {
            display: flex; align-items: center; gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .back-btn {
            width: 42px; height: 42px;
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
            border: 1.5px solid rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            color: var(--white);
            cursor: pointer;
            transition: all 0.18s;
            text-decoration: none;
        }
        .back-btn:hover {
            background: rgba(255,255,255,0.2);
            border-color: var(--amber);
            color: var(--amber);
        }

        .pg-icon-wrap {
            width: 52px; height: 52px;
            background: rgba(245,158,11,0.15);
            border: 1.5px solid rgba(245,158,11,0.3);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--amber);
            font-size: 1.5rem;
        }

        .pg-title-wrap h1 {
            color: var(--white); font-size: 1.45rem; font-weight: 700;
            margin: 0 0 0.15rem 0;
        }
        .pg-subtitle {
            color: rgba(255,255,255,0.7); font-size: 0.82rem;
            margin: 0;
        }

        .btn-export {
            display: inline-flex; align-items: center; gap: 0.45rem;
            font-size: 0.82rem; font-weight: 600;
            padding: 0.55rem 1rem;
            background: var(--emerald); color: var(--white);
            border: none; border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 3px 12px rgba(5,150,105,0.3);
            transition: all 0.18s;
            text-decoration: none;
        }
        .btn-export:hover {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 5px 18px rgba(5,150,105,0.4);
            color: var(--white);
        }

        .filter-panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.85rem;
            align-items: end;
        }

        .filter-field {
            display: flex; flex-direction: column; gap: 0.35rem;
        }

        .filter-label {
            font-size: 0.72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.04em;
            color: var(--slate-600);
        }

        .filter-input {
            padding: 0.55rem 0.75rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--slate-50);
            font-size: 0.82rem;
            color: var(--slate-800);
            outline: none;
            transition: all 0.18s;
            font-family: 'Outfit', sans-serif;
        }
        .filter-input:focus {
            border-color: var(--navy-light);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        .filter-select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            padding-right: 2.25rem;
            appearance: none;
        }

        .btn-filter {
            padding: 0.55rem 1rem;
            background: var(--amber);
            color: var(--navy);
            border: none;
            border-radius: 7px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.18s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
        }
        .btn-filter:hover {
            background: var(--amber-dark);
            transform: translateY(-1px);
        }

        .card-panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card-head {
            background: var(--slate-50);
            border-bottom: 1.5px solid var(--slate-200);
            padding: 1.15rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1.05rem; font-weight: 700;
            color: var(--navy);
            margin: 0;
        }

        .card-body { padding: 0; }

        table.supplier-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.845rem;
        }

        table.supplier-tbl thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
        }

        table.supplier-tbl tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
            color: var(--slate-800);
        }

        table.supplier-tbl tbody tr:hover td {
            background: #F8FAFF;
        }

        table.supplier-tbl tbody tr.supplier-header td {
            background: var(--navy);
            color: var(--white);
            font-weight: 700;
            cursor: pointer;
        }

        table.supplier-tbl tbody tr.supplier-header td {
            padding: 1rem;
            font-size: 0.95rem;
        }

        table.supplier-tbl tbody tr.detail-row {
            display: none;
        }

        table.supplier-tbl tbody tr.detail-row.show {
            display: table-row;
        }

        table.supplier-tbl tbody tr.detail-row td {
            padding-left: 2.5rem;
            font-size: 0.815rem;
            background: var(--slate-50);
        }

        table.supplier-tbl tbody tr.detail-row:hover td {
            background: #F8FAFF;
        }

        .expand-icon {
            display: inline-block;
            transition: transform 0.2s;
            margin-right: 0.5rem;
        }

        .supplier-header.expanded .expand-icon {
            transform: rotate(90deg);
        }

        .supplier-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 0.25rem;
        }

        .supplier-bank {
            font-size: 0.75rem;
            opacity: 0.9;
        }

        .amt-value {
            font-family: 'DM Mono', monospace;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--navy);
        }

        .amt-total {
            font-family: 'DM Mono', monospace;
            font-weight: 800;
            font-size: 1rem;
            color: var(--emerald);
        }

        .badge-count {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.25rem 0.5rem;
            border-radius: 5px;
            background: rgba(255,255,255,0.2);
            color: var(--white);
        }

        .shop-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.3rem 0.6rem;
            border-radius: 5px;
            background: var(--amber-pale);
            color: #92400E;
        }

        .date-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.3rem 0.6rem;
            border-radius: 5px;
            background: var(--slate-100);
            color: var(--slate-600);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 1.5rem;
        }
        .empty-icon {
            width: 80px; height: 80px;
            margin: 0 auto 1rem;
            display: flex; align-items: center; justify-content: center;
            background: var(--slate-100);
            border-radius: 50%;
            color: var(--slate-400);
            font-size: 2rem;
        }
        .empty-title {
            font-size: 1.1rem; font-weight: 700;
            color: var(--slate-600);
            margin-bottom: 0.4rem;
        }
        .empty-desc {
            font-size: 0.875rem; color: var(--slate-500);
            margin-bottom: 1.5rem;
        }

        .summary-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem;
            background: var(--navy);
            color: var(--white);
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .summary-title {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .summary-stats {
            display: flex;
            gap: 2rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.8;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-family: 'DM Mono', monospace;
            font-size: 1.25rem;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
            .pg-header { padding: 1rem; margin-bottom: 1rem; flex-direction: column; align-items: flex-start; }
            .pg-header-content { width: 100%; }
            .btn-export { width: 100%; justify-content: center; margin-top: 0.5rem; }
            .filter-grid { grid-template-columns: 1fr; }
            .summary-bar { flex-direction: column; gap: 1rem; text-align: center; }
            .summary-stats { flex-wrap: wrap; justify-content: center; gap: 1.5rem; }

            table.supplier-tbl thead { display: none; }
            table.supplier-tbl tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1.5px solid var(--slate-200);
                border-radius: 10px;
                padding: 1rem;
                background: var(--white);
            }
            table.supplier-tbl tbody td {
                display: flex;
                justify-content: space-between;
                padding: 0.5rem 0;
                border-bottom: 1px solid var(--slate-100);
            }
            table.supplier-tbl tbody tr.supplier-header td {
                flex-direction: column;
                align-items: flex-start;
            }
            table.supplier-tbl tbody tr.detail-row td {
                padding-left: 1rem;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    @include("user.sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
        <div class="main-wrap">

            <div class="pg-header">
                <div class="pg-header-content">
                    <a href="javascript:history.back()" class="back-btn">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <div class="pg-icon-wrap">
                        <i class="bi bi-bank"></i>
                    </div>
                    <div class="pg-title-wrap">
                        <h1>Supplier Deposit Report</h1>
                        <p class="pg-subtitle">Bank deposits grouped by supplier</p>
                    </div>
                </div>
                <a href="/user/banking/supplier-deposit-report/export?{{ http_build_query(request()->query()) }}" class="btn-export">
                    <i class="bi bi-download"></i> Export CSV
                </a>
            </div>

            <div class="filter-panel">
                <form method="GET" action="">
                    <div class="filter-grid">
                        <div class="filter-field">
                            <label class="filter-label">Date From</label>
                            <input type="date" class="filter-input" name="date_from"
                                value="{{ $dateFrom ?? '' }}">
                        </div>
                        <div class="filter-field">
                            <label class="filter-label">Date To</label>
                            <input type="date" class="filter-input" name="date_to"
                                value="{{ $dateTo ?? '' }}">
                        </div>
                        <div class="filter-field">
                            <label class="filter-label">Shop</label>
                            <select class="filter-input filter-select" name="shop_id">
                                <option value="">All Shops</option>
                                @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ (request('shop_id') == $shop->id) ? 'selected' : '' }}>
                                    {{ $shop->name ?? 'N/A' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-field">
                            <label class="filter-label">Supplier</label>
                            <select class="filter-input filter-select" name="supplier_id">
                                <option value="">All Suppliers</option>
                                @foreach($allSuppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ (request('supplier_id') == $supplier->id) ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-field">
                            <label class="filter-label" style="opacity:0;">Apply</label>
                            <button type="submit" class="btn-filter">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Summary Statistics -->
            <div class="summary-bar">
                <div class="summary-title">Report Summary</div>
                <div class="summary-stats">
                    <div class="stat-item">
                        <div class="stat-label">Total Deposits</div>
                        <div class="stat-value">{{ number_format($grandTotal ?? 0, 2) }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Transactions</div>
                        <div class="stat-value">{{ $grandCount ?? 0 }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Suppliers</div>
                        <div class="stat-value">{{ count($supplierTotals ?? []) }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Average</div>
                        <div class="stat-value">{{ number_format($averageDeposit ?? 0, 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="card-panel">
                <div class="card-head">
                    <h6 class="card-title">Deposits by Supplier</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="supplier-tbl" id="supplierReportTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Supplier & Bank Details</th>
                                    <th>Transfer Date</th>
                                    <th>Beneficiary</th>
                                    <th>Shop</th>
                                    <th>Amount (Tsh)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(empty($supplierTotals))
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <i class="bi bi-bank"></i>
                                            </div>
                                            <div class="empty-title">No Deposits Found</div>
                                            <p class="empty-desc">
                                                No banking deposits recorded for the selected period
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                @else
                                    @php $rowNum = 1; @endphp
                                    @foreach($supplierTotals as $supplierTotal)
                                        @php
                                            $supplier = $supplierTotal->supplier;
                                            $isEven = $loop->iteration % 2 == 0;
                                        @endphp
                                        <!-- Supplier Header Row -->
                                        <tr class="supplier-header" data-supplier-id="{{ $supplier->id ?? '' }}">
                                            <td>{{ $rowNum++ }}</td>
                                            <td colspan="5">
                                                <div class="supplier-name">
                                                    <i class="bi bi-chevron-right expand-icon"></i>
                                                    {{ $supplier->name ?? 'N/A' }}
                                                    <span class="badge-count">
                                                        <i class="bi bi-bank"></i>
                                                        {{ $supplierTotal->transfer_count }} transaction(s)
                                                    </span>
                                                </div>
                                                @if($supplier && isset($supplier->bank_name))
                                                <div class="supplier-bank">
                                                    <i class="bi bi-building"></i> {{ $supplier->bank_name ?? 'N/A' }} -
                                                    Account: {{ $supplier->account_number ?? 'N/A' }}
                                                    @if(isset($supplier->branch) && $supplier->branch)
                                                    | Branch: {{ $supplier->branch }}
                                                    @endif
                                                </div>
                                                @endif
                                                <div style="margin-top: 0.5rem; font-size: 0.875rem; font-weight: 700; color: var(--emerald);">
                                                    Total: {{ number_format($supplierTotal->total_amount, 2) }} Tsh
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Individual Transfer Rows -->
                                        @foreach($supplierTotal->transfers as $transfer)
                                        <tr class="detail-row" data-parent-supplier="{{ $supplier->id ?? '' }}">
                                            <td></td>
                                            <td>
                                                <div class="date-badge">
                                                    <i class="bi bi-calendar3"></i>
                                                    {{ $transfer->transfer_date ? \Carbon\Carbon::parse($transfer->transfer_date)->format('d M Y') : 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                {{ $transfer->beneficiary->name ?? 'N/A' }}
                                            </td>
                                            <td>
                                                @if($transfer->shop)
                                                <span class="shop-badge">
                                                    <i class="bi bi-shop"></i>
                                                    {{ $transfer->shop->name ?? 'N/A' }}
                                                </span>
                                                @else
                                                <span style="color:var(--slate-400); font-size:0.75rem;">Not allocated</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="amt-value">{{ number_format($transfer->amount, 2) }}</span>
                                            </td>
                                            <td>
                                                @if($transfer->description)
                                                <small style="color: var(--slate-500);">{{ Str::limit($transfer->description, 50) }}</small>
                                                @else
                                                <span style="color:var(--slate-300);">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const shopSelect = $('select[name="shop_id"]');
        const supplierSelect = $('select[name="supplier_id"]');
        const filterForm = $('form');
        let isLoading = false;

        // Function to fetch suppliers by shop
        function fetchSuppliersByShop(shopId, callback) {
            if (!shopId) {
                // If no shop selected, load all suppliers (no filter)
                callback(null);
                return;
            }

            // Get current filter values to pass along
            const dateFrom = $('input[name="date_from"]').val();
            const dateTo = $('input[name="date_to"]').val();
            
            $.ajax({
                url: '/user/banking/get-suppliers-by-shop',
                type: 'GET',
                data: {
                    shop_id: shopId,
                    date_from: dateFrom,
                    date_to: dateTo
                },
                beforeSend: function() {
                    // Show loading state
                    supplierSelect.prop('disabled', true);
                    supplierSelect.html('<option value="">Loading...</option>');
                },
                success: function(response) {
                    if (response.success) {
                        // Build supplier options
                        let options = '<option value="">All Suppliers</option>';
                        $.each(response.suppliers, function(index, supplier) {
                            options += '<option value="' + supplier.id + '">' +
                                      supplier.name +
                                      (supplier.bank_name ? ' (' + supplier.bank_name + ')' : '') +
                                      '</option>';
                        });
                        supplierSelect.html(options);
                        
                        // Restore selected supplier if still valid
                        const selectedSupplier = '{{ request('supplier_id') }}';
                        if (selectedSupplier) {
                            supplierSelect.val(selectedSupplier);
                        }
                    } else {
                        supplierSelect.html('<option value="">All Suppliers</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching suppliers:', error);
                    supplierSelect.html('<option value="">All Suppliers</option>');
                },
                complete: function() {
                    supplierSelect.prop('disabled', false);
                }
            });
        }

        // When shop changes, fetch filtered suppliers
        shopSelect.on('change', function() {
            const shopId = $(this).val();
            
            // Fetch suppliers for selected shop
            fetchSuppliersByShop(shopId, function() {
                // After suppliers are loaded, auto-submit the form
                // Only if we want to refresh the report automatically
                // Comment out the next line if you want manual filter submission
                // filterForm.submit();
            });
        });

        // Auto-submit form when supplier changes (after shop selection)
        supplierSelect.on('change', function() {
            // Only auto-submit if a shop is already selected
            if (shopSelect.val()) {
                filterForm.submit();
            }
        });

        // Clear filters double-click
        $('input[type="date"]').dblclick(function() {
            $(this).val('');
        });

        // Toggle supplier detail rows on click
        $('.supplier-header').on('click', function() {
            var supplierId = $(this).data('supplier-id');
            var detailRows = $('.detail-row[data-parent-supplier="' + supplierId + '"]');
            
            // Toggle visibility
            detailRows.toggleClass('show');
            
            // Toggle expanded class on header
            $(this).toggleClass('expanded');
        });

        // Initial load: if shop is pre-selected, fetch filtered suppliers
        $(window).on('load', function() {
            const initialShopId = shopSelect.val();
            if (initialShopId) {
                fetchSuppliersByShop(initialShopId);
            }
        });
    });
</script>

</body>
</html>