@extends('layouts.app')

@section('content')
<div>
    <h3>
        Product Report
    </h2>
</div>
<br>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Product code</th>
            <th>Primary Name</th>
            <th>Secondary Name</th>
            <th>Product Quantity</th>
            <th>Product Price</th>
            <th>Stock</th>
            <th>Expire</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $index => $product)
        <tr>
                <td>{{ ($index + 1)}}</td>
                <td>{{$product->code}}</td>
                <td>{{$product->name01}}</td>
                <td>{{$product->name02}}</td>
                <td>{{$product->quantity }}</td>
                <td>{{$product->sprice}}</td>
                <td>{{$product->stock }}</td>
                <td>{{ $product->expire }}</td>       

            </tr>
        @endforeach
    </tbody>
</table>
@endsection
