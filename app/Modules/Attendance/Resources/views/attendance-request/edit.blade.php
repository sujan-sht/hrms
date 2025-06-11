@extends('admin::layout')

@section('title')
    Edit Attendance Request
@endsection

@section('breadcrum')
<a href="{{ route('attendanceRequest.index') }}" class="breadcrumb-item">Attendance Request </a>
<a class="breadcrumb-item active"> Edit </a>
@endsection

@section('content')

<script>
    $(document).ready(function() {
        $('#start-timepicker').clockTimePicker();
        $('#start-timepicker1').clockTimePicker();
    })
</script>

    {!! Form::model($request,['route'=>['attendanceRequest.update',$request->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'attendanceFormSubmit','role'=>'form','files' => true]) !!}

        @include('attendance::attendance-request.partial.action', ['btnType'=>'Update'])

    {!! Form::close() !!}

@endsection
