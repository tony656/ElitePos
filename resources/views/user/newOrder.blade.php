<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management System</title>
    @include("links")
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .main-content-wrapper {
            flex: 1;
            padding: 0 20px;
            background-color: #f8f9fa;
        }

        .main-content {
            display: flex;
            gap: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .card-modern {
            background: rgba(255, 255, 255, 0.97);
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: fit-content;
        }

        .card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .card-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 24px;
            border-bottom: none;
        }

        .card-header-modern h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-body-modern {
            padding: 20px;
        }

        .form-group-modern {
            margin-bottom: 20px;
        }

        .form-group-modern label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-control-modern {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.90rem;
            transition: all 0.3s ease;
            background: #f9f9f9;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-results-modern {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            max-height: 350px;
            overflow-y: auto;
            z-index: 1000;
            margin-top: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            display: none;
        }

        .search-item-modern {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .search-item-modern:hover {
            background: #f5f7ff;
            border-left: 4px solid #667eea;
            padding-left: 11px;
        }

        .search-item-name-modern {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        .search-item-details-modern {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #999;
        }

        .price-badge {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .btn-modern {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-success-modern {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .btn-success-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
        }

        .price-summary {
            background: linear-gradient(135deg, #f5f7ff 0%, #f0f0ff 100%);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }

        .price-row.total {
            border-top: 2px solid #667eea;
            padding-top: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            color: #667eea;
        }

        .cart-table-modern {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-table-modern thead {
            background: #f5f7ff;
            border-bottom: 2px solid #667eea;
        }

        .cart-table-modern th {
            padding: 10px 6px;
            text-align: left;
            color: #667eea;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cart-table-modern td {
            padding: 10px 6px;
            border-bottom: 1px solid #f0f0f0;
        }

        .cart-table-modern tbody tr:hover {
            background: #f9f9f9;
        }

        .input-number-modern {
            width: 60px;
            padding: 6px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            text-align: center;
            font-size: 0.9rem;
        }

        .input-number-modern:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-delete {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-delete:hover {
            background: #ff5252;
        }

        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .alert-modern {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border: 2px solid #667eea;
            color: #333;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .suspended-order-item {
            background: #fff;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .suspended-order-item:hover {
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
            transform: translateX(4px);
        }

        .suspended-order-header {
            display: flex;
            justify-content: space-between;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }

        .suspended-order-details {
            font-size: 0.85rem;
            color: #666;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .suspended-order-details span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .badge-suspended {
            background: #ffc107;
            color: #333;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .no-suspended {
            text-align: center;
            color: #999;
            padding: 20px;
            font-style: italic;
        }

        @media (max-width: 1200px) {
            .main-content {
                flex-direction: column;
            }

            .card-modern {
                width: 100% !important;
            }
        }

        @media (max-width: 768px) {
            .main-wrapper {
                flex-direction: column;
            }
            
            .main-content-wrapper {
                padding: 15px;
            }
            
            .row-2 {
                grid-template-columns: 1fr;
            }

            .suspended-order-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <!-- Sidebar -->
    @include("admin/sidenav")

    <!-- Main Content -->
    <div class="main-content-wrapper">
    
        <div class="main-content">
            
            <!-- Left Section - Order Form -->
            <div class="card-modern" style="width: 60%;">
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

                <div class="card-header-modern d-flex justify-content-between align-items-center">
                    <h5><i class="bi bi-plus-circle"></i> Create New Sales</h5>
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#suspendedModal">
                        Suspended Orders
                    </button>
                </div>
                <div class="card-body-modern">
                    <!-- Add to Cart Form -->
                    <form id="orderForm">
                        <div class="form-group-modern" style="position: relative;">
                            <label>Product Name</label>
                            <input type="search" id="productName" class="form-control-modern" 
                                   placeholder="Search products..." autocomplete="off">
                            <input type="hidden" name="pId" id="pId">
                            <div id="searchResults" class="search-results-modern"></div>
                        </div>

                        <button type="submit" class="btn-modern btn-success-modern w-100">
                            <i class="bi bi-plus-circle"></i> Add to Cart
                        </button>
                    </form>

                    <!-- Cart Table -->
                    <div style="margin-top: 30px;">
                        <h6 style="color: #333; font-weight: 700; margin-bottom: 15px;">
                            <i class="bi bi-cart"></i> Cart Lists
                        </h6>
                        <div style="overflow-x: auto;">
                            <table class="cart-table-modern">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th style="text-align: right;">Unit Price</th>
                                        <th style="text-align: right;">Amount</th>
                                        <th style="text-align: right;">Discount</th>
                                        <th style="text-align: right;">Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cartBody">
                                    @foreach($cart as $item)
                                        @php
                                            $product = DB::table('products')->where('account', session('account'))->where('product_id', $item->productId)->first('name01');
                                            $amount = $item->productPrice * $item->pQuantity;
                                            $total = $amount - $item->discount;
                                        @endphp
                                        <tr>
                                            <td>{{ $product ? $product->name01 : 'Unknown Product' }}</td>
                                            <td>
                                                <input type="number" class="input-number-modern" 
                                                       value="{{ $item->pQuantity }}" min="1"
                                                       onchange="updateCartItem('{{ $item->order_id }}','{{ $item->productId }}', 'pQuantity', this.value)">
                                            </td>
                                            <td style="text-align: right;">{{ number_format($item->productPrice) }}</td>
                                            <td style="text-align: right;">{{ number_format($amount) }}</td>
                                            <td>
                                                <input type="number" class="input-number-modern" 
                                                       value="{{ $item->discount }}" min="0"
                                                       onchange="updateCartItem('{{ $item->order_id }}','{{ $item->productId }}', 'discount', this.value)">
                                            </td>
                                            <td style="text-align: right; font-weight: 600;">{{ number_format($total) }}</td>
                                            <td>
                                                <form action="removeFromCart" method="post" style="display: inline;">
                                                    @csrf
                                                    <input type="hidden" name="itemId" value="{{ $item->productId }}">
                                                    <input type="hidden" name="orderId" value="{{ $item->order_id }}">
                                                    <button type="submit" class="btn-delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Section - Order Summary & Suspended Orders -->
            <div style="width: 40%; display: flex; flex-direction: column; gap: 20px;">
                
                <!-- Order Summary Card -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h5><i class="bi bi-receipt"></i> Sales Summary</h5>
                    </div>
                    <div class="card-body-modern">
                        <!-- Customer Information -->
                        <div style="margin-bottom: 25px;">
                            <h6 style="color: #333; font-weight: 700; margin-bottom: 15px;">Customer Details</h6>
                            
                            <form action="saveInfos" method="post">
                                @csrf
                                <input type="text" name="orderId" value="{{ $orders->order_id ?? '' }}" hidden>
                                <div class="form-group-modern">
                                    <label>Select Customer</label>
                                    <select id="customerSelect" onchange="this.form.submit()" name="selectedCustomer" class="form-control-modern">
                                        <option value="">-- Select Customer --</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->name }}|{{ $customer->id }}">{{ $customer->name }} - {{ $customer->limits ?? 0 }} limit</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>

                            <div class="text-center mb-3">
                                <a href="customers" class="btn bg py-2 px-3">
                                    <i class="bi bi-plus"></i> New Customer
                                </a>
                            </div>

                            @php
                                $checkz = DB::table('customers')->where('id', $orders->cPhone ?? '')->first();
                                $odez = DB::table('orders')->where('account', session('account'))->where('cName', $orders->cName ?? '')->whereIn('status', ['Debt', 'partial'])
                                    ->where('cPhone', $orders->cPhone ?? '')->sum('credit');
                            @endphp
                            <div class="container shadow-sm p-3">
                                <div style="margin-bottom: 10px;">
                                    Selected Customer: <strong>{{ $checkz->name ?? 'N/A' }}</strong>
                                </div>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Limit</th>
                                            <th>Credit</th>
                                            <th>Available</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ number_format($checkz->limits ?? 0) }}</td>
                                            <td>{{ number_format($odez ?? 0) }}</td>
                                            <td>{{ number_format(($checkz->limits ?? 0) - ($odez ?? 0)) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <p style="margin-top: 15px;">Order ID: <strong>{{ $orders->orderName ?? '' }}</strong></p>
                            <p>Order Status: <strong>{{ $orders->status ?? '' }}</strong></p>
                        </div>

                        <!-- Pricing Summary -->
                        <div class="price-summary">
                            <div class="price-row">
                                <span>Subtotal:</span>
                                <span id="subtotal">{{ number_format($totalP) }}</span>
                            </div>
                            <div class="price-row">
                                <span>Discount:</span>
                                <span id="totalDiscount">{{ number_format($totalD) }}</span>
                            </div>
                            <div class="price-row total">
                                <span>Total:</span>
                                <span id="grandTotal">{{ number_format($totalP - $totalD) }}</span>
                            </div>
                        </div>

                        <form action="payout" method="post">
                            @csrf
                            <input type="text" name="orderId" value="{{ $orders->order_id ?? '' }}" hidden>
                            
                            <!-- Order Type -->
                            <div class="form-group-modern">
                                <label>Sales Type</label>
                                <select id="orderType" name="orderType" class="form-control-modern">
                                    <option value="Sell">Pay</option>
                                    <option value="Debt">Credit</option>
                                    <option value="Suspended">Suspended</option>
                                </select>
                            </div>

                            <div class="form-group-modern border rounded-3 p-2" id="payDist">
                                <label>Distribute Amount</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Paid</label>
                                        <input type="number" id="paid" name="paid" class="form-control-modern">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Credit</label>
                                        <input type="number" id="credit" name="credit" class="form-control-modern">
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Type -->
                            <div class="form-group-modern" id="paymentTypeDiv">
                                <label>Payment Method</label>
                                <select id="paymentType" name="paymentMethod" class="form-control-modern">
                                    <option value="Cash">Cash</option>
                                    <option value="Bank">Bank</option>
                                </select>
                            </div>

                            <div id="debtFields" style="display: none; margin-bottom: 15px;">
                                <textarea class="form-control-modern" placeholder="Debt note..." rows="2"></textarea>
                            </div>

                            <div id="suspendFields" style="display: none; margin-bottom: 15px;">
                                <textarea class="form-control-modern" placeholder="Suspension reason..." rows="2"></textarea>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn-modern btn-success-modern w-100" style="padding: 15px; font-size: 1rem;">
                                <i class="bi bi-check-circle"></i> Submit Sale
                            </button>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<div class="modal" id="suspendedModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="suspendedModalLabel">Suspended Orders</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <!-- Suspended Orders Card -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h5><i class="bi bi-exclamation-triangle"></i> Suspended Orders</h5>
                    </div>
                    <div class="card-body-modern">
                        <div id="suspendedOrdersList" style="max-height: 400px; overflow-y: auto;">
                            @foreach ($Suspended as $index => $Order )
                                
                            <div class="d-flex justify-content-between align-items-center">
                                <h6>
                                    {{ $index + 1 }}.
                                    {{ $Order->cName }}
                                </h6>
                                <form action="resumeOrder" method="post">
                                    @csrf
                                    <input type="hidden" name="orderId" value="{{ $Order->order_id }}" hidden>
                                    <button type="submit}}">
                                    <button class="btn btn-primary">
                                    Resume Order
                                    <i class="bi bi-arrow-right-short"></i>
                                </button>
                                </form>
                            </div>
                                                        @endforeach

                            <div class="no-suspended">
                                <i class="bi bi-inbox"></i> No suspended orders
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
let selectedProduct = null;

// Live search
document.getElementById('productName').addEventListener('input', async (e) => {
    const query = e.target.value.trim();
    const searchResults = document.getElementById('searchResults');

    if (query.length < 2) {
        searchResults.style.display = 'none';
        searchResults.innerHTML = '';
        return;
    }

    try {
        const res = await fetch(`{{ url('admin/searchProduct') }}?query=${encodeURIComponent(query)}`);
        const data = await res.json();

        if (!data.length) {
            searchResults.innerHTML = `<div class="search-item-modern" style="text-align:center;color:#999;">No products found</div>`;
        } else {
            searchResults.innerHTML = data.map(p => `
                <div class="search-item-modern" onclick="selectProduct(${p.id}, '${p.name01}', ${p.sPrice})">
                    <div class="search-item-name-modern">${p.name01}</div>
                    <div class="search-item-details-modern">
                        <span><i class="bi bi-bag"></i> Stock: ${p.quantity}</span>
                        <span class="price-badge">${Number(p.bPrice).toLocaleString()} TZS</span>
                    </div>
                </div>
            `).join('');
        }
        searchResults.style.display = 'block';
    } catch (err) {
        searchResults.innerHTML = `<div class="search-item-modern" style="color:red;text-align:center;">Error loading products</div>`;
        searchResults.style.display = 'block';
    }
});

function selectProduct(id, name, price) {
    document.getElementById('productName').value = name;
    document.getElementById('pId').value = id;
    document.getElementById('searchResults').style.display = 'none';

      // AUTO SUBMIT
    document.getElementById('orderForm').dispatchEvent(
        new Event('submit', { bubbles: true, cancelable: true })
    );
}

// Handle form submission
document.getElementById('orderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const pId = document.getElementById('pId').value;
    const productName = document.getElementById('productName').value;
    
    if (!pId || !productName) {
        alert('Please select a product');
        return;
    }
    
    // Submit to backend
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ url("admin/newOrder") }}';
    
    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="pId" value="${pId}">
        <input type="hidden" name="orderType" value="Sell">
    `;
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
});

// Order type handling
document.getElementById('orderType').addEventListener('change', function() {
    const orderType = this.value;
    const debtFields = document.getElementById('debtFields');
    const paymentTypeDiv = document.getElementById('paymentTypeDiv');
    const suspendFields = document.getElementById('suspendFields');
    const payDist = document.getElementById('payDist');

    debtFields.style.display = 'none';
    paymentTypeDiv.style.display = 'block';
    suspendFields.style.display = 'none';

    if (orderType === 'Debt') {
        debtFields.style.display = 'block';
        payDist.style.display = 'none';
        paymentTypeDiv.style.display = 'none';
        
    } else if (orderType === 'Suspended') {
        suspendFields.style.display = 'block';
        payDist.style.display = 'none';
        paymentTypeDiv.style.display = 'none';
        
    } else if (orderType === 'Sell') {
        payMethod.style.display = 'block';
        paymentTypeDiv.style.display = 'block';
    }
});

// Paid/Credit distribution
const paidInput = document.getElementById('paid');
const creditInput = document.getElementById('credit');

document.addEventListener('DOMContentLoaded', () => {
    // Get grand total (remove commas)
    const grandTotalText = document.getElementById('grandTotal').innerText.replace(/,/g, '');
    const grandTotal = parseFloat(grandTotalText) || 0;

    // Default values
    paidInput.value = grandTotal;
    creditInput.value = 0;

    // When CREDIT changes → reduce PAID
    creditInput.addEventListener('input', () => {
        let credit = parseFloat(creditInput.value) || 0;
        if (credit > grandTotal) credit = grandTotal;
        creditInput.value = credit;
        paidInput.value = (grandTotal - credit).toFixed(2);
    });

    // When PAID changes → calculate CREDIT
    paidInput.addEventListener('input', () => {
        let paid = parseFloat(paidInput.value) || 0;
        if (paid > grandTotal) paid = grandTotal;
        if (paid < 0) paid = 0;
        paidInput.value = paid;
        creditInput.value = (grandTotal - paid).toFixed(2);
    });
});

// Load suspended orders
async function loadSuspendedOrders() {
    try {
        const res = await fetch('{{ url("admin/suspendedOrders") }}');
        const orders = await res.json();
        displaySuspendedOrders(orders);
    } catch (err) {
        console.error('Error loading suspended orders:', err);
    }
}

function displaySuspendedOrders(orders) {
    const list = document.getElementById('suspendedOrdersList');
    
    if (!orders.length) {
        list.innerHTML = '<div class="no-suspended"><i class="bi bi-inbox"></i> No suspended orders</div>';
        return;
    }

    list.innerHTML = orders.map(order => `
        <div class="suspended-order-item" onclick="loadSuspendedOrder(${order.id})">
            <div class="suspended-order-header">
                <span>${order.orderName || 'Order #' + order.id}</span>
                <span class="badge-suspended">SUSPENDED</span>
            </div>
            <div class="suspended-order-details">
                <span><i class="bi bi-person"></i> ${order.cName || 'N/A'}</span>
                <span><i class="bi bi-currency-dollar"></i> ${order.totalAmount?.toLocaleString() || '0'} TZS</span>
                <span><i class="bi bi-calendar"></i> ${new Date(order.created_at).toLocaleDateString()}</span>
                <span><i class="bi bi-phone"></i> ${order.cPhone || 'N/A'}</span>
            </div>
        </div>
    `).join('');
}

function loadSuspendedOrder(orderId) {
    // Navigate to load the suspended order
    window.location.href = `{{ url('admin/resumeOrder') }}/${orderId}`;
}

// Close search on outside click
document.addEventListener('click', (e) => {
    if (!e.target.closest('#productName') && !e.target.closest('#searchResults')) {
        document.getElementById('searchResults').style.display = 'none';
    }
});

// Load suspended orders on page load
document.addEventListener('DOMContentLoaded', () => {
    loadSuspendedOrders();
    
    // Refresh suspended orders every 30 seconds
    setInterval(loadSuspendedOrders, 30000);
});

// Update cart items via backend
function updateCartItem(orderId,productId, field, value) {
    const formData = new FormData();
    formData.append('orderId', orderId);
    formData.append('pId', productId);
    formData.append('field', field);
    formData.append('value', value);

    fetch('{{ url("admin/updateCartItem") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    }).then(response => {
        if(response.ok) {
            location.reload();
        }
    }).catch(err => console.error('Error updating cart:', err));
}
</script>

</body>
</html>