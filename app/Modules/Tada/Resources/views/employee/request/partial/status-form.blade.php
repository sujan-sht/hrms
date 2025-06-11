{!! Form::model($tadaModel, ['route'=>['tada.updateStatus', $tadaModel->id], 'method'=>'POST','class'=>'form-horizontal','role'=>'form', 'id' => 'tada_submit', 'files'=>true]) !!}
{{-- {!! Form::open(['id' => 'requeststatus_submit', 'method' => 'POST', 'class' => 'form-horizontal request-type-form', 'role' => 'form']) !!} --}}

<div class="modal-body">
    <fieldset class="mb-3">
        <div class="form-group row">
            <label class="col-form-label col-lg-3"> Request Status<span class="text-danger">*</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-pencil"></i></span>
                    </span>
                    @php
                        if($tadaModel->status == 'pending') {
                            $statusArray = [
                                'pending' => 'Pending', 
                                'forwarded' => 'Forwarded', 
                                'rejected' => 'Reject'
                            ];
                        } else {
                            $statusArray = [
                                'forwarded' => 'Forwarded', 
                                'fully settled' => 'Fully Settled', 
                                'partially settled' => 'Partially Settled', 
                                'rejected' => 'Reject'
                            ];
                        }
                    @endphp
                    {!! Form::select('status', $statusArray, $tadaModel->status, ['class' => 'form-control status']) !!}
                </div>
            </div>
        </div>

        <div class="form-group row partially_settled" style="display:none">
            <label class="col-form-label col-lg-3"> Method:</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-pencil"></i></span>
                    </span>
                    @php
                        $settledMethodArray = [
                            'advance' => 'Advance',
                            'loan' => 'Loan',
                            'return' => 'Return'
                        ];
                    @endphp
                    {!! Form::select('settled_method', $settledMethodArray, $value = null, ['placeholder'=>'Select Method', 'class' => 'form-control']) !!}
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
                    {!! Form::number('settled_amt', $value = null, ['class' => 'form-control']) !!}
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
                    {!! Form::textarea('settled_remarks', $value = null, ['class' => 'form-control']) !!}
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
                    {!! Form::number('request_closed_amt', $value = null, ['class' => 'form-control']) !!}
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
                    {!! Form::textarea('request_closed_remarks', $value = null, ['class' => 'form-control']) !!}
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
                    {!! Form::textarea('rejected_remarks', $value = null, ['class' => 'form-control']) !!}
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

<script type="text/javascript">
    $('document').ready(function() {

        $(".status").on('change', function() {
            var status  = $(this).val();
            if(status == 'partially settled') {
                $('.partially_settled').css('display', '');
                $('.request_closed').css('display', 'none');
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
        });
    });
</script>