@extends('admin::layout')
@section('title')Tada Type @stop
@section('breadcrum')
    <a href="{{ route('deviceManagement.index') }}" class="breadcrumb-item">Biometric Device Information </a>
    <a class="breadcrumb-item active"> Add Device </a>
@endsection
@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/validation/deviceManagement.js') }}"></script>
@stop

@section('content')
    <!-- Form inputs -->

    {!! Form::open([
        'route' => 'deviceManagement.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'device_submit',
    ]) !!}

    @include('setting::device-management.partial.action', ['btnType' => 'Save'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
