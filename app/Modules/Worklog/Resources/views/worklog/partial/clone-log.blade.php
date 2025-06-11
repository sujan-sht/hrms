<div class="card clone-div">
    <div class="card-body">
        @if ($key > 0)
            <button type="button" class="btn btn-outline-danger mx-1 btn-remove float-right" value=""><i
                    class="icon-trash"></i>&nbsp;&nbsp;Remove</button>
        @endif
        <legend class="text-uppercase font-size-sm font-weight-bold">Log Detail</legend>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-4">Title :<span class="text-danger"> *</span></label>
                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('multi[' . $key . '][title]', $item ? $item->title : null, [
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

            <div class="col-md-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Time (In Hours):</label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('multi[' . $key . '][hours]', $item ? $item->hours : null, [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Time',
                                'id' => 'hours',
                            ]) !!}
                        </div>
                        @if ($errors->has('hours'))
                            <span class="text-danger">{{ $errors->first('hours') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-4">Status :<span class="text-danger"> *</span></label>
                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                        <div class="input-group">

                            {!! Form::select('multi[' . $key . '][status]', $statusList, $item ? $item->status : null, [
                                'id' => 'status',
                                'placeholder' => 'Choose Status',
                                'class' => 'form-control',
                            ]) !!}

                        </div>
                        @if ($errors->has('status'))
                            <span class="text-danger">{{ $errors->first('status') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Priority :</label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('multi[' . $key . '][priority]', $value = $item ? $item->priority : null, [
                                'placeholder' => 'Enter Priority',
                                'id' => 'priority',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        @if ($errors->has('priority'))
                            <span class="text-danger">{{ $errors->first('priority') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-4">Assigned To :</label>
                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('multi[' . $key . '][assigned_to]', $item ? $item->assigned_to : null, [
                                'placeholder' => 'Enter Assigned To',
                                'id' => 'assigned_to',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        @if ($errors->has('assigned_to'))
                            <span class="text-danger">{{ $errors->first('assigned_to') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="row">
                    @if (auth()->user()->user_type == 'admin' ||
                            auth()->user()->user_type == 'super_admin' ||
                            auth()->user()->user_type == 'hr')
                        <label class="col-form-label col-lg-3">Employee :<span class="text-danger"> *</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">

                                {!! Form::select('multi[' . $key . '][employee_id]', $employees, $item ? $item->employee_id : null, [
                                    'id' => 'employee_id',
                                    'placeholder' => 'Choose Employee',
                                    'class' => 'form-control',
                                ]) !!}

                            </div>
                            @if ($errors->has('employee_id'))
                                <span class="text-danger">{{ $errors->first('employee_id') }}</span>
                            @endif
                        </div>
                    {{-- @else
                        {!! Form::hidden('multi[0][employee_id]', auth()->user()->emp_id) !!} --}}
                    @endif
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <label class="col-form-label col-lg-2">Remarks:</label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::textarea('multi[' . $key . '][detail]', $item ? $item->detail : null, [
                                'id' => 'detail',
                                'placeholder' => 'Enter Remarks',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
