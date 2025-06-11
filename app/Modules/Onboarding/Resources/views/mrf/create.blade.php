@extends('admin::layout')
@section('title') Manpower Requisition Forms @endSection  
@section('breadcrum')
<a href="{{route('mrf.index')}}" class="breadcrumb-item">Manpower Requisition Forms</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'mrf.store','method'=>'POST','class'=>'form-horizontal','id'=>'mrfFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('onboarding::mrf.partial.action',['btnType'=>'Save Record']) 
    
    {!! Form::close() !!}

@endSection