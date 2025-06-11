@extends('admin::layout')
@section('title') Appraisal @stop

@section('breadcrum')
    <a href="{{ route('score.index') }}" class="breadcrumb-item">Appraisal</a>
    <a class="breadcrumb-item active">Appraisal List</a>
@endsection

@section('content')
    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    @include('appraisal::appraisal-management.partial.filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Appraisals</h6>
                All the Appraisals Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('appraisal.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                    Add</a>
            </div>
        </div>
    </div>


    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Appraisee</th>
                    <th>Questionnaire</th>
                    <th>Type</th>
                    <th>Total Respondents</th>
                    <th>Valid Till</th>
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
                                {{ optional($appraisal->questionnaire)->title }}
                            </td>
                            <td>
                                {{ ucfirst($appraisal->type) }}
                            </td>
                            <td>
                                {{ optional($appraisal->respondents)->count() ?? 0 }}
                            </td>

                            @if (setting('calendar_type') == 'BS')
                                <td>
                                    @if (!is_null($appraisal->valid_date))
                                        {{ date_converter()->eng_to_nep_convert($appraisal->valid_date) }}
                                    @endif
                                </td>
                            @else
                                <td>{{ $appraisal->valid_date }}</td>
                            @endif
                            <td>{{ $appraisal->created_at->format('Y-M-d') }}</td>

                            @if ($menuRoles->assignedRoles('appraisal.edit'))
                                <td>
                                    {{-- @if ($menuRoles->assignedRoles('appraisal.show'))
                                        <a href="{{route('appraisal-respondent.index',$appraisal->id)}}" class="btn btn-outline-secondary btn-icon mr-1"  data-popup="tooltip" data-placement="top" data-original-title="View Detail">
                                            <i class="icon-eye"></i>
                                        </a>
                                    @endif --}}
                                    @if ($menuRoles->assignedRoles('appraisal.report'))
                                        <a href="{{ route('appraisal.report', $appraisal->id) }}"
                                            class="btn btn-outline-primary btn-icon mr-1" data-popup="tooltip"
                                            data-placement="top" data-original-title="View Report">
                                            <i class="icon-file-text"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('appraisal.downloadReport'))
                                        <a class="btn btn-outline-warning btn-icon mx-1"
                                            href="{{ route('appraisal.downloadReport', $appraisal->id) }}"
                                            data-popup="tooltip" data-placement="bottom"
                                            data-original-title="Download PDF"><i class="icon-download"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('appraisal.delete'))
                                        <a class="btn btn-outline-danger btn-icon confirmDelete"
                                            link="{{ route('appraisal.delete', $appraisal->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
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
    </div>

@endsection
