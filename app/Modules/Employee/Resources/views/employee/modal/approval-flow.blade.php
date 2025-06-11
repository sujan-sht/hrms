{!! Form::model($employees, [
    'method' => 'PUT',
    'route' => ['employee.updateApprovalFlow', $employees->id],
    'class' => 'form-horizontal',
    'id' => 'approvalForm',
    'role' => 'form',
    'files' => true,
]) !!}


@include('employee::employee.empForm.approvalFlow',['isEmployee'=>false,'is_edit'=>true])


 <div class="text-center">
     <button type="submit" class="btn bg-success text-white">Save Changes</button>
 </div>
 {!! Form::close() !!}

 <script src="{{ asset('admin/validation/approval.js')}}"></script>

