@extends('admin::layout')
@section('title')Event @stop
@section('breadcrum')
    <a href="{{ route('leaveType.index') }}" class="breadcrumb-item">Event</a>
    <a class="breadcrumb-item active">Edit</a>
@stop
@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/validation/event.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#start-timepicker').clockTimePicker();
        })
    </script>

@stop

@section('content')

    {!! Form::model($event, [
        'method' => 'PUT',
        'route' => ['event.update', $event->id],
        'class' => 'form-horizontal',
        'role' => 'form',
        'files' => true,
    ]) !!}
    @include('event::event.partial.action', ['btnType' => 'Update'])
    {!! Form::close() !!}

@stop
