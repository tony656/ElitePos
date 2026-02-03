<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Supplier Management</title>
    
    @include("links")
    
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --text-dark: #5a5c69;
            --text-light: #858796;
        }
        button {
            border-radius: 10px !important;
        }
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }
        
        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
        
        .btn1 {
            background-color: #4e73df;
            transition: all 0.3s ease;
        }
        
        .btn1:hover {
            background-color: #2e59d9;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn2 {
            background-color: #e74a3b;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn2:hover {
            background-color: #be2617;
            transform: translateY(-1px);
        }
        
        .vendor-card {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
        }
        
        .vendor-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .credit-positive {
            color: #1cc88a;
            font-weight: bold;
        }
        
        .credit-negative {
            color: #e74a3b;
            font-weight: bold;
        }
        
        .search-container {
            background: linear-gradient(135deg, #f8f9fc 0%, #e5e9f2 100%);
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .action-buttons .btn {
            margin-left: 0.5rem;
            min-width: 80px;
        }
        
        @media (max-width: 768px) {
            .table thead {
                display: none;
            }
            
            .table tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 0.25rem;
            }
            
            .table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem;
                border-bottom: 1px solid #dee2e6;
            }
            
            .table td::before {
                content: attr(data-label);
                font-weight: bold;
                margin-right: 1rem;
            }
            
            .action-buttons {
                display: flex;
                justify-content: flex-end;
            }
        }
    </style>
    
    <link href="{{asset("css/dashboard.css")}}" rel="stylesheet">
