@extends('admin::layout')
@section('title') Resignations @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Resignations</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('css')
<style>
    .error {
        color: red;
    }
</style>
@endsection

@section('content')


@if (Auth::user()->user_type == 'super_admin' || Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'hr')
    @include('offboarding::resignation.partial.advance_filter')
@endif

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Resignations</h6>
            All the Resignation Information will be listed below. You can Create and Modify the data.
        </div>
        @if ($menuRoles->assignedRoles('resignation.create'))
            <div class="mt-1 mr-2">
                <a href="{{ route('resignation.create') }}" class="btn btn-success rounded-pill"><i
                        class="icon-plus2"></i> Add</a>
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
                    <th>Employee Name</th>
                    <th>Reason</th>
                    <th>Last Working Date</th>
                    <th>Attachment</th>
                    <th>Applied Date</th>
                    <th>Status</th>
                    <th width="15%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($resignationModels->total() != 0)
                    @foreach ($resignationModels as $key => $resignationModel)
                        <tr>
                            <td width="5%">#{{ $resignationModels->firstItem() + $key }}</td>
                            <td>
                                <div class="media">
                                    <div class="mr-3">
                                        <a>
                                            <img src="{{ optional($resignationModel->employeeModel)->getImage() }}"
                                                class="rounded-circle" width="40" height="40" alt="">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="media-title font-weight-semibold">
                                            {{ optional($resignationModel->employeeModel)->full_name }}</div>
                                        <span class="text-muted">Code :
                                            {{ optional($resignationModel->employeeModel)->employee_code }}</span>
                                    </div>
                            </td>
                            <td>{{ $resignationModel->remark }}</td>
                            @php
                                $lastWorkingDate =
                                    setting('calendar_type') == 'BS'
                                        ? date_converter()->eng_to_nep_convert($resignationModel->last_working_date)
                                        : getStandardDateFormat($resignationModel->last_working_date);
                            @endphp
                            <td>{{ $resignationModel->last_working_date ? $lastWorkingDate : '-' }}</td>
                            <td>
                                <ul class="media-list">
                                    <li class="media">
                                        <a href="{{ $resignationModel->attachment_file }}" target="_blank"
                                            class="text-secondary">
                                            <i class="icons icon-file-pdf mr-2"></i>Resignation_Letter.pdf
                                        </a>
                                    </li>
                                </ul>
                            </td>
                            @php
                                $createdDate =
                                    setting('calendar_type') == 'BS'
                                        ? date_converter()->eng_to_nep_convert(
                                            date('Y-m-d', strtotime($resignationModel->created_at)),
                                        )
                                        : getStandardDateFormat($resignationModel->created_at);
                            @endphp
                            <td>{{ $createdDate }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $resignationModel->statusDetail['color'] }}">{{ $resignationModel->statusDetail['status'] }}</span>
                            </td>
                            <td class="text-center">
                                <!-- <a href="{{ route('resignation.view', $resignationModel->id) }}" class="btn btn-sm btn-outline-secondary btn-icon mr-1" data-popup="tooltip" data-placement="top" data-original-title="View">
                                        <i class="icon-eye"></i>
                                    </a> -->
                                @if ($menuRoles->assignedRoles('resignation.showReport'))
                                    @if ($resignationModel->status == 3)
                                        <a class="btn btn-sm btn-outline-secondary btn-icon mr-1"
                                            href="{{ route('resignation.showReport', $resignationModel->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Report">
                                            <i class="icon-file-text2"></i>
                                        </a>
                                    @endif
                                @endif
                                @if ($menuRoles->assignedRoles('resignation.updateStatus') && auth()->user()->emp_id != $resignationModel->employee_id)
                                    <a class="btn btn-sm btn-outline-{{ $resignationModel->statusDetail['color'] }} btn-icon updateStatus mr-1"
                                        data-toggle="modal" data-target="#updateStatus"
                                        data-id="{{ $resignationModel->id }}"
                                        data-status="{{ $resignationModel->status }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Status">
                                        <i class="icon-flag3"></i>
                                    </a>
                                @endif

                                @if ($menuRoles->assignedRoles('resignation.letterIssued') || $menuRoles->assignedRoles('resignation.letterReceived'))
                                    @if ($resignationModel->status == 5)
                                        @if (is_null($resignationModel->issued_date))
                                            <a class="btn btn-sm btn-outline-secondary btn-icon letterIssuedModal"
                                                data-toggle="modal" data-target="#letterIssuedModal"
                                                data-popup="tooltip" data-id="{{ $resignationModel->id }}"
                                                data-placement="top" data-original-title="Letter Issued">
                                                <i class="icon-arrow-up7"></i>
                                            </a>
                                        @else
                                            <a class="btn btn-sm btn-outline-secondary btn-icon letterReceivedModal"
                                                data-toggle="modal" data-target="#letterReceivedModal"
                                                data-popup="tooltip" data-id="{{ $resignationModel->id }}"
                                                data-placement="top" data-original-title="Letter Received">
                                                <i class="icon-arrow-down7"></i>
                                            </a>
                                        @endif
                                    @endif
                                @endif

                                @if ($menuRoles->assignedRoles('resignation.edit'))
                                    <a class="btn btn-sm btn-outline-primary btn-icon mr-1"
                                        href="{{ route('resignation.edit', $resignationModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif

                                @if ($menuRoles->assignedRoles('resignation.delete'))
                                    <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete"
                                        link="{{ route('resignation.delete', $resignationModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="10">No Record Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $resignationModels->appends(request()->all())->links() }}
        </span>
    </div>
