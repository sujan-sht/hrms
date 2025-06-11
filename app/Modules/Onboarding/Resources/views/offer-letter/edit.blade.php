@extends('admin::layout')
@section('title') Offer Letters @endSection  
@section('breadcrum')
<a href="{{route('offerLetter.index')}}" class="breadcrumb-item">Offer Letters</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

    {!! Form::model($offerLetterModel,['method'=>'PUT','route'=>['offerLetter.update',$offerLetterModel->id],'class'=>'form-horizontal','id'=>'offerLetterFormSubmit','role'=>'form','files'=>true]) !!} 
        
        @include('onboarding::offer-letter.partial.action',['btnType'=>'Update Record'])
        
    {!! Form::close() !!}

@endSection