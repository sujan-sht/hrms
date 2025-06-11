@extends('admin::layout')

@section('title')
    Create Force Leave Setup
@endsection

@section('breadcrum')
<a href="{{ route('forceLeaveSetup.index') }}" class="breadcrumb-item">Force Leave Setup </a>
<a class="breadcrumb-item active"> Create </a>
@endsection

@section('content')

    {!! Form::open(['route'=>'forceLeaveSetup.store','method'=>'POST','class'=>'form-horizontal','id'=>'forceLeaveSetupFormSubmit','role'=>'form','files' => true]) !!}

        @include('setting::force-leave-setup.partial.action', ['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endsection