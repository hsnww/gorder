@extends('layouts.shop')

@section('main')
    <div class="container">
        <h1>{{ __('categories.details') }}</h1>
        <p><strong>{{ __('categories.name') }}:</strong> {{ app()->getLocale() === 'ar' ? $category->name_ar : $category->name_en }}</p>

        <h2>{{ __('categories.products') }}</h2>
        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <!-- صورة المنتج -->
                        <img src="{{ asset('storage/images/' . $product->photo) }}" class="card-img-top" alt="{{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}">

                        <div class="card-body">
                            <!-- اسم المنتج -->
                            <h5 class="card-title">{{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}</h5>
                            <p class="card-text">
                                <strong>{{ __('categories.price') }}:</strong> ${{ number_format($product->price, 2) }}
                            </p>
                        </div>

                        <div class="card-footer">
                            <!-- زر الإضافة إلى السلة -->
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="input-group mb-2">
                                    <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                                    <button type="submit" class="btn btn-primary">{{ __('categories.add_to_cart') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
