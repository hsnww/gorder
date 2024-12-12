@extends('layouts.shop')

@section('main')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-5 mt-3">
                <h1>{{ __('labels.Cart Fulfillment') }}</h1>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <!-- جداول المزودين الحاليين -->
                <div id="provider-tables">
                    @foreach ($fulfilledOrders as $orderIndex => $order)
                        <div class="provider-table" data-provider-id="{{ $order['provider']->id }}">
                            <h3>{{ __('labels.Provider') }}: {{ $order['provider']->name }}</h3>
                            <p><strong>{{ __('labels.Email') }}:</strong> {{ $order['provider']->email }}</p>
                            <p>
                                <strong>{{ __('labels.Total Price') }}:</strong>
                                <span id="total-price-{{ $order['provider']->id }}">
                                    ${{ number_format($order['total_price'], 2) }}
                                </span>
                            </p>

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
                                        $product = \App\Models\Product::find($productId);
                                        $cartItemKey = collect(session('cart'))->search(function ($item) use ($productId) {
                                        return $item['product_id'] == $productId;
                                        });

                                        $quantity = $cartItemKey !== false ? session('cart')[$cartItemKey]['quantity'] : 0;
                                        $productWarehouse = \App\Models\ProductWarehouse::where('product_id', $productId)
                                        ->where('provider_id', $order['provider_id'])
                                        ->first();

                                        $price = $productWarehouse ? $productWarehouse->price : 0;

                                        $otherProviders = \App\Models\ProductWarehouse::where('product_id', $productId)
                                        ->orderBy('price', 'asc')
                                        ->take(5)
                                        ->get();
                                    @endphp
                                    <tr data-product-id="{{ $productId }}">
                                        <td>
                                            <img src="{{ asset('storage/images/' . $product->photo) }}"
                                                 alt="{{ $product->name_en }}" width="50">
                                            <a href="{{ route('product.compare', ['product_id' => $productId]) }}">
                                                {{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}
                                            </a>
                                        </td>
                                        <td>{{ $quantity }}</td>
                                        <td class="product-price">${{ number_format($price, 2) }}</td>
                                        <td class="product-total">${{ number_format($price * $quantity, 2) }}</td>
                                        <td>
                                            <select class="form-select change-provider"
                                                    data-product-id="{{ $productId }}" data-quantity="{{ $quantity }}">
                                                <option value="">{{ __('labels.Select Provider') }}</option>
                                                @foreach ($otherProviders as $providerOption)
                                                    <option value="{{ $providerOption->provider_id }}"
                                                            data-price="{{ $providerOption->price }}"
                                                        {{ $providerOption->provider_id == $order['provider']->id ? 'selected' : '' }}>
                                                        {{ __('labels.Provider') }}
                                                        : {{ $providerOption->provider->name }} -
                                                        ${{ number_format($providerOption->price, 2) }}
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
                </div>

                <a href="{{ route('cart.index') }}" class="btn btn-primary">{{ __('labels.Back to Cart') }}</a>

                <div class="text-center mt-4 inline-flex">
                    <form action="{{ route('cart.saveSuggestedCarts') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            {{ __('labels.Save Suggested Carts') }}
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.change-provider').forEach(select => {
            select.addEventListener('change', function () {
                const providerId = this.value; // المزود الجديد
                const productId = this.getAttribute('data-product-id'); // معرف المنتج
                const quantity = this.getAttribute('data-quantity'); // الكمية المطلوبة
                const currentRow = this.closest('tr'); // الصف الحالي

                if (!providerId) {
                    alert("Please select a valid provider.");
                    return;
                }

                fetch('{{ route('cart.changeProvider') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({provider_id: providerId, product_id: productId, quantity: quantity})
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
        // تحديث السعر في الصف
                            const newPrice = parseFloat(this.selectedOptions[0].getAttribute('data-price'));
                            const totalPrice = newPrice * quantity;

                            currentRow.querySelector('.product-price').textContent = `$${newPrice.toFixed(2)}`;
                            currentRow.querySelector('.product-total').textContent = `$${totalPrice.toFixed(2)}`;

        // نقل الصف إلى جدول المزود الجديد
                            let newTable = document.querySelector(`.provider-table[data-provider-id="${providerId}"] tbody`);
                            if (!newTable) {
        // إنشاء جدول جديد إذا لم يكن موجودًا
                                const providerName = this.selectedOptions[0].textContent.split(':')[1].split('-')[0].trim(); // استخراج اسم المزود
                                const container = document.getElementById('provider-tables');
                                const newProviderTable = document.createElement('div');
                                newProviderTable.classList.add('provider-table');
                                newProviderTable.setAttribute('data-provider-id', providerId);

                                newProviderTable.innerHTML = `
                                                            <h3>{{ __('labels.Provider') }}: ${providerName}</h3>
                                                            <p>
                                                            <strong>{{ __('labels.Total Price') }}:</strong>
                                                            <span id="total-price-${providerId}">$0.00</span>
                                                            </p>
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
                                                            <tbody></tbody>
                                                            </table>
                                                            `;
                                container.appendChild(newProviderTable);
                                newTable = newProviderTable.querySelector('tbody');
                            }
                            newTable.appendChild(currentRow);

                                // تحديث السعر الإجمالي للجدول الجديد
                            updateTotalPrice(newTable.closest('.provider-table'), providerId);

                                // تحقق من الجدول القديم إذا أصبح فارغًا
                            const oldTable = currentRow.closest('.provider-table');
                            const oldTableBody = oldTable.querySelector('tbody');
                            if (oldTableBody.children.length === 0) {
                                oldTable.remove(); // إزالة الجدول الفارغ
                            } else {
        // تحديث السعر الإجمالي للجدول القديم
                                const oldProviderId = oldTable.getAttribute('data-provider-id');
                                updateTotalPrice(oldTable, oldProviderId);
                            }
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

        // دالة لتحديث السعر الإجمالي لكل جدول
        function updateTotalPrice(providerTable, providerId) {
            const rows = providerTable.querySelectorAll('tbody tr');
            let totalPrice = 0;

            rows.forEach(row => {
                const totalCell = row.querySelector('.product-total');
                if (totalCell) {
                    const productTotal = parseFloat(totalCell.textContent.replace('$', '')) || 0;
                    totalPrice += productTotal;
                }
            });

        // تحديث السعر الإجمالي في العنصر المحدد
            const totalPriceElement = document.getElementById(`total-price-${providerId}`);
            if (totalPriceElement) {
                totalPriceElement.textContent = `$${totalPrice.toFixed(2)}`;
            }
        }
    </script>
@endsection
