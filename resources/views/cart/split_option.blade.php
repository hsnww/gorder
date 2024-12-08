@extends('layouts.shop')

@section('main')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-5 mt-3">
                <h1>{{__('labels.Cart Fulfillment')}}</h1>

                <div class="alert alert-info">
                    <p>{{__('labels.No single provider')}}</p>
                </div>

                <a href="{{ route('cart.splitCalculate') }}" class="btn btn-primary">{{__('labels.Split Cart')}}</a>
                <a href="{{ route('cart.index') }}" class="btn btn-secondary">{{__('labels.Back to Cart')}}</a>

            </div>
        </div>
    </div>
@endsection
