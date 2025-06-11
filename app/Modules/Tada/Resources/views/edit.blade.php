@extends('admin::layout')
@section('title')Claims @stop
@section('breadcrum')
    <a href="{{ route('tada.index') }}" class="breadcrumb-item">Claims </a>
    <a class="breadcrumb-item active"> Edit</a>
@endsection
@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/validation/tada.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select-search').select2();
        })
    </script>
@stop

@section('content')
    <!-- Form inputs -->

    {!! Form::model($tada, [
        'route' => ['tada.update', $tada->id],
        'method' => 'PATCH',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'tada_submit',
        'files' => true,
    ]) !!}

    @include('tada::partial.action', ['btnType' => 'Update Record'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
