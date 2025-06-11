@extends('admin::layout')
@section('title') Offer Letters @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Offer Letters</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


<!-- @include('onboarding::offer-letter.partial.advance_filter') -->

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Offer Letters</h6>
            All the Offer Letter Information will be listed below. You can Create and Modify the data.
        </div>
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Applicant Name</th>
                    <th>Functional Title</th>
                    <th>Join Date</th>
                    <th>Salary</th>
                    <th>Offer Expiry Date</th>
                    <th>Status</th>
                    <th width="12%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($offerLetterModels->total() != 0)
                    @foreach ($offerLetterModels as $key => $offerLetterModel)
                        <tr>
                            <td width="5%">#{{ $offerLetterModels->firstItem() + $key }}</td>
                            <td>{{ optional(optional($offerLetterModel->evaluationModel)->applicantModel)->getFullName() }}
                            </td>
                            <td>{{ optional(optional($offerLetterModel->evaluationModel)->interviewLevelModel)->title }}
                            </td>
                            @php
                                $joinDate = setting('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert($offerLetterModel->join_date) : date('M d, Y', strtotime($offerLetterModel->join_date));
                            @endphp
                            <td>{{ $offerLetterModel->join_date ? $joinDate : '-' }}
                            </td>
                            <td>Rs. {{ number_format($offerLetterModel->salary) }}</td>
                            @php
                                $expiryDate = setting('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert($offerLetterModel->expiry_date) : date('M d, Y', strtotime($offerLetterModel->expiry_date));
                            @endphp
                            <td>{{ $offerLetterModel->expiry_date ? $expiryDate : '-' }}
                            </td>
                            <td>
                                <span
                                    class="badge badge-{{ $offerLetterModel->getStatusWithColor()['color'] }}">{{ $offerLetterModel->getStatusWithColor()['status'] }}</span>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-outline-secondary btn-icon mr-1"
                                    href="{{ route('offerLetter.view', $offerLetterModel->id) }}" data-popup="tooltip"
                                    data-placement="top" data-original-title="View">
                                    <i class="icon-eye"></i>
                                </a>

                                @if ($menuRoles->assignedRoles('offerLetter.updateStatus'))
                                    <a class="btn btn-sm btn-outline-warning btn-icon updateStatus mr-1"
                                        data-toggle="modal" data-target="#updateStatus"
                                        data-id="{{ $offerLetterModel->id }}"
                                        data-status="{{ $offerLetterModel->status }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Status">
                                        <i class="icon-flag3"></i>
                                    </a>
                                @endif

                                @if ($offerLetterModel->status == '2')
                                    @php $applicantModel = optional($offerLetterModel->evaluationModel)->applicantModel; @endphp
                                    <a class="btn btn-sm btn-outline-success btn-icon mr-1"
                                        href="{{ route('employee.create') . '?applicant=' . $applicantModel->id }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Create Employee">
                                        <i class="icon-user-tie"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('offerLetter.edit'))
                                    <a class="btn btn-sm btn-outline-primary btn-icon mr-1"
                                        href="{{ route('offerLetter.edit', $offerLetterModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('offerLetter.delete'))
                                    <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete"
                                        link="{{ route('offerLetter.delete', $offerLetterModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
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
            {{ $offerLetterModels->appends(request()->all())->links() }}
        </span>
    </div>
</div>

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
                    'route' => 'offerLetter.updateStatus',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'role' => 'form',
                ]) !!}
                {!! Form::hidden('id', null, ['id' => 'offerLetterId']) !!}
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Status :</label>
                    <div class="col-lg-9">
                        {!! Form::select('status', $statusList, null, ['id' => 'offerLetterStatus', 'class' => 'form-control']) !!}
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
    $(function() {
        $('#updateStatus').on('show.bs.modal', function(e) {
            var id = $(e.relatedTarget).data('id');
            status = $(e.relatedTarget).data('status');
            $('#offerLetterId').val(id);
            $('#offerLetterStatus option[value=' + status + ']').prop('selected', true);

        })
    })
</script>
@endSection
