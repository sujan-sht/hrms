<style>
    .form-container {
        max-height: 565px;
        /* Adjust height as needed */
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>

<script src="{{ asset('admin/validation/insuranceDetail.js') }}"></script>

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Insurance Details
                        </legend>
                    </div>
                    @if (!isset($employeeModel->insuranceDetail))
                        @if ($menuRoles->assignedRoles('insuranceDetail.save'))
                            @if ($employeeModel->status == 1)
                                <div class="col-1 text-center createInsurance">
                                    <a class="btn btn-sm btn-success rounded-pill createmode" data-name="Insurance"><i
                                            class="icon-plus2"></i> Add</a>
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>S.N</th>
                                <th>Company Name</th>
                                <th>GPA</th>
                                <th>GMI</th>
                                @if ($employeeModel->status == 1)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="insuranceTable">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 d-none">
        <div class="card createInsuranceDetail">
            <div class="card-body">
                <div class="form-container">
                    <legend class="text-uppercase font-size-sm font-weight-bold">GPA Details</legend>
                    <form class="submitInsuranceDetail validateInsuranceDetail">
                        <label class="col-form-label">Company Name:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('company_name', null, [
                                        'placeholder' => 'Enter name',
                                        'class' => 'form-control',
                                        'id' => 'company_name1',
                                    ]) !!}
                                </div>
                                @if ($errors->has('company_name'))
                                    <div class="error text-danger">{{ $errors->first('company_name') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Group Personal Accident: <span
                                class="text-danger">*</span></label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('gpa_enable', [11 => 'Yes', 10 => 'No'], null, [
                                        'placeholder' => 'Select Option',
                                        'class' => 'form-control select-search',
                                        'id' => 'gpa_enable',
                                        'required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('gpa_enable'))
                                    <div class="error text-danger">{{ $errors->first('gpa_enable') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Sum Assured:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('gpa_sum_assured', null, [
                                        'placeholder' => 'Enter Amount',
                                        'class' => 'form-control numeric',
                                        'id' => 'gpa_sum_assured',
                                    ]) !!}
                                </div>
                                @if ($errors->has('gpa_sum_assured'))
                                    <div class="error text-danger">{{ $errors->first('gpa_sum_assured') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Medical Coverage:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('medical_coverage', null, [
                                        'placeholder' => 'Enter Amount',
                                        'class' => 'form-control numeric',
                                        'id' => 'medical_coverage',
                                    ]) !!}
                                </div>
                                @if ($errors->has('medical_coverage'))
                                    <div class="error text-danger">{{ $errors->first('medical_coverage') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Individual:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('individual', null, [
                                        'placeholder' => 'Enter Amount',
                                        'class' => 'form-control numeric',
                                        'id' => 'individual',
                                    ]) !!}
                                </div>
                                @if ($errors->has('individual'))
                                    <div class="error text-danger">{{ $errors->first('individual') }}</div>
                                @endif
                            </div>
                        </div>
                        <br>

                        <legend class="text-uppercase font-size-sm font-weight-bold">GMI Details</legend>

                        <label class="col-form-label">Group Medical Insurance: <span
                                class="text-danger">*</span></label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('gmi_enable', [11 => 'Yes', 10 => 'No'], null, [
                                        'placeholder' => 'Select Option',
                                        'class' => 'form-control select-search',
                                        'id' => 'gmi_enable',
                                        'required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('gmi_enable'))
                                    <div class="error text-danger">{{ $errors->first('gmi_enable') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Type: <span class="text-danger">*</span></label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('individual_or_fam', ['individual' => 'Individual', 'family' => 'Family'], null, [
                                        'placeholder' => 'Select Option',
                                        'class' => 'form-control select-search',
                                        'id' => 'individual_or_fam',
                                        'required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('individual_or_fam'))
                                    <div class="error text-danger">{{ $errors->first('individual_or_fam') }}</div>
                                @endif
                            </div>
                        </div>
                        <br>
                        <span class="familyDiv" style="display: none;">
                            <label class="text-uppercase font-size-sm font-weight-bold">Family</label>
                            <br>
                            <div class="row mb-2">
                                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                    <div class="form-check input-group form-check-inline">
                                        <input type="checkbox" name="spouse" id='spouse' class='form-check-input'
                                            {{ '' }} value="0">
                                        <label class="col-form-label">Spouse </label>
                                    </div>
                                    @if ($errors->has('spouse'))
                                        <div class="error text-danger">{{ $errors->first('spouse') }}</div>
                                    @endif
                                </div>

                                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                    <div class="form-check input-group form-check-inline">
                                        <input type="checkbox" name="kid_one" id='kid_one' class='form-check-input'
                                            {{ '' }} value="0">
                                        <label class="col-form-label">kid One </label>
                                    </div>
                                    @if ($errors->has('kid_one'))
                                        <div class="error text-danger">{{ $errors->first('kid_one') }}</div>
                                    @endif
                                </div>

                                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                    <div class="form-check input-group form-check-inline">
                                        <input type="checkbox" name="kid_two" id='kid_two' class='form-check-input'
                                            {{ '' }} value="0">
                                        <label class="col-form-label">Kid Two </label>
                                    </div>
                                    @if ($errors->has('kid_two'))
                                        <div class="error text-danger">{{ $errors->first('kid_two') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                    <div class="form-check input-group form-check-inline">
                                        <input type="checkbox" name="mom" id='mom' class='form-check-input'
                                            {{ '' }} value="0">
                                        <label class="col-form-label">Mom </label>
                                    </div>
                                    @if ($errors->has('mom'))
                                        <div class="error text-danger">{{ $errors->first('mom') }}</div>
                                    @endif
                                </div>

                                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                    <div class="form-check input-group form-check-inline">
                                        <input type="checkbox" name="dad" id='dad'
                                            class='form-check-input' {{ '' }} value="0">
                                        <label class="col-form-label">Dad </label>
                                    </div>
                                    @if ($errors->has('dad'))
                                        <div class="error text-danger">{{ $errors->first('dad') }}</div>
                                    @endif
                                </div>
                            </div>
                            <br>
                        </span>
                        <label class="col-form-label">Sum Assured:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('gmi_sum_assured', null, [
                                        'placeholder' => 'Enter Amount',
                                        'class' => 'form-control numeric',
                                        'id' => 'gmi_sum_assured',
                                    ]) !!}
                                </div>
                                @if ($errors->has('gmi_sum_assured'))
                                    <div class="error text-danger">{{ $errors->first('gmi_sum_assured') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Hospitality (%):</label>
                        <div class="row mb-2">
                            <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('hospitality_in_perc', null, [
                                        'placeholder' => 'Enter Amount',
                                        'class' => 'form-control numeric',
                                        'id' => 'hospitality_in_perc',
                                    ]) !!}
                                </div>
                                @if ($errors->has('hospitality_in_perc'))
                                    <div class="error text-danger">{{ $errors->first('hospitality_in_perc') }}</div>
                                @endif
                            </div>

                            <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('hospitality_in_amt', null, [
                                        'class' => 'form-control numeric',
                                        'id' => 'hospitality_in_amt',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('hospitality_in_amt'))
                                    <div class="error text-danger">{{ $errors->first('hospitality_in_amt') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Domiciliary (%):</label>
                        <div class="row mb-2">
                            <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('domesticality_in_perc', null, [
                                        'placeholder' => 'Enter Amount',
                                        'class' => 'form-control numeric',
                                        'id' => 'domesticality_in_perc',
                                    ]) !!}
                                </div>
                                @if ($errors->has('domesticality_in_perc'))
                                    <div class="error text-danger">{{ $errors->first('domesticality_in_perc') }}</div>
                                @endif
                            </div>
                            <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('domesticality_in_amt', null, [
                                        'class' => 'form-control numeric',
                                        'id' => 'domesticality_in_amt',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('domesticality_in_amt'))
                                    <div class="error text-danger">{{ $errors->first('domesticality_in_amt') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit"
                                class="ml-2 mt-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                                        class="icon-database-insert"></i></b>Save
                            </button>
                            <a type="submit" href="javascript:void(0)"
                                class="ml-2 mt-2 btn btn-secondary btn-labeled btn-labeled-left go-back float-right"><b><i
                                        class="icon-cancel-circle2"></i></b>Discard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card editInsuranceDetail" style="display: none">
            <div class="card-body">
                <div class="form-container">
                    <legend class="text-uppercase font-size-sm font-weight-bold">GPA Details</legend>
                    <form class="updateInsuranceDetail validateUpdateInsuranceDetail">
                        <label class="col-form-label">Company Name:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('company_name', null, [
                                        'placeholder' => 'Enter name',
                                        'class' => 'form-control',
                                        'id' => 'edit_company_name1',
                                    ]) !!}
                                </div>
                                @if ($errors->has('company_name'))
                                    <div class="error text-danger">{{ $errors->first('company_name') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Group Personal Accident: <span
                                class="text-danger">*</span></label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('gpa_enable', [11 => 'Yes', 10 => 'No'], null, [
                                        'placeholder' => 'Select Option',
                                        'class' => 'form-control select-search',
                                        'id' => 'edit_gpa_enable',
                                        'required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('gpa_enable'))
                                    <div class="error text-danger">{{ $errors->first('gpa_enable') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Sum Assured:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('gpa_sum_assured', null, [
                                        'placeholder' => 'Enter Amount',
                                        'class' => 'form-control numeric',
                                        'id' => 'edit_gpa_sum_assured',
                                    ]) !!}
                                </div>
                                @if ($errors->has('gpa_sum_assured'))
                                    <div class="error text-danger">{{ $errors->first('gpa_sum_assured') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Medical Coverage:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('medical_coverage', null, [
                                        'placeholder' => 'Enter Amount',
                                        'class' => 'form-control numeric',
                                        'id' => 'edit_medical_coverage',
                                    ]) !!}
                                </div>
                                @if ($errors->has('medical_coverage'))
                                    <div class="error text-danger">{{ $errors->first('medical_coverage') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Individual:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('individual', null, [
                                        'placeholder' => 'Enter Amount',
                                        'class' => 'form-control numeric',
                                        'id' => 'edit_individual',
                                    ]) !!}
                                </div>
                                @if ($errors->has('individual'))
                                    <div class="error text-danger">{{ $errors->first('individual') }}</div>
                                @endif
                            </div>
                        </div>
                        <br>

                        <legend class="text-uppercase font-size-sm font-weight-bold">GMI Details</legend>

                        <label class="col-form-label">Group Medical Insurance: <span
                                class="text-danger">*</span></label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('gmi_enable', [11 => 'Yes', 10 => 'No'], null, [
                                        'placeholder' => 'Select Option',
                                        'class' => 'form-control select-search',
                                        'id' => 'edit_gmi_enable',
                                        'required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('gmi_enable'))
                                    <div class="error text-danger">{{ $errors->first('gmi_enable') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Type: <span class="text-danger">*</span></label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('individual_or_fam', ['individual' => 'Individual', 'family' => 'Family'], null, [
                                        'placeholder' => 'Select Option',
                                        'class' => 'form-control select-search',
                                        'id' => 'edit_individual_or_fam',
                                        'required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('individual_or_fam'))
                                    <div class="error text-danger">{{ $errors->first('individual_or_fam') }}</div>
                                @endif
                            </div>
                        </div>
                        <br>
                        <span class="editFamilyDiv">
                            <label class="text-uppercase font-size-sm font-weight-bold">Family</label>
                            <br>
                            <div class="row mb-2">
                                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                    <div class="form-check input-group form-check-inline">
                                        <input type="checkbox" name="spouse" id='edit_spouse'
                                            class='form-check-input' {{ '' }}>
                                        <label class="col-form-label">Spouse </label>
                                    </div>
                                    @if ($errors->has('spouse'))
                                        <div class="error text-danger">{{ $errors->first('spouse') }}</div>
                                    @endif
                                </div>
                                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                    <div class="form-check input-group form-check-inline">
                                        <input type="checkbox" name="kid_one" id='edit_kid_one'
                                            class='form-check-input' {{ '' }}>
                                        <label class="col-form-label">Kid One </label>
                                    </div>
                                    @if ($errors->has('kid_one'))
                                        <div class="error text-danger">{{ $errors->first('kid_one') }}</div>
                                    @endif
                                </div>
                                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                    <div class="form-check input-group form-check-inline">
                                        <input type="checkbox" name="kid_two" id='edit_kid_two'
                                            class='form-check-input' {{ '' }}>
                                        <label class="col-form-label">Kid Two </label>
                                    </div>
                                    @if ($errors->has('kid_two'))
                                        <div class="error text-danger">{{ $errors->first('kid_two') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                    <div class="form-check input-group form-check-inline">
                                        <input type="checkbox" name="mom" id='edit_mom'
                                            class='form-check-input' {{ '' }}>
                                        <label class="col-form-label">Mom </label>
                                    </div>
                                    @if ($errors->has('mom'))
                                        <div class="error text-danger">{{ $errors->first('mom') }}</div>
                                    @endif
                                </div>
                                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                    <div class="form-check input-group form-check-inline">
                                        <input type="checkbox" name="dad" id='edit_dad'
                                            class='form-check-input' {{ '' }}>
                                        <label class="col-form-label">Dad </label>
                                    </div>
                                    @if ($errors->has('dad'))
                                        <div class="error text-danger">{{ $errors->first('dad') }}</div>
                                    @endif
                                </div>
                            </div>
                            <br>
                        </span>
                        <label class="col-form-label">Sum Assured:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('gmi_sum_assured', null, [
                                        'placeholder' => 'Enter Amount',
                                        'class' => 'form-control numeric',
                                        'id' => 'edit_gmi_sum_assured',
                                    ]) !!}
                                </div>
                                @if ($errors->has('gmi_sum_assured'))
                                    <div class="error text-danger">{{ $errors->first('gmi_sum_assured') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Hospitality (%):</label>
                        <div class="row mb-2">
                            <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('hospitality_in_perc', null, [
                                        'placeholder' => 'Enter Amount',
                                        'class' => 'form-control numeric',
                                        'id' => 'edit_hospitality_in_perc',
                                    ]) !!}
                                </div>
                                @if ($errors->has('hospitality_in_perc'))
                                    <div class="error text-danger">{{ $errors->first('hospitality_in_perc') }}</div>
                                @endif
                            </div>

                            <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('hospitality_in_amt', null, [
                                        'class' => 'form-control numeric',
                                        'id' => 'edit_hospitality_in_amt',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('hospitality_in_amt'))
                                    <div class="error text-danger">{{ $errors->first('hospitality_in_amt') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Domiciliary (%):</label>
                        <div class="row mb-2">
                            <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('domesticality_in_perc', null, [
                                        'placeholder' => 'Enter Amount',
                                        'class' => 'form-control numeric',
                                        'id' => 'edit_domesticality_in_perc',
                                    ]) !!}
                                </div>
                                @if ($errors->has('domesticality_in_perc'))
                                    <div class="error text-danger">{{ $errors->first('domesticality_in_perc') }}</div>
                                @endif
                            </div>
                            <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('domesticality_in_amt', null, [
                                        'class' => 'form-control numeric',
                                        'id' => 'edit_domesticality_in_amt',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('domesticality_in_amt'))
                                    <div class="error text-danger">{{ $errors->first('domesticality_in_amt') }}</div>
                                @endif
                            </div>
                        </div>

                        <input type="hidden" name="insuranceDetailId" class="insuranceDetailId">

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

        <div class="card viewInsuranceDetail" style="display: none">
            <div class="card-body">
                <div class="form-container">
                    <legend class="text-uppercase font-size-sm font-weight-bold">GPA Details</legend>
                    <form class="updateInsuranceDetail validateUpdateInsuranceDetail">
                        <label class="col-form-label">Company Name:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('company_name', null, [
                                        'class' => 'form-control',
                                        'id' => 'editt_company_name1',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('company_name'))
                                    <div class="error text-danger">{{ $errors->first('company_name') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Group Personal Accident: </label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('gpa_enable', [11 => 'Yes', 10 => 'No'], null, [
                                        'class' => 'form-control select-search',
                                        'id' => 'editt_gpa_enable',
                                        'disabled',
                                    ]) !!}
                                </div>
                                @if ($errors->has('gpa_enable'))
                                    <div class="error text-danger">{{ $errors->first('gpa_enable') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Sum Assured:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('gpa_sum_assured', null, [
                                        'class' => 'form-control numeric',
                                        'id' => 'editt_gpa_sum_assured',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('gpa_sum_assured'))
                                    <div class="error text-danger">{{ $errors->first('gpa_sum_assured') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Medical Coverage:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('medical_coverage', null, [
                                        'class' => 'form-control numeric',
                                        'id' => 'editt_medical_coverage',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('medical_coverage'))
                                    <div class="error text-danger">{{ $errors->first('medical_coverage') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Individual:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('individual', null, [
                                        'class' => 'form-control numeric',
                                        'id' => 'editt_individual',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('individual'))
                                    <div class="error text-danger">{{ $errors->first('individual') }}</div>
                                @endif
                            </div>
                        </div>
                        <br>
                        <legend class="text-uppercase font-size-sm font-weight-bold">GMI Details</legend>

                        <label class="col-form-label">Group Medical Insurance:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('gmi_enable', [11 => 'Yes', 10 => 'No'], null, [
                                        'class' => 'form-control select-search',
                                        'id' => 'editt_gmi_enable',
                                        'disabled',
                                    ]) !!}
                                </div>
                                @if ($errors->has('gmi_enable'))
                                    <div class="error text-danger">{{ $errors->first('gmi_enable') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Type:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('individual_or_fam', ['individual' => 'Individual', 'family' => 'Family'], null, [
                                        'class' => 'form-control select-search',
                                        'id' => 'editt_individual_or_fam',
                                        'disabled',
                                    ]) !!}
                                </div>
                                @if ($errors->has('individual_or_fam'))
                                    <div class="error text-danger">{{ $errors->first('individual_or_fam') }}</div>
                                @endif
                            </div>
                        </div>
                        <br>
                        <span class="edittFamilyDiv" style="display: none;">
                            <label class="text-uppercase font-size-sm font-weight-bold">Family</label>
                            <br>
                            <div class="row mb-2">
                                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                    <div class="form-check input-group form-check-inline">
                                        <input type="checkbox" name="spouse" id='editt_spouse'
                                            class='form-check-input' {{ '' }}>
                                        <label class="col-form-label">Spouse </label>
                                    </div>
                                    @if ($errors->has('spouse'))
                                        <div class="error text-danger">{{ $errors->first('spouse') }}</div>
                                    @endif
                                </div>
                                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                    <div class="form-check input-group form-check-inline">
                                        <input type="checkbox" name="kid_one" id='editt_kid_one'
                                            class='form-check-input' {{ '' }}>
                                        <label class="col-form-label">kid One </label>
                                    </div>
                                    @if ($errors->has('kid_one'))
                                        <div class="error text-danger">{{ $errors->first('kid_one') }}</div>
                                    @endif
                                </div>
                                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                    <div class="form-check input-group form-check-inline">
                                        <input type="checkbox" name="kid_two" id='editt_kid_two'
                                            class='form-check-input' {{ '' }}>
                                        <label class="col-form-label">kid Two </label>
                                    </div>
                                    @if ($errors->has('kid_two'))
                                        <div class="error text-danger">{{ $errors->first('kid_two') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                    <div class="form-check input-group form-check-inline">
                                        <input type="checkbox" name="mom" id='editt_mom'
                                            class='form-check-input' {{ '' }}>
                                        <label class="col-form-label">Mom </label>
                                    </div>
                                    @if ($errors->has('mom'))
                                        <div class="error text-danger">{{ $errors->first('mom') }}</div>
                                    @endif
                                </div>
                                <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                    <div class="form-check input-group form-check-inline">
                                        <input type="checkbox" name="dad" id='editt_dad'
                                            class='form-check-input' {{ '' }}>
                                        <label class="col-form-label">Dad </label>
                                    </div>
                                    @if ($errors->has('dad'))
                                        <div class="error text-danger">{{ $errors->first('dad') }}</div>
                                    @endif
                                </div>
                            </div>
                        </span>
                        <label class="col-form-label">Sum Assured:</label>
                        <div class="row mb-2">
                            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('gmi_sum_assured', null, [
                                        'class' => 'form-control numeric',
                                        'id' => 'editt_gmi_sum_assured',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('gmi_sum_assured'))
                                    <div class="error text-danger">{{ $errors->first('gmi_sum_assured') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Hospitality (%):</label>
                        <div class="row mb-2">
                            <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('hospitality_in_perc', null, [
                                        'class' => 'form-control numeric',
                                        'id' => 'editt_hospitality_in_perc',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('hospitality_in_perc'))
                                    <div class="error text-danger">{{ $errors->first('hospitality_in_perc') }}</div>
                                @endif
                            </div>

                            <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('hospitality_in_amt', null, [
                                        'class' => 'form-control numeric',
                                        'id' => 'editt_hospitality_in_amt',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('hospitality_in_amt'))
                                    <div class="error text-danger">{{ $errors->first('hospitality_in_amt') }}</div>
                                @endif
                            </div>
                        </div>
                        <label class="col-form-label">Domiciliary (%):</label>
                        <div class="row mb-2">
                            <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('domesticality_in_perc', null, [
                                        'class' => 'form-control numeric',
                                        'id' => 'editt_domesticality_in_perc',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('domesticality_in_perc'))
                                    <div class="error text-danger">{{ $errors->first('domesticality_in_perc') }}</div>
                                @endif
                            </div>
                            <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('domesticality_in_amt', null, [
                                        'class' => 'form-control numeric',
                                        'id' => 'editt_domesticality_in_amt',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('domesticality_in_amt'))
                                    <div class="error text-danger">{{ $errors->first('domesticality_in_amt') }}</div>
                                @endif
                            </div>
                        </div>

                        {{-- <input type="hidden" name="insuranceDetailId" class="insuranceDetailId"> --}}

                        <div class="text-center">
                            {{-- <button type="submit"
                                class="ml-2 mt-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                                        class="icon-database-insert"></i></b>Update</button> --}}
                            <a type="submit" href="javascript:void(0)"
                                class="ml-2 mt-2 btn btn-secondary btn-labeled btn-labeled-left float-right go-back"><b><i
                                        class="icon-cancel-circle2"></i></b>Discard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('employee::employee.js.insuranceDetailJsFunction')

<script>
    // $('.select2').select2();
</script>
