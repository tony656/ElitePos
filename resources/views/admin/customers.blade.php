<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Customer Management</title>
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

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        /* ── Main wrap ── */
        .main-wrap { max-width: 1800px; margin: 0 auto; padding: 1.25rem 1.5rem; }

        /* ── Page header ── */
        .pg-header {
            background: var(--navy);
            border-radius: 12px;
            padding: 1rem 1.4rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.28);
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
        }

        .pg-title-wrap {
            display: flex; align-items: center; gap: 0.75rem;
        }

        .back-btn {
            width: 36px; height: 36px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.75);
            font-size: 0.95rem;
            cursor: pointer; text-decoration: none;
            transition: all 0.15s;
        }
        .back-btn:hover { background: rgba(255,255,255,0.14); color: var(--white); }

        .pg-title {
            color: var(--white); font-size: 1.35rem; font-weight: 700;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .pg-title span { color: var(--amber); }

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

        /* ── Alert ── */
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
        .alert-danger {
            background: var(--rose-pale);
            border-color: var(--rose);
            color: #9F1239;
        }

        /* ── Filter panel ── */
        .filter-panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 1.15rem 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
        }

        .filter-title {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.05em; color: var(--slate-400); margin-bottom: 0.85rem;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr auto;
            gap: 0.85rem;
            align-items: end;
        }

        @media (max-width: 1200px) {
            .filter-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 768px) {
            .filter-grid { grid-template-columns: 1fr; }
        }

        .field { display: flex; flex-direction: column; gap: 0.25rem; }
        .field-label { font-size: 0.78rem; font-weight: 600; color: var(--slate-600); }
        .field-input {
            font-family: 'Outfit', sans-serif;
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .field-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }
        select.field-input {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            padding-right: 2.25rem;
            appearance: none;
        }

        .search-input {
            padding-left: 2.4rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cpath d='m21 21-4.35-4.35'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 0.75rem center;
            background-size: 16px;
        }

        .reset-btn {
            display: inline-flex; align-items: center; gap: 0.35rem;
            font-size: 0.82rem; font-weight: 600;
            padding: 0.48rem 0.9rem;
            border-radius: 7px;
            border: 1.5px solid var(--slate-200);
            background: var(--white);
            color: var(--slate-700);
            cursor: pointer;
            transition: all 0.15s;
        }
        .reset-btn:hover { background: var(--slate-50); border-color: var(--navy-light); color: var(--navy); }

        /* ── Table panel ── */
        .panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            overflow: hidden;
        }

        .table-wrap { overflow-x: auto; }

        table.cust-tbl { width: 100%; border-collapse: collapse; font-size: 0.845rem; }
        table.cust-tbl thead th {
            background: var(--navy);
            color: rgba(255,255,255,0.9);
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            padding: 0.85rem 0.8rem;
            border-bottom: 2px solid var(--navy-mid);
            white-space: nowrap;
            cursor: pointer;
            user-select: none;
            transition: background 0.15s;
        }
        table.cust-tbl thead th:hover { background: var(--navy-mid); }
        table.cust-tbl thead th.sortable::after {
            content: ' ⇅';
            font-size: 0.85rem;
            opacity: 0.5;
            margin-left: 4px;
            transition: opacity 0.2s;
        }
        table.cust-tbl thead th.sortable:hover::after { opacity: 1; }
        table.cust-tbl thead th.sorted-asc::after { content: ' ↑'; opacity: 1; }
        table.cust-tbl thead th.sorted-desc::after { content: ' ↓'; opacity: 1; }

        table.cust-tbl tbody td {
            padding: 0.8rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
            color: var(--slate-800);
        }
        table.cust-tbl tbody tr:hover td { background: #F8FAFF; }

        .cust-name { font-weight: 600; color: var(--navy); margin-bottom: 0; }
        .cust-phone {
            font-family: 'DM Mono', monospace;
            font-size: 0.82rem; color: var(--slate-500);
        }

        /* ── Business badges ── */
        .biz-badge {
            display: inline-flex; align-items: center;
            font-size: 0.72rem; font-weight: 700;
            padding: 0.3rem 0.65rem; border-radius: 6px;
            text-transform: uppercase; letter-spacing: 0.04em;
            border: 1.5px solid;
        }
        .biz-badge.wholesale   { background: var(--sky-pale);     border-color: #7DD3FC; color: #0C4A6E; }
        .biz-badge.manufacturer { background: var(--emerald-pale); border-color: #86EFAC; color: #15803D; }
        .biz-badge.distributor  { background: var(--amber-pale);   border-color: #FCD34D; color: #92400E; }
        .biz-badge.retailer     { background: var(--violet-pale);  border-color: #E9D5FF; color: #6B21A8; }

        .credit-amt {
            font-family: 'DM Mono', monospace;
            font-weight: 500; color: var(--navy); font-size: 0.82rem;
        }
        .sales-amt {
            font-family: 'DM Mono', monospace;
            font-weight: 500; color: var(--slate-700); font-size: 0.82rem;
        }

        /* ── Account badge ── */
        .account-badge {
            display: inline-flex; align-items: center;
            font-size: 0.72rem; font-weight: 600;
            padding: 0.25rem 0.6rem; border-radius: 5px;
            background: var(--slate-100);
            color: var(--slate-600);
            border: 1px solid var(--slate-200);
        }

        /* ── Action buttons ── */
        .action-btns { display: flex; gap: 0.35rem; justify-content: flex-end; }

        .act-btn {
            display: inline-flex; align-items: center; gap: 0.25rem;
            font-size: 0.78rem; font-weight: 600;
            padding: 0.35rem 0.7rem;
            border-radius: 6px; border: 1.5px solid;
            cursor: pointer; transition: all 0.15s;
            background: transparent;
        }
        .act-btn-view   { border-color: var(--sky);  color: var(--sky); }
        .act-btn-delete { border-color: var(--rose); color: var(--rose); }
        .act-btn-view:hover   { background: var(--sky-pale);  transform: scale(1.05); }
        .act-btn-delete:hover { background: var(--rose-pale); transform: scale(1.05); }

        /* ── Empty state ── */
        .empty-state {
            text-align: center; padding: 4rem 1.5rem;
            color: var(--slate-400);
        }
        .empty-state i { font-size: 4rem; display: block; margin-bottom: 0.75rem; opacity: 0.3; }
        .empty-state-title { font-size: 1.1rem; font-weight: 600; color: var(--slate-600); margin-bottom: 0.4rem; }
        .empty-state p { font-size: 0.875rem; margin-bottom: 1.25rem; }

        /* ── Modal ── */
        .modal-content { border: none; border-radius: 12px; overflow: hidden; }
        .modal-header-navy {
            background: var(--navy);
            color: var(--white);
            padding: 1.15rem 1.4rem;
            border-bottom: none;
            display: flex; justify-content: space-between; align-items: center;
        }
        .modal-header-navy .modal-title {
            font-size: 1.1rem; font-weight: 700; margin: 0;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .modal-header-navy .btn-close { filter: invert(1) brightness(0.8); }

        .modal-body { padding: 1.75rem 1.4rem; }
        .modal-footer { padding: 1.15rem 1.4rem; border-top: 1.5px solid var(--slate-200); }

        .mfield { display: flex; flex-direction: column; gap: 0.25rem; margin-bottom: 0.85rem; }
        .mfield-label { font-size: 0.8rem; font-weight: 600; color: var(--slate-600); }
        .mfield-input {
            font-family: 'Outfit', sans-serif;
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .mfield-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }
        select.mfield-input {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            padding-right: 2.25rem;
            appearance: none;
        }
        textarea.mfield-input { resize: vertical; min-height: 90px; }

        .row-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; }
        @media (max-width: 640px) { .row-fields { grid-template-columns: 1fr; } }

        .input-grp {
            display: flex;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            overflow: hidden;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .input-grp:focus-within {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }
        .input-grp-text {
            background: var(--slate-100);
            padding: 0.5rem 0.75rem;
            font-size: 0.82rem; font-weight: 600;
            color: var(--slate-600);
            border-right: 1.5px solid var(--slate-200);
        }
        .input-grp input {
            flex: 1; border: none; outline: none;
            padding: 0.5rem 0.75rem;
            font-family: 'Outfit', sans-serif;
            font-size: 0.875rem;
        }

        .modal-submit {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: 0.45rem;
            font-size: 0.9rem; font-weight: 700;
            padding: 0.7rem;
            background: var(--amber); color: var(--navy);
            border: none; border-radius: 8px; cursor: pointer;
            box-shadow: 0 3px 12px rgba(245,158,11,0.3);
            transition: all 0.18s;
        }
        .modal-submit:hover {
            box-shadow: 0 5px 18px rgba(245,158,11,0.4);
            transform: translateY(-1px);
        }

        /* ── Animation ── */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .panel { animation: slideUp 0.4s ease forwards; }

        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
            .pg-header { padding: 0.85rem 1.1rem; margin-bottom: 1rem; }
            .pg-title { font-size: 1.15rem; }
        }
    </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
        <div class="main-wrap">

            {{-- ── Header ── --}}
            <div class="pg-header">
                <div class="pg-title-wrap">
                    <a href="#" onclick="history.back()" class="back-btn">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <div class="pg-title">
                        Customer <span>Management</span>
                    </div>
                </div>
                <button class="hbtn-primary" data-bs-toggle="modal" data-bs-target="#Customer">
                    <i class="bi bi-plus-lg"></i> New Customer
                </button>
                <a href="{{ url('customer-kpi') }}" class="hbtn-primary" style="margin-left: 10px;">
                    <i class="bi bi-graph-up"></i> Customer KPI
                </a>
            </div>

            {{-- ── Alerts ── --}}
            @if(session('success'))
            <div class="alert-box alert-success">
                <span><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert-box alert-danger">
                <span><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- ── Filter Panel ── --}}
            <div class="filter-panel">
                <div class="filter-title">Search & Filter</div>
                <div class="filter-grid">
                    <div class="field">
                        <label class="field-label">Search Customers</label>
                        <input type="search" class="field-input search-input" id="searchInput"
                            placeholder="Name, contact, business type…">
                    </div>
                    
                    <div class="field">
                        <label class="field-label">Sort By</label>
                        <select id="sortBy" class="field-input">
                            <option value="name">Name (A-Z)</option>
                            <option value="sales">Total Sales</option>
                            <option value="date">Member Since</option>
                            <option value="credit">Credit Limit</option>
                        </select>
                    </div>
                    
                    <div class="field">
                        <label class="field-label">Business Type</label>
                        <select id="filterBusiness" class="field-input">
                            <option value="">All Types</option>
                            <option value="Wholesale">Wholesale</option>
                            <option value="Manufacturer">Manufacturer</option>
                            <option value="Distributor">Distributor</option>
                            <option value="Retailer">Retailer</option>
                        </select>
                    </div>
                    
                    @if(isset($accounts) && $accounts->count() > 0)
                    <div class="field">
                        <label class="field-label">Account</label>
                        <select id="filterAccount" class="field-input">
                            <option value="">All Accounts</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->name }}" {{ $selectedAccount == $account->name ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    <button class="reset-btn" onclick="resetFilters()">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </button>
                </div>
            </div>

            {{-- ── Table Panel ── --}}
            <div class="panel">
                <div class="table-wrap">
                    <table class="cust-tbl" id="customersTable">
                        <thead>
                            <tr>
                                <th style="width:4%;">#</th>
                                <th style="width:18%;" class="sortable" data-sort="name">Name</th>
                                <th style="width:13%;">Contact</th>
                                <th style="width:12%;" class="sortable" data-sort="business">Business Type</th>
                                <th style="width:10%;" class="sortable" data-sort="credit">Credit Limit</th>
                                <th style="width:10%;" class="sortable" data-sort="sales">Total Sales</th>
                                <th style="width:11%;" class="sortable" data-sort="date">Member Since</th>
                                <th style="width:12%;" class="sortable" data-sort="account">Account</th>
                                <th style="width:10%;text-align:right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="customersBody">
                            @if($fetch->isEmpty())
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <i class="bi bi-people-fill"></i>
                                        <div class="empty-state-title">No Customers Found</div>
                                        <p>Add your first customer to get started</p>
                                        <button class="hbtn-primary" data-bs-toggle="modal" data-bs-target="#Customer">
                                            <i class="bi bi-plus-lg"></i> Add New Customer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @else
                                @foreach ($fetch as $index => $customer)
                                <tr class="customer-row"
                                    data-name="{{ strtolower($customer->name) }}"
                                    data-business="{{ strtolower($customer->business) }}"
                                    data-sales="{{ floatval($customer->totalSales) }}"
                                    data-credit="{{ floatval($customer->limits) }}"
                                    data-date="{{ strtotime($customer->created_at) }}"
                                    data-account="{{ strtolower($customer->account ?? '') }}">
                                    
                                    <td>{{ $index + 1 }}</td>
                                    <td><div class="cust-name">{{ $customer->name }}</div></td>
                                    <td><div class="cust-phone">{{ $customer->phone }}</div></td>
                                    <td>
                                        <span class="biz-badge {{ strtolower($customer->businessType) }}">
                                            {{ $customer->business }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="credit-amt">Tsh {{ number_format($customer->limits, 0) }}</span>
                                    </td>
                                    <td>
                                        <span class="sales-amt">{{ number_format($customer->totalSales, 0) }}</span>
                                    </td>
                                    <td>
                                        <span title="{{ $customer->created_at }}">
                                            {{ \Carbon\Carbon::parse($customer->created_at)->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="account-badge">{{ $customer->account ?? '—' }}</span>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="{{ url('admin/customerView') }}?name={{ urlencode($customer->name) }}" class="act-btn act-btn-view"><i class="bi bi-eye"></i> View</a>
                                            <form action="dltCustomer" method="post" style="display:contents;">
                                                @csrf
                                                <button class="act-btn act-btn-delete" name="name" value="{{ $customer->name }}" 
                                                    type="submit" onclick="return confirm('Remove this customer?')">
                                                    <i class="bi bi-trash"></i> Remove
                                                </button>
                                            </form>
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
    </main>
  </div>
</div>

{{-- ════════════════════════════════════════
     NEW CUSTOMER MODAL
════════════════════════════════════════ --}}
<div class="modal fade" id="Customer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header-navy">
                <h4 class="modal-title">
                    <i class="bi bi-person-plus"></i> Create New Customer
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="newCustomer" method="post">
                    @csrf
                    <div class="row-fields">
                        <div class="mfield">
                            <label class="mfield-label">Full Name</label>
                            <input type="text" class="mfield-input" name="name" placeholder="Customer name" required>
                        </div>
                        <div class="mfield">
                            <label class="mfield-label">Contact</label>
                            <input type="text" class="mfield-input" name="contact" placeholder="Contact person name" required>
                        </div>
                    </div>
                    
                    <div class="row-fields">
                        <div class="mfield" style="grid-column: span 2;">
                            <label class="mfield-label">Address</label>
                            <input type="text" class="mfield-input" name="address" placeholder="Customer address" required>
                        </div>
                    </div>

                    <div class="row-fields">
                        <div class="mfield">
                            <label class="mfield-label">Business Type</label>
                            <select name="type" class="mfield-input" required>
                                <option value="" selected disabled>Select Type</option>
                                <option value="Wholesale">Wholesale</option>
                                <option value="Manufacturer">Manufacturer</option>
                                <option value="Distributor">Distributor</option>
                                <option value="Retailer">Retailer</option>
                            </select>
                        </div>
                        <div class="mfield">
                            <label class="mfield-label">Credit Limit</label>
                            <div class="input-grp">
                                <span class="input-grp-text">Tsh</span>
                                <input type="number" name="credit" placeholder="Maximum credit amount" required>
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($accounts) && $accounts->count() > 0)
                    <div class="mfield">
                        <label class="mfield-label">Assign to Shop/Account</label>
                        <select name="account" class="mfield-input">
                            <option selected disabled>Select Shop</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ $selectedAccount == $account->name ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    <div class="mfield">
                        <label class="mfield-label">Assign to Employee</label>
                        <select name="allocation" class="mfield-input">
                            <option selected disabled>Select Employee</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mfield">
                        <label class="mfield-label">Description</label>
                        <textarea name="description" class="mfield-input" placeholder="Additional notes about this customer"></textarea>
                    </div>

                    <button type="submit" class="modal-submit">
                        <i class="bi bi-save"></i> Save Customer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // ════════════════════════════════════════════
    // Sorting
    // ════════════════════════════════════════════
    let currentSort = { field: 'name', direction: 'asc' };
    
    document.querySelectorAll('.cust-tbl th.sortable').forEach(header => {
        header.addEventListener('click', function() {
            const field = this.dataset.sort;
            
            if (currentSort.field === field) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.field = field;
                currentSort.direction = 'asc';
            }
            
            document.querySelectorAll('.cust-tbl th.sortable').forEach(h => {
                h.classList.remove('sorted-asc', 'sorted-desc');
            });
            this.classList.add(currentSort.direction === 'asc' ? 'sorted-asc' : 'sorted-desc');
            
            sortTable();
        });
    });
    
    function sortTable() {
        const rows = Array.from(document.querySelectorAll('#customersBody .customer-row'));
        
        rows.sort((a, b) => {
            let aVal = a.dataset[currentSort.field];
            let bVal = b.dataset[currentSort.field];
            
            if (currentSort.field === 'sales' || currentSort.field === 'credit' || currentSort.field === 'date') {
                aVal = parseFloat(aVal) || 0;
                bVal = parseFloat(bVal) || 0;
            } else {
                aVal = aVal.toLowerCase();
                bVal = bVal.toLowerCase();
            }
            
            if (aVal < bVal) return currentSort.direction === 'asc' ? -1 : 1;
            if (aVal > bVal) return currentSort.direction === 'asc' ? 1 : -1;
            return 0;
        });
        
        const tbody = document.getElementById('customersBody');
        tbody.innerHTML = '';
        rows.forEach((row, index) => {
            row.querySelector('td:first-child').textContent = index + 1;
            tbody.appendChild(row);
        });
    }
    
    // ════════════════════════════════════════════
    // Filter
    // ════════════════════════════════════════════
    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('sortBy').addEventListener('change', handleSortChange);
    document.getElementById('filterBusiness').addEventListener('change', filterTable);
    
    // Account filter - only for admin, reloads page with account parameter
    const accountFilter = document.getElementById('filterAccount');
    if (accountFilter) {
        accountFilter.addEventListener('change', function() {
            const selectedAccount = this.value;
            const url = new URL(window.location.href);
            if (selectedAccount) {
                url.searchParams.set('account', selectedAccount);
            } else {
                url.searchParams.delete('account');
            }
            window.location.href = url.toString();
        });
    }
    
    function filterTable() {
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const businessFilter = document.getElementById('filterBusiness').value.toLowerCase();
        const accountFilter = document.getElementById('filterAccount');
        const accountValue = accountFilter ? accountFilter.value.toLowerCase() : '';
        const rows = document.querySelectorAll('#customersBody .customer-row');

        rows.forEach(row => {
            const name = row.dataset.name;
            const business = row.dataset.business;
            const account = row.dataset.account || '';
            const text = row.textContent.toLowerCase();

            const matchesSearch = text.includes(searchValue);
            const matchesBusiness = !businessFilter || business.includes(businessFilter);
            const matchesAccount = !accountValue || account.includes(accountValue);

            row.style.display = (matchesSearch && matchesBusiness && matchesAccount) ? '' : 'none';
        });
    }
    
    function handleSortChange() {
        const sortValue = document.getElementById('sortBy').value;
        const fieldMap = {
            'name': 'name',
            'sales': 'sales',
            'date': 'date',
            'credit': 'credit'
        };
        
        currentSort.field = fieldMap[sortValue];
        currentSort.direction = 'asc';
        
        document.querySelectorAll('.cust-tbl th.sortable').forEach(h => {
            h.classList.remove('sorted-asc', 'sorted-desc');
        });
        
        sortTable();
    }
    
    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('sortBy').value = 'name';
        document.getElementById('filterBusiness').value = '';
        
        const accountFilter = document.getElementById('filterAccount');
        if (accountFilter) {
            accountFilter.value = '';
        }
        
        currentSort = { field: 'name', direction: 'asc' };
        
        document.querySelectorAll('.cust-tbl th.sortable').forEach(h => {
            h.classList.remove('sorted-asc', 'sorted-desc');
        });
        
        document.querySelectorAll('#customersBody .customer-row').forEach(row => {
            row.style.display = '';
        });
        
        sortTable();
    }
</script>

</body>
</html>