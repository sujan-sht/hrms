    {!! Form::hidden('employee_id', $employee->id, []) !!}

    <div class="form-group row">
        <div class="col-md-12">
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Date : <span class="text-danger">*</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        @php
                            if (setting('calendar_type') == 'BS') {
                                $classData = 'form-control nepali-calendar';
                            } else {
                                $classData = 'form-control daterange-single';
                            }
                        @endphp
                        {!! Form::text('date', null, ['placeholder' => 'Choose Date', 'class' => $classData, 'id' => 'date']) !!}
                    </div>
                    <div id="date-error-message" style="display:none">
                        <span class="text-danger">Select Date</span>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-lg-3">Type :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('type_id', $typeList, $value = null, [
                            'placeholder' => 'Select Type',
                            'class' => 'form-control select-search type',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row organization d-none">
                <label class="col-form-label col-lg-3">Organization :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('organization_id', $organizationList, $value = $employee->organization_id, [
                            'placeholder' => 'Select Organization',
                            'class' => 'form-control select-search',
                            'id' => 'organizationId',
                        ]) !!}
                    </div>
                    <div id="organization-error-message" style="display:none">
                        <span class="text-danger">Select Organization</span>
                    </div>
                </div>
            </div>
            <div class="form-group row branch d-none">
                <label class="col-form-label col-lg-3">Unit :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('branch_id', $filteredBranchList, $value = $employee->branch_id, [
                            'placeholder' => 'Select Unit',
                            'class' => 'form-control select-search',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row department d-none">
                <label class="col-form-label col-lg-3">Sub-Function :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('department_id', $departmentList, $value = $employee->department_id, [
                            // 'id' => 'department_id',
                            'placeholder' => 'Select Sub-Function',
                            'class' => 'form-control select-search',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row level d-none">
                <label class="col-form-label col-lg-3">Grade :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('level_id', $levelList, $value = $employee->level_id, [
                            // 'id' => 'level_id',
                            'placeholder' => 'Select Grade',
                            'class' => 'form-control select-search',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row designation d-none">
                <label class="col-form-label col-lg-3">Designation :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('designation_id', $designationList, $value = $employee->designation_id, [
                            // 'id' => 'designation_id',
                            'placeholder' => 'Select Designation',
                            'class' => 'form-control select-search',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row jobTitle d-none">
                <label class="col-form-label col-lg-3">Functional Title :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-users2"></i></span>
                        </span>
                        {!! Form::text('job_title', $value = $employee->job_title, [
                            // 'id' => 'job_title',
                            'placeholder' => 'Enter Functional Title',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row probationStatus d-none">
                <label class="col-form-label col-lg-3">Probation Status :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select(
                            'probation_status',
                            $probationStatusList,
                            $value = optional($employee->payrollRelatedDetailModel)->probation_status,
                            [
                                'placeholder' => 'Select Probation Status',
                                'class' => 'form-control select-search',
                            ],
                        ) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row payrollChange d-none">
                <label class="col-form-label col-lg-3">Payroll Change :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select(
                            'payroll_change',
                            $payrollChangeList,
                            $value = optional($employee->payrollRelatedDetailModel)->payroll_change,
                            [
                                'placeholder' => 'Select Option',
                                'class' => 'form-control select-search',
                            ],
                        ) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.type').on('change', function() {
                var type = $(this).val()
                if (type == 1) {
                    $('.organization').removeClass('d-none')
                    $('.branch').addClass('d-none')
                    $('.department').addClass('d-none')
                    $('.level').addClass('d-none')
                    $('.designation').addClass('d-none')
                    $('.jobTitle').addClass('d-none')
                    $('.probationStatus').addClass('d-none')
                    $('.payrollChange').addClass('d-none')
                } else if (type == 2) {
                    $('.branch').removeClass('d-none')
                    $('.organization').addClass('d-none')
                    $('.department').addClass('d-none')
                    $('.level').addClass('d-none')
                    $('.designation').addClass('d-none')
                    $('.jobTitle').addClass('d-none')
                    $('.probationStatus').addClass('d-none')
                    $('.payrollChange').addClass('d-none')
                } else if (type == 3) {
                    $('.department').removeClass('d-none')
                    $('.organization').addClass('d-none')
                    $('.branch').addClass('d-none')
                    $('.level').addClass('d-none')
                    $('.designation').addClass('d-none')
                    $('.jobTitle').addClass('d-none')
                    $('.probationStatus').addClass('d-none')
                    $('.payrollChange').addClass('d-none')
                } else if (type == 4) {
                    $('.level').removeClass('d-none')
                    $('.organization').addClass('d-none')
                    $('.branch').addClass('d-none')
                    $('.department').addClass('d-none')
                    $('.designation').addClass('d-none')
                    $('.jobTitle').addClass('d-none')
                    $('.probationStatus').addClass('d-none')
                    $('.payrollChange').addClass('d-none')
                } else if (type == 5) {
                    $('.designation').removeClass('d-none')
                    $('.organization').addClass('d-none')
                    $('.branch').addClass('d-none')
                    $('.department').addClass('d-none')
                    $('.level').addClass('d-none')
                    $('.jobTitle').addClass('d-none')
                    $('.probationStatus').addClass('d-none')
                    $('.payrollChange').addClass('d-none')
                } else if (type == 6) {
                    $('.jobTitle').removeClass('d-none')
                    $('.organization').addClass('d-none')
                    $('.branch').addClass('d-none')
                    $('.department').addClass('d-none')
                    $('.level').addClass('d-none')
                    $('.designation').addClass('d-none')
                    $('.probationStatus').addClass('d-none')
                    $('.payrollChange').addClass('d-none')
                } else if (type == 7) {
                    $('.probationStatus').removeClass('d-none')
                    $('.organization').addClass('d-none')
                    $('.branch').addClass('d-none')
                    $('.department').addClass('d-none')
                    $('.level').addClass('d-none')
                    $('.designation').addClass('d-none')
                    $('.jobTitle').addClass('d-none')
                    $('.payrollChange').addClass('d-none')
                } else if (type == 8) {
                    $('.payrollChange').removeClass('d-none')
                    $('.organization').addClass('d-none')
                    $('.branch').addClass('d-none')
                    $('.department').addClass('d-none')
                    $('.level').addClass('d-none')
                    $('.designation').addClass('d-none')
                    $('.jobTitle').addClass('d-none')
                    $('.probationStatus').addClass('d-none')
                } else {
                    $('.payrollChange').addClass('d-none')
                    $('.organization').addClass('d-none')
                    $('.branch').addClass('d-none')
                    $('.department').addClass('d-none')
                    $('.level').addClass('d-none')
                    $('.designation').addClass('d-none')
                    $('.jobTitle').addClass('d-none')
                    $('.probationStatus').addClass('d-none')
                }
            })
        })
    </script>
