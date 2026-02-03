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
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
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
</head>
<body>
    

<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

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

       <div class="settings-section">
       <div class="d-flex justify-content-between align-items-center">
         <h5 class="section-title mb-0">
          <i class="bi bi-person-badge"></i> Shops
        </h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAccount">
          <i class="bi bi-plus-lg"></i> New Shop
        </button>
       </div>        

        <div class="my-3 table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Location</th>
                <th>Products</th>
                <th class="text-end">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($fetch as $index => $item )
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->location }}</td>
                    <td>
                      @if($item->products)
                        @php
                          $productCount = is_array($item->products) ? count($item->products) : 0;
                        @endphp
                        <span class="selected-products-badge">{{ $productCount }} products</span>
                      @else
                        <span class="text-muted">No products</span>
                      @endif
                    </td>
                    <td class="text-end">
                      <div class="d-flex justify-content-end gap-2">
                        @if ($item->name == session('account'))
                          <span class="badge bg-success px-3 py-2">
                            Active Account
                          </span>
                        @else
                          <form action="switch" method="post" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary" name="account" value="{{ $item->name }}">
                              Switch
                            </button>
                          </form>
                        @endif
                        
      
                        
                        <form action="deleteAccount" method="post" class="d-inline">
                          @csrf
                          <button class="btn btn-sm btn-outline-danger" 
                                  name="accountId" 
                                  value="{{ $item->id }}"
                                  onclick="return confirm('Are you sure you want to delete this account?')">
                            <i class="bi bi-trash"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
              @endforeach
            </tbody>
          </table>
        </div>
       </div>

      <!-- Personal Details Section -->
      <div class="settings-section">
        <h5 class="section-title">
          <i class="bi bi-person-badge"></i> Personal Details
        </h5>
        
        <!-- Personal Profile Picture -->
        <div class="profile-picture-container">
          @if($getData->personal_profile_picture ?? false)
            <img src="{{ asset('storage/' . $getData->personal_profile_picture) }}" 
                 alt="Personal Profile" 
                 class="profile-picture-preview" 
                 id="personalProfilePreview">
          @else
            <div class="profile-picture-placeholder">
              <i class="bi bi-person-circle"></i>
            </div>
          @endif
          <div class="profile-upload-control">
            <label class="form-label">Personal Profile Picture</label>
            <input type="file" 
                   class="form-control" 
                   name="personal_profile_picture" 
                   id="personalProfileInput"
                   accept="image/*">
            <small class="text-muted">Max size: 2MB. Allowed formats: JPG, PNG, GIF</small>
          </div>
        </div>
        
        <form action="personalData" method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="personal_profile_picture_path" id="personalProfilePath">
          
          <div class="mb-3">
            <label for="owner" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="owner" name="ownerName" 
                   value="{{$getData->ownerName ?? ''}}" 
                   placeholder="{{$getData->ownerName ?? 'Your full name'}}">
          </div>
          
          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="phone" 
                   value="{{$getData->phone ?? ''}}" 
                   placeholder="{{$getData->phone ?? '255 xxx xxx xxx'}}" 
                   name="phone">
          </div>
          
          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="{{$getData->email ?? ''}}" 
                   placeholder="{{$getData->email ?? 'your@email.com'}}">
          </div>
          
          <div class="text-end">
            <button type="submit" class="btn bg btn-save">
              <i class="bi bi-save"></i> Save Changes
            </button>
          </div>
        </form>
      </div>

      <!-- Business Details Section -->
      <div class="settings-section">
        <h5 class="section-title">
          <i class="bi bi-building"></i> Business Details
        </h5>
        
        <!-- Business Profile Picture -->
        <div class="profile-picture-container">
          @if($getData->business_profile_picture ?? false)
            <img src="{{ asset('storage/' . $getData->business_profile_picture) }}" 
                 alt="Business Profile" 
                 class="profile-picture-preview" 
                 id="businessProfilePreview">
          @else
            <div class="profile-picture-placeholder">
              <i class="bi bi-building"></i>
            </div>
          @endif
          <div class="profile-upload-control">
            <label class="form-label">Business Profile Picture</label>
            <input type="file" 
                   class="form-control" 
                   name="business_profile_picture" 
                   id="businessProfileInput"
                   accept="image/*">
            <small class="text-muted">Max size: 2MB. Allowed formats: JPG, PNG, GIF</small>
          </div>
        </div>
        
        <form action="businessDetails" method="post" enctype="multipart/form-data" id="businessForm">
          @csrf
          <input type="hidden" name="business_profile_picture_path" id="businessProfilePath">
          
          <div class="mb-3">
            <label for="bName" class="form-label">Business Name</label>
            <input type="text" class="form-control" id="bName" name="bName" 
                   value="{{$getData->bName ?? ''}}" 
                   placeholder="{{$getData->bName ?? 'Your business name'}}">
          </div>
          
          <div class="mb-3">
            <label for="address" class="form-label">Business Address</label>
            <input type="text" class="form-control" id="address" name="address" 
                   value="{{$getData->address ?? ''}}" 
                   placeholder="{{$getData->address ?? 'Street, City, Country'}}">
          </div>
          
          <!-- Multiple Payment Services -->
          <div class="payment-services-container" id="paymentServicesContainer">
            <label class="form-label">Payment Services</label>
            <div id="paymentServicesList">
              @if(isset($getData->payment_services) && is_array($getData->payment_services) && count($getData->payment_services) > 0)
                @foreach($getData->payment_services as $index => $service)
                  <div class="payment-service-item" data-index="{{ $index }}">
                    <div class="payment-service-header">
                      <h6 class="mb-0">Payment Service #{{ $index + 1 }}</h6>
                      @if($index > 0)
                        <button type="button" class="remove-service" onclick="removePaymentService(this)">
                          <i class="bi bi-trash"></i>
                        </button>
                      @endif
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <select name="payment_services[{{ $index }}][provider]" class="form-select mb-3">
                          <option value="">Select service provider</option>
                          <option value="Vodacom" {{ $service['provider'] == 'Vodacom' ? 'selected' : '' }}>Vodacom M-Pesa</option>
                          <option value="Tigo" {{ $service['provider'] == 'Tigo' ? 'selected' : '' }}>Tigo Pesa</option>
                          <option value="Airtel" {{ $service['provider'] == 'Airtel' ? 'selected' : '' }}>Airtel Money</option>
                          <option value="Halotel" {{ $service['provider'] == 'Halotel' ? 'selected' : '' }}>Halotel HaloPesa</option>
                          <option value="Azam Pesa" {{ $service['provider'] == 'Azam Pesa' ? 'selected' : '' }}>Azam Pesa</option>
                          <option value="Zantel" {{ $service['provider'] == 'Zantel' ? 'selected' : '' }}>Zantel EzyPesa</option>
                          <option value="Other" {{ $service['provider'] == 'Other' ? 'selected' : '' }}>Other Service</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <input type="tel" 
                               class="form-control" 
                               name="payment_services[{{ $index }}][number]" 
                               value="{{ $service['number'] ?? '' }}" 
                               placeholder="Payment number">
                      </div>
                    </div>
                  </div>
                @endforeach
              @else
                <div class="payment-service-item" data-index="0">
                  <div class="row">
                    <div class="col-md-6">
                      <select name="payment_services[0][provider]" class="form-select mb-3">
                        <option value="">Select service provider</option>
                        <option value="Vodacom">Vodacom M-Pesa</option>
                        <option value="Tigo">Tigo Pesa</option>
                        <option value="Airtel">Airtel Money</option>
                        <option value="Halotel">Halotel HaloPesa</option>
                        <option value="Azam Pesa">Azam Pesa</option>
                        <option value="Zantel">Zantel EzyPesa</option>
                        <option value="Other">Other Service</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <input type="tel" 
                             class="form-control" 
                             name="payment_services[0][number]" 
                             placeholder="Payment number">
                    </div>
                  </div>
                </div>
              @endif
            </div>
            <button type="button" class="add-another-btn" onclick="addPaymentService()">
              <i class="bi bi-plus-circle"></i> Add Another Payment Service
            </button>
          </div>
          
          <!-- Multiple Bank Accounts -->
          <div class="payment-services-container mt-4" id="bankAccountsContainer">
            <label class="form-label">Bank Accounts</label>
            <div id="bankAccountsList">
              @if(isset($getData->bank_accounts) && is_array($getData->bank_accounts) && count($getData->bank_accounts) > 0)
                @foreach($getData->bank_accounts as $index => $bank)
                  <div class="payment-service-item" data-index="{{ $index }}">
                    <div class="payment-service-header">
                      <h6 class="mb-0">Bank Account #{{ $index + 1 }}</h6>
                      @if($index > 0)
                        <button type="button" class="remove-service" onclick="removeBankAccount(this)">
                          <i class="bi bi-trash"></i>
                        </button>
                      @endif
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <input type="text" 
                               class="form-control mb-3" 
                               name="bank_accounts[{{ $index }}][name]" 
                               value="{{ $bank['name'] ?? '' }}" 
                               placeholder="Bank name">
                      </div>
                      <div class="col-md-6">
                        <input type="text" 
                               class="form-control" 
                               name="bank_accounts[{{ $index }}][account]" 
                               value="{{ $bank['account'] ?? '' }}" 
                               placeholder="Account number">
                      </div>
                    </div>
                  </div>
                @endforeach
              @else
                <div class="payment-service-item" data-index="0">
                  <div class="row">
                    <div class="col-md-6">
                      <input type="text" 
                             class="form-control mb-3" 
                             name="bank_accounts[0][name]" 
                             placeholder="Bank name">
                    </div>
                    <div class="col-md-6">
                      <input type="text" 
                             class="form-control" 
                             name="bank_accounts[0][account]" 
                             placeholder="Account number">
                    </div>
                  </div>
                </div>
              @endif
            </div>
            <button type="button" class="add-another-btn" onclick="addBankAccount()">
              <i class="bi bi-plus-circle"></i> Add Another Bank Account
            </button>
          </div>
          
          <div class="text-end mt-4">
            <button type="submit" class="btn bg btn-save">
              <i class="bi bi-save"></i> Save Changes
            </button>
          </div>
        </form>
      </div>

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
// Profile picture preview functionality
document.getElementById('personalProfileInput').addEventListener('change', function(e) {
    previewImage(e.target, 'personalProfilePreview', 'personalProfilePath');
});

