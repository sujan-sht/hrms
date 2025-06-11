{{-- <script src="{{ asset('admin/validation/medicalDetail.js') }}"></script> --}}
@inject('holdPayment', '\App\Modules\Payroll\Entities\HoldPayment')

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Payroll Details
                        </legend>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>S.N</th>
                                <th>Year</th>
                                <th>Month</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="payrollTable">
                            @if(count($payrollModels) > 0)
                                @foreach($payrollModels as $key => $value)
                                @php
                                  $hold = $holdPayment->getHold($value->employee_id,$value->payroll->year,$value->payroll->month);
                                @endphp
                                    @if(count($hold) == 0)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{optional($value->payroll)->year}}</td>
                                            <td> {{optional($value->payroll)->calendar_type == 'nep' ? date_converter()->_get_nepali_month(optional($value->payroll)->month) : date_converter()->_get_english_month(optional($value->payroll)->month)}}</td>
                                            <td>
                                                <a href="{{ route('payroll.employee.salary.slip', $value->id) }}" class="btn btn-sm btn-outline-secondary btn-icon updateStatus mx-1" data-popup="tooltip" data-placement="top" data-original-title="Print">
                                                    <i class="icon-printer"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="col-md-4 d-none">
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
                                    'class' => 'form-control basicTinymce',
                                    'id' => 'medical_problem'
                                ]) !!}
                            </div>
                            @if ($errors->has('medical_problem'))
                                <div class="error text-danger medical_problem">{{ $errors->first('medical_problem') }}</div>
                            @endif
                            <div class="error text-danger medical_problem"></div>

                        </div>
                    </div>
                    <label class="col-form-label">How do you take care of that medical problem? In a medical emergency if you consume certain medicines Please Let Us Know: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('details', null, [
                                    'placeholder' => 'Enter Details',
                                    'class' => 'form-control basicTinymce',
                                    'id' => 'details'
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
                                    'id' => 'insurance_company_name'
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
                                    'class' => 'form-control basicTinymce',
                                    'id' => 'medical_insurance_details'
                                ]) !!}
                            </div>
                            @if ($errors->has('medical_insurance_details'))
                                <div class="error text-danger">{{ $errors->first('medical_insurance_details') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit"
                            class="ml-2 mt-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i class="icon-database-insert"></i></b>Save
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
                                    'class' => 'form-control basicTinymce',
                                    'id' => 'edit_medical_problem'
                                ]) !!}
                            </div>
                            @if ($errors->has('medical_problem'))
                                <div class="error text-danger">{{ $errors->first('medical_problem') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">How do you take care of that medical problem? In a medical emergency if you consume certain medicines Please Let Us Know: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('details', null, [
                                    'placeholder' => 'Enter Details',
                                    'class' => 'form-control basicTinymce',
                                    'id' => 'edit_details'
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
                                    'id' => 'edit_insurance_company_name'
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
                                    'class' => 'form-control basicTinymce',
                                    'id' => 'edit_medical_insurance_details'
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
    </div> --}}
</div>
{{-- @include('employee::employee.js.medicalDetailJsFunction') --}}
