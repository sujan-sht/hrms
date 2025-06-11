@extends('admin::layout')

@section('title')
    Employee View
@endsection

@section('breadcrum')
    <a href="{{ route('employee.index') }}" class="breadcrumb-item">Employees</a>
    <a class="breadcrumb-item active">View</a>
@endsection
@section('script')
    <!-- Theme JS files -->
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>

@stop

@section('content')
    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
    @if (Auth::user()->user_type == 'super_admin' || Auth::user()->user_type == 'hr')
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-lg-3">
                        <label for="employee_id">Select Employee</label>
                        {!! Form::select('employee_id', $employeeList, $employeeModel->id, [
                            'placeholder' => 'Select Employee',
                            'class' => 'form-control select-search',
                            'id' => 'employee_id',
                        ]) !!}
                    </div>

                </div>
                <button id="viewProfile" class="btn btn-primary">View Profile</button>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row tab-content">
                <div class="col-md-3">
                    <div class="card-img-actions mx-1 mt-1 text-center mb-1">
                        <figure style="height:150px; width:150px; margin: 20px auto 0;" class="text-center">
                            <img class="img img-thumbnail rounded-circle"
                                style="width: 100%; height: 100%; object-fit: cover;" src="{{ $employeeModel->getImage() }}"
                                alt="">
                        </figure>
                    </div>
                    <div class="card-body text-center">
                        <h3 class="font-weight-semibold mb-0">{{ $employeeModel->getFullName() }}</h3>
                        <span class="d-block text-muted">{{ $employeeModel->official_email }}</span>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-semibold">
                                <i class="text-teal icon-folder6 mr-2"></i>
                                <span class="text-teal"> EMPLOYEE&nbsp;&nbsp;DETAILS</span>
                            </h6>
                            <div>
                                <a href="{{ route('employee.downloadProfile', $employeeModel->id) }}"
                                    class="btn btn-outline-secondary rounded-pill"><i class="icon-download mr-1"></i>
                                    Download</a>
                                @if ($employeeModel->status == 1)
                                    @if (Auth::user()->user_type == 'employee')
                                        <a data-toggle="modal" data-target="#employee_profile_edit"
                                            class="btn btn-teal rounded-pill edit_employee"
                                            employee_id="{{ $employeeModel->id }}"
                                            first_name="{{ $employeeModel->first_name }}"
                                            middle_name="{{ $employeeModel->middle_name }}"
                                            last_name="{{ $employeeModel->last_name }}"
                                            phone="{{ $employeeModel->phone }}" mobile="{{ $employeeModel->mobile }}"
                                            personal_email="{{ $employeeModel->personal_email }}"
                                            temporaryaddress="{{ $employeeModel->temporaryaddress }}"
                                            permanentaddress="{{ $employeeModel->permanentaddress }}" data-popup="tooltip"
                                            data-placement="bottom"
                                            data-original-title="Edit">&nbsp;&nbsp;Edit&nbsp;&nbsp;</a>
                                    @else
                                        @if ($menuRoles->assignedRoles('employee.edit'))
                                            <a href="{{ route('employee.edit', $employeeModel->id) }}"
                                                class="btn btn-teal rounded-pill">&nbsp;&nbsp;Edit&nbsp;&nbsp;</a>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="dropdown-divider mb-2"></div>
                        <ul class="row m-0 nav nav-tabs nav-tabs-bottom mr-md-3 wmin-md-200 mb-md-0 border-bottom-0">
                            <li class="nav-item col-3">
                                <a href="#myProfile" class="nav-link text-slate active" data-toggle="tab">
                                    <i class="icon-user mr-1"></i> My Profile
                                </a>
                            </li>
                            <li class="nav-item col-lg-3 col-md-4">
                                <a href="#leave" class="nav-link text-slate leaveDetail" data-toggle="tab">
                                    <i class="icon-exit2 mr-1"></i> Leave Detail
                                </a>
                            </li>
                            @if ($menuRoles->assignedRoles('familyDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#family" class="nav-link text-slate familyDetail" data-toggle="tab">
                                        <i class="icon-users4 mr-1"></i> Family Detail
                                        @if ($menuRoles->assignedRoles('familyDetail.save'))
                                            <i class="icon-pencil ml-2 text-xs"></i>
                                        @endif
                                    </a>
                                </li>
                            @endif

                            @if ($menuRoles->assignedRoles('assetDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4 ">
                                    <a href="#asset" class="nav-link text-slate assetDetail" data-toggle="tab">
                                        <i class="icon-stack-check mr-1"></i> Assets Detail
                                    </a>
                                </li>
                            @endif

                            @if ($menuRoles->assignedRoles('emergencyDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#emergency" class="nav-link text-slate emergencyDetail" data-toggle="tab">
                                        <i class="icon-phone-plus mr-1"></i> Emergency Detail
                                    </a>
                                </li>
                            @endif

                            @if ($menuRoles->assignedRoles('benefitDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#benefit" class="nav-link text-slate benefitDetail" data-toggle="tab">
                                        <i class="icon-thumbs-up2 mr-1"></i> Benefit Detail
                                    </a>
                                </li>
                            @endif

                            @if ($menuRoles->assignedRoles('educationDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#education" class="nav-link text-slate educationDetail" data-toggle="tab">
                                        <i class="icon-graduation mr-1"></i> Education Detail
                                        @if ($menuRoles->assignedRoles('educationDetail.save'))
                                            <i class="icon-pencil ml-2 text-xs"></i>
                                        @endif
                                    </a>
                                </li>
                            @endif

                            @if ($menuRoles->assignedRoles('previousJobDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#previous_employment" class="nav-link text-slate previousJobDetail"
                                        data-toggle="tab">
                                        <i class="icon-briefcase mr-1"></i> Previous Job Detail
                                        @if ($menuRoles->assignedRoles('previousJobDetail.save'))
                                            <i class="icon-pencil ml-2 text-xs"></i>
                                        @endif
                                    </a>
                                </li>
                            @endif

                            @if ($menuRoles->assignedRoles('bankDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#bank" class="nav-link text-slate bankDetail" data-toggle="tab">
                                        <i class="icon-library2 mr-1"></i> Bank Detail
                                        @if ($menuRoles->assignedRoles('bankDetail.save'))
                                            <i class="icon-pencil ml-2 text-xs"></i>
                                        @endif
                                    </a>
                                </li>
                            @endif

                            @if ($menuRoles->assignedRoles('contractDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#contract_probation" class="nav-link text-slate contractDetail"
                                        data-toggle="tab">
                                        <i class="icon-certificate mr-1"></i> Contract Detail
                                    </a>
                                </li>
                            @endif

                            @if ($menuRoles->assignedRoles('documentDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#document" class="nav-link text-slate documentDetail" data-toggle="tab">
                                        <i class="icon-files-empty mr-1"></i> Document Detail
                                    </a>
                                </li>
                            @endif
                            {{-- <li class="nav-item  col-lg-3 col-md-4">
                                <a href="#payroll_history" class="nav-link text-slate" data-toggle="tab">
                                    <i class="icon-credit-card mr-1"></i>Payroll History
                                </a>
                            </li> --}}
                            @if ($menuRoles->assignedRoles('researchAndPublicationDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#research_publication"
                                        class="nav-link text-slate researchAndPublicationDetail" data-toggle="tab">
                                        <i class="icon-file-eye mr-1"></i> Research &amp; Publication Detail
                                        @if ($menuRoles->assignedRoles('researchAndPublicationDetail.save'))
                                            <i class="icon-pencil ml-2 text-xs"></i>
                                        @endif
                                    </a>
                                </li>
                            @endif

                            @if ($menuRoles->assignedRoles('visaAndImmigrationDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#visa_immigration" class="nav-link text-slate visaAndImmigrationDetail"
                                        data-toggle="tab">
                                        <i class="icon-airplane3 mr-1"></i> Visa/Immigration Doc Detail
                                    </a>
                                </li>
                            @endif

                            @if ($menuRoles->assignedRoles('medicalDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#medical" class="nav-link text-slate medicalDetail" data-toggle="tab">
                                        <i class="icon-bed2 mr-1"></i> Medical Detail
                                    </a>
                                </li>
                            @endif

                            @if ($menuRoles->assignedRoles('trainingDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#training_and_certificate" class="nav-link text-slate trainingDetail"
                                        data-toggle="tab">
                                        <i class="icon-reading mr-1"></i> Training & Certificate
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item  col-lg-3 col-md-4">
                                <a href="#timeline" class="nav-link text-slate" data-toggle="tab">
                                    <i class="icon-stats-growth mr-1"></i> Timeline
                                </a>
                            </li>
                            @if ($menuRoles->assignedRoles('employeeCarrierMobility.list'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#carrier_mobility_log_history" class="nav-link text-slate carrierMobilityTab"
                                        data-toggle="tab">
                                        <i class="icon-transmission"></i> Career Mobility Detail
                                    </a>
                                </li>
                            @endif

                            @if ($menuRoles->assignedRoles('awardDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#award" class="nav-link text-slate awardDetails" data-toggle="tab">
                                        <i class="icon-certificate mr-1"></i> Award Detail
                                        @if ($menuRoles->assignedRoles('awardDetail.save'))
                                            <i class="icon-pencil ml-2 text-xs"></i>
                                        @endif
                                    </a>
                                </li>
                            @endif

                            @if ($menuRoles->assignedRoles('skillDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#skill" class="nav-link text-slate skillDetail" data-toggle="tab">
                                        <i class="icon-bed2 mr-1"></i> Skill Detail
                                        @if ($menuRoles->assignedRoles('skillDetail.save'))
                                            <i class="icon-pencil ml-2 text-xs"></i>
                                        @endif
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item  col-lg-3 col-md-4">
                                <a href="#payroll" class="nav-link text-slate payrollDetail" data-toggle="tab">
                                    <i class="icon-bed2 mr-1"></i> Payroll Detail
                                </a>
                            </li>

                            @if ($menuRoles->assignedRoles('documentDetail.appendAll'))
                                <li class="nav-item  col-lg-3 col-md-4">
                                    <a href="#insurance" class="nav-link text-slate insuranceDetail" data-toggle="tab">
                                        <i class="icon-files-empty mr-1"></i> Insurance Detail
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item  col-lg-3 col-md-4">
                                <a href="#nomineeDetail" class="nav-link text-slate nomineeDetail" data-toggle="tab">
                                    <i class="icon-users4 mr-1"></i> Nominee Detail
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-content">

        <div id="myProfile" class="tab-pane fade active show">
            @include('employee::employee.tabView.myProfile')
        </div>

        <div id="skill" class="tab-pane fade">
            @include('employee::employee.tabView.skillDetail')
        </div>

        <div id="award" class="tab-pane fade">
            @include('employee::employee.tabView.awardDetail')
        </div>


        <div id="leave" class="tab-pane fade">
            @include('employee::employee.tabView.leaveDetail')
        </div>

        <div id="family" class="tab-pane fade">
            @include('employee::employee.tabView.familyDetail')
        </div>

        <div id="nomineeDetail" class="tab-pane fade">
            @include('employee::employee.tabView.nomineeDetail')
        </div>

        <div id="asset" class="tab-pane fade">
            @include('employee::employee.tabView.assetDetail')
        </div>

        <div id="emergency" class="tab-pane fade">
            @include('employee::employee.tabView.emergencyDetail')
        </div>

        <div id="benefit" class="tab-pane fade">
            @include('employee::employee.tabView.benefitDetail')
        </div>

        <div id="education" class="tab-pane fade">
            @include('employee::employee.tabView.educationDetail')
        </div>

        <div id="previous_employment" class="tab-pane fade">
            @include('employee::employee.tabView.previousJobDetail')
        </div>

        <div id="bank" class="tab-pane fade">
            @include('employee::employee.tabView.bankDetail')
        </div>

        <div id="contract_probation" class="tab-pane fade">
            @include('employee::employee.tabView.contractDetail')
        </div>

        <div id="document" class="tab-pane fade">
            @include('employee::employee.tabView.documentDetail')
        </div>

        <div id="research_publication" class="tab-pane fade">
            @include('employee::employee.tabView.researchAndPublicationDetail')
        </div>

        <div id="visa_immigration" class="tab-pane fade">
            @include('employee::employee.tabView.visaAndImmigrationDetail')
        </div>

        <div id="medical" class="tab-pane fade">
            @include('employee::employee.tabView.medicalDetail')
        </div>

        <div id="training_and_certificate" class="tab-pane fade">
            @include('employee::employee.tabView.trainingDetail')
        </div>

        <div id="timeline" class="tab-pane fade">
            @include('employee::employee.tabView.timelineDetail')
        </div>

        <div id="carrier_mobility_log_history" class="tab-pane fade">
            @include('employee::employee.tabView.carrierMobilityDetail')
        </div>

        {{-- <div id="transfer_log_history" class="tab-pane fade">
            @include('employee::employee.tabView.transferDetail')
        </div>

        <div id="promotion_log_history" class="tab-pane fade">
            @include('employee::employee.tabView.promotionDetail')
        </div>

        <div id="demotion_log_history" class="tab-pane fade">
            @include('employee::employee.tabView.demotionDetail')
        </div> --}}

        <div id="payroll" class="tab-pane fade">
            @include('employee::employee.tabView.payrollDetail')
        </div>
        <div id="insurance" class="tab-pane fade">
            @include('employee::employee.tabView.insuranceDetail')
        </div>
    </div>

    <div id="employee_profile_edit" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h6 class="modal-title text-white">Edit Details</h6>
                </div>

                <div class="modal-body">

                    {!! Form::open([
                        'route' => ['employee.updateEmployeeProfile', $employeeModel->id],
                        'method' => 'PUT',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'class' => 'profileEditForm',
                        'files' => true,
                    ]) !!}
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="col-form-label">Full Name: <span class="text-danger">*</span></label>
                            <div class="col-lg-12">
                                {!! Form::text('full_name', $value = $employeeModel->full_name ?? null, [
                                    'placeholder' => 'Enter Full Name',
                                    'class' => 'form-control',
                                    'required' => 'required',
                                    'readonly',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-0">
                        <div class="col-lg-6">
                            <label class="col-form-label">National ID: </label>
                            <div class="col-lg-12">
                                {!! Form::text('national_id', $value = $employeeModel->national_id ?? null, [
                                    'placeholder' => 'Enter National ID',
                                    'class' => 'form-control',
                                    // 'required' => 'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="col-form-label">National ID(Attachment): </label>
                            <div class="col-lg-12">
                                {!! Form::file('nationalId_file', $value = null, [
                                    'class' => 'form-control',
                                    // 'required' => 'required',
                                ]) !!}
                            </div>
                        </div>

                    </div>
                    <div class="form-group row mt-0">
                        <div class="col-lg-6">
                            <label class="col-form-label">Passport No: </label>
                            <div class="col-lg-12">
                                {!! Form::text('passport_no', $value = $employeeModel->passport_no ?? null, [
                                    'placeholder' => 'Enter Passport Number',
                                    'class' => 'form-control',
                                    // 'required' => 'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="col-form-label">Passport Image: </label>
                            <div class="col-lg-12">
                                {!! Form::file('passport', $value = null, [
                                    'class' => 'form-control',
                                    // 'required' => 'required',
                                ]) !!}
                            </div>
                        </div>

                    </div>
                    {{-- <div class="form-group row">
                        <label class="col-form-label col-lg-3">First Name: </label>
                        <div class="col-lg-9">
                            {!! Form::text('first_name', $value = null, [
                                'id' => 'first_name',
                                'placeholder' => 'Enter First Name',
                                'class' => 'form-control',
                                'required' => 'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Middle Name:</label>
                        <div class="col-lg-9">
                            {!! Form::text('middle_name', $value = null, [
                                'id' => 'middle_name',
                                'placeholder' => 'Enter Middle Name',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Last Name: </label>
                        <div class="col-lg-9">
                            {!! Form::text('last_name', $value = null, [
                                'id' => 'last_name',
                                'placeholder' => 'Enter Last Name',
                                'class' => 'form-control',
                                'required' => 'required',
                            ]) !!}
                        </div>
                    </div> --}}
                    <div class="form-group row mt-0">
                        <div class="col-lg-6">
                            <label class="col-form-label">Phone (CUG):</label>
                            <div class="col-lg-12">
                                {!! Form::text('phone', $value = $employeeModel->phone ?? null, [
                                    'id' => 'phone',
                                    'placeholder' => 'Enter Phone Number',
                                    'class' => 'form-control numeric',
                                    'readonly' => in_array(auth()->user()->user_type, ['super_admin', 'admin', 'hr']) ? false : 'readonly',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="col-form-label">Mobile: </label>
                            <div class="col-lg-12">
                                {!! Form::text('mobile', $value = $employeeModel->mobile ?? null, [
                                    'id' => 'mobile',
                                    'placeholder' => 'Enter Mobile Number',
                                    'class' => 'form-control numeric',
                                    // 'required' => 'required',
                                ]) !!}
                            </div>
                        </div>

                    </div>
                    <div class="form-group row mt-0">
                        <div class="col-lg-6">
                            <label class="col-form-label">Telephone No:</label>
                            <div class="col-lg-12">
                                {!! Form::text('telephone', $value = $employeeModel->telephone ?? null, [
                                    'placeholder' => 'Enter Telephone Number',
                                    'class' => 'form-control numeric',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="col-form-label">Official Email: <span class="text-danger">*</span></label>
                            <div class="col-lg-12">
                                {!! Form::text('official_email', $employeeModel->official_email ?? null, [
                                    'id' => 'official_email',
                                    'placeholder' => 'Enter Official Email',
                                    'class' => 'form-control',
                                    'readonly' => in_array(auth()->user()->user_type, ['super_admin', 'admin', 'hr']) ? false : 'readonly',
                                ]) !!}

                            </div>
                        </div>

                    </div>
                    <div class="form-group row mt-0">
                        <div class="col-lg-6">
                            <label class="col-form-label">Marital Status: </label>
                            <div class="col-lg-12">
                                {!! Form::select('marital_status', $marital_status, $value = $employeeModel->marital_status ?? null, [
                                    'id' => 'marital_status',
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="col-form-label">Marital Image: </label>
                            <div class="col-lg-12">
                                {!! Form::file('marital_image', $value = null, [
                                    'class' => 'form-control',
                                    'required' => 'required',
                                ]) !!}
                            </div>
                        </div>

                    </div>
                    <div class="form-group row mt-0">
                        <div class="col-lg-6">
                            <label class="col-form-label">Citizenship No: </label>
                            <div class="col-lg-12">
                                {!! Form::text('citizenship_no', $value = $employeeModel->citizenship_no ?? null, [
                                    'placeholder' => 'Enter Citizenship NO',
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="col-form-label">Citizenship Image: </label>
                            <div class="col-lg-12">
                                {!! Form::file('citizen_pic', $value = null, [
                                    'class' => 'form-control',
                                    'required' => 'required',
                                ]) !!}
                            </div>
                        </div>

                    </div>
                    <div class="form-group row mt-0">
                        <div class="col-lg-6">
                            <label class="col-form-label">Blood Group: </label>
                            <div class="col-lg-12">
                                {!! Form::select('blood_group', $blood_group, $value = $employeeModel->blood_group ?? null, [
                                    'id' => 'blood_group',
                                    'class' => 'form-control select-search',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="col-form-label">Ethnicity: </label>
                            <div class="col-lg-12">
                                {!! Form::text('ethnicity', $value = $employeeModel->ethnicity ?? null, [
                                    'placeholder' => 'Enter Ethnicity',
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>

                    </div>
                    <div class="form-group row mt-0">
                        <div class="col-lg-6">
                            <label class="col-form-label">Languages: </label>
                            <div class="col-lg-12">
                                {!! Form::select(
                                    'languages',
                                    [
                                        'Nepali' => 'Nepali',
                                        'English' => 'English',
                                        'Hindi' => 'Hindi',
                                    ],
                                    explode(',', @$employees->languages),
                                    [
                                        'id' => 'languages',
                                        'multiple' => 'multiple',
                                        'name' => 'languages[]',
                                        'class' => 'form-control select-languages',
                                    ],
                                ) !!}
                            </div>
                        </div>


                    </div>
                    {{-- <div class="form-group row">
                        <label class="col-form-label col-lg-3">Permanent Address: <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            {!! Form::text('permanentaddress', $value = null, [
                                'id' => 'permanentaddress',
                                'placeholder' => 'Enter Permanent Address',
                                'class' => 'form-control',
                                'required' => 'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Temporary Address:</label>
                        <div class="col-lg-9">
                            {!! Form::text('temporaryaddress', $value = null, [
                                'id' => 'temporaryaddress',
                                'placeholder' => 'Enter Temporary Address',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                    </div> --}}

                    <div class="text-center">
                        <button type="submit" class="btn bg-success text-white">Update</button>
                        <button type="button" class="btn bg-danger text-white" data-dismiss="modal">Close</button>
                    </div>

                    {!! Form::close() !!}
                </div>

                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('admin/validation/profileEditForm.js') }}"></script>
    <script type="text/javascript">
        $('document').ready(function() {

            $('.toggle-on').text(''); // Removes the text "On"
            $('.toggle-off').text(''); // Removes the text "On"

            // check box for for showing timeline and table
            $('#toggleCheckbox').change(function() {
                if ($(this).prop('checked')) {
                    $('#timelineDiv').show();
                    $('#tableDiv').hide();
                } else {
                    $('#timelineDiv').hide();
                    $('#tableDiv').show();
                }
            });

            $('.edit_employee').on('click', function() {
                var first_name = $(this).attr('first_name');
                $('#first_name').val(first_name);
                var middle_name = $(this).attr('middle_name');
                $('#middle_name').val(middle_name);
                var last_name = $(this).attr('last_name');
                $('#last_name').val(last_name);
                var mobile = $(this).attr('mobile');
                $('#mobile').val(mobile);
                var phone = $(this).attr('phone');
                $('#phone').val(phone);
                var personal_email = $(this).attr('personal_email');
                $('#personal_email').val(personal_email);
                var permanentaddress = $(this).attr('permanentaddress');
                $('#permanentaddress').val(permanentaddress);
                var temporaryaddress = $(this).attr('temporaryaddress');
                $('#temporaryaddress').val(temporaryaddress);
            });

        });
    </script>
@endsection

@push('custom_script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/editor_ckeditor_default.js') }}"></script>

    {{-- <script src="https://cdn.tiny.cloud/1/cjrqkjizx7e1ld0p8kcygaj4cvzc6drni6o4xl298c5hl9l1/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script> --}}
    <script>
        $(document).ready(function() {
            $('#viewProfile').click(function() {
                var employeeId = $('#employee_id').val();
                if (employeeId) {
                    window.location.href = '/admin/employee/view/' + employeeId;
                } else {
                    alert('Please select an employee first.');
                }
            })

            $('.select-search1').select2();
            // tinymce.init({
            //     selector: 'textarea.basicTinymce',
            //     plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            //     toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            //     height: '250',
            //     width: '100%'
            // });

            $('.createmode').click(function() {
                var name = $(this).data('name');
                var card_form = $('.create' + name + 'Detail');

                $(this).parents('.col-md-12').removeClass().addClass('col-md-8');
                card_form.parent().removeClass('d-none');
                $(this).addClass('d-none');

                card_form.find('.form-group-feedback').each(function() {
                    $(this).find('.border-success').removeClass('border-success');
                    $(this).find('.form-control-feedback').remove();
                })
            })

            $('.go-back').click(function() {
                var that = $(this);
                toggleCreateBtn(that)
            })

            $('.select-languages').select2({
                placeholder: "Choose Language",
                ajax: {
                    url: "{{ route('employee.languages') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                },
                tags: true, // Allow adding new items
                createTag: function(params) {
                    return {
                        id: params.term,
                        text: params.term,
                        newOption: true
                    };
                },
                templateResult: function(data) {
                    if (data.newOption) {
                        return `New Language Add: "${data.text}"`;
                    }
                    return data.text;
                }
            });
            $('.select-languages').on('select2:select', function(e) {
                var data = e.params.data;
                if (data.newOption) {
                    $.ajax({
                        url: "{{ route('employee.language.create') }}",
                        method: 'POST',
                        data: {
                            name: data.text,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // var newOption = new Option(response.name, response.id, true, true);
                        },
                        error: function(error) {
                            alert('Error adding language');
                        }
                    });
                }
            });

        });
    </script>
@endpush

@section('popupScript')
    <script>
        function toggleCreateBtn(that) {
            that.closest('.row').find('.col-md-8').removeClass().addClass('col-md-12');
            that.closest('.row').find('.col-md-4').addClass('d-none');
            that.closest('.row').find('.createmode').removeClass('d-none');
        }

        function editModal(that) {
            that.closest('.row').find('.col-md-12').removeClass().addClass('col-md-8');
            that.closest('.row').find('.col-md-4').removeClass('d-none');
            that.closest('.row').find('.createmode').addClass('d-none');
        }

        function viewModal(that) {
            that.closest('.row').find('.col-md-12').removeClass().addClass('col-md-8');
            that.closest('.row').find('.col-md-4').removeClass('d-none');
            that.closest('.row').find('.createmode').addClass('d-none');
        }
    </script>
@endsection
