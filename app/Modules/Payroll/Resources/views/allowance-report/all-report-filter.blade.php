<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{  route('allowanceReport.allReport') }}" method="GET">
            <div class="row">
                @if (Auth::user()->user_type == 'super_admin' || Auth::user()->user_type == 'admin' ||
                Auth::user()->user_type == 'hr' || Auth::user()->user_type == 'division_hr')
                <div class="col-md-3 mb-2">
                    <label class="form-label">Organization <span class="text-danger">*</span> </label>
                    {!! Form::select('organization_id', $organizationList, $value = request('organization_id') ?: null,
                    [
                    'placeholder' => 'Select Organization',
                    'class' => 'form-control select-search ',
                    'id' => 'organizationSelect',
                    ]) !!}
                </div>
                @endif
                <input type="hidden" name="type" value="{{ $type }}">
                {{-- @if (setting('calendar_type') == 'BS')
                <div class="col-md-3">
                    <label class="d-block font-weight-semibold">From Date: <span class="text-danger">*</span></label>
                    <div class="input-group">
                        {!! Form::text('from_nep_date', $value = request('from_nep_date') ?: null, [
                        'placeholder' => 'e.g : YYYY-MM-DD',
                        'class' => 'form-control nepali-calendar from_nep_date',
                        'autocomplete' => 'on',
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="d-block font-weight-semibold">To Date:</label>
                    <div class="input-group">
                        {!! Form::text('to_nep_date', $value = request('to_nep_date') ?: null, [
                        'placeholder' => 'e.g : YYYY-MM-DD',
                        'class' => 'form-control nepali-calendar to_nep_date',
                        'autocomplete' => 'on',
                        ]) !!}
                    </div>
                </div>
                @else --}}
                <div class="col-md-3 mb-2">
                    <label for="from_date" class="form-label">From Date <span class="text-danger">*</span></label>
                    {!! Form::date('from_date', request('from_date'), [
                    'class' => 'form-control',
                    'placeholder' => 'YYYY-MM-DD',
                    'required'
                    ]) !!}
                </div>

                <div class="col-md-3 mb-2">
                    <label for="to_date" class="form-label">To Date <span class="text-danger">*</span></label>
                    {!! Form::date('to_date', request('to_date'), [
                    'class' => 'form-control',
                    'placeholder' => 'YYYY-MM-DD',
                    'required'
                    ]) !!}
                </div>

                {{-- @endif --}}




                <div class="col-md-3 mb-2">
                    <label class="form-label">Employee</label>
                    {!! Form::select('employee_id', [], request('employee_id') ?? '', [
                    'placeholder' => 'Select Employee',
                    'class' => 'form-control select-search employee-filter',
                    'id' => 'employeeId',
                     'data-selected' => old('employee_id') ?: request('employee_id')
                    ]) !!}
                </div>

            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ route('allowanceReport.allReport') }}" class="btn bg-secondary text-white">
                    <i class="icons icon-reset mr-1"></i>Reset
                </a>
            </div>
        </form>

    </div>
</div>


{{-- @section('script') --}}
<script>
    const getEmployeeUrl = "{{ route('allowanceReport.getEmployee') }}";

   function fetchEmployeesByOrganization(organizationId, employeeId) {
    if (!organizationId) return;

    $.ajax({
        url: getEmployeeUrl,
        method: 'GET',
        data: {
            organization_id: organizationId
        },
        success: function (response) {
            let $employeeSelect = $('#employeeId');
            $employeeSelect.empty();
            $employeeSelect.append($('<option>', {
                value: '',
                text: 'Select Employee'
            }));

            $.each(response, function (index, employee) {
                $employeeSelect.append($('<option>', {
                    value: employee.id,
                    text: employee.name,
                    selected: employee.id == employeeId // auto-select if match
                }));
            });
        },
        error: function (xhr) {
            console.error(xhr.responseText);
        }
    });
}


    // On change
    $('#organizationSelect').change(function () {
        let organizationId = $(this).val();
        fetchEmployeesByOrganization(organizationId);
    });

    // On page load: auto-trigger if org is pre-selected
    $(document).ready(function () {
        let preselectedOrgId = $('#organizationSelect').val();
        let employeeId = $('#employeeId').data('selected');
        if (preselectedOrgId) {
            fetchEmployeesByOrganization(preselectedOrgId ,employeeId);
        }
    });
</script>



{{-- @endsection --}}
