@extends('admin::layout')
@section('title') Insurance Type @endSection
@section('breadcrum')
<a href="{{ route('insurance.type.index') }}" class="breadcrumb-item">Insurance Types</a>
@stop

@section('content')
@if (isset($insuranceType) && !is_null($insuranceType))
    {!! Form::open([
        'route' => ['insurance.type.update', $insuranceType->id],
        'method' => 'PUT',
        'class' => 'form-horizontal',
        'role' => 'form',
        'files' => true,
    ]) !!}
@else
    {!! Form::open([
        'route' => 'insurance.type.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'id' => 'insuranceTypeFormSubmit',
        'role' => 'form',
        'files' => true,
    ]) !!}
@endif
@include('insurance::insurance.partial.type-action', ['btnType' => 'Save Record'])
{!! Form::close() !!}
@endSection

@push('custom_script')
<script></script>
@endpush
