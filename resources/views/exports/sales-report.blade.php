@php
    $groupedSales = $sales->groupBy('account');
@endphp

@foreach ($groupedSales as $account => $salesGroup)
    <h3 style="margin-top:30px; font-weight:bold;">
        Account: {{ $account }}
    </h3>
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse; margin-bottom:20px;">
        <thead style="background:#f4f4f4;">
            <tr>
                <th>Sale ID</th>
                <th>Sales Name</th>
                <th>Customer</th>
                <th>Served By</th>
                <th>Date</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salesGroup as $sale)
                <tr>
                    <td>{{ $sale->sales_id }}</td>
                    <td>{{ $sale->salesName }}</td>
                    <td>{{ $sale->cName }}</td>
                    <td>{{ $sale->served_by }}</td>
                    <td>{{ $sale->created_at }}</td>
                    <td>{{ $sale->totalPrice }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach
