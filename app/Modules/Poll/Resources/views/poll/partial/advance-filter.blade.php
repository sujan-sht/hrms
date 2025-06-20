<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ $route }}" method="GET">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="form-label">Mutiple Option Status</label>
                    {!! Form::select('multiple_option_status', $multipleOptionStatus, $value = request('multiple_option_status') ?? null, ['placeholder'=>'Select Status', 'class'=>'form-control select-search']) !!}
                </div>

                <div class="col-md-3 mb-2">
                    <label for="example-email" class="form-label">Expiry Date</label>
                    @php
                        if (setting('calendar_type') == 'BS') {
                            $clData = 'form-control nepali-calendar';
                        } else {
                            $clData = 'form-control daterange-single';
                        }
                    @endphp
                    {!! Form::text('expiry_date', $value = isset($_GET['expiry_date']) ? $_GET['expiry_date'] : null, [
                        'placeholder' => 'e.g : YYYY-MM-DD',
                        'class' => $clData,
                        'autocomplete' => 'off',
                    ]) !!}
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
