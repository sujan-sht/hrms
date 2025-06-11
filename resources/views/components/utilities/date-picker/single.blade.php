{{-- Nepali Calendar --}}
@if ($default == 'nep')
    <input type="text" id="{{ $nepDateAttribute }}" class="{{ $nepDateAttribute }} form-control"
        placeholder="Select Nepali Date" value="{{ $nepali_date }}" name="{{ $nepDateAttribute }}">

    <link rel="stylesheet" href="{{ asset('admin/nepali_calender4/css/nepali.datepicker.v4.0.min.css') }}">
    <script type="text/javascript" src="{{ asset('admin/nepali_calender4/js/nepali.datepicker.v4.0.min.js') }}"></script>

    <script>
        nepali_calendar();

        function nepali_calendar() {
            var nepali_calendar = document.getElementById("{{ $nepDateAttribute }}");
            nepali_calendar.nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 10,
            });
        }
    </script>
@endif
{{-- English Calendar --}}
@if ($default == 'eng')
    <input type="text" id="{{ $engDateAttribute }}" class="{{ $engDateAttribute }} form-control"
        value="{{ $english_date }}" name="{{ $engDateAttribute }}">

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script>
        english_calendar();

        function english_calendar() {
            $('#{{ $engDateAttribute }}').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                autoApply: false,
                autoUpdateInput: false,
            });
        }
    </script>
@endif
