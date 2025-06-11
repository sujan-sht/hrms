<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form>
            <div class="row">
                @php
                    if (setting('calendar_type') == 'BS') {
                        $classData = 'form-control nepali-calendar';
                    } else {
                        $classData = 'form-control daterange-single';
                    }
                @endphp
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="example-email" class="form-label">Date</label>
                        {!! Form::text('date', $value = isset($_GET['date']) ? $_GET['date'] : null, [
                            'placeholder' => 'e.g : YYYY-MM-DD',
                            'class' => $classData,
                            'autocomplete' => 'off',
                        ]) !!}
                    </div>
                </div>

                {{-- @if (auth()->user()->user_type == 'admin' ||
                        auth()->user()->user_type == 'super_admin' ||
                        auth()->user()->user_type == 'hr' ||
                        auth()->user()->user_type == 'division_hr'
                        )
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="example-email" class="form-label">Organization</label>
                            @php
                                if (isset($_GET['organization_id'])) {
                                    $orgValue = $_GET['organization_id'];
                                } else {
                                    $orgValue = null;
                                }
                            @endphp
                            {!! Form::select('organization_id', $organizationList, $value = $orgValue, [
                                'placeholder' => 'Select Organization',
                                'class' => 'form-control select2 organization-filter',
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="example-email" class="form-label">Employee</label>
                            @php
                                if (isset($_GET['employee_id'])) {
                                    $employeeValue = $_GET['employee_id'];
                                } else {
                                    $employeeValue = null;
                                }
                            @endphp
                            {!! Form::select('employee_id', $employeeList, $value = $employeeValue, [
                                'placeholder' => 'Select Employee',
                                'class' => 'form-control select2 employee-filter',
                            ]) !!}
                        </div>
                    </div>
                @endif

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="example-email" class="form-label">Type</label>
                        @php
                            if (isset($_GET['type'])) {
                                $selectedType = $_GET['type'];
                            } else {
                                $selectedType = null;
                            }
                        @endphp
                        {!! Form::select('type', $type, $value = $selectedType, [
                            'placeholder' => 'Select Type',
                            'class' => 'form-control select2',
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="example-email" class="form-label">Status</label>
                        @php
                            if (isset($_GET['status'])) {
                                $selectedStatus = $_GET['status'];
                            } else {
                                $selectedStatus = null;
                            }
                        @endphp
                        {!! Form::select('status', $allStatus, $value = $selectedStatus, [
                            'placeholder' => 'Select Status',
                            'class' => 'form-control select2',
                        ]) !!}
                    </div>
                </div> --}}

            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-1" type="submit">
                    <i class="icons icon-filter3 mr-1"></i>Proceed
                </button>

                {{-- <a href="{{ request()->url() }}" class="btn bg-secondary text-white"><i
                        class="icons icon-reset mr-1"></i>Reset</a> --}}
            </div>
        </form>

    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
