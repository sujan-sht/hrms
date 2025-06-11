@extends('admin::layout')

@section('title')
    Create Attendance Team Request
@endsection

@section('breadcrum')
<a href="{{ route('attendanceRequest.index') }}" class="breadcrumb-item">Attendance Team Request </a>
<a class="breadcrumb-item active"> Create </a>
@endsection

@section('content')

<script>
    $(document).ready(function() {
        $('#start-timepicker').clockTimePicker();
        $('#start-timepicker1').clockTimePicker();
    })
</script>

    {!! Form::open(['route'=>'attendanceTeamRequest.store','method'=>'POST','class'=>'form-horizontal','id'=>'attendanceFormSubmit','role'=>'form','files' => true]) !!}

        @include('attendance::attendance-request-team.partial.action', ['btnType'=>'Request'])

    {!! Form::close() !!}

@endsection
