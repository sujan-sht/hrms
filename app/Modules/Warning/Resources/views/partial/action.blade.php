<script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/editor_ckeditor_default.js') }}"></script>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Subject:<span class="text-danger">*</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-pencil"></i></span>
                                    </span>

                                    {!! Form::text('title', $value = null, [
                                        'id' => 'title',
                                        'placeholder' => 'Enter Title',
                                        'class' => 'form-control',
                                    ]) !!}

                                </div>
                                @if ($errors->has('title'))
                                    <span class="text-danger">{{ $errors->first('title') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Date :<span class="text-danger">*</span></label>

                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-pencil"></i></span>
                                    </span>

                                    @if (setting('calendar_type') == 'BS')
                                        <input type="text" name="date" id="date"
                                            class="form-control nepali-calendar date">
                                    @else
                                        <input type="text" name="date" id="date"
                                            class="form-control daterange-single date">
                                    @endif

                                </div>
                                @if ($errors->has('room_code'))
                                    <span class="text-danger">{{ $errors->first('room_code') }}</span>
                                @endif
                            </div>
                        </div>

                    </div>
                    @if (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'hr' || auth()->user()->user_type == 'admin')
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Organization:<span class="text-danger">*</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-pencil"></i></span>
                                    </span>
        
                                    {!! Form::select('organization_id', $organizationList, $value = $organizationValue ?? null, ['placeholder'=>'Select Organization', 'class'=>'form-control', 'id' => 'organizationId']) !!}
        
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Employee :</label>
        
                            <div class="col-lg-10">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-pencil"></i></span>
                                    </span>
        
                                    {!! Form::select(
                                        'employee_id[]',
                                        isset($employeeList) ? $employeeList : [],
                                        isset($employee_id) ? $employee_id : '',
                                        [
                                            'id' => 'employeeId',
                                            'class' => 'form-control multiselect-select-all-filtering',
                                            'multiple' => 'multiple',
                                        ],
                                    ) !!}
        
                                </div>
                            </div>
                        </div>
                    </div>
                   
                   
                    
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Ref. No.:</label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-pencil"></i></span>
                                    </span>

                                    {!! Form::text('ref_no', $value = null, [
                                        'id' => 'ref_no',
                                        'placeholder' => 'Enter ref no',
                                        'class' => 'form-control',
                                    ]) !!}

                                </div>
                                @if ($errors->has('ref_no'))
                                    <span class="text-danger">{{ $errors->first('ref_no') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Reg. No. :</label>

                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-pencil"></i></span>
                                    </span>

                                    {!! Form::text('reg_no', null, [
                                        'id' => 'reg_no',
                                        'placeholder' => 'Enter Reg No',
                                        'class' => 'form-control',
                                    ]) !!}

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-1">Description :</label>

                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-pencil"></i></span>
                                    </span>

                                    {!! Form::textarea('description', null, [
                                        'id' => 'editor-full',
                                        'placeholder' => 'Enter Description',
                                        'class' => 'form-control',
                                    ]) !!}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="text-right">
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

<script>
    $(document).ready(function() {
        $('#organizationId').on('change', function() {
            var organization_id = $('#organizationId').val();
            
            $.ajax({
                url: "{{ url('admin/notice/getOrganizationEmployee') }}",
                method: 'GET',
                data: {
                    organization_id: [organization_id]
                },
                success: function(data) {
                    $('#employeeId').empty();
                    $.each(data, function(id, name) {
                        $('#employeeId').append(new Option(name, id));
                    });
                    $('#employeeId').multiselect('rebuild');
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + error.message);
                }
            });
        });

       
    });

    
</script>
