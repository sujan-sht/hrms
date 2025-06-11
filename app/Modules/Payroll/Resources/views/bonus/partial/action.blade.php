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
                                            'class' => 'form-control select-search organization_id',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('organization_id'))
                                        <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-6 mb-3 calendar_type" style="display: none;">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Calendar :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group engDiv" style="display: none;">
                                    {!! Form::select('eng_calendar_type', $calendarTypeList, request('calendar_type') ?? null, [
                                        'placeholder' => 'Select Calendar Type',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                <div class="input-group nepDiv" style="display: none;">
                                    {!! Form::select('calendar_type', $nepcalendarTypeList, request('calendar_type') ?? null, [
                                        'placeholder' => 'Select Calendar Type',
                                        'id' => 'calendarType',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                @if ($errors->has('calendar_type'))
                                    <div class="error text-danger">{{ $errors->first('calendar_type') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
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
                                <div class="input-group engDiv" style="display: none;">
                                    {!! Form::select('eng_month', $monthList, request('month') ?? null, [
                                        'placeholder' => 'Select Month',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                <div class="input-group nepDiv" style="display: none;">
                                    {!! Form::select('month', $nepaliMonthList, request('month') ?? null, [
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
    <script src="{{ asset('admin/validation/payroll.js') }}"></script>

    <script>
        $(document).ready(function() {
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
                        if (list.calendar_type == 'nep') {
                            $('.engDiv').hide();
                            $('.nepDiv').show();
                            $('.calendar_type').show();
                            $('.year').show();
                            $('.month').show();
                            $('#nepYear').removeAttr("disabled");
                            $('#nepMonth').removeAttr("disabled");
                            $('#calendarType').removeAttr("disabled");
                        } else {
                            $('.calendar_type').show();
                            $('.year').show();
                            $('.month').show();
                            $('.engDiv').show();
                            $('.nepDiv').hide();
                        }
                    }
                });
            });
        });
    </script>
@endSection
