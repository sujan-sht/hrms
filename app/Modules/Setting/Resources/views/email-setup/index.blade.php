@extends('admin::layout')

@section('title')
    Email Setup
@endsection

@section('breadcrum')
    <a class="breadcrumb-item">Setting</a>
    <a class="breadcrumb-item active">Email Setup</a>
@endsection


@section('content')
    {!! Form::open([
        'route' => 'setting.storeEmailSetup',
        'method' => 'POST',
        'id' => '',
        'class' => 'form-horizontal',
        'role' => 'form',
    ]) !!}
    @include('setting::email-setup.partial.create', ['btnType' => 'Save Record'])

    {!! Form::close() !!}

    <div class="row">
        <div class="col-12">
            <ul class="pagination pagination-rounded justify-content-end mb-3">
            </ul>
        </div>
    </div>
@endsection
