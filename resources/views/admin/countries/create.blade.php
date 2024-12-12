@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>Product Categories</h1>
        <form action="{{route('admin.countries.store')}}" method="post">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Country</label>
                <input type="text" class="form-control" name="name" id="name">
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">Code</label>
                <input type="text" class="form-control" name="code" id="code">
            </div>
               <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
