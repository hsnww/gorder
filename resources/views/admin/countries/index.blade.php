@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>Product Categories</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('admin.countries.create') }}" class="btn btn-primary mb-3">Add New Category</a>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Code</th>
                 <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($countries as $country)
                <tr>
                    <td>{{ $country->name }}</td>
                    <td>{{ $country->code }}</td>
                    <td>
                        <a href="{{ route('admin.countries.edit', $country->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.countries.destroy', $country->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
