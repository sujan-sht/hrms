@extends('admin::layout')
@section('title')Grievance @stop
@section('breadcrum')
    <a href="{{route('grievance.index')}}" class="breadcrumb-item">Grievance</a>
    <a class="breadcrumb-item active">Create</a>
@stop
@section('script')
    <script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>
    <script src="{{ asset('admin/validation/grievance.js') }}"></script>

@endsection

@section('content')
    {!! Form::open([
        'route' => 'grievance.store',
        'method' => 'POST',
        'id' => 'grievance_submit',
        'class' => 'form-horizontal grievanceForm',
        'role' => 'form',
        'files' => true,
    ]) !!}
    @include('grievance::grievance.partial.action', ['btnType' => 'Save Record'])
    {!! Form::close() !!}
@endsection




