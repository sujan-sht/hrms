@extends('admin::employee.layout')
@section('title')TADA Manangement @stop
@section('breadcrum')Edit TADA Request @stop

@section('scripts')
<script src="{{ asset('admin/validation/tada.js') }}"></script>
@stop

@section('content')

<div class="box add-request">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6><a href="{{route('employee-tada.index')}}"><i class="fa fa-chevron-circle-left"></i></a> Edit Tada Request</h6>
                </div>
                <div class="card-body">
                    <h5>Request Details</h5>
                    {!! Form::model($tada, ['route'=>['employee-tada.update', $tada->id], 'method'=>'PATCH','class'=>'form-horizontal','role'=>'form', 'id' => 'tada_submit', 'files'=>true]) !!}
                        @include('tada::employee-tada.partial.action')

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

@endsection
