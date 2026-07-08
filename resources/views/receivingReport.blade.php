<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.receiving_report')</title>
    @include('links')
    
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --navy:       #0B1E3D;
            --navy-mid:   #112952;
            --navy-light: #1A3A6B;
            --amber:      #F59E0B;
            --amber-pale: #FEF3C7;
            --emerald:    #059669;
            --emerald-pale: #D1FAE5;
            --rose:       #E11D48;
            --rose-pale:  #FFE4E6;
            --slate-50:   #F8FAFC;
            --slate-100:  #F1F5F9;
            --slate-200:  #E2E8F0;
            --slate-400:  #94A3B8;
            --slate-600:  #475569;
            --slate-800:  #1E293B;
            --white:      #FFFFFF;
            --font: 'Sora', system-ui, sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --r: 8px; --r-lg: 12px; --r-xl: 16px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            background: #ECF0F8;
            color: var(--slate-800);
            min-height: 100vh;
        }

        .wrap { padding: 1.25rem 1.5rem 2rem; }

        /* Page header */
        .pg-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            background: var(--navy);
            border-radius: var(--r-xl);
            padding: 1rem 1.5rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.25);
        }

        .pg-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--white);
            font-size: 1.35rem;
            font-weight: 700;
        }

        .pg-title-icon {
            width: 40px;
            height: 40px;
            background: rgba(245,158,11,0.2);
            border: 1px solid rgba(245,158,11,0.35);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--amber);
            font-size: 1.1rem;
        }

        .pg-title span { color: var(--amber); }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0.5rem 1rem;
            border-radius: var(--r);
            border: 1px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.07);
            color: var(--white);
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.15);
            color: var(--white);
        }

        /* Filter bar */
        .filter-bar {
            background: var(--slate-100);
            border-bottom: 1px solid var(--slate-200);
            padding: 0.8rem 1.25rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
            border-radius: var(--r-lg) var(--r-lg) 0 0;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .filter-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--slate-600);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
        }

        .filter-input {
            font-size: 0.825rem;
            padding: 0.38rem 0.65rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            transition: border-color 0.18s, box-shadow 0.18s;
            outline: none;
        }

        .filter-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        select.filter-input { cursor: pointer; }

        /* Summary bar */
        .summary-bar {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 0.7rem 1.25rem;
            background: linear-gradient(90deg, #EBF0FA 0%, #F1F5F9 100%);
            border-bottom: 1px solid var(--slate-200);
            flex-wrap: wrap;
        }

        .summary-stat {
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
        }

        .summary-stat-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-stat-value {
            font-size: 1rem;
            font-weight: 500;
            color: var(--navy);
        }

        .summary-stat-value.amber { color: var(--amber); }
        .summary-stat-value.emerald { color: var(--emerald); }
        .summary-stat-value.rose { color: var(--rose); }
        .summary-stat-value.remaining { color: #0f5b8c; font-weight: 700; }

        .summary-divider {
            width: 1px;
            height: 32px;
            background: var(--slate-200);
        }

        /* Table */
        .table-scroll { overflow-x: auto; }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.845rem;
        }

        table.report-table thead th {
            background: var(--slate-100);
            color: var(--slate-600);
            font-size: 0.73rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.65rem 0.85rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 5;
        }

        table.report-table tbody td {
            padding: 0.6rem 0.85rem;
            border-bottom: 1px solid var(--slate-200);
            vertical-align: middle;
            color: var(--slate-800);
        }

        table.report-table tbody tr:hover td { background: #EFF4FF; }

        .product-name { font-weight: 600; color: var(--navy); }
        .mono { font-size: 0.825rem; }

        .remaining-positive { color: var(--emerald); font-weight: 700; }
        .remaining-negative { color: var(--rose); font-weight: 700; }
        .remaining-zero { color: var(--slate-400); }
        .diff-positive { color: var(--emerald); font-weight: 600; }
        .diff-negative { color: var(--rose); font-weight: 600; }
        .diff-zero { color: var(--slate-400); }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .stock-low { background: var(--amber-pale); color: #92400E; }
        .stock-out { background: var(--rose-pale); color: var(--rose); }
        .stock-good { background: var(--emerald-pale); color: var(--emerald); }

        .empty-state {
            text-align: center;
            padding: 3.5rem 1rem;
            color: var(--slate-400);
        }
        .empty-state i { font-size: 2.5rem; margin-bottom: 0.75rem; display: block; }

        .card-panel {
            background: var(--white);
            border-radius: 0 0 var(--r-lg) var(--r-lg);
            box-shadow: 0 2px 16px rgba(11,30,61,0.08);
            overflow: hidden;
        }

        @media(max-width:768px) {
            .wrap { padding: 1rem; }
            .summary-bar { gap: 1rem; }
        }
    </style>
</head>
<body>
@include('sidenav')

    <main class="main-content">
        <div class="wrap">

            {{-- Page Header --}}
            <div class="pg-header">
                <div class="pg-title">
                    <div class="pg-title-icon"><i class="bi bi-clipboard2-data"></i></div>
                    @lang('messages.receiving_and_stock_report').'<span>@lang('messages.report')</span>
                </div>
                <a href="{{ url('view-receivings') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i> @lang('messages.back_to_receivings')
                </a>
            </div>

            {{-- Filter Bar --}}
            <form method="GET" action="{{ url('receiving-report') }}" style="display:contents;">
                <div class="filter-bar">
                    <div class="filter-group">
                        <span class="filter-label">@lang('messages.shop')</span>
                        <select name="shop" class="filter-input" onchange="this.form.submit()">
                            <option value="">@lang('messages.all_shops')</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop['id'] }}" {{ $shopFilter == $shop['id'] ? 'selected' : '' }}>
                                    {{ $shop['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">@lang('messages.date')</span>
                        <input type="date" name="date" class="filter-input"
                            value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}"
                            onchange="this.form.submit()">
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">@lang('messages.from')</span>
                        <input type="date" name="from_date" class="filter-input"
                            value="{{ $fromDate }}" max="{{ date('Y-m-d') }}"
                            onchange="this.form.submit()">
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">@lang('messages.to')</span>
                        <input type="date" name="to_date" class="filter-input"
                            value="{{ $toDate }}" max="{{ date('Y-m-d') }}"
                            onchange="this.form.submit()">
                    </div>
                </div>
            </form>

            {{-- Summary Bar --}}
            <div class="summary-bar">
                <div class="summary-stat">
                    <span class="summary-stat-label">@lang('messages.items')</span>
                    <span class="summary-stat-value">{{ number_format(count($reportRows)) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">📦 Received</span>
                    <span class="summary-stat-value">{{ number_format($totalReceived) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">💰 Sold</span>
                    <span class="summary-stat-value amber">{{ number_format($totalSold ?? 0) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">↩️ Returns (Supplier)</span>
                    <span class="summary-stat-value rose">{{ number_format($totalReturned) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">🔄 Returns (Customer)</span>
                    <span class="summary-stat-value" style="color: #D97706;">{{ number_format($totalCustomerReturned ?? 0) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">✅ Remaining Stock</span>
                    <span class="summary-stat-value remaining">{{ number_format($totalRemaining ?? 0) }}</span>
                </div>
            </div>

            {{-- Table --}}
            <div class="card-panel">
                <div class="table-scroll">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('messages.product')</th>
                                <th>@lang('messages.supplier')</th>
                                <th>@lang('messages.received')</th>
                                <th>@lang('messages.sold')</th>
                                <th>@lang('messages.returns_supplier')</th>
                                <th>@lang('messages.net_sold')</th>
                                <th>@lang('messages.remaining')</th>
                                <th>@lang('messages.status')</th>
                                <th>@lang('messages.total_amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(empty($reportRows))
                            <tr>
                                <td colspan="11">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>@lang('messages.no_data_found_for_filters')</p>
                                    </div>
                                </td>
                            </tr>
                            @else
                                @php $index = 1; @endphp
                                @foreach($reportRows as $row)
                                    @php
                                        $remaining = $row['remainingStock'] ?? 0;
                                        if ($remaining < 0) {
                                            $remainingClass = 'remaining-negative';
                                            $status = '<span class="stock-badge stock-out"><i class="bi bi-arrow-up"></i> Over Sold</span>';
                                        } elseif ($remaining > 0) {
                                            $remainingClass = 'remaining-negative';
                                            $status = '<span class="stock-badge stock-low"><i class="bi bi-arrow-down"></i>In Stock</span>';
                                        } else {
                                            $remainingClass = 'remaining-positive';
                                            $status = '<span class="stock-badge stock-good"><i class="bi bi-check-circle"></i> Settled</span>';
                                        }
                                        
                                        $netSold = $row['netSold'] ?? 0;
                                    @endphp
                                    <tr>
                                        <td style="color:var(--slate-400);font-size:0.8rem;">{{ $index++ }}</td>

                                        <td><span class="product-name">{{ $row['productName'] }}</span></td>

                                        <td><span class="mono">{{ $row['supplierName'] }}</span></td>

                                        <td><span class="mono">{{ number_format($row['approvedQty'] ?? 0) }}</span></td>
                                        
                                        <td><span class="mono" style="color:var(--amber);font-weight:600;">{{ number_format($row['soldQty'] ?? 0) }}</span></td>
                                        
                                        <td><span class="mono" style="color:var(--rose);">{{ number_format($row['returnQty'] ?? 0) }}</span></td>
                                        <td><span class="mono">{{ number_format($netSold) }}</span></td>
                                        <td><span class="mono {{ $remainingClass }}">{{ number_format($remaining) }}</span></td>
                                        <td>{!! $status !!}</td>
                                        <td><span class="mono">Tsh {{ number_format($row['totalPrice'] ?? 0, 2) }}</span></td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
@include('footer')

</body>
</html>
