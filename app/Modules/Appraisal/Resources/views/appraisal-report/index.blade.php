@extends('admin::layout')
@section('title') Appraisal Report @stop

@section('breadcrum')
    <a href="{{ route('appraisal.index') }}" class="breadcrumb-item">Report</a>
    <a class="breadcrumb-item active">Appraisal Report</a>
@endsection

@section('content')
    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    @if (auth()->user()->user_type != 'employee')
        @include('appraisal::appraisal-report.action.filter')
    @endif
    {{-- <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Appraisals</h6>
            </div>
            <div class="mt-1">
                <a href="{{ route('appraisal.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add</a>
            </div>
        </div>
    </div> --}}

    @if (isset($selected_employee))
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Appraisee</th>
                        <th>Total Respondents</th>
                        <th>Average Score</th>
                        <th>Score Percentage</th>
                        <th>Created Date</th>
                        @if ($menuRoles->assignedRoles('appraisal.edit'))
                            <th width="12%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($appraisals->count() != 0)
                        @foreach ($appraisals as $key => $appraisal)
                            <tr>
                                <td width="5%">#{{ ++$key }}</td>
                                <td>{{ optional($appraisal->employee)->full_name }}</td>
                                <td>
                                    {{ count($appraisal->respondents) }}
                                </td>
                                <td>
                                    {{ $appraisal->appraisalResponses->avg('score') }}
                                </td>

                                <td>
                                    @if (count($appraisal->respondents) != 0)
                                        {{ ($appraisal->appraisalResponses->avg('score') * 100) / 5 }}%
                                    @else
                                        0%
                                    @endif
                                </td>

                                <td>
                                    {{ $appraisal->created_at->format('Y-M-d') }}
                                </td>

                                @if ($menuRoles->assignedRoles('appraisal.edit'))
                                    <td>
                                        @if ($menuRoles->assignedRoles('appraisal.show'))
                                            <a href="#" class="btn btn-outline-success btn-icon mx-1"
                                                data-popup="tooltip" data-placement="top" data-original-title="Download">
                                                <i class="icon-download"></i>
                                            </a>
                                        @endif

                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Appraisals Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $appraisals->appends(request()->all())->links() }}
            </span>
        </div>
    @endif
    </div>

@endsection
