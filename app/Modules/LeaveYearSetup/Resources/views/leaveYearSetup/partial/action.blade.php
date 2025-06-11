<script type="text/javascript">
    window.onload = function () {
        var startDateInput = document.getElementById("nepali-datepicker-start-date");
        startDateInput.nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 10
        });
        var endDateInput = document.getElementById("nepali-datepicker-end-date");
        endDateInput.nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 10
        });
    };
</script>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Leave Year Details</legend>
                <div class="form-group row">
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Calender :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('calender_type', ['eng' => 'English', 'nep' => 'Nepali'], request('calendar_type') ?? null, [
    'id' => 'calendarType',
    'placeholder' => 'Select Calendar Type',
    'class' => 'form-control select-search',
]) !!}
                                </div>
                                @if($errors->has('calender_type'))
                                    <div class="error text-danger">{{ $errors->first('calender_type') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3 nepaliItem" style="display: none">
                        <div class="row">
                            <label class="col-form-label col-lg-3"> Leave Year Title :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('leave_year', @$leaveYearSetupModel->leave_year ?? null, ['rows' => 5, 'placeholder' => 'e.g: 2079/80', 'class' => 'form-control']) !!}
                                </div>
                                @if($errors->has('leave_year'))
                                    <div class="error text-danger">{{ $errors->first('leave_year') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3 englishItem" style="display: none">
                        <div class="row">
                            <label class="col-form-label col-lg-3"> Leave Year Title :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('leave_year_english', @$leaveYearSetupModel->leave_year_english ?? null, ['rows' => 5, 'placeholder' => 'e.g: 2022/23', 'class' => 'form-control']) !!}
                                </div>
                                @if($errors->has('leave_year'))
                                    <div class="error text-danger">{{ $errors->first('leave_year') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3 nepaliItem" style="display: none">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Nep Start Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('start_date', @$leaveYearSetupModel->start_date ?? null, ['rows' => 5, 'placeholder' => 'e.g: YYYY-MM-DD', 'class' => 'form-control', 'id' => 'nepali-datepicker-start-date', 'readonly']) !!}
                                </div>
                                @if($errors->has('start_date'))
                                    <div class="error text-danger">{{ $errors->first('start_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3 englishItem" style="display: none">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Eng Start Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('start_date_english', @$leaveYearSetupModel->start_date_english ?? null, ['rows' => 5, 'placeholder' => 'e.g: YYYY-MM-DD', 'class' => 'form-control daterange-single date', 'readonly']) !!}
                                </div>
                                @if($errors->has('start_date_english'))
                                    <div class="error text-danger">{{ $errors->first('start_date_english') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3 nepaliItem" style="display: none">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Nep End Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('end_date', @$leaveYearSetupModel->end_date ?? null, ['rows' => 5, 'placeholder' => 'e.g: YYYY-MM-DD', 'class' => 'form-control ', 'id' => 'nepali-datepicker-end-date', 'readonly']) !!}
                                </div>
                                @if($errors->has('end_date'))
                                    <div class="error text-danger">{{ $errors->first('end_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3 englishItem" style="display: none">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Eng End Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('end_date_english', @$leaveYearSetupModel->end_date_english ?? null, ['rows' => 5, 'placeholder' => 'e.g: YYYY-MM-DD', 'class' => 'form-control daterange-single date', 'readonly']) !!}
                                </div>
                                @if($errors->has('end_date_english'))
                                    <div class="error text-danger">{{ $errors->first('end_date_english') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Status :<span class="text-danger"> *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('status', [1 => 'Active', 0 => 'In-Active'], null, ['placeholder' => 'Select Status', 'class' => 'form-control select-search']) !!}
                                </div>
                                @if($errors->has('status'))
                                    <div class="error text-danger">{{ $errors->first('status') }}</div>
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
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
<script src="{{asset('admin/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
<script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_inputs.js')}}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js')}}"></script>
<script src="{{ asset('admin/validation/leaveYearSetup.js')}}"></script>

<script>
    $(document).ready(function () {

        $('#calendarType').on('change', function () {
            var type = $(this).val();
            if (type == 'nep') {
                $('.englishItem').hide();
                $('.nepaliItem').show();
            } else {
                $('.nepaliItem').hide();
                $('.englishItem').show();
            }
        });

        @isset($leaveYearSetupModel)
            $('#calendarType').change();
        @endisset
    });
</script>
@endSection
