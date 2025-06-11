
@extends('admin::layout')
@section('title') Clearances @endSection
@section('breadcrum')
<a href="{{route('clearance.index')}}" class="breadcrumb-item">Clearances</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        {!! Form::model($clearanceModel,['method'=>'PUT','route'=>['clearance.update',$clearanceModel->id],'class'=>'form-horizontal','id'=>'clearanceFormSubmit','role'=>'form','files'=>true]) !!}

            @include('offboarding::clearance.partial.action',['btnType'=>'Update Record'])

        {!! Form::close() !!}

    </div>
</div>

@endSection
