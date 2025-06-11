@extends('admin::layout')
@section('title') Training Participant @endSection
@section('breadcrum')
    <a href="{{route('training-participant.index', $training_id)}}" class="breadcrumb-item">Training Participants</a>
    <a class="breadcrumb-item active">Create</a>
@endSection

@section('content')

    {!! Form::open(['route'=>['training-participant.store', $training_id],'method'=>'POST','class'=>'form-horizontal','id'=>'trainingParticipantFormSubmit','role'=>'form','files' => true]) !!}

        @include('training::training-participant.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
