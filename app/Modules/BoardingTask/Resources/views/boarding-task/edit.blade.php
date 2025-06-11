@extends('admin::layout')
@section('title') Boarding Task @endSection  
@section('breadcrum')
<a href="{{route('boardingTask.index')}}" class="breadcrumb-item">Boarding Tasks</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

    {!! Form::model($boardingTaskModel,['method'=>'PUT','route'=>['boardingTask.update',$boardingTaskModel->id],'class'=>'form-horizontal','id'=>'boardingTaskFormSubmit','role'=>'form','files'=>true]) !!} 
        
        @include('boardingtask::boarding-task.partial.action',['btnType'=>'Update Record'])
        
    {!! Form::close() !!}

@endSection