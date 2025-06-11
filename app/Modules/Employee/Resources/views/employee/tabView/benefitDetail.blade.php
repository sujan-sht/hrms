<script src="{{ asset('admin/validation/createBenefitDetail.js') }}"></script>
<script src="{{ asset('admin/validation/editBenefitDetail.js') }}"></script>

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Benefit Details
                        </legend>
                    </div>
                    @if ($menuRoles->assignedRoles('benefitDetail.save'))
                        @if ($employeeModel->status == 1)
                            <div class="col-1 text-center">
                                <a class="btn btn-sm btn-success rounded-pill createmode" data-name="Benefit"><i
                                        class="icon-plus2"></i> Add</a>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>S.N</th>
                                <th>Benefit Type</th>
                                <th>Plan</th>
                                <th>Coverage</th>
                                <th>Effective Date</th>
                                <th>Empoyee Contribution (Rs.)</th>
                                <th>Company Contribution (Rs.)</th>

                                {{-- @if ($menuRoles->assignedRoles('cheatSheet.edit')) --}}
                                @if ($employeeModel->status == 1)
                                    <th width="12%">Action</th>
                                @endif
                                {{-- @endif --}}
                            </tr>
                        </thead>
                        <tbody class="benefitTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 d-none">
        <div class="card createBenefitDetail">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Create Benefit Details</legend>
                <form class="submitBenefitDetail validateBenefitDetail">
                    <label class="col-form-label">Benefit Type:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('benefit_type_id', $benefit_types, null, [
                                    'placeholder' => 'Choose Benefit Type',
                                    'class' => 'form-control select-search',
                                    'id' => 'benefitType',
                                ]) !!}
                            </div>
                            @if ($errors->has('benefit_type_id'))
                                <div class="error text-danger">{{ $errors->first('benefit_type_id') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Plan:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('plan', null, [
                                    'placeholder' => 'Enter Plan',
                                    'class' => 'form-control',
                                    'id' => 'plan',
                                ]) !!}
                            </div>
                            @if ($errors->has('plan'))
                                <div class="error text-danger">{{ $errors->first('plan') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Coverage:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('coverage', ['fully' => 'Fully', 'partially' => 'Partially'], null, [
                                    'placeholder' => 'Select Coverage',
                                    'class' => 'form-control select-search',
                                    'id' => 'coverage',
                                ]) !!}
                            </div>
                            @if ($errors->has('coverage'))
                                <div class="error text-danger">{{ $errors->first('coverage') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Effective Date:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('effective_date', null, [
                                    'placeholder' => 'Choose Effective Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'effectiveDate',
                                    'autocomplete' => 'off',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('effective_date'))
                                <div class="error text-danger">{{ $errors->first('effective_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Employee Contribution:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('employee_contribution', null, [
                                    'placeholder' => 'Enter Amount',
                                    'class' => 'form-control',
                                    'id' => 'employeeContribution',
                                ]) !!}
                            </div>
                            @if ($errors->has('employee_contribution'))
                                <div class="error text-danger">{{ $errors->first('employee_contribution') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Company Contribution:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('company_contribution', null, [
                                    'placeholder' => 'Enter Amount',
                                    'class' => 'form-control',
                                    'id' => 'companyContribution',
                                ]) !!}
                            </div>
                            @if ($errors->has('company_contribution'))
                                <div class="error text-danger">{{ $errors->first('company_contribution') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit"
                            class="ml-2 mt-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                                    class="icon-database-insert"></i></b>Save</button>
                        <a type="submit" href="javascript:void(0)"
                            class="ml-2 mt-2 btn btn-secondary btn-labeled btn-labeled-left float-right go-back"><b><i
                                    class="icon-cancel-circle2"></i></b>Discard</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card editBenefitDetail" style="display: none">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Edit Benefit Details</legend>
                <form class="updateBenefitDetail validateUpdateBenefitDetail">
                    <label class="col-form-label">Benefit Type:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('benefit_type_id', $benefit_types, null, [
                                    'placeholder' => 'Choose Benefit Type',
                                    'class' => 'form-control select-search',
                                    'id' => 'editBenefitType',
                                ]) !!}
                            </div>
                            @if ($errors->has('benefit_type_id'))
                                <div class="error text-danger">{{ $errors->first('benefit_type_id') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Plan:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('plan', null, [
                                    'placeholder' => 'Enter Plan',
                                    'class' => 'form-control',
                                    'id' => 'editPlan',
                                ]) !!}
                            </div>
                            @if ($errors->has('plan'))
                                <div class="error text-danger">{{ $errors->first('plan') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Coverage:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('coverage', ['fully' => 'Fully', 'partially' => 'Partially'], null, [
                                    'placeholder' => 'Select Coverage',
                                    'class' => 'form-control select-search',
                                    'id' => 'editCoverage',
                                ]) !!}
                            </div>
                            @if ($errors->has('coverage'))
                                <div class="error text-danger">{{ $errors->first('coverage') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Effective Date:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('effective_date', null, [
                                    'placeholder' => 'Choose Effective Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'editEffectiveDate',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('effective_date'))
                                <div class="error text-danger">{{ $errors->first('effective_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Employee Contribution:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('employee_contribution', null, [
                                    'placeholder' => 'Enter Amount',
                                    'class' => 'form-control',
                                    'id' => 'editEmployeeContribution',
                                ]) !!}
                            </div>
                            @if ($errors->has('employee_contribution'))
                                <div class="error text-danger">{{ $errors->first('employee_contribution') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Company Contribution:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('company_contribution', null, [
                                    'placeholder' => 'Enter Amount',
                                    'class' => 'form-control',
                                    'id' => 'editCompanyContribution',
                                ]) !!}
                            </div>
                            @if ($errors->has('company_contribution'))
                                <div class="error text-danger">{{ $errors->first('company_contribution') }}</div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="benefitDetailId" class="benefitDetailId">

                    <div class="text-center">
                        <button type="submit"
                            class="ml-2 mt-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                                    class="icon-database-insert"></i></b>Update</button>
                        <a type="submit" href="javascript:void(0)"
                            class="ml-2 mt-2 btn btn-secondary btn-labeled btn-labeled-left float-right go-back"><b><i
                                    class="icon-cancel-circle2"></i></b>Discard</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@include('employee::employee.js.benefitDetailJsFunction')

<script>
    // $('.select-search').select2();
</script>
