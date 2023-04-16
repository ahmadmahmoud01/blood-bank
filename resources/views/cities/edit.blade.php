@extends('layouts.master')

@inject('model', 'App\Models\Governorate')


@section('page_name')
    <h1>Edit Governorat</h1>
@endsection

@section('page')
    Governorates
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
    <form action="{{ route('governorates.update', $governorate->id) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $governorate->name }}">
        </div>
        <button type="submit" class="btn btn-primary">update</button>
      </form>

</div>


@endsection
