<fieldset class="mb-1">
    <legend class="text-uppercase font-size-sm font-weight-bold">Finance Setting</legend>
    <div class="row ml-1 mr-1 mt-3 mb-0">
        <div class="col-xl-4 col-sm-4 ">
            <div class="form-group row">
                <label class="col-form-label col-lg-3">PF Facility:<span class="text-danger">*</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            {!! Form::radio('pf', '1', true,['class'=>'form-check-input-styled','data-fouc']) !!}
                            Yes
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            {!! Form::radio('pf', '0', false,['class'=>'form-check-input-styled','data-fouc']) !!}
                            No
                        </label>
                    </div>
                    <span class="text-danger">{{ $errors->first('pf') }}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Gratuity Facility:<span class="text-danger">*</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            {!! Form::radio('gratuity', '1', true,['class'=>'form-check-input-styled','data-fouc']) !!}
                            Yes
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            {!! Form::radio('gratuity', '0', false,['class'=>'form-check-input-styled','data-fouc']) !!}
                            No
                        </label>
                    </div>
                    <span class="text-danger">{{ $errors->first('gratuity') }}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3">SSF Facility:<span class="text-danger">*</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            {!! Form::radio('ssf', '1', true,['class'=>'form-check-input-styled ssf','data-fouc']) !!}
                            Yes
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            {!! Form::radio('ssf', '0', false,['class'=>'form-check-input-styled ssf','data-fouc']) !!}
                            No
                        </label>
                    </div>
                    <span class="text-danger">{{ $errors->first('ssf') }}</span>
                </div>
            </div>
            @php
            if($is_edit) {

                if (empty($setting->ssf) || $setting->ssf == 1) {
                    $display="show";
                } else {
                    $display="none";
                }
            } else {
                $display="show";
            }
            @endphp
            <div class="form-group row SSF_div" style="display: {{$display}};">
                <label class="col-form-label col-lg-3">SSF Taxable / Tax Exempted :<span class="text-danger">*</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right text-right">
                    <div class="input-group">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                {!! Form::radio('ssf_tax', '1', true,['class'=>'form-check-input-styled','data-fouc']) !!}
                                Yes
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                {!! Form::radio('ssf_tax', '0', false,['class'=>'form-check-input-styled','data-fouc']) !!}
                                No
                            </label>
                        </div>
                        <span class="text-danger">{{ $errors->first('ssf_tax') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-sm-8 ">
            {{--<div class="form-group row">
                <label class="col-form-label col-lg-4"> Basic Salary Percentage:</label>
                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-percent"></i></span>
                        </span>
                        @if($is_edit)
                            {!! Form::text('basic_salary_percentage',$setting->basic_salary_percentage ,
                            ['id'=>'basic_salary_percentage','placeholder'=>'Basic Salary Percentage','class'=>'form-control
                            numeric']) !!}
                        @else
                            {!! Form::text('basic_salary_percentage',$value = null,
                            ['id'=>'basic_salary_percentage','placeholder'=>'Basic Salary Percentage','class'=>'form-control
                            numeric']) !!}
                        @endif
                        <span class="text-danger">{{ $errors->first('basic_salary_percentage') }}</span>
                    </div>
                </div>
            </div>--}}
            {!! Form::hidden('basic_salary_percentage',60,['id'=>'basic_salary_percentage']) !!}
            <div class="form-group row">
                <label class="col-form-label col-lg-4">Payroll Calendar Type:<span class="text-danger">*</span></label>
                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        @if($is_edit)
                            {!! Form::select('payroll_calendar_type',[ '0'=>'English','1'=>'Nepali'], $setting->payroll_calendar_type, ['id'=>'payroll_calendar_type','class'=>'form-control']) !!}
                        @else
                            {!! Form::select('payroll_calendar_type',[ '0'=>'English','1'=>'Nepali'], 1, ['id'=>'payroll_calendar_type','class'=>'form-control']) !!}
                        @endif
                        <span class="text-danger">{{ $errors->first('payroll_calendar_type') }}</span>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-4"> Currency:<span class="text-danger">*</span></label>
                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        @if($is_edit)
                            {!! Form::select('currency',$currency,null,['id'=>'currency','class'=>'form-control']) !!}
                        @else
                            {!! Form::select('currency', [''=>'Select Currency'],null,['class'=>'form-control']) !!}

                        @endif
                        <span class="text-danger">{{ $errors->first('currency') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

</fieldset>
<script type="text/javascript">
    $('document').ready(function () {
        $('.ssf').on('change', function () {
            var value = $(this).val();
            if (value == 1) {
                console.log('leave');
                $('.SSF_div').show();
            } else {
                $('.SSF_div').hide();
            }
        });
    });

</script>
