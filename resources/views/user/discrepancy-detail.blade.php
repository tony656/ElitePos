<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discrepancy Detail - #{{ $discrepancy->id }}</title>
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

        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            color: var(--muted); text-decoration: none; font-size: 13px;
            margin-bottom: 1.5rem;
        }
        .back-link:hover { color: var(--text); }

        .info-section {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .info-section-title {
            font-size: 14px; font-weight: 600; margin-bottom: 1rem;
            padding-bottom: 0.5rem; border-bottom: 2px solid var(--border);
        }

        .info-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;
        }
        .info-item label {
            font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em;
            color: var(--muted); display: block; margin-bottom: 4px;
        }
        .info-item span {
            font-size: 14px; font-weight: 500; font-family: var(--mono);
        }
        .info-item.highlight span {
            font-size: 18px; font-weight: 700;
        }
        .info-item.highlight.positive span { color: var(--green); }
        .info-item.highlight.negative span { color: var(--red); }

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

        .transaction-table {
            width: 100%; border-collapse: collapse; font-size: 13px;
        }
        .transaction-table th {
            background: var(--bg); color: var(--muted);
            font-size: 10.5px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.07em; padding: 9px 12px; text-align: left;
            border-bottom: 1px solid var(--border);
        }
        .transaction-table td {
            padding: 10px 12px; border-bottom: 1px solid var(--border);
        }
        .transaction-table tr:last-child td { border-bottom: none; }
        .transaction-table tr:hover { background: var(--bg); }

        .note-box {
            background: var(--bg); border: 1px solid var(--border);
            border-radius: var(--radius-sm); padding: 1rem 1.25rem;
            margin-top: 1.5rem;
        }
        .note-box-title {
            font-size: 13px; font-weight: 600; margin-bottom: 0.5rem;
            color: var(--muted);
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
                <a href="{{ url('user/balance-check/discrepancies/' . $discrepancy->shop_id . '/' . $discrepancy->balance->balance_date) }}" class="back-link">← Back to Discrepancies</a>
                <div class="page-title">Discrepancy Detail</div>
                <div class="page-sub">
                    #{{ $discrepancy->id }} · {{ $discrepancy->getTypeLabelAttribute() }} · 
                    {{ $discrepancy->balance->shop->name ?? 'Unknown Shop' }} · 
                    {{ $discrepancy->balance->balance_date }}
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ url('shopReport?date=' . $discrepancy->balance->balance_date) }}" class="btn-icon">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6">
                        <rect x="1" y="1" width="14" height="14" rx="2"/>
                        <path d="M5 5l6 6M11 5l-6 6"/>
                    </svg>
                    Back to Report
                </a>
            </div>
        </div>

        {{-- Discrepancy Info --}}
        <div class="info-section">
            <div class="info-section-title">Discrepancy Information</div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Type</label>
                    <span>{{ $discrepancy->getTypeLabelAttribute() }}</span>
                </div>
                <div class="info-item">
                    <label>Severity</label>
                    <span class="badge badge-{{ $discrepancy->severity }}">{{ ucfirst($discrepancy->severity) }}</span>
                </div>
                <div class="info-item">
                    <label>Status</label>
                    <span class="{{ $discrepancy->is_resolved ? 'txt-green' : 'txt-red' }}">
                        {{ $discrepancy->is_resolved ? 'Resolved' : 'Unresolved' }}
                    </span>
                </div>
                <div class="info-item">
                    <label>Created</label>
                    <span>{{ $discrepancy->created_at->format('M d, Y H:i') }}</span>
                </div>
                @if($discrepancy->is_resolved)
                <div class="info-item">
                    <label>Resolved By</label>
                    <span>{{ $discrepancy->resolver->name ?? 'Unknown' }}</span>
                </div>
                <div class="info-item">
                    <label>Resolved At</label>
                    <span>{{ $discrepancy->resolved_at->format('M d, Y H:i') }}</span>
                </div>
                @endif
            </div>

            <div class="info-item" style="margin-top: 1rem;">
                <label>Description</label>
                <span style="font-size: 14px; line-height: 1.6;">{{ $discrepancy->description }}</span>
            </div>

            @if($discrepancy->resolution_notes)
            <div class="info-item" style="margin-top: 1rem;">
                <label>Resolution Notes</label>
                <span style="font-size: 14px; line-height: 1.6; color: var(--green);">
                    {{ $discrepancy->resolution_notes }}
                </span>
            </div>
            @endif
        </div>

        {{-- Financial Impact --}}
        <div class="info-section">
            <div class="info-section-title">Financial Impact</div>
            <div class="info-grid">
                <div class="info-item highlight">
                    <label>Expected Value</label>
                    <span class="{{ $discrepancy->expected_value >= 0 ? 'txt-green' : 'txt-red' }}">
                        TSh {{ number_format($discrepancy->expected_value, 2) }}
                    </span>
                </div>
                <div class="info-item highlight">
                    <label>Actual Value</label>
                    <span class="{{ $discrepancy->actual_value >= 0 ? 'txt-green' : 'txt-red' }}">
                        TSh {{ number_format($discrepancy->actual_value, 2) }}
                    </span>
                </div>
                <div class="info-item highlight {{ $discrepancy->impact_amount > 0 ? 'negative' : 'positive' }}">
                    <label>Impact Amount</label>
                    <span>
                        TSh {{ number_format(abs($discrepancy->impact_amount), 2) }}
                        ({{ $discrepancy->impact_amount > 0 ? 'Negative' : 'Positive' }})
                    </span>
                </div>
            </div>
        </div>

        {{-- Related Transactions --}}
        @if($relatedTransactions && $relatedTransactions->isNotEmpty())
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">Related Transactions ({{ $relatedTransactions->count() }})</span>
            </div>
            <div style="overflow-x: auto;">
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Chip</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($relatedTransactions as $tx)
                        <tr>
                            <td><code>{{ $tx->transaction_id }}</code></td>
                            <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $tx->transaction_type) }}</td>
                            <td class="num fw6">{{ number_format($tx->amount, 2) }}</td>
                            <td class="num">{{ number_format($tx->chip_amount, 2) }}</td>
                            <td>
                                <span class="badge {{ $tx->status === 'completed' ? 'badge-ok' : 'badge-bad' }}">
                                    {{ ucfirst($tx->status) }}
                                </span>
                            </td>
                            <td>{{ $tx->description }}</td>
                            <td>{{ $tx->transaction_date->format('H:i:s') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Note for users --}}
        @if(!$discrepancy->is_resolved)
        <div class="note-box">
            <div class="note-box-title">💡 Note</div>
            <p style="font-size: 13px; color: var(--muted); margin: 0;">
                If you believe this discrepancy is incorrect or have questions, please contact your administrator for assistance.
            </p>
        </div>
        @endif

    </main>
</div>
</body>
</html>