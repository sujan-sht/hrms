<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form>
            <div class="row">

                <div class="col-md-3 mb-2">
                    <label class="form-label">Organization</label>
                    @php
                        if (isset($_GET['organization_id'])) {
                            $organizationValue = $_GET['organization_id'];
                        } else {
                            $organizationValue = null;
                        }
                    @endphp
                    {!! Form::select('organization_id', $organizationList, $value = $organizationValue, [
                        'placeholder' => 'Select Organization',
                        'class' => 'form-control select2 organization-filter2',
                    ]) !!}
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Unit</label>
                    @php
                        if (isset($_GET['branch_id'])) {
                            $branchValue = $_GET['branch_id'];
                        } else {
                            $branchValue = null;
                        }
                    @endphp
                    {!! Form::select('branch_id', $branchList, $value = $branchValue, [
                        'placeholder' => 'Select Unit',
                        'class' => 'form-control select2 branch-filter',
                    ]) !!}
                </div>
                <div class="col-md-3 mb-2">
                    <label for="example-email" class="form-label">Sub-Function</label>
                    @php
                        if (isset($_GET['department_id'])) {
                            $departmentValue = $_GET['department_id'];
                        } else {
                            $departmentValue = null;
                        }
                    @endphp
                    {!! Form::select('department_id', $departmentList, $value = $departmentValue, [
                        'class' => 'form-control department-filter',
                        'placeholder' => 'Select Sub-Function',
                        // 'class' => 'form-control multiselect-filtering',
                        // 'multiple' => 'multiple',
                    ]) !!}
                </div>
                <div class="col-md-3 mb-2">
                    <label for="example-email" class="form-label">Designation</label>
                    @php
                        if (isset($_GET['designation_id'])) {
                            $designationValue = $_GET['designation_id'];
                        } else {
                            $designationValue = null;
                        }
                    @endphp
                    {!! Form::select('designation_id', $designationList, $value = $designationValue, [
                        'class' => 'form-control designation-filter',
                        'placeholder' => 'Select Designation',
                        // 'class' => 'form-control multiselect-filtering',
                        // 'multiple' => 'multiple',
                    ]) !!}
                </div>
                <div class="col-md-3 mb-2">
                    <label for="example-email" class="form-label">Grade</label>
                    @php
                        if (isset($_GET['level_id'])) {
                            $levelValue = $_GET['level_id'];
                        } else {
                            $levelValue = null;
                        }
                    @endphp
                    {!! Form::select('level_id', $levelList, $value = $levelValue, [
                        'class' => 'form-control level-filter',
                        'placeholder' => 'Select Grade',
                        // 'class' => 'form-control multiselect-filtering',
                        // 'multiple' => 'multiple',
                    ]) !!}
                </div>
                <div class="col-md-3 mt-2">
                    <label for="example-email" class="form-label">Name</label>
                    @php
                        if (isset($_GET['name'])) {
                            $employeeValue = $_GET['name'];
                        } else {
                            $employeeValue = null;
                        }
                    @endphp
                    {!! Form::text('name', $value = $employeeValue, ['placeholder' => 'Enter Name', 'class' => 'form-control']) !!}
                </div>
                <div class="col-md-3 mt-2">
                    <label for="example-email" class="form-label text-center">Age Range</label>
                    @php
                        if (isset($_GET['age'])) {
                            $age = $_GET['age'];
                        } else {
                            $age = null;
                        }
                    @endphp
                    <div class="row">
                        <div class="col-md-6">
                            <select name="age_from" class="form-control">
                                <option value="" selected>From Age</option>
                                @foreach (range(1, 100) as $item)
                                    <option
                                        value="{{ $item }}"{{ request('age_from') == $item ? 'selected' : '' }}>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select name="age_to" class="form-control">
                                <option value="" selected>To Age</option>
                                @foreach (range(1, 100) as $item)
                                    <option value="{{ $item }}"
                                        {{ request('age_to') == $item ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mt-2">
                    <label for="example-email" class="form-label text-center">Joining Date Range</label>
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::text('tenure_from', $value = request('tenure_from') ?? null, [
                                'class' => 'form-control nepali-calendar',
                                'placeholder' => 'From',
                            ]) !!}
                        </div>
                        <div class="col-md-6">
                            {!! Form::text('tenure_to', $value = request('tenure_to') ?? null, [
                                'class' => 'form-control nepali-calendar',
                                'placeholder' => 'To',
                            ]) !!}
                        </div>
                    </div>
                </div>



                <div class="col-md-3 mt-2">
                    <label for="example-email" class="form-label">Email</label>
                    @php
                        if (isset($_GET['email'])) {
                            $employeeValue = $_GET['email'];
                        } else {
                            $employeeValue = null;
                        }
                    @endphp
                    {!! Form::text('email', $value = $employeeValue, ['placeholder' => 'Enter Email', 'class' => 'form-control']) !!}
                </div>
                <div class="col-md-3 mt-2">
                    <label for="example-email" class="form-label">Phone</label>
                    @php
                        if (isset($_GET['phone'])) {
                            $employeeValue = $_GET['phone'];
                        } else {
                            $employeeValue = null;
                        }
                    @endphp
                    {!! Form::number('phone', $value = $employeeValue, ['placeholder' => 'Enter Phone', 'class' => 'form-control']) !!}
                </div>

                <div class="col-md-3">
                    <div class="mt-2">
                        <label for="example-email" class="form-label">Employee Code</label>
                        @php
                            if (isset($_GET['employee_code'])) {
                                $employeeCode = $_GET['employee_code'];
                            } else {
                                $employeeCode = null;
                            }
                        @endphp
                        {!! Form::text('employee_code', $value = $employeeCode, [
                            'placeholder' => 'Enter Employee Code',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3 mb-2">
                    <div class="mt-2">
                        <label for="example-email" class="form-label">Role</label>

                        <select name="role_name" id="" class="form-control">
                            <option value="">Select Role</option>

                            @foreach ($rolesLists as $role)
                                <option value="{{ $role->user_type }}"
                                    {{ Request::get('role_name') == $role->user_type ? 'selected' : '' }}>
                                    {{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3 mb-2">
                    <div class="mt-2">
                        <label for="example-email" class="form-label">State/Province</label>
                        @php
                            if (isset($_GET['permanentprovince'])) {
                                $provinceValue = $_GET['permanentprovince'];
                            } else {
                                $provinceValue = null;
                            }
                        @endphp
                        {!! Form::select('permanentprovince', $state, $value = $provinceValue, [
                            'placeholder' => 'Select State/Province',
                            'class' => 'form-control select-search',
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3 mb-2">
                    <div class="mt-2">
                        <label class="form-label">Job Type</label>
                        @php
                            if (isset($_GET['job_type'])) {
                                $selected_job_type = $_GET['job_type'];
                            } else {
                                $selected_job_type = null;
                            }
                        @endphp
                        {{-- @php $selected_job_type = isset(request()->job_type) ? request()->job_type : null ; @endphp --}}
                        {!! Form::select('job_type', $jobTypeList, $selected_job_type, [
                            'placeholder' => 'Select Job Type',
                            'class' => 'form-control select2',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ request()->url() }}" class="btn bg-secondary text-white">
                    <i class="icons icon-reset mr-1"></i>Reset
                </a>
            </div>
        </form>

    </div>
</div>

<script>
    $('.select2').select2();
</script>
