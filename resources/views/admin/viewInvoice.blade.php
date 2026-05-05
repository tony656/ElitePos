<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}}</title>
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
        </style>
    
    
        <!-- Custom styles for this template -->
        <link href="{{asset("css/dashboard.css")}}" rel="stylesheet">
      </head>
<body>
    

<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

      <div class="container-fluid d-flex justify-content-between p-3">
        <a href="#" onclick="history.back()" class="btn  d-print-none">
          <i class="bi bi-chevron-left"></i>
          Back
        </a>

        <a href="#" onclick="window.print()" class="btn btn-primary rounded-4 d-print-none">
  <i class="bi bi-printer"></i>
  Print
</a>
      </div>
        <div class="container-fluid text-center bg-light rounded-5">
          @php
          $getName = DB::table('system')->first();
        @endphp
            <h3 class="fw-bold">
                {{$getName->bName ?? 'N/A'}} Invoice
             </h1>
        </div>
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
      
    <div class="container-fluid">
      <div class="row">
        <div class="col">
          <i>
            Contact info.
          </i>
          <div class="container justify-content-between d-flex">
            <div>
                Address:
            </div>
            <pdiv>
                {{$getName->address ?? "N/A"}}
            </div>
            <div class="container justify-content-between d-flex">
              <div>
                  Contact:
              </div>
              <pdiv>
                  {{$getName->phone ?? "N/A"}}
              </div>
              <div class="container justify-content-between d-flex">
                <div>
                    Email:
                </div>
                <pdiv>
                    {{$getName->email ?? "N/A"}}
                </div>
                <div class="container justify-content-between d-flex">
                  <div>
                      Namba ya Malipo
                  </div>
                  <pdiv>
                      {{$getName->lipaCode ?? "N/A"}}
                      ({{$getName->lipaName ?? "N/A"}})
                  </div>

        </div>
        <div class="col">
          <i>
            Customer Info
          </i>
          <div class="container">
            <div class="container justify-content-between d-flex">
              <div>
                  Customer Name
              </div>
              <pdiv>
                  {{$invoices->first()->cName ?? "N/A"}}
              </div>
              <div class="container justify-content-between d-flex">
                <div>
                    Customer Phone
                </div>
                <div>
                    {{$invoices->first()->cPhone ?? "N/A"}}
                </div>
            </div>
          <div class="container justify-content-between d-flex">
              <div>
                  Discount
              </div>
              <div>
                  {{$invoices->first()->discount ?? "N/A"}}
              </div>
          </div>
          <div class="container justify-content-between d-flex">
    
        </div>
        <div class="container justify-content-between d-flex">
          <div>
            otal Amount
          </div>
          <div>
              
              {{(number_format($invoices->sum('totalPrice'))) ?? 0}}
          </div>
      </div>
        <div class="container justify-content-between d-flex">
          <div>
            Grand Amount
          </div>
   
      </div>
     
           </div>
        </div>
      </div>
    </div>
            <div class="container table mt-4">
              @php
                $salex = DB::table('orders')
                                ->where('order_id', '=', $invoices->first()->order_id ?? '0')
                                ->get();
                
                // Group orders by date
                $groupedOrders = $salex->groupBy(function($order) {
                    return \Carbon\Carbon::parse($order->created_at)->format('Y-m-d');
                });
              @endphp
              
              @if($groupedOrders->count() > 1)
              <!-- Date Tabs Navigation -->
              <ul class="nav nav-tabs mb-3" id="dateTabs" role="tablist">
                @foreach($groupedOrders as $date => $orders)
                  <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                            id="tab-{{ $date }}"
                            data-bs-toggle="tab"
                            data-bs-target="#content-{{ $date }}"
                            type="button"
                            role="tab"
                            aria-controls="content-{{ $date }}"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                      <i class="bi bi-calendar3 me-1"></i>
                      {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                      <span class="badge bg-primary ms-1">{{ $orders->count() }}</span>
                    </button>
                  </li>
                @endforeach
              </ul>
              
              <!-- Date Tabs Content -->
              <div class="tab-content" id="dateTabsContent">
                @foreach($groupedOrders as $date => $orders)
                  <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                       id="content-{{ $date }}"
                       role="tabpanel"
                       aria-labelledby="tab-{{ $date }}">
                    <table class="table table-striped table-sm">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Product Name</th>
                          <th>Quantity</th>
                          <th>Price</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($orders as $index => $sale)
                          @php
                            $pNames = DB::table('products')
                              ->where('product_id', '=', $sale->productId)
                              ->first();
                          @endphp
                          <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ ($pNames->name01 ?? '') . ' ' . ($pNames->name02 ?? 'N/A') ?: 'Unknown Product' }}</td>
                            <td>{{ $sale->pQuantity }}</td>
                            <td>{{ number_format($sale->totalPrice) }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr class="table-active">
                          <td colspan="2" class="text-end fw-bold">Subtotal:</td>
                          <td class="fw-bold">{{ $orders->sum('pQuantity') }}</td>
                          <td class="fw-bold">{{ number_format($orders->sum('totalPrice')) }}</td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                @endforeach
              </div>
              @else
              <!-- Single Date - No Tabs Needed -->
              <table class="table table-striped table-sm">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($salex as $index => $sale)
                    @php
                      $pNames = DB::table('products')
                        ->where('product_id', '=', $sale->productId)
                        ->first();
                    @endphp
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>{{ ($pNames->name01 ?? '') . ' ' . ($pNames->name02 ?? 'N/A') ?: 'Unknown Product' }}</td>
                      <td>{{ $sale->pQuantity }}</td>
                      <td>{{ number_format($sale->totalPrice) }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              @endif
             </div>

         </main>
  </div>
</div>
</body>
</html>