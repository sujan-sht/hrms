@extends('admin::layout')

@section('title')
    Edit Overtime Request
@endsection

@section('breadcrum')
<a href="{{ route('overtimeRequest.index') }}" class="breadcrumb-item">Overtime Request</a>
<a class="breadcrumb-item active"> Edit </a>
@endsection

@section('content')

    {!! Form::model($overtimeRequest,['route'=>['overtimeRequest.update',$overtimeRequest->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'overtimeRequestFormSubmit','role'=>'form','files' => true]) !!}

        @include('overtimerequest::overtime-request.partial.action', ['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endsection
