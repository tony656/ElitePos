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
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
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
            @include("admin/sidenav")

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
                    <form action="updateEmployee" method="POST" enctype="multipart/form-data">
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
                                    <input type="text" name="levelStatus" id="levelStatus" class="form-control" value="{{$users->levelStatus ?? ''}}" required>
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
                                        $currentPermissions = json_decode($users->permissions, true) ?? [];
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
    <option value="view_employees">👥 View Employees</option>
    <option value="manage_employees">👥 Manage Employees</option>
    <option value="view_suppliers">👔 View Suppliers</option>
    <option value="manage_suppliers">👔 Manage Suppliers</option>
    <option value="view_customers">👥 View Customers</option>
    <option value="manage_customers">👥 Manage Customers</option>
    <option value="view_items">📦 View Items</option>
    <option value="manage_items">📦 Manage Items</option>
    <option value="create_items">📦 Create Items</option>
    <option value="view_receivings">📦 View Receivings</option>
    <option value="manage_receivings">📦 Manage Receivings</option>
    <option value="create_sales">🛒 Create Sales</option>
    <option value="manage_sales">🛒 Manage Sales</option>
    <option value="view_invoices">📋 View Invoices</option>
    <option value="manage_invoices">📋 Manage Invoices</option>
    <option value="view_reports">📊 View Reports</option>
    <option value="manage_reports">📊 Manage Reports</option>
    <option value="view_full_report">📊 View Full Report</option>
    <option value="manage_full_report">📊 Manage Full Report</option>
    <option value="view_sales_report">📊 View Sales Report</option>
    <option value="manage_sales_report">📊 Manage Sales Report</option>
    <option value="view_stock_report">📊 View Stock Report</option>
    <option value="manage_stock_report">📊 Manage Stock Report</option>
    <option value="view_expenses">💰 View Expenses</option>
    <option value="manage_expenses">💰 Manage Expenses</option>
    <option value="view_logs">📝 View Logs</option>
    <option value="manage_logs">📝 Manage Logs</option>
    <option value="view_settings">⚙️ View Settings</option>
    <option value="manage_settings">⚙️ Manage Settings</option>
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

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button type="submit" class="btn-primary">
                                <i class="bi bi-save"></i> Update Employee
                            </button>
                            <div class="btn-group-actions">
                                @if($users->status != 'deleted')
                                        @csrf
                                        <input type="hidden" name="employeeId" value="{{$users->id}}">
                                        <button type="submit" formaction="banUser" class="btn-warning">
                                            <i class="bi bi-shield-x"></i> {{ $users->status == 'banned' ? 'Unban' : 'Ban' }} User
                                        </button>
                                        @csrf
                                        <input type="hidden" name="employeeId" value="{{$users->id}}">
                                        <button type="submit" formaction="deleteUser" class="btn-danger" onclick="return confirm('Are you sure you want to delete this employee? This action cannot be undone.')">
                                            <i class="bi bi-trash"></i> Delete User
                                        </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.getElementById('addPermission').addEventListener('click', function() {
            const newPermSelect = document.getElementById('newPermission');
            const permValue = newPermSelect.value;
            const permText = newPermSelect.options[newPermSelect.selectedIndex].text;
            
            if (permValue) {
                const permissionsSelect = document.getElementById('permissions');
                const permissionsList = document.getElementById('permissionsList');
                
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

        document.getElementById('selectAllPermissions').addEventListener('click', function() {
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

        document.getElementById('removeSelected').addEventListener('click', function() {
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
    </script>
</body>
</html>