@extends('layouts.master')

@inject('model', 'App\Models\Governorate')


@section('page_name')
    <h1>Create New Governorate</h1>
@endsection

@section('page')
    Governorates
@endsection

@section('content')
<div class="container">

    @error('name')
        <div class="alert alert-danger my-3">{{ $message }}</div>
    @enderror
    <form action="{{ route('governorates.store') }}" method="POST">

        @csrf

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter name">
        </div>
        <button type="submit" class="btn btn-primary">Add</button>
      </form>

</div>


@endsection
