@extends('admin::layout')
@section('title') Appraisal Respondents Detail @stop

@section('breadcrum')
    <a href="{{ route('appraisal.index') }}" class="breadcrumb-item">Appraisal</a>
    <a class="breadcrumb-item active">Response</a>
@endsection

@section('content')
    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">Responses</h6>
                <p>All the Question along with the scores for specifi question is listed below.</p>
            </div>
            {{-- <div class="mt-1">
                <a href="{{ route('appraisal.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add</a>
            </div> --}}
        </div>
    </div>

    <!-- Checkboxes -->
    <div class="card">
        {{-- <div class="card-header header-elements-inline">
                <h5 class="card-title">Questions</h5>
            </div> --}}
        @php
            $authUser = auth()->user();
            $responseUser = $respondent->responses[0]->created_by;
        @endphp

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="text-white">
                        <th>S.N</th>
                        <th>Details</th>
                        @if (optional($respondent->appraisal)->self_evaluation_type == 1)
                            <th>Rating</th>
                        @elseif (optional($respondent->appraisal)->self_evaluation_type == 2)
                            <th>Comment</th>
                        @else
                            <th>Rating</th>
                            <th>Comment</th>
                        @endif
                    </thead>
                    <tbody>
                        @foreach ($respondent->responses as $key => $response)
                            <tr>
                                <td>#{{ ++$key }}</td>
                                <td>
                                    <div class="font-weight-semibold">
                                        {{ optional($response->competenceQuestion)->question }}</div>
                                </td>
                                @if (optional($respondent->appraisal)->self_evaluation_type == 1)
                                    <td>{{ $response->score }}</td>
                                @else
                                    @if (optional($respondent->appraisal)->self_evaluation_type == 2)
                                        <td>{{ $response->comment }}</td>
                                    @else
                                        <td>{{ $response->score }}</td>
                                        <td>{{ $response->comment }}</td>
                                    @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </div>

@endsection
