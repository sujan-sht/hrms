<div>
    <style>
        .ndp-corner-all {
            top: 40px !important;
            left: 40px !important;
        }
    </style>
    <div class="row">
        <div class="col-lg-6" id="{{ $nepDateAttribute }}_container">
            <div class="input-group">
                <span class="input-group-text">Nep</span>
                <input type="text" id="{{ $nepDateAttribute }}" class="{{ $nepDateAttribute }} form-control"
                    placeholder="Select Nepali Date" value="{{ $nepali_date }}"
                    {{ !is_null($disabledCounterpartCalendar) ? ($disabledCounterpartCalendar == 'nep' ? 'disabled' : '') : '' }}>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="input-group" id="{{ $engDateAttribute }}_container">
                <span class="input-group-text">Eng</span>
                <input type="text" id="{{ $engDateAttribute }}" class="{{ $engDateAttribute }} form-control"
                    value="{{ $english_date }}"
                    {{ !is_null($disabledCounterpartCalendar) ? ($disabledCounterpartCalendar == 'eng' ? 'disabled' : '') : '' }}>
            </div>
        </div>
    </div>
    <input type="hidden" name="{{ $nepDateAttribute }}" value="{{ $nepali_date }}">
    <input type="hidden" name="{{ $engDateAttribute }}" value="{{ $english_date }}">
    {{-- Script --}}
    <link rel="stylesheet" href="{{ asset('admin/nepali_calender4/css/nepali.datepicker.v4.0.min.css') }}">
    <script type="text/javascript" src="{{ asset('admin/nepali_calender4/js/nepali.datepicker.v4.0.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
        $(document).ready(function() {
            // Initialize Calendar
            nepali_calendar();
            english_calendar();

            function nepali_calendar() {
                var nepali_calendar = document.getElementById("{{ $nepDateAttribute }}");
                nepali_calendar.nepaliDatePicker({
                    container: "#{{ $nepDateAttribute }}_container",
                    ndpYear: true,
                    ndpMonth: true,
                    ndpYearCount: 10,
                    onChange: function(date) {
                        var nep_date = date.bs;
                        var eng_date = date.ad;

                        syncDates(nep_date, eng_date);
                    }
                });
            }

            function english_calendar() {
                $('#{{ $engDateAttribute }}').daterangepicker({
                    parentEl: "#{{ $engDateAttribute }}_container",
                    autoUpdateInput: false,
                    singleDatePicker: true,
                    showDropdowns: true,
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                    autoApply: false,
                    autoUpdateInput: false,
                }, function(start, end, label) {
                    var date = start.format('YYYY-MM-DD');
                    var nep_date = NepaliFunctions.AD2BS(date);
                    var eng_date = date;

                    syncDates(nep_date, eng_date);
                });
            }

            function syncDates(nep_date, eng_date) {
                var nepali_calendar = $("#{{ $nepDateAttribute }}");
                var english_calendar = $("#{{ $engDateAttribute }}");
                nepali_calendar.val(nep_date).trigger('change');
                english_calendar.val(eng_date).trigger('change');

                $('input[name={{ $engDateAttribute }}]').val(eng_date);
                $('input[name={{ $nepDateAttribute }}]').val(nep_date);
            }
        })
    </script>
</div>
