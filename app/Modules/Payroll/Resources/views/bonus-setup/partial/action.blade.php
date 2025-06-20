@inject('income_setups', '\App\Modules\Payroll\Repositories\IncomeSetupRepository')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="form-group row">
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Organization:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('organization_id', $organizationList, null, [
                                        'class' => 'form-control organization  select-search', 
                                        'id' => 'organization', 
                                        // 'placeholder' => 'Select Organization'
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
                            <label class="col-form-label col-lg-4">Title : <span class="text-danger">*</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('title', null, ['placeholder' => 'Enter Title', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('title'))
                                    <div class="error text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Short Name : <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    @if ($isEdit)
                                        {!! Form::text('short_name', null, ['placeholder' => 'Enter Short Name', 'readonly', 'class' => 'form-control']) !!}
                                    @else
                                        {!! Form::text('short_name', null, ['placeholder' => 'Enter Short Name', 'class' => 'form-control']) !!}
                                    @endif
                                </div>
                                @if ($errors->has('short_name'))
                                    <div class="error text-danger">{{ $errors->first('short_name') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Method :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('method', [1 => 'Fixed', 2 => 'Percentage'], null, [
                                        'class' => 'form-control select-search chooseMethod',
                                    ]) !!}
                                </div>
                                @if ($errors->has('method'))
                                    <div class="error text-danger">{{ $errors->first('method') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3 amountSection" style="display:none;">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Amount :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('amount', null, ['placeholder' => 'e.g. 2000', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('amount'))
                                    <div class="error text-danger">{{ $errors->first('amount') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($isEdit)
                    @php
                        $params = ['organizationId' => $bonusSetupModel->organization_id];
                        $incomeList = $income_setups->getList($params);
                        // dd($incomeList);
                    @endphp
                    <div class="col-lg-6 mb-3 percentageSection" style="display:none;">
                        @foreach ($bonusSetupModel->bonusDetail as $key => $value)
                            <div class="row parent mt-2">
                                <label class="col-form-label col-lg-3">Percentage :</label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="row">
                                        <div class="input-group col-lg-3">
                                            {!! Form::text('percentage[]', $value->percentage, ['placeholder' => 'e.g. 10.2', 'class' => 'form-control']) !!}
                                        </div>
                                        @if ($errors->has('percentage'))
                                            <div class="error text-danger">{{ $errors->first('percentage') }}</div>
                                        @endif
                                        <div class="col-lg-2 col-form-label">
                                            <span> % of </span>
                                        </div>
                                        <div class="input-group col-lg-5 income">
                                            {!! Form::select('income_id[]', $incomeList, $value->income_id, [
                                                'id' => 'incomeId','placeholder' => 'Select Incomes',
                                                'class' => 'form-control select-search1 income-filter',
                                            ]) !!}
                                            {!! Form::hidden('income', $bonusSetupModel->income_id ?? '', [
                                                'id' => 'income_id',
                                                'class' => 'form-control select-search1 income-filter',
                                            ]) !!}
                                        </div>

                                        @if ($errors->has('income_id'))
                                            <div class="error text-danger">{{ $errors->first('income_id') }}</div>
                                        @endif
                                        <div class="input-group col-lg-2">
                                            <button type="button" class="remove btn bg-success-400 btn-icon">
                                                <i class="icon-minus-circle2"></i><b></b>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="row mt-2">
                            <label class="col-form-label col-lg-3">Percentage :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="row">
                                    <div class="input-group col-lg-3">
                                        {!! Form::text('percentage[]', '', ['placeholder' => 'e.g. 10.2', 'class' => 'form-control']) !!}
                                    </div>
                                    @if ($errors->has('percentage'))
                                        <div class="error text-danger">{{ $errors->first('percentage') }}</div>
                                    @endif
                                    <div class="col-lg-2 col-form-label">
                                        <span> % of </span>
                                    </div>
                                    <div class="input-group col-lg-5 income">
                                        {!! Form::select('income_id[]', $incomeList, null, [
                                            'id' => 'incomeId','placeholder' => 'Select Incomes',
                                            'class' => 'form-control select-search1 income-filter',
                                        ]) !!}
                                        {!! Form::hidden('income', $bonusSetupModel->income_id ?? '', [
                                            'id' => 'income_id',
                                            'class' => 'form-control select-search1 income-filter',
                                        ]) !!}
                                    </div>

                                    @if ($errors->has('income_id'))
                                        <div class="error text-danger">{{ $errors->first('income_id') }}</div>
                                    @endif
                                    <div class="input-group col-lg-2">
                                        <button type="button" class="add_particular btn bg-success-400 btn-icon"
                                            id="addMore">
                                            <i class="icon-plus3"></i><b></b>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-repeater"></div>
                        <div id="repeatForm" style="display:none;">
                            <div class="row parent mt-2">
                                <label class="col-form-label col-lg-3">Percentage :</label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="row">
                                        <div class="input-group col-lg-3">
                                            {!! Form::text('percentage[]', '', ['placeholder' => 'e.g. 10.2', 'class' => 'form-control']) !!}
                                        </div>
                                        @if ($errors->has('percentage'))
                                            <div class="error text-danger">{{ $errors->first('percentage') }}</div>
                                        @endif
                                        <div class="col-lg-2 col-form-label">
                                            <span> % of </span>
                                        </div>

                                        <div class="input-group col-lg-5 income">
                                            {!! Form::select('income_id[]', $incomeList, null, [
                                                'id' => 'incomeId','placeholder' => 'Select Incomes',
                                                'class' => 'form-control select-search1 income-filter',
                                            ]) !!}
                                            {!! Form::hidden('income', $bonusSetupModel->income_id ?? '', [
                                                'id' => 'income_id',
                                                'class' => 'form-control select-search1 income-filter',
                                            ]) !!}
                                        </div>

                                        @if ($errors->has('income_id'))
                                            <div class="error text-danger">{{ $errors->first('income_id') }}</div>
                                        @endif
                                        <div class="input-group col-lg-2">
                                            <button type="button" class="remove btn bg-success-400 btn-icon">
                                                <i class="icon-minus-circle2"></i><b></b>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-lg-6 mb-3 percentageSection" style="display:none;">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Percentage :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="row">
                                    <div class="input-group col-lg-3">
                                        {!! Form::text('percentage[]', null, ['placeholder' => 'e.g. 10.2', 'class' => 'form-control']) !!}
                                    </div>
                                    @if ($errors->has('percentage'))
                                        <div class="error text-danger">{{ $errors->first('percentage') }}</div>
                                    @endif
                                    <div class="col-lg-2 col-form-label">
                                        <span> % of </span>
                                    </div>
                                    {{-- <div class="input-group col-lg-5">
                                        {!! Form::select('salary_type', $salaryType, null, [
                                            'class' => 'form-control select-search',
                                        ]) !!}
                                    </div>

                                    @if ($errors->has('salary_type'))
                                        <div class="error text-danger">{{ $errors->first('salary_type') }}</div>
                                    @endif --}}
                                    <div class="input-group col-lg-5 income">
                                        {!! Form::select('income_id[]', [], null, [
                                            'id' => 'incomeId',
                                            'class' => 'form-control select-search1 income-filter',
                                        ]) !!}
                                        {!! Form::hidden('income', null, [
                                            'id' => 'income_id',
                                            'class' => 'form-control select-search1 income-filter',
                                        ]) !!}
                                        {{-- {!! Form::select('income_id', $incomeList, null, ['class' => 'form-control select-search']) !!} --}}
                                    </div>

                                    @if ($errors->has('income_id'))
                                        <div class="error text-danger">{{ $errors->first('income_id') }}</div>
                                    @endif
                                    <div class="input-group col-lg-2">
                                        <button type="button" class="add_particular btn bg-success-400 btn-icon"
                                            id="addMore">
                                            <i class="icon-plus3"></i><b></b>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-repeater"></div>
                        <div id="repeatForm" style="display:none;">
                            <div class="row parent mt-2">
                                <label class="col-form-label col-lg-3">Percentage :</label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="row">
                                        <div class="input-group col-lg-3">
                                            {!! Form::text('percentage[]', null, ['placeholder' => 'e.g. 10.2', 'class' => 'form-control']) !!}
                                        </div>
                                        @if ($errors->has('percentage'))
                                            <div class="error text-danger">{{ $errors->first('percentage') }}
                                            </div>
                                        @endif
                                        <div class="col-lg-2 col-form-label">
                                            <span> % of </span>
                                        </div>
                                        <div class="input-group col-lg-5 income">
                                            {!! Form::select('income_id[]', [], null, [
                                                'id' => 'incomeId',
                                                'class' => 'form-control select-search1 income-filter',
                                            ]) !!}
                                            {!! Form::hidden('income', null, [
                                                'id' => 'income_id',
                                                'class' => 'form-control select-search1 income-filter',
                                            ]) !!}
                                        </div>

                                        @if ($errors->has('income_id'))
                                            <div class="error text-danger">{{ $errors->first('income_id') }}</div>
                                        @endif
                                        
                                        <div class="input-group col-lg-2">
                                            <button type="button" class="remove btn bg-success-400 btn-icon">
                                                <i class="icon-minus-circle2"></i><b></b>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Description:</label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::textarea('description', null, [
                                        'placeholder' => 'Write here..',
                                        'class' => 'form-control basicTinymce1',
                                        'id' => 'editor-full',
                                    ]) !!}
                                </div>

                                @if ($errors->has('description'))
                                    <div class="error text-danger">{{ $errors->first('description') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- @php
        $taxApplicableMonth = $isEdit ? $bonusSetupModel->tax_applicable_month : null;
    @endphp --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Setting Detail</legend>
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Order : <span class="text-danger">*</span></label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::number('order', null, ['placeholder' => 'Enter Order', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('order'))
                                    <div class="error text-danger">{{ $errors->first('order') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Status :</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('status', [11 => 'Active', 10 => 'Inactive'], null, ['class' => 'form-control select-search']) !!}
                                </div>
                                @if ($errors->has('status'))
                                    <div class="error text-danger">{{ $errors->first('status') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">One time Settlement :</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('one_time_settlement', [10 => 'NO', 11 => 'Yes'], null, [
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                @if ($errors->has('one_time_settlement'))
                                    <div class="error text-danger">{{ $errors->first('one_time_settlement') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-12 mb-3 month" style="display:none;">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Tax Applicable Month :</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group engDiv" style="display: none;">
                                    {!! Form::select('eng_month', $monthList, $taxApplicableMonth, [
                                        'placeholder' => 'Select Month',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                <div class="input-group nepDiv" style="display: none;">
                                    {!! Form::select('month', $nepaliMonthList, $taxApplicableMonth, [
                                        'id' => 'nepMonth',
                                        'placeholder' => 'Select Month',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                @if ($errors->has('month'))
                                    <div class="error text-danger">{{ $errors->first('month') }}</div>
                                @endif
                            </div>
                        </div>
                    </div> --}}

                    <div class="col-lg-12" style="padding: 30px;"></div>
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
    <script src="{{ asset('admin/validation/bonusSetup.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/editor_ckeditor_default.js') }}"></script>

    {{-- <script src="https://cdn.tiny.cloud/1/cjrqkjizx7e1ld0p8kcygaj4cvzc6drni6o4xl298c5hl9l1/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script> --}}
    <script>
        $(document).ready(function() {
            // tinymce.init({
            //     selector: 'textarea.basicTinymce',
            //     plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            //     toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            //     height: '250',
            //     width: '100%'
            // });


            $('.chooseMethod').on('change', function() {
                let method = $(this).val();
                if (method == 2) {
                    $('.amountSection').hide();
                    $('.percentageSection').show();
                } else {
                    $('.amountSection').show();
                    $('.percentageSection').hide();
                }
            });
            $('.chooseMethod').trigger('change');
            $('#organization').on('change', function() {
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
                        options += "<option value=''>Select Incomes</option>";
                        $.each(list, function(id, value) {
                            if (id == incomeId) {
                                options += "<option value='" + id + "' selected>" + value +
                                    "</option>";
                            } else {
                                options += "<option value='" + id + "'>" + value +
                                    "</option>";
                            }

                        });

                        $('.income-filter').html(options);
                        // $('.income-filter')({
                        //     placeholder: "Select Incomes"
                        // });

                        // if (incomeId) {
                        //     $('.income-filter').val(incomeId);
                        //     $('.income-filter').attr("selected", true);
                        // }
                    }
                });
            });
            // $('#organization').trigger('change');
            $('#addMore').on('click', function() {
                var html = $('#repeatForm').html();
                $('.form-repeater').append(html);
            });
            $(document).on('click', '.remove', function() {
                $(this).closest('.parent').remove();
            });

        });
    </script>
@endSection
