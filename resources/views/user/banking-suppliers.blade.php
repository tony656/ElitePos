<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Banking Suppliers</title>
    
    @include("links")
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Page-specific styles only - dashboard.css handles layout */
        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color, #e2e8f0);
        }

        .header-left h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary, #0f172a);
            margin: 0;
        }

        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid var(--border-color, #e2e8f0);
            background: var(--bg-primary, #ffffff);
            color: var(--text-primary, #0f172a);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            margin-right: 1rem;
        }

        .back-btn:hover {
            background: var(--bg-secondary, #f8fafc);
            border-color: var(--primary, #2563eb);
            color: var(--primary, #2563eb);
        }

        .empty-state {
            padding: 3rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--text-light, #94a3b8);
            margin-bottom: 1rem;
        }

        .empty-state h5 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-light, #94a3b8);
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-left {
                width: 100%;
                margin-bottom: 1rem;
            }

            .header-left h3 {
                font-size: 1.5rem;
            }
        }
    </style>

    <link href="{{asset("css/dashboard.css")}}" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @include("user/sidenav")

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-left">
                        <a href="#" onclick="history.back()" class="back-btn d-print-none">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                        <h3>Banking Suppliers</h3>
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

                <!-- Banking Suppliers Table -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6>Banking Suppliers Directory</h6>
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
                                                <h5>No Banking Suppliers Found</h5>
                                                <p>Add your first banking supplier to get started</p>
                                                @if(canUser("add_banking_supplier"))
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBankingSupplier">
                                                    <i class="bi bi-plus-lg me-1"></i>
                                                    Add Banking Supplier
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
                                                <form action="/user/banking-supplier/delete/{{ $supplier->id }}"
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
                                        @if($supplier->accounts->count() > 0)
                                        <tr class="collapse-row">
                                            <td colspan="4" class="p-0">
                                                <div class="collapse" id="accounts{{ $supplier->id }}">
                                                    <div class="card card-body bg-light">
                                                        <h6 class="mb-3">Bank Accounts for {{ $supplier->name }}</h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Status</th>
                                                                        <th>Bank Name</th>
                                                                        <th>Account Number</th>
                                                                        <th>Branch</th>
                                                                        <th>SWIFT Code</th>
                                                                        <th>Contact</th>
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($supplier->accounts as $account)
                                                                    <tr>
                                                                        <td>
                                                                            @if($account->is_primary)
                                                                                <span class="badge bg-primary">Primary</span>
                                                                            @else
                                                                                <span class="badge bg-secondary">Additional</span>
                                                                            @endif
                                                                        </td>
                                                                        <td><strong>{{ $account->bank_name }}</strong></td>
                                                                        <td><code>{{ $account->account_number }}</code></td>
                                                                        <td>{{ $account->branch ?? '-' }}</td>
                                                                        <td>{{ $account->swift_code ?? '-' }}</td>
                                                                        <td>{{ $account->contact ?? '-' }}</td>
                                                                        <td>
                                                                            @if(!$account->is_primary && canUser("delete_banking_supplier"))
                                                                            <form action="/user/banking-supplier/account/delete/{{ $account->id }}"
                                                                                  method="POST"
                                                                                  style="display: inline;"
                                                                                  onsubmit="return confirm('Delete this bank account?');">
                                                                                @csrf
                                                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                                                    <i class="bi bi-trash"></i>
                                                                                </button>
                                                                            </form>
                                                                            @endif
                                                                            @if(canUser("add_banking_supplier"))
                                                                            <button type="button"
                                                                                    class="btn btn-sm btn-outline-warning"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#editAccount{{ $account->id }}"
                                                                                    title="Edit">
                                                                                <i class="bi bi-pencil"></i>
                                                                            </button>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
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
                        <form action="/user/banking-supplier/account/update/{{ $account->id }}" method="post">
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
                    <form action="/user/banking-supplier/update/{{ $supplier->id }}" method="post">
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
    <!-- Add Banking Supplier Modal -->
    @if(canUser("add_banking_supplier"))
    <div class="modal fade" id="addBankingSupplier" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Banking Supplier</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/user/banking-supplier/store" method="post">
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
    @endforeach
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
