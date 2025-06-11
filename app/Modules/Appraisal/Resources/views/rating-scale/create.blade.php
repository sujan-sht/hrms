@extends('admin::layout')
@section('title') Rating Scale @endSection

@section('breadcrum')
    <a href="{{ route('ratingScale.index') }}" class="breadcrumb-item">Rating scale</a>
    <a class="breadcrumb-item active">Create</a>
@endsection

@section('content')

    {!! Form::open(['route'=>'ratingScale.store','method'=>'POST','class'=>'form-horizontal','id'=>'competenceLibraryFormSubmit','role'=>'form','files' => false]) !!}

        @include('appraisal::rating-scale.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
