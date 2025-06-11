@section('script')
    <script src="{{ asset('admin/validation/leave.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>


    {{-- <script src="https://unpkg.com/nepali-date-picker@2.0.1/dist/jquery.nepaliDatePicker.min.js"
    crossorigin="anonymous">
</script>
<link rel="stylesheet" href="https://unpkg.com/nepali-date-picker@2.0.1/dist/nepaliDatePicker.min.css"
    crossorigin="anonymous" /> --}}

    <script>
        $(document).ready(function() {
            let minDate = new Date();

            var authUserType = $('#authUserType').val();
            if (authUserType == 'super_admin' || authUserType == 'hr') {
                minDate = $('#leaveYearStartDate').val();
            }

            $(".multiDate").flatpickr({
                mode: "multiple",
                dateFormat: "Y-m-d"
            });

            $('.employee-filter').on('change', function() {
                $('input[name=leave_kind]:radio:checked').trigger('change');
            });


            $('.leaveKind').prop('checked', false);
            $('#radio2').prop('checked', true);
            handleLeaveKindChange();

            $('.leaveKind').on('change', handleLeaveKindChange);

            function handleLeaveKindChange() {
                var leaveKind = $("input[name='leave_kind']:checked").val();
                $('#submitBtn').hide();
                $('#start_date').val('');
                $('#end_date').val('');

                switch (leaveKind) {
                    case '1':
                        $('.startDateDiv').show();
                        $('.endDateDiv').hide();
                        $('.halfTypeDiv').show();
                        $('.leaveTypeDiv').show();
                        $('.leaveDetailDiv').hide();
                        $('.datesDiv').hide();
                        $('.substituteDiv').hide();
                        $('#remainingLeaveDiv').show();
                        getRemainingLeaveDetail(leaveKind);
                        $('#submitBtn').show();
                        break;
                    case '2':
                        $('.startDateDiv').show();
                        $('.endDateDiv').show();
                        $('.halfTypeDiv').hide();
                        $('.leaveDetailDiv').show();
                        $('.leaveTypeDiv').show();
                        $('.datesDiv').hide();
                        $('.substituteDiv').hide();
                        $('#remainingLeaveDiv').show();
                        getRemainingLeaveDetail(leaveKind);
                        break;
                    case '3':
                        $('.startDateDiv').hide();
                        $('.endDateDiv').hide();
                        $('.halfTypeDiv').hide();
                        $('.leaveDetailDiv').hide();
                        $('.leaveTypeDiv').show();
                        $('.datesDiv').show();
                        $('.substituteDiv').hide();
                        $('#remainingLeaveDiv').show();
                        getRemainingLeaveDetail(leaveKind);
                        $('#submitBtn').show();
                        break;
                    case '4':
                        $('.startDateDiv').hide();
                        $('.endDateDiv').hide();
                        $('.halfTypeDiv').hide();
                        $('.leaveDetailDiv').hide();
                        $('.leaveTypeDiv').show();
                        $('.datesDiv').hide();
                        $('.substituteDiv').show();
                        $('#remainingLeaveDiv').show();
                        getRemainingLeaveDetail(leaveKind);
                        break;
                    default:
                        $('.startDateDiv').hide();
                        $('.endDateDiv').hide();
                        $('.halfTypeDiv').hide();
                        $('.leaveDetailDiv').hide();
                        $('.leaveTypeDiv').hide();
                        $('.datesDiv').hide();
                        $('.substituteDiv').hide();
                        $('#remainingLeaveDiv').hide();
                        break;
                }
            }

            // Old Code
            // $('.leaveKind').on('change', function() {
            //     var leaveKind = $(this).val();
            //     $('#submitBtn').hide();
            //     // $('#leave_category_loader').show();
            //     $('#start_date').val('');
            //     $('#end_date').val('');

            //     switch (leaveKind) {
            //         case '1':
            //             $('.startDateDiv').show();
            //             $('.endDateDiv').hide();
            //             $('.halfTypeDiv').show();
            //             $('.leaveTypeDiv').show();
            //             $('.leaveDetailDiv').hide();
            //             $('.datesDiv').hide();
            //             $('.substituteDiv').hide();
            //             $('#remainingLeaveDiv').show();
            //             getRemainingLeaveDetail(leaveKind);
            //             $('#submitBtn').show();
            //             // $('#leave_category_loader').hide();
            //             break;
            //         case '2':
            //             $('.startDateDiv').show();
            //             $('.endDateDiv').show();
            //             $('.halfTypeDiv').hide();
            //             $('.leaveDetailDiv').show();
            //             $('.leaveTypeDiv').show();
            //             $('.datesDiv').hide();
            //             $('.substituteDiv').hide();
            //             $('#remainingLeaveDiv').show();
            //             getRemainingLeaveDetail(leaveKind);
            //             // getEmployeeLeaveTypeList(leaveKind);
            //             break;
            //         case '3':
            //             $('.startDateDiv').hide();
            //             $('.endDateDiv').hide();
            //             $('.halfTypeDiv').hide();
            //             $('.leaveDetailDiv').hide();
            //             $('.leaveTypeDiv').show();
            //             $('.datesDiv').show();
            //             $('.substituteDiv').hide();
            //             $('#remainingLeaveDiv').show();
            //             getRemainingLeaveDetail(leaveKind);
            //             $('#submitBtn').show();
            //             break;
            //         case '4':
            //             $('.startDateDiv').hide();
            //             $('.endDateDiv').hide();
            //             $('.halfTypeDiv').hide();
            //             $('.leaveDetailDiv').hide();
            //             $('.leaveTypeDiv').show();
            //             $('.datesDiv').hide();
            //             $('.substituteDiv').show();
            //             $('#remainingLeaveDiv').show();
            //             getRemainingLeaveDetail(leaveKind);
            //             break;
            //         default:
            //             $('.startDateDiv').hide();
            //             $('.endDateDiv').hide();
            //             $('.halfTypeDiv').hide();
            //             $('.leaveDetailDiv').hide();
            //             $('.leaveTypeDiv').hide();
            //             $('.datesDiv').hide();
            //             $('.substituteDiv').hide();
            //             $('#remainingLeaveDiv').hide();
            //             break;
            //     }
            // });

            var calendar_type = localStorage.getItem('calendar_type');
            $('#leaveType').on('change', function() {
                $('#submitBtn').hide();
                $('#start_date').val('');
                $('#end_date').val('');
                $('#noticeList').html('');
                $('#warningMessage').hide();

                var leaveType = $('#leaveType').val();
                var leaveTypeDetail = $('#leaveType-' + leaveType).attr('data-leave-type');
                leaveTypeDetail = jQuery.parseJSON(leaveTypeDetail);

                checkRemainingLeave(leaveType);

                if (leaveTypeDetail['code'] == 'SIKLV') {
                    minDate = $('#leaveYearStartDate').val();
                    $('.substituteDiv').hide();

                } else if (leaveTypeDetail['code'] == 'SUBLV') {
                    $('.substituteDiv').show();
                    // $('.endDateDiv').hide();
                    $.ajax({
                        url: "{{ route('leave.getSubstituteDateList') }}",
                        method: 'GET',
                        data: {
                            employee_id: $('#employeeId').val(),
                        },
                        success: function(resp) {
                            let values = JSON.parse(resp)
                            if (values.length > 0) {
                                $('.substitute_date').empty();


                                $('.substitute_date').select2({
                                    data: values,
                                    placeholder: 'Choose Date', // Placeholder text
                                    templateResult: function(item) {
                                        return $('<span>' + item.date +
                                            '<em> <span class="text-danger">  [Expires: ' +
                                            item.expiry_date +
                                            ']</span> <span class="badge badge-success badge-pill">' +
                                            item.nod + '</span></em></span>');
                                    },
                                    templateSelection: function(item) {
                                        return $('<span>' + item.date +
                                            '<em> <span class="text-danger">  [Expires: ' +
                                            item.expiry_date +
                                            ']</span> <span class="badge badge-success badge-pill">' +
                                            item.nod + '</span></em></span>');
                                    },

                                });
                                $('.substitute_date').trigger('change');
                            }
                        }
                    });
                } else {
                    $('.substituteDiv').hide();
                    minDate = $('#leaveYearStartDate').val();
                }
                if (calendar_type == 'BS') {
                    nepStartDate(minDate)
                } else {
                    startDate(minDate);
                }

            });

            function checkRemainingLeave(leaveType) {
                var employeeId = $('#employeeId').val();
                var leaveYearId = $('#leave_year_id').val();

                if (employeeId && leaveType) {
                    $.ajax({
                        url: "{{ route('leave.getRemainingLeave') }}", // Add this route in your web.php
                        method: 'GET',
                        data: {
                            leave_year_id: leaveYearId,
                            employee_id: employeeId,
                            leave_type: leaveType,
                        },
                        success: function(remaining_leave) {
                            if (remaining_leave <= 0) {
                                $('#warningMessage').text(
                                    'Warning: You have no remaining leave for the selected leave type.'
                                ).show();
                            }
                        }
                    });
                }
            }

            $('.substitute_date').on('change', function() {
                var leaveType = $('#leaveType').val();
                var leaveTypeDetail = $('#leaveType-' + leaveType).attr('data-leave-type');
                leaveTypeDetail = jQuery.parseJSON(leaveTypeDetail);

                var minDate = $(this).val();
                if (calendar_type == 'BS') {
                    engMinDate = (NepaliFunctions.BS2AD(minDate));
                    var maxDate = moment(engMinDate).add(leaveTypeDetail.max_substitute_days, 'days')
                        .format('YYYY-MM-DD')
                    nepMaxDate = (NepaliFunctions.AD2BS(maxDate));
                    nepStartDate(minDate, nepMaxDate)
                } else {
                    var maxDate = moment(minDate).add(leaveTypeDetail.max_substitute_days, 'days').format(
                        'YYYY-MM-DD')
                    startDate(minDate, maxDate);
                }

            })

            function nepStartDate(minDate, maxDate = '') {
                $("#start_date").nepaliDatePicker({
                    onChange: function() {
                        $('#end_date').val('');
                        $('#noticeList').html('');

                        var leaveType = $('#leaveType').val();
                        var maxDays = $('#leaveType-' + leaveType).attr('data');
                        var startDate = $('#start_date').val();
                        var employeeId = $('#employeeId').val();

                        var leaveKind = $("input[name='leave_kind']:checked").val();
                        if (leaveKind == 1 && maxDays >= 0.5 && maxDays < 1) {
                            maxDays = 1;
                        }
                        var params = {
                            'maxDays': maxDays,
                            'startDate': startDate,
                            'leaveType': leaveType,
                            'employeeId': employeeId,

                        };
                        preProcessData(params);
                    },
                    disableBefore: minDate,
                    disableAfter: maxDate,
                });

            }

            function nepEndDate(minDate, maxDate) {
                $("#end_date").nepaliDatePicker({
                    onChange: function() {
                        var employeeId = $('#employeeId').val();
                        var leaveType = $('#leaveType').val();
                        var maxDays = $('#leaveType-' + leaveType).attr('data');
                        var startDate = $('#start_date').val();
                        var endDate = $('#end_date').val();
                        var params = {
                            'employeeId': employeeId,
                            'leaveType': leaveType,
                            'maxDays': maxDays,
                            'startDate': startDate,
                            'endDate': endDate,
                            'leaveKind': $("input[name='leave_kind']:checked").val()

                        };
                        postProcessData(params);
                    },
                    disableBefore: minDate,
                    disableAfter: maxDate,
                });

            }
            //this is for old style work
            // function startDate(minDate, maxDate = '') {
            //     $('#start_date').daterangepicker({
            //         parentEl: '.content-inner',
            //         singleDatePicker: true,
            //         showDropdowns: true,
            //         minDate: minDate,
            //         maxDate: maxDate,

            //         autoUpdateInput: false,
            //         locale: {
            //             format: 'YYYY-MM-DD'
            //         }
            //     }).on('apply.daterangepicker', function(ev, picker) {
            //         $(this).val(picker.startDate.format('YYYY-MM-DD'));
            //         $('#end_date').val('');
            //         $('#noticeList').html('');

            //         var leaveType = $('#leaveType').val();
            //         var maxDays = $('#leaveType-' + leaveType).attr('data');
            //         var startDate = $('#start_date').val();
            //         var employeeId = $('#employeeId').val();

            //         var leaveKind = $("input[name='leave_kind']:checked").val();
            //         if (leaveKind == 1 && maxDays >= 0.5 && maxDays < 1) {
            //             maxDays = 1;
            //         }
            //         var params = {
            //             'maxDays': maxDays,
            //             'startDate': startDate,
            //             'leaveType': leaveType,
            //             'employeeId': employeeId,

            //         };
            //         preProcessData(params);
            //     });
            // }

            function startDate(minDate, maxDate = '') {
                const $input = $('#start_date');

                // Set min and max attributes
                $input.attr('min', minDate);
                if (maxDate) {
                    $input.attr('max', maxDate);
                }

                $('#start_date').on('change', function() {
                    console.log('hello')
                    const startDate = $(this).val();
                    $('#end_date').val('');
                    $('#noticeList').html('');

                    const leaveType = $('#leaveType').val();
                    let maxDays = $('#leaveType-' + leaveType).attr('data');
                    const employeeId = $('#employeeId').val();
                    const leaveKind = $("input[name='leave_kind']:checked").val();

                    if (leaveKind == 1 && maxDays >= 0.5 && maxDays < 1) {
                        maxDays = 1;
                    }

                    const params = {
                        maxDays: maxDays,
                        startDate: startDate,
                        leaveType: leaveType,
                        employeeId: employeeId
                    };

                    preProcessData(params);
                });
            }


            function endDate(id, minDate, maxDate) {
                const $input = $('#end_date');

                // Set min and max attributes
                $input.attr('min', minDate);
                $input.attr('max', maxDate);

                // Optional: Trigger the date picker on click (for browsers that support it)
                $input.on('click', function() {
                    this.showPicker && this.showPicker(); // triggers the native date picker if available
                });

                // On value change
                $('#end_date').on('change', function() {
                    console.log('click in end' + id)
                    const endDate = $(this).val();
                    const startDate = $('#start_date').val();
                    const employeeId = $('#employeeId').val();
                    const leaveType = $('#leaveType').val();
                    let maxDays = $('#leaveType-' + leaveType).attr('data');
                    const leaveKind = $("input[name='leave_kind']:checked").val();

                    const params = {
                        employeeId: employeeId,
                        leaveType: leaveType,
                        maxDays: maxDays,
                        startDate: startDate,
                        endDate: endDate,
                        leaveKind: leaveKind
                    };

                    postProcessData(params);
                });
            }


            function preProcessData(params) {
                leave_kind = $("input[name='leave_kind']:checked").val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('leave.preProcessData') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        params: params
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.endDate == null || response.numberOfDays < 0) {
                            $('.endDateDiv').hide();
                        } else if (leave_kind == 1) {
                            $('.endDateDiv').hide();
                            var start_date = $('#start_date').val()
                            var end_date = $('#end_date').val(start_date);

                            console.log(start_date + endDate)

                            params['endDate'] = start_date;
                            params['leaveKind'] = leave_kind;

                            endDate('endDate', $('#start_date').val(), start_date);
                            postProcessData(params);

                        } else {
                            $('.endDateDiv').show();
                            if (calendar_type == 'BS') {
                                nepEndDate($('#start_date').val(), response.endDate)
                            } else {
                                endDate('endDate', $('#start_date').val(), response.endDate);
                            }

                        }
                    }
                });
            }

            function postProcessData(params) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('leave.postProcessData') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        params: params
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.noticeList) {
                            $('#noticeList').addClass('col-lg-12');
                            $('#noticeList').html(response.noticeList);
                            if (response.restrictSave == 'true') {
                                $('#submitBtn').hide();
                            } else {
                                $('#submitBtn').show();
                            }
                        } else {
                            $('#noticeList').hide();
                        }
                    }
                });
            }

            function checkDayOff() {
                var total_days = $('[name^="number_of_days[]"]').serializeArray();
                sum_day = 0;
                $.each(total_days, function(i, v) {
                    if (v.value != "") {
                        sum_day += parseInt(v.value);
                    }
                });

                $('.dayOff').empty();
                $.ajax({
                    type: 'GET',
                    url: "{{ route('leave.check.dayoff') }}",
                    data: {
                        sum_day: 0,
                        start_date: $('[name^="start_date"]').val()
                    },
                    success: function(data) {
                        if (data !== 'undefined' && data !== '') {
                            $('<div/>', {
                                'class': 'card card-body',
                                html: '<h3>Day Off:</h3><p class="text-danger">' +
                                    data +
                                    '</p>',
                            }).appendTo($('.dayOff'));
                        }

                    }
                });
            }

            function getRemainingLeaveDetail(leaveKind) {
                employee_id = $('#employeeId').val();

                if (employee_id) {
                    $.ajax({
                        url: "{{ route('leave.getRemainingList') }}",
                        method: 'GET',
                        data: {
                            leave_year_id: $('#leave_year_id').val(),
                            employee_id: $('#employeeId').val(),
                            leave_kind: leaveKind,
                        },
                        success: function(resp) {
                            $('#remainingLeaveDetail').html(resp.view);

                            $('#leaveType').empty();
                            let option =
                                "<option selected disabled>Select Leave Type</option>";
                            resp.leaveTypeList.map(item => {
                                option +=
                                    `<option value=${item.key}>${item.value}</option>`
                            });
                            $('#leaveType').append(option);
                        }
                    });
                } else {
                    $('#remainingLeaveDetail').empty();
                }


            }

            // function getEmployeeLeaveTypeList(leaveKind) {
            //     $.ajax({
            //         url: "{{ route('leave.getList') }}",
            //         method: 'GET',
            //         data: {
            //             leave_year_id: $('#leave_year_id').val(),
            //             employee_id: $('#employeeId').val(),
            //             leave_kind: leaveKind,
            //         },
            //         success: function(resp) {
            //             $('#leaveDetailForm').html(resp);
            //         }
            //     });
            // }

            $('#alt_employee_id').on('change', function() {
                $('#alt_employee_message').closest('.col-lg-12').removeClass('d-none');
            });

            $('#employeeId').on('change', function() {
                var emp_id = $('#employeeId').val();
                var logged_in_emp_id = $('#logged_in_emp_id').val();

                if (emp_id === logged_in_emp_id) {
                    var options = '';
                    options += "<option value='1'>Pending</option>";
                    $('#statusList').html(options);
                } else {
                    var options = '';
                    options += "<option value='1'>Pending</option>";
                    options += "<option value='2'>Forwarded</option>";
                    options += "<option value='3' selected>Accepted</option>";
                    options += "<option value='4'>Rejected</option>";

                    $('#statusList').html(options);
                }
            })
        });
    </script>
@endSection
