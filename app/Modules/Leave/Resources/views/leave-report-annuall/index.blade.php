@extends('admin::layout')
@section('title') Leave Annuall Summary @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Leave Annuall Summary</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@section('css')
<style>
     thead tr:nth-child(2) th {
            background: #546e7a;
            position: sticky;
            top: 46px;
            /* z-index: 2; */
        }
    .table-scroll {
        position: relative;
        max-width: 100%;
        margin: auto;
        overflow: hidden;
        /* border: 0.5px; */
    }

    .table-wrap {
        width: 100%;
        overflow: auto;
    }

    .table-scroll table {
        width: 100%;
        margin: auto;
        border-collapse: separate;
        border-spacing: 0;
    }

    /* .table-scroll th, */
    .table-scroll td {
        /* background: #fff; */
        /* white-space: nowrap; */
        vertical-align: top;
        text-align: center;
    }

    .table-scroll th {
        background: #fff;
        /* white-space: nowrap; */
        /* vertical-align: top; */
        text-align: center;
    }

    .table-bg-white {
        background: #fff;
    }

    .table-scroll thead,
    .table-scroll tfoot {
        background: #f9f9f9;
    }

    .freeze1 {
        position: sticky;
        left: 0;
    }

    .freeze2 {
        position: sticky;
        left: 60px;
    }

    .freeze3 {
        position: sticky;
        left: 120px;
    }

    .freeze4 {
        position: sticky;
        left: 180px;
    }

    .freeze5 {
        position: sticky;
        left: 528px;
    }


    .freeze-head {
        background: #546e7a;
        position: sticky;
        top: 32px;
    }

    .hd {
        z-index: 2 !important;
    }
    .hold{
        background-color: yellow !important;
    }
    @keyframes blink {
        50% {
            opacity: 0;
        }
    }
    .viewTaxCalculation {
        animation: blink 1s infinite;
    }
</style>
@endsection
@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
</div>

