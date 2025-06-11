@extends('admin::layout')
@section('title') Questionnaires @endSection

@section('breadcrum')
    <a href="{{ route('questionnaire.index') }}" class="breadcrumb-item">Questionnaires</a>
    <a class="breadcrumb-item active">Create</a>
@endsection

@section('content')

    {!! Form::open(['route'=>'questionnaire.store','method'=>'POST','class'=>'formClass form-horizontal','id'=>'questionnaireFormSubmit','role'=>'form','files' => false]) !!}
        @include('appraisal::questionnaires.partial.action',['btnType'=>'Save Record'])
    {!! Form::close() !!}

@endSection
