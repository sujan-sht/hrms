<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form>
            <div class="row">
                @if (Auth::user()->user_type == 'super_admin' || Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'hr' || Auth::user()->user_type == 'supervisor')

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="example-email" class="form-label">Organization: <span class="text-danger">*</span></label>
                        @php
                            if (isset($_GET['organization_id'])) {
                                $orgValue = $_GET['organization_id'];
                            } else {
                                $orgValue = null;
                            }
                        @endphp
                        {!! Form::select('organization_id', $organizationList, $value = $orgValue, [
                            // 'placeholder' => 'Select Organization',
                            'id' => 'organization',
                            'class' => 'form-control select2 organization-filter organization_id','required',
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3 mb-2">
                    <label class="form-label">Branch: <span class="text-danger">*</span></label>
                    @php
                        if(isset($_GET['branch_id'])) {
                            $branchValue = $_GET['branch_id'];
                        } else {
                            $branchValue = null;
                        }
                    @endphp
                    {!! Form::select('branch_id', $branchList, $value = $branchValue, ['placeholder'=>'Select Branch', 'class'=>'form-control select2 branch-filter','required']) !!}
                </div>


                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="example-email" class="form-label">Employee: <span class="text-danger">*</span></label>
                        @php
                            if (isset($_GET['employee_id'])) {
                                $employeeValue = $_GET['employee_id'];
                            } else {
                                $employeeValue = null;
                            }
                        @endphp
                        {!! Form::select('employee_id', $employeePluck, $value = $employeeValue, [
                            'placeholder' => 'Select Employee',
                            'class' => 'form-control select2 employee-filter','required',
                        ]) !!}
                    </div>
                </div>
                <div class="col-md-3 year" style="display:none;">
                    <div class="mb-3">
                        <label for="example-email" class="form-label">Year:</label>
                        @php
                            if (isset($_GET['year'])) {
                                $year = $_GET['year'];
                            } else {
                                $year = null;
                            }
                        @endphp
                        <div class="input-group engDiv" style="display: none;">
                            {!! Form::select('eng_year', $englishFiscalYearList, $value = $year, [
                            'id' => 'engFiscalYear',
                            'placeholder' => 'Select Year',
                            'class' => 'form-control select2','required',
                        ]) !!}
                        </div>
                        <div class="input-group nepDiv" style="display: none;">
                            {!! Form::select('year', $fiscalYearList, $value = $year, [
                            'placeholder' => 'Select Year',
                            'id' => 'nepFiscalYear',
                            'class' => 'form-control select2','required',
                        ]) !!}
                        </div>
                    </div>
                </div>



                {{-- <div class="col-md-3">
                    <label class="d-block font-weight-semibold">Month</label>
                    <div class="input-group">
                        {!! Form::select('month', $monthList, request('month') ?? null, ['placeholder'=>'Select Month', 'class'=>'form-control select-search']) !!}
                    </div>
                </div> --}}
                @endif
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icons icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ request()->url() }}" class="btn bg-secondary text-white">
                    <i class="icons icon-reset mr-1"></i>Reset
                </a>
            </div>
        </form>

    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    })
    $('#organization').on('change', function() {
        var organizationId = $('.organization_id').val();
        console.log(organizationId);
        $.ajax({
            type: 'GET',
            url: '/admin/payroll-setting/get-calendar-type',
            data: {
                organization_id: organizationId
            },
            success: function(data) {
                console.log(data);
                var list = JSON.parse(data);
                if (list.calendar_type == 'nep') {
                    $('.year').show();
                    $('.engDiv').hide();
                    $('.nepDiv').show();
                    $('.engFiscalYear').val('');
                } else {
                    $('.year').show();
                    $('.engDiv').show();
                    $('.nepDiv').hide();
                    $('.nepFiscalYear').val('');
                }
                // console.log(list.calendar_type);


            }
        });
    });
</script>
