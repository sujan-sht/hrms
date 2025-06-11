<script src="{{ asset('admin/validation/contractDetail.js') }}"></script>

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Contract Details
                        </legend>
                    </div>
                    @if ($menuRoles->assignedRoles('contractDetail.save'))
                        @if ($employeeModel->status == 1)
                            <div class="col-1 text-center">
                                <a class="btn btn-sm btn-success rounded-pill createmode" data-name="Contract"><i
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
                                <th>Contract Title</th>
                                <th>Start From</th>
                                <th>End To</th>
                                @if ($employeeModel->status == 1)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="contractTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 d-none">
        <div class="card createContractDetail">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Create Contract Details</legend>
                <form class="submitContractDetail validateContractDetail">
                    <label class="col-form-label">Contract Title: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('title', null, [
                                    'placeholder' => 'Enter Contract Title',
                                    'class' => 'form-control',
                                    'id' => 'title',
                                ]) !!}
                            </div>
                            @if ($errors->has('title'))
                                <div class="error text-danger">{{ $errors->first('title') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Start From: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('start_from', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'start_from',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('start_from'))
                                <div class="error text-danger">{{ $errors->first('start_from') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">End To: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('end_to', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'end_to',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('end_to'))
                                <div class="error text-danger">{{ $errors->first('end_to') }}</div>
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

        <div class="card editContractDetail" style="display: none">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Edit Contract Details</legend>
                <form class="updateContractDetail validateUpdateContractDetail">
                    <label class="col-form-label">Contract Title: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('title', null, [
                                    'placeholder' => 'Enter Contract Title',
                                    'class' => 'form-control',
                                    'id' => 'edit_title',
                                ]) !!}
                            </div>
                            @if ($errors->has('title'))
                                <div class="error text-danger">{{ $errors->first('title') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Start From: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('start_from', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'edit_start_from',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('start_from'))
                                <div class="error text-danger">{{ $errors->first('start_from') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">End To: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('end_to', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'edit_end_to',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('end_to'))
                                <div class="error text-danger">{{ $errors->first('end_to') }}</div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="contractDetailId" class="contractDetailId">

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
@include('employee::employee.js.contractDetailJsFunction')

<script>
    // $('.select2').select2();
</script>
