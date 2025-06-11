@extends('admin::layout')
@section('title') Organization Code of Conduct @endSection
@section('breadcrum')
    <a href="{{ route('organization.index') }}" class="breadcrumb-item">Organizations</a>
    <a class="breadcrumb-item active">Code of Conduct</a>
@endSection

@section('content')

<div class="card">
    <div class="card-body">
        @if(isset($organizationModel->code_of_conduct))
            {!! $organizationModel->code_of_conduct !!}
        @endif
    </div>
</div>

@endSection