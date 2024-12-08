@extends('layouts.shop')

@section('main')
    <div class="container">
        <h1>{{ __('provider.details') }}</h1>
        <p><strong>{{ __('provider.name') }}:</strong> {{ $provider->name }}</p>
        <p><strong>{{ __('provider.email') }}:</strong> {{ $provider->email }}</p>

        <h2>{{ __('provider.products_provided') }}</h2>
        <div class="row">
            @foreach ($products as $productWarehouse)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <!-- صورة المنتج -->
                        <img src="{{ asset('storage/images/' . $productWarehouse->product->photo) }}" class="card-img-top" alt="{{ app()->getLocale() === 'ar' ? $productWarehouse->product->name_ar : $productWarehouse->product->name_en }}">

                        <div class="card-body">
                            <!-- اسم المنتج -->
                            <h5 class="card-title">{{ app()->getLocale() === 'ar' ? $productWarehouse->product->name_ar : $productWarehouse->product->name_en }}</h5>
                            <p class="card-text">
                                <strong>{{ __('provider.category') }}:</strong> {{ app()->getLocale() === 'ar' ? $productWarehouse->product->category->name_ar : $productWarehouse->product->category->name_en }}<br>
                                <strong>{{ __('provider.price') }}:</strong> ${{ number_format($productWarehouse->price, 2) }}<br>
                                <strong>{{ __('provider.available') }}:</strong> {{ $productWarehouse->quantity }}
                            </p>
                        </div>

                        <div class="card-footer">
                            <!-- زر الإضافة إلى السلة -->
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $productWarehouse->product->id }}">
                                <input type="hidden" name="provider_id" value="{{ $productWarehouse->provider_id }}">
                                <div class="input-group mb-2">
                                    <input type="number" name="quantity" class="form-control" min="1" max="{{ $productWarehouse->quantity }}" value="1" required>
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
