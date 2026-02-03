<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}}</title>
    @include("links")
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-bg: #f8f9fa;
            --dark-text: #2b2d42;
            --light-text: #8d99ae;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
        }
                
        .dashboard-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .header-title {
            font-weight: 600;
            color: var(--dark-text);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-primary-custom {
            background-color: var(--primary-color);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
        }
        
        .search-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .search-input {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(72, 149, 239, 0.1);
        }
        
        .data-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        .table-header {
            background-color: var(--primary-color);
            color: white;
        }
        
        .table th {
            font-weight: 500;
            padding: 1rem;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #f0f0f0;
        }
        
        .badge-business {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.75rem;
        }
        
        .badge-wholesale {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .badge-manufacturer {
            background-color: #e8f5e9;
            color: #388e3c;
        }
        
        .badge-distributor {
            background-color: #fff3e0;
            color: #f57c00;
        }
        
        .badge-retailer {
            background-color: #f3e5f5;
            color: #8e24aa;
        }
        
        .action-btn {
            border-radius: 6px;
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn-remove {
            background-color: #ffebee;
            color: var(--danger-color);
            border: none;
        }
        
        .btn-remove:hover {
            background-color: #ffcdd2;
        }
        
        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .modal-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem;
            border-bottom: none;
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark-text);
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(72, 149, 239, 0.1);
        }
        
        textarea.form-control {
            min-height: 120px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
            
            .table th, .table td {
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
    

<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-4 py-3">

        <div class="dashboard-header d-flex justify-content-between align-items-center">
            <h3 class="header-title">
                <a href="#" onclick="history.back()" class="text-decoration-none text-dark">
                    <i class="bi bi-chevron-left"></i>          
                </a>
                Customer Management
            </h3>
            <div>
                <button class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#Customer">
                    <i class="bi bi-plus-lg me-2"></i>
                    New Customer
                </button>
            </div>
        </div>
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
        <div class="search-container text-center">
            <h6 class="mb-3 fw-semibold">Search Customers</h6>
            <input type="search" class="form-control search-input" id="search-input" placeholder="Search by name, contact, business type..." >
        </div>

        <div class="data-table table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-header">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Business</th>
                        <th>Credit Limit</th>
                        <th>Description</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($fetch->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="bi bi-people-fill text-muted" style="font-size: 2.5rem;"></i>
                                <p class="mt-2 mb-0">No customers found</p>
                                <button class="btn btn-sm btn-primary-custom text-white mt-3" data-bs-toggle="modal" data-bs-target="#Customer">
                                    Add New Customer
                                </button>
                            </div>
                        </td>
                    </tr>
                    @else
                        @foreach ($fetch as $index => $customer)
                        <form action="dltCustomer" method="post">
                            @csrf
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{$customer->name}}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->address }}</td>
                                <td>
                                    <span class="badge-business badge-{{ strtolower($customer->businessType) }}">
                                        {{$customer->business}}
                                    </span>
                                </td>
                                <td>{{ number_format($customer->limits) }}</td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $customer->description }}">
                                        {{ $customer->description }}
                                    </span>
                                </td>
                                <td class="text-end">
                                  <form action="" method="post">
                                    @csrf
                                     <button formaction="customerView" class="btn btn-sm btn-primary viewCustomerBtn" 
                                        value="{{ $customer->name }}" name="name"
                                        >
                                        <i class="bi bi-eye me-1"></i> View
                                    </button>

                                    <button formaction="dltCustomer" class="btn btn-sm btn2" name="name" value="{{$customer->name}}">
                                        <i class="bi bi-trash me-1"></i> 
                                        Remove
                                    </button>
                                                                      </form>

                                </td>
                            </tr>
                        </form>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        
        <script>
            $(document).ready(function() {
                $('#search-input').on('keyup', function() {
                    var value = $(this).val().toLowerCase();
                    $('tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>
    </main>
  </div>
</div>

<!-- New Customer Modal -->
<div class="modal fade" id="Customer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="bi bi-person-plus me-2"></i>
                    Create New Customer
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="newCustomer" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Customer name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact" class="form-label">Contact</label>
                            <input type="text" class="form-control" name="contact" placeholder="Contact person name" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" placeholder="Customer address" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="type" class="form-label">Business Type</label>
                            <select name="type" class="form-select" required>
                                <option value="Uknown" selected disabled>Select Business Type</option>
                                <option value="Wholesale">Wholesale</option>
                                <option value="Manufacturer">Manufacturer</option>
                                <option value="Distributor">Distributor</option>
                                <option value="Retailer">Retailer</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="credit" class="form-label">Credit Limit</label>
                            <div class="input-group">
                                <span class="input-group-text">Tsh.</span>
                                <input type="number" class="form-control" name="credit" placeholder="Maximum credit amount" required>
                            </div>
                        </div>
                    </div>
                    <div class="my-4">
                        <label for="description" class="form-label">Allocation</label>
                        <select name="allocation" id="allocation" class="form-control">
                            <option selected disabled>Select Employee</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Additional notes about this customer"></textarea>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary text-white py-3">
                            <i class="bi bi-save me-2"></i>
                            Save Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Customer Modal -->
<div class="modal fade" id="viewCustomerModal" tabindex="-1" aria-labelledby="viewCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewCustomerModalLabel">Customer Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="customerDetailsContent">
        Loading...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var viewModal = document.getElementById('viewCustomerModal');
  viewModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var customerId = button.getAttribute('data-customerid');

    var modalBody = document.getElementById('customerDetailsContent');
    modalBody.innerHTML = 'Loading...';

    fetch(`/details/${customerId}`)
      .then(response => response.json())
      .then(data => {
        // Build HTML from returned data
        var html = `
          <p><strong>Name:</strong> ${data.name}</p>
          <p><strong>Quantity:</strong> ${data.quantity}</p>
          <p><strong>Total Price:</strong> ${data.price}</p>
          <!-- Add more fields as needed -->
        `;
        modalBody.innerHTML = html;
      })
      .catch(() => {
        modalBody.innerHTML = '<p class="text-danger">Failed to load customer details.</p>';
      });
  });
});
</script>

</body>
</html>