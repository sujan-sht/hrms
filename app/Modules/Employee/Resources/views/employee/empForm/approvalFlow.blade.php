<div class="form-group row approvalSection">
    <div class="col-md-6 mb-2">
        <legend class="text-uppercase font-size-sm font-weight-bold">Leave Approval</legend>
        <div class="form-group row" id="first_approval_create">
            <label class="col-form-label col-lg-4">First Approval :</span></label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {{-- {!! Form::select('first_approval_user_id',$userList, $value = null,
                    ['id'=>'first_approval_user_id','placeholder'=>'Select First Approval','class'=>'form-control
                    user-filter select-search', $isEmployee ? 'disabled' : '']) !!} --}}
                    {{-- @dd($userList) --}}
                    {!! Form::select('first_approval_user_id', $userList, $value = null, [
                        'id' => 'leaveFirstApproval',
                        'placeholder' => 'Select First Approval',
                        'class' => 'form-control select-search',
                        // $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
        {{-- <div class="form-group row" id="second_approval_create">
            <label class="col-form-label col-lg-4">Second Approval :</span></label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">

                    {!! Form::select('second_approval_user_id', $userList, $value = null, [
                    'id' => 'leaveSecondApproval',
                    'placeholder' => 'Select Second Approval',
                    'class' => 'form-control user-filter select-search',
                    $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row" id="third_approval_create">
            <label class="col-form-label col-lg-4">Third Approval :</span></label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('third_approval_user_id', $userList, $value = null, [
                    'id' => 'leaveThirdApproval',
                    'placeholder' => 'Select Third Approval',
                    'class' => 'form-control user-filter select-search',
                    $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div> --}}
        <div class="form-group row" id="last_approval_create">
            <label class="col-form-label col-lg-4">Last Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {{-- {!! Form::select('last_approval_user_id',$userList, $value = null,
                    ['id'=>'last_approval_user_id','placeholder'=>'Select Last Approval','class'=>'form-control
                    user-filter select-search', $isEmployee ? 'disabled' : '']) !!} --}}

                    {!! Form::select('last_approval_user_id', $userList, $value = null, [
                        'id' => 'leaveLastApproval',
                        'placeholder' => 'Select Last Approval',
                        'class' => 'form-control user-filter select-search',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>



    <div class="col-md-6 mb-2">
        <legend class="text-uppercase font-size-sm font-weight-bold">Attendance Approval</legend>
        @php
            $attendanceFlow = null;
            if ($is_edit) {
                $attendanceFlow = $employees->employeeAttendanceApprovalFlow ?? null;
            }
        @endphp
        {{-- First Approval --}}
        <div class="form-group row" id="first_approval_create">
            <label class="col-form-label col-lg-4">First Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select(
                        'attendance_first_approval_user_id',
                        $userList,
                        $is_edit ? optional($attendanceFlow)->first_approval_user_id : null,
                        [
                            'id' => 'attendance_first_approval_user_id',
                            'placeholder' => 'Select First Approval',
                            'class' => 'form-control select-search branch-filter',
                        ],
                    ) !!}
                </div>
            </div>
        </div>

        {{-- Second Approval --}}
        {{-- <div class="form-group row" id="second_approval_create">
            <label class="col-form-label col-lg-4">Second Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select(
                    'attendance_second_approval_user_id',
                    $userList,
                    $is_edit ? optional($attendanceFlow)->second_approval_user_id : null,
                    [
                    'id' => 'attendance_second_approval_user_id',
                    'placeholder' => 'Select Second Approval',
                    'class' => 'form-control select-search branch-filter'
                    ]
                    ) !!}
                </div>
            </div>
        </div> --}}

        {{-- Third Approval --}}
        {{-- <div class="form-group row" id="third_approval_create">
            <label class="col-form-label col-lg-4">Third Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select(
                    'attendance_third_approval_user_id',
                    $userList,
                    $is_edit ? optional($attendanceFlow)->third_approval_user_id : null,
                    [
                    'id' => 'attendance_third_approval_user_id',
                    'placeholder' => 'Select Third Approval',
                    'class' => 'form-control  select-search branch-filter'
                    ]
                    ) !!}
                </div>
            </div>
        </div> --}}

        {{-- Last Approval --}}
        <div class="form-group row" id="last_approval_create">
            <label class="col-form-label col-lg-4">Last Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select(
                        'attendance_last_approval_user_id',
                        $userList,
                        $is_edit ? optional($attendanceFlow)->last_approval_user_id : null,
                        [
                            'id' => 'attendance_last_approval_user_id',
                            'placeholder' => 'Select Last Approval',
                            'class' => 'form-control select-search branch-filter',
                        ],
                    ) !!}
                </div>
            </div>
        </div>
    </div>


    {{-- travel approval flow  --}}

    <div class="col-md-6 mb-2">
        <legend class="text-uppercase font-size-sm font-weight-bold">Domestic Approval</legend>
        <div class="form-group row" id="first_approval_create">
            <label class="col-form-label col-lg-4">First Approval :</span></label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {{-- {!! Form::select('first_approval_user_id',$userList, $value = null,
                    ['id'=>'first_approval_user_id','placeholder'=>'Select First Approval','class'=>'form-control
                    user-filter select-search', $isEmployee ? 'disabled' : '']) !!} --}}
                    {{-- @dd($userList) --}}
                    {!! Form::select('domestic_first_approval_user_id', $userList, $value = null, [
                        'id' => 'domestic_first_approval_user_id',
                        'placeholder' => 'Select First Approval',
                        'class' => 'form-control user-filter select-search',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row" id="domestic_second_approval_create">
            <label class="col-form-label col-lg-4">Second Approval :</span></label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">

                    {!! Form::select('domestic_second_approval_user_id', $userList, $value = null, [
                        'id' => 'domestic_second_approval_user_id',
                        'placeholder' => 'Select Second Approval',
                        'class' => 'form-control user-filter select-search',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="form-group row" id="domestic_last_approval_create">
            <label class="col-form-label col-lg-4">Last Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {{-- {!! Form::select('last_approval_user_id',$userList, $value = null,
                    ['id'=>'last_approval_user_id','placeholder'=>'Select Last Approval','class'=>'form-control
                    user-filter select-search', $isEmployee ? 'disabled' : '']) !!} --}}

                    {!! Form::select('domestic_last_approval_user_id', $userList, $value = null, [
                        'id' => 'domestic_last_approval_user_id',
                        'placeholder' => 'Select Last Approval',
                        'class' => 'form-control user-filter select-search',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>



    <div class="col-md-6 mb-2">
        <legend class="text-uppercase font-size-sm font-weight-bold">International Approval</legend>
        @php
            $attendanceFlow = null;
            if ($is_edit) {
                $attendanceFlow = $employees->employeeAttendanceApprovalFlow ?? null;
            }
        @endphp
        {{-- First Approval --}}
        {{-- <div class="form-group row" id="first_approval_create">
            <label class="col-form-label col-lg-4">First Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select(
                    'attendance_first_approval_user_id',
                    $userList,
                    $is_edit ? optional($attendanceFlow)->first_approval_user_id : null,
                    [
                    'id' => 'attendance_first_approval_user_id',
                    'placeholder' => 'Select First Approval',
                    'class' => 'form-control user-filter select-search',
                    $isEmployee ? 'disabled' : '',
                    ]
                    ) !!}
                </div>
            </div>
        </div> --}}

        {{-- Second Approval --}}
        {{-- <div class="form-group row" id="second_approval_create">
            <label class="col-form-label col-lg-4">Second Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select(
                    'attendance_second_approval_user_id',
                    $userList,
                    $is_edit ? optional($attendanceFlow)->second_approval_user_id : null,
                    [
                    'id' => 'attendance_second_approval_user_id',
                    'placeholder' => 'Select Second Approval',
                    'class' => 'form-control user-filter select-search',
                    $isEmployee ? 'disabled' : '',
                    ]
                    ) !!}
                </div>
            </div>
        </div> --}}

        {{-- Third Approval --}}
        {{-- <div class="form-group row" id="third_approval_create">
            <label class="col-form-label col-lg-4">Third Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select(
                    'attendance_third_approval_user_id',
                    $userList,
                    $is_edit ? optional($attendanceFlow)->third_approval_user_id : null,
                    [
                    'id' => 'attendance_third_approval_user_id',
                    'placeholder' => 'Select Third Approval',
                    'class' => 'form-control user-filter select-search',
                    $isEmployee ? 'disabled' : '',
                    ]
                    ) !!}
                </div>
            </div>
        </div> --}}

        {{-- Last Approval --}}
        {{-- <div class="form-group row" id="last_approval_create">
            <label class="col-form-label col-lg-4">Last Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select(
                    'attendance_last_approval_user_id',
                    $userList,
                    $is_edit ? optional($attendanceFlow)->last_approval_user_id : null,
                    [
                    'id' => 'attendance_last_approval_user_id',
                    'placeholder' => 'Select Last Approval',
                    'class' => 'form-control user-filter select-search',
                    $isEmployee ? 'disabled' : '',
                    ]
                    ) !!}
                </div>
            </div>
        </div> --}}
    </div>

    <div class="col-md-6 mb-2">
        <legend class="text-uppercase font-size-sm font-weight-bold">Claim & Request Approval</legend>
        <div class="form-group row" id="first_approval_create">
            <label class="col-form-label col-lg-4">First Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('first_claim_approval_user_id', $userList, $value = null, [
                        'placeholder' => 'Select First Approval',
                        'class' => 'form-control user-filter select-search',
                        'id' => 'claimFirstApproval',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row" id="last_approval_create">
            <label class="col-form-label col-lg-4">Last Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('last_claim_approval_user_id', $userList, $value = null, [
                        'placeholder' => 'Select Last Approval',
                        'class' => 'form-control user-filter select-search',
                        'id' => 'claimLastApproval',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="text-warning"><b>Note: </b><i>If there is only one approval then you should choose last
            approval.</i>
    </div> --}}

    <div class="col-md-6 mb-2">
        <legend class="text-uppercase font-size-sm font-weight-bold">Offboard Approval</legend>
        <div class="form-group row" id="first_approval_create">
            <label class="col-form-label col-lg-4">First Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('offboard_first_approval', $userList, $value = null, [
                        'placeholder' => 'Select First Approval',
                        'class' => 'form-control user-filter select-search',
                        'id' => 'offboardFirstApproval',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row" id="last_approval_create">
            <label class="col-form-label col-lg-4">Last Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('offboard_last_approval', $userList, $value = null, [
                        'placeholder' => 'Select Last Approval',
                        'class' => 'form-control user-filter select-search',
                        'id' => 'offboardLastApproval',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-2">
        <legend class="text-uppercase font-size-sm font-weight-bold">Appraisal Approval</legend>

        <div class="form-group row" id="appraisal_first_approval_create">
            <label class="col-form-label col-lg-4">First Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('appraisal_first_approval', $userList, $value = null, [
                        'placeholder' => 'Select First Approval',
                        'class' => 'form-control user-filter select-search',
                        'id' => 'appraisalFirstApproval',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row" id="appraisal_last_approval_create">
            <label class="col-form-label col-lg-4">Last Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('appraisal_last_approval', $userList, $value = null, [
                        'placeholder' => 'Select Last Approval',
                        'class' => 'form-control user-filter select-search',
                        'id' => 'appraisalLastApproval',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-2">
        <legend class="text-uppercase font-size-sm font-weight-bold">Advance Approval</legend>
        <div class="form-group row" id="first_approval_create">
            <label class="col-form-label col-lg-4">First Approval : </label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('advance_first_approval', $userList, $value = null, [
                        'placeholder' => 'Select First Approval',
                        'class' => 'form-control user-filter select-search',
                        'id' => 'advanceFirstApproval',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row" id="last_approval_create">
            <label class="col-form-label col-lg-4">Last Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('advance_last_approval', $userList, $value = null, [
                        'placeholder' => 'Select Last Approval',
                        'class' => 'form-control user-filter select-search',
                        'id' => 'advanceLastApproval',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-2">
        <legend class="text-uppercase font-size-sm font-weight-bold">Travel Request Approval</legend>
        <div class="form-group row" id="first_approval_create">
            <label class="col-form-label col-lg-4">First Approval :</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('business_trip_first_approval', $userList, $value = null, [
                        'placeholder' => 'Select First Approval',
                        'class' => 'form-control user-filter select-search',
                        'id' => 'businessTripFirstApproval',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row" id="last_approval_create">
            <label class="col-form-label col-lg-4">Last Approval : </label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('business_trip_last_approval', $userList, $value = null, [
                        'placeholder' => 'Select Last Approval',
                        'class' => 'form-control user-filter select-search',
                        'id' => 'businessTripLastApproval',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
</div>
{{-- {!! Form::hidden('userDetail', auth()->user(), ['id' => 'userDetail']) !!} --}}

@push('custom_script')
    <script>
        $(function() {

            // is_edit = "{{ $is_edit }}";

            // if (is_edit) {
            //     user_type = "{{ auth()->user()->user_type }}";
            //     if (user_type == 'division_hr') {
            //         $('.approvalSection').css({
            //             "pointer-events": "none",
            //             "opacity": 1
            //         });
            //     }
            // } else {
            filterUserListExceptEmployeeRoleByOrganization()
            // }


            $('.organization-filter3').on('change', function() {
                filterUserListExceptEmployeeRoleByOrganization();
            });


            function filterUserListExceptEmployeeRoleByOrganization() {
                var organizationId = $('.organization-filter3').val();
                // var userId = $('.user-filter').val();

                var leaveFirstApproval = $('#leaveFirstApproval').val();
                var leaveSecondApproval = $('#leaveSecondApproval').val();
                var leaveThirdApproval = $('#leaveThirdApproval').val();
                var leaveLastApproval = $('#leaveLastApproval').val();

                var claimFirstApproval = $('#claimFirstApproval').val();
                var claimLastApproval = $('#claimLastApproval').val();

                var offboardFirstApproval = $('#offboardFirstApproval').val();
                var offboardLastApproval = $('#offboardLastApproval').val();

                var appraisalFirstApproval = $('#appraisalFirstApproval').val();
                var appraisalLastApproval = $('#appraisalLastApproval').val();

                var advanceFirstApproval = $('#advanceFirstApproval').val();
                var advanceLastApproval = $('#advanceLastApproval').val();

                var businessTripFirstApproval = $('#businessTripFirstApproval').val();
                var businessTripLastApproval = $('#businessTripLastApproval').val();

                var attendanceFirstApprovalUserId = $('#attendance_first_approval_user_id').val();
                var attendanceSecondApprovalUserId = $('#attendance_second_approval_user_id').val();
                var attendanceThirdApprovalUserId = $('#attendance_third_approval_user_id').val();
                var attendanceLastApprovalUserId = $('#attendance_last_approval_user_id').val();



                $.ajax({
                    type: 'GET',
                    url: '/admin/organization/get-users-except-employee-role',
                    data: {
                        organization_id: organizationId
                    },
                    success: function(data) {
                        var list = JSON.parse(data);
                        var options = '';

                        options += "<option value=''>Select User</option>";
                        $.each(list, function(id, value) {
                            options += "<option value='" + id + "'>" + value + "</option>";
                        });

                        $('.user-filter').html(options);
                        $('.user-filter').select2();

                        // if(userId) {
                        //     $('.user-filter').val(userId).select2();
                        // }

                        if (leaveFirstApproval) {
                            $('#leaveFirstApproval').val(leaveFirstApproval).select2();
                        }
                        if (leaveSecondApproval) {
                            $('#leaveSecondApproval').val(leaveSecondApproval).select2();
                        }
                        if (leaveThirdApproval) {
                            $('#leaveThirdApproval').val(leaveThirdApproval).select2();
                        }
                        if (leaveLastApproval) {
                            $('#leaveLastApproval').val(leaveLastApproval).select2();
                        }
                        if (claimFirstApproval) {
                            $('#claimFirstApproval').val(claimFirstApproval).select2();
                        }
                        if (claimLastApproval) {
                            $('#claimLastApproval').val(claimLastApproval).select2();
                        }
                        if (offboardFirstApproval) {
                            $('#offboardFirstApproval').val(offboardFirstApproval).select2();
                        }
                        if (offboardLastApproval) {
                            $('#offboardLastApproval').val(offboardLastApproval).select2();
                        }
                        if (appraisalFirstApproval) {
                            $('#appraisalFirstApproval').val(appraisalFirstApproval).select2();
                        }
                        if (appraisalLastApproval) {
                            $('#appraisalLastApproval').val(appraisalLastApproval).select2();
                        }

                        if (advanceFirstApproval) {
                            $('#advanceFirstApproval').val(advanceFirstApproval).select2();
                        }
                        if (advanceLastApproval) {
                            $('#advanceLastApproval').val(advanceLastApproval).select2();
                        }

                        if (businessTripFirstApproval) {
                            $('#businessTripFirstApproval').val(businessTripFirstApproval).select2();
                        }
                        if (businessTripLastApproval) {
                            $('#businessTripLastApproval').val(businessTripLastApproval).select2();
                        }

                        if (attendanceFirstApprovalUserId) {
                            $('#attendance_first_approval_user_id').val(attendanceFirstApprovalUserId)
                                .select2();
                        }

                        if (attendanceSecondApprovalUserId) {
                            $('#attendance_second_approval_user_id').val(attendanceSecondApprovalUserId)
                                .trigger('change.select2');
                        }

                        if (attendanceThirdApprovalUserId) {
                            $('#attendance_third_approval_user_id').val(attendanceThirdApprovalUserId)
                                .trigger('change.select2');
                        }

                        if (attendanceLastApprovalUserId) {
                            $('#attendance_last_approval_user_id').val(attendanceLastApprovalUserId)
                                .trigger('change.select2');
                        }


                    }
                });
            }

        });
    </script>
    <script>
        $(document).ready(function() {
            let syncing = false;

            $('#leaveFirstApproval').on('change', function() {
                if (syncing) return;
                syncing = true;
                $('#attendance_first_approval_user_id').val($(this).val()).trigger('change');
                syncing = false;
            });

            $('#attendance_first_approval_user_id').on('change', function() {
                if (syncing) return;
                syncing = true;
                $('#leaveFirstApproval').val($(this).val()).trigger('change');
                syncing = false;
            });

            $('#leaveLastApproval').on('change', function() {
                if (syncing) return;
                syncing = true;
                $('#attendance_last_approval_user_id').val($(this).val()).trigger('change');
                syncing = false;
            });

            $('#attendance_last_approval_user_id').on('change', function() {
                if (syncing) return;
                syncing = true;
                $('#leaveLastApproval').val($(this).val()).trigger('change');
                syncing = false;
            });

            $('.select-search').select2();
        });
    </script>


    <script>
        // $(document).ready(function () {
        //     $('#attendance_first_approval_user_id').val("{{ optional($attendanceFlow)->first_approval_user_id }}").trigger('change');
        //     $('#attendance_second_approval_user_id').val("{{ optional($attendanceFlow)->second_approval_user_id }}").trigger('change');
        //     $('#attendance_third_approval_user_id').val("{{ optional($attendanceFlow)->third_approval_user_id }}").trigger('change');
        //     $('#attendance_last_approval_user_id').val("{{ optional($attendanceFlow)->last_approval_user_id }}").trigger('change');
        // });
    </script>
@endpush
