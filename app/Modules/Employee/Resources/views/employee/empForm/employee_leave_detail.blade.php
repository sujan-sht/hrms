<div class="row">
    <div class="col-md-12">
        <button class="btn btn-danger pull-right enableEdit" data-dismiss="modal">Edit</button>
        {!! Form::hidden('edit_leave', 0, ['id' => 'edit_leave']) !!}
        <br>
        <small class="text-danger">Note:On clicking edit button you will be able to override leave opening</small>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th width="5%">S.N</th>
                        <th>Leave Type</th>
                        {{-- <th style="width: 200px">Leave Taken</th>
                        <th style="width: 200px">Leave Remaining</th>
                        <th style="width: 200px">Leave Adjustment</th> --}}
                        <th style="width: 200px">Opening Leave</th>
                        <th style="width: 200px; display: none" class="editBox">New Opening Leave</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($employeeLeaveDetails) > 0)
                        @foreach($employeeLeaveDetails as $key => $employeeLeaveDetail)
                            <tr>
                                <td>#{{ ++$key }}</td>
                                <td>{{ $employeeLeaveDetail['leave_type'] }}</td>
                                {{-- <td>{{ $employeeLeaveDetail['leave_taken'] }}</td>
                                <td>{{ $employeeLeaveDetail['leave_remaining'] }}</td>
                                <td>
                                    {!! Form::hidden('employee_leave_ids[]', $employeeLeaveDetail['id'], []) !!}
                                    {!! Form::text('adjust_days[]', $employeeLeaveDetail['leave_remaining'], ['placeholder'=>'0', 'class' => 'form-control numeric']) !!}
                                </td> --}}
                                <td>
                                    {!! Form::hidden('employee_leave_ids[]', $employeeLeaveDetail['id'], []) !!}
                                    {!! Form::text('adjust_days[]', $employeeLeaveDetail['opening_leave'], ['placeholder'=>'0', 'class' => 'form-control numeric','readonly']) !!}
                                </td>
                                <td>
                                    {!! Form::text('edit_adjust_days[]', '', ['class' => 'form-control numeric editBox','readonly','style'=>'display:none']) !!}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">No Leave Details Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
