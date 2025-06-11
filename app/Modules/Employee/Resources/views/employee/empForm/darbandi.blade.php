<div class="form-group row">
    <div class="col-md-12">
        <legend class="text-uppercase font-size-sm font-weight-bold">Manpower Detail</legend>
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Unit :<span class="text-danger"> *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('branch_id', $branchList, $value = null, [
                        'placeholder' => 'Select Unit',
                        'class' => 'form-control select-search branch-filter',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Function :<span class="text-danger"> *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('function_id', $functionList, @$employees->function_id, [
                        'id' => 'function_id',
                        'placeholder' => 'Select Function',
                        'class' => 'form-control select-search',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Sub-Function :<span class="text-danger"> *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('department_id', $department, $value = null, [
                        'id' => 'department_id',
                        'placeholder' => 'Select Sub-Function',
                        'class' => 'form-control select-search department-filter',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Designation :<span class="text-danger"> *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('designation_id', $designation, $value = null, [
                        'id' => 'designation_id',
                        'placeholder' => 'Select Designation',
                        'class' => 'form-control select-search designation-filter',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-3">Line Manager :<span class="text-danger"> </span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('manager_id', $employeeList, null, [
                        'id' => 'manager_id',
                        'placeholder' => 'Select Manager',
                        'class' => 'form-control select-search designation-filter',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
        {{-- @dd($levelList) --}}
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Grade :<span class="text-danger"> *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('level_id', $levelList, $value = null, [
                        'id' => 'level_id',
                        'placeholder' => 'Select Grade',
                        'class' => 'form-control select-search',
                        $isEmployee ? 'disabled' : '',
                    ]) !!}
                </div>
            </div>
        </div>
        {{-- <div class="form-group row">
            <label class="col-form-label col-lg-3">Unit :</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group" id="unit_update">
                    {!! Form::select('unit_id_value', [], $value = null, [
                        'id' => 'unit_id_value',
                        'placeholder' => 'Select Unit',
                        'class' => 'form-control select-search unit-filter',
                        $isEmployee ? 'disabled' : '',
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
                    {!! Form::text('job_title', $value = null, [
                        'id' => 'job_title',
                        'placeholder' => 'Enter Functional Title',
                        'class' => 'form-control',
                    ]) !!}
                </div>
            </div>
        </div> --}}
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#function_id').on('change', function() {
            let functionId = $(this).val();
            let departmentSelect = $('#department_id');

            if (!functionId) {
                departmentSelect.empty().append('<option value="">Select Sub-Function</option>');
                return;
            }

            $.ajax({
                url: '{{ route('getSubFunction') }}',
                type: 'GET',
                data: {
                    function_id: functionId
                },
                success: function(response) {
                    departmentSelect.empty().append(
                        '<option value="">Select Sub-Function</option>');

                    $.each(response, function(key, value) {
                        departmentSelect.append('<option value="' + key + '">' +
                            value + '</option>');
                    });
                },
                error: function() {
                    alert('Error fetching departments.');
                }
            });
        });
    });
</script>
