@extends('admin::layout')
@section('title') Clearances @endSection
@section('breadcrum')
<a href="{{route('interviewLevel.index')}}" class="breadcrumb-item">Clearances</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'clearance.store','method'=>'POST','class'=>'form-horizontal','id'=>'clearanceFormSubmit','role'=>'form','files' => true]) !!}

        @include('offboarding::clearance.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
