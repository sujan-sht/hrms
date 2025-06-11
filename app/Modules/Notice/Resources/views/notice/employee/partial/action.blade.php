<script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js')}}"></script>
<script src="{{ asset('admin/global/js/demo_pages/picker_date.js')}}"></script>

<div class="form-group row">
    <div class="col-12 col-md-3"><label for="employee_name">Title<span class="text-danger">*</span></label>
    </div>
    <div class="col-12 col-md-9">
        {!! Form::text('title', $value = null, ['id'=>'title','placeholder'=>'Enter Title','class'=>'form-control']) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-3"><label for="year">Date <span class="text-danger">*</span></label>
    </div>
    <div class="col-12 col-md-9">
        <div class="m-icon">
           {!! Form::text('notice_date', $value = null, ['id'=>'notice_date','class'=>'form-control daterange-single']) !!}
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-3"><label for="month">Description <span class="text-danger">*</span></label>
    </div>
    <div class="col-12 col-md-9">
        {!! Form::textarea('description', null, ['id' => 'description','placeholder'=>'Enter Description','class' =>'form-control']) !!}
    </div>
</div>