@extends('admin::layout')
@section('title') Appraisal @stop

@section('breadcrum')
    <a href="{{ route('score.index') }}" class="breadcrumb-item">Appraisal</a>
    <a class="breadcrumb-item active">Appraisal List</a>
@endsection

@section('content')
    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    {{-- @include('appraisal::appraisal-management.partial.filter') --}}

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Appraisals</h6>
                All the Appraisals Information will be listed below. You can Create and Modify the data.
            </div>
            {{-- <div class="mt-1">
                <a href="{{ route('appraisal.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add</a>
            </div> --}}
        </div>
    </div>


    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th width="5%">S.N</th>
                    <th width = "70%">Hr Comment</th>
                    <th width = "5%">Average Score</th>
                    <th width = "10%">Valid Till</th>
                    <th width = "10%">Created Date</th>
                    {{-- @if ($menuRoles->assignedRoles('appraisal.edit'))
                        <th width="12%">Action</th>
                    @endif --}}
                </tr>
            </thead>
            <tbody>
                @if ($appraisalDatas->count() != 0)
                    @foreach ($appraisalDatas as $key => $appraisalData)
                        <tr>
                            <td width="5%">#{{ ++$key }}</td>
                            <td width = "70%">{{ $appraisalData->reviewer_comment }}</td>
                            <td width = "5%">{{ $appraisalData->average_score }}</td>
                            <td width = "10%">{{ optional($appraisalData->appraisal)->valid_date }}</td>
                            <td width = "10%">
                                {{ optional($appraisalData->appraisal)->created_at ? optional($appraisalData->appraisal)->created_at->format('Y-M-d') : '' }}
                            </td>

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
    {{-- <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $appraisals->appends(request()->all())->links() }}
        </span>
    </div> --}}
    </div>

@endsection
