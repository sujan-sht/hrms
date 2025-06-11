<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>

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
                                @php $selected_org_id = isset(request()->organization_id) ? request()->organization_id : null ; @endphp
                                {!! Form::select('organization_id', $organizationList, $selected_org_id, [
                                    'class' => 'form-control select2 organization-filtering',
                                    'placeholder' => 'Select Organization',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-md-3">
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
                    </div> --}}

                    {{-- <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Select Department:</label>
                            <div class="input-group">
                                @php $selected_department_id= isset(request()->department_id) ? request()->department_id: null ; @endphp
                                {!! Form::select('department_id', $departmentList, $selected_department_id, [
                                    'id' => 'departmentId',
                                    'placeholder' => 'Select Department',
                                    'class' => 'form-control select2',
                                ]) !!}

                            </div>
                        </div>
                    </div> --}}

                    <div class="col-md-3 employee_id">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Select Employee:</label>
                            <div class="input-group">
                                {!! Form::select('employee_id[]', $employeeList, $value = request('employee_id') ?: null, [
                                    'class' => 'form-control multiselect-select-all-filtering',
                                    'id' => 'employee_id',
                                    'multiple',
                                ]) !!}

                            </div>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="form-label">Contract Type</label>
                            <div class="input-group">
                                {!! Form::select('contract_type', $contractTypeList, request('job_type') ?: null, [
                                    'placeholder' => 'Select Contract Type',
                                    'class' => 'form-control select2',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Type:</label>
                        <div class="input-group">
                            {!! Form::select('type_id', $typeList, request('type_id') ?: null, [
                                'class' => 'form-control select2',
                                'placeholder' => 'Select Type',
                            ]) !!}
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">From Date:</label>
                        <div class="input-group">
                            {!! Form::text('from_nep_date', $value = request('from_nep_date') ?: null, [
                                'placeholder' => 'e.g : YYYY-MM-DD',
                                'class' => 'form-control nepali-calendar',
                                'autocomplete' => 'on',
                            ]) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">To Date:</label>
                        <div class="input-group">
                            {!! Form::text('to_nep_date', $value = request('to_nep_date') ?: null, [
                                'placeholder' => 'e.g : YYYY-MM-DD',
                                'class' => 'form-control nepali-calendar',
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
        $('.select2').select2();

        $('.organization-filtering').on('change', function() {
            var organizationId = $(this).val();
            $.ajax({
                type: 'GET',
                url: '/admin/organization/get-multiple-employees-search',
                data: {
                    organization_id: organizationId,
                },
                success: function(data) {
                    $('.employee_id').html('');
                    $('.employee_id').html(data.view);
                    $('#employee_id').multiselect({
                        includeSelectAllOption: true,
                        enableFiltering: true,
                        enableCaseInsensitiveFiltering: true
                    });
                }
            });
        });

        $('.leaveDateRange').daterangepicker({
            parentEl: '.content-inner',
            autoUpdateInput: false,
            showDropdowns: true,
            // minDate: minDate,
            // maxDate: maxDate,
            locale: {
                format: 'YYYY-MM-DD'
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                'YYYY-MM-DD'));
        }).on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>
