{!! Form::open([
    'route' => 'designation.store',
    'method' => 'POST',
    'class' => 'form-horizontal',
    'id' => 'designationFormSubmit',
    'role' => 'form',
    'files' => true,
]) !!}

@include('setting::designation.partial.action', ['btnType' => 'Save Record'])

{!! Form::close() !!}