document.getElementById('businessProfileInput').addEventListener('change', function(e) {
    previewImage(e.target, 'businessProfilePreview', 'businessProfilePath');
});

function previewImage(input, previewId, hiddenInputId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                // If it's a placeholder div, replace it with an img
                const parent = preview.parentNode;
                const img = document.createElement('img');
                img.id = previewId;
                img.className = 'profile-picture-preview';
                img.src = e.target.result;
                img.alt = 'Profile Preview';
                parent.replaceChild(img, preview);
            }
            
            // Upload to server and get path
            uploadProfilePicture(file, hiddenInputId);
        }
        reader.readAsDataURL(file);
    }
}

function uploadProfilePicture(file, hiddenInputId) {
    const formData = new FormData();
    formData.append('profile_picture', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    fetch('/upload-profile-picture', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(hiddenInputId).value = data.path;
        }
    })
    .catch(error => console.error('Error:', error));
}

// Payment Services Management
let paymentServiceCount = document.querySelectorAll('.payment-service-item').length;
let bankAccountCount = document.querySelectorAll('.payment-service-item').length;

function addPaymentService() {
    const container = document.getElementById('paymentServicesList');
    const newItem = document.createElement('div');
    newItem.className = 'payment-service-item';
    newItem.dataset.index = paymentServiceCount;
    
    newItem.innerHTML = `
        <div class="payment-service-header">
            <h6 class="mb-0">Payment Service #${paymentServiceCount + 1}</h6>
            <button type="button" class="remove-service" onclick="removePaymentService(this)">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        <div class="row">
            <div class="col-md-6">
                <select name="payment_services[${paymentServiceCount}][provider]" class="form-select mb-3">
                    <option value="">Select service provider</option>
                    <option value="Vodacom">Vodacom M-Pesa</option>
                    <option value="Tigo">Tigo Pesa</option>
                    <option value="Airtel">Airtel Money</option>
                    <option value="Halotel">Halotel HaloPesa</option>
                    <option value="Azam Pesa">Azam Pesa</option>
                    <option value="Zantel">Zantel EzyPesa</option>
                    <option value="Other">Other Service</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="tel" 
                       class="form-control" 
                       name="payment_services[${paymentServiceCount}][number]" 
                       placeholder="Payment number">
            </div>
        </div>
    `;
    
    container.appendChild(newItem);
    paymentServiceCount++;
}

