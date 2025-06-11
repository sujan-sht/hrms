@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('css')
    <style>
        .start {
            margin-top: 0.5rem;
            padding: 0.2rem;
        }
    </style>
@endsection

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold"> Skill Details
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
                                <th>Skill</th>
                                <th>Metrics</th>
                                @if ($employeeModel->status == 1)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="skillTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 d-none">
        <div class="card createMedicalDetail">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Create Skill Detail</legend>
                <form class="submitSkill">
                    <label class="col-form-label"> Skill: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('skill_name', null, [
                                    'placeholder' => 'Skill (ex: Project Management)',
                                    'class' => 'form-control',
                                    'id' => 'skill_name',
                                ]) !!}
                            </div>

                        </div>
                    </div>
                    <label class="col-form-label"> Metrics: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div class="form-check me-3">
                                    <input type="radio" id="star5" name="save_rating" value="5"
                                        class="form-check-input" />
                                    <label for="star5" title="5 star"
                                        class="form-check-label">Excellent&nbsp;&nbsp;</label>
                                </div>
                                <div class="form-check me-3">
                                    <input type="radio" id="star4" name="save_rating" value="4"
                                        class="form-check-input" />
                                    <label for="star4" title="4 star"
                                        class="form-check-label">Professional&nbsp;&nbsp;</label>
                                </div>
                                <div class="form-check me-3">
                                    <input type="radio" id="star3" name="save_rating" value="3"
                                        class="form-check-input" />
                                    <label for="star3" title="3 star"
                                        class="form-check-label">Semi-Professional&nbsp;&nbsp;</label>
                                </div>
                                <div class="form-check me-3">
                                    <input type="radio" id="star2" name="save_rating" value="2"
                                        class="form-check-input" />
                                    <label for="star2" title="2 star"
                                        class="form-check-label">Intermediate&nbsp;&nbsp;</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="star1" name="save_rating" value="1"
                                        class="form-check-input" />
                                    <label for="star1" title="1 star"
                                        class="form-check-label">Beginner&nbsp;&nbsp;</label>
                                </div>
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
                <legend class="text-uppercase font-size-sm font-weight-bold">Edit Skill Detail</legend>
                <form class="updateSkillDetail">
                    <label class="col-form-label"> Skill: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('skill', null, [
                                    'placeholder' => 'Skill (ex: Project Management)',
                                    'class' => 'form-control',
                                    'id' => 'edit_skill_name',
                                ]) !!}
                            </div>

                        </div>
                    </div>
                    <label class="col-form-label"> Metrics: </label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <div class="input-group d-flex  flex-row-reverse">
                                    <label for="star5" title="5 star" class="start">Excellent</label>
                                    <input type="radio" id="star5" name="rating" class="form-control"
                                        value="5" />
                                    <label for="star4" title="4 star" class="start">Professional</label>
                                    <input type="radio" id="star4" name="rating" value="4"
                                        class="form-control" />
                                    <label for="star3" title="3 star" class="start">Semi-Professional</label>
                                    <input type="radio" id="star3" name="rating" value="3"
                                        class="form-control" />
                                    <label for="star2" title="2 star" class="start">Intermediate</label>
                                    <input type="radio" id="star2" name="rating" value="2"
                                        class="form-control" />
                                    <label for="star1" title="1 star" class="start">Beginner</label>
                                    <input type="radio" id="star1" name="rating" value="1"
                                        class="form-control" />
                                </div>
                            </div>

                        </div>
                    </div>
                    <input type="hidden" name="skillDetailId" class="skillDetailId">
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
@include('employee::employee.js.skillDetailJsFunction')
