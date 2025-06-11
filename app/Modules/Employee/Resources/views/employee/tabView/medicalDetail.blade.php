{{-- <script src="{{ asset('admin/validation/medicalDetail.js') }}"></script> --}}

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Medical Details
                        </legend>
                    </div>
                    @if ($menuRoles->assignedRoles('medicalDetail.save'))
                        @if ($employeeModel->status == 1)
                            <div class="col-1 text-center">
                                <a class="btn btn-sm btn-success rounded-pill createmode" data-name="Medical"><i
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
                                <th>Medical Problem</th>
                                <th>Details</th>
                                <th>Insurance Company Name</th>
                                <th>Insurance Details</th>
                                @if ($employeeModel->status == 1)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="medicalTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 d-none">
        <div class="card createMedicalDetail">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Create Medical Details</legend>
                <form class="submitMedicalDetail validateMedicalDetail">
                    <label class="col-form-label">Specify Medical Problem: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('medical_problem', null, [
                                    'placeholder' => 'Enter Medical Problem',
                                    'class' => 'form-control',
                                    'id' => 'medical_problem',
                                ]) !!}
                            </div>
                            @if ($errors->has('medical_problem'))
                                <div class="error text-danger medical_problem">{{ $errors->first('medical_problem') }}
                                </div>
                            @endif
                            <div class="error text-danger medical_problem"></div>

                        </div>
                    </div>
                    <label class="col-form-label">How do you take care of that medical problem? In a medical emergency
                        if you consume certain medicines Please Let Us Know: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('details', null, [
                                    'placeholder' => 'Enter Details',
                                    'class' => 'form-control',
                                    'id' => 'details',
                                ]) !!}
                            </div>
                            @if ($errors->has('details'))
                                <div class="error text-danger">{{ $errors->first('details') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Do you have Personal Insurance?(If Yes Provide Details): </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('insurance_company_name', null, [
                                    'placeholder' => 'Enter Insurance Company Name',
                                    'class' => 'form-control',
                                    'id' => 'insurance_company_name',
                                ]) !!}
                            </div>
                            @if ($errors->has('insurance_company_name'))
                                <div class="error text-danger">{{ $errors->first('insurance_company_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('medical_insurance_details', null, [
                                    'placeholder' => 'Enter Medical Insurance Details',
                                    'class' => 'form-control',
                                    'id' => 'medical_insurance_details',
                                ]) !!}
                            </div>
                            @if ($errors->has('medical_insurance_details'))
                                <div class="error text-danger">{{ $errors->first('medical_insurance_details') }}</div>
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

        <div class="card editMedicalDetail" style="display: none">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Edit Medical Details</legend>
                <form class="updateMedicalDetail validateUpdateMedicalDetail">
                    <label class="col-form-label">Specify Medical Problem:</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('medical_problem', null, [
                                    'placeholder' => 'Enter Medical Problem',
                                    'class' => 'form-control',
                                    'id' => 'edit_medical_problem',
                                ]) !!}
                            </div>
                            @if ($errors->has('medical_problem'))
                                <div class="error text-danger">{{ $errors->first('medical_problem') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">How do you take care of that medical problem? In a medical emergency
                        if you consume certain medicines Please Let Us Know: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('details', null, [
                                    'placeholder' => 'Enter Details',
                                    'class' => 'form-control',
                                    'id' => 'edit_details',
                                ]) !!}
                            </div>
                            @if ($errors->has('details'))
                                <div class="error text-danger">{{ $errors->first('details') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Do you have Personal Insurance?(If Yes Provide Details): </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('insurance_company_name', null, [
                                    'placeholder' => 'Enter Insurance Company Name',
                                    'class' => 'form-control',
                                    'id' => 'edit_insurance_company_name',
                                ]) !!}
                            </div>
                            @if ($errors->has('insurance_company_name'))
                                <div class="error text-danger">{{ $errors->first('insurance_company_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('medical_insurance_details', null, [
                                    'placeholder' => 'Enter Medical Insurance Details',
                                    'class' => 'form-control',
                                    'id' => 'edit_medical_insurance_details',
                                ]) !!}
                            </div>
                            @if ($errors->has('medical_insurance_details'))
                                <div class="error text-danger">{{ $errors->first('medical_insurance_details') }}</div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="medicalDetailId" class="medicalDetailId">

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
@include('employee::employee.js.medicalDetailJsFunction')
