@extends('admin::layout')
@section('title') Create @endSection
@section('breadcrum')
    <a href="{{ route('webAttendance.allocationList') }}" class="breadcrumb-item">Restrictions</a>
    <a class="breadcrumb-item active">Create</a>
@stop

@section('content')
    {!! Form::open([
        'route' => ['webAttendance.allocate'],
        'method' => 'POST',
        'class' => 'form-horizontal',
        'id' => 'webAtdAllocateFormSubmit',
        'role' => 'form',
        'files' => true,
    ]) !!}

        @include('attendance::web-attendance-allocation.partial.action', ['btnType'=>'Save Record'])

    {!! Form::close() !!}
@endSection