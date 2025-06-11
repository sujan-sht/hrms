
<script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
               <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Room Name:<span class="text-danger">*</span></label>
                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                                </span>
    
                                {!! Form::text('room_name', $value = null, [
                                    'id' => 'room_name',
                                    'placeholder' => 'Enter room name',
                                    'class' => 'form-control',
                                ]) !!}
    
                            </div>
                            @if ($errors->has('room_name'))
                                <span class="text-danger">{{ $errors->first('room_name') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Room Code :<span class="text-danger">*</span></label>
    
                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                                </span>
    
                                {!! Form::text('room_code', null, [
                                    'id' => 'room_code',
                                    'placeholder' => 'Enter room code',
                                    'class' => 'form-control',
                                ]) !!}
    
                            </div>
                            @if ($errors->has('room_code'))
                                <span class="text-danger">{{ $errors->first('room_code') }}</span>
                            @endif
                        </div>
                    </div>
    
                </div>
               </div>
               <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Floor:</label>
                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                                </span>
    
                                {!! Form::text('floor', $value = null, [
                                    'id' => 'floor',
                                    'placeholder' => 'Enter floor',
                                    'class' => 'form-control',
                                ]) !!}
    
                            </div>
                            @if ($errors->has('floor'))
                                <span class="text-danger">{{ $errors->first('floor') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Wifi Password :</label>
    
                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                                </span>
    
                                {!! Form::text('wifi_password', null, [
                                    'id' => 'wifi_password',
                                    'placeholder' => 'Enter wifi password',
                                    'class' => 'form-control',
                                ]) !!}
    
                            </div>
                        </div>
                    </div>
    
                </div>
               </div>
                
            </div>
        </div>
    </div>
</div>

<div class="text-right">
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

