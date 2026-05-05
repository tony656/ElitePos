<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Employee Management</title>
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

        /* ── Page header ── */
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

        /* ── Table container ── */
        .table-container {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            overflow: hidden;
        }

        table.emp-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.845rem;
        }

        table.emp-tbl thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            padding: 0.85rem 1rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
        }

        table.emp-tbl tbody td {
            padding: 0.95rem 1rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
            color: var(--slate-800);
        }

        table.emp-tbl tbody tr:hover td {
            background: #F8FAFF;
        }

        /* ── Employee photo ── */
        .emp-photo {
            width: 42px; height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--slate-200);
        }

        .emp-photo-placeholder {
            width: 42px; height: 42px;
            border-radius: 50%;
            background: var(--slate-100);
            display: flex; align-items: center; justify-content: center;
            color: var(--slate-400);
            font-size: 1.25rem;
            border: 2px solid var(--slate-200);
        }

        /* ── Role badges ── */
        .role-badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            font-size: 0.72rem; font-weight: 700;
            padding: 0.35rem 0.65rem;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .role-badge.manager {
            background: var(--navy);
            color: var(--amber);
        }

        .role-badge.seller {
            background: var(--emerald-pale);
            color: #065F46;
        }

        /* ── Account badges ── */
        .acc-badges {
            display: flex; flex-wrap: wrap; gap: 0.35rem;
        }

        .acc-badge {
            display: inline-flex; align-items: center;
            font-size: 0.7rem; font-weight: 600;
            padding: 0.3rem 0.6rem;
            border-radius: 5px;
            background: var(--amber-pale);
            color: #92400E;
        }

        .acc-badge.primary {
            background: var(--navy);
            color: var(--amber);
        }

        /* ── View button ── */
        .btn-view {
            display: inline-flex; align-items: center; gap: 0.35rem;
            font-size: 0.75rem; font-weight: 600;
            padding: 0.4rem 0.75rem;
            background: var(--sky-pale);
            color: #075985;
            border: 1.5px solid #BAE6FD;
            border-radius: 7px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-view:hover {
            background: #0EA5E9;
            color: var(--white);
            border-color: #0EA5E9;
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

        /* ══════════════════════════════════════
           MODAL STYLES
        ══════════════════════════════════════ */
        .modal-content {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .modal-dialog {
            max-width: 760px;
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-header-navy .btn-close {
            filter: invert(1) brightness(0.8);
        }

        .modal-body {
            padding: 1.5rem 1.4rem;
            max-height: 70vh;
            overflow-y: auto;
        }

        /* ── Form elements ── */
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

        .form-label .text-muted {
            font-weight: 500;
            color: var(--slate-400);
            text-transform: none;
            letter-spacing: normal;
            font-size: 0.75rem;
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

        .form-select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.8rem center;
            padding-right: 2.25rem;
            appearance: none;
        }

        /* ── Accounts checkbox list ── */
        .acc-check-list {
            max-height: 200px;
            overflow-y: auto;
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            padding: 0.5rem;
            background: var(--slate-50);
            overscroll-behavior: contain;
        }

        .acc-check-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 0.75rem;
            border-radius: 7px;
            transition: background 0.15s;
            cursor: pointer;
        }

        .acc-check-item:hover {
            background: rgba(26,58,107,0.05);
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 1.5px solid var(--slate-300);
            border-radius: 5px;
            cursor: pointer;
            flex-shrink: 0;
            margin: 0;
        }

        .form-check-input:checked {
            background-color: var(--amber);
            border-color: var(--amber);
        }

        .form-check-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--slate-800);
            cursor: pointer;
            margin: 0;
            flex: 1;
        }

        /* ── Permission groups ── */
        .perm-groups {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .perm-group {
            border: 1.5px solid var(--slate-200);
            border-radius: 10px;
            overflow: hidden;
            background: var(--white);
            transition: all 0.2s;
        }

        .perm-group:hover {
            border-color: var(--amber);
        }

        .perm-group-head {
            background: var(--slate-50);
            padding: 0.9rem 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            user-select: none;
            border-bottom: 1.5px solid var(--slate-200);
        }

        .perm-group-head-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }

        .perm-group-icon {
            font-size: 1.3rem;
            min-width: 1.3rem;
        }

        .perm-group-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--navy);
            margin: 0;
        }

        .perm-group-count {
            font-size: 0.7rem;
            color: var(--slate-400);
            margin-left: 0.4rem;
        }

        .perm-group-toggle {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            background: var(--amber-pale);
            color: var(--amber-dark);
            font-size: 0.85rem;
            transition: transform 0.2s;
        }

        .perm-group.collapsed .perm-group-toggle {
            transform: rotate(-90deg);
        }

        .perm-group-body {
            padding: 1rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 0.85rem;
            max-height: 500px;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }

        .perm-group.collapsed .perm-group-body {
            max-height: 0;
            padding-top: 0;
            padding-bottom: 0;
        }

        .perm-item {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
        }

        .perm-item-label {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
            flex: 1;
        }

        .perm-item-label strong {
            font-weight: 600;
            color: var(--slate-800);
            font-size: 0.82rem;
        }

        .perm-item-desc {
            font-size: 0.72rem;
            color: var(--slate-500);
        }

        /* ── Submit button ── */
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

        /* ── Alert ── */
        .alert-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
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

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
            .pg-header { padding: 1rem; margin-bottom: 1rem; flex-direction: column; align-items: flex-start; }
            .pg-header-content { width: 100%; }
            .btn-add { width: 100%; justify-content: center; }
            .perm-group-body { grid-template-columns: 1fr; }
        }

        /* ── Animation ── */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .table-container { animation: slideUp 0.4s ease forwards; }
    </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
        <div class="main-wrap">

            {{-- ── Alerts ── --}}
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

            {{-- ── Page Header ── --}}
            <div class="pg-header">
                <div class="pg-header-content">
                    <div class="pg-icon-wrap">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="pg-title-wrap">
                        <h1>Users Management</h1>
                        <p class="pg-subtitle">Manage employee accounts and permissions</p>
                    </div>
                </div>
                <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                    {{-- Shop Filter --}}
                    <form id="shopFilterForm" method="GET" action="" style="display: inline-flex;">
                        <select class="form-select" name="shop" id="shopFilter" onchange="this.form.submit()" style="padding: 0.5rem 2rem 0.5rem 0.75rem; border-radius: 8px; min-width: 180px;">
                            <option value="">All Shops</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ $shopFilter == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    <button class="btn-add" data-bs-toggle="modal" data-bs-target="#newEmployee">
                        <i class="bi bi-plus-circle"></i> New Employee
                    </button>
                </div>
            </div>

            {{-- ── Employee Table ── --}}
            <div class="table-container">
                <div class="table-responsive">
                    <table class="emp-tbl">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="8%">Photo</th>
                                <th width="15%">Name</th>
                                <th width="12%">Contact</th>
                                <th width="8%">Status</th>
                                <th width="10%">Role</th>
                                <th width="8%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($users->isEmpty())
                            <tr>
                                <td colspan="10">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <div class="empty-title">No Employees Found</div>
                                        <p class="empty-desc">
                                            Start by adding your first employee to get started
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @else
                                @foreach ($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($user->userImg)
                                            <img src="{{ asset('images/' . $user->userImg) }}" 
                                                alt="Employee Photo" 
                                                class="emp-photo">
                                        @else
                                            <div class="emp-photo-placeholder">
                                                <i class="bi bi-person-circle"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td><strong>{{ $user->name ?? 'N/A' }}</strong></td>
                                    <td>{{ $user->contact ?? 'N/A' }}</td>
                                    <td>{{ $user->status }}</td>
                                    <td>
                                        @if($user->levelStatus == 'Manager')
                                            <span class="role-badge manager">
                                                <i class="bi bi-star-fill"></i> {{ $user->levelStatus }}
                                            </span>
                                        @else
                                            <span class="role-badge seller">
                                                <i class="bi bi-shop"></i> {{ $user->levelStatus }}
                                            </span>
                                        @endif
                                    </td>
                              
                                    <td>
                                        <form action="employeeView" method="post" style="display: inline;">
                                            @csrf
                                            <button class="btn-view" name="employeeId" value="{{$user->id}}">
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                        </form>
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

{{-- ══════════════════════════════════════
     NEW EMPLOYEE MODAL
══════════════════════════════════════ --}}
<div class="modal fade" id="newEmployee" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header-navy">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus-fill"></i> Register New Employee
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="registerEmployee" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="fname" 
                            placeholder="John Doe" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Contact Number <span class="text-muted">(optional)</span>
                        </label>
                        <input type="tel" class="form-control" name="contact" 
                            placeholder="+255 123 456 789">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" 
                            placeholder="john@example.com" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Age <span class="text-muted">(optional)</span>
                        </label>
                        <input type="number" class="form-control" name="age" 
                            placeholder="25">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" 
                            name="password1" placeholder="Create a strong password" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" 
                            name="password2" placeholder="Confirm password" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Photo <span class="text-muted">(optional)</span>
                        </label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Employee Role</label>
                        <select class="form-select" name="level" required>
                            <option value="" selected disabled>Select role</option>
                            <option value="Admin">Admin</option>
                            <option value="Manager">Manager</option>
                            <option value="Seller">Seller</option>
                        </select>
                    </div>

                    {{-- Assign Shops --}}
                    <div class="form-group">
                        <label class="form-label">Assign to Shops</label>
                        <p style="font-size:0.78rem; color:var(--slate-500); margin-bottom:0.5rem;">
                            Select which accounts this employee can access
                        </p>
                        <div class="acc-check-list">
                            @if(isset($accounts) && $accounts->count() > 0)
                                @foreach($accounts as $account)
                                    <label class="acc-check-item" for="acc_{{ $account->id }}">
                                        <input class="form-check-input" type="checkbox" 
                                            id="acc_{{ $account->id }}" 
                                            name="accounts[]" 
                                            value="{{ $account->account }}" 
                                            {{ $loop->first ? 'checked' : '' }}>
                                        <span class="form-check-label">
                                            <strong>{{ $account->account }}</strong>
                                        </span>
                                    </label>
                                @endforeach
                            @else
                                <label class="acc-check-item">
                                    <input class="form-check-input" type="checkbox" 
                                        name="accounts[]" 
                                        value="{{ getSessionAccountDisplayName() }}" 
                                        checked disabled>
                                    <span class="form-check-label">
                                        <strong>{{ getSessionAccountDisplayName() }}</strong>
                                        <span class="text-muted"> (Current account)</span>
                                    </span>
                                </label>
                            @endif
                        </div>
                    </div>

                    {{-- Permissions --}}
                    <div class="form-group">
                        <label class="form-label">Permissions</label>
                        <div class="perm-groups">

                            {{-- Employees Group --}}
                            <div class="perm-group">
                                <div class="perm-group-head" onclick="togglePermGroup(this)">
                                    <div class="perm-group-head-left">
                                        <span class="perm-group-icon">👥</span>
                                        <h6 class="perm-group-title">
                                            Employees <span class="perm-group-count">(2)</span>
                                        </h6>
                                    </div>
                                    <span class="perm-group-toggle">⋮</span>
                                </div>
                                <div class="perm-group-body">
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_employees" name="permissions[]" value="view_employees">
                                        <label class="form-check-label perm-item-label" for="perm_view_employees">
                                            <strong>View Employees</strong>
                                            <span class="perm-item-desc">View all employee records</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_manage_employees" name="permissions[]" value="manage_employees">
                                        <label class="form-check-label perm-item-label" for="perm_manage_employees">
                                            <strong>Manage Employees</strong>
                                            <span class="perm-item-desc">Edit/update employee data</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Suppliers Group --}}
                            <div class="perm-group">
                                <div class="perm-group-head" onclick="togglePermGroup(this)">
                                    <div class="perm-group-head-left">
                                        <span class="perm-group-icon">👔</span>
                                        <h6 class="perm-group-title">
                                            Suppliers <span class="perm-group-count">(4)</span>
                                        </h6>
                                    </div>
                                    <span class="perm-group-toggle">⋮</span>
                                </div>
                                <div class="perm-group-body">
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_suppliers" name="permissions[]" value="view_suppliers">
                                        <label class="form-check-label perm-item-label" for="perm_view_suppliers">
                                            <strong>View Suppliers</strong>
                                            <span class="perm-item-desc">View supplier list</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_add_suppliers" name="permissions[]" value="add_supplier">
                                        <label class="form-check-label perm-item-label" for="perm_add_suppliers">
                                            <strong>Add Suppliers</strong>
                                            <span class="perm-item-desc">Create new suppliers</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_edit_suppliers" name="permissions[]" value="edit_supplier">
                                        <label class="form-check-label perm-item-label" for="perm_edit_suppliers">
                                            <strong>Edit Suppliers</strong>
                                            <span class="perm-item-desc">Modify supplier info</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_delete_suppliers" name="permissions[]" value="delete_supplier">
                                        <label class="form-check-label perm-item-label" for="perm_delete_suppliers">
                                            <strong>Delete Suppliers</strong>
                                            <span class="perm-item-desc">Remove suppliers</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Banking Group --}}
                            <div class="perm-group">
                                <div class="perm-group-head" onclick="togglePermGroup(this)">
                                    <div class="perm-group-head-left">
                                        <span class="perm-group-icon">🏦</span>
                                        <h6 class="perm-group-title">
                                            Banking <span class="perm-group-count">(6)</span>
                                        </h6>
                                    </div>
                                    <span class="perm-group-toggle">⋮</span>
                                </div>
                                <div class="perm-group-body">
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_banking" name="permissions[]" value="view_banking">
                                        <label class="form-check-label perm-item-label" for="perm_view_banking">
                                            <strong>View Banking</strong>
                                            <span class="perm-item-desc">Access banking section</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_add_banking_supplier" name="permissions[]" value="add_banking_supplier">
                                        <label class="form-check-label perm-item-label" for="perm_add_banking_supplier">
                                            <strong>Add Banking Supplier</strong>
                                            <span class="perm-item-desc">Create banking suppliers</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_edit_banking_supplier" name="permissions[]" value="edit_banking_supplier">
                                        <label class="form-check-label perm-item-label" for="perm_edit_banking_supplier">
                                            <strong>Edit Banking Supplier</strong>
                                            <span class="perm-item-desc">Modify banking supplier info</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_delete_banking_supplier" name="permissions[]" value="delete_banking_supplier">
                                        <label class="form-check-label perm-item-label" for="perm_delete_banking_supplier">
                                            <strong>Delete Banking Supplier</strong>
                                            <span class="perm-item-desc">Remove banking suppliers</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_add_banking_beneficiary" name="permissions[]" value="add_banking_beneficiary">
                                        <label class="form-check-label perm-item-label" for="perm_add_banking_beneficiary">
                                            <strong>Add Beneficiary</strong>
                                            <span class="perm-item-desc">Create banking beneficiaries</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_edit_banking_beneficiary" name="permissions[]" value="edit_banking_beneficiary">
                                        <label class="form-check-label perm-item-label" for="perm_edit_banking_beneficiary">
                                            <strong>Edit Beneficiary</strong>
                                            <span class="perm-item-desc">Modify beneficiary info</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_delete_banking_beneficiary" name="permissions[]" value="delete_banking_beneficiary">
                                        <label class="form-check-label perm-item-label" for="perm_delete_banking_beneficiary">
                                            <strong>Delete Beneficiary</strong>
                                            <span class="perm-item-desc">Remove beneficiaries</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Customers Group --}}
                            <div class="perm-group">
                                <div class="perm-group-head" onclick="togglePermGroup(this)">
                                    <div class="perm-group-head-left">
                                        <span class="perm-group-icon">🤝</span>
                                        <h6 class="perm-group-title">
                                            Customers <span class="perm-group-count">(4)</span>
                                        </h6>
                                    </div>
                                    <span class="perm-group-toggle">⋮</span>
                                </div>
                                <div class="perm-group-body">
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_customers" name="permissions[]" value="view_customers">
                                        <label class="form-check-label perm-item-label" for="perm_view_customers">
                                            <strong>View Customers</strong>
                                            <span class="perm-item-desc">View customer database</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_add_customers" name="permissions[]" value="add_customers">
                                        <label class="form-check-label perm-item-label" for="perm_add_customers">
                                            <strong>Add Customers</strong>
                                            <span class="perm-item-desc">Register new customers</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_edit_customers" name="permissions[]" value="edit_customer">
                                        <label class="form-check-label perm-item-label" for="perm_edit_customers">
                                            <strong>Edit Customers</strong>
                                            <span class="perm-item-desc">Edit customer database</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_delete_customers" name="permissions[]" value="delete_customers">
                                        <label class="form-check-label perm-item-label" for="perm_delete_customers">
                                            <strong>Delete Customers</strong>
                                            <span class="perm-item-desc">Remove customer records</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Inventory Group --}}
                            <div class="perm-group">
                                <div class="perm-group-head" onclick="togglePermGroup(this)">
                                    <div class="perm-group-head-left">
                                        <span class="perm-group-icon">📦</span>
                                        <h6 class="perm-group-title">
                                            Inventory <span class="perm-group-count">(5)</span>
                                        </h6>
                                    </div>
                                    <span class="perm-group-toggle">⋮</span>
                                </div>
                                <div class="perm-group-body">
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_items" name="permissions[]" value="view_items">
                                        <label class="form-check-label perm-item-label" for="perm_view_items">
                                            <strong>View Items</strong>
                                            <span class="perm-item-desc">View inventory</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_export_items" name="permissions[]" value="export_products">
                                        <label class="form-check-label perm-item-label" for="perm_export_items">
                                            <strong>Export Items</strong>
                                            <span class="perm-item-desc">Export inventory data</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_edit_items" name="permissions[]" value="edit_products">
                                        <label class="form-check-label perm-item-label" for="perm_edit_items">
                                            <strong>Edit Items</strong>
                                            <span class="perm-item-desc">Modify item details</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_create_offer" name="permissions[]" value="create_offer">
                                        <label class="form-check-label perm-item-label" for="perm_create_offer">
                                            <strong>Create Offer</strong>
                                            <span class="perm-item-desc">Create product offers</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_delete_items" name="permissions[]" value="delete_products">
                                        <label class="form-check-label perm-item-label" for="perm_delete_items">
                                            <strong>Delete Items</strong>
                                            <span class="perm-item-desc">Remove items</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Receivings Group --}}
                            <div class="perm-group">
                                <div class="perm-group-head" onclick="togglePermGroup(this)">
                                    <div class="perm-group-head-left">
                                        <span class="perm-group-icon">📥</span>
                                        <h6 class="perm-group-title">
                                            Receivings <span class="perm-group-count">(3)</span>
                                        </h6>
                                    </div>
                                    <span class="perm-group-toggle">⋮</span>
                                </div>
                                <div class="perm-group-body">
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_receivings" name="permissions[]" value="view_receivings">
                                        <label class="form-check-label perm-item-label" for="perm_view_receivings">
                                            <strong>View Receivings</strong>
                                            <span class="perm-item-desc">View receiving records</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_set_restock_date" name="permissions[]" value="set_restock_date">
                                        <label class="form-check-label perm-item-label" for="perm_set_restock_date">
                                            <strong>Set Restock Date</strong>
                                            <span class="perm-item-desc">Manage receiving dates</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_manage_receivings" name="permissions[]" value="manage_receivings">
                                        <label class="form-check-label perm-item-label" for="perm_manage_receivings">
                                            <strong>Approve Receivings</strong>
                                            <span class="perm-item-desc">Process incoming goods</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Sales Group --}}
                            <div class="perm-group">
                                <div class="perm-group-head" onclick="togglePermGroup(this)">
                                    <div class="perm-group-head-left">
                                        <span class="perm-group-icon">🛒</span>
                                        <h6 class="perm-group-title">
                                            Sales <span class="perm-group-count">(3)</span>
                                        </h6>
                                    </div>
                                    <span class="perm-group-toggle">⋮</span>
                                </div>
                                <div class="perm-group-body">
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_sales" name="permissions[]" value="view_sales">
                                        <label class="form-check-label perm-item-label" for="perm_view_sales">
                                            <strong>View Sales</strong>
                                            <span class="perm-item-desc">View sales records</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_create_sales" name="permissions[]" value="create_sales">
                                        <label class="form-check-label perm-item-label" for="perm_create_sales">
                                            <strong>Manage Sales</strong>
                                            <span class="perm-item-desc">Create/edit sales</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_manage_sales_date" name="permissions[]" value="manage_sales_date">
                                        <label class="form-check-label perm-item-label" for="perm_manage_sales_date">
                                            <strong>Manage Sales Date</strong>
                                            <span class="perm-item-desc">Edit sales dates</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Invoices Group --}}
                            <div class="perm-group">
                                <div class="perm-group-head" onclick="togglePermGroup(this)">
                                    <div class="perm-group-head-left">
                                        <span class="perm-group-icon">📋</span>
                                        <h6 class="perm-group-title">
                                            Invoices <span class="perm-group-count">(2)</span>
                                        </h6>
                                    </div>
                                    <span class="perm-group-toggle">⋮</span>
                                </div>
                                <div class="perm-group-body">
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_invoices" name="permissions[]" value="view_invoices">
                                        <label class="form-check-label perm-item-label" for="perm_view_invoices">
                                            <strong>View Invoices</strong>
                                            <span class="perm-item-desc">View invoice list</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_manage_invoices" name="permissions[]" value="manage_invoices">
                                        <label class="form-check-label perm-item-label" for="perm_manage_invoices">
                                            <strong>Manage Invoices</strong>
                                            <span class="perm-item-desc">Create/modify invoices</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Debts Group --}}
                            <div class="perm-group">
                                <div class="perm-group-head" onclick="togglePermGroup(this)">
                                    <div class="perm-group-head-left">
                                        <span class="perm-group-icon">💵</span>
                                        <h6 class="perm-group-title">
                                            Debts <span class="perm-group-count">(3)</span>
                                        </h6>
                                    </div>
                                    <span class="perm-group-toggle">⋮</span>
                                </div>
                                <div class="perm-group-body">
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_shop_debts" name="permissions[]" value="view_shop_debts">
                                        <label class="form-check-label perm-item-label" for="perm_view_shop_debts">
                                            <strong>View Shop Debts</strong>
                                            <span class="perm-item-desc">View shop debt records</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_all_shops" name="permissions[]" value="view_all_shops">
                                        <label class="form-check-label perm-item-label" for="perm_view_all_shops">
                                            <strong>View All Shops</strong>
                                            <span class="perm-item-desc">View debts across shops</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_pay_debts" name="permissions[]" value="pay_debts">
                                        <label class="form-check-label perm-item-label" for="perm_pay_debts">
                                            <strong>Pay Debts</strong>
                                            <span class="perm-item-desc">Process debt payments</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Expenses Group --}}
                            <div class="perm-group">
                                <div class="perm-group-head" onclick="togglePermGroup(this)">
                                    <div class="perm-group-head-left">
                                        <span class="perm-group-icon">💰</span>
                                        <h6 class="perm-group-title">
                                            Expenses <span class="perm-group-count">(2)</span>
                                        </h6>
                                    </div>
                                    <span class="perm-group-toggle">⋮</span>
                                </div>
                                <div class="perm-group-body">
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_expenses" name="permissions[]" value="view_expenses">
                                        <label class="form-check-label perm-item-label" for="perm_view_expenses">
                                            <strong>View Expenses</strong>
                                            <span class="perm-item-desc">View expense records</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_manage_expenses" name="permissions[]" value="manage_expenses">
                                        <label class="form-check-label perm-item-label" for="perm_manage_expenses">
                                            <strong>Manage Expenses</strong>
                                            <span class="perm-item-desc">Add/edit expenses</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Reports Group --}}
                            <div class="perm-group">
                                <div class="perm-group-head" onclick="togglePermGroup(this)">
                                    <div class="perm-group-head-left">
                                        <span class="perm-group-icon">📊</span>
                                        <h6 class="perm-group-title">
                                            Reports <span class="perm-group-count">(6)</span>
                                        </h6>
                                    </div>
                                    <span class="perm-group-toggle">⋮</span>
                                </div>
                                <div class="perm-group-body">
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_reports" name="permissions[]" value="view_reports">
                                        <label class="form-check-label perm-item-label" for="perm_view_reports">
                                            <strong>View Reports</strong>
                                            <span class="perm-item-desc">Access general reports</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_shop_report" name="permissions[]" value="view_shops_report">
                                        <label class="form-check-label perm-item-label" for="perm_view_shop_report">
                                            <strong>View Shops Report</strong>
                                            <span class="perm-item-desc">Complete shops report</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_full_report" name="permissions[]" value="view_full_report">
                                        <label class="form-check-label perm-item-label" for="perm_view_full_report">
                                            <strong>View Full Report</strong>
                                            <span class="perm-item-desc">Complete business report</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_manage_full_report" name="permissions[]" value="manage_full_report">
                                        <label class="form-check-label perm-item-label" for="perm_manage_full_report">
                                            <strong>Manage Full Report</strong>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_manage_shop_cash" name="permissions[]" value="manage_shop_cash_submit">
                                        <label class="form-check-label perm-item-label" for="perm_manage_shop_cash">
                                            <strong>Manage Shop Cash</strong>
                                            <span class="perm-item-desc">Handle cash submissions</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_sales_report" name="permissions[]" value="view_sales_report">
                                        <label class="form-check-label perm-item-label" for="perm_view_sales_report">
                                            <strong>View Sales Report</strong>
                                            <span class="perm-item-desc">Sales analysis</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_stock_report" name="permissions[]" value="view_stock_report">
                                        <label class="form-check-label perm-item-label" for="perm_view_stock_report">
                                            <strong>View Stock Report</strong>
                                            <span class="perm-item-desc">Inventory analysis</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- System Group --}}
                            <div class="perm-group">
                                <div class="perm-group-head" onclick="togglePermGroup(this)">
                                    <div class="perm-group-head-left">
                                        <span class="perm-group-icon">🔧</span>
                                        <h6 class="perm-group-title">
                                            System <span class="perm-group-count">(2)</span>
                                        </h6>
                                    </div>
                                    <span class="perm-group-toggle">⋮</span>
                                </div>
                                <div class="perm-group-body">
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_logs" name="permissions[]" value="view_logs">
                                        <label class="form-check-label perm-item-label" for="perm_view_logs">
                                            <strong>View Logs</strong>
                                            <span class="perm-item-desc">Access system logs</span>
                                        </label>
                                    </div>
                                    <div class="perm-item">
                                        <input class="form-check-input" type="checkbox" id="perm_view_settings" name="permissions[]" value="view_settings">
                                        <label class="form-check-label perm-item-label" for="perm_view_settings">
                                            <strong>View Settings</strong>
                                            <span class="perm-item-desc">View system settings</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="bi bi-save"></i> Save Employee
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Password validation
    var password = document.getElementById("password"),
        confirm_password = document.getElementById("confirm_password");

    function validatePassword() {
        if (password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Passwords don't match");
        } else {
            confirm_password.setCustomValidity('');
        }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;

    // Account checkbox list scroll handling
    const accountsList = document.querySelector('.acc-check-list');
    if (accountsList) {
        accountsList.addEventListener('wheel', function(e) {
            const atTop = this.scrollTop === 0;
            const atBottom = this.scrollTop + this.clientHeight >= this.scrollHeight;

            if (this.scrollHeight > this.clientHeight) {
                if ((atTop && e.deltaY < 0) || (atBottom && e.deltaY > 0)) {
                    return;
                }
                e.stopPropagation();
            }
        }, { passive: true });

        let touchStartY = 0;
        accountsList.addEventListener('touchstart', function(e) {
            touchStartY = e.touches[0].clientY;
        }, { passive: true });

        accountsList.addEventListener('touchmove', function(e) {
            const touchY = e.touches[0].clientY;
            const deltaY = touchStartY - touchY;
            const atTop = this.scrollTop === 0;
            const atBottom = this.scrollTop + this.clientHeight >= this.scrollHeight;

            if (this.scrollHeight > this.clientHeight) {
                if ((atTop && deltaY < 0) || (atBottom && deltaY > 0)) {
                    return;
                }
                e.stopPropagation();
            }
        }, { passive: true });
    }
});

// Toggle permission groups
function togglePermGroup(header) {
    const group = header.closest('.perm-group');
    group.classList.toggle('collapsed');
}
</script>

</body>
</html>