<div>
    <h3>
        Monthly Sales Report
    </h2>
</div>
<br>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Sale Id</th>
            <th>Customer Name</th>
            <th>Customer Phone</th>
            <th>Product Name</th>
            <th>Product Quantity</th>
            <th>Product Price</th>
            <th>Discount</th>
            <th>Coupons</th>
            <th>Total Price</th>
            <th>Served By</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{$row->salesName}}</td>
                <td>{{$row->cName}}</td>
                <td>{{$row->cPhone}}</td>
                <td>{{$prodName->name01 . " ~ ". $prodName->name02 }}</td>
                <td>{{$row->pQuantity }}</td>
                <td>{{$row->productPrice}}</td>
                <td>{{$row->discount }}</td>
                <td>{{ $row->coupons }}</td>
                <td>{{$row->totalPrice }}</td>
                <td>{{$row->served_by }}</td>
                <td>{{ $row->created_at }}</td>

            </tr>
        @endforeach
    </tbody>
</table>
