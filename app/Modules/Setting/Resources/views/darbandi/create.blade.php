@extends('admin::layout')
@section('title')Darbandi Setup @stop
@section('breadcrum')
    <a href="{{ route('darbandi.index') }}" class="breadcrumb-item">Darbandi Setup </a>
    <a class="breadcrumb-item active"> Add Darbandi </a>
@endsection

@section('content')
    <!-- Form inputs -->

    {!! Form::open([
        'route' => 'darbandi.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'darbandi_submit',
    ]) !!}

    @include('setting::darbandi.partial.action', ['btnType' => 'Save'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
