@extends('admin::employee.layout')
@section('title') Advance Request @stop
@section('breadcrum') HR Requisition / Advance Request / Update @stop

@section('script')
<script src="{{asset('admin/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{ asset('admin/validation/tada.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select-search').select2();
    })
</script>
@stop

@section('content')
<!-- Form inputs -->
<div class="card">
    <div class="card-body">

        {!! Form::model($tada, ['route'=>['employeetadaClaim.update', $tada->id], 'method'=>'PATCH','class'=>'form-horizontal','role'=>'form', 'id' => 'tada_submit', 'files'=>true]) !!}

            @include('tada::employee.claim.partial.action',['btnType'=>'Update Record'])

        {!! Form::close() !!}
    </div>
</div>
<!-- /form inputs -->

@endsection
