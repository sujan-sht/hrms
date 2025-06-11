<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="text-light btn-slate">
                <th>#</th>
                <th>Emp Id</th>
                <th>Employee Name</th>
                <th>Designation</th>
                {{-- <th>Status</th>
                <th>Income</th> --}}
                <th>Gross Salary</th>
                <th>Arrear amount</th>
                <th width="15%">Eff. Date</th>
            </tr>
        </thead>
        <tbody>


            @foreach ($employeeList as $key => $item)

                <tr>
                    {!! Form::hidden('emp_id[]', $item->id, ['class' => 'form-control']) !!}
                    {!! Form::hidden('name[]', $item->getFullName(), ['class' => 'form-control']) !!}
                    {!! Form::hidden('organization_id[]', $item->organization_id, ['class' => 'form-control']) !!}
                    {!! Form::hidden('branch_id[]', $item->branch_id, ['class' => 'form-control']) !!}
                    {!! Form::hidden('designation_id[]', $item->designation_id, ['class' => 'form-control']) !!}
                    {!! Form::hidden('emp_status[]', $item->status, ['class' => 'form-control']) !!}
                    {!! Form::hidden('salary[]',  $item->employeeGrossSalarySetup->gross_salary ?? 0  , ['id'=>'salary'.$item->id, 'class' => 'form-control numeric salary'] )!!}

                    <td>{{ '#' . ++$key }}</td>
                    <td>{{ $item->employee_code }}</td>
                    <td>
                        <div class="media">
                            <div class="mr-3">
                                <a href="#">
                                    <img src="{{ $item->getImage() }}"
                                        class="rounded-circle" width="40" height="40" alt="">
                                </a>
                            </div>
                            <div class="media-body">
                                <div class="media-title font-weight-semibold">
                                    {{ $item->getFullName() }}</div>
                                <span
                                    class="text-muted">{{ $item->official_email }}</span>
                            </div>
                        </div>
                    </td>
                    <td>{{ optional($item->designation)->dropvalue}}</td>
                    <td>{{optional($item->employeeGrossSalarySetup)->gross_salary}}</td>
                    <td><input type="text" name="arrear_amt[]" id="arrear_amt_{{$item->id}}" emp_arr_id="{{$item->id}}" class="form-control numeric arrear_amt" /></td>

                    {{-- <td><input type="text" name="increased_by[]" id="increased_by_{{$item->id}}" emp_income_id="{{$item->id}}" class="form-control numeric increased_by" /></td>
                    <td><input type="text" name="new_income[]" id="new_income_{{$item->id}}" emp_income_id="{{$item->id}}" class="form-control numeric new_income" /></td> --}}
                    <td>
                    {!! Form::text('effective_date[]', isset($selected_effective_date) ? $selected_effective_date : null, ['id' => 'effective_date'.$key, 'class'=>'form-control daterange-single']) !!}
                    </td>
            @endforeach
            {{-- @if (isset($employees) && $employees->total() > 0)
                @foreach ($employees as $key => $value)
                    @php
                        $full_name = !empty($value->middle_name) ? $value->first_name.' '.$value->middle_name.' '.$value->last_name : $value->first_name.' '.$value->last_name;
                        $emp_id = $value->id;
                        $emp_income = $assign_employee_income->getIncomeByIncomeidEmpid($value->id, $selected_income_id);
                    @endphp
                    <tr>
                        {!! Form::hidden('income_id[]', $selected_income_id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('emp_id[]', $emp_id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('name[]', $full_name, ['class' => 'form-control']) !!}
                        {!! Form::hidden('org_id[]', $value->organization_id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('department_id[]', $value->department_id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('designation_id[]', $value->designation_id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('emp_status[]', $value->contract_type, ['class' => 'form-control']) !!}
                        {!! Form::hidden('arrear_amt[]', 0, ['class' => 'form-control']) !!}
                        {!! Form::hidden('existing_income[]', !empty($emp_income) && !empty($emp_income->amount) ? $emp_income->amount : 0  , ['id'=>'existing_income_'.$value->id, 'emp_income_id'=>$value->id, 'class' => 'form-control numeric existing_income']) !!}

                        <td>{{$employees->firstItem() + $key}}</td>
                        <td>{{$value->employee_id}}</td>
                        <td>{{ $full_name }}</td>
                        <td>{{ optional($value->designation)->dropvalue}}</td>
                        <td>{{ optional($value->contractType)->dropvalue }}</td>
                        <td>{{ $selected_income }}</td>
                        <td>{{ !empty($emp_income) && !empty($emp_income->amount) ? $emp_income->amount : 0 }}</td>
                        <td><input type="text" name="increased_by[]" id="increased_by_{{$value->id}}" emp_income_id="{{$value->id}}" class="form-control numeric increased_by" /></td>
                        <td><input type="text" name="new_income[]" id="new_income_{{$value->id}}" emp_income_id="{{$value->id}}" class="form-control numeric new_income" /></td>

                        <td>@if (!empty($setting) && $setting->payroll_calendar_type == 1)
                                {!! Form::text('nep_effective_date[]', isset($selected_nep_effective_date) ? $selected_nep_effective_date : null, ['id' => 'nep_effective_date'.$key, 'placeholder'=>'Select Nepali Date','class'=>'form-control nep_effective_date ndp-nepali-calendar', 'key_value'=>$key, 'onfocus'=>'showNdpCalendarBox("nep_effective_date'.$key.'")']) !!}
                                {!! Form::hidden('effective_date[]', isset($selected_effective_date) ? $selected_effective_date : null, ['id' => 'effective_date'.$key, 'class'=>'form-control']) !!}
                            @else
                                {!! Form::text('effective_date[]', isset($selected_effective_date) ? $selected_effective_date : null, ['id' => 'effective_date'.$key, 'class'=>'form-control daterange-single']) !!}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="11">No Data Found</td>
                </tr>
            @endif --}}
        </tbody>
    </table>
    {{-- @if (isset($employees) && $employees->total() > 0) --}}
            <div class="d-flex justify-content-end pt-1 pb-3 pl-3 pr-3">
                <button class="btn bg-teal float-right" type="submit">Update</button>
            </div>
        {{-- @endif --}}

</div>

<script>
     $('.increased_by').on('keyup', function() {
                var inc_by = parseFloat($(this).val());
                var att_id = $(this).attr('emp_income_id');
                var existing_inc = parseFloat($('#existing_income_'+att_id).val()); console.log(existing_inc);
                var new_inc = existing_inc + inc_by;
                $('#new_income_'+att_id).val(new_inc);
            });

    $('.new_income').on('keyup', function() {
        var new_inc = parseFloat($(this).val());
        var att_id = $(this).attr('emp_income_id');
        var existing_inc = parseFloat($('#existing_income_'+att_id).val());
        if(existing_inc > new_inc) {
            var inc_by = existing_inc - new_inc;
        } else {
            var inc_by = new_inc - existing_inc;
        }
        $('#increased_by_'+att_id).val(inc_by);
    });
</script>
