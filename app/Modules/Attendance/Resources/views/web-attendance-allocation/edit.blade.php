@extends('admin::layout')
@section('title') Edit @stop

@section('breadcrum')
    <a href="{{ route('webAttendance.allocationList') }}" class="breadcrumb-item">Restrictions</a>
    <a class="breadcrumb-item active">Edit</a>
@endsection

@section('content')
    {!! Form::model($webAtdAllocation,['route'=>['webAttendance.updateAllocation',['id'=>$webAtdAllocation->id]],'method'=>'PUT','class'=>'form-horizontal','id'=>'webAtdAllocateFormSubmit','role'=>'form','files' => true]) !!}

        @include('attendance::web-attendance-allocation.partial.action', ['btnType'=>'Update Record'])

    {!! Form::close() !!}
@stop
