{!! Form::model($tadaModel, [
    'route' => ['tada.updateStatus', $tadaModel->id],
    'method' => 'POST',
    'class' => 'form-horizontal',
    'role' => 'form',
    'id' => 'tada_submit',
    'files' => true,
]) !!}
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
                        $thisStatus = $tadaModel->getStatus();
                        $user = auth()->user();
                        $usertype = $user->user_type;
                        $statusArray = [];

                        //check if there is only first approval or not
                        if (isset(optional(optional($tadaModel->employee)->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id) && !empty(optional(optional($tadaModel->employee)->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id)) {
                            $singleApproval = false;
                        } else {
                            $singleApproval = true;
                        }
                        //

                        if ($usertype == 'admin' || $usertype == 'super_admin' || $usertype == 'hr' || $usertype == 'division_hr') {
                            $statusArray = [
                                '1' => 'Pending',
                                '2' => 'Forwarded',
                                '3' => 'Accepted',
                                '4' => 'Rejected',
                                '5' => 'Fully Settled',
                                '6' => 'Partially Settled',
                            ];
                            $showUpdate = true;
                        } elseif (optional(optional($tadaModel->employee)->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id == $user->id) {
                            if ($thisStatus == 'Pending') {
                                $statusArray = [
                                    '1' => 'Pending',
                                    '2' => 'Forwarded',
                                    '4' => 'Rejected',
                                ];
                                $showUpdate = true;
                            } else {
                                $statusArray = [];
                                $showUpdate = false;
                            }
                        } elseif (optional(optional($tadaModel->employee)->employeeClaimRequestApprovalDetailModel)->last_claim_approval_user_id == $user->id) {
                            if ($thisStatus == 'Forwarded') {
                                $statusArray = [
                                    '2' => 'Forwarded',
                                    '3' => 'Accepted',
                                    '4' => 'Rejected',
                                    '5' => 'Fully Settled',
                                    '6' => 'Partially Settled',
                                ];
                                $showUpdate = true;
                            } elseif ($thisStatus == 'Partially Settled') {
                                $statusArray = [
                                    '6' => 'Partially Settled',
                                    '3' => 'Accepted',
                                    '4' => 'Rejected',
                                    '5' => 'Fully Settled',
                                ];
                                $showUpdate = true;
                            } elseif ($thisStatus == 'Pending' && $singleApproval == true) {
                                $statusArray = [
                                    '1' => 'Pending',
                                    '3' => 'Accepted',
                                    '4' => 'Rejected',
                                    '5' => 'Fully Settled',
                                    '6' => 'Partially Settled',
                                ];
                                $showUpdate = true;
                            } else {
                                $statusArray = [];
                                $showUpdate = false;
                            }
                        } else {
                            $showUpdate = false;
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
                            'return' => 'Return',
                        ];
                    @endphp
                    {!! Form::select('settled_method', $settledMethodArray, $value = null, [
                        'placeholder' => 'Select Method',
                        'class' => 'form-control',
                    ]) !!}
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

        <div class="form-group row forwaded" style="display:none">
            <label class="col-form-label col-lg-3"> Remarks</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-pencil"></i></span>
                    </span>
                    {!! Form::textarea('forwaded_remarks', $value = null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>


    </fieldset>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-success">Update Status
    </button>
    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
</div>
{!! Form::close() !!}

<script type="text/javascript">
    $('document').ready(function() {

        $(".status").on('change', function() {
            var status = $(this).val();
            console.log(status);
            if (status == '4') {
                $('.partially_settled').css('display', 'none');
                $('.request_closed').css('display', 'none');
                $('.forwaded').css('display', 'none');
                $('.rejected').css('display', '');
            } else if (status == '2') {
                $('.partially_settled').css('display', 'none');
                $('.request_closed').css('display', 'none');
                $('.forwaded').css('display', '');
                $('.rejected').css('display', 'none');
            } else if (status == '6') {
                $('.partially_settled').css('display', '');
                $('.request_closed').css('display', 'none');
                $('.rejected').css('display', 'none');
                $('.forwaded').css('display', 'none');

            } else {
                $('.partially_settled').css('display', 'none');
                $('.request_closed').css('display', 'none');
                $('.rejected').css('display', 'none');
                $('.forwaded').css('display', 'none');

            }
        });

    });
</script>
