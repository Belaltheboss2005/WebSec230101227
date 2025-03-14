@extends('layouts.master')

@section('title', 'Products')

@section('content')
<div class="row">
    <div class="col col-10">
        <h1>Products</h1>
    </div>
        <div class="col col-2">
            <a href="{{route('products_edit')}}" class="btn btn-success form-control">Add Product</a>
        </div>
    </div>

    <div class="row">
        @foreach($products as $product)
        <div class="card mt-2">
            <div class="card-body">
                <div class="row">
                    <div class="col col-sm-12 col-lg-4">
                        <img src="{{ asset("images/$product->photo") }}" class="img-thumbnail" alt="{{ $product->name }}" width="100%">
                    </div>
                    <div class="col col-sm-12 col-lg-8 mt-3">
                        <h3>{{ $product->name }}</h3>
                        <table class="table table-striped">
                            <tr><th width="20%">Name</th><td>{{ $product->name }}</td></tr>
                            <tr><th>Model</th><td>{{ $product->model }}</td></tr>
                            <tr><th>Code</th><td>{{ $product->code }}</td></tr>
                            <tr><th>Description</th><td>{{ $product->description }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
