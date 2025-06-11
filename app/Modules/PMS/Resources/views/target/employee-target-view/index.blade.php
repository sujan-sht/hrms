@extends('admin::layout')
@section('title') Employee Target Report @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Employee Target Report</a>
@endSection

@section('content')

    @if (auth()->user()->user_type != 'employee')
        @include('pms::target.employee-target-view.partial.search')
    @endif

    <div class="card card-body">
        <h1 class="text-center mt-4">{{ $setting->company_name ?? ''}}</h1>
        <h3 class="text-center">Key Performance Indicator Achievement {{ $fiscalYear->fiscal_year ?? ''}}</h3>
        @if (isset($employeeModel))
            <div class="col-lg-12 mb-2">
                <span>Employee: {{ $employeeModel->full_name }}</span><br>
                <span>Organization: {{ optional($employeeModel->organizationModel)->name }}</span><br>
                <span>Department: {{ optional($employeeModel->department)->title }}</span><br>
                <span>Designation: {{ optional($employeeModel->designation)->title }}</span>
            </div>
        @endif
        <table class="table table-bordered">
            <thead class="bg-slate text-center text-white">
                <tr>
                    <th rowspan="3">S.N</th>
                    <th rowspan="3">KRA</th>
                    <th rowspan="3">KPIs</th>
                    <th rowspan="3">Target</th>
                    <th rowspan="3">Frequency/Age</th>
                    <th rowspan="3">Weightage</th>
                    <th colspan="8">TARGET VALUES</th>
                </tr>
                <tr>
                    <th colspan="1">Q1</th>
                    <th colspan="1">Q2</th>
                    <th colspan="1">Q3</th>
                    <th colspan="1">Q4</th>
                </tr>
                <tr>
                    <th>TGT</th>
                    <th>TGT</th>
                    <th>TGT</th>
                    <th>TGT</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $count = 1;
                @endphp
                @forelse ($targetAchievementModel as $key => $targetAchievement)
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ optional($targetAchievement->first()->kraInfo)->title }}</td>
                        <td>{{ optional($targetAchievement->first()->kpiInfo)->title }}</td>
                        <td>{{ optional($targetAchievement->first()->targetInfo)->title }}</td>
                        <td>{{ optional($targetAchievement->first()->targetInfo)->frequency }}</td>
                        <td>{{ optional($targetAchievement->first()->targetInfo)->weightage }}</td>

                        @for ($i = 0; $i < 4; $i++)
                            @php
                                $target_value = $targetAchievement[$i]->target_value ?? '';
                            @endphp
                            <td>{{ $target_value}}</td>
                        @endfor
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">
                            No Records Found !!!
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
@endSection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
@endSection
