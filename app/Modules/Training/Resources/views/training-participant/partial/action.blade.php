<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Training Participant Details</legend>
                <div class="form-group row">
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            {{-- <label class="col-form-label col-lg-4">Participant Name :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('participant_name', null, ['rows'=>5, 'placeholder'=>'Write Participant Name..', 'class'=>'form-control']) !!}
                                </div>
                                @if ($errors->has('participant_name'))
                                    <div class="error text-danger">{{ $errors->first('participant_name') }}</div>
                                @endif
                            </div> --}}

                            <label class="col-form-label col-lg-2">Employee :<span class="text-danger"> *</span></label>
                            <div class="col-lg-10">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-user-plus"></i></span>
                                    </span>
                                    {!! Form::select('employees[]', $employeeList, $value = null, [
                                        'class' => 'form-control multiselect-select-all-filtering',
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
                    {{-- <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Contact Number :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('contact_no', null, ['rows'=>5, 'placeholder'=>'Enter Contact Number..', 'class'=>'form-control numeric']) !!}
                                </div>
                                @if ($errors->has('contact_no'))
                                    <div class="error text-danger">{{ $errors->first('contact_no') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Email :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('email', null, ['rows'=>5, 'placeholder'=>'Write Email Here..', 'class'=>'form-control']) !!}
                                </div>
                                @if ($errors->has('email'))
                                    <div class="error text-danger">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="col-lg-8 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Remarks :<span class="text-danger"> *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('remarks', null, [
                                        'rows' => 5,
                                        'placeholder' => 'Write Remarks Here..',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                @if ($errors->has('remarks'))
                                    <div class="error text-danger">{{ $errors->first('remarks') }}</div>
                                @endif
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Employee Lists</legend>
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
