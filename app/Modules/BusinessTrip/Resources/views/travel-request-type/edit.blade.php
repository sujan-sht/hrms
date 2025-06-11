@extends('admin::layout')

@section('title')
    Edit Travel Request Type
@endsection

@section('breadcrum')
<a href="{{ route('travelRequestType.index') }}" class="breadcrumb-item">Travel Request Type</a>
<a class="breadcrumb-item active"> Edit </a>
@endsection

@section('content')

    {!! Form::model($travelRequestType,['route'=>['travelRequestType.update',$travelRequestType->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'travelRequestTypeFormSubmit','role'=>'form','files' => true]) !!}

        @include('businesstrip::travel-request-type.partial.action', ['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endsection
