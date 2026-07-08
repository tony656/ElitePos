<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Discrepancies - Admin</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #F7F6F2;
            --surface:   #FFFFFF;
            --border:    #E4E2DA;
            --border-md: #CECCC4;
            --text:      #0B1E3D;
            --muted:     #7A7870;
            --accent:    #F59E0B;
            --green:     #1A6B45;
            --green-bg:  #E6F4ED;
            --red:       #B63A2F;
            --red-bg:    #FDECEA;
            --amber:     #F59E0B;
            --amber-bg:  #FEF3D7;
            --purple:    #1A3A6B;
            --purple-bg: #EEECFA;
            --orange:    #EA580C;
            --orange-bg: #FFF4E6;
            --blue:      #0369A1;
            --blue-bg:   #E0F2FE;
            --shadow:    0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --radius:    10px;
            --radius-sm: 6px;
            --font:      'DM Sans', system-ui, sans-serif;
            --mono:      'DM Mono', monospace;
        }

        body {
            background: var(--bg);
            font-family: var(--font);
            color: var(--text);
            font-size: 14px;
            line-height: 1.5;
        }

        .layout { display: flex; min-height: 100vh; }
        .sidebar-wrap { flex-shrink: 0; }
        .main {
            flex: 1;
            min-width: 0;
            padding: 2rem 2.5rem;
        }

        .alert {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.75rem 1rem; border-radius: var(--radius-sm);
            margin-bottom: 1.25rem; font-size: 13px;
        }
        .alert-success { background: var(--green-bg); color: var(--green); border: 1px solid #B2DFC5; }
        .alert-danger  { background: var(--red-bg);   color: var(--red);   border: 1px solid #F5C6C2; }
        .btn-close-sm { background: none; border: none; cursor: pointer; font-size: 16px; color: inherit; opacity: 0.6; }
        .btn-close-sm:hover { opacity: 1; }

        .page-header {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 2rem; gap: 1rem; flex-wrap: wrap;
        }
        .page-title { font-size: 22px; font-weight: 600; letter-spacing: -0.3px; }
        .page-sub   { font-size: 13px; color: var(--muted); margin-top: 3px; }

        .header-actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }

        .btn-icon {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 14px; border-radius: var(--radius-sm);
            font-family: var(--font); font-size: 13px; font-weight: 500;
            cursor: pointer; border: 1px solid var(--border-md);
            background: var(--surface); color: var(--text);
            text-decoration: none; transition: background 0.15s;
        }
        .btn-icon:hover { background: var(--bg); }
        .btn-icon svg { width: 14px; height: 14px; }

        .btn-primary {
            background: var(--accent); color: #fff; border: none;
            padding: 8px 18px; border-radius: var(--radius-sm);
            font-family: var(--font); font-size: 13px; font-weight: 500; cursor: pointer;
        }
        .btn-primary:hover { opacity: 0.85; }
        .btn-secondary {
            background: transparent; color: var(--muted); border: 1px solid var(--border-md);
            padding: 8px 18px; border-radius: var(--radius-sm);
            font-family: var(--font); font-size: 13px; cursor: pointer;
        }
        .btn-secondary:hover { background: var(--bg); }

        .panel {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .panel-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.25rem; border-bottom: 1px solid var(--border);
            gap: 1rem; flex-wrap: wrap;
        }
        .panel-title { font-size: 14px; font-weight: 600; }

        .filter-bar {
            display: flex; gap: 10px; flex-wrap: wrap; align-items: end;
            margin-bottom: 1.5rem; padding: 1rem 1.25rem;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius);
        }
        .filter-group {
            display: flex; flex-direction: column; gap: 4px;
        }
        .filter-group label {
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.06em; color: var(--muted);
        }
        .filter-group input, .filter-group select {
            padding: 6px 10px; border: 1px solid var(--border-md);
            border-radius: var(--radius-sm); font-family: var(--font);
            font-size: 13px; background: var(--surface); color: var(--text);
            min-width: 150px;
        }
        .filter-group input:focus, .filter-group select:focus {
            outline: none; border-color: var(--accent);
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem; margin-bottom: 1.5rem;
        }
        .summary-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 1.25rem;
            box-shadow: var(--shadow);
        }
        .summary-label { font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; color: var(--muted); margin-bottom: 6px; }
        .summary-value { font-size: 24px; font-weight: 700; font-family: var(--mono); }
        .summary-card.critical .summary-value { color: var(--red); }
        .summary-card.high .summary-value { color: var(--orange); }
        .summary-card.medium .summary-value { color: var(--amber); }
        .summary-card.low .summary-value { color: var(--blue); }

        .tbl-scroll { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }

        thead th {
            background: var(--bg); color: var(--muted);
            font-size: 10.5px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.07em; padding: 9px 12px; text-align: left;
            border-bottom: 1px solid var(--border); white-space: nowrap;
        }

        tbody tr { border-bottom: 1px solid var(--border); transition: background 0.12s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #F9F8F4; }
        tbody tr.warn-row { background: #FFFBF0; }
        tbody tr.warn-row:hover { background: #FFF5D6; }

        td { padding: 10px 12px; vertical-align: middle; }

        .badge {
            display: inline-block; padding: 3px 10px; border-radius: 20px;
            font-size: 10.5px; font-weight: 600; letter-spacing: 0.03em;
        }
        .badge-critical { background: var(--red-bg); color: var(--red); }
        .badge-high { background: var(--orange-bg); color: var(--orange); }
        .badge-medium { background: var(--amber-bg); color: var(--amber); }
        .badge-low { background: var(--blue-bg); color: var(--blue); }
        .badge-resolved { background: var(--green-bg); color: var(--green); }

        .txt-green { color: var(--green); }
        .txt-red   { color: var(--red); }
        .txt-amber { color: var(--amber); }
        .txt-muted { color: var(--muted); }

        .empty-state { text-align: center; padding: 4rem 2rem; color: var(--muted); }
        .empty-state h4 { font-size: 16px; font-weight: 500; margin-bottom: 8px; color: var(--text); }
        .empty-state p  { font-size: 13px; }

        .pagination {
            display: flex; gap: 6px; justify-content: center; margin-top: 1.5rem;
        }
        .pagination a, .pagination span {
            padding: 6px 12px; border-radius: var(--radius-sm);
            font-size: 13px; text-decoration: none;
            border: 1px solid var(--border-md);
        }
        .pagination .current {
            background: var(--accent); color: white; border-color: var(--accent);
        }
    </style>
</head>
<body>
<div class="row">
    @include('sidenav')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <button class="btn-close-sm" onclick="this.closest('.alert').remove()">×</button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
                <button class="btn-close-sm" onclick="this.closest('.alert').remove()">×</button>
            </div>
        @endif

        {{-- Page header --}}
        <div class="page-header">
            <div>
                <div class="page-title">All Discrepancies</div>
                <div class="page-sub">System-wide balance issues across all shops</div>
            </div>
            <div class="header-actions">
                <a href="{{ url('shopReport?date=' . date('Y-m-d')) }}" class="btn-icon">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6">
                        <rect x="1" y="1" width="14" height="14" rx="2"/>
                        <path d="M5 5l6 6M11 5l-6 6"/>
                    </svg>
                    Today's Report
                </a>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-label">Total Pending</div>
                <div class="summary-value">{{ $totalPending }}</div>
            </div>
            <div class="summary-card critical">
                <div class="summary-label">Critical Issues</div>
                <div class="summary-value">{{ $summary['critical'] ?? 0 }}</div>
            </div>
            <div class="summary-card high">
                <div class="summary-label">High Priority</div>
                <div class="summary-value">{{ $summary['high'] ?? 0 }}</div>
            </div>
            <div class="summary-card medium">
                <div class="summary-label">Medium</div>
                <div class="summary-value">{{ $summary['medium'] ?? 0 }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Total Resolved</div>
                <div class="summary-value txt-green">{{ $totalResolved }}</div>
            </div>
            <div class="summary-card {{ $totalAmount > 0 ? 'critical' : 'green' }}">
                <div class="summary-label">Total Impact</div>
                <div class="summary-value {{ $totalAmount > 0 ? 'txt-red' : 'txt-green' }}">
                    TSh {{ number_format(abs($totalAmount), 2) }}
                </div>
            </div>
        </div>

        {{-- Filter Bar --}}
        <form method="GET" action="{{ url('balance-check/all') }}" class="filter-bar">
            <div class="filter-group">
                <label>Date From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}">
            </div>
            <div class="filter-group">
                <label>Date To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}">
            </div>
            <div class="filter-group">
                <label>Shop</label>
                <select name="shop_id">
                    <option value="">All Shops</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" {{ $shopFilter == $shop->id ? 'selected' : '' }}>
                            {{ $shop->name ?? $shop->shop_name ?? 'Unknown' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>All</option>
                    <option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="resolved" {{ $statusFilter == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
            </div>
            <div class="filter-group" style="flex-direction: row; align-items: flex-end; gap: 8px;">
                <button type="submit" class="btn-primary">Filter</button>
                <a href="{{ url('balance-check/all') }}" class="btn-icon">Clear</a>
            </div>
        </form>

        {{-- Discrepancies Table --}}
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">
                    Discrepancies 
                    ({{ $discrepancies->total() }} total, {{ $discrepancies->count() }} shown)
                </span>
            </div>
            <div class="tbl-scroll">
                @if($discrepancies->isEmpty())
                    <div class="empty-state">
                        <h4>No discrepancies found</h4>
                        <p>All transactions are balanced for the selected criteria.</p>
                    </div>
                @else
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Shop</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Expected</th>
                            <th>Actual</th>
                            <th>Impact</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($discrepancies as $disc)
                        <tr class="{{ !$disc->is_resolved ? 'warn-row' : '' }}">
                            <td>{{ $disc->id }}</td>
                            <td>{{ $disc->balance->balance_date ?? 'N/A' }}</td>
                            <td>
                                {{ $disc->balance->shop->name ?? 'Unknown' }}
                            </td>
                            <td>
                                <span style="font-weight: 500;">{{ $disc->getTypeLabelAttribute() }}</span>
                            </td>
                            <td style="max-width: 300px;">
                                {{ Str::limit($disc->description, 80) }}
                            </td>
                            <td class="num">{{ number_format($disc->expected_value, 2) }}</td>
                            <td class="num">{{ number_format($disc->actual_value, 2) }}</td>
                            <td class="num {{ $disc->impact_amount > 0 ? 'txt-red' : 'txt-green' }}">
                                {{ number_format(abs($disc->impact_amount), 2) }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $disc->severity }}">{{ ucfirst($disc->severity) }}</span>
                            </td>
                            <td>
                                @if($disc->is_resolved)
                                    <span class="badge badge-resolved">Resolved</span>
                                @else
                                    <span class="badge badge-critical">Unresolved</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('balance-check/discrepancy/' . $disc->id) }}" class="btn-icon" style="font-size: 11px; padding: 4px 8px;">
                                    View
                                </a>
                                @if(!$disc->is_resolved)
                                <form method="POST" action="{{ url('balance-check/resolve/' . $disc->id) }}" style="display: inline;" onsubmit="return confirm('Mark this discrepancy as resolved?')">
                                    @csrf
                                    <button type="submit" class="btn-icon" style="font-size: 11px; padding: 4px 8px; background: var(--green); border-color: var(--green); color: white;">
                                        Resolve
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

            {{-- Pagination --}}
            @if($discrepancies->hasPages())
            <div class="pagination">
                {{ $discrepancies->links() }}
            </div>
            @endif
        </div>

    </main>
</div>
</body>
</html>