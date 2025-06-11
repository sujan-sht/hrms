@extends('admin::layout')
@section('title')Warning @stop

@section('breadcrum')
    <a href="{{ route('warning.index') }}" class="breadcrumb-item">Warning</a>
    <a class="breadcrumb-item active">Create</a>
@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    
@stop
@section('content')

    <!-- Form inputs -->

    {!! Form::open([
        'route' => 'warning.store',
        'method' => 'POST',
        'id' => 'warning_submit',
        'class' => 'form-horizontal',
        'role' => 'form',
        'files' => true,
    ]) !!}
    @include('warning::partial.action', ['btnType' => 'Save'])
    {!! Form::close() !!}

@stop
