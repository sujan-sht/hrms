@extends('admin::layout')
@section('title') Offer Letters @endSection  
@section('breadcrum')
<a href="{{route('offerLetter.index')}}" class="breadcrumb-item">Offer Letters</a>
<a class="breadcrumb-item active">View</a>
@stop

@section('content')

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i class="icon-backward2"></i></b>Go Back</a>
</div>

@endSection