@extends('admin::layout')
@section('title') Geofences @endSection
@section('breadcrum')
    <a href="{{ route('geoFence.allocationList', $geofence_id) }}" class="breadcrumb-item">Allocations</a>
    <a class="breadcrumb-item active">Create</a>
@stop

@section('content')
    {!! Form::open([
        'route' => ['geoFence.allocate', $geofence_id],
        'method' => 'POST',
        'class' => 'form-horizontal',
        'id' => 'geofenceAllocateFormSubmit',
        'role' => 'form',
        'files' => true,
    ]) !!}

        @include('geofence::allocation.partial.action', ['btnType'=>'Save Record'])

    {!! Form::close() !!}
@endSection