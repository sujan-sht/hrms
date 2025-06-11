 <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Fuel Consumption</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/global/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/css/layout.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/css/components.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/css/colors.min.css')}}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <script src="{{asset('admin/assets/js/main/jquery.min.js')}}"></script>


<style type="text/css">
    .purchase_order td, .purchase_order th{
        padding: 9px;
    }
 .table thead tr td,.table tbody tr td{
                 border: 1px solid #000000!important;
            }
            
    @media print{
            .table thead tr td,.table thead tr th,.table tbody tr td,.table tbody tr th {
                 border: 1px solid #000000!important;
            }
        }
</style>
</head>

<body>
{{-- <body onload="window.print();window.onmouseover = function() { self.close(); }"> --}}
    <div class="page-content">
        <div class="content">
            <div class="card">
                <div class="card-body">                        
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>Created Date</th>
                                <th>Employee Name</th>
                                <th>Starting Place</th>
                                <th>Destination Place</th>
                                <th>Vehicle No.</th>
                                <th>Start Km</th>
                                <th>End Km</th>
                                <th>Km Travelled</th>
                                <th>Purpose</th>
                                <th>Parking Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td>{{1}}</td>
                            <td>{{ date('Y-m-d',strtotime($fuelconsumptionDetail->created_at)) }}</td>
                            <td>{{ optional($fuelconsumptionDetail->employeeInfo)->first_name .' '. optional($fuelconsumptionDetail->employeeInfo)->last_name }}</td>
                            <td>{{ $fuelconsumptionDetail->starting_place }}</td>
                            <td>{{ $fuelconsumptionDetail->destination_place }}</td>
                            <td>{{ $fuelconsumptionDetail->vehicle_no }}</td>
                            <td>{{ $fuelconsumptionDetail->start_km }}</td>
                            <td>{{ $fuelconsumptionDetail->end_km }}</td>
                            <td>{{ $fuelconsumptionDetail->km_travelled }}</td>
                            <td>{{ $fuelconsumptionDetail->purpose }}</td>
                            <td>{{ $fuelconsumptionDetail->parking_cost }}</td>
                    </tbody>
                    </table>
                    <br>
                    <br>

                    <div class="row">
                        <div class="col-sm-12">
                                <div class="table-responsive">
                                <table class="purchase_order table  table-lg mb-1 mt-1 text-center">
                                    <thead>
                                        <tr>
                                            <th>Created By</th>
                                            <th>Verified By</th>
                                            <th>Approved By</th>
                                        </tr>
                                        <tr>
                                            <th>.........................................</th>
                                            <th>.........................................</th>
                                            <th>.........................................</th>
                                        </tr>
                                        <tr>
                                            <th>{{ optional($fuelconsumptionDetail->userInfo)->first_name .' '. optional($fuelconsumptionDetail->userInfo)->last_name}}</th>
                                            <th>{{ optional($fuelconsumptionDetail->verifyInfo)->first_name .' '. optional($fuelconsumptionDetail->verifyInfo)->last_name}}</th>
                                            <th>{{ optional($fuelconsumptionDetail->approvedUserInfo)->first_name .' '. optional($fuelconsumptionDetail->approvedUserInfo)->last_name}}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<script>
    $(document).ready(function(){
        window.print();
    });
</script>

