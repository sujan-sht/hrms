@extends('admin::layout')
@section('title') Roles @stop 
@section('breadcrum')
<a href="{{route('role.index')}}" class="breadcrumb-item">Roles</a>
<a class="breadcrumb-item active">Edit</a>
@endsection

@section('script')
<!-- Theme JS files -->
<script src="{{asset('admin/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_inputs.js')}}"></script>
<!-- /theme JS files -->

@stop @section('content')

<!-- Form inputs -->
<div class="card">
    <div class="card-header bg-teal-400 header-elements-inline">
        <h5 class="card-title">Edit Role</h5>
        <div class="header-elements">

        </div>
    </div>

    <div class="card-body">

        {!! Form::model($role,['method'=>'PUT','route'=>['role.update',$role->id],'class'=>'form-horizontal','id'=>'role_submit','role'=>'form','files'=>true]) !!} 
            @include('user::role.partial.action',['btnType'=>'Update']) 
        {!! Form::close() !!}
        
    </div>
</div>
<!-- /form inputs -->

@stop