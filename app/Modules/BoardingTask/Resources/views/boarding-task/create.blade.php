@extends('admin::layout')
@section('title') Boarding Task @endSection  
@section('breadcrum')
<a href="{{route('boardingTask.index')}}" class="breadcrumb-item">Boarding Tasks</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'boardingTask.store','method'=>'POST','class'=>'form-horizontal','id'=>'boardingTaskFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('boardingtask::boarding-task.partial.action',['btnType'=>'Save Record']) 
    
    {!! Form::close() !!}

@endSection