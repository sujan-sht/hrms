@extends('admin::layout')
@section('title') Insurances @endSection
@section('breadcrum')
<a href="{{ route('insurance.index') }}" class="breadcrumb-item">Insurances</a>
@stop

@section('content')

{!! Form::open([
    'route' => ['insurance.update', $insurance->id],
    'method' => 'PUT',
    'class' => 'form-horizontal',
    'role' => 'form',
    'files' => true,
]) !!}
@include('insurance::partial.action', ['btnType' => 'Save Record'])
{!! Form::close() !!}
@endSection

@push('custom_script')
<script></script>
@endpush
