<script src="{{ asset('admin/validation/familyDetail.js') }}"></script>
<script src="{{ asset('admin/validation/editFamilyDetail.js') }}"></script>
<style>
    #ndp-nepali-box {
        top: 221px !important;
    }
</style>

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Family Details
                        </legend>
                    </div>
                    @if ($menuRoles->assignedRoles('familyDetail.save'))
                        @if ($employeeModel->status == 1)
                            <div class="col-1 text-center mt-1">
                                <a class="btn btn-sm btn-success rounded-pill createmode" data-name="Family">Create
                                    New</a>
                            </div>
                        @endif
                    @endif
                </div>


                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>S.N</th>
                                <th>Name</th>
                                <th>Relation</th>
                                <th>Contact No</th>
                                <th>Address</th>
                                @if ($employeeModel->status == 1)
                                    <th width="12%">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="familyTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 d-none">
        {{-- create family detail --}}
        <div class="card createFamilyDetail">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Create family Details</legend>
                <form class="submitFamilyDetail validateFamilyDetail">
                    <label class="col-form-label">Relation:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('relation', $family_relations, null, [
                                    'placeholder' => 'Choose Relation',
                                    'class' => 'form-control select-search1',
                                    'id' => 'relation',
                                ]) !!}
                            </div>
                            @if ($errors->has('relation'))
                                <div class="error text-danger">{{ $errors->first('relation') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Name:<span class="text-danger">*</span></label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('name', null, [
                                            'placeholder' => 'Enter Name',
                                            'class' => 'form-control',
                                            'id' => 'name',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('name'))
                                        <div class="error text-danger">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Date Of Birth:</label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <x-utilities.date-picker :date="null" mode="both" default="nep"
                                        nepDateAttribute="dob" engDateAttribute="eng_dob" />

                                    {{-- <div class="input-group">
                                        {!! Form::text('dob', null, [
                                            'placeholder' => 'Enter DOB',
                                            'class' => 'form-control',
                                            'id' => 'dob-nepali-calendar',
                                        ]) !!}
                                    </div> --}}
                                    @if ($errors->has('dob'))
                                        <div class="error text-danger">{{ $errors->first('dob') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Contact Number:<span class="text-danger">*</span></label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::number('contact_no', null, [
                                            'placeholder' => 'Enter Contact Number',
                                            'class' => 'form-control',
                                            'id' => 'contact_no',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('contact_no'))
                                        <div class="error text-danger">{{ $errors->first('contact_no') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Is Nominee Detail:</label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <div class="input-group">
                                            <input type="radio" name="is_nominee_detail" value="1"
                                                id="is_nominee_detail">&nbsp;Yes
                                            &nbsp;
                                            <input type="radio" name="is_nominee_detail" value="0"
                                                id="is_nominee_detail">&nbsp;No
                                        </div>
                                    </div>
                                    @if ($errors->has('is_nominee_detail'))
                                        <div class="error text-danger">{{ $errors->first('is_nominee_detail') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Is Emergency Contact:</label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <div class="input-group">
                                            <input type="radio" name="is_emergency_contact" id="is_emergency_contact"
                                                value="1">&nbsp;Yes
                                            &nbsp;
                                            <input type="radio" name="is_emergency_contact" id="is_emergency_contact"
                                                value="0">&nbsp;No
                                        </div>
                                    </div>
                                    @if ($errors->has('is_emergency_contact'))
                                        <div class="error text-danger">{{ $errors->first('is_emergency_contact') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Is Dependent:</label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <div class="input-group">
                                            <input type="radio" name="is_dependent" id="is_dependent"
                                                value="1">&nbsp;Yes
                                            &nbsp;
                                            <input type="radio" name="is_dependent" id="is_dependent"
                                                value="0">&nbsp;No
                                        </div>
                                    </div>
                                    @if ($errors->has('is_dependent'))
                                        <div class="error text-danger">{{ $errors->first('is_dependent') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Include In Medical Insurance:</label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <div class="input-group">
                                            <input type="radio" name="include_in_medical_insurance"
                                                id="include_in_medical_insurance" value="1">&nbsp;Yes
                                            &nbsp;
                                            <input type="radio" name="include_in_medical_insurance"
                                                id="include_in_medical_insurance" value="0">&nbsp;No
                                        </div>
                                    </div>
                                    @if ($errors->has('include_in_medical_insurance'))
                                        <div class="error text-danger">
                                            {{ $errors->first('include_in_medical_insurance') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Address:</label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <input type="radio" name="employee_address" id="employee_same"
                                            value="1">&nbsp;
                                        Same as
                                        Employee
                                        &nbsp;
                                        <input type="radio" name="employee_address" id="employee_different"
                                            value="0">&nbsp; Different
                                    </div>

                                    {{-- address list --}}
                                    <div id="family_address" style="display: none;">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <div class="row mt-2">
                                                    <label class="col-form-label col-lg-4">Province:</label>
                                                    <div
                                                        class="col-lg-8 form-group-feedback form-group-feedback-right">
                                                        <div class="input-group">

                                                            {!! Form::select('province_id', $stateList, null, [
                                                                'id' => 'province_id',
                                                                'class' => 'form-control select-search',
                                                                'placeholder' => 'Select Province',
                                                            ]) !!}
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-2" id="districtDiv">
                                                <div class="row">
                                                    <label class="col-form-label col-lg-4">District: </label>

                                                    <div
                                                        class="col-lg-8 form-group-feedback form-group-feedback-right">
                                                        <div class="input-group">
                                                            <select name="district_id" id="district_id"
                                                                class="form-control select-search">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-2">
                                                <div class="row">
                                                    <label class="col-form-label col-lg-4">Municipality/VDC: </label>
                                                    <div
                                                        class="col-lg-8 form-group-feedback form-group-feedback-right">
                                                        <div class="input-group">
                                                            <input type="text" name="municipality"
                                                                id="municipality" class="form-control"
                                                                placeholder="Enter Municipality/VDC">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-2">
                                                <div class="row">
                                                    <label class="col-form-label col-lg-4">Ward No: </label>
                                                    <div
                                                        class="col-lg-8 form-group-feedback form-group-feedback-right">
                                                        <div class="input-group">
                                                            <input type="number" name="ward_no" id="ward_no"
                                                                class="form-control" placeholder="Enter Ward No">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-2">
                                                <div class="row">
                                                    <label class="col-form-label col-lg-4"> Address: </label>
                                                    <div
                                                        class="col-lg-8 form-group-feedback form-group-feedback-right">
                                                        <div class="input-group">
                                                            <input type="text" name="family_address"
                                                                id="address" class="form-control"
                                                                placeholder="Enter Address">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Late Status:</label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <div class="input-group">
                                            <input type="radio" name="late_status" id="late_status"
                                                value="1">&nbsp;Yes
                                            &nbsp;
                                            <input type="radio" name="late_status" id="late_status"
                                                value="0">&nbsp;No
                                        </div>
                                    </div>
                                    @if ($errors->has('late_status'))
                                        <div class="error text-danger">{{ $errors->first('late_status') }}</div>
                                    @endif
                                </div>
                            </div>
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
        {{-- edit family detail --}}
        <div class="card editFamilyDetail" style="display: none">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Edit family Details</legend>

                <form class="updateFamilyDetail validateUpdateFamilyDetail">
                    <label class="col-form-label">Relation:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('editrelation', $family_relations, null, [
                                    'placeholder' => 'Choose Relation',
                                    'class' => 'form-control select-search1',
                                    'id' => 'editrelation',
                                ]) !!}
                            </div>
                            @if ($errors->has('relation'))
                                <div class="error text-danger">{{ $errors->first('relation') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Name:<span class="text-danger">*</span></label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('editname', null, [
                                            'placeholder' => 'Enter Name',
                                            'class' => 'form-control',
                                            'id' => 'editname',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('name'))
                                        <div class="error text-danger">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Date Of Birth:</label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <x-utilities.date-picker :date="null" mode="both" default="nep"
                                        nepDateAttribute="editdob" engDateAttribute="eng_editdob" />
                                    {{-- <div class="input-group">
                                        {!! Form::text('editdob', null, [
                                            'placeholder' => 'Enter DOB',
                                            'class' => 'form-control edit-dob-nepali-calendar',
                                            'id' => 'editdob',
                                        ]) !!}
                                    </div> --}}
                                    @if ($errors->has('dob'))
                                        <div class="error text-danger">{{ $errors->first('dob') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Contact Number:<span class="text-danger">*</span></label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('editcontact_no', null, [
                                            'placeholder' => 'Enter Contact Number',
                                            'class' => 'form-control',
                                            'id' => 'editcontact_no',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('contact_no'))
                                        <div class="error text-danger">{{ $errors->first('contact_no') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Is Nominee Detail:</label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <div class="input-group">
                                            <input type="radio" name="is_nominee_detail" value="1"
                                                id="edit_is_nominee_detail">&nbsp;Yes
                                            &nbsp;
                                            <input type="radio" name="is_nominee_detail" value="0"
                                                id="edit_is_nominee_detail">&nbsp;No
                                        </div>
                                    </div>
                                    @if ($errors->has('is_nominee_detail'))
                                        <div class="error text-danger">{{ $errors->first('is_nominee_detail') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Is Emergency Contact:</label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <div class="input-group">
                                            <input type="radio" name="is_emergency_contact"
                                                id="edit_is_emergency_contact" value="1">&nbsp;Yes
                                            &nbsp;
                                            <input type="radio" name="is_emergency_contact"
                                                id="edit_is_emergency_contact" value="0">&nbsp;No
                                        </div>
                                    </div>
                                    @if ($errors->has('is_emergency_contact'))
                                        <div class="error text-danger">{{ $errors->first('is_emergency_contact') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Is Dependent:</label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <div class="input-group">
                                            <input type="radio" name="is_dependent" id="edit_is_dependent"
                                                value="1">&nbsp;Yes
                                            &nbsp;
                                            <input type="radio" name="is_dependent" id="edit_is_dependent"
                                                value="0">&nbsp;No
                                        </div>
                                    </div>
                                    @if ($errors->has('is_dependent'))
                                        <div class="error text-danger">{{ $errors->first('is_dependent') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Include In Medical Insurance:</label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <div class="input-group">
                                            <input type="radio" name="include_in_medical_insurance"
                                                id="edit_include_in_medical_insurance" value="1">&nbsp;Yes
                                            &nbsp;
                                            <input type="radio" name="include_in_medical_insurance"
                                                id="edit_include_in_medical_insurance" value="0">&nbsp;No
                                        </div>
                                    </div>
                                    @if ($errors->has('include_in_medical_insurance'))
                                        <div class="error text-danger">
                                            {{ $errors->first('include_in_medical_insurance') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Address:</label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <input type="radio" name="employee_address" id="edit_employee_same"
                                            value="1" checked>&nbsp;
                                        Same as
                                        Employee
                                        &nbsp;
                                        <input type="radio" name="employee_address" id="edit_employee_different"
                                            value="0">&nbsp; Different
                                    </div>
                                    {{-- address list --}}
                                    <div id="edit_family_address" style="display: none;">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <div class="row mt-2">
                                                    <label class="col-form-label col-lg-4">Province:</label>
                                                    <div
                                                        class="col-lg-8 form-group-feedback form-group-feedback-right">
                                                        <div class="input-group">

                                                            {!! Form::select('province_id', $stateList, null, [
                                                                'id' => 'edit_province_id',
                                                                'class' => 'form-control select-search',
                                                                'placeholder' => 'Select Province',
                                                            ]) !!}
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-2" id="districtDiv">
                                                <div class="row">
                                                    <label class="col-form-label col-lg-4">District: </label>

                                                    <div
                                                        class="col-lg-8 form-group-feedback form-group-feedback-right">
                                                        <div class="input-group">
                                                            <select name="district_id" id="edit_district_id"
                                                                class="form-control edit_district_id">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-2">
                                                <div class="row">
                                                    <label class="col-form-label col-lg-4">Municipality/VDC: </label>
                                                    <div
                                                        class="col-lg-8 form-group-feedback form-group-feedback-right">
                                                        <div class="input-group">
                                                            <input type="text" name="municipality"
                                                                id="edit_municipality" class="form-control"
                                                                placeholder="Enter Municipality/VDC">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-2">
                                                <div class="row">
                                                    <label class="col-form-label col-lg-4">Ward No: </label>
                                                    <div
                                                        class="col-lg-8 form-group-feedback form-group-feedback-right">
                                                        <div class="input-group">
                                                            <input type="number" name="ward_no" id="edit_ward_no"
                                                                class="form-control" placeholder="Enter Ward No">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-2">
                                                <div class="row">
                                                    <label class="col-form-label col-lg-4"> Address: </label>
                                                    <div
                                                        class="col-lg-8 form-group-feedback form-group-feedback-right">
                                                        <div class="input-group">
                                                            <input type="text" name="family_address"
                                                                id="edit_address" class="form-control"
                                                                placeholder="Enter Address">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label class="col-form-label">Late Status:</label>
                            <div class="row mb-2">
                                <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        <div class="input-group">
                                            <input type="radio" name="late_status" id="edit_late_status"
                                                value="1">&nbsp;Yes
                                            &nbsp;
                                            <input type="radio" name="late_status" id="edit_late_status"
                                                value="0">&nbsp;No
                                        </div>
                                    </div>
                                    @if ($errors->has('late_status'))
                                        <div class="error text-danger">{{ $errors->first('late_status') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="memberId" class="memberId">
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
    </div>
</div>

@include('employee::employee.js.familyDetailJsFunction')
<script>
    $(document).ready(function() {

        $("#dob-nepali-calendar").nepaliDatePicker({
            parentEl: '.createFamilyDetail',
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 10
        });

        $(".edit-dob-nepali-calendar").nepaliDatePicker({
            parentEl: '.editFamilyDetail',
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 10
        });
    })
</script>
<script>
    $(document).ready(function() {

        // Listen for changes on the radio buttons
        $('input[name="employee_address"]').change(function() {
            if ($('#employee_different').is(':checked')) {
                $('#family_address').show(); // Show if "Different" is checked
            } else {
                $('#family_address').hide(); // Hide otherwise
            }

            if ($('#edit_employee_different').is(':checked')) {
                $('#edit_family_address').show(); // Show if "Different" is checked
            } else {
                $('#edit_family_address').hide(); // Hide otherwise
                $('#edit_family_address').val('');

            }

        });
    })
</script>

<script>
    $("#province_id").on('change', function() {
        $('#districtDiv').css('display', 'block');

        var provinceId = $(this).val();
        var $closestDistrictSelect = $(this).closest('.form-group').find('#district_id');
        if (provinceId !== '') {
            $.ajax({
                url: '{{ route('event.get-districts') }}',
                method: 'GET',
                data: {
                    province_id: provinceId
                },
                success: function(response) {
                    $closestDistrictSelect.empty();
                    $.each(response, function(key, district) {
                        $closestDistrictSelect.append($('<option>', {
                            value: key,
                            text: district
                        }));
                    });
                    $closestDistrictSelect.multiselect('rebuild');
                }
            });
        } else {
            $closestDistrictSelect.empty();
        }
    });


    $("#edit_province_id").on('change', function() {
        $('#districtDiv').css('display', 'block');
        // alert('here')
        var provinceId = $(this).val();
        var $closestDistrictSelect = $(this).closest('.form-group').find('#edit_district_id');
        if (provinceId !== '') {
            $.ajax({
                url: '{{ route('event.get-districts') }}',
                method: 'GET',
                data: {
                    province_id: provinceId
                },
                success: function(response) {
                    $closestDistrictSelect.empty();
                    $.each(response, function(key, district) {
                        $closestDistrictSelect.append($('<option>', {
                            value: key,
                            text: district
                        }));
                    });
                    $closestDistrictSelect.multiselect('rebuild');
                }
            });
        } else {
            $closestDistrictSelect.empty();
        }
    });
</script>
