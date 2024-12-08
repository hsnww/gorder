@extends('layouts.shop')

@section('main')
    <div class="container">
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-5">


                <h1>{{__('labels.Price Comparison for')}} {{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}
        </h1>

        <table class="table">
            <thead>
            <tr>
                <th>{{__('labels.Provider')}}</th>
                <th>{{__('labels.Email')}}</th>
                <th>{{__('labels.Price')}}</th>
                <th>{{__('labels.Available Quantity')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($productWarehouses as $warehouse)
                <tr>
                    <td>{{ $warehouse->provider_name }}</td>
                    <td>{{ $warehouse->provider_email }}</td>
                    <td>${{ number_format($warehouse->price, 2) }}</td>
                    <td>{{ $warehouse->quantity }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">{{__('labels.No providers found for this product.')}}</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <a href="{{ route('cart.index') }}" class="btn btn-primary">{{__('labels.Back to Cart')}}</a>
    </div>

        </section>
    </div>
@endsection
