<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Supplier Details</title>
    @include("links")
    
    <style>
        :root {
            --primary-color: #004E89;
            --secondary-color: #f8f9fa;
            --accent-color: #1a659e;
            --text-dark: #343a40;
            --text-light: #6c757d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --border-color: #e9ecef;
            --shadow: 0 2px 10px rgba(0,0,0,0.08);
            --shadow-hover: 0 4px 20px rgba(0,0,0,0.12);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .vendor-header {
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .vendor-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .vendor-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .vendor-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .vendor-card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }

        .vendor-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            color: white;
        }

        .vendor-name {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
        }

        .info-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transform: translateY(-1px);
        }

        .info-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .info-icon.location { background: linear-gradient(135deg, #ff6b6b, #ee5a24); color: white; }
        .info-icon.contact { background: linear-gradient(135deg, #4ecdc4, #44a08d); color: white; }
        .info-icon.business { background: linear-gradient(135deg, #45b7d1, #96c93d); color: white; }
        .info-icon.credit { background: linear-gradient(135deg, #f093fb, #f5576c); color: white; }
        .info-icon.bank { background: linear-gradient(135deg, #4facfe, #00f2fe); color: white; }
        .info-icon.description { background: linear-gradient(135deg, #a8edea, #fed6e3); color: #333; }

        .info-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .products-section {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .section-header {
            padding: 2rem;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .section-header h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .section-header p {
            opacity: 0.9;
            font-size: 1rem;
        }

        .products-table-container {
            overflow-x: auto;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
        }

        .products-table thead th {
            background: #f8f9fa;
            padding: 1.2rem 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-dark);
            border-bottom: 2px solid var(--border-color);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .products-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .products-table tbody tr:hover {
            background: #f8f9fa;
        }

        .products-table td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
        }

        .product-number {
            font-weight: 600;
            color: var(--primary-color);
            width: 60px;
        }

        .product-details {
            min-width: 200px;
        }

        .product-name {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .product-subtitle {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .stock-level {
            min-width: 120px;
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .stock-badge.in-stock {
            background: linear-gradient(135deg, #4ade80, #22c55e);
            color: white;
        }

        .stock-badge.low-stock {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
        }

        .stock-badge.out-of-stock {
            background: linear-gradient(135deg, #f87171, #ef4444);
            color: white;
        }

        .price {
            font-weight: 700;
            color: var(--primary-color);
            min-width: 100px;
        }

        .category {
            min-width: 120px;
        }

        .category-badge {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .actions {
            min-width: 200px;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 0.5rem;
            text-decoration: none;
        }

        .action-btn span {
            display: none;
        }

        .restock-btn {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }

        .restock-btn:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .view-btn {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .view-btn:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .delete-btn {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .delete-btn:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-state-content h5 {
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }

        .empty-state-content p {
            color: var(--text-light);
            margin-bottom: 0;
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--text-light);
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .vendor-header {
                padding: 1.5rem 0;
            }

            .vendor-header h1 {
                font-size: 2rem;
            }

            .vendor-name {
                font-size: 1.5rem;
            }

            .info-item {
                flex-direction: column;
                text-align: center;
                padding: 1rem 0.5rem;
            }

            .info-icon {
                margin-right: 0;
                margin-bottom: 0.5rem;
            }

            .products-table {
                font-size: 0.9rem;
            }

            .products-table thead th,
            .products-table td {
                padding: 0.8rem 0.5rem;
            }

            .action-btn span {
                display: inline;
            }

            .action-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .products-table-container {
                font-size: 0.8rem;
            }

            .product-details,
            .stock-level,
            .category,
            .actions {
                min-width: auto;
            }

            .action-btn {
                padding: 0.3rem 0.6rem;
                margin-right: 0.25rem;
            }
        }
    </style>
    
    
</head>
<body>
    
    
    @include("sidenav")

            <main class="main-content">
               
                <div class="vendor-header">
                    <div class="container">
                        <h3 class="mb-0">Supplier Details</h1>
                        <p class="mb-0 opacity-75">Manage Supplier information and products</p>
                    </div>
                </div>

                <div class="container">
                    <div class="vendor-card">
                        <div class="vendor-card-header">
                            <h3 class="vendor-name">{{ $fetch->name }}</h2>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon location">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">Location</div>
                                            <div class="info-value">{{ $fetch->location }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon contact">
                                            <i class="bi bi-telephone-fill"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">Contact</div>
                                            <div class="info-value">{{ $fetch->contact }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon business">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">Business Type</div>
                                            <div class="info-value">{{ $fetch->businessType }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon credit">
                                            <i class="bi bi-cash"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">Credit</div>
                                            <div class="info-value">{{ $fetch->credit }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon bank">
                                            <i class="bi bi-bank"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">Bank Details</div>
                                            <div class="info-value">{{ $fetch->bank }} ({{ $fetch->card ?? 'No Account number' }})</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-item">
                                        <div class="info-icon description">
                                            <i class="bi bi-info-circle"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">Description</div>
                                            <div class="info-value">{{ $fetch->description }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="container px-3 py-3  my-3 bg-light rounded-3 text-start">
                    
                     <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-2">#</th>
                            <th>Product Details</th>
                            <th>Stock</th>
                            <th>Cost Price</th>
                            <th>Selling Price</th>
                            <th>Category</th>
                            <th class="text-end pe-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($fetchProduct->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-box-seam fs-1 text-muted mb-2"></i>
                                    <h5 class="text-muted">No products found</h5>
                                    <p class="text-muted">Add your first product to get started</p>
                                </div>
                            </td>
                        </tr>
                        @else
                            @foreach ($fetchProduct as $index => $product)
                            <form method="post">
                                @csrf
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <strong>{{ $product->name01 }}</strong>
                                            <small class="text-muted">{{ $product->name02 }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $quantityClass = '';
                                            if($product->quantity <= 0) {
                                                $quantityClass = 'quantity-low';
                                            } elseif($product->quantity < 10) {
                                                $quantityClass = 'quantity-medium';
                                            } else {
                                                $quantityClass = 'quantity-high';
                                            }
                                        @endphp
                                        <span class="quantity-indicator {{ $quantityClass }}"></span>
                                        {{ number_format($product->quantity) }} {{ $product->unit }}
                                    </td>
                                    <td>Tsh {{ number_format($product->bPrice) }}</td>
                                    <td>Tsh {{ number_format($product->sPrice) }}</td>
                                    <td>
                                        <span class="badge-category">{{ $product->category }}</span>
                                    </td>
                                    <td class="text-end">
                                         @if ($product->stock2 > 0)
    <button class="btn btn-sm btn-view me-2" name="product_id" formaction="restockProd" value="{{ $product->product_id }}">
        <i class="bi bi-arrow-counterclockwise"></i> Restock
    </button>
@endif

                                        <button class="btn btn-sm btn-view me-2" name="product_id" formaction="viewProduct" value="{{ $product->product_id }}">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                        <button class="btn btn-sm btn-delete" name="product_id" formaction="dltProduct" value="{{ $product->product_id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </form>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                </div>

            </main>

  @include('footer')

</body>
</html>