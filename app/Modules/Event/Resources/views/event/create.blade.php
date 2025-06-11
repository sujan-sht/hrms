@extends('admin::layout')
@section('title')Event @stop
@section('breadcrum')
    <a href="{{ route('leaveType.index') }}" class="breadcrumb-item">Event</a>
    <a class="breadcrumb-item active">Create</a>
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

            // Fixed width. Multiple selects
            $('.select-fixed-multiple').select2({
                minimumResultsForSearch: Infinity,
                width: 400
            });
        })
    </script>

@stop

@section('content')
    {!! Form::open([
        'route' => 'event.store',
        'method' => 'POST',
        'id' => 'event_submit',
        'class' => 'form-horizontal eventForm',
        'role' => 'form',
        'files' => true,
    ]) !!}
    @include('event::event.partial.action', ['btnType' => 'Save', 'goBack' => true])
    {!! Form::close() !!}
@stop
