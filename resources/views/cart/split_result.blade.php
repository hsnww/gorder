@extends('layouts.shop')

@section('main')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-5 mt-3">
                <!-- عنوان الصفحة -->
                <h1>{{ __('labels.Cart Fulfillment') }}</h1>

                <!-- عرض الطلبات التي تم تحقيقها -->
                @foreach ($fulfilledOrders as $orderIndex => $order)
                    <div class="mb-4">
                        <!-- معلومات المزود -->
                        <h3>{{ __('labels.Provider') }}: {{ $order['provider']->name }}</h3>
                        <p><strong>{{ __('labels.Email') }}:</strong> {{ $order['provider']->email }}</p>
                        <p><strong>{{ __('labels.Total Price') }}:</strong> ${{ number_format($order['total_price'], 2) }}</p>

                        <!-- جدول المنتجات لهذا المزود -->
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
                            @foreach ($order['items'] as $productId)
                                @php
                                    // جلب بيانات المنتج
                                    $product = \App\Models\Product::find($productId);

                                    // البحث عن العنصر في السلة
                                    $cartItemKey = collect(session('cart'))->search(function ($item) use ($productId) {
                                        return $item['product_id'] == $productId;
                                    });

                                    // التحقق من العنصر في السلة
                                    if ($cartItemKey !== false) {
                                        $quantity = session('cart')[$cartItemKey]['quantity'];
                                    } else {
                                        $quantity = 0; // قيمة افتراضية
                                    }

                                    // جلب بيانات المخزن المرتبط بالمنتج والمزود
                                    $productWarehouse = \App\Models\ProductWarehouse::where('product_id', $productId)
                                        ->where('provider_id', $order['provider_id'])
                                        ->first();

                                    $price = $productWarehouse ? $productWarehouse->price : 0;

                                    // جلب قائمة أفضل 5 مزودين بناءً على السعر
                                    $otherProviders = \App\Models\ProductWarehouse::where('product_id', $productId)
                                        ->orderBy('price', 'asc')
                                        ->take(5)
                                        ->get();
                                @endphp

                                    <!-- صف المنتج -->
                                <tr>
                                    <td>
                                        <img src="{{ asset('storage/images/' . $product->photo) }}" alt="{{ $product->name_en }}" width="50">
                                        <a href="{{ route('product.compare', ['product_id' => $productId]) }}">
                                            {{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}
                                        </a>
                                    </td>
                                    <td>{{ $quantity }}</td>
                                    <td class="product-price">${{ number_format($price, 2) }}</td>
                                    <td class="product-total">${{ number_format($price * $quantity, 2) }}</td>
                                    <td>
                                        <select class="form-select change-provider" data-product-id="{{ $productId }}" data-quantity="{{ $quantity }}">
                                            <option value="">{{ __('labels.Select Provider') }}</option>
                                            @foreach ($otherProviders as $providerOption)
                                                <option value="{{ $providerOption->provider_id }}" data-price="{{ $providerOption->price }}"
                                                    {{ $providerOption->provider_id == $order['provider']->id ? 'selected' : '' }}>
                                                    {{ __('labels.Provider') }}: {{ $providerOption->provider->name }} - ${{ number_format($providerOption->price, 2) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach

                <!-- عرض السلع التي لم يتم تحقيقها إذا وجدت -->
                @if (!empty($unfulfilledItems))
                    <div class="alert alert-danger">
                        <h4>{{ __('labels.Unfulfilled Items') }}</h4>
                        <ul>
                            @foreach ($unfulfilledItems as $productId)
                                @php
                                    $product = \App\Models\Product::find($productId);
                                @endphp
                                <li>
                                    <a href="{{ route('product.compare', ['product_id' => $product->id]) }}">
                                        {{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- زر العودة إلى السلة -->
                <a href="{{ route('cart.index') }}" class="btn btn-primary">{{ __('labels.Back to Cart') }}</a>
            </div>
        </div>
    </div>

    <!-- كود JavaScript لتحديث المزود -->
    <script>
        document.querySelectorAll('.change-provider').forEach(select => {
            select.addEventListener('change', function () {
                const providerId = this.value; // المزود الجديد
                const productId = this.getAttribute('data-product-id'); // معرف المنتج
                const quantity = this.getAttribute('data-quantity'); // الكمية المطلوبة

                if (!providerId) {
                    alert("Please select a valid provider.");
                    return;
                }

                // إرسال طلب لتغيير المزود
                fetch('{{ route('cart.changeProvider') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ provider_id: providerId, product_id: productId, quantity: quantity })
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // تحديث السعر مباشرة
                            const row = this.closest('tr'); // البحث عن الصف الحالي
                            const priceCell = row.querySelector('.product-price'); // عمود السعر
                            const totalCell = row.querySelector('.product-total'); // عمود الإجمالي

                            // جلب السعر الجديد من الخيار المحدد
                            const newPrice = parseFloat(this.selectedOptions[0].getAttribute('data-price'));
                            const newTotal = newPrice * quantity;

                            priceCell.textContent = `$${newPrice.toFixed(2)}`;
                            totalCell.textContent = `$${newTotal.toFixed(2)}`;
                        } else {
                            alert(data.message || 'Unable to change provider. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Something went wrong. Please try again.');
                    });
            });
        });
    </script>
@endsection
