@extends('layouts.master')

{{-- @inject('governorates', 'App\Models\Governorate') --}}

@section('page_name')
    <h1>Cities</h1>
@endsection

@section('page')
    Cities
@endsection


@section('content')

{{-- <a href="{{ route('cities.create') }}" class="btn btn-primary my-3"><i class="fa fa-plus"></i> New city</a> --}}
<a class="modal-effect btn btn-md btn-primary my-3" data-effect="effect-scale" data-toggle="modal"
href="#createCity" title="add city"><i class="las la-pen fa fa-plus mr-2"></i>Add City</a>

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



@if(count($cities) > 0)
    <table class="table table-bordered">
        <thead>
        <tr class="text-center">
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Governorate</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
        </tr>
        </thead>
        <tbody>

            @foreach ($cities as $city)

                <tr class="text-center">
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $city->name }}</td>
                    <td>{{ $city->governorate->name }}</td>
                    <td><a href="{{ route('cities.edit', $city->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a></td>
                    {{-- <td><a class="btn btn-primary btn-sm modal-effect" data-toggle="modal" data-effect="effect-scale"
                        data-id="{{ $city->id }}" data-name="{{ $city->name }}" href="#editCity"><i class="fa fa-edit"></i></a></td>
                    <td> --}}
                        {{-- <form action="{{ route('cities.destroy', $city->id) }}" method="POST">

                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                        </form> --}}
                        <td>
                            <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                            data-id="{{ $city->id }}" data-name="{{ $city->name }}"
                            data-toggle="modal" href="#deleteCity" title="حذف"><i
                                class="fa fa-trash"></i></a>
                        </td>
                </tr>

            @endforeach

        </tbody>
    </table>
@endif

<!-- create -->
<div class="modal fade" id="createCity" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add city</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ route('cities.store') }}" method="post" autocomplete="off">
                    {{ method_field('post') }}
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name" class="col-form-label">name</label>
                        <input class="form-control" name="name" type="text">
                    </div>
                    <div class="form-group">

                        <select class="form-control" name="governorate_id">
                            @foreach ($governorates as $governorate)

                                <option value="{{ $governorate->id }}">{{ $governorate->name }}</option>

                            @endforeach
                        </select>

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
{{-- <div class="modal fade" id="editCity" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCity">Edit city</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="" method="post" autocomplete="off">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="text" name="id" id="id" value="">
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
 <div class="modal fade" id="deleteCity">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">city deletion</h6><button aria-label="Close" class="close" data-dismiss="modal"
                    type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ route('cities.destroy', $city->id) }}" method="post">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <div class="modal-body">
                    <p>Are you sure you want to delete this item ?</p><br>
                    <input type="text" name="id" id="id" value="{{ $city->id }}">
                    <input class="form-control" name="name" id="name" type="text" value="{{ $city->name }}" readonly>
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


 <script src="{{ asset('adminassets/dist/js/jquery-3.6.4.js') }}"></script>


<!-- Internal Modal js-->
{{-- <script src="{{ asset('adminassets/dist/js/modal.js') }}"></script> --}}

<script>
    $('#editCity').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var name = button.data('name')
        var modal = $(this)
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #name').val(name);
    })
</script>

<script>
    $('#deleteCity').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var name = button.data('name')
        var modal = $(this)
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #name').val(name);
    })
</script>

