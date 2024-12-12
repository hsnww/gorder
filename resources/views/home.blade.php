@extends('layouts.shop')

@section('main')
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
               @foreach ($products as $product)
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Product image -->
                            <img class="card-img-top" src="{{ asset('storage/images/' . $product->photo) }}" alt="{{ $product->name_en }}" />
                            <!-- Product details -->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name -->
                                    <h5 class="fw-bolder">{{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}</h5>
                                    <!-- Product price -->
                                    <p class="text-muted">${{ number_format($product->price, 2) }}</p>
                                </div>
                            </div>
                            <!-- Product actions -->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center">
                                    <!-- View Details Button -->
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-dark">
                                        {{ __('labels.view_details') }}
                                    </a>
                                    <!-- Add to Cart Button -->
                                    <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-outline-dark mt-1">
                                            {{ __('labels.Add to Cart') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
                {{ $products->links()}}
            </div>
        </div>
    </section>

    @endsection
