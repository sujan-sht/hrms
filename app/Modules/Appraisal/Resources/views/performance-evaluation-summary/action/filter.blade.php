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
        {!! Form::open(['route'=>'performanceEvaluationSummary','method'=>'GET','class'=>'form-horizontal', 'role'=>'form']) !!}
        <div class="row">

            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Select Appraisee:</label>
                    <div class="input-group">
                        @php $selected_emp_id = isset(request()->emp_id) ? request()->emp_id : null ; @endphp
                        {!! Form::select('emp_id', $employee, $selected_emp_id,['class'=>'form-control select-employee select2', 'placeholder'=>'Select Appraisee']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
            <a href="{{ route('performanceEvaluationSummary') }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>


<script>
    $(document).ready(function () {
        $('.select2').select2();
    })
</script>
