    {!! Form::hidden('employee_id', $employee->id, []) !!}

    <div class="form-group row">
        <div class="col-md-12">
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Date : <span class="text-danger">*</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::text('date', null, ['placeholder' => 'Choose Date', 'class' => 'form-control daterange-single date']) !!}
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-lg-3">Type :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('type_id', $typeList, $value = null, [
                            'placeholder' => 'Select Type',
                            'class' => 'form-control select-search',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Organization :</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('organization_id', $organizationList, $value = $employee->organization_id, [
                            'placeholder' => 'Select Organization',
                            'class' => 'form-control select-search',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Unit :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('branch_id', $branchList, $value = $employee->branch_id, [
                            'placeholder' => 'Select Unit',
                            'class' => 'form-control select-search',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Sub-Function :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('department_id', $departmentList, $value = $employee->department_id, [
                            'id' => 'department_id',
                            'placeholder' => 'Select Sub-Function',
                            'class' => 'form-control select-search',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Grade :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('level_id', $levelList, $value = $employee->level_id, [
                            'id' => 'level_id',
                            'placeholder' => 'Select Grade',
                            'class' => 'form-control select-search',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Designation :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('designation_id', $designationList, $value = $employee->designation_id, [
                            'id' => 'designation_id',
                            'placeholder' => 'Select Designation',
                            'class' => 'form-control select-search',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Functional Title :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-users2"></i></span>
                        </span>
                        {!! Form::text('job_title', $value = $employee->job_title, [
                            'id' => 'job_title',
                            'placeholder' => 'Enter Functional Title',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
