<div class="card">
    <div class="card-body">
        <fieldset class="mb-3">
            <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

            <div class="row">
                <label class="col-form-label col-lg-3">Organization :<span class="text-danger"> *</span></label>
                <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('organization_id', $organizationList, null, [
                            'class' => 'select-filter organization-filter',
                        ]) !!}
                    </div>
                    @if ($errors->has('organization_id'))
                        <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                    @endif
                </div>
                <div class="col-md-6"></div>
            </div>

            <div class="row mt-3">
                <label class="col-form-label col-lg-3">Type :<span class="text-danger"> *</span></label>
                <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('type', $typeList, null, [
                            'class' => 'select-filter getType',
                            'placeholder' => 'Select Type'
                        ]) !!}
                    </div>
                    @if ($errors->has('type'))
                        <div class="error text-danger">{{ $errors->first('type') }}</div>
                    @endif
                </div>
                <div class="col-md-6"></div>
            </div>

            <div class="row mt-3 deductionMethodDiv">
                <label class="col-form-label col-lg-3">Deduction Method :<span class="text-danger"> *</span></label>
                <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('method', $methodList, null, [
                            'class' => 'form-control deductionMethod',
                            'placeholder' => 'Select Method'
                        ]) !!}
                    </div>
                    @if ($errors->has('method'))
                        <div class="error text-danger">{{ $errors->first('method') }}</div>
                    @endif
                </div>
                <div class="col-md-6"></div>
            </div>

            <div class="row mt-3 maxLimitDiv">
                <label class="col-form-label col-lg-3">Maximun No. of Limit :<span class="text-danger"> *</span></label>
                <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::number('max_late_days', null, [
                            'class' => 'form-control maxLateDays',
                            'placeholder' => 'Enter Number'
                        ]) !!}
                    </div>
                </div>
                <label class="col-form-label col-lg-6">Times</label>
            </div>

            <div class="row mt-3">
                <label class="col-form-label col-lg-3">Number of Leave Deduction :<span class="text-danger"> *</span></label>
                <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::number('deduct_leave_number', null, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Number'
                        ]) !!}
                    </div>
                </div>
                <label class="col-form-label col-lg-6">Days</label>
            </div>

            <div class="row mt-3">
                <label class="col-form-label col-lg-3">Deduct Leave From :<span class="text-danger"> *</span></label>
                <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('leave_type_id', $leaveTypeList, null, [
                            'class' => 'select-filter leave-type-filter',
                            'placeholder' => 'Choose Leave Type'
                        ]) !!}
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>

            <div class="row mt-3">
                <label class="col-form-label col-lg-3">Unpaid Leave Type :</label>
                <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::select('unpaid_leave_type', $unpaidLeaveTypeList, null, [
                            'class' => 'select-filter unpaid-leave-type-filter',
                            'placeholder' => 'Choose Leave Type'
                        ]) !!}
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
        </fieldset>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/validation/leaveDeductionSetup.js') }}"></script>

    <script>
        $(document).ready(function() {
            showHideMaxLimitSection();

            $('.getType').on('change', function () {
                showHideMaxLimitSection();
            });

            function showHideMaxLimitSection() {
                var type = $('.getType').val();
                if(!type || type == 1 || type == 2 || type == 3 || type == 4){
                    $('.maxLimitDiv').show();
                    $('.deductionMethodDiv').show();
                }else{
                    $('.maxLimitDiv').hide();
                    $('.maxLateDays').val('');

                    $('.deductionMethodDiv').hide();
                    $('.deductionMethod').val('');
                }
            }
        });
    </script>
@stop
