<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items Report</title>
    @include('links')
    <link href="{{asset('css/dashboard.css')}}" rel="stylesheet">
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
            font-family: var(--font);
            background: #ECF0F8;
            color: var(--slate-800);
            min-height: 100vh;
        }

        .wrap { padding: 1.25rem 1.5rem 2rem; }

        /* ── Page header ── */
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
            letter-spacing: -0.01em;
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
            font-family: var(--font);
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.15);
            color: var(--white);
        }

        /* ── Filter bar ── */
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
            font-family: var(--font);
            font-size: 0.825rem;
            padding: 0.38rem 0.65rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            transition: border-color 0.18s, box-shadow 0.18s;
            outline: none;
            min-width: 0;
        }

        .filter-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        select.filter-input { cursor: pointer; }

        /* ── Summary bar ── */
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
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-stat-value {
            font-family: var(--mono);
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--navy);
        }

        .summary-stat-value.amber { color: var(--amber); }
        .summary-stat-value.emerald { color: var(--emerald); }
        .summary-stat-value.rose { color: var(--rose); }

        .summary-divider {
            width: 1px;
            height: 32px;
            background: var(--slate-200);
        }

        /* ── Table ── */
        .table-scroll { overflow-x: auto; }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.825rem;
        }

        table.report-table thead th {
            background: var(--slate-100);
            color: var(--slate-600);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 0.65rem 0.75rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 5;
        }

        table.report-table tbody td {
            padding: 0.55rem 0.75rem;
            border-bottom: 1px solid var(--slate-200);
            vertical-align: middle;
            color: var(--slate-800);
        }

        table.report-table tbody tr { transition: background 0.12s; }
        table.report-table tbody tr:hover td { background: #EFF4FF; }

        .product-name { font-weight: 600; color: var(--navy); }
        .mono { font-family: var(--mono); font-size: 0.8rem; }

        .empty-state {
            text-align: center;
            padding: 3.5rem 1rem;
            color: var(--slate-400);
        }
        .empty-state i { font-size: 2.5rem; margin-bottom: 0.75rem; display: block; }
        .empty-state p { font-size: 0.9rem; margin: 0; }

        /* ── Card panel ── */
        .card-panel {
            background: var(--white);
            border-radius: 0 0 var(--r-lg) var(--r-lg);
            box-shadow: 0 2px 16px rgba(11,30,61,0.08);
            overflow: hidden;
        }

        @media(max-width:768px) {
            .wrap { padding: 1rem; }
            .pg-header { padding: 0.75rem 1rem; }
            .filter-bar { padding: 0.6rem 1rem; }
            .summary-bar { padding: 0.5rem 1rem; gap: 0.75rem; }
        }
    </style>
</head>
<body>
<div class="row">
    @include('admin/sidenav')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="wrap">
 @if(session('success'))
      <div class="alert alert-success  d-flex justify-content-between">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

      </div>
  @endif
  
  @if(session('error'))
      <div class="alert alert-danger d-flex justify-content-between">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

      </div>
  @endif
            {{-- Page Header --}}
            <div class="pg-header">
                <div class="pg-title">
                    <div class="pg-title-icon"><i class="bi bi-box-seam"></i></div>
                    Items <span>Report</span>
                </div>
                <a href="{{ url('admin/products') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i> Back to Items
                </a>
            </div>

            {{-- Filter Bar --}}
            <form method="GET" action="{{ url('admin/items-report') }}" style="display:contents;">
                <div class="filter-bar">
                    <div class="filter-group">
                        <span class="filter-label">Shop</span>
                        <select name="shop" class="filter-input" id="shop" onchange="this.form.submit()">
                            <option value="">All Shops</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ $accountFilter == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">Date</span>
                        <input type="date" name="date" class="filter-input" id="date"
                            value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}"
                            onchange="this.form.submit()">
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">From</span>
                        <input type="date" name="from_date" class="filter-input" id="from_date"
                            value="{{ $fromDate }}" max="{{ date('Y-m-d') }}"
                            onchange="this.form.submit()">
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">To</span>
                        <input type="date" name="to_date" class="filter-input" id="to_date"
                            value="{{ $toDate }}" max="{{ date('Y-m-d') }}"
                            onchange="this.form.submit()">
                    </div>
                </div>
            </form>

            {{-- Summary Bar --}}
            <div class="summary-bar">
                <div class="summary-stat">
                    <span class="summary-stat-label">Items</span>
                    <span class="summary-stat-value">{{ number_format(count($reportRows)) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">Received Qty</span>
                    <span class="summary-stat-value emerald">{{ number_format($totalReceived) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">Returned Qty</span>
                    <span class="summary-stat-value rose">{{ number_format($totalReturned) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">Sold Qty</span>
                    <span class="summary-stat-value" style="color:var(--navy-light);">{{ number_format($totalSold) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">Received Total</span>
                    <span class="summary-stat-value emerald">Tsh {{ number_format($totalReceivedPrice, 2) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">Sold Total</span>
                    <span class="summary-stat-value amber">Tsh {{ number_format($totalSoldPrice, 2) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">Remaining Qty</span>
                    <span class="summary-stat-value" style="color:var(--navy);">{{ number_format($totalRemainingQty) }}</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-stat">
                    <span class="summary-stat-label">Remaining Value</span>
                    <span class="summary-stat-value" style="color:var(--navy);">Tsh {{ number_format($totalRemainingValue, 2) }}</span>
                </div>
            </div>

            {{-- Table --}}
            <div class="card-panel">
                <div class="table-scroll">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th>Unit</th>
                                <th>Received Qty</th>
                                <th>Returned Qty</th>
                                <th>Sold Qty</th>
                                <th>Received Total</th>
                                <th>Sold Total</th>
                                <th>Remaining Qty</th>
                                <th>Remaining Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(empty($reportRows))
                            <tr>
                                <td colspan="12">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>No data found for the selected filters.</p>
                                    </div>
                                </td>
                            </tr>
                            @else
                                @php $index = 1; @endphp
                                @foreach($reportRows as $row)
                                    <tr>
                                        <td style="color:var(--slate-400);font-size:0.78rem;">{{ $index++ }}</td>
                                        <td><span class="product-name">{{ $row['productName'] }}</span></td>
                                        <td><span class="mono">{{ $row['productBrand'] ?: '-' }}</span></td>
                                        <td><span class="mono">{{ $row['category'] ?: '-' }}</span></td>
                                        <td><span class="mono">{{ $row['unit'] ?: '-' }}</span></td>
                                        <td><span class="mono" style="color:var(--emerald);font-weight:600;">{{ number_format($row['receivedQty']) }}</span></td>
                                        <td><span class="mono" style="color:var(--rose);">{{ number_format($row['returnedQty']) }}</span></td>
                                        <td><span class="mono" style="color:var(--navy-light);font-weight:600;">{{ number_format($row['soldQty']) }}</span></td>
                                        <td><span class="mono" style="color:var(--emerald);">Tsh {{ number_format($row['receivedTotal'], 2) }}</span></td>
                                        <td><span class="mono" style="color:var(--amber);">Tsh {{ number_format($row['soldTotal'], 2) }}</span></td>
                                        <td><span class="mono" style="font-weight:600;">{{ number_format($row['remainingQty']) }}</span></td>
                                        <td><span class="mono">Tsh {{ number_format($row['remainingValue'], 2) }}</span></td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($reportRows->hasPages())
                <div style="padding:0.75rem 1.25rem;background:var(--slate-100);border-top:1px solid var(--slate-200);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;">
                    <span style="font-size:0.78rem;color:var(--slate-600);">
                        Showing {{ $reportRows->firstItem() ?? 0 }} to {{ $reportRows->lastItem() ?? 0 }} of {{ number_format($reportRows->total()) }} items
                    </span>
                    <div style="display:flex;gap:0.35rem;align-items:center;">
                        @if($reportRows->onFirstPage())
                            <span style="padding:0.38rem 0.7rem;border-radius:7px;border:1.5px solid var(--slate-200);background:var(--slate-100);color:var(--slate-400);font-size:0.78rem;font-family:var(--font);cursor:not-allowed;">&laquo; Prev</span>
                        @else
                            <a href="{{ $reportRows->previousPageUrl() }}" style="padding:0.38rem 0.7rem;border-radius:7px;border:1.5px solid var(--slate-200);background:var(--white);color:var(--navy);font-size:0.78rem;font-family:var(--font);text-decoration:none;transition:all 0.15s;" onmouseover="this.style.background='var(--slate-100)'" onmouseout="this.style.background='var(--white)'">&laquo; Prev</a>
                        @endif

                        @foreach(range(1, $reportRows->lastPage()) as $page)
                            @if($page == $reportRows->currentPage())
                                <span style="padding:0.38rem 0.7rem;border-radius:7px;border:1.5px solid var(--navy);background:var(--navy);color:var(--white);font-size:0.78rem;font-family:var(--font);font-weight:600;">{{ $page }}</span>
                            @else
                                <a href="{{ $reportRows->url($page) }}" style="padding:0.38rem 0.7rem;border-radius:7px;border:1.5px solid var(--slate-200);background:var(--white);color:var(--slate-600);font-size:0.78rem;font-family:var(--font);text-decoration:none;transition:all 0.15s;" onmouseover="this.style.background='var(--slate-100)'" onmouseout="this.style.background='var(--white)'">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($reportRows->hasMorePages())
                            <a href="{{ $reportRows->nextPageUrl() }}" style="padding:0.38rem 0.7rem;border-radius:7px;border:1.5px solid var(--slate-200);background:var(--white);color:var(--navy);font-size:0.78rem;font-family:var(--font);text-decoration:none;transition:all 0.15s;" onmouseover="this.style.background='var(--slate-100)'" onmouseout="this.style.background='var(--white)'">Next &raquo;</a>
                        @else
                            <span style="padding:0.38rem 0.7rem;border-radius:7px;border:1.5px solid var(--slate-200);background:var(--slate-100);color:var(--slate-400);font-size:0.78rem;font-family:var(--font);cursor:not-allowed;">Next &raquo;</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

        </div>
    </main>
</div>
</body>
</html>