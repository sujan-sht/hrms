@extends('admin::layout')
@section('title') Survey Questions @endSection
@section('breadcrum')
<a href="{{ route('surveyQuestion.index', $surveyModel->id) }}" class="breadcrumb-item">Survey Questions</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')
{!! Form::model($surveyQuestionModel, [
    'method' => 'PUT',
    'route' => ['surveyQuestion.update', $surveyQuestionModel->id],
    'class' => 'form-horizontal',
    'id' => 'surveyQuestionFormSubmit',
    'role' => 'form',
    'files' => true,
]) !!}

@include('survey::survey-question.partial.action', ['btnType' => 'Update Record'])

{!! Form::close() !!}
@endSection
