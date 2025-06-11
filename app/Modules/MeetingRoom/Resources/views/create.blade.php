@extends('admin::layout')
@section('title')Meeting Room @stop

@section('breadcrum')
    <a href="{{ route('meetingRoom.index') }}" class="breadcrumb-item">Meeting Room</a>
    <a class="breadcrumb-item active">Create</a>
@endsection

@section('content')

    <!-- Form inputs -->

    {!! Form::open([
        'route' => 'meetingRoom.store',
        'method' => 'POST',
        'id' => 'meetingRoom_submit',
        'class' => 'form-horizontal',
        'role' => 'form',
        'files' => true,
    ]) !!}
    @include('meetingroom::partial.action', ['btnType' => 'Save'])
    {!! Form::close() !!}

@stop
