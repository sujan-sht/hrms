@extends('admin::layout')
@section('title')
    Substitute Leave
@endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Substitute Leaves</a>
@endsection

@section('content')


    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    @include('employee::employee-substitute-leave.partial.advance-search')


    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Substitute Leaves</h6>
                All the Substitute Leave Information will be listed below. You can Create and Modify the data.
            </div>
            @if (setting('two_step_substitute_leave') == 11)
                @if ($menuRoles->assignedRoles('substituteLeave.create'))
                    <div class="mt-1">
                        <a href="{{ route('substituteLeave.create') }}" class="btn btn-success rounded-pill">Request</a>
                    </div>
                @endif
            @else
                @if ($menuRoles->assignedRoles('substituteLeave.create'))
                    <div class="mt-1">
                        <a href="{{ route('substituteLeave.create') }}" class="btn btn-success rounded-pill">Claim Now</a>
                    </div>
                @endif
            @endif

        </div>
    </div>

    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Employee Name</th>
                        <th>Applied For</th>
                        <th>Ateendance</th>
                        <th>Remark</th>
                        <th>Status</th>
                        @if (setting('two_step_substitute_leave') == 11)
                            <th>Claim Status</th>
                        @endif
                        <th>Applied On</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($employeeSubstituteLeaveModels->total() != 0)
                        @foreach ($employeeSubstituteLeaveModels as $key => $employeeSubstituteLeaveModel)
                            <tr>
                                <td width="5%">#{{ $employeeSubstituteLeaveModels->firstItem() + $key }}</td>
                                <td>
                                    <div class="media">
                                        <div class="mr-3">
                                            <a href="#">
                                                <img src="{{ optional($employeeSubstituteLeaveModel->employee)->getImage() }}"
                                                    class="rounded-circle" width="40" height="40" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-title font-weight-semibold">
                                                {{ optional($employeeSubstituteLeaveModel->employee)->getFullName() }}</div>
                                            <span
                                                class="text-muted">{{ optional($employeeSubstituteLeaveModel->employee)->official_email }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ leaveYearSetup('calendar_type') == "BS" ?  $employeeSubstituteLeaveModel->nepali_date : date('M d, Y', strtotime($employeeSubstituteLeaveModel->date)) }}</td>
                                <td>
                                    <ul class="list-unlisted">
                                        <li class="list-group-item"> Checkin: <strong> {{ $employeeSubstituteLeaveModel->checkin }} </strong> </li>
                                        <li  class="list-group-item"> Checkout: <strong> {{ $employeeSubstituteLeaveModel->checkout }} </strong></li>
                                        <li  class="list-group-item"> Total Working Hr: <strong> {{ $employeeSubstituteLeaveModel->total_working_hr }}  </strong></li>
                                    </ul>
                                </td>
                                <td>{{ $employeeSubstituteLeaveModel->remark }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $employeeSubstituteLeaveModel->getStatusWithColor()['color'] }}">{{ $employeeSubstituteLeaveModel->getStatusWithColor()['status'] }}</span>
                                </td>
                                @if (setting('two_step_substitute_leave') == 11)
                                <td>
                                    <span
                                        class="badge badge-{{ optional(optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->getClaimStatusWithColor())['color'] }}">{{ optional(optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->getClaimStatusWithColor())['claim_status'] }}</span>
                                </td>
                                @endif
                                @php
                                    $createdDate = leaveYearSetup('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($employeeSubstituteLeaveModel->created_at))) : date('M d, Y', strtotime($employeeSubstituteLeaveModel->created_at));
                                @endphp
                                <td>{{ $createdDate }}</td>

                                <td class="d-flex">
                                    <a class="btn btn-outline-secondary btn-icon mx-1"
                                    href="{{ route('substituteLeave.show', $employeeSubstituteLeaveModel->id) }}" data-popup="tooltip"
                                    data-placement="top" data-original-title="View">
                                    <i class="icon-eye"></i>
                                    </a>
                                    @if (
                                        $menuRoles->assignedRoles('substituteLeave.updateStatus') &&
                                            auth()->user()->user_type != 'supervisor' &&
                                            in_array($employeeSubstituteLeaveModel->status, [1, 2])
                                            )
                                            {{-- $employeeSubstituteLeaveModel->status == 1) --}}
                                        <a data-toggle="modal" data-target="#updateStatus"
                                            class="btn btn-outline-warning btn-icon updateStatus mr-2"
                                            data-id="{{ $employeeSubstituteLeaveModel->id }}"
                                            data-status="{{ $employeeSubstituteLeaveModel->status }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Status">
                                            <i class="icon-flag3"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('substituteLeave.edit') && $employeeSubstituteLeaveModel->status == 1)
                                        <a class="btn btn-outline-primary btn-icon mr-2"href="{{ route('substituteLeave.edit', $employeeSubstituteLeaveModel->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('substituteLeave.delete'))
                                        <a class="btn btn-outline-danger btn-icon confirmDelete"
                                            link="{{ route('substituteLeave.delete', $employeeSubstituteLeaveModel->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif

                                    @if (setting('two_step_substitute_leave') == 11)
                                        @if( $menuRoles->assignedRoles('substituteLeave.cancelSubstituteLeaveRequest') && $employeeSubstituteLeaveModel->status == 1 && auth()->user()->user_type != 'super_admin')
                                            {!! Form::open([
                                                'route' => 'substituteLeave.cancelSubstituteLeaveRequest',
                                                'method' => 'PUT',
                                                'class' => 'form-horizontal',
                                                'role' => 'form',
                                            ]) !!}
                                            {!! Form::hidden('id', $employeeSubstituteLeaveModel->id, ['id' => 'leaveId']) !!}
                                            {!! Form::hidden('status', $value = 5) !!}

                                            <button class="btn btn-outline-warning btn-icon mr-1 confirmCancel"
                                                data-placement="bottom" data-popup="tooltip" data-original-title="Cancel"
                                                link="{{ route('substituteLeave.cancelSubstituteLeaveRequest', $employeeSubstituteLeaveModel->id) }}">
                                                <i class="icon-cancel-square"></i></button>

                                            {!! Form::close() !!}
                                        @endif


                                        @if ($employeeSubstituteLeaveModel->status == 3 && optional($employeeSubstituteLeaveModel->employeeSubstituteLeaveClaim)->claim_status == null && $employeeSubstituteLeaveModel->employee_id == auth()->user()->emp_id)
                                            <a href="{{ route('substituteLeave.claim', $employeeSubstituteLeaveModel->id) }}" class=" ml-2 btn btn-outline-success">Claim</a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">No Record Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $employeeSubstituteLeaveModels->appends(request()->all())->links() }}
            </span>
        </div>
    </div>

    <!-- popup modal -->
    <div id="updateStatus" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Update Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open([
                        'route' => 'substituteLeave.updateStatus',
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                    ]) !!}
                    {!! Form::hidden('id', null, ['id' => 'modelId']) !!}
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Status :</label>
                        <div class="col-lg-9">
                            {!! Form::select('status', $statusList, null, ['id' => 'modelStatus', 'class' => 'form-control select-search', 'required']) !!}
                        </div>
                    </div>
                    <div class="form-group row remarksDiv" style="display: none;">
                        <label class="col-form-label col-lg-3">Remarks :</label>
                        <div class="col-lg-9">
                            {!! Form::textarea('status_message', null, ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn bg-success text-white">Save Changes</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script src="{{asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.updateStatus').on('click', function() {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                $('#modelId').val(id);
                $('#modelStatus').select2("val", status);
            });

            $('#modelStatus').on('change', function () {
                var status = $(this).val()
                if(status == 2 || status == 4){
                    $('.remarksDiv').show()
                }else{
                    $('.remarksDiv').hide()
                }
            });

            $('.confirmCancel').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'cancelled!',
                            text: 'Substitute Leave request has been cancelled.',
                            icon: 'success',
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                        $(this).closest('form').submit();
                    }
                });
            });
        });
    </script>
@endSection
