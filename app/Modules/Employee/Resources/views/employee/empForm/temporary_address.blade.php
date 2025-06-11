    <div class="row">
        <div class="col-md-12">

            <div class="form-group row">
                <label class="col-form-label col-lg-3">State/Province:</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        @if (isset($employeeaddress->temporaryprovince) && !is_null($employeeaddress->temporaryprovince))
                            {!! Form::select(
                                'temporaryprovince',
                                $state,
                                $is_edit == true ? ($value = $employeeaddress->temporaryprovince) : ($value = null),
                                [
                                    'id' => 'temporaryprovince',
                                    'placeholder' => 'Select State/Province',
                                    'class' => 'form-control select-search',
                                    $isEmployee ? 'readonly' : '',
                                ],
                            ) !!}
                        @else
                            {!! Form::select('temporaryprovince', $state, $value = null, [
                                'id' => 'temporaryprovince',
                                'placeholder' => 'Select State/Province',
                                'class' => 'form-control select-search',
                                $isEmployee ? 'readonly' : '',
                            ]) !!}
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-lg-3">District:</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">

                        @if (isset($employeeaddress->temporarydistrict) && !is_null($employeeaddress->temporarydistrict))
                            {!! Form::select(
                                'temporarydistrict',
                                $district,
                                $is_edit == true ? ($value = $employeeaddress->temporarydistrict) : ($value = null),
                                [
                                    'id' => 'temporarydistrict',
                                    'placeholder' => 'Select District',
                                    'class' => 'form-control select-search',
                                    $isEmployee ? 'readonly' : '',
                                ],
                            ) !!}
                        @else
                            {!! Form::select('temporarydistrict', $district, $value = null, [
                                'id' => 'temporarydistrict',
                                'placeholder' => 'Select District',
                                'class' => 'form-control select-search',
                                $isEmployee ? 'readonly' : '',
                            ]) !!}
                        @endif

                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Municipality/Vdc:</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-flip-horizontal2"></i></span>
                        </span>
                        @if (isset($employeeaddress->temporarymunicipality_vdc) && !is_null($employeeaddress->temporarymunicipality_vdc))
                            {!! Form::text(
                                'temporarymunicipality_vdc',
                                $is_edit == true ? ($value = $employeeaddress->temporarymunicipality_vdc) : ($value = null),
                                [
                                    'id' => 'temporarymunicipality_vdc',
                                    'placeholder' => 'Enter Municipality/Vdc',
                                    'class' => 'form-control',
                                    $isEmployee ? 'readonly' : '',
                                ],
                            ) !!}
                        @else
                            {!! Form::text('temporarymunicipality_vdc', $value = null, [
                                'id' => 'temporarymunicipality_vdc',
                                'placeholder' => 'Enter Municipality/Vdc',
                                'class' => 'form-control',
                                $isEmployee ? 'readonly' : '',
                            ]) !!}
                        @endif


                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-lg-3">Ward No:</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-flip-horizontal2"></i></span>
                        </span>
                        @if (isset($employeeaddress->temporaryward) && !is_null($employeeaddress->temporaryward))
                            {!! Form::text(
                                'temporaryward',
                                $is_edit == true ? ($value = $employeeaddress->temporaryward) : ($value = null),
                                [
                                    'id' => 'temporaryward',
                                    'placeholder' => 'Enter Ward No.',
                                    'class' => 'form-control',
                                    $isEmployee ? 'readonly' : '',
                                ],
                            ) !!}
                        @else
                            {!! Form::text('temporaryward', $value = null, [
                                'id' => 'temporaryward',
                                'placeholder' => 'Enter Ward No.',
                                'class' => 'form-control',
                                $isEmployee ? 'readonly' : '',
                            ]) !!}
                        @endif


                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-lg-3">Address:</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-location3"></i></span>
                        </span>
                        @if (isset($employeeaddress->temporaryaddress) && !is_null($employeeaddress->temporaryaddress))
                            {!! Form::text(
                                'temporaryaddress',
                                $is_edit == true ? ($value = $employeeaddress->temporaryaddress) : ($value = null),
                                [
                                    'id' => 'temporaryaddress',
                                    'placeholder' => 'Enter City',
                                    'class' => 'form-control',
                                    $isEmployee ? 'readonly' : '',
                                ],
                            ) !!}
                        @else
                            {!! Form::text('temporaryaddress', $value = null, [
                                'id' => 'temporaryaddress',
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
                        @if (isset($employeeaddress->temporary_latitude) && !is_null($employeeaddress->temporary_latitude))
                            {!! Form::text(
                                'temporary_latitude',
                                $is_edit == true ? ($value = $employeeaddress->temporary_latitude) : ($value = null),
                                [
                                    'id' => 'temporary_latitude',
                                    'placeholder' => 'Enter Latitude',
                                    'class' => 'form-control',
                                    $isEmployee ? 'readonly' : '',
                                ],
                            ) !!}
                        @else
                            {!! Form::text('temporary_latitude', $value = null, [
                                'id' => 'temporary_latitude',
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
                        @if (isset($employeeaddress->temporary_longitude) && !is_null($employeeaddress->temporary_longitude))
                            {!! Form::text(
                                'temporary_longitude',
                                $is_edit == true ? ($value = $employeeaddress->temporary_longitude) : ($value = null),
                                [
                                    'id' => 'temporary_longitude',
                                    'placeholder' => 'Enter Longitude',
                                    'class' => 'form-control',
                                    $isEmployee ? 'readonly' : '',
                                ],
                            ) !!}
                        @else
                            {!! Form::text('temporary_longitude', $value = null, [
                                'id' => 'temporary_longitude',
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
        $('document').ready(function() {
            $('#temporaryprovince').on('change', function() {
                const province_id = $('#temporaryprovince').val();
                $.ajax({
                    type: 'GET',
                    url: '/admin/employee/getdistrict/' + province_id,
                    success: function(resp) {
                        $('#temporarydistrict').empty();
                        $('#temporarydistrict').html(resp);
                    }
                });

            });
        });
    </script>
