<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Customer Debt Products</title>
    @include("links")
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3f37c9;
            --success: #10b981;
            --danger: #ef476f;
            --warning-bg: #fef3c7;
            --warning-text: #92400e;
            --text: #1a1a2e;
            --muted: #6c757d;
            --border: #e5e7eb;
            --surface: #f8f9fa;
            --white: #ffffff;
            --radius-md: 8px;
            --radius-lg: 12px;
            --transition: all 0.2s ease;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--text);
            background: #f3f4f6;
            min-height: 100vh;
        }

        main { padding: 2rem !important; }

        .breadcrumb-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
        }
        .breadcrumb-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.8rem;
            color: var(--muted);
            text-decoration: none;
            transition: var(--transition);
        }
        .breadcrumb-link:hover { color: var(--text); }
        .breadcrumb-sep { font-size: 0.8rem; color: var(--border); }
        .breadcrumb-cur { font-size: 0.8rem; color: var(--text); font-weight: 600; }

        .page-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        .page-top h4 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text);
        }
        .page-top p {
            font-size: 0.875rem;
            color: var(--muted);
            margin-top: 0.2rem;
        }

        .customer-banner {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .customer-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: rgba(67,97,238,0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        .customer-name { font-size: 1rem; font-weight: 700; color: var(--text); }
        .customer-meta { font-size: 0.8rem; color: var(--muted); margin-top: 2px; }
        .customer-total { margin-left: auto; text-align: right; }
        .total-label { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; color: var(--muted); }
        .total-value { font-size: 1.4rem; font-weight: 700; color: var(--danger); }

        .metrics-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        .metric {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 0.875rem 1rem;
        }
        .metric-label { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--muted); margin-bottom: 0.25rem; }
        .metric-value { font-size: 1.4rem; font-weight: 700; color: var(--text); }
        .metric-value.danger { color: var(--danger); }
        .metric-value.success { color: var(--success); }

        .invoice-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            margin-bottom: 1.25rem;
            overflow: visible;
            position: relative;
            z-index: 1;
        }

        .invoice-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .invoice-title-wrap {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .invoice-icon {
            width: 30px;
            height: 30px;
            border-radius: var(--radius-md);
            background: rgba(67,97,238,0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .invoice-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 0.25rem 0.625rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-badge.paid {
            background: rgba(16,185,129,0.1);
            color: #065f46;
        }
        .status-badge.partial {
            background: var(--warning-bg);
            color: var(--warning-text);
        }

        .progress-section {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
        }

        .amounts-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .amount-label { font-size: 0.7rem; color: var(--muted); font-weight: 600; text-transform: uppercase; margin-bottom: 0.2rem; }
        .amount-value { font-size: 1.1rem; font-weight: 700; color: var(--text); }
        .amount-value.success { color: var(--success); }
        .amount-value.danger { color: var(--danger); }

        .progress-track {
            height: 6px;
            background: var(--surface);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 0.375rem;
        }
        .progress-fill {
            height: 100%;
            background: var(--success);
            border-radius: 3px;
            transition: width 0.4s ease;
        }
        .progress-labels {
            display: flex;
            justify-content: space-between;
            font-size: 0.72rem;
            color: var(--muted);
        }

        .table { margin-bottom: 0; }

        .table thead th {
            background: var(--surface);
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-top: none;
            border-bottom: 1px solid var(--border);
            padding: 0.625rem 1rem;
        }

        .table tbody td {
            padding: 0.75rem 1rem;
            border-color: var(--border);
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .table tbody tr:hover td { background: var(--surface); }
        .table tbody tr:last-child td { border-bottom: none; }

        .product-name { font-weight: 600; }

        .qty-pill {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            background: var(--surface);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--muted);
        }

        .price-strong { font-weight: 700; }

        .btn-view-invoice {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 0.3rem 0.7rem;
            font-size: 0.78rem;
            font-weight: 600;
            border-radius: var(--radius-md);
            background: rgba(67,97,238,0.1);
            color: var(--primary);
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }
        .btn-view-invoice:hover { background: var(--primary); color: var(--white); }

        .pay-footer {
            padding: 0.875rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            border-top: 1px solid var(--border);
            background: var(--surface);
        }

        .btn-pay {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 700;
            border-radius: var(--radius-md);
            background: var(--success);
            color: white;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }
        .btn-pay:hover { opacity: 0.85; }

        .modal-header {
            background: var(--primary);
            color: white;
            border-bottom: none;
        }
        .modal-header .btn-close { filter: invert(1); }

        .empty-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 3rem;
            text-align: center;
            color: var(--muted);
        }
        .empty-card i { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; }

        @media (max-width: 992px) {
            .metrics-row { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            main { padding: 1rem !important; }
            .amounts-grid { grid-template-columns: 1fr 1fr; }
            .customer-total { display: none; }
        }
        @media (max-width: 576px) {
            .metrics-row { grid-template-columns: 1fr 1fr; }
            .table thead th:nth-child(3),
            .table tbody td:nth-child(3) { display: none; }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @include("user/sidenav")

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">

            @if(session('success'))
                <div class="alert alert-success d-flex justify-content-between">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger d-flex justify-content-between">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="breadcrumb-row">
                <a href="{{ url('admin/shopInvoices') }}" class="breadcrumb-link">
                    <i class="bi bi-shop"></i> Shops
                </a>
                <span class="breadcrumb-sep">/</span>
                <a href="{{ url('admin/shopDebtors/' . urlencode($shopName)) }}" class="breadcrumb-link">
                    {{ $shopName }}
                </a>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-cur">{{ $customerName }}</span>
            </div>

            <div class="page-top">
                <div>
                    <h4><i class="bi bi-receipt"></i> Debt products</h4>
                    <p>All products across outstanding invoices</p>
                </div>
            </div>

            <form action="{{ url('admin/customerDebtProducts') }}" method="post" class="row g-3 align-items-end mb-4">
                @csrf
                <input type="hidden" name="customer" value="{{ $customerName }}">
                <input type="hidden" name="shop" value="{{ $shopName }}">

                <div class="col-md-4">
                    <label class="form-label">Start date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">End date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate ?? '' }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Apply filter</button>
                    <button type="submit" name="reset" value="1" class="btn btn-outline-secondary w-100">Reset</button>
                </div>
            </form>

            @if(!empty($startDate) || !empty($endDate))
                <div class="mb-3">
                    <span class="badge bg-secondary">
                        Showing
                        @if(!empty($startDate))
                            {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }}
                        @else
                            from the beginning
                        @endif
                        @if(!empty($endDate))
                            &mdash; {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                        @endif
                    </span>
                </div>
            @endif

            @php
                $groupedInvoices = $debtProducts->groupBy('orderName');
                $groupedByDate = $debtProducts->groupBy(function ($item) {
                    return \Carbon\Carbon::parse($item->created_at)->format('Y-m-d');
                });
                $totalDebt = $debtProducts->sum('totalPrice');
                $totalPaid = collect($invoicePayments)->sum('paid');
                $totalItems = $debtProducts->count();
                $invoiceCount = $groupedInvoices->count();
                $modalCounter = 0;
            @endphp

            <div class="customer-banner">
                <div class="customer-avatar">
                    {{ strtoupper(substr($customerName, 0, 1)) }}{{ strtoupper(substr(strstr($customerName, ' '), 1, 1)) }}
                </div>
                <div>
                    <div class="customer-name">{{ $customerName }}</div>
                    <div class="customer-meta">
                        <i class="bi bi-building"></i> {{ $shopName }}
                        &nbsp;&middot;&nbsp;
                        {{ $groupedInvoices->count() }} {{ Str::plural('invoice', $groupedInvoices->count()) }}
                    </div>
                </div>
                <div class="customer-total">
                    <div class="total-label">Total debt</div>
                    <div class="total-value">{{ number_format($debtProducts->sum('totalPrice')) }}</div>
                </div>
            </div>

            <div class="metrics-row">
                <div class="metric">
                    <div class="metric-label">Invoices</div>
                    <div class="metric-value">{{ $invoiceCount }}</div>
                </div>
                <div class="metric">
                    <div class="metric-label">Total debt</div>
                    <div class="metric-value danger">{{ number_format($totalDebt) }}</div>
                </div>
                <div class="metric">
                    <div class="metric-label">Paid so far</div>
                    <div class="metric-value success">{{ number_format($totalPaid) }}</div>
                </div>
                <div class="metric">
                    <div class="metric-label">Line items</div>
                    <div class="metric-value">{{ $totalItems }}</div>
                </div>
            </div>

            @php $modalCounter = 0; @endphp
            @forelse($groupedByDate as $date => $dayItems)
                @php
                    $dateLabel = \Carbon\Carbon::parse($date)->format('F j, Y');
                    $dateInvoices = $dayItems->groupBy('orderName');
                @endphp

                @php
                    $dateInvoices = $dayItems->groupBy('orderName');
                @endphp
                <div class="mb-4 px-3 py-2 rounded-3" style="background: #f8f9fa; border: 1px solid #e5e7eb;">
                    <h5 class="mb-0">{{ $dateLabel }}</h5>
                    <p class="text-muted small mb-0">{{ $dateInvoices->count() }} invoice{{ $dateInvoices->count() == 1 ? '' : 's' }} on this date</p>
                </div>

                @foreach($dateInvoices as $invoiceName => $items)
                    @php
                        $invoiceTotal = $items->sum('totalPrice');
                        $paymentInfo = $invoicePayments[$invoiceName] ?? ['paid' => 0, 'total' => $invoiceTotal, 'remaining' => $invoiceTotal];
                        $remaining = $paymentInfo['remaining'];
                        $paid = $paymentInfo['paid'];
                        $isPaid = $remaining <= 0;
                        $paidPct = $invoiceTotal > 0 ? round(($paid / $invoiceTotal) * 100) : 0;
                        $modalId = 'payDebtModal_' . $modalCounter++;
                    @endphp

                <div class="invoice-card">

                    <div class="invoice-header">
                        <div class="invoice-title-wrap">
                            <div class="invoice-icon">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <span class="invoice-name">Invoice #{{ $invoiceName }}</span>
                        </div>
                        @if($isPaid)
                            <span class="status-badge paid">
                                <i class="bi bi-check-circle-fill"></i> Fully paid
                            </span>
                        @else
                            <span class="status-badge partial">
                                <i class="bi bi-clock-fill"></i> {{ $paidPct > 0 ? 'Partial payment' : 'Unpaid' }}
                            </span>
                        @endif
                    </div>

                    <div class="progress-section">
                        <div class="amounts-grid">
                            <div>
                                <div class="amount-label">Total</div>
                                <div class="amount-value">{{ number_format($invoiceTotal) }}</div>
                            </div>
                            <div>
                                <div class="amount-label">Paid</div>
                                <div class="amount-value success">{{ number_format($paid) }}</div>
                            </div>
                            <div>
                                <div class="amount-label">Remaining</div>
                                <div class="amount-value {{ $remaining > 0 ? 'danger' : '' }}">{{ number_format($remaining) }}</div>
                            </div>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" style="width: {{ $paidPct }}%"></div>
                        </div>
                        <div class="progress-labels">
                            <span>{{ $paidPct }}% paid</span>
                            <span>{{ $isPaid ? 'Completed' : (100 - $paidPct) . '% remaining' }}</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Unit price</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $product)
                                    <tr>
                                        <td>
                                            <span class="product-name">{{ $product->name01 ?? $product->productId ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="qty-pill">×{{ $product->pQuantity ?? 0 }}</span>
                                        </td>
                                        <td>{{ number_format($product->productPrice ?? 0) }}</td>
                                        <td>
                                            <span class="price-strong">{{ number_format($product->totalPrice) }}</span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($product->created_at)->format('M d, Y H:i') }}</td>
                                        <td>
                                            @if($paid > 0 && canUser('pay_debts'))
                                                <form action="{{ url('admin/undoInvoiceDebt') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="invoiceName" value="{{ $product->orderName }}">
                                                    <input type="hidden" name="shopName" value="{{ $shopName }}">
                                                    <button
                                                        type="submit"
                                                        class="btn-view-invoice"
                                                        onclick="return confirm('Undo last payment for invoice #{{ $product->orderName }}?');"
                                                    >
                                                        <i class="bi bi-arrow-counterclockwise"></i> Undo payment
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted small">No payment</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(!$isPaid && canUser('pay_debts'))
                        <div class="pay-footer">
                            <button type="button" class="btn-pay" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                                <i class="bi bi-cash-stack"></i>
                                Pay remaining &mdash; {{ number_format($remaining) }}
                            </button>
                        </div>
                    @endif

                </div>

                @if(!$isPaid && canUser('pay_debts'))
                <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true" style="z-index: 999999;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Pay debt — Invoice #{{ $invoiceName }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ url('admin/payInvoiceDebt') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Remaining amount</label>
                                        <input type="text" class="form-control bg-light" value="{{ number_format($remaining) }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Payment method <span class="text-danger">*</span></label>
                                        <select class="form-control" name="payment_method" id="payment_method_{{ $modalId }}" required onchange="toggleChipField('{{ $modalId }}', {{ $availableChip }}, {{ $remaining }})">
                                            <option value="cash">Cash</option>
                                            @if($availableChip > 0)
                                            <option value="chip">Chip</option>
                                            @else
                                            <option value="chip" disabled>Chip (unavailable)</option>
                                            @endif
                                        </select>
                                        @if($availableChip <= 0)
                                        <div class="form-text text-danger">Chip balance is zero. Cash only.</div>
                                        @endif
                                    </div>
                                    <div class="mb-3" id="chip_amount_field_{{ $modalId }}" style="display: none;">
                                        <label class="form-label fw-semibold">Chip amount <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="chip_amount"
                                            min="0" max="{{ $availableChip }}" step="0.01"
                                            placeholder="Enter chip amount…" id="chip_amount_input_{{ $modalId }}"
                                            oninput="updateChipPayment('{{ $modalId }}', {{ $availableChip }}, {{ $remaining }})">
                                        <div class="form-text">Available chip: <strong>{{ number_format($availableChip) }} Tsh</strong></div>
                                        <div class="form-text text-success">Cash portion: <strong id="cash_portion_{{ $modalId }}">0</strong> Tsh</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Payment amount <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="paymentAmount"
                                         max="{{ $remaining }}" step="1" required
                                            placeholder="Enter amount to pay" id="payment_amount_{{ $modalId }}"
                                            oninput="updateCashPortion('{{ $modalId }}', {{ $availableChip }})">
                                        <div class="form-text text-muted">Maximum: {{ number_format($remaining) }}</div>
                                    </div>
                                    <input type="hidden" name="invoiceName" value="{{ $invoiceName }}">
                                    <input type="hidden" name="shopName" value="{{ $shopName }}">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success fw-bold">Confirm payment</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                @endforeach
            @empty
                <div class="empty-card">
                    <i class="bi bi-check-circle text-success"></i>
                    <p class="fw-semibold">No debt products found for this customer.</p>
                </div>
            @endforelse

        </main>
    </div>
</div>

<script>
    // Payment method toggle functions
    function toggleChipField(modalId, availableChip, remaining) {
        const paymentMethod = document.getElementById('payment_method_' + modalId);
        const chipAmountField = document.getElementById('chip_amount_field_' + modalId);
        const chipAmountInput = document.getElementById('chip_amount_input_' + modalId);
        const paymentAmountInput = document.getElementById('payment_amount_' + modalId);
        const cashPortionSpan = document.getElementById('cash_portion_' + modalId);
        
        if (paymentMethod.value === 'chip') {
            chipAmountField.style.display = 'block';
            chipAmountInput.required = true;
            // Auto-set payment amount to chip amount
            if (chipAmountInput.value) {
                paymentAmountInput.value = chipAmountInput.value;
            }
            // Update cash portion
            updateChipPayment(modalId, availableChip, remaining);
        } else {
            chipAmountField.style.display = 'none';
            chipAmountInput.required = false;
            chipAmountInput.value = '';
            // Reset payment amount to full remaining
            paymentAmountInput.value = remaining;
            // Reset cash portion display to full amount
            if (cashPortionSpan) {
                cashPortionSpan.textContent = formatNumber(remaining);
            }
        }
    }
    
    function updateChipPayment(modalId, availableChip, remaining) {
        const paymentMethod = document.getElementById('payment_method_' + modalId);
        const paymentAmountInput = document.getElementById('payment_amount_' + modalId);
        const chipAmountInput = document.getElementById('chip_amount_input_' + modalId);
        const cashPortionSpan = document.getElementById('cash_portion_' + modalId);
        
        if (!paymentAmountInput || !cashPortionSpan || !chipAmountInput) return;
        
        const chipAmount = parseFloat(chipAmountInput.value) || 0;
        
        if (paymentMethod.value === 'chip') {
            // Auto-set payment amount to chip amount
            paymentAmountInput.value = chipAmount;
            // Cash portion is always 0 when using chip
            cashPortionSpan.textContent = '0';
        }
    }
    
    function updateCashPortion(modalId, availableChip) {
        const paymentMethod = document.getElementById('payment_method_' + modalId);
        const paymentAmountInput = document.getElementById('payment_amount_' + modalId);
        const chipAmountInput = document.getElementById('chip_amount_input_' + modalId);
        const cashPortionSpan = document.getElementById('cash_portion_' + modalId);
        
        if (!paymentAmountInput || !cashPortionSpan) return;
        
        const paymentAmount = parseFloat(paymentAmountInput.value) || 0;
        
        if (paymentMethod.value === 'chip') {
            // When chip is selected, cash portion is always 0
            cashPortionSpan.textContent = '0';
        } else {
            // For cash payments, cash portion equals payment amount
            cashPortionSpan.textContent = formatNumber(paymentAmount);
        }
    }
    
    function formatNumber(num) {
        return new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(num);
    }
</script>
</body>
</html>