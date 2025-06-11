@extends('admin::layout')
@section('title') Setting @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Setting</a>
@endsection

@section('script')
    <!-- Theme JS files -->
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <!-- /theme JS files -->
    <script src="{{ asset('admin/validation/setting.js') }}"></script>
@stop
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card bd-card">
                <div class="card-body">
                    @if ($is_edit)
                        {!! Form::model($payrollSetting, [
                            'method' => 'PUT',
                            'route' => ['payrollSetting.update'],
                            'class' => 'form-horizontal',
                            'id' => 'setting_submit',
                            'role' => 'form',
                            'files' => true,
                        ]) !!}
                    @else
                        {!! Form::open([
                            'route' => 'payrollSetting.store',
                            'id' => 'setting_submit',
                            'method' => 'POST',
                            'class' => 'form-horizontal',
                            'role' => 'form',
                            'files' => true,
                        ]) !!}
                    @endif

                    <fieldset class="mb-1">

                        <legend class="text-uppercase font-size-sm font-weight-bold">Payroll Calender Setting</legend>
                        <h6 style="text-decoration: underline;">Note: Once You Set Calendar Type, You cannot change it.</h6>
                            <div class="form-group row">
                                @foreach($organizationList as $key=>$value)
                                {!! Form::hidden('organization_id[]', $value->id) !!}
                                    <div class="col-lg-6 mb-2">
                                        <div class="row">
                                            <label class="col-form-label col-lg-4">{{$value->name}} :<span class="text-danger"> *</span></label>
                                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                                <div class="input-group">
                                                    @if(optional($value->payrollCalender)->calendar_type)
                                                    {!! Form::select('calendar_type[]', $calendarTypeList, optional($value->payrollCalender)->calendar_type, ['id'=>'calendarType', 'class'=>'form-control select-search', 'disabled']) !!}
                                                    {!! Form::hidden('calendar_type[]', optional($value->payrollCalender)->calendar_type, ['id'=>'calendarType']) !!}
                                                    @else
                                                        {!! Form::select('calendar_type[]', $calendarTypeList, optional($value->payrollCalender)->calendar_type, ['id'=>'calendarType', 'class'=>'form-control select-search']) !!}
                                                    @endif
                                                </div>
                                                @if($errors->has('calendar_type'))
                                                    <div class="error text-danger">{{ $errors->first('calendar_type') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                               
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6 mb-2">
                                    <div class="row">
                                        <label class="col-form-label col-lg-4">Enable Branch wise payroll :</label>
                                        <div class="col-lg-8 form-group-feedback form-group-feedback-right d-flex justify-content-between">
                                            <div class="input-group" style="width: 10%;">
                                                <input type="radio" name="branch_type" value="1"  {{ @$branchType->branch_type=='1' ? 'checked':''}} class = "form-check-input" >
                                                <label for="1" class ="form-check-label">Yes</label>
                                            </div>
                                             <div class="input-group" >
                                                <input type="radio" name="branch_type" value="0"  class = "form-check-input" {{ @$branchType->branch_type=='0' ? 'checked':''}}>
    
                                                <label for="0" class ="form-check-label">No</label>
                                            </div>
                                            @if($errors->has('calendar_type'))
                                                <div class="error text-danger">{{ $errors->first('calendar_type') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                               
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6 mb-2">
                                    <div class="row">
                                        <label class="col-form-label col-lg-4">Enable Unit wise payroll :</label>
                                        <div class="col-lg-8 form-group-feedback form-group-feedback-right d-flex justify-content-between">
                                            <div class="input-group" style="width: 10%;">
                                                <input type="radio" name="unit_type" value="1"  class = "form-check-input" {{ @$unitType->unit_type=='1' ? 'checked':''}}>
                                                <label for="1" class ="form-check-label">Yes</label>
                                            </div>
                                             <div class="input-group">
                                                <input type="radio" name="unit_type" value="0"  class = "form-check-input" {{ @$unitType->unit_type=='0' ? 'checked':''}}>
    
                                                <label for="0" class ="form-check-label">No</label>
                                            </div>
                                            @if($errors->has('calendar_type'))
                                                <div class="error text-danger">{{ $errors->first('calendar_type') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                       

                    </fieldset>

                   
                    <div class="text-center">
                        <button type="submit" class="ml-2 text-white btn bg-pink btn-labeled btn-labeled-left"><b><i
                                    class="icon-database-insert"></i></b>{{ $btnType }} Changes</button>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>


        </div>
    </div>


@stop
