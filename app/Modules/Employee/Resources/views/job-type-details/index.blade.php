@extends('admin::layout')
@section('title') Job Detail @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Job Detail</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


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
                        @include('employee::job-type-details.partial.current_detail')
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header header-elements-inline text-light bg-secondary">
                        <h5 class="card-title">New Details</h5>
                    </div>
                    {!! Form::open([
                        'route' => 'employee.storeJobDetail',
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'id' => 'jobDetailFormSubmit',
                        'role' => 'form',
                        'files' => false,
                    ]) !!}
                    <div class="card-body card-temporary-address">
                        @include('employee::job-type-details.partial.new_detail')

                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                                    class="icon-database-insert"></i></b>Save Record</button>

                            {{-- <button type="button" class="btn btn-success btn-labeled btn-labeled-left" id="submitData"
                                data-employee_id={{ request()->employee_id }}><b><i
                                        class="icon-database-insert"></i></b>Save Record</button> --}}
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
    <script src="{{ asset('admin/validation/employee-job-detail.js') }}"></script>
@endSection
