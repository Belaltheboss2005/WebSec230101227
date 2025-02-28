@extends('layouts.master')

@section('title', 'Products')

@section('content')
<div class="container">
    <h1>Product List</h1>

    <!-- Search Form -->
    <form>
        <div class="row">
            <div class="col-md-3">
                <input name="keywords" type="text" class="form-control" placeholder="Search" value="{{ request()->keywords }}">
            </div>
            <div class="col-md-2">
                <input name="min_price" type="number" class="form-control" placeholder="Min Price" value="{{ request()->min_price }}">
            </div>
            <div class="col-md-2">
                <input name="max_price" type="number" class="form-control" placeholder="Max Price" value="{{ request()->max_price }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('products_edit') }}" class="btn btn-success">Add Product</a>
            </div>
        </div>
    </form>

    <!-- Display Products -->
    @foreach($products as $product)
    <div class="card mt-3">
        <div class="card-body">
            <h3>{{ $product->name }}</h3>
            <p><strong>Model:</strong> {{ $product->model }}</p>
            <p>{{ $product->description }}</p>
            <strong>Price: ${{ $product->price }}</strong>

            @if($product->photo)
                <div>
                    <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" width="100">
                </div>
            @endif

            <div class="mt-2">
                <a href="{{ route('products_edit', $product->id) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('products_delete', $product->id) }}" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
@endforeach

</div>
@endsection
