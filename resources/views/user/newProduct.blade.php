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
    <main class="container">

    <div class="container-fluid d-flex justify-content-between bg-light p-3 border-bottom">
        <h4>
            Add Products
        </h4>
       <a href="products" class="btn btn-danger">
        Cancel
       </a>
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
    <div class="container table">
       <form action="addProducts" method="post" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <small>
                --Product image--
                         </small>
                     <div class="container-fluid">
            <div class="container bg-info p-3">
                <label for="image">Product Image</label>
                <input type="file" class="form-control" name="image" requied>
            </div>
        </div>
   
        </div>
        <hr>

        <div class="container">
            <small>
                Basic details
            </small>
        </div>

        <div class="container-fluid d-flex">
            <div class="container">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name01" placeholder="Name" requied>
            </div>
            <div class="container">
                <label for="name">Secondary Name</label>
                <input type="text" class="form-control" name="name02" placeholder="Name" requied>
            </div>
        </div>

        <div class="container-fluid">
            <div class="container">
                <label for="quantity">Product Quantity</label>
                <input type="number" class="form-control" name="quantity" placeholder="Quantity" requied>
            </div>
        </div>

        <div class="container-fluid d-flex">
            <div class="container">
                <label for="price">Buying Price</label>
                <input type="number" class="form-control" name="bPrice" placeholder="Price" requied>
            </div>
            <div class="container">
                <label for="name">Selling Price</label>
                <input type="number" class="form-control" name="sPrice" placeholder="Price" requied>
            </div>
        </div>

        <div class="container-fluid">
            <div class="container">
                <label for="quantity">Expiry Date</label>
                <input type="month" class="form-control" name="expiry" placeholder="Expire" requied>
            </div>
        </div>

        <div class="container text-center mt-3">
            <button class="btn w-50 btn-success p-2" name="saveProduct">
                Save Product
            </button>
        </div>

       </form>
    </div>
    </main>
</div>
</body>
</html>