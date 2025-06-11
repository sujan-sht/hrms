@extends('admin::layout')
@section('title') Survey @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Surveys</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('survey::survey.partial.advance-filter', ['route' => route('survey.index')])

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Surveys</h6>
            All the Surveys Information will be listed below. You can Create and Modify the data.
        </div>
        @if ($menuRoles->assignedRoles('survey.create'))
            <div class="mt-1">
                <a href="{{ route('survey.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
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
                    <th>Title</th>
                    <th>Number of Questions</th>
                    <th>Description</th>
                    <th>Created Date</th>
                    <th>Created By</th>
                    <th width="12%">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($surveyModels->total() != 0)
                    @foreach ($surveyModels as $key => $surveyModel)
                        {{-- {{dd($surveyModel)}} --}}
                        <tr>
                            <td width="5%">#{{ ++$key }}</td>
                            <td>{{ $surveyModel->title }}</td>
                            <td>{{ $surveyModel->surveyQuestions()->count() }}</td>
                            <td>{{ Str::limit($surveyModel->description, 50) }}</td>
                            <td>{{ getStandardDateFormat($surveyModel->created_at) }}</td>
                            <td>{{ optional(optional($surveyModel->user)->userEmployer)->full_name }}</td>

                            <td class="d-flex">
                                @if (
                                    (auth()->user()->user_type == 'division_hr' && auth()->user()->id == $surveyModel->created_by) ||
                                        in_array(auth()->user()->user_type, ['hr', 'admin', 'super_admin']))
                                    @if ($menuRoles->assignedRoles('surveyQuestion.index'))
                                        <a class="btn btn-outline-warning btn-icon mx-1"
                                            href="{{ route('surveyQuestion.index', $surveyModel->id) }}"
                                            data-popup="tooltip" data-placement="top"
                                            data-original-title="Survey Questions">
                                            <i class="icon-question4"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('survey.allocateForm'))
                                        <a class="btn btn-outline-info btn-icon mx-1"
                                            href="{{ route('survey.allocateForm', $surveyModel->id) }}"
                                            data-popup="tooltip" data-placement="top"
                                            data-original-title="Allocate Survey">
                                            <i class="icon-task"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('survey.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('survey.edit', $surveyModel->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('survey.delete'))
                                        <a class="btn btn-outline-danger btn-icon confirmDelete mx-1"
                                            link="{{ route('survey.delete', $surveyModel->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('survey.viewReport'))
                                        <a class="btn btn-outline-secondary btn-icon mx-1"
                                            href="{{ route('survey.viewReport', $surveyModel->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="View Report">
                                            <i class="icon-eye"></i>
                                        </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Survey Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $surveyModels->appends(request()->all())->links() }}
        </span>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@endSection
