@extends('admin::layout')
@section('title') Leave @endSection  
@section('breadcrum')
<a href="{{route('leave.index')}}" class="breadcrumb-item">Leaves</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        {!! Form::model($leaveModel,['method'=>'PUT','route'=>['leave.update',$leaveModel->id],'class'=>'form-horizontal','id'=>'leaveFormSubmit','role'=>'form','files'=>true]) !!} 
            
            @include('leave::leave.partial.action',['btnType'=>'Update Record'])
            
        {!! Form::close() !!}

    </div>
</div>

@endSection