<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} — Employee Details</title>
    @include("links")
    <link href="{{asset('css/dashboard.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
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
            --violet:       #7C3AED;
            --violet-pale:  #EDE9FE;
            --sky:          #0284C7;
            --sky-pale:     #E0F2FE;
            --slate-50:     #F8FAFC;
            --slate-100:    #F1F5F9;
            --slate-200:    #E2E8F0;
            --slate-300:    #CBD5E1;
            --slate-400:    #94A3B8;
            --slate-500:    #64748B;
            --slate-600:    #475569;
            --slate-700:    #334155;
            --slate-800:    #1E293B;
            --white:        #FFFFFF;
            --font: 'Sora', system-ui, sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --r: 8px; --r-lg: 13px; --r-xl: 16px;
        }

        body { font-family: var(--font); background: #ECF0F8; color: var(--slate-800); min-height: 100vh; font-size: 14px; line-height: 1.6; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }

        .wrap { padding: 1.5rem 1.75rem 3rem; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
        .au  { animation: fadeUp 0.38s ease both; }
        .au1 { animation-delay:.04s; } .au2 { animation-delay:.10s; }
        .au3 { animation-delay:.16s; } .au4 { animation-delay:.22s; }
        .au5 { animation-delay:.28s; }

        /* ══ PAGE HEADER ══ */
        .pg-header {
            background: var(--navy); border-radius: var(--r-xl);
            padding: 1.2rem 1.6rem; margin-bottom: 1.4rem;
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; flex-wrap: wrap; position: relative; overflow: hidden;
        }
        .pg-header::before { content:''; position:absolute; top:-50px; right:-30px; width:180px; height:180px; border-radius:50%; background:var(--navy-light); opacity:.45; pointer-events:none; }
        .pg-header::after  { content:''; position:absolute; bottom:-55px; right:100px; width:120px; height:120px; border-radius:50%; background:var(--amber); opacity:.07; pointer-events:none; }
        .pg-left { display:flex; align-items:center; gap:10px; position:relative; z-index:1; }
        .back-btn { width:34px; height:34px; border-radius:var(--r); background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.15); display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,.7); cursor:pointer; flex-shrink:0; transition:all .15s; }
        .back-btn:hover { background:rgba(255,255,255,.16); color:var(--white); }
        .header-icon { width:40px; height:40px; border-radius:var(--r); background:var(--amber); display:flex; align-items:center; justify-content:center; font-size:18px; color:var(--navy); flex-shrink:0; }
        .pg-title-text h1 { font-size:16px; font-weight:700; color:var(--white); letter-spacing:-.2px; }
        .pg-title-text p  { font-size:12px; color:rgba(255,255,255,.45); margin-top:1px; }

        /* ══ LAYOUT GRID ══ */
        .page-grid { display:grid; grid-template-columns: 280px 1fr; gap:1.25rem; align-items:start; }
        @media(max-width:900px) { .page-grid { grid-template-columns:1fr; } }

        /* ══ PROFILE CARD ══ */
        .profile-card {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-xl); overflow: hidden;
            box-shadow: 0 1px 4px rgba(11,30,61,.05);
            position: sticky; top: 1.5rem;
        }
        .profile-banner {
            height: 80px; background: var(--navy);
            position: relative;
        }
        .profile-banner::after {
            content:''; position:absolute; inset:0;
            background: linear-gradient(135deg, var(--navy-light) 0%, transparent 70%);
        }
        .profile-avatar-wrap {
            display: flex; flex-direction: column; align-items: center;
            padding: 0 1.25rem 1.25rem;
            margin-top: -44px; position: relative; z-index: 1;
        }
        .profile-avatar {
            width: 88px; height: 88px; border-radius: 50%;
            border: 4px solid var(--white);
            background: var(--navy-mid);
            object-fit: cover; display: flex; align-items: center; justify-content: center;
            font-size: 28px; font-weight: 700; color: rgba(255,255,255,.85);
            box-shadow: 0 4px 16px rgba(11,30,61,.2); flex-shrink: 0;
            overflow: hidden;
        }
        .profile-name { font-size: 15px; font-weight: 700; color: var(--navy); margin-top: .875rem; text-align: center; }
        .profile-role {
            display: inline-flex; align-items: center; gap: 5px;
            margin-top: 5px; padding: 4px 12px; border-radius: 20px;
            background: var(--amber-pale); color: #92400e;
            font-size: 11.5px; font-weight: 700;
        }

        .profile-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 1px; background: var(--slate-200); margin-top: 1rem; }
        .ps-cell { background: var(--white); padding: .75rem 1rem; text-align: center; }
        .ps-val   { font-size: 17px; font-weight: 700; color: var(--navy); font-family: var(--mono); }
        .ps-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--slate-400); margin-top: 2px; }

        .profile-meta { padding: 1rem 1.25rem; }
        .meta-row { display: flex; align-items: center; gap: 9px; font-size: 12.5px; color: var(--slate-600); padding: 5px 0; border-bottom: 1px solid var(--slate-100); }
        .meta-row:last-child { border-bottom: none; }
        .meta-row i { width: 16px; color: var(--slate-400); font-size: 13px; flex-shrink: 0; }

        .update-photo-btn {
            width: calc(100% - 2.5rem); margin: 0 1.25rem 1.25rem;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            padding: 8px; border: 1.5px dashed var(--slate-300);
            border-radius: var(--r); background: transparent;
            font-family: var(--font); font-size: 12.5px; font-weight: 600;
            color: var(--slate-500); cursor: pointer; transition: all .15s;
        }
        .update-photo-btn:hover { border-color: var(--navy-light); color: var(--navy); background: rgba(26,58,107,.04); }

        /* ══ FORM SECTIONS ══ */
        .form-section {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-xl); overflow: hidden;
            box-shadow: 0 1px 4px rgba(11,30,61,.05);
            margin-bottom: 1.25rem;
        }

        .section-head {
            display: flex; align-items: center; gap: 9px;
            padding: .875rem 1.4rem; border-bottom: 1.5px solid var(--slate-200);
            background: var(--slate-50);
        }
        .section-head-icon { width: 28px; height: 28px; border-radius: var(--r); display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0; }
        .shi-amber   { background: var(--amber-pale);   color: #92400e; }
        .shi-violet  { background: var(--violet-pale);  color: var(--violet); }
        .shi-sky     { background: var(--sky-pale);     color: var(--sky); }
        .shi-rose    { background: var(--rose-pale);    color: var(--rose); }
        .shi-emerald { background: var(--emerald-pale); color: var(--emerald); }
        .section-title { font-size: 13.5px; font-weight: 700; color: var(--navy); }

        .section-body { padding: 1.4rem; }

        /* Fields */
        .field-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
        .field-grid-2:last-child { margin-bottom: 0; }
        @media(max-width:640px) { .field-grid-2 { grid-template-columns: 1fr; } }

        .field { display: flex; flex-direction: column; gap: 5px; }
        .field-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--slate-500); }
        .field-label .req { color: var(--rose); }
        .field-input, .field-select {
            font-family: var(--font); font-size: 13.5px; padding: 9px 12px;
            border: 1.5px solid var(--slate-200); border-radius: var(--r);
            background: var(--white); color: var(--slate-800); outline: none;
            transition: border-color .18s, box-shadow .18s;
        }
        .field-input:focus, .field-select:focus { border-color: var(--navy-light); box-shadow: 0 0 0 3px rgba(26,58,107,.1); }
        .field-input::placeholder { color: var(--slate-400); }
        .field-select { appearance: none; cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 2.25rem; }

        /* ══ PERMISSIONS PANEL ══ */
        .perms-block { background: var(--slate-50); border: 1.5px solid var(--slate-200); border-radius: var(--r-lg); padding: 1rem 1.1rem; margin-bottom: 1rem; }
        .perms-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--slate-400); margin-bottom: .5rem; }
        .perms-hint  { font-size: 11.5px; color: var(--slate-400); margin-bottom: .65rem; display: flex; align-items: center; gap: 5px; }

        .badge-list {
            min-height: 64px; max-height: 160px; overflow-y: auto;
            display: flex; flex-wrap: wrap; gap: 6px; align-content: flex-start;
            overscroll-behavior: contain; scroll-behavior: smooth;
            padding: 4px 0;
        }

        .perm-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 11px; border-radius: 20px;
            background: var(--navy); color: rgba(255,255,255,.85);
            font-size: 11.5px; font-weight: 600; cursor: pointer;
            border: 2px solid transparent; transition: all .15s; user-select: none;
        }
        .perm-badge:hover { opacity: .85; }
        .perm-badge.selected { border-color: var(--amber); box-shadow: 0 0 0 2px rgba(245,158,11,.25); }
        .perm-badge.shop-badge { background: var(--sky); }
        .perm-badge.primary-badge { background: var(--emerald); }

        .empty-badge { font-size: 12.5px; color: var(--slate-400); font-style: italic; padding: 4px 0; }

        .perms-add-row { display: flex; gap: 8px; margin-top: .75rem; flex-wrap: wrap; }
        .perms-add-row .field-select { flex: 1; min-width: 160px; }

        .btn-sm-add { display: inline-flex; align-items: center; gap: 5px; padding: 8px 14px; border-radius: var(--r); background: var(--navy); color: var(--white); font-family: var(--font); font-size: 12.5px; font-weight: 700; border: none; cursor: pointer; transition: all .15s; white-space: nowrap; }
        .btn-sm-add:hover { background: var(--navy-light); }
        .btn-sm-add.amber { background: var(--amber); color: var(--navy); }
        .btn-sm-add.amber:hover { background: #D97706; }

        .btn-sm-del { display: inline-flex; align-items: center; gap: 5px; padding: 8px 14px; border-radius: var(--r); background: var(--rose-pale); color: var(--rose); font-family: var(--font); font-size: 12.5px; font-weight: 700; border: 1.5px solid rgba(225,29,72,.2); cursor: pointer; transition: all .15s; margin-top: .65rem; }
        .btn-sm-del:hover { background: var(--rose); color: var(--white); }

        /* ══ ACTION FOOTER ══ */
        .action-footer { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; padding-top: 1.25rem; border-top: 1.5px solid var(--slate-200); margin-top: 1.25rem; }
        .action-footer-right { display: flex; gap: 8px; flex-wrap: wrap; }

        .btn-save { display: inline-flex; align-items: center; gap: 6px; padding: 10px 22px; border-radius: var(--r); background: var(--amber); color: var(--navy); font-family: var(--font); font-size: 13px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 3px 14px rgba(245,158,11,.3); transition: all .18s; }
        .btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245,158,11,.4); }

        .btn-warn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: var(--r); background: var(--amber-pale); color: #92400e; font-family: var(--font); font-size: 13px; font-weight: 700; border: 1.5px solid rgba(245,158,11,.3); cursor: pointer; transition: all .15s; }
        .btn-warn:hover { background: var(--amber); color: var(--navy); }

        .btn-danger { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: var(--r); background: var(--rose-pale); color: var(--rose); font-family: var(--font); font-size: 13px; font-weight: 700; border: 1.5px solid rgba(225,29,72,.2); cursor: pointer; transition: all .15s; }
        .btn-danger:hover { background: var(--rose); color: var(--white); }

        @media(max-width:768px) { .wrap { padding: 1rem; } }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @include("admin/sidenav")

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="wrap">

                {{-- Page header --}}
                <div class="pg-header au au1">
                    <div class="pg-left">
                        <button class="back-btn" onclick="history.back()"><i class="bi bi-chevron-left"></i></button>
                        <div class="header-icon"><i class="bi bi-person-fill"></i></div>
                        <div class="pg-title-text">
                            <h1>Employee Details</h1>
                            <p>Manage profile, permissions and shop assignments</p>
                        </div>
                    </div>
                </div>

                <div class="page-grid">

                    {{-- ═══ LEFT — Profile card ═══ --}}
                    <aside class="au au2">
                        <div class="profile-card">
                            <div class="profile-banner"></div>
                            <div class="profile-avatar-wrap">
                                @if($users->userImg)
                                    <img class="profile-avatar" src="{{ asset('images/' . $users->userImg) }}" alt="{{ $users->name }}">
                                @else
                                    @php
                                        $initials = strtoupper(substr($users->name ?? 'E', 0, 1));
                                        $parts    = explode(' ', trim($users->name ?? ''));
                                        if(count($parts)>1) $initials .= strtoupper(substr($parts[1],0,1));
                                    @endphp
                                    <div class="profile-avatar">{{ $initials }}</div>
                                @endif
                                <div class="profile-name">{{ $users->name ?? 'N/A' }}</div>
                                <span class="profile-role">
                                    <i class="bi bi-shield-fill"></i>
                                    {{ $users->levelStatus ?? 'N/A' }}
                                </span>
                            </div>

                            @php
                                $currentPermissions = $users->permissions ?? [];
                                if (is_string($currentPermissions)) {
                                    $decoded = json_decode($currentPermissions, true);
                                    $currentPermissions = is_array($decoded) ? $decoded : [];
                                } elseif (!is_array($currentPermissions)) {
                                    $currentPermissions = [];
                                }
                            @endphp
                            <div class="profile-stats">
                                <div class="ps-cell">
                                    <div class="ps-val">{{ count($currentPermissions) }}</div>
                                    <div class="ps-label">Permissions</div>
                                </div>
                                <div class="ps-cell">
                                    <div class="ps-val">{{ $userAccounts->count() }}</div>
                                    <div class="ps-label">Shops</div>
                                </div>
                            </div>

                            <div class="profile-meta">
                                <div class="meta-row"><i class="bi bi-envelope"></i> {{ $users->email ?? '—' }}</div>
                                <div class="meta-row"><i class="bi bi-phone"></i> {{ $users->contact ?? '—' }}</div>
                                <div class="meta-row"><i class="bi bi-calendar3"></i>
                                    @if($users->age) Age {{ $users->age }} @else No age set @endif
                                </div>
                                <div class="meta-row">
                                    <i class="bi bi-circle-fill" style="font-size:8px; color:{{ $users->status === 'active' ? 'var(--emerald)' : 'var(--rose)' }};"></i>
                                    {{ ucfirst($users->status ?? 'unknown') }}
                                </div>
                            </div>

                            <button class="update-photo-btn" onclick="document.getElementById('photo').click()">
                                <i class="bi bi-camera"></i> Update photo
                            </button>
                        </div>
                    </aside>

                    {{-- ═══ RIGHT — Forms ═══ --}}
                    <div>

                        {{-- ── Personal Info ── --}}
                        <div class="form-section au au3">
                            <div class="section-head">
                                <div class="section-head-icon shi-amber"><i class="bi bi-person"></i></div>
                                <span class="section-title">Personal Information</span>
                            </div>
                            <div class="section-body">
                                <form action="{{ url('admin/updateEmployee') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="employeeId" value="{{ $users->id }}">

                                    <div class="field-grid-2">
                                        <div class="field">
                                            <label class="field-label">Full name <span class="req">*</span></label>
                                            <input type="text" class="field-input" name="name" value="{{ $users->name ?? '' }}" required>
                                        </div>
                                        <div class="field">
                                            <label class="field-label">Age <span class="req">*</span></label>
                                            <input type="number" class="field-input" name="age" value="{{ $users->age ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="field-grid-2">
                                        <div class="field">
                                            <label class="field-label">Contact <span class="req">*</span></label>
                                            <input type="text" class="field-input" name="contact" value="{{ $users->contact ?? '' }}" required>
                                        </div>
                                        <div class="field">
                                            <label class="field-label">Email <span class="req">*</span></label>
                                            <input type="email" class="field-input" name="email" value="{{ $users->email ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="field-grid-2">
                                        <div class="field">
                                            <label class="field-label">Profile photo</label>
                                            <input type="file" class="field-input" name="photo" id="photo" accept="image/*">
                                        </div>
                                        <div class="field">
                                            <label class="field-label">Role / Level <span class="req">*</span></label>
                                            <select class="field-select" name="levelStatus" required>
                                                @foreach(['Admin','Manager','Seller'] as $role)
                                                <option value="{{ $role }}" {{ ($users->levelStatus ?? '') == $role ? 'selected' : '' }}>{{ $role }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- ── Permissions ── --}}
                                    <div class="section-head" style="margin: 1.25rem -1.4rem; border-top:1.5px solid var(--slate-200); border-bottom:1.5px solid var(--slate-200); padding:.875rem 1.4rem; background:var(--slate-50);">
                                        <div class="section-head-icon shi-violet"><i class="bi bi-shield-check"></i></div>
                                        <span class="section-title">Permissions</span>
                                    </div>

                                    <div class="perms-block">
                                        <div class="perms-label">Current permissions</div>
                                        <div class="perms-hint"><i class="bi bi-info-circle"></i> Click a badge to select it, then remove</div>
                                        <div class="badge-list" id="permissionsList">
                                            @forelse($currentPermissions as $perm)
                                            <div class="perm-badge" data-value="{{ $perm }}" onclick="toggleSelectItem(this)">
                                                {{ str_replace('_', ' ', ucwords($perm, '_')) }}
                                            </div>
                                            @empty
                                            <span class="empty-badge" id="permEmptyMsg">No permissions assigned</span>
                                            @endforelse
                                        </div>
                                        <select name="permissions[]" id="permissions" style="display:none;" multiple>
                                            @foreach($currentPermissions as $perm)
                                            <option value="{{ $perm }}" selected>{{ $perm }}</option>
                                            @endforeach
                                        </select>

                                        <div class="perms-add-row">
                                            <select id="newPermission" class="field-select">
                                                <option value="" selected disabled>Select permission…</option>
                                                @php
                                                    $allPermissions = [
                                                        'view_employees'=>'View Employees','manage_employees'=>'Manage Employees',
                                                        'view_suppliers'=>'View Suppliers','manage_suppliers'=>'Manage Suppliers',
                                                        'view_customers'=>'View Customers','add_customers'=>'Add Customers',
                                                        'edit_customers'=>'Edit Customers','manage_customers'=>'Manage Customers',
                                                        'view_items'=>'View Items','manage_items'=>'Manage Items',
                                                        'create_items'=>'Create Items','edit_products'=>'Edit Products',
                                                        'view_receivings'=>'View Receivings','set_restock_date'=>'Alter Receivings Date',
                                                        'manage_receivings'=>'Manage Receivings',
                                                        'create_sales'=>'Create Sales','manage_sales_date'=>'Alter Sales Date','manage_sales'=>'Manage Sales',
                                                        'view_invoices'=>'View Invoices','manage_invoices'=>'Manage Invoices',
                                                        'view_shop_debts'=>'View Shop Debts','view_all_shops'=>'View All Shops',
                                                        'pay_debts'=>'Pay Debts',
                                                        'view_reports'=>'View Reports','manage_reports'=>'Manage Reports',
                                                        'view_full_report'=>'View Full Report','manage_full_report'=>'Manage Full Report',
                                                        'manage_shop_cash_submit'=>'Shop Cash Submit',
                                                        'view_sales_report'=>'View Sales Report','manage_sales_report'=>'Manage Sales Report',
                                                        'view_stock_report'=>'View Stock Report','manage_stock_report'=>'Manage Stock Report',
                                                        'view_expenses'=>'View Expenses','manage_expenses'=>'Manage Expenses',
                                                        'view_logs'=>'View Logs','manage_logs'=>'Manage Logs',
                                                        'view_settings'=>'View Settings','manage_settings'=>'Manage Settings',
                                                        'view_shops_report'=>'View Shops Report',
                                                        'view_banking'=>'View Banking',
                                                        'add_banking_supplier'=>'Add Banking Supplier','edit_banking_supplier'=>'Edit Banking Supplier','delete_banking_supplier'=>'Delete Banking Supplier',
                                                        'add_banking_beneficiary'=>'Add Banking Beneficiary','edit_banking_beneficiary'=>'Edit Banking Beneficiary','delete_banking_beneficiary'=>'Delete Banking Beneficiary',
                                                        'add_banking_transfer'=>'Add Banking Transfer','delete_banking_transfer'=>'Delete Banking Transfer',
                                                        'add_banking_chip'=>'Add Banking Chip','edit_banking_chip'=>'Edit Banking Chip','delete_banking_chip'=>'Delete Banking Chip',
                                                    ];
                                                @endphp
                                                @foreach($allPermissions as $val => $lbl)
                                                    @if(!in_array($val, $currentPermissions))
                                                    <option value="{{ $val }}">{{ $lbl }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <button type="button" id="addPermission" class="btn-sm-add">
                                                <i class="bi bi-plus-circle"></i> Add
                                            </button>
                                            <button type="button" id="selectAllPermissions" class="btn-sm-add amber">
                                                <i class="bi bi-check-all"></i> All
                                            </button>
                                        </div>
                                        <button type="button" id="removeSelected" class="btn-sm-del">
                                            <i class="bi bi-trash"></i> Remove selected
                                        </button>
                                    </div>

                                    {{-- ── Shop assignments ── --}}
                                    <div class="section-head" style="margin: 1.25rem -1.4rem; border-top:1.5px solid var(--slate-200); border-bottom:1.5px solid var(--slate-200); padding:.875rem 1.4rem; background:var(--slate-50);">
                                        <div class="section-head-icon shi-sky"><i class="bi bi-shop"></i></div>
                                        <span class="section-title">Shop Assignments</span>
                                    </div>

                                    <div class="perms-block">
                                        <div class="perms-label">Assigned shops</div>
                                        <div class="perms-hint"><i class="bi bi-info-circle"></i> Click to select, then remove</div>
                                        <div class="badge-list" id="accountsList">
                                            @forelse($userAccounts as $ua)
                                            <div class="perm-badge shop-badge {{ $ua->is_primary ? 'primary-badge' : '' }}" data-value="{{ $ua->account }}" onclick="toggleSelectItem(this)">
                                                <i class="bi bi-shop" style="font-size:11px;"></i>
                                                {{ $ua->accountRel->name ?? $ua->account }}
                                                @if($ua->is_primary) <span style="opacity:.7; font-size:10px;">Primary</span> @endif
                                            </div>
                                            @empty
                                            @php
                                                $primaryAccountName = $users->accountRel ? $users->accountRel->name : $users->account;
                                            @endphp
                                            <span class="empty-badge" id="acctEmptyMsg">No shops assigned (using: {{ $primaryAccountName }})</span>
                                            @endforelse
                                        </div>
                                        <select name="accounts[]" id="accounts" style="display:none;" multiple>
                                            @foreach($userAccounts as $ua)
                                            <option value="{{ $ua->account }}" selected>{{ $ua->account }}</option>
                                            @endforeach
                                        </select>

                                        <div class="perms-add-row">
                                            <select id="newAccount" class="field-select">
                                                <option value="" selected disabled>Select shop…</option>
                                                @foreach($accounts as $account)
                                                    @if(!$userAccounts->contains('account', $account->id))
                                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <button type="button" id="addAccount" class="btn-sm-add">
                                                <i class="bi bi-plus-circle"></i> Add
                                            </button>
                                        </div>
                                        <button type="button" id="removeSelectedAccount" class="btn-sm-del">
                                            <i class="bi bi-trash"></i> Remove selected
                                        </button>
                                    </div>

                                    {{-- Action footer --}}
                                    <div class="action-footer">
                                        <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Save changes</button>
                                        <div class="action-footer-right">
                                            @if($users->status != 'deleted')
                                            <button type="submit" formaction="{{ url('admin/banUser') }}" class="btn-warn">
                                                <i class="bi bi-shield-x"></i>
                                                {{ $users->status == 'banned' ? 'Unban' : 'Ban' }}
                                            </button>
                                            <button type="submit" formaction="{{ url('admin/deleteUser') }}" class="btn-danger"
                                                    onclick="return confirm('Delete this employee? This cannot be undone.')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- ── Change Password ── --}}
                        <div class="form-section au au4">
                            <div class="section-head">
                                <div class="section-head-icon shi-rose"><i class="bi bi-key"></i></div>
                                <span class="section-title">Change Password</span>
                            </div>
                            <div class="section-body">
                                <form action="{{ url('admin/changePassword') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="employeeId" value="{{ $users->id }}">
                                    <div class="perms-hint" style="margin-bottom:1rem;"><i class="bi bi-info-circle"></i> Leave blank to keep the current password</div>
                                    <div class="field-grid-2">
                                        <div class="field">
                                            <label class="field-label">New password</label>
                                            <input type="password" class="field-input" name="new_password" placeholder="Enter new password">
                                        </div>
                                        <div class="field">
                                            <label class="field-label">Confirm password</label>
                                            <input type="password" class="field-input" name="confirm_password" placeholder="Confirm new password">
                                        </div>
                                    </div>
                                    <div class="action-footer">
                                        <button type="submit" class="btn-save"><i class="bi bi-key-fill"></i> Update password</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>{{-- end right --}}
                </div>{{-- end page-grid --}}

            </div>
        </main>
    </div>
</div>

<script>
    /* ── Toggle selection ── */
    function toggleSelectItem(el) { el.classList.toggle('selected'); }

    document.addEventListener('DOMContentLoaded', function () {

        /* ══ PERMISSIONS ══ */
        const permSel  = document.getElementById('permissions');
        const permList = document.getElementById('permissionsList');
        const newPermS = document.getElementById('newPermission');

        function existingPerms() { return Array.from(permSel.options).map(o => o.value); }

        function addPermBadge(value, label) {
            document.getElementById('permEmptyMsg')?.remove();
            const b = document.createElement('div');
            b.className = 'perm-badge';
            b.dataset.value = value;
            b.textContent   = label;
            b.onclick = function() { toggleSelectItem(this); };
            permList.appendChild(b);
            permList.scrollTop = permList.scrollHeight;
        }

        function syncPermOption(value, label) {
            const o = document.createElement('option');
            o.value = value; o.text = label; o.selected = true;
            permSel.appendChild(o);
        }

        document.getElementById('addPermission').onclick = () => {
            const v = newPermS.value;
            if (!v) return;
            const l = newPermS.options[newPermS.selectedIndex].text;
            if (!existingPerms().includes(v)) { addPermBadge(v, l); syncPermOption(v, l); }
            newPermS.value = '';
        };

        document.getElementById('selectAllPermissions').onclick = () => {
            const exist = existingPerms();
            for (let i = 1; i < newPermS.options.length; i++) {
                const o = newPermS.options[i];
                if (!exist.includes(o.value)) { addPermBadge(o.value, o.text); syncPermOption(o.value, o.text); }
            }
        };

        document.getElementById('removeSelected').onclick = () => {
            permList.querySelectorAll('.perm-badge.selected').forEach(b => {
                const opt = Array.from(permSel.options).find(o => o.value === b.dataset.value);
                if (opt) opt.remove();
                b.remove();
            });
            if (!permList.querySelector('.perm-badge')) {
                const s = document.createElement('span');
                s.id = 'permEmptyMsg'; s.className = 'empty-badge';
                s.textContent = 'No permissions assigned';
                permList.appendChild(s);
            }
        };

        /* ══ ACCOUNTS ══ */
        const acctSel  = document.getElementById('accounts');
        const acctList = document.getElementById('accountsList');
        const newAcctS = document.getElementById('newAccount');
        const primary  = "{{ $users->account }}";

        function existingAccts() { return Array.from(acctSel.options).map(o => o.value); }

        function addAcctBadge(value, label) {
            document.getElementById('acctEmptyMsg')?.remove();
            const b = document.createElement('div');
            b.className = 'perm-badge shop-badge';
            b.dataset.value = value;
            b.innerHTML = '<i class="bi bi-shop" style="font-size:11px;"></i> ' + label;
            b.onclick = function() { toggleSelectItem(this); };
            acctList.appendChild(b);
            acctList.scrollTop = acctList.scrollHeight;
        }

        function syncAcctOption(value) {
            const o = document.createElement('option');
            o.value = value; o.text = value; o.selected = true;
            acctSel.appendChild(o);
        }

        document.getElementById('addAccount').onclick = () => {
            const v = newAcctS.value;
            if (!v) return;
            const l = newAcctS.options[newAcctS.selectedIndex].text;
            if (!existingAccts().includes(v)) {
                addAcctBadge(v, l);
                syncAcctOption(v);
                newAcctS.remove(newAcctS.selectedIndex);
                newAcctS.value = '';
            }
        };

        document.getElementById('removeSelectedAccount').onclick = () => {
            acctList.querySelectorAll('.perm-badge.selected').forEach(b => {
                const opt = Array.from(acctSel.options).find(o => o.value === b.dataset.value);
                if (opt) opt.remove();
                const newOpt = document.createElement('option');
                newOpt.value = newOpt.text = b.dataset.value;
                newAcctS.appendChild(newOpt);
                b.remove();
            });
            if (!acctList.querySelector('.perm-badge')) {
                const s = document.createElement('span');
                s.id = 'acctEmptyMsg'; s.className = 'empty-badge';
                const primaryName = "{{ $users->accountRel ? $users->accountRel->name : $users->account }}";
                s.textContent = 'No shops assigned (using: ' + primaryName + ')';
                acctList.appendChild(s);
            }
        };

        /* ══ Scroll isolation ══ */
        [permList, acctList].forEach(el => {
            el.addEventListener('wheel', function(e) {
                const atTop = this.scrollTop === 0;
                const atBot = this.scrollTop + this.clientHeight >= this.scrollHeight;
                if (this.scrollHeight > this.clientHeight) {
                    if ((atTop && e.deltaY < 0) || (atBot && e.deltaY > 0)) return;
                    e.stopPropagation();
                }
            }, { passive: true });
        });
    });
</script>
</body>
</html>