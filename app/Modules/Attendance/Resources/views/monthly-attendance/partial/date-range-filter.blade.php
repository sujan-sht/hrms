<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open([
            'route' => 'monthlyAttendanceRange',
            'method' => 'GET',
            'class' => 'form-horizontal',
            'id' => 'tada_filter',
            'role' => 'form',
        ]) !!}
        <div class="row">
            @if (Auth::user()->user_type == 'employee')
                {!! Form::hidden('emp_id[]', $value = optional(auth()->user()->userEmployer)->id, []) !!}
            @elseif(Auth::user()->user_type == 'supervisor')
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Employee:</label>
                        <div class="input-group">
                            @php $selected_emp_id = isset(request()->emp_id) ? request()->emp_id : null ; @endphp
                            {!! Form::select('emp_id[]', $employeeData, $selected_emp_id, [
                                'class' => 'form-control select2',
                                'placeholder' => 'Select Employee',
                            ]) !!}
                        </div>
                    </div>
                </div>
            @elseif(Auth::user()->user_type == 'super_admin' ||
                    Auth::user()->user_type == 'admin' ||
                    Auth::user()->user_type == 'hr' ||
                    Auth::user()->user_type == 'division_hr')
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
                {{-- <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Employee:</label>
                        <div class="input-group">
                            @php $selected_emp_id = isset(request()->emp_id) ? request()->emp_id : null ; @endphp
                            {!! Form::select('emp_id', $employees, $selected_emp_id, [
                                'class' => 'form-control select2 employee-filter',
                                'placeholder' => 'Select Employee',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                </div> --}}
                <div class="col-lg-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Employees:</label>
                        <div class="input-group">
                            @php $selected_emp_id = isset(request()->emp_id) ? request()->emp_id : null ; @endphp
                            {!! Form::select('emp_id[]', $employees, $selected_emp_id, [
                                'class' => 'form-control select2',
                                // 'required',
                                'placeholder' => 'Select Employee',
                            ]) !!}
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Select Shift:</label>
                    <div class="input-group">
                        @php $selected_shift_id = isset(request()->shift_id) ? request()->shift_id : null ; @endphp
                        {!! Form::select('shift_id', $shiftLists, $selected_shift_id, [
                            'class' => 'form-control select2 select-search',
                            'placeholder' => 'Select Shift',
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-3 mt-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">From Date:</label>
                    <x-utilities.date-picker :date="isset(request()->from_date) ? request()->from_date : null" mode="both" default="eng"
                        nepDateAttribute="nep_from_date" engDateAttribute="from_date" />
                </div>
            </div>

            <div class="col-md-3 mt-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">To Date:</label>
                    <x-utilities.date-picker :date="isset(request()->to_date) ? request()->to_date : null" mode="both" default="eng"
                        nepDateAttribute="nep_to_date" engDateAttribute="to_date" />
                </div>
            </div>

        </div>
    </div>


    <div class="d-flex justify-content-end form-group mb-3 pt-1 pb-1 pl-3 pr-3">
        <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
        <a href="{{ request()->url() }}" class="btn bg-secondary text-white"><i
                class="icons icon-reset mr-1"></i>Reset</a>
    </div>
    {!! Form::close() !!}
</div>

<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
<script>
    $(document).ready(function() {

        let type = $('.calendartype').find(":selected").val();
        if (type == 'eng') {
            $('.engdata').css('display', 'block')
            $('.nepdata').css('display', 'none')

            $('.nep_year').removeAttr('required')
            $('.nep_month').removeAttr('required')

            $('.eng_year').attr('required', true)
            $('.eng_month').attr('required', true)
        }

        if (type == 'nep') {
            $('.engdata').css('display', 'none')
            $('.nepdata').css('display', 'block')

            $('.nep_year').attr('required', true)
            $('.nep_month').attr('required', true)

            $('.eng_year').removeAttr('required')
            $('.eng_month').removeAttr('required')
        }

        $('.select2').select2();
    })



    $(document).on('change', '.calendartype', function() {
        let type = $(this).val();
        if (type == 'eng') {
            $('.engdata').css('display', 'block')
            $('.nepdata').css('display', 'none')

            $('.nep_year').removeAttr('required')
            $('.nep_month').removeAttr('required')

            $('.eng_year').attr('required', true)
            $('.eng_month').attr('required', true)

            $('.nep_year').val('')
            $('.nep_month').val('')
        }
        if (type == 'nep') {
            $('.engdata').css('display', 'none')
            $('.nepdata').css('display', 'block')

            $('.nep_year').attr('required', true)
            $('.nep_month').attr('required', true)

            $('.eng_year').removeAttr('required')
            $('.eng_month').removeAttr('required')

            $('.eng_year').val('')
            $('.eng_month').val('')
        }
    })
</script>
