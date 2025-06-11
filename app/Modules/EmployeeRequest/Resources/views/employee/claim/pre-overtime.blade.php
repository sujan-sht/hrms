@extends('admin::employee.layout')
@section('title')Claim & Request Management @stop
@section('breadcrum')Claim & Request Management @stop

@section('scripts')
<script type="text/javascript">
    //$('document').ready(function() {
        $('.delete_request').on('click', function() {
            var link = $(this).attr('link');
            $('.get_link').attr('href', link);
        });
    //});
</script>
@stop

@section('content')


@inject('employee_shift', '\App\Modules\Shift\Repositories\EmployeeShiftRepository')
@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="box">
    <div class="row">
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-header">
                    <h6 class="float-left">Pre Overtime Request</h6>
                    @if($menuRoles->assignedRoles('preOvertimeRequest.create'))
                        <a href="{{route('preOvertimeRequest.create')}}" class="btn btn-primary float-right text-white" type="button">Add
                            Request</a>
                    @endif
                </div>
                <div class="card-body table-content">
                    <table class="table">
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col" colspan="2">Request Title</th>
                            <th scope="col">Overtime hrs.</th>
                            <th scope="col" colspan="2">Description</th>
                            <th scope="col">Status</th>
                            @if($menuRoles->assignedRoles('preOvertimeRequest.edit') ||  $menuRoles->assignedRoles('preOvertimeRequest.delete'))
                                <th scope="col">Action</th>
                            @endif
                        </tr>
                        @if($pre_overtimes->total() != 0)
                            @foreach($pre_overtimes as $key=>$value)
                                <tr>
                                    <td scope="row">{{$value->ot_date}} <span>{{date('l', strtotime($value->ot_date))}}</span> </td>
                                    <td colspan="2">{{$value->title}}</td>
                                    <td>{{$value->ot_hrs}}</td>
                                    <td colspan="2">{!! $value->description !!}</td>
                                    <td>
                                        @if($value->requested_by == auth()->user()->id)
                                            <span >{{ $value->status }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    @if($menuRoles->assignedRoles('preOvertimeRequest.edit') ||  $menuRoles->assignedRoles('preOvertimeRequest.delete'))
                                    <td>
                                    <div class="action-icons">
                                        @if($menuRoles->assignedRoles('preOvertimeRequest.edit'))
                                            <a class="edit" href="{{ route('preOvertimeRequest.edit', $value->id) }}"
                                            data-popup="tooltip" data-placement="bottom" title="Edit">
                                                <i class="fa fa-edit"></i> Edit</a>
                                        @endif
                                        @if($menuRoles->assignedRoles('preOvertimeRequest.delete'))
                                            <a class="delete delete_request" data-toggle="modal" data-target="#modal_theme_warning"
                                            link="{{ route('preOvertimeRequest.delete', $value->id) }}" data-placement="bottom"
                                            data-popup="tooltip" title="Delete">
                                                <i class="fa fa-trash"></i> Delete</a>
                                        @endif
                                    </div>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9">No Pre-overtime Request Found !!!</td>
                            </tr>
                        @endif

                    </table>
                    <span style="margin: 5px;float: right;">
                        @if($pre_overtimes->total() != 0)
                            {!! $pre_overtimes->appends(\Request::except('page'))->render() !!}
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Warning modal -->
<div id="modal_theme_warning" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h6 class="modal-title">Are you sure to Delete a Request ?</h6>
            </div>

            <div class="modal-body">
                <a class="btn btn-success get_link" href="">Yes</a> &nbsp; | &nbsp;
                <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
            </div>

            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<!-- /warning modal -->

@endsection
