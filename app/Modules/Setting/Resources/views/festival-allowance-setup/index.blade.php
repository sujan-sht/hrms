@extends('admin::layout')
@section('title') Festival Allowance Setup @endSection
@section('breadcrum')
<a class="breadcrumb-item">Setting</a>
<a href="{{ route('festivalAllowance.create') }}" class="breadcrumb-item">Festival Allowance Setup</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

@if ($is_edit)
    {!! Form::model($festivalAllowance, [
        'method' => 'PUT',
        'route' => ['festivalAllowance.update', $festivalAllowance->id],
        'class' => 'form-horizontal',
        'id' => 'setting_submit',
        'role' => 'form',
        'files' => true,
    ]) !!}
@else
    {!! Form::open([
        'route' => 'festivalAllowance.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'id' => 'incomeSetupFormSubmit',
        'role' => 'form',
        'files' => true,
    ]) !!}
@endif

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
            <div class="form-group row">

                <div class="col-lg-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Method :</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('method', [1 => 'Fixed', 2 => 'Percentage'], null, [
                                    'class' => 'form-control select-search chooseMethod',
                                ]) !!}
                            </div>
                            @if ($errors->has('method'))
                                <div class="error text-danger">{{ $errors->first('method') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-3 amountSection" style="display:none;">
                    <div class="row">
                        <label class="col-form-label col-lg-3">Amount :</label>
                        <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('amount', null, ['placeholder' => 'e.g. 2000', 'class' => 'form-control']) !!}
                            </div>
                            @if ($errors->has('amount'))
                                <div class="error text-danger">{{ $errors->first('amount') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-3 percentageSection" style="display:none;">
                    <div class="row">
                        <label class="col-form-label col-lg-3">Percentage :</label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="row">
                                <div class="input-group col-lg-4">
                                    {!! Form::text('percentage', null, ['placeholder' => 'e.g. 10.2', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('percentage'))
                                    <div class="error text-danger">{{ $errors->first('percentage') }}</div>
                                @endif
                                <div class="col-lg-2 col-form-label">
                                    <span> % of </span>
                                </div>
                                <div class="input-group col-lg-6">
                                    {!! Form::select('salary_type', [1 => 'Basic salary', 2 => 'Gross salary'], null, [
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>

                                @if ($errors->has('salary_type'))
                                    <div class="error text-danger">{{ $errors->first('salary_type') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-2">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Description:</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('description', null, [
                                    'placeholder' => 'Write here..',
                                    'class' => 'form-control basicTinymce',
                                    'id' => 'editor-full',
                                ]) !!}
                            </div>

                            @if ($errors->has('description'))
                                <div class="error text-danger">{{ $errors->first('description') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-2">
                    <div class="row">
                        <label class="col-form-label col-lg-3">Eligible Month :</label>
                        <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('eligible_month', null, ['placeholder' => 'e.g. 6', 'class' => 'form-control']) !!}
                            </div>
                            @if ($errors->has('eligible_month'))
                                <div class="error text-danger">{{ $errors->first('eligible_month') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Month :<span class="text-danger"> *</span></label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group engDiv">
                                {!! Form::select('month', $nepaliMonthList, request('month') ?? null, [
                                    'placeholder' => 'Select Month',
                                    'class' => 'form-control select-search',
                                ]) !!}
                            </div>
                            {{-- <div class="input-group nepDiv" style="display: none;">
                                        {!! Form::select('month', $nepaliMonthList, request('month') ?? null, ['id'=>'nepMonth', 'placeholder'=>'Select Month', 'class'=>'form-control select-search', 'disabled']) !!}
                                    </div> --}}
                            @if ($errors->has('month'))
                                <div class="error text-danger">{{ $errors->first('month') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            <div class="text-center">
                <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i
                            class="icon-database-insert"></i></b>{{ $btnType }}</button>
            </div>
        </div>
    </div>
</div>

@section('script')
    <script src="{{ asset('admin/validation/incomeSetup.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/editor_ckeditor_default.js') }}"></script>
    {{-- <script src="https://cdn.tiny.cloud/1/cjrqkjizx7e1ld0p8kcygaj4cvzc6drni6o4xl298c5hl9l1/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        $(document).ready(function() {
            tinymce.init({
                selector: 'textarea.basicTinymce',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                height: '250',
                width: '100%'
            });

            $('.chooseMethod').on('change', function() {
                let method = $(this).val();
                if (method == 1) {
                    $('.amountSection').show();
                    $('.percentageSection').hide();
                } else {
                    $('.amountSection').hide();
                    $('.percentageSection').show();
                }
            });
            $('.chooseMethod').trigger('change');
        });
    </script> --}}
@endSection

{!! Form::close() !!}

@endSection
