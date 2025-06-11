@extends('admin::employee.layout')
@section('title') Claim Request @stop
@section('breadcrum') HR Requisition / Claim Request / Create @stop

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

        {!! Form::open(['route'=>'employeetadaClaim.store', 'method'=>'POST','class'=>'form-horizontal','role'=>'form', 'id' => 'tada_submit', 'files'=>true]) !!}

            @include('tada::employee.claim.partial.action',['btnType'=>'Save Record'])

        {!! Form::close() !!}
    </div>
</div>
<!-- /form inputs -->

@endsection
