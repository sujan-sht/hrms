@extends('admin::layout')
@section('title') Applicants @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Applicants</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('onboarding::applicant.partial.advance_filter')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Applicants</h6>
            All the Applicant Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1 mr-2">
            <a href="{{ route('applicant.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                Add</a>
        </div>
        <div class="list-icons mt-2">
            <div class="dropdown position-static">
                <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false">
                    <i class="icon-more2"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('applicant.exportExcel') }}" class="dropdown-item">
                        <i class="icon-file-excel text-success"></i> Export
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>MRF</th>
                    <th>Functional Title</th>
                    <th>Applicant Name</th>
                    <th>Gender</th>
                    <th>Personal Email</th>
                    <th>Contact Number</th>
                    <th>Current Address</th>
                    <th>Expected Salary</th>
                    <th>Source</th>
                    <th>Apply Date</th>
                    <th>Status</th>
                    <th width="12%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($applicantModels->total() != 0)
                    @foreach ($applicantModels as $key => $applicantModel)
                        <tr>
                            <td width="5%">#{{ $applicantModels->firstItem() + $key }}</td>
                            <td>{{ optional($applicantModel->mrfModel)->reference_number }}</td>
                            <td>{{ optional($applicantModel->mrfModel)->title }}</td>
                            <td>{{ $applicantModel->getFullName() }}</td>
                            <td>{{ $applicantModel->gender ? $applicantModel->getGender() : '' }}</td>
                            <td>{{ $applicantModel->email }}</td>
                            <td>{{ $applicantModel->mobile }}</td>
                            <td>{{ $applicantModel->address }}</td>
                            <td>Rs. {{ number_format($applicantModel->expected_salary) }}</td>
                            <td>{{ $applicantModel->source ? $applicantModel->getSource() : '' }}</td>
                            @php
                                $appliedDate =
                                    setting('calendar_type') == 'BS'
                                        ? date_converter()->eng_to_nep_convert(
                                            date('Y-m-d', strtotime($applicantModel->created_at)),
                                        )
                                        : date('M d, Y', strtotime($applicantModel->created_at));
                            @endphp
                            <td>{{ $applicantModel->created_at ? $appliedDate : '-' }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $applicantModel->getStatusWithColor()['color'] }}">{{ $applicantModel->getStatusWithColor()['status'] }}</span>
                            </td>
                            <td>
                                <a href="{{ route('applicant.view', $applicantModel->id) }}"
                                    class="btn btn-sm btn-outline-secondary btn-icon mr-1" data-popup="tooltip"
                                    data-placement="top" data-original-title="View">
                                    <i class="icon-eye"></i>
                                </a>
                                @if ($menuRoles->assignedRoles('applicant.updateStatus'))
                                    <a class="btn btn-sm btn-outline-warning btn-icon updateStatus mr-1"
                                        data-toggle="modal" data-target="#updateStatus"
                                        data-id="{{ $applicantModel->id }}"
                                        data-status="{{ $applicantModel->status }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Status">
                                        <i class="icon-flag3"></i>
                                    </a>
                                @endif
                                @if ($applicantModel->status == '2')
                                    <a class="btn btn-sm btn-outline-success btn-icon mr-1"
                                        href="{{ route('interview.create') . '?applicant=' . $applicantModel->id }}"
                                        data-popup="tooltip" data-placement="top"
                                        data-original-title="Schedule Interview">
                                        <i class="icon-calendar3"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('applicant.edit'))
                                    <a class="btn btn-sm btn-outline-primary btn-icon mr-1"
                                        href="{{ route('applicant.edit', $applicantModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('applicant.delete'))
                                    <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete"
                                        link="{{ route('applicant.delete', $applicantModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
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
            {{ $applicantModels->appends(request()->all())->links() }}
        </span>
    </div>
</div>

<!-- popup modal -->
<div id="updateStatus" class="modal fade" tabindex="-1">
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
                    'route' => 'applicant.updateStatus',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'role' => 'form',
                ]) !!}
                {!! Form::hidden('id', null, ['id' => 'applicantId']) !!}
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Status :</label>
                    <div class="col-lg-9">
                        {!! Form::select('status', $statusList, null, ['id' => 'applicantStatus', 'class' => 'form-control']) !!}
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
<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.updateStatus').on('click', function() {
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            $('#applicantId').val(id);
            $('#applicantStatus').val(status);
        });
    });
</script>
@endSection
