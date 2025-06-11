@extends('admin::layout')
@section('title') Interviews @endSection  
@section('breadcrum')
<a href="{{route('interview.index')}}" class="breadcrumb-item">Interviews</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        {!! Form::model($interviewModel,['method'=>'PUT','route'=>['interview.update',$interviewModel->id],'class'=>'form-horizontal','id'=>'interviewFormSubmit','role'=>'form','files'=>true]) !!} 
            
            @include('onboarding::interview.partial.action',['btnType'=>'Update Record'])
            
        {!! Form::close() !!}

    </div>
</div>

@endSection