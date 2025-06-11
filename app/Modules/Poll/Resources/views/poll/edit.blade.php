@extends('admin::layout')
@section('title') Polls @endSection
@section('breadcrum')
<a href="{{ route('poll.index') }}" class="breadcrumb-item">Polls</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')
{!! Form::model($pollModel, [
    'method' => 'PUT',
    'route' => ['poll.update', $pollModel->id],
    'class' => 'form-horizontal',
    'id' => 'pollFormSubmit',
    'role' => 'form',
    'files' => true,
]) !!}

@include('poll::poll.partial.action', ['btnType' => 'Update Record'])

{!! Form::close() !!}
@endSection
