<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Settings</title>
    @include("links")
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        .main-wrap { max-width: 1600px; margin: 0 auto; padding: 1.25rem 1.5rem; }

        .pg-header {
            background: var(--navy);
            border-radius: 12px;
            padding: 1.2rem 1.4rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.28);
            position: relative;
            overflow: hidden;
        }

        .pg-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(245,158,11,0.08);
            border-radius: 50%;
            pointer-events: none;
        }

        .pg-title {
            color: var(--white); font-size: 1.4rem; font-weight: 700;
            display: flex; align-items: center; gap: 0.6rem;
            position: relative;
            z-index: 1;
            margin: 0;
        }
        .pg-title i { color: var(--amber); font-size: 1.35rem; }

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

        .section-panel {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            overflow: hidden;
            margin-bottom: 1.5rem;
            transition: box-shadow 0.2s;
        }
        .section-panel:hover { box-shadow: 0 4px 16px rgba(11,30,61,0.08); }

        .section-head {
            background: var(--slate-50);
            border-bottom: 1.5px solid var(--slate-200);
            padding: 1.1rem 1.25rem;
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;
        }

        .section-title {
            font-size: 1.05rem; font-weight: 700; color: var(--navy);
            display: flex; align-items: center; gap: 0.5rem;
            margin: 0;
        }
        .section-title i { color: var(--amber); font-size: 1.15rem; }

        .section-body { padding: 1.5rem 1.25rem; }

        .field { display: flex; flex-direction: column; gap: 0.25rem; margin-bottom: 1rem; }
        .field-label {
            font-size: 0.78rem; font-weight: 600; color: var(--slate-600);
            text-transform: uppercase; letter-spacing: 0.04em;
        }
        .field-input {
            font-family: 'Outfit', sans-serif;
            font-size: 0.875rem;
            padding: 0.55rem 0.8rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--slate-50);
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
            font-weight: 500;
        }
        .field-input::placeholder { color: var(--slate-400); opacity: 0.7; }
        .field-input:focus {
            border-color: var(--navy-light);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }
        select.field-input {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.8rem center;
            padding-right: 2.25rem;
            appearance: none;
        }

        .search-wrap { position: relative; margin-bottom: 1rem; }
        .search-input {
            padding-left: 2.4rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cpath d='m21 21-4.35-4.35'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 0.75rem center;
            background-size: 16px;
        }

        /* Table styles (list view) */
        .table-wrap { overflow-x: auto; }
        table.settings-tbl { width: 100%; border-collapse: collapse; font-size: 0.845rem; }
        table.settings-tbl thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            padding: 0.7rem 0.8rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
        }
        table.settings-tbl tbody td {
            padding: 0.75rem 0.8rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
            color: var(--slate-800);
        }
        table.settings-tbl tbody tr:hover td { background: #F8FAFF; }

        /* Card view styles */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.25rem;
            margin-top: 0.5rem;
        }
        .shop-card {
            background: var(--white);
            border: 1px solid var(--slate-200);
            border-radius: 14px;
            padding: 1.2rem;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .shop-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px -12px rgba(0,0,0,0.15);
            border-color: var(--amber);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }
        .shop-name-card {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--navy);
            background: var(--amber-pale);
            padding: 0.2rem 0.7rem;
            border-radius: 40px;
            display: inline-block;
        }
        .shop-badge-active {
            background: var(--emerald-pale);
            color: #065F46;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.25rem 0.6rem;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .shop-details {
            margin: 1rem 0;
        }
        .shop-detail-item {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            padding: 0.4rem 0;
            border-bottom: 1px dashed var(--slate-200);
        }
        .detail-label {
            color: var(--slate-500);
            font-weight: 500;
        }
        .detail-value {
            font-weight: 600;
            color: var(--slate-800);
        }
        .card-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.6rem;
            margin-top: 1rem;
            padding-top: 0.5rem;
            border-top: 1px solid var(--slate-100);
        }
        /* View toggle buttons */
        .view-toggle-group {
            display: flex;
            gap: 0.5rem;
            background: var(--slate-100);
            padding: 0.3rem;
            border-radius: 40px;
        }
        .view-btn {
            background: transparent;
            border: none;
            padding: 0.35rem 1rem;
            border-radius: 32px;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: var(--slate-600);
            transition: all 0.15s;
        }
        .view-btn.active {
            background: var(--white);
            color: var(--navy);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .view-btn i { font-size: 0.9rem; }
        .sort-select {
            font-size: 0.75rem;
            padding: 0.35rem 1.8rem 0.35rem 0.8rem;
            border-radius: 40px;
            border: 1px solid var(--slate-200);
            background-color: var(--white);
            font-weight: 500;
        }
        .controls-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.82rem; font-weight: 600;
            padding: 0.48rem 0.95rem;
            background: var(--amber); color: var(--navy);
            border: none; border-radius: 8px; cursor: pointer;
            box-shadow: 0 3px 12px rgba(245,158,11,0.3);
            transition: all 0.18s;
        }
        .btn-primary:hover {
            box-shadow: 0 5px 18px rgba(245,158,11,0.4);
            transform: translateY(-1px);
            color: var(--navy);
        }
        .btn-save {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.45rem;
            font-size: 0.82rem; font-weight: 700;
            padding: 0.6rem 1.5rem;
            background: var(--amber); color: var(--navy);
            border: none; border-radius: 8px; cursor: pointer;
            box-shadow: 0 3px 12px rgba(245,158,11,0.3);
            transition: all 0.18s;
            text-transform: uppercase; letter-spacing: 0.04em;
        }
        .btn-outline-primary {
            background: transparent;
            color: var(--sky);
            border: 1.5px solid var(--sky);
        }
        .btn-outline-primary:hover {
            background: var(--sky);
            color: var(--white);
        }
        .btn-outline-warning {
            background: transparent;
            color: var(--amber);
            border: 1.5px solid var(--amber);
        }
        .btn-outline-warning:hover {
            background: var(--amber);
            color: var(--navy);
        }
        .btn-outline-danger {
            background: transparent;
            color: var(--rose);
            border: 1.5px solid var(--rose);
        }
        .btn-outline-danger:hover {
            background: var(--rose);
            color: var(--white);
        }
        .badge-active {
            display: inline-flex; align-items: center; gap: 0.3rem;
            font-size: 0.72rem; font-weight: 700;
            padding: 0.4rem 0.75rem; border-radius: 20px;
            background: var(--emerald-pale); color: #065F46;
            text-transform: uppercase; letter-spacing: 0.04em;
        }
        .badge-warning {
            background: var(--amber-pale); color: #92400E;
        }
        .profile-wrap {
            display: flex; align-items: center; gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding: 1.25rem;
            background: var(--amber-pale);
            border: 1.5px solid #FBBF24;
            border-radius: 12px;
        }
        .profile-pic {
            width: 120px; height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--white);
            box-shadow: 0 4px 16px rgba(11,30,61,0.15);
            flex-shrink: 0;
        }
        .profile-placeholder {
            width: 120px; height: 120px;
            border-radius: 50%;
            background: var(--navy);
            display: flex; align-items: center; justify-content: center;
            color: var(--amber);
            font-size: 3rem;
            border: 3px solid var(--white);
            flex-shrink: 0;
        }
        .payment-item {
            background: var(--slate-50);
            border: 1.5px solid var(--slate-200);
            border-radius: 10px;
            padding: 1.15rem;
            margin-bottom: 1rem;
        }
        .row-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; }
        .add-more-btn {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: 0.45rem;
            font-size: 0.82rem; font-weight: 600;
            padding: 0.75rem;
            background: var(--white);
            border: 1.5px dashed var(--slate-300);
            border-radius: 8px;
            cursor: pointer;
        }
        .modal-content { border: none; border-radius: 12px; overflow: hidden; }
        .modal-header-navy {
            background: var(--navy);
            color: var(--white);
            padding: 1.15rem 1.4rem;
        }
        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
            .cards-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    @include("sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
        <div class="main-wrap">

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

            <div class="pg-header">
                <h4 class="pg-title">
                    <i class="bi bi-gear-fill"></i> Settings
                </h4>
            </div>
                <div class="section-panel">
                <div class="section-head">
                    <h5 class="section-title">
                        <i class="bi bi-arrow-left-right"></i> System Mode
                    </h5>
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        @php $currentMode = strtolower(trim($getData->system_mode ?? 'live')); @endphp
                        <span id="systemModeLabel" class="badge-active @if($currentMode === 'backup') badge-warning @endif">{{ ucfirst($currentMode) }}</span>
                        <button class="btn-primary" id="systemModeToggle">{{ $currentMode === 'backup' ? 'Switch to Live' : 'Switch to Backup' }}</button>
                    </div>
                </div>
                </div>
                </div>
            {{-- ══════════════════════════════════════
                 SHOPS SECTION with TABS & SORT + CARD/LIST VIEW
            ══════════════════════════════════════ --}}
            <div class="section-panel">
                <div class="section-head">
                    <h5 class="section-title">
                        <i class="bi bi-shop"></i> Shops
                    </h5>
                    <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#newAccount">
                        <i class="bi bi-plus-lg"></i> New Shop
                    </button>
                </div>

                <div class="section-body">
                    <div class="controls-row">
                        <div class="search-wrap" style="margin-bottom:0; flex:1; max-width:300px;">
                            <input type="text" class="field-input search-input" id="shopSearch" 
                                placeholder="Search shops…" onkeyup="filterAndRender()">
                        </div>
                        <div style="display: flex; gap: 0.75rem; align-items: center;">
                            <select id="sortSelect" class="sort-select" onchange="filterAndRender()">
                                <option value="name_asc">Sort: Name (A-Z)</option>
                                <option value="name_desc">Sort: Name (Z-A)</option>
                                <option value="customers_asc">Customers (low to high)</option>
                                <option value="customers_desc">Customers (high to low)</option>
                            </select>
                            <div class="view-toggle-group">
                                <button class="view-btn active" id="listViewBtn" onclick="setView('list')">
                                    <i class="bi bi-table"></i> List
                                </button>
                                <button class="view-btn" id="cardViewBtn" onclick="setView('card')">
                                    <i class="bi bi-grid-3x3-gap-fill"></i> Cards
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic container for shops (list view default) -->
                    <div id="shopsDynamicContainer">
                        <!-- will be filled by JS -->
                    </div>
                </div>
            </div>

            
            @if (Auth::user()->levelStatus === 'Admin')
            <div class="section-panel">
                <div class="section-head"><h5 class="section-title"><i class="bi bi-building"></i> Business Details</h5></div>
                <div class="section-body">
                    <div class="profile-wrap">
                        @if($getData->business_profile_picture ?? false)
                            <img src="{{ asset('storage/' . $getData->business_profile_picture) }}" class="profile-pic" id="businessProfilePreview">
                        @else
                            <div class="profile-placeholder"><i class="bi bi-building"></i></div>
                        @endif
                        <div class="profile-upload"><label class="field-label">Business Logo</label><input type="file" class="field-input" id="businessProfileInput" accept="image/*"></div>
                    </div>
                    <form action="businessDetails" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="field"><label class="field-label">Business Name</label><input type="text" class="field-input" name="bName" value="{{$getData->bName ?? ''}}"></div>
                        <div class="field"><label class="field-label">Business Address</label><input type="text" class="field-input" name="address" value="{{$getData->address ?? ''}}"></div>
                        <div style="margin-top:1.5rem;"><label class="field-label">Payment Services</label><div id="paymentServicesList">@php $ps = $getData->payment_services ?? []; @endphp @if(count($ps)) @foreach($ps as $idx=>$srv)<div class="payment-item"><div class="row-fields"><select name="payment_services[{{$idx}}][provider]" class="field-input"><option value="Vodacom" {{$srv['provider']=='Vodacom'?'selected':''}}>Vodacom</option><option value="Tigo" {{$srv['provider']=='Tigo'?'selected':''}}>Tigo</option></select><input type="tel" name="payment_services[{{$idx}}][number]" value="{{$srv['number']??''}}" class="field-input" placeholder="Number"></div></div>@endforeach @else <div class="payment-item"><div class="row-fields"><select name="payment_services[0][provider]" class="field-input"><option>Vodacom</option><option>Tigo</option></select><input type="tel" name="payment_services[0][number]" class="field-input" placeholder="Number"></div></div>@endif</div><button type="button" class="add-more-btn" onclick="addPaymentService()">+ Add Payment</button></div>
                        <div style="text-align:right; margin-top:1.5rem;"><button type="submit" class="btn-save"><i class="bi bi-save"></i> Save Changes</button></div>
                    </form>
                </div>
            </div>

            <div class="section-panel">
                <div class="section-head">
                    <h5 class="section-title">
                        <i class="bi bi-wrench"></i> Data Migration
                    </h5>
                </div>
                <div class="section-body">
                    <p style="font-size:0.85rem; color: var(--slate-500); margin-bottom: 1rem;">
                        One-time fix: scans the <strong>orders</strong> and <strong>sales</strong> tables for rows where <code>cPhone</code> contains a phone number (more than 3 digits). It looks up the matching customer by <code>cName</code> in the <strong>customers</strong> table and replaces the phone number with the customer's <strong>ID</strong>.
                    </p>
                    <div style="background: var(--rose-pale); border:1px solid var(--rose); border-radius:8px; padding:0.75rem 1rem; font-size:0.8rem; color: #9F1239; margin-bottom:1rem;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Run this only once.</strong> Re-running may overwrite already-converted data.
                    </div>
                    <form action="{{ route('settings.fixCustomerRefs') }}" method="post" onsubmit="return confirm('Are you sure you want to run this one-time fix?');">
                        @csrf
                        <button type="submit" class="btn-save">
                            <i class="bi bi-wrench"></i> Fix Customer References
                        </button>
                    </form>
                </div>
            </div>
            @endif
            <div class="section-panel">
                <div class="section-head">
                    <div class="section-title">
                        <i class="bi bi-geo-alt"></i>System Location
                    </div>
                    
                </div>
                <div class="section-body">
                        <p>This system is available at the following location:</p>
                        <div class="container">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d541.241697842394!2d38.99257260505241!3d-6.76678583385818!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1sen!2stz!4v1783143796020!5m2!1sen!2stz" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                        </div>
                    </div>
            </div>
        </div>
    </main>
  </div>
</div>

{{-- Modals same as original --}}
<div class="modal fade" id="newAccount" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header-navy"><h4 class="modal-title">Create New Shop</h4><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form action="newAccount" method="post">@csrf<div class="row-fields"><div class="field"><label class="field-label">Shop Name</label><input type="text" class="field-input" name="name" required></div><div class="field"><label class="field-label">Location</label><input type="text" class="field-input" name="location" required></div></div><div style="text-align:right; margin-top:1rem;"><button type="button" class="btn btn-sm btn-outline-danger me-2" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-primary">Create Shop</button></div></form></div></div></div></div>
<div class="modal fade" id="editAccount" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header-navy"><h4 class="modal-title">Edit Shop</h4><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form action="updateAccount" method="post">@csrf<input type="hidden" name="accountId" id="editAccountId"><div class="row-fields"><div class="field"><label class="field-label">Shop Name</label><input type="text" class="field-input" name="name" id="editAccountName" required></div><div class="field"><label class="field-label">Location</label><input type="text" class="field-input" name="location" id="editAccountLocation" required></div></div><div style="text-align:right; margin-top:1rem;"><button type="button" class="btn btn-sm btn-outline-danger me-2" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-primary">Update Shop</button></div></form></div></div></div></div>

<script>
// ──────────────────────────────────────────────
// SHOPS DATA (from blade $fetch)
// ──────────────────────────────────────────────
const shopsData = @json($fetch); // original shops collection

let currentView = 'list'; // list by default
let currentSort = 'name_asc';

function sortShops(shops, sortType) {
    const sorted = [...shops];
    switch(sortType) {
        case 'name_asc': sorted.sort((a,b) => a.name.localeCompare(b.name)); break;
        case 'name_desc': sorted.sort((a,b) => b.name.localeCompare(a.name)); break;
        case 'customers_asc': sorted.sort((a,b) => (a.customers||0) - (b.customers||0)); break;
        case 'customers_desc': sorted.sort((a,b) => (b.customers||0) - (a.customers||0)); break;
        default: sorted.sort((a,b) => a.name.localeCompare(b.name));
    }
    return sorted;
}

function filterShopsData(shops, searchTerm) {
    if (!searchTerm.trim()) return shops;
    const term = searchTerm.toLowerCase();
    return shops.filter(shop => shop.name.toLowerCase().includes(term) || (shop.location && shop.location.toLowerCase().includes(term)));
}

function renderListView(shops) {
    if (!shops.length) return `<div class="text-center py-4 text-muted"><i class="bi bi-shop-slash"></i> No shops found</div>`;
    let html = `<div class="table-wrap"><table class="settings-tbl"><thead><tr><th>Id</th><th>Name</th><th>Location</th><th>Customers</th><th>Users</th><th>Products</th><th style="text-align:right;">Action</th></tr></thead><tbody>`;
    shops.forEach((shop, idx) => {
        const activeBadge = (shop.id == '{{ getCurrentShopId() }}') ? `<span class="badge-active"><i class="bi bi-check-circle-fill"></i> Active</span>` : '';
        const switchBtn = (shop.id != '{{ getCurrentShopId() }}') ? `<form action="switch" method="post" class="d-inline">@csrf<button class="btn btn-sm btn-outline-primary" name="account" value="${shop.id}">Switch</button></form>` : '';
        html += `<tr>
            <td>${idx+1}</td>
            <td><strong>${escapeHtml(shop.name)}</strong></td>
            <td>${escapeHtml(shop.location||'')}</td>
            <td>${shop.customers ?? 0}</td>
            <td>${shop.users ?? 0}</td>
            <td>${shop.products ?? 0}</td>
            <td><div class="d-flex justify-content-end gap-2">${activeBadge}${switchBtn}<button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editAccount" onclick="editAccount(${shop.id}, '${escapeHtml(shop.name).replace(/'/g, "\\'")}', '${escapeHtml(shop.location||'').replace(/'/g, "\\'")}')"><i class="bi bi-pencil"></i></button><form action="deleteAccount" method="post" class="d-inline">@csrf<button class="btn btn-sm btn-outline-danger" name="accountId" value="${shop.id}" onclick="return confirm('Delete shop?')"><i class="bi bi-trash"></i></button></form></div></td>
        </tr>`;
    });
    html += `</tbody></table></div>`;
    return html;
}

function renderCardView(shops) {
    if (!shops.length) return `<div class="text-center py-4 text-muted"><i class="bi bi-shop-slash"></i> No shops found</div>`;
    let html = `<div class="cards-grid">`;
    shops.forEach((shop, idx) => {
        const isActive = (shop.id == '{{ getCurrentShopId() }}');
        const activeChip = isActive ? `<span class="shop-badge-active"><i class="bi bi-check-circle-fill"></i> Active</span>` : '';
        const switchBtn = !isActive ? `<form action="switch" method="post" style="display:inline-block;">@csrf<button class="btn btn-sm btn-outline-primary" name="account" value="${escapeHtml(shop.name)}">Switch</button></form>` : '';
        html += `<div class="shop-card">
            <div class="card-header">
                <span class="shop-name-card">${escapeHtml(shop.name)}</span>
                ${activeChip}
            </div>
            <div class="shop-details">
                <div class="shop-detail-item"><span class="detail-label">📍 Location</span><span class="detail-value">${escapeHtml(shop.location||'—')}</span></div>
                <div class="shop-detail-item"><span class="detail-label">👥 Customers</span><span class="detail-value">${shop.customers ?? 0}</span></div>
                <div class="shop-detail-item"><span class="detail-label">👤 Users</span><span class="detail-value">${shop.users ?? 0}</span></div>
                <div class="shop-detail-item"><span class="detail-label">📦 Products</span><span class="detail-value">${shop.products ?? 0}</span></div>
            </div>
            <div class="card-actions">
                ${switchBtn}
                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editAccount" onclick="editAccount(${shop.id}, '${escapeHtml(shop.name).replace(/'/g, "\\'")}', '${escapeHtml(shop.location||'').replace(/'/g, "\\'")}')"><i class="bi bi-pencil"></i> Edit</button>
                <form action="deleteAccount" method="post" style="display:inline-block;">@csrf<button class="btn btn-sm btn-outline-danger" name="accountId" value="${shop.id}" onclick="return confirm('Delete shop?')"><i class="bi bi-trash"></i></button></form>
            </div>
        </div>`;
    });
    html += `</div>`;
    return html;
}

function escapeHtml(str) { if(!str) return ''; return str.replace(/[&<>]/g, function(m){if(m==='&') return '&amp;'; if(m==='<') return '&lt;'; if(m==='>') return '&gt;'; return m;}); }

function filterAndRender() {
    const searchVal = document.getElementById('shopSearch').value;
    let filtered = filterShopsData(shopsData, searchVal);
    const sortType = document.getElementById('sortSelect').value;
    currentSort = sortType;
    filtered = sortShops(filtered, sortType);
    const container = document.getElementById('shopsDynamicContainer');
    if (currentView === 'list') {
        container.innerHTML = renderListView(filtered);
    } else {
        container.innerHTML = renderCardView(filtered);
    }
}

function setView(view) {
    currentView = view;
    const listBtn = document.getElementById('listViewBtn');
    const cardBtn = document.getElementById('cardViewBtn');
    if (view === 'list') {
        listBtn.classList.add('active');
        cardBtn.classList.remove('active');
    } else {
        cardBtn.classList.add('active');
        listBtn.classList.remove('active');
    }
    filterAndRender(); 
}

// Helper for modals / edit
window.editAccount = function(id, name, location) {
    document.getElementById('editAccountId').value = id;
    document.getElementById('editAccountName').value = name;
    document.getElementById('editAccountLocation').value = location;
}

// Profile image preview & upload (mimic original)
function previewImage(input, previewId, hiddenId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let preview = document.getElementById(previewId);
            if (preview && preview.tagName !== 'IMG') {
                const newImg = document.createElement('img');
                newImg.id = previewId;
                newImg.className = 'profile-pic';
                newImg.src = e.target.result;
                preview.parentNode.replaceChild(newImg, preview);
            } else if (preview) preview.src = e.target.result;
            uploadProfilePicture(file, hiddenId);
        };
        reader.readAsDataURL(file);
    }
}
function uploadProfilePicture(file, hiddenInputId) {
    const formData = new FormData();
    formData.append('profile_picture', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
    fetch('/upload-profile-picture', { method: 'POST', body: formData }).catch(e=>console.error);
}
document.getElementById('personalProfileInput')?.addEventListener('change', function(e) { previewImage(e.target, 'personalProfilePreview', 'personalProfilePath'); });
document.getElementById('businessProfileInput')?.addEventListener('change', function(e) { previewImage(e.target, 'businessProfilePreview', 'businessProfilePath'); });
function addPaymentService() { alert("Payment service add (backend integration)"); }

// initialize shops on page load
document.addEventListener('DOMContentLoaded', () => {
    filterAndRender();
    const sortSelect = document.getElementById('sortSelect');
    if(sortSelect) sortSelect.value = 'name_asc';
});
(function() {
    const modeToggle = document.getElementById('systemModeToggle');
    if (!modeToggle) return;

    modeToggle.addEventListener('click', async function() {
        const currentLabel = document.getElementById('systemModeLabel').textContent.trim().toLowerCase();
        const nextMode = currentLabel === 'backup' ? 'live' : 'backup';

        try {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
            formData.append('mode', nextMode);

            const res = await fetch("{{ route('toggle.system.mode') }}", {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData
            });

            const data = await res.json();
            if (!res.ok) {
                alert(data.message || 'Failed to update system mode');
                return;
            }

            location.reload();
        } catch (e) {
            alert('Error updating system mode');
            console.error(e);
        }
    });
})();
</script>
@include('footer')
</body>
</html>