@extends('admin::layout')
@section('title')Fuel Consumption @stop 
@section('breadcrum')<a class="mr-1" href="{{route('fuelConsumption')}}">Fuel Consumption</a> / Edit @stop

@section('script')
<!-- Theme JS files -->
<script src="{{asset('admin/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_inputs.js')}}"></script>
<!-- /theme JS files -->

@stop @section('content')

<!-- Form inputs -->
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Edit Fuel Consumption</h5>
        <div class="header-elements">
        </div>
    </div>

    <div class="card-body">
        {!! Form::model($fuelConsumption,['method'=>'PUT','route'=>['fuelConsumption.update',$fuelConsumption->id],'class'=>'form-horizontal','id'=>'fuelConsumption_submit','role'=>'form']) !!}
             @include('fuelconsumption::fuelConsumption.partial.action',['btnType'=>'Update']) 
        {!! Form::close() !!}
    </div>
</div>
<!-- /form inputs -->
@stop