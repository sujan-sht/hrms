<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="form-group row">
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Organization:<span class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                        {{-- {!! Form::select('organization_id', $organizationList, null, ['class' => 'form-control select-search organization-filter','id' => 'organization_id','placeholder' => 'Select organization','required']) !!} --}}
                                        @if(count($organizationList) === 1)
                                        @php
                                            $orgGroup = $organizationList->first();
                                        @endphp
                                        @if($orgGroup)
                                            {!! Form::select('organization_id', @$organizationList, $value = $orgGroup->id ?? null, [
                                            'id' => 'organization_id',
                                            'class' => 'form-control select-search organization-filter',
                                            'required',
                                            'placeholder' => 'Select organization'
                                        ]) !!}

                                        @endif
                                    @else
                                        {!! Form::select('organization_id', @$organizationList, $value = $orgGroup->id ?? null, [
                                            'id' => 'organization_id',
                                            'class' => 'form-control select-search organization-filter',
                                            'placeholder' => 'Select Organization',
                                            'required'
                                        ]) !!}
                                    @endif
                                    </div>
                                    @if ($errors->has('organization_id'))
                                        <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                                    @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Employee:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('employee_id', [], null, ['class' => 'form-control select-search employee-filter','placeholder' => 'Select Employee','required']) !!}
                                </div>
                                @if ($errors->has('employee_id'))
                                    <div class="error text-danger">{{ $errors->first('employee_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Select Year:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {{-- {!! Form::select('month', $nepaliMonthList, null, ['class' => 'form-control select-search','placeholder' => 'Select Month','required']) !!} --}}
                                    {!! Form::select('year', $nepaliYearList, null, ['id'=>'year','class' => 'form-control select-search','placeholder' => 'Select Year','required']) !!}
                                </div>
                                @if ($errors->has('year'))
                                    <div class="error text-danger">{{ $errors->first('year') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Select Month:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {{-- {!! Form::select('month', $nepaliMonthList, null, ['class' => 'form-control select-search','placeholder' => 'Select Month','required']) !!} --}}
                                    {!! Form::select('month', [], null, ['id'=>'month','class' => 'form-control select-search','placeholder' => 'Select Month','required']) !!}
                                </div>
                                @if ($errors->has('month'))
                                    <div class="error text-danger">{{ $errors->first('month') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                <legend class="text-uppercase font-size-sm font-weight-bold">Arrear Detail</legend>
                <div class="append-income">
                    <div class="form-group row income">
                        <div class="col-lg-4 mb-3">
                            <div class="row">
                                <label class="col-form-label col-lg-3">Incomes:<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('income_setup_id[0]', [], null, ['class' => 'form-control select-search income-filter','placeholder' => 'Select Incomes','required']) !!}
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
                                        {!! Form::text('arrear_amount[0]', null, ['class' => 'form-control numeric','placeholder' => 'Enter arrear Amount','required']) !!}
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
                                        {!! Form::select('income_type[0]', ['add' => 'Add', 'sub' => 'Sub'], null, ['class' => 'form-control select-search','placeholder' => 'Select Income Type','required']) !!}

                                    </div>
                                    @if ($errors->has('income_type'))
                                        <div class="error text-danger">{{ $errors->first('income_type') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 mb-3">
                            <div class="row">
                                <button type="button" class="add_particular btn bg-success-400 btn-icon text-white"
                                    id="addMore">
                                    <i class="icon-plus3"></i><b></b>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center pt-1 pb-3 pl-3 pr-3">
    <button class="btn bg-teal text-white" type="submit">Save Changes</button>
</div>
<script>
    var selectedEmployee=null;
    $(document).ready(function() {
        let numberIncr = 1;
        const selectedIncomes = new Set(); // Track selected incomes

        // Function to update selected incomes
        function updateSelectedIncomes() {
            selectedIncomes.clear();
            $('.income-filter').each(function () {
                const value = $(this).val();
                if (value) selectedIncomes.add(value);
            });
        }

        const updateMonth=()=>{
            var organization_id = $('#organization_id').val()
            var year=$('#year').val();
            if(organization_id && year){
                $.ajax({
                    type: 'GET',
                    url: "{{route('updateMonth')}}",
                    data: {
                        organization_id: organization_id,
                        year:year
                    },
                    success: function(data) {
                        var list = JSON.parse(data);
                        var options = '';
                        options += "<option value=''>Select Month</option>";
                        $.each(list, function(id, value) {
                            options += "<option value='" + id + "'  >" + value + "</option>";
                        });
                        $('#month').html(options);
                    }
                });
            }else{
                $('#month').html('');
            }

        }

        $('#year').on('change',function(){
            updateMonth();
        });
        $('#organization_id').on('change', function() {
            var organization_id = $(this).val()
            $.ajax({
                type: 'GET',
                url: "{{route('deductionSetup.getIncomeTypes')}}",
                data: {
                    organization_id: organization_id,
                },
                success: function(data) {
                    var list = JSON.parse(data);
                    var options = '';

                    options += "<option value=''>Select Incomes</option>";
                    $.each(list, function(id, value) {
                        options += "<option value='" + id + "'  >" + value + "</option>";
                    });
                    $('.income-filter').html(options);
                    updateMonth();
                }
            });
        });



        $('#addMore').on('click', function () {
            const organizationId = $('#organization_id').val();

            if (!organizationId) {
                alert('Please select an organization first.');
                return;
            }

            updateSelectedIncomes(); // Update selected incomes before the request

            $.ajax({
                type: 'GET',
                url: "{{ route('arrearAdjustment.addIncome') }}",
                data: {
                    organization_id: organizationId,
                    numberIncr: numberIncr,
                    selectedIncomes: Array.from(selectedIncomes) // Send selected incomes to the backend
                },
                success: function (data) {
                    numberIncr++;
                    $('.append-income').append(data.options);
                },
                error: function () {
                    alert('An error occurred while adding income.');
                }
            });
        });

        $(document).on('change', '.income-filter', function () {
            updateSelectedIncomes();
        });

    });
</script>
