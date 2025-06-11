@extends('admin::layout')

@section('title')
    Edit Designation
@endsection

@section('breadcrum')
<a href="{{ route('designation.index') }}" class="breadcrumb-item">Designation</a>
<a class="breadcrumb-item active"> Edit </a>
@endsection

@section('content')

    {!! Form::model($designation,['route'=>['designation.update',$designation->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'designationFormSubmit','role'=>'form','files' => true]) !!}

        @include('setting::designation.partial.action', ['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endsection
