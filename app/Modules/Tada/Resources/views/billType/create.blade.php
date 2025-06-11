@extends('admin::layout')
@section('title')Bill Type @stop
@section('breadcrum')HR Requisition / TADA Management / Create Bill Type @stop

@section('script')
<script src="{{asset('admin/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{ asset('admin/validation/billType.js') }}"></script>
@stop

@section('content')
<!-- Form inputs -->
<div class="card">
    <div class="card-header bg-teal-400 header-elements-inline">
        <h5 class="card-title">Create Bill Type</h5>
        <div class="header-elements">
        </div>
    </div>

    <div class="card-body">

        {!! Form::open(['route'=>'billType.store', 'method'=>'POST','class'=>'form-horizontal','role'=>'form', 'id' => 'bill_type_submit']) !!}
        
            @include('tada::billType.partial.action',['btnType'=>'Save']) 
        
        {!! Form::close() !!}
    </div>
</div>
<!-- /form inputs -->

@endsection
