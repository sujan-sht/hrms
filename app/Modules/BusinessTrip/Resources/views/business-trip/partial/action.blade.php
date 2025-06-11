<style>
    .list-unstyled li {
        padding-top: 10px;

    }
</style>
<div class="row">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                <div class="row">
                    @if (auth()->user()->user_type != 'employee')
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Employee<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::select(
                                        'employee_id',
                                        $employees,
                                        request()->get('employee_id') ? request()->get('employee_id') : null,
                                        [
                                            'id' => 'employee_id',
                                            'class' => 'form-control select-search',
                                            'data-toggle' => 'select2',
                                            'placeholder' => 'Select Employee',
                                        ],
                                    ) !!}
                                    @if ($errors->first('employee_id') != null)
                                        <ul class="parsley-errors-list filled" aria-hidden="false">
                                            <li class="parsley-required">{{ $errors->first('employee_id') }}</li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="employee_id" value="{{ auth()->user()->emp_id }}" id='employee_id'>
                    @endif

                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Functional Title<span class="text-danger"> </span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('title', request()->get('title') ? request()->get('title') : null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Title',
                                    'id' => 'ftitle',
                                ]) !!}

                                @if ($errors->first('title') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('title') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (auth()->user()->user_type != 'employee')
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Designation<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::text('designation', request()->get('designation') ? request()->get('designation') : null, [
                                        'placeholder' => '',
                                        'class' => 'form-control',
                                        'id' => 'designation',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    @endif


                    <div class="col-md-4  @if (auth()->user()->user_type != 'employee') mt-3 @endif">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Departure<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('departure', request()->get('departure') ? request()->get('departure') : null, [
                                    'placeholder' => '',
                                    'class' => 'form-control',
                                    'id' => 'departure',
                                ]) !!}
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4 @if (auth()->user()->user_type != 'employee') mt-3 @endif">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Travel Type<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::select('type_id', $travelTypes, null, [
                                    'class' => 'form-control select-search',
                                    'placeholder' => 'Select Type',
                                    'id' => 'travel-type',
                                ]) !!}

                                @if ($errors->first('type_id') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('type_id') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4  mt-3 ">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Transportation Type<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::select('transport_type', $transpotationTypes, null, [
                                    'class' => 'form-control select-search',
                                    'placeholder' => 'Select Transpotation Type',
                                ]) !!}

                            </div>
                        </div>
                    </div>



                    {{-- @if (setting('calendar_type') == 'BS') --}}
                    {{-- <div class="col-md-4 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">From Date<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('from_date_nep', request()->get('from_date_nep') ?
                                request()->get('from_date_nep') : null, [
                                'placeholder' => 'e.g: YYYY-MM-DD',
                                'class' => 'form-control nepali-calendar',
                                'id' => 'nepFromDate',
                                'readonly',
                                ]) !!}
                            </div>
                            <span class="errorStartDate"></span>
                        </div>
                    </div>

                    <div class="col-md-4 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">To Date<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('to_date_nep', request()->get('to_date_nep') ?
                                request()->get('to_date_nep') : null, [
                                'placeholder' => 'e.g: YYYY-MM-DD',
                                'class' => 'form-control nepali-calendar toDate',
                                'id' => 'nepToDate',
                                'readonly',
                                ]) !!}
                            </div>
                        </div>
                    </div> --}}
                    {{-- @else --}}
                    <div class="col-md-4 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">From Date<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                <x-utilities.date-picker default="nep" nep-date-attribute="from_date"
                                    eng-date-attribute="eng_from_date" mode="both" :date="request('from_date')" />
                                {{-- {!! Form::text('from_date', request()->get('from_date') ?
                                request()->get('from_date') :
                                null, [
                                'placeholder' => 'e.g: YYYY-MM-DD',
                                'class' => 'form-control daterange-single',
                                'id' => 'fromDate',
                                'readonly',
                                ]) !!} --}}
                            </div>
                            <span class="errorStartDate"></span>
                        </div>
                    </div>

                    <div class="col-md-4 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">To Date<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                <x-utilities.date-picker default="nep" nep-date-attribute="to_date"
                                    eng-date-attribute="eng_to_date" mode="both" :date="request('to_date')" />
                                {{-- {!! Form::text('to_date', request()->get('to_date') ? request()->get('to_date') :
                                null,
                                [
                                'placeholder' => 'e.g: YYYY-MM-DD',
                                'class' => 'form-control daterange-single',
                                'id' => 'toDate',
                                'readonly',
                                ]) !!} --}}
                            </div>
                        </div>
                    </div>
                    {{-- @endif --}}




                    <div class="col-md-4 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Purpose<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('purpose', request()->get('purpose') ? request()->get('purpose') : null, [
                                    'placeholder' => '',
                                    'class' => 'form-control',
                                    'id' => 'purpose',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">No of days <span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('request_days', request()->get('request_days') ? request()->get('request_days') : null, [
                                    'placeholder' => '',
                                    'class' => 'form-control',
                                    'id' => 'request_days',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Advance Amount <span class="text-danger"> </span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('advance_amount', request()->get('advance_amount') ? request()->get('advance_amount') : null, [
                                    'placeholder' => '',
                                    'class' => 'form-control',
                                    'id' => 'advance_amount',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Upload Files <span class="text-danger"> </span></label>
                            </div>
                            <div class="col-md-12">
                                <input type="file" name="document" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div id="noticeList" class="mt-3"></div>
                </div>

                <div class="row">

                    <div class="col-md-4  mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Remarks</label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::textarea('remarks', request()->get('remarks') ? request()->get('remarks') : null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter remarks',
                                    'rows' => 3,
                                ]) !!}

                                @if ($errors->first('remarks') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('remarks') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3 @if ($id && $businessTrip->type_id == 2) @else d-none @endif"
                        id="currency-detail">

                        <div class="row fw-bold mb-2 btn-info p-1">
                            <div class="col-md-2">Currency Type</div>
                            <div class="col-md-2">Note</div>
                            <div class="col-md-2">Qty</div>
                            <div class="col-md-2">Conversion Rate</div>
                            <div class="col-md-2">Amount</div>
                            <div class="col-md-2">Action</div>
                        </div>
                        <div id="currency-container">
                            @if (!$id)
                                <div class="row mb-2 currency-row">
                                    <div class="col-md-2">
                                        <select name="foreign_currency_type[]" class="form-select form-select-sm">
                                            @forelse($currencyLists as $currency)
                                                <option value="{{ $currency->dropvalue }}">{{ $currency->dropvalue }}
                                                </option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" name="note[]" class="form-control form-control-sm"
                                            placeholder="Note">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="qty[]" class="form-control form-control-sm qty"
                                            placeholder="Qty">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" step="0.01" name="conversion_rate[]"
                                            class="form-control form-control-sm conversion_rate" placeholder="Rate">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" step="0.01" name="amount[]"
                                            class="form-control form-control-sm amount" placeholder="Amount" readonly>
                                    </div>

                                </div>
                            @else
                                @php
                                    $foreignCurrencyDetail = $businessTrip->foreign_currency_detail;
                                    $foreignDetails = json_decode($foreignCurrencyDetail, true); // decode as array
                                @endphp

                                @if (!empty($foreignDetails['foreign_currency_type']))
                                    @foreach ($foreignDetails['foreign_currency_type'] as $index => $currencyType)
                                        <div class="row mb-2 currency-row">
                                            <div class="col-md-2">
                                                <select name="foreign_currency_type[]"
                                                    class="form-select form-select-sm">
                                                    @forelse($currencyLists as $currency)
                                                        <option value="{{ $currency->dropvalue }}"
                                                            {{ $currency->dropvalue == $currencyType ? 'selected' : '' }}>
                                                            {{ $currency->dropvalue }}
                                                        </option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" name="note[]"
                                                    class="form-control form-control-sm" placeholder="Note"
                                                    value="{{ $foreignDetails['note'][$index] ?? '' }}">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" name="qty[]"
                                                    class="form-control form-control-sm qty" placeholder="Qty"
                                                    value="{{ $foreignDetails['qty'][$index] ?? '' }}">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" step="0.01" name="conversion_rate[]"
                                                    class="form-control form-control-sm conversion_rate"
                                                    placeholder="Rate"
                                                    value="{{ $foreignDetails['conversion_rate'][$index] ?? '' }}">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" step="0.01" name="amount[]"
                                                    class="form-control form-control-sm amount" placeholder="Amount"
                                                    readonly value="{{ $foreignDetails['amount'][$index] ?? '' }}">
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endif

                        </div>

                        <button type="button" class="btn btn-primary btn-sm" id="add-row">Add Row</button>


                        <div class="col-md-4 mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Converted Amount in Npr <span class="text-danger">
                                            *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::text(
                                        'converted_amount_npr',
                                        request()->get('converted_amount_npr') ? request()->get('converted_amount_npr') : null,
                                        [
                                            'placeholder' => '',
                                            'class' => 'form-control',
                                            'id' => 'converted_amount_npr',
                                        ],
                                    ) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                        class="icon-backward2"></i></b>Go Back</a>
            <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                        class="icon-database-insert"></i></b>{{ $btnType }}</button>
        </div>
    </div>

    <div class="col-lg-2">
        <div class="card shadow-sm" style="width: 18rem;">
            <div class="card-body d-none" id="emp-detail-card">
                <ul class="list-unstyled text-start">
                    <li><strong>Employee Name :</strong> <span id="emp-name"></span></li>
                    <li><strong>Employee Code :</strong> <span id="emp-code"></span></li>
                    <li><strong>Phone:</strong> <span id="emp-phone"></span></li>
                    <li><strong>Branch:</strong> <span id="emp-branch"></span></li>
                    <li><strong>Sub-Function:</strong> <span id="emp-department"></span></li>
                    <li><strong>Designation:</strong> <span id="emp-designation"></span></li>
                    <li><strong>Grade:</strong> <span id="emp-level"></span></li>
                </ul>
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('admin/validation/businessTrip.js') }}"></script>

