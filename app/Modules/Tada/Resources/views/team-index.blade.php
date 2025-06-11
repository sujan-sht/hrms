@extends('admin::layout')
@section('title') Claims @stop
@section('breadcrum')
    <a class="breadcrumb-item active"> TADA / Team / Claims </a>
@endsection
@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>
@stop

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    {{-- @include('tada::partial.team-filter') --}}

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Team Claims</h6>
                All the Claim Information will be listed below. You can Create and Modify the data.
            </div>
            {{-- @if ($menuRoles->assignedRoles('tada.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('tada.create') }}" class="btn btn-success rounded-pill">Add Claim</a>
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
                            <th>Title</th>
                            <th>Employee</th>
                            <th>Nepali From Date</th>
                            <th>Nepali To Date</th>
                            <th>English From Date</th>
                            <th>English To Date</th>
                            <th>Total Requested Amount</th>
                            <th>Status</th>
                            @if (
                                $menuRoles->assignedRoles('tada.edit') ||
                                    $menuRoles->assignedRoles('tada.delete') ||
                                    $menuRoles->assignedRoles('tada.show') ||
                                    $menuRoles->assignedRoles('tada.updateStatus'))
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($tadas->count() > 0)
                            @foreach ($tadas as $key => $tada)
                                @php
                                    $requested_amt = $tada->billAmount() ?? 0;

                                    //check if there is first approval or not
                                    if (
                                        isset(
                                            optional(optional($tada->employee)->employeeClaimRequestApprovalDetailModel)
                                                ->first_claim_approval_user_id,
                                        ) &&
                                        !empty(
                                            optional(optional($tada->employee)->employeeClaimRequestApprovalDetailModel)
                                                ->first_claim_approval_user_id
                                        )
                                    ) {
                                        $singleApproval = false;
                                    } else {
                                        $singleApproval = true;
                                    }
                                    //

                                    //show update status button
                                    $thisStatus = $tada->getStatus();
                                    $user = auth()->user();
                                    $usertype = $user->user_type;

                                    if ($usertype == 'admin' || $usertype == 'super_admin' || $usertype == 'hr') {
                                        $showUpdate = true;
                                    } elseif (
                                        optional(optional($tada->employee)->employeeClaimRequestApprovalDetailModel)
                                            ->first_claim_approval_user_id == $user->id
                                    ) {
                                        if ($thisStatus == 'Pending') {
                                            $showUpdate = true;
                                        } else {
                                            $showUpdate = false;
                                        }
                                    } elseif (
                                        optional(optional($tada->employee)->employeeClaimRequestApprovalDetailModel)
                                            ->last_claim_approval_user_id == $user->id
                                    ) {
                                        if (
                                            $thisStatus == 'Forwarded' ||
                                            $thisStatus == 'Partially Settled' ||
                                            ($singleApproval == true && $thisStatus == 'Pending')
                                        ) {
                                            $showUpdate = true;
                                        } else {
                                            $showUpdate = false;
                                        }
                                    } else {
                                        $showUpdate = false;
                                    }
                                @endphp

                                <tr>
                                    {{-- <td>{{ $tadas->firstItem() + $key }}</td> --}}
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $tada->title }}</td>
                                    <td>{{ optional($tada->employee)->first_name }}
                                        {{ optional($tada->employee)->last_name }}</td>
                                    <td>{{ $tada->nep_from_date }}</td>
                                    <td>{{ $tada->nep_to_date }}</td>
                                    <td>{{ $tada->eng_from_date }}</td>
                                    <td>{{ $tada->eng_to_date }}</td>
                                    <td>Rs. {{ $requested_amt }}</td>
                                    <td class="text-teal">
                                        <span
                                            class="badge badge-{{ $tada->getStatusWithColor()['color'] }}">{{ $tada->getStatusWithColor()['status'] }}</span>
                                    </td>
                                    @if (
                                        $menuRoles->assignedRoles('tada.edit') ||
                                            $menuRoles->assignedRoles('tada.delete') ||
                                            $menuRoles->assignedRoles('tada.show') ||
                                            $menuRoles->assignedRoles('tada.updateStatus'))
                                        <td class="d-flex">
                                            @if ($menuRoles->assignedRoles('tada.show'))
                                                <a class="btn btn-outline-info btn-icon mx-1"
                                                    href="{{ route('tada.show', $tada->id) }}" data-popup="tooltip"
                                                    data-placement="bottom" data-original-title="Show Details"><i
                                                        class="icon-eye"></i>
                                                </a>
                                            @endif
                                            @if ($menuRoles->assignedRoles('tada.edit') && ($tada->status == '1' || $tada->status == '6'))
                                                <a class="btn btn-outline-primary btn-icon mx-1"
                                                    href="{{ route('tada.edit', $tada->id) }}" data-popup="tooltip"
                                                    data-placement="bottom" data-original-title="Edit">
                                                    <i class="icon-pencil7"></i>
                                                </a>
                                            @endif
                                            @if ($menuRoles->assignedRoles('tada.delete'))
                                                <a class="btn btn-outline-danger btn-icon confirmDelete mx-1"
                                                    link="{{ route('tada.delete', $tada->id) }}" data-placement="bottom"
                                                    data-popup="tooltip" data-original-title="Delete">
                                                    <i class="icon-trash-alt"></i>
                                                </a>
                                            @endif

                                            @if (
                                                $menuRoles->assignedRoles('tada.updateStatus') &&
                                                    ($tada->status == '1' || $tada->status == '2' || $tada->status == '6') &&
                                                    $showUpdate)
                                                <a data-toggle="modal"
                                                    class="btn btn-outline-secondary btn-icon mx-1 modalPopup"
                                                    data-href="{{ route('tada.updateStatusForm', $tada->id) }}"
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

    <!-- Warning modal -->
    <div id="modal_theme_status" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h6 class="modal-title">Update Request Status </h6>
                </div>
                <div id="content">

                </div>
            </div>
        </div>
    </div>
    <!-- /warning modal -->

    <script type="text/javascript">
        $(function() {
            // $('.modalPopup').click(function(e) {
            //     $('.statusList').empty()

            //     e.preventDefault();

            //     let allStatus = $(this).data('status')
            //     let href = $(this).data('href')

            //     $('.updateForm').attr("action", href)

            //     let values = Object.values(allStatus)
            //     let keys = Object.keys(allStatus)

            //     let option = ''
            //     values.forEach((element, index) => {
            //         return option += `<option value="${keys[index]}">${element}</option>`
            //     });

            //     $('.statusList').append(option)

            // });
            $('.modalPopup').click(function(e) {
                e.preventDefault();
                $('#modal_theme_status').modal('show')
                    .find('#content')
                    .load($(this).attr('data-href'));
            });
        });
    </script>

@endsection
