@extends('admin::layout')
@section('title') Leave Type @endSection
@section('breadcrum')
<a href="{{route('leaveType.index')}}" class="breadcrumb-item">Leave Types</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'leaveType.store','method'=>'POST','class'=>'form-horizontal','id'=>'leaveTypeFormSubmit','role'=>'form','files' => true]) !!}

        @include('leave::leave-type.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
