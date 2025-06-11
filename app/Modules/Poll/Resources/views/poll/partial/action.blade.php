<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Poll Detail</legend>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Question : <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::text('question', null, ['class' => 'form-control', 'placeholder' => 'Write question here..']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-lg-12">
                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Multiple Option Status : <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                @php
                                                    $selectedStatus = null;
                                                    if (isset($_GET['multiple_option_status'])) {
                                                        $selectedStatus = $_GET['multiple_option_status'];
                                                    }
                                                    
                                                @endphp
                                                {!! Form::select('multiple_option_status', $multipleOptionStatus, $selectedStatus, [
                                                    'class' => 'form-control select-search',
                                                    'placeholder' => 'Select Status',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="col-lg-12">
                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Poll Date : <span
                                            class="text-danger">*</span></label>


                                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                <div class="p-1 rounded">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        {{ Form::radio('type', 1, isset($isEdit) && $isEdit == true && $pollModel->type == 1 ? 'checked' : '', ['class' => 'custom-control-input check_type', 'id' => 'PostNow']) }}

                                                        <label class="custom-control-label" for="PostNow">Send Now</label>
                                                    </div>

                                                    <div class="custom-control custom-radio custom-control-inline">
                                                            {{ Form::radio('type', 2, isset($isEdit) && $isEdit == true && $pollModel->type == 2 ? 'checked' : '', ['class' => 'custom-control-input check_type', 'id' => 'Schedule']) }}

                                                        <label class="custom-control-label" for="Schedule">Schedule</label>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($errors->has('type'))
                                                <span class="text-danger">{{ $errors->first('type') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 schedule-row d-none">
                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Schedule Date : <span
                                            class="text-danger">*</span></label>

                                        <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                @php
                                                    $startDate = null;
                                                    if(setting('calendar_type') == 'BS'){
                                                        $clData = 'form-control nepali-calendar';
                                                        if($isEdit && $pollModel['start_date']){
                                                            $startDate = date_converter()->eng_to_nep_convert($pollModel['start_date']);
                                                        }
                                                    }else{
                                                        $clData = 'form-control poll_date_picker';
                                                        if($isEdit && $pollModel['start_date']){
                                                            $startDate = $pollModel['start_date'];
                                                        }
                                                    }
                                                @endphp
                                                {!! Form::text('start_date', $value = $startDate, [
                                                    'placeholder' => 'Choose date..',
                                                    'class' => $clData,
                                                ]) !!}
                                            </div>
                                            @if ($errors->has('start_date'))
                                                <span class="text-danger">{{ $errors->first('start_date') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Expiry Date : <span
                                            class="text-danger">*</span></label>
                                        <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                @php
                                                    $expiryDate = null;
                                                    if(setting('calendar_type') == 'BS'){
                                                        $clData = 'form-control nepali-calendar';
                                                        if($isEdit && $pollModel['expiry_date']){
                                                            $expiryDate = date_converter()->eng_to_nep_convert($pollModel['expiry_date']);
                                                        }
                                                    }else{
                                                        $clData = 'form-control poll_date_picker';
                                                        if($isEdit && $pollModel['expiry_date']){
                                                            $expiryDate = $pollModel['expiry_date'];
                                                        }
                                                    }
                                                @endphp

                                                {!! Form::text('expiry_date', $expiryDate, [
                                                    'rows' => 5,
                                                    'placeholder' => 'Select date..',
                                                    'class' => $clData,
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Display Count : <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::select('show_on_employee', ['11'=>'Yes', '10'=>'No'], $_GET['multiple_option_status'] ?? null, [
                                                    'class' => 'form-control select-search',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('poll::poll.partial.options')

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
    <script src="{{ asset('admin/validation/poll.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>



    <script>
        $(document).ready(function() {
            $(".addMore").click(function() {
                $.ajax({
                    url: "<?php echo route('poll.getRepeaterForm'); ?>",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $(".repeaterForm").append(data.result);
                        $(".select-search").select2();
                        $('.numeric').keyup(function() {
                            if (this.value.match(/[^0-9.]/g)) {
                                this.value = this.value.replace(/[^0-9.]/g, '');
                            }
                        });
                    }
                });
            });

            $('.check_type').on('click', function() {
                var type = $(this).val();
                $('.schedule-row').addClass('d-none');

                if (type == 2) {
                    $('.schedule-row').removeClass('d-none');
                }
            })
            let is_edit = "{{ $isEdit }}"
            if (is_edit) {
                $('.check_type:checked').trigger('click');
            }

            $('.poll_date_picker').daterangepicker({
                parentEl: '.content-inner',
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                minDate: new Date(),
                locale: {
                    format: 'YYYY-MM-DD'
                }

            });
            $('.poll_date_picker').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });

            $('.poll_date_picker').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>
@endSection
