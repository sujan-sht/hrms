@extends('admin::layout')
@section('title') Manpower Requisition Forms @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Manpower Requisition Forms</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('onboarding::mrf.partial.advance_filter', ['route' => 'mrf.index'])

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Manpower Requisition Forms (MRF)</h6>
            All the MRF Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1">
            <a href="{{ route('mrf.history') }}" class="btn btn-info rounded-pill mr-1">History</a>
            <a href="{{ route('mrf.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add</a>
        </div>
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="btn-slate text-light">
                    <th>S.N</th>
                    <th>Ref Number</th>
                    <th>Title</th>
                    <th>Organization</th>
                    <th>Sub-Function</th>
                    <th>Designation</th>
                    <th>Publish Date</th>
                    <th>Last Submission Date</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th width="12%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($mrfModels->total() != 0)
                    @foreach ($mrfModels as $key => $mrfModel)
                        <tr>
                            <td width="5%">#{{ $mrfModels->firstItem() + $key }}</td>
                            <td>{{ $mrfModel->reference_number }}</td>
                            <td>{{ $mrfModel->title }}</td>
                            <td>{{ optional($mrfModel->organizationModel)->name }}</td>
                            <td>{{ optional($mrfModel->getDepartment)->title }}</td>
                            <td>{{ optional($mrfModel->getDesignation)->title }}</td>

                            <td>
                                @if (setting('calendar_type') == 'BS')
                                    {{ date_converter()->eng_to_nep_convert($mrfModel['start_date']) ?? '-' }}
                                @else
                                    {{ date('M d Y', strtotime($mrfModel['start_date'])) ?? '-' }}
                                @endif
                            </td>

                            <td>
                                @if (setting('calendar_type') == 'BS')
                                    {{ date_converter()->eng_to_nep_convert($mrfModel['end_date']) ?? '-' }}
                                @else
                                    {{ date('M d Y', strtotime($mrfModel['end_date'])) ?? '-' }}
                                @endif
                            </td>

                            {{-- <td>{{ $mrfModel->start_date ? date('M d, Y', strtotime($mrfModel->start_date)) : '-' }}</td>
                                <td>{{ $mrfModel->end_date ? date('M d, Y', strtotime($mrfModel->end_date)) : '-' }}</td> --}}
                            <td>
                                <span
                                    class="badge badge-{{ $mrfModel->getStatusWithColor()['color'] }}">{{ $mrfModel->getStatusWithColor()['status'] }}</span>
                            </td>
                            <td>{{ optional(optional($mrfModel->createrUser)->userEmployer)->full_name }}</td>
                            <td class="text-center d-flex">
                                <a href="{{ route('mrf.view', $mrfModel->id) }}"
                                    class="btn btn-sm btn-outline-secondary btn-icon updateStatus mr-1"
                                    data-popup="tooltip" data-placement="top" data-original-title="View">
                                    <i class="icon-eye"></i>
                                </a>
                                @if (
                                    $menuRoles->assignedRoles('mrf.updateStatus') &&
                                        $mrfModel->status != '3' &&
                                        $mrfModel->status != '4' &&
                                        $mrfModel->status != '10')
                                    <a class="btn btn-sm btn-outline-warning btn-icon updateStatus mr-1"
                                        data-toggle="modal" data-target="#updateStatus" data-id="{{ $mrfModel->id }}"
                                        data-status="{{ $mrfModel->status }}" data-popup="tooltip" data-placement="top"
                                        data-original-title="Status">
                                        <i class="icon-flag3"></i>
                                    </a>
                                @endif

                                @if ($menuRoles->assignedRoles('mrf.closeMRF') && $mrfModel->status == '3')
                                    <a class="btn btn-sm btn-outline-danger btn-icon confirmCloseMRF"
                                        link="{{ route('mrf.closeMRF', $mrfModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Close MRF">
                                        <i class="icon-close2"></i>
                                    </a>
                                @endif
                                @if (
                                    $menuRoles->assignedRoles('mrf.edit') &&
                                        $mrfModel->status != '3' &&
                                        $mrfModel->status != '4' &&
                                        $mrfModel->status != '10')
                                    <a class="btn btn-sm btn-outline-primary btn-icon mr-1"
                                        href="{{ route('mrf.edit', $mrfModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                                @if (
                                    $menuRoles->assignedRoles('mrf.delete') &&
                                        $mrfModel->status != '3' &&
                                        $mrfModel->status != '4' &&
                                        $mrfModel->status != '10')
                                    <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete"
                                        link="{{ route('mrf.delete', $mrfModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11">No Record Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $mrfModels->appends(request()->all())->links() }}
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
                    'route' => 'mrf.updateStatus',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'role' => 'form',
                ]) !!}
                {!! Form::hidden('id', null, ['id' => 'mrfId']) !!}
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Status :</label>
                    <div class="col-lg-9">
                        @php unset($statusList[10]); @endphp
                        {!! Form::select('status', $statusList, null, [
                            'id' => 'mrfStatus',
                            'placeholder' => 'Select Status',
                            'class' => 'form-control select2',
                        ]) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Remark :</label>
                    <div class="col-lg-9">
                        {!! Form::textarea('remark', null, ['rows' => 3, 'placeholder' => 'Write remark..', 'class' => 'form-control']) !!}
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
            $('#mrfId').val(id);

            if (status == '8') {
                var options = '';
                options += "<option value=''>Select Status</option>";
                options += "<option value='3'>Publish</option>";
                options += "<option value='9'>Cancel</option>";
                $('#mrfStatus').html(options);
            }
            // $('#mrfStatus').val(status);
        });

        $('.confirmCloseMRF').on('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, close this MRF!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Closed!',
                        text: 'Your MRF has been closed.',
                        icon: 'success',
                        showCancelButton: false,
                        showConfirmButton: false,
                    });
                    window.location.href = $(this).attr('link');
                }
            });
        });
    });
</script>
@endSection
