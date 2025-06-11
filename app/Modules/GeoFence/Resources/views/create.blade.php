@extends('admin::layout')
@section('title')GeoFence @stop

@section('breadcrum')
    <a href="{{ route('geoFence.index') }}" class="breadcrumb-item">GeoFence</a>
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
    <script src="{{ asset('admin/validation/geoFence.js') }}"></script>
@endsection

@section('content')

    <!-- Form inputs -->

    {!! Form::open([
        'route' => 'geoFence.store',
        'method' => 'POST',
        'id' => 'geofence_submit',
        'class' => 'form-horizontal geoFenceForm',
        'role' => 'form',
        'files' => true,
    ]) !!}
    @include('geofence::partial.action', ['btnType' => 'Save Record'])
    {!! Form::close() !!}
    <!-- /form inputs -->

@stop
