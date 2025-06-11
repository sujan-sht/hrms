<script src="{{ asset('admin/validation/createAssetDetail.js') }}"></script>
<script src="{{ asset('admin/validation/editAssetDetail.js') }}"></script>

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Asset Details
                        </legend>
                    </div>
                    {{-- @if ($menuRoles->assignedRoles('assetDetail.save'))
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
                                <th>S.N</th>
                                <th>Asset</th>
                                <th>Quantity</th>
                                <th>Allocated By</th>
                                <th>Allocated Date</th>
                                <th>Return Date</th>
                                <th>NOC</th>

                                {{-- @if ($menuRoles->assignedRoles('cheatSheet.edit')) --}}

                                {{-- @if ($employeeModel->status == 1)
                                    <th width="12%">Action</th>
                                    @endif --}}
                                {{-- @endif --}}
                            </tr>
                        </thead>
                        <tbody class="assetTable">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 d-none">
        <div class="card createAssetDetail">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Create Asset Details</legend>
                <form class="submitAssetDetail validateAssetDetail">
                    <label class="col-form-label">Asset Type:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('assetType', $asset_types, null, [
                                    'placeholder' => 'Choose Asset Type',
                                    'class' => 'form-control select-search',
                                    'id' => 'assetType',
                                ]) !!}
                            </div>
                            @if ($errors->has('asset_type'))
                                <div class="error text-danger">{{ $errors->first('asset_type') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Asset Details:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('assetDetail', null, [
                                    'placeholder' => 'Enter Asset Details',
                                    'class' => 'form-control',
                                    'id' => 'assetDetail',
                                ]) !!}
                            </div>
                            @if ($errors->has('asset_detail'))
                                <div class="error text-danger">{{ $errors->first('asset_detail') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Given Date:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('givenDate', null, [
                                    'placeholder' => 'Choose Given Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'givenDate',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('given_date'))
                                <div class="error text-danger">{{ $errors->first('given_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Return Date:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('returnDate', null, [
                                    'placeholder' => 'Choose Given Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'returnDate',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('return_date'))
                                <div class="error text-danger">{{ $errors->first('return_date') }}</div>
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

        <div class="card editAssetDetail" style="display: none">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Edit Asset Details</legend>
                <form class="updateAssetDetail validateUpdateAssetDetail">
                    <label class="col-form-label">Asset Type:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('editAssetType', $asset_types, null, [
                                    'placeholder' => 'Choose Asset Type',
                                    'class' => 'form-control select-search',
                                    'id' => 'editAssetType',
                                ]) !!}
                            </div>
                            @if ($errors->has('asset_type'))
                                <div class="error text-danger">{{ $errors->first('asset_type') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Asset Details:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('editAssetDetail', null, [
                                    'placeholder' => 'Enter Asset Details',
                                    'class' => 'form-control',
                                    'id' => 'editAssetDetail',
                                ]) !!}
                            </div>
                            @if ($errors->has('asset_detail'))
                                <div class="error text-danger">{{ $errors->first('asset_detail') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Given Date:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('editGivenDate', null, [
                                    'placeholder' => 'Choose Given Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'editGivenDate',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('given_date'))
                                <div class="error text-danger">{{ $errors->first('given_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Return Date:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('editReturnDate', null, [
                                    'placeholder' => 'Choose Given Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'editReturnDate',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('return_date'))
                                <div class="error text-danger">{{ $errors->first('return_date') }}</div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="assetDetailId" class="assetDetailId">

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
@include('employee::employee.js.assetDetailJsFunction')
