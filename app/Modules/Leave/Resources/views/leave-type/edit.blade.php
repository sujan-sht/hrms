@extends('admin::layout')
@section('title') Leave Type @endSection
@section('breadcrum')
<a href="{{route('leaveType.index')}}" class="breadcrumb-item">Leave Types</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

    {!! Form::model($leaveTypeModel,['method'=>'PUT','route'=>['leaveType.update',$leaveTypeModel->id],'class'=>'form-horizontal','id'=>'leaveTypeFormSubmit','role'=>'form','files'=>true]) !!}

        @include('leave::leave-type.partial.action',['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endSection
