{{-- <script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js')}}"></script>
<script src="{{ asset('admin/global/js/demo_pages/picker_date.js')}}"></script>
<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_select2.js')}}"></script>
<script src="{{ asset('admin/validation/fuelConsumption.js')}}"></script> --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <fieldset class="mb-3">
                    <legend class="text-uppercase font-size-sm font-weight-bold"></legend>
                    @if(strtolower(Auth::user()->user_type) == 'employee')
                        {!!Form::hidden('emp_id', Auth::user()->emp_id, ['id' => 'employee_id'])!!}
                    @else
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <label class="col-form-label col-lg-3">Employee:<span class="text-danger">*</span></label>
                                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::select('emp_id',$employee, $value = null, ['id'=>'employee_id','placeholder'=>'Select Employee','class'=>'select-search form-control', 'required']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    @endif
                
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <div class="row">
                               <label class="col-form-label col-lg-3">Starting Place: <span class="text-danger">*</span></label>
                               <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                    {!! Form::text('starting_place', null, ['id'=>'starting_place_id','placeholder'=>'Enter Starting Place','class'=>'startingPlace form-control', 'required']) !!}
                                    </div>
                               </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                               <label class="col-form-label col-lg-3">Destination Place: <span class="text-danger">*</span></label>
                               <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                    {!! Form::text('destination_place', null, ['id'=>'destination_place_id','placeholder'=>'Enter Destination Place','class'=>'destinationPlace form-control', 'required']) !!}
                                    </div>
                               </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <div class="row">
                               <label class="col-form-label col-lg-3">Start KM: <span class="text-danger">*</span></label>
                               <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                    {!! Form::text('start_km', null, ['id'=>'start_km_id','placeholder'=>'Enter Start KM','class'=>'startKm form-control numeric', 'required']) !!}
                                    </div>
                               </div>
                            </div>
                        </div>
                
                        <div class="col-lg-6">
                            <div class="row">
                               <label class="col-form-label col-lg-3">End KM: <span class="text-danger">*</span></label>
                               <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                    {!! Form::text('end_km', null, ['id'=>'end_km_id','placeholder'=>'Enter End KM','class'=>'endKm form-control numeric', 'required']) !!}
                                    </div>
                               </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <div class="row">
                               <label class="col-form-label col-lg-3">Km Travelled: <span class="text-danger">*</span></label>
                               <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                    {!! Form::text('km_travelled', null, ['id'=>'km_travelled_id','placeholder'=>'Enter  KM','class'=>'kmTravelled form-control', 'readonly', 'required']) !!}
                                    </div>
                               </div>
                            </div>
                        </div>
                
                        <div class="col-lg-6">
                            <div class="row">
                               <label class="col-form-label col-lg-3">Vehicle No: <span class="text-danger">*</span></label>
                               <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                    {!! Form::text('vehicle_no', null, ['id'=>'vehicle_no_id','placeholder'=>'Enter Vehicle No','class'=>'vehicleNo form-control', 'required']) !!}
                                    </div>
                               </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <div class="row">
                               <label class="col-form-label col-lg-3">Purpose: <span class="text-danger">*</span></label>
                               <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                    {!! Form::text('purpose', null, ['id'=>'purpose_id','placeholder'=>'Enter Purpose','class'=>'purpose form-control', 'required']) !!}
                                    </div>
                               </div>
                            </div>
                        </div>
                
                        <div class="col-lg-6">
                            <div class="row">
                               <label class="col-form-label col-lg-3">Parking Cost: <span class="text-danger">*</span></label>
                               <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                    {!! Form::text('parking_cost', null, ['id'=>'parking_cost_id','placeholder'=>'Enter Parking Cost','class'=>'parkingCost form-control numeric', 'required']) !!}
                                    </div>
                               </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                
            </div>
        </div>
    </div>
</div>


<div class="text-right">
     <button type="submit" class="ml-2 btn bg-pink-600 btn-labeled btn-labeled-left"><b><i class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <script src="{{ asset('admin/validation/leave.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="{{ asset('admin/validation/fuelConsumption.js')}}"></script>
@endsection

