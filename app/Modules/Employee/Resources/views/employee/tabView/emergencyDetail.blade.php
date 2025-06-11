<script src="{{ asset('admin/validation/createEmergencyDetail.js') }}"></script>
<script src="{{ asset('admin/validation/editEmergencyDetail.js') }}"></script>

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Emergency Details
                        </legend>
                    </div>
                    {{-- @if ($menuRoles->assignedRoles('emergencyDetail.save'))
                        @if ($employeeModel->status == 1)
                            <div class="col-1 text-center">
                                <a class="btn btn-sm btn-success rounded-pill createmode" data-name="Emergency"><i class="icon-plus2"></i> Add</a>
                            </div>
                        @endif
                    @endif --}}
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
                            </tr>
                        </thead>
                        <tbody class="emergencyDetailtable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 d-none">
        <div class="card createEmergencyDetail">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Create Emergency Details</legend>

                <form class="submitEmergencyDetail validateEmergencyDetail">
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

                    <label class="col-form-label">Phone:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('phone', null, [
                                    'placeholder' => 'Enter Phone Number',
                                    'class' => 'form-control numeric',
                                    'id' => 'phone',
                                ]) !!}
                            </div>
                            @if ($errors->has('phone1'))
                                <div class="error text-danger">{{ $errors->first('phone1') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Phone1 (Optional):</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('phone1', null, [
                                    'placeholder' => 'Enter Phone Number (Optional)',
                                    'class' => 'form-control numeric',
                                    'id' => 'phone1',
                                ]) !!}
                            </div>
                            @if ($errors->has('phone2'))
                                <div class="error text-danger">{{ $errors->first('phone2') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Address:</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('address', null, [
                                    'placeholder' => 'Enter Address',
                                    'class' => 'form-control',
                                    'id' => 'address',
                                ]) !!}
                            </div>
                            @if ($errors->has('address'))
                                <div class="error text-danger">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Relation:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('relation', $family_relations, null, [
                                    'placeholder' => 'Choose Relation',
                                    'class' => 'form-control select-search',
                                    'id' => 'relation',
                                ]) !!}
                            </div>
                            @if ($errors->has('relation'))
                                <div class="error text-danger">{{ $errors->first('relation') }}</div>
                            @endif
                        </div>
                    </div>


                    <label class="col-form-label">Note:</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('note', null, [
                                    'placeholder' => 'Enter Note',
                                    'class' => 'form-control',
                                    'id' => 'note',
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
                                    class="icon-database-insert"></i></b>Save</button>
                        <a type="submit" href="javascript:void(0)"
                            class="ml-2 mt-2 btn btn-secondary btn-labeled btn-labeled-left float-right go-back"><b><i
                                    class="icon-cancel-circle2"></i></b>Discard</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="card editEmergencyDetail" style="display: none">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Edit Emergency Details</legend>
                <form class="updateEmergencyDetail validateUpdateEmergencyDetail">
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

                    <label class="col-form-label">Phone:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('editphone', null, [
                                    'placeholder' => 'Enter Phone Number',
                                    'class' => 'form-control',
                                    'id' => 'editphone',
                                ]) !!}
                            </div>
                            @if ($errors->has('phone1'))
                                <div class="error text-danger">{{ $errors->first('phone1') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Phone1 (Optional):</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('editphone1', null, [
                                    'placeholder' => 'Enter Phone Number (Optional)',
                                    'class' => 'form-control',
                                    'id' => 'editphone1',
                                ]) !!}
                            </div>
                            @if ($errors->has('phone2'))
                                <div class="error text-danger">{{ $errors->first('phone2') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Address:</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('editaddress', null, [
                                    'placeholder' => 'Enter Address',
                                    'class' => 'form-control',
                                    'id' => 'editaddress',
                                ]) !!}
                            </div>
                            @if ($errors->has('address'))
                                <div class="error text-danger">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Relation:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('editrelation', $family_relations, null, [
                                    'placeholder' => 'Choose Relation',
                                    'class' => 'form-control select-search',
                                    'id' => 'editrelation',
                                ]) !!}
                            </div>
                            @if ($errors->has('relation'))
                                <div class="error text-danger">{{ $errors->first('relation') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Note:</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('editnote', null, [
                                    'placeholder' => 'Enter Note',
                                    'class' => 'form-control',
                                    'id' => 'editnote',
                                ]) !!}
                            </div>
                            @if ($errors->has('note'))
                                <div class="error text-danger">{{ $errors->first('note') }}</div>
                            @endif
                        </div>
                    </div>

                    <input type="hidden" class="emergencyId">

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

@include('employee::employee.js.emergencyDetailJsFunction')
