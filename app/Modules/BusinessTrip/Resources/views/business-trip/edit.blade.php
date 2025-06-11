@extends('admin::layout')

@section('title')
    Edit Travel Request
@endsection

@section('breadcrum')
<a href="{{ route('businessTrip.index') }}" class="breadcrumb-item">Travel Request</a>
<a class="breadcrumb-item active"> Edit </a>
@endsection

@section('content')

    {!! Form::model($businessTrip,['route'=>['businessTrip.update',$businessTrip->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'businessTripFormSubmit','role'=>'form','files' => true]) !!}

        @include('businesstrip::business-trip.partial.action', ['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endsection
