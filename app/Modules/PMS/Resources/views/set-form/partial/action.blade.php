{{-- Filter Section Start --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-10">
                                <span>Form Setup</span>
                            </div>
                            <div class="col-lg-2">
                                <span style="float: right;">
                                    <button class="btn btn-success btn-sm rounded-pill showAddSection">KRA
                                        Setup</button>
                                    <button class="btn btn-info btn-sm rounded-pill viewEmployeeReport">View
                                        Form</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </legend>
                <div class="form-group row">
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Employee :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    @php $selected_emp_id = isset(request()->employee_id) ? request()->employee_id : null ; @endphp
                                    {!! Form::select('employee_id', $employeeList, $selected_emp_id, [
                                        'placeholder' => 'Select Employee',
                                        'class' => 'form-control select-search employeeId',
                                    ]) !!}
                                </div>
                                @if ($errors->has('employee_id'))
                                    <div class="error text-danger">{{ $errors->first('employee_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <button type="button"
                            class="ml-2 btn btn-secondary btn-labeled btn-labeled-center proceed">{{ $btnType }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Filter Section End --}}

{!! Form::open([
    'route' => 'set-form.store',
    'method' => 'POST',
    'class' => 'form-horizontal',
    'id' => 'targetFormSetupSubmit',
    'role' => 'form',
]) !!}
{!! Form::hidden('employee_id', null, ['id' => 'employee_id']) !!}
<div class="row addDetailsDiv" style="display:none;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">KRA :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('kra_id', $kraList, null, [
                                        'placeholder' => 'Select KRA',
                                        'class' => 'form-control select-search kra',
                                    ]) !!}
                                </div>
                                @if ($errors->has('kra_id'))
                                    <div class="error text-danger">{{ $errors->first('kra_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-1 mb-3">
                        <div class="row">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <a class="btn btn-outline-primary mx-1 newKra"><i
                                            class="icon-plus-circle2"></i>&nbsp;&nbsp;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 mb-3 newKraDiv" style="display:none;">
                        <div class="row">
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('kra_title', null, [
                                        'class' => 'form-control newKraTitle',
                                        'placeholder' => 'Write KRA title here',
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('division_id', $organizationList, null, [
                                        'placeholder' => 'Select Division',
                                        'class' => 'form-control select-search newKraDivision',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('department_id', $departmentList, null, [
                                        'placeholder' => 'Select Sub-Function',
                                        'class' => 'form-control select-search newKraDepartment',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <button type="button"
                                    class="ml-2 btn btn-success btn-labeled btn-labeled-left submitKRA"><b><i
                                            class="icon-database-insert"></i></b>Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row addDetailsDiv" style="display:none;">
    <div class="col-md-12">
        <div class="card clone-div-kpi">
            <div class="card-body">
                <legend>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-11">
                                KPIs Information
                            </div>
                            <div class="col-lg-1 cloneButton">
                                <span class="btn btn-outline-warning mx-1 addMore" style="float:right;"><i
                                        class="icon-plus-circle2"></i>&nbsp;&nbsp;ADD</span>
                            </div>
                        </div>
                    </div>
                </legend>
                <div class="form-group row">
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">KPI :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                        <div class="input-group append_kpi_data">
                                            {!! Form::select('kpi_id[]', [], null, ['placeholder' => 'Select KPI', 'class' => 'form-control kpiId']) !!}
                                        </div>
                                        @if ($errors->has('kpi_id'))
                                            <div class="error text-danger">{{ $errors->first('kpi_id') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-outline-primary btn-xs addKpiModal"
                                                data-toggle="modal" data-target="#addNewKpi"><i
                                                    class="icon-plus-circle2"></i>&nbsp;&nbsp;</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Target :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('title[]', null, [
                                        'rows' => 5,
                                        'placeholder' => 'Write title here..',
                                        'class' => 'form-control titleClass',
                                    ]) !!}
                                </div>
                                @if ($errors->has('title'))
                                    <div class="error text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Fiscal Year:</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('fiscal_year_id[]', $fiscalYearList, null, [
                                        'class' => 'form-control fiscalYearClass',
                                        'placeholder' => 'Select Fiscal Year',
                                    ]) !!}
                                </div>
                                @if ($errors->has('fiscal_year_id'))
                                    <div class="error text-danger">{{ $errors->first('fiscal_year_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Frequency :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select(
                                        'frequency[]',
                                        ['yearly' => 'Yearly', 'quarterly' => 'Quarterly', 'monthly' => 'Monthly', 'daily' => 'Daily'],
                                        null,
                                        ['id' => 'frequency', 'placeholder' => 'Select Frequency', 'class' => 'form-control frequencyClass'],
                                    ) !!}
                                </div>
                                @if ($errors->has('frequency'))
                                    <div class="error text-danger">{{ $errors->first('frequency') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Category :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('category[]', null, [
                                        'rows' => 5,
                                        'placeholder' => 'Write category name..',
                                        'class' => 'form-control categoryClass',
                                    ]) !!}
                                </div>
                                @if ($errors->has('category'))
                                    <div class="error text-danger">{{ $errors->first('category') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Weightage (%) :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('weightage[]', null, [
                                        'rows' => 5,
                                        'placeholder' => 'Enter weightage here..',
                                        'class' => 'form-control numeric weightageClass',
                                        'id' => 'weightageId',
                                    ]) !!}
                                </div>
                                @if ($errors->has('weightage'))
                                    <div class="error text-danger">{{ $errors->first('weightage') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Eligibility (%) :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('eligibility[]', null, [
                                        'rows' => 5,
                                        'placeholder' => 'Enter eligibility here..',
                                        'class' => 'form-control numeric eligibilityClass',
                                        'id' => 'eligibilityId',
                                    ]) !!}
                                </div>
                                @if ($errors->has('eligibility'))
                                    <div class="error text-danger">{{ $errors->first('eligibility') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <legend>
                        <div class="col-lg-12">
                            Set Target Values
                        </div>
                    </legend>
                    @for ($i = 1; $i <= 4; $i++)
                        <div class="col-lg-3 mb-3">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Quarter {{ $i }}</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('target_values[' . $i . '][]', null, [
                                            'class' => 'numeric form-control',
                                            'placeholder' => 'Set value',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="append-clone-kpi">

        </div>
    </div>
    <div class="col-lg-12 mb-3 text-center">
        <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i
                    class="icon-database-insert"></i></b>Save Changes</button>

        <a href="{{ route('set-form.index') }}" class="ml-2 btn btn-secondary btn-labeled btn-labeled-left"><b><i
                    class="icon-backward2"></i></b>Go Back</a>
    </div>
</div>
{!! Form::close() !!}

<div class="row employeeReport" style="display: none;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <h1 class="text-center mt-4">{{ $setting->company_name ?? '' }}</h1>
                <h3 class="text-center">Key Performance Indicator Achievement {{ $fiscalYear->fiscal_year ?? '' }}
                </h3>
                <div class="col-lg-12 mb-3 mt-1">
                    <div class="employeeDetails">
                    </div>
                </div>
                <table class="table table-bordered table-responsive mt-2">
                    <thead class="bg-slate text-center text-white">
                        <tr>
                            <th rowspan="3">S.N</th>
                            <th rowspan="3" style="padding: 0px 150px;">KRA</th>
                            <th rowspan="3" style="padding: 0px 150px;">KPIs</th>
                            <th rowspan="3" style="padding: 0px 150px;">Target</th>
                            <th rowspan="3">Frequency/Age</th>
                            <th rowspan="3">Weightage</th>
                            <th rowspan="3">Eligibility</th>
                            <th colspan="16">TARGET VS ACHIEVEMENT</th>
                            <th rowspan="3">YTD</th>
                            <th rowspan="3">Remarks</th>
                            <th rowspan="3">Action</th>
                        </tr>
                        <tr>
                            <th colspan="4">Q1</th>
                            <th colspan="4">Q2</th>
                            <th colspan="4">Q3</th>
                            <th colspan="4">Q4</th>
                        </tr>
                        <tr>
                            <th style="padding: 0px 45px;">TGT</th>
                            <th>ACH</th>
                            <th>ACH (%)</th>
                            <th>SCORE (%)</th>

                            <th style="padding: 0px 45px;">TGT</th>
                            <th>ACH</th>
                            <th>ACH (%)</th>
                            <th>SCORE (%)</th>

                            <th style="padding: 0px 45px;">TGT</th>
                            <th>ACH</th>
                            <th>ACH (%)</th>
                            <th>SCORE (%)</th>

                            <th style="padding: 0px 45px;">TGT</th>
                            <th>ACH</th>
                            <th>ACH (%)</th>
                            <th>SCORE (%)</th>
                        </tr>
                    </thead>
                    <tbody id="employeeTargetReport">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="addNewKpi" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h6 class="modal-title text-white">Create KPI</h6>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="row">
                            <label class="col-form-label col-lg-3">KRA :</label>
                            <div class="col-lg-9">
                                {!! Form::text('kra_id', null, ['class' => 'form-control', 'readonly', 'id' => 'selectedKRA']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">KPI Title: <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                {!! Form::text('title', null, [
                                    'class' => 'form-control kpiTitle',
                                    'placeholder' => 'Write KPI title here..',
                                    'required',
                                ]) !!}
                            </div>
                            <span class="kpi-error">

                            </span>
                        </div>
                    </div>
                </div>
                {{ Form::hidden('kra_id', null, ['class' => 'kraId']) }}
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn bg-success text-white submitKPI">Submit</button>
                    <button type="button" class="btn bg-danger text-white" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>

    </div>
</div>

@section('script')
    <script>
        $(document).ready(function() {
            let employee_id = "{{ request()->get('employee_id') }}"

            //fetch report on proceed
            $('.proceed').click(function() {
                var emp_id = $('.employeeId').val()
                if (emp_id != '') {
                    $('#employee_id').val(emp_id)
                    $('.showAddSection').trigger('click')
                    fetchEmployeeReport(emp_id)
                }
            })

            function fetchEmployeeReport(emp_id) {
                let formData = {
                    emp_id,
                    "_token": "{{ csrf_token() }}"
                };
                $.ajax({
                    type: "GET",
                    url: "{{ route('set-form.view') }}",
                    data: formData,
                    success: function(resp) {
                        if (resp) {
                            $('#employeeTargetReport').html(resp.view)
                            $('.employeeDetails').html(resp.selected_employee)
                        }
                    }
                })
            }
            //

            //Toggle Employee View Report
            $('.viewEmployeeReport').click(function() {
                var emp_id = $('.employeeId').val()
                if (emp_id != '') {
                    $('.employeeReport').toggle()
                }
            })
            //
            if (employee_id) {
                $('.proceed').trigger('click')
                $('.viewEmployeeReport').trigger('click')
            }

            //Toggle Create section
            $('.showAddSection').click(function() {
                var emp_id = $('.employeeId').val()
                if (emp_id != '') {
                    $('.addDetailsDiv').toggle()
                }
            })
            //

            //FETCH KPI ON CHANGE KRA
            $('.kra').on('change', function() {
                var kra_id = $('.kra').val()
                fetchKpis(kra_id)
            })

            function fetchKpis(kra_id) {
                let formData = {
                    kra_id,
                    "_token": "{{ csrf_token() }}"
                };
                $.ajax({
                    type: "GET",
                    url: "{{ route('target.fetchKPIs') }}",
                    data: formData,
                    success: function(resp) {
                        let kpis = JSON.parse(resp)
                        let kpi_data = ''
                        kpi_data += "<option value=''>Select KPI</option>";
                        $.each(kpis, function(kpi_id, title) {
                            kpi_data += "<option value ='" + kpi_id + "'>" + title + "</option>"
                        })
                        $('.kpiId').html(kpi_data)
                        // $('.kpiId').select2()
                    }
                })
            }
            //

            //Fetch target details on change kpi
            $('.kpiId').on('change', function() {
                var that = $(this)
                var kpi_id = that.closest('.kpiId').val()
                fetchTargetDetails(kpi_id, that)
            })

            function fetchTargetDetails(kpi_id, that) {
                let formData = {
                    kpi_id,
                    "_token": "{{ csrf_token() }}"
                }
                $.ajax({
                    type: "GET",
                    url: "{{ route('set-form.fetchTargetDetails') }}",
                    data: formData,
                    dataType: 'json',
                    success: function(resp) {
                        let location = that.closest('.form-group')
                        if (typeof(resp) != "undefined" && resp !== null) {
                            location.find('.titleClass').val(resp.title)
                            location.find('.fiscalYearClass').val(resp.fiscal_year_id)
                            location.find('.frequencyClass').val(resp.frequency)
                            location.find('.categoryClass').val(resp.category)
                            location.find('.weightageClass').val(resp.weightage)
                        } else {
                            location.find('.titleClass').val('')
                            location.find('.fiscalYearClass').val('')
                            location.find('.frequencyClass').val('')
                            location.find('.categoryClass').val('')
                            location.find('.weightageClass').val('')
                        }
                    }
                })
            }
            //

            //show kra create section
            $('.newKra').click(function() {
                $('.newKraDiv').toggle()
            })
            //

            //Store Kra feature
            $('.submitKRA').click(function() {
                let kra_title = $('.newKraTitle').val()
                let division_id = $('.newKraDivision').val()
                let department_id = $('.newKraDepartment').val()

                let form_data = {
                    'title': kra_title,
                    'division_id': division_id,
                    'department_id': department_id,
                    '_token': "{{ csrf_token() }}"
                }
                $.ajax({
                    type: 'POST',
                    url: "{{ route('set-form.storeSingleKra') }}",
                    dataType: 'json',
                    data: form_data,
                    success: function(resp) {
                        $('.newKraTitle').val('')
                        $('.newKraDivision').val('')
                        $('.newKraDepartment').val('')
                        $('.newKraDiv').hide()
                    }
                })
            })
            //

            // Get and set KRA id value
            $('.addKpiModal').click(function() {
                $('.kraId').val($('.kra').val())
                let kpi_title = $('.kra option:selected').text()
                $('#selectedKRA').val(kpi_title)

            })
            //

            //<i class="icon-plus2"></i> Add kpi
            $('.submitKPI').click(function() {
                let kra_id = $('.kraId').val()
                if (kra_id != '') {
                    let title = $('.kpiTitle').val()
                    let form_data = {
                        kra_id,
                        title,
                        "_token": "{{ csrf_token() }}"
                    }
                    $.ajax({
                        type: "POST",
                        url: "{{ route('set-form.storeSingleKpi') }}",
                        dataType: 'json',
                        data: form_data,
                        success: function(resp) {
                            if (typeof(resp) != "undefined" && resp !== null) {
                                $('#addNewKpi').modal('hide')
                                $('.kpiTitle').val('')
                                fetchKpis(kra_id)
                            }
                        }
                    })
                }
            })
            //

            //fetch kra details with searchable feature
            $('.kra').select2({
                ajax: {
                    url: '{{ route('set-form.filterKraList') }}',
                    delay: 250,
                    type: "POST",
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            _token: '{{ csrf_token() }}',
                            search: params.term,
                            page: params.page
                        }
                        return query;
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;

                        return {
                            results: data.items,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                }
            });
            //

            //Add More section
            $('.addMore').on('click', function() {
                var clone = $('.clone-div-kpi:first')
                var appendClone = clone.clone(true, true).appendTo(".append-clone-kpi");
                appendClone.find(".addMore").remove()
                appendClone.find(".cloneButton").prepend(
                    '<button type="button" class="btn btn-outline-danger mx-1 btn-remove float-right" ><i class="icon-trash"></i>&nbsp;&nbsp;Remove</button>'
                );
                appendClone.find(':input').val('')
            })

            $(document).on('click', '.btn-remove', function() {
                var parent = $(this).parents('.card')
                parent.remove();
            })
            //
        })
    </script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="{{ asset('admin/validation/target-form-setup.js') }}"></script>
@endSection
