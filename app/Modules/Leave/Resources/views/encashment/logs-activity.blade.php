@extends('admin::layout')
@section('title') Leave Encashment Logs @stop

@section('breadcrum')
    <a class="breadcrumb-item active">Leave Encashment Logs</a>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
                style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>

    {{-- <div class="card">
        <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
            <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('leave.encashment') }}" method="GET">
                <div class="row">

                    <div class="col-md-3 mb-2">
                        <label class="form-label">Organization</label>
                        {!! Form::select('organization_id', $organizationList, $value = request('organization_id') ?: null, [
                            'placeholder' => 'Select Organization',
                            'class' => 'form-control select-search',
                        ]) !!}
                    </div>

                </div>
                <div class="d-flex justify-content-end mt-2">
                    <button class="btn bg-yellow mr-2" type="submit">
                        <i class="icon-filter3 mr-1"></i>Filter
                    </button>
                    <a href="{{ request()->url() }}" class="btn bg-secondary text-white">
                        <i class="icons icon-reset mr-1"></i>Reset
                    </a>
                </div>
            </form>

        </div>
    </div> --}}
    <!-- Warning modal -->
    <div id="modal_theme_warning_status" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h6 class="modal-title">Encashed ?</h6>
                </div>
                <script src="{{ asset('admin/global/js/plugins/pickers/pickadate/picker.js') }}"></script>
                <script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>

                <div class="modal-body">
                    {!! Form::open([
                        'route' => 'leave.updateArchivedEncashmentDate',
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'files' => true,
                    ]) !!}


                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Archive Date:</label>
                        <div class="col-lg-9">
                            @if (setting('calendar_type') == 'BS')
                                {!! Form::text('archived_date', $value = null, [
                                    'placeholder' => 'Select Archive Date',
                                    'class' => 'form-control nepali-calendar',
                                    'required',
                                ]) !!}
                            @else
                                {!! Form::text('archived_date', $value = null, [
                                    'placeholder' => 'Select Archive Date',
                                    'class' => 'form-control daterange-single',
                                    'required',
                                ]) !!}
                            @endif
                            <input type="text" value="" id="encashment_id" name="encashment_id" hidden>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn bg-success" id="encashed">Encashed</button>
                        <button type="button" class="btn bg-danger" data-dismiss="modal">Close</button>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- warning modal -->
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Encash Date</th>
                        <th>Encashable leave balance</th>
                        <th>Encashable Amount</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($leaveEncashmentLogsActivity) > 0)
                        @foreach ($leaveEncashmentLogsActivity as $key => $leaveEncashmentActivity)
                            <tr>
                                <td width="5%">#{{ $leaveEncashmentLogsActivity->firstItem() + $key }}</td>
                                <td>{{ optional(optional($leaveEncashmentActivity->leaveEncashmentLog)->employee)->full_name }}
                                </td>
                                <td>{{ optional(optional($leaveEncashmentActivity->leaveEncashmentLog)->leaveType)->name }}
                                </td>
                                <td>{{ optional($leaveEncashmentActivity->leaveEncashmentLog)->encashed_date }}</td>
                                <td>{{ $leaveEncashmentActivity->encashed_leave_balance }}</td>
                                <td>{{ optional($leaveEncashmentActivity->leaveEncashmentLog)->encashed_amount }}</td>
                                <td>{{ $leaveEncashmentActivity->payroll_id ? 'Payroll' : 'Archived' }}</td>
                                <td>
                                    @if (!$leaveEncashmentActivity->payroll_id && !$leaveEncashmentActivity->leaveEncashmentLog->encashed_date)
                                        <a class="btn btn-outline-warning btn-icon border-1 status_employee" id="encashedClicked" data-indexId="{{ $leaveEncashmentActivity->id }}"><i
                                                class="icon-calendar" ></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">Employee Leave Encashment Details Not Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $leaveEncashmentLogsActivity->appends(request()->all())->links() }}
            </span>
        </div>
    </div>

    <script type="text/javascript">
        $('document').ready(function() {
            $('#encashedClicked').on('click', function() {
                var indexId = $(this).attr('data-indexId');
                if(indexId){
                    $('#encashment_id').val(indexId);
                    $('#modal_theme_warning_status').modal('show');
                    return true;
                }
                $('#encashment_id').val('');
                $('#modal_theme_warning_status').modal('hide');
            });
        });
    </script>
@endsection
