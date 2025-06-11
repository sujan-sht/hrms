@extends('admin::layout')
@section('title')App Module @stop
@section('breadcrum')
    <a class="breadcrumb-item active">App Module</a>
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
                        'route' => 'module.apiModuleUpdate',
                        'id' => 'setting_submit',
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'files' => true,
                    ]) !!}


                    <fieldset class="mb-1">

                        <div class="row">
                            <legend class="text-uppercase font-size-sm font-weight-bold">Modules</legend>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($modules as $key => $module)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $module->name }}</td>
                                                    <td>
                                
                                                        <div class="custom-control custom-control-{{ (in_array($module->name, $mandatory_modules)?'teal':'success')}} custom-checkbox mb-2">
                                                            @if (in_array($module->name, $mandatory_modules))
                                                                <input name="modules[{{ $module->name }}]" type="hidden" value="1" />
                                                            @endif
                                                            <input name="modules[{{ $module->name }}]" type="checkbox" class="custom-control-input"
                                                                id="{{ $module->name }}" value="1"
                                                                {{ $module->app_status == 1 ? 'checked' : '' }}
                                                                {{ in_array($module->name, $mandatory_modules) ? 'checked disabled' : '' }}>
                                                            <label class="custom-control-label" for="{{ $module->name }}"></label>
                                                        </div>
                                                    </td>
                                
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
