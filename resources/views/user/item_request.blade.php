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
            .bd-placeholder-img-lg { font-size: 3.5rem; }
        }

        .main-container { min-height: 100vh; }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .page-header h4 { font-weight: 600; margin: 0; }
        .page-header .bi { font-size: 1.5rem; margin-right: 10px; }

        .form-container {
            background: white;
            border-radius: 12px;
            padding: 1.75rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
        }
        .form-container .form-label { font-weight: 600; color: #495057; margin-bottom: 0.5rem; }

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
        .search-item:last-child { border-bottom: none; }
        .search-item.selected {
            background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));
            border-left: 4px solid #667eea;
        }
        .search-item-name { font-weight: 600; color: #212529; margin-bottom: 0.25rem; font-size: 0.95rem; }
        .search-item-details { display: flex; justify-content: space-between; font-size: 0.85rem; }
        .search-item-price { color: #28a745; font-weight: 600; }
        .search-no-results { padding: 1.5rem; color: #6c757d; text-align: center; font-style: italic; }
        .product-input-group { position: relative; }

        .order-summary-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
        .card-body { padding: 1.75rem; }

        .pricing-summary {
            background: linear-gradient(135deg, #f5f7ff 0%, #f0f0ff 100%);
            padding: 1.25rem;
            border-radius: 10px;
            margin: 1.5rem 0;
        }
        .price-row { display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.95rem; }
        .price-row:last-child { margin-bottom: 0; }
        .price-row.total {
            border-top: 2px solid #667eea;
            padding-top: 0.875rem;
            font-weight: 700;
            font-size: 1.1rem;
            color: #667eea;
            margin-top: 0.875rem;
        }

        .btn { border-radius: 8px; padding: 0.75rem 1.5rem; font-weight: 600; transition: all 0.3s ease; }
        .btn-lg { padding: 1rem 2rem; font-size: 1.1rem; }
        .btn-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border: none; }
        .btn-success:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(40,167,69,0.3); }
        .btn-outline-primary { border: 2px solid #667eea; color: #667eea; }
        .btn-outline-primary:hover { background: #667eea; color: white; }

        .items-table-container { margin-top: 2rem; }
        .items-table-container h5 {
            font-weight: 700; color: #333; margin-bottom: 1rem;
            padding-bottom: 0.75rem; border-bottom: 2px solid #667eea;
        }

        .table { border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .table thead { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .table th { font-weight: 600; border: none; padding: 1rem; }
        .table td { padding: 0.875rem 1rem; vertical-align: middle; }
        .table tbody tr:hover { background-color: #f8f9fa; }
        .table tbody tr:last-child td { border-bottom: none; }
        .table .form-control { width: 80px; padding: 0.375rem 0.5rem; text-align: center; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; }

        .form-check-input:checked { background-color: #667eea; border-color: #667eea; }
        .form-check-input:focus { box-shadow: 0 0 0 0.25rem rgba(102,126,234,0.25); }

        .order-info { background: #f8f9fa; padding: 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; }
        .order-info-item { display: flex; justify-content: space-between; margin-bottom: 0.75rem; }
        .order-info-item:last-child { margin-bottom: 0; }
        .badge { font-size: 0.85rem; padding: 0.4em 0.8em; border-radius: 6px; }

        @media (max-width: 768px) {
            .page-header { padding: 1rem; }
            .form-container, .order-summary-card { padding: 1.25rem; }
            .card-body { padding: 1.25rem; }
        }

        .hidden-fields { opacity: 0; height: 0; overflow: hidden; margin: 0; padding: 0; }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="{{asset("css/dashboard.css")}}" rel="stylesheet">
</head>
<body>

{{-- ================================================================
     ALL DATA COMPUTED ONCE HERE — shared by both left and right columns
     ================================================================ --}}
@php
    // Latest pending request for the logged-in account
    $lastOrder = DB::table('item_requests')
        ->where('account', getSessionAccountName())
        ->where('status', 'Pending')
        ->orderBy('id', 'desc')
        ->first();

    $requestName  = $lastOrder->requestName  ?? null;
    $orderDate    = $lastOrder
                    ? date('d-M-Y h:i A', strtotime($lastOrder->created_at))
                    : 'N/A';
    $servedBy     = $lastOrder->served_by    ?? 'N/A';
    $Status       = $lastOrder->status       ?? 'Pending';
    $supplirtName = $lastOrder->supplierName ?? 'Not Selected';

    // Fetch all line items for this request (newest first)
    $orderItems = $requestName
        ? DB::table('item_requests')->where('requestName', $requestName)->orderBy('id', 'desc')->get()
        : collect();

    // Build enriched rows: join with products to get sPrice
    $grandTotal    = 0;
    $enrichedItems = [];

    foreach ($orderItems as $item) {
        $prod      = DB::table('products')
                        ->where('product_id', $item->productId)
                        ->first();
        $unitPrice = $prod ? (float) $prod->sPrice : 0;
        $lineTotal = (int) $item->quantity * $unitPrice;
        $grandTotal += $lineTotal;

        $enrichedItems[] = [
            'item'      => $item,
            'product'   => $prod,
            'unitPrice' => $unitPrice,
            'lineTotal' => $lineTotal,
        ];
    }

    $subtotal  = $grandTotal;   // no discount on item requests
    $Customers = DB::table('accounts')
                    ->where('name', '!=', getSessionAccountName())
                    ->get();
@endphp

<div class="container-fluid main-container">
    <div class="row">
        @include("user/sidenav")

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4><i class="bi bi-cart-plus"></i> Item Request</h4>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ url('user/viewRequest') }}" class="btn btn-outline-light">
                            <i class="bi bi-list-ul"></i> View All Requests
                        </a>
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
            
            <!-- Debug Info - Remove this in production -->
            @php
                $debugAccount = getSessionAccountDisplayName();
                $debugUsername = session('username');
                $debugCount = DB::table('item_requests')->count();
            @endphp
            <div class="alert alert-warning alert-dismissible fade show rounded-3 mb-3" role="alert">
                <strong>Debug Info:</strong><br>
                Session Account: {{ $debugAccount ?? 'EMPTY - This is the problem!' }}<br>
                Session Username: {{ $debugUsername ?? 'EMPTY' }}<br>
                Total Item Requests in DB: {{ $debugCount }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div class="row g-4">

                <!-- ========== LEFT COLUMN ========== -->
                <div class="col-lg-6">

                    <!-- Add Item Form -->
                    <div class="form-container">
                        <h5 class="mb-4 fw-bold text-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add New Item
                        </h5>

                        <form action="/user/itemRequest" method="post" autocomplete="off">
                            @csrf
                            <input type="hidden" name="OrderName" value="{{ $orders->orderName ?? '' }}">

                            <div class="mb-4 product-input-group">
                                <label for="product-name" class="form-label">Search Product</label>
                                <input type="search" class="form-control" id="product-name"
                                       placeholder="Start typing product name..." autocomplete="off">
                                <div id="search-results"></div>
                            </div>

                            <input type="hidden" name="pQuantity" value="1">
                            <input type="hidden" name="requestDate" id="formRequestDate" value="{{ old('requestDate', date('Y-m-d')) }}">

                            <div class="hidden-fields">
                                <input type="text" id="pId"    name="pId"    readonly>
                                <input type="text" id="pPrice" name="pPrice" readonly>
                                <input type="text" value="{{ $orders->order_id  ?? '' }}" name="OrdersIds"   readonly>
                                <input type="text" value="{{ $orders->orderName ?? '' }}" name="OrdersNames" readonly>
                                <input type="number" id="maxDiscount" name="maxDiscount" readonly>
                            </div>

                            <!-- Payment Type Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Payment Type</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="paymentType" id="paymentCashUser" value="cash">
                                        <label class="form-check-label" for="paymentCashUser">
                                            <i class="bi bi-cash-stack text-success"></i> Cash
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="paymentType" id="paymentCreditUser" value="credit" checked>
                                        <label class="form-check-label" for="paymentCreditUser">
                                            <i class="bi bi-credit-card text-primary"></i> Credit
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Supplier Information (moved from right column) -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Supplier Information</h6>
                                <div class="mb-3">
                                    <p class="mb-2">
                                        <strong>Current Supplier:</strong> {{ $supplirtName }}
                                    </p>
                                    <div id="selectCustomer">
                                        <label class="form-label">Select Supplier</label>
                                        <select class="form-select" name="selectedCustomer" id="customerSelect">
                                            <option value="">-- Select supplier --</option>
                                            @foreach ($Customers as $customer)
                                                <option value="{{ $customer->name }}|{{ $customer->id }}">
                                                    {{ $customer->name }} - {{ $customer->id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Assign To User (moved from right column) -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Assign To (Location/User)</h6>
                                <div class="mb-3">
                                    <p class="mb-2">
                                        <strong>Current Assignee:</strong>
                                        {{ $lastOrder && $lastOrder->assigned_to ? $lastOrder->assigned_to : 'Not Assigned' }}
                                    </p>
                                    <div>
                                        <label class="form-label">Select User/Location</label>
                                        <select class="form-select" name="assignedTo" id="assignedToSelect">
                                            <option value="">-- Select User/Location --</option>
                                            @php
                                                // Get users from the same account
                                                $users = DB::table('users')
                                                    ->where('account', getSessionAccountName())
                                                    ->where('levelStatus', '!=', 'Admin')
                                                    ->orderBy('name', 'asc')
                                                    ->get();
                                            @endphp
                                            @foreach($users as $user)
                                                <option value="{{ $user->name }}" {{ (old('assignedTo') == $user->name || ($lastOrder && $lastOrder->assigned_to == $user->name)) ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->levelStatus ?? 'User' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-success btn-lg" type="submit" id="addItemBtn" {{ count($enrichedItems) > 0 ? '' : 'disabled' }}>
                                    <i class="bi bi-plus-circle me-2"></i> Add to Current Item
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Items Table -->
                    <div class="items-table-container">
                        <h5><i class="bi bi-cart3 me-2"></i>Current Items</h5>

                        @if(count($enrichedItems) > 0)
                        <div class="table-responsive rounded-3">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($enrichedItems as $row)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">
                                                {{ $row['product']->name01 ?? 'Product Not Found' }}
                                            </h6>
                                            <small class="text-muted">ID: {{ $row['item']->productId }}</small>
                                        </td>
                                        <td>
                                            <form action="/user/requpdQuant" method="post" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="OrdersIds" value="{{ $row['item']->requestName }}">
                                                <input type="hidden" name="prodId"    value="{{ $row['item']->productId }}">
                                                <input type="number"
                                                       class="form-control form-control-sm"
                                                       onchange="this.form.submit()"
                                                       name="prodQuantity"
                                                       value="{{ $row['item']->quantity }}"
                                                       min="1"
                                                       style="width:80px;">
                                            </form>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold text-success">
                                                {{ number_format($row['unitPrice'], 2) }} TZS
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold">
                                                {{ number_format($row['lineTotal'], 2) }} TZS
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <form action="/user/dltItemReq" method="post" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="itemId"       value="{{ $row['item']->productId }}">
                                                <input type="hidden" name="reqName"      value="{{ $row['item']->requestName }}">
                                                <input type="hidden" name="prodQuantity" value="{{ $row['item']->quantity }}">
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Remove this item?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light fw-bold">
                                        <td colspan="3" class="text-end pe-3">Total:</td>
                                        <td class="text-end text-primary fw-bold">
                                            {{ number_format($grandTotal, 2) }} TZS
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
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

                <!-- ========== RIGHT COLUMN ========== -->
                <div class="col-lg-6">
                    <div class="order-summary-card">
                        <div class="card-body">

                            <!-- Order Info -->
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


                            <!-- Pricing Summary — $subtotal/$grandTotal set at top of file -->
                            <div class="pricing-summary">
                                <div class="price-row">
                                    <span>Subtotal:</span>
                                    <span class="fw-bold">{{ number_format($subtotal, 2) }} TZS</span>
                                </div>
                                <div class="price-row total">
                                    <span>Grand Total:</span>
                                    <span class="fw-bold">{{ number_format($grandTotal, 2) }} TZS</span>
                                </div>
                            </div>
    <!-- Submit -->
                            @if($requestName)
                            <form action="/user/requestSubmit" method="POST">
                                @csrf
                            <!-- Request Date -->
                            <div class="mb-3">
                                <label for="requestDate" class="form-label">Request Date</label>
                                <input type="date" class="form-control" id="requestDate"
                                       name="requestDatePicker" value="{{ old('requestDate', date('Y-m-d')) }}">
                            </div>

                        
                                
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

            </div><!-- /.row -->
        </main>
    </div>
</div>

<script>
$(document).ready(function () {

    // Live product search
    $('#product-name').on('input', function () {
        let query = $(this).val().trim();
        if (query.length > 1) {
            $.ajax({
                url: "{{ url('admin/searchProduct') }}",
                method: 'GET',
                data: { query: query },
                success: function (data) {
                    if (!data || data.error) {
                        $('#search-results')
                            .html('<div class="search-no-results">' + (data.error || 'No results') + '</div>')
                            .show();
                        return;
                    }
                    let output = '';
                    data.forEach(function (product) {
                        let price = parseFloat(product.sPrice) || 0;
                        output += `
                            <div class="search-item"
                                 data-product_id="${product.product_id}"
                                 data-name01="${product.name01}"
                                 data-price="${price}"
                                 data-discount="${product.discount || 0}">
                                <div class="search-item-name">${product.name01}</div>
                                <div class="search-item-details">
                                    <span class="search-item-price">${price.toFixed(2)} TZS</span>
                                </div>
                            </div>`;
                    });
                    $('#search-results').html(output).show();
                },
                error: function () {
                    $('#search-results')
                        .html('<div class="search-no-results">Error loading results</div>')
                        .show();
                }
            });
        } else {
            $('#search-results').hide().html('');
        }
    });

    // Close on outside click
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#product-name, #search-results').length) {
            $('#search-results').hide();
        }
    });

    // Select product → fill hidden fields → auto-submit
    $(document).on('click', '#search-results .search-item', function () {
        $('#search-results .search-item').removeClass('selected');
        $(this).addClass('selected');

        let productName        = $(this).data('name01');
        let productPrice       = $(this).data('price');
        let productId          = $(this).data('product_id');
        let productMaxDiscount = $(this).data('discount') || 0;

        $('#product-name').val(productName);
        $('#pId').val(productId);
        $('#pPrice').val(productPrice);
        $('#maxDiscount').val(productMaxDiscount);
        $('#search-results').hide();

        // Sync request date from visible input to hidden field
        $('#formRequestDate').val($('#requestDatePicker').val());

        // Auto-submit the form
        $('form[action="/user/itemRequest"]').submit();
    });

    // Also update hidden field when requestDatePicker changes
    $('#requestDatePicker').on('change', function() {
        $('#formRequestDate').val($(this).val());
    });

});
</script>

</body>
</html>