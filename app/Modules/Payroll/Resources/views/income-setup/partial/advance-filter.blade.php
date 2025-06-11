<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('incomeSetup.index') }}" method="GET">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="form-label">Organization</label>
                    {!! Form::select('organization_id', $organizationList, null, ['class' => 'form-control select-search']) !!}
                    {{-- {!! Form::select('organization_id', $organizationList, $value = request('organization_id') ? : null, ['placeholder'=>'Select Organization', 'class'=>'form-control select-search']) !!} --}}
                </div>

                <div class="col-md-3 mb-2">
                    <label class="form-label">Branch</label>
                    @php
                        if(isset($_GET['branch_id'])) {
                            $branchValue = $_GET['branch_id'];
                        } else {
                            $branchValue = null;
                        }
                    @endphp
                    {!! Form::select('branch_id', $branchList, $value = $branchValue, ['placeholder'=>'Select Branch', 'class'=>'form-control select2 branch-filter']) !!}
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