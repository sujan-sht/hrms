@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@inject('dateconverter', '\App\Modules\Admin\Entities\DateConverter')

@php
    $currentRoute = Request::route()->getName();
    $Route = explode('.', $currentRoute);
    $current_join__date = '';
    $nepali_date_array = $dateconverter->eng_to_nep(date('Y'), date('m'), date('d'));
    if (Auth::user()->user_type != 'super_admin' && Auth::user()->user_type != 'admin') {
        $join_date = optional(Auth::user()->userEmployer)->join_date;
        $current_join__date = App\Helpers\DateTimeHelper::DateDiffInYearMonthDay($join_date, date('Y-m-d'));
    }
@endphp

<div class="page-header page-header-light">
    <div class="page-header-content header-elements-lg-inline">
        <div class="page-title d-flex">
            <h4><i class="icon-home4 mr-2"></i> <span class="font-weight-semibold">Welcome to Bidhee HRMS</span></h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        <div class="header-elements d-none">
            <div class="d-flex justify-content-center">
                @if (auth()->user()->user_type == 'super_admin' ||
                        auth()->user()->user_type == 'admin' ||
                        auth()->user()->user_type == 'hr')
                    <div style="width: 250px;" class="mr-2">
                        <div class="dropdown">
                            <input type="text" class="form-control" id="search-input" placeholder="Search Module.."
                                data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #b1a9a9;" />
                            <ul class="dropdown-menu" id="search-results"></ul>
                        </div>
                    </div>
                @endif

                <div class="mt-1">
                    {{ date('M d, Y') }} ({{ date('l') }}) |
                    @if (isset($nepali_date_array) && !empty($nepali_date_array))
                        <span>
                            {{ $nepali_date_array['date'] . ' ' . $nepali_date_array['nmonth'] . ', ' . $nepali_date_array['year'] . ' (' . $nepali_date_array['nepali_day'] . ')' }}</span>
                    @endif
                    @if (Auth::user()->user_type == 'super_admin' || Auth::user()->user_type == 'admin')
                        | <span id="displayTime">{{ date('h:i:s A') }}</span>
                    @endif
                </div>

                @if (Auth::user()->user_type != 'super_admin' && Auth::user()->user_type != 'admin')
                    <a href="#" class="btn btn-link btn-float text-dark">
                        <i class="icon-alarm text-primary"></i>
                        <span id="displayTime">{{ date('h:i:s A') }}</span>
                        <span><strong>Tenure: {{ $current_join__date }}</strong></span></a>
                @endif

            </div>
        </div>
    </div>

    <div
        class="breadcrumb-line breadcrumb-line-light header-elements-lg-inline d-flex justify-content-between align-items-center">
        <div class="d-flex">
            <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>PIMS</a>
            @yield('breadcrum')
            <a href="#" class="header-elements-toggle text-body d-lg-none"><i class="icon-more"></i></a>
        </div>
        <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank"> User Manual <i
                class="icon-help"></i></a>


    </div>
</div>

<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script>
    $(document).ready(function() {
        var urlmenu = document.getElementById('moduleData');
        urlmenu.onchange = function() {
            window.location.href = this.options[this.selectedIndex].value;
        };
    })
</script>
<script>
    $(document).ready(function() {
        $('#search-input').on('input', function() {
            let query = $(this).val();
            if (query.length > 1) {
                $.ajax({
                    url: "{{ route('search.active.modules') }}",
                    method: 'GET',
                    data: {
                        query: query
                    },
                    success: function(response) {

                        let results = '';
                        if (response.length > 0) {
                            response.forEach(module => {
                                results += `
                                <li>
                                    <a href="${module.link}" class="dropdown-item">
                                        ${module.name}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                </li>
                            `;
                            });
                        } else {

                            results += `
                                <li>
                                    <a href="{{ route('employee.index') }}" class="dropdown-item">
                                        Employee
                                    </a>
                                    <div class="dropdown-divider"></div>
                                </li>
                            `;

                        }

                        $('#search-results').html(results).show();
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });

            } else {
                $('#search-results').hide();
            }
        });

        // Hide dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('#search-results').hide();
            }
        });
    });
</script>
