@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>

@stop

<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-body">

                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Organization<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::select('org_id', $organizationList, $value = null, [
                                    'class' => 'form-control organizationFilter',
                                    'placeholder' => 'Select Organization',
                                    'data-toggle' => 'select2',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Group Name<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('group_name', $value = null, ['placeholder' => 'e.g: Group A', 'class' => 'form-control']) !!}
                                @if ($errors->first('group_name') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('group_name') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Group Members<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                @php
                                    if (isset($shiftGroupModel) && count($shiftGroupModel->groupMembers) > 0) {
                                        foreach ($shiftGroupModel->groupMembers as $groupMember) {
                                            $groupMemberValue[] = $groupMember->group_member;
                                        }
                                    } else {
                                        $groupMemberValue = null;
                                    }
                                @endphp
                                {!! Form::select('members[]', $employeeList, $value = $groupMemberValue, [
                                    'class' => 'form-control empFilter multiselect-select-all-filtering',
                                    'multiple',
                                    'data-placeholder' => 'Choose Members ...',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Shift<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::select('shift_id', $shiftList, $value = null, [
                                    'placeholder' => 'Select Shift',
                                    'class' => 'form-control',
                                    'required',
                                    'data-toggle' => 'select2',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Default </label>
                            </div>
                            {{-- @dd(@$shiftGroupModel->default) --}}
                            <div class="col-md-12">
                                {!! Form::checkbox('default', 'yes', @$shiftGroupModel->default == 'yes', [
                                    'class' => 'form-control-sm',
                                    'id' => 'default',
                                ]) !!}

                                @if ($errors->first('default') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('default') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div id="grace-period-container">
                    @include('shift::group.partial.grace-time', ['shift' => $shift ?? null])
                </div>
                {{-- <legend class="text-uppercase font-size-sm font-weight-bold">Grace Period (In minutes)</legend>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Check In</label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('ot_grace_period', $value = null, ['placeholder'=>'e.g: 10', 'class'=>'form-control numeric']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Check Out</label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('grace_period_checkout', $value = null, ['placeholder'=>'e.g: 10', 'class'=>'form-control numeric']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Check In (for Penalty)</label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('grace_period_checkin_for_penalty', $value = null, ['placeholder'=>'e.g: 10', 'class'=>'form-control numeric']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Check Out (for Penalty)</label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('grace_period_checkout_for_penalty', $value = null, ['placeholder'=>'e.g: 10', 'class'=>'form-control numeric']) !!}
                            </div>
                        </div>
                    </div>
                </div> --}}
                <br>
                <legend class="text-uppercase font-size-sm font-weight-bold">leave Benchmark Time</legend>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">For First Half</label>
                            </div>
                            <div class="col-md-12">
                                @php
                                    if (isset($shiftGroupModel->leave_benchmark_time_for_first_half)) {
                                        $first_half_time = date(
                                            'H:i',
                                            strtotime($shiftGroupModel->leave_benchmark_time_for_first_half),
                                        );
                                    } else {
                                        $first_half_time = date('H:i', strtotime('13:00:00'));
                                    }
                                @endphp
                                <div class="input-group">
                                    {!! Form::time('leave_benchmark_time_for_first_half', $value = $first_half_time, ['class' => 'form-control']) !!}
                                    <!-- <span class="input-group-text"><i class="icon icon-watch2"></i></span> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">For Second Half</label>
                            </div>
                            <div class="col-md-12">
                                @php
                                    if (isset($shiftGroupModel->leave_benchmark_time_for_second_half)) {
                                        $second_half_time = date(
                                            'H:i',
                                            strtotime($shiftGroupModel->leave_benchmark_time_for_second_half),
                                        );
                                    } else {
                                        $second_half_time = date('H:i', strtotime('14:00:00'));
                                    }
                                @endphp
                                <div class="input-group">
                                    {!! Form::time('leave_benchmark_time_for_second_half', $value = $second_half_time, ['class' => 'form-control']) !!}
                                    <!-- <span class="input-group-text"><i class="icon icon-watch2"></i></span> -->
                                </div>
                            </div>
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

    </div>
</div>
<script>
    $('body').on('change', '.organizationFilter', function() {
        filterEmployeeByOrganization();
    });

    function filterEmployeeByOrganization() {
        var organizationId = $('.organizationFilter').val();

        $.ajax({
            type: 'GET',
            url: '/admin/organization/get-employees',
            data: {
                organization_id: organizationId,
            },
            success: function(data) {
                var list = JSON.parse(data);
                var options = '';
                $('.empFilter').attr('multiple', 'multiple');

                // options += "<option value=''>Select Employee</option>";
                $.each(list, function(id, value) {
                    options += "<option value='" + id + "'  >" + value + "</option>";
                });

                $('.empFilter').html(options);

                // $.each(numericEmpIds, function(index, empId) {
                //     $('.empFilter option[value="' + empId + '"]').prop('selected',
                //         true);
                // });

                $('.empFilter').multiselect('rebuild', {
                    enableFiltering: true,
                    filterPlaceholder: 'Search...',
                    enableCaseInsensitiveFiltering: true
                });
            }
        });
    }


    // $('.organizationFilter').trigger('change');
</script>


<script>
    $(document).ready(function() {
        $('select[name="shift_id"]').on('change', function() {
            let shiftId = $(this).val();

            if (shiftId) {
                $.ajax({
                    url: "{{ route('shiftGroup.getseasonalshift') }}",
                    type: "GET",
                    data: {
                        shift_id: shiftId
                    },
                    success: function(response) {
                        $('#grace-period-container').html(response);
                    },
                    error: function() {
                        alert('Something went wrong!');
                    }
                });
            }
        });
    });
</script>
