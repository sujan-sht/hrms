@extends('admin::layout')

@section('title')
    Create {{ $title }}
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script src="{{ asset('admin/validation/validation.js') }}"></script>
    <script>
        customValidation('shiftFormSubmit');
    </script>
@endsection

@section('breadcrum')
    <a href="{{ route('shift.index') }}" class="breadcrumb-item">Shift </a>
    <a class="breadcrumb-item active"> Create </a>
@endsection

@section('content')
    {!! Form::open([
        'route' => 'newShift.store',
        'method' => 'POST',
        'class' => 'form-horizontal shiftFormSubmit',
        'id' => 'shiftFormSubmit',
        'role' => 'form',
        'files' => true,
    ]) !!}

    @include('newshift::shift.partial.action', ['btnType' => 'Save'])

    {!! Form::close() !!}
@endsection
