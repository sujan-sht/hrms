@extends('admin::layout')
@section('title') Rating Scale @endSection

@section('breadcrum')
<a href="{{ route('ratingScale.index') }}" class="breadcrumb-item">Rating Scale</a>
<a class="breadcrumb-item active">Edit</a>
@endsection

@section('content')

    {!! Form::model($ratingScale,['route'=>['ratingScale.update',$ratingScale->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'ratingScaleFormSubmit','role'=>'form','files' => true]) !!}

        @include('appraisal::rating-scale.partial.action',['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endSection
