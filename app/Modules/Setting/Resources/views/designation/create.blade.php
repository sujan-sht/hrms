@extends('admin::layout')

@section('title')
    Create Designation
@endsection

@section('breadcrum')
<a href="{{ route('designation.index') }}" class="breadcrumb-item">Designation </a>
<a class="breadcrumb-item active"> Create </a>
@endsection

@section('content')

    {!! Form::open(['route'=>'designation.store','method'=>'POST','class'=>'form-horizontal','id'=>'designationFormSubmit','role'=>'form','files' => true]) !!}

        @include('setting::designation.partial.action', ['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endsection