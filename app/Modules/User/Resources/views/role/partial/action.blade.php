<script src="{{asset('admin/global/js/plugins/ui/fab.min.js')}}"></script>

<script src="{{asset('admin/global/js/plugins/ui/prism.min.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/extra_fab.js')}}"></script>
<script src="{{asset('admin/validation/role.js')}}"></script>

<!-- Top right menu -->
<ul class="fab-menu fab-menu-absolute fab-menu-top-right is_stuck" data-fab-toggle="hover" id="fab-menu-affixed-demo-right" style="position: fixed; top: 195px; width: 62px;">
    <li>
        <button type="submit" class="fab-menu-btn btn text-white  bg-pink btn-float rounded-round btn-icon"><i class="icon-database-insert" data-popup="tooltip" data-placement="bottom" data-original-title="{{ $btnType }}"></i></button>
    </li>
</ul>
<!-- /top right menu -->

<fieldset class="mb-3">
    <legend class="text-uppercase font-size-sm font-weight-bold"></legend>

    <div class="form-group row">
        <label class="col-form-label col-lg-2">Role Name:<span class="text-danger">*</span></label>
        <div class="col-lg-10">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-user-lock "></i>
                    </span>
                </span>
                {!! Form::text('name', $value = null, ['id'=>'name','placeholder'=>'Enter Role Name','class'=>'form-control','required' =>'required']) !!}
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-form-label col-lg-2">Select User Type :<span class="text-danger">*</span></label>
        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-users"></i></span>
                </span>
                {!! Form::select('user_type',$user_type ,$value = null, ['id'=>'user_type','class'=>'form-control']) !!}
            </div>
        </div>
    </div>

</fieldset>


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-dark text-white d-flex justify-content-between">
                <h6 class="card-title">List of Modules</h6>
            </div>
        </div>
    </div>
</div>

@php
    $module =array();
    $num = 1;
    $route_name = array();
    $route_list = array();
@endphp

@foreach($routes as $key => $value)
    @php $explode_module = explode(' ',$value);
        $route = $explode_module[0];
        $module[$route][$key] = $value;
        $route_name[] = $route;
        $num++;
    @endphp
@endforeach
@php
    $unique_route = array_unique($route_name);
@endphp

<div class="row">
    @foreach($unique_route as $routeKey => $routeVal)
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-secondary text-white header-elements-inline">
                    <h6 class="card-title">{{$routeVal}}</h6>
                    <div class="header-elements">
                        <button type="button" class="btn bg-success btn-icon select_all" data-popup="tooltip"
                                data-placement="top" data-original-title="Select All"><i
                                    class="icon-checkmark-circle2"></i></button>
                        <button type="button" class="btn bg-yellow btn-icon ml-3 clear_all" data-popup="tooltip"
                                data-placement="top" data-original-title="Clear All"><i class="icon-eraser2"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    @php
                        $new_module = array_shift($module);
                    @endphp

                    @foreach($new_module as $modkey => $modVal)
                        @php
                            $explode_module = explode(' ',$modVal);   
                            $checked = '';
                            $module_name = ((isset($explode_module[2])) ? ucfirst($explode_module[1]) .' '.ucfirst($explode_module[2]) : (isset($explode_module[1]))) ? ucfirst($explode_module[1]) : ucfirst($explode_module[0]);
                        @endphp
                        @if($routeVal == $explode_module[0])
                            @if(count($permission) > 0)
                                @php
                                $test['route_name']=$modkey;
                                if(in_array($test,$permission))
                                {
                                    $checked = "checked='checked'";
                                }
                                @endphp
                            @endif

                            <div class="form-check mb-1">
                                <label class="form-check-label">
                                    <input {{$checked}} type="checkbox" name="route_name[]" value="{{$modkey}}"
                                           class='form-check-input module_checkbox'/> {{$module_name}}
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="text-right">
    <button type="submit" class="submit_product ml-2 btn text-white bg-pink btn-labeled btn-labeled-left"><b><i class="icon-database-insert"></i></b> {{ $btnType }}</button>
</div>

<script type="text/javascript">

    $(document).ready(function () {

        $(document).on('click', '.select_all', function () {
            $(this).parent().parent().siblings().find('.module_checkbox').prop('checked', 'true');
        });

        $(document).on('click', '.clear_all', function () {
            $(this).parent().parent().siblings().find('.module_checkbox').prop('checked', false);
        });

    });

</script>