<script>
    var businessTripId = "{{ $id ?? '' }}";

    $(document).ready(function() {
        var calendar_type = localStorage.getItem('calendar_type');
        // if (calendar_type == 'BS') {
        //     nepStartDate()
        // } else {
        //     startDate();
        // }

        // function startDate() {
        //     $('#from_date').daterangepicker({
        //         parentEl: '.content-inner',
        //         singleDatePicker: true,
        //         showDropdowns: true,
        //         autoUpdateInput: false,
        //         locale: {
        //             format: 'YYYY-MM-DD'
        //         }
        //     }).on('change', function(ev, picker) {
        //         $(this).val(picker.startDate.format('YYYY-MM-DD'));
        //         $('#to_date').val('');
        //         $('#noticeList').html('');

        //         var startDate = $('#from_date').val();
        //         endDate(startDate);
        //     })
        // }

        // function endDate(startDate) {
        //     var id = $('#employee_id').val();
        //     $('#to_date').daterangepicker({
        //         parentEl: '.content-inner',
        //         singleDatePicker: true,
        //         showDropdowns: true,
        //         autoUpdateInput: false,
        //         minDate: startDate,
        //         locale: {
        //             format: 'YYYY-MM-DD'
        //         }
        //     }).on('change', function(ev, picker) {
        //         console.log('here')
        //         $(this).val(picker.startDate.format('YYYY-MM-DD'))
        //         var endDate = $('#to_date').val()
        //         var params = {
        //             'startDate': startDate,
        //             'endDate': endDate,
        //             'id': id,
        //             'businessTripId': businessTripId,
        //         }
        //         postProcessData(params)
        //     });
        // }

        $('#eng_to_date').on('change', function() {
            var params = {
                startDate: $('#eng_from_date').val(),
                endDate: $('#eng_to_date').val(),
                id: $('#employee_id').val(),
                businessTripId: businessTripId // assumes this is defined globally
            };

            postProcessData(params);
        });


        function nepStartDate() {
            $("#nepFromDate").nepaliDatePicker({
                onChange: function() {
                    $('#nepToDate').val('');
                    $('#noticeList').html('');
                    var startDate = $('#nepFromDate').val();
                    nepEndDate(startDate);
                },
            });
        }

        function nepEndDate(startDate) {
            var id = $('#employee_id').val();
            $("#nepToDate").nepaliDatePicker({
                onChange: function() {
                    var endDate = $('#nepToDate').val();
                    var params = {
                        'startDate': startDate,
                        'endDate': endDate,
                        'id': id,
                        'businessTripId': businessTripId,
                    };
                    postProcessData(params);
                },
                disableBefore: startDate,
                // disableAfter: maxDate,
            });
        }

        function postProcessData(params) {
            console.log(params)
            $.ajax({
                type: "POST",
                url: "{{ route('businessTrip.postProcessData') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    params: params
                },
                dataType: 'json',
                success: function(response) {
                    if (response.noticeList) {
                        $('#noticeList').addClass('col-lg-12');
                        $('#noticeList').html(response.noticeList);
                        $('#request_days').val(response.numberOfDays)
                    } else {
                        $('#noticeList').hide();
                    }
                }
            });
        }
    })

    //emp ajax call

    $(document).ready(function() {
        // Your existing change event
        $('#employee_id').on('change', function() {
            let empId = $(this).val();

            $.ajax({
                url: '{{ route('get.employee.info') }}',
                type: 'GET',
                data: {
                    id: empId
                },
                success: function(response) {
                    $('#emp-detail-card').removeClass('d-none');
                    let fullName = [
                        response.first_name,
                        response.middle_name,
                        response.last_name
                    ].filter(Boolean).join(' ');

                    $('#emp-name').text(fullName);
                    $('#emp-code').text(response.employee_code);
                    $('#ftitle').val(response.functional_title);
                    $('#emp-phone').text(response.phone);
                    $('#emp-branch').text(response.branch);
                    $('#emp-department').text(response.department);
                    $('#emp-designation').text(response.designation);
                    $('#department').val(response.department);
                    $('#designation').val(response.designation);
                    $('#emp-level').text(response.level?.title || '');
                },
                error: function(xhr) {
                    console.log("Error:", xhr);
                }
            });
        });

        // ✅ Trigger change if value already exists (edit mode)
        if ($('#employee_id').val()) {
            $('#employee_id').trigger('change');
        }
    });

    const currencyOptions = `
        @forelse($currencyLists as $currency)
            <option value="{{ $currency->dropvalue }}">{{ $currency->dropvalue }}</option>
        @empty
            <option disabled>No currencies found</option>
        @endforelse
    `;

    $('#add-row').on('click', function() {
        let row = `
    <div class="row mb-2 currency-row">
      <div class="col-md-2">
        <select name="foreign_currency_type[]" class="form-select form-select-sm">
          ${currencyOptions}
        </select>
      </div>
      <div class="col-md-2">
        <input type="text" name="note[]" class="form-control form-control-sm" placeholder="Note">
      </div>
      <div class="col-md-2">
        <input type="number" name="qty[]" class="form-control form-control-sm qty" placeholder="Qty">
      </div>
      <div class="col-md-2">
        <input type="number" step="0.01" name="conversion_rate[]" class="form-control form-control-sm conversion_rate" placeholder="Rate">
      </div>
      <div class="col-md-2">
        <input type="number" step="0.01" name="amount[]" class="form-control form-control-sm amount" placeholder="Amount" readonly>
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
      </div>
    </div>
  `;
        $('#currency-container').append(row);
    });


    $(document).on('click', '.remove-row', function() {
        $(this).closest('.currency-row').remove();
    });

    $('#travel-type').on('change', function() {
        let val = $(this).val();
        if (val == 2) {
            $('#currency-detail').removeClass('d-none');
        } else {
            $('#currency-detail').addClass('d-none');

        }
    });

    $(document).on('input', '.qty, .conversion_rate', function() {
        let row = $(this).closest('.currency-row');
        let qty = parseFloat(row.find('.qty').val()) || 0;
        let rate = parseFloat(row.find('.conversion_rate').val()) || 0;
        row.find('.amount').val((qty * rate).toFixed(2));
    });
</script>
