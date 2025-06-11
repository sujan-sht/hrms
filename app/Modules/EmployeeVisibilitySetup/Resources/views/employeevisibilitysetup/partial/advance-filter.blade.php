<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('employeeVisibilitySetup.index') }}" method="GET">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="form-label">Organizations</label>
                    <select name="organization_id" id="organization_id" class="form-control organization_id ">
                        <option value="">Select Organization</option>
                        @foreach ($organizations as $key => $organization)
                            <option value="{{ $key }}"
                                {{ Request::get('organization_id') == $key ? 'selected' : '' }}>{{ $organization }}
                            </option>
                        @endforeach
                    </select>
                </div>




                <div class="col-md-3 mb-2">
                    <label class="form-label">Employee</label>
                    <select name="employee_id" id="employee" class="form-control multiselect-select-all-filtering">
                        <option value="">Select Employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{$employee->id}}">{{$employee->full_name}}</option>
                        @endforeach
                    </select>
                </div>


                <div class="col-md-3 mb-2">
                    <label class="form-label">Roles</label>
                    <select name="role_id" id="role_id" class="form-control role_id ">
                        <option value="">Select Role</option>
                        @foreach ($roles as $key => $role)
                            <option value="{{ $role->user_type }}"
                                {{ Request::get('role_id') == $role->user_type ? 'selected' : '' }}>{{ $role->name }}
                            </option>
                        @endforeach
                    </select>
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


<script>


    $(document).ready(function() {
        $('#employee').select2({
            placeholder: "Select Employee",
            allowClear: true
        });

        $('.organization_id').on('change', function() {
            const organization_id = $(this).val();
            $.ajax({
                type: "get",
                url: "{{ route('getEmployee_By_Organization') }}",
                data: {
                    organization_id: organization_id,
                },
                dataType: "json",
                success: function(response) {
                    $('#employee').empty();
                    $('#employee').append('<option value="">Select Employee</option>');

                    response.forEach(element => {
                        $('#employee').append('<option value="' + element.id +
                            '">' + element.full_name + '</option>');
                    });

                    $('#employee').trigger('change');
                }
            });
        });
    });
</script>




<style>
    input[type=checkbox],
    input[type=radio] {
        height: 18px !important;
    }
</style>
