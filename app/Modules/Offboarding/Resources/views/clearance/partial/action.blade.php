<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div class="row items">
                            <label class="col-form-label col-lg-2">Title :<span class="text-danger"> *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('title', null, ['placeholder' => 'Enter Title', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('title'))
                                    <div class="error text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Description :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('description', null, ['placeholder' => 'Enter Description', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('description'))
                                    <div class="error text-danger">{{ $errors->first('description') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Order :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('order', null, ['placeholder' => 'Enter Order', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('order'))
                                    <div class="error text-danger">{{ $errors->first('order') }}</div>
                                @endif
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Status :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('status', [11 => 'Active', 10 => 'Inactive'], null, ['class' => 'form-control select-search']) !!}
                                </div>
                                @if ($errors->has('status'))
                                    <div class="error text-danger">{{ $errors->first('status') }}</div>
                                @endif
                            </div>
                        </div>
                    </div> --}}
                </div>
                <legend class="text-uppercase font-size-sm font-weight-bold">Other Details</legend>
                @if ($isEdit)
                    @foreach ($clearanceModel->clearanceResponsible as $count => $clearanceResonsible)
                        {{-- <div class="row items">
                            <div class="col-lg-5 mb-3">
                                <div class="row">
                                    <label class="col-form-label col-lg-3">Organization :<span class="text-danger">
                                            *</span></label>
                                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::select('organization_id[]', $organizationList, $clearanceResonsible->organization_id, [
                                                'placeholder' => 'Select Organization',
                                                'class' => 'form-control select-search organization-filte',
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
                                            {!! Form::select('employee_id[]', $employeeList, $clearanceResonsible->employee_id, [
                                                'placeholder' => 'Select Employee',
                                                'class' => 'form-control select-search employee-filte',
                                            ]) !!}

                                        </div>
                                        @if ($errors->has('employee_id'))
                                            <div class="error text-danger">{{ $errors->first('employee_id') }}</div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <a id="remove-btn" class="btn btn-danger rounded-pill"
                                    onclick="$(this).parents('.items').remove()">
                                    <i class="icon-minus-circle2"></i>&nbsp;&nbsp;Remove
                                </a>
                            </div>
                        </div> --}}
                        @php
                            $employeeList= App\Modules\Employee\Entities\Employee::getOrganizationwiseEmployees($clearanceResonsible->organization_id);
                        @endphp

                        @include('offboarding::clearance.partial.repeater-form', [
                            'organizationList' => $organizationList,
                            'employeeList' => $employeeList,
                            'clearanceResonsible' => $clearanceResonsible,
                            'count' => $count,
                        ])
                    @endforeach
                    {{-- <div class="repeaterForm"></div> --}}
                    {{-- <div class="row">
                        <div class="col-lg-2 mb-3">
                            <a id="addMore" class="btn btn-success rounded-pill addMore">
                                <i class="icons icon-plus-circle2 mr-1"></i>Add More
                            </a>
                        </div>
                    </div> --}}
                @else
                    @include('offboarding::clearance.partial.repeater-form', [
                        'organizationList' => $organizationList,
                        'employeeList' => [],

                        'count' => 0,
                    ])
                    {{-- <div class="row">
                        <div class="col-lg-5 mb-3">
                            <div class="row">
                                <label class="col-form-label col-lg-3">Organization :<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('organization_id[]', $organizationList, null, [
                                            'placeholder' => 'Select Organization',
                                            'class' => 'form-control select-search12 organization-filter',
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
                                        {!! Form::select('employee_id[]', [], null, [
                                            'placeholder' => 'Select Employee',
                                            'class' => 'form-control select-search employee-filter',
                                        ]) !!}

                                    </div>
                                    @if ($errors->has('employee_id'))
                                        <div class="error text-danger">{{ $errors->first('employee_id') }}</div>
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <a id="addMore" class="btn btn-success rounded-pill addMore">
                                <i class="icons icon-plus-circle2 mr-1"></i>Add More
                            </a>
                        </div>
                    </div> --}}
                @endif
                <div class="repeaterForm"></div>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btns btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <!-- validation js -->
    <script src="{{ asset('admin/validation/clearance.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    {{-- <script src="{{ asset('admin/js/nrj_custom.js') }}"></script> --}}
    <script>
        $(document).ready(function() {


            $(".addMore").click(function() {
                // console.log('hello');
                $.ajax({
                    url: "<?php echo route('clearance.getRepeaterForm'); ?>",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $(".repeaterForm").append(data.result);
                        $(".select-search").select2();

                    }
                });
            });


        });
    </script>
@endSection
