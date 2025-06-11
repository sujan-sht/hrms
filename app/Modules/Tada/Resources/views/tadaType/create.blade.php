@extends('admin::layout')
@section('title')TADA Type @stop
@section('breadcrum')
    <a href="{{ route('tadaType.index') }}" class="breadcrumb-item">TADA Types</a>
    <a class="breadcrumb-item active"> Add</a>
@endsection
@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/validation/tadaType.js') }}"></script>
@stop

@section('content')
    <!-- Form inputs -->

    {!! Form::open([
        'route' => 'tadaType.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'tada_type_submit',
    ]) !!}

    @include('tada::tadaType.partial.action', ['btnType' => 'Save Record'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
