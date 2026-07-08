<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Shop Debtors</title>
    @include("links")
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3f37c9;
            --success: #10b981;
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
            min-height: 100vh;
        }

        main { padding: 2rem !important; }

        /* ── Back link ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.875rem;
            color: var(--muted);
            text-decoration: none;
            margin-bottom: 1.25rem;
            transition: var(--transition);
        }
        .back-link:hover { color: var(--text); }

        /* ── Page header ── */
        .page-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .page-top h4 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text);
        }
        .page-top p {
            font-size: 0.875rem;
            color: var(--muted);
            margin-top: 0.2rem;
        }

        /* ── Search ── */
        .search-wrap { position: relative; }
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

        /* ── Shop banner ── */
        .shop-banner {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .shop-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            background: rgba(67,97,238,0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.25rem;
        }
        .shop-banner-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
        }
        .shop-banner-loc {
            font-size: 0.8rem;
            color: var(--muted);
        }

        /* ── Metrics ── */
        .metrics-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }
        .metric {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 0.875rem 1rem;
        }
        .metric-label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--muted);
            margin-bottom: 0.25rem;
        }
        .metric-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
        }
        .metric-value.danger { color: var(--danger); }
        .metric-value.success { color: var(--success); }

        /* ── Table card ── */
        .table-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .table { margin-bottom: 0; }

        .table thead th {
            background: var(--surface);
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border);
            border-top: none;
            padding: 0.75rem 1rem;
            white-space: nowrap;
            cursor: pointer;
            user-select: none;
        }

        .table thead th:hover { color: var(--text); }

        .table tbody td {
            padding: 0.875rem 1rem;
            border-color: var(--border);
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover td { background: var(--surface); }

        /* ── Customer cell ── */
        .customer-cell {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }
        .customer-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(67,97,238,0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        .customer-name {
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* ── Badges & values ── */
        .debt-amount {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--danger);
        }
        .orders-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            background: rgba(245,158,11,0.1);
            color: #92400e;
        }
        .date-text {
            font-size: 0.8rem;
            color: var(--muted);
        }

        /* ── Action button ── */
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: var(--radius-md);
            background: rgba(67,97,238,0.1);
            color: var(--primary);
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }
        .btn-action:hover {
            background: var(--primary);
            color: var(--white);
        }

        /* ── Sort indicator ── */
        .sort-icon { font-size: 0.65rem; opacity: 0.5; }
        .sort-icon.active { opacity: 1; color: var(--primary); }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--muted);
        }
        .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; }

        /* ── Responsive ── */
        @media (max-width: 992px) {
            .metrics-row { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            main { padding: 1rem !important; }
            .search-input { width: 160px; }
            .table thead th:nth-child(4),
            .table tbody td:nth-child(4) { display: none; }
        }
        @media (max-width: 576px) {
            .metrics-row { grid-template-columns: 1fr 1fr; }
            .table thead th:nth-child(5),
            .table tbody td:nth-child(5),
            .table thead th:nth-child(6),
            .table tbody td:nth-child(6) { display: none; }
        }
        
        .fully-paid-row {
            background-color: rgba(16, 185, 129, 0.05) !important;
        }
        
        .badge.bg-success {
            background-color: #10b981 !important;
        }
        
        .badge.bg-warning {
            background-color: #f59e0b !important;
        }
    </style>
</head>
<body>
        @include("sidenav")

        <main class="main-content">

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

            <!-- Back link -->
            <a href="{{ url('shopInvoices') }}" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to shops
            </a>

            <!-- Page top -->
            <div class="page-top">
                <div>
                    <h4><i class="bi bi-people"></i> Customers with debts</h4>
                    <p>Outstanding debts — {{ $shop->name }}</p>
                </div>
                <div class="search-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" id="debtorSearch" class="search-input" placeholder="Search customers…">
                </div>
            </div>

            <!-- Shop banner -->
            <div class="shop-banner">
                <div class="shop-icon"><i class="bi bi-building"></i></div>
                <div>
                    <div class="shop-banner-name">{{ $shop->name }}</div>
                    <div class="shop-banner-loc"><i class="bi bi-geo-alt"></i> {{ $shop->location }}</div>
                </div>
            </div>

            <!-- Metrics -->
            <div class="metrics-row">
                <div class="metric">
                    <div class="metric-label">Customers</div>
                    <div class="metric-value" id="metricCust">{{ $debtors->count() }}</div>
                </div>
                <div class="metric">
                    <div class="metric-label">Total debt</div>
                    <div class="metric-value danger" id="metricDebt">{{ number_format($debtors->sum('total_debt')) }}</div>
                </div>
                <div class="metric">
                    <div class="metric-label">Total paid</div>
                    <div class="metric-value success" id="metricPaid">{{ number_format($debtors->sum('total_paid')) }}</div>
                </div>
                <div class="metric">
                    <div class="metric-label">Remaining</div>
                    <div class="metric-value" id="metricRemaining">{{ number_format($debtors->sum('remaining')) }}</div>
                </div>
            </div>

            <!-- Debtors table -->
            <div class="table-card">
                <div class="table-responsive">
                    <table class="table" id="debtorTable">
                        <thead>
                            <tr>
                                <th onclick="sortTable(0)">Customer <span class="sort-icon bi bi-chevron-expand"></span></th>
                                <th>Phone</th>
                                <th onclick="sortTable(2)">Total <span class="sort-icon bi bi-chevron-expand"></span></th>
                                <th onclick="sortTable(3)">Paid <span class="sort-icon bi bi-chevron-expand"></span></th>
                                <th onclick="sortTable(4)">Remaining <span class="sort-icon bi bi-chevron-expand"></span></th>
                                <th onclick="sortTable(5)">Status <span class="sort-icon bi bi-chevron-expand"></span></th>
                                <th onclick="sortTable(6)">Last activity <span class="sort-icon bi bi-chevron-expand"></span></th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($debtors as $debtor)
                                <tr class="debtor-row {{ $debtor->is_fully_paid ? 'fully-paid-row' : '' }}" data-search="{{ strtolower($debtor->cName) }} {{ strtolower($debtor->cPhone ?? '') }}">
                                    <td>
                                        <div class="customer-cell">
                                            <div class="customer-avatar">
                                                {{ strtoupper(substr($debtor->cName, 0, 1)) }}{{ strtoupper(substr(strstr($debtor->cName, ' '), 1, 1)) }}
                                            </div>
                                            <span class="customer-name">{{ $debtor->cName }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $debtor->cPhone ?? 'N/A' }}</td>
                                    <td>
                                        <span class="debt-amount">{{ number_format($debtor->total_debt) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-success fw-bold">{{ number_format($debtor->total_paid) }}</span>
                                    </td>
                                    <td>
                                        <span class="{{ $debtor->remaining > 0 ? 'debt-amount' : 'text-success fw-bold' }}">{{ number_format($debtor->remaining) }}</span>
                                    </td>
                                    <td>
                                        @if($debtor->is_fully_paid)
                                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Fully Paid</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ $debtor->paid_invoices }}/{{ $debtor->order_count }} paid</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="date-text">{{ $debtor->last_order_date ? \Carbon\Carbon::parse($debtor->last_order_date)->format('M d, Y') : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <form action="{{ url('customerDebtProducts') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="customer" value="{{ $debtor->cName }}">
                                            <input type="hidden" name="shop" value="{{ $shop->id }}">
                                            <button type="submit" class="btn-action">
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="bi bi-check-circle text-success"></i>
                                            <p>No customers with debts for this shop</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

<script>
    document.getElementById('debtorSearch').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.debtor-row').forEach(row => {
            row.style.display = row.dataset.search.includes(q) ? '' : 'none';
        });
    });

    let sortDir = [null, null, 'desc', null, null];

    function sortTable(col) {
        const tbody = document.querySelector('#debtorTable tbody');
        const rows = Array.from(tbody.querySelectorAll('.debtor-row'));
        const dir = sortDir[col] === 'asc' ? 'desc' : 'asc';
        sortDir = sortDir.map(() => null);
        sortDir[col] = dir;

        rows.sort((a, b) => {
            const av = a.cells[col].innerText.trim().replace(/,/g, '');
            const bv = b.cells[col].innerText.trim().replace(/,/g, '');
            const an = parseFloat(av), bn = parseFloat(bv);
            const cmp = isNaN(an) ? av.localeCompare(bv) : an - bn;
            return dir === 'asc' ? cmp : -cmp;
        });

        rows.forEach(r => tbody.appendChild(r));

        document.querySelectorAll('thead th .sort-icon').forEach((icon, i) => {
            icon.className = 'sort-icon bi ' + (i === col
                ? (dir === 'asc' ? 'bi-chevron-up active' : 'bi-chevron-down active')
                : 'bi-chevron-expand');
        });
    }
</script>
@include('footer')

</body>
</html>