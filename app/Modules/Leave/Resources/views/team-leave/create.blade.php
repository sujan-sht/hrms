@extends('admin::layout')
@section('title') Leave @endSection
@section('breadcrum')
<a href="{{route('leave.index')}}" class="breadcrumb-item">Leaves</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'leave.teamRequestStore','method'=>'POST','class'=>'form-horizontal','id'=>'leaveFormSubmit','role'=>'form','files' => true]) !!}

        @include('leave::team-leave.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
