@extends('admin::layout')
@section('title') Training Attendee @endSection
@section('breadcrum')
    <a href="{{route('training-attendance.index', $trainingModel->id)}}" class="breadcrumb-item">Training Attendees</a>
    <a class="breadcrumb-item active">Create</a>
@endSection

@section('content')

    {!! Form::open(['route'=>['training-attendance.store', $trainingModel->id],'method'=>'POST','class'=>'form-horizontal','id'=>'trainingAttendanceFormSubmit','role'=>'form','files' => true]) !!}

        @include('training::training-attendance.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
