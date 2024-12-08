@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>Add New Product</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="" disabled selected>Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name_en }} - {{ $category->name_ar }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="name_en" class="form-label">Product Name (English)</label>
                <input
                    type="text"
                    class="form-control"
                    id="name_en"
                    name="name_en"
                    value="{{ old('name_en') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="name_ar" class="form-label">Product Name (Arabic)</label>
                <input
                    type="text"
                    class="form-control"
                    id="name_ar"
                    name="name_ar"
                    value="{{ old('name_ar') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input
                    type="number"
                    class="form-control"
                    id="price"
                    name="price"
                    step="0.01"
                    value="{{ old('price') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Photo</label>
                <input
                    type="file"
                    class="form-control"
                    id="photo"
                    name="photo"
                    accept="image/*"
                    required>
            </div>

            <div class="mb-3">
                <label for="barcode" class="form-label">Barcode</label>
                <input
                    type="text"
                    class="form-control"
                    id="barcode"
                    name="barcode"
                    value="{{ old('barcode') }}"
                    required>
            </div>

            <button type="submit" class="btn btn-primary">Add Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
