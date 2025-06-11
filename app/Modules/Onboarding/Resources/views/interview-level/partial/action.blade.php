<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
            <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row">
                    <div class="col-lg-10 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Title :<span class="text-danger"> *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('title', null, ['placeholder'=>'Enter Title','class'=>'form-control', 'required']) !!}
                                </div>
                                @if($errors->has('title'))
                                    <div class="error text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <legend class="text-uppercase font-size-sm font-weight-bold">Questions</legend>
                @if($isEdit)
                    @foreach($interviewLevelModel->getQuestionModels as $questionModel)
                        <div class="row parent">
                            <div class="col-lg-10 mb-3">
                                <div class="row">
                                    <label class="col-lg-2 col-form-label">Question :<span class="text-danger"> *</span></label>
                                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::text('questions[]', $questionModel->question, ['placeholder'=>'Enter Question','class'=>'form-control','required']) !!}
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
                    <div class="form-repeater"></div>
                    <div class="row">
                        <div class="col-lg-2 mb-3">
                            <a id="addMore" class="btn btn-success rounded-pill">
                                <i class="icons icon-plus-circle2 mr-1"></i>Add More
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-lg-10 mb-3">
                            <div class="row">
                                <label class="col-lg-2 col-form-label">Question :<span class="text-danger"> *</span></label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('questions[]', null, ['placeholder'=>'Enter Question','class'=>'form-control', 'required']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <a id="addMore" class="btn btn-success rounded-pill">
                                <i class="icons icon-plus-circle2 mr-1"></i>Add More
                            </a>
                        </div>
                    </div>
                    <div class="form-repeater"></div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btns btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
<!-- validation js -->
<script src="{{ asset('admin/validation/interviewLevel.js')}}"></script>

<script src="{{asset('admin/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_inputs.js')}}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#addMore').on('click', function () {
            var html = '<div class="row parent"><div class="col-lg-10 mb-3"><div class="row">';
            html += '<label class="col-lg-2 col-form-label">Question :<span class="text-danger"> *</span></label>';
            html += '<div class="col-lg-10 form-group-feedback form-group-feedback-right">';
            html += '<div class="input-group">';
            html += '<input type="text" name="questions[]" class="form-control" placeholder="Enter Question" required>';
            html += '</div>'
            html += '</div></div></div>';
            html += '<div class="col-lg-2 mb-3">';
            html += '<a class="btn btn-danger rounded-pill remove">';
            html += '<i class="icons icon-minus-circle2 mr-1"></i>Remove';
            html += '</a>';
            html += '</div</div>';
            $('.form-repeater').append(html);
        });

        $(document).on('click', '.remove', function() {
            $(this).closest('.parent').hide();
        });
    });
</script>

@endSection
