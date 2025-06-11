<script src="{{ asset('admin/validation/documentDetail.js') }}"></script>

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@php
    if (setting('calendar_type') == 'BS') {
        $classData = 'form-control nepali-calendar';
    } else {
        $classData = 'form-control daterange-single';
    }
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Document Details
                        </legend>
                    </div>
                    @if ($menuRoles->assignedRoles('documentDetail.save'))
                        @if ($employeeModel->status == 1)
                            <div class="col-1 text-center">
                                <a class="btn btn-sm btn-success rounded-pill createmode" data-name="Document"><i
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
                                <th>Document Name</th>
                                <th>ID</th>
                                <th>Issued Date</th>
                                <th>Expiry Date</th>
                                <th>File</th>
                                @if ($employeeModel->status == 1)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="documentTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 d-none">
        <div class="card createDocumentDetail">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Create Document Details</legend>
                <form class="submitDocumentDetail validateDocumentDetail" enctype="multipart/form-data">
                    <label class="col-form-label">Document Name: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('document_name', null, [
                                    'placeholder' => 'Enter document name',
                                    'class' => 'form-control',
                                    'id' => 'document_name',
                                ]) !!}
                            </div>
                            @if ($errors->has('document_name'))
                                <div class="error text-danger">{{ $errors->first('document_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">ID Number: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('id_number', null, [
                                    'placeholder' => 'Enter ID',
                                    'class' => 'form-control',
                                    'id' => 'id_number',
                                ]) !!}
                            </div>
                            @if ($errors->has('id_number'))
                                <div class="error text-danger">{{ $errors->first('id_number') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Issued Date: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('issued_date', null, [
                                    'placeholder' => 'Select date',
                                    'class' => $classData,
                                    'id' => 'issued_date',
                                ]) !!}
                            </div>
                            @if ($errors->has('issued_date'))
                                <div class="error text-danger">{{ $errors->first('issued_date') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Expiry Date: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('expiry_date', null, [
                                    'placeholder' => 'Select date',
                                    'class' => $classData,
                                    'id' => 'expiry_date',
                                ]) !!}
                            </div>
                            @if ($errors->has('expiry_date'))
                                <div class="error text-danger">{{ $errors->first('expiry_date') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Document File:</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-file-plus2"></i></span>
                                </span>
                                {!! Form::file('document_file', ['id' => 'file', 'class' => 'form-control']) !!}
                            </div>
                            @if ($errors->has('document_file'))
                                <div class="error text-danger">{{ $errors->first('document_file') }}</div>
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

        <div class="card editDocumentDetail" style="display: none">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Edit Document Details</legend>
                <form class="updateDocumentDetail validateUpdateDocumentDetail">
                    <label class="col-form-label">Document Name: <span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('document_name', null, [
                                    'placeholder' => 'Enter Document Name',
                                    'class' => 'form-control',
                                    'id' => 'edit_document_name',
                                ]) !!}
                            </div>
                            @if ($errors->has('document_name'))
                                <div class="error text-danger">{{ $errors->first('document_name') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">ID Number: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('id_number', null, [
                                    'placeholder' => 'Enter ID',
                                    'class' => 'form-control',
                                    'id' => 'edit_id_number',
                                ]) !!}
                            </div>
                            @if ($errors->has('id_number'))
                                <div class="error text-danger">{{ $errors->first('id_number') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Issued Date: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('issued_date', null, [
                                    'placeholder' => 'Select date',
                                    'class' => $classData,
                                    'id' => 'edit_issued_date',
                                ]) !!}
                            </div>
                            @if ($errors->has('issued_date'))
                                <div class="error text-danger">{{ $errors->first('issued_date') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Expiry Date: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('expiry_date', null, [
                                    'placeholder' => 'Select date',
                                    'class' => $classData,
                                    'id' => 'edit_expiry_date',
                                ]) !!}
                            </div>
                            @if ($errors->has('expiry_date'))
                                <div class="error text-danger">{{ $errors->first('expiry_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <label class="col-form-label">Document File:</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-file-plus2"></i></span>
                                </span>
                                {!! Form::file('document_file', ['id' => 'edit_file', 'class' => 'form-control']) !!}
                            </div>
                            @if ($errors->has('document_file'))
                                <div class="error text-danger">{{ $errors->first('document_file') }}</div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="documentDetailId" class="documentDetailId">

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
@include('employee::employee.js.documentDetailJsFunction')

<script>
    // $('.select2').select2();
</script>
