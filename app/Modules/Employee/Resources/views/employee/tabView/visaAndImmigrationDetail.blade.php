<script src="{{ asset('admin/validation/visaAndImmigrationDetail.js') }}"></script>

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Visa/Immigration Doc Details
                        </legend>
                    </div>
                    @if ($menuRoles->assignedRoles('visaAndImmigrationDetail.save'))
                        @if ($employeeModel->status == 1)
                            <div class="col-1 text-center">
                                <a class="btn btn-sm btn-success rounded-pill createmode"
                                    data-name="VisaAndImmigration"><i class="icon-plus2"></i> Add</a>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>S.N</th>
                                <th>Country</th>
                                <th>Type</th>
                                <th>Issued Date</th>
                                <th>Expiry Date</th>
                                <th>Document Number</th>
                                <th>Remarks</th>
                                @if ($employeeModel->status == 1)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="visaAndImmigrationTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 d-none">
        <div class="card createVisaAndImmigrationDetail">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Create Visa/Immigration Doc Details
                </legend>
                <form class="submitVisaAndImmigrationDetail validateVisaAndImmigrationDetail">
                    <label class="col-form-label">Country: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('country', $countryList, null, [
                                    'placeholder' => 'Select Country',
                                    'class' => 'form-control select-search',
                                    'id' => 'country',
                                ]) !!}
                            </div>
                            @if ($errors->has('country'))
                                <div class="error text-danger">{{ $errors->first('country') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Type: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('visa_type', null, [
                                    'placeholder' => 'Enter Type',
                                    'class' => 'form-control',
                                    'id' => 'visa_type',
                                ]) !!}
                            </div>
                            @if ($errors->has('visa_type'))
                                <div class="error text-danger">{{ $errors->first('visa_type') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Issued Date: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('issued_date', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'issued_date',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('issued_date'))
                                <div class="error text-danger">{{ $errors->first('issued_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Expiry Date:</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('visa_expiry_date', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'visa_expiry_date',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('visa_expiry_date'))
                                <div class="error text-danger">{{ $errors->first('visa_expiry_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Document Number:</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('passport_number', null, [
                                    'placeholder' => 'Enter Document Number',
                                    'class' => 'form-control',
                                    'id' => 'passport_number',
                                ]) !!}
                            </div>
                            @if ($errors->has('passport_number'))
                                <div class="error text-danger">{{ $errors->first('passport_number') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Remarks:</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('note', null, [
                                    'placeholder' => 'Enter Remarks',
                                    'class' => 'form-control',
                                    'id' => 'remarks',
                                    // 'id' => 'editor-full',
                                ]) !!}
                            </div>
                            @if ($errors->has('note'))
                                <div class="error text-danger">{{ $errors->first('note') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit"
                            class="ml-2 mt-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                                    class="icon-database-insert"></i></b>Save
                        </button>
                        <a type="submit" href="javascript:void(0)"
                            class="ml-2 mt-2 btn btn-secondary btn-labeled btn-labeled-left float-right go-back"><b><i
                                    class="icon-cancel-circle2"></i></b>Discard</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card editVisaAndImmigrationDetail" style="display: none">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Edit Visa/Immigration Doc Details</legend>
                <form class="updateVisaAndImmigrationDetail validateUpdateVisaAndImmigrationDetail">
                    <label class="col-form-label">Country: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('country', $countryList, null, [
                                    'placeholder' => 'Select Country',
                                    'class' => 'form-control select-search',
                                    'id' => 'edit_country',
                                ]) !!}
                            </div>
                            @if ($errors->has('country'))
                                <div class="error text-danger">{{ $errors->first('country') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Type: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('visa_type', null, [
                                    'placeholder' => 'Enter Type',
                                    'class' => 'form-control',
                                    'id' => 'edit_visa_type',
                                ]) !!}
                            </div>
                            @if ($errors->has('visa_type'))
                                <div class="error text-danger">{{ $errors->first('visa_type') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Issued Date: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('issued_date', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'edit_issued_date',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('issued_date'))
                                <div class="error text-danger">{{ $errors->first('issued_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Expiry Date: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('visa_expiry_date', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'edit_visa_expiry_date',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('visa_expiry_date'))
                                <div class="error text-danger">{{ $errors->first('visa_expiry_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Document Number:</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('passport_number', null, [
                                    'placeholder' => 'Enter Document Number',
                                    'class' => 'form-control',
                                    'id' => 'edit_passport_number',
                                ]) !!}
                            </div>
                            @if ($errors->has('passport_number'))
                                <div class="error text-danger">{{ $errors->first('passport_number') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Remarks:</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('note', null, [
                                    'placeholder' => 'Enter Remarks',
                                    'class' => 'form-control',
                                    'id' => 'edit_remarks',
                                    // 'id' => 'edit_note_visa_immig',
                                    // 'id' => 'editor-full',
                                ]) !!}
                            </div>
                            @if ($errors->has('note'))
                                <div class="error text-danger">{{ $errors->first('note') }}</div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="visaAndImmigrationDetailId" class="visaAndImmigrationDetailId">

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
@include('employee::employee.js.visaAndImmigrationDetailJsFunction')
