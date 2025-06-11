{!! Form::hidden('employee_id', $employee->id, []) !!}
@php
    if(setting('calendar_type') == 'BS'){
        $classData = 'form-control nepali-calendar';
    }else{
        $classData = 'form-control daterange-single';
    }
@endphp

<div class="form-group row">
    <div class="col-md-12">
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Type :<span class="text-danger"> *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('type_id', $typeList, $value = null, [
                        'placeholder' => 'Select Type',
                        'class' => 'form-control select-search type',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row jobType d-none">
            <label class="col-form-label col-lg-3">Job Type :<span class="text-danger"> *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('job_type_id', $jobTypeList, $value = null, [
                        'placeholder' => 'Select Job Type',
                        'class' => 'form-control select-search jobTypeClass',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row probationPeriodDays d-none">
            <label class="col-form-label col-lg-3">Probation Period (Days) :<span class="text-danger"> *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                        {!! Form::number('probation_period_days', $value = null, [
                            'placeholder' => 'e.g: 90',
                            'class' => 'form-control',
                        ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row contractStartDate d-none">
            <label class="col-form-label col-lg-3">Start Date :<span class="text-danger"> *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-calendar22"></i></span>
                    </span>
                    {!! Form::text('contract_start_date', $value = null, [
                        'placeholder' => 'Enter contract start date',
                        'class' => $classData,
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="form-group row contractEndDate d-none">
            <label class="col-form-label col-lg-3">End Date :<span class="text-danger"> *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-calendar22"></i></span>
                    </span>
                    {!! Form::text('contract_end_date', $value = null, [
                        'placeholder' => 'Enter contract end date',
                        'class' => $classData,
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="form-group row extendEndDate d-none">
            <label class="col-form-label col-lg-3">End Date :<span class="text-danger"> *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-calendar22"></i></span>
                    </span>
                    {!! Form::text('extend_end_date', $value = null, [
                        'placeholder' => 'Enter end date',
                        'class' => $classData,
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="archiveSection d-none">
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Archive Reason:<span class="text-danger"> *</span></label>
                <div class="col-lg-9">
                    <input type="hidden" name="employment_id" value="{{$employee->id}}">
                    {!! Form::textarea('archive_reason', $value = null, ['placeholder'=>'Enter reason for archive','class'=>'form-control']) !!}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-lg-3">Archive Date :<span class="text-danger"> *</span></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-calendar22"></i></span>
                        </span>
                        {!! Form::text('archived_date', $value = null, [
                            'placeholder' => 'Enter archive date',
                            'class' => $classData,
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.type').on('change', function () {
            var type = $(this).val()
            if (type == 1) {
                $('.jobType').removeClass('d-none')
                $('.extendEndDate').addClass('d-none')
                $('.archiveSection').addClass('d-none')
            } else if (type == 2) {
                $('.jobType').addClass('d-none')
                $('.extendEndDate').removeClass('d-none')
                $('.archiveSection').addClass('d-none')

                $('.probationPeriodDays').addClass('d-none')
                $('.contractStartDate').addClass('d-none')
                $('.contractEndDate').addClass('d-none')
            }else{
                $('.jobType').addClass('d-none')
                $('.extendEndDate').addClass('d-none')
                $('.archiveSection').removeClass('d-none')

                $('.probationPeriodDays').addClass('d-none')
                $('.contractStartDate').addClass('d-none')
                $('.contractEndDate').addClass('d-none')
            }
        })

        $('.jobTypeClass').on('change', function () {
            var type = $(this).val()
            if (type == 1) {
                $('.probationPeriodDays').removeClass('d-none')
                $('.contractStartDate').addClass('d-none')
                $('.contractEndDate').addClass('d-none')
            } else if (type == 2) {
                $('.probationPeriodDays').addClass('d-none')
                $('.contractStartDate').removeClass('d-none')
                $('.contractEndDate').removeClass('d-none')
            }else{
                $('.probationPeriodDays').addClass('d-none')
                $('.contractStartDate').addClass('d-none')
                $('.contractEndDate').addClass('d-none')
            }
        })
    })
</script>