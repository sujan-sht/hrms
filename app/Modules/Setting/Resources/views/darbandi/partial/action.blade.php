<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <fieldset class="mb-3">
                    {{-- <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend> --}}

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="col-form-label">Organization:<span class="text-danger">*</span></label>
                            <div class="form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {{-- <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-office"></i></span>
                                    </span> --}}
                                    {!! Form::select('organization_id', $organizations, null, [
                                        'id' => 'organization_id',
                                        'class' => 'form-control organization-filter2',
                                        'placeholder' => 'Choose Organization',
                                    ]) !!}
                                </div>
                                @if ($errors->has('organization_id'))
                                    <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label class="col-form-label">Designation:<span class="text-danger">*</span></label>
                            <div class="form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {{-- <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-office"></i></span>
                                    </span> --}}
                                    {!! Form::select('designation_id', $designationList, null, [
                                        'id' => 'designation_id',
                                        'class' => 'form-control designation-filter',
                                        'placeholder' => 'Choose Designation',
                                    ]) !!}
                                </div>
                                @if ($errors->has('designation_id'))
                                    <div class="error text-danger">{{ $errors->first('designation_id') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6 mt-2">
                            <label class="col-form-label">No. of Position:<span class="text-danger">*</span></label>
                            <div class="form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {{-- <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-office"></i></span>
                                    </span> --}}

                                    {!! Form::number('no', $darbandi->no ?? '', [
                                        'id' => 'no',
                                        'placeholder' => 'Enter no. of position required for this designation',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>

                                @if ($errors->has('no'))
                                    <div class="error text-danger">{{ $errors->first('no') }}</div>
                                @endif
                            </div>
                        </div>

                        {{-- <div class="col-lg-6">
                            <label class="col-form-label">Employee:</label>
                            <div class="form-group-feedback form-group-feedback-right">
                                <div class="input-group">

                                    <select name="employee_id[]" id="employee_id"
                                        class="employee_list form-control select-search multiselect-select-all-filtering"
                                        multiple data-focus>
                                        <option value='0'>Select Employee</option>
                                    </select>
                                </div>
                                @if ($errors->has('employee_id'))
                                    <div class="error text-danger">{{ $errors->first('employee_id') }}</div>
                                @endif
                                <div id="no-error" class="error text-danger"></div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-lg-6">
                            <label class="col-form-label">Sorting Order:<span class="text-danger">(Note:By default
                                    sorting order will be last)</span></label>
                            <div class="form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-office"></i></span>
                                    </span>

                                    {!! Form::number('sorting_order', $darbandi->sorting_order ?? '', [
                                        'id' => 'sorting_order',
                                        'placeholder' => 'Enter sorting order in darbandi report',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                @if ($errors->has('sorting_order'))
                                    <div class="error text-danger">{{ $errors->first('sorting_order') }}</div>
                                @endif
                            </div>
                        </div> --}}

                    </div>

                </fieldset>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

{{-- @section('script')
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#organization_id, #designation_id').on('change', function() {
                var designation_id = $('#designation_id').val();
                var organization_id = $('#organization_id').val();

                $.ajax({
                    type: 'GET',
                    url: '/admin/darbandi/getEmployee',
                    data: {
                        designation_id: designation_id,
                        organization_id: organization_id
                    },
                    success: function(data) {
                        $('.employee_list').html(data.employeedata);
                    }
                });

            });
            $('#darbandi_submit').on('submit', function(event) {
                var numberOfPositions = parseInt($('#no').val());
                var numberOfEmployeesSelected = $('#employee_id').val().length;

                if (numberOfEmployeesSelected > numberOfPositions) {
                    event.preventDefault();

                    $('#no-error').html(
                        'Number of selected employees cannot exceed the number of positions.');
                } else {
                    $('#no-error').html(
                        '');
                }
            });
        });
    </script>
@endsection --}}
