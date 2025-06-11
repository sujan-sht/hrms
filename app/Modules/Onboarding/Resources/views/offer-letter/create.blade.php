@extends('admin::layout')
@section('title') Offer Letters @endSection  
@section('breadcrum')
<a href="{{route('offerLetter.index')}}" class="breadcrumb-item">Offer Letters</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'offerLetter.store','method'=>'POST','class'=>'form-horizontal','id'=>'offerLetterFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('onboarding::offer-letter.partial.action',['btnType'=>'Save Record']) 
    
    {!! Form::close() !!}

@endSection