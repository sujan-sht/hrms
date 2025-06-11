@extends('admin::layout')

@section('title')
    Create Travel Request
@endsection

@section('breadcrum')
<a href="{{ route('businessTrip.index') }}" class="breadcrumb-item">Travel Request </a>
<a class="breadcrumb-item active"> Create </a>
@endsection

@section('content')

    {!! Form::open(['route'=>'businessTrip.store','method'=>'POST','class'=>'form-horizontal','id'=>'businessTripFormSubmit','role'=>'form','files' => true]) !!}

        @include('businesstrip::business-trip.partial.action', ['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endsection