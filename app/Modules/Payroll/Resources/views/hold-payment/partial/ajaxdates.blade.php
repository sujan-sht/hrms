<div class="form-group row released_status">
    <label class="col-form-label col-lg-3">Employee:<span class="text-danger">*</span></label>
    <div class="col-lg-9">
        {!! Form::select('employee_id[]', $employeeList, null,[
            'class' => 'form-control multi',
            'multiple',
            'required',
        ]) !!}
    </div>
</div>
@if($status == 2)
    <div class="form-group row released_status">
        <label class="col-form-label col-lg-3">Released Year:<span class="text-danger">*</span></label>
        <div class="col-lg-9">
            {!! Form::select('released_year', $yearList, null,['class'=>'form-control','id'=>'released_year']) !!}
        </div>
    </div>

    <div class="form-group row released_status">
        <label class="col-form-label col-lg-3">Released Month:<span class="text-danger">*</span></label>
        <div class="col-lg-9">
            {!! Form::select('released_month', $monthList, null,['class'=>'form-control','id'=>'released_month','placeholder'=>'Select Month','required']) !!}
        </div>
    </div>
@endif
