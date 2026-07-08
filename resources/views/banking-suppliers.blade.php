<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - @lang('messages.banking_suppliers')</title>
    
    @include("links")
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --primary-light: #3b82f6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-light: #94a3b8;
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #e2e8f0;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.1);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }

        .container-fluid {
            padding: 0;
        }

        .row {
            margin: 0;
        }

        main {
            padding: 2rem !important;
            background: transparent !important;
            border-radius: 0 !important;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-left h3 {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.5px;
            margin: 0;
        }

        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            background: var(--bg-primary);
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .back-btn:hover {
            background: var(--bg-secondary);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Primary Button */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%) !important;
            border: none !important;
            color: white !important;
            font-weight: 600 !important;
            padding: 0.65rem 1.25rem !important;
            border-radius: var(--radius-md) !important;
            font-size: 0.9375rem !important;
            cursor: pointer;
            transition: all 0.3s ease !important;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2) !important;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.35) !important;
        }

        .btn-primary:active {
            transform: translateY(0) !important;
        }

        /* Secondary Buttons */
        .btn-sm {
            padding: 0.4rem 0.75rem !important;
            font-size: 0.8125rem !important;
            border-radius: var(--radius-sm) !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
            border: 1px solid var(--border-color) !important;
        }

        .btn-outline-primary {
            background: transparent !important;
            color: var(--primary) !important;
            border: 1.5px solid var(--primary) !important;
        }

        .btn-outline-primary:hover {
            background: var(--primary) !important;
            color: white !important;
            transform: translateY(-1px) !important;
        }

        .btn-outline-warning {
            background: transparent !important;
            color: var(--warning) !important;
            border: 1.5px solid var(--warning) !important;
        }

        .btn-outline-warning:hover {
            background: var(--warning) !important;
            color: white !important;
            transform: translateY(-1px) !important;
        }

        .btn-outline-danger {
            background: transparent !important;
            color: var(--danger) !important;
            border: 1.5px solid var(--danger) !important;
        }

        .btn-outline-danger:hover {
            background: var(--danger) !important;
            color: white !important;
            transform: translateY(-1px) !important;
        }

        /* Card */
        .card {
            background: var(--bg-primary) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: var(--radius-lg) !important;
            box-shadow: var(--shadow-sm) !important;
            overflow: hidden !important;
        }

        .card-header {
            background: var(--bg-secondary) !important;
            border-bottom: 1px solid var(--border-color) !important;
            padding: 1.5rem !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h6 {
            font-weight: 700 !important;
            color: var(--text-primary) !important;
            margin: 0 !important;
            font-size: 1.125rem;
            letter-spacing: -0.5px;
        }

        .card-body {
            padding: 1.5rem !important;
        }

        /* Table */
        .table {
            border-collapse: collapse !important;
            margin-bottom: 0 !important;
        }

        .thead-light th {
            background: var(--bg-secondary) !important;
            border-bottom: 1px solid var(--border-color) !important;
            color: var(--text-secondary) !important;
            font-weight: 600 !important;
            font-size: 0.8125rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.3px !important;
            padding: 1rem !important;
        }

        .table tbody tr {
            border-bottom: 1px solid var(--border-color) !important;
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: var(--bg-secondary) !important;
        }

        .table tbody td {
            padding: 1rem !important;
            color: var(--text-primary) !important;
            font-size: 0.9375rem !important;
        }

        .text-muted {
            color: var(--text-light) !important;
            font-size: 0.875rem !important;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .action-buttons form {
            display: inline-flex;
            gap: 0.5rem;
        }

        /* Modal */
        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%) !important;
            color: white !important;
            border: none !important;
            padding: 1.5rem !important;
        }

        .modal-title {
            font-weight: 700 !important;
            font-size: 1.25rem !important;
        }

        .btn-close-white {
            opacity: 0.8 !important;
        }

        .btn-close-white:hover {
            opacity: 1 !important;
        }

        .modal-body {
            padding: 2rem !important;
        }

        .form-label {
            font-weight: 600 !important;
            color: var(--text-primary) !important;
            margin-bottom: 0.5rem !important;
            font-size: 0.9375rem !important;
        }

        .form-control,
        .form-select {
            border: 1px solid var(--border-color) !important;
            border-radius: var(--radius-md) !important;
            padding: 0.65rem 0.875rem !important;
            font-size: 0.9375rem !important;
            color: var(--text-primary) !important;
            background: var(--bg-primary) !important;
            transition: all 0.2s ease !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
            outline: none !important;
        }

        .text-danger {
            color: var(--danger) !important;
        }

        .d-grid {
            display: grid !important;
        }

        .gap-2 {
            gap: 0.5rem !important;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .empty-state h5 {
            color: var(--text-primary);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }

        /* Utilities */
        .d-flex {
            display: flex !important;
        }

        .justify-content-between {
            justify-content: space-between !important;
        }

        .justify-content-center {
            justify-content: center !important;
        }

        .align-items-center {
            align-items: center !important;
        }

        .flex-wrap {
            flex-wrap: wrap !important;
        }

        .gap-1 {
            gap: 0.25rem !important;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .mb-1 {
            margin-bottom: 0.25rem !important;
        }

        .mb-2 {
            margin-bottom: 0.5rem !important;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        .mt-2 {
            margin-top: 0.5rem !important;
        }

        .mt-3 {
            margin-top: 1rem !important;
        }

        .mt-4 {
            margin-top: 1.5rem !important;
        }

        .me-1 {
            margin-right: 0.25rem !important;
        }

        .me-2 {
            margin-right: 0.5rem !important;
        }

        .ps-2 {
            padding-left: 0.5rem !important;
        }

        .pe-2 {
            padding-right: 0.5rem !important;
        }

        .py-2 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .py-4 {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-end {
            text-align: end !important;
        }

        .col {
            flex: 1;
        }

        .col-md-6 {
            width: 50%;
        }

        .col-md-8 {
            width: 66.66%;
        }

        .col-md-9 {
            margin-left: 25%;
            width: 75%;
        }

        .col-lg-10 {
            width: 100%;
        }

        .col-xl-3 {
            width: 25%;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -0.75rem;
        }

        .row > * {
            padding: 0.75rem;
        }

        .no-gutters {
            margin: 0 !important;
        }

        .no-gutters > * {
            padding: 0 !important;
        }

        .d-print-none {
            print: none !important;
        }

        .modal-dialog {
            max-width: 600px !important;
        }

        .modal-dialog.modal-lg {
            max-width: 800px !important;
        }

        .modal-content {
            border: none !important;
            border-radius: var(--radius-lg) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            main {
                padding: 1rem !important;
            }

            .page-header {
                margin-bottom: 1.5rem;
                padding-bottom: 1rem;
            }

            .header-left h3 {
                font-size: 1.5rem;
            }

            .table thead {
                display: none !important;
            }

            .table tr {
                display: block !important;
                margin-bottom: 1rem !important;
                border: 1px solid var(--border-color) !important;
                border-radius: var(--radius-md) !important;
                padding: 1rem !important;
                background: var(--bg-primary) !important;
            }

            .table td {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                padding: 0.75rem 0 !important;
                border-bottom: 1px solid var(--border-color) !important;
            }

            .table td:last-child {
                border-bottom: none !important;
                padding-top: 1rem !important;
                margin-top: 0.75rem !important;
                padding-top: 0.75rem !important;
                border-top: 1px solid var(--border-color) !important;
            }

            .table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--text-secondary);
                min-width: 100px;
            }

            .action-buttons {
                width: 100% !important;
                justify-content: flex-end;
            }

            .btn-primary {
                width: 100%;
                justify-content: center;
            }
        }

        /* Animation */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: slideUp 0.5s ease-out forwards;
        }
    </style>

    <link href="{{asset("css/dashboard.css")}}" rel="stylesheet">
</head>
<body>
            @include("sidenav")

            <main class="main-content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-left">
                        <a href="javascript:history.back()" class="back-btn d-print-none">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                        <h3>{{ __('messages.banking_suppliers') }}</h3>
                    </div>
                    <div>
                        @if(canUser("add_banking_supplier"))
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBankingSupplier">
                            <i class="bi bi-plus-lg"></i>
                            Add Banking Supplier
                        </button>
                        @endif
                    </div>
                </div>

                <!-- {{ __('messages.banking_suppliers_table') }} -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6>{{ __('messages.banking_suppliers_directory') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="bankingSuppliersTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="ps-2" width="5%">#</th>
                                        <th>Name</th>
                                        <th>Primary Bank Account</th>
                                        <th class="text-end pe-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($suppliers->isEmpty())
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-state">
                                                <i class="bi bi-bank text-muted"></i>
                                                <h5>{{ __('messages.no_banking_suppliers_found') }}</h5>
                                                <p>Add your first banking supplier to get started</p>
                                                @if(canUser("add_banking_supplier"))
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBankingSupplier">
                                                    <i class="bi bi-plus-lg me-1"></i> Add Banking Supplier
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @else
                                        @foreach ($suppliers as $index => $supplier)
                                        <tr>
                                            <td class="ps-2">{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $supplier->name }}</strong>
                                                @if($supplier->address)
                                                <div class="text-muted small">{{ $supplier->address }}</div>
                                                @endif
                                                @if($supplier->accounts->count() > 0)
                                                <div class="mt-2">
                                                    <button class="btn btn-sm btn-outline-primary"
                                                            type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#accounts{{ $supplier->id }}">
                                                        <i class="bi bi-eye"></i> View Bank Accounts ({{ $supplier->accounts->count() }})
                                                    </button>
                                                </div>
                                                @endif
                                            </td>
                                            <td data-label="Primary Account">
                                                @php
                                                    $primary = $supplier->accounts->where('is_primary', true)->first();
                                                @endphp
                                                @if($primary)
                                                    <strong>{{ $primary->bank_name }}</strong><br>
                                                    <small class="text-muted">{{ $primary->account_number }}</small>
                                                @else
                                                    <span class="text-muted">No primary account</span>
                                                @endif
                                            </td>
                                            <td class="text-end action-buttons" data-label="Actions">
                                                @if(canUser("edit_banking_supplier"))
                                                <button type="button" class="btn btn-sm btn-outline-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editBankingSupplier{{ $supplier->id }}" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                @endif
                                                @if(canUser("add_banking_supplier"))
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#addAccount{{ $supplier->id }}-supplier" title="Add Account">
                                                    <i class="bi bi-plus-circle"></i>
                                                </button>
                                                @endif
                                                @if(canUser("delete_banking_supplier"))
                                                <form action="/banking-supplier/delete/{{ $supplier->id }}"
                                                      method="POST"
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Are you sure you want to delete this banking supplier?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
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

    <!-- Edit Banking Supplier Modals -->
    @if(canUser("edit_banking_supplier"))
    @foreach ($suppliers as $supplier)
    <div class="modal fade" id="editBankingSupplier{{ $supplier->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Banking Supplier</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/banking-supplier/update/{{ $supplier->id }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name"
                                   value="{{ $supplier->name }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="bank_name"
                                   value="{{ $supplier->bank_name }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Account Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="account_number"
                                   value="{{ $supplier->account_number }}" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Branch</label>
                                <input type="text" class="form-control" name="branch"
                                       value="{{ $supplier->branch }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">SWIFT Code</label>
                                <input type="text" class="form-control" name="swift_code"
                                       value="{{ $supplier->swift_code }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Contact</label>
                            <input type="text" class="form-control" name="contact"
                                   value="{{ $supplier->contact }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2">{{ $supplier->address }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3">{{ $supplier->description }}</textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Update Supplier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif

    <!-- Add Bank Account Modals for Each Supplier -->
    @if(canUser("add_banking_supplier"))
    @foreach ($suppliers as $supplier)
    <div class="modal fade" id="addAccount{{ $supplier->id }}-supplier" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Bank Account to {{ $supplier->name }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/banking-supplier/account/store/{{ $supplier->id }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="bank_name" placeholder="Bank name" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="account_number" placeholder="Account number" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Branch</label>
                                <input type="text" class="form-control" name="branch" placeholder="Branch (optional)">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">SWIFT Code</label>
                                <input type="text" class="form-control" name="swift_code" placeholder="SWIFT code (optional)">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact</label>
                                <input type="text" class="form-control" name="contact" placeholder="Phone or email (optional)">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <input type="checkbox" name="is_primary" value="1" class="me-2">
                                    Set as Primary Account
                                </label>
                                <small class="text-muted d-block">Primary account will be used as default for transfers</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2" placeholder="Physical address (optional)"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Additional information (optional)"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="bi bi-save me-1"></i> Save Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <!-- Edit Bank Account Modals -->
    @if(canUser("add_banking_supplier"))
    @foreach($suppliers as $supplier)
        @foreach($supplier->accounts as $account)
        <div class="modal fade" id="editAccount{{ $account->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Bank Account - {{ $supplier->name }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/banking-supplier/account/update/{{ $account->id }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="bank_name" 
                                           value="{{ $account->bank_name }}" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="account_number" 
                                           value="{{ $account->account_number }}" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Branch</label>
                                    <input type="text" class="form-control" name="branch" 
                                           value="{{ $account->branch }}">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">SWIFT Code</label>
                                    <input type="text" class="form-control" name="swift_code" 
                                           value="{{ $account->swift_code }}">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Contact</label>
                                    <input type="text" class="form-control" name="contact" 
                                           value="{{ $account->contact }}">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <input type="checkbox" name="is_primary" value="1" 
                                               {{ $account->is_primary ? 'checked' : '' }} class="me-2">
                                        Set as Primary Account
                                    </label>
                                    <small class="text-muted d-block">Primary account used for transfers</small>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="2">{{ $account->address }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3">{{ $account->description }}</textarea>
                            </div>
                            
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary py-2">
                                    <i class="bi bi-save me-1"></i> Update Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endforeach
    @endif

    @endif

    @if(canUser("add_banking_supplier"))
    <div class="modal fade" id="addBankingSupplier" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Banking Supplier</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/banking-supplier/store" method="post">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" placeholder="Supplier name" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="bank_name" placeholder="Bank name" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="account_number" placeholder="Account number" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Branch</label>
                                <input type="text" class="form-control" name="branch" placeholder="Branch (optional)">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">SWIFT Code</label>
                                <input type="text" class="form-control" name="swift_code" placeholder="SWIFT code (optional)">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact</label>
                                <input type="text" class="form-control" name="contact" placeholder="Phone or email (optional)">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2" placeholder="Physical address (optional)"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Additional information (optional)"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="bi bi-save me-1"></i> Save Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        $(document).ready(function() {
            // Auto-focus first input in modal
            $('#addBankingSupplier').on('shown.bs.modal', function() {
                $(this).find('input:first').focus();
            });
        });
    </script>
</body>
</html>
