@extends('admin::layout')
@section('title')
    Substitute Leave
@endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Claimed Substitute Leaves</a>
@endsection

@section('content')


    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    {{-- @include('employee::employee-substitute-leave.partial.advance-search') --}}


    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Claimed Substitute Leaves</h6>
                All the Requested Substitute Leave Information will be listed below.
            </div>
        </div>
    </div>

    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Employee Name</th>
                        <th>Applied For</th>
                        {{-- <th>Remark</th> --}}
                        <th>Claim Status</th>
                        {{-- <th>Applied Date</th> --}}
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($employeeSubstituteLeaveModels->total() != 0)
                        @foreach ($employeeSubstituteLeaveModels as $key => $employeeSubstituteLeaveModel)
                        @inject('leave_flow', '\App\Modules\Leave\Repositories\LeaveRepository')
                        @php
                            $emp_leave_flow = $leave_flow->getEmployeeApprovalFlow($employeeSubstituteLeaveModel->employee_id);
                            $emp_status = 8;
                            if ($emp_id == $employeeSubstituteLeaveModel->employee_id) {
                                $emp_status = 1; //pending
                            } else {
                                if (!empty($emp_leave_flow)) {
                                    if (!empty($emp_leave_flow->first_approval_user_id) && $emp_leave_flow->first_approval_user_id > 0) {
                                        if (optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->claim_status == 1 && $emp_leave_flow->first_approval_user_id == $user_id) {
                                            $emp_status = 5; //pending, rejected, forwarded
                                        } elseif (optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->claim_status == 2 && $emp_leave_flow->first_approval_user_id == $user_id) {
                                            $emp_status = 2; //forwarded
                                        } elseif (optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->claim_status == 4 && $emp_leave_flow->first_approval_user_id == $user_id) {
                                            $emp_status = 4; //rejected
                                        } elseif (optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->claim_status == 2 && $emp_leave_flow->last_approval_user_id == $user_id) {
                                            $emp_status = 7; //forwarded, rejected, accepted
                                        } elseif (optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->claim_status == 1 && $emp_leave_flow->last_approval_user_id == $user_id) {
                                            $emp_status = 6; //pending
                                        } elseif (optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->claim_status == 3 && $emp_leave_flow->first_approval_user_id == $user_id) {
                                            $emp_status = 3; //empty
                                        } elseif (optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->claim_status != 1 && optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->claim_status != 2 && $emp_leave_flow->last_approval_user_id == $user_id) {
                                            $emp_status = 3; //empty
                                        }
                                    } else {
                                        if ($emp_leave_flow->last_approval_user_id == $user_id) {
                                            $emp_status = 9; //rejected, accepted
                                        }
                                    }
                                }
                            }
                        @endphp
                            <tr>
                                <td width="5%">#{{ $employeeSubstituteLeaveModels->firstItem() + $key }}</td>
                                <td>
                                    <div class="media">
                                        <div class="mr-3">
                                            <a href="#">
                                                <img src="{{ optional($employeeSubstituteLeaveModel->employee)->getImage() }}"
                                                    class="rounded-circle" width="40" height="40" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-title font-weight-semibold">
                                                {{ optional($employeeSubstituteLeaveModel->employee)->getFullName() }}</div>
                                            <span
                                                class="text-muted">{{ optional($employeeSubstituteLeaveModel->employee)->official_email }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ leaveYearSetup('calendar_type') == "BS" ?  $employeeSubstituteLeaveModel->nepali_date : date('M d, Y', strtotime($employeeSubstituteLeaveModel->date)) }}</td>
                                {{-- <td>{{ $employeeSubstituteLeaveModel->remark }}</td> --}}
                                <td>
                                    <span
                                        class="badge badge-{{ optional(optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->getClaimStatusWithColor())['color'] }}">{{ optional(optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->getClaimStatusWithColor())['claim_status'] }}</span>
                                </td>
                                @php
                                    $createdDate = leaveYearSetup('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($employeeSubstituteLeaveModel->created_at))) : date('M d, Y', strtotime($employeeSubstituteLeaveModel->created_at));
                                @endphp
                                {{-- <td>{{ $createdDate }}</td> --}}

                                <td class="d-flex">

                                    @if (auth()->user()->user_type == 'super_admin' || ($menuRoles->assignedRoles('substituteLeave.updateClaimStatus') && in_array(optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->claim_status, [1,2])))
                                        <a data-toggle="modal" data-target="#updateClaimStatus" class="btn btn-outline-warning btn-icon updateClaimStatus mr-1" data-id="{{ optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->id }}"
                                            data-status="{{ optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->claim_status }}" emp_status="{{ $emp_status }}" data-popup="tooltip" data-placement="top" data-original-title="Claim Status">
                                            <i class="icon-flag3"></i>
                                        </a>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">No Record Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $employeeSubstituteLeaveModels->appends(request()->all())->links() }}
            </span>
        </div>
    </div>

    <!-- popup modal -->
    <div id="updateClaimStatus" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Update Claim Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open([
                        'route' => 'substituteLeave.updateClaimStatus',
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                    ]) !!}
                    {!! Form::hidden('id', null, ['id' => 'leaveId']) !!}
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Claim Status :</label>
                        <div class="col-lg-9">
                            {!! Form::select('claim_status', $claimStatusList, null, ['id' => 'claimStatus', 'class' => 'form-control select-search', 'required']) !!}
                        </div>
                    </div>
                    <div class="form-group row remarksDiv" style="display: none;">
                        <label class="col-form-label col-lg-3">Remarks :</label>
                        <div class="col-lg-9">
                            {!! Form::textarea('status_message', null, ['class' => 'form-control', 'required']) !!}
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
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#claimStatus').select2();
            $('#claimStatus').on('change', function() {
                var status = $(this).val();
                if(status == '2' || status == '4') {
                    $('.remarksDiv').show();
                } else {
                    $('.remarksDiv').hide();
                }
            });
            $('.updateClaimStatus').on('click', function() {
                var id = $(this).attr('data-id');
                console.log(id);
                var status = $(this).attr('data-status');
                var emp_status = $(this).attr('emp_status');

                let option_html;
                if (emp_status == 1 || emp_status == 6) {
                    option_html = '<option value="1">Pending</option>';
                } else if (emp_status == 5) {
                    option_html =
                        '<option value="1">Pending</option><option value="2">Forwarded</option><option value="4">Rejected</option>';
                } else if (emp_status == 7) {
                    option_html =
                        '<option value="2">Forwarded</option><option value="3">Accepted</option><option value="4">Rejected</option>';
                } else if (emp_status == 2) {
                    option_html = '<option value="2">Forwarded</option>';
                } else if (emp_status == 3) {
                    option_html = '';
                } else if (emp_status == 4) {
                    option_html = '<option value="4">Rejected</option>';
                } else if (emp_status == 9) {
                    option_html =
                        '<option value="1">Pending</option><option value="3">Accepted</option><option value="4">Rejected</option>';
                }

                $('#leaveId').val(id);
                $('#claimStatus').val(status);
                $('#claimStatus').html(option_html);
                $('.remarksDiv').hide();

                $('#claimStatus option[value=' + status + ']').prop('selected', true);
            });

        });
    </script>
@endSection
