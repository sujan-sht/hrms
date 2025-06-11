@extends('admin::layout')
@section('title') Poll @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Polls</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@inject('orgModel', '\App\Modules\Organization\Entities\Organization')


@section('content')


<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Polls</h6>
            All the Polls Information will be listed below.
        </div>
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Poll</th>
                    <th>Organization</th>
                </tr>
            </thead>
            <tbody>
                @if ($pollModels->total() != 0)
                @foreach ($pollModels as $key => $pollModel)
                    <tr>
                        <td width="5%">#{{ ++$key }}</td>
                        <td>{{ $pollModel->question }}</td>
                        <td>
                            @php
                                $pollParticipantOrganizations = $pollModel->pollParticipants->groupBy('organization_id');
                            @endphp
                            @foreach ($pollParticipantOrganizations as $org_id =>$pollParticipants)
                                @php
                                    $organization = $orgModel->find($org_id);
                                @endphp
                                <ul>
                                    <li>{{ $organization->name }}</li>
                                    <ul>

                                        @foreach ($pollParticipants as $pollParticipant)
                                            @if (optional($pollParticipant->department)->title)
                                                <li>{{ optional($pollParticipant->department)->title . ' Department'}}</li>
                                            @endif
                                            @if (optional($pollParticipant->level)->title)
                                                <li>{{ optional($pollParticipant->level)->title . ' Level'}}</li>
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
                    <td colspan="7">No Survey Poll Found !!!</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $pollModels->appends(request()->all())->links() }}
        </span>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@endSection
