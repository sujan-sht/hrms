{!! Form::open([
    'method' => 'POST',
    'class' => 'form-horizontal',
    'id' => 'fiscal-year-form-submit-ajax',
    'role' => 'form',
    'files' => true,
]) !!}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Create Fiscal Year</legend>
                <div class="form-group row">
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Nep Fiscal Year :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('fiscal_year', null, [
                                        'rows' => 5,
                                        'placeholder' => 'e.g: 2079/80',
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('fiscal_year'))
                                    <div class="error text-danger">{{ $errors->first('fiscal_year') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Eng Fiscal Year :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('fiscal_year_english', null, [
                                        'rows' => 5,
                                        'placeholder' => 'e.g: 2022/23',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                @if ($errors->has('fiscal_year'))
                                    <div class="error text-danger">{{ $errors->first('fiscal_year') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Nep Start Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('start_date', null, [
                                        'rows' => 5,
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control',
                                        'id' => 'nepali-datepicker-start-date',
                                        'readonly',
                                        'required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('start_date'))
                                    <div class="error text-danger">{{ $errors->first('start_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Eng Start Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('start_date_english', null, [
                                        'rows' => 5,
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control daterange-single date',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('start_date_english'))
                                    <div class="error text-danger">{{ $errors->first('start_date_english') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Nep End Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('end_date', null, [
                                        'rows' => 5,
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control ',
                                        'id' => 'nepali-datepicker-end-date',
                                        'readonly',
                                        'required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('end_date'))
                                    <div class="error text-danger">{{ $errors->first('end_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Eng End Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('end_date_english', null, [
                                        'rows' => 5,
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control daterange-single date',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('end_date_english'))
                                    <div class="error text-danger">{{ $errors->first('end_date_english') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Status :<span class="text-danger"> *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('status', [1 => 'Active', 0 => 'In-Active'], null, [
                                        'placeholder' => 'Select Status',
                                        'class' => 'form-control select-search',
                                        'required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('status'))
                                    <div class="error text-danger">{{ $errors->first('status') }}</div>
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
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>Create</button>
    <a href="{{ route('fiscalYearSetup.index') }}" class="btn btn-secondary text-white"><i class="icon-list"></i> View
        Fiscal Years</a>
</div>
{!! Form::close() !!}

<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('admin/validation/fiscalYearSetup.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/js/ajax-call.js') }}"></script>
<script type="text/javascript">
    window.onload = function() {
        var startDateInput = document.getElementById("nepali-datepicker-start-date");
        startDateInput.nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 10
        });
        var endDateInput = document.getElementById("nepali-datepicker-end-date");
        endDateInput.nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 10
        });
    };
</script>
<script>
    $(document).ready(function() {
        setupCSRFToken();

        $('#fiscal-year-form-submit-ajax').on('submit', function(e) {
            e.preventDefault();

            ajaxSubmit("#fiscal-year-form-submit-ajax",
                "{{ route('fiscalYearSetupAjax.store') }}");
        });

        //  ajax submit
        function ajaxSubmit(formId, routeURL) {
            var formData = new FormData();
            $.each($(formId).serializeArray(), function(key, value) {
                formData.append(value.name, value.value);
            });
            $.ajax({
                url: routeURL,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status) {
                        $('.icon-spinner').replaceWith('');
                        toastr.success(response.message, 'Success');
                    } else {
                        toastr.error(response.message, 'Error');
                    }
                    removeSpinnerLoading(formId);
                },
                error: function(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    toastr.error(response.message, 'Error');
                    removeSpinnerLoading(formId);
                },
                complete: function() {
                    $(formId)[0].reset();
                    removeSpinnerLoading(formId);
                }
            });
        }


        //  setup CSRF token
        function setupCSRFToken() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        // remove spinner
        function removeSpinnerLoading(that) {
            $(that).find('button[type=submit]')
                .attr('disabled', false).find('.spinner').remove();
        }

    });
</script>
