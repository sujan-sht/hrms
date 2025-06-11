@extends('admin::layout')

@section('title')
    Create {{ $title }}
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
@stop

@section('breadcrum')
    <a href="{{ route('shiftGroup.index') }}" class="breadcrumb-item">Shift Group</a>
    <a class="breadcrumb-item active"> Create </a>
@endsection

@section('content')

    {!! Form::open([
        'route' => 'shiftGroup.store',
        'method' => 'POST',
        'id' => 'shiftGroupSubmit',
        'class' => 'form-horizontal',
        'role' => 'form',
        'files' => true,
    ]) !!}

    @include('shift::group.partial.action', ['btnType' => 'Save'])

    {!! Form::close() !!}

@stop
