@extends('admin::layout')
@section('title') Onboard @endSection  
@section('breadcrum')
<a href="{{route('onboard.index')}}" class="breadcrumb-item">Onboards</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

    {!! Form::model($onboardModel,['method'=>'PUT','route'=>['onboard.update'],'class'=>'form-horizontal','id'=>'onboardFormSubmit','role'=>'form','files'=>true]) !!} 
        
        @include('onboarding::onboard.partial.action',['btnType'=>'Update Record'])
        
    {!! Form::close() !!}

@endSection