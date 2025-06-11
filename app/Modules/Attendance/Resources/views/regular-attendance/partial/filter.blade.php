<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form>
            <div class="row">
                @if (Auth::user()->user_type == 'super_admin' || Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'hr')
                    <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Select Organization:</label>
                            <div class="input-group">
                                @php $selected_org_id = isset(request()->org_id) ? request()->org_id : null ; @endphp
                                {!! Form::select('org_id', $organizationList, $selected_org_id, [
                                    'class' => 'form-control select2 organization-filter organization-filter2',
                                    'placeholder' => 'Select Organization',
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
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Select Sub-Function:</label>
                            <div class="input-group">
                                @php $selected_department_id= isset(request()->department_id) ? request()->department_id: null ; @endphp
                                {!! Form::select('department_id', $departmentList, $selected_department_id, [
                                    'id' => 'departmentId',
                                    'placeholder' => 'Select Sub-Function',
                                    'class' => 'form-control select2 department-filter',
                                ]) !!}

                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Select Employee:</label>
                            <div class="input-group">
                                @php $selected_emp_id = isset(request()->emp_id) ? request()->emp_id : null ; @endphp
                                {!! Form::select('emp_id', $employees, $selected_emp_id, [
                                    'class' => 'form-control select2 employee-filter',
                                    'placeholder' => 'Select Employee',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Select Status:</label>
                            <div class="input-group">
                                @php
                                    unset($statusList['H']);
                                    unset($statusList['D']);
                                    // unset($statusList['P*']);

                                $selected_status = isset(request()->status) ? request()->status : null; @endphp
                                {!! Form::select('status', $statusList, $selected_status, [
                                    'class' => 'form-control select2',
                                    'placeholder' => 'Select Status',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Job Type</label>
                            <div class="input-group">
                                {!! Form::select('job_type', $jobTypeList, $value = request('job_type') ?: null, [
                                    'placeholder' => 'Select Job Type',
                                    'class' => 'form-control select2 select-search'
                                ]) !!}
                            </div>
                        </div>
                    </div> --}}
                @endif
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Calendar Type:</label>
                        <div class="input-group">
                            @php $calendarType = isset(request()->calendar_type) ? request()->calendar_type : 'eng' ; @endphp
                            {!! Form::select('calendar_type', ['eng' => 'English', 'nep' => 'Nepali'], $calendarType, [
                                'class' => 'form-control calendartype select2',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-3 engDate">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">

                        <label for="example-email" class="form-label">Date:</label>
                        @php
                            if (isset($_GET['date_range'])) {
                                $dateRangeValue = $_GET['date_range'];
                            } else {
                                $dateRangeValue = null;
                            }
                        @endphp
                        {!! Form::text('date_range', $value = $dateRangeValue, [
                            'placeholder' => 'e.g : YYYY-MM-DD',
                            'class' => 'form-control daterange-single',
                            'autocomplete' => 'off',
                            // 'id' => 'maxDateId',
                        ]) !!}
                    </div>
                </div>


                <div class="col-md-3 nepDate" style="display:none">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">

                        <label for="example-email" class="form-label">Date:</label>
                        @php
                            if (isset($_GET['nep_date_range'])) {
                                $dateRangeValue = $_GET['nep_date_range'];
                            } else {
                                $dateRangeValue = null;
                            }
                        @endphp
                        {!! Form::text('nep_date_range', $value = $dateRangeValue, [
                            'placeholder' => 'e.g : YYYY-MM-DD',
                            'class' => 'form-control nepali-calendar',
                            'autocomplete' => 'off',
                        ]) !!}
                    </div>
                </div>
                {{-- <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Type:</label>
                        <div class="input-group">
                            @php
                                $selectedType = isset(request()->type) ? request()->type : null;
                            @endphp
                            {!! Form::select('type', $typeList, $selectedType, [
                                'class' => 'form-control select2',
                                'placeholder' => 'Select Type',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Medium:</label>
                        <div class="input-group">
                            @php
                                $selectedMedium = isset(request()->medium) ? request()->medium : null;
                            @endphp
                            {!! Form::select('medium', $mediumList, $selectedMedium, [
                                'class' => 'form-control select2',
                                'placeholder' => 'Select Medium',
                            ]) !!}
                        </div>
                    </div>
                </div> --}}

            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-1" type="submit">
                    <i class="icons icon-filter3 mr-1"></i>Filter
                </button>

                <a href="{{ request()->url() . '?date_range=' . date('Y-m-d') }}" class="btn bg-secondary text-white"><i
                        class="icons icon-reset mr-1"></i>Reset</a>
            </div>
        </form>

    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
        $(document).ready(function() {
            calendarType();

            function calendarType() {
                let type = $('.calendartype').find(":selected").val();

                if (type == 'eng') {
                    $('.engDate').css('display', 'block')
                    $('.nepDate').css('display', 'none')
                    $("input[name='nep_date_range']").val();

                }
                if (type == 'nep') {
                    $('.engDate').css('display', 'none')
                    $('.nepDate').css('display', 'block')

                    $("input[name='date_range']").val();
                }
            }


            $(document).on('change', '.calendartype', function() {
                // let type = $(this).val();
                calendarType();
            });

        })

    });
</script>
<script>
    $(document).ready(function() {
        $('#function_id').select2();

        $('.select2').select2();


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

                        subfunction.select2('rebuild');

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
