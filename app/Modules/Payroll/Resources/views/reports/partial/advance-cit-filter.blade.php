<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_multiselect.js')}}"></script>

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
                        if(isset($_GET['organization_id'])) {
                            $organizationValue = $_GET['organization_id'];
                        } else {
                            $organizationValue = null;
                        }
                    @endphp
                    {{-- {!! Form::select('organization_id', $organizationList, $organizationId = $organizationValue, ['placeholder'=>'Select Organization', 'class'=>'form-control select2']) !!} --}}
                    {!! Form::select('organization_id', $organizationList, $organizationId, [
                        // 'placeholder'=>'Select Organization', 
                        'class'=>'form-control select2', 
                        'id'=>'organization_id']) !!}
                </div>

                <div class="col-md-3 mb-2">
                    <label class="form-label">Branch</label>
                    @php
                        if(isset($_GET['branch_id'])) {
                            $branchValue = $_GET['branch_id'];
                        } else {
                            $branchValue = null;
                        }
                    @endphp
                    {!! Form::select('branch_id', $branchList, $value = $branchValue, ['placeholder'=>'Select Branch', 'class'=>'form-control select2 branch-filter','id'=>'branch_id']) !!}
                </div>

                <div class="col-md-3 mb-2">
                    <label class="form-label">Year</label>
                    {!! Form::select('year_id', $year, optional($payrollModel)->year, ['placeholder'=>'Select Year', 'class'=>'form-control select2', 'id'=>'year_id']) !!}
                </div>

                <div class="col-md-3 mb-2">
                    <label class="form-label">Month</label>
                    {!! Form::select('month_id', $month, optional($payrollModel)->month, ['placeholder'=>'Select Month', 'class'=>'form-control select2', 'id'=>'month_id']) !!}
                </div>

                <div class="col-md-3 mb-2">
                    @php
                        if(isset($_GET['employee_id'])) {
                            $employeeValue = $_GET['employee_id'];
                        } else {
                            $employeeValue = null;
                        }
                    @endphp
                    <label class="form-label">Employee</label>
                    {!! Form::select('employee_id', $employeeList, $employeeValue, ['placeholder'=>'Select Employee', 'class'=>'form-control select2', 'id'=>'employee_id']) !!}
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
