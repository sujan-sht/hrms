<style>
.select2-container {
    width:99%!important
}
.clock-timepicker {
    width:100%!important
}
</style>
<div class="form-group row">
    <div class="col-12 col-md-3"><label for="title">Event Title <span class="text-danger">*</span></label>
    </div>
    <div class="col-12 col-md-8">
        {!! Form::text('title', $value = null, ['id'=>'title','placeholder'=>'Enter Event Title','class'=>'form-control']) !!}
        <span class="text-danger">{{ $errors->first('title') }}</span>
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-3"><label for="event_date">Event Date:<span class="text-danger">*</span></div>

    <div class="col-12 col-md-8">
        {!! Form::text('event_date', $value = null, ['id'=>'event_date','class'=>'form-control dashboard-event-date']) !!}
        <span class="text-danger">{{ $errors->first('event_date') }}</span>
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-3"><label for="event_time">Event Time:<span class="text-danger">*</span></div>

    <div class="col-12 col-md-8">
        {!! Form::text('event_time', null, ['class' => 'form-control', 'id' => 'start-timepicker']) !!}
        <span class="text-danger">{{ $errors->first('event_time') }}</span>                
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-3"><label for="tagged_users">Tag Employees:</div>
    <div class="col-12 col-md-8">
        {!! Form::select('tagged_users[]', $event_users, null,
        ['id'=>'tagged_users', 'class'=>'form-control select-fixed-multiple', 'multiple']) !!}
        <span class="text-danger">{{ $errors->first('tagged_employees') }}</span>
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-3"><label for="title">Event Location:</div>
    <div class="col-12 col-md-8">
        {!! Form::text('location', $value = null, ['id'=>'location','placeholder'=>'Enter Event Location','class'=>'form-control']) !!}
        <span class="text-danger">{{ $errors->first('location') }}</span>
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-3"><label for="description">Description :<span class="text-danger">*</span></div>
    <div class="col-12 col-md-8">
        {!! Form::textarea('description', null, ['id' => 'description','placeholder'=>'Enter Description',
        'class' =>'form-control']) !!}
        <span class="text-danger">{{ $errors->first('description') }}</span>
    </div>
</div>

<div class="form-group row">
    <div class="col-12 col-md-3"><label for="note">Note :</div>
    <div class="col-12 col-md-8">
        {!! Form::textarea('note', null, ['id' => 'note','placeholder'=>'Enter Note', 'class' =>'form-control'])!!}
        <span class="text-danger">{{ $errors->first('note') }}</span>
    </div>
</div>


