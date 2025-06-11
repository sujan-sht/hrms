<!-- Warning modal -->
<div id="modal_add_employee_shift" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h6 class="modal-title">Add Employee Shift</h6>
            </div>

            {!! Form::open(['route'=>'employeeshift.store', 'method'=>'POST','class'=>'form-horizontal','role'=>'form', 'id' => 'shift_type_form']) !!}
            <div class="modal-body">

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Choose Shift:<span class="text-danger">*</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil5"></i></span>
                                </span>
                                {{ Form::text('shift', null, ['class' => 'form-control', 'readonly', 'required']) }}
                            </div>
                        </div>
                    </div>

                    {{ Form::hidden('shift_id', null) }}

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Choose Employee:<span class="text-danger">*</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-user"></i></span>
                                </span>
                                {!! Form::select('employee_id', $employees, null, ['placeholder'=>'Choose Employee','class'=>'form-control', 'required']) !!}
                            </div>
                        </div>
                    </div>


            </div>

            <div class="modal-footer">
                {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- /warning modal -->
