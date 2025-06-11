@extends('admin::layout')
@section('title')Work log @stop

@section('breadcrum')
    <a href="{{ route('worklog.index') }}" class="breadcrumb-item">Work Log</a>
    <a class="breadcrumb-item active">Create</a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/validation/workLog.js') }}"></script>
@endsection

@section('content')

    <!-- Form inputs -->

    {!! Form::open([
        'route' => 'worklog.store',
        'method' => 'POST',
        'id' => 'worklog_submit',
        'class' => 'form-horizontal workLogForm',
        'role' => 'form',
        'files' => true,
    ]) !!}
    @include('worklog::worklog.partial.action', ['btnType' => 'Save Record'])
    {!! Form::close() !!}
    <!-- /form inputs -->

@stop
