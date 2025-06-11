@extends('admin::layout')
@section('title') Substitute Leave @endSection
@section('breadcrum')
    <a href="{{ route('substituteLeave.index') }}" class="breadcrumb-item">Substitute Leaves</a>
    <a class="breadcrumb-item active">Create</a>
@endSection

@section('content')

    {!! Form::open(['route'=>'substituteLeave.store','method'=>'POST','class'=>'form-horizontal','id'=>'employeeSubstituteLeaveFormSubmit','role'=>'form','files' => true]) !!}
    @php
        if(setting('two_step_substitute_leave') == 11){
            $btnType='Request Now';
        }else{
            $btnType='Claim Now';
        }
    @endphp

    @include('employee::employee-substitute-leave.partial.action',['btnType'=>$btnType])

    {!! Form::close() !!}

@endSection
