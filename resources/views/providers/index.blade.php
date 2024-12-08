@extends('layouts.shop')

@section('main')
    <div class="container">
        <h1>{{ __('provider.list') }}</h1>
        <table class="table">
            <thead>
            <tr>
                <th>{{ __('provider.name') }}</th>
                <th>{{ __('provider.email') }}</th>
                <th>{{ __('provider.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($providers as $provider)
                <tr>
                    <td>{{ $provider->name }}</td>
                    <td>{{ $provider->email }}</td>
                    <td>
                        <a href="{{ route('providers.show', $provider->id) }}" class="btn btn-primary btn-sm">
                            {{ __('provider.view_details') }}
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
