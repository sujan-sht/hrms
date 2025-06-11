@extends('admin::layout')
@section('title') Appraisal Respondents Detail @stop

@section('breadcrum')
    <a href="{{ route('appraisal.index') }}" class="breadcrumb-item">Appraisal</a>
    <a class="breadcrumb-item active">Respondents Detail</a>
@endsection

@section('content')
    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Respondents</h6>
                <p>All the respondents invited to Respond in appraisal is shown below.</p>
            </div>
            {{-- <div class="mt-1">
                <a data-toggle="modal" data-target="#addRespondents" class="btn btn-success rounded-pill">Add More
                    Respondent</a>
            </div> --}}
        </div>
    </div>


    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Respondent Name</th>
                    <th>Respondent Email</th>
                    <th>Respond Status</th>
                    <th>Average Rating</th>
                    @if ($menuRoles->assignedRoles('appraisal.edit'))
                        <th width="12%">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if ($appraisal->respondents->count() != 0)
                    @foreach ($appraisal->respondents as $key => $respondent)
                        <tr>
                            <td width="5%">#{{ ++$key }}</td>
                            <td>{{ ucfirst($respondent->name) }}</td>
                            <td>
                                {{ $respondent->email }}
                            </td>
                            <td>
                                @if ($respondent->already_responded)
                                    <div class="badge badge-success">Responded</div>
                                @else
                                    <div class="badge badge-danger">Not Responded</div>
                                @endif
                            </td>
                            <td>
                                {{ round(optional($respondent->responses)->avg('score') ?? 0, 2) }}
                            </td>
                            @if ($respondent->already_responded)
                                @if ($menuRoles->assignedRoles('appraisal-respondent.view'))
                                    <td>

                                        @if ($menuRoles->assignedRoles('appraisal.show'))
                                            <a class="btn btn-outline-secondary btn-icon mx-1"
                                                href="{{ route('appraisal-respondent.view', $respondent->id) }}"
                                                data-popup="tooltip" data-placement="top" data-original-title="View Detail">
                                                <i class="icon-eye"></i>
                                            </a>
                                        @endif

                                        @if ($menuRoles->assignedRoles('appraisal.show'))
                                            <a class="btn btn-outline-success btn-icon mx-1"
                                                href="{{ route('appraisal-respondent.print', $respondent->id) }}"
                                                target="_blank"
                                                data-popup="tooltip" data-placement="top" data-original-title="Print">
                                                <i class="icon-printer"></i>
                                            </a>
                                        @endif

                                    </td>
                                @endif
                            @else
                                <td>
                                    @if ($menuRoles->assignedRoles('appraisal-respondent.resendEmail'))
                                        <a href="{{ route('appraisal-respondent.resendEmail') . '?id=' . $respondent->id }}"
                                            class="btn btn-outline-warning btn-icon mx-1" aria-disabled="true"
                                            data-popup="tooltip" data-placement="top" data-original-title="Resend">
                                            <i class="icon-paperplane"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('appraisal.show'))
                                        <a class="btn btn-outline-secondary btn-icon mx-1" aria-disabled="true"
                                            data-popup="tooltip" data-placement="top">
                                            <i class="icon-eye-blocked"></i>
                                        </a>
                                    @endif

                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Respondents Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    {{-- <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $appraisal->respondents->appends(request()->all())->links() }}
        </span>
    </div> --}}
    </div>

    <!-- view modal -->
    <div id="addRespondents" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h6 class="modal-title">Add More Respondents</h6>
                </div>
                <form action="{{ route('appraisal-respondent.addRespondent', $appraisal->id) }}" method="POST"> @csrf
                    <div class="modal-body">

                        @if ($appraisal->type == 'internal')
                        <input type="hidden" name="type" value="internal">
                            <div class="form-group">
                                {!! Form::select('respondent_id', $employees, null, [
                                    'placeholder' => 'Choose Employees',
                                    'class' => 'form-control',
                                    'required',
                                ]) !!}
                            </div>
                        @else
                        <input type="hidden" name="type" value="external">
                            {!! Form::text('name', null, ['class' => 'form-control mb-3', 'placeholder' => 'Enter Name']) !!}

                            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email Address']) !!}
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                                    class="icon-database-insert"></i></b>Submit</button>
                        <button type="button" class="btn bg-teal-400" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- view modal -->

@endsection
