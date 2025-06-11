@extends('admin::layout')

@section('title')
    Create {{ $title }}
@endsection

@section('breadcrum')
<a href="{{ route('shift.index') }}" class="breadcrumb-item">Shift </a>
<a class="breadcrumb-item active"> Create </a>
@endsection

@section('content')

    {!! Form::open(['route'=>'shift.store','method'=>'POST','class'=>'form-horizontal','id'=>'shiftFormSubmit','role'=>'form','files' => true]) !!}

        @include('shift::shift.partial.action', ['btnType'=>'Save'])

    {!! Form::close() !!}

@endsection
