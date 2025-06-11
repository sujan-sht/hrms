@extends('admin::layout')
@section('title') Appraisal @endSection

@section('breadcrum')
    <a href="{{ route('appraisal.index') }}" class="breadcrumb-item">Appraisal</a>
    <a class="breadcrumb-item active">Create</a>
@endsection

@section('content')

    {!! Form::open(['route'=>'appraisal.store','method'=>'POST','class'=>'form-horizontal','id'=>'appraisalFormSubmit','role'=>'form','files' => false]) !!}

        @include('appraisal::appraisal-management.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
