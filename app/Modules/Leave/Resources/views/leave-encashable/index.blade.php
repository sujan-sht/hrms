@extends('admin::layout')
@section('title') Leave Encashable Summary @stop

@section('breadcrum')
    <a href="{{ route('leave.encashableLeave') }}" class="breadcrumb-item">Leave Encashable</a>
    <a class="breadcrumb-item active">Summary</a>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
                style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>
    <div class="card">
        <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
            <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('leave.encashableLeave') }}" method="GET">
                <div class="row">

                    <div class="col-md-3 mb-2">
                        <label class="form-label">Organization <span class="text-danger">*</span></label>
                        {!! Form::select('organization_id', $organizationList, $value = request('organization_id') ?: null, [
                            'placeholder' => 'Select Organization',
                            'class' => 'form-control select2 organization-filter2',
                            'required',
                        ]) !!}
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Unit</label>
                        @php
                            if (isset($_GET['branch_id'])) {
                                $branchValue = $_GET['branch_id'];
                            } else {
                                $branchValue = null;
                            }
                        @endphp
                        {!! Form::select('branch_id', $branchList, $value = $branchValue, [
                            'placeholder' => 'Select Unit',
                            'class' => 'form-control select2 branch-filter',
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

    <div class="card">
        <div class="card-body">
            <legend class="text-uppercase font-size-sm font-weight-bold">Indexes</legend>
            <div class="row">
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm alpha-danger text-danger-800 border-danger-600">TL</button>
                    <span class="text-danger-800 ml-1">Total Leave</span>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm alpha-slate text-slate-800 border-slate-600">LT</button>
                    <span class="text-slate-800 ml-1">Leave Taken</span>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm alpha-info text-info-800 border-info-600">RL</button>
                    <span class="text-info-800 ml-1">Remaining Leave</span>
                </div>
            </div>
        </div>
    </div>



    {{-- @include('leave::leave-opening.employee-widget') --}}
    @if (request('organization_id'))
        <div class="card card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th>S.N</th>
                            <th>Employee Name</th>
                            @if (!empty($allLeaveTypes))
                                @foreach ($allLeaveTypes as $leaveType)
                                    <th class="text-center">{{ $leaveType }}
                                        <table class="table table-hover">
                                            <thead>
                                                <tr class="text-light btn-slate">
                                                    <th>TL</th>
                                                    <th>LT</th>
                                                    <th>RL</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </th>
                                @endforeach
                            @endif
                            <th>Total Leaves</th>
                            <th>Status</th>
                            <th>Action</th>

                            {{-- <th>Percentage</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($employeeLeaveTypes) > 0 && !empty($allLeaveTypes))
                            @foreach ($employeeLeaveTypes as $key => $employeeLeaveType)
                                <tr>
                                    <td width="5%">#{{ $employeeLeaveTypes->firstItem() + $key }}</td>
                                    @php
                                        if (
                                            $employeeLeaveType->profile_pic != null &&
                                            $employeeLeaveType->profile_pic != ''
                                        ) {
                                            $profile_pic = asset(
                                                'uploads/employee/profile_pic/' . $employeeLeaveType->profile_pic,
                                            );
                                        } else {
                                            $profile_pic = asset('admin/default.png');
                                        }

                                        if (!empty($employeeLeaveType->middle_name)) {
                                            $full_name =
                                                $employeeLeaveType->first_name .
                                                ' ' .
                                                $employeeLeaveType->middle_name .
                                                ' ' .
                                                $employeeLeaveType->last_name;
                                        } else {
                                            $full_name =
                                                $employeeLeaveType->first_name . ' ' . $employeeLeaveType->last_name;
                                        }
                                    @endphp

                                    <td class="d-flex text-nowrap">
                                        <div class="media">
                                            <div class="mr-3">
                                                <a href="#">
                                                    <img src="{{ $profile_pic }}" class="rounded-circle" width="40"
                                                        height="40" alt="">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <div class="media-title font-weight-semibold">{{ $full_name }}</div>
                                                <span class="text-muted">Code:
                                                    {{ $employeeLeaveType->employee_code }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    @if ($employeeLeaveType['leaveDetails'])
                                        @foreach ($employeeLeaveType['leaveDetails'] as $leaveDetail)
                                            <td>
                                                <table class="table table-border">
                                                    <tr>
                                                        <td>{{ $leaveDetail['total_leave'] }}</td>
                                                        <td>{{ $leaveDetail['leave_taken'] }}</td>
                                                        <td style="border-right: 1px solid black">
                                                            {{ $leaveDetail['leave_remain'] }}</td>

                                                    </tr>
                                                </table>
                                            </td>
                                        @endforeach
                                    @else
                                        <td>
                                            <table class="table table-border">
                                                <tr>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td style="border-right: 1px solid black">0</td>

                                                </tr>
                                            </table>
                                        </td>
                                    @endif
                                    <td class="leave-remain">
                                        {{ $employeeLeaveType->remainingLeave }}
                                    </td>
                                    <td>
                                        @if ($employeeLeaveType->checkEnchasableLeave == false)
                                            <span class="badge badge-danger">Unpaid</span>
                                        @else
                                            <span class="badge badge-success">Paid</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($employeeLeaveType->checkEnchasableLeave == false)
                                            <a data-toggle="modal" data-target="#updateStatus"
                                                class="btn btn-outline-success btn-icon updateStatus mx-1"
                                                data-emp-id="{{ $employeeLeaveType->id }}"
                                                data-leave-remain="{{ $employeeLeaveType->remainingLeave }}"
                                                data-popup="tooltip" data-placement="top"
                                                data-original-title="Encashable Status">
                                                <i class="icon-coin-dollar"></i>
                                            </a>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">No Employee Encashable Leave Details Found !!!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <span class="float-right pagination align-self-end mt-3">
                    {{ $employeeLeaveTypes->appends(request()->all())->links() }}
                </span>
            </div>
        </div>
    @endif


    <!-- popup modal -->
    <div id="updateStatus" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Encashable Leave Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open([
                        'route' => 'leave.storeEncashableLeave',
                        'method' => 'POST',
                        'class' => 'form-horizontal updateLeaveEncashable',
                        'role' => 'form',
                    ]) !!}
                    {!! Form::hidden('employee_id', null, ['id' => 'employee_id']) !!}
                    {!! Form::hidden('leave_year_id', $previousLeaveYear->id, ['id' => 'leave_year_id']) !!}
                    <div class="form-group">
                        <div class="row mb-1">
                            <label class="col-form-label col-lg-3">No. of Leave :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                {!! Form::text('total_leave', $value ?? null, ['id' => 'total_leave', 'class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="row mb-1">
                            <label class="col-form-label col-lg-3">Cash :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                {!! Form::text('amt', null, ['id' => 'amt', 'class' => 'form-control numeric', 'required']) !!}
                            </div>
                        </div>

                        <div class="row mb-1">
                            <label class="col-form-label col-lg-3">Payment Date :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                {!! Form::text('payment_date', null, [
                                    'id' => 'payment_date',
                                    'class' => 'form-control daterange-single',
                                    'required',
                                ]) !!}
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
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script>
        $(document).ready(function() {
            // initiate select2
            $('.select2').select2();

            $('#leaveStatus').on('change', function() {
                var status = $(this).val();
                if (status == '2' || status == '4') {
                    $('#statusMessage').show();
                } else {
                    $('#statusMessage').hide();
                }
            });

            $('.updateStatus').on('click', function(e) {
                e.preventDefault();
                var emp_id = $(this).data('emp-id');
                leave_remain = $(this).data('leave-remain')
                // var leave_remain = $(this).closest('tr').find('.leave-remain').text();
                // l1=leave_remain.replace(/^(?=\n)$|^\s*|\s*$|\n\n+/gm,"")
                $('.updateLeaveEncashable').find('#employee_id').val(emp_id);
                $('.updateLeaveEncashable').find('#total_leave').val(leave_remain);
            });
        });
    </script>
@endSection
