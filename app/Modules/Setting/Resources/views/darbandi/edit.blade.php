@extends('admin::layout')
@section('title')Darbandi Setup @stop
@section('breadcrum')
    <a href="{{ route('darbandi.index') }}" class="breadcrumb-item">Darbandi Setup </a>
    <a class="breadcrumb-item active"> Edit Darbandi </a>
@endsection

@section('content')
    <!-- Form inputs -->


    {!! Form::model($darbandi, [
        'route' => ['darbandi.update', $darbandi->id],
        'method' => 'PUT',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'darbandi_submit',
    ]) !!}

    @include('setting::darbandi.partial.action', ['btnType' => 'Update'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
