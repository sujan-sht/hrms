@extends('admin::layout')
@section('title')Setting @stop
@section('breadcrum')Edit Setting @stop

@section('script')
    <!-- Theme JS files -->
    <script src="{{asset('admin/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script src="{{asset('admin/global/js/demo_pages/form_inputs.js')}}"></script>
    <!-- /theme JS files -->
    <script src="{{ asset('admin/validation/setting.js')}}"></script>
    <script src="{{asset('admin/global/js/plugins/pickers/color/spectrum.js')}}"></script>
    <script src="{{asset('admin/global/js/demo_pages/picker_color.js')}}"></script>
    <script src="{{asset('admin/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script src="{{asset('admin/global/js/plugins/forms/styling/switch.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('.form-check-input-styled').uniform();
        });
    </script>


@stop
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card bd-card">
                <div class="bg-white card-header header-elements-inline">
                    <h6 class="card-title">Setting</h6>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-bottom border-bottom-0" style="position: absolute;">
                        <li class="nav-item"><a href="#bottom-divided-tab1" class="btn alpha-pink rounded-round nav-link active show" data-toggle="tab"><i class="text-slate-700 icon-cog mr-2"></i><span class="text-dark">Basic Setting</span></a>
                        </li>
                        <li class="nav-item"><a href="#bottom-divided-tab2" class="ml-3 alpha-pink rounded-round nav-link" data-toggle="tab"><i class="text-primary-400 icon-bucket mr-2"></i><span class="text-dark">Color Setting</span></a></li>
                        <li class="nav-item"><a href="#bottom-divided-tab3" class="ml-3 alpha-pink rounded-round nav-link" data-toggle="tab"><i class="text-primary-400 icon-book3 mr-2"></i><span class="text-dark">Payroll Setting</span></a></li>
                        <li class="nav-item"><a href="#bottom-divided-tab4" class="ml-3 alpha-pink rounded-round nav-link" data-toggle="tab"><i class="text-primary-400 icon-coin-dollar mr-2"></i><span class="text-dark">Finance Setting</span></a></li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="bottom-divided-tab1">

                            <div style="margin-top: 64px;">
                                {!! Form::model($setting,['method'=>'post','route'=>['setting.updatebasicsetting',$setting->id],'class'=>'form-horizontal','id'=>'organization_submit','role'=>'form','files'=>true]) !!}
                                    @include('setting::setting.partial.basic')
                                    <div class="text-right">
                                        <button type="submit" class="btn bg-teal-400"><i class="icon-plus-circle2"></i>
                                            Update Basic Setting
                                        </button>
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>

                        <div class="tab-pane fade" id="bottom-divided-tab2">

                            <div style="margin-top: 64px;">

                                {!! Form::model($setting,['method'=>'post','route'=>['setting.updatebasicsetting',$setting->id],'class'=>'form-horizontal','id'=>'organization_submit','role'=>'form','files'=>true]) !!}

                                <input type="hidden" value="">
                                    @include('setting::setting.partial.color')
                                    <div class="text-right">
                                        <button type="submit" class="btn bg-teal-400"><i class="icon-plus-circle2"></i>
                                            Update Color Setting
                                        </button>
                                    </div>
                               {!! Form::close() !!}

                            </div>
                        </div>

                        <div class="tab-pane fade"  id="bottom-divided-tab3">
                            <div style="margin-top: 64px;">
                                <form action="{{ route('setting.basicsetting') }}" method="post"
                                      enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    @include('setting::setting.partial.payroll')

                                    <div class="text-right">
                                        <button type="submit" class="btn bg-teal-400"><i class="icon-plus-circle2"></i>
                                            Save Payroll Setting
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <div class="tab-pane fade"  id="bottom-divided-tab4">
                            <div style="margin-top: 64px;">
                                <form action="{{ route('setting.basicsetting') }}" method="post"
                                      enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    @include('setting::setting.partial.finance')

                                    <div class="text-right">
                                        <button type="submit" class="btn bg-teal-400"><i class="icon-plus-circle2"></i>
                                            Save Finance Setting
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>




@stop
