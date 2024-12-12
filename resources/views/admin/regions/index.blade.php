@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>Regions</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('admin.regions.create') }}" class="btn btn-primary mb-3">Add New Category</a>
        <table class="table table-bordered">
            <thead>
            <tr>
                 <th>Name</th>
                <th>Country</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($regions as $region)
                <tr>
                    <td>{{ $region->name }}</td>
                    <td>{{ $region->country->name }}</td>
                    <td>
                        <a href="{{ route('admin.regions.edit', $region->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.regions.destroy', $region->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
