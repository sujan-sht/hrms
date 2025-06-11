@extends('admin::layout')
@section('title') Leave Deduction Setup @stop
@section('breadcrum')
    <a href="{{ route('leaveDeductionSetup.index') }}" class="breadcrumb-item">Leave Deduction Setup</a>
    <a class="breadcrumb-item active">Create</a>
@endsection

@section('content')

    <!-- Form inputs -->

    {!! Form::open([
        'route' => 'leaveDeductionSetup.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'leaveDeductionSetupSubmit',
    ]) !!}

    @include('setting::leave-deduction-setup.partial.action', ['btnType' => 'Save'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
