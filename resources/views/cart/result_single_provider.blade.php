@extends('layouts.shop')

@section('main')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-5 mt-3">
                <!-- عنوان الصفحة -->
                <h1>{{ __('labels.Your Cart') }} - {{ __('labels.Single Provider') }}</h1>

                <!-- معلومات المزود -->
                <h3 id="provider-name">{{ __('labels.Provider') }}: {{ $provider->name }}</h3>
                <p><strong>{{ __('labels.Email') }}:</strong> {{ $provider->email }}</p>
                <p><strong>{{ __('labels.Total Price') }}:</strong>
                    <span id="total-price">${{ number_format($bestPrice, 2) }}</span>
                </p>

                <!-- الحاوية لإضافة الجداول الجديدة -->
                <div id="provider-tables">
                    <!-- الجداول الحالية -->
                    <div class="provider-table" data-provider-id="{{ $provider->id }}">
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
                            <tbody id="product-table">
                            @foreach ($cart as $item)
                                @php
                                    $product = \App\Models\Product::find($item['product_id']);
                                    $productWarehouse = \App\Models\ProductWarehouse::where('product_id', $item['product_id'])
                                        ->where('provider_id', $item['provider_id'])
                                        ->first();
                                    $price = $productWarehouse ? $productWarehouse->price : 0;
                                @endphp
                                <tr data-product-id="{{ $item['product_id'] }}">
                                    <td>
                                        <img src="{{ asset('storage/images/' . $product->photo) }}" alt="{{ $product->name_en }}" width="50">
                                        {{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}
                                    </td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td class="product-price">${{ number_format($price, 2) }}</td>
                                    <td class="product-total">${{ number_format($price * $item['quantity'], 2) }}</td>
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
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <!-- زر حفظ السلة المقترحة -->
                    <form action="{{ route('cart.saveSingle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="provider_id" value="{{ $provider->id }}">
                        <button type="submit" class="btn btn-success">{{ __('labels.Save Suggested Cart') }}</button>
                    </form>

                    <!-- زر العودة إلى السلة -->
                    <a href="{{ route('cart.index') }}" class="btn btn-primary">{{ __('labels.Back to Cart') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
