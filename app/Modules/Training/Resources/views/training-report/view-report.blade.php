@extends('admin::layout')
@section('title') Training Report @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Training Report</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-4">
            <legend class="text-uppercase font-size-sm font-weight-bold">Training Facilitation</legend>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <table class="table table-bordered">
                            {{-- @dd($facilitation); --}}
                            <thead class="bg-slate text-center text-white">
                                <tr>
                                    <th>Facilitation</th>
                                    <th>Count of Module</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ 'External' }}</td>
                                    <td>{{ $facilitation['external'] }}</td>
                                </tr>

                                <tr>
                                    <td>{{ 'Internal' }}</td>
                                    <td>{{ $facilitation['internal'] }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ 'Grand Total' }}</b></td>
                                    <td><b>{{ $facilitation['grand_total'] }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <legend class="text-uppercase font-size-sm font-weight-bold">Training Location</legend>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <table class="table table-bordered">
                            <thead class="bg-slate text-center text-white">
                                <tr>
                                    <th>Location</th>
                                    <th>Count of Module</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ 'Physical' }}</td>
                                    <td>{{ $location['physical'] }}</td>
                                </tr>

                                <tr>
                                    <td>{{ 'Virtual' }}</td>
                                    <td>{{ $location['virtual'] }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ 'Grand Total' }}</b></td>
                                    <td><b>{{ $location['grand_total'] }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <legend class="text-uppercase font-size-sm font-weight-bold">Training Type</legend>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <table class="table table-bordered">
                            <thead class="bg-slate text-center text-white">
                                <tr>
                                    <th>Type</th>
                                    <th>Count of Module</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ 'Behavioural' }}</td>
                                    <td>{{ $type['behavioural'] }}</td>
                                </tr>

                                <tr>
                                    <td>{{ 'Functional' }}</td>
                                    <td>{{ $type['functional'] }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ 'Grand Total' }}</b></td>
                                    <td><b>{{ $type['grand_total'] }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <legend class="text-uppercase font-size-sm font-weight-bold">Training MIS-{{ setting('company_name') }} GROUP :
                2079/2080</legend>
            <div class="card card-body">
                <div class=" table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-slate text-center text-white">
                            <tr>
                                <th colspan="14">Number of Mandays </th>
                            </tr>
                            <tr style="background-color: #546e7a; text-white;">
                                <th>Training</th>
                                <th>Organization</th>
                                <th>Shrawan</th>
                                <th>Bhadra</th>
                                <th>Ashwin</th>
                                <th>Kartik</th>
                                <th>Mangshir</th>
                                <th>Poush</th>
                                <th>Magh</th>
                                <th>Falgun</th>
                                <th>Chaitra</th>
                                <th>Baisakh</th>
                                <th>Jestha</th>
                                <th>Ashad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_mandays_Shrawan = 0;
                                $total_mandays_Bhadra = 0;
                                $total_mandays_Ashwin = 0;
                                $total_mandays_Kartik = 0;
                                $total_mandays_Mangshir = 0;
                                $total_mandays_Poush = 0;
                                $total_mandays_Magh = 0;
                                $total_mandays_Falgun = 0;
                                $total_mandays_Chaitra = 0;
                                $total_mandays_Baisakh = 0;
                                $total_mandays_Jestha = 0;
                                $total_mandays_Ashad = 0;
                            @endphp

                            @if (isset($no_of_mandays_month_division_wise) && !empty($no_of_mandays_month_division_wise))
                                @foreach ($no_of_mandays_month_division_wise as $key => $value)
                                    <tr>
                                        <td>{{ $value->title }}</td>

                                        <td>{{ optional($value->organization)->name }}</td>

                                        @if (optional($value->monthInfo)->dropvalue == 'Shrawan')
                                            <td>{{ $value->no_of_mandays }}</td>
                                            @php
                                                $total_mandays_Shrawan += $value->no_of_mandays;
                                            @endphp
                                        @else
                                            <td>-</td>
                                        @endif

                                        @if (optional($value->monthInfo)->dropvalue == 'Bhadra')
                                            <td>{{ $value->no_of_mandays }}</td>
                                            @php
                                                $total_mandays_Bhadra += $value->no_of_mandays;
                                            @endphp
                                        @else
                                            <td>-</td>
                                        @endif

                                        @if (optional($value->monthInfo)->dropvalue == 'Ashwin')
                                            <td>{{ $value->no_of_mandays }}</td>
                                            @php
                                                $total_mandays_Ashwin += $value->no_of_mandays;
                                            @endphp
                                        @else
                                            <td>-</td>
                                        @endif

                                        @if (optional($value->monthInfo)->dropvalue == 'Kartik')
                                            <td>{{ $value->no_of_mandays }}</td>
                                            @php
                                                $total_mandays_Kartik += $value->no_of_mandays;
                                            @endphp
                                        @else
                                            <td>-</td>
                                        @endif

                                        @if (optional($value->monthInfo)->dropvalue == 'Mangshir')
                                            <td>{{ $value->no_of_mandays }}</td>
                                            @php
                                                $total_mandays_Mangshir += $value->no_of_mandays;
                                            @endphp
                                        @else
                                            <td>-</td>
                                        @endif

                                        @if (optional($value->monthInfo)->dropvalue == 'Poush')
                                            <td>{{ $value->no_of_mandays }}</td>
                                            @php
                                                $total_mandays_Poush += $value->no_of_mandays;
                                            @endphp
                                        @else
                                            <td>-</td>
                                        @endif

                                        @if (optional($value->monthInfo)->dropvalue == 'Magh')
                                            <td>{{ $value->no_of_mandays }}</td>
                                            @php
                                                $total_mandays_Magh += $value->no_of_mandays;
                                            @endphp
                                        @else
                                            <td>-</td>
                                        @endif

                                        @if (optional($value->monthInfo)->dropvalue == 'Falgun')
                                            <td>{{ $value->no_of_mandays }}</td>
                                            @php
                                                $total_mandays_Falgun += $value->no_of_mandays;
                                            @endphp
                                        @else
                                            <td>-</td>
                                        @endif

                                        @if (optional($value->monthInfo)->dropvalue == 'Chaitra')
                                            <td>{{ $value->no_of_mandays }}</td>
                                            @php
                                                $total_mandays_Chaitra += $value->no_of_mandays;
                                            @endphp
                                        @else
                                            <td>-</td>
                                        @endif

                                        @if (optional($value->monthInfo)->dropvalue == 'Baisakh')
                                            <td>{{ $value->no_of_mandays }}</td>
                                            @php
                                                $total_mandays_Baisakh += $value->no_of_mandays;
                                            @endphp
                                        @else
                                            <td>-</td>
                                        @endif

                                        @if (optional($value->monthInfo)->dropvalue == 'Jestha')
                                            <td>{{ $value->no_of_mandays }}</td>
                                            @php
                                                $total_mandays_Jestha += $value->no_of_mandays;
                                            @endphp
                                        @else
                                            <td>-</td>
                                        @endif

                                        @if (optional($value->monthInfo)->dropvalue == 'Ashad')
                                            <td>{{ $value->no_of_mandays }}</td>
                                            @php
                                                $total_mandays_Ashad += $value->no_of_mandays;
                                            @endphp
                                        @else
                                            <td>-</td>
                                        @endif
                                    </tr>
                                @endforeach

                                <tr>
                                    <td><b>Total</b></td>
                                    <td></td>
                                    <td><b>{{ $total_mandays_Shrawan }}</b></td>
                                    <td><b>{{ $total_mandays_Bhadra }}</b></td>
                                    <td><b>{{ $total_mandays_Ashwin }}</b></td>
                                    <td><b>{{ $total_mandays_Kartik }}</b></td>
                                    <td><b>{{ $total_mandays_Mangshir }}</b></td>
                                    <td><b>{{ $total_mandays_Poush }}</b></td>
                                    <td><b>{{ $total_mandays_Magh }}</b></td>
                                    <td><b>{{ $total_mandays_Falgun }}</b></td>
                                    <td><b>{{ $total_mandays_Chaitra }}</b></td>
                                    <td><b>{{ $total_mandays_Baisakh }}</b></td>
                                    <td><b>{{ $total_mandays_Jestha }}</b></td>
                                    <td><b>{{ $total_mandays_Ashad }}</b></td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="13">No Records Found !!!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop
