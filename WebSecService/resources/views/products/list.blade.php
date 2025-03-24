@extends('layouts.master')

@section('title', 'Products')

@section('content')
<div class="container">
    <div class="row">
        <div class="col col-10">
            <h1>Products List</h1>
            <div class="d-flex justify-content-between mb-3">
                @can('products_edit')
                    <div class="col col-2">
                        <a href="{{ route('products_edit') }}" class="btn btn-success form-control">Add Product</a>
                    </div>
                @endcan
                <div class="col col-2">
                    <a href="{{ route('cart.show') }}" class="btn btn-primary form-control">Show Cart</a>
                </div>
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

            <!-- Search Form -->
            <form method="GET" action="{{ route('products_list') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-2">
                        <input name="keywords" type="text" class="form-control" placeholder="Search Keywords" value="{{ request()->keywords }}">
                    </div>
                    <div class="col-md-2">
                        <input name="min_price" type="number" class="form-control" placeholder="Min Price" value="{{ request()->min_price }}">
                    </div>
                    <div class="col-md-2">
                        <input name="max_price" type="number" class="form-control" placeholder="Max Price" value="{{ request()->max_price }}">
                    </div>
                    <div class="col-md-2">
                        <select name="order_by" class="form-select">
                            <option value="" disabled selected>Order By</option>
                            <option value="name" {{ request()->order_by == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="price" {{ request()->order_by == 'price' ? 'selected' : '' }}>Price</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="order_direction" class="form-select">
                            <option value="ASC" {{ request()->order_direction == 'ASC' ? 'selected' : '' }}>ASC</option>
                            <option value="DESC" {{ request()->order_direction == 'DESC' ? 'selected' : '' }}>DESC</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('products_list') }}" class="btn btn-danger">Reset</a>
                    </div>
                </div>
            </form>

            <!-- Product Cards -->
            <div class="row">
                @foreach($products as $product)
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h3>{{ $product->name }}</h3>
                                <div>
                                    @can('products_edit')
                                        <a href="{{ route('products_edit', $product->id) }}" class="btn btn-success">Edit</a>
                                    @endcan
                                    @can('products_delete')
                                        <a href="{{ route('products_delete', $product->id) }}" class="btn btn-danger">Delete</a>
                                    @endcan
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    @if($product->photo)
                                        @if(Str::startsWith($product->photo, 'http'))
                                            <img src="{{ $product->photo }}" alt="{{ $product->name }}" class="img-fluid">
                                        @else
                                            <img src="{{ asset('storage/products/' . $product->photo) }}" alt="{{ $product->name }}" class="img-fluid">
                                        @endif
                                    @else
                                        <img src="{{ asset('images/default.png') }}" alt="No Image" class="img-fluid">
                                    @endif
                                </div>

                                <div class="col-md-8">
                                    <table class="table">
                                        <tr><th>Name</th><td>{{ $product->name }}</td></tr>
                                        <tr><th>Model</th><td>{{ $product->model }}</td></tr>
                                        <tr><th>Code</th><td>{{ $product->code }}</td></tr>
                                        <tr><th>Price</th><td>{{ $product->price }} LE</td></tr>
                                        <tr><th>Description</th><td>{{ $product->description }}</td></tr>
                                        <tr><th>Stock</th><td>{{ $product->stock }}</td></tr> <!-- Display the stock quantity -->
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
