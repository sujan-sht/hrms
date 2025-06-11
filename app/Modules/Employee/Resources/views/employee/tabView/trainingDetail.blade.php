{{-- <script src="{{ asset('admin/validation/editTrainingDetail.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
<style>
    *{
        margin: 0;
        padding: 0;
    }
    .rate {
        float: left;
        height: 46px;
        padding: 0 10px;
    }
    .rate:not(:checked) > input {
        position:absolute;
        top:-9999px;
    }
    .rate:not(:checked) > label {
        float:right;
        width:1em;
        overflow:hidden;
        white-space:nowrap;
        cursor:pointer;
        font-size:30px;
        color:#ccc;
    }
    .rate:not(:checked) > label:before {
        content: '★ ';
    }
    .rate > input:checked ~ label {
        color: #ffc700;
    }
    .rate:not(:checked) > label:hover,
    .rate:not(:checked) > label:hover ~ label {
        color: #deb217;
    }
    .rate > input:checked + label:hover,
    .rate > input:checked + label:hover ~ label,
    .rate > input:checked ~ label:hover,
    .rate > input:checked ~ label:hover ~ label,
    .rate > label:hover ~ input:checked ~ label {
        color: #c59b08;
    }
</style> --}}

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Training & Certificate
                        </legend>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>S.N</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Rating</th>
                                {{-- @if ($menuRoles->assignedRoles('cheatSheet.edit')) --}}

                                @if ($employeeModel->status == 1)
                                    <th width="12%">Action</th>
                                @endif
                                {{-- @endif --}}
                            </tr>
                        </thead>
                        <tbody class="trainingTable">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 d-none">
        <div class="card">
            <div class="card-header bg-secondary text-white border-bottom-0">
                <h6 class="mb-0">Trainer Detail</h6>
                {{-- <div class="d-inline-flex ms-auto">
                    <a class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample1" role="button"
                        aria-expanded="false" aria-controls="multiCollapseExample1"><i class="icon-circle-up2"></i></a>

                </div> --}}
            </div>

            {{-- <div class="collapse multi-collapse" id="multiCollapseExample1"> --}}
            <div class="card-body">
                <table class="table table-hover">
                    <tbody id="appendTrainer">

                    </tbody>
                </table>
            </div>
            {{-- </div> --}}
        </div>


        <div class="card editTrainingDetail">
            <div class="card-body">

                <fieldset>

                </fieldset>

                <legend class="text-uppercase font-size-sm font-weight-bold">Rating</legend>
                <form class="updateTrainingDetail validateUpdateAssetDetail">

                    <label class="col-form-label">Training Title:</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('editTrainingTitle', null, [
                                    'class' => 'form-control',
                                    'id' => 'editTrainingTitle',
                                    'readonly',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <label class="col-form-label">Give Rating</label>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                @php
                                    $ratingList = [1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'];
                                @endphp
                                {!! Form::select('editTrainingRating', $ratingList, null, [
                                    'class' => 'form-control select-search',
                                    'id' => 'editTrainingRating',
                                    'placeholder' => 'Select rating',
                                ]) !!}

                                {{-- {!! Form::text('editTrainingRating', null, [
                                    'class' => 'form-control numeric',
                                    'id' => 'editTrainingRating',
                                    'placeholder' => 'Enter rating',
                                ]) !!} --}}
                                {{-- <div class="rate">
                                    <input type="radio" id="editTrainingRating" name="editTrainingRating" value="5" />
                                    <label for="star5" title="5 stars">5 stars</label>

                                    <input type="radio" id="editTrainingRating" name="editTrainingRating" value="4" />
                                    <label for="star4" title="4 stars">4 stars</label>

                                    <input type="radio" id="editTrainingRating" name="editTrainingRating" value="3" />
                                    <label for="star3" title="3 stars">3 stars</label>

                                    <input type="radio" id="editTrainingRating" name="editTrainingRating" value="2" />
                                    <label for="star2" title="2 stars">2 stars</label>

                                    <input type="radio" id="editTrainingRating" name="editTrainingRating" value="1" />
                                    <label for="star1" title="1 star">1 star</label>
                              </div> --}}
                            </div>
                        </div>
                    </div>



                    <input type="hidden" name="trainingDetailId" class="trainingDetailId">

                    <div class="text-center">
                        <button
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


@include('employee::employee.js.trainingDetailJsFunction')

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
@endSection
