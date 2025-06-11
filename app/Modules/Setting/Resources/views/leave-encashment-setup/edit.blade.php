@extends('admin::layout')

@section('title')
    Edit Leave Encashment Setup
@endsection

@section('breadcrum')
<a href="{{ route('leaveEncashmentSetup.index') }}" class="breadcrumb-item">Leave Encashment Setup</a>
<a class="breadcrumb-item active"> Edit </a>
@endsection

@section('content')
    {!! Form::model($leaveEncashmentSetup,['route'=>['leaveEncashmentSetup.update',$leaveEncashmentSetup->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'leaveEncashmentSetupFormSubmit','role'=>'form','files' => true]) !!}

        @include('setting::leave-encashment-setup.partial.action', ['btnType'=>'Update Record'])

    {!! Form::close() !!}
@endsection
