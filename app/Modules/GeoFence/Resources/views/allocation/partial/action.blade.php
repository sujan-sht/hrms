{!! Form::hidden('geofence_id', $geofence_id, []) !!}

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Organization: <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select(
                                        'organization_id',
                                        $organizationList,
                                        request()->get('organization_id') ? request()->get('organization_id') : null,
                                        [
                                            'class' => 'form-control select-search organization organization-filter2',
                                            'placeholder' => 'Select Organization',
                                        ],
                                    ) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Branch: </label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('branch_id', $branchList, request()->get('branch_id') ? request()->get('branch_id') : null, [
                                        'class' => 'form-control branch-filter select-search',
                                        'placeholder' => 'Select branch',
                                        'id' => 'branch',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-group append-clone">
                    @if ($isEdit)
                        {!! Form::hidden('allocation_id', $geoFenceAllocation['id'], ['id' => 'allocation']) !!}
                        <div class="row clone-div mb-2">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                                        <div class="row">
                                            <label class="col-form-label col-lg-2">
                                                Sub-Function: <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group col-lg-10">
                                                {!! Form::select(
                                                    'department_id',
                                                    $departmentList,
                                                    request()->get('department_id') ? request()->get('department_id') : null,
                                                    ['placeholder' => 'Select Sub-Function', 'class' => 'form-control department select-search', 'required'],
                                                ) !!}
                                                <span class="errorType"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 form-group-feedback">
                                        <div class="row">
                                            <label class="col-form-label col-lg-2">
                                                Employee: <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group col-lg-10">
                                                {!! Form::select(
                                                    'employee_ids[]',
                                                    $employeeList,
                                                    isset($geoFenceAllocation['employee_id']) ? json_decode($geoFenceAllocation['employee_id']) : null,
                                                    ['class' => 'form-control employee multiselect-select-all-filtering', 'multiple', 'required'],
                                                ) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- @if (isset($geoFenceAllocations) && count($geoFenceAllocations) > 0)
                        @foreach ($geoFenceAllocations as $key => $geoFenceAllocation)
                            @include('geofence::allocation.partial.clone', [
                                'btnType' => 'Edit',
                                'count' => $key,
                                'holidayDetail' => $geoFenceAllocation,
                            ])
                        @endforeach --}}
                    @else
                        @include('geofence::allocation.partial.clone', [
                            'btnType' => 'Create',
                            'count' => 0,
                        ])
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <script src="{{ asset('admin/validation/geofence-allocate.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>

    <script>
        $(document).ready(function() {


            $('.department').on('change', function() {
                var that = $(this).closest('.clone-div')
                filterData(that)
                var department_id = $(this).val()
                checkExist(department_id, that)
            })
            $(document).on('change', '.department', function() {
                var that = $(this).closest('.clone-div')
                filterData(that)
                var department_id = $(this).val()
                checkExist(department_id, that)
            })

            function filterData(that) {
                var department_id = that.find('.department').val()
                var organization_id = $('.organization').val()
                var branch_id = $('#branch').val()

                $.ajax({
                    type: 'GET',
                    url: "{{ route('geoFence.filterOrgDepartmentwise') }}",
                    data: {
                        'department_id': department_id,
                        'organization_id': organization_id,
                        'branch_id': branch_id,
                    },
                    success: function(data) {
                        var list = JSON.parse(data);
                        var options = '';
                        that.find('.employee').attr('multiple', 'multiple');

                        $.each(list, function(id, value) {
                            options += "<option value='" + id + "'  >" + value + "</option>";
                        });

                        that.find('.employee').html(options);
                        that.find('.employee').multiselect('rebuild', {
                            enableFiltering: true,
                            filterPlaceholder: 'Search...',
                            enableCaseInsensitiveFiltering: true
                        })
                    }
                })
            }

            function checkExist(department_id, that) {
                var is_edit = "{{ $isEdit }}"
                if (is_edit == true) {
                    var id = $('#allocation').val()
                }
                $.ajax({
                    type: 'GET',
                    url: "{{ route('allocation.checkExists') }}",
                    data: {
                        organization_id: $('.organization').val(),
                        department_id: department_id,
                        geofence_id: "{{ $geofence_id }}",
                        id: id
                    },
                    success: function(resp) {
                        if (resp != null && resp == 1) {
                            that.find('.department').css('border-color', 'red');
                            that.find('.errorType').html(
                                '<i class="icon-thumbs-down3 mr-1"></i> This GeoFence already allocated for above Organization & Sub-Function.'
                            );
                            that.find('.errorType').removeClass('text-success');
                            that.find('.errorType').addClass('text-danger');
                            that.find('.department').focus();
                            $(".department").val(null);
                            // $('.employee').val(null)
                            event.preventDefault();
                        } else {
                            that.find('.department').css('border-color', 'green');
                            that.find('.errorType').html('');
                            that.find('.errorType').removeClass('text-danger');
                            that.find('.errorType').addClass('text-success');
                        }
                    }
                });
            }

            $('.btn-clone').on('click', function() {
                appendClone()
            })

            function appendClone() {
                count = $('.clone-div').length
                $.ajax({
                    type: "get",
                    url: "{{ route('geoFence.clone.day') }}",
                    data: {
                        count: count,
                    },
                    success: function(res) {
                        $('.append-clone').append(res.data)
                        $('.department').select2()
                        $('.employee').attr('multiple', 'multiple')

                        $('.employee').multiselect({
                            includeSelectAllOption: true,
                            enableFiltering: true,
                            filterPlaceholder: 'Search...',
                            enableCaseInsensitiveFiltering: true
                        });
                    }
                });
            }

            $(document).on('click', '.btn-remove', function() {
                var parent = $(this).parent().parent()
                parent.remove()
            })

            // $('.department').trigger('change')
        })
    </script>
@endsection
