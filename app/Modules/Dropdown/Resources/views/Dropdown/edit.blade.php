@extends('admin::layout')
@section('title')Edit Dropdown Value @stop 
@section('breadcrum')Edit Dropdown Value @stop

@section('script')
<!-- Theme JS files -->
<script src="{{asset('admin/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_inputs.js')}}"></script>
<!-- /theme JS files -->

@stop @section('content')

<!-- Form inputs -->
<div class="card">
    <div class="card-header bg-teal-400 header-elements-inline">
        <h5 class="card-title">Edit Dropdown Value</h5>
        <div class="header-elements">

        </div>
    </div>
    
    <div class="card-body">

        {!! Form::model($dropdown_val,['method'=>'PUT','route'=>['dropdown.update',$dropdown_val->id],'class'=>'form-horizontal','role'=>'form','files'=>true]) !!} 
        
            @include('dropdown::Dropdown.partial.action',['btnType'=>'Update']) 
        
        {!! Form::close() !!}
    </div>
</div>
<!-- /form inputs -->

@stop