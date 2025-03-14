@extends('layouts.master')
@section('title', 'Mini Test Page')
@section('content')
<div class="container m-3">
    <h1 class="text-center mb-4">Supermarket bill</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach ($items as $item)
                    @php $total = $item['quantity'] * $item['price']; @endphp
                    <tr>
                        <td>{{ $item['item'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ $item['price'] }}</td>
                        <td>{{ $total }}</td>
                    </tr>
                    @php $grandTotal += $total; @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Grand Total</td>
                    <td>{{ $grandTotal }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
