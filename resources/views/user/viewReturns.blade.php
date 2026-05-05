<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Returns</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #004E89;
            --secondary-color: #1a659e;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --text-dark: #343a40;
            --text-light: #6c757d;
        }
        body { min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; zoom: 1.1; }
        .main-container { max-width: 1900px; margin: 0 auto; padding: 0.75rem; }
        .page-header { background: white; padding: 0.9rem 1.2rem; border-radius: 8px; margin-bottom: 0.75rem; box-shadow: 0 2px 8px rgba(0,0,0,0.06); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .page-title { font-size: 1.4rem; font-weight: 700; color: var(--danger-color); display: flex; align-items: center; gap: 0.4rem; }
        .returns-container { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); height: calc(100vh - 180px); display: flex; flex-direction: column; overflow: hidden; }
        .returns-header { width: 100%; padding: 1rem; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; background: #fff5f5; }
        .returns-title { font-size: 1.2rem; font-weight: 600; color: var(--danger-color); display: flex; align-items: center; gap: 0.5rem; }
        .returns-body { flex: 1; overflow-y: auto; padding: 0; }
        .table-container { height: 100%; overflow-y: auto; }
        .table { margin-bottom: 0; font-size: 0.85rem; }
        .table thead th { padding: 0.5rem; font-weight: 600; border-bottom: 2px solid #e9ecef; background: #f8f9fa; position: sticky; top: 0; z-index: 10; }
        .table tbody td { padding: 0.5rem; border-bottom: 1px solid #e9ecef; vertical-align: middle; }
        .table tbody tr:hover { background: #fff5f5; }
        .badge { font-size: 0.75rem; padding: 0.3rem 0.5rem; }
        .btn { font-size: 0.85rem; }
        .alert { border: none; border-radius: 8px; margin-bottom: 0.75rem; padding: 0.75rem 1rem; font-size: 0.9rem; }
        .alert-success { background: rgba(40,167,69,0.1); border-left: 4px solid var(--success-color); color: #155724; }
        .alert-danger { background: rgba(220,53,69,0.1); border-left: 4px solid var(--danger-color); color: #721c24; }
        .return-badge { background: var(--danger-color); color: white; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #d0d0d0; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="row">
        @include('user/sidenav')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="main-container">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-title">
                        <i class="bi bi-arrow-return-left"></i>
                        View Returns
                    </div>
                    <a href="{{ url('user/make-return') }}" class="btn btn-danger">
                        <i class="bi bi-plus-circle me-1"></i> New Return
                    </a>
                </div>

                <!-- Alerts -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Returns Table -->
                <div class="returns-container">
                    <div class="returns-header">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleTableData()" id="toggleBtn">
                                <i class="bi bi-plus-lg" id="toggleIcon"></i> <span id="toggleText">Show</span>
                            </button>
                            <div class="returns-title">
                                <i class="bi bi-arrow-return-left"></i> Returns List Only
                            </div>
                        </div>
                        <form method="GET" action="{{ url('user/view-returns') }}" class="d-flex align-items-center gap-2 flex-wrap">
                            <label for="date" class="form-label mb-0">Date:</label>
                            <input type="date" name="date" onchange="this.form.submit()" class="form-control" id="date" value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}">
                            
                            <label for="from_date" class="form-label mb-0">From:</label>
                            <input type="date" name="from_date" onchange="this.form.submit()" class="form-control" id="from_date" value="{{ $fromDate }}" max="{{ date('Y-m-d') }}">
                            
                            <label for="to_date" class="form-label mb-0">To:</label>
                            <input type="date" name="to_date" onchange="this.form.submit()" class="form-control" id="to_date" value="{{ $toDate }}" max="{{ date('Y-m-d') }}">
                            
                            <label for="status" class="form-label mb-0">Status:</label>
                            <select name="status" onchange="this.form.submit()" class="form-control" id="status">
                                <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="Pending" {{ $statusFilter == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Approved" {{ $statusFilter == 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Returned" {{ $statusFilter == 'Returned' ? 'selected' : '' }}>Returned</option>
                            </select>
                        </form>
                    </div>

                    <div class="returns-body" id="returnsBody" style="display: none;">
                        <div class="table-container">
                            @php
                                $totalQty = $products->sum('quantity');
                                $totalValue = $products->sum(function($p){ return ($p->quantity ?? 0) * ($p->price ?? 0); });
                            @endphp
                            
                            <div class="p-3 bg-light border-bottom">
                                <div class="d-flex gap-4">
                                    <div><strong>Total Returns:</strong> {{ number_format($products->count()) }}</div>
                                    <div><strong>Total Quantity:</strong> {{ number_format($totalQty) }}</div>
                                    <div><strong>Total Value:</strong> Tsh {{ number_format($totalValue, 2) }}</div>
                                </div>
                                <div class="mt-2 text-muted small">
                                    <i class="bi bi-info-circle"></i> Returns decrease product quantities in the inventory.
                                </div>
                            </div>

                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Cost</th>
                                        <th>Total</th>
                                        <th>Payment</th>
                                        <th>Supplier</th>
                                        <th>Allocated</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $index => $item)
                                    <tr id="row-{{ $item->productId }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ date('M d, Y', strtotime($item->created_at)) }}</td>
                                        <td><strong>{{ $item->productName }}</strong></td>
                                        <td><span class="text-danger">{{ number_format($item->quantity) }}</span></td>
                                        <td>Tsh. {{ number_format($item->price, 2) }}</td>
                                        <td><strong class="text-danger">Tsh. {{ number_format($item->price * $item->quantity, 2) }}</strong></td>
                                        <td>
                                            @if($item->isDebt == 1)
                                                <span class="badge bg-warning text-dark">Credit</span>
                                            @else
                                                <span class="badge bg-success">Cash</span>
                                            @endif
                                        </td>
                                        <td><i class="bi bi-person"></i> {{ $item->supplier ?? 'Unknown' }}</td>
                                        <td><i class="bi bi-person-check"></i> {{ $item->served_by ?? 'Unknown' }}</td>
                                        <td>
                                            <span class="badge bg-danger">Returned</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">
                                            <i class="bi bi-arrow-return-left fs-4 d-block mb-2"></i>
                                            No returns found for the selected date.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
    // Toggle table data visibility - default hidden
    const STORAGE_KEY = 'returnsTableVisible';
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const saved = localStorage.getItem(STORAGE_KEY);
        const isVisible = saved === 'true';
        updateToggleState(isVisible);
    });
    
    function toggleTableData() {
        const tbody = document.getElementById('returnsBody');
        const isCurrentlyHidden = tbody.style.display === 'none';
        localStorage.setItem(STORAGE_KEY, isCurrentlyHidden ? 'true' : 'false');
        updateToggleState(isCurrentlyHidden);
    }
    
    function updateToggleState(show) {
        const tbody = document.getElementById('returnsBody');
        const icon = document.getElementById('toggleIcon');
        const text = document.getElementById('toggleText');
        
        if (show) {
            tbody.style.display = '';
            icon.className = 'bi bi-dash-lg';
            text.textContent = 'Hide';
        } else {
            tbody.style.display = 'none';
            icon.className = 'bi bi-plus-lg';
            text.textContent = 'Show';
        }
    }
    </script>
</body>
</html>