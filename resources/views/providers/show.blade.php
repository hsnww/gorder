@extends('layouts.shop')

@section('main')
    <div class="container">
        <h1>{{ __('provider.details') }}</h1>
        <p><strong>{{ __('provider.name') }}:</strong> {{ $provider->name }}</p>
        <p><strong>{{ __('provider.email') }}:</strong> {{ $provider->email }}</p>

        <h2>{{ __('provider.products_provided') }}</h2>
        <div class="row">
            @foreach ($products as $productWarehouse)
                @php
                    $product = $productWarehouse->product;
                @endphp
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <!-- صورة المنتج -->
                        <img src="{{ asset('storage/images/' . $product->photo) }}" class="card-img-top" alt="{{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}">

                        <div class="card-body">
                            <!-- اسم المنتج -->
                            <h5 class="card-title">{{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}</h5>
                            <p class="card-text">
                                <strong>{{ __('provider.category') }}:</strong> {{ app()->getLocale() === 'ar' ? $product->category->name_ar : $product->category->name_en }}<br>
                                <strong>{{ __('provider.price') }}:</strong> ${{ number_format($productWarehouse->price, 2) }}<br>
                                <strong>{{ __('provider.available') }}:</strong> {{ $productWarehouse->quantity }}
                            </p>
                        </div>

                        <div class="card-footer">
                            <!-- زر الإضافة إلى السلة -->
                            <form action="{{ route('provider.cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="provider_id" value="{{ $provider->id }}">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="input-group">
                                    <input type="number" name="quantity" value="1" min="1" class="form-control">
                                    <button type="submit" class="btn btn-primary">{{ __('provider.add_to_cart') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
