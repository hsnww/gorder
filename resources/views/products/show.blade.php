@extends('layouts.shop')

@section('main')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6">
                <!-- صورة المنتج -->
                <img src="{{ asset('storage/images/' . $product->photo) }}" alt="{{ $product->name_en }}" class="img-fluid">
            </div>
            <div class="col-md-6">
                <h1>{{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}</h1>
                <p>{{__('labels.barcode')}}:{{ $product->barcode }}</p>

                <!-- إضافة كميات إلى السلة -->
                <form action="{{ route('cart.add') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">{{__('labels.Quantity')}}</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="bi bi-cart-plus-fill"></i> {{__('labels.Add to Cart')}}
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection

