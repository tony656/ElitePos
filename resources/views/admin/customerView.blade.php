<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    @include('links')
    <style>
        :root {
            --primary-color: #054082;
            --secondary-color: #f8f9fa;
            --accent-color: #054082;
            --text-dark: #343a40;
            --text-light: #6c757d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .customer-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
            animation: slideUp 0.5s ease-out;
        }

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

        .customer-header {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .customer-name {
            font-size: 1.8rem;
            font-weight: 700;
        }

        .customer-status {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.9rem;
            backdrop-filter: blur(10px);
        }

        .customer-info {
            padding: 2rem;
        }

        .info-row {
            display: flex;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px solid #e9ecef;
            transition: background 0.3s ease;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row:hover {
            background: #f8f9fa;
            border-radius: 8px;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .info-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 1rem;
            font-size: 1.1rem;
            background: rgba(0, 78, 137, 0.1);
            color: var(--primary-color);
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 0.85rem;
            color: var(--text-light);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1.1rem;
            color: var(--text-dark);
            font-weight: 600;
            margin-top: 0.2rem;
        }

        .sales-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            animation: slideUp 0.6s ease-out 0.1s both;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sales-table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 8px;
        }

        .sales-table thead {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
        }

        .sales-table th {
            padding: 1.2rem;
            font-weight: 600;
            text-align: left;
            letter-spacing: 0.5px;
            font-size: 0.95rem;
        }

        .sales-table td {
            padding: 1.2rem;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .sales-table tbody tr {
            transition: all 0.3s ease;
        }

        .sales-table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }

        .product-name {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .product-name-primary {
            font-weight: 600;
            color: var(--text-dark);
        }

        .product-name-secondary {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        .quantity-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }

        .quantity-high {
            background-color: var(--success-color);
        }

        .quantity-medium {
            background-color: var(--warning-color);
        }

        .quantity-low {
            background-color: var(--danger-color);
        }

        .badge-category {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            background: rgba(0, 78, 137, 0.1);
            color: var(--primary-color);
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-view {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            border: none;
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 78, 137, 0.3);
            color: white;
            text-decoration: none;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            color: var(--text-light);
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state-text {
            font-size: 1.1rem;
            font-weight: 500;
        }

        .price-highlight {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.05rem;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 1rem;
            }

            .customer-header {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .customer-name {
                font-size: 1.5rem;
            }

            .sales-table {
                font-size: 0.9rem;
            }

            .sales-table th, .sales-table td {
                padding: 0.8rem;
            }

            .btn-view {
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="row">

  @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-4 py-3">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between">
            <div class="page-title">
                <i class="bi bi-person-circle"></i>
                Customer Details
            </div>
            <div class="">
                <button class="btn bg-color px-3 py-2" data-bs-toggle="modal" data-bs-target="#editCustomer">
                    <i class="bi bi-pencil-square"></i>
                    Edit
                </button>
            </div>
        </div>

        <!-- Customer Card -->
        <div class="customer-card">
            <div class="customer-header">
                <div>
                    <div class="customer-name">{{ $get->name }}</div>
                </div>
                <div class="customer-status">
                    <i class="bi bi-check-circle"></i> Active Customer
                </div>
            </div>

            <div class="customer-info">

                <div class="row">
                <!-- Address -->
                <div class="info-row col-md-6">
                    <div class="info-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Location</div>
                        <div class="info-value">{{ $get->address }}</div>
                    </div>
                </div>

                <!-- Phone -->
                <div class="info-row col-md-6">
                    <div class="info-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Contact</div>
                        <div class="info-value">{{ $get->phone }}</div>
                    </div>
                </div>
</div>
                <div class="row">
                <!-- Business Type -->
                <div class="info-row col-md-6">
                    <div class="info-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Business Type</div>
                        <div class="info-value">{{ $get->business }}</div>
                    </div>
                </div>

                <!-- Credit Limit -->
                <div class="info-row col-md-6">
                    <div class="info-icon">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Credit Limit</div>
                        <div class="info-value price-highlight">Tsh {{ number_format($get->limits) }}</div>
                    </div>
                </div>
</div>
                
                <!-- Description -->
                @if($get->description)
                <div class="info-row">
                    <div class="info-icon">
                        <i class="bi bi-chat-left-text"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Description</div>
                        <div class="info-value">{{ $get->description }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sales Section -->
        <div class="sales-section">
            <div class="section-title">
                <i class="bi bi-receipt"></i>
                Sales History
            </div>

            @if($sales->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <div class="empty-state-text">No sales records found for this customer</div>
            </div>
            @else
            <div style="overflow-x: auto;">
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price per Unit</th>
                            <th>Total</th>
                            <th>Discount</th>
                            <th>Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $index => $product)
                        @php
        $productz = DB::table('products')->where('account', session('account'))->where('product_id', $product->productId)->first();

    @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="product-name">
                                    <span class="product-name-primary">{{ $productz->name01 }}</span>
                                    <span class="product-name-secondary">{{ $productz->name02 }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="quantity-indicator" 
                                    style="background-color: 
                                        @if($product->pQuantity <= 0) var(--danger-color)
                                        @elseif($product->pQuantity < 10) var(--warning-color)
                                        @else var(--success-color)
                                        @endif">
                                </span>
                                {{ number_format($product->pQuantity) }} {{ $product->unit }}
                            </td>
                            <td>
                                <span class="price-highlight">Tsh {{ number_format($product->productPrice) }}</span>
                            </td>
                            <td>
                                <span class="price-highlight">Tsh {{ number_format($product->totalPrice) }}</span>
                            </td>
                            <td>
                                <span class="badge-category">{{ $product->discount }}</span>
                            </td>
                              <td>
                                <span class="">{{ $product->created_at }}</span>
                            </td>
                            <td class="text-end">
                                <form method="post" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-view" name="salesName" value="{{ $product->sales_id }}" formaction="viewSales">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    <div class="modal" id="editCustomer">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="th4modal-title">
                        Customer Edits
                    </div>
                    <button class="btn btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="editCustomer" method="post">
                    @csrf
                    <input type="text" value="{{ $get->id }}" name="customerId" hidden>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" value="{{ $get->name }}" placeholder="Customer name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact" class="form-label">Contact</label>
                            <input type="text" class="form-control" name="contact" placeholder="Contact person name" value="{{ $get->phone }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" placeholder="Customer address" value="{{ $get->address }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="type" class="form-label">Business Type</label>
                            <select name="type" class="form-select" required>
                                <option value="{{ $get->business }}">{{ $get->business }}</option>
                                <option value="Uknown" disabled>Select Business Type</option>
                                <option value="Wholesale">Wholesale</option>
                                <option value="Manufacturer">Manufacturer</option>
                                <option value="Distributor">Distributor</option>
                                <option value="Retailer">Retailer</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="credit" class="form-label">Credit Limit</label>
                            <div class="input-group">
                                <span class="input-group-text">Tsh</span>
                                <input type="number" value="{{ $get->limits }}" class="form-control" name="credit" placeholder="Maximum credit amount" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Additional notes about this customer" required>
                            {{ $get->description }}
                        </textarea>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary text-white py-3">
                            <i class="bi bi-save me-2"></i>
                            Save Customer
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>