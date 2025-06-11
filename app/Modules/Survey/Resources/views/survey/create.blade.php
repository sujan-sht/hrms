@extends('admin::layout')
@section('title') Survey @endSection
@section('breadcrum')
<a href="{{route('survey.index')}}" class="breadcrumb-item">Surveys</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'survey.store','method'=>'POST','class'=>'form-horizontal','id'=>'surveyFormSubmit','role'=>'form','files' => true]) !!}
        @include('survey::survey.partial.action', ['btnType' => 'Save Record'])
    {!! Form::close() !!}
@endSection
