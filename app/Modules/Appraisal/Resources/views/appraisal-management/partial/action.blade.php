<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Organization :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('organization', $organizationModel, null, [
                                        'placeholder' => 'Select Organization',
                                        'class' => 'form-control select-search organization-filter',
                                    ]) !!}
                                </div>
                                @if ($errors->has('organization'))
                                    <div class="error text-danger">{{ $errors->first('organization') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Appraisee :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {{-- {!! Form::select('employee_id[]', [], null, [
                                            'placeholder' => 'Select Employee',
                                            'class' => 'form-control select-search employee-filter',
                                        ]) !!} --}}
                                    {!! Form::select('appraisee[]', [], null, ['class' => 'form-control employee-filter','multiple']) !!}
                                </div>
                                @if ($errors->has('appraisee'))
                                    <div class="error text-danger">{{ $errors->first('appraisee') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Form Type :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('questionnaire_id', $questionnaires, null, [
                                        'placeholder' => 'Choose Form Type',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                @if ($errors->has('questionnaire_id'))
                                    <div class="error text-danger">{{ $errors->first('questionnaire_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if (setting('calendar_type') == 'BS')
                    <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Due Date :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('valid_date', null, [
                                        'placeholder' => 'Choose Date ',
                                        'class' => 'form-control daterange-nep-single',
                                        'readonly'
                                    ]) !!}
                                </div>
                                @if ($errors->has('valid_date'))
                                    <div class="error text-danger">{{ $errors->first('valid_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Due Date :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('valid_date', null, [
                                        'placeholder' => 'Choose Date ',
                                        'class' => 'form-control daterange-single',
                                        'readonly'
                                    ]) !!}
                                </div>
                                @if ($errors->has('valid_date'))
                                    <div class="error text-danger">{{ $errors->first('valid_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {!! Form::hidden('enable_hod_evaluation', 11) !!}
                    {!! Form::hidden('hod_evaluation_type', 1) !!}

                </div>

                {{-- <div class="form-group row">

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Appraisal Type :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group ">
                                    {!! Form::select('type', ['internal' => 'Internal', 'external' => 'External'], null, [
                                        'placeholder' => 'Choose Type',
                                        'class' => 'form-control appraisalType select-search',
                                    ]) !!}
                                </div>
                                @if ($errors->has('type'))
                                    <div class="error text-danger">{{ $errors->first('type') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                <div class="internal d-none">
                    @include('appraisal::appraisal-management.partial.internal-employee-detail')
                </div>

                <div class="external d-none">
                    @include('appraisal::appraisal-management.partial.external-employee-detail')
                </div>

                <div class="form-repeater"></div> --}}

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Setting Detail</legend>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Enable Self Evaluation?<span class="text-danger"> *</span></label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('enable_self_evaluation', $noYesList, null, ['class'=>'form-control select-search', 'id'=>'enableSelfEvaluation']) !!}
                                </div>
                                @if ($errors->has('enable_self_evaluation'))
                                    <div class="error text-danger">{{ $errors->first('enable_self_evaluation') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-3 selfEvaluationDiv" style="display:none;">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Self Evaluation Type</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('self_evaluation_type', $evaluationTypeList, null, ['class'=>'form-control select-search', 'id'=>'selfEvaluation']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Enable Supervisor Evaluation?<span class="text-danger"> *</span></label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('enable_supervisor_evaluation', $noYesList, null, ['class'=>'form-control select-search', 'id'=>'enableSupervisorEvaluation']) !!}
                                </div>
                                @if ($errors->has('enable_supervisor_evaluation'))
                                    <div class="error text-danger">{{ $errors->first('enable_supervisor_evaluation') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-3 supervisorEvaluationDiv" style="display:none;">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Supervisor Evaluation Type</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('supervisor_evaluation_type', $evaluationTypeList, null, ['class'=>'form-control select-search', 'id'=>'supervisorEvaluation']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Enable HOD Evaluation?<span class="text-danger"> *</span></label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('enable_hod_evaluation', $noYesList, null, ['class'=>'form-control select-search', 'id'=>'enableHODEvaluation']) !!}
                                </div>
                                @if ($errors->has('enable_hod_evaluation'))
                                    <div class="error text-danger">{{ $errors->first('enable_hod_evaluation') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-3 hodEvaluationDiv" style="display:none;">
                        <div class="row">
                            <label class="col-form-label col-lg-8">HOD Evaluation Type</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('hod_evaluation_type', $evaluationTypeList, null, ['placeholder'=>'Choose type', 'class'=>'form-control select-search', 'id'=>'hodEvaluation']) !!}
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Appraiser Detail</legend>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Appraisal Type :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group ">
                                    {!! Form::select('type', ['internal' => 'Internal', 'external' => 'External'], null, [
                                        'placeholder' => 'Choose Type',
                                        'class' => 'form-control appraisalType select-search',
                                    ]) !!}
                                </div>
                                @if ($errors->has('type'))
                                    <div class="error text-danger">{{ $errors->first('type') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="internal d-none">
                    @include('appraisal::appraisal-management.partial.internal-employee-detail')
                </div>

                <div class="external d-none">
                    @include('appraisal::appraisal-management.partial.external-employee-detail')
                </div>

                <div class="form-repeater"></div>
            </div>
        </div>
    </div>
</div> --}}

<div class="text-center">
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>
<script src="{{ asset('admin/js/nrj_custom.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js')}}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
<script>
    $('.appraisalType').on('change', function() {
        $(".form-repeater").empty()
        let type = $(this).val()
        if (type == 'internal') {
            $('.internal').removeClass('d-none');
            $('.external').addClass('d-none');
        }
        if (type == 'external') {
            $('.internal').addClass('d-none');
            $('.external').removeClass('d-none');
        }
    })

    $(".addMore").click(function() {
        let type = $(".appraisalType").val();

        if (type == null || type == '') {
            toastr.error('Please Choose Type First!')
        }

        $.ajax({
            url: "<?php echo route('appraisal.appendRespondent'); ?>",
            method: 'POST',
            data: {
                type: type,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                console.log(data.result)
                $(".form-repeater").append(data.result);
                // $(".select-search").select2();

            }
        });
    });

    $(document).on('change', '.employee', function() {
        let id = $(this).val()
        let parent = $(this).closest('.items')
        let name = parent.find('.internal_name')
        let email = parent.find('.internal_email')
        $.ajax({
            url: "<?php echo route('employee.getNameandEmail'); ?>",
            method: 'POST',
            data: {
                id: id,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                console.log(data)
                name.val(data.full_name)
                email.val(data.personal_email)
                // $(".select-search").select2();

            }
        });
    });

    $(document).ready(function() {
        $('#enableSelfEvaluation').on('change', function () {
            var isEnable = $(this).val()
            if(isEnable == 11){
                $('.selfEvaluationDiv').show()
            }else{
                $('.selfEvaluationDiv').hide()
                $('#selfEvaluation').val('')
            }
        })

        $('#enableSupervisorEvaluation').on('change', function () {
            var isEnable = $(this).val()
            if(isEnable == 11){
                $('.supervisorEvaluationDiv').show()
            }else{
                $('.supervisorEvaluationDiv').hide()
                $('#supervisorEvaluation').val('')
            }
        })

        $('#enableHODEvaluation').on('change', function () {
            var isEnable = $(this).val()
            if(isEnable == 11){
                $('.hodEvaluationDiv').show()
            }else{
                $('.hodEvaluationDiv').hide()
                $('#hodEvaluation').val('')
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
    })
</script>
