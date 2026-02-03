<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}}</title>
    @include("links")
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --dark-text: #2b2d42;
            --success-color: #4caf50;
            --danger-color: #f44336;
            --warning-color: #ff9800;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: var(--dark-text);
        }
        
        .dashboard-container {
            min-height: 100vh;
        }
        
        .gradient-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(67, 97, 238, 0.3);
        }
        
        .card-style {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-style:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .customer-info-card {
            background-color: white;
            border-left: 4px solid var(--accent-color);
        }
        
        .summary-card {
            background-color: white;
            height: 100%;
        }
        
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem;
        }
        
        .table tbody tr {
            transition: background-color 0.2s ease;
            border-bottom: 1px solid #e9ecef;
        }
        
        .table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }
        
        .btn-action {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            transition: all 0.3s ease;
        }
        
        .btn-back {
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }
        
        .btn-back:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateX(-3px);
        }
        
        .btn-invoice {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-invoice:hover {
            background-color: #3ab7d8;
            color: white;
        }
        
        .btn-submit {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-submit:hover {
            background-color: #3d8b40;
            color: white;
        }
        
        .btn-view {
            background-color: var(--primary-color);
            color: white;
            border: none;
            font-size: 0.875rem;
        }
        
        .btn-view:hover {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-delete {
            background-color: transparent;
            color: var(--danger-color);
            border: 1px solid var(--danger-color);
            font-size: 0.875rem;
        }
        
        .btn-delete:hover {
            background-color: var(--danger-color);
            color: white;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #e0e0e0;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(76, 201, 240, 0.25);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            padding: 1rem 1.25rem;
        }
        
        .badge-discount {
            background-color: #e3f2fd;
            color: var(--primary-color);
        }
        
        .badge-coupon {
            background-color: #e8f5e9;
            color: var(--success-color);
        }
        
        .total-display {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .info-section {
            background: linear-gradient(135deg, #f5f7fb 0%, #e8ebf5 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            display: inline-block;
            font-size: 0.875rem;
        }
        
        .status-suspended {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-completed {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .amount-section {
            background-color: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
        }
        
        .amount-section.credit {
            border-left-color: var(--danger-color);
        }
        
        .amount-section.paid {
            border-left-color: var(--success-color);
        }
        
        .amount-label {
            font-size: 0.875rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        .amount-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 0.5rem;
        }
        
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-text);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary-color);
            display: inline-block;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .customer-header {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.1) 0%, rgba(60, 55, 201, 0.1) 100%);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary-color);
        }
        
        .customer-header h6 {
            margin: 0;
            font-size: 1.1rem;
        }
        
        .customer-header .cName {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        @media (max-width: 768px) {
            .summary-column {
                margin-top: 2rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons form {
                width: 100%;
            }
            
            .action-buttons button {
                width: 100%;
            }
            
            .section-title {
                font-size: 1.1rem;
            }
            
            .amount-value {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    
    
    <div class="dashboard-container container-fluid">
        <div class="row">
            @include("admin/sidenav")

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <!-- Header Actions -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="#" onclick="history.back()" class="btn btn-action btn-back">
                        <i class="bi bi-chevron-left"></i>
                        Back
                    </a>

                    <form action="viewInvoice" method="post">
                        @csrf
                        <button class="btn btn-action btn-invoice" name="invoice" value="{{$orders->orderName ?? null}}">
                            <i class="bi bi-receipt"></i>
                            Generate Invoice
                        </button>
                    </form>
                </div>                           
                
                <!-- Alerts -->
                @if(session('success'))
                <div class="alert alert-success mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                </div>
                @endif
                
                <!-- Customer Header -->
                <div class="customer-header">
                    <h6>
                        <strong>Customer Name:</strong>
                        <span class="cName">{{$orders->cName ?? "N/A"}}</span>
                    </h6>
                </div>
                
                <div class="row mb-4">
                    <!-- Customer Info -->
                    <div class="col-md-8">
                        <div class="card-style customer-info-card p-4 h-100">
                            <div class="section-title">Order Details</div>
                            <div class="row mt-3">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Order Id:</span>
                                        <span class="fw-medium">{{$orders->orderName ?? "N/A"}}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Discount:</span>
                                        <span class="fw-medium badge badge-discount p-2">{{$orders->discount ?? "N/A"}}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Phone / ID:</span>
                                        <span class="fw-medium">{{$orders->cPhone ?? "N/A"}}</span>
                                    </div>
                                </div>
                
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Status:</span>
                                        <span class="status-badge @if($orders->status ?? '' == 'Suspended') status-suspended @elseif($orders->status  ?? '' == 'Completed') status-completed @else status-active @endif">
                                            {{$orders->status ?? "N/A"}}
                                        </span>
                                    </div>
                                </div>
                                
                                
                            </div>
                            <!-- Amount Sections -->
                                <div class="container mt-3">
                                    <div class="section-title mb-3">Financial Summary</div>
                                </div>
                                
                                <div class="row">
                                    <div class="amount-section col-6">
                                        <div class="amount-label">Total Amount</div>
                                        @php
                                        if ($Orders) {
                                            $sum = $Orders->sum('totalPrice');
                                        } else {
                                            $sum = 0;
                                        }          
                                        $discount = $orders->discount ?? 0;
                                        $couponCode = $orders->coupons ?? null;

                                        $toatal = $sum-$discount;
                                        @endphp
                                        <div class="amount-value text-primary">
                                            {{(number_format($toatal)) ?? "N/A"}} Tsh
                                        </div>
                                    </div>
                                    
                                    <div class="amount-section paid col-6">
                                        <div class="amount-label">Paid Amount</div>
                                  
                                        <div class="amount-value text-success">
                                            {{(number_format($paidSoFar)) ?? "N/A"}} Tsh
                                        </div>
                                    </div>
                                    
                                    <div class="amount-section credit col-6">
                                        <div class="amount-label">Amount Credited</div>
                                        @php
                                        if ($Orders) {
                                            $sum = $Orders->sum('credit');
                                        } else {
                                            $sum = 0;
                                        }          
                                        @endphp
                                        <div class="amount-value text-danger">
                                            {{(number_format($sum)) ?? "N/A"}} Tsh
                                        </div>
                                    </div>
                                    
                                    <div class="amount-section col-6">
                                        <div class="amount-label">Outstanding Balance</div>
                                        <div class="amount-value text-primary">
                                              @php
                                        if ($Orders) {
                                            $sum = $Orders->sum('credit');
                                        } else {
                                            $sum = 0;
                                        }          
                                        @endphp
                                            {{(number_format($sum-$paidSoFar)) ?? 0}} Tsh
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            Amount remaining after credit
                                        </small>
                                    </div>
                                </div>
                        </div>
                    </div>
                    
                    <!-- Order Summary -->
                    <div class="col-md-4 mt-3 mt-md-0">
                        <div class="card-style summary-card p-4">
                            <h5 class="mb-4 fw-bold">
                                <i class="bi bi-receipt-cutoff me-2"></i>Order Summary
                            </h5>
                            
                            <div class="mb-3">
                                <form action="discount" method="post">
                                    @csrf
                                    <input type="hidden" name="orderName" value="{{$orders->orderName ?? null}}">
                                    <label class="form-label fw-bold">Apply Discount</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" onchange="this.form.submit()" name="discount" value="{{$orders->discount ?? ''}}" placeholder="Enter discount amount">
                                        <span class="input-group-text">Tsh</span>
                                    </div>
                                </form>
                            </div>
                
                            <div class="mb-3">
                                <label class="form-label fw-bold">Grand Total</label>
                                <input type="text" class="form-control bg-light fw-bold" value="{{ number_format($Orders->sum('totalPrice')) }}" readonly>
                            </div>
                            
                            <div class="mb-4 border-top pt-3"></div>
                            
                            <form action="" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="customerId" value="{{ $orders->cPhone ?? '' }}">

                                @if (empty($orders->status) || $orders->status == 'Suspended')                                

                                <div class="mb-3">
                                    <label for="orderType" class="form-label fw-bold">Transaction Type</label>
                                    <select class="form-select" name="orderType" id="orderType" required>
                                        <option value="Sell">Sell</option>
                                        <option value="Debt">Debt</option>
                                        <option value="Suspended">Suspended</option>
                                    </select>
                                </div>
                                
                                <button type="submit" formaction="payout" class="btn btn-action btn-submit w-100">
                                    <i class="bi bi-credit-card me-2"></i> Complete Order
                                </button>
                                @endif
                                
                                <div class="mb-3 border border-danger border-2 p-2 rounded-3 mt-3">
                                    <label for="paymentAmount" class="form-label fw-bold mb-2">Amount to Pay</label>
                                    <input type="number" name="paymentAmount" id="paymentAmount" class="form-control border-danger" placeholder="Enter payment amount">
                                </div>
                                
                                <button type="submit" formaction="processDebt" class="btn btn-action btn-submit w-100">
                                    <i class="bi bi-credit-card me-2"></i> Process Debt Payment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="card-style p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-cart-check me-2"></i>Customer Orders
                        </h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Orders as $index => $group)
                                @php
                                    $productName = DB::table('products')->where('product_id', $group->productId)->value('name01');
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <small>{{ $group->created_at->format('M d, Y') ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $productName }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $group->pQuantity ?? 0 }}</span>
                                    </td>
                                    <td>
                                        
                                            @php
                                            if ($group->status === "Sell") {
                                                $status = "In Progress";
                                            } else {
                                                $status = $group->status;
                                            }
                                            @endphp
                                        <strong>{{ $status }}</strong>
                                    </td>
                                    <td>
                                       @if ($group->status === "Paid")
                                           <i class="bi bi-check-circle-fill text-success"></i>
                                       @endif
                                        <strong>{{ number_format($group->credit) }}</strong>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <!-- View Button -->
                                            <form action="viewProduct" method="post" style="display: inline;">
                                                @csrf
                                                <input type="hidden" value="{{$group->order_id ?? 'Unknown Order'}}" name="OrdersIds" readonly>
                                                <input type="hidden" value="{{$group->productId}}" name="prodId" readonly>
                                                <button type="submit" class="btn btn-sm btn-view" title="View order items">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                            </form>
                                            
                                            <!-- Delete Button -->
                                            <form action="dltProdOrd" method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                                @csrf
                                                <input type="hidden" value="{{$group->order_id ?? 'Unknown Order'}}" name="OrdersIds" readonly>
                                                <input type="hidden" value="{{$group->productId}}" name="prodId" readonly>
                                                <input type="hidden" value="{{$group->pQuantity}}" name="prodQuantity" readonly>
                                                <button type="submit" class="btn btn-sm btn-delete" title="Delete order">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>