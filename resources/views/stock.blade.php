<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Product Reports</title>
    @include("links")
    
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --text-dark: #5a5c69;
            --text-light: #858796;
            --success-color: #1cc88a;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --info-color: #36b9cc;
        }
        
        .report-card {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            border-left: 4px solid var(--primary-color);
            overflow: hidden;
        }
        
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: rgba(78, 115, 223, 0.1);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .product-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
        }
        
        .metric-label {
            font-size: 0.75rem;
            color: var(--text-light);
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .metric-value {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
        }
        
        .capital-badge {
            background-color: rgba(78, 115, 223, 0.1);
            color: var(--primary-color);
            border-left: 4px solid var(--primary-color);
        }
        
        .profit-badge {
            background-color: rgba(28, 200, 138, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-active {
            background-color: rgba(28, 200, 138, 0.1);
            color: var(--success-color);
        }
        
        .status-pending {
            background-color: rgba(246, 194, 62, 0.1);
            color: var(--warning-color);
        }
        
        .divider {
            border-top: 1px dashed rgba(0,0,0,0.1);
            margin: 1rem 0;
        }
        
        .currency {
            font-size: 0.8em;
            color: var(--text-light);
        }
        
        @media (max-width: 768px) {
            .metric-group {
                flex-direction: column !important;
            }
            
            .metric-col {
                margin-bottom: 1rem;
            }
        }
    </style>
    
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    
    
    <div class="container-fluid">
        <div class="row">
            @include("sidenav")
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3">
                <!-- Page Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h3 class="h2">Stock Reports</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                      
                    </div>
                </div>
                
                <!-- Report Cards Grid -->
                <div class="row g-4">
                    @foreach ($report as $index => $product)
                    <div class="col-xl-4 col-md-6">
                        <div class="card shadow-sm h-100 report-card">
                            <div class="card-header d-flex align-items-center p-3">
                                <div class="product-icon me-3">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $product->name }}</h5>
                                    <span class="status-badge {{ $product->ended_at ? 'status-active' : 'status-pending' }}">
                                        {{ $product->ended_at ? 'Completed' : 'Active' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-body p-3">
                                <!-- Product Meta -->
                                <div class="d-flex justify-content-between mb-2">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $product->created_at->format('M d, Y') }}
                                    </small>
                                    @if($product->ended_at)
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-check me-1"></i>
                                        {{ $product->ended_at->format('M d, Y') }}
                                    </small>
                                    @endif
                                </div>
                                
                                <div class="divider"></div>
                                
                                <!-- Metrics -->
                                <div class="d-flex metric-group">
                                    <div class="metric-col me-3">
                                        <div class="mb-2">
                                            <div class="metric-label">Total Quantity</div>
                                            <div class="metric-value">
                                                {{ number_format($product->quantity) }}
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="metric-label">Buying Price</div>
                                            <div class="metric-value">
                                                <span class="currency">Tsh.</span>{{ number_format($product->bPrice) }}
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="metric-label">Selling Price</div>
                                            <div class="metric-value">
                                                <span class="currency">Tsh.</span>{{ number_format($product->sPrice) }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="metric-col">
                                        <div class="mb-2">
                                            <div class="metric-label">Sold Quantity</div>
                                            <div class="metric-value">
                                                {{ number_format($product->sQuantity) }}
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="metric-label">Revenue</div>
                                            <div class="metric-value">
                                                <span class="currency">Tsh.</span>{{ number_format($product->amount) }}
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="metric-label">Profit</div>
                                            <div class="metric-value text-success">
                                                <span class="currency">Tsh.</span>{{ number_format($product->profit) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="divider"></div>
                                
                                <!-- Summary -->
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="p-2 rounded capital-badge">
                                            <div class="metric-label">Total Capital</div>
                                            <div class="metric-value">
                                                <span class="currency">Tsh.</span>{{ number_format($product->tBprice) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-2 rounded profit-badge">
                                            <div class="metric-label">Total Profit</div>
                                            <div class="metric-value">
                                                <span class="currency">Tsh.</span>{{ number_format($product->profit) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Profitability Indicator -->
                                @php
                                    $profitability = ($product->profit / max(1, $product->tBprice)) * 100;
                                    $indicatorClass = $profitability >= 20 ? 'bg-success' : ($profitability >= 10 ? 'bg-warning' : 'bg-danger');
                                @endphp
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="metric-label">Profitability</span>
                                        <span class="metric-value">{{ number_format($profitability, 2) }}%</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar {{ $indicatorClass }}" 
                                             role="progressbar" 
                                             style="width: {{ min(100, $profitability) }}%" 
                                             aria-valuenow="{{ $profitability }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Empty State -->
                @if($report->isEmpty())
                <div class="text-center py-5 my-5">
                    <div class="mb-4">
                        <i class="bi bi-graph-up text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-muted">No Product Reports Available</h4>
                    <p class="text-muted">Start tracking your product performance to see reports here</p>
                </div>
                @endif
            </main>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Add animation to cards on page load
            $('.report-card').each(function(index) {
                $(this).css('opacity', 0);
                $(this).animate({
                    opacity: 1,
                    marginTop: '0px'
                }, 300 + (index * 100));
            });
            
            // Tooltip initialization
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
    @include('footer')

</body>
</html>