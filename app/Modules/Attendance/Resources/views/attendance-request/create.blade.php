@extends('admin::layout')

@section('title')
    Create Attendance Request
@endsection

@section('breadcrum')
<a href="{{ route('attendanceRequest.index') }}" class="breadcrumb-item">Attendance Request </a>
<a class="breadcrumb-item active"> Create </a>
@endsection

@section('content')

<script>
    $(document).ready(function() {
        $('#start-timepicker').clockTimePicker();
        $('#start-timepicker1').clockTimePicker();
    })
</script>

    {!! Form::open(['route'=>'attendanceRequest.store','method'=>'POST','class'=>'form-horizontal','id'=>'attendanceFormSubmit','role'=>'form','files' => true]) !!}

        @include('attendance::attendance-request.partial.action', ['btnType'=>'Request'])

    {!! Form::close() !!}
@endsection

@push('custom_script')

    <script>
        @if(auth()->user()->user_type == 'employee')
        $(document).ready(function() {
            let empId = '{!! getEmpId() !!}';

            if (empId) {
                empModel = (jQuery.parseJSON(empId));
                $('#employee_id').val([empModel.id]).trigger('change');
            }
        });
        @endif
    </script>
@endpush
