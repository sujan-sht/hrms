@extends('admin::layout')
@section('title')
    Organization
@endSection
@section('breadcrum')
    <a href="{{ route('organization.index') }}" class="breadcrumb-item">Organizations</a>
    <a class="breadcrumb-item active">Create</a>
@endSection

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::open([
                'route' => 'organization.store',
                'method' => 'POST',
                'class' => 'form-horizontal',
                'id' => 'organizationFormSubmit',
                'role' => 'form',
                'files' => true,
            ]) !!}

            @include('organization::organization.partial.action', ['btnType' => 'Save'])

            {!! Form::close() !!}
        </div>
    </div>


@endSection
