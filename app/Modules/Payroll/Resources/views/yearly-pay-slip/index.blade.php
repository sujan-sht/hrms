@extends('admin::layout')
@section('title') Yearly Pay Slip @endSection
@section('breadcrum')
<a href="{{route('payroll.index')}}" class="breadcrumb-item">Payroll</a>
<a class="breadcrumb-item active">Yearly PaySlip</a>
@stop

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>
@include('payroll::yearly-pay-slip.partial.advance_filter')
@endSection