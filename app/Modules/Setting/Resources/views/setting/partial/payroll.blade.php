<legend class="text-uppercase font-size-sm font-weight-bold">Payroll Information</legend>

<div class="form-group row">
    <label class="col-form-label col-lg-3">Base Salary:</label>
    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
        <div class="input-group">
            <span class="input-group-prepend">
                <span class="input-group-text"><i class="icon-office"></i></span>
            </span>
            @if($is_edit)
                {!! Form::text('base_salary',$setting->base_salary, ['id'=>'base_salary','placeholder'=>'Enter Base Salary','class'=>'form-control numeric']) !!}
            @else
                {!! Form::text('base_salary',$value = null, ['id'=>'base_salary','placeholder'=>'Enter Base Salary','class'=>'form-control numeric']) !!}
            @endif
            <span class="text-danger">{{ $errors->first('base_salary') }}</span>
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-form-label col-lg-3">Taxable No. of Months:</label>
    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
        <div class="input-group">
            <span class="input-group-prepend">
                <span class="input-group-text"><i class="icon-home5"></i></span>
            </span>
            @if($is_edit)
                {!! Form::text('taxable_month',$setting->taxable_month, ['id'=>'taxable_month','placeholder'=>'Enter Taxable No. of Months','class'=>'form-control numeric', 'max' => 14]) !!}
            @else
                {!! Form::text('taxable_month',$value = null, ['id'=>'taxable_month','placeholder'=>'Enter Taxable No. of Months','class'=>'form-control numeric', 'max' => 14]) !!}
            @endif
            <span class="text-danger">{{ $errors->first('taxable_month') }}</span>
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-form-label col-lg-3">Apply tax in site allowance:</label>
    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                {!! Form::radio('site_allowance_tax', '1', isset($setting) && $setting->site_allowance_tax,['class'=>'form-check-input-styled','data-fouc']) !!}
                Yes
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                {!! Form::radio('site_allowance_tax', '0',  isset($setting) && !$setting->site_allowance_tax,['class'=>'form-check-input-styled','data-fouc']) !!}
                No
            </label>
        </div>
        <span class="text-danger">{{ $errors->first('site_allowance_tax') }}</span>
    </div>
</div>


