<script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <fieldset class="mb-3">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Title:<span class="text-danger">*</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil5"></i></span>
                                </span>
                                {!! Form::text('title', null, ['placeholder' => 'Enter Title', 'class' => 'form-control']) !!}
                            </div>
                            @if ($errors->has('title'))
                            <div class="error text-danger">{{ $errors->first('title') }}</div>
                        @endif
                        </div>
                    </div>
                    @if (auth()->user()->user_type == 'admin' ||
                        auth()->user()->user_type == 'super_admin' ||
                        auth()->user()->user_type == 'Admin' ||
                        auth()->user()->user_type == 'hr')
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Employee Name:<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-git-pull-request"></i></span>
                                    </span>
                                    {!! Form::select('employee_id', $users, $value = null, [
                                        'id' => 'employee_id',
                                        'placeholder' => 'Select Employee',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    @else
                        {!! Form::hidden('employee_id', $value = auth()->user()->emp_id, [
                            'placeholder' => 'Select Employee',
                            'class' => 'form-control',
                        ]) !!}
                    @endif
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Request Type:<span class="text-danger">*</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-git-pull-request"></i></span>
                                </span>
                                {!! Form::select('type_id', $requestTypes, null, [
                                    'placeholder' => 'Select Request Type',
                                    'class' => 'form-control',
                                    'onchange' => 'yesnoCheck(this);',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div id="travel_block"
                        style="display: {{ $is_edit && isset($employeeRequest) && $employeeRequest->type_id == 2 ? 'block' : 'none' }};">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Travel Date:<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-calendar22"></i></span>
                                    </span>
                                    {!! Form::text('travel_date', null, [
                                        'id' => 'travel_date',
                                        'placeholder' => 'Select Travel Date',
                                        'class' => 'form-control daterange-single',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Market Visit Location:<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-git-pull-request"></i></span>
                                    </span>
                                    {!! Form::text('market_visit_location', null, [
                                        'id' => 'market_visit_location',
                                        'placeholder' => 'Enter Market Visit Location',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Night Halt:<span class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-git-pull-request"></i></span>
                                    </span>
                                    {!! Form::text('night_halt', null, [
                                        'id' => 'night_halt',
                                        'placeholder' => 'Enter Night Halt',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Transport Cost:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">Rs.</span>
                                    </span>
                                    {!! Form::text('transport_cost', null, [
                                        'placeholder' => 'Enter Transport Cost',
                                        'class' => 'form-control numeric',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Lodging:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">Rs.</span>
                                    </span>
                                    {!! Form::text('lodging', null, ['placeholder' => 'Enter PR Cost', 'class' => 'form-control numeric']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Fooding:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">Rs.</span>
                                    </span>
                                    {!! Form::text('fooding', null, ['placeholder' => 'Enter PR Cost', 'class' => 'form-control numeric']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Local DA:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">Rs.</span>
                                    </span>
                                    {!! Form::text('local_DA', null, ['placeholder' => 'Enter Transport Cost', 'class' => 'form-control numeric']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">DA:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">Rs.</span>
                                    </span>
                                    {!! Form::text('DA', null, ['placeholder' => 'Enter Transport Cost', 'class' => 'form-control numeric']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Telephone:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-phone"></i></span>
                                    </span>
                                    {!! Form::number('telephone', null, ['placeholder' => 'Enter telephone', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">MIS/Motor Cycle Expenses:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">Rs.</span>
                                    </span>
                                    {!! Form::text('motor_cycle_expenses', null, [
                                        'placeholder' => 'Enter Motor Cycle Expenses',
                                        'class' => 'form-control numeric',
                                    ]) !!}
                                </div>
                            </div>
                        </div>


                        {{-- <div class="form-group row">
            <label class="col-form-label col-lg-3">PR:</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text">Rs.</span>
                    </span>
                    {!! Form::text('PR', null, ['placeholder'=>'Enter PR Cost','class'=>'form-control numeric']) !!}
                </div>
            </div>
        </div>    --}}
                    </div>

                    <div id="ifYes"
                        style="display: {{ $is_edit && isset($employeeRequest) && $employeeRequest->type_id == 1 ? 'block' : 'none' }};">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Benefit Type:<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-git-pull-request"></i></span>
                                    </span>
                                    {!! Form::select('dropdown_id', $dropdown, null, [
                                        'placeholder' => 'Select Benefit Type',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Pay Type:<span class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-git-pull-request"></i></span>
                                    </span>
                                    {!! Form::select('pay_type', ['1' => 'Cash', '2' => 'Cheque'], $value = null, [
                                        'id' => 'pay_type',
                                        'class' => 'form-control',
                                        'onchange' => 'show(this);',
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div id="yes" style="display: none;">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Bank Name:<span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-pencil5"></i></span>
                                        </span>
                                        {!! Form::text('bank_name', null, ['placeholder' => 'Enter Bank Name', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Account Number:<span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-pencil5"></i></span>
                                        </span>
                                        {!! Form::text('account_number', null, ['placeholder' => 'Enter Account Number', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Cost:<span class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-pencil5"></i></span>
                                    </span>
                                    {!! Form::text('cost', null, ['placeholder' => 'Enter Cost', 'class' => 'form-control numeric']) !!}
                                </div>
                            </div>
                        </div>



                    </div>

                    <div id="show_bill"
                        style="display:{{ $is_edit && isset($employeeRequest) && ($employeeRequest->type_id == 2 || $employeeRequest->type_id == 1) ? 'block' : 'none' }}">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Upload Bill: <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-pencil5"></i></span>
                                    </span>
                                    {!! Form::file('bill', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Description:</label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil5"></i></span>
                                </span>
                                {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>

                    @if (auth()->user()->user_type == 'super_admin')
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Status:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-database-check"></i></span>
                                    </span>
                                    {!! Form::select('status', ['1' => 'Approve', '0' => 'Pending', '2' => 'Cancel'], null, [
                                        'id' => 'status',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                </fieldset>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

<script type="text/javascript">
    function yesnoCheck(that) {
        if (that.value == "1") {
            document.getElementById("ifYes").style.display = "block";
            document.getElementById("show_bill").style.display = "block";
            document.getElementById("travel_block").style.display = "none";
        } else if (that.value == "2") {
            document.getElementById("travel_block").style.display = "block";
            document.getElementById("show_bill").style.display = "block";
            document.getElementById("ifYes").style.display = "none";
        } else {
            document.getElementById("ifYes").style.display = "none";
            document.getElementById("travel_block").style.display = "none";
            document.getElementById("show_bill").style.display = "none";
        }
    }
</script>

<script type="text/javascript">
    function show(that) {
        if (that.value == "2") {
            document.getElementById("yes").style.display = "block";
        } else {
            document.getElementById("yes").style.display = "none";
        }
    }
</script>
