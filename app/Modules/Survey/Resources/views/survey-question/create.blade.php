@extends('admin::layout')
@section('title') Survey Questions @endSection
@section('breadcrum')
<a href="{{ route('surveyQuestion.index', $surveyModel->id) }}" class="breadcrumb-item">Survey Questions</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')
{!! Form::open([
    'route' => 'surveyQuestion.store',
    'method' => 'POST',
    'class' => 'form-horizontal',
    'id' => 'surveyQuestionFormSubmit',
    'role' => 'form',
    'files' => true,
]) !!}

@include('survey::survey-question.partial.action', ['btnType' => 'Save Record'])

{!! Form::close() !!}
@endSection
