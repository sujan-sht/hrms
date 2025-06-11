@extends('admin::layout')
@section('title')Bill @stop
@section('breadcrum')HR Requisition / TADA Management / Create Bill @stop

@section('script')
<script src="{{asset('admin/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{ asset('admin/validation/bill.js') }}"></script>
@stop

@section('content')
<!-- Form inputs -->
<div class="card">
    <div class="card-header bg-teal-400 header-elements-inline">
        <h5 class="card-title">Create Bill</h5>
        <div class="header-elements">
        </div>
    </div>

    <div class="card-body">

        {!! Form::open(['route'=>'tadaBill.store', 'method'=>'POST','class'=>'form-horizontal','role'=>'form', 'id' => 'bill_submit', 'enctype' => 'multipart/form-data']) !!}
        
            @include('tada::bill.partial.action',['btnType'=>'Save']) 
        
        {!! Form::close() !!}
    </div>
</div>
<!-- /form inputs -->

@endsection
