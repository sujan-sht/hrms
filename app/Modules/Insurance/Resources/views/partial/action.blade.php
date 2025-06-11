<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Insurance Type<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12 ml-4">

                                {!! Form::select('insurance_type', $insuranceTypes, @$insurance->insurance_type_id ?? null, [
                                    'id' => 'insurance_type',
                                    'class' => 'form-control select-search',
                                    'placeholder' => 'Select Insurance Type',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('insurance::insurance.type.forms.life-insurance')
            @include('insurance::insurance.type.forms.accident-insurance')
            @include('insurance::insurance.type.forms.medical-insurance')
        </div>

        <div class="text-center">
            <a href="{{ route('insurance.index') }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                        class="icon-backward2"></i></b>Go Back</a>
            <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                        class="icon-database-insert"></i></b>{{ $btnType }}</button>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {

        // edit
        let selectedText = $('#insurance_type option:selected').text();
        if (selectedText != '' || selectedText != null) {
            if (selectedText == 'Life Insurance') {
                $('.life-insurance-form').show();
                $('.accident-insurance-form').hide();
                $('.medical-insurance-form').hide();
            } else if (selectedText == 'Accident Insurance') {
                $('.accident-insurance-form').show();
                $('.life-insurance-form').hide();
                $('.medical-insurance-form').hide();
            } else if (selectedText == 'Medical Insurance') {
                $('.medical-insurance-form').show();
                $('.accident-insurance-form').hide();
                $('.life-insurance-form').hide();
            }

        }

        let selectedValue = $("input[name='medical_premium_payment_by']:checked").val();
        if (selectedValue === 'sharing') {
            $('#medical_sharing_container').css('display', 'block');
        } else {
            $('#medical_sharing_container').css('display', 'none');
        }

        let premium_payment_by = $("input[name='premium_payment_by']:checked").val();
        if (premium_payment_by === 'sharing') {
            $('#sharing_container').css('display', 'block');
        } else {
            $('#sharing_container').css('display', 'none');
        }



        // on change
        $("#insurance_type").on('change', function() {
            var value = $(this).val();
            if (value != '' || value != null) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('insurance.type.show') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": value,
                    },
                    success: function(response) {
                        if (response.status) {
                            switch (response.data.title) {
                                case 'Life Insurance':
                                    console.log('life insurance');
                                    $('.medical-insurance-form').hide();
                                    $('.accident-insurance-form').hide();
                                    $('.life-insurance-form').show();
                                    $('.life-insurance').text(response.data.title);
                                    break;
                                case 'Medical Insurance':
                                    console.log('medical insurance');
                                    $('.life-insurance-form').hide();
                                    $('.accident-insurance-form').hide();
                                    $('.medical-insurance-form').show();
                                    $('.medical-insurance').text(response.data.title);
                                    break;
                                case 'Accident Insurance':
                                    console.log('accident insurance');
                                    $('.accident-insurance-form').show();
                                    $('.accident-insurance').text(response.data.title);
                                    $('.life-insurance-form').hide();
                                    $('.medical-insurance-form').hide();
                                    break;
                                default:
                                    alert(
                                        'Insurance Type not found. I have only [Life Insurance, Medical Insurance, Accident Insurance]'
                                    );
                                    break;
                            }
                        }
                    }
                });
            }

        });
    });


    $(document).ready(function() {

        $('input[type="radio"][name="premium_payment_by"]').on('change', function() {
            let selectedValue = $(this).val();
            if (selectedValue === 'sharing') {
                $('#sharing_container').css('display', 'block');
            } else {
                $('#sharing_container').css('display', 'none');
            }
        });


        $('input[type="radio"][name="medical_premium_payment_by"]').on('change', function() {
            let selectedValue = $(this).val();
            if (selectedValue === 'sharing') {
                $('#medical_sharing_container').css('display', 'block');
            } else {
                $('#medical_sharing_container').css('display', 'none');
            }
        });

    })
</script>
