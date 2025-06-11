@extends('admin::layout')
@section('title') Competence @endSection

@section('breadcrum')
<a href="{{ route('competence.index') }}" class="breadcrumb-item">Competence</a>
<a class="breadcrumb-item active">Edit</a>
@endsection

@section('content')

    {!! Form::model($competency,['route'=>['competence.update',$competency->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'competenceFormSubmit','role'=>'form','files' => true]) !!}

        @include('appraisal::competency.partial.action',['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endSection
