@inject('income_setups', '\App\Modules\Payroll\Repositories\IncomeSetupRepository')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="form-group row">
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Organization:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('organization_id', $organizationList, null, [
                                        // 'placeholder' => 'Select Organization',
                                        'class' => 'form-control select-search organization','required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('organization_id'))
                                    <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Deduct From :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                @php
                                    if($isEdit) {
                                        if(count($leaveAmountSetupModel->leaveAmountDetail) > 0) {
                                            foreach($leaveAmountSetupModel->leaveAmountDetail as $model) {
                                                $incomeValues[] = $model->income_setup_id;
                                            }
                                        }else{
                                        $incomeValues = null;
                                        }
                                    } else {
                                        $incomeValues = null;
                                    }
                                // dd($incomeValues)
                                @endphp
                                <div class="input-group col-lg-10 income">
                                    {!! Form::select('income_id[]', [], $incomeValues, [
                                        'id' => 'incomeId',
                                        'class' => 'form-control income-filter multiselect-select-all-filtering','multiple',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                   

                </div>
            </div>
        </div>
    </div>
    </div>
   

<div class="text-center">
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <script src="{{ asset('admin/validation/deductionSetup.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>

    {{-- <script src="https://cdn.tiny.cloud/1/cjrqkjizx7e1ld0p8kcygaj4cvzc6drni6o4xl298c5hl9l1/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script> --}}
    <script>
        $(document).ready(function() {
            $('.organization').on('change', function() {
                getIncomesByOrganization();
            });
            $('.organization').trigger('change');
        });


        function getIncomesByOrganization() {
            var incomeValues = {!! json_encode($incomeValues) !!};
            var organizationId = $('.organization').val();
            var incomeId = $('#income_id').val();
            $.ajax({
                type: 'GET',
                url: '/admin/deduction-setup/get-incomes',
                data: {
                    organization_id: organizationId
                },
                success: function(data) {
                    var list = JSON.parse(data);
                    var options = '';
                    $('.income-filter').attr('multiple', 'multiple');

                    $.each(list, function(id, value) {
                        options += "<option value='" + id + "'  >" + value + "</option>";
                    });

                    $('.income-filter').html(options);

                    $('.income-filter').multiselect('rebuild', {
                        enableFiltering: true,
                        filterPlaceholder: 'Search...',
                        enableCaseInsensitiveFiltering: true
                    });

                    // Reselect previously selected values
                    $.each(incomeValues, function(index, value) {
                        $('.income-filter option[value="' + value + '"]').prop('selected', true);
                    });

                    // Trigger change event to update multiselect UI
                    $('.income-filter').multiselect('refresh');
                }
            });
        }
    </script>
@endSection
