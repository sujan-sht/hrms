@extends('admin::employee.layout')
@section('title')TADA @stop
@section('breadcrum')Team TADA Requests @stop

@section('scripts')

{{-- <script type="text/javascript">
    $('document').ready(function () {
        $('.update_request').on('click', function () {
            var link = $(this).attr('link');
            var req_status = $(this).attr('att-status');
            $('.request-type-form').attr('action', link);
        });

        $('.update_other_request').on('click', function () {
            var link = $(this).attr('link');
            var req_status = $(this).attr('att-status');
            $('.request-type-form').attr('action', link);
        });

        $(".other_status").on('change', function() {
            var status  = $(this).val();
            if(status == 'partially settled') {
                $('.partially_settled').css('display', '');
                $('.request_closed').css('display', 'none');
                $('.rejected').css('display', 'none');
            } else  if(status == 'request closed') {
                $('.partially_settled').css('display', 'none');
                $('.request_closed').css('display', '');
                $('.rejected').css('display', 'none');
            } else  if(status == 'rejected') {
                $('.partially_settled').css('display', 'none');
                $('.request_closed').css('display', 'none');
                $('.rejected').css('display', '');
            } else {
                $('.partially_settled').css('display', 'none');
                $('.request_closed').css('display', 'none');
                $('.rejected').css('display', 'none');
            }
        })

        $('.delete_request').on('click', function() {
            var link = $(this).attr('link');
            $('.get_link').attr('href', link);
        });
    });

</script> --}}
@stop

@section('content')
@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
<div class="box">
    <div class="row">
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-header table-card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Team TADA Requests</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab"
                                aria-controls="all" aria-selected="true">All <span>{{$tadas->total()}}</span></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="requested-tab" data-toggle="tab" href="#requested" role="tab"
                                aria-controls="requested" aria-selected="false">Pending <span>{{$pending_requests->total()}}</span></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="forwarded-tab" data-toggle="tab" href="#forwarded" role="tab"
                                aria-controls="forwarded" aria-selected="false">Forwarded <span>{{$forwarded_requests->total()}}</span></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab"
                                aria-controls="approved" aria-selected="false">Fully Settled <span>{{$fully_settled_requests->total()}}</span></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="declined-tab" data-toggle="tab" href="#declined" role="tab"
                                aria-controls="declined" aria-selected="false">Partially Settled <span>{{$partially_settled_requests->total()}}</span></a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="forapproval-tab" data-toggle="tab" href="#forapproval" role="tab"
                                aria-controls="forapproval" aria-selected="false">Request Closed <span>{{$closed_requests->total()}}</span></a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab"
                                aria-controls="rejected" aria-selected="false">Rejected <span>{{$rejected_requests->total()}}</span></a>
                        </li>
                    </ul>
                    <div class="tab-content table-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                            @include('tada::employee-tada.partial.all')
                        </div>
                        <div class="tab-pane fade" id="requested" role="tabpanel" aria-labelledby="requested-tab">
                            @include('tada::employee-tada.partial.requested')
                        </div>
                        <div class="tab-pane fade" id="forwarded" role="tabpanel" aria-labelledby="forwarded-tab">
                            @include('tada::employee-tada.partial.forwarded')
                        </div>
                       <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                            @include('tada::employee-tada.partial.fully-settled')
                        </div>
                        <div class="tab-pane fade" id="declined" role="tabpanel" aria-labelledby="declined-tab">
                            @include('tada::employee-tada.partial.partially-settled')
                        </div>

                        <div class="tab-pane fade" id="forapproval" role="tabpanel" aria-labelledby="forapproval-tab">
                            @include('tada::employee-tada.partial.request-closed')
                        </div>
                        <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                            @include('tada::employee-tada.partial.rejected')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Warning modal -->
<div id="modal_theme_warning" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h6 class="modal-title">Update Tada Request Status </h6>
            </div>
            {!! Form::open(['id'=>'requeststatus_submit','method'=>'POST','class'=>'form-horizontal request-type-form','role'=>'form']) !!}
            <div class="modal-body">
                <fieldset class="mb-3">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Update Request Status</legend>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3"> Request Status<span class="text-danger">*</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                                </span>
                                {!! Form::select('status',['rejected'=>'Reject','forwarded'=>'Forward Request'], $value = null,['class'=>'form-control status']) !!}
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update Status <i class="icon-database-insert"></i>
                </button>
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- /warning modal -->


<!-- Warning modal -->
<div id="modal_theme_update" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h6 class="modal-title">Update Tada Request Status </h6>
            </div>
            {!! Form::open(['id'=>'requeststatus_submit','method'=>'POST','class'=>'form-horizontal request-type-form','role'=>'form']) !!}
            <div class="modal-body">
                <fieldset class="mb-3">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Update Request Status</legend>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3"> Request Status<span class="text-danger">*</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                                </span>
                                {!! Form::select('status',['fully settled'=>'Fully Settled', 'partially settled'=>'Partially Settled', 'request closed'=> 'Request Closed', 'rejected'=>'Reject'], $value = null,['class'=>'form-control other_status']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row partially_settled" style="display:none">
                        <label class="col-form-label col-lg-3"> Amount<span class="text-danger">*</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                                </span>
                                {!! Form::number('settled_amt', $value = null,['class'=>'form-control']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row partially_settled" style="display:none">
                        <label class="col-form-label col-lg-3"> Remarks</label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                                </span>
                                {!! Form::textarea('settled_remarks', $value = null,['class'=>'form-control']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row request_closed" style="display:none">
                        <label class="col-form-label col-lg-3"> Amount<span class="text-danger">*</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                                </span>
                                {!! Form::number('request_closed_amt', $value = null,['class'=>'form-control']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row request_closed" style="display:none">
                        <label class="col-form-label col-lg-3"> Remarks</label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                                </span>
                                {!! Form::textarea('request_closed_remarks', $value = null,['class'=>'form-control']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row rejected" style="display:none">
                        <label class="col-form-label col-lg-3"> Remarks</label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                                </span>
                                {!! Form::textarea('rejected_remarks', $value = null,['class'=>'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update Status <i class="icon-database-insert"></i>
                </button>
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- /warning modal -->

<!-- Warning modal -->
<div id="modal_theme_delete" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-body">
                <center>
                    <i class="icon-alert text-danger icon-3x"></i>
                </center>
                <br>
                <center>
                    <h2>Are You Sure Want To Delete ?</h2>
                    <a class="btn btn-success get_link" href="">Yes, Delete It!</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </center>
            </div>
        </div>
    </div>
</div>
<!-- /warning modal -->
@endsection
