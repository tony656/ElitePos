<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $supplier->name }} - Debt Details - {{ config("app.name") }}</title>
    @include("links")
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:         #0B1E3D;
            --navy-mid:     #112952;
            --navy-light:   #1A3A6B;
            --amber:        var(--theme-primary);
            --amber-pale:   #FEF3C7;
            --emerald:      #059669;
            --emerald-pale: #D1FAE5;
            --rose:         #E11D48;
            --rose-pale:    #FFE4E6;
            --violet:       #7C3AED;
            --violet-pale:  #EDE9FE;
            --slate-50:     #F8FAFC;
            --slate-100:    #F1F5F9;
            --slate-200:    #E2E8F0;
            --slate-300:    #CBD5E1;
            --slate-400:    #94A3B8;
            --slate-500:    #64748B;
            --slate-600:    #475569;
            --slate-700:    #334155;
            --slate-800:    #1E293B;
            --white:        #FFFFFF;
            --font: 'Sora', system-ui, sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --r: 8px; --r-lg: 13px; --r-xl: 16px;
        }

        body { font-family: var(--font); background: #ECF0F8; color: var(--slate-800); min-height: 100vh; font-size: 14px; line-height: 1.6; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }
        .wrap { padding: 1.5rem 1.75rem 3rem; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
        .au  { animation: fadeUp 0.38s ease both; }
        .au1 { animation-delay:0.04s; } .au2 { animation-delay:0.10s; }

        .alert { display:flex; align-items:center; justify-content:space-between; padding:.75rem 1rem; border-radius:var(--r); margin-bottom:1rem; font-size:13px; font-weight:500; }
        .alert-success { background:var(--emerald-pale); color:var(--emerald); border-left:3px solid var(--emerald); }
        .alert-danger  { background:var(--rose-pale);    color:var(--rose);    border-left:3px solid var(--rose); }
        .close-btn { background:none; border:none; cursor:pointer; color:inherit; font-size:16px; opacity:.6; }
        .close-btn:hover { opacity:1; }

        .detail-header { background:var(--navy); color:var(--white); padding:1.2rem 1.4rem; border-radius:var(--r-xl); margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; box-shadow:0 8px 32px rgba(11,30,61,.25); }
        .detail-header-left { display:flex; align-items:center; gap:10px; }
        .detail-header-icon { width:36px; height:36px; border-radius:var(--r); background:rgba(255,255,255,.12); display:flex; align-items:center; justify-content:center; font-size:16px; }
        .detail-header h3 { font-size:15px; font-weight:700; margin:0; }
        .detail-header p  { font-size:12px; color:rgba(255,255,255,.65); margin:2px 0 0; }
        .back-link { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:var(--r); background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.16); color:rgba(255,255,255,.75); font-family:var(--font); font-size:12.5px; font-weight:500; cursor:pointer; transition:all .15s; text-decoration:none; }
        .back-link:hover { background:rgba(255,255,255,.14); color:var(--white); }

        .metrics-grid { display:grid; grid-template-columns:repeat(4, minmax(0,1fr)); gap:1rem; margin-bottom:1.4rem; }
        @media(max-width:900px){ .metrics-grid { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:480px){ .metrics-grid { grid-template-columns:1fr; } }

        .metric-card { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-lg); padding:1.1rem 1.2rem; box-shadow:0 1px 4px rgba(11,30,61,.05); position:relative; overflow:hidden; }
        .metric-card::after { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:var(--mc); }
        .mc-navy    { --mc:var(--navy); }
        .mc-rose    { --mc:var(--rose); }
        .mc-emerald { --mc:var(--emerald); }
        .mc-amber   { --mc:var(--amber); }
        .metric-inner { display:flex; align-items:center; gap:12px; }
        .metric-icon { width:42px; height:42px; border-radius:var(--r); flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:17px; }
        .mi-navy    { background:rgba(11,30,61,.08); color:var(--navy); }
        .mi-rose    { background:var(--rose-pale);    color:var(--rose); }
        .mi-emerald { background:var(--emerald-pale); color:var(--emerald); }
        .mi-amber   { background:var(--amber-pale);   color:#92400e; }
        .metric-label { font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--slate-400); margin-bottom:4px; }
        .metric-value { font-family:var(--mono); font-size:20px; font-weight:500; color:var(--navy); letter-spacing:-.5px; line-height:1; }
        .mv-red   { color:var(--rose); }
        .mv-green { color:var(--emerald); }

        .panel { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-xl); overflow:hidden; box-shadow:0 1px 4px rgba(11,30,61,.05); }
        .panel-head { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.4rem; border-bottom:1.5px solid var(--slate-200); background:var(--slate-50); gap:1rem; flex-wrap:wrap; }
        .panel-head-left { display:flex; align-items:center; gap:10px; }
        .panel-head-icon { width:30px; height:30px; border-radius:var(--r); background:rgba(11,30,61,.07); color:var(--navy-light); display:flex; align-items:center; justify-content:center; font-size:13px; }
        .panel-title { font-size:13.5px; font-weight:700; color:var(--navy); }
        .result-pill { font-size:11px; font-weight:600; font-family:var(--mono); background:var(--slate-200); color:var(--slate-600); padding:2px 9px; border-radius:20px; }

        .tbl-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; font-size:13px; }
        thead th { background:var(--navy); color:rgba(255,255,255,.65); font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; padding:10px 16px; white-space:nowrap; border:none; text-align:left; }
        thead th:last-child { text-align:right; }
        tbody tr { border-bottom:1px solid var(--slate-100); transition:background .12s; }
        tbody tr:last-child { border-bottom:none; }
        tbody tr:hover td { background:#F7F9FF; }
        td { padding:11px 16px; vertical-align:middle; }
        td:last-child { text-align:right; }

        .idx-cell  { font-size:12px; color:var(--slate-400); font-family:var(--mono); }
        .mono-val   { font-family:var(--mono); font-size:12.5px; font-weight:500; }
        .credit-val { color:var(--rose); }
        .paid-val   { color:var(--emerald); }
        .remaining-val { font-weight:700; }

        .badge-paid      { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; background:var(--emerald-pale); color:var(--emerald); }
        .badge-credit    { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; background:var(--rose-pale);    color:var(--rose); }

        .action-btns { display:flex; gap:5px; justify-content:flex-end; flex-wrap:wrap; align-items:center; }
        .act-btn { display:inline-flex; align-items:center; gap:5px; padding:6px 13px; border-radius:var(--r); font-family:var(--font); font-size:12px; font-weight:600; cursor:pointer; border:1.5px solid; transition:all .15s; white-space:nowrap; background:transparent; text-decoration:none; }
        .ab-pay  { border-color:var(--emerald); color:var(--emerald); }
        .ab-pay:hover  { background:var(--emerald); color:var(--white); }
        .ab-view { border-color:var(--navy-light); color:var(--navy-light); }
        .ab-view:hover { background:var(--navy); color:var(--white); }
        .ab-del  { border-color:var(--rose); color:var(--rose); }
        .ab-del:hover  { background:var(--rose); color:var(--white); }
        .ab-edit { border-color:var(--amber); color:#92400e; }
        .ab-edit:hover { background:var(--amber); color:var(--navy); }

        .empty-state { text-align:center; padding:4rem 1.5rem; color:var(--slate-400); }
        .empty-state i  { font-size:2.5rem; display:block; margin-bottom:.75rem; opacity:.3; }
        .empty-state h4 { font-size:15px; font-weight:600; color:var(--slate-600); margin-bottom:5px; }
        .empty-state p  { font-size:13px; }

        .inline-form { display:inline-flex; align-items:center; gap:6px; }
        .inline-form input[type="number"] { width:90px; padding:5px 8px; border:1.5px solid var(--slate-200); border-radius:var(--r); font-family:var(--font); font-size:12px; color:var(--slate-800); }
        .inline-form input[type="number"]:focus { border-color:var(--navy-light); outline:none; }
        .btn-sm { padding:5px 12px; border-radius:var(--r); font-family:var(--font); font-size:11px; font-weight:600; cursor:pointer; border:none; transition:all .15s; }
        .btn-success { background:var(--emerald); color:var(--white); }
        .btn-success:hover { background:#047857; }
        .btn-danger { background:var(--rose); color:var(--white); }
        .btn-danger:hover { background:#be123c; }
        .btn-warning { background:var(--amber); color:var(--navy); }
        .btn-warning:hover { background:#FBBF24; }
        .btn-secondary { background:var(--slate-200); color:var(--slate-700); }
        .btn-secondary:hover { background:var(--slate-300); }

        .payment-history { margin-top:1.25rem; }
        .payment-history table { font-size:12px; }
        .payment-history th { background:var(--slate-100); color:var(--slate-600); font-size:10px; padding:6px 12px; }
        .payment-history td { padding:6px 12px; border-bottom:1px solid var(--slate-100); }

        @media(max-width:768px){ .wrap{padding:1rem;} .action-btns{flex-direction:column;} }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @include("sidenav")

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
            <div class="wrap">
                {{-- Alerts --}}
                @if(session('success'))
                <div class="alert alert-success au au1">
                    <span><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</span>
                    <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger au au1">
                    <span><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</span>
                    <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
                </div>
                @endif

                {{-- Detail header --}}
                <div class="detail-header au au1">
                    <div class="detail-header-left">
                        <div class="detail-header-icon"><i class="bi bi-person"></i></div>
                        <div>
                            <h3>{{ $supplier->name }}</h3>
                            <p>Supplier ID: {{ $supplier->id }} | Total Orders: {{ count($debtDetails) }}</p>
                        </div>
                    </div>
                    <a href="{{ url('supplier-debts') }}?{{ http_build_query(request()->except('supplierId')) }}" class="back-link">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>

                {{-- Metrics --}}
                <div class="metrics-grid au au2">
                    <div class="metric-card mc-navy">
                        <div class="metric-inner">
                            <div class="metric-icon mi-navy"><i class="bi bi-receipt"></i></div>
                            <div><div class="metric-label">Total Orders</div><div class="metric-value">{{ count($debtDetails) }}</div></div>
                        </div>
                    </div>
                    <div class="metric-card mc-rose">
                        <div class="metric-inner">
                            <div class="metric-icon mi-rose"><i class="bi bi-exclamation-circle"></i></div>
                            <div><div class="metric-label">Total Debt</div><div class="metric-value mv-red">{{ number_format($totalDebt) }}</div></div>
                        </div>
                    </div>
                    <div class="metric-card mc-emerald">
                        <div class="metric-inner">
                            <div class="metric-icon mi-emerald"><i class="bi bi-cash-stack"></i></div>
                            <div><div class="metric-label">Total Paid</div><div class="metric-value mv-green" style="font-size:16px;">{{ number_format($totalPaid) }}</div></div>
                        </div>
                    </div>
                    <div class="metric-card mc-amber">
                        <div class="metric-inner">
                            <div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div>
                            <div><div class="metric-label">Remaining</div><div class="metric-value mv-red" style="font-size:16px;">{{ number_format($totalDebt - $totalPaid) }}</div></div>
                        </div>
                    </div>
                </div>

                {{-- Debt details table --}}
                <div class="panel au au3">
                    <div class="panel-head">
                        <div class="panel-head-left">
                            <div class="panel-head-icon"><i class="bi bi-table"></i></div>
                            <span class="panel-title">Debt Records</span>
                            <span class="result-pill">{{ count($debtDetails) }} records</span>
                        </div>
                    </div>

                    <div class="tbl-wrap">
                        @if(empty($debtDetails) || count($debtDetails) == 0)
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <h4>No debt records found</h4>
                            <p>This supplier has no credit orders</p>
                        </div>
                        @else
                        <table>
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Date</th>
                                    <th>Order ID</th>
                                    <th>Status</th>
                                    <th>Total Amount</th>
                                    <th>Paid</th>
                                    <th>Remaining</th>
                                    <th style="text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $rowIndex = 1; @endphp
                                @foreach ($debtDetails as $detail)
                                @php
                                    $receiving = $detail['receiving'];
                                    $paid = $detail['paid'];
                                    $remaining = $detail['remaining'];
                                    $isFullyPaid = $receiving->isPaid == 1 || $remaining <= 0;
                                    $orderDate = date('Y-m-d', strtotime($receiving->created_at));
                                @endphp
                                <tr>
                                    <td class="idx-cell">{{ $rowIndex++ }}</td>
                                    <td><span class="mono-val">{{ $orderDate }}</span></td>
                                    <td><span class="mono-val">{{ $receiving->receivingId }}</span></td>
                                    <td>
                                        @if($isFullyPaid)
                                            <span class="badge-paid"><i class="bi bi-check-circle-fill"></i> Paid</span>
                                        @else
                                            <span class="badge-credit"><i class="bi bi-exclamation-triangle-fill"></i> Credit</span>
                                        @endif
                                    </td>
                                    <td><span class="mono-val credit-val">{{ number_format($receiving->price * $receiving->quantity) }}</span></td>
                                    <td><span class="mono-val paid-val">{{ number_format($paid) }}</span></td>
                                    <td>
                                        <span class="mono-val remaining-val {{ $remaining > 0 ? 'credit-val' : 'paid-val' }}">
                                            {{ number_format($remaining) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            @if(!$isFullyPaid)
                                            <form method="POST" action="{{ url('process-debt-payment') }}" class="inline-form" style="display:inline-flex;">
                                                @csrf
                                                <input type="hidden" name="receivingId" value="{{ $receiving->receivingId }}">
                                                <input type="hidden" name="supplierId" value="{{ $supplier->id }}">
                                                <input type="hidden" name="shop" value="{{ $selectedShopId }}">
                                                <input type="hidden" name="amount" value="{{ $remaining }}">
                                                <button type="submit" class="btn-sm btn-success" title="Pay Full Amount">
                                                    <i class="bi bi-cash-coin"></i> Pay
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ url('update-debt-payment') }}" class="inline-form" style="display:inline-flex;" id="edit-form-{{ $receiving->receivingId }}">
                                                @csrf
                                                <input type="hidden" name="receivingId" value="{{ $receiving->receivingId }}">
                                                <input type="hidden" name="supplierId" value="{{ $supplier->id }}">
                                                <input type="hidden" name="shop" value="{{ $selectedShopId }}">
                                                <input type="number" name="amount" value="{{ $paid }}" step="0.01" min="0" max="{{ $receiving->price * $receiving->quantity }}" required>
                                                <button type="submit" class="btn-sm btn-warning" title="Update Payment">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            </form>
                                            @endif

                                            <form method="POST" action="{{ url('delete-debt-record') }}" class="inline-form" style="display:inline-flex;" onsubmit="return confirm('Are you sure you want to delete this debt record?')">
                                                @csrf
                                                <input type="hidden" name="receivingId" value="{{ $receiving->receivingId }}">
                                                <input type="hidden" name="supplierId" value="{{ $supplier->id }}">
                                                <input type="hidden" name="shop" value="{{ $selectedShopId }}">
                                                <button type="submit" class="btn-sm btn-danger" title="Delete Record">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>

                                            <a href="{{ url('supplier-items') }}?supplier={{ $supplier->id }}&shop={{ $selectedShopId }}&date_from={{ request('date_from', date('Y-m-d')) }}&date_to={{ request('date_to', date('Y-m-d')) }}" class="btn-sm btn-secondary" style="text-decoration:none; display:inline-flex; align-items:center; gap:4px;" target="_blank">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Payment history --}}
                                @if(count($detail['payment_history']) > 0)
                                <tr>
                                    <td colspan="8" style="padding:0; background:var(--slate-50);">
                                        <div class="payment-history" style="padding:1rem 1.4rem;">
                                            <strong style="font-size:11px; text-transform:uppercase; letter-spacing:.05em; color:var(--slate-500);">Payment History</strong>
                                            <table style="margin-top:0.5rem;">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Amount</th>
                                                        <th>Account</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($detail['payment_history'] as $payment)
                                                    <tr>
                                                        <td>{{ date('Y-m-d H:i', strtotime($payment->created_at)) }}</td>
                                                        <td class="paid-val" style="font-weight:600;">{{ number_format($payment->amount) }}</td>
                                                        <td>{{ $payment->account }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
    // Prevent double-click on action buttons
    document.querySelectorAll('.act-btn, .btn-sm').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (this.dataset.submitted) {
                e.preventDefault();
                return false;
            }
            this.dataset.submitted = 'true';
            setTimeout(() => { this.dataset.submitted = ''; }, 3000);
        });
    });
</script>
</body>
</html>