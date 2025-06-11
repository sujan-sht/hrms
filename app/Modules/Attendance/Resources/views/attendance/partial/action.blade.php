@section('style')
<!-- Clock picker CSS -->
<link href="{{ asset('admin/assets/libs/clockpicker/bootstrap-clockpicker.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

<div class="row">

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">

                <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">General Detail</h5>


                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Title<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::select('title', $titleList, $value = null, ['id'=>'shiftTitle', 'class'=>'form-control', 'data-toggle'=>'select2']) !!}
                                @if($errors->first('title') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('title') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="customTitle" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Custom Title<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::text('custom_title', $value = null, ['placeholder'=>'Custom Title', 'class'=>'form-control', 'required']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Start Time</label>
                            </div>
                            <div class="col-md-12">
                                @php
                                    if(isset($shiftModel)) {
                                        $startTimeValue = $shiftModel->start_time;
                                    } else {
                                        $startTimeValue = "10:00";
                                    }
                                @endphp
                                <div class="input-group clockpicker">
                                    {!! Form::text('start_time', $value = $startTimeValue, ['class'=>'form-control']) !!}
                                    <span class="input-group-text"><i class="mdi mdi-clock-outline"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">End Time</label>
                            </div>
                            <div class="col-md-12">
                                @php
                                    if(isset($shiftModel)) {
                                        $endTimeValue = $shiftModel->end_time;
                                    } else {
                                        $endTimeValue = "17:00";
                                    }
                                @endphp
                                <div class="input-group clockpicker">
                                    {!! Form::text('end_time', $value = $endTimeValue, ['class'=>'form-control']) !!}
                                    <span class="input-group-text"><i class="mdi mdi-clock-outline"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <a href="{{ URL::previous() }}" class="btn btn-warning waves-effect waves-light me-1">
                        <span class="btn-label"><i class="mdi mdi-keyboard-backspace"></i></span>Go Back
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light">
                        <span class="btn-label"><i class="mdi mdi-content-save"></i></span>Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <img src="https://venushrms.com/img/featurres/roaster.png">
    </div>

</div>

@section('script')
<!-- Select2 JS -->
<script src="{{ asset('admin/assets/libs/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/pages/form-advanced.init.js') }}"></script>
<!-- Clock Picker JS -->
<script src="{{ asset('admin/assets/libs/spectrum-colorpicker2/spectrum.min.js') }}"></script>
<script src="{{ asset('admin/assets/libs/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
<!-- Init JS -->
<script src="{{asset('admin/assets/js/pages/form-pickers.init.js')}}"></script>
<script>
    $(function() {
        $('#shiftTitle').on('change', function() {
            var title = $(this).val();
            if(title == 'Custom') {
                $('#customTitle').show();
            } else {
                $('#customTitle').hide();
            }
        });
    });
</script>
@endsection
