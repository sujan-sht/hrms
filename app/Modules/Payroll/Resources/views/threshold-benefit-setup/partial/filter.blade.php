<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form>
            <div class="row">
                @if (Auth::user()->user_type == 'super_admin' || Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'hr' | Auth::user()->user_type == 'supervisor')

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="example-email" class="form-label">Organization</label>
                        @php
                            if (isset($_GET['organizationId'])) {
                                $orgValue = $_GET['organizationId'];
                            } else {
                                $orgValue = null;
                            }
                        @endphp
                        {!! Form::select('organizationId', $organizationList, $value = $orgValue, [
                            // 'placeholder' => 'Select Organization',
                            'class' => 'form-control select2 organization-filter organization-filter2',
                        ]) !!}
                    </div>
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
                @endif
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icons icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ request()->url() }}" class="btn bg-secondary text-white">
                    <i class="icons icon-reset mr-1"></i>Reset
                </a>
            </div>
        </form>

    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
