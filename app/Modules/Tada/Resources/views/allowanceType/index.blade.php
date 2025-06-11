@extends('admin::layout')
@section('title')Allowance Type @stop
@section('breadcrum')HR Requisition / TADA Management / Allowance Type @stop

@section('script')
<script src="{{asset('admin/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
@stop

@section('content')

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@if($menuRoles->assignedRoles('allowanceType.create'))
<div class="card">
    <div class="card-body d-sm-flex align-items-sm-center justify-content-sm-between flex-sm-wrap">
        <a href="{{ route('allowanceType.create') }}" class="btn bg-teal-400"><i class="icon-plus-circle2"></i> Add Allowance Type</a>
        <button type="button" class="btn btn-default btn-sm bg-purple-400" data-toggle="modal" data-target="#modal_default">
            <i class="icon-history"></i> History
        </button>
        @include('history::includes.modal', ['history_type' => 'Allowance Type'])
    </div>
</div>
@endif

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">List of Allowance Type</h5>
    </div>
    <div class="table-responsive ">
        <table class="table table-{{config('admin.color-class.table')}}">
            <thead>
                <tr class="bg-{{config('admin.color-class.thead_tr')}}">
                    <th>#</th>
                    <th>Title</th>
                    <th>Status</th>
                    @if($menuRoles->assignedRoles('allowanceType.edit') || $menuRoles->assignedRoles('allowanceType.delete'))
                        <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if($allowanceTypes->total() > 0)
                    @foreach ($allowanceTypes as $key => $type)
                    <tr>
                        <td>{{ $allowanceTypes->firstItem() + $key }}</td>
                        <td>{{ $type->title }}</td>
                        <td class="text-teal">
                            <span data-popup="tooltip" data-original-title="{{ $type->status ? 'Active' : 'In-Active' }}" class="btn btn-outline btn-icon {{ $type->status ? 'bg-success text-success border-success' : 'bg-danger text-danger border-danger' }} border-2 rounded-round">
                                <i class="{{ $type->status ? 'icon-checkmark4' : 'icon-cross2' }}"></i>
                            </span>
                        </td>
                        @if($menuRoles->assignedRoles('allowanceType.edit') || $menuRoles->assignedRoles('allowanceType.delete'))
                            <td>
                                @if($menuRoles->assignedRoles('allowanceType.edit'))
                                    <a class="btn bg-teal-400 btn-icon rounded-round" href="{{ route('allowanceType.edit', $type->id) }}" data-popup="tooltip" data-placement="bottom" data-original-title="Edit"><i class="icon-pencil6"></i></a>
                                @endif
                                @if($menuRoles->assignedRoles('allowanceType.delete'))
                                    <a data-toggle="modal" data-target="#modal_theme_warning" class="btn bg-danger-400 btn-icon rounded-round delete_allowance_type" link="{{ route('allowanceType.delete', $type->id) }}" data-placement="bottom" data-popup="tooltip" data-original-title="Delete"><i class="icon-bin"></i></a>
                                @endif
                            </td>
                        @endif
                    </tr>
                    @endforeach
                @else
                    <tr><td>No Data Found!</td></tr>
                @endif
            </tbody>
        </table>
        <span style="margin: 5px;float: right;">
            @if($allowanceTypes->total() != 0)
                {{ $allowanceTypes->links() }}
            @endif
        </span>
    </div>
</div>

<!-- Warning modal -->
<div id="modal_theme_warning" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h6 class="modal-title">Are you sure to Delete a allowance type ?</h6>
            </div>

            <div class="modal-body">
                <a class="btn btn-success get_link" href="">Yes</a> &nbsp; | &nbsp;
                <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /warning modal -->

<script type="text/javascript">
    $('document').ready(function() {
        $('.delete_allowance_type').on('click', function() {
            var link = $(this).attr('link');
            $('.get_link').attr('href', link);
        });
    });
</script>

@endsection
