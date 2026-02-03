<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Sales Receipt</title>
    @include("links")
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #ecf0f1;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                font-size: 12pt;
            }
            .receipt-container {
                box-shadow: none !important;
                border: none !important;
            }
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .receipt-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem;
            text-align: center;
            border-bottom: 5px solid var(--accent-color);
        }
        
        .receipt-title {
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }
        
        .info-section {
            padding: 1.5rem;
            border-bottom: 1px dashed #ddd;
        }
        
        .info-card {
            background: var(--secondary-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .info-card-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }
        
        .info-card-title i {
            margin-right: 0.5rem;
            font-size: 1.2rem;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 500;
            color: var(--text-light);
        }
        
        .info-value {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .total-row {
            background: var(--secondary-color);
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .grand-total {
            background: var(--primary-color);
            color: white;
            font-size: 1.2rem;
        }
        
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .receipt-table thead {
            background: var(--primary-color);
            color: white;
        }
        
        .receipt-table th {
            padding: 0.75rem;
            text-align: left;
        }
        
        .receipt-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #eee;
        }
        
        .receipt-table tr:last-child td {
            border-bottom: none;
        }
        
        .receipt-footer {
            padding: 1.5rem;
            text-align: center;
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .alert {
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .barcode {
            text-align: center;
            padding: 1rem 0;
            font-family: 'Libre Barcode 128', cursive;
            font-size: 2.5rem;
        }
    </style>
    
    <link href="{{asset('css/dashboard.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap" rel="stylesheet">
</head>
<body class="bg-light">
    
    
    <div class="container-fluid">
        <div class="row">
            @include("admin/sidenav")
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3 no-print">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="#" onclick="history.back()" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="bi bi-printer me-1"></i> Print Receipt
                    </button>
                </div>
                
                @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                </div>
                @endif
            </main>
        </div>
    </div>
    
    <!-- Receipt Content -->
    <div class="container my-4">
        <div class="receipt-container">
               @php
        $getName = DB::table('system')->first();
        $Name = $getName->bName ?? 'SALSTECH';
    @endphp
            <!-- Receipt Header -->
            <div class="receipt-header">
                <h3 class="receipt-title">{{ $Name }}</h1>
                <p class="mb-0">OFFICIAL SALES RECEIPT</p>
                <div class="barcode">*{{$sales->sales_id ?? '000000'}}*</div>
            </div>
            
            <!-- Business Info -->
            <div class="info-section">
                <div class="info-card">
                    <div class="info-card-title">
                        <i class="bi bi-shop"></i> Business Information
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address:</span>
                        <span class="info-value">{{$getName->address ?? "Not specified"}}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Contact:</span>
                        <span class="info-value">{{$getName->phone ?? "Not specified"}}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{$getName->email ?? "Not specified"}}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Method:</span>
                        <span class="info-value">
                            {{$getName->lipaCode ?? "N/A"}} ({{$getName->lipaName ?? "N/A"}})
                        </span>
                    </div>
                </div>
                
                <!-- Customer Info -->
                <div class="info-card">
                    <div class="info-card-title">
                        <i class="bi bi-person"></i> Customer Information
                    </div>
                    <div class="info-row">
                        <span class="info-label">Customer Name:</span>
                        <span class="info-value">{{$sales->cName ?? "Walk-in Customer"}}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Customer Phone:</span>
                        <span class="info-value">{{$sales->cPhone ?? "N/A"}}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Receipt #:</span>
                        <span class="info-value">{{$sales->sales_id ?? "N/A"}}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date:</span>
                        <span class="info-value">{{ now()->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Sales Items -->
            <div class="info-section">
                <table class="receipt-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $salex = DB::table('sales')
                                    ->where('sales_id', '=', $sales->sales_id)
                                    ->get();
                        $salez = DB::table('sales')
                                    ->where('salesName', '=', $sales->salesName)
                                    ->sum('totalPrice');
                        @endphp
                        
                        @foreach ($salex as $index => $sale)
                        @php
                        $pNames = DB::table('products')
                                    ->where('product_id', '=', $sale->productId)
                                    ->first();
                        @endphp
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{ ($pNames->name01 ?? '') . ' ' . ($pNames->name02 ?? 'N/A') ?: 'Unknown Product' }}</td>
                            <td>{{$sale->pQuantity}}</td>
                            <td>Tsh.{{ number_format($sale->totalPrice) }}</td>
                        </tr>
                        @endforeach
                        
                        <!-- Totals -->
                        <tr class="total-row">
                            <td colspan="3" class="text-end">Subtotal:</td>
                            <td>Tsh.{{ number_format($salez) ?? 0 }}</td>
                        </tr>
                        
                        @if($sales->discount ?? 0 > 0)
                        <tr class="total-row">
                            <td colspan="3" class="text-end">Discount:</td>
                            <td>-Tsh.{{ number_format($sales->discount ?? 0) }}</td>
                        </tr>
                        @endif
                        
                        @php
                        $couponCode = $sales->coupons;
                        $getAmount = DB::table('coupons')->where('couponCode', '=', $couponCode)->first();
                        $couponAmount = $getAmount->amount ?? 0;
                        @endphp
                        
                        @if($couponAmount > 0)
                        <tr class="total-row">
                            <td colspan="3" class="text-end">Coupon ({{$sales->coupons}}):</td>
                            <td>-Tsh.{{ number_format($couponAmount) }}</td>
                        </tr>
                        @endif
                        
                        <tr class="grand-total">
                            <td colspan="3" class="text-end">GRAND TOTAL:</td>
                            <td>Tsh.{{ number_format($salez - ($sales->discount ?? 0) - $couponAmount) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Receipt Footer -->
            <div class="receipt-footer">
                <p>Thank you for your business!</p>
                <p class="mb-0">This receipt serves as your official proof of purchase.</p>
                <div class="mt-3">
                    <small>Receipt ID: {{$sales->sales_id ?? 'N/A'}} | Generated on: {{ now()->format('M d, Y h:i A') }}</small>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Automatically trigger print when page loads (optional)
        // window.addEventListener('load', function() {
        //     window.print();
        // });
    </script>
</body>
</html>