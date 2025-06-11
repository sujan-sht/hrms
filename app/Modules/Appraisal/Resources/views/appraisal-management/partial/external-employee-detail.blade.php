@if ($is_edit)
    {{-- @foreach ($interviewLevelModel->getQuestionModels as $questionModel)
        <div class="row parent">
            <div class="col-lg-10 mb-3">
                <div class="row">
                    <label class="col-lg-2 col-form-label">Question :<span class="text-danger"> *</span></label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('questions[]', $questionModel->question, [
                                'placeholder' => 'Enter Question',
                                'class' => 'form-control',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 mb-3">
                <a class="btn btn-danger rounded-pill remove">
                    <i class="icons icon-minus-circle2 mr-1"></i>Remove
                </a>
            </div>
        </div>
    @endforeach
    <div class="row">
        <div class="col-lg-2 mb-3">
            <a id="addMore" class="btn btn-success rounded-pill">
                <i class="icons icon-plus-circle2 mr-1"></i>Add More
            </a>
        </div>
    </div> --}}
@else
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="row">
                <label class="col-form-label col-lg-2">Employee Name :<span class="text-danger"> *</span></label>
                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::text('ext_name[]', null, ['placeholder' => 'Enter Name', 'class' => 'form-control','autocomplete' => false]) !!}
                    </div>
                    @if ($errors->has('name'))
                        <div class="error text-danger">{{ $errors->first('name') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="row">
                <label class="col-form-label col-lg-2">Email :<span class="text-danger">
                        *</span></label>
                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        {!! Form::text('ext_email[]', null, [
                            'placeholder' => 'Email Address',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                    @if ($errors->has('email'))
                        <div class="error text-danger">{{ $errors->first('email') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-2 mb-3">
            <a id="addMore" class="btn btn-success rounded-pill addMore">
                <i class="icons icon-plus-circle2 mr-1"></i>Add More
            </a>
        </div>
    </div>
@endif
