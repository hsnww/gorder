@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>Add New Category</h1>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name_en" class="form-label">Name (English)</label>
                <input type="text" class="form-control" id="name_en" name="name_en" required>
            </div>
            <div class="mb-3">
                <label for="name_ar" class="form-label">Name (Arabic)</label>
                <input type="text" class="form-control" id="name_ar" name="name_ar" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
