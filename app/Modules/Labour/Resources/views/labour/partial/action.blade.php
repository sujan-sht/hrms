@php
    if(setting('calendar_type') == 'BS'){
        $classData = 'form-control nepali-calendar';
    }else{
        $classData = 'form-control daterange-single';
    }
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                
                <div class="form-group row">
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Organization:<span class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('organization', $organizations, null, [
                                    'placeholder' => 'Choose Organization',
                                    'class' => 'form-control',
                                    'id' => 'organization'
                                ]) !!}
                                </div>
                                @if ($errors->has('last_name'))
                                    <div class="error text-danger">{{ $errors->first('last_name') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">First Name:<span class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('first_name', null, ['placeholder' => 'Enter First Name', 'class' => 'form-control','required']) !!}
                                </div>
                                @if ($errors->has('first_name'))
                                    <div class="error text-danger">{{ $errors->first('first_name') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Middle Name:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('middle_name', null, ['placeholder' => 'Enter Middle Name', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('middle_name'))
                                    <div class="error text-danger">{{ $errors->first('middle_name') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Last Name:<span class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('last_name', null, ['placeholder' => 'Enter Last Name', 'class' => 'form-control','required']) !!}
                                </div>
                                @if ($errors->has('last_name'))
                                    <div class="error text-danger">{{ $errors->first('last_name') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Skill Type:<span class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('skill_type', $skills, null, [
                                    'placeholder' => 'Choose Skill Type',
                                    'class' => 'form-control',
                                    'id' => 'skill_type'
                                ]) !!}
                                </div>
                                @if ($errors->has('last_name'))
                                    <div class="error text-danger">{{ $errors->first('last_name') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Pan No.:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('pan_no', null, ['placeholder' => 'Enter Pan no', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('pan_no'))
                                    <div class="error text-danger">{{ $errors->first('pan_no') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Date of Join:<span class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('join_date', null, [
                                    'placeholder' => 'Select date',
                                    'class' => $classData,
                                    'id' => 'join_date'
                                ]) !!}
                                </div>
                                @if ($errors->has('join_date'))
                                    <div class="error text-danger">{{ $errors->first('join_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Attachment:</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::file('attachment', ['id' => 'attachment', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        
                    </div>
        
                </div>
                <div class="row">
                    <label class="col-form-label col-lg-3">Description:</label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::textarea('description', null, ['placeholder' => 'Enter Description', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

