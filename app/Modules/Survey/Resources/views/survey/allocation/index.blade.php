@extends('admin::layout')
@section('title') Allocation @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Allocations</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@inject('orgModel', '\App\Modules\Organization\Entities\Organization')


@section('content')


{{-- @include('survey::survey.partial.advance-filter', ['route' => route('survey.index')]) --}}

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Allocations</h6>
            All the Allocations Information will be listed below.
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
                    <th>Organization</th>
                </tr>
            </thead>
            <tbody>
                @if ($surveyModels->total() != 0)
                @foreach ($surveyModels as $key => $surveyModel)
                    <tr>
                        <td width="5%">#{{ ++$key }}</td>
                        <td>{{ $surveyModel->title }}</td>
                        <td>
                            @php
                                $surveyParticipantOrganizations = $surveyModel->surveyParticipants->groupBy('organization_id');
                            @endphp
                            @foreach ($surveyParticipantOrganizations as $org_id =>$surveyParticipants)
                                @php
                                    $organization = $orgModel->find($org_id);
                                @endphp
                                <ul>
                                    <li>{{ $organization->name }}</li>
                                    <ul>

                                        @foreach ($surveyParticipants as $surveyParticipant)
                                            @if (optional($surveyParticipant->department)->title)
                                                <li>{{ optional($surveyParticipant->department)->title . ' Department'}}</li>
                                            @endif
                                            @if (optional($surveyParticipant->level)->title)
                                                <li>{{ optional($surveyParticipant->level)->title . ' Level'}}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </ul>
                            @endforeach
                        </td>

                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7">No Survey Allocation Found !!!</td>
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
