@extends('layouts.shop')

@section('main')
    <div class="container">
        <h1>{{ __('labels.All Carts') }}</h1>

        <!-- عرض السلة الأولية -->
        <h2>{{ __('labels.Initial Cart') }}</h2>
        @if (count($cart) > 0)
            <table class="table">
                <thead>
                <tr>
                    <th>{{ __('labels.product') }}</th>
                    <th>{{ __('labels.Quantity') }}</th>
                    <th>{{ __('labels.actions') }}</th>
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
                                <button type="submit" class="btn btn-danger btn-sm">{{ __('labels.Remove') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>{{ __('labels.Your initial cart is empty!') }}</p>
        @endif

        <!-- عرض سلال المزودين -->
        <h2>{{ __('labels.Provider Carts') }}</h2>
        @if (count($providerCarts) > 0)
            @foreach ($providerCarts as $providerId => $cart)
                <h3>{{ __('labels.Provider Cart') }} #{{ $providerId }}</h3>
                @if (isset($cart['products']) && count($cart['products']) > 0)
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{ __('labels.product') }}</th>
                            <th>{{ __('labels.Quantity') }}</th>
                            <th>{{ __('labels.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($cart['products'] as $product)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/images/' . $product['photo']) }}" alt="{{ $product['name'] }}" width="50">
                                    {{ $product['name'] }}
                                </td>
                                <td>{{ $product['quantity'] }}</td>
                                <td>
                                    <!-- زر حذف المنتج -->
                                    <form action="{{ route('provider.cart.remove') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="provider_id" value="{{ $providerId }}">
                                        <input type="hidden" name="product_id" value="{{ $product['product_id'] }}">
                                        <button type="submit" class="btn btn-danger btn-sm">{{ __('labels.Remove') }}</button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p>{{ __('labels.No products in this provider cart.') }}</p>
                @endif
            @endforeach
        @else
            <p>{{ __('labels.No provider carts available.') }}</p>
        @endif

    </div>
@endsection
