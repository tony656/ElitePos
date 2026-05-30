<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Request Details</title>
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

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface-2);
            color: var(--ink);
        }

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
        .btn-danger    { background: var(--red); color: #fff; }
        .btn-danger:hover { background: #a93226; }

        .card {
            background: var(--surface);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            margin: 1.5rem 2rem;
            overflow: hidden;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            background: var(--surface-2);
            border-bottom: 1px solid var(--border);
        }

        .card-header h3 {
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .info-item label {
            display: block;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--ink-light);
            margin-bottom: .3rem;
        }

        .info-item span {
            font-size: 1rem;
            font-weight: 600;
            color: var(--ink);
        }

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
        .pill-pending { background: var(--amber-soft); color: var(--amber); }
        .pill-approved{ background: var(--green-soft); color: var(--green); }
        .pill-rejected{ background: var(--red-soft); color: var(--red); }
        .pill-submitted{ background: var(--blue-soft); color: var(--blue); }
        .pill-mixed   { background: var(--purple-soft); color: var(--purple); }

        .stock-available {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .25rem .65rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
            background: var(--green-soft);
            color: var(--green);
        }
        .stock-unavailable {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .25rem .65rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
            background: var(--red-soft);
            color: var(--red);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table thead tr {
            background: var(--surface-2);
        }

        .details-table th {
            padding: .85rem 1rem;
            text-align: left;
            font-size: .75rem;
            font-weight: 600;
            color: var(--ink-light);
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .details-table td {
            padding: .85rem 1rem;
            border-bottom: 1px solid var(--border);
            font-size: .875rem;
            vertical-align: middle;
        }

        .details-table tbody tr:last-child td {
            border-bottom: none;
        }

        .totals {
            margin-top: 1.5rem;
            padding: 1rem 1.25rem;
            background: var(--surface-2);
            border-radius: var(--radius);
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .totals div label {
            font-size: .7rem;
            text-transform: uppercase;
            color: var(--ink-light);
            display: block;
            margin-bottom: .2rem;
        }

        .totals div span {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .action-buttons {
            display: flex;
            gap: .75rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        @media (max-width: 768px) {
            .card {
                margin: 1rem;
            }
            .info-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        @include("admin/sidenav")

        <main class="col-md-9 ms-sm-auto col-lg-10 px-0 pt-3 bg-light">
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
            <div class="page-header">
                <div class="page-header-left">
                    <a href="{{ url('admin/viewRequest') }}" class="back-btn">
                        <i class="bi bi-chevron-left"></i> Back to Requests
                    </a>
                    <span class="page-title">Request Details: {{ $requestId }}</span>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3><i class="bi bi-clipboard-data me-2"></i> Request Information</h3>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Request ID</label>
                            <span>{{ $requestId }}</span>
                        </div>
                        <div class="info-item">
                            <label>Date</label>
                            <span>{{ date('F d, Y', strtotime($items[0]->created_at)) }}</span>
                        </div>
                        <div class="info-item">
                            <label>From Shop (Requester)</label>
                            <span>{{ $requesterName ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <label>To Shop (Supplier)</label>
                            <span>{{ $supplierName ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Overall Status</label>
                            @php
                                $pillMap = [
                                    'Pending' => 'pill-pending',
                                    'Approved' => 'pill-approved',
                                    'Rejected' => 'pill-rejected',
                                    'Submitted' => 'pill-submitted',
                                    'Mixed' => 'pill-mixed'
                                ];
                                $pillClass = $pillMap[$overallStatus] ?? 'pill-mixed';
                            @endphp
                            <span class="pill {{ $pillClass }}">{{ $overallStatus }}</span>
                        </div>
                        <div class="info-item">
                            <label>Payment Type</label>
                            <span>{{ ucfirst($items[0]->payment_type ?? 'Cash') }}</span>
                        </div>
                    </div>

                    <div class="table-wrapper">
                        <table class="details-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Product ID</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>Stock Status</th>
                                    <th>Item Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($formattedItems as $index => $item)
                                    @php
                                        $itemTotal = $item->quantity * $item->price;
                                        $stockBadge = ($item->stockQty >= $item->quantity)
                                            ? '<span class="stock-available">✓ In Stock (' . number_format($item->stockQty) . ' avail)</span>'
                                            : '<span class="stock-unavailable">✗ Out of Stock (Need ' . number_format($item->quantity - $item->stockQty) . ' more)</span>';
                                        
                                        $itemPillMap = [
                                            'Pending' => 'pill-pending',
                                            'Approved' => 'pill-approved',
                                            'Rejected' => 'pill-rejected',
                                            'Submitted' => 'pill-submitted',
                                        ];
                                        $itemPillClass = $itemPillMap[$item->status] ?? 'pill-mixed';
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong style="color: var(--blue);">{{ $item->productName }}</strong>
                                        </td>
                                        <td><small style="color: var(--ink-light);">{{ $item->productId ?? 'N/A' }}</small></td>
                                        <td>{{ number_format($item->quantity) }}</td>
                                        <td>Tsh {{ number_format($item->price) }}</td>
                                        <td><strong>Tsh {{ number_format($itemTotal) }}</strong></td>
                                        <td>{!! $stockBadge !!}</td>
                                        <td><span class="pill {{ $itemPillClass }}">{{ $item->status }}</span></td>
                                        <td>
                                            @if($isAdmin && $item->status !== 'Approved')
                                                <div style="display: flex; gap: 5px;">
                                                    <form method="post" action="/admin/approveRequest" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="requestName" value="{{ $requestId }}">
                                                        <input type="hidden" name="product_id" value="{{ $item->productId }}">
                                                        <input type="hidden" name="supplierId" value="{{ $supplierAccount }}">
                                                        <button type="submit" class="btn btn-success btn-sm" title="Approve this item">
                                                            <i class="bi bi-check-circle"></i> Approve
                                                        </button>
                                                    </form>
                                                    <form method="post" action="/admin/rejectRequest" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="requestName" value="{{ $requestId }}">
                                                        <input type="hidden" name="product_id" value="{{ $item->productId }}">
                                                        <input type="hidden" name="supplierId" value="{{ $supplierAccount }}">
                                                        <button type="submit" class="btn btn-sm" style="background: var(--red-soft); color: var(--red);" title="Reject this item">
                                                            <i class="bi bi-x-circle"></i> Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="totals">
                        <div>
                            <label>Total Items</label>
                            <span>{{ count($formattedItems) }}</span>
                        </div>
                        <div>
                            <label>Total Quantity</label>
                            <span>{{ number_format($totalQuantity) }}</span>
                        </div>
                        <div>
                            <label>Total Price</label>
                            <span>Tsh {{ number_format($totalPrice) }}</span>
                        </div>
                    </div>

                   
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>