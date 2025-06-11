@extends('admin::layout')
@section('title')Employee Shift @stop
@section('breadcrum')HR Activities Setup / Shift Management / Shift Analytics @stop
@section('script')
    <script src="{{asset('admin/global/js/plugins/extensions/jquery_ui/interactions.min.js')}}"></script>
    <script src="{{asset('admin/global/js/plugins/extensions/jquery_ui/touch.min.js')}}"></script>
    <script src="{{asset('admin/global/js/demo_pages/components_collapsible.js')}}"></script>


@stop

@section('content')
    <!-- Form inputs -->
    <div class="card">
        <div class="card-header bg-teal header-elements-inline">
            <h5 class="card-title">Shift Chart</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center" colspan="2">Legend</th>
                        </tr>
                        <tr>
                            <th><span class="badge badge-success"><i class="icon-pencil6"></i></span></th>
                            <th>Assigned</th>
                        </tr>
                        <tr>
                            <th><span class="badge badge-danger"><i class="icon-pencil6"></i></span></th>
                            <th>Not-Assigned</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th colspan="2">Shift Name</th>
                            <th>Shift Start Time</th>
                            <th>Shift End Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($shifts as $shift_key=>$shift)
                            <tr>
                                <td>{{++$shift_key}}</td>
                                <td class="text-teal"><b>{!! substr($shift->title ,0, 1) !!}</b></td>
                                <td>{{$shift->title}}</td>
                                <td>{{$shift->start_time}}</td>
                                <td>{{$shift->end_time}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <hr>
            <div class="table-responsive">
                <div class="card-group-control card-group-control-right" id="accordion-control-right">
                    @if($group_data->count() !=0)
                        @foreach($group_data as $key=>$group_data_value)
                            <div class="card">
                                <div class="card-header bg-teal">
                                    <h6 class="card-title">
                                        <a data-toggle="collapse" class="text-default collapsed"
                                           href="#accordion-control-right-group{{$key}}"># {{$group_data_value->group_name}}</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-right-group{{$key}}" class="collapse" data-parent="#accordion-control-right">
                                    <div class="card-body">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr class="bg-slate">
                                                    <th>Employee Name</th>
                                                    <th>Sunday</th>
                                                    <th>Monday</th>
                                                    <th>Tuesday</th>
                                                    <th>Wednesday</th>
                                                    <th>Thursday</th>
                                                    <th>Friday</th>
                                                    <th>Saturday</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($group_data_value->getGroupMember as $group_member_value)
                                                <tr>
                                                    <td class="font-weight-bold">{{ $group_member_value->getEmployee->first_name }} {{ $group_member_value->getEmployee->last_name }}</td>
                                                    @foreach ($days as $key_days => $day)
                                                        <td>
                                                            @foreach ($shifts as $shift)
                                                                @php
                                                                    $check = $shift->checkShift($group_member_value->group_member, $shift->id,$key_days,$group_data_value->id);
                                                               
                                                                @endphp

                                                                @if ($check)
                                                                    <a data-toggle="modal"
                                                                       data-target="#modal_theme_warning"
                                                                       data-placement="bottom" data-popup="tooltip"
                                                                       data-original-title="Remove Shift"
                                                                       class="text-center remove-employee-shift btn btn-outline-success btn-sm text-success"
                                                                       link="{{ route('employeeshift.remove', ['employee_id' => $group_member_value->group_member, 'shift_id' => $shift->id,'days'=>$key_days,'group_id'=>$group_data_value->id]) }}">
                                                                        {!! substr($shift->title ,0, 1) !!}
                                                                    </a>
                                                                @else
                                                                    <a data-toggle="modal"
                                                                       data-target="#modal_theme_warning"
                                                                       data-placement="bottom" data-popup="tooltip"
                                                                       data-original-title="Add Shift"
                                                                       class="text-center add-employee-shift btn btn-outline-danger btn-sm text-danger"
                                                                       link="{{ route('employeeshift.add', ['employee_id' => $group_member_value->group_member  , 'shift_id' => $shift->id,'days'=>$key_days,'group_id'=>$group_data_value->id]) }}">
                                                                        {!! substr($shift->title ,0, 1) !!}
                                                                    </a>

                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    @else
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">
                                    Employee Group Is Not Made
                                    <a href="{{ route('group.index') }}" title="Group Management"
                                       class="btn bg-teal-400 float-right">
                                        <i class="icon-users"> Group Management</i>
                                    </a>
                                </h6>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if (auth()->user()->userType == 'super_admin')
        <span style="margin: 5px;float: right;">
        @if($employees->total() != 0)
                {{ $employees->links() }}
            @endif
    </span>
    @endif


    <!-- Warning modal -->
    <div id="modal_theme_warning" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h6 class="modal-title">Are you sure to make changes to shift ?</h6>
                </div>

                <div class="modal-body">
                    <a class="btn btn-success get_link" href="">Yes</a> &nbsp; | &nbsp;
                    <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /warning modal -->


    <script type="text/javascript">
        $('document').ready(function () {
            $('.remove-employee-shift').on('click', function () {
                var link = $(this).attr('link');
                console.log(link);
                $('.get_link').attr('href', link);
            });

            $('.add-employee-shift').on('click', function () {
                var link = $(this).attr('link');
                $('.get_link').attr('href', link);
            });
        });
    </script>

@endsection
