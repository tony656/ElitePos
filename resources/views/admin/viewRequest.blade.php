<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Requested Items</title>
    @include("links")
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --danger-color: #f72585;
            --warning-color: #f8961e;
            --success-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --dark-text: #2b2d42;
            --light-text: #8d99ae;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-text);
        }
        
        .dashboard-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1rem 2rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
        }
        
        .stat-card.out-of-stock {
            border-left-color: var(--danger-color);
        }
        
        .stat-card.expired {
            border-left-color: var(--warning-color);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }
        
        .stat-icon.out-of-stock {
            background-color: rgba(247, 37, 133, 0.1);
            color: var(--danger-color);
        }
        
        .stat-icon.expired {
            background-color: rgba(248, 150, 30, 0.1);
            color: var(--warning-color);
        }
        
        .product-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .product-table thead {
            background-color: var(--primary-color);
            color: white;
        }
        
        .product-table th {
            font-weight: 500;
            padding: 1rem;
        }
        
        .product-table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }
        
        .badge-category {
            background-color: #e9ecef;
            color: var(--dark-text);
            font-weight: 500;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
        }
        
        .btn-view {
            background-color: var(--primary-color);
            color: white;
            border-radius: 8px;
            padding: 0.375rem 0.75rem;
            transition: all 0.3s ease;
        }
        
        .btn-view:hover {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-delete {
            background-color: #f8f9fa;
            color: var(--danger-color);
            border-radius: 8px;
            padding: 0.375rem 0.75rem;
            transition: all 0.3s ease;
            border: 1px solid #f8f9fa;
        }
        
        .btn-delete:hover {
            background-color: rgba(247, 37, 133, 0.1);
            border-color: rgba(247, 37, 133, 0.2);
        }
        
        .search-box {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .search-box:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }
        
        .quantity-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 6px;
        }
        
        .quantity-low {
            background-color: var(--danger-color);
        }
        
        .quantity-medium {
            background-color: var(--warning-color);
        }
        
        .quantity-high {
            background-color: #38b000;
        }
        
        /* Modal Styles */
        .modal-request-item {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .modal-request-item:last-child {
            border-bottom: none;
        }
        
        .request-total {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border-left: 4px solid var(--primary-color);
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: rgba(248, 150, 30, 0.1);
            color: var(--warning-color);
        }
        
        .status-approved {
            background-color: rgba(76, 201, 240, 0.1);
            color: var(--success-color);
        }
        
        .status-rejected {
            background-color: rgba(247, 37, 133, 0.1);
            color: var(--danger-color);
        }
        
        .status-submitted {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }
        
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 1rem;
            }
            
            .product-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    