@include('leave::leave-report-annuall.partial.advance-filter', ['route' => route('leave.annualSummary')])

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Leave Overview</h6>
            All the Leaves Overview Information will be listed below.
        </div>
        <div class="list-icons mt-2">
            <div class="dropdown position-static">
                <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false">
                    <i class="icon-more2"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    {{-- <a href="#" class="dropdown-item" data-toggle="modal" data-target="#previous_leave_detail_import">
                        <i class="icon-file-excel text-success"></i> Import
                    </a> --}}
                    {{-- <a href="{{ route('leave.exportLeaveOverview', request()->all()) }}#" class="dropdown-item">
                        <i class="icon-file-excel text-success"></i> Export
                    </a> --}}
                    <a id="exportToExcel"  class="dropdown-item">
                        <i class="icon-file-excel text-success"></i> Export
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="card card-body">
    <div class="col-md-1 mb-1">
        <label class="form-label">Per Page</label>
        {!! Form::select('page_limit', ['30'=>30,'50'=>50,'100'=>100,'200'=>200,'500'=>500,'All'=>'All'], $value = request('page_limit') ? : null, ['class'=>'form-control filterPageLimit']) !!}
    </div>
    <div id="table-scroll" class="table-scroll">

        <h6 class="text-center">Leave Year {{ @$leaveYearDetail->calendar_type=='nep' ? @$leaveYearDetail->leave_year : @$leaveYearDetail->leave_year_english }}</h6>
        <div class="table-wrap">
            <table class="table table-responsive table-bordered" id="table2excel">
                <thead>
                    <tr class="text-light btn-slate">
                        <th rowspan="2" class="freeze-head freeze1 hd">S.N</th>
                        <th rowspan="2" class="freeze-head freeze2 hd">Employee Code</th>
                        <th rowspan="2" class="freeze-head freeze2 hd">Full Name</th>
                        <th rowspan="2" class="freeze-head freeze2 hd">Nepali Joining Date</th>
                        <th rowspan="2" class="freeze-head freeze2 hd">Department</th>
                        <th rowspan="2" class="freeze-head freeze2 hd">Designation</th>
                        <th rowspan="2" class="freeze-head freeze2 hd">Employment Status</th>
                        <th rowspan="2" class="freeze-head freeze2 hd">Branch</th>
                        <th colspan="7" class="text-center freeze-head">{{ @$leaveName }} ({{ @$leaveYearDetail->calendar_type=='nep' ? @$leaveYearDetail->leave_year : @$leaveYearDetail->leave_year_english }})</th>
                        <th rowspan="2" class="freeze-head freeze2">Remarks</th>

                    </tr>
                    <tr class="text-white">
                        <th>Opening</th>
                        <th>{{ @$leaveCode }} ({{ @$leaveYearDetail->calendar_type=='nep' ? @$leaveYearDetail->leave_year : @$leaveYearDetail->leave_year_english }})</th>
                        <th>{{ @$leaveCode }} Taken</th>
                        <th>Balance</th>
                        <th>Encashable Limit</th>
                        <th>Encashable Leave</th>
                        <th>Closing Balance</th>
                    </tr>
                </thead>
                @if($showStatus)
                <tbody>
                    @foreach ($employeeLeaveSummaries as $key=>$emp)
                       <tr>
                            <td>
                                {{ $key+1 }}
                            </td>
                            <td>
                                {{ @$emp->employee_code }}
                            </td>
                            <td>
                                {{ @$emp->getFullName() }}
                            </td>
                            <td>
                                {{ @$emp->nepali_join_date }}
                            </td>
                            <td>
                                {{ optional($emp->department)->title }}
                            </td>
                            <td>
                                {{ optional($emp->designation)->title }}
                            </td>
                            <td>
                                {{optional($emp->payrollRelatedDetailModel)->getJobType() }}
                            </td>
                            <td>
                                {{ optional($emp->branchModel)->name }}
                            </td>
                            @foreach ($emp['employeeLeaveDetails'] as $leaveDetail)
                                <td>{{ $leaveDetail }}</td>
                            @endforeach
                            <td>
                                <input type="text" class="form-control form-control-sm" value="{{ @$emp->carry_forward_text }}" readonly>
                            </td>
                       </tr>
                    @endforeach
                </tbody>
                @endif
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <ul class="pagination pagination-rounded justify-content-end mb-3">

            @if ($showStatus && $employeeLeaveSummaries->total() != 0)
                {{ $employeeLeaveSummaries->appends(request()->all())->links() }}
            @endif
        </ul>
    </div>
</div>
@endsection

@section('script')

<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
<script src="{{ asset('admin/validation/validation.js') }}"></script>
<script src="{{ asset('admin/js/jquery.table2excel.js') }}"></script>
<script>
    $('document').ready(function() {
        $('.filterPageLimit').change(function(e){
            var pageLimit=$(this).val();
            $('#page_limit').val(pageLimit);
            $('#filterForm').submit();
        });
        $("#exportToExcel").click(function(e) {
            var table = $('#table2excel');
            if (table && table.length) {
                var clone = $(table).clone();
                clone.find('input[type="hidden"]').remove();
                clone.find('td span.email').remove();
                clone.table2excel({
                    exclude: ".noExl",
                    name: "Payroll Report",
                    filename: "payroll_report_" + new Date().toISOString().replace(/[\-\:\.]/g,
                        "") + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true
                });
            }
        });
        $('#leave_year_id').change(function(){
            var leaveyearId=$(this).val();
            var selectedType="{{ @request()->get('leave_type_id') ?? null}}";
            $.ajax({
                url:"{{ route('getLeaveYearTypeLeave') }}",
                type:"get",
                data:{
                    leaveyearId:leaveyearId
                },
                success:function(response){
                    if(response.error){
                        return false;
                    }
                    $('#leave_type_id').html('');
                    var leaveTypeHtml='';
                    leaveTypeHtml+='<option value="">Select Leave Type</option>';
                    $.each(response.data,function(index,value){
                        leaveTypeHtml +=`<option value="${index}" ${selectedType==index ? "selected":""}>${value}</option>`;
                    });
                    $('#leave_type_id').html(leaveTypeHtml);
                }
            });
        });

        $('#leave_year_id').change();
    });
</script>

@endSection
