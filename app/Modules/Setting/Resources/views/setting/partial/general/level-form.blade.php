{!! Form::open([
    'route' => 'level.store',
    'method' => 'POST',
    'class' => 'form-horizontal',
    'id' => 'levelFormSubmit',
    'role' => 'form',
    'files' => true,
]) !!}

@include('setting::level.partial.action', ['btnType' => 'Save Record'])

{!! Form::close() !!}
