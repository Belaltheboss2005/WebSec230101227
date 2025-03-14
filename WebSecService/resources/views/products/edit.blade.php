@extends('layouts.master')

@section('title', 'Edit Product')

@section('content')
<div class="container">
    <h1>{{ $product->id ? 'Edit Product' : 'New Product' }}</h1>

    <form action="{{ route('products_save', $product->id) }}" method="post">
        {{ csrf_field() }}

        <div class="row mb-2">
            <div class="col-md-6">
                <label class="form-label">Code:</label>
                <input type="text" class="form-control" name="code" value="{{ $product->code }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Model:</label>
                <input type="text" class="form-control" name="model" value="{{ $product->model }}" required>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-6">
                <label class="form-label">Name:</label>
                <input type="text" class="form-control" name="name" value="{{ $product->name }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Price:</label>
                <input type="number" class="form-control" name="price" value="{{ $product->price }}" required>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label">Photo:</label>
            <input type="text" class="form-control" name="photo" value="{{ $product->photo }}" >
        </div>

        <div class="mb-2">
            <label class="form-label">Description:</label>
            <textarea class="form-control" name="description" required>{{ $product->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
