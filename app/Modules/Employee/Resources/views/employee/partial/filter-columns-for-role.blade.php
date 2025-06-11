<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Organization</label>
            @php
                if (isset($_GET['organization_id'])) {
                    $organizationValue = $_GET['organization_id'];
                } else {
                    $organizationValue = null;
                }
            @endphp
            {!! Form::select('organization_id[]', $organizationList, $value = $organizationValue, [
                //  'placeholder' => 'Select Organization',
                'class' => 'form-control multiselect-filtering',
                'multiple',
            ]) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Unit</label>
            @php
                if (isset($_GET['branch_id'])) {
                    $branchValue = $_GET['branch_id'];
                } else {
                    $branchValue = null;
                }
            @endphp
            {!! Form::select('branch_id[]', $branchList, $value = $branchValue, [
                //  'placeholder' => 'Select Unit',
                'class' => 'form-control multiselect-filtering',
                'multiple',
            ]) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label for="example-email" class="form-label">Function</label>
            @php
                $functionList = App\Modules\Setting\Entities\Functional::pluck('title', 'id');
            @endphp
            {!! Form::select('function_id', $functionList, request()->function_id, [
                'class' => 'form-control select2',
                'placeholder' => 'Select Function',
                'id' => 'function_id',
            ]) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label for="example-email" class="form-label">Sub-Function</label>
            @php
                if (isset($_GET['department_id'])) {
                    $employeeValue = $_GET['department_id'];
                } else {
                    $employeeValue = null;
                }
            @endphp
            {!! Form::select('department_id[]', $departmentList, $value = $employeeValue, [
                'class' => 'form-control multiselect-filtering',
                'id' => 'department_id',
                'multiple',
                //  'placeholder' => 'Select Sub-Function',
            ]) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label for="example-email" class="form-label">Designation</label>
            @php
                if (isset($_GET['designation_id'])) {
                    $employeeValue = $_GET['designation_id'];
                } else {
                    $employeeValue = null;
                }
            @endphp
            {!! Form::select('designation_id[]', $designationList, $value = $employeeValue, [
                //  'placeholder' => 'Select Designation',
                'class' => 'form-control multiselect-filtering',
                'multiple',
            ]) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label for="example-email" class="form-label">Grade</label>
            @php
                if (isset($_GET['level_id'])) {
                    $levelValue = $_GET['level_id'];
                } else {
                    $levelValue = null;
                }
            @endphp
            {!! Form::select('level_id[]', $levelList, $value = $levelValue, [
                //  'placeholder' => 'Select Grade',
                'class' => 'form-control multiselect-filtering',
                'multiple',
            ]) !!}
        </div>
    </div>
    {{-- <div class="col-md-3">
        <div class="mb-3">
            <label for="example-email" class="form-label">Unit</label>
            @php
                if (isset($_GET['unit-filter'])) {
                    $levelValue = $_GET['unit-filter'];
                } else {
                    $levelValue = null;
                }
            @endphp
            {!! Form::select('unit_id', [], $value = $levelValue, [
                'class' => 'form-control unit-filter select2',
                'placeholder' => 'Select Unit',
                // 'class'=>'form-control select2', 'multiple' => 'multiple'
            ]) !!}
        </div>
    </div> --}}
    <div class="col-md-3">
        <div class="mb-3">
            <label for="example-email" class="form-label">Email</label>
            @php
                if (isset($_GET['email'])) {
                    $employeeValue = $_GET['email'];
                } else {
                    $employeeValue = null;
                }
            @endphp
            {!! Form::text('email', $value = $employeeValue, ['placeholder' => 'Enter Email', 'class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label for="example-email" class="form-label">Full Name</label>

            @php
                if (isset($_GET['name'])) {
                    $name = $_GET['name'];
                } else {
                    $name = null;
                }
            @endphp
            {!! Form::text('name', $value = $name, [
                'placeholder' => 'Search Employee',
                'class' => 'form-control',
                'id' => 'filterFullName',
            ]) !!}
            <ul id="search-employee-results" class="suggestion-box"></ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize Select2 for both fields
        $('#function_id').select2();

        $('#department_id').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            buttonWidth: '100%',
            nonSelectedText: 'Select Sub-Function'
        });


        $('#function_id').on('change', function() {
            let functionId = $(this).val();
            let subfunction = $('#department_id');
            console.log(subfunction);

            subfunction.html('').trigger('change'); // Clear previous

            if (functionId) {
                $.ajax({
                    url: '{{ route('getSubFunction') }}',
                    type: 'GET',
                    data: {
                        function_id: functionId
                    },
                    success: function(response) {
                        subfunction.empty(); // Remove old options
                        $.each(response, function(key, value) {
                            let option = new Option(value, key, false, false);
                            subfunction.append(option);
                        });

                        subfunction.multiselect('rebuild');

                    },
                    error: function(xhr) {
                        console.log("AJAX error:", xhr.responseText);
                        alert("Failed to load sub-functions.");
                    }
                });
            }
        });
    });
</script>
