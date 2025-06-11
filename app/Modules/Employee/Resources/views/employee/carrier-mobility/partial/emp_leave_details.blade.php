<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header header-elements-inline text-light bg-secondary">
                <h5 class="card-title">Previous Leave Details</h5>
                <div class="header-elements">

                </div>
            </div>

            <div class="card-body">
                @foreach ($employeeLeaveDetails as $leaveDetail)
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4">{{$leaveDetail['leave_type']}}:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-users2"></i></span>
                                        </span>
                                        {!! Form::text('job_title', $value = $leaveDetail['leave_remaining'], [
                                            // 'id' => 'job_title',
                                            'class' => 'form-control',
                                            'readonly',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header header-elements-inline text-light bg-secondary">
                <h5 class="card-title">New Leave Details</h5>
            </div>
            {!! Form::open([
                'route' => 'employee.storeCarrierMobility',
                'method' => 'POST',
                'class' => 'form-horizontal updateEmployeeLeave',
                'id' => 'updateEmployeeLeave',
                'role' => 'form',
                'files' => false,
            ]) !!}
            {!! Form::hidden('employee_id', $employee_id) !!}
            {!! Form::hidden('organization_id', $organization_id) !!}
            {!! Form::hidden('date', $date) !!}
            {!! Form::hidden('type_id', $type_id) !!}
            <div class="card-body card-temporary-address">
                @foreach($leave_types as $leave_type)
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4">{{$leave_type->name}}:</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-users2"></i></span>
                                        </span>
                                        {!! Form::text('leave_remaining' . '[' . $leave_type->id . ']' ,$value = null, [
                                            'class' => 'form-control','required',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="text-center">
                    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left mt-3"><b><i class="icon-database-insert"></i></b>Save Record</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
