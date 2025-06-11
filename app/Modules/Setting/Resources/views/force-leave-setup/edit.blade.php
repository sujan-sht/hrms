@extends('admin::layout')

@section('title')
    Edit Force Leave Setup
@endsection

@section('breadcrum')
<a href="{{ route('forceLeaveSetup.index') }}" class="breadcrumb-item">Force Leave Setup</a>
<a class="breadcrumb-item active"> Edit </a>
@endsection

@section('content')
    {!! Form::model($forceLeaveSetup,['route'=>['forceLeaveSetup.update',$forceLeaveSetup->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'forceLeaveSetupFormSubmit','role'=>'form','files' => true]) !!}

        @include('setting::force-leave-setup.partial.action', ['btnType'=>'Update Record'])

    {!! Form::close() !!}
@endsection
