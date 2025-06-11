@extends('admin::layout')
@section('title')Allowance Type @stop
@section('breadcrum')HR Requisition / TADA Management / Edit Allowance Type @stop

@section('script')
<script src="{{asset('admin/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{ asset('admin/validation/allowanceType.js') }}"></script>
@stop

@section('content')
<!-- Form inputs -->
<div class="card">
    <div class="card-header bg-teal-400 header-elements-inline">
        <h5 class="card-title">Edit Allowance Type</h5>
        <div class="header-elements">
        </div>
    </div>


    <div class="card-body">

        {!! Form::model($allowanceType, ['route'=>['allowanceType.update', $allowanceType->id], 'method'=>'PATCH','class'=>'form-horizontal','role'=>'form', 'id' => 'allowance_type_submit']) !!}
        
            @include('tada::allowanceType.partial.action',['btnType'=>'Update']) 
        
        {!! Form::close() !!}
    </div>
</div>
<!-- /form inputs -->

@endsection
