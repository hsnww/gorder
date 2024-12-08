@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>Edit Product Category</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name_en" class="form-label">Name (English)</label>
                <input
                    type="text"
                    class="form-control"
                    id="name_en"
                    name="name_en"
                    value="{{ old('name_en', $category->name_en) }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="name_ar" class="form-label">Name (Arabic)</label>
                <input
                    type="text"
                    class="form-control"
                    id="name_ar"
                    name="name_ar"
                    value="{{ old('name_ar', $category->name_ar) }}"
                    required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
