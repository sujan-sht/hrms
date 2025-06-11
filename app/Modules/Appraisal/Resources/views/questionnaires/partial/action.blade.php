<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="form-group row">
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Form Name :<span class="text-danger"> *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('title', null, ['placeholder' => 'Enter Title', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('title'))
                                    <div class="error text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Type :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('form', [1=>'Part 1'], null, ['class'=>'form-control select-search']) !!}

                                </div>
                                <div class="error text-danger" id="msg"></div>
                                @if ($errors->has('form'))
                                    <div class="error text-danger">{{ $errors->first('form') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Competency Library :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('competency_library_id', $competency_libraries, null, [
                                        'placeholder' => 'Choose Competency Library',
                                        'class' => 'form-control',
                                        'autocomplete' => 'off',
                                        'required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('competency_library_id'))
                                    <div class="error text-danger">{{ $errors->first('competency_library_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Competencies :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select(
                                        'competency_ids[]',
                                        $competencies,
                                        $value = $is_edit ? json_decode($questionnaire->competency_ids) : null,
                                        [
                                            'id' => 'competenciesData',
                                            'class' => 'example-optgroup-limit competenciesData form-control multiselect-filtering',
                                            'multiple',
                                        ],
                                    ) !!}
                                </div>
                                <div class="error text-danger" id="msg"></div>
                                @if ($errors->has('competency_ids'))
                                    <div class="error text-danger">{{ $errors->first('competency_ids') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (setting('calendar_type') == 'BS')
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Roll Out Date:<span class="text-danger">*</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('roll_out_date', $value = null, [
                                        'id' => 'roll_out_date',
                                        'class' => 'form-control daterange-nep-single',
                                        'placeholder' => 'e.g: YYYY-MM-DD',

                                        // 'placeholder' => 'e.g : YYYY-MM-DD to YYYY-MM-DD',
                                        // 'class' => 'form-control daterange-buttons',
                                        // 'autocomplete' => 'off',
                                    ]) !!}
                                </div>
                                @if ($errors->has('roll_out_date'))
                                    <div class="error text-danger">{{ $errors->first('roll_out_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Roll Out Date:<span class="text-danger">*</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('roll_out_date', $value = null, [
                                        'id' => 'roll_out_date',
                                        'class' => 'form-control daterange-single',
                                        'placeholder' => 'e.g: YYYY-MM-DD',

                                        // 'placeholder' => 'e.g : YYYY-MM-DD to YYYY-MM-DD',
                                        // 'class' => 'form-control daterange-buttons',
                                        // 'autocomplete' => 'off',
                                    ]) !!}
                                </div>
                                @if ($errors->has('roll_out_date'))
                                    <div class="error text-danger">{{ $errors->first('roll_out_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- <div class="col-lg-6 ">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Start Date:<span class="text-danger">*</span></label>

                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('event_start_date', $value = null, [
                                        'id' => 'event_start_date',
                                        'class' => 'form-control daterange-single',
                                        'placeholder' => 'e.g: YYYY-MM-DD',

                                        // 'placeholder' => 'e.g : YYYY-MM-DD to YYYY-MM-DD',
                                        // 'class' => 'form-control daterange-buttons',
                                        // 'autocomplete' => 'off',
                                    ]) !!}
                                </div>
                                @if ($errors->has('event_start_date'))
                                    <div class="error text-danger">{{ $errors->first('event_start_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

</div>

<div class="text-center">
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

<script>
    $(document).ready(function() {
        $('.formClass').on('submit', function(e) {

            // if ($("#competenciesData option:selected").length > 3) {
            //     $("#msg").text('select Max 3 option at a time');
            // e.preventDefault()

            // }

            if ($("#competenciesData option:selected").length < 3) {
                $("#msg").text('select Min 3 option at a time');
            e.preventDefault()


            }
            if ($("#competenciesData option:selected").length == 3) {
                return true;
            }

        })

        nepDatePicker('daterange-nep-single');

        function nepDatePicker(element) {
            var dobInput = $('.' + element);
            dobInput.nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 10
            });
        }

    });
</script>

<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
