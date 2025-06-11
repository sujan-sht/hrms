@extends('admin::layout')

@section('title')
    Create Travel Expense
@endsection

@section('breadcrum')
<a href="{{ route('travelexpense.index') }}" class="breadcrumb-item">Travel Expense </a>
<a class="breadcrumb-item active"> Create </a>
@endsection

@section('content')

  {!! Form::model($travelExpense,['route'=>['travelexpense.update',$travelExpense->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'businessTripFormSubmit','role'=>'form','files' => true]) !!}

        @include('businesstrip::travelexpense.partial.action', ['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endsection
