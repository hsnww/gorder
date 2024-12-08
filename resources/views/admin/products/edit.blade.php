@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>Edit Product</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name_en }} - {{ $category->name_ar }}
                        </option>
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
                    value="{{ old('name_en', $product->name_en) }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="name_ar" class="form-label">Product Name (Arabic)</label>
                <input
                    type="text"
                    class="form-control"
                    id="name_ar"
                    name="name_ar"
                    value="{{ old('name_ar', $product->name_ar) }}"
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
                    value="{{ old('price', $product->price) }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Product Photo</label>
                <input
                    type="file"
                    class="form-control"
                    id="photo"
                    name="photo"
                    accept="image/*">
                <small class="text-muted">Leave empty to keep the current photo.</small>
                <div class="mt-3">
                    <img src="{{ asset('storage/images/' . $product->photo) }}" alt="{{ $product->name_en }}" width="100">
                </div>
            </div>

            <div class="mb-3">
                <label for="barcode" class="form-label">Barcode</label>
                <input
                    type="text"
                    class="form-control"
                    id="barcode"
                    name="barcode"
                    value="{{ old('barcode', $product->barcode) }}"
                    required>
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
