<div class="card card-body filter-option">
    {!! Form::open(['route' => 'fuelConsumption', 'method' => 'get']) !!}
    <div class="row">
        @if (setting('calendar_type') == 'BS')
            <div class="col-md-3 mt-3">
                <label class="d-block font-weight-semibold">From Date:</label>
                <div class="input-group">
                    {!! Form::text('from_date', $value = request('from_date') ?: null, [
                        'placeholder' => 'e.g : YYYY-MM-DD',
                        'class' => 'form-control nepali-calendar',
                        'autocomplete' => 'on',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3 mt-3">
                <label class="d-block font-weight-semibold">To Date:</label>
                <div class="input-group">
                    {!! Form::text('to_date', $value = request('to_date') ?: null, [
                        'placeholder' => 'e.g : YYYY-MM-DD',
                        'class' => 'form-control nepali-calendar',
                        'autocomplete' => 'on',
                    ]) !!}
                </div>
            </div>
        @else
            <div class="col-md-3 mt-3">
                <label class="d-block font-weight-semibold">Date Range:</label>
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="text-blue icon-calendar2"></i>
                        </span>
                    </span>
                    @php
                        $search_from_to = array_key_exists('search_from_to', $search_value)
                            ? $search_value['search_from_to']
                            : '';
                    @endphp
                    {!! Form::text('search_from_to', $value = $search_from_to, [
                        'id' => 'search_from',
                        'placeholder' => 'Search From - To',
                        'class' => 'form-control form-control-lg  daterange-buttons',
                        'readonly',
                    ]) !!}
                </div>
            </div>
        @endif
        @if (Auth::user()->user_type != 'employee')
            <div class="col-md-3 mt-3">
                <label class="d-block font-weight-semibold">Organization:</label>
                <div class="input-group">
                    @php
                        $search_organization_by = array_key_exists('organizationId', $search_value)
                            ? $search_value['organizationId']
                            : '';
                    @endphp
                    {!! Form::select('organizationId[]', $organizationList, $search_organization_by, [
                        'id' => 'organizationId',
                        'class' => 'form-control multiselect-select-all-filtering',
                        'multiple' => 'multiple',
                    ]) !!}
                </div>
            </div>
        @endif

        @if (Auth::user()->user_type != 'employee')
            <div class="col-md-3 mt-3">
                <label class="d-block font-weight-semibold">Employee:</label>
                <div class="input-group">
                    {{--
                    {!! Form::select('employee_id[]', $employee, $search_raise_by, [
                        'class' => 'form-control multiselect-select-all employee-fiter',
                        'multiple' => 'multiple',
                    ]) !!} --}}
                    @php
                        $search_raise_by = array_key_exists('employee_id', $search_value)
                            ? $search_value['employee_id']
                            : '';
                    @endphp
                    {!! Form::select('employee_id[]', isset($employee) ? $employee : [], $search_raise_by, [
                        'id' => 'employee_id',
                        'class' => 'form-control multiselect-select-all-filtering',
                        'multiple' => 'multiple',
                    ]) !!}
                </div>
            </div>
        @endif

        <div class="col-md-3 mt-3">
            <label class="d-block font-weight-semibold">Status:</label>
            <div class="input-group">
                @php
                    $search_status = array_key_exists('status', $search_value) ? $search_value['status'] : '';
                @endphp
                {!! Form::select(
                    'status',
                    ['pending' => 'Pending', 'verified' => 'Verified', 'approved' => 'Approved'],
                    $value = $search_status,
                    ['id' => 'status', 'class' => 'form-control', 'placeholder' => 'Select Status'],
                ) !!}
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end mt-2">
        <button class="btn bg-primary" type="submit">
            Search Now
        </button>
        <a href="{{ route('fuelConsumption') }}" data-popup="tooltip" data-placement="top"
            data-original-title="Refresh Search" class="btn bg-danger ml-2"><i class="icon-spinner9"></i></a>
    </div>
    {!! Form::close() !!}
</div>
<script>
    $(document).ready(function() {
        $('#organizationId').on('change', function() {
            var organization_id = $('#organizationId').val();

            $.ajax({
                url: "{{ url('admin/notice/getOrganizationEmployee') }}", // Add this route in your web.php
                method: 'GET',
                data: {
                    organization_id: organization_id
                },
                success: function(data) {
                    $('#employee_id').empty();
                    $.each(data, function(id, name) {
                        $('#employee_id').append(new Option(name, id));
                    });
                    $('#employee_id').multiselect('rebuild');
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + error.message);
                }
            });
        });


    });
</script>
