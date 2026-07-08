<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
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
    @include("sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
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

  <div class="container-fluid d-flex justify-content-between bg-light p-3">
    <h4>

        Coupons
    </h4>
    <button class="btn bg-color rounded-3" data-bs-toggle="modal" data-bs-target="#generate">
        <i class="bi bi-stars"></i>
        Generate Coupon
    </button>
  </div>

  <div class="container table mt-3 p-3" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;">
    <table class="table table-sm table-striped" >
<thead>
<tr>
    <th>
        # 
    </th>
    <th>
        Coupon Code
    </th>
    <th>
       Amount
    </th>
    <th>
       Status
    </th>
    <th>
       Created at
    </th>
    <th>
        Expire Date
    </th>
    <th>
        Action
    </th>
</tr>
</thead>
<tbody>
@if($data->isEmpty())
<tr>
    <td colspan="5" class="text-center">
        No Products found.
    </td>
</tr>
@else
    @foreach ($data as $index => $coupon)
    <form action="dltcoupon" method="post">
        @csrf
<tr>
    <td>
        {{ $index + 1 }}
        
    </td>
    <td style="letter-spacing: 0.2rem;">
        {{$coupon->couponCode ?? 'N/A'}}
    </td>
    <td>
        {{(number_format($coupon->amount ?? 0))}}
    </td>
    <td>
        {{$coupon->status ?? 'N/A'}}
    </td>
    <td>
        {{$coupon->created_at ?? 'N/A'}}
    </td>
    <td>
        {{$coupon->expire ?? 'N/A'}}
    </td>
    <td>
        <input type="hidden" name="coupId" value="{{$coupon->couponCode}}">
        <button class="btn btn-sm rounded-4 bg-danger text-light" name="dltcoupon" value="{{$coupon->product_id}}">
            <i class="bi bi-trash"></i>
        </button>
    </td>
</tr>
</form>
@endforeach
@endif
</tbody>
    </table>
</div>
    </main>
  </div>
</div>
    
<div class="modal" id="generate">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    New coupons
                </h4>
            </div>
            <div class="modal-body">
                <form action="couponnew" method="post">
                    @csrf
                    <label for="quantity">
                        coupon quantity
                    </label>
                    <input type="number" name="quantity" class="form-control" placeholder="quanitiy">

                    <label for="amount">amount</label>
                    <input type="number" name="amount" class="form-control">
                    <label for="expire">
                        Expire date
                    </label>
                    <input type="date" name="expire" class="form-control">

                    <div class="container mt-3 text-center">
                        <button class="btn bg-color px-5">
                            <i class="bi bi-stars"></i>
                            Generate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>