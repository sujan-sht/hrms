@extends('admin::layout')
@section('title')Expense Head @stop
@section('breadcrum')
<a class="breadcrumb-item active"> Expense Head </a>
@endsection

@section('content')

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Expense Head </h6>
            All the Expense Head Information will be listed below. You can Create and Modify the data.
        </div>

    </div>
</div>

<div class="card">
    <div class="card-body">

        <div class="row m-4">
            <div class="col-sm-4">
                <form method="POST" action="{{ route('expensehead.storeUpdate') }}">
                    @csrf
                    <div class="form-row align-items-center">
                        <div class="col">
                            <input type="text" class="form-control" id="title" name="title" required>
                            <input type="hidden" class="form-control" id="id" name="id">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="table-responsive ">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>#</th>
                        <th>Title</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($lists->total() > 0)
                    @foreach ($lists as $key => $type)
                    <tr>
                        <td>{{ $lists->firstItem() + $key }}</td>
                        <td>{{ $type->title }}</td>
                        <td>
                            <button class="btn btn-outline-primary btn-icon mx-1 edit-btn"
                                data-title="{{ $type->title }}" data-id="{{ $type->id }}">
                                <i class="icon-pencil7"></i>
                                </button>


                                <a class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                    link="{{ route('expensehead.delete', $type->id) }}" data-placement="bottom"
                                    data-popup="tooltip" data-original-title="Delete"><i class="icon-trash-alt"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td>No Data Found!</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <span style="margin: 5px;float: right;">
                @if ($lists->total() != 0)
                {{ $lists->links() }}
                @endif
            </span>
        </div>
    </div>
</div>

@endsection
@section('script')

<script>
    $(document).on('click', '.edit-btn', function() {
        var title = $(this).data('title');
        var id = $(this).data('id');

        $('#title').val(title);
        $('#id').val(id);
    });
</script>


@endsection
