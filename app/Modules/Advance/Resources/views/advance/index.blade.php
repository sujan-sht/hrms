@extends('admin::layout')

@section('title') {{ $title }} @endSection

@section('breadcrum')
<a class="breadcrumb-item active">{{ $title }}</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

@if (auth()->user()->user_type != 'employee')
    @include('advance::advance.partial.filter')
@endif

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of {{ $title }}</h6>
            All the information data will be listed below. You can Create and Modify the data.
        </div>
        @if ($menuRoles->assignedRoles('advance.create'))
            <div class="mt-1">
                <a href="{{ route('advance.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                    Add</a>
            </div>
        @endif
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Employee Name</th>
                    <th>Amount Taken</th>
                    <th>Starting Date</th>
                    <th>Settlement Type</th>
                    <th>Status</th>
                    <th>Approval Status</th>
                    <th width="300px" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($advanceModels->total() != 0)
                    @foreach ($advanceModels as $key => $advanceModel)
                        <tr>
                            <td width="25px">#{{ $advanceModels->firstItem() + $key }}</td>
                            <td>
                                <div class="media">
                                    <div class="mr-3">
                                        <img src="{{ optional($advanceModel->employeeModel)->getImage() }}"
                                            class="rounded-circle" width="40" height="40" alt="">
                                    </div>
                                    <div class="media-body">
                                        <div class="media-title font-weight-semibold">
                                            {{ optional($advanceModel->employeeModel)->getFullName() }}</div>
                                        <span
                                            class="text-muted">{{ optional($advanceModel->employeeModel)->official_email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>Rs. {{ $advanceModel->advance_amount }}</td>
                            @if (setting('calendar_type') == 'BS')
                                <td>
                                    @if (!is_null($advanceModel->from_date))
                                        {{ date_converter()->eng_to_nep_convert($advanceModel->from_date) }}
                                    @endif
                                </td>
                            @else
                                <td>{{ date('d M, Y', strtotime($advanceModel->from_date)) }}</td>
                            @endif
                            <td>{{ $advanceModel->settlement_type_title }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $advanceModel->statusDetail['color'] }}">{{ $advanceModel->statusDetail['title'] }}</span>
                            </td>
                            <td>
                                {{-- @dd($advanceModel->approvalStatusDetail['color']); --}}
                                <span
                                    class="badge badge-{{ $advanceModel->approvalStatusDetail['color'] }}">{{ $advanceModel->approvalStatusDetail['title'] }}</span>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-outline-secondary btn-icon mr-1"
                                    href="{{ route('advance.view', $advanceModel->id) }}" data-popup="tooltip"
                                    data-placement="top" data-original-title="View">
                                    <i class="icon-eye"></i>
                                </a>
                                {{-- @if (($menuRoles->assignedRoles('advance.edit') && in_array($advanceModel->status, [1, 2]) && $advanceModel->settlement_type == 2) || $advanceModel->approval_status != 3) --}}
                                {{-- @dd($advanceModel->approval_status); --}}
                                @if (
                                    $menuRoles->assignedRoles('advance.edit') &&
                                        in_array($advanceModel->status, [1, 2]) &&
                                        $advanceModel->settlement_type == 2 &&
                                        (Auth::user()->user_type != 'employee' || $advanceModel->approval_status != 3))
                                    <a class="btn btn-sm btn-outline-primary btn-icon mr-1"
                                        href="{{ route('advance.edit', $advanceModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                                @if (
                                    $menuRoles->assignedRoles('advance.delete') &&
                                        ($advanceModel->approval_status != 3 || Auth::user()->user_type == 'super_admin'))
                                    <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete"
                                        link="{{ route('advance.delete', $advanceModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('advance.updateStatus') && Auth::user()->user_type != 'employee')
                                    @if ($advanceModel->approval_status != 3 || $advanceModel->approval_status != 4)
                                        <a data-toggle="modal" data-target="#updateStatus"
                                            class="btn btn-outline-warning btn-icon updateStatus mx-1"
                                            data-id="{{ $advanceModel->id }}"
                                            data-status="{{ $advanceModel->approval_status }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Status">
                                            <i class="icon-flag3"></i>
                                        </a>
                                    @endif
                                @endif
                                @if ($menuRoles->assignedRoles('advance.printPreview'))
                                    <a class="btn btn-sm btn-outline-primary btn-icon mr-1"
                                        href="{{ route('advance.printPreview', $advanceModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Print Preview">
                                        <i class="icon-printer"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="10">No Record Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $advanceModels->appends(request()->all())->links() }}
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
                    'route' => 'advance.updateStatus',
                    'method' => 'POST',
                    'class' => 'form-horizontal updateAdvanceStatusForm',
                    'role' => 'form',
                ]) !!}
                {!! Form::hidden('id', null, ['id' => 'advanceId']) !!}
                <div class="form-group">
                    <div class="row">
                        <label class="col-form-label col-lg-3">Status :</label>
                        <div class="col-lg-9">
                            {!! Form::select('approval_status', $approvalstatusList, null, [
                                'id' => 'advanceStatus',
                                'class' => 'form-control select2',
                            ]) !!}
                        </div>
                    </div>
                    <div id="statusMessage" class="row mt-3" style="display:none;">
                        <label class="col-form-label col-lg-3">Message :</label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('status_message', null, [
                                    'rows' => 3,
                                    'placeholder' => 'Write message..',
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
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

@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.updateStatus').on('click', function(e) {
            e.preventDefault();
            $('#approvalStatus').prop('selected', false);
            var id = $(this).data('id');
            var approvalStatus = $(this).data('status');
            console.log(approvalStatus);
            $('.updateAdvanceStatusForm').find('#advanceId').val(id);
            $('#advanceStatus option[value=' + approvalStatus + ']').prop('selected', true);
        });
    });
</script>
@endSection
