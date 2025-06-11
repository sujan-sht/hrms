<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open([
            'route' => 'monthlyAttendanceSummary',
            'method' => 'GET',
            'class' => 'form-horizontal',
            'id' => 'tada_filter',
            'role' => 'form',
        ]) !!}
        <div class="row">
            {{-- <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Date Range : </label>
                    <div class="input-group">
                        @php
                            if (isset($_GET['date_range'])) {
                                $dateRangeValue = $_GET['date_range'];
                            } else {
                                $dateRangeValue = null;
                            }
                        @endphp
                        {!! Form::text('date_range', $value = $dateRangeValue, [
                            'placeholder' => 'e.g : YYYY-MM-DD to YYYY-MM-DD',
                            'class' => 'form-control daterange-buttons',
                            'autocomplete' => 'off',
                        ]) !!}
                    </div>
                </div>
            </div> --}}
            @if (Auth::user()->user_type == 'employee')
                {!! Form::hidden('emp_id', $value = optional(auth()->user()->userEmployer)->id, []) !!}
            @elseif(Auth::user()->user_type == 'supervisor')
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Employee:</label>
                        <div class="input-group">
                            @php $selected_emp_id = isset(request()->emp_id) ? request()->emp_id : null ; @endphp
                            {{-- @php $selected_emp_id = isset(request()->emp_id) ? request()->emp_id : optional(auth()->user()->userEmployer)->id ; @endphp --}}
                            {!! Form::select('emp_id', $employeeData, $selected_emp_id, [
                                'class' => 'form-control select2',
                                'placeholder' => 'Select Employee',
                            ]) !!}
                        </div>
                    </div>
                </div>
            @elseif(Auth::user()->user_type == 'super_admin' || Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'hr')
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Organization:</label>
                        <div class="input-group">
                            @php $selected_org_id = isset(request()->org_id) ? request()->org_id : (isset(request()->organization_id) ? request()->organization_id : null) ; @endphp
                            {{-- @php $selected_org_id = isset(request()->org_id) ? request()->org_id : optional(auth()->user()->userEmployer)->organization_id ; @endphp --}}
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
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Employee:</label>
                        <div class="input-group">
                            @php
                                if (Auth::user()->user_type == 'hr') {
                                    $selected_emp_id = isset(request()->emp_id) ? request()->emp_id : '';
                                } else {
                                    $selected_emp_id = isset(request()->emp_id)
                                        ? request()->emp_id
                                        : optional(auth()->user()->userEmployer)->id;
                                }
                            @endphp
                            {!! Form::select('emp_id', $employees, $selected_emp_id ?? '', [
                                'class' => 'form-control select2 employee-filter',
                                'placeholder' => 'Select Employee',
                            ]) !!}
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Select Calendar Type:</label>
                    <div class="input-group">
                        @php $calendarType = isset(request()->calendar_type) ? request()->calendar_type : null ; @endphp
                        {!! Form::select('calendar_type', ['eng' => 'English', 'nep' => 'Nepali'], $calendarType, [
                            'class' => 'form-control calendartype select2',
                            'required',
                        ]) !!}
                    </div>
                </div>
            </div>

            @if (auth()->user()->user_type == 'super_admin' ||
                    auth()->user()->user_type == 'admin' ||
                    auth()->user()->user_type == 'hr')
                <div class="col-md-3 mt-2 engdata">
                @else
                    <div class="col-md-3 engdata">
            @endif
            <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                <label class="d-block font-weight-semibold">Select English Year:</label>
                <div class="input-group">
                    @php $eng_year = isset(request()->eng_year) ? request()->eng_year : null ; @endphp
                    {!! Form::select('eng_year', $eng_years, $eng_year, [
                        'class' => 'form-control eng_year select2',
                        'placeholder' => 'Select English Year',
                    ]) !!}
                </div>
            </div>
        </div>


        @if (auth()->user()->user_type == 'super_admin' ||
                auth()->user()->user_type == 'admin' ||
                auth()->user()->user_type == 'hr')
            <div class="col-md-3 mt-2 engdata">
            @else
                <div class="col-md-3 engdata">
        @endif
        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
            <label class="d-block font-weight-semibold">Select English Month:</label>
            <div class="input-group">
                @php $eng_month = isset(request()->eng_month) ? request()->eng_month : null ; @endphp
                {!! Form::select('eng_month', $eng_months, $eng_month, [
                    'class' => 'form-control eng_month select2',
                    'placeholder' => 'Select English Month',
                ]) !!}
            </div>
        </div>
    </div>

    @if (auth()->user()->user_type == 'super_admin' ||
            auth()->user()->user_type == 'admin' ||
            auth()->user()->user_type == 'hr')
        <div class="col-md-3 mt-2 nepdata" style="display: none">
        @else
            <div class="col-md-3 nepdata" style="display: none">
    @endif
    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
        <label class="d-block font-weight-semibold">Select Nepali Year:</label>
        <div class="input-group">
            @php $nep_year = isset(request()->nep_year) ? request()->nep_year : null ; @endphp
            {!! Form::select('nep_year', $nep_years, $nep_year, [
                'class' => 'form-control nep_year select2',
                'placeholder' => 'Select Nepali Year',
            ]) !!}
        </div>
    </div>
</div>

@if (auth()->user()->user_type == 'super_admin' ||
        auth()->user()->user_type == 'admin' ||
        auth()->user()->user_type == 'hr')
    <div class="col-md-3 mt-2 nepdata" style="display: none">
    @else
        <div class="col-md-3 nepdata" style="display: none">
@endif
<div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
    <label class="d-block font-weight-semibold">Select Nepali Month:</label>
    <div class="input-group">
        @php $nep_month = isset(request()->nep_month) ? request()->nep_month : null ; @endphp
        {!! Form::select('nep_month', $nep_months, $nep_month, [
            'class' => 'form-control nep_month select2',
            'placeholder' => 'Select Nepali Month',
        ]) !!}
    </div>
</div>
</div>

</div>

<div class="d-flex justify-content-end mt-2">
    <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
    <a href="{{ request()->url() }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
</div>
{!! Form::close() !!}
</div>
</div>


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
