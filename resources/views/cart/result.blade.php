@extends('layouts.shop')

@section('main')
    <div class="container">
        <h1>Your Cart - Cheapest Provider</h1>

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

        @if (isset($provider))
            <!-- اسم التاجر -->
            <div class="mb-4">
                <h3>Provider: {{ $provider->name }}</h3>
                <p><strong>Email:</strong> {{ $provider->email }}</p>
                <p><strong>Total Price:</strong> ${{ number_format($bestPrice, 2) }}</p>
            </div>

            <!-- جدول السلة -->
            <table class="table">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $cart = session('cart', []);
                @endphp
                @foreach ($cart as $item)
                    @php
                        $product = \App\Models\Product::find($item['product_id']);
                        $productWarehouse = \App\Models\ProductWarehouse::where('product_id', $item['product_id'])
                            ->where('provider_id', $provider->id)
                            ->first();
                    @endphp
                    <tr>
                        <td>
                            <img src="{{ asset('storage/images/' . $product->photo) }}" alt="{{ $product->name_en }}" width="50">
                            <a href="{{ route('product.compare', ['product_id' => $product->id]) }}">
                                {{ $product->name_en }}
                            </a>
                        </td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>${{ number_format($productWarehouse->price, 2) }}</td>
                        <td>${{ number_format($productWarehouse->price * $item['quantity'], 2) }}</td>
                        <td>
                            <!-- زر حذف المنتج -->
                            <form action="{{ route('cart.remove') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p class="text-danger">No provider can fulfill your order!</p>
        @endif

        <a href="{{ route('cart.index') }}" class="btn btn-primary">Back to Cart</a>
    </div>
@endsection
