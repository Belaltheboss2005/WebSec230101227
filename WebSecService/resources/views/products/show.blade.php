@extends('layouts.master')

@section('title', 'Your Cart')

@section('content')
<div class="container">
    <h1>Your Cart</h1>
    @if($cartItems->isEmpty())
        <p>Your cart is empty.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->product->price }} LE</td>
                        <td>{{ $item->quantity * $item->product->price }} LE</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
