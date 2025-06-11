@extends('admin::layout')
@section('title') Template @endSection  

@section('breadcrum')
    <a href="{{ route('template.index') }}" class="breadcrumb-item">Templates</a>
    <a class="breadcrumb-item active">Edit</a>
@endsection

<script src="{{ asset('admin/global/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/editor_ckeditor_default.js') }}"></script>


@section('content')

    {!! Form::model($template,['route'=>['template.update',$template->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'cheatSheetFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('template::template.partial.action',['btnType'=>'Update Record']) 
    
    {!! Form::close() !!}

@endSection