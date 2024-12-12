@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>Create city</h1>
        <form action="{{route('admin.regions.store')}}" method="post">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Region</label>
                <input type="text" class="form-control" name="name" id="name">
            </div>
            <div class="mb-3">
                <label for="country_id" class="form-label">Country</label>

                <select name="country_id" class="form-control">
                    <option value="">Select Country</option>
                    @foreach($countries as $country)
                        <option value="{{$country->id}}">{{$country->name}}</option>
                    @endforeach
                </select>

            </div>
               <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
