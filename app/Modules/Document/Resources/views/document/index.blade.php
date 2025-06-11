@extends('admin::layout')
@section('title') Document @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Documents</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('document::document.partial.advance-filter', ['route' => route('document.index')])

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Documents</h6>
            All the Documents Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1">
            <a href="{{ route('document.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
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
                    <th>Title</th>
                    <th>Document Type</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th width="12%">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($documentModels->total() != 0)
                    @foreach ($documentModels as $key => $documentModel)
                        <tr>
                            <td width="5%">#{{ $documentModels->firstItem() + $key }}</td>

                            <td>{{ $documentModel->title }}</td>
                            <td>{{ ucfirst($documentModel->type) }}</td>
                            <td>{{ Str::limit($documentModel->description, 50) }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $documentModel->getStatusWithColor()['color'] }}">{{ $documentModel->getStatusWithColor()['status'] }}
                                </span>
                            </td>
                            <td>{{ $documentModel->created_at ? date('M d, Y', strtotime($documentModel->created_at)) : '-' }}
                            </td>

                            <td class="d-flex">
                                <a class="btn btn-outline-secondary btn-icon mx-1"
                                    href="{{ route('document.show', $documentModel->id) }}" data-popup="tooltip"
                                    data-placement="top" data-original-title="View">
                                    <i class="icon-eye"></i>
                                </a>
                                @if (auth()->user()->user_type == 'employee' || auth()->user()->user_type == 'supervisor')
                                    @if ($menuRoles->assignedRoles('document.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('document.edit', $documentModel->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif
                                @endif

                                {{-- @if ($emp_id == $documentModel->employee_id)
                                    <!-- do nothing -->
                                @elseif (auth()->user()->user_type == 'super_admin' || ($menuRoles->assignedRoles('document.updateStatus') && in_array($documentModel->status, [1,2])))

                                    <a data-toggle="modal" data-target="#updateStatus" class="btn btn-outline-warning btn-icon updateStatus mx-1"
                                            data-id="{{ $documentModel->id }}" data-status="{{ $documentModel->status }}" emp_status="{{ $emp_status }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Status">
                                        <i class="icon-flag3"></i>
                                    </a>
                                @endif --}}

                                {{-- @if (auth()->user()->user_type == 'super_admin' || ($menuRoles->assignedRoles('document.delete') && in_array($documentModel->status, [1, 2]))) --}}
                                @if ($menuRoles->assignedRoles('document.delete'))
                                    <a class="btn btn-outline-danger btn-icon confirmDelete mx-1"
                                        link="{{ route('document.delete', $documentModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif

                                {{-- @endif --}}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Document Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $documentModels->appends(request()->all())->links() }}
        </span>
    </div>
</div>

<!-- popup modal -->
{{-- <div id="updateStatus" class="modal fade" tabindex="-1">
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
                    'route' => 'document.updateStatus',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'role' => 'form',
                ]) !!}
                {!! Form::hidden('id', null, ['id' => 'leaveId']) !!}
                <div class="form-group">
                    <div class="row">
                        <label class="col-form-label col-lg-3">Status :</label>
                        <div class="col-lg-9">
                            {!! Form::select('status', $statusList, null, ['id' => 'leaveStatus', 'class' => 'form-control select2']) !!}
                        </div>
                    </div>
                    <div id="statusMessage" class="row mt-3" style="display:none;">
                        <label class="col-form-label col-lg-3">Message :</label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('status_message', null, ['rows' => 3, 'placeholder' => 'Write message..', 'class' => 'form-control']) !!}
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
</div> --}}

@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
<script>
    $(document).ready(function() {
        // initiate select2
        $('.select2').select2();

        // $('#leaveStatus').on('change', function() {
        //     var status = $(this).val();
        //     if(status == '2' || status == '4') {
        //         $('#statusMessage').show();
        //     } else {
        //         $('#statusMessage').hide();
        //     }
        // });

        // $('.updateStatus').on('click', function() {
        //     var id = $(this).attr('data-id');
        //     var status = $(this).attr('data-status');
        //     var emp_status = $(this).attr('emp_status');

        //     let option_html;
        //     if (emp_status == 1 || emp_status == 6) {
        //         option_html = '<option value="1">Pending</option>';
        //     } else if (emp_status == 5) {
        //         option_html = '<option value="1">Pending</option><option value="4">Rejected</option><option value="2">Forwarded</option>';
        //     } else if (emp_status == 7) {
        //         option_html = '<option value="2">Forwarded</option><option value="4">Rejected</option><option value="3">Accepted</option>';
        //     } else if (emp_status == 2) {
        //         option_html = '<option value="2">Forwarded</option>';
        //     } else if (emp_status == 3) {
        //         option_html = '';
        //     } else if (emp_status == 4) {
        //         option_html = '<option value="4">Rejected</option>';
        //     } else if (emp_status == 9) {
        //         option_html = '<option value="3">Accepted</option><option value="4">Rejected</option>';
        //     }

        //     $('#leaveId').val(id);
        //     $('#leaveStatus').val(status);
        //     $('#leaveStatus').html(option_html);
        //     $('#statusMessage').hide();
        // });
    });
</script>
@endSection
