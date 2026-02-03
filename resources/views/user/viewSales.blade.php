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
    
@include("user/header")
<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

    <main class="container">

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
                {{$getName->bName ?? 'N/A'}}
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
                  {{$sales->cName ?? "N/A"}}
              </div>
              <div class="container justify-content-between d-flex">
                <div>
                    Customer Phone
                </div>
                <div>
                    {{$sales->cPhone ?? "N/A"}}
                </div>
            </div>
          <div class="container justify-content-between d-flex">
              <div>
                  Discount
              </div>
              <div>
                  {{$sales->discount ?? "N/A"}}
              </div>
          </div>
          <div class="container justify-content-between d-flex">
            <div>
               Coupon Code
            </div>
            <div>
              @php
                 $coupon = DB::table('coupons')->where('couponCode', '=', $sales->coupons)->first();
              $coup = $sales->coupons. "(".((number_format($coupon->amount ?? 0))).")";
              @endphp
                {{$coup ?? "N/A"}}
            </div>
        </div>
        <div class="container justify-content-between d-flex">
          <div>
            otal Amount
          </div>
          <div>
              
              {{(number_format($sales->sum('totalPrice'))) ?? "N/A"}}
          </div>
      </div>
        <div class="container justify-content-between d-flex">
          <div>
            Grand Amount
          </div>
          <div class="text-success fw-bold">
            @php
              $sum = $sales->sum('totalPrice');
              $discount = $sales->discount ?? 0;
              $couponCode = $sales->coupons;
    
              $getAmount = DB::table('coupons')->where('couponCode', '=', $couponCode)->first();
              $couponAmount = $getAmount->amount  ?? 0;
    
              $toatal = $sum-$discount-$couponAmount;
    
            @endphp
              {{(number_format($toatal)) ?? "N/A"}}
          </div>
      </div>
     
           </div>
        </div>
      </div>
    </div>
            <div class="container table mt-4">
       
              <table class="table table-striped table-sm">
                <thead>
                  <tr>
                    <th>
                      #
                    </th>
                    <th>
                    Product Name
                    </th>
                    <th>
                      Quantity
                    </th>
                    <th>
                      Price
                    </th>
                  
                  </tr>
                </thead>
                <tbody>
                  @php
                  $salex = DB::table('sales')
                                  ->where('sales_id', '=', $sales->sales_id)                      
                                  ->get();
                                  @endphp
                                  @foreach ($salex as $index => $sale)
                               
                                  @php
                                    $pNames = DB::table('products')
                                  ->where('product_id', '=', $sale->productId)                      
                                  ->first();
                                  @endphp
                  <tr>  
      <td>
      {{$index + 1}}
      </td>
      <td>
      {{$pNames->name01}}
      </td>
      <td>
      {{$sale->pQuantity}}
      </td>
      <td>
      {{(number_format($sale->totalPrice))}}
      </td>
      
                  </tr>
                  @endforeach
      
                </tbody>
              </table>
             </div>      

         </main>
  </div>
</div>
</body>
</html>