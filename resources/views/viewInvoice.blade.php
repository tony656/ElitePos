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
    

@include("sidenav")

    <main class="main-content">

      <div class="container-fluid d-flex justify-content-between p-3">
          <a href="#" onclick="history.back()" class="btn  d-print-none">
            <i class="bi bi-chevron-left"></i>
            @lang('messages.back')
          </a>

        <a href="#" onclick="window.print()" class="btn btn-primary rounded-4 d-print-none">
  <i class="bi bi-printer"></i>
  @lang('messages.print')
</a>
      </div>
        <div class="container-fluid text-center bg-light rounded-5">
          @php
          $getName = DB::table('system')->first();
        @endphp
            <h3 class="fw-bold">
                {{$getName->bName ?? 'N/A'}} @lang('messages.invoice')
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
            @lang('messages.contact_info')
          </i>
          <div class="container justify-content-between d-flex">
            <div>
                @lang('messages.address'):
            </div>
            <pdiv>
                {{$getName->address ?? "N/A"}}
            </div>
            <div class="container justify-content-between d-flex">
              <div>
                  @lang('messages.contact'):
              </div>
              <pdiv>
                  {{$getName->phone ?? "N/A"}}
              </div>
              <div class="container justify-content-between d-flex">
                <div>
                      @lang('messages.email'):
                </div>
                <pdiv>
                    {{$getName->email ?? "N/A"}}
                </div>
                <div class="container justify-content-between d-flex">
                  <div>
                      @lang('messages.namba_ya_malimo')
                  </div>
                  <pdiv>
                      {{$getName->lipaCode ?? "N/A"}}
                      ({{$getName->lipaName ?? "N/A"}})
                  </div>

        </div>
        <div class="col">
          <i>
            @lang('messages.customer_info')
          </i>
          <div class="container">
            <div class="container justify-content-between d-flex">
              <div>
                  @lang('messages.customer_name')
              </div>
              <pdiv>
                  {{$invoices->first()->cName ?? "N/A"}}
              </div>
              <div class="container justify-content-between d-flex">
                <div>
                      @lang('messages.customer_phone')
                </div>
                <div>
                    {{$invoices->first()->cPhone ?? "N/A"}}
                </div>
            </div>
          <div class="container justify-content-between d-flex">
              <div>
                  @lang('messages.discount')
              </div>
              <div>
                  {{$invoices->first()->discount ?? "N/A"}}
              </div>
          </div>
          <div class="container justify-content-between d-flex">
    
        </div>
        <div class="container justify-content-between d-flex">
          <div>
            @lang('messages.total_amount')
          </div>
          <div>
              
              {{(number_format($invoices->sum('totalPrice'))) ?? 0}}
          </div>
      </div>
        <div class="container justify-content-between d-flex">
          <div>
            @lang('messages.grand_amount')
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
                          <th>@lang('messages.products_col_product_id')</th>
                          <th>@lang('messages.products_col_product')</th>
                          <th>@lang('messages.col_qty')</th>
                          <th>@lang('messages.price')</th>
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
                            <td>{{ ($pNames->name01 ?? '') . ' ' . ($pNames->name02 ?? 'N/A') ?: __('messages.unknown_product') }}</td>
                            <td>{{ $sale->pQuantity }}</td>
                            <td>{{ number_format($sale->totalPrice) }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr class="table-active">
                          <td colspan="2" class="text-end fw-bold">@lang('messages.subtotal'):</td>
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
                          <th>@lang('messages.products_col_product_id')</th>
                          <th>@lang('messages.products_col_product')</th>
                          <th>@lang('messages.col_qty')</th>
                          <th>@lang('messages.price')</th>
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
                      <td>{{ ($pNames->name01 ?? '') . ' ' . ($pNames->name02 ?? 'N/A') ?: __('messages.unknown_product') }}</td>
                      <td>{{ $sale->pQuantity }}</td>
                      <td>{{ number_format($sale->totalPrice) }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              @endif
             </div>

         </main>
@include('footer')

</body>
</html>