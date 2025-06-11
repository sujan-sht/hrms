@extends('admin::layout')

@section('title')
    Edit Request
@endsection

@section('breadcrum')
<a href="{{ route('rosterRequest.index') }}" class="breadcrumb-item">Request</a>
<a class="breadcrumb-item active"> Edit </a>
@endsection

@section('content')

    {!! Form::model($rosterRequest,['route'=>['rosterRequest.update',$rosterRequest->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'rosterRequestFormSubmit','role'=>'form','files' => true]) !!}

        @include('newshift::requests.partial.action', ['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endsection
