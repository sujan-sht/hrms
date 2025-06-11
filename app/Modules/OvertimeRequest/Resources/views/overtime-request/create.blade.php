@extends('admin::layout')

@section('title')
    Create Overtime Request
@endsection

@section('breadcrum')
<a href="{{ route('overtimeRequest.index') }}" class="breadcrumb-item">Overtime Request </a>
<a class="breadcrumb-item active"> Create </a>
@endsection

@section('content')

    {!! Form::open(['route'=>'overtimeRequest.store','method'=>'POST','class'=>'form-horizontal','id'=>'overtimeRequestFormSubmit','role'=>'form','files' => true]) !!}

        @include('overtimerequest::overtime-request.partial.action', ['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endsection