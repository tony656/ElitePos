<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Settings</title>
    @include("links")
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #f0f4ff;
            --secondary-color: #3f37c9;
            --accent-color: #30C5FF;
            --success-color: #4cc9f0;
            --danger-color: #ef476f;
            --light-bg: #f8f9fa;
            --dark-text: #1a1a2e;
            --light-text: #6c757d;
            --border-color: #e5e7eb;
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            transition: var(--transition);
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--dark-text);
            min-height: 100vh;
        }
        
        main {
            padding: 2rem !important;
        }
        
        .settings-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            margin-bottom: 2.5rem;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.15);
            position: relative;
            overflow: hidden;
        }
        
        .settings-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            pointer-events: none;
        }
        
        .settings-header h4 {
            font-size: 1.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            z-index: 1;
            margin-bottom: 0;
        }
        
        .settings-header i {
            font-size: 1.5rem;
        }
        
        .settings-section {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
        }
        
        .settings-section:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .section-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--primary-light);
        }
        
        .section-title i {
            font-size: 1.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 0.75rem;
            display: block;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-control, .form-select {
            border-radius: var(--border-radius);
            padding: 0.95rem 1.25rem;
            border: 2px solid var(--border-color);
            background-color: #fafbfc;
            font-size: 0.95rem;
            transition: var(--transition);
            margin-bottom: 1.5rem;
            width: 100%;
            font-weight: 500;
        }
        
        .form-control::placeholder {
            color: var(--light-text);
            opacity: 0.7;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            background-color: white;
            box-shadow: 0 0 0 4px var(--primary-light);
            outline: none;
        }
        
        .btn-save {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 0.95rem 2rem;
            font-weight: 600;
            width: 100%;
            max-width: 220px;
            margin-top: 1.5rem;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.25);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }
        
        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.35);
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            color: white;
        }
        
        .btn-save:active {
            transform: translateY(-1px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.25);
            font-weight: 600;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.35);
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-danger {
            color: var(--danger-color);
            border: 2px solid var(--danger-color);
        }
        
        .btn-outline-danger:hover {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: var(--primary-light);
            color: var(--primary-color);
            font-weight: 700;
            border: none;
            padding: 1.25rem;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .table tbody td {
            padding: 1.25rem;
            border-color: var(--border-color);
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: var(--light-bg);
        }
        
        .selected-products-badge {
            background: linear-gradient(135deg, var(--success-color) 0%, #00d4ff 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(76, 201, 240, 0.2);
        }
        
        .account-products-container {
            margin-top: 1.5rem;
            padding: 1.5rem;
            background: var(--primary-light);
            border-radius: var(--border-radius);
            border: 2px solid #d4deff;
        }
        
        .account-product-tag {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            margin: 0.5rem 0.5rem 0.5rem 0;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
        }
        
        .account-product-tag i {
            margin-left: 0.5rem;
            cursor: pointer;
            opacity: 0.8;
        }
        
        .account-product-tag i:hover {
            opacity: 1;
            transform: scale(1.1);
        }
        
        .modal-content {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 1.75rem;
        }
        
        .modal-header .modal-title {
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .alert {
            border: none;
            border-left: 4px solid;
            border-radius: var(--border-radius);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(76, 201, 240, 0.1) 0%, rgba(0, 212, 255, 0.05) 100%);
            border-left-color: var(--success-color);
            color: #0d6a57;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 71, 111, 0.1) 0%, rgba(255, 71, 87, 0.05) 100%);
            border-left-color: var(--danger-color);
            color: #921a40;
        }
        
        .badge {
            font-weight: 600;
            padding: 0.6rem 1rem !important;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .d-flex.justify-content-between.align-items-center {
            margin-bottom: 1.5rem;
        }
        
        hr {
            margin: 2.5rem 0;
            border: none;
            border-top: 2px solid var(--border-color);
        }
        
        /* Profile Picture Styles */
        .profile-picture-container {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: var(--primary-light);
            border-radius: var(--border-radius);
            border: 2px solid #d4deff;
        }
        
        .profile-picture-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .profile-picture-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            border: 4px solid white;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .profile-upload-control {
            flex: 1;
        }
        
        .profile-upload-control .form-control {
            padding: 0.75rem;
            background: white;
        }
        
        /* Payment Service Styles */
        .payment-service-item {
            background: var(--light-bg);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 2px solid var(--border-color);
        }
        
        .payment-service-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .remove-service {
            color: var(--danger-color);
            cursor: pointer;
            font-size: 1.25rem;
            padding: 0.5rem;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .remove-service:hover {
            background: rgba(239, 71, 111, 0.1);
        }
        
        .add-another-btn {
            width: 100%;
            padding: 1rem;
            background: var(--primary-light);
            border: 2px dashed var(--primary-color);
            color: var(--primary-color);
            border-radius: var(--border-radius);
            font-weight: 600;
            margin-top: 1rem;
        }
        
        .add-another-btn:hover {
            background: var(--primary-color);
            color: white;
        }
        
        @media (max-width: 768px) {
            main {
                padding: 1rem !important;
            }
            
            .settings-section {
                padding: 1.5rem;
            }
            
            .btn-save {
                width: 100%;
                max-width: none;
            }
            
            .settings-header {
                padding: 1.5rem;
            }
            
            .settings-header h4 {
                font-size: 1.5rem;
            }
            
            .modal-lg {
                max-width: 95%;
            }
            
            .profile-picture-container {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }
        }
    </style>
    
    <!-- Shop switching specific styles -->
    <style>
        .shop-switching-section {
            background: linear-gradient(135deg, #f0f4ff 0%, #e8f4ff 100%);
            border: 2px solid #d4deff;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .shop-option {
            padding: 1rem;
            margin-bottom: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            background: white;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .shop-option:hover {
            border-color: var(--primary-color);
            background-color: var(--primary-light);
            transform: translateX(5px);
        }
        
        .shop-option.active {
            border-color: var(--primary-color);
            background-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .shop-option .shop-name {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--dark-text);
        }
        
        .shop-option .shop-location {
            font-size: 0.85rem;
            color: var(--light-text);
            margin-top: 0.25rem;
        }
        
        .shop-option .badge-current {
            background: linear-gradient(135deg, var(--success-color) 0%, #00d4ff 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .shop-option .badge-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .shop-select-wrapper {
            position: relative;
        }
        
        .shop-select-wrapper::after {
            content: '\f078';
            font-family: 'bootstrap-icons';
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: var(--light-text);
        }
        
        #shopSelect {
            appearance: none;
            padding-right: 2.5rem;
            cursor: pointer;
        }
        
        .shop-info-text {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            background: var(--light-bg);
            border-radius: var(--border-radius);
            font-size: 0.9rem;
        }
        
        .shop-info-text i {
            color: var(--primary-color);
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    

<div class="container-fluid">
  <div class="row">
    @include("user/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">

       @if(session('success'))
      <div class="alert alert-success  d-flex justify-content-between">
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

      <div class="settings-header">
        <h4 class="mb-0">
          <i class="bi bi-gear-fill"></i> Settings
        </h4>
      </div>

      <!-- Shop Switching Section -->
      @if(isset($fetch) && $fetch->count() > 0)
      <div class="settings-section">
        <h5 class="section-title">
          <i class="bi bi-shop"></i> Shop Switching
        </h5>
        <p class="text-muted mb-4">Switch between your assigned shops. Your current active shop is highlighted.</p>
        
        <form action="/switch" method="post" id="switchShopForm">
          @csrf
          <div class="mb-3">
            <label for="shopSelect" class="form-label">Select Shop</label>
            <select name="account" id="shopSelect" class="form-select" onchange="switchShop()">
              @foreach($fetch as $shop)
                <option value="{{ $shop->id }}"
                  {{ (getSessionAccountDisplayName() == $shop->id) ? 'selected' : '' }}>
                  {{ $shop->id }}
                  @if(isset($currentShop) && $currentShop && $currentShop->name == $shop->id)
                    (Current)
                  @endif
                  @if($shop->is_primary ?? false)
                    (Primary)
                  @endif
                </option>
              @endforeach
            </select>
          </div>
          <div class="text-end">
            <button type="submit" class="btn bg btn-save" id="switchBtn">
              <i class="bi bi-arrow-repeat"></i> Switch Shop
            </button>
          </div>
        </form>
      </div>
      @endif

    </main>
  </div>
</div>

<!-- New Account Modal -->
<div class="modal fade" id="newAccount" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create New Account</h4>
        <button class="btn btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="newAccount" method="post" id="newAccountForm">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Account Name</label>
                <input type="text" class="form-control" name="name" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" class="form-control" name="location" required>
              </div>
            </div>
          </div>
          
          <div class="text-end">
            <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Create Account</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// Shop switching functionality
function switchShop() {
    const select = document.getElementById('shopSelect');
    if (select) {
        const form = document.getElementById('switchShopForm');
        if (form) {
            form.submit();
        }
    }
}
</script>
</body>
</html>