@extends('admin::layout')
@section('title') Requests @stop
@section('breadcrum')
    <a class="breadcrumb-item active"> TADA / Team / Requests  </a>
@endsection
@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@stop

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of All Team Requests</h6>
                All the Request Information will be listed below. You can Create and Modify the data.

            </div>
            {{-- @if ($menuRoles->assignedRoles('tadaRequest.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('tadaRequest.create') }}" class="btn btn-success rounded-pill">Add Request</a>
                </div>
            @endif --}}

        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th>#</th>
                            {{-- <th>Title</th> --}}
                            <th>Employee</th>
                            <th>Requested Date</th>
                            <th>Total Requested Amount</th>
                            <th>Status</th>
                            @if ($menuRoles->assignedRoles('tadaRequest.edit') ||
                                $menuRoles->assignedRoles('tadaRequest.delete') ||
                                $menuRoles->assignedRoles('tadaRequest.show') ||
                                $menuRoles->assignedRoles('tadaRequest.updateStatus'))
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($tadas->count() > 0)
                            @foreach ($tadas as $key => $tada)
                                {{-- @php
                                    $requested_amt = $tada->billAmount() ?? 0;
                                    $getStatusList = [
                                    ];
                                @endphp --}}
                                @php
                                    $requested_amt = $tada->billAmount() ?? 0;

                                    //check if there is only first approval or not
                                    if (isset(optional(optional($tada->employee)->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id) && !empty(optional(optional($tada->employee)->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id)) {
                                        $singleApproval = false;
                                    } else {
                                        $singleApproval = true;
                                    }
                                    //
                                    $thisStatus = $tada->getStatus();

                                    $user = auth()->user();
                                    $usertype = $user->user_type;
                                    $statusArray = [];

                                    if ($usertype == 'admin' || $usertype == 'super_admin' || $usertype == 'hr') {
                                        $statusArray = [
                                            '1' => 'Pending',
                                            '2' => 'Forwarded',
                                            '3' => 'Accepted',
                                            '4' => 'Rejected',
                                        ];
                                        $showUpdate = true;
                                    } elseif (optional(optional($tada->employee)->employeeClaimRequestApprovalDetailModel)->first_claim_approval_user_id == $user->id) {
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
                                    } elseif (optional(optional($tada->employee)->employeeClaimRequestApprovalDetailModel)->last_claim_approval_user_id == $user->id) {

                                        if ($thisStatus == 'Forwarded') {
                                            $statusArray = [
                                                '2' => 'Forwarded',
                                                '3' => 'Accepted',
                                                '4' => 'Rejected',
                                            ];
                                            $showUpdate = true;
                                        }elseif($thisStatus == 'Pending' && $singleApproval == true){
                                            $statusArray = [
                                                '1' => 'Pending',
                                                '3' => 'Accepted',
                                                '4' => 'Rejected',
                                            ];
                                            $showUpdate = true;
                                        }else {
                                            $statusArray = [];
                                            $showUpdate = false;
                                        }
                                    } else {
                                        $showUpdate = false;
                                    }
                                @endphp


                                <tr>
                                    {{-- <td>{{ $tadas->firstItem() + $key }}</td> --}}
                                    <td>{{ $key+1}}</td>
                                    {{-- <td>{{ $tada->title }}</td> --}}
                                    <td>{{ optional($tada->employee)->first_name }}
                                        {{ optional($tada->employee)->last_name }}</td>
                                    <td>{{ $tada->nep_request_date }}</td>
                                    <td>Rs. {{ $requested_amt }}</td>
                                    <td class="text-teal">
                                        <span class="badge badge-{{ $tada->getStatusWithColor()['color'] }}">{{ $tada->getStatusWithColor()['status'] }}</span>
                                    </td>
                                    @if ($menuRoles->assignedRoles('tadaRequest.edit') ||
                                        $menuRoles->assignedRoles('tadaRequest.delete') ||
                                        $menuRoles->assignedRoles('tadaRequest.show') ||
                                        $menuRoles->assignedRoles('tadaRequest.updateStatus'))
                                        <td class="d-flex">
                                            @if ($menuRoles->assignedRoles('tadaRequest.show'))
                                                <a class="btn btn-outline-info btn-icon mx-1"
                                                    href="{{ route('tadaRequest.show', $tada->id) }}" data-popup="tooltip"
                                                    data-placement="bottom" data-original-title="Show Details"><i
                                                        class="icon-eye"></i>
                                                </a>
                                            @endif
                                            @if ($menuRoles->assignedRoles('tadaRequest.edit') &&
                                                ($tada->status == '1' || $tada->status == '6'))
                                                <a class="btn btn-outline-primary btn-icon mx-1"
                                                    href="{{ route('tadaRequest.edit', $tada->id) }}" data-popup="tooltip"
                                                    data-placement="bottom" data-original-title="Edit">
                                                    <i class="icon-pencil7"></i>
                                                </a>
                                            @endif
                                            @if ($menuRoles->assignedRoles('tadaRequest.delete'))
                                                <a data-toggle="modal" data-target="#modal_theme_warning"
                                                    class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                                    link="{{ route('tadaRequest.delete', $tada->id) }}"
                                                    data-placement="bottom" data-popup="tooltip"
                                                    data-original-title="Delete">
                                                    <i class="icon-trash-alt"></i>
                                                </a>
                                            @endif

                                            @if ($menuRoles->assignedRoles('tadaRequest.updateStatus')  && ($tada->status == '1' || $tada->status == '2') && $showUpdate)
                                                <a data-toggle="modal" class="btn btn-outline-secondary btn-icon mx-1 modalPopup"
                                                    data-status="{{ json_encode($statusArray) }}"
                                                    data-href="{{ route('tadaRequest.updateStatus', $tada->id) }}"
                                                    data-placement="bottom" data-popup="tooltip"
                                                    data-original-title="Update Status">
                                                    <i class="icon-flag3"></i>
                                                </a>
                                            @endif

                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>No Data Found!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <span style="margin: 5px;float: right;">
                    {{-- @if ($tadas->total() != 0)
                        {{ $tadas->links() }}
                    @endif --}}
                </span>
            </div>
        </div>
    </div>

    <!-- update status -->
    <div id="modal_theme_status" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h6 class="modal-title">Update Request Status </h6>
                </div>
                <div id="content">
                    <form class="updateForm" method="POST">@csrf
                        <div class="modal-body">
                            <fieldset class="mb-3">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3"> Request Status<span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text"><i class="icon-pencil"></i></span>
                                            </span>
                                            <select name="status"class="form-control statusList">

                                            </select>
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
                                            {!! Form::textarea('forwarded_remarks', $value = null, ['class' => 'form-control']) !!}
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
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /update status -->

    <script>
        $(function() {
            $('.modalPopup').click(function(e) {
                $('.statusList').empty()

                e.preventDefault();
                $('#modal_theme_status').modal('show')

                let allStatus = $(this).data('status')
                let href = $(this).data('href')

                $('.updateForm').attr("action", href)

                let values = Object.values(allStatus)
                let keys = Object.keys(allStatus)

                let option = ''
                values.forEach((element, index) => {
                    return option += `<option value="${keys[index]}">${element}</option>`
                });
                $('.statusList').append(option)

                // show rejected remarks section
                $(".statusList").on('change', function() {
                    var status  = $(this).val();
                    $('.rejected').css('display', 'none');
                    $('.forwaded').css('display', 'none');

                    if (status == '4') {
                        $('.rejected').css('display', '');
                    }
                    if (status == '2') {
                        $('.forwaded').css('display', '');
                    }
                });
                //

            });
        });
    </script>

@endsection
