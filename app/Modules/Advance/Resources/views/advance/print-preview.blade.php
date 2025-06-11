
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Advance Preview</title>

        <!-- Global stylesheets -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
        type="text/css">
        <link href="{{asset('admin/assets/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('admin/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('admin/assets/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('admin/assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('admin/assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('admin/assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">

        <link href="{{asset('admin/assets/css/style.css')}}" rel="stylesheet" type="text/css">
        <!-- /global stylesheets -->
        <!-- /global stylesheets -->

        <link href="{{ asset('admin/css/additional.css') }}" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="{{ asset('admin/css/colors.css') }}">

        <!-- Core JS files -->
        <script src="{{ asset('admin/global/js/main/jquery.min.js') }}"></script>
        <script src="{{ asset('admin/global/js/main/jquery.min.js') }}"></script>
        <script src="/bootstrap.min.js"></script>
        <script src="{{ asset('admin/global/js/main/bootstrap.bundle.min.js') }}"></script>
        <!-- /core JS files -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

        <!-- Theme JS files -->
        <script src="{{asset('admin/assets/js/plugins/prism.min.js')}}"></script>
        <script src="{{asset('admin/assets/js/plugins/sticky.min.js')}}"></script>

        <script src="{{asset('admin/assets/js/main/app.js')}}"></script>
        <script src="{{asset('admin/assets/js/pages/components_scrollspy.js')}}"></script>

    </head>



<body style="overflow:auto">
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0 50px 0 50px;  /* this affects the margin in the printer settings */
        }
    </style>

    <div class="salary-sheet">
        <div class="container">
            {{-- <div class="row">
                <div class="col-10">
                    <div class="salary-sheet-body">
                        <div class="salary-paid-block">
                            @if($payrollModel->calendar_type == 'nep')
                            @php $month = date_converter()->_get_nepali_month($payrollModel->month) @endphp
                            @else
                            @php $month = date_converter()->_get_english_month($payrollModel->month) @endphp
                            @endif
                            <h4>Employee Salary Transfer Letter of
                                {{ ($payrollModel->year.' - ' . $month) }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <a id="exportToExcel" class="btn btn-success rounded-pill">Export Report</a>
                </div>
            </div> --}}
            <div class="row" style="margin-top: 200px;">
                <div class="col-12">
                    <div class="salary-sheet-header">
                        <div class="company-name">
                            <h1>{{optional(optional($advanceModel->employeeModel)->organizationModel)->name}}</h1><br />

                        </div>
                        <div class="company-logo">
                            @if(!empty($setting->company_logo) && file_exists(public_path('uploads/setting/'.$setting->company_logo)))
                                <img src="{{asset('uploads/setting/'.$setting->company_logo)}}" />
                            @else
                                <img src="{{asset('admin/assets/images/salary-image/logo.png')}}" alt=""/>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                {{-- <div class="col-12">
                    <div class="salary-sheet-body">
                        <div class="salary-paid-block">
                            <span>Dear Sir, </span><br />
                            <p>On behalf of {{ optional($payrollModel->organization)->name }}, I request
                            you to please debit salary of all our employees from our bank A/C No. {{ $payrollModel->organization->bank_account_no }}, the details of which have been mentioned below: </p>
                        </div>
                    </div>
                </div> --}}
                <div class="table-responsive">
                    <table class="table table-striped" id="table2excel">
                        <thead class="bg-slate-700 text-white">
                            <tr>
                                <th>S.No.</th>
                                <th>Created Date</th>
                                <th>Organization Name</th>
                                <th>Branch</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Advance Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>1</td>
                                @if (setting('calendar_type') == 'BS')
                                    @if (!is_null($advanceModel->from_date))
                                        {{ date_converter()->eng_to_nep_convert($advanceModel->from_date) }}
                                    @endif
                                @else
                                    <td>{{$advanceModel->from_date}}</td>
                                @endif
                                <td>{{optional(optional($advanceModel->employeeModel)->organizationModel)->name}}</td>
                                <td>{{optional(optional($advanceModel->employeeModel)->branchModel)->name}}</td>
                                <td>{{optional($advanceModel->employeeModel)->getFullName()}}</td>
                                <td>{{optional(optional($advanceModel->employeeModel)->designation)->title}}</td>
                                <td>{{$advanceModel->advance_amount}}</td>
                                
                            </tr>
                        </tbody>
                    </table>
                    <span style="margin: 5px;float: right;">
                    </span>
                </div>
            </div>
            <div class="row" style="margin-top:50px">
                <div class="col-12">
                    <div class="salary-sheet-footer">
                        <div class="salary-footer-column">
                            <div class="footer-title">Created By:</div>
                        </div>
                        <div class="salary-footer-column">
                            <div class="footer-title">Verified By</div>
                        </div>
                        <div class="salary-footer-column">
                            <div class="footer-title">Approved By</div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary float-right" id="print-salary-slip">
                <i class="icon-printer"></i>&nbsp&nbsp&nbspPrint
            </button>
        </div>
    </div>
</body>

<script>
    $(document).ready(function () {
        $('#print-salary-slip').click(function () {
            $(this).hide();
            window.print();
            $(this).show();
        });
    });
</script>
</html>
