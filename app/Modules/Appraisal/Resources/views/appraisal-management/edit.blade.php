@extends('admin::layout')
@section('title') Appraisal @endSection

@section('breadcrum')
<a href="{{ route('appraisal.index') }}" class="breadcrumb-item">Appraisal</a>
<a class="breadcrumb-item active">Edit</a>
@endsection

@section('content')

    {!! Form::model($appraisal,['route'=>['appraisal.update',$appraisal->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'competenceFormSubmit','role'=>'form','files' => true]) !!}

        @include('appraisal::appraisal-management.partial.action',['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endSection
