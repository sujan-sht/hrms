@extends('admin::layout')
@section('title')Requests @stop
@section('breadcrum')
    <a href="{{ route('tadaRequest.index') }}" class="breadcrumb-item">Requests</a>
    <a class="breadcrumb-item active"> Edit </a>
@endsection
@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/validation/tadaRequest.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select-search').select2();
            // $('#employee_id').prop("disabled", true);
        })
    </script>
@stop

@section('content')
    <!-- Form inputs -->

    {!! Form::model($tada, [
        'route' => ['tadaRequest.update', $tada->id],
        'method' => 'PATCH',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'tadaRequest_submit',
        'files' => true,
    ]) !!}

    @include('tada::request.partial.action', ['btnType' => 'Update Record'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
