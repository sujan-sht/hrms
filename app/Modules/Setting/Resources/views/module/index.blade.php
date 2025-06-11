@extends('admin::layout')
@section('title') Module @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Module</a>
@endsection

@section('script')
    <!-- Theme JS files -->
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <!-- /theme JS files -->
    {{-- <script src="{{ asset('admin/validation/setting.js') }}"></script> --}}
@stop
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card bd-card">
                <div class="card-body">
                    {!! Form::open([
                        'route' => 'module.update',
                        'id' => 'setting_submit',
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'files' => true,
                    ]) !!}


                    <fieldset class="mb-1">

                        <div class="row">
                            <div class="col-lg-6">
                                <legend class="text-uppercase font-size-sm font-weight-bold">Active Module</legend>

                                @include('setting::module.partial.table', [
                                    'modules' => $active_modules,
                                ])
                            </div>
                            <div class="col-lg-6">
                                <legend class="text-uppercase font-size-sm font-weight-bold">In-Active Module</legend>
                                @include('setting::module.partial.table', [
                                    'modules' => $inactive_modules,
                                ])
                            </div>
                        </div>


                    </fieldset>


                    <div class="text-right">
                        <button type="submit"
                            class="ml-2 mt-2 text-white btn bg-success btn-labeled btn-labeled-left"><b><i
                                    class="icon-database-insert"></i></b>Update</button>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>


        </div>
    </div>


@stop
