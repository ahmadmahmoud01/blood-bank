@extends('layouts.master')


@section('page_name')
    <h1>Categories</h1>
@endsection

@section('page')
    Categories
@endsection


@section('content')

{{-- <a href="{{ route('categories.create') }}" class="btn btn-primary my-3"><i class="fa fa-plus"></i> New category</a> --}}
<a class="modal-effect btn btn-md btn-primary my-3" data-effect="effect-scale" data-toggle="modal"
href="#createGov" title="add category"><i class="las la-pen fa fa-plus mr-2"></i>Add Category</a>

@error('name')
        <div class="alert alert-danger my-3">{{ $message }}</div>
@enderror

@if (session('create'))
    <div class="alert alert-success">
        {{ session('create') }}
    </div>
@endif

@if (session('delete'))
    <div class="alert alert-danger">
        {{ session('delete') }}
    </div>
@endif



@if(count($categories) > 0)
    <table class="table table-bordered">
        <thead>
        <tr class="text-center">
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
        </tr>
        </thead>
        <tbody>

            @foreach ($categories as $category)

                <tr class="text-center">
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $category->name }}</td>
                    <td><a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a></td>
                    {{-- <td><a class="btn btn-primary btn-sm modal-effect" data-toggle="modal" data-effect="effect-scale"
                        data-id="{{ $category->id }}" data-name="{{ $category->name }}" href="#editCat"><i class="fa fa-edit"></i></a></td>
                    <td> --}}
                        {{-- <form action="{{ route('categories.destroy', $category->id) }}" method="POST">

                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                        </form> --}}
                    <td>
                        <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                        data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                        data-toggle="modal" href="#deleteCat" title="حذف"><i
                            class="fa fa-trash"></i></a>
                    </td>
                </tr>

            @endforeach

        </tbody>
    </table>
@endif

<!-- create -->
<div class="modal fade" id="createGov" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ route('categories.store') }}" method="post" autocomplete="off">
                    {{ method_field('post') }}
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name" class="col-form-label">name</label>
                        <input class="form-control" name="name" type="text">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">add</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


<!-- Edit -->
{{-- <div class="modal fade" id="editCat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCat">Edit category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ route() }}" method="post" autocomplete="off">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="hidden" name="id" id="id" value="">
                        <label for="name" class="col-form-label">name</label>
                        <input class="form-control" name="name" id="name" type="text" >
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">update</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div> --}}

 <!-- delete -->
 <div class="modal fade" id="deleteCat">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">category deletion</h6><button aria-label="Close" class="close" data-dismiss="modal"
                    type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ route('categories.destroy', $category->id) }}" method="post">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <div class="modal-body">
                    <p>Are you sure you want to delete this item ?</p><br>
                    <input type="hidden" name="id" id="id" value="{{ $category->id }}">
                    <input class="form-control" name="name" id="name" type="text" readonly>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                    <button type="submit" class="btn btn-danger">yes</button>
                </div>
            </form>

        </div>
    </div>
 </div>

 @endsection


<!-- Internal Modal js-->
{{-- <script src="{{ asset('adminassets/dist/js/modal.js') }}"></script> --}}
@push('scripts')
<script>
    console.log('hello')
    $('#editCat').on('show.bs.modal', function(event) {
        console.log('bs show')
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var name = button.data('name')
        console.log(id,name);
        $("#id").val(id);
        $("#name").val(name);
        // var modal = $(this)
        // modal.find('.modal-body #id').val(id);
        // modal.find('.modal-body #name').val(name);
    })
</script>

<script>
    $('#deleteCat').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var name = button.data('name')
        var modal = $(this)
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #name').val(name);
    })
</script>
@endpush
