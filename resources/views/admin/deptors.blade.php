<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Debtors Management</title>
    @include("links")
    <style>
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
        
        /* Custom styles */
        .page-header {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom-width: 1px;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-in-progress {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .action-btn {
            min-width: 100px;
            border-radius: 20px;
            padding: 5px 15px;
        }
        
        .search-container {
            position: relative;
            margin-bottom: 20px;
        }
        
        #search-results {
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            background: white;
            position: absolute;
            z-index: 1000;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: none;
        }
        
        .search-result-item {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }
        
        .search-result-item:hover {
            background-color: #f8f9fa;
        }
        
        .empty-state {
            padding: 40px 20px;
            text-align: center;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #dee2e6;
        }
    </style>
    <link href="{{asset('css/dashboard.css')}}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>    
    <div class="container-fluid">
        <div class="row">
            @include("admin/sidenav")
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-3">
                    <h4 class="">
                        <i class="bi bi-people-fill me-2"></i>Supplier Credit Management
                    </h4>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-printer me-1"></i> Print Report
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-download me-1"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="search-container">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Search debtors..." aria-label="Search" id="debtor-search">
                        <button class="btn btn-primary" type="button">
                            <i class="bi bi-funnel-fill me-1"></i> Filter
                        </button>
                    </div>
                    <div id="search-results"></div>
                </div>
                
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Order ID</th>
                                    <th width="25%">Supplier</th>
                                     <th width="15%">Status</th>
                                      <th width="15%">Total Credit</th>
                                       <th width="15%">Remaining Credit</th>
                                    <th width="20%">Created At</th>                                   
                                    <th width="20%" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($orders->isEmpty())
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <h4>No Debtor Orders Found</h4>
                                            <p>There are currently no outstanding debtor orders in the system.</p>
                                        </div>
                                    </td>
                                </tr>
                                @else
                                @foreach ($orders as $index => $order)
                                <tr>
                                    <td>{{$index + 1}}</td>
                                    <td>
                                        <strong>{{$order->debt_id}}</strong>
                                    </td>
                                    <td>{{$fetch->name}}</td>
                                   
                                    <td>
                                        @php
                                        $status = 'Completed';
                                           if($order->status == NULL) {
                                            $status = "Not Completed";
                                           } else {
                                            $status = 'Completed';
                                           }
                                           @endphp
                                        <span class="status-badge">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($order->amount) }}</td>
                                    <td>{{ number_format(($order->amount - $order->paid)) }}</td>
                                     <td>
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="text-end d-flex">
                                      <div>
                                          <form action="viewOrder" method="post">
                        @csrf
                        <button class="btn btn-sm rounded-3 px-3" name="orderName" value="{{$order->orderName}}">
                            <i class="bi bi-eye"></i>
                            View
                        </button>
                      </form>
                                      </div>
                              <div class="w-100 btn-group">
                                       <button class="btn btn-outline-primary btn-sm" 
        data-bs-toggle="modal" 
        data-bs-target="#paymentModal" 
        data-orderId="{{ $order->debt_id }}">
    <i class="bi bi-cash me-1"></i> Pay
</button>
                                       <button class="btn btn-sm btn-outline-danger" 
        data-bs-toggle="modal" 
        data-bs-target="#deleteModal" 
        data-orderId="{{ $order->debt_id }}">
    <i class="bi bi-trash me-1"></i> 
</button>
                                 </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#debtor-search').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                if(searchTerm.length > 2) {
                    // In a real implementation, you would make an AJAX call here
                    // This is just a placeholder for the functionality
                    $('#search-results').html('<div class="search-result-item">Search functionality would show results here</div>').show();
                } else {
                    $('#search-results').hide();
                }
            });
            
            // Close search results when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#debtor-search, #search-results').length) {
                    $('#search-results').hide();
                }
            });
            
            // Format dates in the table
            $('.date-cell').each(function() {
                const dateText = $(this).text();
                if(dateText) {
                    const date = new Date(dateText);
                    $(this).text(date.toLocaleDateString() + ' ' + date.toLocaleTimeString());
                }
            });
        });
    </script>

    <!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="deleteForm" method="post" action="deleteDebt">
      @csrf
      <input type="hidden" name="debtId" id="modalOrderName" value="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Delete Debtor Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this Supplier Creidt order? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger" name="debtId" id="modaldeleteId">Delete</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

    <!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="paymentForm" method="post" action="madeniPay">
      @csrf
      <input type="hidden" name="debtId" id="modalOrderName" value="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="paymentModalLabel">Enter Payment Amount</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="paymentAmount" class="form-label">Amount Paying</label>
            <input type="number" min="0" step="0.01" class="form-control" id="paymentAmount" name="paymentAmount" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Pay</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
  var paymentModal = document.getElementById('paymentModal')
  paymentModal.addEventListener('show.bs.modal', function (event) {
    // Button that triggered the modal
    var button = event.relatedTarget
    // Extract orderName from data attribute
    var orderName = button.getAttribute('data-orderId')
    // Update the hidden input value in the modal form
    var inputOrderName = paymentModal.querySelector('#modalOrderName')
    inputOrderName.value = orderName
  })
</script>
<script>
  var paymentModal = document.getElementById('deleteModal')
  paymentModal.addEventListener('show.bs.modal', function (event) {
    // Button that triggered the modal
    var button = event.relatedTarget
    // Extract orderName from data attribute
    var orderName = button.getAttribute('data-orderId')
    // Update the hidden input value in the modal form
    var inputOrderName = paymentModal.querySelector('#modaldeleteId')
    inputOrderName.value = orderName
  })
</script>
</body>
</html>