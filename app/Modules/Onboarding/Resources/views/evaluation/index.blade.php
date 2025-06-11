@extends('admin::layout')
@section('title') Evaluations @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Evaluations</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('onboarding::evaluation.partial.advance_filter')

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

            <div class="mt-1 mr-2 bulkDropdown" style="display: none">
                <div class="d-flex">
                    <div class="">
                        <select name="" id="" class="form-control" required disabled>
                            <option value="">Choose Option</option>
                            <option value="offer_letter" selected>Bulk Send Offer Letter</option>
                        </select>
                    </div>
                    <div class="ml-2">
                        <button type="submit" class="btn bg-primary text-white mr-1"><i
                                class="icon-paperplane mr-1"></i>Send</button>
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
                        <th>#</th>
                        <th>S.N</th>
                        <th>Applicant Name</th>
                        <th>MRF Title</th>
                        <th>Total Score</th>
                        <th>Percentage</th>
                        <th>Created Date</th>
                        <th width="12%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($evaluationModels->total() != 0)
                        @foreach ($evaluationModels as $key => $evaluationModel)
                            <tr>
                                <td class="check">
                                    @if (!$evaluationModel->offerLetter)
                                        <input name="bulk[]" value="{{ $evaluationModel->id }}" type="checkbox"
                                            class="bulkCheck">
                                    @else
                                        <input type="checkbox" checked disabled>
                                    @endif
                                </td>
                                <td width="5%">#{{ $evaluationModels->firstItem() + $key }}
                                </td>
                                <td>{{ optional($evaluationModel->applicantModel)->getFullName() }}</td>
                                <td>{{ optional(optional($evaluationModel->applicantModel)->mrfModel)->title }}</td>
                                <td>{{ round($evaluationModel->total_score, 2) }}</td>
                                <td>
                                    @php
                                        $countQuestionModels = optional($evaluationModel->interviewLevelModel)->getQuestionModels->count();

                                        $filter['parent_id'] = $evaluationModel->id;
                                        $evaluationCountModels =  (new App\Modules\Onboarding\Repositories\EvaluationRepository)->findAll(100, $filter);
                                        $score ='';
                                        if(count($evaluationCountModels) > 0){
                                            $score = ($evaluationModel->total_score / count($evaluationCountModels) / ($countQuestionModels * 5)) * 5;
                                        }
                                    @endphp

                                    {{ round($score, 2) }} % out of 5
                                </td>

                                @php
                                    $createdDate = setting('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($evaluationModel->created_at))) : date('d M, Y', strtotime($evaluationModel->created_at));
                                @endphp
                                <td>{{ $createdDate }}</td>
                                <td class="text-center">
                                    <a href="{{ route('evaluation.report', $evaluationModel->id) }}"
                                        class="btn btn-sm btn-outline-secondary btn-icon mr-1" data-popup="tooltip"
                                        data-placement="top" data-original-title="View">
                                        <i class="icon-eye"></i>
                                    </a>
                                    <a href="{{ route('evaluation.subIndex', ['parent_id' => $evaluationModel->id]) }}"
                                        class="btn btn-sm btn-outline-info btn-icon mr-1" data-popup="tooltip"
                                        data-placement="top" data-original-title="List">
                                        <i class="icon-list-numbered"></i>
                                    </a>
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
<script>
    $(document).ready(function() {
        $('.updateStatus').on('click', function() {
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            $('#evaluationId').val(id);
            $('#evaluationStatus').val(status);
        });
    });

    $(document).on('change', '.bulkCheck', function() {
        let checkedCount = document.querySelectorAll('.check input[type="checkbox"]:checked').length;

        if (checkedCount > 0) {
            $('.bulkDropdown').css('display', 'block');
        }

        if (checkedCount <= 0) {
            $('.bulkDropdown').css('display', 'none');
        }
    })
</script>
@endSection
