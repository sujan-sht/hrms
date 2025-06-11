@extends('admin::employee.layout')
@section('title')Claim & Request Management @stop
@section('breadcrum')Claim & Request Management @stop

@section('scripts')
    <script src="{{ asset('admin/global/js/plugins/ui/moment/moment.min.js')}}"></script>
    <script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js')}}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/picker_date.js')}}"></script>
    <script src="{{ asset('employee/validation/preOvertimeRequest.js') }}" ></script>
@stop

@section('content')
<div class="box add-request">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6><a href="{{route('claimRequest.preovertime')}}"><i class="fa fa-chevron-circle-left"></i></a> Edit Request</h6>
                </div>
                <div class="card-body">
                    <h5>Request Details</h5>
                    {!! Form::model($pre_overtime, ['route'=>['preOvertimeRequest.update', $pre_overtime->id], 'method'=>'PATCH','class'=>'form-horizontal','role'=>'form', 'id' => 'employee_request_submit']) !!}
                        @include('employeerequest::employee.request.preovertime.partial.action')

                        <hr />
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-primary mr-2" type="submit" value="submit" name="btn_name">Update</button>
                                {{--<button class="btn btn-default mr-2" type="submit" value="submit_new" name="btn_name">Update and New</button>
                                <button class="btn btn-default" type="submit">Cancel</button>--}}
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop