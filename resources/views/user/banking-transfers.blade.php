<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Banking Deposits</title>
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

        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        .main-wrap { max-width: 1900px; margin: 0 auto; padding: 1.25rem 1.5rem; }

        .pg-header {
            background: var(--navy);
            border-radius: 12px;
            padding: 1.4rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.28);
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
            position: relative;
            overflow: hidden;
        }

        .pg-header::before {
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

        .pg-header-content {
            display: flex; align-items: center; gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .back-btn {
            width: 42px; height: 42px;
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
            border: 1.5px solid rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            color: var(--white);
            cursor: pointer;
            transition: all 0.18s;
            text-decoration: none;
        }
        .back-btn:hover {
            background: rgba(255,255,255,0.2);
            border-color: var(--amber);
            color: var(--amber);
        }

        .pg-icon-wrap {
            width: 52px; height: 52px;
            background: rgba(245,158,11,0.15);
            border: 1.5px solid rgba(245,158,11,0.3);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--amber);
            font-size: 1.5rem;
        }

        .pg-title-wrap h1 {
            color: var(--white); font-size: 1.45rem; font-weight: 700;
            margin: 0 0 0.15rem 0;
        }
        .pg-subtitle {
            color: rgba(255,255,255,0.7); font-size: 0.82rem;
            margin: 0;
        }

        .btn-add {
            display: inline-flex; align-items: center; gap: 0.45rem;
            font-size: 0.82rem; font-weight: 600;
            padding: 0.55rem 1rem;
            background: var(--amber); color: var(--navy);
            border: none; border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 3px 12px rgba(245,158,11,0.3);
            transition: all 0.18s;
            position: relative;
            z-index: 1;
        }
        .btn-add:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 18px rgba(245,158,11,0.4);
            color: var(--navy);
        }

        .filter-panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.85rem;
            align-items: end;
        }

        .filter-field {
            display: flex; flex-direction: column; gap: 0.35rem;
        }

        .filter-label {
            font-size: 0.72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.04em;
            color: var(--slate-600);
        }

        .filter-input {
            padding: 0.55rem 0.75rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--slate-50);
            font-size: 0.82rem;
            color: var(--slate-800);
            outline: none;
            transition: all 0.18s;
            font-family: 'Outfit', sans-serif;
        }
        .filter-input:focus {
            border-color: var(--navy-light);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        .filter-select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            padding-right: 2.25rem;
            appearance: none;
        }

        .btn-filter {
            padding: 0.55rem 1rem;
            background: var(--amber);
            color: var(--navy);
            border: none;
            border-radius: 7px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.18s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
        }
        .btn-filter:hover {
            background: var(--amber-dark);
            transform: translateY(-1px);
        }

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
            font-size: 1.05rem; font-weight: 700;
            color: var(--navy);
            margin: 0;
        }

        .card-body { padding: 0; }

        table.transfers-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.845rem;
        }

        table.transfers-tbl thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
        }

        table.transfers-tbl tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
            color: var(--slate-800);
        }

        table.transfers-tbl tbody tr:hover td {
            background: #F8FAFF;
        }

        .amt-value {
            font-family: 'DM Mono', monospace;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--navy);
        }

        .bank-name {
            font-weight: 600;
            color: var(--navy);
        }

        .bank-detail {
            font-size: 0.75rem;
            color: var(--slate-500);
        }

        .shop-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.3rem 0.6rem;
            border-radius: 5px;
            background: var(--amber-pale);
            color: #92400E;
        }

        .action-row {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .btn-delete {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.4rem 0.75rem;
            background: transparent;
            color: var(--rose);
            border: 1.5px solid var(--rose);
            border-radius: 7px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-delete:hover {
            background: var(--rose);
            color: var(--white);
            transform: scale(1.05);
        }

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
            margin-bottom: 1.5rem;
        }

        .modal-content {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .modal-dialog {
            max-width: 680px;
        }

        .modal-header-navy {
            background: var(--navy);
            color: var(--white);
            padding: 1.25rem 1.4rem;
            border-bottom: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header-navy .modal-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin: 0;
        }

        .modal-header-navy .btn-close {
            filter: invert(1) brightness(0.8);
        }

        .modal-body {
            padding: 1.5rem 1.4rem;
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

        .form-label .required {
            color: var(--rose);
            margin-left: 0.2rem;
        }

        .form-control, .form-select, .form-textarea {
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

        .form-control:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--navy-light);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        .form-select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.8rem center;
            padding-right: 2.25rem;
            appearance: none;
        }

        .form-textarea {
            resize: vertical;
            min-height: 90px;
        }

        .form-hint {
            font-size: 0.75rem;
            color: var(--slate-500);
            margin-top: 0.4rem;
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
            margin-top: 1.25rem;
        }
        .btn-submit:hover {
            background: var(--amber-dark);
            transform: translateY(-1px);
            box-shadow: 0 5px 18px rgba(245,158,11,0.4);
        }

        .row-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
            .pg-header { padding: 1rem; margin-bottom: 1rem; flex-direction: column; align-items: flex-start; }
            .pg-header-content { width: 100%; }
            .btn-add { width: 100%; justify-content: center; }
            .filter-grid { grid-template-columns: 1fr; }
            .row-grid { grid-template-columns: 1fr; }

            table.transfers-tbl thead { display: none; }
            table.transfers-tbl tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1.5px solid var(--slate-200);
                border-radius: 10px;
                padding: 1rem;
                background: var(--white);
            }
            table.transfers-tbl tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.65rem 0;
                border-bottom: 1px solid var(--slate-100);
            }
            table.transfers-tbl tbody td:last-child {
                border-bottom: none;
                padding-top: 0.85rem;
                border-top: 1px solid var(--slate-200);
                margin-top: 0.5rem;
            }
            table.transfers-tbl tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--slate-500);
                min-width: 100px;
                font-size: 0.75rem;
            }
            .action-row { width: 100%; justify-content: flex-end; }
        }

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
    @include("user.sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
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
        <div class="main-wrap">

            <div class="pg-header">
                <div class="pg-header-content">
                    <a href="javascript:history.back()" class="back-btn">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <div class="pg-icon-wrap">
                        <i class="bi bi-bank"></i>
                    </div>
                    <div class="pg-title-wrap">
                        <h1>Banking Deposits</h1>
                        <p class="pg-subtitle">Track deposits and fund transfers</p>
                    </div>
                </div>
                @if(canUser("add_banking_transfer"))
                <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addBankingTransfer">
                    <i class="bi bi-plus-lg"></i> Add Deposit
                </button>
                @endif
            </div>

            <div class="filter-panel">
                <form method="GET" action="">
                    <div class="filter-grid">
                        <div class="filter-field">
                            <label class="filter-label">Date From</label>
                            <input type="date" class="filter-input" name="date_from"
                                value="{{ $date_from ?? '' }}">
                        </div>
                        <div class="filter-field">
                            <label class="filter-label">Date To</label>
                            <input type="date" class="filter-input" name="date_to"
                                value="{{ $date_to ?? '' }}">
                        </div>
                        <div class="filter-field">
                            <label class="filter-label">Shop</label>
                            <select class="filter-input filter-select" name="shop_id">
                                <option value="">All Shops</option>
                                @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ ($shop_id ?? '') == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->name }} ({{ $shop->location ?? 'N/A' }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-field">
                            <label class="filter-label">Sort By</label>
                            <select class="filter-input filter-select" name="sort_by">
                                <option value="transfer_date" {{ ($sort_by ?? 'transfer_date') == 'transfer_date' ? 'selected' : '' }}>Date</option>
                                <option value="amount" {{ ($sort_by ?? '') == 'amount' ? 'selected' : '' }}>Amount</option>
                                <option value="created_at" {{ ($sort_by ?? '') == 'created_at' ? 'selected' : '' }}>Created At</option>
                            </select>
                        </div>
                        <div class="filter-field">
                            <label class="filter-label">Direction</label>
                            <select class="filter-input filter-select" name="sort_direction">
                                <option value="desc" {{ ($sort_direction ?? 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                                <option value="asc" {{ ($sort_direction ?? '') == 'asc' ? 'selected' : '' }}>Ascending</option>
                            </select>
                        </div>
                        <div class="filter-field">
                            <label class="filter-label" style="opacity:0;">Apply</label>
                            <button type="submit" class="btn-filter">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Statistics Summary -->
            <div class="card-panel" style="margin-bottom: 1.5rem;">
                <div class="card-head" style="border-bottom: none; padding-bottom: 0;">
                    <h6 class="card-title">Deposit Summary</h6>
                </div>
                <div class="card-body" style="padding: 1.25rem;">
                    <div class="row-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <div style="background: var(--slate-50); padding: 1rem; border-radius: 8px; border: 1.5px solid var(--slate-200);">
                            <div style="font-size: 0.75rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Total Deposits</div>
                            <div style="font-size: 1.5rem; font-weight: 800; color: var(--navy); font-family: 'DM Mono', monospace;">{{ number_format($totalDeposits ?? 0, 2) }}</div>
                        </div>
                        <div style="background: var(--slate-50); padding: 1rem; border-radius: 8px; border: 1.5px solid var(--slate-200);">
                            <div style="font-size: 0.75rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Number of Deposits</div>
                            <div style="font-size: 1.5rem; font-weight: 800; color: var(--navy);">{{ $depositCount ?? 0 }}</div>
                        </div>
                        <div style="background: var(--slate-50); padding: 1rem; border-radius: 8px; border: 1.5px solid var(--slate-200);">
                            <div style="font-size: 0.75rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Average Deposit</div>
                            <div style="font-size: 1.5rem; font-weight: 800; color: var(--navy); font-family: 'DM Mono', monospace;">{{ number_format($averageDeposit ?? 0, 2) }}</div>
                        </div>
                        <div style="background: var(--slate-50); padding: 1rem; border-radius: 8px; border: 1.5px solid var(--slate-200);">
                            <div style="font-size: 0.75rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Highest Deposit</div>
                            <div style="font-size: 1.5rem; font-weight: 800; color: var(--emerald); font-family: 'DM Mono', monospace;">{{ number_format($maxDeposit ?? 0, 2) }}</div>
                        </div>
                        <div style="background: var(--slate-50); padding: 1rem; border-radius: 8px; border: 1.5px solid var(--slate-200);">
                            <div style="font-size: 0.75rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Lowest Deposit</div>
                            <div style="font-size: 1.5rem; font-weight: 800; color: var(--rose); font-family: 'DM Mono', monospace;">{{ number_format($minDeposit ?? 0, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-panel">
                <div class="card-head">
                    <h6 class="card-title">Deposit History</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="transfers-tbl">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th>Supplier Account</th>
                                    <th>Beneficiary</th>
                                    <th>Beneficiary Account</th>
                                    <th>Amount</th>
                                    <th>Allocated Shop</th>
                                    <th>Description</th>
                                    <th style="text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($transfers->isEmpty())
                                <tr>
                                    <td colspan="10">
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <i class="bi bi-bank"></i>
                                            </div>
                                            <div class="empty-title">No Deposits Found</div>
                                            <p class="empty-desc">
                                                Create your first banking deposit to get started
                                            </p>
                                            @if(canUser("add_banking_transfer"))
                                            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addBankingTransfer">
                                                <i class="bi bi-plus-lg"></i> Add Deposit
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @else
                                    @foreach ($transfers as $index => $transfer)
                                    <tr>
                                        <td data-label="#"> {{ $index + 1 }} </td>
                                        <td data-label="Date">
                                            {{ \Carbon\Carbon::parse($transfer->transfer_date)->format('d M Y') }}
                                        </td>
                                        <td data-label="Supplier">
                                            <div class="bank-name">{{ $transfer->supplier->name ?? 'N/A' }}</div>
                                        </td>
                                        <td data-label="Supplier Account">
                                            @if($transfer->supplierAccount)
                                                <div class="bank-name">{{ $transfer->supplierAccount->bank_name }}</div>
                                                <div class="bank-detail">{{ $transfer->supplierAccount->account_number }}</div>
                                            @else
                                                <span style="color:var(--slate-400); font-size:0.82rem;">Not specified</span>
                                            @endif
                                        </td>
                                        <td data-label="Beneficiary">
                                            <div class="bank-name">{{ $transfer->beneficiary->name ?? 'N/A' }}</div>
                                        </td>
                                        <td data-label="Beneficiary Account">
                                            @if($transfer->beneficiaryAccount)
                                                <div class="bank-name">{{ $transfer->beneficiaryAccount->bank_name }}</div>
                                                <div class="bank-detail">{{ $transfer->beneficiaryAccount->account_number }}</div>
                                            @else
                                                <span style="color:var(--slate-400); font-size:0.82rem;">Not specified</span>
                                            @endif
                                        </td>
                                        <td data-label="Amount">
                                            <span class="amt-value">{{ number_format($transfer->amount, 2) }}</span>
                                        </td>
                                        <td data-label="Allocated Shop">
                                            @if($transfer->shop)
                                                <span class="shop-badge">
                                                    <i class="bi bi-shop"></i>
                                                    {{ $transfer->shop->name ?? 'N/A' }}
                                                </span>
                                            @else
                                                <span style="color:var(--slate-400); font-size:0.82rem;">Not allocated</span>
                                            @endif
                                        </td>
                                        <td data-label="Description">
                                            {{ $transfer->description ?? '-' }}
                                        </td>
                                        <td data-label="Actions">
                                            <div class="action-row">
                                                @if(canUser("delete_banking_transfer"))
                                                <form action="/user/banking-transfer/delete/{{ $transfer->id }}" 
                                                    method="POST" 
                                                    style="display: inline;"
                                                    onsubmit="return confirm('Delete this deposit? This action cannot be undone.');">
                                                    @csrf
                                                    <button type="submit" class="btn-delete" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
  </div>
</div>

@if(canUser("add_banking_transfer"))
<div class="modal fade" id="addBankingTransfer" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-navy">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Add Banking Deposit
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="/user/banking-transfer/store" method="post">
                    @csrf

                    <div class="row-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Transfer Date <span class="required">*</span>
                            </label>
                            <input type="date" class="form-control" name="transfer_date"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                         <div class="form-group">
                        <label class="form-label">
                            Allocate to Shop <span class="required">*</span>
                        </label>
                        <select class="form-select" name="shop_id" required>
                            <option value="">Select Shop</option>
                            @foreach($shops as $shop)
                            <option value="{{ $shop->id }}">
                                {{ $shop->name }} ({{ $shop->location ?? 'N/A' }})
                            </option>
                            @endforeach
                        </select>
                        <div class="form-hint">The full transfer amount will be allocated to this shop</div>
                    </div>
                        
                    </div>

                    <div class="row-grid">
                        
 <div class="form-group">
                            <label class="form-label">
                                Supplier <span class="required">*</span>
                            </label>
                            <select class="form-select" name="supplier_id" id="supplier_id" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">
                                    {{ $supplier->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                      
                         <div class="form-group">
                            <label class="form-label">
                                Beneficiary <span class="required">*</span>
                            </label>
                            <select class="form-select" name="beneficiary_id" id="beneficiary_id" required>
                                <option value="">Select Beneficiary</option>
                                @foreach($beneficiaries as $beneficiary)
                                <option value="{{ $beneficiary->id }}">
                                    {{ $beneficiary->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row-grid">
                      

                        <div class="form-group">
                            <label class="form-label">
                                Beneficiary Account <span class="required">*</span>
                            </label>
                            <select class="form-select" name="beneficiary_account_id" id="beneficiary_account_id" required>
                                <option value="">Select Beneficiary Account</option>
                            </select>
                            <div class="form-hint">Select which bank account to credit</div>
                        </div>

                        
                        <div class="form-group">
                            <label class="form-label">
                                Amount <span class="required">*</span>
                            </label>
                            <input type="number" step="0.01" min="0.01" class="form-control" 
                                name="amount" placeholder="0.00" required>
                        </div>
                    </div>

                  

                    <button type="submit" class="btn-submit">
                        <i class="bi bi-send"></i> Create Deposit
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Get supplier accounts data
    var suppliersData = {!! json_encode($suppliers->map(function($supplier) {
        return [
            'id' => $supplier->id,
            'accounts' => $supplier->accounts->map(function($account) {
                return [
                    'id' => $account->id,
                    'bank_name' => $account->bank_name,
                    'account_number' => $account->account_number,
                    'is_primary' => $account->is_primary
                ];
            })
        ];
    })) !!};
    
    // Get beneficiary accounts data
    var beneficiariesData = {!! json_encode($beneficiaries->map(function($beneficiary) {
        return [
            'id' => $beneficiary->id,
            'accounts' => $beneficiary->accounts->map(function($account) {
                return [
                    'id' => $account->id,
                    'bank_name' => $account->bank_name,
                    'account_number' => $account->account_number,
                    'is_primary' => $account->is_primary
                ];
            })
        ];
    })) !!};

    // Function to populate account dropdown
    function populateAccountDropdown(selectElement, accounts) {
        selectElement.empty();
        selectElement.append('<option value="">Select Account</option>');
        
        if (accounts && accounts.length > 0) {
            $.each(accounts, function(index, account) {
                var optionText = account.bank_name + ' - ' + account.account_number;
                if (account.is_primary) {
                    optionText += ' (Primary)';
                }
                selectElement.append(
                    $('<option></option>')
                        .val(account.id)
                        .text(optionText)
                );
            });
        }
    }

    // When supplier changes, load their accounts
    $('#supplier_id').on('change', function() {
        var supplierId = parseInt($(this).val());
        var supplierAccounts = [];
        
        if (supplierId) {
            var supplier = suppliersData.find(function(s) { return s.id === supplierId; });
            if (supplier) {
                supplierAccounts = supplier.accounts;
            }
        }
        
        populateAccountDropdown($('#supplier_account_id'), supplierAccounts);
    });

    // When beneficiary changes, load their accounts
    $('#beneficiary_id').on('change', function() {
        var beneficiaryId = parseInt($(this).val());
        var beneficiaryAccounts = [];
        
        if (beneficiaryId) {
            var beneficiary = beneficiariesData.find(function(b) { return b.id === beneficiaryId; });
            if (beneficiary) {
                beneficiaryAccounts = beneficiary.accounts;
            }
        }
        
        populateAccountDropdown($('#beneficiary_account_id'), beneficiaryAccounts);
    });

    // Form submission validation
    $('#addBankingTransfer form').on('submit', function(e) {
        var beneficiaryAccountId = $('select[name="beneficiary_account_id"]').val();
        
        if (!beneficiaryAccountId) {
            e.preventDefault();
            alert('Please select a beneficiary account');
            return false;
        }
    });
    
    // Auto-focus first input in modal
    $('#addBankingTransfer').on('shown.bs.modal', function() {
        $(this).find('input:first').focus();
    });
});
</script>

</body>
</html>