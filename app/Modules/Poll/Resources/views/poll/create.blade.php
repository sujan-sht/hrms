@extends('admin::layout')
@section('title') Polls @endSection
@section('breadcrum')
<a href="{{ route('poll.index') }}" class="breadcrumb-item">Polls</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')
{!! Form::open([
    'route' => 'poll.store',
    'method' => 'POST',
    'class' => 'form-horizontal',
    'id' => 'pollFormSubmit',
    'role' => 'form',
    'files' => true,
]) !!}

@include('poll::poll.partial.action', ['btnType' => 'Save Record'])

{!! Form::close() !!}
@endSection
