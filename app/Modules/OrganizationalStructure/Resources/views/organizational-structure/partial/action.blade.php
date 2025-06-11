{{-- @inject('employeeRepo', '\App\Modules\Employee\Repositories\EmployeeRepository') --}}

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class=" row">
                            <label class="col-form-label col-lg-3">Title:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('title', null, ['class' => 'form-control', 'required']) !!}
                                </div>
                                @if($errors->has('title'))
                                    <div class="error text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Root Employee:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('root_employee_id', $allEmployeeList, null, [
                                        'class' => 'form-control select-search rootEmployee',
                                        'placeholder' => 'Select Employee',
                                        'required',
                                    ]) !!}
                                </div>
                                @if($errors->has('root_employee_id'))
                                    <div class="error text-danger">{{ $errors->first('root_employee_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class=" row">
                            <label class="col-form-label col-lg-3">Designation:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('designation', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Job Role:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('job_role', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class=" row">
                            <label class="col-form-label col-lg-3">KRA:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('kra', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">KPI:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('kpi', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Structure Flow Detail
                    <div class="text-warning"><small><b>Note: </b><i>Choose root employee as parent employee for at least one employee.</i></small></div>
                </legend>

                <div class="form-group append-clone">
                    @if (isset($orgStructureDetails) && count($orgStructureDetails) > 0)
                        @foreach ($orgStructureDetails as $key => $orgStructureDetail)
                            @include('organizationalstructure::organizational-structure.partial.clone', [
                                'btnType' => 'Edit',
                                'count' => $key,
                                'orgStructureDetail' => $orgStructureDetail,
                            ])
                        @endforeach
                    @else
                        @include('organizationalstructure::organizational-structure.partial.clone', [
                            'btnType' => 'Create',
                            'count' => 0,
                        ])
                    @endif
                </div>

                {{-- @if ($is_edit)
                    @php
                        $params = ['root_emp_id' => $orgStructure->root_employee_id];
                        $empList = $employeeRepo->getListExceptSelectedEmployee($params, 1);
                    @endphp
                    @if (isset($orgStructureDetails) && count($orgStructureDetails) > 0)
                        @foreach ($orgStructureDetails as $key => $orgStructureDetail)
                            <div class="row mb-2">
                                <label class="col-form-label col-lg-2">
                                    Employee: <span class="text-danger">*</span>
                                </label>
                            
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-lg-5 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::select('structure_details[employee_id][]',
                                                    $empList,
                                                    isset($orgStructureDetail['employee_id']) ? $orgStructureDetail['employee_id'] : null,
                                                    ['placeholder' => 'Select Employee', 'class' => 'form-control empList', 'required'],
                                                ) !!}
                                            </div>
                                            @if($errors->has('structure_details[employee_id][]'))
                                                <div class="error text-danger">{{ $errors->first('structure_details[employee_id][]') }}</div>
                                            @endif
                                        </div>
                                        <label class="col-form-label col-lg-2">Parent Employee: <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-5 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::select('structure_details[parent_employee_id][]',
                                                    $employeeList,
                                                    isset($orgStructureDetail['parent_employee_id']) ? $orgStructureDetail['parent_employee_id'] : null,
                                                    ['placeholder' => 'Select Employee', 'class' => 'form-control select-search', 'required'],
                                                ) !!}
                                            </div>
                                            @if($errors->has('structure_details[parent_employee_id][]'))
                                                <div class="error text-danger">{{ $errors->first('structure_details[parent_employee_id][]') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="col-lg-2">
                                    @if ($key == 0)
                                        <a class="btn btn-success rounded-pill btn-clone">
                                            <i class="icon-plus-circle2 mr-1"></i>Add More
                                        </a>
                                    @else
                                        <a class="btn btn-danger rounded-pill btn-remove"><i class="icon-minus-circle2 mr-1"></i>Remove</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                @else
                    <div class="form-group">
                        <div class="row mb-2">
                            <label class="col-form-label col-lg-2">
                                Employee: <span class="text-danger">*</span>
                            </label>
                        
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-5 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::select('structure_details[employee_id][]', [], null, ['placeholder' => 'Select Employee', 'class' => 'form-control empList', 'required']
                                        ) !!}
                                        </div>
                                        @if($errors->has('structure_details[employee_id][]'))
                                            <div class="error text-danger">{{ $errors->first('structure_details[employee_id][]') }}</div>
                                        @endif
                                    </div>
                                    <label class="col-form-label col-lg-2">Parent Employee: <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-5 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::select('structure_details[parent_employee_id][]',$employeeList,null,
                                                ['placeholder' => 'Select Employee', 'class' => 'form-control select-search', 'required'],
                                            ) !!}
                                        </div>
                                        @if($errors->has('structure_details[parent_employee_id][]'))
                                            <div class="error text-danger">{{ $errors->first('structure_details[parent_employee_id][]') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-lg-2">
                                <a class="btn btn-success rounded-pill btn-clone">
                                    <i class="icon-plus-circle2 mr-1"></i>Add More
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="formRepeater"></div>

                <div class="form-group" style="display:none;" id="formClone">
                    <div class="row mb-2">
                        <label class="col-form-label col-lg-2">
                            Employee: <span class="text-danger">*</span>
                        </label>
                    
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-5 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('structure_details[employee_id][]', [], null, ['placeholder' => 'Select Employee', 'class' => 'form-control empList', 'required']) !!}
                                    </div>
                                    @if($errors->has('structure_details[employee_id][]'))
                                        <div class="error text-danger">{{ $errors->first('structure_details[employee_id][]') }}</div>
                                    @endif
                                </div>
                                <label class="col-form-label col-lg-2">Parent Employee: <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-5 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('structure_details[parent_employee_id][]', $employeeList, null, ['placeholder' => 'Select Employee', 'class' => 'form-control parentEmployee', 'required']) !!}
                                    </div>
                                    @if($errors->has('structure_details[parent_employee_id][]'))
                                        <div class="error text-danger">{{ $errors->first('structure_details[parent_employee_id][]') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-lg-2">
                            <a class="btn btn-danger rounded-pill btn-remove"><i class="icon-minus-circle2 mr-1"></i>Remove</a>
                        </div>
                    </div>
                </div> --}}
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
    <script>
        $(document).ready(function() {
            $('.btn-clone').on('click', function() {
                appendClone()
            })

            function appendClone() {
                count = $('.clone-div').length
                root_emp_id = $('.rootEmployee option:selected').val()
                $.ajax({
                    type: "get",
                    url: "{{ route('organizationalStructure.clone.day') }}",
                    data: {
                        count: count,
                        root_emp_id: root_emp_id
                    },
                    success: function(res) {
                        $('.append-clone').append(res.data)
                        $('.empList').select2()
                        $('.parentEmpList').select2()
                    }
                });
            }

            $(document).on('click', '.btn-remove', function() {
                var parent = $(this).parent().parent()
                parent.remove()
            })

            $('.rootEmployee').on('change', function () {
                var root_emp_id = $(this).val()
                $.ajax({
                    type: "get",
                    url: "{{ route('organizationalStructure.getOtherEmployeeList') }}",
                    data: {
                        root_emp_id: root_emp_id,
                    },
                    success: function(data) {
                        var list = JSON.parse(data);

                        var options = ''
                        options += "<option value=''>Select Employee</option>"
                        $.each(list, function(id, value){
                            options += "<option value='" + id + "'>" + value + "</option>"
                        });
                        $('.empList').html(options)
                    }
                })
            })
            // $('.rootEmployee').trigger('change')


            // $('.btn-clone').on('click', function() {
            //         appendClone()
            //     })

            //     function appendClone() {
            //         var formClone = $('#formClone').html()
            //         $('.formRepeater').append(formClone)
            //         // $('.parentEmployee').select2();
            //     }

            //     $(document).on('click', '.btn-remove', function() {
            //         var parent = $(this).parent().parent()
            //         parent.remove()
            //     })

            //     $('.rootEmployee').on('change', function () {
            //         var root_emp_id = $(this).val()
            //         $.ajax({
            //             type: "get",
            //             url: "{{ route('organizationalStructure.getOtherEmployeeList') }}",
            //             data: {
            //                 root_emp_id: root_emp_id,
            //             },
            //             success: function(data) {
                            
            //                 var list = JSON.parse(data);

            //                 var options = ''
            //                 options += "<option value=''>Select Employee</option>"
            //                 $.each(list, function(id, value){
            //                     options += "<option value='" + id + "'>" + value + "</option>"
            //                 });
            //                 $('.empList').html(options)
            //                 // $('.empList').select2();
            //             }
            //         })
            //     })

            // $('.rootEmployee').trigger('change')
        })
    </script>
@endSection
