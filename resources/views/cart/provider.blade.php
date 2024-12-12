@extends('layouts.shop')

@section('main')
    <div class="container">
        <h1>{{ __('labels.Provider Cart') }}</h1>

        @php
            $providerCarts = session()->get('provider_carts', []);
            $cart = $providerCarts[$providerId] ?? null;
            $provider = \App\Models\Provider::find($providerId); // جلب معلومات المزود
        @endphp

        @if ($provider)
            <h2>{{ __('labels.Provider') }}: {{ $provider->name }}</h2>
            <p><strong>{{ __('labels.Email') }}:</strong> {{ $provider->email }}</p>
        @endif

        @if ($cart && isset($cart['products']) && count($cart['products']) > 0)
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
            <p>{{ __('labels.No products in this cart.') }}</p>
        @endif

        <a href="{{ route('providers.show', $providerId) }}" class="btn btn-primary">{{ __('labels.Back to Provider') }}</a>
    </div>
@endsection
