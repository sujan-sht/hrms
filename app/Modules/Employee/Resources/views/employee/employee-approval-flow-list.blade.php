@extends('admin::layout')

@section('title')
    Approval Flow List
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active">Approval Flow List </a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @include('employee::employee.partial.emp-approval-flow-list-advance-filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Approval Flows</h6>
                All the Employees Approval Flows Information will be listed below.
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
                        <th>Leave</th>
                        <th>Attendance</th>
                        <th>Claim/Request</th>
                        <th>Offboard</th>
                        <th>Appraisal</th>
                        <th>Advance</th>
                        <th>Domestic Travel</th>
                        <th>Int'l Travel</th>
                        <th>Travel Request</th>
                        @if ($menuRoles->assignedRoles('employee.updateApprovalFlow'))
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @if ($employees->total() > 0)
                        @foreach ($employees as $key => $emp)
                            {{-- @dd($emp) --}}
                            <tr>
                                <td>
                                    #{{ $employees->firstItem() + $i }}
                                </td>
                                <td>
                                    <div class="media">
                                        <div class="mr-3">
                                            <a href="#">
                                                <img src="{{ $emp->getImage() }}" class="rounded-circle" width="40"
                                                    height="40" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-title font-weight-semibold">
                                                {{ $emp->getFullName() }}</div>
                                            <span
                                                class="text-muted">{{ $emp->official_email ?? $emp->personal_email }}</span>

                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <ul type="circle">
                                        @if (optional($emp->employeeApprovalFlowRelatedDetailModel)->userFirstApproval)
                                            <li>
                                                {{ optional(optional(optional($emp->employeeApprovalFlowRelatedDetailModel)->userFirstApproval)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                        @if (optional($emp->employeeApprovalFlowRelatedDetailModel)->userSecondApproval)
                                            <li>
                                                {{ optional(optional(optional($emp->employeeApprovalFlowRelatedDetailModel)->userSecondApproval)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                        @if (optional($emp->employeeApprovalFlowRelatedDetailModel)->userThirdApproval)
                                            <li>
                                                {{ optional(optional(optional($emp->employeeApprovalFlowRelatedDetailModel)->userThirdApproval)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                        @if (optional($emp->employeeApprovalFlowRelatedDetailModel)->userLastApproval)
                                            <li>
                                                {{ optional(optional(optional($emp->employeeApprovalFlowRelatedDetailModel)->userLastApproval)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                    </ul>

                                </td>


                                <td>
                                    <ul type="circle">
                                        @if (optional($emp->employeeAttendanceApprovalFlow)->userFirstApproval)
                                            <li>
                                                {{ optional(optional(optional($emp->employeeAttendanceApprovalFlow)->userFirstApproval)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                        @if (optional($emp->employeeAttendanceApprovalFlow)->userSecondApproval)
                                            <li>
                                                {{ optional(optional(optional($emp->employeeAttendanceApprovalFlow)->userSecondApproval)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                        @if (optional($emp->employeeAttendanceApprovalFlow)->userThirdApproval)
                                            <li>
                                                {{ optional(optional(optional($emp->employeeAttendanceApprovalFlow)->userThirdApproval)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                        @if (optional($emp->employeeAttendanceApprovalFlow)->userLastApproval)
                                            <li>
                                                {{ optional(optional(optional($emp->employeeAttendanceApprovalFlow)->userLastApproval)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                    </ul>

                                </td>

                                <td>
                                    <ul type="circle">
                                        @if (optional($emp->employeeClaimRequestApprovalDetailModel)->firstApproval)
                                            <li> {{ optional(optional(optional($emp->employeeClaimRequestApprovalDetailModel)->firstApproval)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                        @if (optional($emp->employeeClaimRequestApprovalDetailModel)->lastApproval)
                                            <li> {{ optional(optional(optional($emp->employeeClaimRequestApprovalDetailModel)->lastApproval)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                    </ul>
                                </td>

                                <td>
                                    <ul type="circle">
                                        @if (optional($emp->employeeOffboardApprovalDetailModel)->firstApprovalUserModel)
                                            <li> {{ optional(optional(optional($emp->employeeOffboardApprovalDetailModel)->firstApprovalUserModel)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                        @if (optional($emp->employeeOffboardApprovalDetailModel)->lastApprovalUserModel)
                                            <li>{{ optional(optional(optional($emp->employeeOffboardApprovalDetailModel)->lastApprovalUserModel)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                    </ul>
                                </td>

                                <td>
                                    <ul type="circle">
                                        @if (optional($emp->employeeAppraisalApprovalDetailModel)->firstApprovalUserModel)
                                            <li>{{ optional(optional(optional($emp->employeeAppraisalApprovalDetailModel)->firstApprovalUserModel)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                        @if (optional($emp->employeeAppraisalApprovalDetailModel)->lastApprovalUserModel)
                                            <li>{{ optional(optional(optional($emp->employeeAppraisalApprovalDetailModel)->lastApprovalUserModel)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                    </ul>
                                </td>
                                <td>
                                    <ul type="circle">
                                        @if (optional($emp->employeeAdvanceApprovalDetailModel)->firstApprovalUserModel)
                                            <li>{{ optional(optional(optional($emp->employeeAdvanceApprovalDetailModel)->firstApprovalUserModel)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                        @if (optional($emp->employeeAdvanceApprovalDetailModel)->lastApprovalUserModel)
                                            <li>{{ optional(optional(optional($emp->employeeAdvanceApprovalDetailModel)->lastApprovalUserModel)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                    </ul>
                                </td>
                                <td>
                                    <ul type="circle">
                                        @if (optional($emp->employeeBusinessTripApprovalDetailModel)->firstApprovalUserModel)
                                            <li>{{ optional(optional(optional($emp->employeeBusinessTripApprovalDetailModel)->firstApprovalUserModel)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                        @if (optional($emp->employeeBusinessTripApprovalDetailModel)->lastApprovalUserModel)
                                            <li>{{ optional(optional(optional($emp->employeeBusinessTripApprovalDetailModel)->lastApprovalUserModel)->userEmployer)->full_name }}
                                            </li>
                                        @endif
                                    </ul>
                                </td>

                                 <td> </td>
                                 <td> </td>
                                @if ($menuRoles->assignedRoles('employee.updateApprovalFlow'))
                                    <td>
                                        <a data-toggle="modal" data-target="#approvalModal"
                                            class="btn btn-outline-warning btn-icon mx-1" data-emp-id="{{ $emp->id }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Status">
                                            <i class="icon-flag3"></i>
                                        </a>
                                    </td>
                                @endif

                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">No record found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $employees->appends(request()->all())->links() }}
            </span>
        </div>
    </div>

    <!-- popup modal -->
    <div id="approvalModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Approval Flow</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- {!! Form::open([
                        'route' => 'leave.updateStatus',
                        'method' => 'POST',
                        'class' => 'form-horizontal updateLeaveStatusForm',
                        'role' => 'form',
                    ]) !!}
                    {!! Form::hidden('id', null, ['id' => 'leaveId']) !!}
                    <div class="form-group">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Status :</label>
                            <div class="col-lg-9">
                                {!! Form::select('status', [], null, ['id' => 'leaveStatus', 'class' => 'form-control select2']) !!}
                            </div>
                        </div>
                        <div id="statusMessage" class="row mt-3" style="display:none;">
                            <label class="col-form-label col-lg-3">Message :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::textarea('status_message', null, [
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
                    {!! Form::close() !!} --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom_script')
    <script>
        $(function() {
            $('#approvalModal').on('show.bs.modal', function(e) {
                var emp_id = $(e.relatedTarget).data('emp-id');
                var that = $(this);
                $.ajax({
                    url: "{{ route('employee.getApprovalUsers') }}",
                    type: "get", //send it through get method
                    data: {
                        emp_id: emp_id,
                    },
                    success: function(response) {
                        that.find('.modal-body').html(response.view);
                    },
                    error: function(xhr) {
                        //Do Something to handle error
                    }
                });
                // alert('asd');
            })
        });
    </script>
@endpush
