@extends('admin::layout')
@section('title')Bill @stop
@section('breadcrum')HR Requisition / TADA Management / Edit Bill @stop

@section('script')
<script src="{{asset('admin/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{ asset('admin/validation/bill.js') }}"></script>
@stop

@section('content')
<!-- Form inputs -->
<div class="card">
    <div class="card-header bg-teal-400 header-elements-inline">
        <h5 class="card-title">Edit Bill</h5>
        <div class="header-elements">
        </div>
    </div>

    <div class="card-body">

        {!! Form::model($tadaBill, ['route'=>['tadaBill.update', $tadaBill->id], 'method'=>'PATCH','class'=>'form-horizontal','role'=>'form', 'id' => 'bill_submit']) !!}
        
            @include('tada::bill.partial.action',['btnType'=>'Update']) 
        
        {!! Form::close() !!}
    </div>
</div>
<!-- /form inputs -->

@endsection
