@extends('admin::layout')
@section('title') Questionnaire @endSection

@section('breadcrum')
<a href="{{ route('questionnaire.index') }}" class="breadcrumb-item">Questionnaire</a>
<a class="breadcrumb-item active">Edit</a>
@endsection

@section('content')

    {!! Form::model($questionnaire,['route'=>['questionnaire.update',$questionnaire->id],'method'=>'PUT','class'=>'formClass form-horizontal','id'=>'questionnaireFormSubmit','role'=>'form','files' => true]) !!}

        @include('appraisal::questionnaires.partial.action',['btnType'=>'Update Record'])

    {!! Form::close() !!}

    <script>

    $(document).on('ready', function(){
        $(".competenciesData").select2({
            maximumSelectionLength: 3
        });
    })


    </script>

@endSection
