@extends('layouts.shop')

@section('main')
    <div class="container">
        <h1>{{ __('categories.list') }}</h1>
        <table class="table">
            <thead>
            <tr>
                <th>{{ __('categories.name') }}</th>
                <th>{{ __('categories.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ app()->getLocale() === 'ar' ? $category->name_ar : $category->name_en }}</td>
                    <td>
                        <a href="{{ route('categories.show', $category->id) }}" class="btn btn-primary btn-sm">
                            {{ __('categories.view_details') }}
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
