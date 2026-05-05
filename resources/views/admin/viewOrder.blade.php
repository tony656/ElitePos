<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Order Details</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --navy:          #0B1E3D;
            --navy-mid:      #112952;
            --navy-light:    #1A3A6B;
            --amber:         #F59E0B;
            --amber-dark:    #D97706;
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

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        /* ── Main wrap ── */
        .main-wrap { max-width: 1900px; margin: 0 auto; padding: 1.25rem 1.5rem; }

        /* ── Header actions ── */
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.55rem 1rem;
            background: var(--white);
            color: var(--navy);
            border: 1.5px solid var(--slate-300);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.18s;
            text-decoration: none;
        }
        .btn-back:hover {
            background: var(--navy);
            color: var(--white);
            border-color: var(--navy);
            transform: translateX(-2px);
        }

        .btn-invoice {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.55rem 1rem;
            background: var(--amber);
            color: var(--navy);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 3px 12px rgba(245,158,11,0.3);
            transition: all 0.18s;
        }
        .btn-invoice:hover {
            background: var(--amber-dark);
            transform: translateY(-1px);
            box-shadow: 0 5px 18px rgba(245,158,11,0.4);
            color: var(--navy);
        }

        /* ── Alerts ── */
        .alert {
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .alert-success {
            background: var(--emerald-pale);
            color: #065F46;
        }
        .alert-danger {
            background: var(--rose-pale);
            color: #9F1239;
        }

        /* ── Customer header ── */
        .customer-header {
            background: var(--navy);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.28);
            position: relative;
            overflow: hidden;
        }

        .customer-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 350px;
            height: 350px;
            background: rgba(245,158,11,0.08);
            border-radius: 50%;
            pointer-events: none;
        }

        .customer-header-content {
            position: relative;
            z-index: 1;
        }

        .customer-label {
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: rgba(255,255,255,0.7);
            margin-bottom: 0.35rem;
        }

        .customer-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--amber);
        }

        /* ── Content grid ── */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* ── Card ── */
        .card-panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            overflow: hidden;
        }

        .card-head {
            background: var(--slate-50);
            border-bottom: 1.5px solid var(--slate-200);
            padding: 1.15rem 1.25rem;
        }

        .card-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--navy);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* ── Info grid ── */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--slate-100);
        }

        .info-label {
            font-size: 0.82rem;
            color: var(--slate-500);
            font-weight: 600;
        }

        .info-value {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--navy);
        }

        /* ── Status badge ── */
        .status-badge {
            display: inline-flex;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.35rem 0.65rem;
            border-radius: 5px;
        }

        .status-badge.suspended {
            background: var(--amber-pale);
            color: #92400E;
        }

        .status-badge.completed {
            background: var(--emerald-pale);
            color: #065F46;
        }

        .status-badge.active {
            background: var(--sky-pale);
            color: #075985;
        }

        /* ── Discount badge ── */
        .discount-badge {
            background: var(--violet-pale);
            color: #5B21B6;
            padding: 0.35rem 0.65rem;
            border-radius: 5px;
            font-weight: 700;
            font-size: 0.82rem;
        }

        /* ── Financial summary ── */
        .section-divider {
            border-top: 2px solid var(--slate-200);
            margin: 1.25rem 0;
        }

        .section-label {
            font-size: 0.875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--navy);
            margin-bottom: 1rem;
        }

        .finance-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .finance-box {
            background: var(--slate-50);
            border-left: 3px solid var(--navy);
            border-radius: 8px;
            padding: 1rem;
        }

        .finance-box.total { border-left-color: var(--navy); }
        .finance-box.paid { border-left-color: var(--emerald); }
        .finance-box.credit { border-left-color: var(--rose); }
        .finance-box.balance { border-left-color: var(--amber); }

        .finance-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--slate-500);
            margin-bottom: 0.5rem;
        }

        .finance-value {
            font-family: 'DM Mono', monospace;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--navy);
        }

        .finance-hint {
            font-size: 0.72rem;
            color: var(--slate-500);
            margin-top: 0.35rem;
        }

        /* ── Order summary sidebar ── */
        .summary-panel {
            position: sticky;
            top: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--slate-600);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 0.6rem 0.85rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            background: var(--slate-50);
            font-size: 0.875rem;
            color: var(--slate-800);
            outline: none;
            transition: all 0.18s;
            font-family: 'Outfit', sans-serif;
        }

        .form-control::placeholder {
            color: var(--slate-400);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--navy-light);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        .form-control:read-only {
            background: var(--slate-100);
            font-weight: 700;
            color: var(--navy);
            font-family: 'DM Mono', monospace;
        }

        .form-select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.8rem center;
            padding-right: 2.25rem;
            appearance: none;
        }

        .input-group {
            display: flex;
            gap: 0;
        }

        .input-group .form-control {
            border-radius: 8px 0 0 8px;
        }

        .input-group-text {
            padding: 0.6rem 0.85rem;
            background: var(--navy);
            color: var(--white);
            border: 1.5px solid var(--navy);
            border-radius: 0 8px 8px 0;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .btn-submit {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.75rem 1rem;
            background: var(--amber);
            color: var(--navy);
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.18s;
            box-shadow: 0 3px 12px rgba(245,158,11,0.3);
            margin-top: 0.75rem;
        }
        .btn-submit:hover {
            background: var(--amber-dark);
            transform: translateY(-1px);
            box-shadow: 0 5px 18px rgba(245,158,11,0.4);
        }

        .danger-box {
            border: 2px solid var(--rose);
            border-radius: 8px;
            padding: 1rem;
            background: var(--rose-pale);
        }

        .danger-box .form-control {
            border-color: var(--rose);
        }

        /* ── Order items table ── */
        .order-card {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            margin-bottom: 1rem;
            overflow: hidden;
            transition: all 0.18s;
        }

        .order-header {
            display: grid;
            grid-template-columns: 40px 1fr 180px 120px 150px 120px;
            align-items: center;
            padding: 1rem 1.25rem;
            cursor: pointer;
            transition: all 0.15s;
            gap: 1rem;
        }

        .order-header:hover {
            background: var(--slate-50);
        }

        .expand-icon {
            display: inline-flex;
            transition: transform 0.3s;
            color: var(--navy);
        }

        .expand-icon.expanded {
            transform: rotate(90deg);
        }

        .order-id {
            font-weight: 700;
            color: var(--navy);
            font-size: 0.875rem;
        }

        .order-date {
            font-size: 0.78rem;
            color: var(--slate-500);
        }

        .order-status {
            display: inline-flex;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.35rem 0.65rem;
            border-radius: 5px;
        }

        .order-status.paid {
            background: var(--emerald-pale);
            color: #065F46;
        }

        .order-status.warning {
            background: var(--amber-pale);
            color: #92400E;
        }

        .order-total {
            font-family: 'DM Mono', monospace;
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--navy);
        }

        .btn-pay {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.4rem 0.75rem;
            background: transparent;
            color: var(--sky);
            border: 1.5px solid var(--sky);
            border-radius: 7px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-pay:hover {
            background: var(--sky);
            color: var(--white);
        }

        /* ── Order details ── */
        .order-details {
            display: none;
            background: var(--slate-50);
            border-top: 1.5px solid var(--slate-200);
        }

        .order-details.show {
            display: block;
        }

        .products-wrap {
            padding: 1.25rem;
        }

        .products-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        table.products-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        table.products-tbl thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.65rem 0.85rem;
            border-bottom: 2px solid var(--slate-200);
        }

        table.products-tbl tbody td {
            padding: 0.75rem 0.85rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
            color: var(--slate-800);
        }

        table.products-tbl tbody tr:hover {
            background: var(--white);
        }

        .product-name {
            font-weight: 600;
            color: var(--navy);
        }

        .product-sub {
            font-size: 0.75rem;
            color: var(--slate-500);
        }

        .qty-badge {
            display: inline-flex;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.3rem 0.6rem;
            border-radius: 5px;
            background: var(--slate-200);
            color: var(--slate-700);
        }

        /* ── Modal ── */
        .modal-content {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .modal-dialog {
            max-width: 520px;
        }

        .modal-header-navy {
            background: var(--navy);
            color: var(--white);
            padding: 1.25rem 1.4rem;
            border-bottom: none;
        }

        .modal-header-navy .modal-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0;
        }

        .modal-header-navy .btn-close {
            filter: invert(1) brightness(0.8);
        }

        .modal-body {
            padding: 1.5rem 1.4rem;
        }

        .modal-footer {
            padding: 1rem 1.4rem;
            border-top: 1.5px solid var(--slate-200);
        }

        .btn-cancel {
            padding: 0.6rem 1rem;
            background: transparent;
            color: var(--slate-600);
            border: 1.5px solid var(--slate-300);
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.18s;
        }
        .btn-cancel:hover {
            background: var(--slate-600);
            color: var(--white);
            border-color: var(--slate-600);
        }

        .btn-confirm {
            padding: 0.6rem 1rem;
            background: var(--emerald);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.18s;
            box-shadow: 0 3px 12px rgba(5,150,105,0.3);
        }
        .btn-confirm:hover {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 5px 18px rgba(5,150,105,0.4);
        }

        .form-text {
            font-size: 0.75rem;
            color: var(--slate-500);
            margin-top: 0.35rem;
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 4rem 1.5rem;
        }
        .empty-icon {
            width: 80px; height: 80px;
            margin: 0 auto 1rem;
            display: flex; align-items: center; justify-content: center;
            background: var(--slate-100);
            border-radius: 50%;
            color: var(--slate-400);
            font-size: 2rem;
        }
        .empty-title {
            font-size: 1.1rem; font-weight: 700;
            color: var(--slate-600);
            margin-bottom: 0.4rem;
        }
        .empty-desc {
            font-size: 0.875rem; color: var(--slate-500);
        }

        /* ── Responsive ── */
        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            .summary-panel {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
            .header-actions { flex-direction: column; align-items: flex-start; }
            .btn-back, .btn-invoice { width: 100%; justify-content: center; }
            .info-grid { grid-template-columns: 1fr; }
            .finance-grid { grid-template-columns: 1fr; }
            .order-header {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
        }

        /* ── Animation ── */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .card-panel { animation: slideUp 0.4s ease forwards; }
    </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
        <div class="main-wrap">

            {{-- ── Header Actions ── --}}
            <div class="header-actions">
                <a href="javascript:history.back()" class="btn-back">
                    <i class="bi bi-chevron-left"></i> Back
                </a>
                <form action="viewInvoice" method="post">
                    @csrf
                    <button class="btn-invoice" name="invoice" value="{{$firstOrder->orderName ?? null}}">
                        <i class="bi bi-receipt"></i> Generate Invoice
                    </button>
                </form>
            </div>

            {{-- ── Alerts ── --}}
            @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
            </div>
            @endif

            {{-- ── Customer Header ── --}}
            <div class="customer-header">
                <div class="customer-header-content">
                    <div class="customer-label">Customer Name</div>
                    <div class="customer-name">{{$firstOrder->cName ?? "N/A"}}</div>
                </div>
            </div>

            {{-- ── Content Grid ── --}}
            <div class="content-grid">
                {{-- Main Content --}}
                <div>
                    {{-- Order Details Card --}}
                    <div class="card-panel">
                        <div class="card-head">
                            <h6 class="card-title">
                                <i class="bi bi-file-text"></i> Order Details
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Order ID</span>
                                    <span class="info-value">{{$firstOrder->orderName ?? "N/A"}}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Discount</span>
                                    <span class="discount-badge">{{$firstOrder->discount ?? "N/A"}}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Phone / ID</span>
                                    <span class="info-value">{{$firstOrder->cPhone ?? "N/A"}}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Status</span>
                                    <span class="status-badge @if($firstOrder->status ?? '' == 'Suspended') suspended @elseif($firstOrder->status  ?? '' == 'Completed') completed @else active @endif">
                                        {{$firstOrder->status ?? "N/A"}}
                                    </span>
                                </div>
                            </div>

                            <div class="section-divider"></div>

                            <div class="section-label">Financial Summary</div>

                            <div class="finance-grid">
                                <div class="finance-box total">
                                    <div class="finance-label">Total Amount</div>
                                    @php
                                        if ($Orders) {
                                            $sum = $Orders->sum('totalPrice');
                                        } else {
                                            $sum = 0;
                                        }
                                        $discount = $firstOrder->discount ?? 0;
                                        $total = $sum - $discount;
                                    @endphp
                                    <div class="finance-value">{{number_format($total)}} Tsh</div>
                                </div>

                                <div class="finance-box paid">
                                    <div class="finance-label">Paid Amount</div>
                                    <div class="finance-value">{{number_format($paidSoFar)}} Tsh</div>
                                </div>

                                <div class="finance-box credit">
                                    <div class="finance-label">Amount Credited</div>
                                    @php
                                        if ($Orders) {
                                            $creditSum = $Orders->sum('credit');
                                        } else {
                                            $creditSum = 0;
                                        }
                                    @endphp
                                    <div class="finance-value">{{number_format($creditSum)}} Tsh</div>
                                </div>

                                <div class="finance-box balance">
                                    <div class="finance-label">Outstanding Balance</div>
                                    <div class="finance-value">{{number_format($creditSum - $paidSoFar)}} Tsh</div>
                                    <div class="finance-hint">Amount remaining after credit</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div>
                    <div class="summary-panel">
                        <div class="card-panel">
                            <div class="card-head">
                                <h6 class="card-title">
                                    <i class="bi bi-receipt-cutoff"></i> Order Summary
                                </h6>
                            </div>
                            <div class="card-body">
                                {{-- Discount Form --}}
                                <form action="discount" method="post">
                                    @csrf
                                    <input type="hidden" name="orderName" value="{{$firstOrder->orderName ?? null}}">
                                    <div class="form-group">
                                        <label class="form-label">Apply Discount</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" onchange="this.form.submit()" 
                                                name="discount" value="{{$firstOrder->discount ?? ''}}" 
                                                placeholder="Enter discount">
                                            <span class="input-group-text">Tsh</span>
                                        </div>
                                    </div>
                                </form>

                                <div class="form-group">
                                    <label class="form-label">Grand Total</label>
                                    <input type="text" class="form-control" 
                                        value="{{ number_format($Orders->sum('totalPrice')) }}" readonly>
                                </div>

                                <div class="section-divider"></div>

                                {{-- Order Processing Form --}}
                                <form action="" method="POST">
                                    @csrf
                                    <input type="hidden" name="customerId" value="{{ $firstOrder->cPhone ?? '' }}">

                                    @if (empty($firstOrder->status) || $firstOrder->status == 'Suspended')
                                    <div class="form-group">
                                        <label class="form-label">Transaction Type</label>
                                        <select class="form-select" name="orderType" required>
                                            <option value="Sell">Sell</option>
                                            <option value="Debt">Debt</option>
                                            <option value="Suspended">Suspended</option>
                                        </select>
                                    </div>

                                    <button type="submit" formaction="payout" class="btn-submit">
                                        <i class="bi bi-credit-card"></i> Complete Order
                                    </button>
                                    @endif

                                    <div class="danger-box" style="margin-top: 1.25rem;">
                                        <div class="form-group" style="margin-bottom: 0.75rem;">
                                            <label class="form-label" style="color: var(--rose);">Payment Method</label>
                                            <select class="form-select" name="payment_method" id="payment_method_sidebar" required onchange="toggleChipField('sidebar', {{ $availableChip }})">
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
                                        <div class="form-group" id="chip_amount_field_sidebar" style="display: none; margin-bottom: 0.75rem;">
                                            <label class="form-label">Chip Amount</label>
                                            <input type="number" class="form-control" name="chip_amount"
                                                min="0" max="{{ $availableChip }}" step="0.01"
                                                placeholder="Enter chip amount…" id="chip_amount_input_sidebar"
                                                oninput="updateCashPortion('sidebar', {{ $availableChip }})">
                                            <div class="form-text">Available chip: <strong>{{ number_format($availableChip) }} Tsh</strong></div>
                                            <div class="form-text text-success">Cash portion: <strong id="cash_portion_sidebar">0</strong> Tsh</div>
                                        </div>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">Amount to Pay</label>
                                            <input type="number" name="paymentAmount" class="form-control"
                                                placeholder="Enter payment amount"
                                                id="payment_amount_sidebar"
                                                oninput="updateCashPortion('sidebar', {{ $availableChip }})">
                                        </div>
                                    </div>

                                    <button type="submit" formaction="processDebt" class="btn-submit">
                                        <i class="bi bi-cash-stack"></i> Process Debt Payment
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Order Items ── --}}
            <div class="card-panel">
                <div class="card-head">
                    <h6 class="card-title">
                        <i class="bi bi-cart-check"></i> Order Items
                    </h6>
                </div>
                <div class="card-body">
                    @if($groupedOrders->count() > 0)
                        @php $modalCounter = 0; @endphp
                        @foreach($groupedOrders as $orderId => $orderItems)
                            @php
                                $firstItem = $orderItems->first();
                                $orderTotal = $orderItems->sum('totalPrice');
                                $orderQty = $orderItems->sum('pQuantity');
                                $paidAmount = $orderPayments[$orderId] ?? 0;
                                $remaining = $orderTotal - $paidAmount;
                                $isPaid = $remaining <= 0;
                                $status = $firstItem->status === 'Sell' ? 'In Progress' : $firstItem->status;
                                $modalId = 'payDebtModal_' . $modalCounter++;
                            @endphp

                            <div class="order-card">
                                <div class="order-header" onclick="toggleOrderDetails('{{ $orderId }}')">
                                    <div>
                                        <i class="bi bi-chevron-right expand-icon" id="icon-{{ $orderId }}"></i>
                                    </div>
                                    <div>
                                        <div class="order-id">#{{ $firstItem->orderName ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <div class="order-date">{{ \Carbon\Carbon::parse($firstItem->created_at)->format('M d, Y H:i') }}</div>
                                    </div>
                                    <div>
                                        <span class="order-status {{ $isPaid ? 'paid' : 'warning' }}">
                                            {{ $isPaid ? 'Paid' : $status }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="order-total">{{ number_format($orderTotal) }} Tsh</div>
                                    </div>
                                    <div style="text-align: right;">
                                        @if(!$isPaid && canUser('pay_debts'))
                                        <button type="button" class="btn-pay" data-bs-toggle="modal" 
                                            data-bs-target="#{{ $modalId }}" onclick="event.stopPropagation();">
                                            <i class="bi bi-cash-stack"></i> Pay
                                        </button>
                                        @endif
                                    </div>
                                </div>

                                <div class="order-details" id="details-{{ $orderId }}">
                                    <div class="products-wrap">
                                        <div class="products-title">Products in this order</div>
                                        <table class="products-tbl">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th>Product</th>
                                                    <th>Qty</th>
                                                    <th>Unit Price</th>
                                                    <th>Total</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orderItems as $idx => $item)
                                                    @php
                                                        $product = DB::table('products')->where('product_id', $item->productId)->first();
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $idx + 1 }}</td>
                                                        <td>
                                                            <div class="product-name">{{ $product->name01 ?? 'N/A' }}</div>
                                                            @if(!empty($product->name02))
                                                                <div class="product-sub">{{ $product->name02 }}</div>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="qty-badge">{{ $item->pQuantity ?? 0 }}</span>
                                                        </td>
                                                        <td style="font-family: 'DM Mono', monospace;">
                                                            {{ number_format($item->productPrice ?? 0) }} Tsh
                                                        </td>
                                                        <td style="font-family: 'DM Mono', monospace; font-weight: 700;">
                                                            {{ number_format($item->totalPrice) }} Tsh
                                                        </td>
                                                        <td style="font-size: 0.75rem; color: var(--slate-500);">
                                                            {{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y H:i') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-inbox"></i>
                            </div>
                            <div class="empty-title">No Orders Found</div>
                            <p class="empty-desc">This customer has no pending orders</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </main>
  </div>
</div>

{{-- ══════════════════════════════════════
     PAYMENT MODALS
══════════════════════════════════════ --}}
@if(isset($groupedOrders) && $groupedOrders->count() > 0)
    @php $modalCounter = 0; @endphp
    @foreach($groupedOrders as $orderId => $orderItems)
        @php
            $firstItem = $orderItems->first();
            $orderTotal = $orderItems->sum('totalPrice');
            $paidAmount = $orderPayments[$orderId] ?? 0;
            $remaining = $orderTotal - $paidAmount;
            $isPaid = $remaining <= 0;
            $modalId = 'payDebtModal_' . $modalCounter++;
        @endphp

        @if(!$isPaid && canUser('pay_debts'))
        <div class="modal fade" id="{{ $modalId }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header-navy">
                        <h5 class="modal-title">
                            <i class="bi bi-cash-stack me-2"></i>Pay Debt — Order #{{ $firstItem->orderName }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ url('admin/payInvoiceDebt') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label">Remaining Amount</label>
                                <input type="text" class="form-control"
                                    value="{{ number_format($remaining) }} Tsh" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-select" name="payment_method" id="payment_method_{{ $modalId }}" required onchange="toggleChipField('{{ $modalId }}', {{ $availableChip }})">
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
                            <div class="form-group" id="chip_amount_field_{{ $modalId }}" style="display: none;">
                                <label class="form-label">Chip Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="chip_amount"
                                    min="0" max="{{ $availableChip }}" step="0.01"
                                    placeholder="Enter chip amount…" id="chip_amount_input_{{ $modalId }}">
                                <div class="form-text">Available chip: <strong>{{ number_format($availableChip) }} Tsh</strong></div>
                                <div class="form-text text-success">Cash portion will be: <strong id="cash_portion_{{ $modalId }}">{{ number_format($remaining) }}</strong> Tsh</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="paymentAmount"
                                    min="0" max="{{ $remaining }}" step="0.01" required
                                    placeholder="Enter amount to pay" id="payment_amount_{{ $modalId }}"
                                    oninput="updateCashPortion('{{ $modalId }}', {{ $availableChip }})">
                                <div class="form-text">Maximum: {{ number_format($remaining) }} Tsh</div>
                            </div>
                            <input type="hidden" name="invoiceName" value="{{ $firstItem->orderName }}">
                            <input type="hidden" name="shopName" value="{{ $firstItem->account ?? 'Unknown' }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-confirm">Confirm Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endforeach
@endif
<script>
    function toggleOrderDetails(orderId) {
        const detailsRow = document.getElementById('details-' + orderId);
        const icon = document.getElementById('icon-' + orderId);

        if (detailsRow.classList.contains('show')) {
            detailsRow.classList.remove('show');
            icon.classList.remove('expanded');
        } else {
            detailsRow.classList.add('show');
            icon.classList.add('expanded');
        }
    }

    // Payment method toggle functions
    function toggleChipField(modalId, availableChip) {
        const paymentMethod = document.getElementById('payment_method_' + modalId);
        const chipAmountField = document.getElementById('chip_amount_field_' + modalId);
        const chipAmountInput = document.getElementById('chip_amount_input_' + modalId);
        const paymentAmountInput = document.getElementById('payment_amount_' + modalId);
        const cashPortionSpan = document.getElementById('cash_portion_' + modalId);
        
        if (paymentMethod.value === 'chip') {
            chipAmountField.style.display = 'block';
            chipAmountInput.required = true;
            // Update cash portion
            updateCashPortion(modalId, availableChip);
        } else {
            chipAmountField.style.display = 'none';
            chipAmountInput.required = false;
            chipAmountInput.value = '';
            // Reset cash portion display to full amount
            if (cashPortionSpan && paymentAmountInput.value) {
                cashPortionSpan.textContent = formatNumber(paymentAmountInput.value);
            }
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
            const chipAmount = parseFloat(chipAmountInput.value) || 0;
            const cashPortion = Math.max(0, paymentAmount - chipAmount);
            cashPortionSpan.textContent = formatNumber(cashPortion);
        } else {
            cashPortionSpan.textContent = formatNumber(paymentAmount);
        }
    }
    
    function formatNumber(num) {
        return new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(num);
    }
</script>

</body>
</html>