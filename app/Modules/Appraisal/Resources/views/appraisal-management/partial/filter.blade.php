<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        {!! Form::open(['route'=>'appraisal.index','method'=>'GET','class'=>'form-horizontal', 'id'=> 'tada_filter', 'role'=>'form']) !!}
        <div class="row">

            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Valid From Date:</label>
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-calendar"></i></span>
                        </span>
                        @php $selected_from_date = isset(request()->search_from) && !empty(request()->search_from) ? request()->search_from : ''; @endphp
                        <input id="search_from" value="{{$selected_from_date}}" placeholder="Pick Date" class="form-control form-control-lg  daterange-single" name="from_date" type="text">

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Valid To Date:</label>
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-calendar"></i></span>
                        </span>
                        @php $selected_to_date = isset(request()->search_to) && !empty(request()->search_to) ? request()->search_to : ''; @endphp
                        <input id="search_to" value="{{$selected_to_date}}" placeholder="Pick Date" class="form-control form-control-lg  daterange-single" name="to_date" type="text">
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Select Appraisee:</label>
                    <div class="input-group">
                        @php $selected_emp_id = isset(request()->emp_id) ? request()->emp_id : null ; @endphp
                        {!! Form::select('emp_id', $employee, $selected_emp_id,['class'=>'form-control select-employee select2', 'placeholder'=>'Select Appraisee']) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Select Questionnaire:</label>
                    <div class="input-group">
                        @php $questionnaire_id = isset(request()->questionnaire_id) ? request()->questionnaire_id : null ; @endphp
                        {!! Form::select('questionnaire_id', $questionnaires, $questionnaire_id,['class'=>'form-control select-employee select2', 'placeholder'=>'Select Questionnaire']) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Select Type:</label>
                    <div class="input-group">
                        @php $type = isset(request()->type) ? request()->type : null ; @endphp
                        {!! Form::select('type', ['internal' => 'Internal', 'external' => 'External'], $type,['class'=>'form-control select-employee select2', 'placeholder'=>'Select Type']) !!}
                    </div>
                </div>
            </div>


        </div>

        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
            <a href="{{ route('appraisal.index') }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>


<script>
    $(document).ready(function () {
        $('.select2').select2();
    })
</script>
