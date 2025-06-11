@extends('admin::layout')
@section('title') Mass Increment @endSection
@section('breadcrum')
    <a class="breadcrumb-item">Payroll</a>
    <a href="{{route('incomeSetup.index')}}" class="breadcrumb-item">Mass Increment</a>
    <a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::model($employeeMassIncrement, [
        'method' => 'PUT',
        'route' => ['employeeMassIncrement.update',$employeeMassIncrement->id],
        'class' => 'form-horizontal',
        'id' => 'incomeSetupFormSubmit',
        'role' => 'form',
        'files' => true,
    ]) !!}
        @include('employeemassincrement::mass-increment.partial.actionEdit',['btnType'=>'Save Record'])

    {!! Form::close() !!}


@endSection