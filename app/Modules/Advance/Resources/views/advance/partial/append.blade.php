<div id="repeatForm" >
    <div class="row parent">
        {{-- <div class="col-lg-5 mb-3">
            <div class="row items">
                <label class="col-form-label col-lg-2">Due Date :</label>
                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::text('partial_date[]', null, [
                            'placeholder' => 'YYYY-MM-DD',
                            'class' => 'form-control daterange-single',
                            'readonly',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-5 mb-3 engDueDate">
            <div class="row items">
                <label class="col-form-label col-lg-2">Due Date :</label>
                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::text('partial_date[]', null, [
                            'placeholder' => 'YYYY-MM-DD',
                            'class' => 'form-control daterange-single',
                            'readonly',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-3 nepDueDate" style="display:none">
            <div class="row items">
                <label class="col-form-label col-lg-2">Due Date :<span class="text-danger">
                        *</span></label>
                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::text('partial_date_nep[]', null, [
                            'rows' => 5,
                            'placeholder' => 'e.g: YYYY-MM-DD',
                            'class' => 'form-control daterange-nep-single',
                            'readonly',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-3">
            <div class="row items">
                <label class="col-form-label col-lg-2">Amount :</label>
                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::text('partial_amount[]', null, ['placeholder' => 'Enter Amount', 'class' => 'form-control numeric']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 mb-3">
            <a class="btn btn-danger rounded-pill remove">
                <i class="icon-minus-circle2 mr-1"></i>Remove
            </a>
        </div>
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

    $('#organization').on('change', function() {
        var organizationId = $('#organization').val();
        $.ajax({
            type: 'GET',
            url: '/admin/payroll-setting/get-calendar-type',
            data: {
                organization_id: organizationId
            },
            success: function(data) {
                var list = JSON.parse(data);
                if (list.calendar_type == 'nep') {
                    $('.engDueDate').hide();
                    $('.nepDueDate').show();
                } else {
                    $('.nepDueDate').hide();
                    $('.engDueDate').show();
                }
            }
        });
    });
    $('#organization').trigger('change');
</script>
