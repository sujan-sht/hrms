@extends('admin::layout')
@section('title')Create Loan @stop
@section('breadcrum')
    <a href="{{ route('loan.index') }}" class="breadcrumb-item">All Loans</a>
    <a class="breadcrumb-item active">Create</a>
@stop

@section('script')
    <script></script>
@stop

@section('content')
    {!! Form::open([
        'route' => 'loan.store',
        'method' => 'POST',
        'id' => 'staff-loan',
        'class' => 'form-horizontal eventForm',
        'role' => 'form',
        'files' => true,
    ]) !!}
    @include('loan::partial.loan-form')
    {!! Form::close() !!}
@stop
