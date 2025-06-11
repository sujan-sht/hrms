@php
    $current_join__date = '';
    if (
        optional($employeeModel->getUser)->user_type != 'super_admin' &&
        optional($employeeModel->getUser)->user_type != 'admin'
    ) {
        $current_join__date = App\Helpers\DateTimeHelper::DateDiffInYearMonthDay(
            $employeeModel->join_date,
            date('Y-m-d'),
        );
    }
@endphp

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <ul class="media-list">
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Organization :</span>
                        <div class="ml-auto">{{ optional($employeeModel->organizationModel)->name }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Employee Code :</span>
                        <div class="ml-auto">{{ $employeeModel->employee_code }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">National ID :</span>
                        <div class="ml-auto">{{ $employeeModel->national_id ?? null }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Passport Number :</span>
                        <div class="ml-auto">{{ $employeeModel->passport_no ?? null }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Full Name :</span>
                        <div class="ml-auto">{{ $employeeModel->getFullname() }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Phone (CUG):</span>
                        <div class="ml-auto">{{ $employeeModel->phone }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Mobile :</span>
                        <div class="ml-auto">{{ $employeeModel->mobile }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Telephone Number :</span>
                        <div class="ml-auto">{{ $employeeModel->telephone }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Email :</span>
                        <div class="ml-auto">{{ $employeeModel->personal_email }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Gender :</span>
                        <div class="ml-auto">{{ optional($employeeModel->getGender)->dropvalue }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Marital Status :</span>
                        <div class="ml-auto">{{ optional($employeeModel->getMaritalStatus)->dropvalue }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Date of Birth :</span>
                        <div class="ml-auto">
                            {{ setting('calendar_type') == 'BS' ? $employeeModel->nep_dob : $employeeModel->dob }}
                        </div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Blood Group :</span>
                        <div class="ml-auto">{{ optional($employeeModel->getBloodGroup)->dropvalue }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Citizenship ID :</span>
                        <div class="ml-auto">{{ $employeeModel->citizenship_no }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Ethnicity :</span>
                        <div class="ml-auto">{{ $employeeModel->ethnicity }}</div>
                    </li>

                    <li class="media mt-2">
                        <span class="font-weight-semibold">Languages :</span>
                        <div class="ml-auto">
                            {{ !is_null($employeeModel->languages) ? $employeeModel->languages : null }}
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Temporary Address</legend>
                <ul class="media-list">
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Address :</span>
                        <div class="ml-auto">{{ $employeeModel->temporaryaddress }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Municipality/VDC :</span>
                        <div class="ml-auto">{{ $employeeModel->temporarymunicipality_vdc }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">District :</span>
                        <div class="ml-auto">{{ optional($employeeModel->temporaryDistrictModel)->district_name }}
                        </div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Province/State :</span>
                        <div class="ml-auto">{{ optional($employeeModel->temporaryProvinceModel)->province_name }}
                        </div>
                    </li>
                </ul>
                <legend class="text-uppercase font-size-sm font-weight-bold mt-2">Permanent Address</legend>
                @if ($employeeModel->country)
                    <ul class="media-list">
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Country :</span>
                            <div class="ml-auto">
                                {{ optional($employeeModel->getCountry($employeeModel->country))->name }}</div>

                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Address :</span>
                            <div class="ml-auto">{{ $employeeModel->permanentaddress }}</div>
                        </li>
                    </ul>
                @else
                    <ul class="media-list">
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Address :</span>
                            <div class="ml-auto">{{ $employeeModel->permanentaddress }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Municipality/VDC :</span>
                            <div class="ml-auto">{{ $employeeModel->permanentmunicipality_vdc }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">District :</span>
                            <div class="ml-auto">{{ optional($employeeModel->permanentDistrictModel)->district_name }}
                            </div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Province/State :</span>
                            <div class="ml-auto">{{ optional($employeeModel->permanentProvinceModel)->province_name }}
                            </div>
                        </li>
                    </ul>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Official Details</legend>
                <ul class="media-list">
                    {{-- <li class="media mt-2">
                        <span class="font-weight-semibold">Email :</span>
                        <div class="ml-auto">{{ $employeeModel->official_email }}</div>
                    </li> --}}
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Unit :</span>
                        <div class="ml-auto">{{ optional($employeeModel->branchModel)->name }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Join Date :</span>
                        <div class="ml-auto">
                            {{ setting('calendar_type') == 'BS' ? $employeeModel->nepali_join_date : date('d M, Y', strtotime($employeeModel->join_date)) }}
                        </div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Tenure :</span>
                        <div class="ml-auto">{{ $current_join__date }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Day Off :</span>
                        <div class="ml-auto">{{ $employeeModel->dayoff }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Function :</span>
                        <div class="ml-auto">{{ optional($employeeModel->department)->title }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Sub-Funtion :</span>
                        <div class="ml-auto">{{ optional($employeeModel->designation)->title }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Grade :</span>
                        <div class="ml-auto">{{ optional($employeeModel->level)->title }}</div>
                    </li>
                    {{-- <li class="media mt-2">
                        <span class="font-weight-semibold">Functional Title :</span>
                        <div class="ml-auto">{{ $employeeModel->job_title }}</div>
                    </li> --}}
                </ul>
                <legend class="text-uppercase font-size-sm font-weight-bold mt-2">User Details</legend>
                <ul class="media-list">
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Username :</span>
                        <div class="ml-auto">{{ $employeeModel->user_name ?? '' }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">User Type :</span>
                        <div class="ml-auto">{{ $employeeModel->user_type ?? '' }}</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Payroll Related Details</legend>
                <ul class="media-list">
                    <li class="media mt-2">
                        <span class="font-weight-semibold">PAN No:</span>
                        <div class="ml-auto">{{ $employeeModel->pan_no }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">SSF No :</span>
                        <div class="ml-auto">{{ $employeeModel->ssf_no }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">PF No:</span>
                        <div class="ml-auto">{{ $employeeModel->pf_no }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">CIT No :</span>
                        <div class="ml-auto">{{ $employeeModel->cit_no }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Gratuity Fund Account Number :</span>
                        <div class="ml-auto">{{ $employeeModel->gratuity_fund_account_no }}</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Leave / Attendance Approval Flow</legend>
                <ul class="media-list">
                    <li class="media mt-2">
                        <span class="font-weight-semibold">First Supervisor :</span>
                        <div class="ml-auto">
                            {{ optional(optional(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->userFirstApproval)->userEmployer)->full_name }}
                        </div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Second Supervisor :</span>
                        <div class="ml-auto">
                            {{ optional(optional(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->userSecondApproval)->userEmployer)->full_name }}
                        </div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Third Supervisor :</span>
                        <div class="ml-auto">
                            {{ optional(optional(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->userThirdApproval)->userEmployer)->full_name }}
                        </div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Second Supervisor :</span>
                        <div class="ml-auto">
                            {{ optional(optional(optional($employeeModel->employeeApprovalFlowRelatedDetailModel)->userLastApproval)->userEmployer)->full_name }}
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Claim / Request Approval Flow</legend>
                <ul class="media-list">
                    <li class="media mt-2">
                        <span class="font-weight-semibold">First Supervisor :</span>
                        <div class="ml-auto">
                            {{ optional(optional(optional($employeeModel->employeeClaimRequestApprovalDetailModel)->firstApproval)->userEmployer)->full_name }}
                        </div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Second Supervisor :</span>
                        <div class="ml-auto">
                            {{ optional(optional(optional($employeeModel->employeeClaimRequestApprovalDetailModel)->lastApproval)->userEmployer)->full_name }}
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Job Description</legend>
                <ul class="media-list">
                    <li class="media mt-2">
                        <span class="font-weight-semibold"></span>
                        @if (!is_null($employeeModel->job_description))
                            <div class="ml-auto">{{ $employeeModel->job_description }}</div>
                        @endif
                        @if (!is_null($employeeModel->resume))
                            <a href="{{ asset('uploads/employee/resume/' . $employeeModel->resume) }}"
                                target="_blank" class="btn btn-outline-secondary btn-sm"><i class="icon-eye"></i>
                                Preview</a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Archive Description</legend>
                @if (
                    $employeeModel &&
                        $employeeModel->archivedDetails &&
                        count($employeeModel->archivedDetails->where('status', '1')) > 0)
                    @foreach ($employeeModel->archivedDetails->where('status', '1') as $data)
                        <p class="card-text">Archive Date: {{ $data->archived_date }}
                            <br>Archive Reason: {!! $data->archive_reason !!}
                        </p>
                    @endforeach
                @else
                    <p class="card-text">Archive Date: {{ $employeeModel->archived_date }}
                        <br>Archive Reason: {!! $employeeModel->archive_reason !!}
                    </p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Archive Active Description</legend>

                @if (
                    $employeeModel &&
                        $employeeModel->archivedDetails &&
                        count($employeeModel->archivedDetails->where('status', '2')) > 0)
                    @foreach ($employeeModel->archivedDetails->where('status', '2') as $data)
                        <p class="card-text">Active Date: {{ $data->archived_date }}
                        </p>
                    @endforeach
                @else
                    <p class="card-text">Active Date:
                    </p>
                @endif
            </div>
        </div>
    </div>


</div>
