<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ $route }}" method="GET">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label for="example-email" class="form-label">Question</label>
                    {!! Form::text('question', $value = request('question') ?? null , [
                        'placeholder' => 'Enter question',
                        'class' => 'form-control',
                    ]) !!}
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Question Type</label>
                    {!! Form::select('question_type', $questionType, $value = request('question_type') ?? null, ['placeholder'=>'Select Type', 'class'=>'form-control select-search']) !!}
                </div>

            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ request()->url() }}" class="btn bg-secondary text-white">
                    <i class="icons icon-reset mr-1"></i>Reset
                </a>
            </div>
        </form>

    </div>
</div>
