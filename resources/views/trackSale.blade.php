<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config('app.name') }} · Track Sale</title>
  @include('links')
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      background: #eef2f9;
      font-family: 'Outfit', sans-serif;
      color: #1e293b;
      min-height: 100vh;
      padding: 1.5rem;
    }
    ::-webkit-scrollbar { width:6px; height:6px; }
    ::-webkit-scrollbar-track { background:#e2e8f0; border-radius:10px; }
    ::-webkit-scrollbar-thumb { background:#94a3b8; border-radius:10px; }

    .track-wrap {
      max-width: 1280px;
      margin: 0 auto;
    }

    /* header */
    .track-header {
      background: #0b1e3d;
      border-radius: 20px;
      padding: 1.4rem 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 12px 30px rgba(11,30,61,0.25);
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
    }
    .header-left { display:flex; align-items:center; gap:1rem; }
    .back-btn {
      background: rgba(255,255,255,0.08);
      border: 1.5px solid rgba(255,255,255,0.15);
      color: white;
      width: 44px; height:44px;
      border-radius: 12px;
      display: flex; align-items:center; justify-content:center;
      font-size: 1.3rem;
      transition:0.2s;
      cursor:pointer;
      text-decoration:none;
    }
    .back-btn:hover { background:rgba(245,158,11,0.2); border-color:#f59e0b; color:#f59e0b; }
    .header-icon {
      background: rgba(245,158,11,0.12);
      border: 1.5px solid rgba(245,158,11,0.25);
      border-radius: 14px;
      width: 54px; height:54px;
      display: flex; align-items:center; justify-content:center;
      color: #f59e0b;
      font-size: 1.8rem;
    }
    .header-title h1 { color:white; font-weight:700; font-size:1.5rem; letter-spacing:-0.3px; margin-bottom:2px; }
    .header-title p { color:rgba(255,255,255,0.65); font-size:0.85rem; margin:0; }
    .header-right { display:flex; align-items:center; gap:1rem; }
    .track-badge {
      background: rgba(255,255,255,0.08);
      padding:0.4rem 1rem;
      border-radius:40px;
      color:#e2e8f0;
      font-size:0.8rem;
      font-weight:500;
      border:1px solid rgba(255,255,255,0.06);
      display:flex;
      align-items:center;
      gap:6px;
    }
    .track-badge i { color:#f59e0b; }

    /* search */
    .search-panel {
      background: white;
      border-radius: 18px;
      padding: 1.25rem 1.5rem;
      margin-bottom: 2rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.02);
      border:1px solid #e2e8f0;
    }
    .search-form {
      display:flex;
      flex-wrap:wrap;
      gap:0.75rem;
      align-items:center;
      position:relative;
    }
    .search-form .bi-search {
      position:absolute;
      left:1rem;
      top:50%;
      transform:translateY(-50%);
      color:#94a3b8;
      font-size:1.1rem;
      pointer-events:none;
    }
    .search-input {
      flex:1 1 240px;
      padding:0.8rem 1rem 0.8rem 2.8rem;
      border:1.5px solid #e2e8f0;
      border-radius:40px;
      background:#f8fafc;
      font-size:0.9rem;
      font-weight:400;
      color:#0f172a;
      transition:0.2s;
      outline:none;
      font-family:'Outfit', sans-serif;
    }
    .search-input:focus { border-color:#1a3a6b; background:white; box-shadow:0 0 0 4px rgba(26,58,107,0.08); }
    .search-input::placeholder { color:#94a3b8; font-weight:300; }
    .btn-track {
      background:#0b1e3d;
      border:none;
      padding:0.8rem 1.6rem;
      border-radius:40px;
      font-weight:600;
      font-size:0.9rem;
      color:white;
      display:inline-flex;
      align-items:center;
      gap:0.6rem;
      transition:0.15s;
      cursor:pointer;
      box-shadow:0 4px 10px rgba(11,30,61,0.15);
      font-family:'Outfit', sans-serif;
    }
    .btn-track:hover { background:#112952; transform:translateY(-2px); box-shadow:0 8px 18px rgba(11,30,61,0.2); }

    /* alert */
    .alert-box {
      display:flex;
      align-items:center;
      gap:0.75rem;
      padding:0.9rem 1.2rem;
      border-radius:14px;
      font-weight:500;
      margin-bottom:1.5rem;
      background:#f1f5f9;
      border-left:5px solid #64748b;
    }
    .alert-box.success { background:#d1fae5; border-left-color:#059669; color:#065f46; }
    .alert-box.error { background:#ffe4e6; border-left-color:#e11d48; color:#9f1239; }

    /* info card */
    .info-card {
      background:white;
      border-radius:18px;
      padding:1.4rem 1.8rem;
      margin-bottom:2rem;
      border:1px solid #e2e8f0;
      box-shadow:0 4px 12px rgba(0,0,0,0.02);
      display:grid;
      grid-template-columns: repeat(auto-fit, minmax(160px,1fr));
      gap:1.2rem;
    }
    .info-item .label { font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.04em; color:#64748b; }
    .info-item .value { font-weight:700; font-size:1.05rem; color:#0b1e3d; margin-top:2px; font-family:'DM Mono', monospace; }

    /* steps */
    .steps-stack { display:flex; flex-direction:column; gap:1rem; }
    .step-card {
      background:white;
      border-radius:18px;
      border:1px solid #e2e8f0;
      box-shadow:0 4px 12px rgba(0,0,0,0.02);
      overflow:hidden;
    }
    .step-card:hover { border-color:#cbd5e1; }
    .step-header {
      display:flex;
      align-items:center;
      gap:1rem;
      padding:1rem 1.5rem;
      cursor:pointer;
      user-select:none;
      transition:background 0.1s;
      border-bottom:1px solid #f1f5f9;
    }
    .step-header:hover { background:#fafcff; }
    .step-number {
      width:38px; height:38px;
      border-radius:40px;
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight:700;
      font-size:0.9rem;
      flex-shrink:0;
    }
    .step-number.active { background:#0b1e3d; color:white; }
    .step-number.completed { background:#059669; color:white; }
    .step-number.pending { background:#e2e8f0; color:#475569; }
    .step-title { font-weight:700; font-size:1rem; color:#0b1e3d; }
    .step-badge {
      margin-left:auto;
      font-size:0.7rem;
      font-weight:700;
      padding:0.25rem 0.8rem;
      border-radius:40px;
      text-transform:uppercase;
      letter-spacing:0.02em;
    }
    .step-badge.success { background:#d1fae5; color:#065f46; }
    .step-badge.warning { background:#fef3c7; color:#92400e; }
    .step-badge.secondary { background:#f1f5f9; color:#475569; }
    .step-chevron {
      margin-left:0.5rem;
      font-size:1.1rem;
      color:#94a3b8;
      transition:transform 0.25s ease;
    }
    .step-chevron.collapsed { transform:rotate(-90deg); }
    .step-body {
      padding:1.2rem 1.5rem;
      transition:max-height 0.3s ease, padding 0.3s ease;
      overflow:hidden;
    }
    .step-body.collapsed { max-height:0 !important; padding-top:0; padding-bottom:0; }

    /* table */
    .track-table { width:100%; border-collapse:collapse; font-size:0.82rem; }
    .track-table thead th {
      background:#f8fafc;
      color:#475569;
      font-weight:600;
      font-size:0.7rem;
      text-transform:uppercase;
      letter-spacing:0.04em;
      padding:0.6rem 0.8rem;
      border-bottom:2px solid #e2e8f0;
      text-align:left;
    }
    .track-table tbody td { padding:0.7rem 0.8rem; border-bottom:1px solid #f1f5f9; color:#1e293b; }
    .track-table tfoot td { padding:0.7rem 0.8rem; border-top:2px solid #e2e8f0; font-weight:700; color:#0b1e3d; }
    .track-table .text-right { text-align:right; }
    .track-table .text-center { text-align:center; }
    .text-mono { font-family:'DM Mono', monospace; }
    .empty-state { text-align:center; padding:1.8rem 1rem; color:#94a3b8; }
    .empty-state i { font-size:1.8rem; display:block; margin-bottom:0.4rem; }

    @media (max-width:640px) {
      body { padding:1rem; }
      .track-header { flex-direction:column; align-items:stretch; gap:1rem; }
      .header-right { justify-content:flex-start; }
      .search-form { flex-direction:column; align-items:stretch; }
      .search-input { flex:auto; }
      .btn-track { justify-content:center; }
      .info-card { grid-template-columns:1fr 1fr; }
    }
      .text-changed { color:#e11d48; font-weight:700; }
    .text-positive { color:#059669; font-weight:600; }
    .text-negative { color:#e11d48; font-weight:600; }
    .diff-badge {
      display:inline-flex;
      align-items:center;
      gap:3px;
      padding:1px 6px;
      border-radius:8px;
      font-size:0.7rem;
      font-weight:700;
      font-family:'DM Mono', monospace;
    }
    .diff-badge.pos { background:#d1fae5; color:#065f46; }
    .diff-badge.neg { background:#ffe4e6; color:#9f1239; }
</style>
</head>
<body>

<div class="row">
    
@include('sidenav')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
    <div class="track-wrap">

  <!-- HEADER -->
  <header class="track-header">
    <div class="header-left">
      <a href="javascript:history.back()" class="back-btn"><i class="bi bi-chevron-left"></i></a>
      <div class="header-icon"><i class="bi bi-diagram-3"></i></div>
      <div class="header-title">
        <h1>Track Sale</h1>
        <p>Full lifecycle · real-time tracking</p>
      </div>
    </div>
    <div class="header-right">
      <span class="track-badge"><i class="bi bi-clock-history"></i> live</span>
      <span class="track-badge"><i class="bi bi-check2-circle"></i> 5 steps</span>
    </div>
  </header>

  <!-- ALERTS (real session flashes) -->
  @if(session('success'))
  <div class="alert-box success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
  @endif
  @if(session('error'))
  <div class="alert-box error"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
  @endif
  @if(isset($error))
  <div class="alert-box error"><i class="bi bi-exclamation-triangle-fill"></i> {{ $error }}</div>
  @endif

  <!-- SEARCH (uses real route) -->
  <div class="search-panel">
    <form class="search-form" method="GET" action="{{ route('track.sale') }}">
      <i class="bi bi-search"></i>
      <input type="text" class="search-input" name="sales_name" placeholder="Paste sales name or track number …" value="{{ request('sales_name') }}" required>
      <button type="submit" class="btn-track"><i class="bi bi-search"></i> Track Sale</button>
    </form>
  </div>

  @if(isset($salesName) && !isset($error))
  <!-- INFO CARD (real data) -->
  <div class="info-card">
    <div class="info-item"><span class="label">Sales Name</span><span class="value text-mono">{{ $salesName }}</span></div>
    <div class="info-item"><span class="label">Sale Date</span><span class="value">{{ date('d M Y', strtotime($saleDate)) }}</span></div>
    <div class="info-item"><span class="label">Account / Shop</span><span class="value">{{ $shopName }}</span></div>
    <div class="info-item"><span class="label">Total Items</span><span class="value">{{ $saleItems->count() }}</span></div>
  </div>

  <!-- STEPS (real data) -->
  <div class="steps-stack">

    <!-- STEP 1: Item Request -->
    <div class="step-card">
      <div class="step-header" onclick="toggleStep(this)">
        <div class="step-number {{ $itemRequests->count() > 0 ? 'completed' : 'active' }}">1</div>
        <span class="step-title">Item Request</span>
        <span class="step-badge {{ $itemRequests->count() > 0 ? 'success' : 'secondary' }}">{{ $itemRequests->count() }} item(s)</span>
        <i class="bi bi-chevron-down step-chevron"></i>
      </div>
      <div class="step-body">
        @if($itemRequests->count() > 0)
        <table class="track-table">
          <thead><tr><th>Product</th><th class="text-center">Qty</th><th class="text-right">Price</th><th class="text-right">Total</th><th class="text-center">Status</th></tr></thead>
          <tbody>
            @foreach($itemRequests as $item)
            @php $product = DB::table('products')->where('product_id', $item->productId)->first(); @endphp
            <tr>
              <td>{{ $product->name01 ?? 'Unknown' }}</td>
              <td class="text-center">{{ $item->quantity }}</td>
              <td class="text-right">Tsh {{ number_format($item->price) }}</td>
              <td class="text-right">Tsh {{ number_format($item->totalPrice) }}</td>
              <td class="text-center"><span class="step-badge {{ $item->status == 'Pending' ? 'warning' : ($item->status == 'Submitted' ? 'secondary' : 'success') }}">{{ $item->status }}</span></td>
            </tr>
            @endforeach
          </tbody>
          <tfoot><tr><td colspan="3" class="text-right">Total</td><td class="text-right">Tsh {{ number_format($itemRequests->sum('totalPrice')) }}</td><td></td></tr></tfoot>
        </table>
        @else
        <div class="empty-state"><i class="bi bi-inbox"></i> No item requests found for this sale's products on {{ date('d M Y', strtotime($saleDate)) }}</div>
        @endif
      </div>
    </div>

    <!-- STEP 2: Approved -->
    <div class="step-card">
      <div class="step-header" onclick="toggleStep(this)">
        <div class="step-number {{ $approvedRequests->count() > 0 ? 'completed' : 'pending' }}">2</div>
        <span class="step-title">Approved</span>
        <span class="step-badge {{ $approvedRequests->count() > 0 ? 'success' : 'secondary' }}">{{ $approvedRequests->count() }} item(s)</span>
        <i class="bi bi-chevron-down step-chevron"></i>
      </div>
      <div class="step-body">
        @if($approvedRequests->count() > 0)
        <table class="track-table">
          <thead><tr><th>Product</th><th class="text-center">Qty</th><th class="text-right">Price</th><th class="text-right">Total</th></tr></thead>
          <tbody>
            @foreach($approvedRequests as $item)
            @php $product = DB::table('products')->where('product_id', $item->productId)->first(); $diff = $diffRequestApproved[$item->productId] ?? null; @endphp
            <tr>
              <td>{{ $product->name01 ?? 'Unknown' }}</td>
              <td class="text-center{{ ($diff && ($diff['qtyChanged'] ?? false)) ? ' text-changed' : '' }}">
                {{ $item->quantity }}
                @if($diff && $diff['qtyChanged'])
                  <span class="diff-badge {{ $diff['qtyDiff'] > 0 ? 'pos' : 'neg' }}">
                    {{ $diff['qtyDiff'] > 0 ? '+' : '' }}{{ $diff['qtyDiff'] }}
                  </span>
                @endif
              </td>
              <td class="text-right{{ ($diff && ($diff['priceChanged'] ?? false)) ? ' text-changed' : '' }}">
                Tsh {{ number_format($item->price) }}
                @if($diff && $diff['priceChanged'])
                  <span class="diff-badge {{ $diff['priceDiff'] > 0 ? 'pos' : 'neg' }}">
                    {{ $diff['priceDiff'] > 0 ? '+' : '' }}{{ number_format($diff['priceDiff']) }}
                  </span>
                @endif
              </td>
              <td class="text-right">
                Tsh {{ number_format($item->totalPrice) }}
              </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot><tr><td colspan="2" class="text-right">Total</td><td></td><td class="text-right">Tsh {{ number_format($approvedRequests->sum('totalPrice')) }}</td></tr></tfoot>
        </table>
        @else
        <div class="empty-state"><i class="bi bi-hourglass-split"></i> No approved items found for this sale's products</div>
        @endif
      </div>
    </div>

    <!-- STEP 3: Approved Receiving -->
    <div class="step-card">
      <div class="step-header" onclick="toggleStep(this)">
        <div class="step-number {{ $receivings->count() > 0 ? 'completed' : 'pending' }}">3</div>
        <span class="step-title">Approved Receiving</span>
        <span class="step-badge {{ $receivings->count() > 0 ? 'success' : 'secondary' }}">{{ $receivings->count() }} item(s)</span>
        <i class="bi bi-chevron-down step-chevron"></i>
      </div>
      <div class="step-body">
        @if($receivings->count() > 0)
        <table class="track-table">
          <thead><tr><th>Receiving ID</th><th>Product</th><th class="text-center">Qty</th><th class="text-right">Price</th><th class="text-right">Total</th><th class="text-center">Status</th></tr></thead>
          <tbody>
            @foreach($receivings as $item)
            @php $product = DB::table('products')->where('product_id', $item->productId)->first(); $recDiff = $diffApprovedReceiving[$item->productId] ?? null; @endphp
            <tr>
              <td class="text-mono">{{ $item->receivingId ?? 'N/A' }}</td>
              <td>{{ $product->name01 ?? 'Unknown' }}</td>
              <td class="text-center{{ ($recDiff && ($recDiff['qtyChanged'] ?? false)) ? ' text-changed' : '' }}">
                {{ $item->quantity }}
                @if($recDiff && $recDiff['qtyChanged'])
                  <span class="diff-badge {{ $recDiff['qtyDiff'] > 0 ? 'pos' : 'neg' }}">
                    {{ $recDiff['qtyDiff'] > 0 ? '+' : '' }}{{ $recDiff['qtyDiff'] }}
                  </span>
                @endif
              </td>
              <td class="text-right{{ ($recDiff && ($recDiff['priceChanged'] ?? false)) ? ' text-changed' : '' }}">
                Tsh {{ number_format($item->price) }}
                @if($recDiff && $recDiff['priceChanged'])
                  <span class="diff-badge {{ $recDiff['priceDiff'] > 0 ? 'pos' : 'neg' }}">
                    {{ $recDiff['priceDiff'] > 0 ? '+' : '' }}{{ number_format($recDiff['priceDiff']) }}
                  </span>
                @endif
              </td>
              <td class="text-right">
                Tsh {{ number_format($item->price * $item->quantity) }}
              </td>
              <td class="text-center"><span class="step-badge {{ $item->status == 'Approved' ? 'success' : ($item->status == 'Not Approved' ? 'warning' : 'secondary') }}">{{ $item->status ?? 'Pending' }}</span></td>
            </tr>
            @endforeach
          </tbody>
          <tfoot><tr><td colspan="3" class="text-right">Total</td><td></td><td class="text-right">Tsh {{ number_format($receivings->sum(fn($i) => $i->price * $i->quantity)) }}</td><td></td></tr></tfoot>
        </table>
        @else
        <div class="empty-state"><i class="bi bi-box-seam"></i> No receivings found for this sale's products</div>
        @endif
      </div>
    </div>

    <!-- STEP 4: Sale Transaction -->
    <div class="step-card">
      <div class="step-header" onclick="toggleStep(this)">
        <div class="step-number completed">4</div>
        <span class="step-title">Sale Transaction</span>
        <span class="step-badge success">{{ $saleItems->count() }} item(s)</span>
        <i class="bi bi-chevron-down step-chevron"></i>
      </div>
      <div class="step-body">
        <table class="track-table">
          <thead><tr><th>#</th><th>Product</th><th class="text-center">Qty</th><th class="text-right">Unit Price</th><th class="text-right">Total</th><th class="text-center">Type</th></tr></thead>
          <tbody>
            @foreach($saleItems as $index => $saleItem)
            @php $product = DB::table('products')->where('product_id', $saleItem->productId)->first(); $saleDiff = $diffReceivingSale[$saleItem->productId] ?? null; @endphp
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $product->name01 ?? 'Unknown' }}</td>
              <td class="text-center{{ ($saleDiff && ($saleDiff['qtyChanged'] ?? false)) ? ' text-changed' : '' }}">
                {{ $saleItem->pQuantity }}
                @if($saleDiff && $saleDiff['qtyChanged'])
                  <span class="diff-badge {{ $saleDiff['qtyDiff'] > 0 ? 'pos' : 'neg' }}">
                    {{ $saleDiff['qtyDiff'] > 0 ? '+' : '' }}{{ $saleDiff['qtyDiff'] }}
                  </span>
                @endif
              </td>
              <td class="text-right{{ ($saleDiff && ($saleDiff['priceChanged'] ?? false)) ? ' text-changed' : '' }}">
                Tsh {{ number_format($saleItem->productPrice) }}
                @if($saleDiff && $saleDiff['priceChanged'])
                  <span class="diff-badge {{ $saleDiff['priceDiff'] > 0 ? 'pos' : 'neg' }}">
                    {{ $saleDiff['priceDiff'] > 0 ? '+' : '' }}{{ number_format($saleDiff['priceDiff']) }}
                  </span>
                @endif
              </td>
              <td class="text-right">Tsh {{ number_format($saleItem->totalPrice) }}</td>
              <td class="text-center"><span class="step-badge {{ $saleItem->transactionType == 'Cash' ? 'success' : 'warning' }}">{{ $saleItem->transactionType }}</span></td>
            </tr>
            @endforeach
          </tbody>
          <tfoot><tr><td colspan="3" class="text-right">Total</td><td></td><td class="text-right">Tsh {{ number_format($saleItems->sum('totalPrice')) }}</td><td></td></tr></tfoot>
        </table>
      </div>
    </div>

    <!-- STEP 5: Cash Submitted -->
    <div class="step-card">
      <div class="step-header" onclick="toggleStep(this)">
        <div class="step-number {{ $cashSubmitted ? 'completed' : 'pending' }}">5</div>
        <span class="step-title">Cash Submitted</span>
        @if($cashSubmitted)
        <span class="step-badge success">Tsh {{ number_format($cashSubmitted->submitted_cash) }}</span>
        @else
        <span class="step-badge secondary">Not submitted</span>
        @endif
        <i class="bi bi-chevron-down step-chevron"></i>
      </div>
      <div class="step-body">
        @if($cashSubmitted)
        <div class="info-card" style="margin:0; border:none; padding:0.5rem 0; grid-template-columns: repeat(auto-fit, minmax(150px,1fr));">
          <div class="info-item"><span class="label">Submitted Amount</span><span class="value">Tsh {{ number_format($cashSubmitted->submitted_cash) }}</span></div>
          <div class="info-item"><span class="label">Report Date</span><span class="value">{{ date('d M Y', strtotime($cashSubmitted->report_date)) }}</span></div>
        </div>
        @else
        <div class="empty-state"><i class="bi bi-cash-stack"></i> No cash submission found for {{ date('d M Y', strtotime($saleDate)) }}</div>
        @endif
      </div>
    </div>

  </div><!-- end steps-stack -->
  @endif

  <!-- small footer extra -->
  @if(isset($salesName) && !isset($error))
  <div style="margin-top: 1.8rem; background: white; border-radius: 18px; border: 1px solid #e2e8f0; padding: 1rem 1.5rem; display: flex; flex-wrap: wrap; gap: 1.5rem; justify-content: space-between; align-items: center;">
    <div><i class="bi bi-clock" style="color:#64748b;"></i> <span style="font-weight:500;">Last updated:</span> <span class="text-mono" style="color:#0b1e3d;">{{ now()->format('d M Y · H:i') }}</span></div>
    <div><i class="bi bi-check2-all" style="color:#059669;"></i> <span style="font-weight:500;">{{ $cashSubmitted ? '5/5 steps complete' : '4/5 steps complete' }}</span></div>
    <div><span class="step-badge {{ $cashSubmitted ? 'success' : 'warning' }}" style="font-size:0.75rem;"><i class="bi {{ $cashSubmitted ? 'bi-check-circle' : 'bi-clock' }}"></i> {{ $cashSubmitted ? 'delivered' : 'in progress' }}</span></div>
  </div>
  @endif

</div>
</main>
</div><!-- track-wrap -->

<script>
  function toggleStep(header) {
    const body = header.nextElementSibling;
    const chevron = header.querySelector('.step-chevron');
    if (body) body.classList.toggle('collapsed');
    if (chevron) chevron.classList.toggle('collapsed');
  }
  // expand first step by default
  document.addEventListener('DOMContentLoaded', function() {
    const firstBody = document.querySelector('.step-card .step-body');
    if (firstBody) firstBody.classList.remove('collapsed');
    const firstChevron = document.querySelector('.step-card .step-chevron');
    if (firstChevron) firstChevron.classList.remove('collapsed');
  });
</script>
@include('footer')

</body>
</html>