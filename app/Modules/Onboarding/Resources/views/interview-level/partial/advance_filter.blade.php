<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open(['route'=>'interviewLevel.index', 'method'=>'get']) !!}
            <div class="row">
                <div class="col-md-3">
                    <label class="d-block font-weight-semibold">Title</label>
                    <div class="input-group">
                        {!! Form::text('title', request('title') ?? null, ['placeholder'=>'Enter title', 'class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
                <a href="{{ request()->url() }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
            </div>
        {!! Form::close() !!}
    </div>
</div>
