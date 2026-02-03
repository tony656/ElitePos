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

      <div class="container-fluid p-3">
        <a href="#" onclick="history.back()" class="btn">
          <i class="bi bi-chevron-left"></i>
          Back
        </a>
      </div>
        <div class="container-fluid text-center bg-light rounded-5 p-5" style="background: linear-gradient(to right, #E4E5E6, #0769a7);
">
            <h3 class="fw-bold">
                {{$orders->orderName ?? "N/A"}}
            </h3>
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
       <div class="container">
        <div class="container justify-content-between d-flex">
          <div>
              Customer Name
          </div>
          <pdiv>
              {{$orders->cName ?? "N/A"}}
          </div>
          <div class="container justify-content-between d-flex">
            <div>
                Customer Phone
            </div>
            <div>
                {{$orders->cPhone ?? "N/A"}}
            </div>
        </div>
      <div class="container justify-content-between d-flex">
          <div>
              Discount
          </div>
          <div>
              {{$orders->discount ?? "N/A"}}
          </div>
      </div>
      <div class="container justify-content-between d-flex">
        <div>
           Coupon Code
        </div>
        <div>
            {{$orders->coupon ?? "N/A"}}
        </div>
    </div>
    <div class="container justify-content-between d-flex">
      <div>
        Total Amount
      </div>
      <div>
        @php
if ($orders) {
    $sum = $orders->sum('totalPrice');
} else {
    $sum = 0; // or handle the null case as needed
}          
          $discount = $orders->discount ?? 0;
          $couponCode = $orders->coupons ?? null;

          $getAmount = DB::table('coupons')->where('couponCode', '=', $couponCode)->first();
          $couponAmount = $getAmount->amount  ?? 0;

          $toatal = $sum-$discount-$couponAmount;

        @endphp
          {{(number_format($toatal)) ?? "N/A"}}
      </div>
  </div>
       </div>

       <div class="container-fluid">

        <div class="row">
          <div class="col">

            <div class="container table">
       
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
                    <th>
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @php
                  $idOrder = $orders->order_id ?? null;
                  $Orders = DB::table('orders')
                                  ->where('order_id', $idOrder)                      
                                  ->get();
                                  @endphp
                                  @foreach ($Orders as $index => $order)
                               
                                  @php
                                    $pNames = DB::table('products')
                                  ->where('product_id', '=', $order->productId)                      
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
      {{$order->pQuantity}}
      </td>
      <td>
      {{(number_format($order->totalPrice))}}
      </td>
      <td>
      <form action="dltProdOrd" method="post">
      @csrf
      <input type="hidden" class="form-control" value="{{$order->order_id}}" name="OrdersIds" @readonly(true)>
      
      <input type="hidden" class="form-control" value="{{$order->productId}}" name="prodId" @readonly(true)>
      
      <button class="btn btn-close btn-sm"></button>
      
      </form>
      </td>
                  </tr>
                  @endforeach
      
                </tbody>
              </table>
             </div>
          </div>
          <div class="col-4 bg-light rounded-3 py-3">
            
          <div class="container-fluid">
            <form action="discount" method="post">
              @csrf
              <input type="hidden" name="orderName"  value="{{$orders->orderName ?? null}}">
              <input type="text" class="form-control"  onchange="this.form.submit()" name="discount" value="{{$orders->discount ?? ''}}" placeholder="Discount">
            </form>
          </div>

          <div class="container-fluid my-3">
            <form action="coupon" method="post">
              @csrf
              <input type="text" class="form-control" value="{{$orders->coupons ?? ''}}" onchange="this.form.submit()" name="coupon" placeholder="Coupon">
              <input type="hidden" name="orderName" value="{{$orders->orderName ?? null}}">

            </form>
          </div>

          <div class="container-fluid">
            <label for="total">
              Grand Total
            </label>
          
            <input type="number" class="form-control" value="{{ $Orders->sum('totalPrice') }}" readonly>
          </div>

          <div class="container text-center mt-3 ">
           <form action="updateOrder" method="post">
            @csrf
            <button class="btn w-100 p-2 btn-primary" name="OrderName" value="{{$orders->orderName ?? null}}">
              <i class="bi bi-basket3"></i>
              Update Order
            </button>
           </form>
           <br>
           <form action="payout" method="post">
            @csrf
            <input type="hidden" class="form-control" value="{{$orders->order_id ?? null}}" name="OrdersIds" @readonly(true)>

            <button class="btn w-100 p-2 btn-success">
              <i class="bi bi-cash-stack"></i>
              Confirm Payout
            </button>
           </form>
          </div>
          </div>
        </div>
       </div>

     
    </main>
  </div>
</div>
</body>
</html>