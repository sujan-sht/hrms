<script src="{{ asset('admin/validation/bankDetail.js') }}"></script>

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Bank Details
                        </legend>
                    </div>
                    @if ($menuRoles->assignedRoles('bankDetail.save'))
                        @if ($employeeModel->status == 1)
                            <div class="col-1 text-center">
                                <a class="btn btn-sm btn-success rounded-pill createmode" data-name="Bank"><i
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
                                <th>Bank Name</th>
                                <th>Bank Code</th>
                                <th>Bank Address</th>
                                <th>Bank Unit</th>
                                <th>Account Type</th>
                                <th>Account Number</th>
                                @if ($employeeModel->status == 1)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bankTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 d-none">
        <div class="card createBankDetail">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Create Bank Details</legend>
                <form class="submitBankDetail validateBankDetail">
                    <label class="col-form-label">Bank Name: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('bank_name', $bank_names, null, [
                                    'placeholder' => 'Select Bank',
                                    'class' => 'form-control select-search',
                                    'id' => 'bank_name',
                                ]) !!}
                            </div>
                            @if ($errors->has('bank_name'))
                                <div class="error text-danger">{{ $errors->first('bank_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Bank Code: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('bank_code', null, [
                                    'placeholder' => 'Enter Bank Code',
                                    'class' => 'form-control',
                                    'id' => 'bank_code',
                                ]) !!}
                            </div>
                            @if ($errors->has('bank_code'))
                                <div class="error text-danger">{{ $errors->first('bank_code') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Bank Address: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('bank_address', null, [
                                    'placeholder' => 'Enter Bank Address',
                                    'class' => 'form-control',
                                    'id' => 'bank_address',
                                ]) !!}
                            </div>
                            @if ($errors->has('bank_address'))
                                <div class="error text-danger">{{ $errors->first('bank_address') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Bank Unit: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('bank_branch', null, [
                                    'placeholder' => 'Enter Unit Unit',
                                    'class' => 'form-control',
                                    'id' => 'bank_branch',
                                ]) !!}
                            </div>
                            @if ($errors->has('bank_branch'))
                                <div class="error text-danger">{{ $errors->first('bank_branch') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Account Type: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('account_type', $account_types, null, [
                                    'placeholder' => 'Choose Account Type',
                                    'class' => 'form-control select-search',
                                    'id' => 'account_type',
                                ]) !!}
                            </div>
                            @if ($errors->has('account_type'))
                                <div class="error text-danger">{{ $errors->first('account_type') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Account Number: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('account_number', null, [
                                    'placeholder' => 'Enter Account Number',
                                    'class' => 'form-control',
                                    'id' => 'account_number',
                                ]) !!}
                            </div>
                            @if ($errors->has('account_number'))
                                <div class="error text-danger">{{ $errors->first('account_number') }}</div>
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

        <div class="card editBankDetail" style="display: none">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Edit Bank Details</legend>
                <form class="updateBankDetail validateUpdateBankDetail">
                    <label class="col-form-label">Bank Name: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('bank_name', $bank_names, null, [
                                    'placeholder' => 'Select Bank',
                                    'class' => 'form-control select-search',
                                    'id' => 'edit_bank_name',
                                ]) !!}
                            </div>
                            @if ($errors->has('bank_name'))
                                <div class="error text-danger">{{ $errors->first('bank_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Bank Code: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('bank_code', null, [
                                    'placeholder' => 'Enter Bank Code',
                                    'class' => 'form-control',
                                    'id' => 'edit_bank_code',
                                ]) !!}
                            </div>
                            @if ($errors->has('bank_code'))
                                <div class="error text-danger">{{ $errors->first('bank_code') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Bank Address: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('bank_address', null, [
                                    'placeholder' => 'Enter Bank Address',
                                    'class' => 'form-control',
                                    'id' => 'edit_bank_address',
                                ]) !!}
                            </div>
                            @if ($errors->has('bank_address'))
                                <div class="error text-danger">{{ $errors->first('bank_address') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Bank Unit: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('bank_branch', null, [
                                    'placeholder' => 'Enter Unit Unit',
                                    'class' => 'form-control',
                                    'id' => 'edit_bank_branch',
                                ]) !!}
                            </div>
                            @if ($errors->has('bank_branch'))
                                <div class="error text-danger">{{ $errors->first('bank_branch') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Account Type: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('account_type', $account_types, null, [
                                    'placeholder' => 'Choose Account Type',
                                    'class' => 'form-control select-search',
                                    'id' => 'edit_account_type1',
                                ]) !!}
                            </div>
                            @if ($errors->has('account_type'))
                                <div class="error text-danger">{{ $errors->first('account_type') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Account Number: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('account_number', null, [
                                    'placeholder' => 'Enter Account Number',
                                    'class' => 'form-control',
                                    'id' => 'edit_account_number',
                                ]) !!}
                            </div>
                            @if ($errors->has('account_number'))
                                <div class="error text-danger">{{ $errors->first('account_number') }}</div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="bankDetailId" class="bankDetailId">

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
@include('employee::employee.js.bankDetailJsFunction')

<script>
    // $('.select2').select2();
</script>
