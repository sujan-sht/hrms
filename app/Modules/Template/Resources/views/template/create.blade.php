@extends('admin::layout')
@section('title') Template @endSection  

@section('breadcrum')
    <a href="{{ route('template.index') }}" class="breadcrumb-item">Templates</a>
    <a class="breadcrumb-item active">Edit</a>
@endsection

<script src="{{ asset('admin/global/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/editor_ckeditor_default.js') }}"></script>

<script src="{{asset('admin/global_assets/js/demo_pages/editor_summernote.js')}}"></script>

@section('content')

    {!! Form::open(['route'=>'template.store','method'=>'POST','class'=>'form-horizontal','id'=>'templateFormSubmit','role'=>'form','files' => false]) !!}
    
        @include('template::template.partial.action',['btnType'=>'Save Record']) 
    
    {!! Form::close() !!}

@endSection