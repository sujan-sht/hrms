<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form>
            <div class="row">
                @if (Auth::user()->user_type == 'super_admin' ||
                        Auth::user()->user_type == 'admin' ||
                        Auth::user()->user_type == 'hr' ||
                        Auth::user()->user_type == 'supervisor')
                    {{-- <div class="col-md-3">
                    <div class="mb-3">
                        <label for="example-email" class="form-label">Organization</label>
                        @php
                            if (isset($_GET['organization_id'])) {
                                $orgValue = $_GET['organization_id'];
                            } else {
                                $orgValue = null;
                            }
                        @endphp
                        {!! Form::select('organization_id', $organizationList, $value = $orgValue, [
                            'placeholder' => 'Select Organization',
                            'class' => 'form-control select2',
                        ]) !!}
                    </div>
                </div> --}}
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="d-block font-weight-semibold">Select Organization:</label>
                            <div class="input-group">
                                @php $selected_org_id = isset(request()->organization_id) ? request()->organization_id : null ; @endphp
                                {!! Form::select('organization_id', $organizationList, $selected_org_id, [
                                    'class' => 'form-control select2 organization-filter organization-filter2 organization_id',
                                    'id' => 'organization',
                                    // 'placeholder' => 'Select Organization',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="form-label">Select Branch:</label>
                        @php
                            if(isset($_GET['branch_id'])) {
                                $branchValue = $_GET['branch_id'];
                            } else {
                                $branchValue = null;
                            }
                        @endphp
                        {!! Form::select('branch_id', $branchList, $value = $branchValue, ['placeholder'=>'Select Branch', 'class'=>'form-control select2 branch-filter']) !!}
                    </div>


                    {{-- <div class="col-md-3">
                    <div class="mb-3">
                        <label for="example-email" class="form-label">Employee</label>
                        @php
                            if (isset($_GET['employee_id'])) {
                                $employeeValue = $_GET['employee_id'];
                            } else {
                                $employeeValue = null;
                            }
                        @endphp
                        {!! Form::select('employee_id', $employeeList, $value = $employeeValue, [
                            'placeholder' => 'Select Employee',
                            'class' => 'form-control select2',
                        ]) !!}
                    </div>
                </div> --}}
                    <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Select Employee:</label>
                            <div class="input-group">
                                @php $selected_emp_id = isset(request()->employee_id) ? request()->employee_id : null ; @endphp
                                {!! Form::select('employee_id', $employeeList, $selected_emp_id, [
                                    'class' => 'form-control select2 employee-filter',
                                    'placeholder' => 'Select Employee','required',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-3 year" style="display: none;">
                        <div class="mb-3">
                            <label for="example-email" class="form-label">Year</label>
                            @php
                                if (isset($_GET['year']) && $_GET['year']) {
                                    $year = $_GET['year'];
                                }
                                if (isset($_GET['eng_year']) && $_GET['eng_year']) {
                                    $year = $_GET['eng_year'];
                                }
                                else {
                                    $year = null;
                                }
                            @endphp
                            <div class="input-group engDiv" style="display: none;">
                                {!! Form::select('eng_year', $yearList, $value = $year, [
                                    'id' => 'engYear',
                                    'placeholder' => 'Select Year',
                                    'class' => 'form-control select2',
                                ]) !!}
                            </div>
                            <div class="input-group nepDiv" style="display: none;">
                                {!! Form::select('year', $nepaliYearList, $value = $year, [
                                    'id' => 'nepYear',
                                    'placeholder' => 'Select Year',
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-md-3 year" style="display:none;">
                        <div class="mb-3">
                            <label for="example-email" class="form-label">Year:</label>
                            @php
                                if (isset($_GET['year']) && $_GET['year']) {
                                    $year = $_GET['year'];
                                }
                                elseif(isset($_GET['eng_year']) && $_GET['eng_year']){
                                    $year = $_GET['eng_year'];
                                }
                                else {
                                    $year = null;
                                }
                            @endphp
                            <div class="input-group engDiv" style="display:none;">
                                {!! Form::select('eng_year', $englishFiscalYearList, $value = $year, [
                                    'placeholder' => 'Select Year',
                                    'id' => 'engFiscalYear',
                                    'class' => 'form-control select2',
                                    'required',
                                ]) !!}
                            </div>
                            <div class="input-group nepDiv" style="display:none;">
                                {!! Form::select('year', $fiscalYearList, $value = $year, [
                                    'placeholder' => 'Select Year',
                                    'id' => 'nepFiscalYear',
                                    'class' => 'form-control select2',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icons icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ request()->url() }}" class="btn bg-secondary text-white">
                    <i class="icons icon-reset mr-1"></i>Reset
                </a>
            </div>
        </form>

    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
        payrollCalendarType();
        $('#organization').on('change', function() {
            payrollCalendarType();
        });
    })
</script>

<script>
    function payrollCalendarType() {
        var organizationId = $('.organization_id').val();
        $.ajax({
            type: 'GET',
            url: '/admin/payroll-setting/get-calendar-type',
            data: {
                organization_id: organizationId
            },
            success: function(data) {
                var list = JSON.parse(data);
                if (list.calendar_type == 'nep') {
                    $('.engDiv').hide();
                    $('.nepDiv').show();
                    $('#engFiscalYear').removeAttr('required');
                    $('#nepFiscalYear').attr('required',true);
                    $('.calendar_type').show();
                    $('.year').show();
                    $('.month').show();
                    $('#nepYear').removeAttr("disabled");
                    $('#nepMonth').removeAttr("disabled");
                    $('#calendarType').removeAttr("disabled");
                    $('#engYear').val('');
                } else {
                    $('.calendar_type').show();
                    $('.year').show();
                    $('.month').show();
                    $('.engDiv').show();
                    $('.nepDiv').hide();
                    $('#engFiscalYear').attr('required',true);
                    $('#nepFiscalYear').removeAttr('required');
                    $('#nepYear').val('');
                }

            }
        });
    }
</script>
