@extends('admin::layout')
@section('title') MRF Approval Flow - Edit @stop
@section('breadcrum')
    <a href="{{ route('mrfApprovalFlow.index') }}" class="breadcrumb-item">MRF Approval Flow</a>
    <a class="breadcrumb-item active">Edit</a>
@endsection

@section('content')

    <!-- Form inputs -->

    {!! Form::model($mrfApprovalFlowModel, [
        'route' => ['mrfApprovalFlow.update', $mrfApprovalFlowModel->id],
        'method' => 'PUT',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'formSubmit',
    ]) !!}

    @include('setting::mrf-approval-flow.partial.action', ['btnType' => 'Update'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
