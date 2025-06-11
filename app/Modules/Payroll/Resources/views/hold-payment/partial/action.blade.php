{{-- @dd($calendarTypeList,$nepcalendarTypeList); --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row">
                    @if (isset($organizationId))
                        {!! Form::hidden('organization_id', $organizationId, []) !!}
                    @else
                        <div class="col-lg-6 mb-3">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Organization :<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('organization_id', $organizationList, null, [
                                            'placeholder' => 'Select Organization',
                                            'id' => 'organization',
                                            'class' => 'form-control select-search organization_id organization-filtering',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('organization_id'))
                                        <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-6 mb-3 employee_id">
                        <div class="row form-group mb-0">
                            <label class="col-form-label col-lg-4">Select Employees:</label>
                            <div class="col-lg-8">
                                <div class="input-group">
                                    @php $selected_emp_id = isset(request()->employee_id) ? request()->employee_id : null ; @endphp
                                    {!! Form::select('employee_id[]', $employeeList, $selected_emp_id, [
                                        'id' => 'employee_id',
                                        'class' => 'form-control multiselect-select-all-filtering employee_id',
                                        'multiple',
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                            @if ($errors->has('employee_id'))
                                <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                            @endif
                        </div>
                    </div>
                    {!! Form::hidden('calendar_type', null, ['id' => 'calendar_type']) !!}
                    <div class="col-lg-6 mb-3 year" style="display: none;">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Year :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group engDiv" style="display: none;">
                                    {!! Form::select('eng_year', $yearList, request('year') ?? null, [
                                        'placeholder' => 'Select Year',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                <div class="input-group nepDiv" style="display: none;">
                                    {!! Form::select('year', $nepaliYearList, request('year') ?? null, [
                                        'id' => 'nepYear',
                                        'placeholder' => 'Select Year',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                @if ($errors->has('year'))
                                    <div class="error text-danger">{{ $errors->first('year') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3 month" style="display: none;">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Month :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group engDiv filterMonth" style="display: none;">
                                    {{-- {!! Form::select('eng_month[]', $monthList, request('month') ?? null, [ --}}
                                    {!! Form::select('eng_month[]', [], request('month') ?? null, [
                                        'class' => 'form-control multiselect-select-all-filtering',
                                        'multiple',
                                        'required',
                                    ]) !!}
                                </div>
                                <div class="input-group nepDiv filterMonth" style="display: none;">
                                    {{-- {!! Form::select('month[]', $nepaliMonthList, request('month') ?? null, [ --}}
                                    {!! Form::select('month[]', [], request('month') ?? null, [
                                        'id' => 'nepMonth',
                                        'class' => 'form-control multiselect-select-all-filtering',
                                        'multiple',
                                        'required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('month'))
                                    <div class="error text-danger">{{ $errors->first('month') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-6 mb-3 released_year" style="display: none;">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Year :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group engDiv" style="display: none;">
                                    {!! Form::select('released_eng_year', $yearList, request('year') ?? null, [
                                        'placeholder' => 'Select Year',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                <div class="input-group nepDiv" style="display: none;">
                                    {!! Form::select('released_year', $nepaliYearList, request('year') ?? null, [
                                        'id' => 'nepYear',
                                        'placeholder' => 'Select Year',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                @if ($errors->has('year'))
                                    <div class="error text-danger">{{ $errors->first('year') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3 released_month" style="display: none;">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Month :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group engDiv" style="display: none;">
                                    {!! Form::select('released_eng_month', $monthList, request('month') ?? null, [
                                        'placeholder' => 'Select Month',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                <div class="input-group nepDiv" style="display: none;">
                                    {!! Form::select('released_month', $nepaliMonthList, request('month') ?? null, [
                                        'id' => 'nepMonth',
                                        'placeholder' => 'Select Month',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                @if ($errors->has('month'))
                                    <div class="error text-danger">{{ $errors->first('month') }}</div>
                                @endif
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Notes :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::textarea('notes', null, ['id' => 'notes', 'class' => 'form-control', 'placeholder' => 'Enter notes']) !!}
                                </div>
                                @if ($errors->has('organization_id'))
                                    <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <!-- validation js -->
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script src="{{ asset('admin/validation/hold-payment.js') }}"></script>

    <script>
        $(document).ready(function() {
            @isset($holdPayment)
            var selectedMonth="{{@$holdPayment->month ?? null}}";
            var selectedEmployee="{{@$holdPayment->employee_id ?? null}}";
            @else
            var selectedMonth=null;
            var selectedEmployee=null;
            @endisset
            
            var yearType = null;
            const getFilterMonth = () => {
                var filterList = {
                    'calender_type': $('#calendar_type').val(),
                    'year': $(`${yearType}`).val(),
                    'organization_id': $('#organization').val(),
                    'selectedMonth':selectedMonth
                };
                $.ajax({
                    url: "{{ route('filter-hold-payment-mont') }}",
                    type: "get",
                    data: {
                        data: filterList
                    },
                    success: function(response) {
                        if (response.error) {
                            $('.filterMonth').html('');
                            return false;
                        }
                        $('.filterMonth').html('');
                        console.log('Month Data',response.data);
                        $('.filterMonth').html(response.data);
                        $('.multiselect-select-all-filtering').multiselect({
                            includeSelectAllOption: true,
                            enableFiltering: true,
                            enableCaseInsensitiveFiltering: true
                        });
                    }
                });
            }
            const setYearAction = (action) => {
                $(`${action}`).on('change', function() {
                    getFilterMonth();
                });
            }

            $('#calendarType').on('change', function() {
                var type = $(this).val();
                if (type == 'nep') {
                    $('.engDiv').hide();
                    $('.nepDiv').show();
                    $('#nepYear').removeAttr("disabled");
                    $('#nepMonth').removeAttr("disabled");
                } else {
                    $('.engDiv').show();
                    $('.nepDiv').hide();
                }
            });
            $('#organization').on('change', function() {
                var organizationId = $('.organization_id').val();
                $.ajax({
                    type: 'GET',
                    url: '/admin/payroll-setting/get-calendar-type',
                    data: {
                        organization_id: organizationId
                    },
                    success: function(data) {
                        var list = JSON.parse(data);
                        var calenderType = list.calendar_type;
                        $('#calendar_type').val(calenderType);
                        if (list.calendar_type == 'nep') {
                            $('.engDiv').hide();
                            $('.nepDiv').show();
                            $('.calendar_type').show();
                            $('.year').show();
                            $('.month').show();
                            $('#nepYear').removeAttr("disabled");
                            $('#nepMonth').removeAttr("disabled");
                            $('#calendarType').removeAttr("disabled");
                            yearType = '#nepYear';
                        } else {
                            $('.calendar_type').show();
                            $('.year').show();
                            $('.month').show();
                            $('.engDiv').show();
                            $('.nepDiv').hide();
                            yearType = '.year';
                        }
                        getFilterMonth();
                        setYearAction(yearType);

                    }
                });
            });
            $('.organization-filtering').on('change', function() {
                var organizationId = $('.organization-filtering').val();
                $.ajax({
                    type: 'GET',
                    url: '/admin/organization/get-multiple-employees',
                    data: {
                        organization_id: organizationId,
                        selectedEmployee:selectedEmployee
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

            @isset($holdPayment)
            $('#organization').trigger('change');
            @endisset


        });
    </script>
@endSection
