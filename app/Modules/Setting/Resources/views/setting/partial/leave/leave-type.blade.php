{!! Form::open([
    'route' => 'leaveType.store',
    'method' => 'POST',
    'class' => 'form-horizontal',
    'id' => 'leaveTypeFormSubmit',
    'role' => 'form',
    'files' => true,
]) !!}

@include('leave::leave-type.partial.action', ['btnType' => 'Save Record'])

{!! Form::close() !!}
