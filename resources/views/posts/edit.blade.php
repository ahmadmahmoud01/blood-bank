@extends('layouts.master')

@inject('model', 'App\Models\Governorate')


@section('page_name')
    <h1>Edit Post</h1>
@endsection

@section('page')
    Posts
@endsection

@section('content')
<div class="container">
    @if (session('update'))
        <div class="alert alert-success">
            {{ session('update') }}
        </div>
    @endif

    @error('title')
        <div class="alert alert-danger my-3">{{ $message }}</div>
    @enderror
    @error('content')
        <div class="alert alert-danger my-3">{{ $message }}</div>
    @enderror
    @error('category_id')
        <div class="alert alert-danger my-3">{{ $message }}</div>
    @enderror

    <form action="{{ route('posts.update', $post->id) }}" method="post" autocomplete="off">
        {{ method_field('PUT') }}
        {{ csrf_field() }}
        <div class="form-group">
            <label for="name" class="col-form-label">title</label>
            <input class="form-control" name="title" type="text" value="{{ $post->title }}">
        </div>
        <div class="form-group">
            <label for="name" class="col-form-label">content</label>
            <textarea class="form-control" cols="3" rows="5" name="content" type="text">{{ $post->title }}</textarea>
        </div>
        <div class="form-group">

            <select class="form-control" name="category_id">
                @foreach ($categories as $category)

                    <option value="{{ $category->id }}" @selected($category->id == $post->category->id)>{{ $category->name }}</option>

                @endforeach
            </select>

        </div>


        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">add</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">update</button>
        </div>
    </form>

</div>


@endsection
