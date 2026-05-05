<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offered Products Report</title>
    @include("links")
    <style>
        .card-modern {
            background: rgba(255, 255, 255, 0.97);
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .card-header-modern {
            background: linear-gradient(135deg, #e83e8c 0%, #fd59d7 100%);
            color: white;
            padding: 20px;
            border-bottom: none;
        }
        
        .card-header-modern h5 {
            margin: 0;
            font-weight: 700;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .stat-card.pink {
            background: linear-gradient(135deg, #e83e8c 0%, #fd59d7 100%);
        }
        
        .stat-card.green {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .table-modern {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table-modern thead {
            background: #f5f7ff;
            border-bottom: 2px solid #e83e8c;
        }
        
        .table-modern th {
            padding: 12px;
            text-align: left;
            color: #e83e8c;
            font-weight: 700;
            font-size: 0.85rem;
        }
        
        .table-modern td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .table-modern tbody tr:hover {
            background: #f9f9f9;
        }
        
        .offer-badge {
            display: inline-block;
            background: linear-gradient(135deg, #e83e8c 0%, #fd59d7 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="row">
    <div class="col-2">
        @include("admin/sidenav")
    </div>
    
    <div class="col">
        <div class="pt-3 px-4">
            <div class="card-modern">
                <div class="card-header-modern d-flex justify-content-between align-items-center">
                    <h5><i class="bi bi-gift"></i> Offered Products Report</h5>
                    <a href="{{ url('admin/shopReport') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Back to Reports
                    </a>
                </div>
                <div class="card-body-modern">
                    <!-- Filter Section -->
                    <div class="filter-section">
                        <form method="GET" action="{{ url('admin/offeredProductsReport') }}" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-filter"></i> Filter
                                </button>
                                <a href="{{ url('admin/offeredProductsReport') }}" class="btn btn-outline-secondary ms-2">
                                    <i class="bi bi-reset"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Stats -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="stat-card pink">
                                <div class="stat-value">{{ number_format($totalOfferedItems) }}</div>
                                <div class="stat-label">Total Free Items Given</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card green">
                                <div class="stat-value">{{ number_format($totalOrdersWithOffers) }}</div>
                                <div class="stat-label">Orders with Offers</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Table -->
                    @if(count($offeredProducts) > 0)
                    <div class="table-responsive">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Free Quantity Given</th>
                                    <th>Times Given</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($offeredProducts as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $products[$item->productId] ?? 'Unknown Product' }}</strong>
                                    </td>
                                    <td>
                                        <span class="offer-badge">
                                            <i class="bi bi-gift"></i> {{ number_format($item->total_quantity) }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($item->order_count) }} orders</td>
                                    <td>
                                        <span class="badge bg-success">Active</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-gift" style="font-size: 4rem; color: #ccc;"></i>
                        <h5 class="mt-3 text-muted">No offered products found</h5>
                        <p class="text-muted">Try adjusting the date range</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>