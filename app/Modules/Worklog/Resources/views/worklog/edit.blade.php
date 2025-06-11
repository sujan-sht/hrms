@extends('admin::layout')
@section('title')Work Log @stop
@section('breadcrum')
    <a href="{{ route('worklog.index') }}" class="breadcrumb-item">Work Log</a>
    <a class="breadcrumb-item active">Edit</a>
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

    {!! Form::model($worklog, [
        'method' => 'PUT',
        'route' => ['worklog.update', $worklog->id],
        'class' => 'form-horizontal',
        'id'=>'worklog_submit',
        'role' => 'form',
    ]) !!}
    @include('worklog::worklog.partial.action', ['btnType' => 'Update Record'])
    {!! Form::close() !!}

    <!-- /form inputs -->

@stop
