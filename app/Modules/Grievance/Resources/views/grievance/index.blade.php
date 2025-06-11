@extends('admin::layout')
@section('title')Grievance @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Grievance</a>
@stop
@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@stop
@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Grievances</h6>
                All the Grievance Informations will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('grievance.create'))
                <div class="mr-2">
                    <a href="{{ route('grievance.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                        Add</a>
                </div>
            @endif
            @if ($menuRoles->assignedRoles('grievance.exportAll'))
                <div class="list-icons mt-2">
                    <div class="dropdown position-static">
                        <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false">
                            <i class="icon-more2"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">

                            <a href="{{ route('grievance.exportAll') }}" class="dropdown-item">
                                <i class="icon-file-excel text-success"></i> Export
                            </a>

                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>


    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th width="30%">Remark</th>
                        @if (
                            $menuRoles->assignedRoles('grievance.delete') ||
                                $menuRoles->assignedRoles('grievance.view') ||
                                $menuRoles->assignedRoles('grievance.updateStatus'))
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($grievances->total() != 0)
                        @foreach ($grievances as $key => $value)
                            <tr>
                                <td>#{{ $grievances->firstItem() + $key }}</td>
                                <td>{{ $value->getSubjectType() }}</td>
                                <td>{{ getStandardDateFormat($value->created_at) }}</td>
                                <td>{{ $value->getStatus() }}
                                    @if ($value->status == 1)
                                        <br>
                                        <em>
                                            {{ getStandardDateFormat($value->resolved_date) }}
                                        </em>
                                    @endif
                                </td>
                                <td>{!! Str::limit($value->remark, 150) !!}</td>

                                {{-- <td>{{ $value->is_anonymous == 11 ? 'Yes' : 'No' }}</td> --}}
                                @if (
                                    $menuRoles->assignedRoles('grievance.delete') ||
                                        $menuRoles->assignedRoles('grievance.view') ||
                                        $menuRoles->assignedRoles('grievance.updateStatus'))
                                    <td>
                                        @if ($menuRoles->assignedRoles('grievance.view'))
                                            <a class="btn btn-outline-secondary btn-icon mx-1"
                                                href="{{ route('grievance.view', $value->id) }}" data-popup="tooltip"
                                                data-original-title="View" data-placement="bottom"><i
                                                    class="icon-eye"></i></a>
                                        @endif

                                        @if ($menuRoles->assignedRoles('grievance.delete'))
                                            <a data-toggle="modal" data-target="#modal_theme_warning"
                                                class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                                link="{{ route('grievance.delete', $value->id) }}" data-popup="tooltip"
                                                data-original-title="Delete" data-placement="bottom">
                                                <i class="icon-trash-alt"></i>
                                            </a>
                                        @endif

                                        @if ($menuRoles->assignedRoles('grievance.updateStatus'))
                                            <a data-toggle="modal" data-target="#updateStatus"
                                                class="btn btn-outline-warning btn-icon updateStatus mx-1"
                                                data-id="{{ $value->id }}" data-status="{{ $value->status }}"
                                                data-popup="tooltip" data-placement="top" data-original-title="Status">
                                                <i class="icon-flag3"></i>
                                            </a>
                                        @endif

                                    </td>
                                @endif

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No Grievance Found !!!</td>
                        </tr>
                    @endif
                </tbody>

            </table>
            <span style="margin: 5px;float: right;">
                @if ($grievances->total() != 0)
                    {{ $grievances->links() }}
                @endif
            </span>
        </div>
    </div>

    <div id="updateStatus" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Update Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open([
                        'route' => 'grievance.updateStatus',
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                    ]) !!}
                    {!! Form::hidden('id', null, ['id' => 'grievanceId']) !!}
                    <div class="form-group">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Status :</label>
                            <div class="col-lg-9">
                                {!! Form::select('status', $statusList, null, [
                                    'id' => 'grievanceStatus',
                                    'class' => 'form-control select2',
                                    'placeholder' => 'Choose Status',
                                ]) !!}
                            </div>
                        </div>
                        <div id="resolvedDiv" class="row mt-3" style="display:none;">
                            <label class="col-form-label col-lg-3">Date of Issue Resolved :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('resolved_date', null, [
                                        'placeholder' => 'Choose Date',
                                        'class' => 'form-control daterange-single',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        <div id="statusMessage" class="row mt-3" style="display:none;">
                            <label class="col-form-label col-lg-3">Remarks :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::textarea('remark', null, [
                                        'rows' => 3,
                                        'placeholder' => 'Write message..',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn bg-success text-white">Save Changes</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
@section('popupScript')
    <script>
        $(document).ready(function() {
            $('body').on('change', '#grievanceStatus', function() {
                var status = $(this).val();
                if (status == '1') {
                    $('#resolvedDiv').show();
                    $('#statusMessage').hide();
                } else {
                    $('#resolvedDiv').hide();
                    $('#statusMessage').show();

                }
            });

            $('#updateStatus').on('show.bs.modal', function(e) {
                var id = $(e.relatedTarget).data('id');
                status = $(e.relatedTarget).data('status');

                $('#grievanceId').val(id);
            })

        })
    </script>
@endsection
