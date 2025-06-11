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
    @include('businesstrip::allowance-setup.partial.advance-filter-test')
            {!! Form::open([
                'route' => 'businessTrip.storeEmployeeAllowanceTest',
                'method' => 'POST',
                'id' => '',
                'class' => 'form-horizontal',
                'role' => 'form',
            ]) !!}
            @include('businesstrip::allowance-setup.partial.createtest', ['btnType' => 'Save Record'])
                <input type="text" hidden name="allowance_type" value="{{$travelAllowanceType->id}}">
            {!! Form::close() !!}

        <div class="row">
            <div class="col-12">
                <ul class="pagination pagination-rounded justify-content-end mb-3">
                </ul>
            </div>
        </div>
@endsection
