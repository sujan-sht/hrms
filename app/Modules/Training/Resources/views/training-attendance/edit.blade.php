@extends('admin::layout')
@section('title') Training Attendee @endSection
@section('breadcrum')
    <a href="{{route('training-attendance.index', $trainingModel->id)}}" class="breadcrumb-item">Training Attendees</a>
    <a class="breadcrumb-item active">Edit</a>
@endSection

@section('content')

<div class="card">
    <div class="card-body">

        {!! Form::model($trainingAttendanceModel,['method'=>'PUT','route'=>['training-attendance.update',['training_id'=>$trainingModel->id, 'id'=>$trainingAttendanceModel->id]],'class'=>'form-horizontal','id'=>'trainingAttendanceFormSubmit','role'=>'form','files'=>true]) !!}

            @include('training::training-attendance.partial.action',['btnType'=>'Update Record'])

        {!! Form::close() !!}

    </div>
</div>

@endSection
