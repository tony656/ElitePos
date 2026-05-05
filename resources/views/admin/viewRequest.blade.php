<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Requested Items</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink:        #0f1117;
            --ink-light:  #6b7280;
            --surface:    #ffffff;
            --surface-2:  #f4f6fb;
            --border:     #e5e9f2;
            --blue:       #3b5bdb;
            --blue-soft:  #eef2ff;
            --green:      #0d9060;
            --green-soft: #ecfdf5;
            --amber:      #c05621;
            --amber-soft: #fff7ed;
            --red:        #c0392b;
            --red-soft:   #fef2f2;
            --purple:     #7c3aed;
            --purple-soft:#f5f3ff;
            --radius:     10px;
            --shadow-sm:  0 1px 3px rgba(0,0,0,.07);
            --shadow:     0 4px 16px rgba(0,0,0,.08);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface-2);
            color: var(--ink);
        }

        /* ── PAGE HEADER ───────────────────────────── */
        .page-header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 1.1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .page-header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            color: var(--ink-light);
            text-decoration: none;
            font-size: .85rem;
            font-weight: 500;
            padding: .4rem .75rem;
            border-radius: var(--radius);
            transition: background .15s, color .15s;
        }
        .back-btn:hover { background: var(--surface-2); color: var(--ink); }

        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--ink);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
        }

        /* ── BUTTONS ───────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .5rem 1rem;
            border-radius: var(--radius);
            font-size: .85rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all .18s ease;
            white-space: nowrap;
        }
        .btn-primary   { background: var(--blue);  color: #fff; }
        .btn-primary:hover { background: #2f4dc4; color: #fff; }
        .btn-success   { background: var(--green); color: #fff; }
        .btn-success:hover { background: #0a7a53; color: #fff; }
        .btn-outline   { background: transparent; color: var(--ink); border: 1px solid var(--border); }
        .btn-outline:hover { background: var(--surface-2); }
        .btn-ghost     { background: transparent; color: var(--blue); border: 1px solid var(--blue-soft); }
        .btn-ghost:hover { background: var(--blue-soft); }
        .btn-sm        { padding: .35rem .75rem; font-size: .8rem; }

        /* ── STAT CARDS ────────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin: 1.5rem 2rem;
        }

        .stat-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .stat-label {
            font-size: .78rem;
            font-weight: 500;
            color: var(--ink-light);
            letter-spacing: .03em;
            text-transform: uppercase;
            margin-bottom: .3rem;
        }
        .stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 1.9rem;
            font-weight: 700;
            line-height: 1;
        }

        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 1.2rem;
        }
        .stat-icon-blue   { background: var(--blue-soft);   color: var(--blue);  }
        .stat-icon-amber  { background: var(--amber-soft);  color: var(--amber); }
        .stat-icon-green  { background: var(--green-soft);  color: var(--green); }

        /* ── TOOLBAR ───────────────────────────────── */
        .toolbar {
            margin: 0 2rem 1rem;
            display: flex;
            gap: .75rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-wrap {
            flex: 1;
            min-width: 200px;
            position: relative;
        }
        .search-wrap i {
            position: absolute;
            left: .85rem; top: 50%;
            transform: translateY(-50%);
            color: var(--ink-light);
            pointer-events: none;
        }
        .search-input {
            width: 100%;
            padding: .55rem 1rem .55rem 2.4rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: .875rem;
            background: var(--surface);
            color: var(--ink);
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .search-input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(59,91,219,.1);
        }

        .date-input {
            padding: .55rem .85rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: .875rem;
            background: var(--surface);
            color: var(--ink);
            outline: none;
            cursor: pointer;
        }
        .date-input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(59,91,219,.1);
        }

        /* ── TABLE CARD ────────────────────────────── */
        .table-card {
            margin: 0 2rem 2rem;
            background: var(--surface);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .tbl { width: 100%; border-collapse: collapse; }

        .tbl thead tr {
            background: var(--ink);
        }
        .tbl thead th {
            padding: .85rem 1rem;
            font-size: .75rem;
            font-weight: 600;
            color: rgba(255,255,255,.7);
            text-transform: uppercase;
            letter-spacing: .06em;
            white-space: nowrap;
        }
        .tbl thead th:first-child { padding-left: 1.5rem; }
        .tbl thead th:last-child  { padding-right: 1.5rem; text-align: right; }

        .tbl tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .12s;
        }
        .tbl tbody tr:last-child { border-bottom: none; }
        .tbl tbody tr:hover { background: #fafbff; }

        .tbl td {
            padding: .85rem 1rem;
            font-size: .875rem;
            vertical-align: middle;
        }
        .tbl td:first-child { padding-left: 1.5rem; }
        .tbl td:last-child  { padding-right: 1.5rem; }

        .td-actions { text-align: right; white-space: nowrap; }

        /* ── BADGES / PILLS ────────────────────────── */
        .pill {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .25rem .7rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .pill-you     { background: var(--blue-soft);   color: var(--blue);  }
        .pill-shop    { background: var(--surface-2);   color: var(--ink);   }
        .pill-pending { background: var(--amber-soft);  color: var(--amber); }
        .pill-approved{ background: var(--green-soft);  color: var(--green); }
        .pill-rejected{ background: var(--red-soft);    color: var(--red);   }
        .pill-submitted{ background: var(--blue-soft);  color: var(--blue);  }
        .pill-mixed   { background: var(--purple-soft); color: var(--purple);}
        .pill-stock   { background: var(--amber-soft);  color: var(--amber); }

        /* dot indicator before status */
        .pill::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: currentColor;
            opacity: .7;
        }

        .req-id {
            font-family: 'Syne', sans-serif;
            font-size: .78rem;
            font-weight: 600;
            color: var(--ink-light);
        }

        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }
        .empty-state-icon { font-size: 3rem; color: var(--border); margin-bottom: 1rem; }
        .empty-state h5 { font-size: 1rem; font-weight: 600; color: var(--ink-light); margin-bottom: .4rem; }
        .empty-state p  { font-size: .85rem; color: var(--ink-light); }

        /* ── MODAL ─────────────────────────────────── */
        .modal-content {
            border: none;
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
            overflow: hidden;
        }
        .modal-header {
            background: var(--ink);
            color: #fff;
            padding: 1.25rem 1.5rem;
            border: none;
        }
        .modal-header .btn-close { filter: invert(1); opacity: .6; }
        .modal-header .btn-close:hover { opacity: 1; }
        .modal-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem; }

        .modal-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
            padding: 1.25rem 1.5rem;
            background: var(--surface-2);
            border-bottom: 1px solid var(--border);
        }
        .modal-meta-item label {
            display: block;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--ink-light);
            margin-bottom: .2rem;
        }
        .modal-meta-item span { font-size: .9rem; font-weight: 600; }

        .modal-body { padding: 1.25rem 1.5rem; }

        .modal-tbl { width: 100%; border-collapse: collapse; font-size: .85rem; }
        .modal-tbl thead tr { background: var(--surface-2); }
        .modal-tbl thead th {
            padding: .6rem .85rem;
            text-align: left;
            font-size: .72rem;
            font-weight: 600;
            color: var(--ink-light);
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        .modal-tbl tbody tr { border-bottom: 1px solid var(--border); }
        .modal-tbl tbody tr:last-child { border-bottom: none; }
        .modal-tbl td { padding: .7rem .85rem; vertical-align: middle; }

        .totals-row {
            display: flex;
            gap: 1.5rem;
            margin-top: 1.25rem;
            padding: 1rem 1.25rem;
            background: var(--surface-2);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            flex-wrap: wrap;
        }
        .totals-row .t-item label {
            display: block;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--ink-light);
            margin-bottom: .2rem;
        }
        .totals-row .t-item span {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
            background: var(--surface);
        }

        /* ── RESPONSIVE ────────────────────────────── */
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .stats-grid .stat-card:last-child { grid-column: span 2; }
        }
        @media (max-width: 600px) {
            .stats-grid { grid-template-columns: 1fr; margin: 1rem; }
            .stats-grid .stat-card:last-child { grid-column: auto; }
            .table-card, .toolbar { margin-left: 1rem; margin-right: 1rem; }
            .page-header { padding: 1rem; }
            .modal-meta { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

@php
    $isAdmin = Auth::check() && Auth::user()->levelStatus === 'Admin';
@endphp

<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-0 pt-3 bg-light">

        {{-- ── PAGE HEADER ── --}}
        <div class="page-header">
            <div class="page-header-left">
                <a href="#" onclick="history.back()" class="back-btn">
                    <i class="bi bi-chevron-left"></i> Back
                </a>
                <span class="page-title">Requested Items</span>
            </div>
            <div class="header-actions">
                <input type="date" id="dateFilter" class="date-input" title="Filter by date">
                <a href="{{ url('user/itemRequest') }}" class="btn btn-outline">
                    <i class="bi bi-list-ul"></i> Item Requests
                </a>
                <a href="{{ url('user/itemRequest') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> New Request
                </a>
            </div>
        </div>

        {{-- ── STAT CARDS ── --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div>
                    <div class="stat-label">Total Requests</div>
                    <div class="stat-value">{{ number_format($totalRequest) }}</div>
                </div>
                <div class="stat-icon stat-icon-blue">
                    <i class="bi bi-inbox-fill"></i>
                </div>
            </div>
            <div class="stat-card">
                <div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-value">{{ number_format($totalPednding) }}</div>
                </div>
                <div class="stat-icon stat-icon-amber">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
            <div class="stat-card">
                <div>
                    <div class="stat-label">Submitted</div>
                    <div class="stat-value">{{ number_format($totalSub) }}</div>
                </div>
                <div class="stat-icon stat-icon-green">
                    <i class="bi bi-send-check-fill"></i>
                </div>
            </div>
        </div>

        {{-- ── TOOLBAR ── --}}
        <div class="toolbar">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="search" class="search-input" id="search-input" placeholder="Search by request ID, shop name, status…">
            </div>
        </div>

        {{-- ── TABLE ── --}}
        <div class="table-card">
            <div class="table-responsive">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Request ID</th>
                            <th><i class="bi bi-arrow-right-circle me-1"></i>From Shop</th>
                            <th><i class="bi bi-arrow-left-circle me-1"></i>To Shop</th>
                            <th>Items</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="request-table-body">

                        @if(empty($groupedRequests))
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                                    <h5>No requests found</h5>
                                    <p>No item requests have been made yet</p>
                                </div>
                            </td>
                        </tr>
                        @else
                            @php $index = 1; @endphp
                            @foreach ($groupedRequests as $requestId => $items)
                                @php
                                    /* ── Per-row variables ── */
                                    $requesterAccount = $items[0]->account      ?? '';
                                    $supplierAccount  = $items[0]->supplierName ?? '';

                                    /* Who am I? */
                                    $iAmRequester = (getSessionAccountDisplayName() === $requesterAccount);
                                    $iAmReceiver  = (getSessionAccountDisplayName() === $supplierAccount);

                                    /* Totals */
                                    $totalQuantity = 0;
                                    $totalPrice    = 0;
                                    foreach ($items as $item) {
                                        $totalQuantity += $item->quantity;
                                        $totalPrice    += $item->quantity * $item->price;
                                    }

                                    /* Date */
                                    $requestDate          = $items[0]->created_at ?? now();
                                    $requestDateFormatted = date('Y-m-d', strtotime($requestDate));

                                    /* Overall status */
                                    $statuses = array_unique(array_column($items->toArray(), 'status'));
                                    if (count($statuses) === 1) {
                                        $overallStatus = $statuses[0];
                                    } elseif (in_array('Pending', $statuses)) {
                                        $overallStatus = 'Pending';
                                    } elseif (in_array('Approved', $statuses)) {
                                        $overallStatus = 'Approved';
                                    } else {
                                        $overallStatus = 'Mixed';
                                    }
                                @endphp

                                <tr class="request-row" data-date="{{ $requestDateFormatted }}">
                                    <td style="color:var(--ink-light); font-size:.8rem;">{{ $index++ }}</td>
                                    <td style="white-space:nowrap; font-size:.83rem;">
                                        {{ date('M d, Y', strtotime($requestDate)) }}
                                    </td>
                                    <td><span class="req-id">{{ $requestId }}</span></td>

                                    {{-- FROM SHOP (requester) --}}
                                    <td>
                                        @if($iAmRequester)
                                            <span class="pill pill-you"><i class="bi bi-person-fill"></i> You</span>
                                        @else
                                            <span class="pill pill-shop">{{ $requesterAccount ?: 'N/A' }}</span>
                                        @endif
                                    </td>

                                    {{-- TO SHOP (supplier/receiver) --}}
                                    <td>
                                        @if($iAmReceiver)
                                            <span class="pill pill-you"><i class="bi bi-person-fill"></i> You</span>
                                        @elseif($supplierAccount)
                                            <span class="pill pill-shop">{{ $supplierAccount }}</span>
                                        @else
                                            <span style="color:var(--ink-light); font-size:.8rem;">—</span>
                                        @endif
                                    </td>

                                    <td><strong>{{ count($items) }}</strong></td>
                                    <td>{{ number_format($totalQuantity) }}</td>
                                    <td style="white-space:nowrap;">Tsh {{ number_format($totalPrice) }}</td>
                                    <td>
                                        <span class="pill {{ $items[0]->payment_type === 'cash' ? 'bg-success' : 'bg-info' }}">
                                            {{ $items[0]->payment_type ? ucfirst($items[0]->payment_type) : 'Cash' }}
                                        </span>
                                    </td>
                                    <td>{{ $items[0]->assigned_to ?? 'N/A' }}</td>

                                    {{-- STATUS --}}
                                    <td>
                                        @php
                                            $pillMap = [
                                                'Pending'      => 'pill-pending',
                                                'Approved'     => 'pill-approved',
                                                'Rejected'     => 'pill-rejected',
                                                'Submitted'    => 'pill-submitted',
                                                'Out of Stock' => 'pill-stock',
                                                'Mixed'        => 'pill-mixed',
                                            ];
                                            $pillClass = $pillMap[$overallStatus] ?? 'pill-mixed';
                                        @endphp
                                        <span class="pill {{ $pillClass }}">{{ $overallStatus }}</span>
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="td-actions">
                                        {{-- View details (everyone) --}}
                                        <button class="btn btn-ghost btn-sm view-request-btn"
                                                data-request-id="{{ $requestId }}"
                                                data-items='@json($items)'
                                                data-total-quantity="{{ $totalQuantity }}"
                                                data-total-price="{{ $totalPrice }}"
                                                data-is-receiver="{{ $iAmReceiver ? 'true' : 'false' }}"
                                                data-is-admin="{{ $isAdmin ? 'true' : 'false' }}">
                                            <i class="bi bi-eye"></i> Details 
                                        </button>

                                        {{-- RECEIVER (Shop 2) or ADMIN — Approve All --}}
                                        @if(($iAmReceiver || (Auth::check() && Auth::user()->levelStatus === 'Admin')) && $overallStatus !== 'Approved')
                                            <form method="post" class="d-inline"
                                                  action="{{ route('admin.request.approveAll') }}">
                                                @csrf
                                                <input type="hidden" name="requestName" value="{{ $requestId }}">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="bi bi-check2-all"></i> Approve All
                                                </button>
                                            </form>
                                        @endif

                                        {{-- REQUESTER (Shop 1) — View Receiving when approved --}}
                                        @if($iAmRequester && $overallStatus === 'Approved')
                                            <a href="{{ url('user/view-receivings') }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-box-arrow-in-right"></i> View Receiving
                                            </a>
                                        @endif

                                        {{-- ADMIN — Delete Request --}}
                                        @if($isAdmin)
                                            <form method="post" class="d-inline" action="{{ route('admin.request.delete') }}" onsubmit="return confirm('Are you sure you want to delete this entire request? This action cannot be undone.');">
                                                @csrf
                                                <input type="hidden" name="requestName" value="{{ $requestId }}">
                                                <button type="submit" class="btn btn-sm" style="background: var(--red-soft); color: var(--red); border: none;">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                        </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>
        </div>

    </main>
  </div>
</div>

{{-- ── REQUEST DETAILS MODAL ── --}}
<div class="modal fade" id="requestDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-clipboard2-data me-2"></i>
                    Request <span id="modal-request-id"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-meta">
                <div class="modal-meta-item">
                    <label>Supplier / To Shop</label>
                    <span id="modal-supplier-name">—</span>
                </div>
                <div class="modal-meta-item">
                    <label>Date</label>
                    <span id="modal-request-date">—</span>
                </div>
                <div class="modal-meta-item">
                    <label>Overall Status</label>
                    <span id="modal-request-status"></span>
                </div>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table class="modal-tbl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Assigned To</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="modal-request-items"></tbody>
                    </table>
                </div>

                <div class="totals-row">
                    <div class="t-item">
                        <label>Total Items</label>
                        <span id="modal-total-items">—</span>
                    </div>
                    <div class="t-item">
                        <label>Total Quantity</label>
                        <span id="modal-total-quantity">—</span>
                    </div>
                    <div class="t-item">
                        <label>Total Price</label>
                        <span id="modal-total-price">—</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {

    /* ── Search ── */
    $('#search-input').on('input', function () {
        var q = $(this).val().toLowerCase();
        $('.request-row').each(function () {
            $(this).toggle($(this).text().toLowerCase().includes(q));
        });
    });

    /* ── Date filter ── */
    $('#dateFilter').on('change', function () {
        var d = $(this).val();
        if (!d) { $('.request-row').show(); return; }
        $('.request-row').each(function () {
            $(this).toggle($(this).data('date') === d);
        });
    });

    /* ── View-details modal ── */
    $(document).on('click', '.view-request-btn', function () {
        var requestId    = $(this).data('request-id');
        var items        = $(this).data('items');
        var totalQty     = $(this).data('total-quantity');
        var totalPrice   = $(this).data('total-price');
        var isReceiver   = $(this).attr('data-is-receiver') === 'true';
        var isAdmin      = $(this).attr('data-is-admin') === 'true';
        var sessionAcct  = "{{ getSessionAccountDisplayName() }}";
        console.log('Modal opened - isReceiver:', isReceiver, 'isAdmin:', isAdmin, 'sessionAccount:', sessionAcct);

        /* Header */
        $('#modal-request-id').text(requestId);
        $('#modal-supplier-name').text(items[0].supplierName || '—');
        $('#modal-request-date').text(items[0].created_at
            ? new Date(items[0].created_at).toLocaleDateString('en-GB', {day:'2-digit', month:'short', year:'numeric'})
            : '—');
        $('#modal-total-items').text(items.length);
        $('#modal-total-quantity').text(Number(totalQty).toLocaleString());
        $('#modal-total-price').text('Tsh ' + Number(totalPrice).toLocaleString());

        /* Status */
        var statuses = items.map(i => i.status);
        var all = v => statuses.every(s => s === v);
        var overall = all('Pending') ? 'Pending'
                    : all('Approved') ? 'Approved'
                    : all('Rejected') ? 'Rejected'
                    : all('Submitted') ? 'Submitted'
                    : 'Mixed';
        var pillMap = {
            Pending  : 'pill-pending',
            Approved : 'pill-approved',
            Rejected : 'pill-rejected',
            Submitted: 'pill-submitted',
            Mixed    : 'pill-mixed'
        };
        $('#modal-request-status')
            .html(`<span class="pill ${pillMap[overall] || 'pill-mixed'}">${overall}</span>`);

        /* Rows */
        $('#modal-request-items').empty();
        $.each(items, function (i, item) {
            var pillCls = pillMap[item.status] || 'pill-mixed';
            var itemTotal = item.quantity * item.price;

            /* Action buttons: show to RECEIVER (supplier) or ADMIN */
            var actionsHtml = '';
            console.log('Processing item:', item.productName || item.productId, '| Status:', item.status, '| isReceiver:', isReceiver, '| isAdmin:', isAdmin, '| Show buttons?', (isReceiver || isAdmin) && item.status !== 'Approved');
            if ((isReceiver || isAdmin) && item.status !== 'Approved') {
                actionsHtml = `
                    <form method="post" class="d-inline">
                        @csrf
                        <input type="hidden" name="requestName" value="${requestId}">
                        <input type="hidden" name="product_id" value="${item.productId}">
                        <button class="btn btn-success btn-sm" name="product_id"
                                formaction="/admin/approveRequest" value="${item.productId}">
                            <i class="bi bi-check-circle"></i>
                        </button>
                        <button class="btn btn-sm" style="background:var(--red-soft);color:var(--red);"
                                name="product_id" formaction="/admin/rejectRequest" value="${item.productId}">
                            <i class="bi bi-x-circle"></i>
                        </button>
                        <button class="btn btn-sm" style="background:var(--amber-soft);color:var(--amber);"
                                name="product_id" formaction="/admin/outOfStockRequest" value="${item.productId}">
                            <i class="bi bi-slash-circle"></i>
                        </button>
                    </form>`;
            }

            $('#modal-request-items').append(`
                <tr>
                    <td style="color:var(--ink-light);font-size:.8rem;">${i + 1}</td>
                    <td><strong>${item.productName || 'Unknown'}</strong></td>
                    <td>${Number(item.quantity).toLocaleString()}</td>
                    <td style="white-space:nowrap;">Tsh ${Number(itemTotal).toLocaleString()}</td>
                    <td>
                        <span class="badge ${item.payment_type === 'cash' ? 'bg-success' : 'bg-info'}">
                            ${item.payment_type ? item.payment_type.charAt(0).toUpperCase() + item.payment_type.slice(1) : 'Cash'}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-secondary">
                            ${item.assigned_to || 'N/A'}
                        </span>
                    </td>
                    <td><span class="pill ${pillCls}">${item.status}</span></td>
                    <td>${actionsHtml}</td>
                </tr>
            `);
        });

        new bootstrap.Modal(document.getElementById('requestDetailsModal')).show();
    });

});
</script>
</body>
</html>