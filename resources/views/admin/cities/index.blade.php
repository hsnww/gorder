@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>Cities</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('admin.cities.create') }}" class="btn btn-primary mb-3">Add New Category</a>
        <table class="table table-bordered">
            <thead>
            <tr>
                 <th>Name</th>
                <th>Region</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cities as $city)
                <tr>
                    <td>{{ $city->name }}</td>
                    <td>{{ $city->region->name }}</td>
                    <td>
                        <a href="{{ route('admin.cities.edit', $city->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.cities.destroy', $city->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
