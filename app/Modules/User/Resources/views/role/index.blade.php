@extends('admin::layout')
@section('title') Roles @stop
@section('breadcrum')
<a class="breadcrumb-item active">Roles</a>
@endsection

@section('content')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>

        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Role</h6>
            All the Role Information will be listed below. You can Modify Create Role As per User Type.
        </div>
        <a href="{{route('role.create')}}" class="btn bg-success text-white btn-labeled btn-labeled-left" style="float: left"><b><i class="icon-add-to-list"></i></b> Create Role</a>
    </div>
</div>

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">List of Role</h5>

    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr class="bg-secondary text-white">
                    <th>#</th>
                    <th>Role Name</th>
                    <th>User Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                @if($role->total() != 0)
                 @foreach($role as $key => $value)

                <tr>
                    <td>{{$role->firstItem() +$key}}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->user_type }}</td>

                    <td class="{{ ($value->status == '1') ? 'text-teal' : 'text-warning' }} "><span data-popup="tooltip" data-original-title="{{ ($value->status == '1') ? 'Active' : 'In-Active' }}"><i class="{{ ($value->status == '1') ? 'icon-check' : 'icon-x' }}"></i> </span></td>
                    <td>

                        <a class="btn bg-info btn-icon rounded-round" href="{{route('role.edit',$value->id)}}" data-popup="tooltip" data-placement="bottom" data-original-title="Edit"><i class="icon-pencil6"></i></a>
                        <a data-toggle="modal" data-target="#modal_theme_warning" class="btn bg-danger btn-icon rounded-round delete_role" link="{{route('role.delete',$value->id)}}" data-popup="tooltip" data-placement="bottom" data-original-title="Delete"><i class="icon-bin"></i></a>
                    </td>
                </tr>
                @endforeach @else
                <tr>
                    <td colspan="4">No Role Found !!!</td>
                </tr>
                @endif
            </tbody>
        </table>

        <span style="margin: 5px;float: right;">
            @if($role->total() != 0)
                {{ $role->links() }}
            @endif
        </span>

    </div>
</div>


<!-- Warning modal -->
<div id="modal_theme_warning" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <center>
                <i class="icon-alert icon-2x text-danger border-danger border-3 rounded-pill p-3 mb-1 mt-1"></i>
                </center>
                <br>
                <center>
                    <h2>Are You Sure Want To Delete ?</h2>
                    <a class="btn btn-success get_link" href="">Yes, Delete It!</a>
                    <button type="button" class="ml-1 btn btn-danger" data-dismiss="modal">Cancel</button>
                </center>
            </div>
        </div>
    </div>
</div>
<!-- /warning modal -->

<script type="text/javascript">
    $('document').ready(function() {
        $('.delete_role').on('click', function() {
            var link = $(this).attr('link');
            $('.get_link').attr('href', link);
        });


    });
</script>

@endsection
