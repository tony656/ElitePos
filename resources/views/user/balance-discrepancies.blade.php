<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Discrepancies - {{ $shop->name ?? 'Unknown Shop' }} - {{ $date }}</title>
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

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .summary-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 1.25rem;
            box-shadow: var(--shadow);
        }
        .summary-label { font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; color: var(--muted); margin-bottom: 6px; }
        .summary-value { font-size: 28px; font-weight: 700; font-family: var(--mono); }
        .summary-card.critical .summary-value { color: var(--red); }
        .summary-card.high .summary-value { color: var(--orange); }
        .summary-card.medium .summary-value { color: var(--amber); }
        .summary-card.low .summary-value { color: var(--blue); }

        .tbl-scroll { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }

        thead th {
            background: var(--bg); color: var(--muted);
            font-size: 10.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em;
            padding: 9px 12px; text-align: left; white-space: nowrap;
            border-bottom: 1px solid var(--border);
        }

        tbody tr { border-bottom: 1px solid var(--border); transition: background 0.12s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #F9F8F4; }
        tbody tr.warn-row { background: #FFFBF0; }
        tbody tr.warn-row:hover { background: #FFF5D6; }

        td { padding: 10px 12px; text-align: left; vertical-align: middle; }

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

        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            color: var(--muted); text-decoration: none; font-size: 13px;
            margin-bottom: 1.5rem;
        }
        .back-link:hover { color: var(--text); }

        .info-box {
            background: var(--bg); border: 1px solid var(--border);
            border-radius: var(--radius-sm); padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }
        .info-box-title { font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .info-box-content { font-size: 13px; color: var(--muted); }

        .discrepancy-type-header {
            font-size: 13px; font-weight: 600; color: var(--text);
            padding: 0.75rem 1rem;
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .transaction-list {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius-sm); margin-bottom: 1rem;
        }
        .transaction-item {
            padding: 0.75rem 1rem; border-bottom: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
            font-size: 12px;
        }
        .transaction-item:last-child { border-bottom: none; }
        .transaction-item:hover { background: var(--bg); }
        .tx-type { font-weight: 500; color: var(--text); }
        .tx-ref { color: var(--muted); font-size: 11px; margin-left: 8px; }
        .tx-amount { font-family: var(--mono); font-weight: 600; }
        .tx-amount.positive { color: var(--green); }
        .tx-amount.negative { color: var(--red); }

        .detail-section {
            margin-top: 1.5rem;
        }
        .detail-title {
            font-size: 14px; font-weight: 600; margin-bottom: 1rem;
            padding-bottom: 0.5rem; border-bottom: 2px solid var(--border);
        }
    </style>
</head>
<body>
<div class="row">
    @include('user/sidenav')

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
                <a href="{{ url('shopReport?date=' . $date) }}" class="back-link">← Back to Shop Report</a>
                <div class="page-title">Balance Discrepancies</div>
                <div class="page-sub">
                    {{ $shop->name ?? 'Unknown Shop' }} — {{ $date }}
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ url('shopReport?date=' . $date) }}" class="btn-icon">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6">
                        <rect x="1" y="1" width="14" height="14" rx="2"/>
                        <path d="M5 5l6 6M11 5l-6 6"/>
                    </svg>
                    Back to Report
                </a>
            </div>
        </div>

        {{-- Balance Summary --}}
        @if($balance)
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-label">Expected Cash</div>
                <div class="summary-value">{{ number_format($balance->expected_cash, 2) }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Cash Submitted</div>
                <div class="summary-value">{{ number_format($balance->cash_submitted, 2) }}</div>
            </div>
            <div class="summary-card {{ abs($balance->cash_difference) > 0.01 ? 'critical' : '' }}">
                <div class="summary-label">Difference</div>
                <div class="summary-value {{ $balance->cash_difference > 0 ? 'txt-red' : 'txt-green' }}">
                    {{ number_format($balance->cash_difference, 2) }}
                </div>
            </div>
            <div class="summary-card {{ $balance->is_balanced ? 'green' : 'red' }}">
                <div class="summary-label">Status</div>
                <div class="summary-value">{{ $balance->is_balanced ? '✓ Balanced' : '✗ Unbalanced' }}</div>
            </div>
        </div>
        @endif

        {{-- Discrepancy Summary --}}
        @if($summary['total'] > 0)
        <div class="info-box">
            <div class="info-box-title">📊 Discrepancy Summary</div>
            <div class="info-box-content">
                Total: {{ $summary['total'] }} |
                Critical: <span class="txt-red">{{ $summary['critical'] }}</span> |
                High: <span class="txt-amber">{{ $summary['high'] }}</span> |
                Medium: {{ $summary['medium'] }} |
                Low: {{ $summary['low'] }} |
                Resolved: <span class="txt-green">{{ $summary['resolved'] }}</span> |
                Unresolved: <span class="txt-red">{{ $summary['unresolved'] }}</span>
            </div>
        </div>
        @endif

        {{-- Discrepancies by Type --}}
        @if($groupedDiscrepancies->isNotEmpty())
            @foreach($groupedDiscrepancies as $type => $discrepancies)
            <div class="discrepancy-type-header">
                {{ $discrepancies->first()->type_label ?? ucfirst(str_replace('_', ' ', $type)) }} 
                ({{ $discrepancies->count() }})
            </div>
            
            <div class="panel">
                <table>
                    <thead>
                        <tr>
                            <th>Severity</th>
                            <th>Description</th>
                            <th>Expected</th>
                            <th>Actual</th>
                            <th>Impact</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($discrepancies as $disc)
                        <tr class="{{ !$disc->is_resolved ? 'warn-row' : '' }}">
                            <td>
                                <span class="badge badge-{{ $disc->severity }}">
                                    {{ ucfirst($disc->severity) }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 500;">{{ $disc->description }}</div>
                                @if($disc->transaction)
                            <div style="font-size: 11px; color: var(--muted); margin-top: 4px;">
                                Transaction: {{ $disc->transaction->transaction_id ?? $disc->transaction_id }}
                            </div>
                            @endif
                            </td>
                            <td class="num">{{ number_format($disc->expected_value, 2) }}</td>
                            <td class="num">{{ number_format($disc->actual_value, 2) }}</td>
                            <td class="num {{ $disc->impact_amount > 0 ? 'txt-red' : 'txt-green' }}">
                                {{ number_format(abs($disc->impact_amount), 2) }}
                            </td>
                            <td>
                                @if($disc->is_resolved)
                                    <span class="badge badge-resolved">Resolved</span>
                                    <div style="font-size: 11px; color: var(--muted); margin-top: 4px;">
                                        by {{ $disc->resolver->name ?? 'Unknown' }} on {{ $disc->resolved_at->format('M d, Y H:i') }}
                                    </div>
                                @else
                                    <span class="badge badge-critical">Unresolved</span>
                                @endif
                            </td>
                            <td>{{ $disc->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <a href="{{ url('user/balance-check/discrepancy/' . $disc->id) }}" class="btn-icon" style="font-size: 11px; padding: 4px 10px;">
                                    Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach
        @else
            <div class="empty-state">
                <h4>No discrepancies found</h4>
                <p>All transactions are balanced for this shop on {{ $date }}.</p>
            </div>
        @endif

        {{-- Related Transactions --}}
        @if($transactions->isNotEmpty())
        <div class="panel" style="margin-top: 1.5rem;">
            <div class="panel-header">
                <span class="panel-title">All Transactions for {{ $date }} ({{ $transactions->count() }})</span>
            </div>
            <div class="tbl-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Reference</th>
                            <th>Amount</th>
                            <th>Chip</th>
                            <th>Status</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $tx)
                        <tr>
                            <td>{{ $tx->transaction_date->format('H:i:s') }}</td>
                            <td>
                                <span style="font-weight: 500; text-transform: capitalize;">
                                    {{ str_replace('_', ' ', $tx->transaction_type) }}
                                </span>
                            </td>
                            <td>
                                {{ $tx->transaction_id }}
                                @if($tx->reference)
                                    <div style="font-size: 11px; color: var(--muted);">
                                        {{ class_basename($tx->reference_type) }}: {{ $tx->reference_id }}
                                    </div>
                                @endif
                            </td>
                            <td class="num fw6">{{ number_format($tx->amount, 2) }}</td>
                            <td class="num">{{ number_format($tx->chip_amount, 2) }}</td>
                            <td>
                                <span class="badge {{ $tx->status === 'completed' ? 'badge-ok' : 'badge-bad' }}">
                                    {{ ucfirst($tx->status) }}
                                </span>
                            </td>
                            <td>{{ Str::limit($tx->description, 50) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </main>
</div>
</body>
</html>