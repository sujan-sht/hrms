<style>
    .list-unstyled li {
        padding-top: 10px;

    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="form-row">
                    <div class="form-group col-md-4">

                        {!! Form::label('employee_name', 'Employee Name') !!}
                        {!! Form::select('employee_id', $employees, request()->get('employee_id') ?
                        request()->get('employee_id') : null, [
                        'id' => 'employee_id',
                        'class' => 'form-control select-search',
                        'data-toggle' => 'select2',
                        'placeholder' => 'Select Employee'
                        ]) !!}
                        @error('employee_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('department', 'Department') !!}
                        {!! Form::text('department', null, ['class' => 'form-control' . ($errors->has('department') ? '
                        is-invalid' : '')]) !!}
                        @error('department')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('designation', 'Designation') !!}
                        {!! Form::text('designation', null, ['class' => 'form-control' . ($errors->has('designation') ?
                        ' is-invalid' : '')]) !!}
                        @error('designation')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('expenses_type', 'Expenses Type') !!}
                        {!! Form::select('expenses_type', $travelTypes, null, [
                        'class' => 'form-control' . ($errors->has('expenses_type') ? ' is-invalid' : ''),
                        'placeholder' => 'Select expenses type'
                        ]) !!}
                        @error('expenses_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('from_date', 'From Date') !!}
                        {!! Form::date('from_date', null, ['class' => 'form-control' . ($errors->has('from_date') ? '
                        is-invalid' : '')]) !!}
                        @error('from_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('to_date', 'To Date') !!}
                        {!! Form::date('to_date', null, ['class' => 'form-control' . ($errors->has('to_date') ? '
                        is-invalid' : '')]) !!}
                        @error('to_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('departure', 'Departure') !!}
                        {!! Form::text('departure', null, ['class' => 'form-control' . ($errors->has('departure') ? '
                        is-invalid' : '')]) !!}
                        @error('departure')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('destination', 'Destination') !!}
                        {!! Form::text('destination', null, ['class' => 'form-control' . ($errors->has('destination') ?
                        ' is-invalid' : '')]) !!}
                        @error('destination')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('purpose', 'Purpose') !!}
                        {!! Form::text('purpose', null, ['class' => 'form-control' . ($errors->has('purpose') ? '
                        is-invalid' : '')]) !!}
                        @error('purpose')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                </div>
            </div>
        </div>

        <div class="row">

            @if(!$travelExpense)
            <div class="card mt-4">
                <div class="card-header">
                    <strong>Expense Details</strong>
                </div>
                <div class="card-body">
                    {{-- Header Row --}}
                    <div class="row fw-bold mb-2 btn-info p-1 text-white">
                        <div class="col-md-1">ER Type</div>
                        <div class="col-md-1">Location</div>
                        <div class="col-md-1">Date</div>
                        <div class="col-md-1">Ticket/Bill No</div>
                        <div class="col-md-1">Expenses Head</div>
                        <div class="col-md-1">Conversion Rate</div>
                        <div class="col-md-1">Currency</div>
                        <div class="col-md-1">Amount</div>
                        <div class="col-md-2">Remark</div>
                        <div class="col-md-1">Action</div>
                    </div>

                    {{-- Repeater Container --}}

                    <div id="expense-repeater">
                        <div class="row mb-2 expense-row">
                            <div class="col-md-1">
                                {!! Form::select('er_type[]', $erTypes, null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-1">
                                {!! Form::text('location[]', null, ['class' => 'form-control form-control-sm',
                                'placeholder' => 'Location']) !!}
                            </div>
                            <div class="col-md-1">
                                {!! Form::date('date[]', null, ['class' => 'form-control form-control-sm']) !!}
                            </div>
                            <div class="col-md-1">
                                {!! Form::text('ticket_bill_no[]', null, ['class' => 'form-control form-control-sm',
                                'placeholder' => 'Ticket/Bill']) !!}
                            </div>
                            <div class="col-md-1">
                                {!! Form::select('expenses_head[]', $expenseHeads, null, ['class' => 'form-control'])
                                !!}
                            </div>
                            <div class="col-md-1">
                                {!! Form::text('conversion_rate[]', null, ['class' => 'form-control form-control-sm',
                                'placeholder' => 'Rate']) !!}
                            </div>
                            <div class="col-md-1">
                                <select name="foreign_currency_type[]" class="form-control">
                                    @forelse($currencyLists as $currency)
                                    <option value="{{ $currency->dropvalue }}">{{ $currency->dropvalue }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-1">
                                {!! Form::text('amount[]', null, ['class' => 'form-control ', 'placeholder' =>
                                'Amount']) !!}
                            </div>
                            <div class="col-md-2">
                                {!! Form::text('remark[]', null, ['class' => 'form-control', 'placeholder' => 'Remark'])
                                !!}
                            </div>
                            <div class="col-md-1 d-flex align-items-center">
                                {{-- <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button> --}}
                            </div>
                        </div>
                    </div>


                    {{-- Add More and Total --}}
                    <div class="row mt-3">
                        <div class="col-md-8">
                            <button type="button" class="btn btn-secondary btn-sm" id="add-expense-row">+ Add
                                More</button>
                        </div>
                        <div class="col-md-2">
                            {!! Form::label('total_amount', 'Total Amount') !!}
                            {!! Form::text('total_amount', null, ['class' => 'form-control form-control-sm']) !!}
                            @error('total_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            @else

            <div class="card mt-4">
                <div class="card-header">
                    <strong>Expense Details</strong>
                </div>
                <div class="card-body">
                    {{-- Header Row --}}
                    <div class="row fw-bold mb-2 btn-info p-1 text-white">
                        <div class="col-md-1">ER Type</div>
                        <div class="col-md-1">Location</div>
                        <div class="col-md-1">Date</div>
                        <div class="col-md-1">Ticket/Bill No</div>
                        <div class="col-md-1">Expenses Head</div>
                        <div class="col-md-1">Conversion Rate</div>
                        <div class="col-md-1">Currency</div>
                        <div class="col-md-1">Amount</div>
                        <div class="col-md-2">Remark</div>
                        <div class="col-md-1">Action</div>
                    </div>

                    {{-- Repeater Container --}}
                    @php

                    $travelExpDetails = $travelExpense->expense_details;
                    $travelExpDetails = json_decode($travelExpDetails, true) ?? []; // already decoded as array
                    $count = count($travelExpDetails['er_type'] ?? []) ?: 0;

                    @endphp
                    <div id="expense-repeater">
                    @for ($i = 0; $i < $count; $i++)
                    <div class="row mb-3 expense-row">
                        <div class="col-md-1">
                            {!! Form::select('er_type[]', $erTypes, $travelExpDetails['er_type'][$i] ?? null, ['class'
                            => 'form-control']) !!}
                        </div>
                        <div class="col-md-1">
                            {!! Form::text('location[]', $travelExpDetails['location'][$i] ?? '', ['class' =>
                            'form-control form-control-sm', 'placeholder' => 'Location']) !!}
                        </div>
                        <div class="col-md-1">
                            {!! Form::date('date[]', $travelExpDetails['date'][$i] ?? '', ['class' => 'form-control
                            form-control-sm']) !!}
                        </div>
                        <div class="col-md-1">
                            {!! Form::text('ticket_bill_no[]', $travelExpDetails['ticket_bill_no'][$i] ?? '', ['class'
                            => 'form-control form-control-sm', 'placeholder' => 'Ticket/Bill']) !!}
                        </div>
                        <div class="col-md-1">
                            {!! Form::select('expenses_head[]', $expenseHeads, $travelExpDetails['expenses_head'][$i] ??
                            null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-1">
                            {!! Form::text('conversion_rate[]', $travelExpDetails['conversion_rate'][$i] ?? '', ['class'
                            => 'form-control form-control-sm', 'placeholder' => 'Rate']) !!}
                        </div>
                        <div class="col-md-1">
                            <select name="foreign_currency_type[]" class="form-control">
                                @forelse($currencyLists as $currency)
                                <option value="{{ $currency->dropvalue }}" {{
                                    ($travelExpDetails['foreign_currency_type'][$i] ?? '' )==$currency->dropvalue ?
                                    'selected' : '' }}>
                                    {{ $currency->dropvalue }}
                                </option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-1">
                            {!! Form::text('amount[]', $travelExpDetails['amount'][$i] ?? '', ['class' =>
                            'form-control', 'placeholder' => 'Amount']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::text('remark[]', $travelExpDetails['remark'][$i] ?? '', ['class' =>
                            'form-control', 'placeholder' => 'Remark']) !!}
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                        </div>
                </div>
                @endfor
                </div>



                {{-- Add More and Total --}}
                <div class="row mt-3">
                    <div class="col-md-8">
                        <button type="button" class="btn btn-secondary btn-sm" id="add-expense-row">+ Add
                            More</button>
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('total_amount', 'Total Amount') !!}
                        {!! Form::text('total_amount', null, ['class' => 'form-control form-control-sm','id' => 'total_amount']) !!}
                        @error('total_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        @endif




    </div>

    <div class="text-center mt-3 mb-5">
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                    class="icon-backward2"></i></b>Go Back</a>
        <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                    class="icon-database-insert"></i></b>{{ $btnType }}</button>
    </div>
</div>


</div>

<script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script>
    const erTypes = @json($erTypes);
    const expenseHeads = @json($expenseHeads);
    const currencyOptions = `
        @forelse($currencyLists as $currency)
            <option value="{{ $currency->dropvalue }}">{{ $currency->dropvalue }}</option>
        @empty
            <option disabled>No currencies found</option>
        @endforelse
    `;

    $(document).ready(function () {

        function buildOptions(options) {
            let html = '';
            for (const key in options) {
                html += `<option value="${key}">${options[key]}</option>`;
            }
            return html;
        }

        // Function to calculate total amount
        function updateTotalAmount() {
            let total = 0;
            $('#expense-repeater .expense-row').each(function () {
                let amount = parseFloat($(this).find('input[name="amount[]"]').val());
                if (!isNaN(amount)) {
                    total += amount;
                }
            });
            $('#total_amount').val(total.toFixed(2)); // Update total field
        }

        // Add new row
        $('#add-expense-row').click(function () {
            let erTypeOptions = buildOptions(erTypes);
            let expenseHeadOptions = buildOptions(expenseHeads);

            let newRow = `
            <div class="expense-row row mb-3">
                <div class="form-group col-md-1">
                    <select name="er_type[]" class="form-control">
                        ${erTypeOptions}
                    </select>
                </div>
                <div class="form-group col-md-1">
                    <input type="text" name="location[]" class="form-control">
                </div>
                <div class="form-group col-md-1">
                    <input type="date" name="date[]" class="form-control">
                </div>
                <div class="form-group col-md-1">
                    <input type="text" name="ticket_bill_no[]" class="form-control">
                </div>
                <div class="form-group col-md-1">
                    <select name="expenses_head[]" class="form-control">
                        ${expenseHeadOptions}
                    </select>
                </div>
                <div class="form-group col-md-1">
                    <input type="text" name="conversion_rate[]" class="form-control">
                </div>
                <div class="form-group col-md-1">
                    <select name="currency[]" class="form-control"> <!-- corrected duplicate name -->
                        ${currencyOptions}
                    </select>
                </div>
                <div class="form-group col-md-1">
                    <input type="text" name="amount[]" class="form-control amount-field">
                </div>
                <div class="form-group col-md-2">
                    <input type="text" name="remark[]" class="form-control">
                </div>
                <div class="form-group col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                </div>
            </div>`;

            $('#expense-repeater').append(newRow);
            updateTotalAmount(); // Update total after adding new row
        });

        // Remove row
        $(document).on('click', '.remove-row', function () {
            $(this).closest('.expense-row').remove();
            updateTotalAmount(); // Update total after removing a row
        });

        // Listen for amount changes
        $(document).on('input', '.amount-field', function () {
            updateTotalAmount(); // Update total when any amount changes
        });

    });
</script>
