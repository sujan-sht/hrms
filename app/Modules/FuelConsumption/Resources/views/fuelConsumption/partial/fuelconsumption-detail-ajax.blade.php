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

</head>

<body>

    <div class="page-content">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr class="bg-slate-400">
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
                            <tr>
                                <td>{{1}}</td>
                                <td>{{ date('Y-m-d',strtotime($fuelConsumptionDetail->created_at)) }}</td>
                                <td>{{ optional($fuelConsumptionDetail->employeeInfo)->first_name .' '. optional($fuelConsumptionDetail->employeeInfo)->last_name }}</td>
                                <td>{{ $fuelConsumptionDetail->starting_place }}</td>
                                <td>{{ $fuelConsumptionDetail->destination_place }}</td>
                                <td>{{ $fuelConsumptionDetail->vehicle_no }}</td>
                                <td>{{ $fuelConsumptionDetail->start_km }}</td>
                                <td>{{ $fuelConsumptionDetail->end_km }}</td>
                                <td>{{ $fuelConsumptionDetail->km_travelled }}</td>
                                <td>{{ $fuelConsumptionDetail->purpose }}</td>
                                <td>{{ $fuelConsumptionDetail->parking_cost }}</td>
                            </tr>
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
                                            <th>{{ optional($fuelConsumptionDetail->userInfo)->first_name .' '. optional($fuelConsumptionDetail->userInfo)->last_name}}</th>
                                            <th>{{ optional($fuelConsumptionDetail->verifyInfo)->first_name .' '. optional($fuelConsumptionDetail->verifyInfo)->last_name}}</th>
                                            <th>{{ optional($fuelConsumptionDetail->approvedUserInfo)->first_name .' '. optional($fuelConsumptionDetail->approvedUserInfo)->last_name}}</th>
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

