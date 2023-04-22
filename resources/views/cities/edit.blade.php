@extends('layouts.master')

{{-- @inject('model', 'App\Models\Governorate') --}}


@section('page_name')
    <h1>Edit City</h1>
@endsection

@section('page')
    Cities
@endsection

@section('content')
<div class="container">
    @if (session('update'))
        <div class="alert alert-success">
            {{ session('update') }}
        </div>
    @endif

    @error('name')
        <div class="alert alert-danger my-3">{{ $message }}</div>
    @enderror

    <form action="{{ route('cities.update', $city->id) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $city->name }}">
        </div>
        <div class="form-group">
            <label for="name">governorate</label>
            <select class="form-control" name="governorate_id">
                @foreach ($governorates as $governorate)

                    <option value="{{ $governorate->id }}" @selected($governorate->id == $city->governorate->id)>{{ $governorate->name }}</option>

                @endforeach
            </select>

        </div>
        <button type="submit" class="btn btn-primary">update</button>
      </form>

</div>


@endsection
