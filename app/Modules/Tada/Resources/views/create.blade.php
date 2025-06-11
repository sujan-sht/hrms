@extends('admin::layout')
@section('title')Claim @stop
@section('breadcrum')
    <a href="{{ route('tada.index') }}" class="breadcrumb-item">Claims</a>
    <a class="breadcrumb-item active"> Add Claim </a>
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

    {!! Form::open([
        'route' => 'tada.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'tada_submit',
        'files' => true,
    ]) !!}
    @include('tada::partial.action', ['btnType' => 'Claim Now'])

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
