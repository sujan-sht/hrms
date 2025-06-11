<script src="{{ asset('admin/validation/createPreviousJobDetail.js') }}"></script>
<script src="{{ asset('admin/validation/editPreviousJobDetail.js') }}"></script>

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Previous Job Details
                        </legend>
                    </div>
                    @if ($menuRoles->assignedRoles('previousJobDetail.save'))
                        @if ($employeeModel->status == 1)
                            <div class="col-1 text-center">
                                <a class="btn btn-sm btn-success rounded-pill createmode" data-name="PreviousJob"><i
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
                                <th>Company Name</th>
                                <th>Functional Title</th>
                                <th>Industry Type</th>
                                <th>Role Key</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                @if ($employeeModel->status == 1)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="previousJobTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 d-none">
        <div class="card createPreviousJobDetail">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Create Previous Job Details</legend>
                <form class="submitPreviousJobDetail validatePreviousJobDetail">
                    <label class="col-form-label">Company Name:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('company_name', null, [
                                    'placeholder' => 'Enter Company Name',
                                    'class' => 'form-control',
                                    'id' => 'company_name',
                                ]) !!}
                            </div>
                            @if ($errors->has('company_name'))
                                <div class="error text-danger">{{ $errors->first('company_name') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Address:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('address', null, [
                                    'placeholder' => 'Enter Address',
                                    'class' => 'form-control',
                                    'id' => 'create_address',
                                ]) !!}
                            </div>
                            @if ($errors->has('address'))
                                <div class="error text-danger">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">From Date:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('from_date', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'from_date',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('from_date'))
                                <div class="error text-danger">{{ $errors->first('from_date') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">To Date:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('to_date', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'to_date',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('to_date'))
                                <div class="error text-danger">{{ $errors->first('to_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Functional Title:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('job_title', null, [
                                    'placeholder' => 'Enter Functional Title',
                                    'class' => 'form-control',
                                    'id' => 'job_title',
                                ]) !!}
                            </div>
                            @if ($errors->has('job_title'))
                                <div class="error text-danger">{{ $errors->first('job_title') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Designation on Joining:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('designation_on_joining', null, [
                                    'placeholder' => 'Enter Designation on Joining',
                                    'class' => 'form-control',
                                    'id' => 'designation_on_joining',
                                ]) !!}
                            </div>
                            @if ($errors->has('designation_on_joining'))
                                <div class="error text-danger">{{ $errors->first('designation_on_joining') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Designation on Leaving:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('designation_on_leaving', null, [
                                    'placeholder' => 'Enter Designation on Leaving',
                                    'class' => 'form-control',
                                    'id' => 'designation_on_leaving',
                                ]) !!}
                            </div>
                            @if ($errors->has('designation_on_leaving'))
                                <div class="error text-danger">{{ $errors->first('designation_on_leaving') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Industry Type:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('industry_type', null, [
                                    'placeholder' => 'Enter Industry Type',
                                    'class' => 'form-control',
                                    'id' => 'industry_type',
                                ]) !!}
                            </div>
                            @if ($errors->has('industry_type'))
                                <div class="error text-danger">{{ $errors->first('industry_type') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Break in Career:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('break_in_career', null, [
                                    'placeholder' => 'Enter Break in Career',
                                    'class' => 'form-control',
                                    'id' => 'break_in_career',
                                ]) !!}
                            </div>
                            @if ($errors->has('break_in_career'))
                                <div class="error text-danger">{{ $errors->first('break_in_career') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Reason for Leaving:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('reason_for_leaving', null, [
                                    'placeholder' => 'Enter Reason for Leaving',
                                    'class' => 'form-control',
                                    'id' => 'reason_for_leaving',
                                ]) !!}
                            </div>
                            @if ($errors->has('reason_for_leaving'))
                                <div class="error text-danger">{{ $errors->first('reason_for_leaving') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Role Key:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('role_key', null, [
                                    'placeholder' => 'Enter Role Key',
                                    'class' => 'form-control',
                                    'id' => 'role_key',
                                ]) !!}
                            </div>
                            @if ($errors->has('role_key'))
                                <div class="error text-danger">{{ $errors->first('role_key') }}</div>
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

        <div class="card editPreviousJobDetail" style="display: none">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Edit Previous Job Details</legend>
                <form class="updatePreviousJobDetail validateUpdatePreviousJobDetail">
                    <label class="col-form-label">Company Name:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('company_name', null, [
                                    'placeholder' => 'Enter Company Name',
                                    'class' => 'form-control edit_company_name',
                                    'id' => 'edit_company_name',
                                ]) !!}
                            </div>
                            @if ($errors->has('company_name'))
                                <div class="error text-danger">{{ $errors->first('company_name') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Address:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('address', null, [
                                    'placeholder' => 'Enter Address',
                                    'class' => 'form-control edit_address',
                                    'id' => 'edit_address',
                                ]) !!}
                            </div>
                            @if ($errors->has('address'))
                                <div class="error text-danger">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">From Date:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('from_date', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single edit_from_date',
                                    'id' => 'edit_from_date',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('from_date'))
                                <div class="error text-danger">{{ $errors->first('from_date') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">To Date:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('to_date', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single edit_to_date',
                                    'id' => 'edit_to_date',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('to_date'))
                                <div class="error text-danger">{{ $errors->first('to_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Functional Title:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('job_title', null, [
                                    'placeholder' => 'Enter Functional Title',
                                    'class' => 'form-control edit_job_title',
                                    'id' => 'edit_job_title',
                                ]) !!}
                            </div>
                            @if ($errors->has('job_title'))
                                <div class="error text-danger">{{ $errors->first('job_title') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Designation on Joining:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('designation_on_joining', null, [
                                    'placeholder' => 'Enter Designation on Joining',
                                    'class' => 'form-control edit_designation_on_joining',
                                    'id' => 'edit_designation_on_joining',
                                ]) !!}
                            </div>
                            @if ($errors->has('designation_on_joining'))
                                <div class="error text-danger">{{ $errors->first('designation_on_joining') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Designation on Leaving:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('designation_on_leaving', null, [
                                    'placeholder' => 'Enter Designation on Leaving',
                                    'class' => 'form-control edit_designation_on_leaving',
                                    'id' => 'edit_designation_on_leaving',
                                ]) !!}
                            </div>
                            @if ($errors->has('designation_on_leaving'))
                                <div class="error text-danger">{{ $errors->first('designation_on_leaving') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Industry Type:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('industry_type', null, [
                                    'placeholder' => 'Enter Industry Type',
                                    'class' => 'form-control edit_industry_type',
                                    'id' => 'edit_industry_type',
                                ]) !!}
                            </div>
                            @if ($errors->has('industry_type'))
                                <div class="error text-danger">{{ $errors->first('industry_type') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Break in Career:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('break_in_career', null, [
                                    'placeholder' => 'Enter Break in Career',
                                    'class' => 'form-control edit_break_in_career',
                                    'id' => 'edit_break_in_career',
                                ]) !!}
                            </div>
                            @if ($errors->has('break_in_career'))
                                <div class="error text-danger">{{ $errors->first('break_in_career') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Reason for Leaving:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('reason_for_leaving', null, [
                                    'placeholder' => 'Enter Reason for Leaving',
                                    'class' => 'form-control edit_reason_for_leaving',
                                    'id' => 'edit_reason_for_leaving',
                                ]) !!}
                            </div>
                            @if ($errors->has('reason_for_leaving'))
                                <div class="error text-danger">{{ $errors->first('reason_for_leaving') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Role Key:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('role_key', null, [
                                    'placeholder' => 'Enter Role Key',
                                    'class' => 'form-control edit_role_key',
                                    'id' => 'edit_role_key',
                                ]) !!}
                            </div>
                            @if ($errors->has('role_key'))
                                <div class="error text-danger">{{ $errors->first('role_key') }}</div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="previousJobDetailId" class="previousJobDetailId">

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
@include('employee::employee.js.previousJobDetailJsFunction')

<script>
    // $('.select2').select2();
</script>
