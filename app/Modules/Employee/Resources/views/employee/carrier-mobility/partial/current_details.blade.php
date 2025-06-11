    <div class="form-group row">
        <div class="col-md-12">
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Organization :</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('organization_id', $organizationList, $value = $employee->organization_id, [
                            'class' => 'form-control',
                            'disabled',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Unit :</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('branch_id', $branchList, $value = $employee->branch_id, [
                            'class' => 'form-control',
                            'disabled',
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-lg-3">Sub-Function :</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        @php
                            $functionList = App\Modules\Setting\Entities\Functional::pluck('title', 'id');
                        @endphp
                        {!! Form::select('ofunction_id', $functionList, $value = $employee->ofunction_id, [
                            // 'id' => 'department_id',
                            'class' => 'form-control',
                            'disabled',
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-lg-3">Sub-Function :</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('department_id', $departmentList, $value = $employee->department_id, [
                            // 'id' => 'department_id',
                            'class' => 'form-control',
                            'disabled',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Grade :</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('level_id', $levelList, $value = $employee->level_id, [
                            // 'id' => 'level_id',
                            'class' => 'form-control',
                            'disabled',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Designation :</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('designation_id', $designationList, $value = $employee->designation_id, [
                            // 'id' => 'designation_id',
                            'class' => 'form-control',
                            'disabled',
                        ]) !!}
                    </div>
                </div>
            </div>
            {{-- <div class="form-group row">
                <label class="col-form-label col-lg-3">Functional Title :</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-users2"></i></span>
                        </span>
                        {!! Form::text('job_title', $value = $employee->job_title, [
                            // 'id' => 'job_title',
                            'class' => 'form-control',
                            'readonly',
                        ]) !!}
                    </div>
                </div>
            </div> --}}


            @php
                $statuss =
                    @$employee->payrollRelatedDetailModel->contract_type == '11'
                        ? 'Contract'
                        : (@$employee->payrollRelatedDetailModel->contract_type == '10'
                            ? 'Probation'
                            : null);
            @endphp
            <div class="form-group row">
                <label class="col-form-label col-lg-3"> Status :</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::text('sdf', $statuss, ['class' => 'form-control', 'disabled' => true, 'readonly']) !!}
                    </div>
                </div>
            </div>
            @if (@$employee->payrollRelatedDetailModel->contract_type != '12')
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Start Date :</label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text(
                                'probation_status',
                                @$employee->payrollRelatedDetailModel->probation_status == 11
                                    ? date_converter()->eng_to_nep_convert(@$employee->payrollRelatedDetailModel->probation_start_date)
                                    : date_converter()->eng_to_nep_convert(@$employee->payrollRelatedDetailModel->contract_start_date),
                                [
                                    'class' => 'form-control',
                                    'disabled',
                                    'id' => 'old_start_date',
                                    'readonly,',
                                ],
                            ) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">End Date :</label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text(
                                'probation_status',
                                @$employee->payrollRelatedDetailModel->probation_status == 11
                                    ? date_converter()->eng_to_nep_convert(@$employee->payrollRelatedDetailModel->probation_end_date)
                                    : date_converter()->eng_to_nep_convert(@$employee->payrollRelatedDetailModel->contract_end_date),
                                [
                                    'class' => 'form-control',
                                    'disabled',
                                    'id' => 'old_end-date',
                                    'readonly,',
                                ],
                            ) !!}
                        </div>
                    </div>
                </div>
            @elseif(@$employee->payrollRelatedDetailModel->contract_type == '12')
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Employment Status :</label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('asdf', 'Permanent', [
                                'class' => 'form-control',
                                'readonly',
                                'disabled',
                            ]) !!}
                        </div>
                    </div>
                </div>
            @endif

            {{-- <div class="form-group row">
                <label class="col-form-label col-lg-3">Probation Status :</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select(
                            'probation_status',
                            $probationStatusList,
                            $value = optional($employee->payrollRelatedDetailModel)->probation_status,
                            [
                                'class' => 'form-control',
                                'placeholder' => 'Select Option',
                                'disabled',
                            ],
                        ) !!}
                    </div>
                </div>
            </div> --}}
            {{-- <div class="form-group row">
                <label class="col-form-label col-lg-3">Payroll Change :</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('payroll_change', $payrollChangeList, $value = optional($employee->payrollRelatedDetailModel)->payroll_change, [
                            'class' => 'form-control',
                            'placeholder' => 'Select Option',
                            'disabled'
                        ]) !!}
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
