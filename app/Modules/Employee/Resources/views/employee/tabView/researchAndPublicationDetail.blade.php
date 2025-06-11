<script src="{{ asset('admin/validation/researchAndPublicationDetail.js') }}"></script>

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Research And Publication Details
                        </legend>
                    </div>
                    @if ($menuRoles->assignedRoles('researchAndPublicationDetail.save'))
                        @if ($employeeModel->status == 1)
                            <div class="col-1 text-center">
                                <a class="btn btn-sm btn-success rounded-pill createmode"
                                    data-name="ResearchAndPublication"><i class="icon-plus2"></i> Add</a>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>S.N</th>
                                <th>Research Title</th>
                                <th>Note</th>
                                @if ($employeeModel->status == 1)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="researchAndPublicationTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 d-none">
        <div class="card createResearchAndPublicationDetail">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Create Research And Publication Details
                </legend>
                <form class="submitResearchAndPublicationDetail validateResearchAndPublicationDetail">
                    <label class="col-form-label">Research:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('research_title', null, [
                                    'placeholder' => 'Enter Research Title',
                                    'class' => 'form-control',
                                    'id' => 'research_title',
                                ]) !!}
                            </div>
                            @if ($errors->has('research_title'))
                                <div class="error text-danger">{{ $errors->first('research_title') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Note:</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('note', null, [
                                    'placeholder' => 'Enter Note',
                                    'class' => 'form-control basicTinymce1',
                                    'id' => 'editor-full',
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

        <div class="card editResearchAndPublicationDetail" style="display: none">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Edit Research And Publication Details
                </legend>
                <form class="updateResearchAndPublicationDetail validateUpdateResearchAndPublicationDetail">
                    <label class="col-form-label">Research Title:<span class="text-danger">*</span></label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('research_title', null, [
                                    'placeholder' => 'Enter Research Title',
                                    'class' => 'form-control',
                                    'id' => 'edit_research_title',
                                ]) !!}
                            </div>
                            @if ($errors->has('research_title'))
                                <div class="error text-danger">{{ $errors->first('research_title') }}</div>
                            @endif
                        </div>
                    </div>
                    <label class="col-form-label">Note:</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('note', null, [
                                    'placeholder' => 'Enter Note',
                                    'class' => 'form-control basicTinymce1',
                                    // 'id' => 'edit_note1',
                                    'id' => 'editor-full',
                                ]) !!}
                            </div>
                            @if ($errors->has('note'))
                                <div class="error text-danger">{{ $errors->first('note') }}</div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="researchAndPublicationDetailId" class="researchAndPublicationDetailId">

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

@include('employee::employee.js.researchAndPublicationDetailJsFunction')
