<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Employee Details</title>
    @include("links")
    <style>
        :root {
            --primary: #0f3460;
            --primary-light: #16213e;
            --accent: #1abc76;
            --accent-alt: #30c5ff;
            --danger: #e74c3c;
            --warning: #f39c12;
            --info: #3498db;
            --light-bg: #f8f9fa;
            --border-color: #e0e7ff;
            --text-primary: #2c3e50;
            --text-muted: #7f8c8d;
            --border-radius: 1.25rem;
            --box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            --box-shadow-lg: 0 15px 40px rgba(0,0,0,0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            color: var(--text-primary);
            min-height: 100vh;
        }

        .container-fluid {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding-bottom: 2rem;
        }

        main {
            padding-bottom: 2rem;
        }

        /* Header Navigation */
        .header-nav {
            background: white;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-back {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-back:hover {
            transform: translateX(-3px);
            box-shadow: 0 5px 15px rgba(15, 52, 96, 0.3);
        }

        /* Profile Header */
        .profile-header {
            background: white;
            border-radius: var(--border-radius);
            padding: 2.5rem;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: var(--box-shadow);
            border: 1px solid var(--border-color);
        }

        .profile-photo {
            width: 180px;
            height: 180px;
            margin: 0 auto 1.5rem;
            border: 6px solid var(--accent-alt);
            border-radius: 50%;
            object-fit: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.1) 0%, rgba(26, 188, 118, 0.1) 100%);
            box-shadow: 0 10px 30px rgba(48, 197, 255, 0.2);
        }

        .profile-photo svg {
            width: 100px;
            height: 100px;
        }

        .profile-status {
            display: inline-block;
            background: linear-gradient(135deg, rgba(26, 188, 118, 0.15) 0%, rgba(48, 197, 255, 0.05) 100%);
            color: var(--accent);
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
            border: 1px solid rgba(26, 188, 118, 0.2);
        }

        /* Form Container */
        .form-container {
            background: white;
            border-radius: var(--border-radius);
            padding: 2.5rem;
            box-shadow: var(--box-shadow);
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .form-row.full {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control, .form-select {
            border-radius: 0.75rem;
            padding: 1rem;
            border: 2px solid var(--border-color);
            font-size: 1rem;
            transition: all 0.2s ease;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(26, 188, 118, 0.1);
        }

        .form-text {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 0.5rem;
        }

        /* Permissions Section */
        .permissions-section {
            background: linear-gradient(135deg, rgba(26, 188, 118, 0.05) 0%, rgba(48, 197, 255, 0.05) 100%);
            border-radius: 1rem;
            padding: 2rem;
            border: 2px solid var(--border-color);
        }

        .permissions-list {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 2px solid var(--border-color);
            margin-bottom: 1.5rem;
            min-height: 120px;
            max-height: 200px;
            overflow-y: auto;
        }

        .permission-item {
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg, var(--accent) 0%, #0fa063 100%);
            color: white;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            display: inline-block;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .permission-item:last-child {
            margin-bottom: 0;
        }

        .add-permission-group {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .btn-add {
            background: linear-gradient(135deg, var(--accent) 0%, #0fa063 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 5px 15px rgba(26, 188, 118, 0.3);
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 188, 118, 0.4);
        }

        .btn-remove {
            background: linear-gradient(135deg, var(--danger) 0%, #c0392b 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .btn-remove:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.4);
        }

        /* Helper text below lists */
        .list-helper-text {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid var(--border-color);
        }

        .btn-primary, .btn-warning, .btn-danger {
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent) 0%, #0fa063 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(26, 188, 118, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 188, 118, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning) 0%, #d68910 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(243, 156, 18, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #c0392b 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.4);
        }

        .btn-group-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .add-permission-group {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }

            .form-container {
                padding: 1.5rem;
            }

            .profile-header {
                padding: 1.5rem;
            }

            .header-nav {
                padding: 1rem;
            }

            .profile-photo {
                width: 140px;
                height: 140px;
            }
        }
    </style>
    <link href="{{asset("css/dashboard.css")}}" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @include("user/sidenav")

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

                <div class="header-nav">
                    <button class="btn-back" onclick="history.back()">
                        <i class="bi bi-chevron-left"></i> Back
                    </button>
                </div>

                <!-- Profile Header -->
                <div class="profile-header">
                    @if($users->userImg)
                        <img src="{{ asset('images/' . $users->userImg) }}" class="profile-photo" alt="Employee Photo">
                    @else
                        <div class="profile-photo">
                            <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="6" r="4" fill="#0f3460"></circle>
                                <path d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z" fill="#0f3460"></path>
                            </svg>
                        </div>
                    @endif
                    <button type="button" class="btn-update-photo" onclick="document.getElementById('photo').click()">
                        <i class="bi bi-camera"></i> Update Picture
                    </button>
                    <h2 style="margin: 1rem 0 0.5rem 0; font-weight: 700; color: var(--primary);">{{ $users->name ?? 'N/A' }}</h2>
                    <span class="profile-status">{{ $users->levelStatus ?? 'N/A' }}</span>
                </div>

                <!-- Form Container -->
                <div class="form-container">
                    <form action="{{ url('user/updateEmployee') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="employeeId" value="{{$users->id}}">

                        <!-- Personal Information -->
                        <div class="form-section">
                            <h3 style="margin-bottom: 1.5rem; color: var(--primary); font-size: 1.2rem; font-weight: 700;">
                                <i class="bi bi-person me-2"></i>Personal Information
                            </h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{$users->name ?? ''}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="age" class="form-label">Age</label>
                                    <input type="number" name="age" id="age" class="form-control" value="{{$users->age ?? ''}}" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="contact" class="form-label">Contact Number</label>
                                    <input type="text" name="contact" id="contact" class="form-control" value="{{$users->contact ?? ''}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{$users->email ?? ''}}" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="photo" class="form-label">Profile Photo</label>
                                    <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                                    @if($users->userImg)
                                        <span class="form-text">💡 Leave empty to keep current photo</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="levelStatus" class="form-label">Role / Level Status</label>
                                    <select class="form-select" name="levelStatus" id="levelStatus" class="form-control" required>
                                        <option value="Admin" {{ ($users->levelStatus ?? '') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="Manager" {{ ($users->levelStatus ?? '') == 'Manager' ? 'selected' : '' }}>Manager</option>
                                        <option value="Seller" {{ ($users->levelStatus ?? '') == 'Seller' ? 'selected' : '' }}>Seller</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions Management -->
                        <div class="form-section">
                            <h3 style="margin-bottom: 1.5rem; color: var(--primary); font-size: 1.2rem; font-weight: 700;">
                                <i class="bi bi-shield-check me-2"></i>Permissions Management
                            </h3>
                            
                            <div class="permissions-section">
                                <label class="form-label" style="display: block; margin-bottom: 1rem;">Current Permissions</label>
                                <div class="permissions-list" id="permissionsList">
                                    @php
                                        $currentPermissions = $users->permissions ?? [];
                                    @endphp
                                    @forelse($currentPermissions as $perm)
                                        <div class="permission-item">
                                            @if($perm == 'manage_products') 📦 @elseif($perm == 'manage_orders') 📋 @elseif($perm == 'manage_customers') 👥 @elseif($perm == 'view_sales') 📊 @elseif($perm == 'manage_expenses') 💰 @elseif($perm == 'manage_employees') 👨‍💼 @endif
                                            {{ str_replace('_', ' ', ucwords($perm, '_')) }}
                                        </div>
                                    @empty
                                        <span style="color: var(--text-muted);">No permissions assigned</span>
                                    @endforelse
                                </div>
                                <select name="permissions[]" id="permissions" style="display: none;">
                                    @foreach($currentPermissions as $perm)
                                        <option value="{{ $perm }}" selected>{{ $perm }}</option>
                                    @endforeach
                                </select>

                                <div class="add-permission-group">
                                    <select id="newPermission" class="form-select">
    <option value="" selected disabled>Select permission to add</option>
    @php
        $allPermissions = [
            'view_employees' => '👥 View Employees',
            'manage_employees' => '👥 Manage Employees',
            'view_suppliers' => '👔 View Suppliers',
            'manage_suppliers' => '👔 Manage Suppliers',
            'view_customers' => '👥 View Customers',
            'add_customers' => '👥 Add Customers',
            'edit_customers' => '👥 Edit Customers',
            'manage_customers' => '👥 Manage Customers',
            'view_items' => '📦 View Items',
            'manage_items' => '📦 Manage Items',
            'create_items' => '📦 Create Items',
            'view_receivings' => '📦 View Receivings',
            'manage_receivings' => '📦 Manage Receivings',
            'create_sales' => '🛒 Create Sales',
            'manage_sales' => '🛒 Manage Sales',
            'view_invoices' => '📋 View Invoices',
            'manage_invoices' => '📋 Manage Invoices',
            'view_shop_debts' => '📋 View Shop Debts',
            'view_all_shops' => '🏪 View All Shops',
            'pay_debts' => '💵 Pay Debts',
            'view_reports' => '📊 View Reports',
            'manage_reports' => '📊 Manage Reports',
            'view_full_report' => '📊 View Full Report',
            'manage_full_report' => '📊 Manage Full Report',
            'view_sales_report' => '📊 View Sales Report',
            'manage_sales_report' => '📊 Manage Sales Report',
            'view_stock_report' => '📊 View Stock Report',
            'manage_stock_report' => '📊 Manage Stock Report',
            'view_expenses' => '💰 View Expenses',
            'manage_expenses' => '💰 Manage Expenses',
            'view_logs' => '📝 View Logs',
            'manage_logs' => '📝 Manage Logs',
            'view_settings' => '⚙️ View Settings',
            'manage_settings' => '⚙️ Manage Settings',
            'manage_shop_cash_submit' => '💰 Manage Shop Cash Submit',
            'add_banking_transfer' => '🏦 Add Banking Transfer',
            'delete_banking_transfer' => '🏦 Delete Banking Transfer',
            'add_banking_chip' => '🔶 Add Banking Chip',
            'edit_banking_chip' => '🔶 Edit Banking Chip',
            'delete_banking_chip' => '🔶 Delete Banking Chip',
        ];
        $currentPermissions = json_decode($users->permissions, true) ?? [];
    @endphp
    @foreach($allPermissions as $permValue => $permLabel)
        @if(!in_array($permValue, $currentPermissions))
            <option value="{{ $permValue }}">{{ $permLabel }}</option>
        @endif
    @endforeach
</select>
                                    <button type="button" id="addPermission" class="btn-add">
                                        <i class="bi bi-plus-circle"></i> Add
                                    </button>
                                    <button type="button" id="selectAllPermissions" class="btn-add">
                                        <i class="bi bi-check-all"></i> Select All
                                    </button>
                                </div>

                                <button type="button" id="removeSelected" class="btn-remove">
                                    <i class="bi bi-trash"></i> Remove Selected
                                </button>
                            </div>
                        </div>

                        <!-- Accounts Management -->
                        <div class="form-section">
                            <h3 style="margin-bottom: 1.5rem; color: var(--primary); font-size: 1.2rem; font-weight: 700;">
                                <i class="bi bi-building me-2"></i>Account Assignments
                            </h3>
                            
                            <div class="permissions-section">
                                <label class="form-label" style="display: block; margin-bottom: 1rem;">Current Assigned Accounts</label>
                                <div class="permissions-list" id="accountsList">
                                    @forelse($userAccounts as $ua)
                                        <div class="permission-item">
                                            🏪 {{ $ua->account }}
                                            @if($ua->is_primary)
                                                <span style="background: rgba(15, 52, 96, 0.3); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; margin-left: 8px;">Primary</span>
                                            @endif
                                        </div>
                                    @empty
                                        <span style="color: var(--text-muted);">No additional accounts assigned (using primary: {{ $users->account }})</span>
                                    @endforelse
                                </div>

                                <!-- Hidden select for form submission -->
                                <select name="accounts[]" id="accounts" style="display: none;" multiple>
                                    @foreach($userAccounts as $ua)
                                        <option value="{{ $ua->account }}" selected>{{ $ua->account }}</option>
                                    @endforeach
                                </select>

                                <div class="add-permission-group">
                                    <select id="newAccount" class="form-select">
                                        <option value="" selected disabled>Select account to add</option>
                                        @foreach($accounts as $account)
                                            @php
                                                $isAssigned = $userAccounts->contains('account', $account->account);
                                            @endphp
                                            @if(!$isAssigned)
                                                <option value="{{ $account->account }}">{{ $account->account }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button type="button" id="addAccount" class="btn-add">
                                        <i class="bi bi-plus-circle"></i> Add Account
                                    </button>
                                </div>

                                <button type="button" id="removeSelectedAccount" class="btn-remove">
                                    <i class="bi bi-trash"></i> Remove Selected Account
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button type="submit" class="btn-primary">
                                <i class="bi bi-save"></i> Update Employee
                            </button>
                            <div class="btn-group-actions">
                                @if($users->status != 'deleted')
                                        <input type="hidden" name="employeeId" value="{{$users->id}}">
                                        <button type="submit" formaction="{{ url('user/banUser') }}" class="btn-warning">
                                            <i class="bi bi-shield-x"></i> {{ $users->status == 'banned' ? 'Unban' : 'Ban' }} User
                                        </button>
                                        <input type="hidden" name="employeeId" value="{{$users->id}}">
                                        <button type="submit" formaction="{{ url('user/deleteUser') }}" class="btn-danger" onclick="return confirm('Are you sure you want to delete this employee? This action cannot be undone.')">
                                            <i class="bi bi-trash"></i> Delete User
                                        </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Password Change Form -->
                <div class="form-container">
                    <form action="{{ url('user/changePassword') }}" method="POST">
                        @csrf
                        <input type="hidden" name="employeeId" value="{{$users->id}}">

                        <div class="form-section">
                            <h3 style="margin-bottom: 1.5rem; color: var(--primary); font-size: 1.2rem; font-weight: 700;">
                                <i class="bi bi-key me-2"></i>Change Password
                            </h3>
                            
                            <div class="permissions-section">
                                <p class="list-helper-text">
                                    <i class="bi bi-info-circle"></i>
                                    Enter a new password to change the employee's password. Leave blank to keep current password.
                                </p>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter new password">
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm new password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button type="submit" class="btn-primary">
                                <i class="bi bi-key"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Account management - Add Account
            const addAccountBtn = document.getElementById('addAccount');
            if (addAccountBtn) {
                addAccountBtn.addEventListener('click', function() {
                    const newAccountSelect = document.getElementById('newAccount');
                    if (!newAccountSelect) return;
                    
                    const accountValue = newAccountSelect.value;
                    
                    if (accountValue && accountValue !== '') {
                        const accountsSelect = document.getElementById('accounts');
                        const accountsList = document.getElementById('accountsList');
                        
                        // Check if account already exists in hidden select
                        const existingOptions = Array.from(accountsSelect.options).map(opt => opt.value);
                        if (!existingOptions.includes(accountValue)) {
                            // Add to hidden select
                            const option = document.createElement('option');
                            option.value = accountValue;
                            option.text = accountValue;
                            option.selected = true;
                            accountsSelect.appendChild(option);
                            
                            // Clear "no accounts" message if it exists
                            const noAccountsMsg = accountsList.querySelector('span');
                            if (noAccountsMsg) {
                                accountsList.innerHTML = '';
                            }
                            
                            // Add visual item to list
                            const accountItem = document.createElement('div');
                            accountItem.className = 'permission-item';
                            accountItem.innerHTML = '🏪 ' + accountValue;
                            accountsList.appendChild(accountItem);
                            
                            // Remove the selected option from dropdown
                            newAccountSelect.remove(newAccountSelect.selectedIndex);
                            newAccountSelect.value = '';
                        }
                    }
                });
            }

            // Account management - Remove Account
            const removeAccountBtn = document.getElementById('removeSelectedAccount');
            if (removeAccountBtn) {
                removeAccountBtn.addEventListener('click', function() {
                    const accountsSelect = document.getElementById('accounts');
                    const accountsList = document.getElementById('accountsList');
                    const newAccountSelect = document.getElementById('newAccount');
                    const options = accountsSelect.options;
                    
                    // Get selected accounts
                    const selectedAccounts = Array.from(options)
                        .filter(opt => opt.selected)
                        .map(opt => opt.value);
                    
                    // Remove selected options
                    for (let i = options.length - 1; i >= 0; i--) {
                        if (options[i].selected) {
                            // Add back to dropdown
                            const newOption = document.createElement('option');
                            newOption.value = options[i].value;
                            newOption.text = options[i].value;
                            newAccountSelect.appendChild(newOption);
                            
                            options[i].remove();
                        }
                    }
                    
                    // Update display list - remove items for removed accounts
                    const items = accountsList.querySelectorAll('.permission-item');
                    items.forEach(item => {
                        const accountText = item.textContent.replace('🏪', '').replace('Primary', '').trim();
                        selectedAccounts.forEach(acc => {
                            if (accountText.includes(acc)) {
                                item.remove();
                            }
                        });
                    });
                    
                    // If no accounts left, show a message
                    if (accountsSelect.options.length === 0) {
                        accountsList.innerHTML = '<span style="color: var(--text-muted);">No additional accounts assigned (using primary: {{ $users->account }})</span>';
                    }
                });
            }

            // Permissions management - Add Permission
            const addPermissionBtn = document.getElementById('addPermission');
            if (addPermissionBtn) {
                addPermissionBtn.addEventListener('click', function() {
                    const newPermSelect = document.getElementById('newPermission');
                    if (!newPermSelect) return;
                    
                    const permValue = newPermSelect.value;
                    
                    if (permValue && permValue !== '') {
                        const permissionsSelect = document.getElementById('permissions');
                        const permissionsList = document.getElementById('permissionsList');
                        const permText = newPermSelect.options[newPermSelect.selectedIndex].text;
                        
                        // Check if permission already exists
                        const existingOptions = Array.from(permissionsSelect.options).map(opt => opt.value);
                        if (!existingOptions.includes(permValue)) {
                            // Add to hidden select
                            const option = document.createElement('option');
                            option.value = permValue;
                            option.text = permText;
                            option.selected = true;
                            permissionsSelect.appendChild(option);
                            
                            // Add visual item to list
                            const permItem = document.createElement('div');
                            permItem.className = 'permission-item';
                            permItem.textContent = permText;
                            permissionsList.appendChild(permItem);
                        }
                        
                        newPermSelect.value = '';
                    }
                });
            }

            // Permissions management - Select All
            const selectAllBtn = document.getElementById('selectAllPermissions');
            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', function() {
                    const newPermSelect = document.getElementById('newPermission');
                    const permissionsSelect = document.getElementById('permissions');
                    const permissionsList = document.getElementById('permissionsList');
                    
                    // Get existing permissions
                    const existingOptions = Array.from(permissionsSelect.options).map(opt => opt.value);
                    
                    // Loop through all options in the newPermission select, skipping the first (disabled)
                    for (let i = 1; i < newPermSelect.options.length; i++) {
                        const option = newPermSelect.options[i];
                        const permValue = option.value;
                        const permText = option.text;
                        
                        // Check if permission already exists
                        if (!existingOptions.includes(permValue)) {
                            // Add to hidden select
                            const newOption = document.createElement('option');
                            newOption.value = permValue;
                            newOption.text = permText;
                            newOption.selected = true;
                            permissionsSelect.appendChild(newOption);
                            
                            // Add visual item to list
                            const permItem = document.createElement('div');
                            permItem.className = 'permission-item';
                            permItem.textContent = permText;
                            permissionsList.appendChild(permItem);
                        }
                    }
                });
            }

            // Permissions management - Remove Selected
            const removePermBtn = document.getElementById('removeSelected');
            if (removePermBtn) {
                removePermBtn.addEventListener('click', function() {
                    const permissionsSelect = document.getElementById('permissions');
                    const permissionsList = document.getElementById('permissionsList');
                    const options = permissionsSelect.options;
                    
                    // Get selected permissions
                    const selectedPerms = Array.from(options)
                        .filter(opt => opt.selected)
                        .map(opt => opt.value);
                    
                    // Remove selected options
                    for (let i = options.length - 1; i >= 0; i--) {
                        if (options[i].selected) {
                            permissionsSelect.remove(i);
                        }
                    }
                    
                    // Update display list
                    const items = permissionsList.querySelectorAll('.permission-item');
                    items.forEach(item => {
                        const permText = item.textContent.trim();
                        selectedPerms.forEach(perm => {
                            if (permText.includes(perm.replace('_', ' '))) {
                                item.remove();
                            }
                        });
                    });
                });
            }
        });
    </script>
</body>
</html>