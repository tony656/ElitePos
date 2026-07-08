<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} - @lang('messages.view_returns_page_title')</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --navy:          #0B1E3D;
            --navy-mid:      #112952;
            --navy-light:    #1A3A6B;
            --amber:         #F59E0B;
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

        .main-wrap { max-width: 1900px; margin: 0 auto; padding: 1.25rem 1.5rem; }

        /* Page header */
        .pg-header {
            background: var(--navy);
            border-radius: 12px;
            padding: 1rem 1.4rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.28);
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
        }

        .pg-title {
            color: var(--white); font-size: 1.35rem; font-weight: 700;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .pg-title i { color: var(--violet); font-size: 1.25rem; }

        .hbtn-primary {
            display: inline-flex; align-items: center; gap: 0.45rem;
            font-size: 0.875rem; font-weight: 600;
            padding: 0.5rem 1.1rem;
            background: var(--amber); color: var(--navy);
            border: none; border-radius: 8px; cursor: pointer;
            box-shadow: 0 3px 12px rgba(245,158,11,0.3);
            transition: all 0.18s; text-decoration: none;
        }
        .hbtn-primary:hover {
            box-shadow: 0 5px 18px rgba(245,158,11,0.4);
            transform: translateY(-1px);
            color: var(--navy);
        }

        /* Alert */
        .alert-box {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            border-left: 4px solid;
        }
        .alert-success {
            background: var(--emerald-pale);
            border-color: var(--emerald);
            color: #065F46;
        }
        .alert-error {
            background: var(--rose-pale);
            border-color: var(--rose);
            color: #9F1239;
        }

        /* Date Groups Container */
        .groups-container {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* Group Card */
        .date-group-card {
            background: var(--white);
            border-radius: 12px;
            border: 1.5px solid var(--slate-200);
            overflow: hidden;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
        }

        .group-header {
            background: linear-gradient(135deg, var(--slate-50) 0%, var(--white) 100%);
            padding: 1rem 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
            border-bottom: 1px solid transparent;
        }
        .group-header:hover {
            background: linear-gradient(135deg, #F8FAFF 0%, var(--slate-50) 100%);
        }

        .date-info {
            display: flex;
            align-items: baseline;
            gap: 1.2rem;
            flex-wrap: wrap;
        }

        .date-badge {
            background: var(--violet);
            color: white;
            padding: 0.35rem 1.2rem;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
        }

        .group-stats {
            display: flex;
            gap: 1rem;
            font-size: 0.8rem;
        }

        .group-stats span {
            background: var(--slate-100);
            padding: 0.25rem 0.9rem;
            border-radius: 20px;
            font-weight: 600;
            color: var(--slate-700);
        }

        .expand-icon {
            font-size: 1.5rem;
            color: var(--violet);
            transition: transform 0.3s ease;
        }

        .group-details {
            display: none;
            background: var(--white);
            border-top: 1.5px solid var(--slate-200);
            animation: slideDown 0.3s ease;
        }

        .group-details.expanded {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Summary inside group */
        .group-summary {
            background: var(--violet-pale);
            padding: 0.85rem 1.5rem;
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            border-bottom: 1px solid var(--slate-200);
            font-size: 0.85rem;
        }

        .group-summary-item {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }

        .group-summary-item strong {
            color: var(--violet);
            font-weight: 700;
        }

        /* Table inside group */
        .table-wrap {
            overflow-x: auto;
            padding: 0;
        }

        .returns-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.845rem;
        }

        .returns-tbl thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
            border-bottom: 2px solid var(--slate-200);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .returns-tbl tbody td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
        }

        .returns-tbl tbody tr:hover td {
            background: #F8FAFF;
        }

        .prod-name {
            font-weight: 600;
            color: var(--navy);
        }

        .pay-badge, .status-badge {
            display: inline-flex;
            align-items: center;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.25rem 0.55rem;
            border-radius: 5px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .pay-badge.credit { background: var(--amber-pale); color: #92400E; }
        .pay-badge.cash { background: var(--emerald-pale); color: #065F46; }

        .status-badge.pending { background: var(--amber-pale); color: #92400E; }
        .status-badge.approved { background: var(--emerald-pale); color: #065F46; }
        .status-badge.returned { background: var(--violet-pale); color: #5B21B6; }

        .act-btn {
            width: 30px;
            height: 30px;
            border: 1.5px solid;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all 0.15s;
            background: transparent;
            margin: 0 2px;
        }

        .act-btn-approve { border-color: var(--emerald); color: var(--emerald); }
        .act-btn-approve:hover { background: var(--emerald-pale); transform: scale(1.08); }
        .act-btn-reject { border-color: var(--rose); color: var(--rose); }
        .act-btn-reject:hover { background: var(--rose-pale); transform: scale(1.08); }
        .act-btn-delete { border-color: var(--slate-400); color: var(--slate-500); }
        .act-btn-delete:hover { background: var(--slate-100); transform: scale(1.08); }

        .empty-state {
            text-align: center;
            padding: 4rem 1.5rem;
            color: var(--slate-400);
        }

        .empty-state i {
            font-size: 4rem;
            display: block;
            margin-bottom: 0.75rem;
            opacity: 0.3;
        }

        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
            .group-header { flex-direction: column; align-items: flex-start; gap: 0.8rem; }
            .date-info { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

    @include("sidenav")

        <main class="main-content">
            <div class="main-wrap">
                <!-- Header -->
                <div class="pg-header">
                    <div class="pg-title">
                        <i class="bi bi-arrow-return-left"></i>
                        {{ __('messages.view_returns_page_header') }}
                    </div>
                    <a href="{{ url('make-return') }}" class="hbtn-primary">
                        <i class="bi bi-plus-circle"></i> {{ __('messages.new_return') }}
                    </a>
                </div>

                <!-- Alerts -->
                @if(session('success'))
                <div class="alert-box alert-success">
                    <span><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert-box alert-error">
                    <span><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Filter Bar -->
                <div style="background: white; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; border: 1.5px solid var(--slate-200);">
                    <form method="GET" action="{{ url('view-returns') }}" class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label fw-bold small">From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date', $fromDate ?? '') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small">To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date', $toDate ?? '') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small">Status</label>
                            <select name="status" class="form-select">
                                <option value="all" {{ (request('status', $statusFilter ?? 'all') == 'all') ? 'selected' : '' }}>All Status</option>
                                <option value="Pending" {{ (request('status', $statusFilter ?? 'all') == 'Pending') ? 'selected' : '' }}>Pending</option>
                                <option value="Approved" {{ (request('status', $statusFilter ?? 'all') == 'Approved') ? 'selected' : '' }}>Approved</option>
                                <option value="Returned" {{ (request('status', $statusFilter ?? 'all') == 'Returned') ? 'selected' : '' }}>Returned</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small">Shop</label>
                            <select name="shop" class="form-select">
                                <option value="">All Shops</option>
                                @foreach($shops ?? [] as $shop)
                                    <option value="{{ $shop['id'] }}" {{ request('shop', $shopFilter ?? '') == $shop['id'] ? 'selected' : '' }}>
                                        {{ $shop['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100" style="background: var(--violet); border: none;">
                                <i class="bi bi-funnel"></i> Apply Filters
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Grouped Returns Display -->
                <div class="groups-container">
                    @php
                        // Group products by date
                        $groupedReturns = [];
                        foreach ($products as $item) {
                            $date = date('Y-m-d', strtotime($item->created_at));
                            if (!isset($groupedReturns[$date])) {
                                $groupedReturns[$date] = [];
                            }
                            $groupedReturns[$date][] = $item;
                        }
                        
                        // Sort dates descending
                        krsort($groupedReturns);
                        
                        $grandTotalQty = $products->sum('quantity');
                        $grandTotalValue = $products->sum(function($p){ return ($p->quantity ?? 0) * ($p->price ?? 0); });
                    @endphp

                    @if(count($groupedReturns) > 0)
                        @foreach($groupedReturns as $date => $dateItems)
                            @php
                                $dateTotalQty = collect($dateItems)->sum('quantity');
                                $dateTotalValue = collect($dateItems)->sum(function($item) { 
                                    return ($item->quantity ?? 0) * ($item->price ?? 0); 
                                });
                                $formattedDate = date('F d, Y', strtotime($date));
                            @endphp
                            
                            <div class="date-group-card" data-date="{{ $date }}">
                                <div class="group-header" onclick="toggleGroup(this)">
                                    <div class="date-info">
                                        <div class="date-badge">
                                            <i class="bi bi-calendar3"></i> {{ $formattedDate }}
                                        </div>
                                        <div class="group-stats">
                                            <span><i class="bi bi-box"></i> {{ count($dateItems) }} Items</span>
                                            <span><i class="bi bi-calculator"></i> Qty: {{ number_format($dateTotalQty) }}</span>
                                            <span><i class="bi bi-currency-dollar"></i> Tsh {{ number_format($dateTotalValue, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="expand-icon">
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                </div>
                                
                                <div class="group-details">
                                    <div class="group-summary">
                                        <div class="group-summary-item"><strong>📦 Total Returns:</strong> {{ count($dateItems) }}</div>
                                        <div class="group-summary-item"><strong>📊 Total Quantity:</strong> {{ number_format($dateTotalQty) }}</div>
                                        <div class="group-summary-item"><strong>💰 Total Value:</strong> Tsh {{ number_format($dateTotalValue, 2) }}</div>
                                    </div>
                                    
                                    <div class="table-wrap">
                                        <table class="returns-tbl">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product</th>
                                                    <th>Qty</th>
                                                    <th>Cost</th>
                                                    <th>Total</th>
                                                    <th>Payment</th>
                                                    <th>Supplier</th>
                                                    <th>From</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dateItems as $index => $item)
                                                @php
                                                    $supName = DB::table('vendors')->where('id', $item->supplier)->value('name');
                                                    $from = DB::table('accounts')->where('id', $item->account)->value('name');
                                                    $status = strtolower(trim($item->status ?? 'returned'));
                                                @endphp
                                                <tr id="row-{{ $item->productId }}">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td><div class="prod-name">{{ $item->productName }}</div></td>
                                                    <td>{{ number_format($item->quantity) }}</td>
                                                    <td>Tsh {{ number_format($item->price, 2) }}</td>
                                                    <td>Tsh {{ number_format($item->price * $item->quantity, 2) }}</td>
                                                    <td>
                                                        @if($item->isDebt == 1)
                                                            <span class="pay-badge credit">Credit</span>
                                                        @else
                                                            <span class="pay-badge cash">Cash</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $supName ?? 'Unknown' }}</td>
                                                    <td>{{ $from ?? 'Unknown' }}</td>
                                                    <td>
                                                        @if($status == 'pending')
                                                            <span class="status-badge pending">Pending</span>
                                                        @elseif($status == 'approved')
                                                            <span class="status-badge approved">Approved</span>
                                                        @else
                                                            <span class="status-badge returned">Returned</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($status == 'pending')
                                                            <form method="post" style="display:inline;">
                                                                @csrf
                                                                <input type="hidden" name="return_id" value="{{ $item->id }}">
                                                                <button formaction="{{ url('return/approve') }}"
                                                                    class="act-btn act-btn-approve" title="Approve return"
                                                                    onclick="return confirm('Approve this return? Stock will be deducted.')">
                                                                    <i class="bi bi-check-lg"></i>
                                                                </button>
                                                            </form>
                                                            <form method="post" style="display:inline;">
                                                                @csrf
                                                                <input type="hidden" name="return_id" value="{{ $item->id }}">
                                                                <button formaction="{{ url('return/reject') }}"
                                                                    class="act-btn act-btn-reject" title="Reject return"
                                                                    onclick="return confirm('Reject this return? No stock will be deducted.')">
                                                                    <i class="bi bi-x-lg"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <form method="post" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="product_id" value="{{ $item->productId }}">
                                                            <input type="hidden" name="action" value="delete">
                                                            <button formaction="{{ url('dltrestock') }}"
                                                                class="act-btn act-btn-delete" title="Delete"
                                                                onclick="return confirm('Delete this return record?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Grand Total Summary -->
                        <div style="background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%); color: white; border-radius: 12px; padding: 1rem 1.5rem; margin-top: 1rem;">
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-white-50">GRAND TOTAL</small>
                                    <h5 class="mb-0">{{ number_format(count($products)) }} Returns</h5>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-white-50">TOTAL QUANTITY</small>
                                    <h5 class="mb-0">{{ number_format($grandTotalQty) }} Units</h5>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-white-50">TOTAL VALUE</small>
                                    <h5 class="mb-0">Tsh {{ number_format($grandTotalValue, 2) }}</h5>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-arrow-return-left"></i>
                            <div class="empty-state-title">No Returns Found</div>
                            <p>No returns found for the selected criteria</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>

<script>
// Toggle group expansion function
function toggleGroup(headerElement) {
    const card = headerElement.closest('.date-group-card');
    const detailsDiv = card.querySelector('.group-details');
    const icon = headerElement.querySelector('.expand-icon i');
    
    if (detailsDiv.classList.contains('expanded')) {
        detailsDiv.classList.remove('expanded');
        icon.className = 'bi bi-chevron-down';
    } else {
        detailsDiv.classList.add('expanded');
        icon.className = 'bi bi-chevron-up';
    }
}

// Optional: Expand first group by default on page load
document.addEventListener('DOMContentLoaded', function() {
    const firstGroup = document.querySelector('.date-group-card');
    if (firstGroup) {
        const header = firstGroup.querySelector('.group-header');
        const details = firstGroup.querySelector('.group-details');
        const icon = firstGroup.querySelector('.expand-icon i');
        
        // Expand the first group by default
        if (details && !details.classList.contains('expanded')) {
            details.classList.add('expanded');
            if (icon) icon.className = 'bi bi-chevron-up';
        }
    }
    
    // Store expanded groups in localStorage (optional)
    const cards = document.querySelectorAll('.date-group-card');
    cards.forEach(card => {
        const date = card.getAttribute('data-date');
        const isExpanded = localStorage.getItem(`group_${date}`) === 'expanded';
        if (isExpanded) {
            const details = card.querySelector('.group-details');
            const icon = card.querySelector('.expand-icon i');
            if (details && !details.classList.contains('expanded')) {
                details.classList.add('expanded');
                if (icon) icon.className = 'bi bi-chevron-up';
            }
        }
        
        // Save state when toggling
        const header = card.querySelector('.group-header');
        header.addEventListener('click', function() {
            const detailsDiv = card.querySelector('.group-details');
            const newState = detailsDiv.classList.contains('expanded') ? 'expanded' : 'collapsed';
            localStorage.setItem(`group_${date}`, newState === 'expanded' ? 'expanded' : 'collapsed');
        });
    });
});
</script>
@include('footer')

</body>
</html>