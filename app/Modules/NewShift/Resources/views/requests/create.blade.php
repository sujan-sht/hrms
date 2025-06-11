@extends('admin::layout')

@section('title')
    Create Request
@endsection

@section('breadcrum')
<a href="{{ route('rosterRequest.index') }}" class="breadcrumb-item">Requests </a>
<a class="breadcrumb-item active"> Create </a>
@endsection

@section('content')

    {!! Form::open(['route'=>'rosterRequest.store','method'=>'POST','class'=>'form-horizontal','id'=>'rosterRequestFormSubmit','role'=>'form','files' => true]) !!}

        @include('newshift::requests.partial.action', ['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endsection