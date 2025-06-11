<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form class="filterForm">
            <div class="row">

                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Organization: <span
                                class="text-danger">*</span></label>
                        @php
                            if (isset($_GET['organization_id'])) {
                                $organizationValue = $_GET['organization_id'];
                            } else {
                                $organizationValue = null;
                            }
                        @endphp
                        {!! Form::select('organization_id', $organizationList, $value = $organizationValue, [
                            'placeholder' => 'Select Organization',
                            'class' => 'form-control select-search organization-filter2',
                            'required',
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Sub-Function:</label>
                        @php
                            if (isset($_GET['department_id'])) {
                                $departmentValue = $_GET['department_id'];
                            } else {
                                $departmentValue = null;
                            }
                        @endphp
                        {!! Form::select('department_id', $departmentList, $value = $departmentValue, [
                            'placeholder' => 'Select Sub-Function',
                            'class' => 'form-control select-search department-filter',
                        ]) !!}
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Employees:</label>
                        <div class="input-group">
                            @php $selected_emp_id = isset(request()->emp_ids) ? request()->emp_ids : null ; @endphp
                            {!! Form::select('emp_ids[]', [], $selected_emp_id, [
                                'class' => 'form-control empFilter multiselect-select-all-filtering',
                                'id' => 'emp_ids',
                            ]) !!}
                        </div>
                    </div>
                </div>

                {{-- <div class="col-md-3">
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
                            'multiple' => 'multiple',
                        ]) !!}
                    </div>
                </div> --}}


                <div class="col-md-3 engdata">

                    {{-- <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select English Month: <span
                                class="text-danger">*</span></label>

                        <div class="input-group">
                            {!! Form::select('month', date_converter()->getEngMonths(), request()->month, [
                                'class' => 'form-control month select-search month',
                                'placeholder' => 'Select English Month',
                                'required',
                            ]) !!}
                        </div>
                    </div> --}}
                </div>

                {{-- <div class="col-md-3 engdata mt-2">

                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Week Range: <span
                                class="text-danger">*</span></label>

                        <div class="input-group">
                            {!! Form::select('week_range', [], request()->week_range, [
                                'class' => 'form-control week_range select-search',
                                'placeholder' => 'Select Week Range',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                </div> --}}

                <div class="col-md-3 engdata mt-2">

                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Start Date: <span
                                class="text-danger">*</span></label>

                        <div class="input-group">
                            {!! Form::date('start_date', request()->start_date, [
                                'class' => 'form-control',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-3 engdata mt-2">

                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">End Date: <span class="text-danger">*</span></label>

                        <div class="input-group">
                            {!! Form::date('end_date', request()->end_date, [
                                'class' => 'form-control',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-1" type="submit">
                    <i class="icons icon-filter3 mr-1"></i>Filter
                </button>

                <a href="{{ request()->url() }}" class="btn bg-secondary text-white"><i
                        class="icons icon-reset mr-1"></i>Reset</a>
            </div>

        </form>

    </div>
</div>
<!-- Required CSS -->
<link href="{{ asset('admin/global/css/plugins/forms/selects/bootstrap_multiselect.css') }}" rel="stylesheet">

<!-- Required JS -->
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script>
    $(document).ready(function() {

        $('.organization-filter2').on('change', function() {
            filterEmployeeByOrgDepartment();
        });

        $('.department-filter').on('change', function() {
            filterEmployeeByOrgDepartment();
        });

        selected_employees = '{!! json_encode(request()->get('emp_ids')) !!}';
        var validJsonString = selected_employees.replace(/'/g, '"');
        var numericEmpIds = JSON.parse(validJsonString);


        function filterEmployeeByOrgDepartment() {
            var organizationId = $('.organization-filter2').val();
            var departmentId = $('.department-filter').val();

            $.ajax({
                type: 'GET',
                url: '/admin/organization/get-employees',
                data: {
                    organization_id: organizationId,
                    department_id: departmentId,
                },
                success: function(data) {
                    var list = JSON.parse(data);
                    var options = '';
                    $('.empFilter').attr('multiple', 'multiple');

                    $.each(list, function(id, value) {
                        options += "<option value='" + id + "'  >" + value + "</option>";
                    });

                    $('.empFilter').html(options);

                    $.each(numericEmpIds, function(index, empId) {
                        $('.empFilter option[value="' + empId + '"]').prop('selected',
                            true);
                    });

                    $('.empFilter').multiselect('destroy').multiselect({
                        enableFiltering: true,
                        filterPlaceholder: 'Search...',
                        enableCaseInsensitiveFiltering: true,
                        includeSelectAllOption: true,
                        selectAllText: 'Select All',
                        allSelectedText: 'All Selected',
                        numberDisplayed: 1,
                        nonSelectedText: 'Select Employees'
                    });

                }
            });
        }

        $('.organization-filter2').trigger('change');
        $('.department-filter').trigger('change');

    });
</script>
