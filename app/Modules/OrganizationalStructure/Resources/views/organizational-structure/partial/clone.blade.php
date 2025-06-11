<div class="row clone-div mb-2">
    <label class="col-form-label col-lg-2">
        Employee: <span class="text-danger">*</span>
    </label>

    <div class="col-lg-8">
        <div class="row">
            <div class="col-lg-5 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('structure_details[' . $count . '][employee_id]',
                        $employeeList,
                        isset($orgStructureDetail['employee_id']) ? $orgStructureDetail['employee_id'] : null,
                        ['placeholder' => 'Select Employee', 'class' => 'form-control empList select-search', 'required'],
                    ) !!}
                </div>
            </div>
            <label class="col-form-label col-lg-2">Parent Employee: <span class="text-danger">*</span>
            </label>
            <div class="col-lg-5 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('structure_details[' . $count . '][parent_employee_id]',
                        $allEmployeeList,
                        isset($orgStructureDetail['parent_employee_id']) ? $orgStructureDetail['parent_employee_id'] : null,
                        ['placeholder' => 'Select Employee', 'class' => 'form-control parentEmpList select-search', 'required'],
                    ) !!}
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