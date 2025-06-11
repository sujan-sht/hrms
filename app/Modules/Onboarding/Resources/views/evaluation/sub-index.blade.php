@extends('admin::layout')
@section('title') Evaluations @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Evaluations</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

<form action="{{ route('evaluation.bulkOfferLetter') }}" method="GET">@csrf

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Evaluations</h6>
                All the Evaluation Information will be listed below. You can Create and Modify the data.
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
                        <th>MRF Title</th>
                        <th>Total Score</th>
                        <th>Percentage</th>
                        <th>Interviewer</th>
                        <th>Created Date</th>
                        <th width="12%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($evaluationModels->total() != 0)
                        @foreach ($evaluationModels as $key => $evaluationModel)
                            <tr>
                                <td width="5%">#{{ $evaluationModels->firstItem() + $key }}
                                </td>
                                <td>{{ optional($evaluationModel->applicantModel)->getFullName() }}</td>
                                <td>{{ optional(optional($evaluationModel->applicantModel)->mrfModel)->title }}</td>
                                <td>{{ round($evaluationModel->total_score, 2) }}</td>
                                <td>{{ round($evaluationModel->percentage, 2) }} %</td>
                                <td>{{ optional($evaluationModel->employeeModel)->full_name }}</td>
                                <td>{{ date('d M, Y', strtotime($evaluationModel->created_at)) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('evaluation.view', $evaluationModel->id) }}"
                                        class="btn btn-sm btn-outline-secondary btn-icon mr-1" data-popup="tooltip"
                                        data-placement="top" data-original-title="View">
                                        <i class="icon-eye"></i>
                                    </a>
                                    {{-- @if ($menuRoles->assignedRoles('evaluation.edit'))
                                        <a class="btn btn-sm btn-outline-primary btn-icon mr-1"
                                            href="{{ route('evaluation.edit', $evaluationModel->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif --}}
                                    @if ($menuRoles->assignedRoles('evaluation.delete'))
                                        <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete"
                                            link="{{ route('evaluation.delete', $evaluationModel->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">No Record Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
</form>
<div class="col-12">
    <span class="float-right pagination align-self-end mt-3">
        {{ $evaluationModels->appends(request()->all())->links() }}
    </span>
</div>
</div>

@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
@endSection
