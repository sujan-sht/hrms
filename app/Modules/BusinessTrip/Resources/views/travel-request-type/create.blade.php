@extends('admin::layout')

@section('title')
    Create Travel Request Type
@endsection

@section('breadcrum')
<a href="{{ route('travelRequestType.index') }}" class="breadcrumb-item">Travel Request Type</a>
<a class="breadcrumb-item active"> Create </a>
@endsection

@section('content')
    {!! Form::open(['route'=>'travelRequestType.store','method'=>'POST','class'=>'form-horizontal','id'=>'travelRequestTypeFormSubmit','role'=>'form']) !!}

        @include('businesstrip::travel-request-type.partial.action', ['btnType'=>'Save Record'])

    {!! Form::close() !!}
@endsection