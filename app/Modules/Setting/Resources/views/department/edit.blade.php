@extends('admin::layout')

@section('title')
    Edit Sub-Function
@endsection

@section('breadcrum')
    <a href="{{ route('department.index') }}" class="breadcrumb-item">Sub-Function</a>
    <a class="breadcrumb-item active"> Edit </a>
@endsection

@section('content')
    {!! Form::model($department, [
        'route' => ['department.update', $department->id],
        'method' => 'PUT',
        'class' => 'form-horizontal',
        'id' => 'departmentFormSubmit',
        'role' => 'form',
        'files' => true,
    ]) !!}

    @include('setting::department.partial.action', ['btnType' => 'Update Record'])

    {!! Form::close() !!}
@endsection
