
@extends('admin::layout')
@section('title') Create Fuel Consumption @endSection
@section('breadcrum')
<a href="{{ route('fuelConsumption') }}" class="breadcrumb-item">Create Fuel Consumption</a>
<a class="breadcrumb-item active">Create</a>
@endSection

@section('content')

{!! Form::open([
    'route' => 'fuelConsumption.store',
    'method' => 'POST',
    'class' => 'form-horizontal',
    'id' => 'fuelConsumption_submit',
    'role' => 'form',
]) !!}
@include('fuelconsumption::fuelConsumption.partial.action', ['btnType' => 'Save'])
{!! Form::close() !!}

@endSection
