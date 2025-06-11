@extends('admin::layout')
@section('title')Request Type @stop
@section('breadcrum')
    <a href="{{ route('employeeRequestType.index') }}" class="breadcrumb-item">Request Type Management </a>
    <a class="breadcrumb-item active">Edit Request Type</a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/validation/employeeRequestType.js') }}"></script>
@stop

@section('content')
    <!-- Form inputs -->


    {!! Form::model($requestType, [
        'route' => ['employeeRequestType.update', $requestType->id],
        'method' => 'PATCH',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'request_type_submit',
    ]) !!}

    @include('employeerequest::employeeRequestType.partial.action', ['btnType' => 'Update'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
