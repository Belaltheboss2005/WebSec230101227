@extends('layouts.master')

@section('title', 'Insufficient Credit')

@section('content')
<div class="container">
    <h1>Insufficient Credit</h1>
    <p>You do not have enough credits to add this product to the cart.</p>
    <a href="{{ route('products_list') }}" class="btn btn-primary">Back to Products</a>
</div>
@endsection
