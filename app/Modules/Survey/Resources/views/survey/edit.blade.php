@extends('admin::layout')
@section('title') Survey @endSection
@section('breadcrum')
<a href="{{ route('survey.index') }}" class="breadcrumb-item">Surveys</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

{!! Form::model($surveyModel, [
    'method' => 'PUT',
    'route' => ['survey.update', $surveyModel->id],
    'class' => 'form-horizontal',
    'id' => 'surveyFormSubmit',
    'role' => 'form',
    'files' => true,
]) !!}

    @include('survey::survey.partial.action', ['btnType' => 'Update Record'])
{!! Form::close() !!}

@endSection
