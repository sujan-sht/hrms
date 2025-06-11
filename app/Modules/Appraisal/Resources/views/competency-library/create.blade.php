@extends('admin::layout')
@section('title') Competence Library @endSection

@section('breadcrum')
    <a href="{{ route('competenceLibrary.index') }}" class="breadcrumb-item">Competence Library</a>
    <a class="breadcrumb-item active">Create</a>
@endsection

@section('content')

    {!! Form::open(['route'=>'competenceLibrary.store','method'=>'POST','class'=>'form-horizontal','id'=>'competenceLibraryFormSubmit','role'=>'form','files' => false]) !!}

        @include('appraisal::competency-library.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
