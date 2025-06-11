@extends('admin::layout')
@section('title') Resignations @endSection  
@section('breadcrum')
<a href="{{route('resignation.index')}}" class="breadcrumb-item">Resignations</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        {!! Form::model($resignationModel,['method'=>'PUT','route'=>['resignation.update',$resignationModel->id],'class'=>'form-horizontal','id'=>'resignationFormSubmit','role'=>'form','files'=>true]) !!} 
            
            @include('offboarding::resignation.partial.action',['btnType'=>'Update Record'])
            
        {!! Form::close() !!}

    </div>
</div>

@endSection