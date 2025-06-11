@extends('admin::layout')
@section('title') Leave Deduction Setup @stop
@section('breadcrum')
    <a href="{{ route('leaveDeductionSetup.index') }}" class="breadcrumb-item">Leave Deduction Setup</a>
    <a class="breadcrumb-item active">Edit</a>
@endsection

@section('content')

    <!-- Form inputs -->

    {!! Form::model($leaveDeductionSetupModel, [
        'route' => ['leaveDeductionSetup.update', $leaveDeductionSetupModel->id],
        'method' => 'PUT',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'leaveDeductionSetupSubmit',
    ]) !!}

    @include('setting::leave-deduction-setup.partial.action', ['btnType' => 'Update'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
