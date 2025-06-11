@extends('admin::layout')
@section('title') Survey Question @endSection
@section('breadcrum')
<a href="{{ route('survey.index') }}" class="breadcrumb-item">Surveys</a>
<a class="breadcrumb-item active">Survey Questions</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('survey::survey-question.partial.advance-filter', [
    'route' => route('surveyQuestion.index', $survey_id),
])

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Survey Questions</h6>
            All the Survey Questions Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1">
            <a href="{{ route('surveyQuestion.create', $survey_id) }}" class="btn btn-success rounded-pill"><i
                    class="icon-plus2"></i> Add</a>
        </div>
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Survey</th>
                    <th>Question</th>
                    <th>Question Type</th>
                    <th>Created Date</th>
                    <th>Created By</th>
                    <th width="12%">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($surveyQuestionModels->total() != 0)
                    @foreach ($surveyQuestionModels as $key => $surveyQuestionModel)
                        <tr>
                            <td width="5%">#{{ ++$key }}</td>
                            <td>{{ optional($surveyQuestionModel->survey)->title }}</td>
                            <td>{{ $surveyQuestionModel->question }}</td>
                            <td>{{ $surveyQuestionModel->getQuestionType() }}</td>

                            <td>{{ getStandardDateFormat($surveyQuestionModel->created_at) }}</td>
                            <td>{{ optional(optional($surveyQuestionModel->user)->userEmployer)->full_name }}</td>
                            <td class="d-flex">
                                @if ($menuRoles->assignedRoles('surveyQuestion.edit'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('surveyQuestion.edit', ['survey_id' => $survey_id, 'id' => $surveyQuestionModel->id]) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif

                                @if ($menuRoles->assignedRoles('surveyQuestion.delete'))
                                    <a class="btn btn-outline-danger btn-icon confirmDelete mx-1"
                                        link="{{ route('surveyQuestion.delete', $surveyQuestionModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Survey Question Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $surveyQuestionModels->appends(request()->all())->links() }}
        </span>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
@endSection
