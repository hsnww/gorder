@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>Product Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">Add New Category</a>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Name (English)</th>
                <th>Name (Arabic)</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $category->name_en }}</td>
                    <td>{{ $category->name_ar }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