<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3 bg-light">

        <div class="dashboard-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0">
                    <a href="#" onclick="history.back()" class="text-decoration-none text-dark">
                        <i class="bi bi-chevron-left"></i>          
                        Requested Items
                    </a>
                </h4>
            </div>
            <div>
                <select id="monthFilter" class="form-select form-select-sm" style="width: auto;">
                    <option value="all">All Months</option>
                    @php
                        $months = [];
                        foreach($groupedRequests as $requestId => $items) {
                            $month = date('F Y', strtotime($items[0]->created_at ?? now()));
                            $months[$month] = $month;
                        }
                    @endphp
                    @foreach($months as $month)
                        <option value="{{ $month }}">{{ $month }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Total Products Card -->
            <div class="col-md-4">
                <div class="stat-card h-100 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Requests</h6>
                            <h3 class="mb-0">{{ number_format($totalRequest) }}</h2>
                        </div>
                        <div class="stat-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 10L12 5L4 10L12 15L20 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M20 14L12 19L4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Out of Stock Card -->
            <div class="col-md-4">
                <div class="stat-card out-of-stock h-100 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Pending Requests</h6>
                            <h3 class="mb-0">{{ number_format($totalPednding) }}</h2>
                        </div>
                        <div class="stat-icon out-of-stock">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M13.0618 4.4295C12.6211 3.54786 11.3635 3.54786 10.9228 4.4295L3.88996 18.5006C3.49244 19.2959 4.07057 20.2317 4.95945 20.2317H19.0252C19.914 20.2317 20.4922 19.2959 20.0947 18.5006L13.0618 4.4295ZM9.34184 3.6387C10.4339 1.45376 13.5507 1.45377 14.6428 3.63871L21.6756 17.7098C22.6608 19.6809 21.228 22 19.0252 22H4.95945C2.75657 22 1.32382 19.6809 2.30898 17.7098L9.34184 3.6387Z"></path>
                                <path d="M12 8V13" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"></path>
                                <path d="M12 16L12 16.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Expired Card -->
            <div class="col-md-4">
                <div class="stat-card expired h-100 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Submitted</h6>
                            <h3 class="mb-0">{{ number_format($totalSub) }}</h2>
                        </div>
                        <div class="stat-icon expired">
                            <svg width="24" height="24" viewBox="0 0 48 48" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.2,31.9h0a2,2,0,0,0-.9-2.9A11.8,11.8,0,0,1,6.1,16.8,12,12,0,0,1,16.9,6a12.1,12.1,0,0,1,11.2,5.6,2.3,2.3,0,0,0,2.3.9h0a2,2,0,0,0,1.1-3,15.8,15.8,0,0,0-15-7.4,16,16,0,0,0-4.8,30.6A2,2,0,0,0,14.2,31.9Z"></path>
                                <path d="M16.5,11.5v5h-5a2,2,0,0,0,0,4h9v-9a2,2,0,0,0-4,0Z"></path>
                                <path d="M45.7,43l-15-26a2,2,0,0,0-3.4,0l-15,26A2,2,0,0,0,14,46H44A2,2,0,0,0,45.7,43ZM29,42a2,2,0,1,1,2-2A2,2,0,0,1,29,42Zm2-8a2,2,0,0,1-4,0V26a2,2,0,0,1,4,0Z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 border-0">
            <div class="card-body p-3 d-flex">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="search" class="form-control search-box border-start-0" id="search-input" placeholder="Search requests...">
                </div>
            </div>
        </div>

        <div class="card product-table">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Items Count</th>
                            <th>Total Quantity</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <!-- In your viewRequest.blade.php, replace the table body section -->
<tbody id="request-table-body">
    @if(empty($groupedRequests))
    <tr>
        <td colspan="8" class="text-center py-4">
            <div class="d-flex flex-column align-items-center">
                <i class="bi bi-box-seam fs-1 text-muted mb-2"></i>
                <h5 class="text-muted">No requests found</h5>
                <p class="text-muted">No requests have been made yet</p>
            </div>
        </td>
    </tr>
    @else
        @php $index = 1; @endphp
        @foreach ($groupedRequests as $requestId => $items)
            @php
                $totalQuantity = 0;
                $totalPrice = 0;
                $requestDate = $items[0]->created_at ?? now();
                $monthYear = date('F Y', strtotime($requestDate));
                
                // Get supplier name (use the first item's supplier or account)
                $supplierName = $items[0]->supplierName ?? $items[0]->account ?? 'N/A';
                
                foreach($items as $item) {
                    $totalQuantity += $item->quantity;
                    $totalPrice += ($item->quantity * $item->price);
                }
                
                // Get overall status for this request
                $statuses = [];
                foreach($items as $item) {
                    $statuses[] = $item->status;
                }
                $statuses = array_unique($statuses);
                
                if (count($statuses) === 1) {
                    $overallStatus = $statuses[0];
                } else {
                    if (in_array('Pending', $statuses)) {
                        $overallStatus = 'Pending';
                    } elseif (in_array('Approved', $statuses)) {
                        $overallStatus = 'Approved';
                    } else {
                        $overallStatus = 'Mixed';
                    }
                }
            @endphp
            <tr class="request-row" data-month="{{ $monthYear }}">
                <td>{{ $index++ }}</td>
                <td>
                    <div class="d-flex flex-column">
                        <strong>{{ $requestId }}</strong>
                        <small class="text-muted">{{ $supplierName }}</small>
                    </div>
                </td>
                <td>{{ count($items) }}</td>
                <td>{{ number_format($totalQuantity) }}</td>
                <td>Tsh {{ number_format($totalPrice) }}</td>
                <td>
                    @if($overallStatus == 'Pending')
                        <span class="status-badge status-pending">Pending</span>
                    @elseif($overallStatus == 'Approved')
                        <span class="status-badge status-approved">Approved</span>
                    @elseif($overallStatus == 'Rejected')
                        <span class="status-badge status-rejected">Rejected</span>
                    @elseif($overallStatus == 'Submitted')
                        <span class="status-badge status-submitted">Submitted</span>
                    @elseif($overallStatus == 'Out of Stock')
                        <span class="badge bg-warning">Out of Stock</span>
                    @else
                        <span class="badge bg-secondary">{{ $overallStatus }}</span>
                    @endif
                </td>
                <td>{{ date('M d, Y', strtotime($requestDate)) }}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-view me-2 view-request-btn" 
                            data-request-id="{{ $requestId }}"
                            data-items='@json($items)'
                            data-total-quantity="{{ $totalQuantity }}"
                            data-total-price="{{ $totalPrice }}">
                        <i class="bi bi-eye"></i> View Details
                    </button>
                    @if (session('account') == $supplierName)
                        <form method="post" class="d-inline" action="{{ route('admin.request.approveAll') }}">
                            @csrf
                            <input type="hidden" name="requestName" value="{{ $requestId }}">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-check-circle"></i> Approve All
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
    </main>
  </div>
</div>

<!-- Request Details Modal -->
<div class="modal fade" id="requestDetailsModal" tabindex="-1" aria-labelledby="requestDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestDetailsModalLabel">Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6>Request ID: <span id="modal-request-id" class="text-primary"></span></h6>
                        <h6>Supplier: <span id="modal-supplier-name" class="text-muted"></span></h6>
                    </div>
                    <div class="col-md-6">
                        <h6>Date: <span id="modal-request-date" class="text-muted"></span></h6>
                        <h6>Status: <span id="modal-request-status" class="status-badge"></span></h6>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="modal-request-items">
                            <!-- Items will be populated here -->
                        </tbody>
                    </table>
                </div>
                
                <div class="request-total">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Total Items: <span id="modal-total-items" class="fw-bold"></span></h6>
                            <h6>Total Quantity: <span id="modal-total-quantity" class="fw-bold"></span></h6>
                        </div>
                        <div class="col-md-6">
                            <h6>Total Price: <span id="modal-total-price" class="fw-bold"></span></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Search functionality
        $('#search-input').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.request-row').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Month filter functionality
        $('#monthFilter').on('change', function() {
            var selectedMonth = $(this).val();
            
            if (selectedMonth === 'all') {
                $('.request-row').show();
            } else {
                $('.request-row').each(function() {
                    var rowMonth = $(this).data('month');
                    $(this).toggle(rowMonth === selectedMonth);
                });
            }
        });

        // View request details modal
        $('.view-request-btn').on('click', function() {
            var requestId = $(this).data('request-id');
            var items = $(this).data('items');
            var totalQuantity = $(this).data('total-quantity');
            var totalPrice = $(this).data('total-price');
            
            // Set modal header info
            $('#modal-request-id').text(requestId);
            $('#modal-supplier-name').text(items[0].supplierName || 'N/A');
            $('#modal-request-date').text(new Date(items[0].created_at).toLocaleDateString());
            $('#modal-total-items').text(items.length);
            $('#modal-total-quantity').text(totalQuantity.toLocaleString());
            $('#modal-total-price').text('Tsh ' + totalPrice.toLocaleString());
            
            // Clear previous items
            $('#modal-request-items').empty();
            
            // Populate items
            $.each(items, function(index, item) {
                var statusClass = '';
                switch(item.status) {
                    case 'Pending':
                        statusClass = 'status-pending';
                        break;
                    case 'Approved':
                        statusClass = 'status-approved';
                        break;
                    case 'Rejected':
                        statusClass = 'status-rejected';
                        break;
                    case 'Submitted':
                        statusClass = 'status-submitted';
                        break;
                    default:
                        statusClass = 'bg-secondary';
                }
                
                var itemTotal = item.quantity * item.price;
                
                var actionsHtml = '';
if ("{{ session('account') }}" === (item.supplierName || '')) {
    actionsHtml = `
        <form method="post" class="d-inline">
            @csrf
            <input type="hidden" name="requestName" value="${requestId}">
            <input type="hidden" name="product_id" value="${item.productId}">
            <button class="btn btn-sm btn-success btn-view" name="product_id" formaction="approveRequest" value="${item.productId}">
                Approve
            </button>
            <button class="btn btn-sm btn-danger" name="product_id" formaction="rejectRequest" value="${item.productId}">
                Reject
            </button>
            <button class="btn btn-sm btn-warning" name="product_id" formaction="outOfStockRequest" value="${item.productId}">
                Out of Stock
            </button>
        </form>
    `;
}
                
                $('#modal-request-items').append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.productName || 'Unknown Product'}</td>
                        <td>${item.quantity.toLocaleString()}</td>
                        <td>Tsh ${item.price.toLocaleString()}</td>
                        <td>Tsh ${itemTotal.toLocaleString()}</td>
                        <td><span class="status-badge ${statusClass}">${item.status}</span></td>
                        <td>${actionsHtml}</td>
                    </tr>
                `);
            });
            
            // Set overall status
            var statuses = items.map(item => item.status);
            var overallStatus = '';
            if (statuses.every(s => s === 'Pending')) overallStatus = 'Pending';
            else if (statuses.every(s => s === 'Approved')) overallStatus = 'Approved';
            else if (statuses.every(s => s === 'Rejected')) overallStatus = 'Rejected';
            else if (statuses.every(s => s === 'Submitted')) overallStatus = 'Submitted';
            else overallStatus = 'Mixed';
            
            $('#modal-request-status').text(overallStatus).addClass('status-badge ' + 
                (overallStatus === 'Pending' ? 'status-pending' :
                 overallStatus === 'Approved' ? 'status-approved' :
                 overallStatus === 'Rejected' ? 'status-rejected' :
                 overallStatus === 'Submitted' ? 'status-submitted' : 'bg-secondary'));
            
            // Show modal
            var modal = new bootstrap.Modal(document.getElementById('requestDetailsModal'));
            modal.show();
        });
    });
</script>
</body>
</html>