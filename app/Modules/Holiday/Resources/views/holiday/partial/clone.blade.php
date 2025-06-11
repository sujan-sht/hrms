<div class="row clone-div mb-2">
    <label class="col-form-label col-lg-2">
        @if ($count == 0)
            Holiday Title: <span class="text-danger">*</span>
        @endif
    </label>

    <div class="col-lg-8">
        <div class="row">
            <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text(
                        'holiday_days[' . $count . '][day]',
                        isset($holidayDetail['sub_title']) ? $holidayDetail['sub_title'] : null,
                        [
                            'id' => 'day',
                            'placeholder' => 'Enter Holiday Title',
                            'class' => 'form-control',
                            'required'
                        ],
                    ) !!}
                </div>
                @if ($errors->has('day'))
                    <div class="error text-danger">{{ $errors->first('day') }}</div>
                @endif
            </div>

            <div
                class="col-lg-6 form-group-feedback form-group-feedback-right eng_date {{ $calendar_type == 1 ? 'd-none' : '' }} ">
                <div class="input-group">
                    {!! Form::text(
                        'holiday_days[' . $count . '][eng_date]',
                        isset($holidayDetail['eng_date']) ? $holidayDetail['eng_date'] : null,
                        [
                            'placeholder' => 'YYYY-MM-DD',
                            'class' => 'form-control daterange-single',
                            'required'
                        ],
                    ) !!}
                </div>
                @if ($errors->has('eng_date'))
                    <div class="error text-danger">{{ $errors->first('eng_date') }}</div>
                @endif
            </div>

            <div
                class="col-lg-6 form-group-feedback form-group-feedback-right nep_date {{ $calendar_type == 2 ? 'd-none' : '' }}">
                <div class="input-group">
                    {!! Form::text(
                        'holiday_days[' . $count . '][nep_date]',
                        isset($holidayDetail['nep_date']) ? $holidayDetail['nep_date'] : null,
                        [
                            'placeholder' => 'YYYY-MM-DD',
                            'class' => 'form-control daterange-nep-single',
                            'required'
                        ],
                    ) !!}
                </div>
                @if ($errors->has('nep_date'))
                    <div class="error text-danger">{{ $errors->first('nep_date') }}</div>
                @endif
            </div>

        </div>

    </div>

    <div class="col-lg-2">
        @if ($count == 0)
            <a class="btn btn-success rounded-pill btn-clone">
                <i class="icon-plus-circle2 mr-1"></i>Add More
            </a>
        @else
        <a class="btn btn-danger rounded-pill btn-remove"><i class="icon-minus-circle2 mr-1"></i>Remove</a>
        @endif

    </div>
</div>

<script>
    nepDatePicker('daterange-nep-single');

    function nepDatePicker(element) {
        var dobInput = $('.' + element);
        dobInput.nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 10
        });
    }

    engDatePicker('daterange-single');

    function engDatePicker(element) {
        $('.' + element).daterangepicker({
            parentEl: '.content-inner',
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD'
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
        }).on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    }
</script>
