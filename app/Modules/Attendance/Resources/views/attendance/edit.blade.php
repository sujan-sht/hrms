@extends('admin::layout')

@section('title')
    Edit {{ $title }}
@endsection

@section('breadcrum')
    <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Dasboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('shift.index') }}">{{ $title }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')

    {!! Form::model($shiftModel,['method'=>'PUT','route'=>['shift.update',$shiftModel->id],'class'=>'form-horizontal','id'=>'shiftFormSubmit','role'=>'form','files'=>true]) !!}

        @include('shift::shift.partial.action', ['btnType'=>'Save Changes'])

    {!! Form::close() !!}

@endsection
