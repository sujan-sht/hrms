@extends('admin::layout')
@section('title') Payroll @endSection
@section('breadcrum')
<a href="{{ route('payroll.index') }}" class="breadcrumb-item">Payroll</a>
<a class="breadcrumb-item active">Create</a>
@stop
@section('content')

    {!! Form::open(['route'=>'payroll.store','method'=>'POST','class'=>'form-horizontal','id'=>'payrollFormSubmit','role'=>'form','files' => true]) !!}

        @include('payroll::payroll.partial.action',['btnType'=>'Generate'])

    {!! Form::close() !!}

@endSection
