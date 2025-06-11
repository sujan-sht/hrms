@extends('admin::layout')
@section('title') Send Bulk Offer Letter @endSection
@section('breadcrum')
<a href="{{route('evaluation.index')}}" class="breadcrumb-item">Evaluations</a>
<a class="breadcrumb-item active">Bulk Offer Letter</a>
@stop

@section('content')

    {!! Form::open(['route'=>'evaluation.sendBulkOfferLetter','method'=>'POST','class'=>'form-horizontal','role'=>'form','files' => true]) !!}
        <input type="hidden" name="data" value="{{json_encode(request()->bulk)}}">
        @include('onboarding::evaluation.bulk-offer-letter.partial.action',['btnType'=>'Send Bulk Offer Letter'])

    {!! Form::close() !!}

@endSection
