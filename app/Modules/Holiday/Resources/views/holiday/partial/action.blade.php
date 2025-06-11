<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Apply For All :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('apply_for_all', [10 => 'No', 11 => 'Yes'], null, [
                                        'class' => 'form-control select-search apply-for-all',
                                    ]) !!}
                                </div>
                                @if ($errors->has('apply_for_all'))
                                    <div class="error text-danger">{{ $errors->first('apply_for_all') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 all">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Organization : <span class="text-danger">*</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('organization_id', $organizationList,
                                        $value = count($organizationList) === 1 ? array_key_first($organizationList->toArray()) : null,
                                    [
                                        'class' => 'form-control select-filter1',
                                        'placeholder'=>'Select Organization',
                                        'id'=>'organizationId',
                                        'required'
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-group row all">
                    @if(count($managedGroupIds))
                        @foreach ($managedGroupIds as $key => $data)
                            <div class="row clone-div col-lg-10 clone-div{{$key}}">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <label class="col-form-label col-lg-4">Provinces :<span class="text-danger"> *</span></label>
                                        <div class="col-lg-8 form-group-feedback">
                                            <div class="input-group">
                                                {!! Form::select('province_id[]', $province ?? [], $data['province_id']??null, [
                                                    'placeholder' => 'Select Provinces',
                                                    'data-key' =>$key,
                                                    "class"=>"form-control select-search provinceSelectEdit",
                                                    'required'
                                                ]) !!}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <label class="col-form-label col-lg-4">District :<span class="text-danger"> *</span></label>
                                        <div class="col-lg-8 form-group-feedback">
                                            <div class="input-group">
                                                {!! Form::select('district_id[]', $districtList ?? [], $data['district_id']??null, [
                                                    // 'placeholder' => 'Select District',
                                                    'class'=>'form-control multiselect-select-all-filtering districtSelect'.$key,
                                                    'data-selected-districts' => $data['district_id']??null,
                                                    'multiple' => 'multiple',
                                                    'required'
                                                ]) !!}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($key == 0)
                                <div class="col-lg-2">
                                    <label for="" class="btn btn-success rounded-pill plus"><i class="icon-plus-circle2 mr-1"></i> Add More </label>
                                </div>
                            @else
                                <div class="col-lg-2 clone-div{{$key}}">
                                    <label for="" class="btn btn-danger rounded-pill minusKey" data-key="{{'clone-div'.$key}}"><i class="icon-minus-circle2 mr-1"></i> Remove</label>
                                </div>
                            @endif
                        @endforeach

                    @else
                    <div class="row clone-div col-lg-10">
                        <div class="col-lg-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Provinces :<span class="text-danger"> *</span></label>
                                <div class="col-lg-8 form-group-feedback">
                                    <div class="input-group">
                                        {!! Form::select('province_id[]', $province ?? [], null, [
                                            'placeholder' => 'Select Provinces',
                                            'class'=>'form-control select-search provinceSelect',
                                            'required'
                                        ]) !!}

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <label class="col-form-label col-lg-4">District :<span class="text-danger"> *</span></label>
                                <div class="col-lg-8 form-group-feedback">
                                    <div class="input-group">
                                        {!! Form::select('district_id[]', $districtList ?? [], null, [
                                            'class'=>'form-control multiselect-select-all-filtering districtSelect',
                                            'multiple' => 'multiple',
                                            'required'
                                        ]) !!}

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <label for="" class="btn btn-success rounded-pill plus"><i class="icon-plus-circle2 mr-1"></i> Add More</label>
                    </div>
                    @endif
                </div>
                <div class="cloneData" style="margin-top: -20px;"></div>




                {{-- @endif --}}
                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class=" row">
                            <label class="col-form-label col-lg-4">Fiscal Year:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('fiscal_year_id', $fiscalYearList,$currentFiscalyear, ['class' => 'form-control select-search']) !!}
                                </div>
                                @if ($errors->has('fiscal_year_id'))
                                    <div class="error text-danger">{{ $errors->first('fiscal_year_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4">Calendar Type:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('calendar_type', $calendar_type, null, [
                                        'class' => 'form-control select-search',
                                        'id' => 'calendar_type',
                                    ]) !!}
                                </div>
                                @if ($errors->has('calendar_type'))
                                    <div class="error text-danger">{{ $errors->first('calendar_type') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group append-clone">
                    @if (isset($holidayDetails) && count($holidayDetails) > 0)
                        @foreach ($holidayDetails as $key => $holidayDetail)
                            @include('holiday::holiday.partial.clone', [
                                'btnType' => 'Edit',
                                'count' => $key,
                                'calendar_type' => $holiday->calendar_type,
                                'holidayDetail' => $holidayDetail,
                            ])
                        @endforeach
                    @else
                        @include('holiday::holiday.partial.clone', [
                            'btnType' => 'Edit',
                            'count' => 0,
                            'calendar_type' => 1,
                        ])
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Other Detail</legend>
                {{-- @dd($holiday->gender_type) --}}
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Gender:<span class="text-danger">
                            *</span></label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::select('gender_type', $gender_type, null, [
                                'class' => 'form-control select-search',
                            ]) !!}
                        </div>
                        @if ($errors->has('gender_type'))
                            <div class="error text-danger">{{ $errors->first('gender_type') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Religion:<span class="text-danger">
                        *</span></label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::select('religion_type', $religion_type, null, [
                                'class' => 'form-control select-search',
                            ]) !!}
                        </div>
                        @if ($errors->has('religion_type'))
                            <div class="error text-danger">{{ $errors->first('religion_type') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Status:<span class="text-danger">
                            *</span></label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::select('status', [11 => 'Active', 10 => 'Inactive'], null, ['class' => 'form-control select-search']) !!}
                        </div>
                        @if ($errors->has('status'))
                            <div class="error text-danger">{{ $errors->first('status') }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Is Festival Holiday?:<span class="text-danger">
                            *</span></label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::select('is_festival', [10 => 'No', 11 => 'Yes'], null, ['class' => 'form-control select-search']) !!}
                        </div>
                        @if ($errors->has('is_festival'))
                            <div class="error text-danger">{{ $errors->first('is_festival') }}</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

<script>
    $(document).ready(function() {

        $('.select-search').select2();

        // var btn_delete =
        //     '<button type="button" class="btn btn-sm btn-danger btn-remove" ><i class="icon-trash"></i></button>';

        $('.btn-clone').on('click', function() {
            // var clone = $(this).parent().parent();
            // var appendClone = clone.clone().appendTo(".append-clone");
            // appendClone.find("button").replaceWith(btn_delete);
            // appendClone.find(".col-lg-3").empty();
            // console.log(count);
            appendClone();
        })

        function appendClone() {
            calendar_type = $('#calendar_type option:selected').val();
            count = $('.clone-div').length;

            $.ajax({
                type: "get",
                url: "{{ route('holiday.clone.day') }}",
                data: {
                    calendar_type: calendar_type,
                    count: count,
                },
                success: function(res) {
                    $('.append-clone').append(res.data);

                }
            });
        }

        $(document).on('click', '.btn-remove', function() {
            var parent = $(this).parent().parent();
            parent.remove();
        })


        $('#calendar_type').on('change', function() {
            value = $(this).val();
            holiday_date = $('input[name="holiday_date"]');
            if (value == 1) {
                $('.nep_date').removeClass('d-none').find('input').attr('required', true);
                $('.eng_date').addClass('d-none').find('input').attr('required', false);
                // engDatePicker('daterange-eng-single');
            } else {
                $('.eng_date').removeClass('d-none').find('input').attr('required', true);
                $('.nep_date').addClass('d-none').find('input').attr('required', false);
                // nepDatePicker('daterange-nep-single');
            }
        })

        $('#calendar_type').trigger('change');

        nepDatePicker('daterange-nep-single');

        function nepDatePicker(element) {
            var dobInput = $('.' + element);
            dobInput.nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 10
            });
        }

        $(".apply-for-all").change(function() {
            if ($(this).val() == 10) {
                $('.all').show();
            } else {
                $('.branch-filter2').val('').select2({
                    placeholder: 'Select Branch',
                });
                $('.organization-filter2').val('').select2({
                    placeholder: 'Select Organization',
                });
                $('.all').hide();
            }
        });

        $(".apply-for-all").trigger('change');


        $('#organizationId').on('change', function() {
            var organization_id = $('#organizationId').val();

            $.ajax({
                url: "{{ url('admin/holiday/getOrganizationBranch') }}", // Add this route in your web.php
                method: 'GET',
                data: {
                    organization_id: organization_id
                },
                success: function(data) {
                    $('#branchId').empty();
                    $.each(data, function(id, name) {
                        $('#branchId').append(new Option(name, id));
                    });
                    $('#branchId').multiselect('rebuild');
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + error.message);
                }
            });
        });

    });
</script>

<script>
    let sectionIndex = 1;

    $(document).ready(function() {


        $('#addFormSection').on('click', function() {
            $('#dynamic-form-container').append(getFormSectionTemplate(sectionIndex));
            sectionIndex++;
        });

        $(document).on('click', '.removeSection', function() {
            $(this).closest('.form-group.row.all').remove();
        });
    });


    function getFormSectionTemplate(index) {
        return `
            <div class="form-group row all" data-index="${index}">
                <div class="col-lg-5">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Organization: <span class="text-danger">*</span></label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <select name="organization_id[]" class="form-control select-filter1 org_id" data-ind="${index}" required>
                                    <option value="">Select Organization</option>
                                    @foreach ($organizationList as $key => $organization)
                                    <option value="{{ $key }}">{{ $organization }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Branch:</label>
                        <div class="col-lg-8 form-group-feedback">
                            <div class="input-group">
                                <select name="branchId[${index}][]" class="form-control branchId multiselect-select-all-filtering" data-ind="${index}" required >
                                    <option value="">Select Branch</option>
                                    <!-- Populate branch options dynamically -->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Remove button for dynamically added sections -->
                <button type="button" class="btn btn-danger removeSection">Remove</button>
            </div>
        `;
    }
</script>

<script>
    $(document).on('change', '.org_id', function() {
        var index = $(this).data('ind');
        var organization_id = $(this).val();

        console.log(organization_id);
        $.ajax({
            url: "{{ url('admin/holiday/getOrganizationBranch') }}",
            method: 'GET',
            data: {
                organization_id: organization_id
            },
            success: function(data) {
                const branchSelect = $(`.branchId[data-ind="${index}"]`);

                branchSelect.empty();
                branchSelect.attr('multiple', 'multiple');

                branchSelect.multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true
                });

                $.each(data, function(id, name) {
                    branchSelect.append(new Option(name, id));
                });

                branchSelect.multiselect('rebuild');
            },
            error: function(xhr, status, error) {
                console.log('Error: ' + error.message);
            }
        });
    });
</script>
