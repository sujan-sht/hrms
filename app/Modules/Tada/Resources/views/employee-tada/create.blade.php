@extends('admin::employee.layout')
@section('title')TADA Manangement @stop
@section('breadcrum')Create TADA Request @stop

@section('scripts')
<script src="{{ asset('admin/validation/tada.js') }}"></script>
@stop

@section('content')

<div class="box add-request">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6><a href="{{route('employee-tada.index')}}"><i class="fa fa-chevron-circle-left"></i></a> Add Tada</h6>
                </div>
                <div class="card-body">
                    <h5>Tada Details</h5>
                    {!! Form::open(['route'=>'employee-tada.store', 'method'=>'POST','class'=>'form-horizontal','role'=>'form', 'id' => 'tada_submit', 'files'=>true]) !!}
                        @include('tada::employee-tada.partial.action')

                        <hr />
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-primary mr-2" type="submit" value="submit" name="btn_name">Submit</button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
