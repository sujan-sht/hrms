@extends('admin::layout')
@section('title') Edit @stop

@section('breadcrum')
    <a href="{{ route('geoFence.allocationList', $geofence_id) }}" class="breadcrumb-item">Allocations</a>
    <a class="breadcrumb-item active">Edit</a>
@endsection

@section('content')
    {!! Form::model($geoFenceAllocation,['route'=>['geoFence.updateAllocation',['geofence_id' => $geofence_id, 'id'=>$geoFenceAllocation->id]],'method'=>'PUT','class'=>'form-horizontal','id'=>'geofenceAllocateFormSubmit','role'=>'form','files' => true]) !!}

        @include('geofence::allocation.partial.action', ['btnType'=>'Update Record'])

    {!! Form::close() !!}
@stop
