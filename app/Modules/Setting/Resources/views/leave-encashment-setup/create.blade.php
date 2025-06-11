@extends('admin::layout')

@section('title')
    Create Leave Encashment Setup
@endsection

@section('breadcrum')
<a href="{{ route('leaveEncashmentSetup.index') }}" class="breadcrumb-item">Leave Encashment Setup </a>
<a class="breadcrumb-item active"> Create </a>
@endsection

@section('content')

    {!! Form::open(['route'=>'leaveEncashmentSetup.store','method'=>'POST','class'=>'form-horizontal','id'=>'leaveEncashmentSetupFormSubmit','role'=>'form','files' => true]) !!}

        @include('setting::leave-encashment-setup.partial.action', ['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endsection