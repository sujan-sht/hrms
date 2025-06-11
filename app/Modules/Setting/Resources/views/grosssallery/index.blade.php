@extends('admin::layout')
@section('title') Setting Gross Salary Setup @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Setting  Gross Salary Setup</a>
@endsection

@section('script')
    <!-- Theme JS files -->
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <!-- /theme JS files -->
    <script src="{{ asset('admin/validation/setting.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/editor_ckeditor_default.js') }}"></script>
    <script src="{{ asset('admin/validation/setting.js') }}"></script>

    

@endsection
@section('content')

<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to change the synchronization setting?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmButton">Confirm</button>
            </div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-12">
            <div class="card bd-card">
                <div class="card-body">
                        {!! Form::open([
                            'route' => 'gross-salary.store',
                            'id' => 'setting_submit',
                            'method' => 'POST',
                            'class' => 'form-horizontal',
                            'role' => 'form',
                            'files' => true,
                        ]) !!}

                    <fieldset class="mb-1">

                        <legend class="text-uppercase font-size-sm font-weight-bold">Gross Salary Setup</legend>

                        <div class="form-group row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <label class="col-form-label col-lg-4">Setup Type: <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                        @foreach($types as $key=>$type)
                                            <div class="input-group">
                                                <input type="radio" name="gross_salary_type" value="{{$type}}" {{@$grossSalarySetupSetting->gross_salary_type==$type ? 'checked' :''}} class = "form-check-input" required>

                                                {!! Form::label('company_type_small',$key, ['class' => 'form-check-label']) !!}
                                                <br>
                                            </div>
                                        @endforeach
                                        @error('allowance_type')
                                            <p class="text-danger">{{@$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>


                    </fieldset>

                    <div class="text-center">
                        <button type="submit" class="ml-2 text-white btn bg-pink btn-labeled btn-labeled-left"><b><i
                                    class="icon-database-insert"></i></b> Changes</button>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>


        </div>
    </div>


@stop