function removePaymentService(button) {
    const item = button.closest('.payment-service-item');
    item.remove();
    // Reindex remaining items
    reindexPaymentServices();
}

function reindexPaymentServices() {
    const items = document.querySelectorAll('#paymentServicesList .payment-service-item');
    items.forEach((item, index) => {
        item.dataset.index = index;
        item.querySelector('h6').textContent = `Payment Service #${index + 1}`;
        
        // Update input names
        const providerSelect = item.querySelector('[name*="[provider]"]');
        const numberInput = item.querySelector('[name*="[number]"]');
        
        providerSelect.name = `payment_services[${index}][provider]`;
        numberInput.name = `payment_services[${index}][number]`;
    });
    paymentServiceCount = items.length;
}

// Bank Accounts Management
function addBankAccount() {
    const container = document.getElementById('bankAccountsList');
    const newItem = document.createElement('div');
    newItem.className = 'payment-service-item';
    newItem.dataset.index = bankAccountCount;
    
    newItem.innerHTML = `
        <div class="payment-service-header">
            <h6 class="mb-0">Bank Account #${bankAccountCount + 1}</h6>
            <button type="button" class="remove-service" onclick="removeBankAccount(this)">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        <div class="row">
            <div class="col-md-6">
                <input type="text" 
                       class="form-control mb-3" 
                       name="bank_accounts[${bankAccountCount}][name]" 
                       placeholder="Bank name">
            </div>
            <div class="col-md-6">
                <input type="text" 
                       class="form-control" 
                       name="bank_accounts[${bankAccountCount}][account]" 
                       placeholder="Account number">
            </div>
        </div>
    `;
    
    container.appendChild(newItem);
    bankAccountCount++;
}

function removeBankAccount(button) {
    const item = button.closest('.payment-service-item');
    item.remove();
    // Reindex remaining items
    reindexBankAccounts();
}

function reindexBankAccounts() {
    const items = document.querySelectorAll('#bankAccountsList .payment-service-item');
    items.forEach((item, index) => {
        item.dataset.index = index;
        item.querySelector('h6').textContent = `Bank Account #${index + 1}`;
        
        // Update input names
        const nameInput = item.querySelector('[name*="[name]"]');
        const accountInput = item.querySelector('[name*="[account]"]');
        
        nameInput.name = `bank_accounts[${index}][name]`;
        accountInput.name = `bank_accounts[${index}][account]`;
    });
    bankAccountCount = items.length;
}
</script>
</body>
</html>