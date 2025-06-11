<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form>
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="example-email" class="form-label">Employee:</label>
                        @php
                            if (isset($_GET['employee_id'])) {
                                $employeeValue = $_GET['employee_id'];
                            } else {
                                $employeeValue = null;
                            }
                        @endphp
                        {!! Form::select('employee_id', $employeeList, $value = $employeeValue, [
                            'placeholder' => 'Select Employee',
                            'class' => 'form-control select2 employee-filter',
                        ]) !!}
                         {{-- <p class="eng_year-error" style="color:red"></p> --}}
                    </div>
                </div>
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
