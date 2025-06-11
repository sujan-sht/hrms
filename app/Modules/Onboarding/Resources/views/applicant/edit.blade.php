@extends('admin::layout')
@section('title') Applicants @endSection  
@section('breadcrum')
<a href="{{route('applicant.index')}}" class="breadcrumb-item">Applicants</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        {!! Form::model($applicantModel,['method'=>'PUT','route'=>['applicant.update',$applicantModel->id],'class'=>'form-horizontal','id'=>'applicantFormSubmit','role'=>'form','files'=>true]) !!} 
            
            @include('onboarding::applicant.partial.action',['btnType'=>'Update Record'])
            
        {!! Form::close() !!}

    </div>
</div>

@endSection