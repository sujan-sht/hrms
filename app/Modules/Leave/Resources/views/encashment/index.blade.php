@extends('admin::layout')
@section('title') Leave Encashment @stop

@section('breadcrum')
    <a class="breadcrumb-item active">Leave Encashment</a>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
                style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>
    @php
        $colors = ['Pending' => 'secondary', 'Encashed' => 'success'];
    @endphp
    <div class="card">
        <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
            <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('leave.encashment') }}" method="GET">
                <div class="row">

                    <div class="col-md-3 mb-2">
                        <label class="form-label">Organization</label>
                        {!! Form::select('organization_id', $organizationList, $value = request('organization_id') ?: null, [
                            'placeholder' => 'Select Organization',
                            'class' => 'form-control select-search',
                        ]) !!}
                    </div>

                </div>
                <div class="d-flex justify-content-end mt-2">
                    <button class="btn bg-yellow mr-2" type="submit">
                        <i class="icon-filter3 mr-1"></i>Filter
                    </button>
                    <a href="{{ request()->url() }}" class="btn bg-secondary text-white">
                        <i class="icons icon-reset mr-1"></i>Reset
                    </a>
                </div>
            </form>

        </div>
    </div>

    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Encashment Threshold</th>
                        <th>Leave Remaining</th>
                        <th>Exceeded Balance</th>
                        <th>Total Balance</th>
                        <th>Eligible Encashment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($leaveEncashmentLogs) > 0)
                        @foreach ($leaveEncashmentLogs as $key => $leaveEncashmentLog)
                            <tr>
                                <td width="5%">#{{ $leaveEncashmentLogs->firstItem() + $key }}</td>
                                <td>{{ optional($leaveEncashmentLog->employee)->full_name }}</td>
                                <td>{{ optional($leaveEncashmentLog->leaveType)->name }}</td>
                                <td>{{ $leaveEncashmentLog->encashment_threshold }}</td>
                                <td>{{ $leaveEncashmentLog->leave_remaining }}</td>
                                <td>{{ $leaveEncashmentLog->exceeded_balance }}</td>
                                <td>{{ $leaveEncashmentLog->total_balance }}</td>
                                <td>{{ $leaveEncashmentLog->eligible_encashment }}</td>

                                <td class="text-center">
                                    <span class="badge badge-{{ $colors[$leaveEncashmentLog->getStatus() ?? 'Pending'] }}">{{ $leaveEncashmentLog->getStatus() ?? 'Pending' }}</span>
                                </td>
                                <td>
                                    @if ($leaveEncashmentLog->status == 1 && $leaveEncashmentLog->eligible_encashment > 0)
                                        <a data-toggle="modal" data-target="#updateStatus"
                                            class="btn btn-outline-success btn-icon updateStatus mx-1"
                                            data-id="{{ $leaveEncashmentLog->id }}"
                                            data-eligible-encashment="{{ $leaveEncashmentLog->eligible_encashment }}"
                                            data-income-type = "{{ optional(optional(optional(optional($leaveEncashmentLog->employee)->organizationModel)->leaveEncashmentSetup)->incomeSetup)->title }}"
                                            data-income-type-id = "{{ optional(optional(optional(optional($leaveEncashmentLog->employee)->organizationModel)->leaveEncashmentSetup)->incomeSetup)->id }}"
                                            data-popup="tooltip" data-placement="top"
                                            data-original-title="Update Status">
                                            <i class="icon-coin-dollar"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">Employee Leave Encashment Details Not Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $leaveEncashmentLogs->appends(request()->all())->links() }}
            </span>
        </div>
    </div>

    <!-- popup modal -->
    <div id="updateStatus" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Are you sure you want to encash this leave?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open([
                        'route' => 'leave.updateEncashmentStatus',
                        'method' => 'POST',
                        'class' => 'form-horizontal updateLeaveEncashable',
                        'role' => 'form',
                    ]) !!}
                    {!! Form::hidden('encash_log_id', null, ['id' => 'encashLogId']) !!}
                    {!! Form::hidden('income_type_id', null, ['id' => 'incomeTypeId']) !!}

                    <div class="form-group">
                        <div class="row mb-1">
                            <label class="col-form-label col-lg-4">Eligible Encashment :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                {!! Form::text('eligible_encashment', null, ['id' => 'eligibleEncashment', 'class' => 'form-control', 'readonly']) !!}
                            </div>
                        </div>

                        <div class="row mb-1">
                            <label class="col-form-label col-lg-4">Income Type :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                {!! Form::text('income_type', null, ['id' => 'incomeType', 'class' => 'form-control', 'readonly', 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn bg-success text-white">Encash</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        $(document).ready(function() {
            $('.updateStatus').on('click', function(e) {
                e.preventDefault();
                var encashment_log_id = $(this).data('id');
                var eligible_encashment = $(this).data('eligible-encashment');
                var income_type = $(this).data('income-type');
                var income_type_id = $(this).data('income-type-id');

                $('.updateLeaveEncashable').find('#encashLogId').val(encashment_log_id);
                $('.updateLeaveEncashable').find('#eligibleEncashment').val(eligible_encashment);
                $('.updateLeaveEncashable').find('#incomeType').val(income_type);
                $('.updateLeaveEncashable').find('#incomeTypeId').val(income_type_id);
            });
        });
    </script>
@endSection
