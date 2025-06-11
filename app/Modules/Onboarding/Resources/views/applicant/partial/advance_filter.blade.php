<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open(['route'=>'applicant.index', 'method'=>'get']) !!}
            <div class="row">
                <div class="col-md-2">
                    <label class="d-block font-weight-semibold">Apply Date</label>
                    <div class="input-group">
                        @php
                            if (setting('calendar_type') == 'BS') {
                                $classData = 'form-control nepali-calendar';
                            } else {
                                $classData = 'form-control daterange-single';
                            }
                        @endphp
                        {!! Form::text('date', request('date') ?? null, ['placeholder'=>'e.g: YYYY-MM-DD', 'class'=>$classData]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="d-block font-weight-semibold">MRF</label>
                    <div class="input-group">
                        {!! Form::select('mrf', $mrfList, request('mrf') ?? null, ['placeholder'=>'Select MRF', 'class'=>'form-control select-search']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="d-block font-weight-semibold">Status</label>
                    <div class="input-group">
                        {!! Form::select('status', $statusList, request('status') ?? null, ['placeholder'=>'Select Status', 'class'=>'form-control select-search']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="d-block font-weight-semibold">Applicant Name</label>
                    <div class="input-group">
                        {!! Form::text('applicant', request('applicant') ?? null, ['placeholder'=>'Enter Applicant Name', 'class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="d-block font-weight-semibold">Mobile Number</label>
                    <div class="input-group">
                        {!! Form::text('mobile', request('mobile') ?? null, ['placeholder'=>'Enter Mobile Number', 'class'=>'form-control numeric']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="d-block font-weight-semibold">Source</label>
                    <div class="input-group">
                        {!! Form::select('source', $sourceList, request('source') ?? null, ['placeholder'=>'Select Source', 'class'=>'form-control select-search']) !!}
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
