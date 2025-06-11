@extends('admin::layout')
@section('title') Tax Slab Setup @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Payroll</a>
<a class="breadcrumb-item active">Tax slab Setup</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

{{-- @include('payroll::tax-slab.partial.advance-filter') --}}

{{-- @if (request()->get('organization_id')) --}}
    {!! Form::open([
        'route' => 'taxSlab.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'id' => '',
        'role' => 'form',
        'files' => true,
    ]) !!}

    @include('payroll::tax-slab.partial.action', ['btnType' => 'Save Record'])
    {!! Form::hidden('organization_id', request()->get('organization_id')) !!}

    {!! Form::close() !!}
{{-- @endif --}}
@endsection


@section('script')
<script>
    $(document).ready(function() {

    });
</script>
@endSection
