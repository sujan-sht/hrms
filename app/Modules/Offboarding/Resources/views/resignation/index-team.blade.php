@extends('admin::layout')
@section('title') Resignations @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Resignations</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('offboarding::resignation.partial.advance_filter')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Resignations</h6>
            All the Resignation Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1 mr-2">
            <a href="{{ route('resignation.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                Add</a>
        </div>
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
                                </div>
                            </td>
                            <td>{{ $resignationModel->remark }}</td>
                            <td>{{ $resignationModel->last_working_date ? date('M d, Y', strtotime($resignationModel->last_working_date)) : '-' }}
                            </td>
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
                            <td>{{ getStandardDateFormat($resignationModel->created_at) }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $resignationModel->statusDetail['color'] }}">{{ $resignationModel->statusDetail['status'] }}</span>
                            </td>
                            <td class="text-center">
                                @if ($menuRoles->assignedRoles('resignation.updateStatus'))
                                    @php
                                        if (
                                            !isset(
                                                optional(
                                                    optional($resignationModel->employeeModel)
                                                        ->employeeOffboardApprovalDetailModel,
                                                )->last_approval,
                                            )
                                        ) {
                                            $checkStatusValue = 'A';
                                        } elseif (
                                            auth()->user()->id ==
                                            optional(
                                                optional($resignationModel->employeeModel)
                                                    ->employeeOffboardApprovalDetailModel,
                                            )->first_approval
                                        ) {
                                            $checkStatusValue = 'B';
                                        } elseif (
                                            auth()->user()->id ==
                                            optional(
                                                optional($resignationModel->employeeModel)
                                                    ->employeeOffboardApprovalDetailModel,
                                            )->last_approval
                                        ) {
                                            $checkStatusValue = 'C';
                                        } else {
                                            $checkStatusValue = 'D';
                                        }
                                    @endphp
                                    <a class="btn btn-sm btn-outline-warning btn-icon updateStatus mr-1"
                                        data-toggle="modal" data-target="#updateStatus"
                                        data-id="{{ $resignationModel->id }}"
                                        data-status="{{ $resignationModel->status }}"
                                        data-check-status="{{ $checkStatusValue }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Status">
                                        <i class="icon-flag3"></i>
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

@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.updateStatus').on('click', function() {
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            var checkStatus = $(this).attr('data-check-status');
            $('#resignationId').val(id);
            // $('#resignationStatus').val(status);

            let newOption;
            if (checkStatus == 'A') {
                newOption =
                    '<option value="1">Pending</option><option value="3">Accept</option><option value="4">Reject</option>';
            } else if (checkStatus == 'B') {
                newOption =
                    '<option value="1">Pending</option><option value="2">Forward</option><option value="4">Reject</option>';
            } else if (checkStatus == 'C') {
                newOption =
                    '<option value="2">Forward</option><option value="3">Accept</option><option value="4">Reject</option>';
            } else {
                newOption =
                    '<option value="1">Pending</option><option value="3">Forward</option><option value="3">Accept</option><option value="4">Reject</option>';
            }

            $('#resignationStatus').html(newOption).select2();
            $('#resignationStatus option[value=' + status + ']').prop('selected', true);
        });
    });
</script>
@endSection
