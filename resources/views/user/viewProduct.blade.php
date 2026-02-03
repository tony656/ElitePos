<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Product Details</title>
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
        }
        
        .product-header {
            background-color: var(--secondary-color);
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        
        .product-image-container {
            width: 230px;
            height: 230px;
            border: 5px solid var(--primary-color);
            box-shadow: 0 4px 12px rgba(0, 78, 137, 0.2);
            transition: all 0.3s ease;
        }
        
        .product-image-container:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 16px rgba(0, 78, 137, 0.3);
        }
        
        .detail-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
        }
        
        .detail-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .detail-label {
            color: var(--text-light);
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.3rem;
        }
        
        .detail-value {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 1rem;
        }
        
        .stock-badge {
            font-size: 1.1rem;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            letter-spacing: 0.5px;
        }
        
        .in-stock {
            background-color: #fff;
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }
        
        .low-stock {
            background-color:#fff;
            color: var(--warning-color);
            border: 1px solid var(--warning-color);
        }
        
        .out-of-stock {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
            border: 1px solid var(--danger-color);
        }
        
        .description-box {
            background-color: var(--secondary-color);
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
            padding: 1.5rem;
        }
        
        .edit-btn {
            background-color: var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .edit-btn:hover {
            background-color: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .price-value {
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .modal-header {
            background-color: var(--primary-color);
            color: white;
        }
        
        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
            
            .product-image-container {
                width: 180px;
                height: 180px;
                margin: 0 auto 1.5rem;
            }
        }
    </style>
    
    <link href="{{asset('css/dashboard.css')}}" rel="stylesheet">
</head>
<body>
    
                @include( "user/header")

    <div class="container-fluid">

                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center product-header p-3 mb-4">
                    <a href="#" onclick="history.back()" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                    <h4 class="mb-0 text-dark">Product Details</h4>
                    @if (canUser('manage_products'))
                   <button class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#editProduct">
                        <i class="bi bi-pencil-square me-1"></i> Edit Product
                    </button>
                     @endif
                </div>
                
                <!-- Product Image -->
                <div class="d-flex justify-content-center mb-4">
                    <div class="product-image-container rounded-circle overflow-hidden">
                        <img src="{{asset('images/' . $products->img)}}" class="w-100 h-100 object-fit-cover" alt="{{$products->name01}}">
                    </div>
                </div>
                
                <!-- Product Details Grid -->
                <div class="detail-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
                    <!-- Row 1 -->
                    <div class="detail-card p-3">
                        <div class="detail-label">Product Name</div>
                        <div class="detail-value">{{$products->name01}}</div>
                    </div>
                    
                    <div class="detail-card p-3">
                        <div class="detail-label">Brand / Manufacturer</div>
                        <div class="detail-value">{{$products->name02}}</div>
                    </div>
                    
                    <!-- Row 2 -->
                    <div class="detail-card p-3">
                        <div class="detail-label">Product Code</div>
                        <div class="detail-value">{{$products->code}}</div>
                    </div>
                    
                    <div class="detail-card p-3">
                        <div class="detail-label">Quantity in Stock</div>
                        <div class="detail-value">{{$products->quantity}} {{$products->unit}}</div>
                    </div>
                    
                    <!-- Row 3 -->
                    <div class="detail-card p-3">
                        <div class="detail-label">Category</div>
                        <div class="detail-value">{{$products->category}}</div>
                    </div>
                    
                    <div class="detail-card p-3">
                        <div class="detail-label">Unit Measurement</div>
                        <div class="detail-value">{{$products->unit}}</div>
                    </div>
                    
                    <!-- Row 4 -->
                    <div class="detail-card p-3">
                        <div class="detail-label">Discount</div>
                        <div class="detail-value">{{$products->discount}}</div>
                    </div>
                    
                    <div class="detail-card p-3">
                        <div class="detail-label">Wholesale Price</div>
                        <div class="price-value">Tsh.{{ number_format($products->wholesale) }}</div>
                    </div>
                    
                    <!-- Row 5 -->
                    <div class="detail-card p-3">
                        <div class="detail-label">Cost Price</div>
                        <div class="price-value">
                            {{ ($products->bPrice) == null ? "Not Set" : "Tsh.".number_format($products->bPrice) }}
                        </div>
                    </div>
                    
                    <div class="detail-card p-3">
                        <div class="detail-label">Selling Price</div>
                        <div class="price-value">
                            {{ ($products->sPrice) == null ? "Not Set" : "Tsh.".number_format($products->sPrice) }}
                        </div>
                    </div>
                    
                    <!-- Row 6 -->
                    <div class="detail-card p-3">
                        <div class="detail-label">Supplier</div>
                        <div class="detail-value">{{$products->supplier ?? ''}}</div>
                    </div>
                    
                    <div class="detail-card p-3">
                        <div class="detail-label">Expiry Date</div>
                        <div class="detail-value">{{$products->expire ?? 'Not specified'}}</div>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="description-box mb-4">
                    <h5 class="mb-3 text-dark">Description</h5>
                    <p class="mb-0">{{$products->description ?? 'No description available for this product.'}}</p>
                </div>
                
                <!-- Stock Status -->
                <div class="d-flex justify-content-center mb-5">
                    @php
                        $stockClass = 'in-stock';
                        if($products->quantity <= 0) {
                            $stockClass = 'out-of-stock';
                        } elseif($products->quantity < 10) {
                            $stockClass = 'low-stock';
                        }
                    @endphp
                    
                    <div class="stock-badge {{$stockClass}} text-center">
                        <i class="bi bi-box-seam me-2"></i>
                        {{$products->stock}}
                    </div>
        </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProduct" tabindex="-1" aria-labelledby="editProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductLabel">Edit Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="updateProducts" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="product_id" value="{{$products->product_id}}">
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="image" name="image">
                                    <label class="input-group-text" for="image">
                                        <i class="bi bi-upload"></i>
                                    </label>
                                </div>
                                <small class="text-muted">Current image will be replaced</small>
                            </div>
                            <div class="col-md-6">
                                <div class="border p-2 text-center bg-light rounded">
                                    <small class="text-muted">Current Image</small>
                                    <img src="{{asset('images/' . $products->img)}}" class="img-thumbnail mt-2" style="max-height: 80px;" alt="Current Image">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name01" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="name01" name="name01" value="{{$products->name01}}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="name02" class="form-label">Brand/Manufacturer</label>
                                <input type="text" class="form-control" id="name02" name="name02" value="{{$products->name02}}" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" id="category" class="form-select" required>
                                    <option value="{{ $products->category }}" selected>{{ $products->category }}</option>
                                    <option value="Foods">Foods</option>
                                    <option value="Drinks">Drinks</option>
                                    <option value="Furniture">Furniture</option>
                                    <option value="devices">Electronic Devices</option>
                                    <option value="Farming">Farming</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="unit" class="form-label">Unit Measurement</label>
                                <select name="unit" id="unit" class="form-select" required>
                                    <option value="{{$products->unit}}" selected>{{$products->unit}}</option>
                                    <option value="pieces">Pieces</option>
                                    <option value="Kg">Kg</option>
                                    <option value="liter">Liter</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">Product Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="{{$products->quantity}}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="location" class="form-label">Stock Location</label>
                                <input type="text" class="form-control" id="location" name="location" value="{{$products->location}}" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="wholesale" class="form-label">Wholesale Price (Tsh.)</label>
                                <input type="number" step="0.01" class="form-control" id="wholesale" name="wholesale" value="{{$products->wholesale}}">
                            </div>
                            <div class="col-md-4">
                                <label for="bPrice" class="form-label">Cost Price (Tsh.)</label>
                                <input type="number" step="0.01" class="form-control" id="bPrice" name="bPrice" value="{{$products->bPrice}}">
                            </div>
                            <div class="col-md-4">
                                <label for="sPrice" class="form-label">Selling Price (Tsh.)</label>
                                <input type="number" step="0.01" class="form-control" id="sPrice" name="sPrice" value="{{$products->sPrice}}" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="expiry" class="form-label">Expiry Date</label>
                                <input type="month" class="form-control" id="expiry" name="expiry" value="{{$products->expire}}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{$products->description}}</textarea>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="bi bi-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enhance the form with some client-side validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            
            if(form) {
                form.addEventListener('submit', function(e) {
                    const sPrice = document.getElementById('sPrice').value;
                    const bPrice = document.getElementById('bPrice').value;
                    
                    if(parseFloat(sPrice) < parseFloat(bPrice)) {
                        e.preventDefault();
                        alert('Selling price cannot be lower than cost price!');
                        return false;
                    }
                    
                    return true;
                });
            }
        });
    </script>
</body>
</html>