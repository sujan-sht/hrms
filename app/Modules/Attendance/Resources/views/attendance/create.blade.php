@extends('admin::layout')

@section('title')
    Create {{ $title }}
@endsection

@section('breadcrum')
    <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Dasboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('shift.index') }}">{{ $title }}</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')

    {!! Form::open(['route'=>'shift.store','method'=>'POST','class'=>'form-horizontal','id'=>'shiftFormSubmit','role'=>'form','files' => true]) !!}

        @include('shift::shift.partial.action', ['btnType'=>'Save Changes'])

    {!! Form::close() !!}

@endsection
