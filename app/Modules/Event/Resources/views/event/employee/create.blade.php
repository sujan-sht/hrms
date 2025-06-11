@extends('admin::employee.layout')
@section('title')Event @stop 
@section('breadcrum')Add Event @stop

@section('scripts')
<script src="{{asset('admin/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>
<script src="{{asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
<script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_inputs.js')}}"></script>
<script src="{{ asset('employee/validation/event.js')}}"></script>

<script>
$(document).ready(function() {
    $('#start-timepicker').clockTimePicker();

    // Fixed width. Multiple selects
    $('.select-fixed-multiple').select2({
        minimumResultsForSearch: Infinity,
        width: 400
    });

    // Single picker
    $('.daterange-single').daterangepicker({ 
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD'
        }
        
    });
})
</script>

@stop 

@section('content')

<div class="box add-request">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6><a href="{{route('employee-event.index')}}"><i class="fa fa-chevron-circle-left"></i></a>
                        Add Event</h6>
                </div>
                <div class="card-body">
                    <h5>Event Details</h5>
                    {!! Form::open(['route'=>'employee-event.store','method'=>'POST','id'=>'event_submit','class'=>'form-horizontal','role'=>'form']) !!}
                        @include('event::event.employee.partial.action')

                        <hr />
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-primary mr-3" type="submit" value="submit" name="btn_name">Submit</button>
                                {{--<button class="btn btn-default mr-3" type="submit" value="submit_new" name="btn_name">Submit and New</button>--}}
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@stop