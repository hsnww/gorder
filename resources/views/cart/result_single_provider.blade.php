@extends('layouts.shop')

@section('main')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-5 mt-3">
        <h1>{{__('labels.Your Cart')}}-{{__('labels.Single Provider')}}</h1>

        <h3>{{__('labels.Provider')}}: {{ $provider->name }}</h3>
        <p><strong>{{__('labels.Email')}}:</strong> {{ $provider->email }}</p>
        <p><strong>{{__('labels.Total Price')}}:</strong> ${{ number_format($bestPrice, 2) }}</p>

                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('labels.product') }}</th>
                        <th>{{ __('labels.Quantity') }}</th>
                        <th>{{ __('labels.Price') }}</th>
                        <th>{{ __('labels.Total') }}</th>
                        <th>{{ __('labels.Change Provider') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        // جلب السلة من الجلسة
                        $cart = session('cart', []);
                    @endphp

                    @foreach ($cart as $item)
                        @php
                            // التحقق من وجود provider_id وproduct_id
                            if (!isset($item['provider_id']) || !isset($item['product_id'])) {
                                continue; // تخطي العنصر إذا كان أحدهما غير موجود
                            }

                            // جلب بيانات المنتج
                            $product = \App\Models\Product::find($item['product_id']);
                            if (!$product) {
                                continue; // تخطي العنصر إذا لم يتم العثور على المنتج
                            }

                            // جلب بيانات المزود
                            $provider = \App\Models\Provider::find($item['provider_id']);
                            if (!$provider) {
                                continue; // تخطي العنصر إذا لم يتم العثور على المزود
                            }

                            // جلب السعر من المخزن
                            $productWarehouse = \App\Models\ProductWarehouse::where('product_id', $item['product_id'])
                                ->where('provider_id', $item['provider_id'])
                                ->first();

                            $price = $productWarehouse ? $productWarehouse->price : 0;
                        @endphp

                            <!-- عرض صف المنتج -->
                        <tr>
                            <td>
                                <img src="{{ asset('storage/images/' . $product->photo) }}" alt="{{ $product->name_en }}" width="50">
                                {{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}
                            </td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>${{ number_format($price, 2) }}</td>
                            <td>${{ number_format($price * $item['quantity'], 2) }}</td>
                            <td>
                                <!-- قائمة تغيير المزود -->
                                <select class="form-select change-provider" data-product-id="{{ $item['product_id'] }}" data-quantity="{{ $item['quantity'] }}">
                                    <option value="">{{ __('labels.Select Provider') }}</option>
                                    @foreach (\App\Models\ProductWarehouse::where('product_id', $item['product_id'])->orderBy('price', 'asc')->take(5)->get() as $providerOption)
                                        <option value="{{ $providerOption->provider_id }}" {{ $providerOption->provider_id == $item['provider_id'] ? 'selected' : '' }}>
                                            {{ __('labels.Provider') }}: {{ $providerOption->provider->name }} - ${{ number_format($providerOption->price, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <script>
                    document.querySelectorAll('.change-provider').forEach(select => {
                        select.addEventListener('change', function () {
                            const providerId = this.value;
                            const productId = this.getAttribute('data-product-id');
                            const quantity = this.getAttribute('data-quantity');

                            if (providerId) {
                                fetch('{{ route('cart.changeProvider') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    },
                                    body: JSON.stringify({ provider_id: providerId, product_id: productId, quantity: quantity })
                                }).then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            location.reload(); // إعادة تحميل الصفحة لتحديث السلة
                                        } else {
                                            alert(data.message);
                                        }
                                    });
                            }
                        });
                    });
                </script>

        <a href="{{ route('cart.index') }}" class="btn btn-primary">{{__('labels.Back to Cart')}}</a>
    </div>
    </div>
    </div>
@endsection
