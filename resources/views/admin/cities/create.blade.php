@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>Product Categories</h1>
        <form action="{{route('admin.cities.store')}}" method="post">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">City</label>
                <input type="text" class="form-control" name="name" id="name">
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">Code</label>
                <select name="region_id" class="form-control">
                    <option value="">Select Country</option>
                    @foreach($regions as $region)
                        <option value="{{$region->id}}">{{$region->name}}</option>
                    @endforeach
                </select>
            </div>
               <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
