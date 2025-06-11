<script type="text/javascript">
    window.onload = function() {
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
                                            'class' => 'form-control select-search organization_id organization-filter',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('organization_id'))
                                        <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Employee :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    @php
                                        if (isset($_GET['employee_id'])) {
                                            $employeeValue = $_GET['employee_id'];
                                        } else {
                                            $employeeValue = null;
                                        }
                                    @endphp
                                    {!! Form::select('employee_id', $employeeList, $value = $employeeValue, [
                                        'placeholder' => 'Select Employee',
                                        'class' => 'form-control select2 employee-filter',
                                        'required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('employee_id'))
                                    <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Nep Start Date :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('start_date', null, ['rows'=>5, 'placeholder'=>'e.g: YYYY-MM-DD', 'class'=>'form-control', 'id'=>'nepali-datepicker-start-date', 'readonly']) !!}
                                </div>
                                @if($errors->has('start_date'))
                                    <div class="error text-danger">{{ $errors->first('start_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-lg-6 mb-3">
                        <div class="row engDiv" style="display:none">
                            <label class="col-form-label col-lg-4">From Date :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('from_date', null, ['rows'=>5, 'placeholder'=>'e.g: YYYY-MM-DD', 'class'=>'form-control daterange-single date', 'readonly']) !!}
                                </div>
                                @if($errors->has('from_date'))
                                    <div class="error text-danger">{{ $errors->first('from_date') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row nepDiv" style="display:none">
                            <label class="col-form-label col-lg-4">From date :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('nep_from_date', null, ['rows'=>5, 'placeholder'=>'e.g: YYYY-MM-DD', 'class'=>'form-control ','id'=>'nepali-datepicker-start-date', 'readonly']) !!}
                                </div>
                                @if($errors->has('to_date'))
                                    <div class="error text-danger">{{ $errors->first('to_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row engDiv" style="display:none">
                            <label class="col-form-label col-lg-4">To date :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('to_date', null, ['rows'=>5, 'placeholder'=>'e.g: YYYY-MM-DD', 'class'=>'form-control daterange-single date', 'readonly']) !!}
                                </div>
                                @if($errors->has('to_date'))
                                    <div class="error text-danger">{{ $errors->first('to_date') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row nepDiv" style="display:none">
                            <label class="col-form-label col-lg-4">To date :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('nep_to_date', null, ['rows'=>5, 'placeholder'=>'e.g: YYYY-MM-DD', 'class'=>'form-control ','id'=>'nepali-datepicker-end-date', 'readonly']) !!}
                                </div>
                                @if($errors->has('nep_to_date'))
                                    <div class="error text-danger">{{ $errors->first('nep_to_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                                       
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Notes :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::textarea('notes', null, ['id'=>'notes','class'=>'form-control', 'placeholder' => 'Enter notes']) !!}
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
<script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/validation/stop-payment.js') }}"></script>
<script>
    $(document).ready(function() {
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
                    } else {
                        $('.engDiv').show();
                        $('.nepDiv').hide();
                    }
                    // console.log(list.calendar_type);


                }
            });
        });
    });
</script>
@endsection
