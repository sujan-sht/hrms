@extends('admin::layout')
@section('title') Competence Library @endSection

@section('breadcrum')
<a href="{{ route('competenceLibrary.index') }}" class="breadcrumb-item">Competence Library</a>
<a class="breadcrumb-item active">Edit</a>
@endsection

@section('content')

    {!! Form::model($competencyLibrary,['route'=>['competenceLibrary.update',$competencyLibrary->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'competenceLibraryFormSubmit','role'=>'form','files' => true]) !!}

        @include('appraisal::competency-library.partial.action',['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endSection
