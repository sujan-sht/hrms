@extends('admin::layout')
@section('title')Province Setup @stop
@section('breadcrum')
    <a href="{{ route('province-setup.index') }}" class="breadcrumb-item">Province Setup </a>
    <a class="breadcrumb-item active"> Edit Province </a>
@endsection

@section('content')
    <!-- Form inputs -->


    {!! Form::model($province, [
        'route' => ['province-setup.update', $province->id],
        'method' => 'PUT',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'Province_submit',
    ]) !!}

    @include('setting::province-setup.partial.action', ['btnType' => 'Update'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
