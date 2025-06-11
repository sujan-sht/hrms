<div class="form-group row income">
    <div class="col-lg-3 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-3">Incomes:<span class="text-danger">
                    *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('income_setup_id[' . $numberIncr . ']', $incomes, null, [
                        'class' => 'form-control select-search income-filter income-selection',
                        'placeholder' => 'Select Incomes',
                        'required',
                    ]) !!}
                </div>
                @if ($errors->has('income_setup_id'))
                    <div class="error text-danger">{{ $errors->first('income_setup_id') }}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-3 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-3">Exiting Amount:<span class="text-danger">
                    *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('exiting_amount[' . $numberIncr . ']', null, [
                        'class' => 'form-control numeric',
                        'placeholder' => 'Exiting Amount',
                        'required',
                        'readonly',
                    ]) !!}
                </div>
                @if ($errors->has('exiting_amount'))
                    <div class="error text-danger">{{ $errors->first('exiting_amount') }}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-3 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-3">Increased Amount:<span class="text-danger">
                    *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('increased_amount[' . $numberIncr . ']', null, [
                        'class' => 'form-control numeric',
                        'placeholder' => 'Enter Increased Amount',
                        'required',
                    ]) !!}
                </div>
                @if ($errors->has('increased_amount'))
                    <div class="error text-danger">{{ $errors->first('increased_amount') }}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-2 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-4">Effective Date:<span class="text-danger">
                    *</span></label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('effective_date[' . $numberIncr . ']', $value = null, [
                        'placeholder' => 'Select Effective Date',
                        'readonly',
                        'required',
                        'class' => 'form-control nepali-calendar',
                    ]) !!}

                </div>
                @if ($errors->has('effective_date'))
                    <div class="error text-danger">{{ $errors->first('effective_date') }}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-1 mb-3">
        <div class="row">
            <button type="button" class="removeIncome btn bg-danger-400 btn-icon text-white">
                <i class="icon-minus3"></i><b></b>
            </button>
        </div>
    </div>
</div>
<script>
    
    $('.removeIncome').on('click', function() {
        $(this).closest('.income').remove();
        var removeAttribute=$('.removeIncome');
        if(removeAttribute.length > 0){
            $('.updateBtn').show();
        }else{
            $('.updateBtn').hide();
        }
    });

    $('.select-search').select2();
    $(".nepali-calendar").nepaliDatePicker();
    $('.numeric').keyup(function() {
        if (this.value.match(/[^0-9.]/g)) {
            this.value = this.value.replace(/[^0-9.]/g, '');
        }
    });
    var beforeDate="{{@$currentDateInNep}}";
    $('.nepali-calendar').nepaliDatePicker({
        ndpYear: true,
        ndpMonth: true,
        ndpTriggerButton: false,
        disableBefore: beforeDate,
        onChange: function() {
        }
        });
</script>
