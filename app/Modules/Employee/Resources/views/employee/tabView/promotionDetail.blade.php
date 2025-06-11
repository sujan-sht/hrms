@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Promotion History</legend>
                    </div>
                    {{-- @if ($menuRoles->assignedRoles('employeeTransfer.create'))
                        @if ($employeeModel->status == 1)
                            <div class="col-1 text-center mt-1">
                                <a class="btn btn-sm btn-success rounded-pill createmode" data-name="Asset"><i class="icon-plus2"></i> Add</a>
                            </div>
                        @endif
                    @endif --}}
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                {{-- <th>S.N</th>
                                <th>Transfer Date</th>
                                <th>Organization (Source)</th>
                                <th>Organization (Destination)</th>
                                <th>Remarks</th>
                                @if ($employeeModel->status == 1)
                                    <th width="12%">Action</th>
                                @endif --}}

                                <th>S.N</th>
                                <th>Organization</th>
                                <th>Unit</th>
                                <th>Sub-Function</th>
                                <th>Grade</th>
                                <th>Designation</th>
                                <th>Functional Title</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody class="employeePromotionTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="col-md-4 d-none">
        <div class="card createAssetDetail">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Details</legend>
                <form class="submitCreateForm validateAssetDetail">

                    {!! Form::hidden('employee_id', $employeeModel->id, ['id' => 'employeeId']) !!}

                    <label class="col-form-label">Transfer Date :<span class="text-danger"> *</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('transfer_date', null, [
                                    'placeholder' => 'YYYY-MM-DD',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'transferDate',
                                    'readonly'
                                ]) !!}
                            </div>
                            @if ($errors->has('transfer_date'))
                                <div class="error text-danger">{{ $errors->first('transfer_date') }}</div>
                            @endif
                        </div>
                    </div>

                    {!! Form::hidden('from_org_id', $employeeModel->organization_id, ['id' => 'fromOrganizationId']) !!}

                    <label class="col-form-label">Organization (Destination) :<span class="text-danger"> *</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('to_org_id', $transferOrganizationList, null, [
                                    'placeholder' => 'Choose Organization',
                                    'class' => 'form-control select-search',
                                    'id' => 'toOrganizationId'
                                ]) !!}
                            </div>
                            @if ($errors->has('to_org_id'))
                                <div class="error text-danger">{{ $errors->first('to_org_id') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Remarks :</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('remarks', null, [
                                    'rows' => 6,
                                    'placeholder' => 'Enter remarks',
                                    'class' => 'form-control',
                                    'id' => 'remarks'
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <!-- <label class="col-form-label">Status :</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('status', $transferStatusList, null, [
                                    'class' => 'form-control select-search',
                                    'id' => 'status'
                                ]) !!}
                            </div>
                        </div>
                    </div> -->

                    <div class="text-center">
                        <button type="submit" class="ml-2 mt-2 btn btn-success btn-labeled btn-labeled-left float-right">
                            <b><i class="icon-database-insert"></i></b>Create
                        </button>
                        <a type="submit" href="javascript:void(0)" class="ml-2 mt-2 btn btn-secondary btn-labeled btn-labeled-left float-right go-back">
                            <b><i class="icon-cancel-circle2"></i></b>Discard
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card editAssetDetail" style="display: none">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Details</legend>
                <form class="submitUpdateForm validateUpdateAssetDetail">

                    {!! Form::hidden('id', null, ['id' => 'primaryId']) !!}
                    {!! Form::hidden('employee_id', $employeeModel->id, ['id' => 'employeeId']) !!}

                    <label class="col-form-label">Transfer Date :<span class="text-danger"> *</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('transfer_date', null, [
                                    'placeholder' => 'YYYY-MM-DD',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'transferDate',
                                    'readonly'
                                ]) !!}
                            </div>
                            @if ($errors->has('transfer_date'))
                                <div class="error text-danger">{{ $errors->first('transfer_date') }}</div>
                            @endif
                        </div>
                    </div>

                    {!! Form::hidden('from_org_id', $employeeModel->organization_id, ['id' => 'fromOrganizationId']) !!}

                    <label class="col-form-label">Organization (Destination) :<span class="text-danger"> *</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('to_org_id', $transferOrganizationList, null, [
                                    'placeholder' => 'Choose Organization',
                                    'class' => 'form-control select-search',
                                    'id' => 'toOrganizationId'
                                ]) !!}
                            </div>
                            @if ($errors->has('to_org_id'))
                                <div class="error text-danger">{{ $errors->first('to_org_id') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Remarks :</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('remarks', null, [
                                    'rows' => 6,
                                    'placeholder' => 'Enter remarks',
                                    'class' => 'form-control',
                                    'id' => 'remarks'
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <!-- <label class="col-form-label">Status :</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('status', $transferStatusList, null, [
                                    'class' => 'form-control select-search',
                                    'id' => 'status'
                                ]) !!}
                            </div>
                        </div>
                    </div> -->

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

@include('employee::employee.js.promotionDetailJsFunction')

{{-- <script src="{{ asset('admin/validation/employee-transfer.js') }}"></script> --}}
