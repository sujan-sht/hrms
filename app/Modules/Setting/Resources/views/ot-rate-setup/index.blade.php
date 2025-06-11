@extends('admin::layout')
@section('title') OT Rate Setup @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Setting</a>
<a class="breadcrumb-item active">OT Rate Setup</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

@include('setting::ot-rate-setup.partial.filter')

@if (request()->get('organization_id'))
@if ($is_edit)
{!! Form::model($otRateSetupModel, [
    'method' => 'PUT',
    'route' => ['otRateSetup.update'],
    'class' => 'form-horizontal',
    'id' => 'setting_submit',
    'role' => 'form',
    'files' => true,
]) !!}
@else
{!! Form::open([
    'route' => 'otRateSetup.store',
    'method' => 'POST',
    'class' => 'form-horizontal',
    'id' => '',
    'role' => 'form',
    'files' => true,
]) !!}
@endif

    @include('setting::ot-rate-setup.partial.action')
    {{-- {!! Form::hidden('organization_id', request()->get('organization_id')) !!} --}}

    {!! Form::close() !!}
@endif
@endsection
