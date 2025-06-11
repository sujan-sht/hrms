@extends('admin::layout')
@section('title')
    Forms
@endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Forms</a>
@endSection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @include('pms::set-form.partial.search')
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Forms</h6>
                All the Forms Information will be listed below. You can Create and View the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('set-form.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                    Add</a>
            </div>
        </div>
    </div>

    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Employee</th>
                        <th>Organization</th>
                        <th>Sub-Function</th>
                        <th>Designation</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created Date</th>
                        <th>Rollout Date</th>
                        <th width="12%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($PmsEmployeesModels->total() != 0)
                        @foreach ($PmsEmployeesModels as $key => $PmsEmployeesModel)
                            <tr>
                                <td width="5%">#{{ $PmsEmployeesModels->firstItem() + $key }}</td>
                                <td>{{ optional($PmsEmployeesModel->employeeModel)->full_name }}</td>
                                <td>{{ optional(optional($PmsEmployeesModel->employeeModel)->organizationModel)->name }}
                                </td>
                                <td>{{ optional(optional($PmsEmployeesModel->employeeModel)->department)->title }}</td>
                                <td>{{ optional(optional($PmsEmployeesModel->employeeModel)->designation)->title }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $PmsEmployeesModel->getStatusWithColor()['color'] }}">{{ $PmsEmployeesModel->getStatusWithColor()['status'] }}</span>
                                </td>
                                <td>{{ optional(optional($PmsEmployeesModel->userModel)->userEmployer)->full_name }}</td>
                                <td>{{ getStandardDateFormat($PmsEmployeesModel->created_at) }}</td>
                                <td>{{ $PmsEmployeesModel->rollout_date }}</td>


                                <td class="d-flex">
                                    @if ($menuRoles->assignedRoles('PMSViewFinalReport'))
                                        <a class="btn btn-outline-secondary btn-icon mx-1"
                                            href="{{ route('PMSViewFinalReport', ['emp_id' => $PmsEmployeesModel->employee_id]) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="View Report">
                                            <i class="icon-eye"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('set-form.pmsEmployeeupdateStatus') && $PmsEmployeesModel->status != 5)
                                        <a data-toggle="modal" data-target="#updateStatus"
                                            class="btn btn-outline-warning btn-icon updateStatus mx-1"
                                            data-id="{{ $PmsEmployeesModel->id }}"
                                            data-status="{{ $PmsEmployeesModel->status }}"
                                            data-employee_id="{{ $PmsEmployeesModel->employee_id }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Status">
                                            <i class="icon-flag3"></i>
                                        </a>
                                    @endif

                                    @if ($PmsEmployeesModel->status == 1)
                                        @if ($menuRoles->assignedRoles('set-form.pmsEmployeeupdateStatus'))
                                            <a data-toggle="modal" data-target="#rolloutModal"
                                                class="btn btn-outline-secondary btn-icon setRollout mx-1"
                                                data-id="{{ $PmsEmployeesModel->id }}"
                                                data-rolloutdate="{{ $PmsEmployeesModel->rollout_date }}"
                                                data-employee_id="{{ $PmsEmployeesModel->employee_id }}"
                                                data-type="{{ $PmsEmployeesModel->type }}" data-popup="tooltip"
                                                data-placement="top" data-original-title="Set rollout date">
                                                <i class="icon-gear"></i>
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Forms Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $PmsEmployeesModels->appends(request()->all())->links() }}
            </span>
        </div>
    </div>

    <!-- Update Status Modal -->
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
                        'route' => 'set-form.pmsEmployeeupdateStatus',
                        'method' => 'POST',
                        'class' => 'form-horizontal updatePmsEmployeeStatusForm',
                        'role' => 'form',
                    ]) !!}
                    {!! Form::hidden('id', null, ['id' => 'pmsEmployeeId']) !!}
                    {!! Form::hidden('employee_id', null, ['id' => 'employeeId']) !!}

                    <div class="form-group">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Status :</label>
                            <div class="col-lg-9">
                                {!! Form::select('status', $statusList, null, ['id' => 'pmsEmployeeStatus', 'class' => 'form-control select2']) !!}
                            </div>
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

    <!-- Set Rollout Modal -->
    <div id="rolloutModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Set Rollout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open([
                        'route' => 'set-form.setRollout',
                        'method' => 'POST',
                        'class' => 'form-horizontal setRolloutDate',
                        'role' => 'form',
                    ]) !!}
                    {!! Form::hidden('id', null, ['id' => 'pms_employee']) !!}
                    {!! Form::hidden('employee_id', null, ['id' => 'employeeId']) !!}

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="row">
                                <label class="col-form-label col-lg-3">Type<span class="text-danger"> *</span></label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input checkType" name="type" value="1"
                                        checked id="sendNow">
                                    <label class="form-check-label" for="sendNow">Send Now</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input checkType" name="type" value="2"
                                        id="schedule">
                                    <label class="form-check-label" for="schedule">Schedule</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-2 scheduleDiv d-none">
                            <div class="row">
                                <label class="col-form-label col-lg-3">Date<span class="text-danger"> *</span></label>
                                <div class="col-lg-9">
                                    {!! Form::text('rollout_date', $value = null, [
                                        'placeholder' => 'Choose Date',
                                        'class' => 'form-control daterange-single rolloutDatePicker date readonly',
                                        'required',
                                        'id' => 'rolloutDate',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
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
    <script>
        $(document).ready(function() {

            $('.updateStatus').on('click', function() {
                var id = $(this).data('id')
                var employeeId = $(this).data('employee_id')
                var pmsEmployeeStatus = $(this).data('status')

                $('.updatePmsEmployeeStatusForm').find('#pmsEmployeeId').val(id)
                $('.updatePmsEmployeeStatusForm').find('#employeeId').val(employeeId)
                $('#pmsEmployeeStatus option[value=' + pmsEmployeeStatus + ']').prop('selected', true)
            });

            $('.setRollout').on('click', function() {
                let type = $(this).data('type')

                $('.setRolloutDate').find('#rolloutDate').val($(this).data('rolloutdate'))
                $('.setRolloutDate').find('#pms_employee').val($(this).data('id'))
                $('.setRolloutDate').find('#employeeId').val($(this).data('employee_id'))
                if (type) {
                    if (type == 2) {
                        $('#schedule').prop("checked", true)
                        $('.checkType').trigger('click')
                    }
                }

                $('#rolloutDate').daterangepicker({
                    parentEl: '.content-inner',
                    singleDatePicker: true,
                    showDropdowns: true,
                    autoUpdateInput: false,
                    minDate: new Date(),
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                });
                $('#rolloutDate').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD'));
                });

                $('#rolloutDate').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });
            })


            $('.checkType').on('click', function() {
                var type = $(this).val();
                $('.scheduleDiv').addClass('d-none')
                if (type == 2) {
                    $('.scheduleDiv').removeClass('d-none')
                }
            })

        })
    </script>
@endSection
