<div class="row permanent-form">
    <div class="col-md-12">
        <div class="form-group row state">
            <label class="col-form-label col-lg-3">State/Province:<span class="text-danger">*</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    @if (isset($employeeaddress->permanentprovince) && !is_null($employeeaddress->permanentprovince))
                        {!! Form::select(
                            'permanentprovince',
                            $state,
                            $is_edit == true ? ($value = $employeeaddress->permanentprovince) : ($value = null),
                            [
                                'id' => 'permanentprovince',
                                'placeholder' => 'Select State/Province',
                                'class' => 'form-control select-search',
                                $isEmployee ? 'readonly' : '',
                            ],
                        ) !!}
                    @else
                        {!! Form::select('permanentprovince', $state, $value = null, [
                            'id' => 'permanentprovince',
                            'placeholder' => 'Select State/Province',
                            'class' => 'form-control select-search',
                            $isEmployee ? 'readonly' : '',
                        ]) !!}
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group row country" style="display:none">
            <label class="col-form-label col-lg-3">Country:<span class="text-danger">*</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    @if (isset($employeeaddress->country) && !is_null($employeeaddress->country))
                        {!! Form::select('country', $state, $is_edit == true ? ($value = $employeeaddress->country) : ($value = null), [
                            'id' => 'country',
                            'placeholder' => 'Select State/Province',
                            'class' => 'form-control select-search country-search',
                            $isEmployee ? 'readonly' : '',
                        ]) !!}
                    @else
                        {!! Form::select('country', $countryList, $value = null, [
                            'id' => 'country',
                            'placeholder' => 'Select Country',
                            'class' => 'form-control select-search country-search',
                            $isEmployee ? 'readonly' : '',
                        ]) !!}
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group row district">
            <label class="col-form-label col-lg-3">District:<span class="text-danger">*</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    @if (isset($employeeaddress->permanentdistrict) && !is_null($employeeaddress->permanentdistrict))
                        {!! Form::select(
                            'permanentdistrict',
                            $district,
                            old('permanentdistrict', $employeeaddress->permanentdistrict),
                            // $is_edit == true ? ($value = $employeeaddress->permanentdistrict) : ($value = null),
                            [
                                'id' => 'permanentdistrict',
                                'placeholder' => 'Select District',
                                'class' => 'form-control select-search',
                                $isEmployee ? 'readonly' : '',
                            ],
                        ) !!}
                    @else
                        {!! Form::select('permanentdistrict', $district, $value = null, [
                            'id' => 'permanentdistrict',
                            'placeholder' => 'Select District',
                            'class' => 'form-control select-search',
                            $isEmployee ? 'readonly' : '',
                        ]) !!}
                    @endif


                </div>
            </div>
        </div>
        <div class="form-group row minicipality">
            <label class="col-form-label col-lg-3">Municipality/Vdc:<span class="text-danger">*</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-flip-horizontal2"></i></span>
                    </span>
                    @if (isset($employeeaddress->permanentmunicipality_vdc) && !is_null($employeeaddress->permanentmunicipality_vdc))
                        {!! Form::text(
                            'permanentmunicipality_vdc',
                            $is_edit == true ? ($value = $employeeaddress->permanentmunicipality_vdc) : ($value = null),
                            [
                                'id' => 'permanentmunicipality_vdc',
                                'placeholder' => 'Enter Municipality/Vdc',
                                'class' => 'form-control',
                                $isEmployee ? 'readonly' : '',
                            ],
                        ) !!}
                    @else
                        {!! Form::text('permanentmunicipality_vdc', $value = null, [
                            'id' => 'permanentmunicipality_vdc',
                            'placeholder' => 'Enter Municipality/Vdc',
                            'class' => 'form-control',
                            $isEmployee ? 'readonly' : '',
                        ]) !!}
                    @endif


                </div>
            </div>
        </div>

        <div class="form-group row ward">
            <label class="col-form-label col-lg-3">Ward No: <span class="text-danger">*</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-flip-horizontal2"></i></span>
                    </span>
                    @if (isset($employeeaddress->permanentward) && !is_null($employeeaddress->permanentward))
                        {!! Form::text(
                            'permanentward',
                            $is_edit == true ? ($value = $employeeaddress->permanentward) : ($value = null),
                            [
                                'id' => 'permanentward',
                                'placeholder' => 'Enter Ward No.',
                                'class' => 'form-control',
                                $isEmployee ? 'readonly' : '',
                            ],
                        ) !!}
                    @else
                        {!! Form::text('permanentward', $value = null, [
                            'id' => 'permanentward',
                            'placeholder' => 'Enter Ward No.',
                            'class' => 'form-control',
                            $isEmployee ? 'readonly' : '',
                        ]) !!}
                    @endif


                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-3">Address:<span class="text-danger">*</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-location3"></i></span>
                    </span>
                    @if (isset($employeeaddress->permanentaddress) && !is_null($employeeaddress->permanentaddress))
                        {!! Form::text(
                            'permanentaddress',
                            $is_edit == true ? ($value = $employeeaddress->permanentcity) : ($value = null),
                            [
                                'id' => 'permanentaddress',
                                'placeholder' => 'Enter City',
                                'class' => 'form-control',
                                $isEmployee ? 'readonly' : '',
                            ],
                        ) !!}
                    @else
                        {!! Form::text('permanentaddress', $value = null, [
                            'id' => 'permanentaddress',
                            'placeholder' => 'Enter Address',
                            'class' => 'form-control',
                            $isEmployee ? 'readonly' : '',
                        ]) !!}
                    @endif


                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-3">Latitude:</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-location3"></i></span>
                    </span>
                    @if (isset($employeeaddress->permanent_latitude) && !is_null($employeeaddress->permanent_latitude))
                        {!! Form::text(
                            'permanent_latitude',
                            $is_edit == true ? ($value = $employeeaddress->permanent_latitude) : ($value = null),
                            [
                                'id' => 'permanent_latitude',
                                'placeholder' => 'Enter Latitude',
                                'class' => 'form-control',
                                $isEmployee ? 'readonly' : '',
                            ],
                        ) !!}
                    @else
                        {!! Form::text('permanent_latitude', $value = null, [
                            'id' => 'permanent_latitude',
                            'placeholder' => 'Enter Latitude',
                            'class' => 'form-control',
                            $isEmployee ? 'readonly' : '',
                        ]) !!}
                    @endif


                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Longitude:</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-location3"></i></span>
                    </span>
                    @if (isset($employeeaddress->permanent_longitude) && !is_null($employeeaddress->permanent_longitude))
                        {!! Form::text(
                            'permanent_longitude',
                            $is_edit == true ? ($value = $employeeaddress->permanent_longitude) : ($value = null),
                            [
                                'id' => 'permanent_longitude',
                                'placeholder' => 'Enter Longitude',
                                'class' => 'form-control',
                                $isEmployee ? 'readonly' : '',
                            ],
                        ) !!}
                    @else
                        {!! Form::text('permanent_longitude', $value = null, [
                            'id' => 'permanent_longitude',
                            'placeholder' => 'Enter Longitude',
                            'class' => 'form-control',
                            $isEmployee ? 'readonly' : '',
                        ]) !!}
                    @endif


                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // $('#permanentdistrict').empty();
        // $('#permanentdistrict').html('<option value="">Select District</option>');

        $('#permanentprovince').on('change', function() {
            check_permanent_length();

            const province_id = $('#permanentprovince').val();

            if (province_id == "") {
                $('#permanentdistrict').empty();
                $('#permanentdistrict').html('<option value="">Select District</option>');
                return true;
            }

            $.ajax({
                type: 'GET',
                url: '/admin/employee/getdistrict/' + province_id,
                success: function(resp) {
                    $('#permanentdistrict').empty();
                    $('#permanentdistrict').append(
                        '<option value="">Select District</option>');

                    $('#permanentdistrict').append(resp);
                }
            });

        });

        // $('#permanentprovince').trigger('change');

        $('#permanentdistrict').on('change', function() {
            check_permanent_length();
        });

        function check_permanent_length() {
            var isValid = true;
            check_boolean_arr = [];
            $('.permanent-form').find("input:text, select").each(
                function() {
                    var element = $(this);
                    if (!$(this).hasClass('country-search')) {
                        if (element.val() == "") {
                            isValid = false;
                        }
                        check_boolean_arr.push(isValid);
                    }

                });

            if (check_boolean_arr.includes(false)) {
                $('#temporary_address').hide();
            } else {
                $('#temporary_address').show();
            }
        }


        $('#permanentmunicipality_vdc,#permanentaddress').keyup(function() {
            check_permanent_length();
        });
    });
</script>
