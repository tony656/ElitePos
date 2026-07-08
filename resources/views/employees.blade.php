<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Employee Management</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --navy: #0B1E3D;
            --navy-mid: #112952;
            --navy-light: #1A3A6B;
            --amber: #F59E0B;
            --amber-dark: #D97706;
            --amber-pale: #FEF3C7;
            --emerald: #059669;
            --emerald-pale: #D1FAE5;
            --rose: #E11D48;
            --rose-pale: #FFE4E6;
            --slate-50: #F8FAFC;
            --slate-100: #F1F5F9;
            --slate-200: #E2E8F0;
            --slate-300: #CBD5E1;
            --slate-400: #94A3B8;
            --slate-500: #64748B;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1E293B;
            --white: #FFFFFF;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background: #EEF2F9;
            color: var(--slate-800);
        }

        .main-wrap { max-width: 1900px; margin: 0 auto; padding: 1.25rem 1.5rem; }

        /* Header */
        .page-header {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%);
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-icon {
            width: 56px;
            height: 56px;
            background: rgba(245,158,11,0.15);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--amber);
        }

        .header-title h1 {
            color: white;
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
        }

        .header-title p {
            color: rgba(255,255,255,0.7);
            font-size: 0.85rem;
            margin: 0.25rem 0 0;
        }

        .btn-primary-custom {
            background: var(--amber);
            color: var(--navy);
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245,158,11,0.3);
            background: var(--amber-dark);
            color: white;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid var(--slate-200);
            transition: all 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .stat-info h4 {
            color: var(--slate-500);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 0.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--navy);
            margin: 0;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            background: var(--amber-pale);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--amber);
        }

        /* Filters */
        .filters-bar {
            background: white;
            border-radius: 16px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            border: 1px solid var(--slate-200);
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 350px;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
        }

        .search-box input {
            width: 100%;
            padding: 0.65rem 1rem 0.65rem 2.5rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--amber);
            box-shadow: 0 0 0 3px rgba(245,158,11,0.1);
        }

        .filter-select {
            padding: 0.65rem 2rem 0.65rem 1rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            background: white;
            cursor: pointer;
        }

        /* Table */
        .table-wrapper {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--slate-200);
            overflow: hidden;
        }

        .employees-table {
            width: 100%;
            border-collapse: collapse;
        }

        .employees-table thead th {
            background: var(--slate-50);
            padding: 1rem 1.25rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--slate-500);
            border-bottom: 1px solid var(--slate-200);
            text-align: left;
        }

        .employees-table tbody td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--slate-100);
            font-size: 0.85rem;
            vertical-align: middle;
        }

        .employees-table tbody tr:hover td {
            background: var(--slate-50);
        }

        .employee-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            background: var(--slate-100);
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .role-admin { background: var(--navy); color: var(--amber); }
        .role-manager { background: var(--navy-light); color: white; }
        .role-seller { background: var(--emerald-pale); color: #065F46; }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-view {
            background: var(--amber-pale);
            color: var(--amber-dark);
        }

        .btn-view:hover {
            background: var(--amber);
            color: white;
        }

        .btn-delete {
            background: var(--rose-pale);
            color: var(--rose);
        }

        .btn-delete:hover {
            background: var(--rose);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            background: var(--slate-100);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--slate-400);
            margin: 0 auto 1rem;
        }

        /* Alert */
        .alert-custom {
            padding: 0.75rem 1rem;
            border-radius: 12px;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .alert-success {
            background: var(--emerald-pale);
            border-left: 4px solid var(--emerald);
            color: #065F46;
        }

        .alert-error {
            background: var(--rose-pale);
            border-left: 4px solid var(--rose);
            color: #9F1239;
        }

        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
            .page-header { flex-direction: column; align-items: flex-start; }
            .stats-grid { grid-template-columns: 1fr; }
            .filters-bar { flex-direction: column; align-items: stretch; }
            .search-box { max-width: none; }
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
                <div class="alert-custom alert-success">
                    <span><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert-custom alert-error">
                    <span><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-left">
                        <div class="header-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="header-title">
                            <h1>Employee Management</h1>
                            <p>Manage your team members and their permissions</p>
                        </div>
                    </div>
                    <button class="btn-primary-custom" onclick="openCreateModal()">
                        <i class="bi bi-plus-circle"></i> New Employee
                    </button>
                </div>

                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-info">
                            <h4>Total Employees</h4>
                            <div class="stat-number">{{ number_format($users->count()) }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h4>Active</h4>
                            <div class="stat-number">{{ number_format($users->where('status', 'online')->count()) }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h4>Admins</h4>
                            <div class="stat-number">{{ number_format($users->where('levelStatus', 'Admin')->count()) }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filters-bar">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" placeholder="Search by name, email, or role...">
                    </div>
                    <div class="">
                        <select class="filter-select" id="roleFilter">
                        <option value="">All Roles</option>
                        <option value="Admin">Admin</option>
                        <option value="Manager">Manager</option>
                        <option value="Seller">Seller</option>
                    </select>
                    <select class="filter-select" id="shopFilter">
                        <option value="">All Shops</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account['name'] }}">{{ $account['name'] }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-wrapper">
                    <table class="employees-table" id="employeesTable">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Contact</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Assigned Shops</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @forelse($users as $user)
                            @php
                                if($user->status === 'banned') {
                                    $class = "bg-danger bg-gradient";
                                } else {
                                    $class = '';
                                }
                            @endphp
                            <tr class="{{ $class }}">
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        @if($user->userImg)
                                            <img src="{{ asset('/public/images/' . $user->userImg) }}" class="employee-avatar">
                                        @else
                                            <div class="employee-avatar" style="display: flex; align-items: center; justify-content: center; background: var(--slate-200);">
                                                <i class="bi bi-person" style="font-size: 1.2rem;"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div style="font-weight: 700; color: var(--navy);">{{ $user->name }}</div>
                                            <div style="font-size: 0.75rem; color: var(--slate-500);">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->contact ?? 'Not provided' }}</td>
                                <td>
                                    @if($user->levelStatus == 'Admin')
                                        <span class="role-badge role-admin"><i class="bi bi-shield-fill-check"></i> Admin</span>
                                    @elseif($user->levelStatus == 'Manager')
                                        <span class="role-badge role-manager"><i class="bi bi-star-fill"></i> Manager</span>
                                    @else
                                        <span class="role-badge role-seller"><i class="bi bi-cart-fill"></i> Seller</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="display: inline-flex; align-items: center; gap: 0.3rem;">
                                        <i class="bi bi-circle-fill" style="font-size: 0.5rem; color: var(--emerald);"></i>
                                        {{ $user->status ?? 'Active' }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        
                                       $accountz = DB::table('user_accounts')->where('user_id', $user->id)->pluck('account')->toArray();

$accountNames = DB::table('accounts')->whereIn('id', $accountz)->pluck('name')->toArray();
@endphp

@foreach(array_slice($accountNames, 0, 2) as $name)
                                        <span class="role-badge" style="background: var(--slate-100); color: var(--slate-600); margin: 0.15rem;">{{ $name }}</span>
                                    @endforeach
                                    @if(count($accountNames) > 2)
                                        <span class="role-badge" style="background: var(--slate-100);">+{{ count($accountNames) - 2 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <form action="/employeeView/{{ $user->id }}" method="post">
                                            @csrf
                                            <button type="submit" class="action-btn btn-view">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                        </form>
                                        <button class="action-btn btn-delete" onclick="deleteEmployee({{ $user->id }}, '{{ $user->name }}')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <h4 style="margin-bottom: 0.5rem;">No Employees Yet</h4>
                                        <p style="color: var(--slate-500);">Click "New Employee" to get started</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Create Employee Modal Container -->
<div id="createModalContainer"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function openCreateModal() {
    fetch('/employees/create-modal')
        .then(response => response.text())
        .then(html => {
            document.getElementById('createModalContainer').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('createEmployeeModal'));
            modal.show();
            
            // Load permission groups after modal is shown
            setTimeout(() => {
                initPermissionGroups();
                initPasswordValidation();
            }, 100);
        });
}

function viewEmployee(id) {
    window.location.href = '/employeeView/' + id;
}

function deleteEmployee(id, name) {
    if (confirm(`Are you sure you want to delete ${name}? This action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/employeeDelete';
        form.innerHTML = `
            @csrf
            <input type="hidden" name="employeeId" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Search and filter functionality
function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const roleFilter = document.getElementById('roleFilter').value.toLowerCase();
    const rows = document.querySelectorAll('#tableBody tr');
    
    rows.forEach(row => {
        if (row.querySelector('.empty-state')) return;
        
        const text = row.innerText.toLowerCase();
        const role = row.querySelector('.role-badge')?.innerText.toLowerCase() || '';
        
        let matchesSearch = searchTerm === '' || text.includes(searchTerm);
        let matchesRole = roleFilter === '' || role.includes(roleFilter);
        
        row.style.display = matchesSearch && matchesRole ? '' : 'none';
    });
}

document.getElementById('searchInput')?.addEventListener('input', filterTable);
document.getElementById('roleFilter')?.addEventListener('change', filterTable);

function initPermissionGroups() {
    document.querySelectorAll('.perm-group-head').forEach(header => {
        header.onclick = () => {
            const group = header.closest('.perm-group');
            group.classList.toggle('collapsed');
        };
    });
}

function initPasswordValidation() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    if (password && confirmPassword) {
        function validate() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity("Passwords don't match");
            } else {
                confirmPassword.setCustomValidity('');
            }
        }
        password.onchange = validate;
        confirmPassword.onkeyup = validate;
    }
}
</script>

</body>
</html>