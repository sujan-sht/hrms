@extends('admin::layout')

@section('title')
    {{ $title }}
@endsection

@section('breadcrum')
    <a class="breadcrumb-item">Business Trip</a>
    <a class="breadcrumb-item active">Allowance Setup</a>

@endsection

{{-- @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles') --}}

@section('content')
    @include('businesstrip::allowance-setup.partial.advance-filter')
    @if (request()->get('organization_id'))
            {!! Form::open([
                'route' => 'businessTrip.storeEmployeeAllowance',
                'method' => 'POST',
                'id' => '',
                'class' => 'form-horizontal',
                'role' => 'form',
            ]) !!}
            @include('businesstrip::allowance-setup.partial.create', ['btnType' => 'Save Record'])

            {!! Form::close() !!}

        <div class="row">
            <div class="col-12">
                <ul class="pagination pagination-rounded justify-content-end mb-3">
                </ul>
            </div>
        </div>
    @endif
@endsection
