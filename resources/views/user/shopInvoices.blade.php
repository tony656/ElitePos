<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Shop Invoices</title>
    @include("links")
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3f37c9;
            --success: #10b981;
            --danger: #ef476f;
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
            min-height: 100vh;
        }

        main { padding: 2rem !important; }

        /* ── Page header ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .page-header-left h4 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .page-header-left p {
            font-size: 0.875rem;
            color: var(--muted);
            margin-top: 0.25rem;
        }

        /* ── Controls ── */
        .controls {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        
        /* Date filter styles */
        .date-filter-form {
            display: flex;
            align-items: center;
        }
        
        .date-filter {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--white);
            padding: 0.375rem 0.75rem;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
        }
        
        .date-input {
            border: none;
            font-size: 0.85rem;
            color: var(--text);
            outline: none;
            width: 120px;
        }
        
        .date-input::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.6;
        }
        
        .date-input::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }
        
        .date-separator {
            color: var(--muted);
            font-size: 0.85rem;
        }
        
        .filter-btn {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 0.4rem 0.75rem;
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .filter-btn:hover {
            background: var(--primary-dark);
        }
        
        .clear-filter {
            color: var(--danger);
            font-size: 0.85rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.4rem 0.5rem;
        }
        
        .clear-filter:hover {
            text-decoration: underline;
        }

        .search-wrap {
            position: relative;
        }

        .search-wrap i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            pointer-events: none;
            font-size: 0.85rem;
        }

        .search-input {
            padding: 0.45rem 0.75rem 0.45rem 2rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            background: var(--white);
            color: var(--text);
            width: 220px;
            transition: var(--transition);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
        }

        .view-toggle {
            display: flex;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
            background: var(--white);
        }

        .view-btn {
            padding: 0.45rem 0.75rem;
            border: none;
            background: transparent;
            cursor: pointer;
            color: var(--muted);
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .view-btn.active {
            background: var(--primary);
            color: var(--white);
        }

        /* ── Summary metrics ── */
        .metrics-row {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .metric-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1rem 1.25rem;
        }

        .metric-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--muted);
            margin-bottom: 0.25rem;
        }

        .metric-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text);
        }

        .metric-value.success { color: var(--success); }
        .metric-value.danger  { color: var(--danger); }

        /* ── Card view ── */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1rem;
        }

        .shop-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            text-decoration: none;
            display: block;
            transition: var(--transition);
        }

        .shop-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(67,97,238,0.1);
        }

        .card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .shop-avatar {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-md);
            background: rgba(67,97,238,0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .debt-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            background: rgba(239,71,111,0.1);
            color: var(--danger);
        }
        
        .paid-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            background: rgba(16,185,129,0.1);
            color: var(--success);
        }

        .shop-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.2rem;
        }

        .shop-location {
            font-size: 0.8rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .card-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 0.75rem 0;
        }

        .card-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
        }

        .stat-label {
            font-size: 0.7rem;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
        }

        .stat-value {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
        }

        .stat-value.success { color: var(--success); }
        .stat-value.danger  { color: var(--danger); }

        .card-footer {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 0.75rem;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--primary);
        }

        /* ── List view ── */
        .shop-list {
            display: flex;
            flex-direction: column;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            background: var(--white);
        }

        .list-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            text-decoration: none;
            border-bottom: 1px solid var(--border);
            transition: var(--transition);
        }

        .list-item:last-child { border-bottom: none; }

        .list-item:hover { background: var(--surface); }

        .list-info { flex: 1; min-width: 0; }

        .list-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 2px;
        }

        .list-location {
            font-size: 0.78rem;
            color: var(--muted);
        }

        .list-stats {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .list-stat-label {
            font-size: 0.7rem;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
        }

        .list-stat-value {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text);
            text-align: right;
        }

        .list-stat-value.success { color: var(--success); }
        .list-stat-value.danger  { color: var(--danger); }

        .list-chevron { color: var(--muted); }

        /* ── Utilities ── */
        .hidden { display: none !important; }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--muted);
        }

        .empty-state i { font-size: 3rem; display: block; margin-bottom: 0.75rem; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            main { padding: 1rem !important; }
            .metrics-row { grid-template-columns: 1fr 1fr; }
            .list-stats { gap: 1rem; }
            .search-input { width: 160px; }
            .controls { flex-direction: column; align-items: stretch; }
            .date-filter { flex-wrap: wrap; justify-content: center; }
            .date-input { width: 100px; }
        }

        @media (max-width: 480px) {
            .metrics-row { grid-template-columns: 1fr; }
            .list-stats { display: none; }
        }
        
        .fully-paid-card {
            border-color: #10b981 !important;
            background: rgba(16, 185, 129, 0.03);
        }
        
        .fully-paid-card:hover {
            border-color: #10b981 !important;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.15) !important;
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger d-flex justify-content-between">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page header -->
            <div class="page-header">
                <div class="page-header-left">
                    <h4><i class="bi bi-shop"></i> Shops with Invoices</h4>
                    <p>Select a shop to view customers with debts</p>
                </div>
                <div class="controls">
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#manualInvoiceModal">
                        <i class="bi bi-plus-circle"></i> Create Manual Invoice
                    </button>
                    <div class="search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" id="shopSearch" class="search-input" placeholder="Search shops…">
                    </div>
                    <form method="GET" action="{{ url('user/shopInvoices') }}" class="date-filter-form">
                        <div class="date-filter">
                            <input type="date" name="start_date" class="date-input" value="{{ $startDate ?? '' }}" title="Start Date">
                            <span class="date-separator">to</span>
                            <input type="date" name="end_date" class="date-input" value="{{ $endDate ?? '' }}" title="End Date">
                            <button type="submit" class="filter-btn">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            @if($startDate || $endDate)
                                <a href="{{ url('user/shopInvoices') }}" class="clear-filter" title="Clear filters">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                    <div class="view-toggle">
                        <button class="view-btn active" id="cardViewBtn" onclick="setView('card')" title="Card view">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </button>
                        <button class="view-btn" id="listViewBtn" onclick="setView('list')" title="List view">
                            <i class="bi bi-list-ul"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary metrics -->
            <div class="metrics-row">
                <div class="metric-card">
                    <div class="metric-label">Total shops</div>
                    <div class="metric-value" id="metricShops">{{ count($shopsWithInvoices) }}</div>
                </div>
                <div class="metric-card">
                    <div class="metric-label">Total invoices</div>
                    <div class="metric-value success" id="metricInvoices">{{ $shopsWithInvoices->sum('invoice_count') }}</div>
                </div>
                <div class="metric-card">
                    <div class="metric-label">Total debts</div>
                    <div class="metric-value danger" id="metricDebts">{{ $shopsWithInvoices->sum('debt_count') }}</div>
                </div>
            </div>

            <!-- Card view -->
            <div id="cardView" class="card-grid">
                @forelse($shopsWithInvoices as $shop)
                    <a href="{{ url('user/shopDebtors/'.$shop['id']) }}" class="shop-card {{ $shop['debt_count'] == 0 ? 'fully-paid-card' : '' }}" data-name="{{ strtolower($shop['name']) }} {{ strtolower($shop['location']) }}">
                        <div class="card-top">
                            <div class="shop-avatar">
                                {{ strtoupper(substr($shop['name'], 0, 1)) }}{{ strtoupper(substr(strstr($shop['name'], ' '), 1, 1)) }}
                            </div>
                            @if($shop['debt_count'] > 0)
                                <span class="debt-badge">{{ $shop['debt_count'] }} debts</span>
                            @else
                                <span class="paid-badge"><i class="bi bi-check-circle"></i> All Paid</span>
                            @endif
                        </div>
                        <div class="shop-name">{{ $shop['name'] }}</div>
                        <div class="shop-location">
                            <i class="bi bi-geo-alt"></i> {{ $shop['location'] }}
                        </div>
                        <hr class="card-divider">
                        <div class="card-stats">
                            <div>
                                <div class="stat-label">Invoices</div>
                                <div class="stat-value">{{ $shop['invoice_count'] }}</div>
                            </div>
                            <div>
                                <div class="stat-label">Total</div>
                                <div class="stat-value success">{{ number_format($shop['total_amount']) }}</div>
                            </div>
                            <div>
                                <div class="stat-label">Debts</div>
                                <div class="stat-value {{ $shop['debt_count'] > 0 ? 'danger' : 'success' }}">{{ $shop['debt_count'] }}</div>
                            </div>
                        </div>
                        <div class="card-footer">
                            View details <i class="bi bi-arrow-right"></i>
                        </div>
                    </a>
                @empty
                    <div class="col-12 empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>No shops with invoices found</p>
                    </div>
                @endforelse
            </div>

            <!-- List view -->
            <div id="listView" class="shop-list hidden">
                @forelse($shopsWithInvoices as $shop)
                    <a href="{{ url('user/shopDebtors/'.$shop['id']) }}" class="list-item" data-name="{{ strtolower($shop['name']) }} {{ strtolower($shop['location']) }}">
                        <div class="shop-avatar">
                            {{ strtoupper(substr($shop['name'], 0, 1)) }}{{ strtoupper(substr(strstr($shop['name'], ' '), 1, 1)) }}
                        </div>
                        <div class="list-info">
                            <div class="list-name">{{ $shop['name'] }}</div>
                            <div class="list-location"><i class="bi bi-geo-alt"></i> {{ $shop['location'] }}</div>
                        </div>
                        <div class="list-stats">
                            <div>
                                <div class="list-stat-label">Invoices</div>
                                <div class="list-stat-value">{{ $shop['invoice_count'] }}</div>
                            </div>
                            <div>
                                <div class="list-stat-label">Total</div>
                                <div class="list-stat-value success">{{ number_format($shop['total_amount']) }}</div>
                            </div>
                            <div>
                                <div class="list-stat-label">Debts</div>
                                <div class="list-stat-value danger">{{ $shop['debt_count'] }}</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right list-chevron"></i>
                    </a>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>No shops with invoices found</p>
                    </div>
                @endforelse
            </div>

            <!-- Empty search result -->
            <div id="noResults" class="empty-state hidden">
                <i class="bi bi-search"></i>
                <p>No shops match your search.</p>
            </div>

        </main>
    </div>
</div>

<script>
    let currentView = 'card';

    function setView(v) {
        currentView = v;
        document.getElementById('cardView').classList.toggle('hidden', v !== 'card');
        document.getElementById('listView').classList.toggle('hidden', v !== 'list');
        document.getElementById('cardViewBtn').classList.toggle('active', v === 'card');
        document.getElementById('listViewBtn').classList.toggle('active', v === 'list');
        localStorage.setItem('shopInvoicesView', v);
    }

    document.getElementById('shopSearch').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        const cardItems = document.querySelectorAll('#cardView .shop-card');
        const listItems = document.querySelectorAll('#listView .list-item');
        let visible = 0;

        function filterItems(items) {
            items.forEach(el => {
                const match = el.dataset.name.includes(q);
                el.classList.toggle('hidden', !match);
                if (match) visible++;
            });
        }

        filterItems(cardItems);
        filterItems(listItems);
        document.getElementById('noResults').classList.toggle('hidden', visible > 0);
    });

    // Restore preferred view from localStorage
    const saved = localStorage.getItem('shopInvoicesView');
    if (saved) setView(saved);
</script>
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
                                    <option value="{{ $shop->id }}">{{ $shop->name }} - {{ $shop->location }}</option>
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