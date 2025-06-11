<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Title<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {{-- {!! Form::select('title', $titleList, isset($shiftModel) ? $shiftModel->title : '', [
                                    'id' => 'shiftTitle',
                                    'class' => 'form-control',
                                    'data-toggle' => 'select2',
                                ]) !!} --}}
                                {!! Form::text('title', isset($shiftModel) ? $shiftModel->title : '', [
                                    'id' => 'shiftTitle',
                                    'class' => 'form-control',
                                    'required'
                                ]) !!}
                                @if ($errors->first('title') != null)
                                    <ul class="parsley-errors-list filled" aria-hidden="false">
                                        <li class="parsley-required">{{ $errors->first('title') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div id="customTitle" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Custom Title<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::text('custom_title', isset($shiftModel) ? $shiftModel->custom_title : '', [
                                        'placeholder' => 'Custom Title',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Is Seasonal Shift?<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                <div>
                                    <input type="radio" name="seasonal" id="yes" value="1" {{ (isset($shiftModel->seasonal) && $shiftModel->seasonal==1) ? 'checked' : '' }}> <label
                                        for="yes"> Yes</label>
                                </div>
                                <div>
                                    <input type="radio" name="seasonal" id="no" value="0" {{ (isset($shiftModel->seasonal) && $shiftModel->seasonal==0) ? 'checked' : '' }}> <label
                                        for="no"> No</label>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <input type="hidden" name="isEdit" value="{{ $isEdit }}" id="isEdit">
                    <input type="hidden" name="shift_id" value="{{ isset($shiftModel) ? $shiftModel->id : null }}" id="shiftId">

                </div>
                <div class="seasonForm mt-3"></div> <!-- Container to append new forms -->
                {{-- <div class="nonSeasonal " style="{{ (isset($shiftModel->seasonal) && $shiftModel->seasonal==1) ? 'display: none' : ''}} " >
                    <legend class="text-uppercase font-size-sm font-weight-bold">Day-wise Shift Times</legend>
                    @if (isset($shiftModel->shiftDayWise) && count($shiftModel->shiftDayWise) > 0)
                        @foreach ($shiftModel->shiftDayWise as $key => $shiftDayWise)


                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label class="form-label">Day</label>
                                    <div class="input-group">
                                        {!! Form::text("day[$shiftDayWise->day]", $shiftDayWise->day, ['class' => 'form-control', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Checkin Start Time <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        {!! Form::time("checkin_start_time[$shiftDayWise->day]", isset($shiftDayWise->checkin_start_time) ? $shiftDayWise->checkin_start_time : '00:00', ['class' => 'form-control', 'required']) !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        {!! Form::time("start_time[$shiftDayWise->day]", isset($shiftDayWise->start_time) ? $shiftDayWise->start_time : '00:00', ['class' => 'form-control start-time', 'required']) !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">End Time <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        {!! Form::time("end_time[$shiftDayWise->day]", isset($shiftDayWise->end_time) ? $shiftDayWise->end_time : '00:00', ['class' => 'form-control end-time', 'required']) !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Total Hrs</label>
                                    <div class="input-group">
                                        {!! Form::text("total_hrs",null, ['class' => 'form-control total-hrs', 'readonly']) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else

                        @foreach($daysOfWeek as $day => $fullName)
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label class="form-label">Day</label>
                                    <div class="input-group">
                                        {!! Form::text("day[$day]", $day, ['class' => 'form-control', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Checkin Start Time <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        {!! Form::time("checkin_start_time[$day]", '08:00', ['class' => 'form-control', 'required']) !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        {!! Form::time("start_time[$day]", '10:00', ['class' => 'form-control start-time', 'required']) !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">End Time <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        {!! Form::time("end_time[$day]", '17:00', ['class' => 'form-control end-time', 'required']) !!}
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Total Hrs</label>
                                    <div class="input-group">
                                        {!! Form::text("total_hrs",null, ['class' => 'form-control total-hrs', 'readonly']) !!}
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    @endif
                </div> --}}
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

<script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>

<script>
    $(document).ready(function() {
        var numberIncr = 1;
        // Hide the form section by default
        $(".seasonForm").empty();

        // by default set to seasonal shift
        $(".nonSeasonal").hide();
        var isEdit = $("#isEdit").val();
        var shift_id = $("#shiftId").val();
        $.ajax({
            url: "{{ route('shift.getSeasonForm') }}",
            method: 'POST',
            data: {
                numberIncr: numberIncr,
                isEdit : isEdit,
                shift_id : shift_id,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                $(".seasonForm").append(data.result);
                numberIncr++; // Increment the form number for the next addition
            },
            error: function(xhr, status, error) {
                alert("An error occurred while loading the form. Please try again.");
            }
        });

        // Handle "Yes" radio button click
        // $("#yes").click(function() {
        //     $(".nonSeasonal").hide();
        //     var isEdit = $("#isEdit").val();
        //     var shift_id = $("#shiftId").val();
        //     $.ajax({
        //         url: "{{ route('shift.getSeasonForm') }}",
        //         method: 'POST',
        //         data: {
        //             numberIncr: numberIncr,
        //             isEdit : isEdit,
        //             shift_id : shift_id,
        //             _token: "{{ csrf_token() }}"
        //         },
        //         success: function(data) {
        //             $(".seasonForm").append(data.result);
        //             numberIncr++; // Increment the form number for the next addition
        //         },
        //         error: function(xhr, status, error) {
        //             alert("An error occurred while loading the form. Please try again.");
        //         }
        //     });
        // });
        // // $("#yes").trigger('click');
        // if ($("#yes").is(":checked")) {
        //     $("#yes").trigger('click');
        // }

        // Handle "No" radio button click
        $("#no").click(function() {
            $(".seasonForm").empty(); // Clear any previously added forms
            numberIncr = 1; // Reset numberIncr when "No" is clicked
            $(".nonSeasonal").show();
        });

        // Handle "Remove" button click for dynamic forms
        $(document).on('click', '.remove-btn', function(e) {
            e.preventDefault();
            $(this).parent().parent().parent().parent().remove(); // Remove the closest row (form section)
            numberIncr--; // Decrement the number if you want to maintain proper counting
        });

        // Handle "Add More" button in the dynamically added forms
        $(document).on('click', '.addMore', function() {
            $.ajax({
                url: "{{ route('shift.getSeasonForm') }}",
                method: 'POST',
                data: {
                    numberIncr: numberIncr,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    $(".seasonForm").append(data.result);
                    initializeNepaliCalendar();
                    numberIncr++;
                },
                error: function(xhr, status, error) {
                    alert("An error occurred while loading the form. Please try again.");
                }
            });
        });
        // Function to initialize the Nepali calendar on new inputs
        function initializeNepaliCalendar() {
            $(".nepali-calendar").nepaliDatePicker();
        }
    });



    $(function() {
        $('#shiftTitle').on('change', function() {
            var title = $(this).val();
            if (title == 'Custom') {
                $('#customTitle').show();
            } else {
                $('#customTitle').hide();
            }
        });

        $('#shiftTitle').trigger('change');
    });

    function calculateTotalHours(startTime, endTime) {
        const start = new Date('1970-01-01T' + startTime + 'Z');
        const end = new Date('1970-01-01T' + endTime + 'Z');
        if (end < start) {
            end.setDate(end.getDate() + 1);
        }
        const diff = end - start;
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

        const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
        return `${hours}.${formattedMinutes}`;
    }

    $('.start-time, .end-time').on('change', function() {
        const row = $(this).closest('.row');
        const startTime = row.find('.start-time').val();
        const endTime = row.find('.end-time').val();
        const totalHours = calculateTotalHours(startTime, endTime);
        row.find('.total-hrs').val(totalHours);
    });

    $('.start-time, .end-time').trigger('change');


</script>
