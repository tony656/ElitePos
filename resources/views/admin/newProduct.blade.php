<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Add Product</title>
    @include("links")
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --danger-color: #f72585;
            --success-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --dark-text: #2b2d42;
            --light-text: #8d99ae;
            --border-radius: 12px;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-text);
        }
        
        .form-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1rem 2rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .section-title {
            color: #4361ee;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(67, 97, 238, 0.1);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark-text);
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.1);
        }
        
        textarea.form-control {
            min-height: 120px;
        }
        
        .btn-submit {
            background-color: #4361ee;
            color: white;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            width: 100%;
            max-width: 300px;
        }
        
        .btn-submit:hover {
            background-color: #3f37c9;
            transform: translateY(-2px);
        }
        
        .btn-cancel {
            background-color: white;
            color: #f72585;
            border: 1px solid #f72585;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover {
            background-color: rgba(247, 37, 133, 0.1);
        }
        
        .image-upload-container {
            background: linear-gradient(135deg, #30C5FF 0%, #4361ee 100%);
            border-radius: 12px;
            padding: 1.5rem;
            color: white;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .image-upload-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(48, 197, 255, 0.2);
        }
        
        .image-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .image-upload-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .alert {
            border-radius: 12px;
        }
        
        @media (max-width: 768px) {
            .form-container {
                padding: 1.5rem;
            }
            
            .section-title {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    

<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <div class="form-header d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="bi bi-plus-circle"></i> Add New Product
            </h4>
            <a href="products" class="btn btn-cancel">
                <i class="bi bi-x-lg"></i> Cancel
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success mb-4">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
        </div>
        @endif

        <form action="addProducts" method="post" enctype="multipart/form-data">
            @csrf
            
            <div class="form-container">
                <h5 class="section-title">
                    <i class="bi bi-image"></i> Product Image
                </h5>
                
                <div class="image-upload-container">
                    <label for="image" class="image-upload-label">
                        <i class="bi bi-cloud-arrow-up image-upload-icon"></i>
                        <span>Click to upload product image</span>
                        <input type="file" class="d-none" id="image" name="image">
                    </label>
                    <small>Recommended size: 800x800px (JPEG, PNG)</small>
                </div>
            </div>

            <div class="form-container">
                <h5 class="section-title">
                    <i class="bi bi-card-text"></i> Basic Details
                </h5>
                
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label for="name01" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name01" name="name01" placeholder="Enter product name" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="name02" class="form-label">Brand/Manufacturer</label>
                        <input type="text" class="form-control" id="name02" name="name02" placeholder="Enter brand name" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select id="category" name="category" class="form-select" required>
                            <option value="" disabled selected>Select category</option>
                            <option value="Foods">Foods</option>
                            <option value="Drinks">Drinks</option>
                            <option value="Furniture">Furniture</option>
                            <option value="devices">Electronic Devices</option>
                            <option value="Farming">Farming</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="unit" class="form-label">Unit Measurement</label>
                        <select id="unit" name="unit" class="form-select" required>
                            <option value="" disabled selected>Select unit</option>
                            <option value="pieces">Pieces</option>
                            <option value="box">Box</option>
                            <option value="set">Set</option>
                            <option value="meter">Meter</option>
                            <option value="Kg">Kilogram (Kg)</option>
                            <option value="liter">Liter</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Enter product description"></textarea>
                </div>
            </div>

            <div class="form-container">
                <h5 class="section-title">
                    <i class="bi bi-tags"></i> Pricing Management
                </h5>
                
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label for="bPrice" class="form-label">Cost Price</label>
                        <div class="input-group">
                            <span class="input-group-text">Tsh</span>
                            <input type="number" class="form-control" id="bPrice" name="bPrice" placeholder="0.00" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="sPrice" class="form-label">Selling Price</label>
                        <div class="input-group">
                            <span class="input-group-text">Tsh</span>
                            <input type="number" class="form-control" id="sPrice" name="sPrice" placeholder="0.00" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="wholesale" class="form-label">Wholesale Price</label>
                        <div class="input-group">
                            <span class="input-group-text">Tsh</span>
                            <input type="number" class="form-control" id="wholesale" name="wholesale" placeholder="0.00">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <label for="discount" class="form-label">Discount Limit</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="discount" name="discount" placeholder="0" min="0">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-container">
                <h5 class="section-title">
                    <i class="bi bi-box-seam"></i> Stock & Inventory
                </h5>
                
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label for="quantity" class="form-label">Current Stock Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Stock Location</label>
                        <input type="text" class="form-control" id="location" name="location" placeholder="Enter storage location">
                    </div>
                </div>
            </div>

            <div class="form-container">
                <h5 class="section-title">
                    <i class="bi bi-truck"></i> Supplier/Vendor
                </h5>
                
                <div class="mb-3">
                    <label for="supplier" class="form-label">Supplier</label>
                    <select id="supplier" name="supplier" class="form-select" required>
                        <option value="" disabled selected>Select Supplier</option>
                        @php
                            $fetch = DB::table('vendors')->get();
                        @endphp
                        @foreach ($fetch as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-container">
                <h5 class="section-title">
                    <i class="bi bi-calendar-check"></i> Expiry Date Tracking
                </h5>
                
                <div class="mb-3">
                    <label for="expiry" class="form-label">Expiry Date</label>
                    <input type="month" class="form-control" id="expiry" name="expiry" required>
                </div>
            </div>

            <div class="text-center my-4">
                <button type="submit" class="btn btn-primary p-3 w-100" name="saveProduct">
                    <i class="bi bi-save"></i> Save Product
                </button>
            </div>
        </form>
    </main>
  </div>
</div>

<script>
    // Preview image before upload
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const uploadLabel = document.querySelector('.image-upload-label');
                uploadLabel.innerHTML = `
                    <img src="${e.target.result}" style="max-height: 100px; border-radius: 8px; margin-bottom: 10px;">
                    <span>Click to change image</span>
                `;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
</body>
</html>