<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Employee Management</title>
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
        
        /* Header Section */
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow-lg);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            border: none;
        }

        .dashboard-header h4 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .dashboard-header i {
            font-size: 2rem;
        }
        
        .btn-primary {
            background-color: var(--accent);
            border: none;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            font-weight: 600;
            color: white;
            box-shadow: 0 5px 15px rgba(26, 188, 118, 0.3);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary:hover {
            background-color: #0fa063;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 188, 118, 0.4);
            color: white;
        }
        
        /* Employee Table */
        .employee-table {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .employee-table:hover {
            box-shadow: var(--box-shadow-lg);
        }
        
        .employee-table thead {
            background: linear-gradient(135deg, #f5f7fa 0%, #eff2f5 100%);
            border-bottom: 2px solid var(--border-color);
        }

        .employee-table thead th {
            font-weight: 700;
            padding: 1.25rem;
            color: var(--primary);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .employee-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--border-color);
        }
        
        .employee-table tbody tr:hover {
            background: linear-gradient(90deg, rgba(26, 188, 118, 0.05) 0%, transparent 100%);
        }

        .employee-table tbody td {
            padding: 1.25rem;
            vertical-align: middle;
            color: var(--text-primary);
        }

        .employee-table tbody td:first-child {
            font-weight: 700;
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .badge-admin {
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.15) 0%, rgba(26, 188, 118, 0.05) 100%);
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid rgba(15, 52, 96, 0.2);
        }
        
        .badge-seller {
            background: linear-gradient(135deg, rgba(26, 188, 118, 0.15) 0%, rgba(48, 197, 255, 0.05) 100%);
            color: var(--accent);
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid rgba(26, 188, 118, 0.2);
        }
        
        .btn-manage {
            background: linear-gradient(135deg, var(--accent) 0%, #0fa063 100%);
            color: white;
            border-radius: 0.75rem;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 3px 10px rgba(26, 188, 118, 0.2);
        }
        
        .btn-manage:hover {
            background: linear-gradient(135deg, #0fa063 0%, #0d7d50 100%);
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 5px 15px rgba(26, 188, 118, 0.3);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-state i {
            font-size: 3.5rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h5 {
            color: var(--text-primary);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-muted);
        }
        
        /* Modal Styles */
        .modal-content {
            border-radius: var(--border-radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--box-shadow-lg);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border: none;
            padding: 1.75rem;
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .modal-header .modal-title {
            font-weight: 700;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .btn-close:hover {
            opacity: 1;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .form-label {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-label .text-muted {
            font-weight: 500;
            color: var(--text-muted) !important;
            text-transform: none;
            letter-spacing: normal;
        }
        
        .form-control, .form-select {
            border-radius: 0.75rem;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            margin-bottom: 1.25rem;
            transition: all 0.2s ease;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(26, 188, 118, 0.1);
            outline: none;
        }

        .form-check {
            padding: 0.75rem 0;
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 0.4rem;
            border: 2px solid var(--border-color);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .form-check-input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(26, 188, 118, 0.1);
        }

        .form-check-label {
            font-weight: 500;
            color: var(--text-primary);
            margin-left: 0.75rem;
            cursor: pointer;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, var(--accent) 0%, #0fa063 100%);
            color: white;
            border-radius: 0.75rem;
            padding: 1rem;
            font-weight: 700;
            transition: all 0.3s ease;
            border: none;
            width: 100%;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 5px 15px rgba(26, 188, 118, 0.3);
        }
        
        .btn-submit:hover {
            background: linear-gradient(135deg, #0fa063 0%, #0d7d50 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 188, 118, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }
        
        .text-muted {
            color: var(--text-muted) !important;
            font-size: 0.875rem;
        }

        .employee-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--border-color);
        }

        .no-photo {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light-bg);
            color: var(--text-muted);
            border: 2px solid var(--border-color);
        }
        
        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1.5rem;
            }

            .btn-primary {
                width: 100%;
                justify-content: center;
            }

            .employee-table thead th,
            .employee-table tbody td {
                padding: 0.75rem 0.5rem;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @include("admin/sidenav")

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">

                <div class="dashboard-header">
                    <h4>
                        <i class="bi bi-people-fill"></i> Employee Management
                    </h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newEmployee">
                        <i class="bi bi-plus-circle"></i> New Employee
                    </button>
                </div>

                <div class="employee-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="10%">Photo</th>
                                    <th width="20%">Employee Name</th>
                                    <th width="10%">Age</th>
                                    <th width="15%">Contact</th>
                                    <th width="15%">Email</th>
                                    <th width="10%">Role</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($users->isEmpty())
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <h5>No employees found</h5>
                                            <p>Start by adding your first employee to get started</p>
                                        </div>
                                    </td>
                                </tr>
                                @else
                                    @foreach ($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            
                                            @if($user->userImg)
                                                <img src="{{ asset('images/' . $user->userImg) ?? "asset('images/EliteLogo.png')" }}" alt="Employee Photo" class="employee-photo">
                                            @else
                                                <div class="no-photo">
                                                    <i class="bi bi-person-circle"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td><strong>{{ $user->name ?? 'N/A' }}</strong></td>
                                        <td>{{ $user->age ?? 'N/A' }}</td>
                                        <td>{{ $user->contact ?? 'N/A' }}</td>
                                        <td>{{ $user->email ?? 'N/A' }}</td>
                                        <td>
                                            @if($user->levelStatus == 'Manager')
                                                <span class="badge-admin">👔 {{ $user->levelStatus }}</span>
                                            @else
                                                <span class="badge-seller">🏪 {{ $user->levelStatus }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="employeeView" method="post" style="display: inline;">
                                                @csrf
                                                <button class="btn btn-sm btn-manage" name="employeeId" value="{{$user->id}}">
                                                    <i class="bi bi-gear"></i> Manage
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

                <!-- New Employee Modal -->
                <div class="modal fade" id="newEmployee" tabindex="-1" aria-labelledby="newEmployeeLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="newEmployeeLabel">
                                    <i class="bi bi-person-plus-fill"></i> Register New Employee
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="registerEmployee" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="fname" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="fname" name="fname" placeholder="John Doe" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="contact" class="form-label">Contact Number <span class="text-muted">(optional)</span></label>
                                        <input type="tel" class="form-control" id="contact" name="contact" placeholder="+234 123 456 7890">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="john@example.com" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="age" class="form-label">Age <span class="text-muted">(optional)</span></label>
                                        <input type="number" class="form-control" id="age" name="age" placeholder="25">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password1" placeholder="Create a strong password" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="password2" placeholder="Confirm password" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="photo" class="form-label">Photo <span class="text-muted">(optional)</span></label>
                                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                    </div>

                                    <div class="mb-3">
                                        <label for="level" class="form-label">Employee Role</label>
                                        <select class="form-select" id="level" name="level" required>
                                            <option value="" selected disabled>Select role</option>
                                            <option value="Manager">👔 Manager</option>
                                            <option value="Seller">🏪 Seller</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
    <label class="form-label">Permissions</label>
    
    <!-- Employees -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_view_employees" name="permissions[]" value="view_employees">
        <label class="form-check-label" for="perm_view_employees">
            👥 View Employees
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_manage_employees" name="permissions[]" value="manage_employees">
        <label class="form-check-label" for="perm_manage_employees">
            👥 Manage Employees
        </label>
    </div>

    <!-- Suppliers -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_view_suppliers" name="permissions[]" value="view_suppliers">
        <label class="form-check-label" for="perm_view_suppliers">
            👔 View Suppliers
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_manage_suppliers" name="permissions[]" value="manage_suppliers">
        <label class="form-check-label" for="perm_manage_suppliers">
            👔 Manage Suppliers
        </label>
    </div>

    <!-- Customers -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_view_customers" name="permissions[]" value="view_customers">
        <label class="form-check-label" for="perm_view_customers">
            👥 View Customers
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_manage_customers" name="permissions[]" value="manage_customers">
        <label class="form-check-label" for="perm_manage_customers">
            👥 Manage Customers
        </label>
    </div>

    <!-- Items -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_view_items" name="permissions[]" value="view_items">
        <label class="form-check-label" for="perm_view_items">
            📦 View Items
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_manage_items" name="permissions[]" value="manage_items">
        <label class="form-check-label" for="perm_manage_items">
            📦 Manage Items
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_create_items" name="permissions[]" value="create_items">
        <label class="form-check-label" for="perm_create_items">
            📦 Create Items
        </label>
    </div>

    <!-- Receivings -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_view_receivings" name="permissions[]" value="view_receivings">
        <label class="form-check-label" for="perm_view_receivings">
            📦 View Receivings
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_manage_receivings" name="permissions[]" value="manage_receivings">
        <label class="form-check-label" for="perm_manage_receivings">
            📦 Manage Receivings
        </label>
    </div>

    <!-- Sales -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_create_sales" name="permissions[]" value="create_sales">
        <label class="form-check-label" for="perm_create_sales">
            🛒 Create Sales
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_manage_sales" name="permissions[]" value="manage_sales">
        <label class="form-check-label" for="perm_manage_sales">
            🛒 Manage Sales
        </label>
    </div>

    <!-- Invoices -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_view_invoices" name="permissions[]" value="view_invoices">
        <label class="form-check-label" for="perm_view_invoices">
            📋 View Invoices
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_manage_invoices" name="permissions[]" value="manage_invoices">
        <label class="form-check-label" for="perm_manage_invoices">
            📋 Manage Invoices
        </label>
    </div>

    <!-- Reports -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_view_reports" name="permissions[]" value="view_reports">
        <label class="form-check-label" for="perm_view_reports">
            📊 View Reports
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_manage_reports" name="permissions[]" value="manage_reports">
        <label class="form-check-label" for="perm_manage_reports">
            📊 Manage Reports
        </label>
    </div>

    <!-- Full Report -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_view_full_report" name="permissions[]" value="view_full_report">
        <label class="form-check-label" for="perm_view_full_report">
            📊 View Full Report
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_manage_full_report" name="permissions[]" value="manage_full_report">
        <label class="form-check-label" for="perm_manage_full_report">
            📊 Manage Full Report
        </label>
    </div>

    <!-- Sales Report -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_view_sales_report" name="permissions[]" value="view_sales_report">
        <label class="form-check-label" for="perm_view_sales_report">
            📊 View Sales Report
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_manage_sales_report" name="permissions[]" value="manage_sales_report">
        <label class="form-check-label" for="perm_manage_sales_report">
            📊 Manage Sales Report
        </label>
    </div>

    <!-- Stock Report -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_view_stock_report" name="permissions[]" value="view_stock_report">
        <label class="form-check-label" for="perm_view_stock_report">
            📊 View Stock Report
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_manage_stock_report" name="permissions[]" value="manage_stock_report">
        <label class="form-check-label" for="perm_manage_stock_report">
            📊 Manage Stock Report
        </label>
    </div>

    <!-- Expenses -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_view_expenses" name="permissions[]" value="view_expenses">
        <label class="form-check-label" for="perm_view_expenses">
            💰 View Expenses
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_manage_expenses" name="permissions[]" value="manage_expenses">
        <label class="form-check-label" for="perm_manage_expenses">
            💰 Manage Expenses
        </label>
    </div>

    <!-- Logs -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_view_logs" name="permissions[]" value="view_logs">
        <label class="form-check-label" for="perm_view_logs">
            📝 View Logs
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_manage_logs" name="permissions[]" value="manage_logs">
        <label class="form-check-label" for="perm_manage_logs">
            📝 Manage Logs
        </label>
    </div>

    <!-- Settings -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_view_settings" name="permissions[]" value="view_settings">
        <label class="form-check-label" for="perm_view_settings">
            ⚙️ View Settings
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="perm_manage_settings" name="permissions[]" value="manage_settings">
        <label class="form-check-label" for="perm_manage_settings">
            ⚙️ Manage Settings
        </label>
    </div>
</div>

                                    <button type="submit" class="btn btn-submit">
                                        <i class="bi bi-save"></i> Save Employee
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
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
        });
    </script>
</body>
</html>