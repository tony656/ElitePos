<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}}</title>
    @include("links")
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .btn-back {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back:hover {
            transform: translateX(-5px);
            color: #ffd700;
        }

        .btn-report {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-report:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .stats-container {
            max-width: 100%;
            margin: 0 auto 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 5px solid;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card.total-products {
            border-left-color: #667eea;
        }

        .stat-card.out-of-stock {
            border-left-color: #f56565;
        }

        .stat-card.expired {
            border-left-color: #ed8936;
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .stat-card-title {
            font-size: 14px;
            color: #718096;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 24px;
        }

        .stat-card.total-products .stat-card-icon {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .stat-card.out-of-stock .stat-card-icon {
            background: rgba(245, 101, 101, 0.1);
            color: #f56565;
        }

        .stat-card.expired .stat-card-icon {
            background: rgba(237, 137, 54, 0.1);
            color: #ed8936;
        }

        .stat-card-value {
            font-size: 32px;
            font-weight: 700;
            color: #1a202c;
        }

        .content-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 20px;
        }

        .search-filter-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .search-filter-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: end;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
        }

        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .search-label {
            font-weight: 600;
            color: #2d3748;
            display: block;
            margin-bottom: 8px;
        }

        .table-section {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .table {
            margin: 0;
            border-collapse: collapse;
        }

        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .table thead th {
            padding: 18px;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table tbody td {
            padding: 16px 18px;
            vertical-align: middle;
            color: #2d3748;
        }

        .product-name {
            font-weight: 600;
            color: #667eea;
        }

        .company-name {
            color: #718096;
            font-size: 14px;
        }

        .btn-manage {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
        }

        .btn-manage:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .no-products {
            text-align: center;
            padding: 50px 20px;
            color: #718096;
        }

        .no-products-icon {
            font-size: 48px;
            color: #cbd5e0;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .header-top {
                flex-direction: column;
                text-align: center;
            }

            .search-filter-row {
                grid-template-columns: 1fr;
            }

            .table {
                font-size: 12px;
            }

            .table thead th,
            .table tbody td {
                padding: 12px 8px;
            }

            .stat-card-value {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    @include("user/header")
    
    <!-- Header Section -->
    <div class="header-section">
        <div class="header-top d-flex text-light justify-content-between">
            <h4>
              All Products
            </h4>
            <button class="btn-report" onclick="downloadReport()">
                <i class="fas fa-download"></i> Request Report
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card total-products" onclick="location.href='products';">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Products</span>
                <div class="stat-card-icon">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
            <div class="stat-card-value">
                @php
                $TProducts = DB::table('products')->count();
                @endphp
                {{number_format($TProducts)}}
            </div>
        </div>

        <div class="stat-card out-of-stock" onclick="location.href='products';">
            <div class="stat-card-header">
                <span class="stat-card-title">Out of Stock</span>
                <div class="stat-card-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
            <div class="stat-card-value">
                @php
                $ofs = DB::table('products')
                    ->where('quantity', '<', 1)
                    ->count();
                @endphp
                {{number_format($ofs)}}
            </div>
        </div>

        <div class="stat-card expired" onclick="location.href='products';">
            <div class="stat-card-header">
                <span class="stat-card-title">Expired</span>
                <div class="stat-card-icon">
                    <i class="fas fa-hourglass-end"></i>
                </div>
            </div>
            <div class="stat-card-value">
                @php
                $cureemtMonth = date("Y-m"); 
                $expired = DB::table('products')
                    ->where('expire', '<', $cureemtMonth)
                    ->count();
                @endphp
                {{number_format($expired)}}
            </div>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="content-container">
        <div class="search-filter-section">
            <div class="search-filter-row">
                <div>
                    <label class="search-label">Search Products</label>
                    <input type="search" class="form-control" id="product-name" placeholder="Search by name or company...">
                    <div class="container border-0" id="product-results"></div>
                </div>
                <div>
                    <label class="search-label">Sort By</label>
                    <select name="filter" class="form-select">
                        <option value="all">A - Z</option>
                        <option value="Low">Low to High</option>
                        <option value="High">High to low</option>
                        <option value="Expire">Expire date</option>
                        <option value="stock">Out of Stock</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="table-section">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Company Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($products->isEmpty())
                        <tr>
                            <td colspan="6" class="no-products">
                                <div class="no-products-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <p>No Products found.</p>
                            </td>
                        </tr>
                        @else
                        @foreach ($products as $index => $product)
                        <form action="viewProduct" method="post">
                            @csrf
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="product-name">{{$product->name02}}</span></td>
                                <td><span class="company-name">{{$product->name01}}</span></td>
                                <td>{{number_format($product->quantity)}}</td>
                                <td>{{number_format($product->sPrice)}}</td>
                                <td class="text-end">
                                    
                                    <button class="btn-manage" name="product_id" value="{{$product->product_id}}" type="submit">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        </form>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function downloadReport() {
            window.location.href = "{{ route('user.product.report.export') }}";
        }

        $(document).ready(function() {
            $('#product-name').on('input', function() {
                let query = $(this).val();
                if (query.length > 0) {
                    $.ajax({
                        url: "{{ url('user/searchProduct') }}",
                        method: 'GET',
                        data: { query: query },
                        success: function(data) {
                            $('#product-results').html(data);
                        }
                    });
                } else {
                    $('#product-results').html('');
                }
            });
        });
    </script>
</body>
</html>