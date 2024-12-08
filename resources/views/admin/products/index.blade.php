@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>Products</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">Add New Product</a>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Name (English)</th>
                <th>Name (Arabic)</th>
                <th>Price</th>
                <th>Photo</th>
                <th>Barcode</th>
                <th colspan="2">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->category->name_en }}</td>
                    <td>{{ $product->name_en }}</td>
                    <td>{{ $product->name_ar }}</td>
                    <td>{{ $product->price }}</td>
                    <td><img src="{{ asset('storage/images/'.$product->photo) }}" alt="{{ $product->name_en }}" width="50"></td>
                    <td>{{ $product->barcode }}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                    <td>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
