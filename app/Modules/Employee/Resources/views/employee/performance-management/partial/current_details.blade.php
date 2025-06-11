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
                        {!! Form::select('department_id', $departmentList, $value = $employee->department_id, [
                            'id' => 'department_id',
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
                            'id' => 'level_id',
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
                            'id' => 'designation_id',
                            'class' => 'form-control',
                            'disabled',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Functional Title :</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-users2"></i></span>
                        </span>
                        {!! Form::text('job_title', $value = $employee->job_title, [
                            'id' => 'job_title',
                            'class' => 'form-control',
                            'readonly',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
