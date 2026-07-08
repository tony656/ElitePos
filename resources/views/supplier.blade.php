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

        <div class="container-fluid d-flex justify-content-between bg-light p-3 border-bottom">
            <h4>
                <a href="#" onclick="history.back()" class="btn  d-print-none">
                    <i class="bi bi-chevron-left"></i>          
                  
                All Products
            </a>
            </h4>
           <h6>
            
    <button class="btn bg-color text-light" onclick="downloadReport()">
        <i class="bi bi-stars"></i>
        Request Report
    </button>
    <script>
        function downloadReport() {
            window.location.href = "{{ route('product.report.export') }}";
        }
    </script>
    
    
           </h6>
        </div>

        <div class="btn-group w-100 my-3">
            <button class="btn  p-3 text-body bg-light mx-3  rounded-4 stylish-btn">
                <div class="container text-start">
                <h6>
                    Total Products
                </h6>
            </div>
            <div class="container d-flex justify-content-between">
                @php
                $TProducts = DB::table('products')
                                ->count();
@endphp      
<span>
    <svg width="60px" height="60px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M20 10L12 5L4 10L12 15L20 10Z" stroke="#222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20 14L12 19L4 14" stroke="#222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></span>
<h3>
{{(number_format($TProducts))}}
</h2>
            </div>
            
        </button>

        <button class="btn  p-3 text-body bg-light mx-3  rounded-4 stylish-btn">
            <div class="container text-start">
                        <h6>
                           Out of Stock
                        </h6>
                    </div>
                    <div class="container d-flex justify-content-between">
                        @php
                        $ofs = DB::table('products')
                                        ->where('quantity', '<', 1)
                                        ->count();
        @endphp      
        <span>
            <svg width="60px" height="60px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M13.0618 4.4295C12.6211 3.54786 11.3635 3.54786 10.9228 4.4295L3.88996 18.5006C3.49244 19.2959 4.07057 20.2317 4.95945 20.2317H19.0252C19.914 20.2317 20.4922 19.2959 20.0947 18.5006L13.0618 4.4295ZM9.34184 3.6387C10.4339 1.45376 13.5507 1.45377 14.6428 3.63871L21.6756 17.7098C22.6608 19.6809 21.228 22 19.0252 22H4.95945C2.75657 22 1.32382 19.6809 2.30898 17.7098L9.34184 3.6387Z" fill="#1C1C1C"></path> <path d="M12 8V13" stroke="#1C1C1C" stroke-width="1.7" stroke-linecap="round"></path> <path d="M12 16L12 16.5" stroke="#1C1C1C" stroke-width="1.7" stroke-linecap="round"></path> </g></svg>
        </span>
        <h3>
        {{(number_format($ofs))}}
        </h2>
                    </div>
                    
                </button>

                <button class="btn  p-3 text-body bg-light mx-3  rounded-4 stylish-btn">
                    <div class="container text-start">
                                <h6>
                                   Expired
                                </h6>
                            </div>
                            <div class="container d-flex justify-content-between">
                                @php
                                $cureemtMonth = date("Y-m"); 
                                $ofs = DB::table('products')
                                                ->where('expire', '<', $cureemtMonth)
                                                ->count();
                @endphp      
                <span>
                    <svg width="60px" height="60px" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>expire-solid</title> <g id="Layer_2" data-name="Layer 2"> <g id="icons_Q2" data-name="icons Q2"> <g> <rect width="48" height="48" fill="none"></rect> <g> <path d="M14.2,31.9h0a2,2,0,0,0-.9-2.9A11.8,11.8,0,0,1,6.1,16.8,12,12,0,0,1,16.9,6a12.1,12.1,0,0,1,11.2,5.6,2.3,2.3,0,0,0,2.3.9h0a2,2,0,0,0,1.1-3,15.8,15.8,0,0,0-15-7.4,16,16,0,0,0-4.8,30.6A2,2,0,0,0,14.2,31.9Z"></path> <path d="M16.5,11.5v5h-5a2,2,0,0,0,0,4h9v-9a2,2,0,0,0-4,0Z"></path> <path d="M45.7,43l-15-26a2,2,0,0,0-3.4,0l-15,26A2,2,0,0,0,14,46H44A2,2,0,0,0,45.7,43ZM29,42a2,2,0,1,1,2-2A2,2,0,0,1,29,42Zm2-8a2,2,0,0,1-4,0V26a2,2,0,0,1,4,0Z"></path> </g> </g> </g> </g> </g></svg>   
                </span>
                <h3>
                {{(number_format($ofs))}}
                </h2>
                            </div>
                            
                        </button>

        </div>

        
    <div class="container p-3 bg-light">
      
        <input type="search" class="form-control rounded-4" id="search-input" placeholder="Search...">
       </div>
       <script>
        $(document).ready(function() {
          $('#product-name').on('input', function() {
            let query = $(this).val();
        
            if (query.length > 0) {
              $.ajax({
                url: "{{ url('searchProduct') }}", // Adjust the URL as needed
                method: 'GET',
                data: { query: query },
                success: function(data) {
                  $('#product-results').html(data);
                }
              });
            } else {
              $('#product-results').html(''); // Clear results if input is empty
            }
          });
        
        });
      </script>

    

    <div class="container table p-3" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;">
        <table class="table table-sm border1 rounded-3 table-striped" >
<thead>
    <tr>
        <th>
            # 
        </th>
        <th>
         Name
        </th>
   
        <th>
            Quantity
        </th>
        <th>
            B.price
        </th>
        <th>
            S.price
        </th>
         <th>
            N.stock
        </th>
         <th>
            S.B.price
        </th>
           <th>
            S.S.price
        </th>
        <th class="text-end pe-3">
            Action
        </th>
    </tr>
</thead>
<tbody>
    @if($products->isEmpty())
    <tr>
        <td colspan="5" class="text-center">
            No Products found.
        </td>
    </tr>
@else
        @foreach ($products as $index => $product)
        <form action="viewProduct" method="post">
            @csrf
    <tr>
        <td>
            {{ $index + 1 }}
            
        </td>
        <td>
            {{$product->name02}} | {{$product->name01}}
        </td>
        <td>
            {{(number_format($product->quantity))}}
        
        </td>
        <td>
            {{(number_format($product->bPrice))}}
            
        </td>
        <td>
            {{(number_format($product->sPrice))}}
           
        </td>
        <td>
            {{(number_format($product->stock2))}}
        </td>
        <td>
    {{(number_format($product->stock2_bprice))}}
        </td>
        <td>
    {{(number_format($product->stock2_sprice))}}
        </td>
        <td class="text-end">
            <button class="btn btn-sm btn1 rounded-3 px-3" name="product_id" value="{{$product->product_id}}">
                <i class="bi bi-wrench"></i>
                Manage
            </button>
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
                var value = $(this).val().toLowerCase(); // Get the search input value
                $('.table tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1) // Show/Hide rows based on the search input
                });
            });
        });
        </script>
    </main>
@include('footer')

</body>
</html>