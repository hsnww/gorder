@extends('layouts.shop')

@section('main')
    <div class="container">
        <h1>{{ __('Cart for Provider') }}: {{ $provider->name }}</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table">
            <thead>
            <tr>
                <th>{{ __('Product') }}</th>
                <th>{{ __('Quantity') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($products as $item)
                <tr>
                    <td>
                        <img src="{{ asset('storage/images/' . $item['product']->photo) }}" alt="{{ $item['product']->name_en }}" width="50">
                        {{ app()->getLocale() === 'ar' ? $item['product']->name_ar : $item['product']->name_en }}
                    </td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>
                        <form action="{{ route('provider.cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="provider_id" value="{{ $providerId }}">
                            <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                            <button type="submit" class="btn btn-danger btn-sm">{{ __('Remove') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">{{ __('No items in cart.') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <a href="{{ route('providers.show', $providerId) }}" class="btn btn-primary">{{ __('Back to Provider') }}</a>
    </div>
@endsection
