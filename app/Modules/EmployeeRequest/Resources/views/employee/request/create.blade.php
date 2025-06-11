@extends('admin::employee.layout')
@section('title')Claim & Request Management @stop
@section('breadcrum')Claim & Request Management @stop
@section('scripts')
    <script src="{{ asset('employee/validation/employeeRequest.js') }}"></script>
    <script>
        function cancel() {
            document.execCommand('Stop')
        }
    </script>
@stop

@section('content')

    <div class="box add-request">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6><a href="{{ route('employeerequest.index') }}"><i class="fa fa-chevron-circle-left"></i></a> Add
                            Request</h6>
                    </div>
                    <div class="card-body">
                        <h5>Request Details</h5>
                        {!! Form::open([
                            'route' => 'employeeRequest.store',
                            'method' => 'POST',
                            'class' => 'form-horizontal',
                            'role' => 'form',
                            'id' => 'employee_request_submit',
                        ]) !!}
                        @include('employeerequest::employee.request.partial.action')

                        <hr />
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-primary mr-2" type="submit" value="submit"
                                    name="btn_name">Submit</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
