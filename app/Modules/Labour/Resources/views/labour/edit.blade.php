@extends('admin::layout')
@section('title')Labour KYE @stop
@section('breadcrum')
    <a href="{{ route('labour.index') }}" class="breadcrumb-item">Labour KYE </a>
    <a class="breadcrumb-item active"> Edit</a>
@endsection

@section('content')
    <!-- Form inputs -->

    {!! Form::model($labour, [
        'route' => ['labour.update', $labour->id],
        'method' => 'PATCH',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'skill_submit',
        'files' => true,
    ]) !!}

    @include('labour::labour.partial.action', ['btnType' => 'Update Record'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
