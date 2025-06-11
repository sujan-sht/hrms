@extends('admin::layout')

@section('title')
    Edit {{ $title }}
@endsection

@section('breadcrum')
<a href="{{ route('shiftGroup.index') }}" class="breadcrumb-item">Shift Group</a>
<a class="breadcrumb-item active"> Edit </a>
@endsection

@section('content')

    {!! Form::model($shiftGroupModel,['method'=>'PUT','route'=>['shiftGroup.update',$shiftGroupModel->id],'class'=>'form-horizontal','id'=>'shiftGroupFormSubmit','role'=>'form','files'=>true]) !!}

        @include('shift::group.partial.action', ['btnType'=>'Save Changes'])

    {!! Form::close() !!}

@endsection
