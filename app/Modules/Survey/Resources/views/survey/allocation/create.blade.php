@extends('admin::layout')
@section('title') Surveys @endSection
@section('breadcrum')
<a href="{{ route('survey.index') }}" class="breadcrumb-item">Surveys</a>
<a class="breadcrumb-item active">Allocate</a>
@stop

@section('content')

{!! Form::open([
    'route' => 'survey.allocate',
    'method' => 'POST',
    'class' => 'form-horizontal',
    'id' => 'surveyAllocateFormSubmit',
    'role' => 'form',
    'files' => true,
]) !!}

{!! Form::hidden('survey_id', $survey_id, []) !!}

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Allocate Survey</legend>
                <div class="form-group row">
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Organization : <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    {!! Form::select('organization_ids[]', $organizationList, isset($_GET['organization_ids']) ?? null, [
                                        'class' => 'form-control multiselect-select-all-filtering ',
                                        'multiple' => 'multiple',
                                    ]) !!}
                                </div>
                                @if ($errors->has('organization_ids'))
                                    <div class="error text-danger">{{ $errors->first('organization_ids') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Choose Option : <span
                                    class="text-danger">*</span></label>


                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <div class="p-1 rounded">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            {{ Form::radio('type', 1, '', ['class' => 'custom-control-input', 'id' => 'department']) }}

                                            <label class="custom-control-label" for="department">Sub-Function</label>
                                        </div>

                                        <div class="custom-control custom-radio custom-control-inline">
                                            {{ Form::radio('type', 2, '', ['class' => 'custom-control-input', 'id' => 'level']) }}

                                            <label class="custom-control-label" for="level">Grade</label>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('type'))
                                    <span class="text-danger">{{ $errors->first('type') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3 departmentDiv d-none">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Sub-Function : <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    {!! Form::select('department_ids[]', $departmentList, isset($_GET['department_ids']) ?? null, [
                                        'class' => 'form-control multiselect-select-all-filtering',
                                        'multiple' => 'multiple',
                                    ]) !!}
                                </div>
                                @if ($errors->has('department_ids'))
                                    <div class="error text-danger">{{ $errors->first('department_ids') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3 levelDiv d-none">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Grade : <span class="text-danger">*</span></label>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    {!! Form::select('level_ids[]', $levelList, isset($_GET['level_ids']) ?? null, [
                                        'class' => 'form-control multiselect-select-all-filtering',
                                        'multiple' => 'multiple',
                                    ]) !!}
                                </div>
                                @if ($errors->has('level_ids'))
                                    <div class="error text-danger">{{ $errors->first('level_ids') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>

    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>Save Record</button>
</div>

{!! Form::close() !!}

@endSection

@section('script')
<script>
    $(document).ready(function() {
        $('input[type=radio][name=type]').change(function() {
            if ($(this).val() == 1) {
                $('.departmentDiv').removeClass('d-none')
                $('.levelDiv').addClass('d-none')
            } else if ($(this).val() == 2) {
                $('.departmentDiv').addClass('d-none')
                $('.levelDiv').removeClass('d-none')
            }
        })
    })
</script>
<script src="{{ asset('admin/validation/survey-allocate.js') }}"></script>

<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>

<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
@endsection
