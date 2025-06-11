@extends('admin::layout')
@section('title') Competence @endSection

@section('breadcrum')
    <a href="{{ route('competence.index') }}" class="breadcrumb-item">Competence</a>
    <a class="breadcrumb-item active">Create</a>
@endsection

@section('content')

    {!! Form::open(['route'=>'competence.store','method'=>'POST','class'=>'form-horizontal','id'=>'competenceFormSubmit','role'=>'form','files' => false]) !!}

        @include('appraisal::competency.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
