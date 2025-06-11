@extends('admin::layout')

@section('title')
    Create Sub-Function
@endsection

@section('breadcrum')
    <a href="{{ route('department.index') }}" class="breadcrumb-item">Sub-Function </a>
    <a class="breadcrumb-item active"> Create </a>
@endsection

@section('content')
    {!! Form::open([
        'route' => 'department.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'id' => 'departmentFormSubmit',
        'role' => 'form',
        'files' => true,
    ]) !!}

    @include('setting::department.partial.action', ['btnType' => 'Save Record'])

    {!! Form::close() !!}
@endsection
