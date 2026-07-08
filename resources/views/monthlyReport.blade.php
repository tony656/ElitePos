<div>
    <h3>
        @lang('messages.monthly_sales_report')
    </h3>
</div>
<br>
<table>
    <thead>
        <tr>
            <th>@lang('messages.id')</th>
            <th>@lang('messages.sale_id')</th>
            <th>@lang('messages.customer_name')</th>
            <th>@lang('messages.customer_phone')</th>
            <th>@lang('messages.product_name')</th>
            <th>@lang('messages.product_quantity')</th>
            <th>@lang('messages.product_price')</th>
            <th>@lang('messages.discount')</th>
            <th>@lang('messages.coupons')</th>
            <th>@lang('messages.total_price')</th>
            <th>@lang('messages.served_by')</th>
            <th>@lang('messages.date')</th>
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
