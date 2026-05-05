<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - All Invoices</title>
    @include("links")
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #f0f4ff;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --danger-color: #ef476f;
            --warning-color: #ffd166;
            --light-bg: #f8f9fa;
            --dark-text: #1a1a2e;
            --light-text: #6c757d;
            --border-color: #e5e7eb;
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--dark-text);
            min-height: 100vh;
        }
        
        main {
            padding: 2rem !important;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            margin-bottom: 2.5rem;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.15);
        }
        
        .page-header h4 {
            font-size: 1.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .shop-selector {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
        }
        
        .shop-selector label {
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 0.5rem;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: var(--primary-light);
            color: var(--primary-color);
            font-weight: 700;
            border: none;
            padding: 1rem;
            text-transform: uppercase;
            font-size: 0.85rem;
        }
        
        .table tbody td {
            padding: 1rem;
            border-color: var(--border-color);
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: var(--light-bg);
        }
        
        .badge {
            font-weight: 600;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
        
        .badge-success {
            background-color: #10b981;
        }
        
        .badge-danger {
            background-color: var(--danger-color);
        }
        
        .badge-warning {
            background-color: var(--warning-color);
            color: #000;
        }
        
        .shop-info {
            background: var(--primary-light);
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            border: 2px solid var(--primary-color);
        }
        
        .shop-info h5 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .shop-info p {
            margin-bottom: 0.25rem;
            color: var(--dark-text);
        }
        
        @media (max-width: 768px) {
            main {
                padding: 1rem !important;
            }
            
            .shop-selector .form-select {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @include("user/sidenav")
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
                @if(session('success'))
                <div class="alert alert-success d-flex justify-content-between">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger d-flex justify-content-between">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                <div class="page-header">
                    <h4>
                        <i class="bi bi-receipt"></i> All Shops Invoices
                    </h4>
                    <p class="mb-0 mt-2">View invoices from all shops</p>
                    <button type="button" class="btn btn-light mt-2" data-bs-toggle="modal" data-bs-target="#manualInvoiceModal">
                        <i class="bi bi-plus-circle"></i> Create Manual Invoice
                    </button>
                </div>
                
                <div class="shop-selector">
                    <form action="allInvoices" method="get">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Select Shop</label>
                                <select name="shop" class="form-select" onchange="this.form.submit()">
                                    @foreach($shops as $shop)
                                        <option value="{{ $shop->id }}" {{ $selectedShop == $shop->id ? 'selected' : '' }}>
                                            {{ $shop->name }} - {{ $shop->location }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                
                @if($shopDetails)
                <div class="shop-info">
                    <h5><i class="bi bi-building"></i> {{ $shopDetails->name }}</h5>
                    <p><strong>Location:</strong> {{ $shopDetails->location }}</p>
                    <p><strong>Total Invoices:</strong> {{ $invoices->count() }}</p>
                </div>
                @endif
                
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Customer</th>
                                        <th>Phone</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Served By</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->orderName }}</td>
                                            <td>{{ $invoice->cName ?? 'N/A' }}</td>
                                            <td>{{ $invoice->cPhone ?? 'N/A' }}</td>
                                            <td>{{ number_format($invoice->totalPrice ?? 0) }}</td>
                                            <td>
                                                @if($invoice->status == 'Paid')
                                                    <span class="badge badge-success">Paid</span>
                                                @elseif($invoice->status == 'Debt')
                                                    <span class="badge badge-danger">Debt</span>
                                                @elseif($invoice->status == 'Partial')
                                                    <span class="badge badge-warning">Partial</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $invoice->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $invoice->served_by ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox fs-1"></i>
                                                    <p class="mt-2">No invoices found for this shop</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

    <!-- Manual Invoice Modal -->
    <div class="modal fade" id="manualInvoiceModal" tabindex="-1" aria-labelledby="manualInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--primary-color); color: white;">
                    <h5 class="modal-title" id="manualInvoiceModalLabel">
                        <i class="bi bi-receipt"></i> Create Manual Invoice
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('user/createManualInvoice') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Shop/Account Selector -->
                        <div class="mb-3">
                            <label for="account" class="form-label fw-semibold">Shop</label>
                            <select class="form-select" id="shopSelect" name="account" required>
                                <option value="">Select a shop...</option>
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}">{{ $shop->id }} - {{ $shop->location }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Customer Search -->
                        <div class="mb-3">
                            <label for="customerSearch" class="form-label fw-semibold">Customer</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" id="customerSearch"
                                       placeholder="Search customer by name or phone..."
                                       name="customer_search" autocomplete="off" required>
                                <input type="hidden" name="customer_id" id="customerId">
                                <input type="hidden" name="customer_name" id="customerNameHidden">
                            </div>
                            <div id="customerSearchResults" class="list-group position-absolute w-100 mt-1"
                                 style="max-height: 200px; overflow-y: auto; z-index: 1000; display: none;"></div>
                            <small class="text-muted" id="selectedCustomerName"></small>
                        </div>

                        <!-- Invoice Date -->
                        <div class="mb-3">
                            <label for="invoiceDate" class="form-label fw-semibold">Invoice Date</label>
                            <input type="date" class="form-control" id="invoiceDate" 
                                   name="invoice_date" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Amount -->
                        <div class="mb-3">
                            <label for="invoiceAmount" class="form-label fw-semibold">Amount (TZS)</label>
                            <div class="input-group">
                                <span class="input-group-text">Tsh</span>
                                <input type="number" class="form-control" id="invoiceAmount" 
                                       name="amount" min="0" step="0.01" placeholder="0.00" required>
                            </div>
                        </div>

                        <!-- Notes (Optional) -->
                        <div class="mb-3">
                            <label for="invoiceNotes" class="form-label fw-semibold">Notes (Optional)</label>
                            <textarea class="form-control" id="invoiceNotes" name="notes" 
                                      rows="2" placeholder="Add any additional notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Create Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Customer search functionality for manual invoice modal
        document.getElementById('customerSearch').addEventListener('input', function() {
            const query = this.value.trim();
            const resultsDiv = document.getElementById('customerSearchResults');
            const shopSelect = document.getElementById('shopSelect');
            const selectedShop = shopSelect ? shopSelect.value : '';
            
            if (query.length < 2) {
                resultsDiv.style.display = 'none';
                return;
            }
            
            if (!selectedShop) {
                resultsDiv.innerHTML = '<div class="list-group-item text-muted">Please select a shop first</div>';
                resultsDiv.style.display = 'block';
                return;
            }

            fetch(`{{ url('user/searchCustomers') }}?query=${encodeURIComponent(query)}&account=${encodeURIComponent(selectedShop)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        resultsDiv.innerHTML = '<div class="list-group-item text-muted">No customers found</div>';
                        resultsDiv.style.display = 'block';
                    } else {
                        resultsDiv.innerHTML = data.map(customer => `
                            <div class="list-group-item list-group-item-action"
                                 onclick="selectCustomer(${customer.id}, '${customer.name.replace(/'/g, "\\'")}', ${customer.limits || 0})">
                                <div class="d-flex justify-content-between">
                                    <strong>${customer.name}</strong>
                                    <small class="text-muted">Limit: ${Number(customer.limits || 0).toLocaleString()}</small>
                                </div>
                                <small class="text-muted">${customer.phone || 'No phone'}</small>
                            </div>
                        `).join('');
                        resultsDiv.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultsDiv.innerHTML = '<div class="list-group-item text-danger">Error loading customers</div>';
                    resultsDiv.style.display = 'block';
                });
        });

        function selectCustomer(id, name, limit) {
            document.getElementById('customerSearch').value = name;
            document.getElementById('customerId').value = id;
            document.getElementById('customerNameHidden').value = name;
            document.getElementById('selectedCustomerName').textContent = `Selected: ${name}`;
            document.getElementById('customerSearchResults').style.display = 'none';
        }

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            const searchInput = document.getElementById('customerSearch');
            const resultsDiv = document.getElementById('customerSearchResults');
            if (searchInput && resultsDiv && !searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
                resultsDiv.style.display = 'none';
            }
        });
    </script>