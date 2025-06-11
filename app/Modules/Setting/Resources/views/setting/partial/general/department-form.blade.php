{!! Form::open([
    'route' => 'department.store',
    'method' => 'POST',
    'class' => 'form-horizontal',
    'id' => 'departmentFormSubmit',
    'role' => 'form',
    'files' => true,
]) !!}

@include('setting::department.partial.action', ['btnType' => 'Save Record'])

{!! Form::close() !!}
