@extends('admin::layout')
@section('title') Interviews @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Interviews</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('onboarding::interview.partial.advance_filter')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Interviews</h6>
            All the Interview Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1">
            <a href="{{ route('interview.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
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
                    <th>Applicant</th>
                    <th>Level of Interview</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Venue</th>
                    <th>Status</th>
                    <th width="12%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($interviewModels->total() != 0)
                    @foreach ($interviewModels as $key => $interviewModel)
                        <tr>
                            <td width="5%">#{{ $interviewModels->firstItem() + $key }}</td>
                            <td>{{ optional($interviewModel->applicantModel)->getFullName() }}</td>
                            <td>{{ optional($interviewModel->interviewLevelModel)->title }}</td>

                            @php
                                $date1 =
                                    setting('calendar_type') == 'BS'
                                        ? date_converter()->eng_to_nep_convert($interviewModel->date)
                                        : date('M d, Y', strtotime($interviewModel->date));
                            @endphp
                            <td>{{ $interviewModel->date ? $date1 : '-' }}</td>
                            <td>{{ $interviewModel->time ? date('h:i A', strtotime(date('Y-m-d') . ' ' . $interviewModel->time)) : '-' }}
                            </td>
                            <td>{{ $interviewModel->venue }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $interviewModel->getStatusWithColor()['color'] }}">{{ $interviewModel->getStatusWithColor()['status'] }}</span>
                            </td>
                            <td class="text-center">
                                @if ($menuRoles->assignedRoles('interview.updateStatus'))
                                    <a class="btn btn-sm btn-outline-warning btn-icon updateStatus mr-1"
                                        data-toggle="modal" data-target="#updateStatus"
                                        data-id="{{ $interviewModel->id }}" data-status="{{ $interviewModel->status }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Status">
                                        <i class="icon-flag3"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('evaluation.create') && $interviewModel->status == '1')
                                    <a href="{{ route('evaluation.create') . '?interview=' . $interviewModel->id }}"
                                        class="btn btn-sm btn-outline-success btn-icon mr-1" data-popup="tooltip"
                                        data-placement="top" data-original-title="Evaluate">
                                        <i class="icon-pen2"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('interview.edit'))
                                    <a class="btn btn-sm btn-outline-primary btn-icon mr-1"
                                        href="{{ route('interview.edit', $interviewModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('interview.delete'))
                                    <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete"
                                        link="{{ route('interview.delete', $interviewModel->id) }}"
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
            {{ $interviewModels->appends(request()->all())->links() }}
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
                    'route' => 'interview.updateStatus',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'role' => 'form',
                ]) !!}
                {!! Form::hidden('id', null, ['id' => 'interviewId']) !!}
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Status :</label>
                    <div class="col-lg-9">
                        {!! Form::select('status', $statusList, null, ['id' => 'interviewStatus', 'class' => 'form-control']) !!}
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
            $('#interviewId').val(id);
            $('#interviewStatus').val(status);
        });
    });
</script>
@endSection
