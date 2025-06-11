@extends('admin::layout')
@section('title') Requests @stop
@section('breadcrum')
    <a href="{{ route('tadaRequest.index') }}" class="breadcrumb-item">Requests</a>
    <a class="breadcrumb-item active"> Create </a>
@endsection
@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/validation/tadaRequest.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select-search').select2();
        })
    </script>
@stop

@section('content')
    <!-- Form inputs -->


    {!! Form::open([
        'route' => 'tadaRequest.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'tadaRequest_submit',
        'files' => true,
    ]) !!}

    @include('tada::request.partial.action', ['btnType' => 'Request Now'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection

@push('custom_script')
    <script>
        $(document).ready(function() {
            let empId = '{!! getEmpId() !!}';

            if (empId) {
                empModel = (jQuery.parseJSON(empId));
                $('#employee_id').val([empModel.id]).trigger('change');
            }
        });
    </script>
@endpush
