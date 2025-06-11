<div class="row items">
    <div class="col-lg-5 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-3">Organization :<span class="text-danger">
                    *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('organization_id[]', $organizationList, $clearanceResonsible->organization_id ?? null, [
                        'placeholder' => 'Select Organization',
                        'class' => 'form-control select-search organizationFilter',
                    ]) !!}
                </div>
                @if ($errors->has('organization_id'))
                    <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                @endif
            </div>

        </div>
    </div>
    <div class="col-lg-5 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-2">Employee :<span class="text-danger">
                    *</span></label>
            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('employee_id[]', $employeeList, $clearanceResonsible->employee_id ?? null, [
                        'placeholder' => 'Select Employee',
                        'class' => 'form-control select-search employeeFilter',
                    ]) !!}

                </div>
                @if ($errors->has('employee_id'))
                    <div class="error text-danger">{{ $errors->first('employee_id') }}</div>
                @endif
            </div>

        </div>
    </div>

    @if ($count >= 1)
        <div class="col-lg-2 mb-3">
            <a id="remove-btn" class="btn btn-danger rounded-pill" onclick="$(this).parents('.items').remove()">
                <i class="icon-minus-circle2"></i>&nbsp;&nbsp;Remove
            </a>
        </div>
    @else
        <div class="col-lg-2 mb-3">
            <a id="addMore" class="btn btn-success rounded-pill addMore">
                <i class="icons icon-plus-circle2 mr-1"></i>Add More
            </a>
        </div>
    @endif

</div>
{{--
<div class="row items">
    <div class="col-lg-5 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-3">Organization :<span class="text-danger">
                    *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('organization_id[]', $organizationList, null, [
                        'placeholder' => 'Select Organization',
                        'class' => 'form-control select-search1 organization-filter',
                    ]) !!}
                </div>
                @if ($errors->has('organization_id'))
                    <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                @endif
            </div>

        </div>
    </div>
    <div class="col-lg-5 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-2">Employee :<span class="text-danger">
                    *</span></label>
            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('employee_id[]', $employeeList, null, [
                        'placeholder' => 'Select Employee',
                        'class' => 'form-control select-search employee-filter1',
                    ]) !!}

                </div>
                @if ($errors->has('employee_id'))
                    <div class="error text-danger">{{ $errors->first('employee_id') }}</div>
                @endif
            </div>

        </div>
    </div>

</div> --}}

{{-- <script src="{{ asset('admin/js/nrj_custom.js') }}"></script> --}}


<script>
    $(function() {
        $('.organizationFilter').select2();
        $('.organizationFilter').on('change', function() {
            var organizationId = $(this).val();
            empFilter = $(this).closest('.items').find('.employeeFilter');

            $.ajax({
                type: 'GET',
                url: '/admin/organization/get-employees',
                data: {
                    organization_id: organizationId
                },
                success: function(data) {
                    var list = JSON.parse(data);
                    var options = '';

                    options += "<option value=''>Select Employee</option>";
                    $.each(list, function(id, value) {
                        options += "<option value='" + id + "'>" + value +
                            "</option>";
                    });

                    empFilter.html(options);
                }
            });
        });
    })
</script>
