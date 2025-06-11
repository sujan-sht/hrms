<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form class="attendanceFilter">
            <div class="row">
                @if (Auth::user()->user_type == 'super_admin' ||
                        Auth::user()->user_type == 'admin' ||
                        Auth::user()->user_type == 'hr' ||
                        Auth::user()->user_type == 'division_hr')
                    <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Select Organization: <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                @php $selected_org_id = isset(request()->org_id) ? request()->org_id : null ; @endphp
                                {!! Form::select('org_id', $organizationList, $selected_org_id, [
                                    'class' => 'form-control select-search organizationFilter organization-filter2',
                                    'placeholder' => 'Select Organization',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Select Unit:</label>
                            <div class="input-group">
                                @php $selected_branch_id = isset(request()->branch_id) ? request()->branch_id : null ; @endphp
                                {!! Form::select('branch_id', $branchList, $selected_branch_id, [
                                    'class' => 'form-control select2 branch-filter',
                                    'placeholder' => 'Select Unit',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Select Sub-Function:</label>
                            <div class="input-group">
                                @php $selected_department_id= isset(request()->department_id) ? request()->department_id: null ; @endphp
                                {!! Form::select('department_id', $departmentList, $selected_department_id, [
                                    'id' => 'departmentId',
                                    'placeholder' => 'Select Sub-Function',
                                    'class' => 'form-control select2',
                                ]) !!}

                            </div>
                        </div>
                    </div> --}}

                    <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Select Employee:<span class="text-danger">* (Max
                                    5)</span></label>
                            <div class="input-group">
                                @php $selected_emp_id = isset(request()->emp_id) ? request()->emp_id : null ; @endphp
                                {!! Form::select('emp_id[]', [], $selected_emp_id, [
                                    'class' => 'form-control empFilter multiselect-select-all-filtering',
                                    'multiple',
                                ]) !!}
                            </div>
                            <div class="error text-danger" id="msg"></div>
                        </div>
                    </div>
                @endif

                {{-- <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Status:</label>
                        <div class="input-group">

                            {!! Form::select('status', $statusList, request()->get('status'), [
                                'class' => 'form-control select-search',
                                'placeholder' => 'Select Status',
                                'required'
                            ]) !!}
                        </div>
                    </div>
                </div> --}}
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Type: <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            @php
                                $selectedType = isset(request()->type) ? request()->type : null;
                            @endphp
                            {!! Form::select('type', $typeList, $selectedType, [
                                'class' => 'form-control select-search',
                                'placeholder' => 'Select Type',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Medium:</label>
                        <div class="input-group">
                            @php
                                $selectedMedium = isset(request()->medium) ? request()->medium : null;
                            @endphp
                            {!! Form::select('medium', $mediumList, $selectedMedium, [
                                'class' => 'form-control select-search',
                                'placeholder' => 'Select Medium',
                                'required'
                            ]) !!}
                        </div>
                    </div>
                </div> --}}

                <div class="col-lg-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Calendar Type:</label>
                        <div class="input-group">
                            @php $calendarType = isset(request()->calendar_type) ? request()->calendar_type : null ; @endphp
                            {!! Form::select('calendar_type', ['eng' => 'English', 'nep' => 'Nepali'], $calendarType, [
                                'class' => 'form-control calendartype select-search1',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">From Date: <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            {!! Form::text('from_eng_date', $value = request('from_eng_date') ?: null, [
                                'placeholder' => 'e.g : YYYY-MM-DD',
                                'class' => 'form-control daterange-single from_eng_date',
                                'autocomplete' => 'on',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">To Date: <span class="text-danger">*</span></label>
                        <div class="input-group">
                            {!! Form::text('to_eng_date', $value = request('to_eng_date') ?: null, [
                                'placeholder' => 'e.g : YYYY-MM-DD',
                                'class' => 'form-control daterange-single to_eng_date',
                                'autocomplete' => 'on',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 d-none">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">From Date: <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            {!! Form::text('from_nep_date', $value = request('from_nep_date') ?: null, [
                                'placeholder' => 'e.g : YYYY-MM-DD',
                                'class' => 'form-control nepali-calendar from_nep_date',
                                'autocomplete' => 'on',
                            ]) !!}
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 d-none">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">To Date: <span class="text-danger">*</span></label>
                        <div class="input-group">
                            {!! Form::text('to_nep_date', $value = request('to_nep_date') ?: null, [
                                'placeholder' => 'e.g : YYYY-MM-DD',
                                'class' => 'form-control nepali-calendar to_nep_date',
                                'autocomplete' => 'on',
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
<script>
    $(document).ready(function() {
        customValidation('attendanceFilter')
        // $(".nepali-calendar").nepaliDatePicker();

        $('body').on('change', '.organizationFilter', function() {
            filterEmployeeByOrganization();
        });

        selected_employees = '{!! json_encode(request()->get('emp_id')) !!}';
        var validJsonString = selected_employees.replace(/'/g, '"');
        var numericEmpIds = JSON.parse(validJsonString);



        function filterEmployeeByOrganization() {
            var organizationId = $('.organizationFilter').val();

            $.ajax({
                type: 'GET',
                url: '/admin/organization/get-employees',
                data: {
                    organization_id: organizationId,
                },
                success: function(data) {
                    var list = JSON.parse(data);
                    var options = '';
                    $('.empFilter').attr('multiple', 'multiple');

                    // options += "<option value=''>Select Employee</option>";
                    $.each(list, function(id, value) {
                        options += "<option value='" + id + "'  >" + value + "</option>";
                    });

                    $('.empFilter').html(options);

                    $.each(numericEmpIds, function(index, empId) {
                        $('.empFilter option[value="' + empId + '"]').prop('selected',
                            true);
                    });

                    $('.empFilter').multiselect('rebuild', {
                        enableFiltering: true,
                        filterPlaceholder: 'Search...',
                        enableCaseInsensitiveFiltering: true
                    });
                }
            });
        }

        $('.organizationFilter').trigger('change');

        $('.empFilter').on('change', function() {
            // Get all selected options
            var selectedOptions = $(".empFilter option:selected");

            // Check if more than 5 are selected
            if (selectedOptions.length > 5) {
                // Display message
                $("#msg").text('Select only 5 employees');

                // Deselect the last selected option
                selectedOptions.each(function(index, option) {
                    if (index >= 5) {
                        $(option).prop("selected", false);
                    }
                });
            } else {
                // Clear the message if 5 or fewer are selected
                $("#msg").text('');
            }
        });

        // $('.attendanceFilter').on('submit', function(e) {
        //     if ($(".empFilter option:selected").length > 5) {
        //         $("#msg").text('Select only 5 employees');
        //         e.preventDefault()
        //     }
        // })

        $(document).on('change', '.calendartype', function() {
            let type = $(this).val();
            if (type == 'eng') {
                $('.from_eng_date').attr('required', true)
                $('.to_eng_date').attr('required', true)
                $('.from_eng_date').parents('.col-lg-3').removeClass('d-none')
                $('.to_eng_date').parents('.col-lg-3').removeClass('d-none')

                $('.from_nep_date').attr('required', false)
                $('.to_nep_date').attr('required', false)

                $('.from_nep_date').parents('.col-lg-3').addClass('d-none')
                $('.to_nep_date').parents('.col-lg-3').addClass('d-none')
            }
            if (type == 'nep') {
                $('.from_eng_date').attr('required', false)
                $('.to_eng_date').attr('required', false)
                $('.from_eng_date').parents('.col-lg-3').addClass('d-none')
                $('.to_eng_date').parents('.col-lg-3').addClass('d-none')

                $('.from_nep_date').attr('required', true)
                $('.to_nep_date').attr('required', true)
                $('.from_nep_date').parents('.col-lg-3').removeClass('d-none')
                $('.to_nep_date').parents('.col-lg-3').removeClass('d-none')

            }
        })

        $('.calendartype').trigger('change')
    })
</script>
