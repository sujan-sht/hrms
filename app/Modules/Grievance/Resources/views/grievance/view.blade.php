@extends('admin::layout')
@section('title')
    View Grievance
@endsection
@section('breadcrum')
    <a href="{{ route('grievance.index') }}" class="breadcrumb-item">Grievance</a>
    <a class="breadcrumb-item active">View</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">

                    <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                    <ul class="media-list">
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Anonmyous:</span>
                            <div class="ml-2">{{ $grievance->is_anonymous == 11 ? 'Yes' : 'No' }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Subject Type :</span>
                            <div class="ml-2">{{ $grievance->getSubjectType() }}</div>
                        </li>

                        @if ($grievance->subject_type == 1)
                            <legend class="text-uppercase font-size-sm font-weight-bold">Grievance Detail</legend>

                            <li class="media mt-2">
                                <span class="font-weight-semibold">Subject related to grievances:</span>
                                <div class="ml-2">{{ $grievance->getSingleGrievanceMeta('related_grievances') }}
                                </div>
                            </li>

                            <li class="media mt-2">
                                <span class="font-weight-semibold">Grievances Details:</span>
                                <div class="ml-2">{{ $grievance->getSingleGrievanceMeta('detail') }}</div>
                            </li>
                        @elseif($grievance->subject_type == 2)
                            <legend class="text-uppercase font-size-sm font-weight-bold">Disciplinary Detail</legend>

                            <li class="media mt-2">
                                <span class="font-weight-semibold">Employee involved in Misconduct :</span>
                                <div class="ml-2">{{ $grievance->getSingleGrievanceMeta('emp_name') }}
                                </div>
                            </li>

                            <li class="media mt-2">
                                <span class="font-weight-semibold">Sub-Function :</span>
                                <div class="ml-2">{{ $grievance->getSingleGrievanceMeta('dept') }}</div>
                            </li>
                            {{-- <li class="media mt-2">
                                <span class="font-weight-semibold">Type of Misconduct :</span>
                                <div class="ml-2">
                                    {{ $grievance->getSingleGrievanceMeta('misconduct_type') }}
                                </div>
                            </li> --}}
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Date of Misconduct :</span>
                                <div class="ml-2">{{ $grievance->getSingleGrievanceMeta('date') }}
                                </div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Time of Misconduct :</span>
                                <div class="ml-2">
                                    {{ date('h:i A', strtotime($grievance->getSingleGrievanceMeta('time'))) }}
                                </div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Location of Misconduct :</span>
                                <div class="ml-2">{{ $grievance->getSingleGrievanceMeta('location') }}
                                </div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Witness Name :</span>
                                <div class="ml-2">{{ $grievance->getSingleGrievanceMeta('witness_name') }}
                                </div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Details of Misconduct:</span>
                                <div class="ml-2">{{ $grievance->getSingleGrievanceMeta('detail') }}</div>
                            </li>
                        @elseif($grievance->subject_type == 3)
                            <legend class="text-uppercase font-size-sm font-weight-bold">Suggestion Detail</legend>

                            <li class="media mt-2">
                                <span class="font-weight-semibold">Suggestion Details:</span>
                                <div class="ml-2">{{ $grievance->getSingleGrievanceMeta('detail') }}
                                </div>
                            </li>
                        @elseif($grievance->subject_type == 4)
                            <legend class="text-uppercase font-size-sm font-weight-bold">Other Detail</legend>

                            <li class="media mt-2">
                                <span class="font-weight-semibold">Other Details:</span>
                                <div class="ml-2">{{ $grievance->getSingleGrievanceMeta('detail') }}
                                </div>
                            </li>
                        @endif

                        @if ($grievance->attachment)
                            <legend class="text-uppercase font-size-sm font-weight-bold">Attachment</legend>

                            <ul class="media-list">
                                <li class="media">
                                    <div class="mr-3">
                                        {{-- <img src="{{ asset('uploads/notice/' . $notice->file) }}" alt="" width="150"
                                        height="100"> --}}
                                        <i class="icon-file-text me-3"></i>
                                    </div>
                                    <div class="media-body">
                                        <a href="{{ asset('uploads/grievance/' . $grievance->attachment) }}"
                                            target="_blank">
                                            {{ $grievance->attachment }}
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        @if ($grievance->grievanceEmployee()->exists())
            {{-- @dd($grievance->grievanceEmployee) --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Employee Detail</legend>
                        <ul class="media-list">
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Full Name :</span>
                                <div class="ml-2">
                                    {{ optional(optional($grievance->grievanceEmployee)->employee)->full_name }}</div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Designation :</span>
                                <div class="ml-2">
                                    {{ optional(optional($grievance->grievanceEmployee)->designation)->title }}
                                </div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Sub-Function :</span>
                                <div class="ml-2">
                                    {{ optional(optional($grievance->grievanceEmployee)->department)->title }}
                                </div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Division :</span>
                                <div class="ml-2">
                                    {{ optional(optional($grievance->grievanceEmployee)->division)->name }}
                                </div>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // initiate select2
            $('.select-search').select2();

        });
    </script>
@endsection