</head>
<body>
    
    
    
    <div class="container-fluid">
        <div class="row">
            @include("admin/sidenav")

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3 bg-light rounded-3">
                <!-- Page Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <a href="#" onclick="history.back()" class="btn btn-outline-secondary me-2 d-print-none">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                        <h3 class="h2 mb-0">Supplier Management</h1>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#vendor">
                            <i class="bi bi-plus-lg me-1"></i>
                            New Supplier
                        </button>
                    </div>
                </div>

                <!-- Search Section -->
                <div class="search-container mb-4">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="search" class="form-control border-start-0" id="search-input" placeholder="Search suppliers by name, contact, or business type...">
                                <button class="btn btn-outline-secondary" type="button" id="clear-search">
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supplier Summary Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-primary shadow h-100 py-2 vendor-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Suppliers</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($fetch) }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-success shadow h-100 py-2 vendor-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Wholesale Suppliers</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $fetch->where('businessType', 'Wholesale')->count() }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-box-seam fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-info shadow h-100 py-2 vendor-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Active Credit</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            Tsh.{{ number_format($fetch->sum('credit')) }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-credit-card fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-warning shadow h-100 py-2 vendor-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Manufacturers</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $fetch->where('businessType', 'Manufacturer')->count() }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-gear fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supplier Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Supplier Directory</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="#" onclick="window.print()"><i class="bi bi-printer me-2"></i>Print</a></li>
                                <li><a class="dropdown-item" href="#" onclick="downloadReport()"><i class="bi bi-download me-2"></i>Export</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="vendorTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="ps-2" width="5%">#</th>
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Type</th>
                                        <th>Credit (Tsh)</th>
                                        <th class="text-end pe-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($fetch->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                                <h5 class="mt-3 text-muted">No Suppliers Found</h5>
                                                <p class="text-muted">Add your first supplier to get started</p>
                                                <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#vendor">
                                                    <i class="bi bi-plus-lg me-1"></i>
                                                    Add Supplier
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @else
                                        @foreach ($fetch as $index => $vendor)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $vendor->name }}</strong>
                                                <div class="text-muted small">{{ $vendor->location }}</div>
                                            </td>
                                            <td>
                                                {{ $vendor->contact }}
                                             
                                            </td>
                                            <td>
                                                <span>
                                                    {{ $vendor->businessType }}
                                                </span>
                                            </td>
                                            <td class="{{ $vendor->credit > 0 ? 'credit-positive' : 'credit-negative' }}">
                                                {{ number_format($vendor->credit) }}
                                            </td>
                                            <td class="text-end action-buttons">
                                                    <form action="" method="post" class="d-inline">
                                                    @csrf
                                                        
                                             <button formaction="viewVendor" class="btn btn-sm btn-outline-primary" name="vendorId" value="{{ $vendor->id }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                  <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" 
                                                        data-bs-target="#editVendor{{ $vendor->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                    <button formaction="dltVendeor" class="btn btn-sm btn-outline-danger" 
                                                            name="product_id" value="{{ $vendor->id }}"
                                                            onclick="return confirm('Are you sure you want to delete this vendor?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                               
                                                </form>  
                                           
                                             
                                            </td>
                                        </tr>
                                        
                                        <!-- Vendor Details Modal -->
                                        <div class="modal fade" id="vendorDetails{{ $vendor->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Supplier Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label text-muted">Supplier Name</label>
                                                                    <p class="form-control-plaintext">{{ $vendor->name }}</p>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label text-muted">Contact Information</label>
                                                                    <p class="form-control-plaintext">{{ $vendor->contact }}</p>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label text-muted">Business Address</label>
                                                                    <p class="form-control-plaintext">{{ $vendor->location }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label text-muted">Business Type</label>
                                                                    <p class="form-control-plaintext">
                                                                        <span class="badge bg-primary">{{ $vendor->businessType }}</span>
                                                                    </p>
                                                                </div>
                                                 
                                                                <div class="mb-3">
                                                                    <label class="form-label text-muted">Bank Details</label>
                                                                    <p class="form-control-plaintext">
                                                                        {{ $vendor->bank ? $vendor->bank . ' (' . $vendor->account . ')' : 'Not provided' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label text-muted">Description</label>
                                                            <div class="card bg-light p-3">
                                                                {{ $vendor->description ?: 'No description provided' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Edit Vendor Modal -->
                                        <div class="modal fade" id="editVendor{{ $vendor->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Supplier</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="updateVendor" method="post">
                                                            @csrf
                                                            <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                                                            
                                                            <div class="mb-3">
                                                                <label for="editName{{ $vendor->id }}" class="form-label">Name</label>
                                                                <input type="text" class="form-control" id="editName{{ $vendor->id }}" 
                                                                       name="name" value="{{ $vendor->name }}" required>
                                                            </div>
                                                            
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="editContact{{ $vendor->id }}" class="form-label">Contact</label>
                                                                    <input type="text" class="form-control" id="editContact{{ $vendor->id }}" 
                                                                           name="contact" value="{{ $vendor->contact }}" required>
                                                                </div>
                                                      
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label for="editAddress{{ $vendor->id }}" class="form-label">Address</label>
                                                                <input type="text" class="form-control" id="editAddress{{ $vendor->id }}" 
                                                                       name="address" value="{{ $vendor->location }}" required>
                                                            </div>
                                                            
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="editType{{ $vendor->id }}" class="form-label">Business Type</label>
                                                                    <select name="type" id="editType{{ $vendor->id }}" class="form-select" required>
                                                                        <option value="Wholesale" {{ $vendor->businessType == 'Wholesale' ? 'selected' : '' }}>Wholesale</option>
                                                                        <option value="Manufacturer" {{ $vendor->businessType == 'Manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                                                                        <option value="Distributor" {{ $vendor->businessType == 'Distributor' ? 'selected' : '' }}>Distributor</option>
                                                                        <option value="Retailer" {{ $vendor->businessType == 'Retailer' ? 'selected' : '' }}>Retailer</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="editBank{{ $vendor->id }}" class="form-label">Bank Name</label>
                                                                    <input type="text" class="form-control" id="editBank{{ $vendor->id }}" 
                                                                           name="bank" value="{{ $vendor->bank }}">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label for="editAccount{{ $vendor->id }}" class="form-label">Account Number</label>
                                                                <input type="text" class="form-control" id="editAccount{{ $vendor->id }}" 
                                                                       name="account" value="{{ $vendor->account }}">
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label for="editDescription{{ $vendor->id }}" class="form-label">Description</label>
                                                                <textarea name="description" id="editDescription{{ $vendor->id }}" 
                                                                          class="form-control" rows="3">{{ $vendor->description }}</textarea>
                                                            </div>
                                                            
                                                            <div class="d-grid gap-2">
                                                                <button type="submit" class="btn btn-primary">Update Supplier</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        @if(!$fetch->isEmpty())
                        <div class="row mt-3">
                            <div class="col-md-12 d-flex justify-content-center py-4">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="vendor">
                                    Add New Supplier
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
 
    <!-- Add Supplier Modal -->
    <div class="modal fade" id="vendor" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add New Supplier</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="newVendor" method="post">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="vendorName" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="vendorName" name="name" placeholder="Enter vendor name" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="vendorContact" class="form-label">Contact <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="vendorContact" name="contact" placeholder="Phone number or email" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="vendorAddress" class="form-label">Business Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="vendorAddress" name="address" placeholder="Physical address or location" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="businessType" class="form-label">Business Type <span class="text-danger">*</span></label>
                                <select name="type" id="businessType" class="form-select" required>
                                    <option value="" selected disabled>Select business type</option>
                                    <option value="Wholesale">Wholesale</option>
                                    <option value="Manufacturer">Manufacturer</option>
                                    <option value="Distributor">Distributor</option>
                                    <option value="Retailer">Retailer</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bankName" class="form-label">Bank Name</label>
                                <input type="text" class="form-control" id="bankName" name="bank" placeholder="Bank name (optional)">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="accountNumber" class="form-label">Account Number</label>
                                <input type="text" class="form-control" id="accountNumber" name="account" placeholder="Bank account number (optional)">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="vendorDescription" class="form-label">Description</label>
                            <textarea name="description" id="vendorDescription" class="form-control" rows="3" placeholder="Additional information about the vendor"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="bi bi-save me-1"></i> Save Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Enhanced search functionality
            $('#search-input').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#vendorTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            
            // Clear search
            $('#clear-search').on('click', function() {
                $('#search-input').val('');
                $('#vendorTable tbody tr').show();
            });
            
            // Download report function
            window.downloadReport = function() {
                window.location.href = "{{ route('admin.product.report.export') }}";
            };
            
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Auto-focus search input on page load
            $('#search-input').focus();
        });
    </script>
</body>
</html>