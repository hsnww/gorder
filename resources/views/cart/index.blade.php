@extends('layouts.shop')

@section('main')
    <div class="container">
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-5">

            <h1>{{__('labels.Your Cart')}}</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
           @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @php
            $cart = session('cart', []);
        @endphp

        @if (count($cart) > 0)
            <table class="table">
                <thead>
                <tr>
                    <th>{{__('labels.product')}}</th>
                    <th>{{__('labels.Quantity')}}</th>
                    <th>{{__('labels.actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($cart as $item)
                    @php
                        $product = \App\Models\Product::find($item['product_id']);
                    @endphp
                    <tr>
                        <td>
                            <img src="{{ asset('storage/images/' . $product->photo) }}" alt="{{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}" width="50">
                            {{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}
                        </td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>
                            <!-- زر حذف المنتج -->
                            <form action="{{ route('cart.remove') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                <button type="submit" class="btn btn-danger btn-sm">{{__('labels.Remove')}}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <a href="{{ route('cart.calculate') }}" class="btn btn-success mb-5">{{__('labels.Calculate Cheapest')}}</a>
        @else
            <p>{{__('labels.Your cart is empty!')}}!</p>
        @endif
    </div>
    </section>
    </div>
@endsection
