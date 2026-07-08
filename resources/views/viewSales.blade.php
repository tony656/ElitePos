<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} — Sales Receipt</title>
    @include("links")
    
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&family=Libre+Barcode+128&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:         #0B1E3D;
            --navy-mid:     #112952;
            --navy-light:   #1A3A6B;
            --amber:        #F59E0B;
            --amber-pale:   #FEF3C7;
            --emerald:      #059669;
            --emerald-pale: #D1FAE5;
            --rose:         #E11D48;
            --rose-pale:    #FFE4E6;
            --slate-50:     #F8FAFC;
            --slate-100:    #F1F5F9;
            --slate-200:    #E2E8F0;
            --slate-300:    #CBD5E1;
            --slate-400:    #94A3B8;
            --slate-500:    #64748B;
            --slate-600:    #475569;
            --slate-800:    #1E293B;
            --white:        #FFFFFF;
            --font: 'Sora', system-ui, sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --r: 8px; --r-lg: 12px;
        }

        body {  background: #ECF0F8; color: var(--slate-800); font-size: 14px; line-height: 1.6; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }

        /* ══ TOOLBAR (no-print) ══ */
        .toolbar {
            background: var(--white); border-bottom: 1.5px solid var(--slate-200);
            padding: .875rem 1.5rem; display: flex; align-items: center;
            justify-content: space-between; gap: 1rem; flex-wrap: wrap;
            position: sticky; top: 0; z-index: 10;
            box-shadow: 0 1px 4px rgba(11,30,61,.06);
        }
        .toolbar-left { display: flex; align-items: center; gap: 8px; }
        .tool-title { font-size: 14px; font-weight: 700; color: var(--navy); }
        .tool-sub   { font-size: 11.5px; color: var(--slate-400);  margin-left: 8px; }
        .toolbar-right { display: flex; gap: 8px; }

        .btn-back { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: var(--r); border: 1.5px solid var(--slate-200); background: transparent;  font-size: 13px; color: var(--slate-600); cursor: pointer; transition: all .15s; text-decoration: none; }
        .btn-back:hover { background: var(--slate-100); color: var(--slate-800); }
        .btn-print { display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px; border-radius: var(--r); border: none; background: var(--navy); color: var(--white);  font-size: 13px; font-weight: 600; cursor: pointer; transition: all .15s; box-shadow: 0 2px 8px rgba(11,30,61,.2); }
        .btn-print:hover { background: var(--navy-light); }

        /* Alerts */
        .alert { display: flex; align-items: center; gap: 8px; padding: .75rem 1rem; border-radius: var(--r); font-size: 13px; font-weight: 500; margin: 1rem 1.5rem 0; }
        .alert-success { background: var(--emerald-pale); color: var(--emerald); border-left: 3px solid var(--emerald); }
        .alert-danger  { background: var(--rose-pale);    color: var(--rose);    border-left: 3px solid var(--rose); }

        /* ══ RECEIPT WRAP ══ */
        .receipt-outer { padding: 2rem 1.5rem 3rem; display: flex; justify-content: center; }

        .receipt {
            width: 100%; max-width: 720px;
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 4px 32px rgba(11,30,61,.12);
            overflow: hidden;
        }

        /* ── Header ── */
        .rcpt-header {
            background: var(--navy); padding: 2rem 2rem 1.5rem;
            position: relative; overflow: hidden;
        }
        .rcpt-header::before {
            content: ''; position: absolute; top: -60px; right: -40px;
            width: 200px; height: 200px; border-radius: 50%;
            background: var(--navy-light); opacity: .5; pointer-events: none;
        }
        .rcpt-header::after {
            content: ''; position: absolute; bottom: -50px; left: 60px;
            width: 150px; height: 150px; border-radius: 50%;
            background: var(--amber); opacity: .06; pointer-events: none;
        }

        .rcpt-header-inner { position: relative; z-index: 1; display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }

        .brand-block {}
        .brand-name { font-size: 22px; font-weight: 700; color: var(--white); letter-spacing: -.3px; line-height: 1.2; }
        .brand-sub  { font-size: 10px; letter-spacing: .2em; text-transform: uppercase; color: rgba(255,255,255,.4); margin-top: 4px; }
        .brand-meta { font-size: 11px; color: rgba(255,255,255,.55); margin-top: 6px; line-height: 1.5; }

        .rcpt-badge {
            text-align: right;
        }
        .rcpt-type { display: inline-block; background: var(--amber); color: var(--navy); font-size: 10px; font-weight: 800; letter-spacing: .15em; text-transform: uppercase; padding: 4px 12px; border-radius: 20px; }
        .rcpt-id   {  font-size: 13px; color: rgba(255,255,255,.55); margin-top: 6px; }

        .barcode-row {
            margin-top: 1.25rem; padding-top: 1.25rem;
            border-top: 1px solid rgba(255,255,255,.1);
            text-align: center; position: relative; z-index: 1;
        }
        .barcode-font {  font-size: 2.75rem; color: var(--white); line-height: 1; display: block; }
        .barcode-text {  font-size: 10.5px; color: rgba(255,255,255,.4); margin-top: 3px; letter-spacing: .1em; }

        /* ── Divider ── */
        .rcpt-divider { display: flex; align-items: center; gap: 0; }
        .div-circle { width: 22px; height: 22px; border-radius: 50%; background: #ECF0F8; flex-shrink: 0; }
        .div-dashes { flex: 1; border-top: 2px dashed var(--slate-200); }

        /* ── Body sections ── */
        .rcpt-body { padding: 1.5rem 1.75rem; }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem; }
        @media(max-width:540px) { .info-grid { grid-template-columns: 1fr; } }

        .info-card {
            background: var(--slate-50); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg); padding: 1rem 1.1rem;
        }
        .info-card-head {
            display: flex; align-items: center; gap: 7px;
            font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
            color: var(--slate-400); margin-bottom: .75rem;
            padding-bottom: .6rem; border-bottom: 1.5px solid var(--slate-200);
        }
        .info-card-head i { font-size: 13px; color: var(--amber); }

        .info-row { display: flex; justify-content: space-between; gap: .5rem; padding: 5px 0; border-bottom: 1px solid var(--slate-100); }
        .info-row:last-child { border-bottom: none; padding-bottom: 0; }
        .info-label { font-size: 12px; color: var(--slate-400); flex-shrink: 0; }
        .info-value { font-size: 12.5px; font-weight: 600; color: var(--slate-800); text-align: right;  }
        .info-value.plain {  }

        /* Status badge */
        .status-paid    { display: inline-block; padding: 2px 9px; border-radius: 20px; font-size: 11px; font-weight: 700; background: var(--emerald-pale); color: var(--emerald); }
        .status-debt    { display: inline-block; padding: 2px 9px; border-radius: 20px; font-size: 11px; font-weight: 700; background: var(--rose-pale); color: var(--rose); }
        .status-partial { display: inline-block; padding: 2px 9px; border-radius: 20px; font-size: 11px; font-weight: 700; background: var(--amber-pale); color: #92400e; }

        /* ── Items table ── */
        .items-section { margin-bottom: 1.25rem; }
        .items-section-head {
            font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
            color: var(--slate-400); margin-bottom: .65rem; display: flex; align-items: center; gap: 6px;
        }
        .items-section-head::after { content: ''; flex: 1; height: 1px; background: var(--slate-200); }

        .items-table { width: 100%; border-collapse: collapse; }
        .items-table thead th {
            background: var(--navy); color: rgba(255,255,255,.7);
            font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;
            padding: 9px 12px; border: none; text-align: left;
        }
        .items-table thead th:last-child { text-align: right; }
        .items-table tbody tr { border-bottom: 1px solid var(--slate-100); transition: background .1s; }
        .items-table tbody tr:last-child { border-bottom: none; }
        .items-table tbody tr:hover td { background: var(--slate-50); }
        .items-table td { padding: 10px 12px; vertical-align: middle; font-size: 13px; }
        .items-table td:last-child { text-align: right;  font-weight: 600; color: var(--slate-800); }

        .idx-cell  { font-size: 11.5px; color: var(--slate-400);  width: 32px; }
        .prod-btn  {
            background: none; border: none; cursor: pointer; text-align: left;
             font-size: 13px; font-weight: 600; color: var(--navy);
            padding: 0; transition: color .15s;
        }
        .prod-btn:hover { color: var(--amber); text-decoration: underline; }
        .qty-chip  { display: inline-block; padding: 2px 9px; border-radius: 20px; background: var(--slate-100); color: var(--slate-600);  font-size: 12px; font-weight: 600; }

        /* ── Totals block ── */
        .totals-block {
            background: var(--slate-50); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg); overflow: hidden;
        }
        .total-row { display: flex; justify-content: space-between; align-items: center; padding: 9px 16px; border-bottom: 1px solid var(--slate-100); font-size: 13px; }
        .total-row:last-child { border-bottom: none; }
        .total-label { color: var(--slate-500); font-weight: 500; }
        .total-val   {  font-weight: 600; color: var(--slate-800); }
        .total-row.discount .total-val { color: var(--emerald); }
        .total-row.paid-row .total-val { color: var(--emerald); }

        .grand-row {
            background: var(--navy); display: flex; justify-content: space-between; align-items: center;
            padding: 13px 16px;
        }
        .grand-label { font-size: 13px; font-weight: 700; color: rgba(255,255,255,.75); text-transform: uppercase; letter-spacing: .06em; }
        .grand-val   {  font-size: 18px; font-weight: 700; color: var(--amber); }

        /* ── Footer ── */
        .rcpt-footer {
            text-align: center; padding: 1.5rem 1.75rem;
            border-top: 1.5px solid var(--slate-100);
        }
        .footer-thanks { font-size: 15px; font-weight: 700; color: var(--navy); margin-bottom: 5px; }
        .footer-sub    { font-size: 12px; color: var(--slate-400); margin-bottom: .875rem; }
        .footer-meta   { font-size: 11px; color: var(--slate-300);  }

        .footer-dots { display: flex; justify-content: center; gap: 5px; margin-bottom: 1rem; }
        .footer-dot { width: 6px; height: 6px; border-radius: 50%; }
        .fd-amber   { background: var(--amber); }
        .fd-navy    { background: var(--navy); }
        .fd-slate   { background: var(--slate-300); }

        /* ══ PRINT STYLES ══ */
        @media print {
            .no-print, .toolbar { display: none !important; }
            body { background: white; font-size: 11pt; }
            .receipt-outer { padding: .5rem; }
            .receipt { box-shadow: none; border-radius: 0; max-width: 100%; }
            .rcpt-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .rcpt-header::before, .rcpt-header::after { display: none; }
            .grand-row { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .items-table thead { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .barcode-font { color: black; }
        }

        @media(max-width:640px) {
            .rcpt-body { padding: 1.25rem 1rem; }
        }
    </style>
</head>
<body>

{{-- ══ TOOLBAR (no-print) ══ --}}
@if(!isset($public))
<div class="toolbar no-print">
    <div class="toolbar-left">
        @if(isset($sales) && $sales)
        <a href="#" onclick="history.back()" class="btn-back"><i class="bi bi-arrow-left"></i> Back</a>
        @endif
        <span class="tool-title">Sales Receipt</span>
        <span class="tool-sub">{{ $sales->salesName ?? 'N/A' }}</span>
    </div>
    <div class="toolbar-right">
        @if(isset($sales) && $sales)
        <button onclick="window.print()" class="btn-print">
            <i class="bi bi-printer"></i> Print
        </button>
        @endif
    </div>
</div>
@endif

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success no-print"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger no-print"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
@endif

{{-- ══ RECEIPT ══ --}}
<div class="receipt-outer">
    <div class="receipt">

        @php
            $getName = $getName ?? DB::table('system')->first();
            $bizName = $getName->bName ?? 'SALSTECH';
        @endphp

        @if(isset($error))
        <div class="rcpt-header">
            <div class="rcpt-header-inner">
                <div class="brand-block">
                    <div class="brand-name">{{ $bizName }}</div>
                    <div class="brand-sub">Official Sales Receipt</div>
                </div>
                <div class="rcpt-badge">
                    <div class="rcpt-type">Error</div>
                </div>
            </div>
        </div>
        <div class="rcpt-divider">
            <div class="div-circle" style="margin-left:-11px;"></div>
            <div class="div-dashes"></div>
            <div class="div-circle" style="margin-right:-11px;"></div>
        </div>
        <div class="rcpt-body">
            <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ $error }}</div>
        </div>
        @else
        @php
            $grandTotal = $allsales->sum('totalPrice');
            $remaining  = $grandTotal - ($paid ?? 0);
            $status     = $remaining <= 0 ? 'Paid' : (($paid ?? 0) > 0 ? 'Partial' : 'Debt');
        @endphp

        {{-- ── Header ── --}}
        <div class="rcpt-header">
            <div class="rcpt-header-inner">
                <div class="brand-block">
                    <div class="brand-name">{{ $bizName }}</div>
                    <div class="brand-sub">Official Sales Receipt</div>
                    <div class="brand-meta">
                        @if($getName->phone ?? null)<i class="bi bi-telephone-fill" style="margin-right:4px;"></i>{{ $getName->phone }}@endif
                        @if($getName->address ?? null)
                            <br><i class="bi bi-geo-alt-fill" style="margin-right:4px;"></i>{{ $getName->address }}
                        @endif
                    </div>
                </div>
                <div class="rcpt-badge">
                    <div class="rcpt-type">@if(isset($public))Verified & Legit Receipt @endif</div>
                    <div class="rcpt-id">#{{ $sales->salesName ?? '000000' }}</div>
                </div>
            </div>
      
        </div>

        {{-- Tear edge --}}
        <div class="rcpt-divider">
            <div class="div-circle" style="margin-left:-11px;"></div>
            <div class="div-dashes"></div>
            <div class="div-circle" style="margin-right:-11px;"></div>
        </div>

        {{-- ── Body ── --}}
        <div class="rcpt-body">

            {{-- Info cards row --}}
            <div class="info-grid">
                @if(!isset($public))
                {{-- Business info --}}
                <div class="info-card">
                    <div class="info-card-head"><i class="bi bi-shop"></i> Business</div>
                    <div class="info-row">
                        <span class="info-label">Address</span>
                        <span class="info-value plain">{{ $getName->address ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <span class="info-value">{{ $getName->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value plain" style="font-size:11.5px;">{{ $getName->email ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Lipa</span>
                        <span class="info-value">{{ $getName->lipaCode ?? 'N/A' }}</span>
                    </div>
                </div>
                @endif
                {{-- Customer info --}}
                <div class="info-card">
                    <div class="info-card-head"><i class="bi bi-person-circle"></i> Customer</div>
                    <div class="info-row">
                        <span class="info-label">Name</span>
                        <span class="info-value plain">{{ $sales->cName ?? 'Walk-in' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <span class="info-value">{{ $sales->cPhone ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date</span>
                        <span class="info-value">{{ $sales->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            @if($status === 'Paid')
                                <span class="status-paid">Paid</span>
                            @elseif($status === 'Partial')
                                <span class="status-partial">Partial</span>
                            @else
                                <span class="status-debt">Debt</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- ── Items ── --}}
            <div class="items-section">
                <div class="items-section-head"><i class="bi bi-box-seam" style="color:var(--amber);"></i> Purchased Items</div>
                <div style="border-radius:var(--r-lg); overflow-x:auto; border:1.5px solid var(--slate-200);">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th style="width:32px;">#</th>
                                <th>Product</th>
                                <th style="width:60px; text-align:center;">Qty</th>
                                <th>Discount</th>
                                <th style="text-align:right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allsales as $index => $sale)
                            @php
                                $pNames = DB::table('products')->where('product_id', $sale->productId)->first();
                                if($sale->offered_items == 1) {
                                    $productLabel = "🎁".trim(($pNames->name01 ?? '') . ' ~ Offer');
                                } else {
                                
                                $productLabel = trim(($pNames->name01 ?? '') . ' ~ ' . ($pNames->name02 ?? '')) ?: 'Unknown Product';
                                }
                            @endphp
                            <tr>
                                <td class="idx-cell">{{ $index + 1 }}</td>
                                <td>
                                    <form action="" method="post" style="display:inline;">
                                        @csrf
                                        <button name="product_id" formaction="viewProduct" value="{{ $sale->productId }}" class="prod-btn">
                                            {{ $productLabel }}
                                        </button>
                                    </form>
                                </td>
                                <td style="text-align:center;"><span class="qty-chip">{{ $sale->pQuantity }}</span></td>
                                <td class="text-danger">{{ $sale->discount }}</td>
                                <td>Tsh {{ number_format($sale->totalPrice) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── Totals ── --}}
            <div class="totals-block">
                <div class="total-row">
                    <span class="total-label">Subtotal</span>
                    <span class="total-val">Tsh {{ number_format($grandTotal + $allsales->sum('discount') ?? 0) }}</span>
                </div>

                <div class="total-row discount">
                    <span class="total-label">Discount</span>
                    <span class="total-val text-danger">− Tsh {{ number_format($allsales->sum('discount') ?? 0) }}</span>
                </div>
             
                <div class="total-row paid-row">
                    <span class="total-label">Amount paid</span>
                    <span class="total-val">Tsh {{ number_format($paid ?? 0) }}</span>
                </div>
                
                @if($remaining > 0)
                <div class="total-row">
                    <span class="total-label" style="color:var(--rose);">Remaining</span>
                    <span class="total-val" style="color:var(--rose);">Tsh {{ number_format($remaining) }}</span>
                </div>
                @endif

                <div class="grand-row">
                    <span class="grand-label">Grand total</span>
                    <span class="grand-val">Tsh {{ number_format($grandTotal) }}</span>
    </div>

     {{-- ── Footer ── --}}
        <div class="rcpt-footer">
            <div class="footer-dots">
                <div class="footer-dot fd-amber"></div>
                <div class="footer-dot fd-navy"></div>
                <div class="footer-dot fd-slate"></div>
                <div class="footer-dot fd-navy"></div>
                <div class="footer-dot fd-amber"></div>
            </div>
            <div class="footer-thanks">Thank you for your business!</div>
            <div class="footer-sub">This receipt serves as your official proof of purchase.</div>
            <div class="footer-meta">
                Receipt ID: {{ $sales->sales_id ?? 'N/A' }} &nbsp;·&nbsp;
                Generated: {{ now()->format('d M Y, h:i A') }}
            </div>
            <div style="margin-top:.75rem; text-align:center;">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode(url('/receipt?sales_id=' . ($sales->sales_id ?? ''))) }}" 
                     alt="Receipt QR" 
                     style="width:90px; height:90px; border-radius:6px; border:1px solid var(--slate-200); display:block; margin:0 auto;">
                <div style="font-size:10px; color:var(--slate-400); margin-top:4px;">
                    @if(isset($public))Scan to verify receipt@else Scan to view receipt @endif
                </div>
                @if(isset($public))
                <div style="margin-top:.75rem;">
                    <button onclick="window.print()" style="background:var(--navy); color:var(--white); border:none; padding:9px 22px; border-radius:20px; font-size:12px; font-weight:600; cursor:pointer; box-shadow:0 2px 8px rgba(11,30,61,.2);">
                        <i class="bi bi-printer"></i> Print Receipt
                    </button>
                </div>
                @endif
            </div>
        </div>
        
</div>
@endif
@if(isset($public))
<script>
window.print = function() {};
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
        e.preventDefault();
        e.stopPropagation();
    }
}, true);
</script>
@endif
@include('footer')

</body>
</html>