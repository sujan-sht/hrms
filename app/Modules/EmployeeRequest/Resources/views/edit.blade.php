@extends('admin::layout')
@section('title')Request @stop
@section('breadcrum')HR Requisition / Request Management / Edit Request @stop

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/validation/employeeRequest.js') }}"></script>
@stop

@section('content')
    <!-- Form inputs -->

    {!! Form::model($employeeRequest, [
        'route' => ['employeeRequest.update', $employeeRequest->id],
        'method' => 'PATCH',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'employee_request_submit',
    ]) !!}

    @include('employeerequest::partial.action', ['btnType' => 'Update'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
