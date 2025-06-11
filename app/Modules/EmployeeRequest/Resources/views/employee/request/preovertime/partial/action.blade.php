<div class="form-group row">
    <div class="col-12 col-md-2"><label for="request_title">Request Title <span>*</span></label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::text('title', null, ['placeholder'=>'Enter Title','class'=>'form-control']) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-2"><label for="request_type">Date<span class="text-danger">*</span></label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::text('ot_date', $value = null, ['id' => 'ot_date', 'placeholder'=>'Select Pre-overtime Date','class'=>'form-control daterange-single']) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-2"><label for="request_title">Overtime Hours <span>*</span></label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::text('ot_hrs', null, ['placeholder'=>'Enter Overtime hrs','class'=>'form-control numeric']) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-2"><label for="description">Description</label>
    </div>
    <div class="col-12 col-md-7">
        {!! Form::textarea('description', null, ['class'=>'form-control']) !!}
    </div>
</div>