@extends('admin::layout')
@section('title')Meeting Room @stop

@section('breadcrum')
    <a href="{{ route('meetingRoom.index') }}" class="breadcrumb-item">Meeting Room</a>
    <a class="breadcrumb-item active">Edit</a>
@endsection

@section('content')

     {!! Form::model($room, [
        'method' => 'PUT',
        'route' => ['meetingRoom.update', $room->id],
        'class' => 'form-horizontal',
        'role' => 'form',
        'files' => true,
    ]) !!}
    @include('meetingroom::partial.action', ['btnType' => 'Update'])
    {!! Form::close() !!}

@stop
