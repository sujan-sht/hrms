<div class="row clone-div mb-2">
    <div class="col-lg-10">
        <div class="row">
            <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                <div class="row">
                    <label class="col-form-label col-lg-2">
                        Sub-Function: <span class="text-danger">*</span>
                    </label>
                    <div class="input-group col-lg-10">
                        {!! Form::select('web_atd_details[' . $count . '][department_id]', $departmentList, null, [
                            'placeholder' => 'Select Sub-Function',
                            'class' => 'form-control department select-search department-filter',
                            'required',
                        ]) !!}
                        <span class="errorType"></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 form-group-feedback">
                <div class="row">
                    <label class="col-form-label col-lg-2">
                        Employee: <span class="text-danger">*</span>
                    </label>
                    <div class="input-group col-lg-10">
                        {!! Form::select('web_atd_details[' . $count . '][employee_ids][]', $employeeList, null, [
                            'class' => 'form-control employee multiselect-select-all-filtering',
                            'multiple',
                            'required',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-2">
        @if ($count == 0)
            <a class="btn btn-success rounded-pill btn-clone">
                <i class="icon-plus-circle2 mr-1"></i>Add More
            </a>
        @else
            <a class="btn btn-danger rounded-pill btn-remove"><i class="icon-minus-circle2 mr-1"></i>Remove</a>
        @endif

    </div>
</div>
