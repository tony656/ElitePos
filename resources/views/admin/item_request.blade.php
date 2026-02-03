<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Item Request</title>
    @include("links")
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
        
        /* Main Layout */
        body {
            background-color: #f8f9fa;
        }
        
        .main-container {
            min-height: 100vh;
        }
        
        /* Header */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .page-header h4 {
            font-weight: 600;
            margin: 0;
        }
        
        .page-header .bi {
            font-size: 1.5rem;
            margin-right: 10px;
        }
        
        /* Form Container */
        .form-container {
            background: white;
            border-radius: 12px;
            padding: 1.75rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
        }
        
        .form-container .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.1);
        }
        
        /* Search Results */
        #search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1000;
            background: white;
            border: 2px solid #667eea;
            border-radius: 8px;
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            margin-top: 2px;
            display: none;
        }
        
        .search-item {
            padding: 0.875rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid #f1f1f1;
            transition: all 0.2s ease;
        }
        
        .search-item:hover {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding-left: 0.75rem;
        }
        
        .search-item:last-child {
            border-bottom: none;
        }
        
        .search-item.selected {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border-left: 4px solid #667eea;
        }
        
        .search-item-name {
            font-weight: 600;
            color: #212529;
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
        }
        
        .search-item-details {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
        }
        
        .search-item-price {
            color: #28a745;
            font-weight: 600;
        }
        
        .search-item-stock {
            color: #6c757d;
            font-weight: 500;
        }
        
        .search-no-results {
            padding: 1.5rem;
            color: #6c757d;
            text-align: center;
            font-style: italic;
        }
        
        /* Product Input Group */
        .product-input-group {
            position: relative;
        }
        
        /* Order Summary Card */
        .order-summary-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.25rem 1.75rem;
            border-bottom: none;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: 600;
        }
        
        .card-body {
            padding: 1.75rem;
        }
        
        /* Pricing Summary */
        .pricing-summary {
            background: linear-gradient(135deg, #f5f7ff 0%, #f0f0ff 100%);
            padding: 1.25rem;
            border-radius: 10px;
            margin: 1.5rem 0;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }
        
        .price-row:last-child {
            margin-bottom: 0;
        }
        
        .price-row.total {
            border-top: 2px solid #667eea;
            padding-top: 0.875rem;
            font-weight: 700;
            font-size: 1.1rem;
            color: #667eea;
            margin-top: 0.875rem;
        }
        
        /* Buttons */
        .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
        }
        
        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
        }
        
        .btn-outline-primary:hover {
            background: #667eea;
            color: white;
        }
        
        /* Items Table */
        .items-table-container {
            margin-top: 2rem;
        }
        
        .items-table-container h5 {
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #667eea;
        }
        
        .table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .table th {
            font-weight: 600;
            border: none;
            padding: 1rem;
        }
        
        .table td {
            padding: 0.875rem 1rem;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .table .form-control {
            width: 80px;
            padding: 0.375rem 0.5rem;
            text-align: center;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Toggle Switch */
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        
        /* Order Info */
        .order-info {
            background: #f8f9fa;
            padding: 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .order-info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }
        
        .order-info-item:last-child {
            margin-bottom: 0;
        }
        
        .badge {
            font-size: 0.85rem;
            padding: 0.4em 0.8em;
            border-radius: 6px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 1rem;
            }
            
            .form-container, .order-summary-card {
                padding: 1.25rem;
            }
            
            .card-body {
                padding: 1.25rem;
            }
        }
        
        /* Hidden fields */
        .hidden-fields {
            opacity: 0;
            height: 0;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="{{asset("css/dashboard.css")}}" rel="stylesheet">
</head>
<body>

<div class="container-fluid main-container">
    <div class="row">
        @include("admin/sidenav")

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4><i class="bi bi-cart-plus"></i> Item Request</h4>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h4><i class="bi bi-receipt"></i> Item Request Detail</h4>
                    </div>
                </div>
            </div>
            
            <!-- Alerts -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <!-- Main Content -->
            <div class="row g-4">
                <!-- Left Column - Order Form -->
                <div class="col-lg-6">
                    <div class="form-container">
                        <h5 class="mb-4 fw-bold text-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add New Item
                        </h5>
                        
                        <form action="itemRequest" method="post" autocomplete="off">
                            @csrf
                            <input type="hidden" name="OrderName" value="{{$orders->orderName ?? ''}}">
                            
                            <!-- Product Search -->
                            <div class="mb-4 product-input-group">
                                <label for="product-name" class="form-label">Search Product</label>
                                <input type="search" class="form-control" id="product-name" 
                                       placeholder="Start typing product name..." autocomplete="off">
                                <div id="search-results"></div>
                            </div>
                            
                            <!-- Quantity, Discount, and Total -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label for="pQuantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="pQuantity"
                                           name="pQuantity" value="1" min="1" required>
                                </div>
                               <!-- <div class="col-md-4">
                                    <label for="discount" class="form-label">Discount (TZS)</label>
                                    <input type="number" class="form-control" id="discount"
                                           name="discount" value="0" min="0" step="0.01">
                                    <small class="text-muted" id="maxDiscountHint"></small>
                                </div> -->
                                <div class="col-md-4">
                                    <label for="totalPrice" class="form-label">Total Price (TZS)</label>
                                    <input type="number" class="form-control" id="totalPrice"
                                           name="totalPrice" readonly style="background-color: #f8f9fa;">
                                </div>
                            </div>
                            
                            <!-- Hidden Fields -->
                            <div class="hidden-fields">
                                <input type="text" class="form-control" id="pId" name="pId" readonly>
                                <input type="text" class="form-control" id="pPrice" name="pPrice" readonly>
                                <input type="text" class="form-control" value="{{$orders->order_id ?? ''}}"
                                       name="OrdersIds" readonly>
                                <input type="text" class="form-control" value="{{$orders->orderName ?? ''}}"
                                       name="OrdersNames" readonly>
                                <input type="number" id="maxDiscount" name="maxDiscount" readonly>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button class="btn btn-success btn-lg" type="submit">
                                    <i class="bi bi-plus-circle me-2"></i> Add to Current Item
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Items Table -->
                    <div class="items-table-container">
                        <h5><i class="bi bi-cart3 me-2"></i>Current Items</h5>
                        @php
                            $lastOrder = DB::table('item_requests')
                                    ->where('requestName', '!=', '')
                                    ->where('status', '=', 'Pending')
                                    ->orderBy('id', 'desc')
                                    ->first();
                            $defaultValues = [
                                'requestName' => 'N/A',
                                'created_at' => now(),
                                'cName' => 'N/A',
                                'served_by' => 'N/A',
                            ];
                            $orderData = $lastOrder ? (array)$lastOrder : $defaultValues;
                            $requestName = $orderData['requestName'];
                            $orderItems = $requestName ? DB::table('item_requests')
                                                    ->where('requestName', $requestName)
                                                    ->get() : collect();
                        @endphp
                        
                        @if($orderItems->count() > 0)
                        <div class="table-responsive rounded-3">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="w-50">Product</th>
                                        <th>Qty</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItems as $item)
                                        @php
                                            $product = DB::table('products')
                                                        ->where('product_id', $item->productId)
                                                        ->first();
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-3">
                                                        <h6 class="mb-0">{{ $product->name01 ?? 'Product Not Found' }}</h6>
                                                        <small class="text-muted">ID: {{ $item->productId }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <form action="reqdltProdOrd" method="post" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="OrdersIds" value="{{ $item->requestName }}">
                                                    <input type="hidden" name="prodId" value="{{ $item->productId }}">
                                                    <input type="number" class="form-control form-control-sm" 
                                                           onchange="this.form.submit()" 
                                                           name="prodQuantity" 
                                                           value="{{ $item->quantity }}"
                                                           min="1"
                                                           style="width: 80px;">
                                                </form>
                                            </td>
                                            <td class="text-end">
                                                <span class="fw-bold">{{ number_format($item->price, 2) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <form action="dltItemReq" method="post" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="itemId" value="{{ $item->productId }}">
                                                    <input type="hidden" name="reqName" value="{{ $item->requestName }}">
                                                    <input type="hidden" class="form-control" value="{{$item->quantity}}" name="prodQuantity" readonly>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Are you sure you want to remove this item?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5 bg-light rounded-3">
                            <i class="bi bi-cart-x display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No items in the order yet</h5>
                            <p class="text-muted">Start by searching and adding products above</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Right Column - Order Summary -->
                <div class="col-lg-6">
                    <div class="order-summary-card">                 
                        
                        <div class="card-body">
                            @php
                                $lastOrder = DB::table('item_requests')
                                                ->where('requestName', '!=', '')
                                                ->where('status', '=', 'Pending')
                                                ->orderBy('id', 'desc')
                                                ->first();
                                $Customers = DB::table('accounts')->where('name', '!=', session('account'))->get();
                                $defaultValues = [
                                    'requestName' => 'N/A',
                                    'created_at' => now(),
                                    'served_by' => 'N/A',
                                    'supplirtName' => 'Not Selected'
                                ];
                                $orderData = $lastOrder ? (array)$lastOrder : $defaultValues;
                                $requestName = $orderData['requestName'];
                                $orderDate = $lastOrder ? date('d-M-Y h:i A', strtotime($orderData['created_at'])) : 'N/A';
                                $servedBy = $orderData['served_by'];
                                $Status = $orderData['status'] ?? 'Pending';
                                $orderItems = $requestName ? DB::table('item_requests')
                                                        ->where('requestName', $requestName)
                                                        ->get() : collect();
                                $prodPrice = $orderItems->sum('price');
                                $subtotal = $orderItems->sum('price');
                                $newDisc = $orderItems->sum('discount');
                                $grandTotal =  $subtotal;
                                $supplirtName = $lastOrder->supplierName ?? 'Not Selected';
                            @endphp
                            
                            <!-- Order Information -->
                            <div class="order-info">
                                <div class="order-info-item">
                                    <span class="text-muted">Request ID:</span>
                                    <span class="fw-bold">{{ $requestName ?? 'NEW-ORDER' }}</span>
                                </div>
                                
                                <div class="order-info-item">
                                    <span class="text-muted">Date:</span>
                                    <span>{{ $orderDate }}</span>
                                </div>
                                
                                <div class="order-info-item">
                                    <span class="text-muted">Status:</span>
                                    <span class="badge bg-info">{{ $Status }}</span>
                                </div>
                                
                                <div class="order-info-item">
                                    <span class="text-muted">Served By:</span>
                                    <span>{{ $servedBy }}</span>
                                </div>
                            </div>
                            
                            <!-- Customer Information -->
                            <form action="saveInfo" method="post" class="mb-4">
                                @csrf
                                <input type="hidden" name="requestName" value="{{ $requestName }}">
                                
                                <h6 class="fw-bold mb-3">Supplier Information</h6>
                                
                                <div class="mb-3">
                                    <div class=" mb-3">
                                      <p>
                                        supplier name: {{ $supplirtName }}
                                      </p>
                                    </div>
                                    
                                    <!-- Select existing supplier -->
                                    <div id="selectCustomer">
                                        <label class="form-label">Select Supplier</label>
                                        <select class="form-select" name="selectedCustomer" onchange="this.form.submit()" id="customerSelect">
                                            <option value="">-- Select supplier --</option>
                                            @foreach ($Customers as $customer)
                                                <option value="{{ $customer->name }}|{{ $customer->id }}">
                                                    {{ $customer->name }} - {{ $customer->id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                </div>
             
                            </form>
                            
                            <!-- Pricing Summary -->
                            <div class="pricing-summary">
                                <div class="price-row">
                                    <span>Subtotal:</span>
                                    <span class="fw-bold">{{ number_format($prodPrice, 2) }} TZS</span>
                                </div>
                   
                                <div class="price-row total">
                                    <span>Grand Total:</span>
                                    <span class="fw-bold text-primary">{{ number_format($grandTotal, 2) }} TZS</span>
                                </div>
                            </div>
                            
                            <!-- Finalize Order -->
                            @if($requestName)
                            <form action="requestSubmit" method="POST">
                                @csrf
                                <input type="hidden" name="OrdersIds" value="{{ $requestName }}">                                
                             
                                <button type="submit" class="btn btn-success w-100 btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Submit Request
                                </button>
                            </form>
                            @else
                            <div class="alert alert-info text-center">
                                <i class="bi bi-info-circle me-2"></i>
                                Add products to begin order
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
$(document).ready(function() {
    // Live search functionality
    $('#product-name').on('input', function() {
        let query = $(this).val().trim();
        if (query.length > 1) {
            $.ajax({
                url: "{{ url('admin/searchProduct') }}",
                method: 'GET',
                data: { query: query },
                success: function(data) {
                    if (data.error) {
                        $('#search-results').html('<div class="search-no-results">' + data.error + '</div>').show();
                    } else {
                        let output = '';
                        data.forEach(function(product) {
                            output += `
                                <div class="search-item"
                                     data-product_id="${product.product_id}"
                                     data-name01="${product.name01}"
                                     data-price="${product.bPrice}"
                                     data-discount="${product.discount}">
                                    <div class="search-item-content">
                                        <div class="search-item-name">${product.name01}</div>
                                        <div class="search-item-details">
                                            <span class="search-item-price">${Number(product.bPrice).toFixed(2)} TZS</span>
                                            <span class="search-item-stock">Stock: ${Number(product.quantity)}</span>
                                        </div>
                                    </div>
                                </div>`;
                        });
                        $('#search-results').html(output).show();
                    }
                },
                error: function() {
                    $('#search-results').html('<div class="search-no-results">Error loading results</div>').show();
                }
            });
        } else {
            $('#search-results').hide().html('');
        }
    });
    
    // Hide search results when clicking elsewhere
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#product-name, #search-results').length) {
            $('#search-results').hide();
        }
    });
    
    // Handle product selection
    $(document).on('click', '#search-results .search-item', function() {
        // Remove selected class from all items
        $('#search-results .search-item').removeClass('selected');
        // Add selected class to clicked item
        $(this).addClass('selected');
        
        let productName = $(this).data('name01');
        let productPrice = $(this).data('price');
        let productId = $(this).data('product_id');
        let productMaxDiscount = $(this).data('discount') || 0;
        
        $('#product-name').val(productName);
        $('#pId').val(productId);
        $('#pPrice').val(productPrice);
        $('#maxDiscount').val(productMaxDiscount);
        $('#discount').attr('max', productMaxDiscount);
        $('#search-results').hide();

        $('#maxDiscountHint').text(`Max discount: ${productMaxDiscount} TZS`);
        updateTotalPrice();
    });
    
    // Calculate total price
    $('#pQuantity, #discount').on('input', updateTotalPrice);
    
    function updateTotalPrice() {
        let quantity = parseInt($('#pQuantity').val()) || 0;
        let price = parseFloat($('#pPrice').val()) || 0;
        let discount = parseFloat($('#discount').val()) || 0;
        let maxDiscount = parseFloat($('#maxDiscount').val()) || 0;

        // Validate discount
        if (discount > maxDiscount) {
            alert(`Discount cannot exceed ${maxDiscount} TZS for this product`);
            $('#discount').val(maxDiscount);
            discount = maxDiscount;
        }

        let total = (price * quantity) - discount;
        $('#totalPrice').val(total > 0 ? total.toFixed(2) : '0.00');
    }
    
    // Toggle between existing and custom customer
    $('#customToggle').change(function() {
        if ($(this).is(':checked')) {
            $('#selectCustomer').slideUp();
            $('#customFields').slideDown();
            $('#customerSelect').val('');
        } else {
            $('#selectCustomer').slideDown();
            $('#customFields').slideUp();
            $('input[name="Cname"], input[name="Cphone"]').val('');
        }
    });
});

// Toggle order type fields
function toggleOrderTypeFields(select) {
    const debtFields = document.getElementById('debtFields');
    const suspendFields = document.getElementById('suspendFields');

    debtFields.style.display = 'none';
    suspendFields.style.display = 'none';

    if (select.value === 'Debt') {
        debtFields.style.display = 'block';
    } else if (select.value === 'Suspended') {
        suspendFields.style.display = 'block';
    }
}
</script>

</body>
</html>