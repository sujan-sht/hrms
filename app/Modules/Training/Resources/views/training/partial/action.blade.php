<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Training Details</legend>
                <div class="form-group row">
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Organization :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('division_id', $organizationList, null, [
                                        'id' => 'divisionId',
                                        'placeholder' => 'Select Organization',
                                        'class' => 'form-control select-search organization-filter2',
                                    ]) !!}
                                </div>
                                @if ($errors->has('division_id'))
                                    <div class="error text-danger">{{ $errors->first('division_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Sub-Function :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('department_id', $departmentList, null, [
                                        'id' => 'departmentId',
                                        'placeholder' => 'Select Sub-Function',
                                        'class' => 'form-control select-search department-filter',
                                    ]) !!}
                                </div>
                                @if ($errors->has('department_id'))
                                    <div class="error text-danger">{{ $errors->first('department_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Training Type :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select(
                                        'type',
                                        ['functional' => 'Functional', 'behavioural' => 'Behavioural', 'wellness' => 'Wellness'],
                                        null,
                                        ['id' => 'typeId', 'placeholder' => 'Select Training Type', 'class' => 'form-control select-search'],
                                    ) !!}
                                </div>
                                @if ($errors->has('type'))
                                    <div class="error text-danger">{{ $errors->first('type') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3 functionalTypeDiv" style="display:none;">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Functional Type :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('functional_type', ['product' => 'Product', 'process' => 'Process', 'skill' => 'Skill'], null, [
                                        'id' => 'functionalType',
                                        'placeholder' => 'Select Functional Type',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                @if ($errors->has('functional_type'))
                                    <div class="error text-danger">{{ $errors->first('functional_type') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Training Title :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('title', null, [
                                        'rows' => 5,
                                        'placeholder' => 'Write training title..',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                @if ($errors->has('title'))
                                    <div class="error text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Start Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    @php
                                        $fromDate = null;
                                        if (setting('calendar_type') == 'BS') {
                                            $clData = 'form-control nepali-calendar';
                                            if ($isEdit && $trainingModel['from_date']) {
                                                $fromDate = date_converter()->eng_to_nep_convert(
                                                    $trainingModel['from_date'],
                                                );
                                            }
                                        } else {
                                            $clData = 'form-control daterange-single';
                                            if ($isEdit && $trainingModel['from_date']) {
                                                $fromDate = $trainingModel['from_date'];
                                            }
                                        }
                                    @endphp

                                    {!! Form::text('from_date', $fromDate, [
                                        'id' => 'fromDateId',
                                        'rows' => 5,
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => $clData,
                                        'autocomplete' => 'off',
                                    ]) !!}
                                </div>
                                @if ($errors->has('from_date'))
                                    <div class="error text-danger">{{ $errors->first('from_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">End Date :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    @php
                                        $toDate = null;
                                        if (setting('calendar_type') == 'BS') {
                                            $clData = 'form-control nepali-calendar';
                                            if ($isEdit && $trainingModel['to_date']) {
                                                $toDate = date_converter()->eng_to_nep_convert(
                                                    $trainingModel['to_date'],
                                                );
                                            }
                                        } else {
                                            $clData = 'form-control daterange-single';
                                            if ($isEdit && $trainingModel['to_date']) {
                                                $toDate = $trainingModel['to_date'];
                                            }
                                        }
                                    @endphp
                                    {!! Form::text('to_date', $toDate, [
                                        'id' => 'toDateId',
                                        'rows' => 5,
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => $clData,
                                        'autocomplete' => 'off',
                                    ]) !!}
                                </div>
                                @if ($errors->has('to_date'))
                                    <div class="error text-danger">{{ $errors->first('to_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4"># of Pax/training:</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('pax_training', null, [
                                        'id' => 'pax_training',
                                        'rows' => 5,
                                        'placeholder' => 'Pax/training',
                                        'class' => 'form-control numeric',
                                    ]) !!}
                                </div>
                                @if ($errors->has('pax_training'))
                                    <div class="error text-danger">{{ $errors->first('pax_training') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Month :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('month[]', $monthList, null, [
                                        'id' => 'monthId',
                                        'class' => 'form-control multiselect-select-all',
                                        'multiple' => 'multiple',
                                        'data-fouc',
                                        'required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('month'))
                                    <div class="error text-danger">{{ $errors->first('month') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Frequency:</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('frequency', null, [
                                        'id' => 'frequency',
                                        // 'rows' => 5,
                                        'placeholder' => 'Frequency',
                                        'class' => 'form-control',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('frequency'))
                                    <div class="error text-danger">{{ $errors->first('frequency') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">No. of Participants :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('no_of_participants', null, [
                                        'id' => 'noOfParticipantsId',
                                        'rows' => 5,
                                        'placeholder' => 'Enter Number..',
                                        'class' => 'form-control numeric',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('no_of_participants'))
                                    <div class="error text-danger">{{ $errors->first('no_of_participants') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">No. of Days :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('no_of_days', null, [
                                        'id' => 'noOfDaysId',
                                        'rows' => 5,
                                        'placeholder' => 'Total training days..',
                                        'class' => 'form-control numeric',
                                    ]) !!}
                                </div>
                                @if ($errors->has('no_of_days'))
                                    <div class="error text-danger">{{ $errors->first('no_of_days') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">No. of Mandays :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('no_of_mandays', null, [
                                        'id' => 'noOfMandaysId',
                                        'rows' => 5,
                                        'placeholder' => 'Enter Number..',
                                        'class' => 'form-control numeric',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('no_of_mandays'))
                                    <div class="error text-danger">{{ $errors->first('no_of_mandays') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Location :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('location', ['physical' => 'Physical', 'virtual' => 'Virtual'], null, [
                                        'id' => 'locationId',
                                        'placeholder' => 'Select Training Location',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                @if ($errors->has('location'))
                                    <div class="error text-danger">{{ $errors->first('location') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Targeted Participant :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('targeted_participant', null, [
                                        'id' => 'targeted_participant',
                                        'rows' => 5,
                                        'placeholder' => 'Targeted Participant',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                @if ($errors->has('targeted_participant'))
                                    <div class="error text-danger">{{ $errors->first('targeted_participant') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>



                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Facilitator :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('facilitator', ['internal' => 'Internal', 'external' => 'External'], null, [
                                        'id' => 'facilitatorId',
                                        'placeholder' => 'Select Facilitator Type',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                @if ($errors->has('facilitator'))
                                    <div class="error text-danger">{{ $errors->first('facilitator') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Facilitator's Name :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('facilitator_name', null, [
                                        'rows' => 5,
                                        'placeholder' => 'Enter Facilitator\'s Name..',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                @if ($errors->has('facilitator_name'))
                                    <div class="error text-danger">{{ $errors->first('facilitator_name') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Planned Budget (Rs.) :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('planned_budget', null, [
                                        'id' => 'plannedBudgetId',
                                        'rows' => 5,
                                        'placeholder' => 'Enter Amount..',
                                        'class' => 'form-control numeric',
                                    ]) !!}
                                </div>
                                @if ($errors->has('planned_budget'))
                                    <div class="error text-danger">{{ $errors->first('planned_budget') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Actual Expense Incurred (Rs.) :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('actual_expense_incurred', null, [
                                        'id' => 'actualExpenseIncurredtId',
                                        'rows' => 5,
                                        'placeholder' => 'Enter Amount..',
                                        'class' => 'form-control numeric',
                                    ]) !!}
                                </div>
                                @if ($errors->has('actual_expense_incurred'))
                                    <div class="error text-danger">{{ $errors->first('actual_expense_incurred') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>


                    {{-- <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">No. of Employees :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('no_of_employee', null, [
                                        'id' => 'noOfEmployeeId',
                                        'rows' => 5,
                                        'placeholder' => 'Enter Number..',
                                        'class' => 'form-control numeric',
                                    ]) !!}
                                </div>
                                @if ($errors->has('no_of_employee'))
                                    <div class="error text-danger">{{ $errors->first('no_of_employee') }}</div>
                                @endif
                            </div>
                        </div>
                    </div> --}}

                    {{-- <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Status :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select(
                                        'status',
                                        ['publish' => 'Publish', 'unpublish' => 'Unpublish', 'completed' => 'Completed'],
                                        null,
                                        ['id' => 'statusId', 'placeholder' => 'Select Status', 'class' => 'form-control select-search'],
                                    ) !!}
                                </div>
                                @if ($errors->has('status'))
                                    <div class="error text-danger">{{ $errors->first('status') }}</div>
                                @endif
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Training For :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('training_for', ['employee' => 'Employee', 'dealer' => 'Dealer'], null, [
                                        'placeholder' => 'Select option',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                @if ($errors->has('training_for'))
                                    <div class="error text-danger">{{ $errors->first('training_for') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Full Marks :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('full_marks', null, [
                                        'rows' => 5,
                                        'placeholder' => 'Enter Number..',
                                        'class' => 'form-control numeric',
                                    ]) !!}
                                </div>
                                @if ($errors->has('full_marks'))
                                    <div class="error text-danger">{{ $errors->first('full_marks') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Fiscal Year:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('fiscal_year_id', $fiscalYearList, null, [
                                        'class' => 'form-control select-search',
                                        'placeholder' => 'Select Fiscal Year',
                                    ]) !!}
                                </div>
                                @if ($errors->has('fiscal_year_id'))
                                    <div class="error text-danger">{{ $errors->first('fiscal_year_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-1">Objective:</label>
                            <div class="col-lg-11 form-group-feedback form-group-feedback-right">
                                {!! Form::textarea('description', null, [
                                    'placeholder' => 'Write here..',
                                    'class' => 'form-control basicTinymce1',
                                    'id' => 'editor-full',
                                ]) !!}
                            </div>
                            @if ($errors->has('description'))
                                <div class="error text-danger">{{ $errors->first('description') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <script src="{{ asset('admin/validation/training.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/editor_ckeditor_default.js') }}"></script>
    {{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> --}}
    <script>
        $(document).ready(function() {
            // tinymce.init({
            //     selector: 'textarea.basicTinymce',
            //     height: '200'
            // });


            $('#typeId').on('change', function() {
                var type = $(this).val();
                if (type == 'functional') {
                    $('.functionalTypeDiv').show();
                } else {
                    $('.functionalTypeDiv').hide();
                }
            });

            $('#typeId').trigger('change');

            $('#fromDateId').daterangepicker({
                parentEl: '.content-inner',
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));

                date = $(this).val();
                $('#noOfDaysId').val("");
                $('#toDateId').val("");

                datePicker('toDateId', date);
            });

            datePicker('toDateId');

            function datePicker(id, minDate = '', maxDate = '') {
                $('#' + id).daterangepicker({
                    parentEl: '.content-inner',
                    singleDatePicker: true,
                    showDropdowns: true,
                    autoUpdateInput: false,
                    minDate: minDate,
                    maxDate: maxDate,
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD'));
                    var startDate = $('#fromDateId').val();
                    var endDate = $(this).val();
                    diff = getDateDiff(startDate, endDate);
                    console.log(diff);
                    $('#noOfDaysId').val(diff)
                });
            }

            function getDateDiff(start, end) {
                var date1 = new Date(start);
                var date2 = new Date(end);
                var diffDays = parseInt((date2 - date1) / (1000 * 60 * 60 * 24), 10);
                return diffDays + 1;
            }

            freq = participant_no = 0;
            $('#monthId').on("change", function() {
                month_length = $(this).val().length;
                var freq = parseInt($('#frequency').val(month_length));
                $('#noOfParticipantsId').val('');
                if ($('#pax_training').val() != '') {
                    participant_no = parseInt($('#pax_training').val()) * month_length;
                    $('#noOfParticipantsId').val(participant_no);
                    $('#noOfDaysId').trigger("keyup");

                }

            });

            $('#pax_training').on("keyup", function() {
                $('#monthId').trigger("change");
                $('#noOfDaysId').trigger("keyup");
            });


            $('#noOfDaysId').on("keyup", function() {
                noOfDaysId = $(this).val();
                $('#noOfMandaysId').val('')
                if (participant_no != '' && noOfDaysId != '') {
                    $('#noOfMandaysId').val(noOfDaysId * participant_no);
                }
            });

        });
    </script>
@endSection
