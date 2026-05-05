<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} - Paid Invoices</title>
    @include("links")
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3f37c9;
            --success: #10b981;
            --success-light: rgba(16,185,129,0.1);
            --danger: #ef476f;
            --warning: #f59e0b;
            --text: #1a1a2e;
            --muted: #6c757d;
            --border: #e5e7eb;
            --surface: #f8f9fa;
            --white: #ffffff;
            --radius-md: 8px;
            --radius-lg: 12px;
            --transition: all 0.2s ease;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--text);
            background: #f3f4f6;
        }

        main { padding: 2rem !important; }

        .breadcrumb-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
        }
        .breadcrumb-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.8rem;
            color: var(--muted);
            text-decoration: none;
        }
        .breadcrumb-link:hover { color: var(--text); }
        .breadcrumb-sep { font-size: 0.8rem; color: var(--border); }
        .breadcrumb-cur { font-size: 0.8rem; color: var(--text); font-weight: 600; }

        .page-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .page-top h4 { font-size: 1.5rem; font-weight: 700; color: var(--text); }
        .page-top p { font-size: 0.875rem; color: var(--muted); margin-top: 0.25rem; }

        .filters-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            margin-bottom: 1.25rem;
        }
        .filters-row {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        .filter-group { flex: 1; min-width: 150px; }
        .filter-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 0.375rem;
            text-transform: uppercase;
        }
        .filter-select, .filter-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            transition: var(--transition);
        }
        .filter-select:focus, .filter-input:focus {
            border-color: var(--primary);
            outline: none;
        }
        .filter-btn {
            padding: 0.5rem 1.25rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }
        .filter-btn:hover { background: var(--primary-dark); }
        .filter-btn-reset {
            padding: 0.5rem 1.25rem;
            background: var(--surface);
            color: var(--text);
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        @media (max-width: 768px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        .stat-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .stat-icon.success { background: var(--success-light); color: var(--success); }
        .stat-icon.primary { background: rgba(67,97,238,0.1); color: var(--primary); }
        .stat-icon.warning { background: rgba(245,158,11,0.1); color: var(--warning); }
        .stat-icon.info { background: rgba(6,182,212,0.1); color: #06b6d4; }
        .stat-content { flex: 1; }
        .stat-label { font-size: 0.75rem; color: var(--muted); text-transform: uppercase; font-weight: 600; }
        .stat-value { font-size: 1.5rem; font-weight: 700; color: var(--text); }

        .data-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }
        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-title { font-size: 1rem; font-weight: 700; color: var(--text); }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--muted);
            background: var(--surface);
            border-bottom: 2px solid var(--border);
        }
        .data-table td {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            border-bottom: 1px solid var(--border);
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover { background: var(--surface); }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.625rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-badge.paid {
            background: var(--success-light);
            color: #065f46;
        }

        .amount {
            font-weight: 600;
            color: var(--success);
        }
        
        .customer-cell { font-weight: 600; }
        .date-cell { color: var(--muted); font-size: 0.8rem; }
        
        .empty-state {
            padding: 3rem;
            text-align: center;
            color: var(--muted);
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--border);
        }

        .shop-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: var(--surface);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--muted);
        }
    </style>
</head>
<body>
    @include("user.sidenav")
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="breadcrumb-row">
            <a href="{{ url('user/home') }}" class="breadcrumb-link"><i class="bi bi-house"></i> Home</a>
            <span class="breadcrumb-sep">/</span>
            <a href="{{ url('user/shopInvoices') }}" class="breadcrumb-link">Shop Invoices</a>
            <span class="breadcrumb-sep">/</span>
            <span class="breadcrumb-cur">Paid Invoices</span>
        </div>

        <div class="page-top">
            <div>
                <h4><i class="bi bi-check-circle text-success me-2"></i>Paid Invoices</h4>
                <p>View all debt payments and their summary</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#manualInvoiceModal">
                    <i class="bi bi-plus-circle"></i> Create Manual Invoice
                </button>
            </div>
        </div>

        <div class="filters-card">
            <form method="GET" action="{{ url('user/paidInvoices') }}">
                <div class="filters-row">
                    <div class="filter-group">
                        <label class="filter-label">Start Date</label>
                        <input type="date" name="start_date" class="filter-input" value="{{ $startDate ?? '' }}">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">End Date</label>
                        <input type="date" name="end_date" class="filter-input" value="{{ $endDate ?? '' }}">
                    </div>
                    <div class="filter-group">
                        <button type="submit" class="filter-btn"><i class="bi bi-filter me-1"></i> Filter</button>
                        <a href="{{ url('user/paidInvoices') }}" class="filter-btn-reset ms-2">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon success"><i class="bi bi-currency-dollar"></i></div>
                <div class="stat-content">
                    <div class="stat-label">Total Paid</div>
                    <div class="stat-value">Tsh {{ number_format($totalPaid) }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon primary"><i class="bi bi-receipt"></i></div>
                <div class="stat-content">
                    <div class="stat-label">Payments Made</div>
                    <div class="stat-value">{{ $paymentCount }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon warning"><i class="bi bi-file-earmark-text"></i></div>
                <div class="stat-content">
                    <div class="stat-label">Invoices Cleared</div>
                    <div class="stat-value">{{ $uniqueInvoices }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon info"><i class="bi bi-people"></i></div>
                <div class="stat-content">
                    <div class="stat-label">Customers</div>
                    <div class="stat-value">{{ $customersWithPayments }}</div>
                </div>
            </div>
        </div>

        <div class="data-card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-list-ul me-2"></i>Payment History</div>
                <span class="shop-badge">{{ getSessionAccountDisplayName() }}</span>
            </div>
            @if($payments->count() > 0)
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Invoice</th>
                            <th>Amount Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td class="date-cell">
                                {{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y h:i A') }}
                            </td>
                            <td class="customer-cell">{{ $payment->cName }}</td>
                            <td>
                                <span class="status-badge paid">
                                    <i class="bi bi-check-circle"></i>
                                    {{ $payment->orderId }}
                                </span>
                            </td>
                            <td class="amount">Tsh {{ number_format($payment->amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <div>No payments found for the selected filters</div>
            </div>
            @endif
        </div>
    </main>
</body>
</html>

    <!-- Manual Invoice Modal -->
    <div class="modal fade" id="manualInvoiceModal" tabindex="-1" aria-labelledby="manualInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--primary); color: white;">
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