@extends('admin::layout')
@section('title') Create DropDown Value @stop 
@section('breadcrum')
<a href="{{route('dropdown.index')}}" class="breadcrumb-item">DropDowns</a>
<a class="breadcrumb-item active">Create Value</a>
@endsection

@section('script')
<!-- Theme JS files -->
<script src="{{asset('admin/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_inputs.js')}}"></script>
<!-- /theme JS files -->
<script src="{{ asset('admin/validation/dropDownValue.js')}}"></script>

@stop @section('content')

<!-- Form inputs -->
<div class="card">
    <div class="card-header bg-teal-400 header-elements-inline">
        <h5 class="card-title">Create Dropdown Value</h5>
        <div class="header-elements">

        </div>
    </div>
    
    <div class="card-body">

        {!! Form::open(['route'=>'dropdown.store','id'=>'dropDownValue_submit','method'=>'POST','class'=>'form-horizontal','role'=>'form','files' => true]) !!}
        
            @include('dropdown::Dropdown.partial.action',['btnType'=>'Save']) 
        
        {!! Form::close() !!}
    </div>
</div>
<!-- /form inputs -->

@stop