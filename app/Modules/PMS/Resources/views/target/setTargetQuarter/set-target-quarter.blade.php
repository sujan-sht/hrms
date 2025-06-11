@extends('admin::layout')
@section('title') Set Target Quarter @endSection
@section('breadcrum')
    <a class="breadcrumb-item" href="{{route('target.index')}}">Targets</a>
    <a class="breadcrumb-item active">Set Target Quarter</a>
@endSection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Target Title</th>
                        <th>Quarter</th>
                        <th>Target Value</th>
                        <th>Achieved Value</th>
                        <th>Achieved (in %)</th>
                        <th>Score (in %)</th>
                        <th width="12%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($targetDetails) && !empty($targetDetails))
                        @foreach($targetDetails as $key => $targetDetail)
                            @for($i =1; $i <= $targetDetail->no_of_quarter; $i++)
                                <?php
                                    $target_achievement_model = $targetDetail->getDetails($targetDetail->id, $i);
                                    if($target_achievement_model){
                                        $target_value = $target_achievement_model->target_value;
                                        $achieved_value = $target_achievement_model->achieved_value;
                                        $achieved_percent = $target_achievement_model->achieved_percent;
                                        $score = $target_achievement_model->score;

                                    }else{
                                        $target_value = '';
                                        $achieved_value = '';
                                        $achieved_percent = '';
                                        $score = '';
                                    }
                                ?>
                                <tr>
                                    <td width="5%">#{{ $i }}</td>
                                    <td>{{ $targetDetail->title }}</td>
                                    <td>{{ $i }}</td>

                                    <td>{{$target_value}}</td>
                                    <td>{{$achieved_value}}</td>
                                    <td>{{$achieved_percent}}</td>
                                    <td>{{$score}}</td>
                                    <td>
                                        @if($target_value == '')
                                            <a data-toggle="modal" data-target="#setTargetValue" class="btn btn-outline-primary btn-icon rounded-round set_target_value" data-original-title="Set Target Value"  data-popup="tooltip" kra_val = "{{$targetDetail->kra_id}}" kpi_val = "{{$targetDetail->kpi_id}}" target_val = "{{$targetDetail->id}}" quarter_val = "{{$i}}" weightage_val = "{{$targetDetail->weightage}}"><i class="icon-target2"></i></a>
                                        @else
                                            <a data-toggle="modal" data-target="#setAchievedValue" class="btn btn-outline-success btn-icon rounded-round set_achieved_value" data-original-title="Set Achieved Value" data-popup="tooltip" kra_val = "{{$targetDetail->kra_id}}" kpi_val = "{{$targetDetail->kpi_id}}" target_val = "{{$targetDetail->id}}" quarter_val = "{{$i}}" weightage_val = "{{$targetDetail->weightage}}" get_target_value = "{{$target_value}}" get_achieved_value = "{{$achieved_value}}"><i class="icon-medal2"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Target Quarter Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Set Target Value modal -->
    <div id="setTargetValue" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-pink-800">
                    <h6 class="modal-title">Set Target Value</h6>
                </div>

                <div class="modal-body">
                    {!! Form::open(['route'=>'target.setValue','method'=>'POST','class'=>'form-horizontal', 'role'=>'form','files' => true]) !!}
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Target Value:</label>
                            <div class="col-lg-9">
                                {!! Form::text('target_value', $value = null, ['id'=>'target_value','placeholder'=>'Enter Target Value','class'=>'form-control numeric','required'=>'required']) !!}
                            </div>
                        </div>

                        {{ Form::hidden('kra_id', '', array('class' => 'kra')) }}
                        {{ Form::hidden('kpi_id', '', array('class' => 'kpi')) }}
                        {{ Form::hidden('target_id', '', array('class' => 'target')) }}
                        {{ Form::hidden('quarter', '', array('class' => 'quarter')) }}
                        {{ Form::hidden('weightage', '', array('class' => 'weightage')) }}

                        <div class="text-center">
                            <button type="submit" class="btn bg-success">Save</button>
                            <button type="button" class="btn bg-danger" data-dismiss="modal">Close</button>
                        </div>
                    {!! Form::close() !!}
                </div>

                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- Set Target Value modal -->

     <!-- Set Achieved Value modal -->
     <div id="setAchievedValue" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-pink-800">
                    <h6 class="modal-title">Set Achieved Value</h6>
                </div>

                <div class="modal-body">
                    {!! Form::open(['route'=>'target.setValue','method'=>'POST','class'=>'form-horizontal','role'=>'form','files' => true]) !!}
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Target Value:</label>
                            <div class="col-lg-9">
                                {!! Form::text('get_target_value', $value = null, ['id'=>'get_target_value','class'=>'form-control', 'readonly']) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Achieved Value:</label>
                            <div class="col-lg-9">
                                {!! Form::text('achieved_value', $value = null, ['id'=>'achieved_value','placeholder'=>'Enter Achieved Value','class'=>'form-control numeric','required'=>'required']) !!}
                            </div>
                        </div>
                        {{ Form::hidden('kra_id', '', array('class' => 'kra_class')) }}
                        {{ Form::hidden('kpi_id', '', array('class' => 'kpi_class')) }}
                        {{ Form::hidden('target_id', '', array('class' => 'target_class')) }}
                        {{ Form::hidden('quarter', '', array('class' => 'quarter_class')) }}
                        {{ Form::hidden('weightage', '', array('class' => 'weightage_class')) }}

                        <div class="text-center">
                            <button type="submit" class="btn bg-success">Save</button>
                            <button type="button" class="btn bg-danger" data-dismiss="modal">Close</button>
                        </div>
                    {!! Form::close() !!}
                </div>

                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- Set Achieved Value modal -->

    <script type="text/javascript">
        $('document').ready(function () {
            //set target value
            $('.set_target_value').on('click', function () {
                var kra_val = $(this).attr('kra_val');
                $('.kra').val(kra_val);

                var kpi_val = $(this).attr('kpi_val');
                $('.kpi').val(kpi_val);

                var target_val = $(this).attr('target_val');
                $('.target').val(target_val);

                var quarter_val = $(this).attr('quarter_val');
                $('.quarter').val(quarter_val);

                var weightage_val = $(this).attr('weightage_val');
                $('.weightage').val(weightage_val);
            });
            //

            //set achieved value
            $('.set_achieved_value').on('click', function () {
                var kra_val = $(this).attr('kra_val');
                $('.kra_class').val(kra_val);

                var kpi_val = $(this).attr('kpi_val');
                $('.kpi_class').val(kpi_val);

                var target_val = $(this).attr('target_val');
                $('.target_class').val(target_val);

                var quarter_val = $(this).attr('quarter_val');
                $('.quarter_class').val(quarter_val);

                var weightage_val = $(this).attr('weightage_val');
                $('.weightage_class').val(weightage_val);

                var get_target_value = $(this).attr('get_target_value');
                $('#get_target_value').val(get_target_value);

                var get_achieved_value = $(this).attr('get_achieved_value');
                $('#achieved_value').val(get_achieved_value);
            });
            //
        });
    </script>
@endsection
