<div class="form-group row income">
    <div class="col-lg-4 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-3">Incomes:<span class="text-danger">
                    *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('income_setup_id['.$numberIncr.']', $incomes, null, ['class' => 'form-control select-search income-filter','placeholder' => 'Select Incomes','required']) !!}
                </div>
                @if ($errors->has('income_setup_id'))
                    <div class="error text-danger">{{ $errors->first('income_setup_id') }}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-3">Arrear Amount:<span class="text-danger">
                    *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('arrear_amount['.$numberIncr.']', null, ['class' => 'form-control numeric','placeholder' => 'Enter Arrear Amount','required']) !!}
                </div>
                @if ($errors->has('arrear_amount'))
                    <div class="error text-danger">{{ $errors->first('arrear_amount') }}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-3 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-4">Income Type:<span class="text-danger">
                    *</span></label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('income_type['.$numberIncr.']', ['add' => 'Add', 'sub' => 'Sub'], null, ['class' => 'form-control select-search','placeholder' => 'Select Income Type','required']) !!}
                   
                </div>
                @if ($errors->has('income_type'))
                    <div class="error text-danger">{{ $errors->first('income_type') }}</div>
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
    $('.removeIncome').on('click',function(){ 
        $(this).closest('.income').remove();
    });

    $('.select-search').select2();
    $('.numeric').keyup(function() {
        if (this.value.match(/[^0-9.]/g)) {
            this.value = this.value.replace(/[^0-9.]/g, '');
        }
    });
</script>