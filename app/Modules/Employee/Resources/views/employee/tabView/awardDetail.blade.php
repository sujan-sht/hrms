{{-- <script src="{{ asset('admin/validation/medicalDetail.js') }}"></script> --}}

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold"> Award Details
                        </legend>
                    </div>
                    @if ($employeeModel->status == 1)
                        <div class="col-1 text-center">
                            <a class="btn btn-sm btn-success rounded-pill createmode" data-name="Medical">Create
                                New</a>
                        </div>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>S.N</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Attachment</th>
                                {{-- <th>Status</th> --}}
                                @if ($employeeModel->status == 1)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="awardTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 d-none">
        <div class="card createMedicalDetail">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Create Award Detail</legend>
                <form class="submitAwardDetail">
                    <label class="col-form-label"> Title: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('title', null, [
                                    'placeholder' => 'Enter Title',
                                    'class' => 'form-control',
                                    'id' => 'title',
                                ]) !!}
                            </div>

                        </div>
                    </div>
                    <label class="col-form-label"> Date: </label>
                    <div class="row mb-2" id="date-award-container">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {{-- {!! Form::text('date', null, [
                                    'placeholder' => 'Enter Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'date-award',
                                ]) !!} --}}

                                <x-utilities.date-picker default="nep" nep-date-attribute="nep_date"
                                    eng-date-attribute="date" mode="both" :date="request('abc')" />
                            </div>

                        </div>
                    </div>
                    <label class="col-form-label"> Attachment: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <input type="file" name="attachment[]" id="attachment" class="form-control" multiple
                                    accept="image/*">
                            </div>
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
                <legend class="text-uppercase font-size-sm font-weight-bold">Edit Award Detail</legend>
                <form class="updateAwardDetail">
                    <label class="col-form-label"> Title: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('title', null, [
                                    'placeholder' => 'Enter Title',
                                    'class' => 'form-control',
                                    'id' => 'edit_title',
                                ]) !!}
                            </div>

                        </div>
                    </div>
                    <label class="col-form-label"> Date: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('date', null, [
                                    'placeholder' => 'Enter Date',
                                    'class' => 'form-control nepali-calendar',
                                    'id' => 'edit_date',
                                ]) !!}
                            </div>

                        </div>
                    </div>
                    <label class="col-form-label"> Attachment: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <input type="file" name="attachment[]" id="edit_attachment" class="form-control"
                                    multiple accept="image/*">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="awardDetailId" class="awardDetailId">

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
@include('employee::employee.js.awardDetailJsFunction')
