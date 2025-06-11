@extends('admin::layout')
@section('title') MRF Approval Flow - Create @stop
@section('breadcrum')
    <a href="{{ route('mrfApprovalFlow.index') }}" class="breadcrumb-item">MRF Approval Flow</a>
    <a class="breadcrumb-item active">Create</a>
@endsection

@section('content')

    <!-- Form inputs -->

    {!! Form::open([
        'route' => 'mrfApprovalFlow.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'formSubmit',
    ]) !!}

    @include('setting::mrf-approval-flow.partial.action', ['btnType' => 'Save'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
