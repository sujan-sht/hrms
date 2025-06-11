@extends('admin::layout')
@section('title')Request @stop

@section('breadcrum')
    <a href="{{ route('employeerequest.index') }}" class="breadcrumb-item">Request Management </a>
    <a class="breadcrumb-item active">Create Request</a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/validation/employeeRequest.js') }}"></script>
@stop

@section('content')
    <!-- Form inputs -->


    {!! Form::open([
        'route' => 'employeeRequest.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'employee_request_submit',
    ]) !!}

    @include('employeerequest::partial.action', ['btnType' => 'Save'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
