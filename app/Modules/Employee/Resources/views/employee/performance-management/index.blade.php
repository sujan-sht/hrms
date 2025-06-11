@extends('admin::layout')
@section('title') Performance Management @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Performance Management</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('employee::employee.performance-management.partial.advance-filter', ['route' => route('employee.viewPerformanceManagement')])

@if (!empty($employee))
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements-inline text-light bg-secondary">
                    <h5 class="card-title">Current Details</h5>
                    <div class="header-elements">

                    </div>
                </div>

                <div class="card-body">
                    @include('employee::employee.performance-management.partial.current_details')
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements-inline text-light bg-secondary">
                    <h5 class="card-title">New Details</h5>
                </div>
                {!! Form::open(['route'=>'employee.storePerformanceDetails','method'=>'POST','class'=>'form-horizontal','id'=>'performanceDetailFormSubmit','role'=>'form','files' => false]) !!}
                    <div class="card-body card-temporary-address">
                        @include('employee::employee.performance-management.partial.new_details')

                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                                class="icon-database-insert"></i></b>Save Record</button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endif



@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="{{asset('admin/validation/performance-detail.js')}}"></script>
@endSection