</div>

<!-- popup modal -->
<div id="updateStatus" class="modal fade" tabindex="-1">
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
                    'route' => 'resignation.updateStatus',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'role' => 'form',
                ]) !!}
                {!! Form::hidden('id', null, ['id' => 'resignationId']) !!}
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Status :</label>
                    <div class="col-lg-9">
                        {!! Form::select('status', $statusList, null, ['id' => 'resignationStatus', 'class' => 'form-control']) !!}
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

<!-- letter issued modal -->
<div id="letterIssuedModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Letter Issued</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open([
                    'route' => 'resignation.letterIssued',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'role' => 'form',
                ]) !!}
                {!! Form::hidden('id', null, ['id' => 'resignationId']) !!}
                <div class="form-group">
                    <div class="row">
                        <label class="col-form-label col-lg-3">Date : <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            {!! Form::text('issued_date', null, [
                                // 'id' => 'atdStatus',
                                'placeholder' => 'e.g: YYYY-MM-DD',
                                'class' => 'form-control daterange-single',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div id="statusMessage" class="row mt-3">
                        <label class="col-form-label col-lg-3">Message :</label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('issued_remark', null, [
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

<!-- letter received modal -->
<div id="letterReceivedModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Letter Received</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open([
                    'route' => 'resignation.letterReceived',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'role' => 'form',
                ]) !!}
                {!! Form::hidden('id', null, ['id' => 'resignationId']) !!}
                <div class="form-group">
                    <div class="row">
                        <label class="col-form-label col-lg-3">Date Received: <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            {!! Form::text('received_date', null, [
                                'placeholder' => 'e.g: YYYY-MM-DD',
                                'class' => 'form-control daterange-single',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <label class="col-form-label col-lg-3">Received By: <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            {!! Form::text('received_by', null, [
                                'placeholder' => 'Enter Received By',
                                'class' => 'form-control',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div id="statusMessage" class="row mt-3">
                        <label class="col-form-label col-lg-3">Message :</label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('received_remark', null, [
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

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#updateStatus').on('show.bs.modal', function(e) {
            // $('#resignationStatus').select2();
            var id = $(e.relatedTarget).data('id');
            status = $(e.relatedTarget).data('status');

            $('#resignationId').val(id);
            // $('#resignationStatus').val(status).select2();
            $('#resignationStatus option[value=' + status + ']').prop('selected', true);

        })

        $('#letterIssuedModal, #letterReceivedModal').on('show.bs.modal', function(e) {
            var id = $(e.relatedTarget).data('id');
            $(this).find('#resignationId').val(id);
        })
    });
</script>
@endSection
