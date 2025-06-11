<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Training Attendees Details</legend>
                <div class="form-group row">
                    <div class="col-lg-12 mb-3">
                        <div class="row">

                            <label class="col-form-label col-lg-2">Employee :<span class="text-danger"> *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                {!! Form::select('employees[]', $employeeList, $value = null, [
                                    'class' => 'form-control multiselect-select-all',
                                    'multiple' => 'multiple',
                                    'id' => 'employees',
                                ]) !!}
                                @if ($errors->has('employees'))
                                    <div class="error text-danger">{{ $errors->first('employees') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Attendees Lists</legend>
                <ul class="emp-lists">

                </ul>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <script src="{{ asset('admin/validation/training-participant.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#employees').on("change", function() {
                $('.emp-lists').empty();
                emp_name = $('#employees option:selected').toArray();
                emp_name.forEach(element => {
                    $('.emp-lists').append('<li>' + element.text + '</li>');
                });

            });

        });
    </script>
@endSection
